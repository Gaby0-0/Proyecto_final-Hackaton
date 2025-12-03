<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'tipo'
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    // Relación muchos a muchos con equipos
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipo_evento')
                    ->withPivot('estado', 'fecha_inscripcion')
                    ->withTimestamps();
    }

    // Relación con evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class);
    }
}