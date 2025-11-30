<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\EventoController;
use App\Http\Controllers\Admin\ProyectoController;
use App\Http\Controllers\Admin\EvaluacionController;
use App\Http\Controllers\Admin\ConstanciaController;
use App\Http\Controllers\Admin\InformeController;
use App\Http\Controllers\Admin\ConfiguracionController;

// Rutas públicas o de usuarios normales
Route::get('/', function () {
    return view('welcome');
});

// Grupo de rutas de administración (sin middleware temporalmente para pruebas)
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de usuarios
    Route::resource('usuarios', UsuarioController::class);
    
    // Gestión de eventos
    Route::resource('eventos', EventoController::class);
    
    // Proyectos
    Route::resource('proyectos', ProyectoController::class);
    
    // Evaluaciones
    Route::resource('evaluaciones', EvaluacionController::class);
    
    // Constancias
    Route::resource('constancias', ConstanciaController::class);
    
    // Informes
    Route::get('informes', [InformeController::class, 'index'])->name('informes.index');
    
    // Configuración
    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::put('configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
});