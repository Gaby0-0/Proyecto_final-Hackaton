<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosEstudiante extends Model
{
    protected $table = 'datos_estudiante';

    protected $fillable = [
        'user_id',
        'numero_control',
        'carrera',
        'semestre',
        'telefono',
        'fecha_nacimiento',
        'direccion',
        'datos_completos',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'datos_completos' => 'boolean',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
