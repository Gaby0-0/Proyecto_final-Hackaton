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

    // Rutas de evaluaciones
    Route::get('/evaluaciones', [\App\Http\Controllers\Juez\EvaluacionController::class, 'index'])->name('evaluaciones.index');
    Route::get('/evaluaciones/{equipo}', [\App\Http\Controllers\Juez\EvaluacionController::class, 'show'])->name('evaluaciones.show');
    Route::get('/evaluaciones/{equipo}/crear', [\App\Http\Controllers\Juez\EvaluacionController::class, 'crear'])->name('evaluaciones.crear');
    Route::post('/evaluaciones/{equipo}', [\App\Http\Controllers\Juez\EvaluacionController::class, 'store'])->name('evaluaciones.store');
    Route::get('/evaluaciones/{equipo}/editar', [\App\Http\Controllers\Juez\EvaluacionController::class, 'editar'])->name('evaluaciones.editar');
    Route::put('/evaluaciones/{equipo}', [\App\Http\Controllers\Juez\EvaluacionController::class, 'update'])->name('evaluaciones.update');

    // Mis evaluaciones
    Route::get('/mis-evaluaciones', [\App\Http\Controllers\Juez\EvaluacionController::class, 'misEvaluaciones'])->name('mis-evaluaciones');
});

// Rutas para Estudiantes
Route::middleware(['auth', 'estudiante'])->prefix('dashboard')->name('estudiante.')->group(function() {
    Route::get('/', [EstudianteDashboardController::class, 'index'])->name('dashboard');

    // Rutas de equipos para estudiantes
    Route::get('/equipos', [\App\Http\Controllers\Estudiante\EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/equipos/crear', [\App\Http\Controllers\Estudiante\EquipoController::class, 'create'])->name('equipos.create');
    Route::post('/equipos', [\App\Http\Controllers\Estudiante\EquipoController::class, 'store'])->name('equipos.store');
    Route::post('/equipos/unirse-codigo', [\App\Http\Controllers\Estudiante\EquipoController::class, 'unirseCodigo'])->name('equipos.unirse-codigo');
    Route::get('/equipos/{equipo}', [\App\Http\Controllers\Estudiante\EquipoController::class, 'show'])->name('equipos.show');
    Route::post('/equipos/{equipo}/unirse', [\App\Http\Controllers\Estudiante\EquipoController::class, 'unirse'])->name('equipos.unirse');
    Route::delete('/equipos/{equipo}/salir', [\App\Http\Controllers\Estudiante\EquipoController::class, 'salir'])->name('equipos.salir');
    Route::delete('/equipos/{equipo}', [\App\Http\Controllers\Estudiante\EquipoController::class, 'destroy'])->name('equipos.destroy');
});

Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Rutas del proceso de registro extendido (requieren autenticación)
Route::middleware(['auth'])->group(function() {
    Route::get('/registro/datos-estudiante', [RegistroController::class, 'mostrarDatosEstudiante'])->name('registro.datos-estudiante');
    Route::post('/registro/datos-estudiante', [RegistroController::class, 'guardarDatosEstudiante'])->name('registro.datos-estudiante.store');
    Route::get('/registro/equipos', [RegistroController::class, 'mostrarEquipos'])->name('registro.equipos');
    Route::post('/registro/equipos/unirse', [RegistroController::class, 'unirseEquipo'])->name('registro.equipos.unirse');
    Route::post('/registro/equipos/crear', [RegistroController::class, 'crearEquipoRegistro'])->name('registro.equipos.crear');
});