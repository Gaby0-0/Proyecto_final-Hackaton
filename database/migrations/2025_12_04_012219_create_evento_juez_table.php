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
        Schema::create('evento_juez', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('juez_id')->constrained('users')->onDelete('cascade');
            $table->string('estado')->default('asignado'); // asignado, confirmado, rechazado
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamps();

            // Evitar duplicados
            $table->unique(['evento_id', 'juez_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_juez');
    }
};
