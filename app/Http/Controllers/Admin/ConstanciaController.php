<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Constancia;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Http\Request;

class ConstanciaController extends Controller
{
    // Listar todas las constancias
    public function index(Request $request)
    {
        $query = Constancia::with(['usuario', 'evento', 'equipo']);

        // Filtros
        if ($request->filled('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('buscar')) {
            $query->whereHas('usuario', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->buscar . '%')
                  ->orWhere('email', 'like', '%' . $request->buscar . '%');
            });
        }

        $constancias = $query->orderBy('fecha_emision', 'desc')->paginate(20);

        // Obtener eventos para el filtro
        $eventos = Evento::orderBy('fecha_inicio', 'desc')->get();

        // Estadísticas
        $totalConstancias = Constancia::count();
        $constanciasGanador = Constancia::where('tipo', 'ganador')->count();
        $constanciasJuez = Constancia::where('tipo', 'juez')->count();

        return view('admin.constancias.index', compact(
            'constancias',
            'eventos',
            'totalConstancias',
            'constanciasGanador',
            'constanciasJuez'
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
        if (!$evento->puedeGenerarConstancias()) {
            return back()->with('error', 'No se pueden generar constancias. El evento debe estar marcado como "Finalizado" para expedir constancias.');
        }

        try {
            // Generar constancias de ganadores (si existen)
            $ganadores = 0;
            if ($evento->tieneGanador()) {
                $ganadores = $evento->generarConstanciasGanadores();
            }

            // Generar constancias para jueces
            $jueces = $evento->generarConstanciasJueces();

            return back()->with('success', "Se generaron {$ganadores} constancias de ganadores y {$jueces} reconocimientos de jueces para el evento {$evento->nombre}");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar constancias: ' . $e->getMessage());
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
        $totalMiembros = $equiposInscritos->sum(fn($equipo) => $equipo->miembros->count());
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
