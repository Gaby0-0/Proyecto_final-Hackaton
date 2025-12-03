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
        Schema::table('equipos', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('nombre');
            $table->integer('max_integrantes')->default(5)->after('proyecto_id');
            $table->boolean('activo')->default(true)->after('max_integrantes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'max_integrantes', 'activo']);
        });
    }
};
