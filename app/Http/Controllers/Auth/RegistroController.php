<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;    

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('auth.login.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed'
    ]);

    // Crear usuario con rol de estudiante por defecto
    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'admin'    => 0,
        'role'     => 'estudiante'
    ]);

    // TODO: Descomentar cuando la tabla usuarios exista
    /*
    Usuario::create([
        'user_id'  => $user->id,
        'nombre'   => $request->name,
        'control'  => $request->control ?? '123456',
        'rol_id'   => 1,
        'carrera'  => $request->carrera ?? 'Ingeniería en Sistemas Computacionales',
    ]);
    */

    // Iniciar sesión automáticamente
    auth()->login($user);

    // Redirigir según el rol (estudiantes van a su dashboard)
    return redirect()->route('estudiante.dashboard')->with('success','¡Registro exitoso! Bienvenido/a ' . $user->name);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
