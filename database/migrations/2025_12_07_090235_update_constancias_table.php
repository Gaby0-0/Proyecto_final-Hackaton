<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('constancias', function (Blueprint $table) {
            // Hacer equipo_id nullable
            $table->foreignId('equipo_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('constancias', function (Blueprint $table) {
            // Revertir a NOT NULL si haces rollback
            $table->foreignId('equipo_id')->nullable(false)->change();
        });
    }
};
