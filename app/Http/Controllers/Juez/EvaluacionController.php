<?php

namespace App\Http\Controllers\Juez;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Evaluacion;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    // Lista de eventos asignados con equipos para evaluar
    public function index(Request $request)
    {
        $user = Auth::user();

        // Obtener eventos asignados al juez con equipos que tienen proyectos
        $eventosAsignados = $user->eventosAsignados()
            ->with(['equiposAprobados' => function ($query) {
                $query->withPivot([
                    'proyecto_titulo',
                    'proyecto_descripcion',
                    'proyecto_final_url',
                ]);
            }])
            ->orderBy('fecha_inicio', 'desc')
            ->get()
            ->map(function ($evento) use ($user) {
                // Filtrar solo equipos con proyecto
                $equiposConProyecto = $evento->equiposAprobados->filter(function ($equipo) {
                    return ! empty($equipo->pivot->proyecto_titulo);
                });

                // Contar cuántos ha evaluado
                $evaluados = Evaluacion::where('evaluador_id', $user->id)
                    ->where('evento_id', $evento->id)
                    ->count();

                $evento->equipos_con_proyecto = $equiposConProyecto->count();
                $evento->equipos_evaluados = $evaluados;

                return $evento;
            });

        return view('juez.evaluaciones.index', compact('eventosAsignados'));
    }

    // Ver equipos de un evento para evaluar
    public function verEvento(Evento $evento)
    {
        $user = Auth::user();

        // Verificar que el juez esté asignado a este evento
        if (! $user->eventosAsignados()->where('evento_id', $evento->id)->exists()) {
            return redirect()->route('juez.evaluaciones.index')
                ->with('error', 'No tienes acceso a este evento');
        }

        // Obtener equipos con proyecto del evento
        $equipos = $evento->equiposAprobados()
            ->with(['miembros'])
            ->withPivot([
                'proyecto_titulo',
                'proyecto_descripcion',
                'avances',
                'proyecto_final_url',
                'fecha_entrega_final',
            ])
            ->get()
            ->filter(function ($equipo) {
                return ! empty($equipo->pivot->proyecto_titulo);
            })
            ->map(function ($equipo) use ($evento, $user) {
                // Verificar si ya evaluó este equipo en este evento
                $evaluacion = Evaluacion::where('evento_id', $evento->id)
                    ->where('equipo_id', $equipo->id)
                    ->where('evaluador_id', $user->id)
                    ->first();

                $equipo->evaluacion_existente = $evaluacion;

                return $equipo;
            });

        return view('juez.evaluaciones.evento', compact('evento', 'equipos'));
    }

    // Mostrar formulario de evaluación
    public function crear(Evento $evento, Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar que el juez esté asignado al evento
        if (! $user->eventosAsignados()->where('evento_id', $evento->id)->exists()) {
            return redirect()->route('juez.evaluaciones.index')
                ->with('error', 'No tienes acceso a este evento');
        }

        // Verificar si ya evaluó este equipo en este evento
        $evaluacionExistente = Evaluacion::where('evento_id', $evento->id)
            ->where('equipo_id', $equipo->id)
            ->where('evaluador_id', $user->id)
            ->first();

        if ($evaluacionExistente) {
            return redirect()->route('juez.evaluaciones.evento', $evento)
                ->with('error', 'Ya has evaluado este equipo. Puedes editar tu evaluación.');
        }

        // Obtener el proyecto del equipo
        $inscripcion = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->withPivot([
                'proyecto_titulo',
                'proyecto_descripcion',
                'avances',
                'proyecto_final_url',
                'fecha_entrega_final',
            ])
            ->first();

        if (! $inscripcion || empty($inscripcion->pivot->proyecto_titulo)) {
            return redirect()->route('juez.evaluaciones.evento', $evento)
                ->with('error', 'Este equipo no tiene proyecto para evaluar');
        }

        // VALIDACIÓN CRÍTICA: Verificar que el equipo tenga su proyecto final subido
        if (empty($inscripcion->pivot->proyecto_final_url)) {
            return redirect()->route('juez.evaluaciones.evento', $evento)
                ->with('error', 'Este equipo no ha subido su proyecto final. Solo se pueden evaluar proyectos con entrega final completada.');
        }

        $equipo->load('miembros');

        // Decodificar avances
        $avances = [];
        if ($inscripcion->pivot->avances) {
            $avances = json_decode($inscripcion->pivot->avances, true) ?? [];
        }

        return view('juez.evaluaciones.crear', compact('evento', 'equipo', 'inscripcion', 'avances'));
    }

    // Guardar evaluación
    public function store(Request $request, Evento $evento, Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar que el juez esté asignado al evento
        if (! $user->eventosAsignados()->where('evento_id', $evento->id)->exists()) {
            return redirect()->route('juez.evaluaciones.index')
                ->with('error', 'No tienes acceso a este evento');
        }

        // Verificar si ya evaluó este equipo en este evento
        $evaluacionExistente = Evaluacion::where('evento_id', $evento->id)
            ->where('equipo_id', $equipo->id)
            ->where('evaluador_id', $user->id)
            ->first();

        if ($evaluacionExistente) {
            return redirect()->route('juez.evaluaciones.evento', $evento)
                ->with('error', 'Ya has evaluado este equipo.');
        }

        // VALIDACIÓN CRÍTICA: Verificar que el equipo tenga su proyecto final subido
        $inscripcion = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->withPivot('proyecto_final_url')
            ->first();

        if (! $inscripcion || empty($inscripcion->pivot->proyecto_final_url)) {
            return redirect()->route('juez.evaluaciones.evento', $evento)
                ->with('error', 'Este equipo no ha subido su proyecto final. Solo se pueden evaluar proyectos con entrega final completada.');
        }

        $validated = $request->validate([
            'puntuacion' => 'required|numeric|min:0|max:100',
            'comentarios' => 'nullable|string',
            'criterio_innovacion' => 'nullable|numeric|min:0|max:20',
            'criterio_funcionalidad' => 'nullable|numeric|min:0|max:20',
            'criterio_presentacion' => 'nullable|numeric|min:0|max:20',
            'criterio_impacto' => 'nullable|numeric|min:0|max:20',
            'criterio_tecnico' => 'nullable|numeric|min:0|max:20',
        ]);

        Evaluacion::create([
            'evento_id' => $evento->id,
            'equipo_id' => $equipo->id,
            'evaluador_id' => $user->id,
            'puntuacion' => $validated['puntuacion'],
            'comentarios' => $validated['comentarios'],
            'criterio_innovacion' => $validated['criterio_innovacion'] ?? null,
            'criterio_funcionalidad' => $validated['criterio_funcionalidad'] ?? null,
            'criterio_presentacion' => $validated['criterio_presentacion'] ?? null,
            'criterio_impacto' => $validated['criterio_impacto'] ?? null,
            'criterio_tecnico' => $validated['criterio_tecnico'] ?? null,
        ]);

        return redirect()->route('juez.evaluaciones.evento', $evento)
            ->with('success', 'Evaluación guardada exitosamente para el equipo '.$equipo->nombre);
    }

    // Editar evaluación existente
    public function editar(Evento $evento, Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar que el juez esté asignado al evento
        if (! $user->eventosAsignados()->where('evento_id', $evento->id)->exists()) {
            return redirect()->route('juez.evaluaciones.index')
                ->with('error', 'No tienes acceso a este evento');
        }

        $evaluacion = Evaluacion::where('evento_id', $evento->id)
            ->where('equipo_id', $equipo->id)
            ->where('evaluador_id', $user->id)
            ->firstOrFail();

        // Obtener el proyecto del equipo
        $inscripcion = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->withPivot([
                'proyecto_titulo',
                'proyecto_descripcion',
                'avances',
                'proyecto_final_url',
                'fecha_entrega_final',
            ])
            ->first();

        $equipo->load('miembros');

        // Decodificar avances
        $avances = [];
        if ($inscripcion->pivot->avances) {
            $avances = json_decode($inscripcion->pivot->avances, true) ?? [];
        }

        return view('juez.evaluaciones.editar', compact('evento', 'equipo', 'evaluacion', 'inscripcion', 'avances'));
    }

    // Actualizar evaluación
    public function update(Request $request, Evento $evento, Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar que el juez esté asignado al evento
        if (! $user->eventosAsignados()->where('evento_id', $evento->id)->exists()) {
            return redirect()->route('juez.evaluaciones.index')
                ->with('error', 'No tienes acceso a este evento');
        }

        $evaluacion = Evaluacion::where('evento_id', $evento->id)
            ->where('equipo_id', $equipo->id)
            ->where('evaluador_id', $user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'puntuacion' => 'required|numeric|min:0|max:100',
            'comentarios' => 'nullable|string',
            'criterio_innovacion' => 'nullable|numeric|min:0|max:20',
            'criterio_funcionalidad' => 'nullable|numeric|min:0|max:20',
            'criterio_presentacion' => 'nullable|numeric|min:0|max:20',
            'criterio_impacto' => 'nullable|numeric|min:0|max:20',
            'criterio_tecnico' => 'nullable|numeric|min:0|max:20',
        ]);

        $evaluacion->update([
            'puntuacion' => $validated['puntuacion'],
            'comentarios' => $validated['comentarios'],
            'criterio_innovacion' => $validated['criterio_innovacion'] ?? null,
            'criterio_funcionalidad' => $validated['criterio_funcionalidad'] ?? null,
            'criterio_presentacion' => $validated['criterio_presentacion'] ?? null,
            'criterio_impacto' => $validated['criterio_impacto'] ?? null,
            'criterio_tecnico' => $validated['criterio_tecnico'] ?? null,
        ]);

        return redirect()->route('juez.evaluaciones.evento', $evento)
            ->with('success', 'Evaluación actualizada exitosamente');
    }

    // Ver mis evaluaciones
    public function misEvaluaciones()
    {
        $evaluaciones = Evaluacion::with(['equipo.proyecto', 'equipo.miembros'])
            ->where('evaluador_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('juez.evaluaciones.mis-evaluaciones', compact('evaluaciones'));
    }
}
