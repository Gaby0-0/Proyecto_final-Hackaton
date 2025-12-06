<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Equipo;
use App\Models\User;
use App\Models\Evaluacion;
use App\Models\Constancia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformeController extends Controller
{
    // Dashboard principal de informes
    public function index()
    {
        // Estadísticas generales
        $totalEventos = Evento::count();
        $eventosActivos = Evento::where('activo', true)->count();
        $eventosFinalizados = Evento::where('activo', false)->count();

        $totalEquipos = Equipo::count();
        $equiposActivos = Equipo::where('activo', true)->count();

        $totalEstudiantes = User::where('rol', 'estudiante')->count();
        $totalJueces = User::where('rol', 'juez')->count();
        $totalAdmins = User::where('rol', 'admin')->count();

        $totalEvaluaciones = Evaluacion::count();
        $promedioEvaluacionesGeneral = Evaluacion::avg('puntuacion');

        $totalConstancias = Constancia::count();
        $constanciasGanadores = Constancia::where('tipo', 'ganador')->count();
        $constanciasParticipantes = Constancia::where('tipo', 'participante')->count();

        // Eventos próximos
        $eventosProximos = Evento::where('fecha_inicio', '>', now())
            ->orderBy('fecha_inicio', 'asc')
            ->limit(5)
            ->get();

        // Eventos recientes
        $eventosRecientes = Evento::where('fecha_fin', '<', now())
            ->orderBy('fecha_fin', 'desc')
            ->limit(5)
            ->get();

        // Top equipos por evaluaciones
        $topEquipos = Equipo::withCount('evaluaciones')
            ->having('evaluaciones_count', '>', 0)
            ->orderBy('evaluaciones_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($equipo) {
                $equipo->promedio_evaluaciones = $equipo->evaluaciones()->avg('puntuacion');
                return $equipo;
            });

        // Jueces más activos
        $juecesActivos = User::where('rol', 'juez')
            ->withCount('evaluacionesRealizadas')
            ->having('evaluaciones_realizadas_count', '>', 0)
            ->orderBy('evaluaciones_realizadas_count', 'desc')
            ->limit(10)
            ->get();

        // Estadísticas por mes (últimos 6 meses)
        $estadisticasMensuales = $this->obtenerEstadisticasMensuales();

        return view('admin.informes.index', compact(
            'totalEventos',
            'eventosActivos',
            'eventosFinalizados',
            'totalEquipos',
            'equiposActivos',
            'totalEstudiantes',
            'totalJueces',
            'totalAdmins',
            'totalEvaluaciones',
            'promedioEvaluacionesGeneral',
            'totalConstancias',
            'constanciasGanadores',
            'constanciasParticipantes',
            'eventosProximos',
            'eventosRecientes',
            'topEquipos',
            'juecesActivos',
            'estadisticasMensuales'
        ));
    }

    // Informe detallado de eventos
    public function eventos()
    {
        $eventos = Evento::withCount(['equipos', 'evaluaciones', 'constancias'])
            ->with('equipoGanador')
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(20);

        foreach ($eventos as $evento) {
            $evento->promedio_evaluaciones = $evento->evaluaciones()->avg('puntuacion');
            $evento->total_participantes = $evento->equipos()
                ->with('miembros')
                ->get()
                ->sum(function($equipo) {
                    return $equipo->miembros->count();
                });
        }

        return view('admin.informes.eventos', compact('eventos'));
    }

    // Informe detallado de equipos
    public function equipos()
    {
        $equipos = Equipo::withCount(['miembros', 'eventos', 'evaluaciones'])
            ->with('proyecto')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        foreach ($equipos as $equipo) {
            $equipo->promedio_evaluaciones = $equipo->evaluaciones()->avg('puntuacion');
            $equipo->eventos_ganados = Evento::where('equipo_ganador_id', $equipo->id)->count();
        }

        return view('admin.informes.equipos', compact('equipos'));
    }

    // Informe de evaluaciones
    public function evaluaciones()
    {
        $evaluacionesPorEvento = Evento::withCount('evaluaciones')
            ->having('evaluaciones_count', '>', 0)
            ->get()
            ->map(function($evento) {
                return [
                    'evento' => $evento,
                    'total' => $evento->evaluaciones_count,
                    'promedio' => $evento->evaluaciones()->avg('puntuacion'),
                    'maxima' => $evento->evaluaciones()->max('puntuacion'),
                    'minima' => $evento->evaluaciones()->min('puntuacion'),
                ];
            });

        $evaluacionesPorJuez = User::where('rol', 'juez')
            ->withCount('evaluacionesRealizadas')
            ->having('evaluaciones_realizadas_count', '>', 0)
            ->get()
            ->map(function($juez) {
                return [
                    'juez' => $juez,
                    'total' => $juez->evaluaciones_realizadas_count,
                    'promedio' => $juez->evaluacionesRealizadas()->avg('puntuacion'),
                ];
            });

        $distribucionPuntuaciones = Evaluacion::select(
            DB::raw('FLOOR(puntuacion/10)*10 as rango'),
            DB::raw('COUNT(*) as cantidad')
        )
            ->groupBy('rango')
            ->orderBy('rango')
            ->get();

        return view('admin.informes.evaluaciones', compact(
            'evaluacionesPorEvento',
            'evaluacionesPorJuez',
            'distribucionPuntuaciones'
        ));
    }

    // Informe de constancias
    public function constancias()
    {
        $constanciasPorEvento = Evento::withCount('constancias')
            ->having('constancias_count', '>', 0)
            ->get()
            ->map(function($evento) {
                return [
                    'evento' => $evento,
                    'total' => $evento->constancias_count,
                    'ganadores' => $evento->constancias()->where('tipo', 'ganador')->count(),
                    'participantes' => $evento->constancias()->where('tipo', 'participante')->count(),
                ];
            });

        $constanciasPorTipo = Constancia::select('tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->get();

        $constanciasRecientes = Constancia::with(['user', 'evento', 'equipo'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.informes.constancias', compact(
            'constanciasPorEvento',
            'constanciasPorTipo',
            'constanciasRecientes'
        ));
    }

    // Informe de participación
    public function participacion()
    {
        $participacionPorEvento = Evento::withCount(['equipos'])
            ->get()
            ->map(function($evento) {
                $totalParticipantes = $evento->equipos()
                    ->with('miembros')
                    ->get()
                    ->sum(function($equipo) {
                        return $equipo->miembros->count();
                    });

                return [
                    'evento' => $evento,
                    'equipos' => $evento->equipos_count,
                    'participantes' => $totalParticipantes,
                    'promedio_por_equipo' => $evento->equipos_count > 0 ?
                        round($totalParticipantes / $evento->equipos_count, 2) : 0,
                ];
            });

        $estudiantesActivos = User::where('rol', 'estudiante')
            ->whereHas('equipos')
            ->count();

        $estudiantesSinEquipo = User::where('rol', 'estudiante')
            ->whereDoesntHave('equipos')
            ->count();

        return view('admin.informes.participacion', compact(
            'participacionPorEvento',
            'estudiantesActivos',
            'estudiantesSinEquipo'
        ));
    }

    // Obtener estadísticas mensuales
    private function obtenerEstadisticasMensuales()
    {
        $meses = [];

        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $mesInicio = $fecha->copy()->startOfMonth();
            $mesFin = $fecha->copy()->endOfMonth();

            $meses[] = [
                'mes' => $fecha->format('M Y'),
                'eventos' => Evento::whereBetween('fecha_inicio', [$mesInicio, $mesFin])->count(),
                'equipos' => Equipo::whereBetween('created_at', [$mesInicio, $mesFin])->count(),
                'evaluaciones' => Evaluacion::whereBetween('created_at', [$mesInicio, $mesFin])->count(),
                'constancias' => Constancia::whereBetween('created_at', [$mesInicio, $mesFin])->count(),
            ];
        }

        return $meses;
    }
}
