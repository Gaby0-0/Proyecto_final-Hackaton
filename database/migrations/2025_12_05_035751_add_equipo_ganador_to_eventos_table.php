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
            $table->foreignId('equipo_ganador_id')->nullable()->after('max_equipos')->constrained('equipos')->onDelete('set null');
            $table->timestamp('fecha_seleccion_ganador')->nullable()->after('equipo_ganador_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['equipo_ganador_id']);
            $table->dropColumn(['equipo_ganador_id', 'fecha_seleccion_ganador']);
        });
    }
};
