<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $fillable = [
        'tipo',
        'mensaje',
        'url',
        'equipo_id',
        'evento_id',
        'leida',
    ];

    protected function casts(): array
    {
        return [
            'leida' => 'boolean',
        ];
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    public function marcarComoLeida(): void
    {
        $this->update(['leida' => true]);
    }

    public static function crearSolicitudEquipo(Equipo $equipo, Evento $evento): self
    {
        return self::create([
            'tipo' => 'solicitud_equipo',
            'mensaje' => "El equipo {$equipo->nombre} ha solicitado inscribirse al evento {$evento->nombre}",
            'url' => route('admin.eventos.solicitudes', $evento),
            'equipo_id' => $equipo->id,
            'evento_id' => $evento->id,
        ]);
    }
}
