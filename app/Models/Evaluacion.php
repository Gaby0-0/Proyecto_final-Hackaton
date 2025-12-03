<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';

    protected $fillable = [
        'evento_id',
        'equipo_id',
        'evaluador_id',
        'puntuacion',
        'comentarios',
        'estado',
        'criterio_innovacion',
        'criterio_funcionalidad',
        'criterio_presentacion',
        'criterio_impacto',
        'criterio_tecnico'
    ];

    protected $casts = [
        'puntuacion' => 'decimal:2',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function evaluador()
    {
        return $this->belongsTo(User::class, 'evaluador_id');
    }
}