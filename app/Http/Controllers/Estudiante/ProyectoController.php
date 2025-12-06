<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProyectoController extends Controller
{
    // Mostrar lista de proyectos del usuario (por equipo y evento)
    public function index()
    {
        $user = Auth::user();

        // Obtener equipos del usuario con sus eventos inscritos
        $equipos = $user->equipos()->with(['eventos' => function ($query) {
            $query->whereIn('equipo_evento.estado', ['inscrito', 'participando', 'finalizado'])
                  ->withPivot([
                      'estado',
                      'proyecto_titulo',
                      'proyecto_descripcion',
                      'avances',
                      'proyecto_final_url',
                      'fecha_entrega_final',
                      'notas_equipo'
                  ]);
        }])->get();

        return view('estudiante.proyectos.index', compact('equipos'));
    }

    // Mostrar formulario para editar/subir proyecto de un equipo en un evento
    public function edit(Equipo $equipo, Evento $evento)
    {
        $user = Auth::user();

        // Verificar que el usuario es miembro del equipo
        if (!$equipo->miembros()->where('user_id', $user->id)->exists()) {
            return redirect()->route('estudiante.proyectos.index')
                ->with('error', 'No tienes acceso a este proyecto');
        }

        // Verificar que el equipo está inscrito en el evento
        $inscripcion = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->whereIn('equipo_evento.estado', ['inscrito', 'participando', 'finalizado'])
            ->withPivot([
                'estado',
                'proyecto_titulo',
                'proyecto_descripcion',
                'avances',
                'proyecto_final_url',
                'fecha_entrega_final',
                'notas_equipo'
            ])
            ->first();

        if (!$inscripcion) {
            return redirect()->route('estudiante.proyectos.index')
                ->with('error', 'El equipo no está inscrito en este evento');
        }

        // Verificar si es líder
        $esLider = $equipo->usuarioEsLider($user->id);

        // Decodificar avances JSON
        $avances = [];
        if ($inscripcion->pivot->avances) {
            $avances = json_decode($inscripcion->pivot->avances, true) ?? [];
        }

        return view('estudiante.proyectos.edit', compact('equipo', 'evento', 'inscripcion', 'esLider', 'avances'));
    }

    // Actualizar información del proyecto
    public function updateInfo(Request $request, Equipo $equipo, Evento $evento)
    {
        $user = Auth::user();

        // Verificar que el usuario es líder del equipo
        if (!$equipo->usuarioEsLider($user->id)) {
            return redirect()->back()
                ->with('error', 'Solo el líder del equipo puede actualizar la información del proyecto');
        }

        // Verificar que el equipo está inscrito en el evento
        if (!$equipo->eventos()->where('evento_id', $evento->id)->exists()) {
            return redirect()->route('estudiante.proyectos.index')
                ->with('error', 'El equipo no está inscrito en este evento');
        }

        $validated = $request->validate([
            'proyecto_titulo' => 'required|string|max:255',
            'proyecto_descripcion' => 'required|string',
            'notas_equipo' => 'nullable|string'
        ]);

        // Actualizar la información en la tabla pivot
        $equipo->eventos()->updateExistingPivot($evento->id, [
            'proyecto_titulo' => $validated['proyecto_titulo'],
            'proyecto_descripcion' => $validated['proyecto_descripcion'],
            'notas_equipo' => $validated['notas_equipo'] ?? null
        ]);

        return redirect()->back()
            ->with('success', 'Información del proyecto actualizada exitosamente');
    }

    // Subir avance
    public function subirAvance(Request $request, Equipo $equipo, Evento $evento)
    {
        $user = Auth::user();

        // Verificar que el usuario es líder del equipo
        if (!$equipo->usuarioEsLider($user->id)) {
            return redirect()->back()
                ->with('error', 'Solo el líder del equipo puede subir avances');
        }

        $validated = $request->validate([
            'descripcion' => 'required|string|max:255',
            'archivo' => 'required|file|max:51200|mimes:pdf,doc,docx,zip,rar,pptx,mp4,avi'
        ]);

        // Guardar archivo
        $path = $request->file('archivo')->store('avances', 'public');

        // Obtener avances actuales
        $inscripcion = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->withPivot('avances')
            ->first();

        $avances = [];
        if ($inscripcion && $inscripcion->pivot->avances) {
            $avances = json_decode($inscripcion->pivot->avances, true) ?? [];
        }

        // Agregar nuevo avance
        $avances[] = [
            'descripcion' => $validated['descripcion'],
            'archivo' => $path,
            'archivo_nombre' => $request->file('archivo')->getClientOriginalName(),
            'fecha' => now()->toDateTimeString(),
            'usuario' => $user->name
        ];

        // Actualizar en la base de datos
        $equipo->eventos()->updateExistingPivot($evento->id, [
            'avances' => json_encode($avances)
        ]);

        return redirect()->back()
            ->with('success', 'Avance subido exitosamente');
    }

    // Eliminar avance
    public function eliminarAvance(Request $request, Equipo $equipo, Evento $evento, $indice)
    {
        $user = Auth::user();

        // Verificar que el usuario es líder del equipo
        if (!$equipo->usuarioEsLider($user->id)) {
            return redirect()->back()
                ->with('error', 'Solo el líder del equipo puede eliminar avances');
        }

        // Obtener avances actuales
        $inscripcion = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->withPivot('avances')
            ->first();

        if (!$inscripcion || !$inscripcion->pivot->avances) {
            return redirect()->back()->with('error', 'No se encontraron avances');
        }

        $avances = json_decode($inscripcion->pivot->avances, true) ?? [];

        if (!isset($avances[$indice])) {
            return redirect()->back()->with('error', 'Avance no encontrado');
        }

        // Eliminar archivo del storage
        if (isset($avances[$indice]['archivo'])) {
            Storage::disk('public')->delete($avances[$indice]['archivo']);
        }

        // Eliminar del array
        array_splice($avances, $indice, 1);

        // Actualizar en la base de datos
        $equipo->eventos()->updateExistingPivot($evento->id, [
            'avances' => json_encode($avances)
        ]);

        return redirect()->back()
            ->with('success', 'Avance eliminado exitosamente');
    }

    // Subir proyecto final
    public function subirProyectoFinal(Request $request, Equipo $equipo, Evento $evento)
    {
        $user = Auth::user();

        // Verificar que el usuario es líder del equipo
        if (!$equipo->usuarioEsLider($user->id)) {
            return redirect()->back()
                ->with('error', 'Solo el líder del equipo puede subir el proyecto final');
        }

        $validated = $request->validate([
            'proyecto_final' => 'required|file|max:102400|mimes:pdf,doc,docx,zip,rar,pptx,mp4,avi'
        ]);

        // Eliminar archivo anterior si existe
        $inscripcion = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->withPivot('proyecto_final_url')
            ->first();

        if ($inscripcion && $inscripcion->pivot->proyecto_final_url) {
            Storage::disk('public')->delete($inscripcion->pivot->proyecto_final_url);
        }

        // Guardar nuevo archivo
        $path = $request->file('proyecto_final')->store('proyectos_finales', 'public');

        // Actualizar en la base de datos
        $equipo->eventos()->updateExistingPivot($evento->id, [
            'proyecto_final_url' => $path,
            'fecha_entrega_final' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Proyecto final subido exitosamente');
    }

    // Eliminar proyecto final
    public function eliminarProyectoFinal(Equipo $equipo, Evento $evento)
    {
        $user = Auth::user();

        // Verificar que el usuario es líder del equipo
        if (!$equipo->usuarioEsLider($user->id)) {
            return redirect()->back()
                ->with('error', 'Solo el líder del equipo puede eliminar el proyecto final');
        }

        // Obtener proyecto final
        $inscripcion = $equipo->eventos()
            ->where('evento_id', $evento->id)
            ->withPivot('proyecto_final_url')
            ->first();

        if (!$inscripcion || !$inscripcion->pivot->proyecto_final_url) {
            return redirect()->back()->with('error', 'No hay proyecto final para eliminar');
        }

        // Eliminar archivo del storage
        Storage::disk('public')->delete($inscripcion->pivot->proyecto_final_url);

        // Actualizar en la base de datos
        $equipo->eventos()->updateExistingPivot($evento->id, [
            'proyecto_final_url' => null,
            'fecha_entrega_final' => null
        ]);

        return redirect()->back()
            ->with('success', 'Proyecto final eliminado exitosamente');
    }
}
