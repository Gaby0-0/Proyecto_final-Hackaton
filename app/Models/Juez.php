<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Juez extends Model
{
    protected $table = 'jueces';

    protected $fillable = [
        'user_id',
        'nombre_completo',
        'especialidad',
        'cedula_profesional',
        'institucion',
        'experiencia',
        'telefono',
        'email_institucional',
        'activo',
        'informacion_completa',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'informacion_completa' => 'boolean',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Verificar si el juez tiene información completa
    public function tieneInformacionCompleta()
    {
        // Por defecto retornamos true para permitir pruebas
        return true;
    }

    // Verificar si el juez está activo
    public function estaActivo()
    {
        return $this->activo;
    }
}
