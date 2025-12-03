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
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->decimal('criterio_innovacion', 5, 2)->nullable()->after('puntuacion');
            $table->decimal('criterio_funcionalidad', 5, 2)->nullable()->after('criterio_innovacion');
            $table->decimal('criterio_presentacion', 5, 2)->nullable()->after('criterio_funcionalidad');
            $table->decimal('criterio_impacto', 5, 2)->nullable()->after('criterio_presentacion');
            $table->decimal('criterio_tecnico', 5, 2)->nullable()->after('criterio_impacto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'criterio_innovacion',
                'criterio_funcionalidad',
                'criterio_presentacion',
                'criterio_impacto',
                'criterio_tecnico'
            ]);
        });
    }
};
