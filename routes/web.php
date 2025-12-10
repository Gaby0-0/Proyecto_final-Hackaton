<?php

use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\ConstanciaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EquipoController;
use App\Http\Controllers\Admin\EvaluacionController;
use App\Http\Controllers\Admin\EventoController;
use App\Http\Controllers\Admin\InformeController;
use App\Http\Controllers\Admin\JuezController;
use App\Http\Controllers\Admin\NotificacionController;
use App\Http\Controllers\Admin\ProyectoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\Estudiante\DashboardController as EstudianteDashboardController;
use App\Http\Controllers\Juez\DashboardController as JuezDashboardController;
use App\Http\Controllers\Juez\PerfilController as JuezPerfilController;
use Illuminate\Support\Facades\Route;

// Rutas públicas o de usuarios normales
Route::get('/', function () {
    return view('welcome');
});

// Grupo de rutas de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Gestión de usuarios
    Route::resource('usuarios', UsuarioController::class);

    // Gestión de equipos
    Route::resource('equipos', EquipoController::class);

    // Gestión de eventos
    Route::resource('eventos', EventoController::class);
    Route::get('eventos/{evento}/solicitudes', [EventoController::class, 'solicitudes'])->name('eventos.solicitudes');
    Route::post('eventos/{evento}/aprobar-solicitud/{equipo}', [EventoController::class, 'aprobarSolicitud'])->name('eventos.aprobar-solicitud');
    Route::post('eventos/{evento}/rechazar-solicitud/{equipo}', [EventoController::class, 'rechazarSolicitud'])->name('eventos.rechazar-solicitud');
    Route::get('eventos/{evento}/asignar-jueces', [EventoController::class, 'asignarJueces'])->name('eventos.asignar-jueces');
    Route::post('eventos/{evento}/agregar-juez', [EventoController::class, 'agregarJuez'])->name('eventos.agregar-juez');
    Route::delete('eventos/{evento}/quitar-juez/{juez}', [EventoController::class, 'quitarJuez'])->name('eventos.quitar-juez');
    Route::post('eventos/{evento}/asignar-jueces-auto', [EventoController::class, 'asignarJuecesAuto'])->name('eventos.asignar-jueces-auto');
    Route::get('eventos/{evento}/seleccionar-ganador', [EventoController::class, 'seleccionarGanador'])->name('eventos.seleccionar-ganador');
    Route::post('eventos/{evento}/establecer-ganador', [EventoController::class, 'establecerGanador'])->name('eventos.establecer-ganador');
    Route::post('eventos/{evento}/establecer-ganador-auto', [EventoController::class, 'establecerGanadorAutomatico'])->name('eventos.establecer-ganador-auto');
    Route::delete('eventos/{evento}/quitar-ganador', [EventoController::class, 'quitarGanador'])->name('eventos.quitar-ganador');
    Route::get('eventos/{evento}/proyectos', [EventoController::class, 'verProyectos'])->name('eventos.proyectos');
    Route::get('eventos/{evento}/proyectos/{equipo}', [EventoController::class, 'verProyectoDetalle'])->name('eventos.proyecto-detalle');

    // Proyectos
    Route::resource('proyectos', ProyectoController::class);

    // Evaluaciones
    Route::get('evaluaciones/evento/{evento}', [EvaluacionController::class, 'porEvento'])->name('evaluaciones.evento');
    Route::get('evaluaciones/equipo/{equipo}', [EvaluacionController::class, 'porEquipo'])->name('evaluaciones.equipo');
    Route::resource('evaluaciones', EvaluacionController::class);

    // Constancias - specific routes must come before resource routes
    Route::get('constancias/evento/{evento}', [ConstanciaController::class, 'porEvento'])->name('constancias.evento');
    Route::get('constancias/usuario/{usuario}', [ConstanciaController::class, 'porUsuario'])->name('constancias.usuario');
    Route::post('constancias/generar-evento/{evento}', [ConstanciaController::class, 'generarPorEvento'])->name('constancias.generar-evento');
    Route::post('constancias/{constancia}/regenerar', [ConstanciaController::class, 'regenerar'])->name('constancias.regenerar');
    Route::resource('constancias', ConstanciaController::class)->only(['index', 'show', 'destroy']);

    // Informes
    Route::get('informes', [InformeController::class, 'index'])->name('informes.index');
    Route::get('informes/eventos', [InformeController::class, 'eventos'])->name('informes.eventos');
    Route::get('informes/equipos', [InformeController::class, 'equipos'])->name('informes.equipos');
    Route::get('informes/evaluaciones', [InformeController::class, 'evaluaciones'])->name('informes.evaluaciones');
    Route::get('informes/constancias', [InformeController::class, 'constancias'])->name('informes.constancias');
    Route::get('informes/participacion', [InformeController::class, 'participacion'])->name('informes.participacion');
    Route::post('informes/enviar-email', [InformeController::class, 'enviarInformeEmail'])->name('informes.enviar-email');

    // Configuración
    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::put('configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');

    // Gestión de jueces
    Route::resource('jueces', JuezController::class);

    // Asignación de jueces
    Route::get('jueces-asignar', [JuezController::class, 'asignar'])->name('jueces.asignar');
    Route::post('jueces-asignar-aleatorio', [JuezController::class, 'asignarAleatorio'])->name('jueces.asignar-aleatorio');
    Route::post('jueces-asignar-manual', [JuezController::class, 'asignarManual'])->name('jueces.asignar-manual');
    Route::post('jueces-desasignar', [JuezController::class, 'desasignar'])->name('jueces.desasignar');

    // Perfil de administrador
    Route::get('perfil', [\App\Http\Controllers\Admin\PerfilController::class, 'index'])->name('perfil.index');
    Route::get('perfil/editar', [\App\Http\Controllers\Admin\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('perfil', [\App\Http\Controllers\Admin\PerfilController::class, 'update'])->name('perfil.update');
    Route::post('perfil/cambiar-password', [\App\Http\Controllers\Admin\PerfilController::class, 'cambiarPassword'])->name('perfil.cambiar-password');

    // Notificaciones
    Route::get('notificaciones/{notificacion}/leer', [NotificacionController::class, 'marcarComoLeida'])->name('notificaciones.marcar-leida');
    Route::post('notificaciones/marcar-todas-leidas', [NotificacionController::class, 'marcarTodasComoLeidas'])->name('notificaciones.marcar-todas-leidas');
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
Route::middleware(['auth', 'juez'])->prefix('juez')->name('juez.')->group(function () {
    Route::get('/', [JuezDashboardController::class, 'index'])->name('dashboard');

    // Rutas de perfil
    Route::get('/perfil/completar', [JuezPerfilController::class, 'completar'])->name('perfil.completar');
    Route::post('/perfil/guardar', [JuezPerfilController::class, 'guardar'])->name('perfil.guardar');
    Route::get('/perfil', [JuezPerfilController::class, 'mostrar'])->name('perfil.mostrar');
    Route::get('/perfil/editar', [JuezPerfilController::class, 'editar'])->name('perfil.editar');
    Route::put('/perfil', [JuezPerfilController::class, 'actualizar'])->name('perfil.actualizar');

    // Rutas de evaluaciones
    Route::get('/evaluaciones', [\App\Http\Controllers\Juez\EvaluacionController::class, 'index'])->name('evaluaciones.index');
    Route::get('/evaluaciones/evento/{evento}', [\App\Http\Controllers\Juez\EvaluacionController::class, 'verEvento'])->name('evaluaciones.evento');
    Route::get('/evaluaciones/evento/{evento}/equipo/{equipo}/crear', [\App\Http\Controllers\Juez\EvaluacionController::class, 'crear'])->name('evaluaciones.crear');
    Route::post('/evaluaciones/evento/{evento}/equipo/{equipo}', [\App\Http\Controllers\Juez\EvaluacionController::class, 'store'])->name('evaluaciones.store');
    Route::get('/evaluaciones/evento/{evento}/equipo/{equipo}/editar', [\App\Http\Controllers\Juez\EvaluacionController::class, 'editar'])->name('evaluaciones.editar');
    Route::put('/evaluaciones/evento/{evento}/equipo/{equipo}', [\App\Http\Controllers\Juez\EvaluacionController::class, 'update'])->name('evaluaciones.update');

    // Mis evaluaciones
    Route::get('/mis-evaluaciones', [\App\Http\Controllers\Juez\EvaluacionController::class, 'misEvaluaciones'])->name('mis-evaluaciones');

    // Rutas de eventos para jueces
    Route::get('/eventos', [\App\Http\Controllers\Juez\EventoController::class, 'index'])->name('eventos.index');
    Route::get('/eventos/{evento}', [\App\Http\Controllers\Juez\EventoController::class, 'show'])->name('eventos.show');
    Route::get('/eventos/{evento}/proyectos/{equipo}', [\App\Http\Controllers\Juez\EventoController::class, 'verProyecto'])->name('eventos.ver-proyecto');

    // Rutas de constancias para jueces
    Route::get('/constancias', [\App\Http\Controllers\Juez\ConstanciaController::class, 'index'])->name('constancias.index');
    Route::get('/constancias/{constancia}', [\App\Http\Controllers\Juez\ConstanciaController::class, 'show'])->name('constancias.show');
});

// Rutas para Estudiantes
Route::middleware(['auth', 'estudiante'])->prefix('dashboard')->name('estudiante.')->group(function () {
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

    // Rutas de eventos para estudiantes
    Route::get('/eventos', [\App\Http\Controllers\Estudiante\EventoController::class, 'index'])->name('eventos.index');
    Route::get('/eventos/{evento}', [\App\Http\Controllers\Estudiante\EventoController::class, 'show'])->name('eventos.show');
    Route::post('/eventos/{evento}/inscribir', [\App\Http\Controllers\Estudiante\EventoController::class, 'inscribir'])->name('eventos.inscribir');
    Route::post('/eventos/{evento}/cancelar', [\App\Http\Controllers\Estudiante\EventoController::class, 'cancelarInscripcion'])->name('eventos.cancelar');

    // Rutas de proyectos para estudiantes
    Route::get('/proyectos', [\App\Http\Controllers\Estudiante\ProyectoController::class, 'index'])->name('proyectos.index');
    Route::get('/proyectos/{equipo}/{evento}', [\App\Http\Controllers\Estudiante\ProyectoController::class, 'edit'])->name('proyectos.edit');
    Route::put('/proyectos/{equipo}/{evento}/info', [\App\Http\Controllers\Estudiante\ProyectoController::class, 'updateInfo'])->name('proyectos.update-info');
    Route::post('/proyectos/{equipo}/{evento}/avances', [\App\Http\Controllers\Estudiante\ProyectoController::class, 'subirAvance'])->name('proyectos.subir-avance');
    Route::delete('/proyectos/{equipo}/{evento}/avances/{indice}', [\App\Http\Controllers\Estudiante\ProyectoController::class, 'eliminarAvance'])->name('proyectos.eliminar-avance');
    Route::post('/proyectos/{equipo}/{evento}/final', [\App\Http\Controllers\Estudiante\ProyectoController::class, 'subirProyectoFinal'])->name('proyectos.subir-final');
    Route::delete('/proyectos/{equipo}/{evento}/final', [\App\Http\Controllers\Estudiante\ProyectoController::class, 'eliminarProyectoFinal'])->name('proyectos.eliminar-final');

    // Rutas de evaluaciones para estudiantes
    Route::get('/evaluaciones', [\App\Http\Controllers\Estudiante\EvaluacionController::class, 'index'])->name('evaluaciones.index');
    Route::get('/evaluaciones/{equipo}/{evento}', [\App\Http\Controllers\Estudiante\EvaluacionController::class, 'ver'])->name('evaluaciones.ver');

    // Rutas de constancias para estudiantes
    Route::get('/constancias', [\App\Http\Controllers\Estudiante\ConstanciaController::class, 'index'])->name('constancias.index');
    Route::get('/constancias/{constancia}', [\App\Http\Controllers\Estudiante\ConstanciaController::class, 'show'])->name('constancias.show');
    Route::get('/constancias/{constancia}/descargar', [\App\Http\Controllers\Estudiante\ConstanciaController::class, 'descargar'])->name('constancias.descargar');

    // Perfil de estudiante
    Route::get('/perfil', [\App\Http\Controllers\Estudiante\PerfilController::class, 'index'])->name('perfil.index');
    Route::get('/perfil/editar', [\App\Http\Controllers\Estudiante\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [\App\Http\Controllers\Estudiante\PerfilController::class, 'update'])->name('perfil.update');
    Route::post('/perfil/cambiar-password', [\App\Http\Controllers\Estudiante\PerfilController::class, 'cambiarPassword'])->name('perfil.cambiar-password');
    Route::post('/perfil/equipo/{equipo}/rol', [\App\Http\Controllers\Estudiante\PerfilController::class, 'actualizarRolEquipo'])->name('perfil.actualizar-rol-equipo');
});

Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Rutas del proceso de registro extendido (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/registro/datos-estudiante', [RegistroController::class, 'mostrarDatosEstudiante'])->name('registro.datos-estudiante');
    Route::post('/registro/datos-estudiante', [RegistroController::class, 'guardarDatosEstudiante'])->name('registro.datos-estudiante.store');
    Route::get('/registro/equipos', [RegistroController::class, 'mostrarEquipos'])->name('registro.equipos');
    Route::post('/registro/equipos/unirse', [RegistroController::class, 'unirseEquipo'])->name('registro.equipos.unirse');
    Route::post('/registro/equipos/crear', [RegistroController::class, 'crearEquipoRegistro'])->name('registro.equipos.crear');
});
