<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Estudiante\ActualizarRolEquipoRequest;
use App\Http\Requests\Estudiante\UpdatePerfilEstudianteRequest;
use App\Http\Requests\Shared\CambiarPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    // Mostrar perfil del estudiante
    public function index()
    {
        $user = Auth::user();
        $user->load('datosEstudiante', 'equipos');

        return view('estudiante.perfil.index', compact('user'));
    }

    // Mostrar formulario de edición
    public function edit()
    {
        $user = Auth::user();
        $user->load('datosEstudiante');

        return view('estudiante.perfil.edit', compact('user'));
    }

    // Actualizar perfil
    public function update(UpdatePerfilEstudianteRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        // Actualizar usuario
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Actualizar o crear datos de estudiante
        $user->datosEstudiante()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'numero_control' => $validated['numero_control'] ?? null,
                'carrera' => $validated['carrera'] ?? null,
                'semestre' => $validated['semestre'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'github' => $validated['github'] ?? null,
                'linkedin' => $validated['linkedin'] ?? null,
                'portafolio' => $validated['portafolio'] ?? null,
                'datos_completos' => true,
            ]
        );

        return redirect()->route('estudiante.perfil.index')
            ->with('success', 'Perfil actualizado exitosamente');
    }

    // Cambiar contraseña
    public function cambiarPassword(CambiarPasswordRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        // Verificar contraseña actual
        if (! Hash::check($validated['password_actual'], $user->password)) {
            return back()->with('error', 'La contraseña actual no es correcta');
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Contraseña actualizada exitosamente');
    }

    // Actualizar rol específico en un equipo
    public function actualizarRolEquipo(ActualizarRolEquipoRequest $request, $equipoId)
    {
        $user = Auth::user();

        $validated = $request->validated();

        // Verificar que el usuario es miembro del equipo
        $equipo = $user->equipos()->where('equipo_id', $equipoId)->first();

        if (! $equipo) {
            return back()->with('error', 'No eres miembro de este equipo');
        }

        // Actualizar rol específico
        $user->equipos()->updateExistingPivot($equipoId, [
            'rol_especifico' => $validated['rol_especifico'],
        ]);

        return back()->with('success', 'Rol actualizado exitosamente');
    }
}
