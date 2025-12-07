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
        'equipo_primer_lugar_id',
        'equipo_segundo_lugar_id',
        'equipo_tercer_lugar_id',
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

    // Relaciones con los equipos ganadores
    public function equipoPrimerLugar()
    {
        return $this->belongsTo(Equipo::class, 'equipo_primer_lugar_id');
    }

    public function equipoSegundoLugar()
    {
        return $this->belongsTo(Equipo::class, 'equipo_segundo_lugar_id');
    }

    public function equipoTercerLugar()
    {
        return $this->belongsTo(Equipo::class, 'equipo_tercer_lugar_id');
    }

    // Método legacy para compatibilidad con código existente
    public function equipoGanador()
    {
        return $this->equipoPrimerLugar();
    }

    // Verificar si ya tiene equipo ganador
    public function tieneGanador()
    {
        return !is_null($this->equipo_primer_lugar_id);
    }

    // Verificar si tiene todos los ganadores definidos
    public function tieneTresGanadores()
    {
        return !is_null($this->equipo_primer_lugar_id) &&
               !is_null($this->equipo_segundo_lugar_id) &&
               !is_null($this->equipo_tercer_lugar_id);
    }

    // Verificar si el evento está finalizado
    public function estaFinalizado()
    {
        return $this->estado === 'finalizado';
    }

    // Verificar si puede generar constancias
    public function puedeGenerarConstancias()
    {
        // Solo puede generar constancias si el evento está finalizado
        return $this->estaFinalizado();
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

    // Determinar los 3 mejores equipos automáticamente
    public function determinarTresGanadoresAutomatico()
    {
        $equiposConPromedios = $this->equiposConPromedios();

        if ($equiposConPromedios->isEmpty()) {
            return collect();
        }

        // Filtrar solo equipos que tengan al menos una evaluación
        $equiposEvaluados = $equiposConPromedios->filter(function($equipo) {
            return !is_null($equipo->promedio_evaluacion) && $equipo->num_evaluaciones > 0;
        });

        if ($equiposEvaluados->isEmpty()) {
            return collect();
        }

        // Retornar los 3 mejores (ya están ordenados por promedio descendente)
        return $equiposEvaluados->take(3);
    }

    // Establecer ganador automáticamente (solo 1er lugar - método legacy)
    public function establecerGanadorAutomatico()
    {
        $ganadores = $this->determinarTresGanadoresAutomatico();

        if ($ganadores->isEmpty()) {
            return null;
        }

        // Establecer los 3 ganadores si hay al menos 3 equipos evaluados
        $this->update([
            'equipo_primer_lugar_id' => $ganadores->get(0)->id ?? null,
            'equipo_segundo_lugar_id' => $ganadores->get(1)->id ?? null,
            'equipo_tercer_lugar_id' => $ganadores->get(2)->id ?? null,
            'fecha_seleccion_ganador' => now(),
        ]);

        return $ganadores;
    }

    // Relación con constancias
    public function constancias()
    {
        return $this->hasMany(Constancia::class);
    }

    // Generar constancias para todos los participantes (DEPRECADO - Solo ganadores y jueces)
    public function generarConstanciasParticipantes()
    {
        // Ya no se generan constancias de participantes
        // Solo se generan para ganadores y jueces
        return 0;
    }

    // Generar constancia para el equipo ganador (método actualizado para 3 lugares)
    public function generarConstanciaGanador()
    {
        return $this->generarConstanciasGanadores();
    }

    // Generar constancias para los 3 equipos ganadores
    public function generarConstanciasGanadores()
    {
        // Validar que el evento esté finalizado
        if (!$this->puedeGenerarConstancias()) {
            throw new \Exception('No se pueden generar constancias. El evento debe estar finalizado.');
        }

        $constanciasGeneradas = 0;

        // Definir los ganadores y sus lugares
        $ganadores = [
            ['id' => $this->equipo_primer_lugar_id, 'lugar' => 1, 'texto' => '1er lugar'],
            ['id' => $this->equipo_segundo_lugar_id, 'lugar' => 2, 'texto' => '2do lugar'],
            ['id' => $this->equipo_tercer_lugar_id, 'lugar' => 3, 'texto' => '3er lugar'],
        ];

        foreach ($ganadores as $ganadorInfo) {
            if (!$ganadorInfo['id']) {
                continue; // Saltar si no hay equipo para este lugar
            }

            $equipo = Equipo::with('miembros')->find($ganadorInfo['id']);

            if (!$equipo) {
                continue;
            }

            // Obtener el nombre del proyecto desde la tabla pivot
            $inscripcion = $equipo->eventos()
                ->where('evento_id', $this->id)
                ->withPivot('proyecto_titulo')
                ->first();

            $proyectoNombre = $inscripcion->pivot->proyecto_titulo ?? 'Proyecto del equipo ' . $equipo->nombre;

            foreach ($equipo->miembros as $miembro) {
                // Verificar si ya existe constancia de ganador para este lugar
                $existe = Constancia::where('user_id', $miembro->id)
                    ->where('evento_id', $this->id)
                    ->where('tipo', 'ganador')
                    ->where('lugar', $ganadorInfo['lugar'])
                    ->exists();

                if (!$existe) {
                    // Crear nueva constancia de ganador
                    Constancia::create([
                        'user_id' => $miembro->id,
                        'equipo_id' => $equipo->id,
                        'evento_id' => $this->id,
                        'tipo' => 'ganador',
                        'lugar' => $ganadorInfo['lugar'],
                        'proyecto_nombre' => $proyectoNombre,
                        'descripcion' => 'Constancia de ' . $ganadorInfo['texto'] . ' en ' . $this->nombre,
                    ]);
                    $constanciasGeneradas++;
                }
            }
        }

        return $constanciasGeneradas;
    }

    // Generar constancias para los jueces asignados
    public function generarConstanciasJueces()
    {
        // Validar que el evento esté finalizado
        if (!$this->puedeGenerarConstancias()) {
            throw new \Exception('No se pueden generar constancias. El evento debe estar finalizado.');
        }

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
                    'equipo_id' => null, // Los jueces no tienen equipo
                    'tipo' => 'juez',
                    'descripcion' => 'Reconocimiento por participar como juez evaluador en ' . $this->nombre,
                ]);
                $constanciasGeneradas++;
            }
        }

        return $constanciasGeneradas;
    }
}