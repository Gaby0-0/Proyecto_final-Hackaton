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
        Schema::create('juez_equipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('juez_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->enum('estado', ['asignado', 'evaluado', 'pendiente'])->default('asignado');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamps();

            // Asegurarse de que un juez no se asigne al mismo equipo dos veces
            $table->unique(['juez_id', 'equipo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juez_equipo');
    }
};
