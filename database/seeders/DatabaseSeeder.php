<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Evaluacion;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Crear usuarios normales
        $users = User::factory(50)->create();

        // Crear equipos
        $equipos = [];
        $nombresEquipos = [
            'Alpha Developers',
            'Beta Coders',
            'Gamma Innovations',
            'Delta Force',
            'Epsilon Team'
        ];

        foreach ($nombresEquipos as $nombre) {
            $equipos[] = Equipo::create([
                'nombre' => $nombre,
                'descripcion' => 'Equipo de desarrollo ' . $nombre,
                'activo' => true
            ]);
        }

        // Crear eventos
        $eventos = [];
        
        $eventos[] = Evento::create([
            'nombre' => 'Hackatón 2025',
            'descripcion' => 'Competencia de programación intensiva de 48 horas',
            'fecha_inicio' => now()->subDays(2),
            'fecha_fin' => now()->addDays(1),
            'estado' => 'activo',
            'tipo' => 'hackaton'
        ]);

        $eventos[] = Evento::create([
            'nombre' => 'Desafío de código de primavera',
            'descripcion' => 'Retos de algoritmos y estructuras de datos',
            'fecha_inicio' => now()->addDays(10),
            'fecha_fin' => now()->addDays(12),
            'estado' => 'programado',
            'tipo' => 'desafio'
        ]);

        $eventos[] = Evento::create([
            'nombre' => 'Concurso de Innovación en IA',
            'descripcion' => 'Proyectos innovadores usando inteligencia artificial',
            'fecha_inicio' => now()->subDays(5),
            'fecha_fin' => now()->addDays(2),
            'estado' => 'activo',
            'tipo' => 'concurso'
        ]);

        // Asignar participantes a eventos
        foreach ($eventos as $evento) {
            $evento->participantes()->attach(
                $users->random(rand(30, 50))->pluck('id')
            );
        }

        // Asignar equipos a eventos
        foreach ($eventos as $evento) {
            $evento->equipos()->attach(
                collect($equipos)->random(rand(2, 4))->pluck('id')
            );
        }

        // Crear evaluaciones
        foreach ($eventos as $evento) {
            foreach ($evento->equipos as $equipo) {
                Evaluacion::create([
                    'evento_id' => $evento->id,
                    'equipo_id' => $equipo->id,
                    'evaluador_id' => $admin->id,
                    'puntuacion' => rand(70, 100),
                    'comentarios' => 'Excelente trabajo del equipo ' . $equipo->nombre,
                    'estado' => rand(0, 1) ? 'completada' : 'pendiente'
                ]);
            }
        }
    }
}