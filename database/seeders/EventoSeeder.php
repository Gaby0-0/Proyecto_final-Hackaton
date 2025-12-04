<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evento;
use Carbon\Carbon;

class EventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Evento 1: Hackathon Activo
        Evento::create([
            'nombre' => 'Hackathon Innovación 2025',
            'descripcion' => 'Competencia de desarrollo de soluciones innovadoras. Participa con tu equipo y crea proyectos que resuelvan problemas reales usando tecnología.',
            'fecha_inicio' => Carbon::now()->addDays(5),
            'fecha_fin' => Carbon::now()->addDays(7),
            'estado' => 'activo',
            'tipo' => 'hackathon',
            'max_equipos' => 30,
            'modalidad' => 'hibrida'
        ]);

        // Evento 2: Feria de Proyectos Programada
        Evento::create([
            'nombre' => 'Feria de Proyectos Tecnológicos',
            'descripcion' => 'Exhibición de proyectos innovadores desarrollados por estudiantes. Una oportunidad para mostrar tu trabajo y recibir retroalimentación de expertos.',
            'fecha_inicio' => Carbon::now()->addDays(30),
            'fecha_fin' => Carbon::now()->addDays(32),
            'estado' => 'programado',
            'tipo' => 'feria',
            'max_equipos' => 50,
            'modalidad' => 'presencial'
        ]);
    }
}
