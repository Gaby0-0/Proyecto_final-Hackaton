<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar el seeder de usuarios por defecto
        $this->call([
            UsuariosPorDefectoSeeder::class,
        ]);
    }
}
