<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use App\Models\Evento;
use App\Models\Equipo;
use Illuminate\Http\Request;

class EvaluacionController extends Controller
{
    // Listar todas las evaluaciones
    public function index(Request $request)
    {
        $query = Evaluacion::with(['evento', 'equipo', 'evaluador']);

        // Filtros
        if ($request->filled('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }

        if ($request->filled('equipo_id')) {
            $query->where('equipo_id', $request->equipo_id);
        }

        if ($request->filled('evaluador_id')) {
            $query->where('evaluador_id', $request->evaluador_id);
        }

        $evaluaciones = $query->orderBy('created_at', 'desc')->paginate(20);

        // Datos para filtros
        $eventos = Evento::orderBy('fecha_inicio', 'desc')->get();
        $equipos = Equipo::orderBy('nombre')->get();

        // Estadísticas
        $totalEvaluaciones = Evaluacion::count();
        $promedioGeneral = Evaluacion::avg('puntuacion');

        return view('admin.evaluaciones.index', compact(
            'evaluaciones',
            'eventos',
            'equipos',
            'totalEvaluaciones',
            'promedioGeneral'
        ));
    }

    // Ver detalles de una evaluación
    public function show(Evaluacion $evaluacion)
    {
        $evaluacion->load(['evento', 'equipo.miembros', 'evaluador']);

        return view('admin.evaluaciones.show', compact('evaluacion'));
    }

    // Evaluaciones por evento
    public function porEvento(Evento $evento)
    {
        $evaluaciones = $evento->evaluaciones()
            ->with(['equipo', 'evaluador'])
            ->orderBy('puntuacion', 'desc')
            ->get();

        // Calcular estadísticas
        $totalEvaluaciones = $evaluaciones->count();
        $promedioEvento = $evaluaciones->avg('puntuacion');
        $evaluacionMasAlta = $evaluaciones->max('puntuacion');
        $evaluacionMasBaja = $evaluaciones->min('puntuacion');

        // Agrupar por equipo
        $evaluacionesPorEquipo = $evaluaciones->groupBy('equipo_id')->map(function($evals) {
            return [
                'equipo' => $evals->first()->equipo,
                'evaluaciones' => $evals,
                'promedio' => $evals->avg('puntuacion'),
                'total' => $evals->count(),
            ];
        })->sortByDesc('promedio')->values();

        return view('admin.evaluaciones.evento', compact(
            'evento',
            'evaluaciones',
            'totalEvaluaciones',
            'promedioEvento',
            'evaluacionMasAlta',
            'evaluacionMasBaja',
            'evaluacionesPorEquipo'
        ));
    }

    // Evaluaciones por equipo
    public function porEquipo(Equipo $equipo)
    {
        $evaluaciones = $equipo->evaluaciones()
            ->with(['evento', 'evaluador'])
            ->orderBy('created_at', 'desc')
            ->get();

        $promedioEquipo = $evaluaciones->avg('puntuacion');

        return view('admin.evaluaciones.equipo', compact('equipo', 'evaluaciones', 'promedioEquipo'));
    }

    // Eliminar evaluación
    public function destroy(Evaluacion $evaluacion)
    {
        $evaluacion->delete();

        return back()->with('success', 'Evaluación eliminada exitosamente');
    }
}
