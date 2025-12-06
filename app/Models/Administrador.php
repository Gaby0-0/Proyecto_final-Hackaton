<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administradores';

    protected $fillable = [
        'user_id',
        'nombre_completo',
        'cargo',
        'departamento',
        'telefono',
        'extension',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Verificar si el administrador está activo
    public function estaActivo()
    {
        return $this->activo;
    }
}
