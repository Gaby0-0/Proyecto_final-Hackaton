<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ActualizarEstadosEventos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eventos:actualizar-estados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza automáticamente los estados de los eventos según sus fechas de inicio y fin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $ahora = now();
        $actualizados = 0;

        $eventosAActivar = \App\Models\Evento::where('estado', 'programado')
            ->whereRaw('fecha_inicio <= ?', [$ahora])
            ->whereRaw('fecha_fin > ?', [$ahora])
            ->get();

        foreach ($eventosAActivar as $evento) {
            $evento->update(['estado' => 'activo']);
            $actualizados++;
        }

        $eventosAFinalizar = \App\Models\Evento::where('estado', 'activo')
            ->whereRaw('fecha_fin <= ?', [$ahora])
            ->get();

        foreach ($eventosAFinalizar as $evento) {
            $evento->update(['estado' => 'finalizado']);
            $actualizados++;
        }

        $eventosARevertir = \App\Models\Evento::where('estado', 'finalizado')
            ->whereRaw('fecha_inicio > ?', [$ahora])
            ->get();

        foreach ($eventosARevertir as $evento) {
            $evento->update(['estado' => 'programado']);
            $actualizados++;
        }

        if ($actualizados > 0) {
            $this->info("Actualizados {$actualizados} eventos");
        }

        return Command::SUCCESS;
    }
}
