<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;

class NotificacionController extends Controller
{
    public function marcarComoLeida(Notificacion $notificacion)
    {
        $notificacion->marcarComoLeida();

        // Redirigir a la URL de la notificación si existe
        if ($notificacion->url) {
            return redirect($notificacion->url);
        }

        return back();
    }

    public function marcarTodasComoLeidas()
    {
        Notificacion::where('leida', false)->update(['leida' => true]);

        return back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }
}
