<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignacionController extends Controller
{
    // Mostrar interfaz de asignación
    public function index(Request $request)
    {
        $query = Equipo::with(['proyecto', 'jueces', 'miembros']);

        // Filtrar por categoría
        if ($request->filled('categoria')) {
            $query->whereHas('proyecto', function($q) use ($request) {
                $q->where('categoria', $request->categoria);
            });
        }

        // Filtrar solo equipos con proyecto
        $query->whereNotNull('proyecto_id');

        $equipos = $query->latest()->paginate(15);

        // Obtener todas las categorías disponibles
        $categorias = Proyecto::whereNotNull('categoria')
                              ->distinct()
                              ->pluck('categoria');

        // Obtener todos los jueces
        $jueces = User::where('role', 'juez')->get();

        // Estadísticas
        $totalEquipos = Equipo::whereNotNull('proyecto_id')->count();
        $totalJueces = User::where('role', 'juez')->count();
        $totalAsignaciones = DB::table('juez_equipo')->count();

        return view('admin.asignaciones.index', compact(
            'equipos',
            'categorias',
            'jueces',
            'totalEquipos',
            'totalJueces',
            'totalAsignaciones'
        ));
    }

    // Asignar jueces de manera aleatoria por categoría
    public function asignarAleatorio(Request $request)
    {
        $validated = $request->validate([
            'categoria' => 'nullable|string',
            'num_jueces_por_equipo' => 'required|integer|min:1|max:10'
        ]);

        $numJueces = $validated['num_jueces_por_equipo'];

        // Obtener equipos según la categoría
        $equiposQuery = Equipo::whereNotNull('proyecto_id')
                              ->with('proyecto');

        if ($request->filled('categoria')) {
            $equiposQuery->whereHas('proyecto', function($q) use ($request) {
                $q->where('categoria', $request->categoria);
            });
        }

        $equipos = $equiposQuery->get();

        if ($equipos->isEmpty()) {
            return redirect()->back()->with('error', 'No hay equipos disponibles con los criterios seleccionados.');
        }

        // Obtener todos los jueces disponibles
        $jueces = User::where('role', 'juez')->get();

        if ($jueces->count() < $numJueces) {
            return redirect()->back()->with('error', 'No hay suficientes jueces disponibles. Se requieren al menos ' . $numJueces . ' jueces.');
        }

        $asignacionesRealizadas = 0;

        DB::beginTransaction();
        try {
            foreach ($equipos as $equipo) {
                // Obtener jueces ya asignados a este equipo
                $juecesAsignados = $equipo->jueces->pluck('id')->toArray();

                // Calcular cuántos jueces faltan por asignar
                $juecesRestantes = $numJueces - count($juecesAsignados);

                if ($juecesRestantes > 0) {
                    // Obtener jueces disponibles (que no estén ya asignados)
                    $juecesDisponibles = $jueces->whereNotIn('id', $juecesAsignados);

                    // Seleccionar aleatoriamente los jueces faltantes
                    $juecesSeleccionados = $juecesDisponibles->random(min($juecesRestantes, $juecesDisponibles->count()));

                    foreach ($juecesSeleccionados as $juez) {
                        // Asignar juez al equipo
                        $equipo->jueces()->attach($juez->id, [
                            'estado' => 'asignado',
                            'fecha_asignacion' => now()
                        ]);
                        $asignacionesRealizadas++;
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', "Se realizaron {$asignacionesRealizadas} asignaciones de jueces exitosamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al asignar jueces: ' . $e->getMessage());
        }
    }

    // Asignar un juez manualmente a un equipo
    public function asignarManual(Request $request)
    {
        $validated = $request->validate([
            'juez_id' => 'required|exists:users,id',
            'equipo_id' => 'required|exists:equipos,id'
        ]);

        // Verificar que el usuario sea un juez
        $juez = User::findOrFail($validated['juez_id']);
        if ($juez->role !== 'juez') {
            return redirect()->back()->with('error', 'El usuario seleccionado no es un juez.');
        }

        // Verificar que el equipo tenga proyecto
        $equipo = Equipo::findOrFail($validated['equipo_id']);
        if (!$equipo->proyecto_id) {
            return redirect()->back()->with('error', 'El equipo no tiene un proyecto asignado.');
        }

        // Verificar si ya está asignado
        if ($equipo->jueces()->where('juez_id', $juez->id)->exists()) {
            return redirect()->back()->with('error', 'Este juez ya está asignado a este equipo.');
        }

        try {
            $equipo->jueces()->attach($juez->id, [
                'estado' => 'asignado',
                'fecha_asignacion' => now()
            ]);

            return redirect()->back()->with('success', "Juez {$juez->name} asignado exitosamente al equipo {$equipo->nombre}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al asignar juez: ' . $e->getMessage());
        }
    }

    // Desasignar un juez de un equipo
    public function desasignar(Request $request)
    {
        $validated = $request->validate([
            'juez_id' => 'required|exists:users,id',
            'equipo_id' => 'required|exists:equipos,id'
        ]);

        $equipo = Equipo::findOrFail($validated['equipo_id']);
        $juez = User::findOrFail($validated['juez_id']);

        try {
            $equipo->jueces()->detach($validated['juez_id']);

            return redirect()->back()->with('success', "Juez {$juez->name} desasignado exitosamente del equipo {$equipo->nombre}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al desasignar juez: ' . $e->getMessage());
        }
    }

    // Limpiar todas las asignaciones
    public function limpiarAsignaciones(Request $request)
    {
        $validated = $request->validate([
            'categoria' => 'nullable|string'
        ]);

        try {
            if ($request->filled('categoria')) {
                // Limpiar solo asignaciones de equipos con esa categoría
                $equiposIds = Equipo::whereHas('proyecto', function($q) use ($request) {
                    $q->where('categoria', $request->categoria);
                })->pluck('id');

                DB::table('juez_equipo')
                    ->whereIn('equipo_id', $equiposIds)
                    ->delete();

                return redirect()->back()->with('success', 'Asignaciones de la categoría limpiadas exitosamente.');
            } else {
                // Limpiar todas las asignaciones
                DB::table('juez_equipo')->truncate();
                return redirect()->back()->with('success', 'Todas las asignaciones han sido limpiadas exitosamente.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al limpiar asignaciones: ' . $e->getMessage());
        }
    }

    // Ver detalles de las asignaciones de un equipo
    public function verAsignaciones(Equipo $equipo)
    {
        $equipo->load(['proyecto', 'jueces', 'miembros', 'evaluaciones']);

        return view('admin.asignaciones.show', compact('equipo'));
    }
}
