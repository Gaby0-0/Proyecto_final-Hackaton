<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
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
