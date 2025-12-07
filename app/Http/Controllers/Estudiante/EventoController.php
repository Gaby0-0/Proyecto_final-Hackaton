<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    // Mostrar lista de eventos disponibles
    public function index()
    {
        $user = Auth::user();

        // Obtener todos los eventos activos que no hayan alcanzado el límite
        $eventosDisponibles = Evento::where('eventos.estado', 'activo')
            ->withCount('equiposAprobados')
            ->get()
            ->filter(function($evento) {
                // Filtrar solo los que tienen cupo disponible
                $equiposInscritos = $evento->equipos()
                    ->whereIn('equipo_evento.estado', ['inscrito', 'participando'])
                    ->count();
                return $equiposInscritos < $evento->max_equipos;
            })
            ->sortBy('fecha_inicio')
            ->values();

        // Obtener eventos en los que el usuario está inscrito (con sus equipos)
        $misEquipos = $user->equipos()->get();

        $eventosInscritos = collect();
        foreach ($misEquipos as $equipo) {
            $eventos = $equipo->eventos()
                ->whereIn('equipo_evento.estado', ['pendiente', 'inscrito', 'participando'])
                ->where('eventos.estado', 'activo')
                ->with('equipos')
                ->get();

            foreach ($eventos as $evento) {
                $evento->equipo_inscrito = $equipo;
                $evento->estado_inscripcion = $evento->pivot->estado;
                $eventosInscritos->push($evento);
            }
        }

        $eventosInscritos = $eventosInscritos->unique('id');

        return view('estudiante.eventos.index', compact('eventosDisponibles', 'eventosInscritos', 'misEquipos'));
    }

    // Mostrar detalles de un evento
    public function show(Evento $evento)
    {
        $user = Auth::user();

        // Cargar relaciones del evento
        $evento->load(['equiposAprobados', 'equiposPendientes']);

        // Obtener equipos del usuario
        $misEquipos = $user->equipos()->get();

        // Verificar para cada equipo si puede inscribirse
        $equiposConEstado = $misEquipos->map(function ($equipo) use ($evento) {
            $yaInscrito = $equipo->estaInscritoEnEvento($evento->id);
            $puedeInscribirse = !$yaInscrito && $equipo->puedeInscribirseAEvento($evento);
            $esLider = $equipo->usuarioEsLider(Auth::id());

            // Obtener estado de inscripción si existe
            $estadoInscripcion = null;
            if ($yaInscrito) {
                $estadoInscripcion = $equipo->eventos()
                    ->where('evento_id', $evento->id)
                    ->first()
                    ->pivot
                    ->estado;
            }

            return [
                'equipo' => $equipo,
                'yaInscrito' => $yaInscrito,
                'puedeInscribirse' => $puedeInscribirse,
                'esLider' => $esLider,
                'estadoInscripcion' => $estadoInscripcion
            ];
        });

        return view('estudiante.eventos.show', compact('evento', 'equiposConEstado'));
    }

    // Inscribir equipo a un evento
    public function inscribir(Request $request, Evento $evento)
    {
        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
        ]);

        $equipo = Equipo::findOrFail($request->equipo_id);
        $user = Auth::user();

        // Verificar que el usuario sea líder del equipo
        if (!$equipo->usuarioEsLider($user->id)) {
            return redirect()->back()
                ->with('error', 'Solo el líder del equipo puede inscribirlo a eventos.');
        }

        // Verificar que el usuario sea miembro del equipo
        if (!$equipo->miembros()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                ->with('error', 'No eres miembro de este equipo.');
        }

        // Verificar si el equipo ya está inscrito
        if ($equipo->estaInscritoEnEvento($evento->id)) {
            return redirect()->back()
                ->with('error', 'Tu equipo ya está inscrito o tiene una solicitud pendiente en este evento.');
        }

        // Verificar que el evento esté disponible
        if (!$evento->estaDisponibleParaInscripcion()) {
            return redirect()->back()
                ->with('error', 'Este evento no está disponible para inscripción en este momento.');
        }

        // Verificar restricción de categoría
        if (!$equipo->puedeInscribirseAEvento($evento)) {
            return redirect()->back()
                ->with('error', 'Tu equipo ya está inscrito en un evento de la categoría "' . $evento->categoria . '". Solo puedes inscribirte a un evento por categoría.');
        }

        // Inscribir equipo (estado pendiente por defecto)
        $equipo->eventos()->attach($evento->id, [
            'estado' => 'pendiente',
            'fecha_inscripcion' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Solicitud de inscripción enviada exitosamente. El administrador revisará tu solicitud.');
    }

    // Cancelar inscripción de un equipo
    public function cancelarInscripcion(Request $request, Evento $evento)
    {
        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
        ]);

        $equipo = Equipo::findOrFail($request->equipo_id);
        $user = Auth::user();

        // Verificar que el usuario sea líder del equipo
        if (!$equipo->usuarioEsLider($user->id)) {
            return redirect()->back()
                ->with('error', 'Solo el líder del equipo puede cancelar la inscripción.');
        }

        // Verificar que el equipo esté inscrito
        if (!$equipo->estaInscritoEnEvento($evento->id)) {
            return redirect()->back()
                ->with('error', 'Tu equipo no está inscrito en este evento.');
        }

        // Obtener el estado actual
        $estadoActual = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->first()
            ->pivot
            ->estado;

        // Solo permitir cancelar si está pendiente
        if ($estadoActual !== 'pendiente') {
            return redirect()->back()
                ->with('error', 'No puedes cancelar una inscripción ya aprobada. Contacta al administrador.');
        }

        // Cancelar inscripción
        $equipo->eventos()->detach($evento->id);

        return redirect()->back()
            ->with('success', 'Inscripción cancelada exitosamente.');
    }
}
