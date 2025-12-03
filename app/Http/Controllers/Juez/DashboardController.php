<?php

namespace App\Http\Controllers\Juez;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('juez.dashboard.index', compact('user'));
    }
}
