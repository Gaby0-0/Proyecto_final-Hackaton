<?php

namespace App\Observers;

use App\Models\Evento;
use Carbon\Carbon;

class EventoObserver
{
    /**
     * Handle the Evento "retrieved" event.
     * Actualiza automáticamente el estado del evento basándose en fecha/hora actual.
     */
    public function retrieved(Evento $evento): void
    {
        // Solo actualizar si tiene fechas definidas
        if ($evento->fecha_inicio && $evento->fecha_fin) {
            // Actualizar estado según fecha/hora actual
            $evento->actualizarEstadoSegunFecha();
        }
    }

    /**
     * Handle the Evento "creating" event.
     * NOTA: El estado se calcula en el controlador, no aquí, para evitar conflictos.
     * Solo convertimos las fechas si vienen como strings.
     */
    public function creating(Evento $evento): void
    {
        // Convertir las fechas del formato del formulario (Y-m-d\TH:i) a Carbon si vienen como strings
        if (is_string($evento->fecha_inicio)) {
            $evento->fecha_inicio = Carbon::parse($evento->fecha_inicio);
        }

        if (is_string($evento->fecha_fin)) {
            $evento->fecha_fin = Carbon::parse($evento->fecha_fin);
        }

        // NO calculamos el estado aquí - se hace en el controlador para tener control total
    }

    /**
     * Handle the Evento "updating" event.
     * Recalcula el estado si se modificaron las fechas.
     */
    public function updating(Evento $evento): void
    {
        // Si se modificaron las fechas, recalcular el estado
        if ($evento->isDirty(['fecha_inicio', 'fecha_fin'])) {
            // Asegurarse de que las fechas estén en formato Carbon
            if (is_string($evento->fecha_inicio)) {
                $evento->fecha_inicio = Carbon::parse($evento->fecha_inicio);
            }

            if (is_string($evento->fecha_fin)) {
                $evento->fecha_fin = Carbon::parse($evento->fecha_fin);
            }

            // Solo recalcular si no está cancelado
            if ($evento->estado !== 'cancelado' && $evento->fecha_inicio && $evento->fecha_fin) {
                $evento->estado = $evento->obtenerEstadoSegunFecha();
            }
        }
    }
}
