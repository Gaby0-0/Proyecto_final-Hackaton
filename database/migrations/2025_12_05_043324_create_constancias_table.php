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
        Schema::create('constancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->enum('tipo', ['ganador', 'participante'])->default('participante');
            $table->string('numero_folio')->unique();
            $table->string('archivo_url')->nullable();
            $table->timestamp('fecha_emision')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('descargada')->default(false);
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index(['user_id', 'evento_id']);
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constancias');
    }
};
