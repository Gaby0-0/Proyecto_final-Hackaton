<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Estudiante\StoreEquipoRequest;
use App\Http\Requests\Estudiante\TransferirLiderazgoRequest;
use App\Http\Requests\Estudiante\UnirseCodigoRequest;
use App\Models\Equipo;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipoController extends Controller
{
    // Mostrar lista de equipos disponibles
    public function index()
    {
        $user = Auth::user();

        // Equipos del usuario
        $misEquipos = $user->equipos()->with('miembros', 'proyecto')->get();

        return view('estudiante.equipos.index', compact('misEquipos'));
    }

    // Unirse a equipo usando código
    public function unirseCodigo(UnirseCodigoRequest $request)
    {
        $validated = $request->validated();

        $equipo = Equipo::where('codigo', strtoupper($validated['codigo']))->first();

        if (! $equipo) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Código de equipo no válido. Verifica el código e intenta de nuevo.');
        }

        $user = Auth::user();

        // Verificar si el equipo está activo
        if (! $equipo->activo) {
            return redirect()->back()->with('error', 'Este equipo no está activo.');
        }

        // Verificar si puede unirse
        if (! $equipo->puedeUnirse($user)) {
            if ($equipo->estaLleno()) {
                return redirect()->back()->with('error', 'Este equipo ya está lleno.');
            } else {
                return redirect()->back()->with('error', 'Ya eres miembro de este equipo.');
            }
        }

        // Unir al usuario como miembro con rol específico
        $equipo->miembros()->attach($user->id, [
            'rol_equipo' => 'miembro',
            'rol_especifico' => $validated['rol_especifico'] ?? 'Miembro',
        ]);

        return redirect()->route('estudiante.equipos.show', $equipo)
            ->with('success', 'Te has unido exitosamente al equipo '.$equipo->nombre);
    }

    // Mostrar formulario para crear equipo
    public function create()
    {
        $proyectos = Proyecto::all();

        return view('estudiante.equipos.create', compact('proyectos'));
    }

    // Guardar nuevo equipo
    public function store(StoreEquipoRequest $request)
    {
        $validated = $request->validated();

        $equipo = Equipo::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'proyecto_id' => $validated['proyecto_id'] ?? null,
            'max_integrantes' => $validated['max_integrantes'],
            'activo' => true,
        ]);

        // El creador se convierte automáticamente en líder
        $equipo->miembros()->attach(Auth::id(), [
            'rol_equipo' => 'lider',
            'rol_especifico' => 'Líder del Equipo',
        ]);

        return redirect()->route('estudiante.equipos.index')
            ->with('success', 'Equipo creado exitosamente. Ahora eres el líder del equipo.');
    }

    // Mostrar detalles de un equipo
    public function show(Equipo $equipo)
    {
        $equipo->load('miembros', 'proyecto', 'eventos');

        // Verificar si el usuario es miembro
        $esMiembro = $equipo->miembros()->where('user_id', Auth::id())->exists();

        // Verificar si el usuario es líder
        $esLider = $equipo->miembros()
            ->where('user_id', Auth::id())
            ->wherePivot('rol_equipo', 'lider')
            ->exists();

        return view('estudiante.equipos.show', compact('equipo', 'esMiembro', 'esLider'));
    }

    // Unirse a un equipo
    public function unirse(Request $request, Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar si el equipo está activo
        if (! $equipo->activo) {
            return redirect()->back()->with('error', 'Este equipo no está activo.');
        }

        // Verificar si puede unirse
        if (! $equipo->puedeUnirse($user)) {
            if ($equipo->estaLleno()) {
                return redirect()->back()->with('error', 'Este equipo ya está lleno.');
            } else {
                return redirect()->back()->with('error', 'Ya eres miembro de este equipo.');
            }
        }

        // Unir al usuario como miembro
        $equipo->miembros()->attach($user->id, ['rol_equipo' => 'miembro']);

        return redirect()->route('estudiante.equipos.show', $equipo)
            ->with('success', 'Te has unido al equipo exitosamente.');
    }

    // Salir de un equipo
    public function salir(Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar si es miembro
        if (! $equipo->miembros()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'No eres miembro de este equipo.');
        }

        // Verificar si es el líder
        $esLider = $equipo->miembros()
            ->where('user_id', $user->id)
            ->wherePivot('rol_equipo', 'lider')
            ->exists();

        if ($esLider) {
            // Si es el líder, verificar que haya otros miembros
            if ($equipo->miembros()->count() > 1) {
                return redirect()->back()->with('error', 'Como líder, debes transferir el liderazgo o eliminar el equipo antes de salir.');
            }
            // Si es el único miembro, eliminar el equipo
            $equipo->delete();

            return redirect()->route('estudiante.equipos.index')
                ->with('success', 'Has salido del equipo y el equipo ha sido eliminado.');
        }

        // Remover al usuario del equipo
        $equipo->miembros()->detach($user->id);

        return redirect()->route('estudiante.equipos.index')
            ->with('success', 'Has salido del equipo exitosamente.');
    }

    // Eliminar equipo (solo líder)
    public function destroy(Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar si es el líder
        $esLider = $equipo->miembros()
            ->where('user_id', $user->id)
            ->wherePivot('rol_equipo', 'lider')
            ->exists();

        if (! $esLider) {
            return redirect()->back()->with('error', 'Solo el líder puede eliminar el equipo.');
        }

        $equipo->delete();

        return redirect()->route('estudiante.equipos.index')
            ->with('success', 'Equipo eliminado exitosamente.');
    }

    // Transferir liderazgo a otro miembro del equipo (solo líder)
    public function transferirLiderazgo(TransferirLiderazgoRequest $request, Equipo $equipo)
    {
        $validated = $request->validated();
        $user = Auth::user();

        // Verificar si el usuario actual es el líder
        $esLider = $equipo->miembros()
            ->where('user_id', $user->id)
            ->wherePivot('rol_equipo', 'lider')
            ->exists();

        if (! $esLider) {
            return redirect()->back()->with('error', 'Solo el líder puede transferir el liderazgo.');
        }

        $nuevoLiderId = $validated['nuevo_lider_id'];

        // Verificar que no se esté transfiriendo a sí mismo
        if ($nuevoLiderId == $user->id) {
            return redirect()->back()->with('error', 'No puedes transferir el liderazgo a ti mismo.');
        }

        // Verificar que el nuevo líder sea miembro del equipo
        $nuevoLiderEsMiembro = $equipo->miembros()
            ->where('user_id', $nuevoLiderId)
            ->exists();

        if (! $nuevoLiderEsMiembro) {
            return redirect()->back()->with('error', 'El usuario seleccionado no es miembro de este equipo.');
        }

        // Cambiar rol del líder actual a miembro
        $equipo->miembros()->updateExistingPivot($user->id, [
            'rol_equipo' => 'miembro',
            'rol_especifico' => 'Miembro',
        ]);

        // Cambiar rol del nuevo líder
        $equipo->miembros()->updateExistingPivot($nuevoLiderId, [
            'rol_equipo' => 'lider',
            'rol_especifico' => 'Líder del Equipo',
        ]);

        return redirect()->route('estudiante.equipos.show', $equipo)
            ->with('success', 'El liderazgo ha sido transferido exitosamente.');
    }
}
