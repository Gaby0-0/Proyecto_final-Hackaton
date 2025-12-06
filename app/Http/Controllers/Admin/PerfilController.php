<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    // Mostrar perfil del administrador
    public function index()
    {
        $user = Auth::user();
        $user->load('datosAdministrador');

        return view('admin.perfil.index', compact('user'));
    }

    // Mostrar formulario de edición
    public function edit()
    {
        $user = Auth::user();
        $user->load('datosAdministrador');

        return view('admin.perfil.edit', compact('user'));
    }

    // Actualizar perfil
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nombre_completo' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'departamento' => 'nullable|string|max:255',
        ]);

        // Actualizar usuario
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Actualizar o crear datos de administrador
        $user->datosAdministrador()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nombre_completo' => $validated['nombre_completo'] ?? $validated['name'],
                'telefono' => $validated['telefono'] ?? null,
                'departamento' => $validated['departamento'] ?? null,
                'activo' => true,
            ]
        );

        return redirect()->route('admin.perfil.index')
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
}
