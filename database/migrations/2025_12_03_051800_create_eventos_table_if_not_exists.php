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
        if (!Schema::hasTable('eventos')) {
            Schema::create('eventos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->text('descripcion')->nullable();
                $table->date('fecha')->nullable();
                $table->datetime('fecha_inicio');
                $table->datetime('fecha_fin');
                $table->string('estado')->default('activo');
                $table->string('tipo')->nullable();
                $table->integer('max_equipos')->default(50);
                $table->string('modalidad')->default('presencial');
                $table->timestamps();
            });
        } else {
            // Agregar columnas faltantes si la tabla ya existe
            Schema::table('eventos', function (Blueprint $table) {
                if (!Schema::hasColumn('eventos', 'fecha_inicio')) {
                    $table->datetime('fecha_inicio')->nullable();
                }
                if (!Schema::hasColumn('eventos', 'fecha_fin')) {
                    $table->datetime('fecha_fin')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No eliminamos la tabla en el down
    }
};
