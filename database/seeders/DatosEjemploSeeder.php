<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Evaluacion;
use App\Models\Evento;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatosEjemploSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Proyectos
        $proyecto1 = Proyecto::create([
            'nombre' => 'Sistema de Gestión Escolar',
            'fecha_inicio' => now()->subDays(30),
            'fecha_fin' => now()->addDays(30),
        ]);

        $proyecto2 = Proyecto::create([
            'nombre' => 'App de Delivery',
            'fecha_inicio' => now()->subDays(15),
            'fecha_fin' => now()->addDays(45),
        ]);

        $proyecto3 = Proyecto::create([
            'nombre' => 'Plataforma de E-learning',
            'fecha_inicio' => now()->subDays(10),
            'fecha_fin' => now()->addDays(50),
        ]);

        // Crear Eventos
        $evento1 = Evento::create([
            'nombre' => 'Hackathon 2025 - Primavera',
            'fecha' => now()->addDays(15),
        ]);

        $evento2 = Evento::create([
            'nombre' => 'Concurso de Innovación Tecnológica',
            'fecha' => now()->addDays(30),
        ]);

        $evento3 = Evento::create([
            'nombre' => 'Demo Day Emprendimiento',
            'fecha' => now()->subDays(5),
        ]);

        // Crear Equipos
        $equipo1 = Equipo::create([
            'nombre' => 'Los Innovadores',
            'proyecto_id' => $proyecto1->id,
        ]);

        $equipo2 = Equipo::create([
            'nombre' => 'CodeMasters',
            'proyecto_id' => $proyecto2->id,
        ]);

        $equipo3 = Equipo::create([
            'nombre' => 'Tech Warriors',
            'proyecto_id' => $proyecto3->id,
        ]);

        $equipo4 = Equipo::create([
            'nombre' => 'ByteBuilders',
            'proyecto_id' => null,
        ]);

        $equipo5 = Equipo::create([
            'nombre' => 'DevDynamos',
            'proyecto_id' => null,
        ]);

        // Crear Evaluaciones (asignar al juez si existe)
        $juez = User::where('role', 'juez')->first();

        if ($juez) {
            Evaluacion::create([
                'evento_id' => $evento1->id,
                'equipo_id' => $equipo1->id,
                'evaluador_id' => $juez->id,
                'puntuacion' => 85.50,
                'comentarios' => 'Excelente proyecto, buena implementación técnica.',
                'estado' => 'completada',
            ]);

            Evaluacion::create([
                'evento_id' => $evento1->id,
                'equipo_id' => $equipo2->id,
                'evaluador_id' => $juez->id,
                'puntuacion' => 92.00,
                'comentarios' => 'Innovador y bien ejecutado. Gran potencial comercial.',
                'estado' => 'completada',
            ]);

            Evaluacion::create([
                'evento_id' => $evento2->id,
                'equipo_id' => $equipo3->id,
                'evaluador_id' => $juez->id,
                'puntuacion' => null,
                'comentarios' => null,
                'estado' => 'pendiente',
            ]);

            Evaluacion::create([
                'evento_id' => $evento2->id,
                'equipo_id' => $equipo4->id,
                'evaluador_id' => $juez->id,
                'puntuacion' => null,
                'comentarios' => null,
                'estado' => 'pendiente',
            ]);
        }

        $this->command->info('✅ Datos de ejemplo creados:');
        $this->command->info('   - 3 Proyectos');
        $this->command->info('   - 3 Eventos');
        $this->command->info('   - 5 Equipos');
        $this->command->info('   - 4 Evaluaciones (2 completadas, 2 pendientes)');
    }
}
