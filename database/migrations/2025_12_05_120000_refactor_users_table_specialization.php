<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Esta migración especializa la tabla users, moviendo campos específicos
     * de cada tipo de usuario a sus respectivas tablas.
     */
    public function up(): void
    {
        // 1. Asegurar que las tablas específicas tengan todos los campos necesarios

        // Actualizar tabla jueces
        Schema::table('jueces', function (Blueprint $table) {
            if (!Schema::hasColumn('jueces', 'nombre_completo')) {
                $table->string('nombre_completo')->after('user_id');
            }
            if (!Schema::hasColumn('jueces', 'email_institucional')) {
                $table->string('email_institucional')->nullable()->after('telefono');
            }
        });

        // Actualizar tabla administradores
        Schema::table('administradores', function (Blueprint $table) {
            if (!Schema::hasColumn('administradores', 'nombre_completo')) {
                $table->string('nombre_completo')->after('user_id');
            }
            if (!Schema::hasColumn('administradores', 'cargo')) {
                $table->string('cargo')->nullable()->after('departamento');
            }
        });

        // Actualizar tabla datos_estudiante
        Schema::table('datos_estudiante', function (Blueprint $table) {
            if (!Schema::hasColumn('datos_estudiante', 'nombre_completo')) {
                $table->string('nombre_completo')->after('user_id');
            }
            if (!Schema::hasColumn('datos_estudiante', 'apellido_paterno')) {
                $table->string('apellido_paterno')->nullable()->after('nombre_completo');
            }
            if (!Schema::hasColumn('datos_estudiante', 'apellido_materno')) {
                $table->string('apellido_materno')->nullable()->after('apellido_paterno');
            }
        });

        // 2. Limpiar tabla users - eliminar campos específicos que ahora están en tablas especializadas
        Schema::table('users', function (Blueprint $table) {
            // Eliminar campos que ahora están en tablas específicas
            if (Schema::hasColumn('users', 'nombre_completo')) {
                $table->dropColumn('nombre_completo');
            }
            if (Schema::hasColumn('users', 'especialidad')) {
                $table->dropColumn('especialidad');
            }
            if (Schema::hasColumn('users', 'informacion_completa')) {
                $table->dropColumn('informacion_completa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los cambios
        Schema::table('users', function (Blueprint $table) {
            $table->string('nombre_completo')->nullable();
            $table->string('especialidad')->nullable();
            $table->boolean('informacion_completa')->default(false);
        });

        Schema::table('jueces', function (Blueprint $table) {
            if (Schema::hasColumn('jueces', 'email_institucional')) {
                $table->dropColumn('email_institucional');
            }
        });

        Schema::table('administradores', function (Blueprint $table) {
            if (Schema::hasColumn('administradores', 'cargo')) {
                $table->dropColumn('cargo');
            }
        });

        Schema::table('datos_estudiante', function (Blueprint $table) {
            if (Schema::hasColumn('datos_estudiante', 'nombre_completo')) {
                $table->dropColumn(['nombre_completo', 'apellido_paterno', 'apellido_materno']);
            }
        });
    }
};
