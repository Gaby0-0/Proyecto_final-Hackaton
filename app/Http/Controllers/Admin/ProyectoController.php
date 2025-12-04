<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index(Request $request)
    {
        $query = Proyecto::withCount('equipos');

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
        }

        $proyectos = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        return view('admin.proyectos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tecnologias' => 'nullable|string',
            'requisitos' => 'nullable|string'
        ]);

        Proyecto::create($validated);

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto creado exitosamente');
    }

    public function show(Proyecto $proyecto)
    {
        $proyecto->load(['equipos.miembros']);
        return view('admin.proyectos.show', compact('proyecto'));
    }

    public function edit(Proyecto $proyecto)
    {
        return view('admin.proyectos.edit', compact('proyecto'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tecnologias' => 'nullable|string',
            'requisitos' => 'nullable|string'
        ]);

        $proyecto->update($validated);

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto actualizado exitosamente');
    }

    public function destroy(Proyecto $proyecto)
    {
        // Verificar si tiene equipos asociados
        if ($proyecto->equipos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un proyecto con equipos asociados');
        }

        $proyecto->delete();

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto eliminado exitosamente');
    }
}
