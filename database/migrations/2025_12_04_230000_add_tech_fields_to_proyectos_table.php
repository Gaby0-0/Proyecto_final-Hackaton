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
        if (! Schema::hasColumn('proyectos', 'tecnologias')) {
            Schema::table('proyectos', function (Blueprint $table) {
                $table->text('tecnologias')->nullable()->after('categoria');
                $table->text('requisitos')->nullable()->after('tecnologias');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn(['tecnologias', 'requisitos']);
        });
    }
};
