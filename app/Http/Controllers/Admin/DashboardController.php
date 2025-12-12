<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Evaluacion;
use App\Models\Evento;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener estadísticas del mes anterior para comparación
        $mesAnterior = now()->subMonth();

        // Total de usuarios y crecimiento
        $totalUsuarios = User::count();
        $usuariosMesAnterior = User::where('created_at', '<=', $mesAnterior)->count();
        $crecimientoUsuarios = $usuariosMesAnterior > 0
            ? round((($totalUsuarios - $usuariosMesAnterior) / $usuariosMesAnterior) * 100, 1)
            : 0;

        // Equipos activos y crecimiento
        $equiposActivos = Equipo::count();
        $equiposActivosMesAnterior = Equipo::where('created_at', '<=', $mesAnterior)->count();
        $crecimientoEquipos = $equiposActivosMesAnterior > 0
            ? round((($equiposActivos - $equiposActivosMesAnterior) / $equiposActivosMesAnterior) * 100, 1)
            : 0;

        // Eventos activos
        $eventosActivos = Evento::count();
        $eventosActivosMesAnterior = Evento::where('created_at', '<=', $mesAnterior)->count();
        $crecimientoEventos = $eventosActivosMesAnterior > 0
            ? round((($eventosActivos - $eventosActivosMesAnterior) / $eventosActivosMesAnterior) * 100, 1)
            : 0;

        // Evaluaciones pendientes
        $evaluacionesPendientes = Evaluacion::count();

        // Eventos recientes (últimos 5)
        $eventosRecientes = Evento::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Actividad reciente del sistema
        $actividadSistema = $this->obtenerActividadReciente();

        // Usuarios por rol (si existe la tabla de usuarios)
        $usuariosPorRol = $this->obtenerUsuariosPorRol();

        // Estadísticas de equipos
        $estadisticasEquipos = [
            'total' => $equiposActivos,
            'recientes' => Equipo::where('created_at', '>=', now()->subDays(7))->count(),
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
            'actividadSistema',
            'usuariosPorRol',
            'estadisticasEquipos'
        ));
    }

    private function obtenerActividadReciente()
    {
        $actividades = [];

        // Últimos equipos creados
        $ultimosEquipos = Equipo::orderBy('created_at', 'desc')->take(3)->get();
        foreach ($ultimosEquipos as $equipo) {
            $actividades[] = [
                'tipo' => 'equipo',
                'mensaje' => "Nuevo equipo registrado: \"{$equipo->nombre}\"",
                'tiempo' => $equipo->created_at->diffForHumans(),
                'icono' => 'info',
                'fecha' => $equipo->created_at,
            ];
        }

        // Últimas evaluaciones
        $ultimasEvaluaciones = Evaluacion::orderBy('created_at', 'desc')->take(2)->get();
        foreach ($ultimasEvaluaciones as $evaluacion) {
            $actividades[] = [
                'tipo' => 'evaluacion',
                'mensaje' => 'Nueva evaluación registrada',
                'tiempo' => $evaluacion->created_at->diffForHumans(),
                'icono' => 'success',
                'fecha' => $evaluacion->created_at,
            ];
        }

        // Últimos usuarios
        $ultimosUsuarios = User::orderBy('created_at', 'desc')->take(2)->get();
        foreach ($ultimosUsuarios as $usuario) {
            $actividades[] = [
                'tipo' => 'usuario',
                'mensaje' => "Nuevo usuario registrado: {$usuario->name}",
                'tiempo' => $usuario->created_at->diffForHumans(),
                'icono' => 'info',
                'fecha' => $usuario->created_at,
            ];
        }

        // Ordenar por fecha
        usort($actividades, function ($a, $b) {
            return $b['fecha'] <=> $a['fecha'];
        });

        return array_slice($actividades, 0, 6);
    }

    private function obtenerUsuariosPorRol()
    {
        return [
            'administradores' => User::where('role', 'admin')->count(),
            'usuarios' => User::where('role', 'estudiante')->count(),
            'jueces' => User::where('role', 'juez')->count(),
            'total' => User::count(),
        ];
    }
}
