<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsuarioRequest;
use App\Http\Requests\Admin\UpdateUsuarioRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->buscar.'%')
                    ->orWhere('email', 'like', '%'.$request->buscar.'%');
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
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(StoreUsuarioRequest $request)
    {
        $validated = $request->validated();

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

    public function update(UpdateUsuarioRequest $request, User $usuario)
    {
        $validated = $request->validated();

        if ($request->filled('password')) {
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
