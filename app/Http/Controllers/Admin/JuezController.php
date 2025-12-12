<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AsignarJuezAleatorioRequest;
use App\Http\Requests\Admin\AsignarJuezManualRequest;
use App\Http\Requests\Admin\DesasignarJuezRequest;
use App\Http\Requests\Admin\StoreJuezRequest;
use App\Http\Requests\Admin\UpdateJuezRequest;
use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JuezController extends Controller
{
    /**
     * Display a listing of judges.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'juez')->with('datosJuez');

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->buscar.'%')
                    ->orWhere('email', 'like', '%'.$request->buscar.'%')
                    ->orWhereHas('datosJuez', function ($subq) use ($request) {
                        $subq->where('nombre_completo', 'like', '%'.$request->buscar.'%')
                            ->orWhere('especialidad', 'like', '%'.$request->buscar.'%');
                    });
            });
        }

        // Filtro por especialidad
        if ($request->filled('especialidad')) {
            $query->whereHas('datosJuez', function ($q) use ($request) {
                $q->where('especialidad', $request->especialidad);
            });
        }

        // Filtro por estado activo
        if ($request->filled('activo')) {
            $query->whereHas('datosJuez', function ($q) use ($request) {
                $q->where('activo', $request->activo);
            });
        }

        $jueces = $query->withCount(['equiposAsignados', 'eventosAsignados'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Obtener especialidades únicas para el filtro
        $especialidades = DB::table('jueces')
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
    public function store(StoreJuezRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Crear el usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'juez',
            ]);

            // Crear datos del juez en la tabla jueces (siempre activo por defecto)
            $user->datosJuez()->create([
                'nombre_completo' => $validated['nombre_completo'],
                'especialidad' => $validated['especialidad'],
                'cedula_profesional' => $validated['cedula_profesional'] ?? null,
                'institucion' => $validated['institucion'] ?? null,
                'experiencia' => $validated['experiencia'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'activo' => true, // Siempre activo al crear
                'informacion_completa' => true, // Ya que nombre_completo y especialidad son requeridos
            ]);

            DB::commit();

            return redirect()->route('admin.jueces.index')
                ->with('success', 'Juez creado exitosamente y está activo para ser asignado a eventos');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al crear el juez: '.$e->getMessage())
                ->withInput();
        }
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

        $juez->load(['datosJuez', 'equiposAsignados.proyecto', 'evaluaciones']);

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

        $juez->load('datosJuez');

        return view('admin.jueces.edit', compact('juez'));
    }

    /**
     * Update the specified judge in storage.
     */
    public function update(UpdateJuezRequest $request, User $juez)
    {
        // Verificar que sea un juez
        if ($juez->role !== 'juez') {
            abort(404);
        }

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Actualizar datos del usuario
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $juez->update($updateData);

            // Actualizar o crear datos del juez
            $datosJuez = [
                'nombre_completo' => $validated['nombre_completo'] ?? $validated['name'],
                'cedula_profesional' => $validated['cedula_profesional'] ?? null,
                'institucion' => $validated['institucion'] ?? null,
                'experiencia' => $validated['experiencia'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'activo' => $request->has('activo'),
            ];

            // Solo actualizar especialidad si está presente en el request validado
            if (isset($validated['especialidad']) && ! empty($validated['especialidad'])) {
                $datosJuez['especialidad'] = $validated['especialidad'];
            }

            // Determinar si la información está completa
            // Obtener la especialidad actual o la nueva
            $especialidadActual = $datosJuez['especialidad'] ?? $juez->datosJuez?->especialidad;
            $nombreCompleto = $validated['nombre_completo'] ?? $validated['name'];
            $datosJuez['informacion_completa'] = ! empty($nombreCompleto) && ! empty($especialidadActual);

            $juez->datosJuez()->updateOrCreate(
                ['user_id' => $juez->id],
                $datosJuez
            );

            DB::commit();

            return redirect()->route('admin.jueces.index')
                ->with('success', 'Juez actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al actualizar el juez: '.$e->getMessage())
                ->withInput();
        }
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
            $query->whereHas('proyecto', function ($q) use ($request) {
                $q->where('categoria', $request->categoria);
            });
        }

        if ($request->filled('evento_id')) {
            $query->whereHas('eventos', function ($q) use ($request) {
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
    public function asignarAleatorio(AsignarJuezAleatorioRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Construir query para equipos
            $query = Equipo::with('proyecto');

            if ($request->filled('categoria')) {
                $query->whereHas('proyecto', function ($q) use ($request) {
                    $q->where('categoria', $request->categoria);
                });
            }

            if ($request->filled('evento_id')) {
                $query->whereHas('eventos', function ($q) use ($request) {
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
                $juecesDisponibles = $jueces->filter(function ($juez) use ($juecesAsignados) {
                    return ! in_array($juez->id, $juecesAsignados);
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

                    if (! $yaAsignado) {
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

            return back()->with('error', 'Error al asignar jueces: '.$e->getMessage());
        }
    }

    /**
     * Assign a specific judge to a specific team.
     */
    public function asignarManual(AsignarJuezManualRequest $request)
    {
        $validated = $request->validated();

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
    public function desasignar(DesasignarJuezRequest $request)
    {
        $validated = $request->validated();

        DB::table('juez_equipo')
            ->where('juez_id', $validated['juez_id'])
            ->where('equipo_id', $validated['equipo_id'])
            ->delete();

        return back()->with('success', 'Juez desasignado exitosamente');
    }
}
