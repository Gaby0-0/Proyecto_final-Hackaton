<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('estudiante.dashboard.index', compact('user'));
    }
}
