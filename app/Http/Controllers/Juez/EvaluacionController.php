<?php

namespace App\Http\Controllers\Juez;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Evaluacion;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    // Lista de equipos disponibles para evaluar
    public function index(Request $request)
    {
        $query = Equipo::with(['proyecto', 'miembros', 'evaluaciones']);

        // Filtrar solo equipos con proyecto asignado
        $query->whereNotNull('proyecto_id');

        // Filtros
        if ($request->filled('proyecto')) {
            $query->where('proyecto_id', $request->proyecto);
        }

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Obtener equipos
        $equipos = $query->latest()->paginate(12);

        // Obtener proyectos para el filtro
        $proyectos = Proyecto::all();

        return view('juez.evaluaciones.index', compact('equipos', 'proyectos'));
    }

    // Ver detalles del equipo y proyecto para evaluar
    public function show(Equipo $equipo)
    {
        $equipo->load(['proyecto', 'miembros', 'evaluaciones.evaluador']);

        // Verificar si el juez ya evaluó este equipo
        $miEvaluacion = $equipo->evaluaciones()
            ->where('evaluador_id', Auth::id())
            ->first();

        return view('juez.evaluaciones.show', compact('equipo', 'miEvaluacion'));
    }

    // Mostrar formulario de evaluación
    public function crear(Equipo $equipo)
    {
        $equipo->load('proyecto', 'miembros');

        // Verificar si ya evaluó este equipo
        $evaluacionExistente = Evaluacion::where('equipo_id', $equipo->id)
            ->where('evaluador_id', Auth::id())
            ->first();

        if ($evaluacionExistente) {
            return redirect()->route('juez.evaluaciones.show', $equipo)
                ->with('error', 'Ya has evaluado este equipo. Puedes editar tu evaluación.');
        }

        return view('juez.evaluaciones.crear', compact('equipo'));
    }

    // Guardar evaluación
    public function store(Request $request, Equipo $equipo)
    {
        // Verificar si ya evaluó este equipo
        $evaluacionExistente = Evaluacion::where('equipo_id', $equipo->id)
            ->where('evaluador_id', Auth::id())
            ->first();

        if ($evaluacionExistente) {
            return redirect()->route('juez.evaluaciones.show', $equipo)
                ->with('error', 'Ya has evaluado este equipo.');
        }

        $validated = $request->validate([
            'puntuacion' => 'required|numeric|min:0|max:100',
            'comentarios' => 'nullable|string',
            // Criterios específicos
            'criterio_innovacion' => 'nullable|numeric|min:0|max:20',
            'criterio_funcionalidad' => 'nullable|numeric|min:0|max:20',
            'criterio_presentacion' => 'nullable|numeric|min:0|max:20',
            'criterio_impacto' => 'nullable|numeric|min:0|max:20',
            'criterio_tecnico' => 'nullable|numeric|min:0|max:20',
        ]);

        Evaluacion::create([
            'equipo_id' => $equipo->id,
            'evaluador_id' => Auth::id(),
            'puntuacion' => $validated['puntuacion'],
            'comentarios' => $validated['comentarios'],
            'criterio_innovacion' => $validated['criterio_innovacion'] ?? null,
            'criterio_funcionalidad' => $validated['criterio_funcionalidad'] ?? null,
            'criterio_presentacion' => $validated['criterio_presentacion'] ?? null,
            'criterio_impacto' => $validated['criterio_impacto'] ?? null,
            'criterio_tecnico' => $validated['criterio_tecnico'] ?? null,
        ]);

        return redirect()->route('juez.evaluaciones.index')
            ->with('success', 'Evaluación guardada exitosamente para el equipo ' . $equipo->nombre);
    }

    // Editar evaluación existente
    public function editar(Equipo $equipo)
    {
        $equipo->load('proyecto', 'miembros');

        $evaluacion = Evaluacion::where('equipo_id', $equipo->id)
            ->where('evaluador_id', Auth::id())
            ->firstOrFail();

        return view('juez.evaluaciones.editar', compact('equipo', 'evaluacion'));
    }

    // Actualizar evaluación
    public function update(Request $request, Equipo $equipo)
    {
        $evaluacion = Evaluacion::where('equipo_id', $equipo->id)
            ->where('evaluador_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'puntuacion' => 'required|numeric|min:0|max:100',
            'comentarios' => 'nullable|string',
            // Criterios específicos
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

        return redirect()->route('juez.evaluaciones.show', $equipo)
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
