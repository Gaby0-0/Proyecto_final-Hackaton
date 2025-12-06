<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'numero_control' => 'nullable|string|max:50',
            'carrera' => 'nullable|string|max:255',
            'semestre' => 'nullable|integer|min:1|max:12',
            'telefono' => 'nullable|string|max:20',
            'github' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'portafolio' => 'nullable|url|max:255',
        ]);

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
    public function cambiarPassword(Request $request)
    {
        $validated = $request->validate([
            'password_actual' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($validated['password_actual'], $user->password)) {
            return back()->with('error', 'La contraseña actual no es correcta');
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Contraseña actualizada exitosamente');
    }

    // Actualizar rol específico en un equipo
    public function actualizarRolEquipo(Request $request, $equipoId)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'rol_especifico' => 'required|string|max:255',
        ]);

        // Verificar que el usuario es miembro del equipo
        $equipo = $user->equipos()->where('equipo_id', $equipoId)->first();

        if (!$equipo) {
            return back()->with('error', 'No eres miembro de este equipo');
        }

        // Actualizar rol específico
        $user->equipos()->updateExistingPivot($equipoId, [
            'rol_especifico' => $validated['rol_especifico'],
        ]);

        return back()->with('success', 'Rol actualizado exitosamente');
    }
}
