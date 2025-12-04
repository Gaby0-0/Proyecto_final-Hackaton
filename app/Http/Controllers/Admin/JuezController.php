<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Proyecto;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class JuezController extends Controller
{
    /**
     * Display a listing of judges.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'juez');

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->buscar . '%')
                  ->orWhere('email', 'like', '%' . $request->buscar . '%')
                  ->orWhere('nombre_completo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('especialidad', 'like', '%' . $request->buscar . '%');
            });
        }

        // Filtro por especialidad
        if ($request->filled('especialidad')) {
            $query->where('especialidad', $request->especialidad);
        }

        // Filtro por estado activo
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        $jueces = $query->withCount(['equiposAsignados', 'eventosAsignados'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        // Obtener especialidades únicas para el filtro
        $especialidades = User::where('role', 'juez')
                             ->whereNotNull('especialidad')
                             ->distinct()
                             ->pluck('especialidad');

        return view('admin.jueces.index', compact('jueces', 'especialidades'));
    }

    /**
     * Show the form for creating a new judge.
     */
    public function create()
    {
        return view('admin.jueces.create');
    }

    /**
     * Store a newly created judge in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'nombre_completo' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'juez';
        $validated['activo'] = $request->has('activo');
        $validated['informacion_completa'] = !empty($validated['nombre_completo']) && !empty($validated['especialidad']);

        User::create($validated);

        return redirect()->route('admin.jueces.index')
            ->with('success', 'Juez creado exitosamente');
    }

    /**
     * Display the specified judge.
     */
    public function show(User $juez)
    {
        // Verificar que sea un juez
        if ($juez->role !== 'juez') {
            abort(404);
        }

        $juez->load(['equiposAsignados.proyecto', 'evaluaciones']);

        return view('admin.jueces.show', compact('juez'));
    }

    /**
     * Show the form for editing the specified judge.
     */
    public function edit(User $juez)
    {
        // Verificar que sea un juez
        if ($juez->role !== 'juez') {
            abort(404);
        }

        return view('admin.jueces.edit', compact('juez'));
    }

    /**
     * Update the specified judge in storage.
     */
    public function update(Request $request, User $juez)
    {
        // Verificar que sea un juez
        if ($juez->role !== 'juez') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $juez->id,
            'nombre_completo' => 'nullable|string|max:255',
            'especialidad' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $validated['activo'] = $request->has('activo');
        $validated['informacion_completa'] = !empty($validated['nombre_completo']) && !empty($validated['especialidad']);

        $juez->update($validated);

        return redirect()->route('admin.jueces.index')
            ->with('success', 'Juez actualizado exitosamente');
    }

    /**
     * Remove the specified judge from storage.
     */
    public function destroy(User $juez)
    {
        // Verificar que sea un juez
        if ($juez->role !== 'juez') {
            abort(404);
        }

        $juez->delete();

        return redirect()->route('admin.jueces.index')
            ->with('success', 'Juez eliminado exitosamente');
    }

    /**
     * Show the form for assigning judges to teams.
     */
    public function asignar(Request $request)
    {
        $query = Equipo::with(['proyecto', 'jueces']);

        // Filtros
        if ($request->filled('categoria')) {
            $query->whereHas('proyecto', function($q) use ($request) {
                $q->where('categoria', $request->categoria);
            });
        }

        if ($request->filled('evento_id')) {
            $query->whereHas('eventos', function($q) use ($request) {
                $q->where('eventos.id', $request->evento_id);
            });
        }

        $equipos = $query->paginate(15);

        // Obtener jueces disponibles
        $jueces = User::where('role', 'juez')
                     ->withCount('equiposAsignados')
                     ->orderBy('equipos_asignados_count', 'asc')
                     ->get();

        // Obtener categorías únicas
        $categorias = Proyecto::distinct()->pluck('categoria');

        // Obtener eventos
        $eventos = Evento::orderBy('fecha_inicio', 'desc')->get();

        return view('admin.jueces.asignar', compact('equipos', 'jueces', 'categorias', 'eventos'));
    }

    /**
     * Assign judges to teams randomly based on project category.
     */
    public function asignarAleatorio(Request $request)
    {
        $validated = $request->validate([
            'categoria' => 'nullable|string',
            'evento_id' => 'nullable|exists:eventos,id',
            'num_jueces' => 'required|integer|min:1|max:5',
        ]);

        DB::beginTransaction();
        try {
            // Construir query para equipos
            $query = Equipo::with('proyecto');

            if ($request->filled('categoria')) {
                $query->whereHas('proyecto', function($q) use ($request) {
                    $q->where('categoria', $request->categoria);
                });
            }

            if ($request->filled('evento_id')) {
                $query->whereHas('eventos', function($q) use ($request) {
                    $q->where('eventos.id', $request->evento_id);
                });
            }

            $equipos = $query->get();

            if ($equipos->isEmpty()) {
                return back()->with('error', 'No se encontraron equipos con los criterios seleccionados');
            }

            // Obtener jueces disponibles ordenados por carga de trabajo
            $jueces = User::where('role', 'juez')
                         ->withCount('equiposAsignados')
                         ->orderBy('equipos_asignados_count', 'asc')
                         ->get();

            if ($jueces->isEmpty()) {
                return back()->with('error', 'No hay jueces disponibles para asignar');
            }

            $numJueces = min($validated['num_jueces'], $jueces->count());
            $asignaciones = 0;

            foreach ($equipos as $equipo) {
                // Obtener jueces ya asignados a este equipo
                $juecesAsignados = $equipo->jueces->pluck('id')->toArray();

                // Seleccionar jueces que no estén ya asignados a este equipo
                $juecesDisponibles = $jueces->filter(function($juez) use ($juecesAsignados) {
                    return !in_array($juez->id, $juecesAsignados);
                });

                // Si no hay suficientes jueces disponibles, usar todos
                if ($juecesDisponibles->count() < $numJueces) {
                    $juecesDisponibles = $jueces;
                }

                // Seleccionar aleatoriamente los jueces necesarios
                $juecesSeleccionados = $juecesDisponibles->shuffle()->take($numJueces);

                foreach ($juecesSeleccionados as $juez) {
                    // Verificar si ya está asignado
                    $yaAsignado = DB::table('juez_equipo')
                                   ->where('juez_id', $juez->id)
                                   ->where('equipo_id', $equipo->id)
                                   ->exists();

                    if (!$yaAsignado) {
                        DB::table('juez_equipo')->insert([
                            'juez_id' => $juez->id,
                            'equipo_id' => $equipo->id,
                            'estado' => 'asignado',
                            'fecha_asignacion' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $asignaciones++;
                    }
                }

                // Actualizar el contador de asignaciones para balancear la carga
                $jueces = User::where('role', 'juez')
                             ->withCount('equiposAsignados')
                             ->orderBy('equipos_asignados_count', 'asc')
                             ->get();
            }

            DB::commit();

            return redirect()->route('admin.jueces.asignar')
                ->with('success', "Se realizaron {$asignaciones} asignaciones exitosamente");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asignar jueces: ' . $e->getMessage());
        }
    }

    /**
     * Assign a specific judge to a specific team.
     */
    public function asignarManual(Request $request)
    {
        $validated = $request->validate([
            'juez_id' => 'required|exists:users,id',
            'equipo_id' => 'required|exists:equipos,id',
        ]);

        // Verificar que el usuario sea un juez
        $juez = User::findOrFail($validated['juez_id']);
        if ($juez->role !== 'juez') {
            return back()->with('error', 'El usuario seleccionado no es un juez');
        }

        // Verificar si ya está asignado
        $yaAsignado = DB::table('juez_equipo')
                       ->where('juez_id', $validated['juez_id'])
                       ->where('equipo_id', $validated['equipo_id'])
                       ->exists();

        if ($yaAsignado) {
            return back()->with('error', 'Este juez ya está asignado a este equipo');
        }

        DB::table('juez_equipo')->insert([
            'juez_id' => $validated['juez_id'],
            'equipo_id' => $validated['equipo_id'],
            'estado' => 'asignado',
            'fecha_asignacion' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Juez asignado exitosamente');
    }

    /**
     * Remove a judge assignment from a team.
     */
    public function desasignar(Request $request)
    {
        $validated = $request->validate([
            'juez_id' => 'required|exists:users,id',
            'equipo_id' => 'required|exists:equipos,id',
        ]);

        DB::table('juez_equipo')
          ->where('juez_id', $validated['juez_id'])
          ->where('equipo_id', $validated['equipo_id'])
          ->delete();

        return back()->with('success', 'Juez desasignado exitosamente');
    }
}
