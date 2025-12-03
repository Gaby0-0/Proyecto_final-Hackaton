<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configuracion_global', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->string('valor');
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        // Insertar configuraciones por defecto
        DB::table('configuracion_global')->insert([
            [
                'clave' => 'max_equipos_por_estudiante',
                'valor' => '3',
                'descripcion' => 'Máximo de equipos que puede crear o unirse un estudiante',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'clave' => 'min_integrantes_equipo',
                'valor' => '1',
                'descripcion' => 'Mínimo de integrantes para crear un equipo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_global');
    }
};
