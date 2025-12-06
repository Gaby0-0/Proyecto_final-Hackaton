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
        Schema::create('jueces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre_completo');
            $table->string('especialidad');
            $table->string('cedula_profesional')->nullable();
            $table->string('institucion')->nullable();
            $table->text('experiencia')->nullable();
            $table->string('telefono')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('informacion_completa')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jueces');
    }
};
