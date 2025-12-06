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
        Schema::table('equipo_user', function (Blueprint $table) {
            // Agregar campo para rol específico del integrante
            $table->string('rol_especifico')->nullable()->after('rol_equipo');
            // Ejemplos: 'Desarrollador Frontend', 'Desarrollador Backend', 'Diseñador UI/UX',
            // 'Scrum Master', 'Tester', 'DevOps', etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipo_user', function (Blueprint $table) {
            $table->dropColumn('rol_especifico');
        });
    }
};
