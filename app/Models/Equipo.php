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
                    ->withPivot('rol_equipo', 'rol_especifico')
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
                    ->withPivot([
                        'estado',
                        'fecha_inscripcion',
                        'proyecto_titulo',
                        'proyecto_descripcion',
                        'avances',
                        'proyecto_final_url',
                        'fecha_entrega_final',
                        'notas_equipo'
                    ])
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

    // Verificar si el equipo puede inscribirse a un evento según la categoría
    public function puedeInscribirseAEvento(Evento $evento)
    {
        // Si el evento no tiene categoría, permitir inscripción
        if (!$evento->categoria) {
            return true;
        }

        // Obtener eventos a los que el equipo ya está inscrito o pendiente
        $eventosInscritos = $this->eventos()
            ->whereIn('equipo_evento.estado', ['pendiente', 'inscrito', 'participando'])
            ->get();

        // Verificar si ya está inscrito en un evento de la misma categoría
        foreach ($eventosInscritos as $eventoInscrito) {
            if ($eventoInscrito->categoria === $evento->categoria) {
                return false; // Ya está inscrito en un evento de esta categoría
            }
        }

        return true;
    }

    // Verificar si el usuario actual es líder del equipo
    public function usuarioEsLider($userId)
    {
        return $this->miembros()
            ->where('user_id', $userId)
            ->wherePivot('rol_equipo', 'lider')
            ->exists();
    }

    // Verificar si el equipo ya está inscrito o tiene solicitud pendiente en un evento
    public function estaInscritoEnEvento($eventoId)
    {
        return $this->eventos()
            ->where('evento_id', $eventoId)
            ->whereIn('equipo_evento.estado', ['pendiente', 'inscrito', 'participando'])
            ->exists();
    }

    // Verificar si el equipo tiene proyecto subido para un evento
    public function tieneProyectoEnEvento($eventoId)
    {
        $evento = $this->eventos()
            ->where('evento_id', $eventoId)
            ->withPivot('proyecto_titulo', 'proyecto_descripcion')
            ->first();

        if (!$evento) {
            return false;
        }

        return !empty($evento->pivot->proyecto_titulo) && !empty($evento->pivot->proyecto_descripcion);
    }

    // Obtener el proyecto de un evento específico
    public function obtenerProyectoEvento($eventoId)
    {
        return $this->eventos()
            ->where('evento_id', $eventoId)
            ->withPivot([
                'proyecto_titulo',
                'proyecto_descripcion',
                'avances',
                'proyecto_final_url',
                'fecha_entrega_final',
                'notas_equipo'
            ])
            ->first();
    }

    // Relación con constancias
    public function constancias()
    {
        return $this->hasMany(Constancia::class);
    }
}