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
            // Agregar columnas si no existen
            if (!Schema::hasColumn('eventos', 'descripcion')) {
                $table->text('descripcion')->nullable();
            }
            if (!Schema::hasColumn('eventos', 'tipo')) {
                $table->string('tipo')->nullable();
            }
            if (!Schema::hasColumn('eventos', 'estado')) {
                $table->string('estado')->default('activo');
            }
            if (!Schema::hasColumn('eventos', 'max_equipos')) {
                $table->integer('max_equipos')->default(50);
            }
            if (!Schema::hasColumn('eventos', 'modalidad')) {
                $table->string('modalidad')->default('presencial');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            if (Schema::hasColumn('eventos', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
            if (Schema::hasColumn('eventos', 'tipo')) {
                $table->dropColumn('tipo');
            }
        });
    }
};
