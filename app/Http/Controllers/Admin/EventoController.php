<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        // Datos de prueba (sin base de datos)
        $eventos = collect([
            (object)[
                'id' => 1,
                'nombre' => 'Hackatón 2025',
                'proyecto' => 'Desarrollo de aplicaciones móviles',
                'fecha_inicio' => '28/02/2025',
                'fecha_fin' => '02/03/2025',
                'estado' => 'Activo',
                'modalidad' => 'Presencial',
                'participantes_actuales' => 45,
                'participantes_max' => 100
            ],
            (object)[
                'id' => 2,
                'nombre' => 'Festival de código virtual',
                'proyecto' => 'Sistema de Gestión Web',
                'fecha_inicio' => '14/03/2025',
                'fecha_fin' => '16/03/2025',
                'estado' => 'Programado',
                'modalidad' => 'Virtual',
                'participantes_actuales' => 82,
                'participantes_max' => 150
            ],
            (object)[
                'id' => 3,
                'nombre' => 'Desafío de IA',
                'proyecto' => 'Aplicación de IA',
                'fecha_inicio' => '09/04/2025',
                'fecha_fin' => '11/04/2025',
                'estado' => 'Programado',
                'modalidad' => 'Híbrida',
                'participantes_actuales' => 23,
                'participantes_max' => 80
            ],
            (object)[
                'id' => 4,
                'nombre' => 'Concurso de desarrollo web 2024',
                'proyecto' => 'Plataforma de comercio electrónico',
                'fecha_inicio' => '30/11/2024',
                'fecha_fin' => '04/12/2024',
                'estado' => 'Finalizado',
                'modalidad' => 'Presencial',
                'participantes_actuales' => 95,
                'participantes_max' => 120
            ],
            (object)[
                'id' => 5,
                'nombre' => 'Innovación móvil',
                'proyecto' => 'Desarrollo de aplicaciones móviles',
                'fecha_inicio' => '19/02/2025',
                'fecha_fin' => '21/02/2025',
                'estado' => 'Cancelado',
                'modalidad' => 'Presencial',
                'participantes_actuales' => 0,
                'participantes_max' => 60
            ]
        ]);

        return view('admin.eventos.index', compact('eventos'));

        /* DESCOMENTAR CUANDO TENGAS LA BASE DE DATOS
        $query = Evento::query();

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        // Filtro por estado
        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        }

        // Filtro por modalidad
        if ($request->filled('modalidad') && $request->modalidad !== 'todas') {
            $query->where('modalidad', $request->modalidad);
        }

        $eventos = $query->orderBy('fecha_inicio', 'desc')->paginate(10);

        return view('admin.eventos.index', compact('eventos'));
        */
    }

    public function create()
    {
        return view('admin.eventos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:activo,programado,finalizado,cancelado',
            'modalidad' => 'required|in:presencial,virtual,hibrida',
            'participantes_max' => 'required|integer|min:1'
        ]);

        Evento::create($validated);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento creado exitosamente');
    }

    public function show(Evento $evento)
    {
        return view('admin.eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        return view('admin.eventos.edit', compact('evento'));
    }

    public function update(Request $request, Evento $evento)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:activo,programado,finalizado,cancelado',
            'modalidad' => 'required|in:presencial,virtual,hibrida',
            'participantes_max' => 'required|integer|min:1'
        ]);

        $evento->update($validated);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento actualizado exitosamente');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento eliminado exitosamente');
    }
}