<?php

namespace App\Http\Controllers\Juez;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    // Mostrar lista de eventos asignados al juez
    public function index()
    {
        $user = Auth::user();

        // Obtener eventos asignados al juez
        $eventosAsignados = $user->eventosAsignados()
            ->withCount(['equiposAprobados'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view('juez.eventos.index', compact('eventosAsignados'));
    }

    // Mostrar detalles de un evento y sus equipos/proyectos
    public function show(Evento $evento)
    {
        $user = Auth::user();

        // Verificar que el juez esté asignado a este evento
        if (! $user->eventosAsignados()->where('evento_id', $evento->id)->exists()) {
            return redirect()->route('juez.eventos.index')
                ->with('error', 'No tienes acceso a este evento');
        }

        // Obtener equipos inscritos en el evento con sus proyectos
        $equipos = $evento->equiposAprobados()
            ->with(['miembros'])
            ->withPivot([
                'estado',
                'proyecto_titulo',
                'proyecto_descripcion',
                'avances',
                'proyecto_final_url',
                'fecha_entrega_final',
                'notas_equipo',
            ])
            ->get()
            ->map(function ($equipo) {
                $tieneProyecto = ! empty($equipo->pivot->proyecto_titulo) && ! empty($equipo->pivot->proyecto_descripcion);

                // Decodificar avances
                $avances = [];
                if ($equipo->pivot->avances) {
                    $avances = json_decode($equipo->pivot->avances, true) ?? [];
                }

                return [
                    'equipo' => $equipo,
                    'tieneProyecto' => $tieneProyecto,
                    'proyecto' => [
                        'titulo' => $equipo->pivot->proyecto_titulo,
                        'descripcion' => $equipo->pivot->proyecto_descripcion,
                        'avances' => $avances,
                        'proyecto_final_url' => $equipo->pivot->proyecto_final_url,
                        'fecha_entrega_final' => $equipo->pivot->fecha_entrega_final,
                        'notas_equipo' => $equipo->pivot->notas_equipo,
                    ],
                    'estado' => $equipo->pivot->estado,
                ];
            });

        // Separar equipos con y sin proyecto
        $equiposConProyecto = $equipos->filter(fn ($e) => $e['tieneProyecto']);
        $equiposSinProyecto = $equipos->filter(fn ($e) => ! $e['tieneProyecto']);

        return view('juez.eventos.show', compact('evento', 'equiposConProyecto', 'equiposSinProyecto'));
    }

    // Ver proyecto específico de un equipo en un evento
    public function verProyecto(Evento $evento, Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar que el juez esté asignado a este evento
        if (! $user->eventosAsignados()->where('evento_id', $evento->id)->exists()) {
            return redirect()->route('juez.eventos.index')
                ->with('error', 'No tienes acceso a este evento');
        }

        // Verificar que el equipo esté inscrito en el evento
        $inscripcion = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->withPivot([
                'estado',
                'proyecto_titulo',
                'proyecto_descripcion',
                'avances',
                'proyecto_final_url',
                'fecha_entrega_final',
                'notas_equipo',
            ])
            ->first();

        if (! $inscripcion) {
            return redirect()->route('juez.eventos.show', $evento)
                ->with('error', 'Este equipo no está inscrito en el evento');
        }

        // Decodificar avances
        $avances = [];
        if ($inscripcion->pivot->avances) {
            $avances = json_decode($inscripcion->pivot->avances, true) ?? [];
        }

        // Cargar miembros del equipo
        $equipo->load('miembros');

        return view('juez.eventos.proyecto', compact('evento', 'equipo', 'inscripcion', 'avances'));
    }
}
