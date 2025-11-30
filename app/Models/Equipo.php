<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function participantes()
    {
        return $this->hasMany(Participante::class);
    }

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'evento_equipo');
    }
}