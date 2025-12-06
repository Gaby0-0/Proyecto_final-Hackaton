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
        Schema::table('equipo_evento', function (Blueprint $table) {
            $table->string('proyecto_titulo')->nullable()->after('fecha_inscripcion');
            $table->text('proyecto_descripcion')->nullable()->after('proyecto_titulo');
            $table->json('avances')->nullable()->after('proyecto_descripcion');
            $table->string('proyecto_final_url')->nullable()->after('avances');
            $table->timestamp('fecha_entrega_final')->nullable()->after('proyecto_final_url');
            $table->text('notas_equipo')->nullable()->after('fecha_entrega_final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipo_evento', function (Blueprint $table) {
            $table->dropColumn([
                'proyecto_titulo',
                'proyecto_descripcion',
                'avances',
                'proyecto_final_url',
                'fecha_entrega_final',
                'notas_equipo'
            ]);
        });
    }
};
