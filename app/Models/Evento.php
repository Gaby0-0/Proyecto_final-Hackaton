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
        'tipo',
        'max_equipos',
        'modalidad'
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

    // Obtener equipos con solicitud pendiente
    public function equiposPendientes()
    {
        return $this->equipos()->wherePivot('estado', 'pendiente');
    }

    // Obtener equipos aprobados
    public function equiposAprobados()
    {
        return $this->equipos()->whereIn('equipo_evento.estado', ['inscrito', 'participando', 'finalizado']);
    }

    // Verificar si hay cupo disponible
    public function tieneCupoDisponible()
    {
        if (!$this->max_equipos) {
            return true; // Sin límite
        }
        return $this->equiposAprobados()->count() < $this->max_equipos;
    }

    // Verificar si puede aceptar más equipos
    public function puedeAceptarEquipo()
    {
        return $this->estado === 'activo' && $this->tieneCupoDisponible();
    }

    // Relación muchos a muchos con jueces
    public function jueces()
    {
        return $this->belongsToMany(User::class, 'evento_juez', 'evento_id', 'juez_id')
                    ->withPivot('estado', 'fecha_asignacion')
                    ->withTimestamps();
    }

    // Verificar si el evento está activo según las fechas
    public function estaActivoPorFecha()
    {
        $ahora = now();
        return $ahora->between($this->fecha_inicio, $this->fecha_fin);
    }

    // Verificar si el evento está disponible para inscripción
    public function estaDisponibleParaInscripcion()
    {
        return $this->estado === 'activo' &&
               $this->estaActivoPorFecha() &&
               $this->tieneCupoDisponible();
    }
}