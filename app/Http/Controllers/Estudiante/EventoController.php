<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    // Mostrar lista de eventos disponibles
    public function index()
    {
        $user = Auth::user();

        Evento::all()->each(function ($evento) {
            $evento->actualizarEstadoSegunFecha();
        });

        $eventosDisponibles = Evento::where('eventos.estado', 'programado')
            ->withCount('equiposAprobados')
            ->get()
            ->filter(function ($evento) {
                return $evento->tieneCupoDisponible();
            })
            ->sortBy('fecha_inicio')
            ->values();

        $misEquipos = $user->equipos()->get();

        $eventosInscritos = collect();
        foreach ($misEquipos as $equipo) {
            $eventos = $equipo->eventos()
                ->whereIn('equipo_evento.estado', ['pendiente', 'inscrito', 'participando'])
                ->whereIn('eventos.estado', ['programado', 'activo'])
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
        // Actualizar estado del evento según fecha actual
        $evento->actualizarEstadoSegunFecha();

        $user = Auth::user();

        // Cargar relaciones del evento
        $evento->load(['equiposAprobados', 'equiposPendientes']);

        // Obtener equipos del usuario
        $misEquipos = $user->equipos()->get();

        // Verificar para cada equipo si puede inscribirse
        $equiposConEstado = $misEquipos->map(function ($equipo) use ($evento) {
            $yaInscrito = $equipo->estaInscritoEnEvento($evento->id);
            $puedeInscribirse = ! $yaInscrito && $equipo->puedeInscribirseAEvento($evento);
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
                'estadoInscripcion' => $estadoInscripcion,
            ];
        });

        return view('estudiante.eventos.show', compact('evento', 'equiposConEstado'));
    }

    // Inscribir equipo a un evento
    public function inscribir(Request $request, Evento $evento)
    {
        // Actualizar estado del evento según fecha actual
        $evento->actualizarEstadoSegunFecha();

        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
        ]);

        $equipo = Equipo::findOrFail($request->equipo_id);
        $user = Auth::user();

        // Verificar que el usuario sea líder del equipo
        if (! $equipo->usuarioEsLider($user->id)) {
            return redirect()->back()
                ->with('error', 'Solo el líder del equipo puede inscribirlo a eventos.');
        }

        // Verificar que el usuario sea miembro del equipo
        if (! $equipo->miembros()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                ->with('error', 'No eres miembro de este equipo.');
        }

        // Verificar si el equipo ya está inscrito
        if ($equipo->estaInscritoEnEvento($evento->id)) {
            return redirect()->back()
                ->with('error', 'Tu equipo ya está inscrito o tiene una solicitud pendiente en este evento.');
        }

        // Verificar que el evento esté programado (no activo ni finalizado)
        if ($evento->estado !== 'programado') {
            return redirect()->back()
                ->with('error', 'Solo puedes inscribirte a eventos programados. Este evento ya está en curso o ha finalizado.');
        }

        // Verificar que haya cupo disponible
        if (! $evento->tieneCupoDisponible()) {
            return redirect()->back()
                ->with('error', 'Este evento ha alcanzado el límite de equipos.');
        }

        // Verificar restricción de categoría
        if (! $equipo->puedeInscribirseAEvento($evento)) {
            return redirect()->back()
                ->with('error', 'Tu equipo ya está inscrito en un evento de la categoría "'.$evento->categoria.'". Solo puedes inscribirte a un evento por categoría.');
        }

        // Inscribir equipo (estado pendiente por defecto)
        $equipo->eventos()->attach($evento->id, [
            'estado' => 'pendiente',
            'fecha_inscripcion' => now(),
        ]);

        // Crear notificación para el administrador
        Notificacion::crearSolicitudEquipo($equipo, $evento);

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
        if (! $equipo->usuarioEsLider($user->id)) {
            return redirect()->back()
                ->with('error', 'Solo el líder del equipo puede cancelar la inscripción.');
        }

        // Verificar que el equipo esté inscrito
        if (! $equipo->estaInscritoEnEvento($evento->id)) {
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
