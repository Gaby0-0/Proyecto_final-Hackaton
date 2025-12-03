<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipo::with(['proyecto', 'miembros']);

        // Filtros
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('proyecto')) {
            $query->where('proyecto_id', $request->proyecto);
        }

        $equipos = $query->latest()->paginate(10);
        $proyectos = Proyecto::all();

        return view('admin.equipos.index', compact('equipos', 'proyectos'));
    }

    public function create()
    {
        $proyectos = Proyecto::all();
        $usuarios = User::where('role', 'estudiante')->get();

        return view('admin.equipos.create', compact('proyectos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'descripcion' => 'nullable|string',
            'max_integrantes' => 'required|integer|min:2|max:20',
            'lider_id' => 'nullable|exists:users,id',
            'miembros' => 'nullable|array',
            'miembros.*' => 'exists:users,id',
        ]);

        $equipo = Equipo::create([
            'nombre' => $validated['nombre'],
            'proyecto_id' => $validated['proyecto_id'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'max_integrantes' => $validated['max_integrantes'],
        ]);

        // Asignar líder si se especificó
        if (!empty($validated['lider_id'])) {
            $equipo->miembros()->attach($validated['lider_id'], ['rol_equipo' => 'lider']);
        }

        // Asignar miembros
        if (!empty($validated['miembros'])) {
            foreach ($validated['miembros'] as $miembroId) {
                // No duplicar si ya es líder
                if ($miembroId != $validated['lider_id']) {
                    $equipo->miembros()->attach($miembroId, ['rol_equipo' => 'miembro']);
                }
            }
        }

        return redirect()->route('admin.equipos.index')
            ->with('success', 'Equipo creado exitosamente.');
    }

    public function show(Equipo $equipo)
    {
        $equipo->load(['proyecto', 'miembros', 'eventos', 'evaluaciones']);

        return view('admin.equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo)
    {
        $proyectos = Proyecto::all();
        $usuarios = User::where('role', 'estudiante')->get();
        $equipo->load('miembros');

        return view('admin.equipos.edit', compact('equipo', 'proyectos', 'usuarios'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'proyecto_id' => 'nullable|exists:proyectos,id',
            'descripcion' => 'nullable|string',
            'max_integrantes' => 'required|integer|min:2|max:20',
            'lider_id' => 'nullable|exists:users,id',
            'miembros' => 'nullable|array',
            'miembros.*' => 'exists:users,id',
        ]);

        $equipo->update([
            'nombre' => $validated['nombre'],
            'proyecto_id' => $validated['proyecto_id'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'max_integrantes' => $validated['max_integrantes'],
        ]);

        // Actualizar miembros
        $equipo->miembros()->detach(); // Remover todos

        // Asignar líder
        if (!empty($validated['lider_id'])) {
            $equipo->miembros()->attach($validated['lider_id'], ['rol_equipo' => 'lider']);
        }

        // Asignar miembros
        if (!empty($validated['miembros'])) {
            foreach ($validated['miembros'] as $miembroId) {
                if ($miembroId != $validated['lider_id']) {
                    $equipo->miembros()->attach($miembroId, ['rol_equipo' => 'miembro']);
                }
            }
        }

        return redirect()->route('admin.equipos.index')
            ->with('success', 'Equipo actualizado exitosamente.');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();

        return redirect()->route('admin.equipos.index')
            ->with('success', 'Equipo eliminado exitosamente.');
    }
}
