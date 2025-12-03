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
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->nullable()->constrained('eventos')->onDelete('cascade');
            $table->foreignId('equipo_id')->nullable()->constrained('equipos')->onDelete('cascade');
            $table->foreignId('evaluador_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('puntuacion', 5, 2)->nullable();
            $table->text('comentarios')->nullable();
            $table->enum('estado', ['pendiente', 'completada', 'revisada'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
