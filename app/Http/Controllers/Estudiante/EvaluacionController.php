<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Evaluacion;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    /**
     * Mostrar todas las evaluaciones de los equipos del estudiante
     */
    public function index()
    {
        $user = Auth::user();

        // Obtener todos los equipos del estudiante
        $equipos = $user->equipos()->with(['eventos'])->get();

        // Obtener evaluaciones agrupadas por equipo y evento
        $evaluacionesPorEquipo = [];

        foreach ($equipos as $equipo) {
            foreach ($equipo->eventos as $evento) {
                // Obtener todas las evaluaciones del equipo en este evento
                $evaluaciones = Evaluacion::where('equipo_id', $equipo->id)
                    ->where('evento_id', $evento->id)
                    ->with(['evaluador'])
                    ->get();

                if ($evaluaciones->count() > 0) {
                    // Calcular promedio
                    $promedio = $evaluaciones->avg('puntuacion');

                    $evaluacionesPorEquipo[] = [
                        'equipo' => $equipo,
                        'evento' => $evento,
                        'evaluaciones' => $evaluaciones,
                        'promedio' => round($promedio, 2),
                        'total_evaluaciones' => $evaluaciones->count(),
                    ];
                }
            }
        }

        return view('estudiante.evaluaciones.index', compact('evaluacionesPorEquipo'));
    }

    /**
     * Ver detalle de evaluaciones de un equipo en un evento específico
     */
    public function ver(Equipo $equipo, $eventoId)
    {
        $user = Auth::user();

        // Verificar que el usuario pertenece al equipo
        if (! $equipo->miembros->contains($user->id)) {
            return redirect()->route('estudiante.evaluaciones.index')
                ->with('error', 'No tienes acceso a estas evaluaciones');
        }

        // Obtener el evento
        $evento = $equipo->eventos()->findOrFail($eventoId);

        // Obtener todas las evaluaciones del equipo en este evento
        $evaluaciones = Evaluacion::where('equipo_id', $equipo->id)
            ->where('evento_id', $eventoId)
            ->with(['evaluador'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($evaluaciones->count() === 0) {
            return redirect()->route('estudiante.evaluaciones.index')
                ->with('error', 'Este equipo no tiene evaluaciones en este evento');
        }

        // Calcular estadísticas
        $estadisticas = [
            'promedio_general' => round($evaluaciones->avg('puntuacion'), 2),
            'puntuacion_maxima' => round($evaluaciones->max('puntuacion'), 2),
            'puntuacion_minima' => round($evaluaciones->min('puntuacion'), 2),
            'total_evaluaciones' => $evaluaciones->count(),
            'promedio_innovacion' => round($evaluaciones->avg('criterio_innovacion'), 2),
            'promedio_funcionalidad' => round($evaluaciones->avg('criterio_funcionalidad'), 2),
            'promedio_presentacion' => round($evaluaciones->avg('criterio_presentacion'), 2),
            'promedio_impacto' => round($evaluaciones->avg('criterio_impacto'), 2),
            'promedio_tecnico' => round($evaluaciones->avg('criterio_tecnico'), 2),
        ];

        // Obtener información del proyecto
        $proyecto = $equipo->eventos()
            ->where('evento_id', $eventoId)
            ->withPivot([
                'proyecto_titulo',
                'proyecto_descripcion',
                'proyecto_final_url',
            ])
            ->first();

        return view('estudiante.evaluaciones.ver', compact('equipo', 'evento', 'evaluaciones', 'estadisticas', 'proyecto'));
    }
}
