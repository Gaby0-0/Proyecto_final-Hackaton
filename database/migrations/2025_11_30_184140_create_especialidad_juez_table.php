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
        Schema::create('especialidad_juez', function (Blueprint $table) {
        $table->foreignId('especialidad_id')->constrained('especialidades')->onDelete('cascade');
        $table->foreignId('juez_id')->constrained('jueces')->onDelete('cascade');
        $table->primary(['especialidad_id', 'juez_id']);    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especialidad_juez');
    }
};
