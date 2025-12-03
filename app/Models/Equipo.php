<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'proyecto_id',
        'descripcion',
        'max_integrantes',
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

    // Relación muchos a muchos con jueces asignados
    public function jueces()
    {
        return $this->belongsToMany(User::class, 'juez_equipo', 'equipo_id', 'juez_id')
                    ->withPivot('estado', 'fecha_asignacion')
                    ->withTimestamps();
    }

    // Verificar si el equipo está lleno
    public function estaLleno()
    {
        return $this->miembros()->count() >= $this->max_integrantes;
    }

    // Verificar si un usuario puede unirse
    public function puedeUnirse(User $user)
    {
        // Verificar si ya es miembro
        if ($this->miembros()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Verificar si el equipo está lleno
        return !$this->estaLleno();
    }

    // Generar código único para el equipo
    public static function generarCodigoUnico()
    {
        do {
            $codigo = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (self::where('codigo', $codigo)->exists());

        return $codigo;
    }

    // Boot method para generar código automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($equipo) {
            if (empty($equipo->codigo)) {
                $equipo->codigo = self::generarCodigoUnico();
            }
        });
    }
}