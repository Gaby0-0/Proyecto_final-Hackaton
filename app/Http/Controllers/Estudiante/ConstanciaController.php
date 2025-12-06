<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Constancia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConstanciaController extends Controller
{
    // Mostrar lista de constancias del estudiante
    public function index()
    {
        $user = Auth::user();

        // Obtener constancias del usuario ordenadas por fecha
        $constancias = $user->constancias()
            ->with(['evento', 'equipo'])
            ->orderBy('fecha_emision', 'desc')
            ->get();

        // Separar por tipo
        $constanciasGanador = $constancias->where('tipo', 'ganador');
        $constanciasParticipante = $constancias->where('tipo', 'participante');

        return view('estudiante.constancias.index', compact('constancias', 'constanciasGanador', 'constanciasParticipante'));
    }

    // Ver detalle de una constancia
    public function show(Constancia $constancia)
    {
        $user = Auth::user();

        // Verificar que la constancia pertenece al usuario
        if ($constancia->user_id !== $user->id) {
            return redirect()->route('estudiante.constancias.index')
                ->with('error', 'No tienes acceso a esta constancia');
        }

        $constancia->load(['evento', 'equipo.miembros']);

        return view('estudiante.constancias.show', compact('constancia'));
    }

    // Descargar constancia (PDF)
    public function descargar(Constancia $constancia)
    {
        $user = Auth::user();

        // Verificar que la constancia pertenece al usuario
        if ($constancia->user_id !== $user->id) {
            return redirect()->route('estudiante.constancias.index')
                ->with('error', 'No tienes acceso a esta constancia');
        }

        // Marcar como descargada
        $constancia->marcarDescargada();

        // Si ya existe archivo generado, descargarlo
        if ($constancia->archivo_url && Storage::disk('public')->exists($constancia->archivo_url)) {
            return Storage::disk('public')->download($constancia->archivo_url, 'Constancia-' . $constancia->numero_folio . '.pdf');
        }

        // Si no existe archivo, generar PDF básico (puedes usar una librería como DomPDF)
        return $this->generarPDF($constancia);
    }

    // Generar PDF de constancia
    private function generarPDF(Constancia $constancia)
    {
        $constancia->load(['evento', 'equipo', 'usuario']);

        // Aquí puedes usar DomPDF o cualquier otra librería
        // Por ahora, retornamos un mensaje
        return response()->json([
            'message' => 'Generación de PDF pendiente de implementar',
            'constancia' => [
                'folio' => $constancia->numero_folio,
                'tipo' => $constancia->tipo,
                'evento' => $constancia->evento->nombre,
                'usuario' => $constancia->usuario->name,
                'fecha' => $constancia->fecha_emision->format('d/m/Y'),
            ]
        ]);
    }
}
