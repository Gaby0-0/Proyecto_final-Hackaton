<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuariosDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'name' => 'Admin Usuario',
            'email' => 'admin@concursito.com',
            'password' => Hash::make('12345678'),
            'admin' => 1,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Usuario Juez
        User::create([
            'name' => 'Juez Usuario',
            'email' => 'juez@concursito.com',
            'password' => Hash::make('12345678'),
            'admin' => 0,
            'role' => 'juez',
            'email_verified_at' => now(),
        ]);

        // Usuario Estudiante
        User::create([
            'name' => 'Alumno Usuario',
            'email' => 'alumno@concursito.com',
            'password' => Hash::make('12345678'),
            'admin' => 0,
            'role' => 'estudiante',
            'email_verified_at' => now(),
        ]);

        $this->command->info(' 3 usuarios creados exitosamente:');
        $this->command->info('   Admin: admin@concursito.com / 12345678');
        $this->command->info('   Juez: juez@concursito.com / 12345678');
        $this->command->info('   Alumno: alumno@concursito.com / 12345678');
    }
}
