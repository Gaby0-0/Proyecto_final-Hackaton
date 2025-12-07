<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constancia extends Model
{
    protected $table = 'constancias';

    protected $fillable = [
        'user_id',
        'equipo_id',
        'evento_id',
        'tipo',
        'lugar',
        'proyecto_nombre',
        'numero_folio',
        'archivo_url',
        'fecha_emision',
        'descripcion',
        'descargada'
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'descargada' => 'boolean',
    ];

    // Relación con el usuario (estudiante)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    // Relación con el evento
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    // Generar número de folio único
    public static function generarFolio()
    {
        $year = date('Y');
        $lastConstancia = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $numero = $lastConstancia ? intval(substr($lastConstancia->numero_folio, -6)) + 1 : 1;

        return 'CONST-' . $year . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }

    // Boot method para generar folio automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($constancia) {
            if (empty($constancia->numero_folio)) {
                $constancia->numero_folio = self::generarFolio();
            }
            if (empty($constancia->fecha_emision)) {
                $constancia->fecha_emision = now();
            }
        });
    }

    // Verificar si es de ganador
    public function esGanador()
    {
        return $this->tipo === 'ganador';
    }

    // Verificar si es de participante
    public function esParticipante()
    {
        return $this->tipo === 'participante';
    }

    // Marcar como descargada
    public function marcarDescargada()
    {
        $this->update(['descargada' => true]);
    }
}
