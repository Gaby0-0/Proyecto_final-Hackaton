<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Constancia;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Http\Request;

class ConstanciaController extends Controller
{
    // Listar constancias agrupadas por eventos finalizados
    public function index(Request $request)
    {
        // Obtener eventos finalizados con constancias
        $eventosFinalizados = Evento::where('estado', 'finalizado')
            ->withCount('constancias')
            ->having('constancias_count', '>', 0)
            ->with(['constancias' => function ($query) {
                $query->with(['usuario', 'equipo'])->orderBy('tipo', 'asc');
            }])
            ->orderBy('fecha_fin', 'desc')
            ->get();

        // Eventos finalizados sin constancias (para generar)
        $eventosSinConstancias = Evento::where('estado', 'finalizado')
            ->doesntHave('constancias')
            ->orderBy('fecha_fin', 'desc')
            ->get();

        // Estadísticas
        $totalConstancias = Constancia::count();
        $constanciasGanador = Constancia::where('tipo', 'ganador')->count();
        $constanciasJuez = Constancia::where('tipo', 'juez')->count();
        $constanciasParticipante = Constancia::where('tipo', 'participante')->count();

        return view('admin.constancias.index', compact(
            'eventosFinalizados',
            'eventosSinConstancias',
            'totalConstancias',
            'constanciasGanador',
            'constanciasJuez',
            'constanciasParticipante'
        ));
    }

    // Ver detalles de una constancia
    public function show(Constancia $constancia)
    {
        $constancia->load(['usuario', 'evento', 'equipo.miembros']);

        return view('admin.constancias.show', compact('constancia'));
    }

    // Generar constancias para un evento
    public function generarPorEvento(Evento $evento)
    {
        // Validar que el evento esté finalizado
        if (! $evento->puedeGenerarConstancias()) {
            return back()->with('error', 'No se pueden generar constancias. El evento debe estar marcado como "Finalizado" para expedir constancias.');
        }

        // Verificar que haya equipos o jueces
        $equiposCount = $evento->equiposAprobados()->count();
        $juecesCount = $evento->jueces()->count();

        if ($equiposCount === 0 && $juecesCount === 0) {
            return back()->with('error', "No se pueden generar constancias para el evento '{$evento->nombre}' porque no tiene equipos participantes ni jueces asignados.");
        }

        try {
            // Generar constancias de ganadores (si existen)
            $ganadores = 0;
            if ($evento->tieneGanador()) {
                $ganadores = $evento->generarConstanciasGanadores();
            }

            // Generar constancias para participantes
            $participantes = $evento->generarConstanciasParticipantes();

            // Generar constancias para jueces
            $jueces = $evento->generarConstanciasJueces();

            $total = $ganadores + $participantes + $jueces;

            if ($total === 0) {
                return back()->with('warning', "No se generaron constancias nuevas. Es posible que ya existan constancias para todos los participantes del evento '{$evento->nombre}'.");
            }

            $mensaje = "Se generaron {$total} constancia(s) para el evento '{$evento->nombre}': ";
            $detalles = [];

            if ($ganadores > 0) {
                $detalles[] = "{$ganadores} de ganadores";
            }
            if ($participantes > 0) {
                $detalles[] = "{$participantes} de participantes";
            }
            if ($jueces > 0) {
                $detalles[] = "{$jueces} de jueces";
            }

            if (! $evento->tieneGanador() && $equiposCount > 0) {
                $mensaje .= implode(', ', $detalles).'. Nota: No se generaron constancias de ganadores porque no hay ganador asignado en el evento.';
            } else {
                $mensaje .= implode(', ', $detalles).'.';
            }

            return back()->with('success', $mensaje);
        } catch (\Exception $e) {
            \Log::error('Error al generar constancias', [
                'evento_id' => $evento->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Error al generar constancias: '.$e->getMessage());
        }
    }

    // Eliminar constancia
    public function destroy(Constancia $constancia)
    {
        $constancia->delete();

        return back()->with('success', 'Constancia eliminada exitosamente');
    }

    // Regenerar constancia
    public function regenerar(Constancia $constancia)
    {
        // Actualizar fecha de emisión
        $constancia->update([
            'fecha_emision' => now(),
            'descargada' => false,
            'archivo_url' => null, // Forzar regeneración de PDF
        ]);

        return back()->with('success', 'Constancia regenerada exitosamente');
    }

    // Ver constancias de un evento específico
    public function porEvento(Evento $evento)
    {
        $constancias = $evento->constancias()
            ->with(['usuario', 'equipo'])
            ->orderBy('tipo', 'asc')
            ->orderBy('fecha_emision', 'desc')
            ->get();

        $constanciasGanador = $constancias->where('tipo', 'ganador');
        $constanciasParticipante = $constancias->where('tipo', 'participante');

        // Verificar si faltan constancias por generar
        $equiposInscritos = $evento->equiposAprobados()->with('miembros')->get();
        $totalMiembros = $equiposInscritos->sum(fn ($equipo) => $equipo->miembros->count());
        $totalConstancias = $constancias->count();

        return view('admin.constancias.evento', compact(
            'evento',
            'constancias',
            'constanciasGanador',
            'constanciasParticipante',
            'totalMiembros',
            'totalConstancias'
        ));
    }

    // Ver constancias de un usuario
    public function porUsuario(User $usuario)
    {
        $constancias = $usuario->constancias()
            ->with(['evento', 'equipo'])
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return view('admin.constancias.usuario', compact('usuario', 'constancias'));
    }
}
