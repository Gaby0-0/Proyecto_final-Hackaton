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
        'categoria',
        'max_equipos',
        'modalidad',
        'equipo_ganador_id',
        'fecha_seleccion_ganador'
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'fecha_seleccion_ganador' => 'datetime',
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

    // Relación con el equipo ganador
    public function equipoGanador()
    {
        return $this->belongsTo(Equipo::class, 'equipo_ganador_id');
    }

    // Verificar si ya tiene equipo ganador
    public function tieneGanador()
    {
        return !is_null($this->equipo_ganador_id);
    }

    // Obtener el promedio de evaluación de un equipo en este evento
    public function promedioEvaluacionEquipo($equipoId)
    {
        $evaluaciones = Evaluacion::where('evento_id', $this->id)
            ->where('equipo_id', $equipoId)
            ->get();

        if ($evaluaciones->isEmpty()) {
            return null;
        }

        return $evaluaciones->avg('puntuacion');
    }

    // Obtener todos los equipos con sus promedios de evaluación
    public function equiposConPromedios()
    {
        return $this->equiposAprobados()
            ->with(['miembros'])
            ->withPivot([
                'proyecto_titulo',
                'proyecto_descripcion',
                'proyecto_final_url',
                'fecha_entrega_final'
            ])
            ->get()
            ->filter(function($equipo) {
                // Solo equipos con proyecto
                return !empty($equipo->pivot->proyecto_titulo);
            })
            ->map(function($equipo) {
                $promedio = $this->promedioEvaluacionEquipo($equipo->id);
                $numEvaluaciones = Evaluacion::where('evento_id', $this->id)
                    ->where('equipo_id', $equipo->id)
                    ->count();

                $equipo->promedio_evaluacion = $promedio;
                $equipo->num_evaluaciones = $numEvaluaciones;
                $equipo->evaluaciones_evento = Evaluacion::where('evento_id', $this->id)
                    ->where('equipo_id', $equipo->id)
                    ->with('evaluador')
                    ->get();

                return $equipo;
            })
            ->sortByDesc('promedio_evaluacion')
            ->values();
    }

    // Determinar ganador automáticamente (equipo con mayor promedio)
    public function determinarGanadorAutomatico()
    {
        $equiposConPromedios = $this->equiposConPromedios();

        if ($equiposConPromedios->isEmpty()) {
            return null;
        }

        // Filtrar solo equipos que tengan al menos una evaluación
        $equiposEvaluados = $equiposConPromedios->filter(function($equipo) {
            return !is_null($equipo->promedio_evaluacion) && $equipo->num_evaluaciones > 0;
        });

        if ($equiposEvaluados->isEmpty()) {
            return null;
        }

        // El ganador es el primero (mayor promedio)
        return $equiposEvaluados->first();
    }

    // Establecer ganador automáticamente
    public function establecerGanadorAutomatico()
    {
        $ganador = $this->determinarGanadorAutomatico();

        if ($ganador) {
            $this->update([
                'equipo_ganador_id' => $ganador->id,
                'fecha_seleccion_ganador' => now(),
            ]);

            return $ganador;
        }

        return null;
    }

    // Relación con constancias
    public function constancias()
    {
        return $this->hasMany(Constancia::class);
    }

    // Generar constancias para todos los participantes
    public function generarConstanciasParticipantes()
    {
        $equiposParticipantes = $this->equiposAprobados()
            ->with(['miembros'])
            ->get();

        $constanciasGeneradas = 0;

        foreach ($equiposParticipantes as $equipo) {
            foreach ($equipo->miembros as $miembro) {
                // Verificar que no exista ya una constancia para este usuario en este evento
                $existe = Constancia::where('user_id', $miembro->id)
                    ->where('evento_id', $this->id)
                    ->where('equipo_id', $equipo->id)
                    ->exists();

                if (!$existe) {
                    Constancia::create([
                        'user_id' => $miembro->id,
                        'equipo_id' => $equipo->id,
                        'evento_id' => $this->id,
                        'tipo' => 'participante',
                        'descripcion' => 'Constancia de participación en ' . $this->nombre,
                    ]);
                    $constanciasGeneradas++;
                }
            }
        }

        return $constanciasGeneradas;
    }

    // Generar constancia para el equipo ganador
    public function generarConstanciaGanador()
    {
        if (!$this->equipo_ganador_id) {
            return 0;
        }

        $equipoGanador = $this->equipoGanador()->with('miembros')->first();

        if (!$equipoGanador) {
            return 0;
        }

        $constanciasGeneradas = 0;

        foreach ($equipoGanador->miembros as $miembro) {
            // Verificar si ya existe constancia de ganador
            $existe = Constancia::where('user_id', $miembro->id)
                ->where('evento_id', $this->id)
                ->where('tipo', 'ganador')
                ->exists();

            if (!$existe) {
                // Si existe constancia de participante, actualizarla a ganador
                $constanciaParticipante = Constancia::where('user_id', $miembro->id)
                    ->where('evento_id', $this->id)
                    ->where('tipo', 'participante')
                    ->first();

                if ($constanciaParticipante) {
                    $constanciaParticipante->update([
                        'tipo' => 'ganador',
                        'descripcion' => 'Constancia de 1er lugar en ' . $this->nombre,
                    ]);
                } else {
                    // Crear nueva constancia de ganador
                    Constancia::create([
                        'user_id' => $miembro->id,
                        'equipo_id' => $equipoGanador->id,
                        'evento_id' => $this->id,
                        'tipo' => 'ganador',
                        'descripcion' => 'Constancia de 1er lugar en ' . $this->nombre,
                    ]);
                }
                $constanciasGeneradas++;
            }
        }

        return $constanciasGeneradas;
    }

    // Generar constancias para los jueces asignados
    public function generarConstanciasJueces()
    {
        $jueces = $this->jueces;

        if ($jueces->isEmpty()) {
            return 0;
        }

        $constanciasGeneradas = 0;

        foreach ($jueces as $juez) {
            // Verificar si ya existe constancia de juez
            $existe = Constancia::where('user_id', $juez->id)
                ->where('evento_id', $this->id)
                ->where('tipo', 'juez')
                ->exists();

            if (!$existe) {
                Constancia::create([
                    'user_id' => $juez->id,
                    'evento_id' => $this->id,
                    'tipo' => 'juez',
                    'descripcion' => 'Reconocimiento por participar como juez evaluador en ' . $this->nombre,
                ]);
                $constanciasGeneradas++;
            }
        }

        return $constanciasGeneradas;
    }
}