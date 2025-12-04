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
        Schema::table('equipo_evento', function (Blueprint $table) {
            // Modificar el enum para incluir 'pendiente'
            $table->dropColumn('estado');
        });

        Schema::table('equipo_evento', function (Blueprint $table) {
            $table->enum('estado', ['pendiente', 'inscrito', 'participando', 'finalizado'])->default('pendiente')->after('evento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipo_evento', function (Blueprint $table) {
            $table->dropColumn('estado');
        });

        Schema::table('equipo_evento', function (Blueprint $table) {
            $table->enum('estado', ['inscrito', 'participando', 'finalizado'])->default('inscrito')->after('evento_id');
        });
    }
};
