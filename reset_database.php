<?php

/**
 * Script para limpiar la base de datos y crear usuarios de prueba
 * Ejecutar desde la raíz del proyecto con: php reset_database.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Juez;
use App\Models\Administrador;
use App\Models\DatosEstudiante;

echo "🗑️  Limpiando base de datos...\n\n";

// Limpiar tablas en orden correcto para evitar errores de foreign key
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Limpiar constancias
DB::table('constancias')->truncate();
echo "✅ Constancias eliminadas\n";

// Limpiar evaluaciones
DB::table('evaluaciones')->truncate();
echo "✅ Evaluaciones eliminadas\n";

// Limpiar relaciones de equipos
DB::table('equipo_user')->truncate();
DB::table('equipo_evento')->truncate();
echo "✅ Relaciones de equipos eliminadas\n";

// Limpiar jueces asignados a eventos
DB::table('evento_juez')->truncate();
echo "✅ Asignaciones de jueces eliminadas\n";

// Limpiar equipos
DB::table('equipos')->truncate();
echo "✅ Equipos eliminados\n";

// Limpiar proyectos
DB::table('proyectos')->truncate();
echo "✅ Proyectos eliminados\n";

// Limpiar eventos
DB::table('eventos')->truncate();
echo "✅ Eventos eliminados\n";

// Limpiar datos específicos de usuarios
DB::table('datos_estudiante')->truncate();
DB::table('jueces')->truncate();
DB::table('administradores')->truncate();
echo "✅ Datos específicos de usuarios eliminados\n";

// Limpiar usuarios
DB::table('users')->truncate();
echo "✅ Usuarios eliminados\n";

DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n👤 Creando usuarios de prueba...\n\n";

// Crear ADMINISTRADOR
$admin = User::create([
    'name' => 'Admin',
    'email' => 'admin@test.com',
    'password' => Hash::make('password'),
    'role' => 'admin',
    'activo' => true,
]);

Administrador::create([
    'user_id' => $admin->id,
    'nombre_completo' => 'Administrador del Sistema',
    'cargo' => 'Administrador General',
    'departamento' => 'Tecnologías de la Información',
    'telefono' => '9511234567',
    'activo' => true,
]);

echo "✅ Admin creado:\n";
echo "   Email: admin@test.com\n";
echo "   Password: password\n\n";

// Crear JUEZ
$juez = User::create([
    'name' => 'Dr. Juan Pérez',
    'email' => 'juez@test.com',
    'password' => Hash::make('password'),
    'role' => 'juez',
    'activo' => true,
]);

Juez::create([
    'user_id' => $juez->id,
    'nombre_completo' => 'Dr. Juan Pérez López',
    'especialidad' => 'Desarrollo de Software',
    'cedula_profesional' => '12345678',
    'institucion' => 'Instituto Tecnológico de Oaxaca',
    'experiencia' => '10 años en desarrollo de software',
    'telefono' => '9519876543',
    'email_institucional' => 'juan.perez@oaxaca.tecnm.mx',
    'activo' => true,
    'informacion_completa' => true,
]);

echo "✅ Juez creado:\n";
echo "   Email: juez@test.com\n";
echo "   Password: password\n\n";

// Crear ESTUDIANTE
$estudiante = User::create([
    'name' => 'María González',
    'email' => 'estudiante@test.com',
    'password' => Hash::make('password'),
    'role' => 'estudiante',
    'activo' => true,
]);

DatosEstudiante::create([
    'user_id' => $estudiante->id,
    'nombre_completo' => 'María González Ramírez',
    'apellido_paterno' => 'González',
    'apellido_materno' => 'Ramírez',
    'numero_control' => '20240001',
    'carrera' => 'Ingeniería en Sistemas Computacionales',
    'semestre' => 6,
    'telefono' => '9517654321',
    'datos_completos' => true,
]);

echo "✅ Estudiante creado:\n";
echo "   Email: estudiante@test.com\n";
echo "   Password: password\n\n";

echo "🎉 ¡Base de datos limpiada y usuarios de prueba creados!\n\n";
echo "📝 Resumen:\n";
echo "   - 1 Administrador\n";
echo "   - 1 Juez\n";
echo "   - 1 Estudiante\n";
echo "   - Todos con password: password\n\n";
