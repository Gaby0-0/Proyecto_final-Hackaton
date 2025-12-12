<?php

namespace App\Http\Controllers\Juez;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Evaluacion;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Total de equipos con proyectos asignados
        $totalEquipos = Equipo::whereNotNull('proyecto_id')->count();

        // Evaluaciones realizadas por este juez
        $misEvaluaciones = Evaluacion::where('evaluador_id', Auth::id())->count();

        // Equipos pendientes de evaluar (equipos con proyecto que el juez no ha evaluado)
        $equiposEvaluados = Evaluacion::where('evaluador_id', Auth::id())
            ->pluck('equipo_id')
            ->toArray();

        $pendientes = Equipo::whereNotNull('proyecto_id')
            ->whereNotIn('id', $equiposEvaluados)
            ->count();

        return view('juez.dashboard.index', compact(
            'user',
            'totalEquipos',
            'misEvaluaciones',
            'pendientes'
        ));
    }
}
