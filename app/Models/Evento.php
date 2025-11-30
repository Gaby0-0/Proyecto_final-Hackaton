<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'tipo'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function participantes()
    {
        return $this->belongsToMany(User::class, 'evento_participante');
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'evento_equipo');
    }
}