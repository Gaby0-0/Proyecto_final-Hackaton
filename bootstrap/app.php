<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Binding personalizado para jueces - debe resolver al modelo User, no Juez
            Route::bind('juez', function (string $value) {
                return \App\Models\User::where('id', $value)
                    ->where('role', 'juez')
                    ->firstOrFail();
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Configurar TrustProxies para detectar HTTPS correctamente en producción
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'juez' => \App\Http\Middleware\JuezMiddleware::class,
            'estudiante' => \App\Http\Middleware\EstudianteMiddleware::class,
            'usuario.activo' => \App\Http\Middleware\VerificarUsuarioActivo::class,
            'juez.info' => \App\Http\Middleware\VerificarInformacionJuez::class,
        ]);

        // Middleware globales removidos - se aplican individualmente en las rutas
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
