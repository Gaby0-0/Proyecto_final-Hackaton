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
        Schema::table('constancias', function (Blueprint $table) {
            // Agregar campo para especificar el lugar (1, 2, 3 o null)
            $table->tinyInteger('lugar')->nullable()->after('tipo')->comment('1 = Primer lugar, 2 = Segundo lugar, 3 = Tercer lugar, null = Participante o Juez');

            // Agregar campo para el nombre del proyecto ganador
            $table->string('proyecto_nombre')->nullable()->after('lugar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('constancias', function (Blueprint $table) {
            $table->dropColumn(['lugar', 'proyecto_nombre']);
        });
    }
};
