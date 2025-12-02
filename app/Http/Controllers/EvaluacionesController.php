<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvaluacionesController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total' => 4,
            'pendientes' => 2,
            'en_progreso' => 1,
            'completadas' => 1,
        ];

        $eventos = [];

        $defaultEvaluaciones = [];

        return view('evaluaciones.index', compact('stats', 'eventos', 'defaultEvaluaciones'));
    }

    public function show($id)
    {
        $evaluacion = (object)[
            'id' => $id,
            'proyecto' => 'ChatBot Educativo Inteligente',
            'descripcion' => 'Sistema de chatbot inteligente',
        ];

        $stats = [
            'completadas' => 1,
            'en_progreso' => 1,
            'pendientes' => 2,
        ];

        return view('evaluaciones.show', compact('evaluacion', 'stats'));
    }
}