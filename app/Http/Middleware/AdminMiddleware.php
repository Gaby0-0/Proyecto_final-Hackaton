<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
       
                if (!$request->user()) {
                        return redirect()->route('login');
                    }

                    $user = $request->user();

            // Si no es admin (columna admin = 0)
            if (!$user->admin) {
                abort(403, 'No tienes permisos para acceder a esta área.');
            }

            // Bloquear caché para que no pueda regresar con ← o →
            return $next($request)
                ->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma','no-cache')
                ->header('Expires','Sat, 01 Jan 1990 00:00:00 GMT');
    }
}