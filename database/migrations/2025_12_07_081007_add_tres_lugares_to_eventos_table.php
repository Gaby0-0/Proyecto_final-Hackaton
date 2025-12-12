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
        Schema::table('eventos', function (Blueprint $table) {
            // Renombrar equipo_ganador_id a equipo_primer_lugar_id
            $table->renameColumn('equipo_ganador_id', 'equipo_primer_lugar_id');

            // Agregar campos para segundo y tercer lugar
            $table->foreignId('equipo_segundo_lugar_id')->nullable()->after('equipo_primer_lugar_id')->constrained('equipos')->onDelete('set null');
            $table->foreignId('equipo_tercer_lugar_id')->nullable()->after('equipo_segundo_lugar_id')->constrained('equipos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Eliminar campos de segundo y tercer lugar
            $table->dropForeign(['equipo_segundo_lugar_id']);
            $table->dropForeign(['equipo_tercer_lugar_id']);
            $table->dropColumn(['equipo_segundo_lugar_id', 'equipo_tercer_lugar_id']);

            // Renombrar de vuelta a equipo_ganador_id
            $table->renameColumn('equipo_primer_lugar_id', 'equipo_ganador_id');
        });
    }
};
