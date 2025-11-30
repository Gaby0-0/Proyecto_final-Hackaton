<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Datos de prueba (comentar cuando tengas la BD lista)
        $totalUsuarios = 123;
        $crecimientoUsuarios = 12;
        $equiposActivos = 45;
        $crecimientoEquipos = 12;
        $eventosActivos = 12;
        $crecimientoEventos = 12;
        $evaluacionesPendientes = 8;
        
        // Eventos recientes de prueba
        $eventosRecientes = collect([
            (object)[
                'id' => 1,
                'nombre' => 'Hackatón 2025',
                'fecha_inicio' => now()->subDays(2),
                'fecha_fin' => now()->addDays(1),
                'estado' => 'activo',
                'participantes' => collect(range(1, 89))
            ],
            (object)[
                'id' => 2,
                'nombre' => 'Desafío de código de primavera',
                'fecha_inicio' => now()->subDays(10),
                'fecha_fin' => now()->subDays(8),
                'estado' => 'programado',
                'participantes' => collect(range(1, 45))
            ],
            (object)[
                'id' => 3,
                'nombre' => 'Concurso de Innovación en IA',
                'fecha_inicio' => now()->subDays(5),
                'fecha_fin' => now()->addDays(2),
                'estado' => 'programado',
                'participantes' => collect(range(1, 67))
            ]
        ]);
        
        // Actividad del sistema
        $actividadSistema = [
            [
                'tipo' => 'equipo',
                'mensaje' => 'Nuevo equipo registrado: "Alpha Developers"',
                'tiempo' => 'Hace 2 horas',
                'icono' => 'info'
            ],
            [
                'tipo' => 'evaluacion',
                'mensaje' => 'Evaluación completada para "Beta Coders"',
                'tiempo' => 'Hace 4 horas',
                'icono' => 'success'
            ],
            [
                'tipo' => 'archivo',
                'mensaje' => 'Archivo subido: proyecto_gamma.pdf',
                'tiempo' => 'Hace 6 horas',
                'icono' => 'info'
            ],
            [
                'tipo' => 'error',
                'mensaje' => 'Error en generación de constancia',
                'tiempo' => 'Hace 1 día',
                'icono' => 'error'
            ]
        ];
        
        return view('admin.dashboard.index', compact(
            'totalUsuarios',
            'crecimientoUsuarios',
            'equiposActivos',
            'crecimientoEquipos',
            'eventosActivos',
            'crecimientoEventos',
            'evaluacionesPendientes',
            'eventosRecientes',
            'actividadSistema'
        ));
        
        /* DESCOMENTAR CUANDO TENGAS LA BASE DE DATOS LISTA
        // Obtener estadísticas del mes anterior
        $mesAnterior = now()->subMonth();
        
        // Total de usuarios y crecimiento
        $totalUsuarios = User::count();
        $usuariosMesAnterior = User::where('created_at', '<=', $mesAnterior)->count();
        $crecimientoUsuarios = $usuariosMesAnterior > 0 
            ? round((($totalUsuarios - $usuariosMesAnterior) / $usuariosMesAnterior) * 100, 1) 
            : 0;
        
        // Equipos activos y crecimiento
        $equiposActivos = Equipo::where('activo', true)->count();
        $equiposActivosMesAnterior = Equipo::where('activo', true)
            ->where('created_at', '<=', $mesAnterior)
            ->count();
        $crecimientoEquipos = $equiposActivosMesAnterior > 0 
            ? round((($equiposActivos - $equiposActivosMesAnterior) / $equiposActivosMesAnterior) * 100, 1) 
            : 0;
        
        // Eventos activos y crecimiento
        $eventosActivos = Evento::where('estado', 'activo')->count();
        $eventosActivosMesAnterior = Evento::where('estado', 'activo')
            ->where('created_at', '<=', $mesAnterior)
            ->count();
        $crecimientoEventos = $eventosActivosMesAnterior > 0 
            ? round((($eventosActivos - $eventosActivosMesAnterior) / $eventosActivosMesAnterior) * 100, 1) 
            : 0;
        
        // Evaluaciones pendientes
        $evaluacionesPendientes = Evaluacion::where('estado', 'pendiente')->count();
        
        // Eventos recientes
        $eventosRecientes = Evento::with('participantes')
            ->orderBy('fecha_inicio', 'desc')
            ->take(3)
            ->get();
        */
    }
}