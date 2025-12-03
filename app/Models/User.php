<?php

// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'admin',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

public function usuario(): HasOne
{
    return $this->hasOne(\App\Models\Usuario::class);
}

    // Relación con datos de estudiante
    public function datosEstudiante()
    {
        return $this->hasOne(DatosEstudiante::class);
    }

    // Relación muchos a muchos con equipos
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipo_user')
                    ->withPivot('rol_equipo')
                    ->withTimestamps();
    }

    // Equipos donde es líder
    public function equiposComoLider()
    {
        return $this->belongsToMany(Equipo::class, 'equipo_user')
                    ->wherePivot('rol_equipo', 'lider')
                    ->withTimestamps();
    }

    // Evaluaciones que ha realizado (para jueces)
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'evaluador_id');
    }

    // Equipos asignados (para jueces)
    public function equiposAsignados()
    {
        return $this->belongsToMany(Equipo::class, 'juez_equipo', 'juez_id', 'equipo_id')
                    ->withPivot('estado', 'fecha_asignacion')
                    ->withTimestamps();
    }

}