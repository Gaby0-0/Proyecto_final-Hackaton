<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    public function index(Request $request)
    {
        // Obtener proyectos entregados por los equipos desde la tabla pivot equipo_evento
        $query = DB::table('equipo_evento')
            ->join('equipos', 'equipo_evento.equipo_id', '=', 'equipos.id')
            ->join('eventos', 'equipo_evento.evento_id', '=', 'eventos.id')
            ->whereNotNull('equipo_evento.proyecto_titulo')
            ->select(
                'equipo_evento.id as pivot_id',
                'equipo_evento.proyecto_titulo',
                'equipo_evento.proyecto_descripcion',
                'equipo_evento.proyecto_final_url',
                'equipo_evento.fecha_entrega_final',
                'equipos.id as equipo_id',
                'equipos.nombre as equipo_nombre',
                'eventos.id as evento_id',
                'eventos.nombre as evento_nombre',
                'eventos.categoria as evento_categoria'
            );

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('equipo_evento.proyecto_titulo', 'like', '%'.$request->buscar.'%')
                    ->orWhere('equipo_evento.proyecto_descripcion', 'like', '%'.$request->buscar.'%')
                    ->orWhere('equipos.nombre', 'like', '%'.$request->buscar.'%');
            });
        }

        $proyectos = $query->orderBy('equipo_evento.fecha_entrega_final', 'desc')
            ->paginate(10);

        return view('admin.proyectos.index', compact('proyectos'));
    }

    public function show($pivotId)
    {
        // Obtener el proyecto entregado por un equipo específico
        $proyecto = DB::table('equipo_evento')
            ->join('equipos', 'equipo_evento.equipo_id', '=', 'equipos.id')
            ->join('eventos', 'equipo_evento.evento_id', '=', 'eventos.id')
            ->where('equipo_evento.id', $pivotId)
            ->select(
                'equipo_evento.*',
                'equipos.id as equipo_id',
                'equipos.nombre as equipo_nombre',
                'equipos.codigo as equipo_codigo',
                'eventos.id as evento_id',
                'eventos.nombre as evento_nombre',
                'eventos.categoria as evento_categoria'
            )
            ->first();

        if (! $proyecto) {
            abort(404, 'Proyecto no encontrado');
        }

        // Obtener los miembros del equipo
        $miembros = DB::table('equipo_user')
            ->join('users', 'equipo_user.user_id', '=', 'users.id')
            ->where('equipo_user.equipo_id', $proyecto->equipo_id)
            ->select('users.*', 'equipo_user.rol_especifico')
            ->get();

        return view('admin.proyectos.show', compact('proyecto', 'miembros'));
    }

    // Los siguientes métodos están deshabilitados porque el administrador
    // no puede crear/editar/eliminar proyectos. Los proyectos son entregados por los equipos.

    public function create()
    {
        abort(403, 'No autorizado. Los proyectos son entregados por los equipos.');
    }

    public function store(Request $request)
    {
        abort(403, 'No autorizado. Los proyectos son entregados por los equipos.');
    }

    public function edit($id)
    {
        abort(403, 'No autorizado. Los proyectos son entregados por los equipos.');
    }

    public function update(Request $request, $id)
    {
        abort(403, 'No autorizado. Los proyectos son entregados por los equipos.');
    }

    public function destroy($id)
    {
        abort(403, 'No autorizado. Los proyectos son entregados por los equipos.');
    }
}
