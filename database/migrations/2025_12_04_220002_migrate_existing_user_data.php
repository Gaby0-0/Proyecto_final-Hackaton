<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar datos de jueces existentes
        $jueces = DB::table('users')
            ->where('role', 'juez')
            ->get();

        foreach ($jueces as $juez) {
            // Solo insertar si no existe ya un registro
            $exists = DB::table('jueces')->where('user_id', $juez->id)->exists();

            if (!$exists) {
                DB::table('jueces')->insert([
                    'user_id' => $juez->id,
                    'nombre_completo' => $juez->nombre_completo ?? $juez->name,
                    'especialidad' => $juez->especialidad ?? '',
                    'activo' => $juez->activo ?? true,
                    'informacion_completa' => $juez->informacion_completa ?? false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Migrar datos de administradores existentes
        $administradores = DB::table('users')
            ->where('role', 'admin')
            ->get();

        foreach ($administradores as $admin) {
            // Solo insertar si no existe ya un registro
            $exists = DB::table('administradores')->where('user_id', $admin->id)->exists();

            if (!$exists) {
                DB::table('administradores')->insert([
                    'user_id' => $admin->id,
                    'nombre_completo' => $admin->nombre_completo ?? $admin->name,
                    'activo' => $admin->activo ?? true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // En caso de rollback, eliminar los datos migrados
        DB::table('jueces')->truncate();
        DB::table('administradores')->truncate();
    }
};
