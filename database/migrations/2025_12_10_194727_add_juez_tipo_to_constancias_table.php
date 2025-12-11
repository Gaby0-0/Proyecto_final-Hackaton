<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el ENUM para incluir 'juez'
        DB::statement("ALTER TABLE constancias MODIFY COLUMN tipo ENUM('ganador', 'participante', 'juez') NOT NULL DEFAULT 'participante'");

        // Agregar columna 'lugar' solo si no existe
        if (! Schema::hasColumn('constancias', 'lugar')) {
            Schema::table('constancias', function (Blueprint $table) {
                $table->integer('lugar')->nullable()->after('tipo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar columna lugar
        Schema::table('constancias', function (Blueprint $table) {
            $table->dropColumn('lugar');
        });

        // Revertir ENUM a valores originales
        DB::statement("ALTER TABLE constancias MODIFY COLUMN tipo ENUM('ganador', 'participante') NOT NULL DEFAULT 'participante'");
    }
};
