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
        Schema::create('equipo_participante', function (Blueprint $table) {
        $table->id();
        $table->foreignId('equipo_id')->constrained('equipos');
        $table->foreignId('participante_id')->constrained('usuarios');
        $table->foreignId('perfil_id')->constrained('perfiles');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_participante');
    }
};
