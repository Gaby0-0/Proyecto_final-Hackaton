<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        // Datos de prueba
        $usuarios = collect([
            (object)[
                'id' => 1,
                'nombre' => 'Ana Pérez García',
                'email' => 'ana.perez@university.edu',
                'rol' => 'Estudiante',
                'estado' => 'Activo',
                'ultimo_acceso' => '15/03/2024 14:30'
            ],
            (object)[
                'id' => 2,
                'nombre' => 'Dr. Carlos López',
                'email' => 'carlos.lopez@university.edu',
                'rol' => 'Juez',
                'estado' => 'Activo',
                'ultimo_acceso' => '14/03/2024 09:15'
            ],
            (object)[
                'id' => 3,
                'nombre' => 'María García Admin',
                'email' => 'maria.garcia@university.edu',
                'rol' => 'Administrador',
                'estado' => 'Activo',
                'ultimo_acceso' => '15/03/2024 16:45'
            ],
            (object)[
                'id' => 4,
                'nombre' => 'Luis Rodríguez',
                'email' => 'luis.rodriguez@university.edu',
                'rol' => 'Estudiante',
                'estado' => 'Inactivo',
                'ultimo_acceso' => '28/02/2024 11:20'
            ]
        ]);

        return view('admin.usuarios.index', compact('usuarios'));

        /* DESCOMENTAR CUANDO TENGAS LA BASE DE DATOS
        $query = User::query();

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->buscar . '%')
                  ->orWhere('email', 'like', '%' . $request->buscar . '%');
            });
        }

        // Filtro por rol
        if ($request->filled('rol') && $request->rol !== 'todos') {
            $query->where('role', $request->rol);
        }

        // Filtro por estado
        if ($request->filled('estado') && $request->estado !== 'todos') {
            if ($request->estado === 'activo') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.usuarios.index', compact('usuarios'));
        */
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,juez,estudiante'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function show(User $usuario)
    {
        return view('admin.usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'role' => 'required|in:admin,juez,estudiante'
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $usuario->update($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}