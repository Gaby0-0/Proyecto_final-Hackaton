<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificarUsuarioActivo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Excluir rutas públicas que no requieren verificación de usuario activo
        $rutasExcluidas = [
            'login',
            'registro',
            'registro.store',
            'login.store',
            'logout',
        ];

        // Si la ruta actual está en las rutas excluidas, continuar sin verificar
        if (in_array($request->route()?->getName(), $rutasExcluidas)) {
            return $next($request);
        }

        if (Auth::check()) {
            $user = Auth::user();

            // Verificar si el usuario está activo
            if (! $user->activo) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Tu cuenta ha sido desactivada. Contacta al administrador.']);
            }
        }

        return $next($request);
    }
}
