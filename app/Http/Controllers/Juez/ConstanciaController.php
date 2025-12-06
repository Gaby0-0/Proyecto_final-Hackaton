<?php

namespace App\Http\Controllers\Juez;

use App\Http\Controllers\Controller;
use App\Models\Constancia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConstanciaController extends Controller
{
    // Mostrar lista de constancias del juez
    public function index()
    {
        $user = Auth::user();

        // Obtener constancias del juez ordenadas por fecha
        $constancias = $user->constancias()
            ->with(['evento'])
            ->where('tipo', 'juez')
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return view('juez.constancias.index', compact('constancias'));
    }

    // Ver detalle de una constancia
    public function show(Constancia $constancia)
    {
        $user = Auth::user();

        // Verificar que la constancia pertenece al juez
        if ($constancia->user_id !== $user->id) {
            return redirect()->route('juez.constancias.index')
                ->with('error', 'No tienes acceso a esta constancia');
        }

        $constancia->load(['evento', 'usuario']);

        return view('juez.constancias.show', compact('constancia'));
    }
}
