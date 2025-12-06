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
        'role',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
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

    // Relación con datos de juez
    public function datosJuez()
    {
        return $this->hasOne(Juez::class);
    }

    // Relación con datos de administrador
    public function datosAdministrador()
    {
        return $this->hasOne(Administrador::class);
    }

    // Relación muchos a muchos con equipos
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipo_user')
                    ->withPivot('rol_equipo', 'rol_especifico')
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

    // Alias para evaluaciones (más semántico para jueces)
    public function evaluacionesRealizadas()
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

    // Eventos asignados (para jueces)
    public function eventosAsignados()
    {
        return $this->belongsToMany(Evento::class, 'evento_juez', 'juez_id', 'evento_id')
                    ->withPivot('estado', 'fecha_asignacion')
                    ->withTimestamps();
    }

    // Verificar si el usuario tiene información completa según su rol
    public function tieneInformacionCompleta()
    {
        switch ($this->role) {
            case 'juez':
                return $this->datosJuez && $this->datosJuez->tieneInformacionCompleta();
            case 'estudiante':
                return $this->datosEstudiante && $this->datosEstudiante->datos_completos;
            case 'admin':
                return true; // Los admins siempre tienen información completa por defecto
            default:
                return false;
        }
    }

    // Verificar si el usuario está activo
    public function estaActivo()
    {
        switch ($this->role) {
            case 'juez':
                return $this->datosJuez && $this->datosJuez->estaActivo();
            case 'estudiante':
                return true; // Los estudiantes están siempre activos
            case 'admin':
                return $this->datosAdministrador && $this->datosAdministrador->estaActivo();
            default:
                return false;
        }
    }

    // Obtener el nombre completo desde la tabla específica
    public function getNombreCompletoAttribute()
    {
        switch ($this->role) {
            case 'juez':
                return $this->datosJuez->nombre_completo ?? $this->name;
            case 'estudiante':
                return $this->datosEstudiante->nombre_completo ?? $this->name;
            case 'admin':
                return $this->datosAdministrador->nombre_completo ?? $this->name;
            default:
                return $this->name;
        }
    }

    // Obtener los datos específicos según el rol
    public function obtenerDatosEspecificos()
    {
        switch ($this->role) {
            case 'juez':
                return $this->datosJuez;
            case 'estudiante':
                return $this->datosEstudiante;
            case 'admin':
                return $this->datosAdministrador;
            default:
                return null;
        }
    }

    // Relación con constancias
    public function constancias()
    {
        return $this->hasMany(Constancia::class);
    }

    // Obtener constancias de ganador
    public function constanciasGanador()
    {
        return $this->hasMany(Constancia::class)->where('tipo', 'ganador');
    }

    // Obtener constancias de participante
    public function constanciasParticipante()
    {
        return $this->hasMany(Constancia::class)->where('tipo', 'participante');
    }
}