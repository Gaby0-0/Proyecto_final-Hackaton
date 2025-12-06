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
        $constanciasParticipante = Constancia::where('tipo', 'participante')->count();
        $constanciasJuez = Constancia::where('tipo', 'juez')->count();

        return view('admin.constancias.index', compact(
            'constancias',
            'eventos',
            'totalConstancias',
            'constanciasGanador',
            'constanciasParticipante',
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
        // Generar constancias de participante para todos
        $participantes = $evento->generarConstanciasParticipantes();

        // Si hay ganador, generar constancias de ganador
        $ganadores = 0;
        if ($evento->equipo_ganador_id) {
            $ganadores = $evento->generarConstanciaGanador();
        }

        // Generar constancias para jueces
        $jueces = $evento->generarConstanciasJueces();

        return back()->with('success', "Se generaron {$participantes} constancias de participante, {$ganadores} de ganador y {$jueces} reconocimientos de jueces para el evento {$evento->nombre}");
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
