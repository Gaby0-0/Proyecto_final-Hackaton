<?php

namespace Database\Seeders;

use App\Models\DatosEstudiante;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosPorDefectoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Usuario Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@concursito.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'activo' => true,
            ]
        );

        $this->command->info('✓ Usuario Admin creado: admin@concursito.com / admin123');

        // 2. Usuario Estudiante
        $estudiante = User::firstOrCreate(
            ['email' => 'estudiante@concursito.com'],
            [
                'name' => 'Estudiante Demo',
                'password' => Hash::make('estudiante123'),
                'role' => 'estudiante',
                'activo' => true,
            ]
        );

        // Crear datos de estudiante
        DatosEstudiante::firstOrCreate(
            ['user_id' => $estudiante->id],
            [
                'numero_control' => 'C21001234',
                'carrera' => 'Ingeniería en Sistemas Computacionales',
                'semestre' => 7,
                'telefono' => '1234567890',
            ]
        );

        $this->command->info('✓ Usuario Estudiante creado: estudiante@concursito.com / estudiante123');

        // 3. Usuario Juez
        $juez = User::firstOrCreate(
            ['email' => 'juez@concursito.com'],
            [
                'name' => 'Juez Demo',
                'password' => Hash::make('juez123'),
                'role' => 'juez',
                'activo' => true,
            ]
        );

        $this->command->info('✓ Usuario Juez creado: juez@concursito.com / juez123');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('USUARIOS POR DEFECTO CREADOS:');
        $this->command->info('========================================');
        $this->command->info('Admin:       admin@concursito.com / admin123');
        $this->command->info('Estudiante:  estudiante@concursito.com / estudiante123');
        $this->command->info('Juez:        juez@concursito.com / juez123');
        $this->command->info('========================================');
    }
}
