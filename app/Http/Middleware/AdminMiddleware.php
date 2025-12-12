<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder al panel de administración.');
        }

        // Verificar si el usuario tiene rol de administrador
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'administrador') {
            abort(403, 'No tienes permisos para acceder a esta área.');
        }

        return $next($request);
    }
}
