<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'nombre',
        'proyecto_id',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relación con proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Relación muchos a muchos con usuarios (miembros del equipo)
    public function miembros()
    {
        return $this->belongsToMany(User::class, 'equipo_user')
                    ->withPivot('rol_equipo')
                    ->withTimestamps();
    }

    // Obtener solo el líder del equipo
    public function lider()
    {
        return $this->belongsToMany(User::class, 'equipo_user')
                    ->wherePivot('rol_equipo', 'lider')
                    ->withTimestamps();
    }

    // Relación muchos a muchos con eventos (convocatorias)
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'equipo_evento')
                    ->withPivot('estado', 'fecha_inscripcion')
                    ->withTimestamps();
    }

    // Relación con evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class);
    }
}