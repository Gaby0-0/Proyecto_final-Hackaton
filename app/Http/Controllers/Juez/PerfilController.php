<?php

namespace App\Http\Controllers\Juez;

use App\Http\Controllers\Controller;
use App\Http\Requests\Juez\ActualizarPerfilJuezRequest;
use App\Http\Requests\Juez\GuardarPerfilJuezRequest;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    /**
     * Mostrar formulario para completar información del perfil.
     */
    public function completar()
    {
        $user = Auth::user();

        // Si ya tiene información completa, redirigir al dashboard
        if ($user->tieneInformacionCompleta()) {
            return redirect()->route('juez.dashboard');
        }

        return view('juez.perfil.completar', compact('user'));
    }

    /**
     * Guardar información completa del perfil.
     */
    public function guardar(GuardarPerfilJuezRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        // Crear o actualizar datos del juez
        $user->datosJuez()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nombre_completo' => $validated['nombre_completo'],
                'especialidad' => $validated['especialidad'],
                'cedula_profesional' => $validated['cedula_profesional'] ?? null,
                'institucion' => $validated['institucion'] ?? null,
                'experiencia' => $validated['experiencia'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'informacion_completa' => true,
                'activo' => true,
            ]
        );

        return redirect()->route('juez.dashboard')
            ->with('success', 'Tu perfil ha sido completado exitosamente.');
    }

    /**
     * Mostrar el perfil del juez.
     */
    public function mostrar()
    {
        $user = Auth::user();

        return view('juez.perfil.mostrar', compact('user'));
    }

    /**
     * Mostrar formulario de edición del perfil.
     */
    public function editar()
    {
        $user = Auth::user();

        return view('juez.perfil.editar', compact('user'));
    }

    /**
     * Actualizar el perfil del juez.
     */
    public function actualizar(ActualizarPerfilJuezRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        // Actualizar datos del juez
        $user->datosJuez()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nombre_completo' => $validated['nombre_completo'],
                'especialidad' => $validated['especialidad'],
                'cedula_profesional' => $validated['cedula_profesional'] ?? null,
                'institucion' => $validated['institucion'] ?? null,
                'experiencia' => $validated['experiencia'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'informacion_completa' => true,
            ]
        );

        return redirect()->route('juez.perfil.mostrar')
            ->with('success', 'Perfil actualizado exitosamente.');
    }
}
