<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\EquipoController;
use App\Http\Controllers\Admin\EventoController;
use App\Http\Controllers\Admin\ProyectoController;
use App\Http\Controllers\Admin\EvaluacionController;
use App\Http\Controllers\Admin\ConstanciaController;
use App\Http\Controllers\Admin\InformeController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\Juez\DashboardController as JuezDashboardController;
use App\Http\Controllers\Estudiante\DashboardController as EstudianteDashboardController;
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

    // Gestión de equipos
    Route::resource('equipos', EquipoController::class);

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

// Ruta para mostrar la vista de crear evento
Route::get('/eventos/crear', function () {
    return view('eventos.create');
})->name('eventos.create');
Route::get('/equipos', function () {
    return view('equipos.index');
})->name('equipos.index');
Route::get('/eventos/panel', function () {
    return view('eventos.panel');
});
// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas para Jueces
Route::middleware(['auth', 'juez'])->prefix('juez')->name('juez.')->group(function() {
    Route::get('/', [JuezDashboardController::class, 'index'])->name('dashboard');
});

// Rutas para Estudiantes
Route::middleware(['auth', 'estudiante'])->prefix('dashboard')->name('estudiante.')->group(function() {
    Route::get('/', [EstudianteDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');