<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Evento::withCount('equipos');

            // Filtro por búsqueda
            if ($request->filled('buscar')) {
                $query->where('nombre', 'like', '%' . $request->buscar . '%');
            }

            // Filtro por estado
            if ($request->filled('estado') && $request->estado !== 'todos') {
                $query->where('estado', $request->estado);
            }

            $eventos = $query->orderByDesc('fecha_inicio')->paginate(10);

            return view('admin.eventos.index', compact('eventos'));
        } catch (\Exception $e) {
            \Log::error('Error en admin.eventos.index: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Error al cargar eventos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.eventos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:activo,programado,finalizado,cancelado',
            'modalidad' => 'required|in:presencial,virtual,hibrida',
            'max_equipos' => 'required|integer|min:1',
            'tipo' => 'nullable|string'
        ]);

        Evento::create($validated);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento creado exitosamente');
    }

    public function show(Evento $evento)
    {
        $evento->load(['equipos.miembros', 'equipos.proyecto', 'jueces']);
        return view('admin.eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        return view('admin.eventos.edit', compact('evento'));
    }

    public function update(Request $request, Evento $evento)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:activo,programado,finalizado,cancelado',
            'modalidad' => 'required|in:presencial,virtual,hibrida',
            'max_equipos' => 'required|integer|min:1',
            'tipo' => 'nullable|string'
        ]);

        $evento->update($validated);

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento actualizado exitosamente');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();

        return redirect()->route('admin.eventos.index')
            ->with('success', 'Evento eliminado exitosamente');
    }

    // Ver solicitudes pendientes de un evento
    public function solicitudes(Evento $evento)
    {
        $solicitudesPendientes = $evento->equiposPendientes()
            ->with(['miembros', 'proyecto'])
            ->get();

        $equiposAprobados = $evento->equiposAprobados()
            ->with(['miembros', 'proyecto'])
            ->get();

        return view('admin.eventos.solicitudes', compact('evento', 'solicitudesPendientes', 'equiposAprobados'));
    }

    // Aprobar solicitud de equipo
    public function aprobarSolicitud(Evento $evento, $equipoId)
    {
        // Verificar si hay cupo disponible
        if (!$evento->tieneCupoDisponible()) {
            return back()->with('error', 'No hay cupo disponible para este evento');
        }

        // Actualizar estado de la solicitud
        $evento->equipos()->updateExistingPivot($equipoId, [
            'estado' => 'inscrito'
        ]);

        return back()->with('success', 'Solicitud aprobada exitosamente');
    }

    // Rechazar solicitud de equipo
    public function rechazarSolicitud(Evento $evento, $equipoId)
    {
        // Eliminar la relación (rechazar)
        $evento->equipos()->detach($equipoId);

        return back()->with('success', 'Solicitud rechazada');
    }

    // Mostrar formulario de asignación de jueces a eventos
    public function asignarJueces(Evento $evento)
    {
        // Obtener jueces activos
        $jueces = User::where('role', 'juez')
                     ->where('activo', true)
                     ->whereNotNull('especialidad')
                     ->with('eventosAsignados')
                     ->get();

        // Obtener jueces ya asignados a este evento
        $juecesAsignados = $evento->jueces;

        // Agrupar jueces por especialidad
        $jucesPorEspecialidad = $jueces->groupBy('especialidad');

        return view('admin.eventos.asignar-jueces', compact('evento', 'jueces', 'juecesAsignados', 'jucesPorEspecialidad'));
    }

    // Asignar un juez a un evento
    public function agregarJuez(Request $request, Evento $evento)
    {
        $validated = $request->validate([
            'juez_id' => 'required|exists:users,id',
        ]);

        // Verificar que el usuario sea un juez
        $juez = User::findOrFail($validated['juez_id']);
        if ($juez->role !== 'juez') {
            return back()->with('error', 'El usuario seleccionado no es un juez');
        }

        // Verificar si ya está asignado
        if ($evento->jueces()->where('juez_id', $juez->id)->exists()) {
            return back()->with('error', 'Este juez ya está asignado a este evento');
        }

        // Asignar juez al evento
        $evento->jueces()->attach($juez->id, [
            'estado' => 'asignado',
            'fecha_asignacion' => now(),
        ]);

        return back()->with('success', 'Juez asignado exitosamente al evento');
    }

    // Desasignar un juez de un evento
    public function quitarJuez(Evento $evento, $juezId)
    {
        $evento->jueces()->detach($juezId);

        return back()->with('success', 'Juez desasignado del evento');
    }

    // Asignar jueces automáticamente por especialidad
    public function asignarJuecesAuto(Request $request, Evento $evento)
    {
        $validated = $request->validate([
            'especialidad' => 'nullable|string',
            'cantidad' => 'required|integer|min:1|max:10',
        ]);

        $query = User::where('role', 'juez')
                    ->where('activo', true)
                    ->whereNotNull('especialidad');

        // Filtrar por especialidad si se proporciona
        if ($request->filled('especialidad')) {
            $query->where('especialidad', $request->especialidad);
        }

        // Obtener jueces disponibles
        $juecesDisponibles = $query->withCount('eventosAsignados')
                                  ->orderBy('eventos_asignados_count', 'asc')
                                  ->get();

        // Filtrar jueces que no estén ya asignados a este evento
        $juecesYaAsignados = $evento->jueces->pluck('id')->toArray();
        $juecesDisponibles = $juecesDisponibles->filter(function($juez) use ($juecesYaAsignados) {
            return !in_array($juez->id, $juecesYaAsignados);
        });

        if ($juecesDisponibles->isEmpty()) {
            return back()->with('error', 'No hay jueces disponibles con los criterios especificados');
        }

        // Tomar la cantidad solicitada
        $cantidad = min($validated['cantidad'], $juecesDisponibles->count());
        $juecesSeleccionados = $juecesDisponibles->take($cantidad);

        // Asignar jueces al evento
        foreach ($juecesSeleccionados as $juez) {
            $evento->jueces()->attach($juez->id, [
                'estado' => 'asignado',
                'fecha_asignacion' => now(),
            ]);
        }

        return back()->with('success', "Se asignaron {$cantidad} jueces al evento exitosamente");
    }
}