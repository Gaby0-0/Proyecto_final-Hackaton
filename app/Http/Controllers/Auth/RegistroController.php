<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\DatosEstudiante;
use App\Models\Equipo;
use App\Models\ConfiguracionGlobal;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;    

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

        // Iniciar sesión automáticamente
        auth()->login($user);

        // Redirigir al siguiente paso: datos de estudiante
        return redirect()->route('registro.datos-estudiante')
            ->with('success','¡Cuenta creada! Ahora completa tus datos');
    }

    // Mostrar formulario de datos de estudiante
    public function mostrarDatosEstudiante()
    {
        // Verificar si ya completó este paso
        if (Auth::user()->datosEstudiante && Auth::user()->datosEstudiante->datos_completos) {
            return redirect()->route('registro.equipos');
        }

        return view('auth.registro.datos-estudiante');
    }

    // Guardar datos de estudiante
    public function guardarDatosEstudiante(Request $request)
    {
        $request->validate([
            'numero_control' => 'required|string|unique:datos_estudiante,numero_control',
            'carrera' => 'required|string',
            'semestre' => 'nullable|string',
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'direccion' => 'nullable|string',
        ]);

        DatosEstudiante::create([
            'user_id' => Auth::id(),
            'numero_control' => $request->numero_control,
            'carrera' => $request->carrera,
            'semestre' => $request->semestre,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'direccion' => $request->direccion,
            'datos_completos' => true,
        ]);

        return redirect()->route('registro.equipos')
            ->with('success', 'Datos guardados correctamente');
    }

    // Mostrar formulario de equipos
    public function mostrarEquipos()
    {
        // Verificar que completó datos de estudiante
        if (!Auth::user()->datosEstudiante || !Auth::user()->datosEstudiante->datos_completos) {
            return redirect()->route('registro.datos-estudiante');
        }

        // Verificar si ya tiene equipo
        if (Auth::user()->equipos()->count() > 0) {
            return redirect()->route('estudiante.dashboard')
                ->with('success', 'Ya tienes un equipo asignado');
        }

        $maxEquipos = ConfiguracionGlobal::obtener('max_equipos_por_estudiante', 3);

        return view('auth.registro.equipos', compact('maxEquipos'));
    }

    // Unirse a equipo con código
    public function unirseEquipo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|exists:equipos,codigo',
        ]);

        $equipo = Equipo::where('codigo', $request->codigo)->firstOrFail();

        // Verificar si puede unirse
        if (!$equipo->puedeUnirse(Auth::user())) {
            if ($equipo->estaLleno()) {
                return back()->with('error', 'Este equipo ya está lleno');
            } else {
                return back()->with('error', 'Ya eres miembro de este equipo');
            }
        }

        // Unir al usuario como miembro
        $equipo->miembros()->attach(Auth::id(), ['rol_equipo' => 'miembro']);

        return redirect()->route('estudiante.dashboard')
            ->with('success', 'Te has unido al equipo ' . $equipo->nombre);
    }

    // Crear equipo desde registro
    public function crearEquipoRegistro(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:equipos,nombre',
            'descripcion' => 'nullable|string',
            'max_integrantes' => 'required|integer|min:1|max:10',
        ]);

        $equipo = Equipo::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'max_integrantes' => $request->max_integrantes,
            'activo' => true,
        ]);

        // El creador se convierte automáticamente en líder
        $equipo->miembros()->attach(Auth::id(), ['rol_equipo' => 'lider']);

        return redirect()->route('estudiante.dashboard')
            ->with('success', 'Equipo creado exitosamente. Eres el líder del equipo ' . $equipo->nombre);
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
