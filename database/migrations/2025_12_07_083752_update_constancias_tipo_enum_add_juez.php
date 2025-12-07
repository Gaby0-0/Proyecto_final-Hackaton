<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero, actualizar las constancias existentes de 'participante' a NULL temporalmente
        // para evitar conflictos al cambiar el ENUM
        DB::table('constancias')
            ->where('tipo', 'participante')
            ->update(['tipo' => 'ganador']); // Temporalmente convertir participantes a ganadores

        // Modificar el ENUM para incluir solo 'ganador' y 'juez'
        DB::statement("ALTER TABLE constancias MODIFY COLUMN tipo ENUM('ganador', 'juez') DEFAULT 'ganador'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Volver al ENUM anterior
        DB::statement("ALTER TABLE constancias MODIFY COLUMN tipo ENUM('ganador', 'participante') DEFAULT 'participante'");

        // Convertir jueces de vuelta a participantes
        DB::table('constancias')
            ->where('tipo', 'juez')
            ->update(['tipo' => 'participante']);
    }
};
