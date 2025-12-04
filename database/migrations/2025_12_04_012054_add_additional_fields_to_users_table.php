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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nombre_completo')->nullable()->after('name');
            $table->string('especialidad')->nullable()->after('nombre_completo');
            $table->boolean('activo')->default(true)->after('especialidad');
            $table->boolean('informacion_completa')->default(false)->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nombre_completo', 'especialidad', 'activo', 'informacion_completa']);
        });
    }
};
