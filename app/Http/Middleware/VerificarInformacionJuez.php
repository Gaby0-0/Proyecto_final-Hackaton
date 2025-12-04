<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificarInformacionJuez
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Solo aplica a jueces
            if ($user->role === 'juez') {
                // Verificar si tiene información completa
                if (!$user->tieneInformacionCompleta()) {
                    // Permitir acceso solo a las rutas de perfil y logout
                    if (!$request->routeIs('juez.perfil.*') && !$request->routeIs('logout')) {
                        return redirect()->route('juez.perfil.completar')
                            ->with('warning', 'Debes completar tu información de perfil para continuar.');
                    }
                }
            }
        }

        return $next($request);
    }
}
