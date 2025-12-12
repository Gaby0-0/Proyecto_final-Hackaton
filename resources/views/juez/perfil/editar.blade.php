@extends('layouts.juez')

@section('title', 'Editar Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Perfil</h1>
            <p class="text-gray-600 mt-2">Actualiza tu información personal y profesional</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form action="{{ route('juez.perfil.actualizar') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Nombre Completo -->
                    <div>
                        <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nombre_completo"
                               name="nombre_completo"
                               value="{{ old('nombre_completo', $user->nombre_completo) }}"
                               required
                               placeholder="Ej: Dr. Juan Pérez García"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre_completo') border-red-500 @enderror">
                        @error('nombre_completo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Incluye títulos y grados académicos si lo deseas</p>
                    </div>

                    <!-- Especialidad -->
                    <div>
                        <label for="especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                            Especialidad <span class="text-red-500">*</span>
                        </label>
                        <select id="especialidad"
                                name="especialidad"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('especialidad') border-red-500 @enderror">
                            <option value="">Selecciona tu especialidad</option>
                            <option value="Desarrollo de Software" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Desarrollo de Software' ? 'selected' : '' }}>Desarrollo de Software</option>
                            <option value="Inteligencia Artificial" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Inteligencia Artificial' ? 'selected' : '' }}>Inteligencia Artificial</option>
                            <option value="Ciencia de Datos" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Ciencia de Datos' ? 'selected' : '' }}>Ciencia de Datos</option>
                            <option value="Ciberseguridad" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Ciberseguridad' ? 'selected' : '' }}>Ciberseguridad</option>
                            <option value="Redes y Telecomunicaciones" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Redes y Telecomunicaciones' ? 'selected' : '' }}>Redes y Telecomunicaciones</option>
                            <option value="Bases de Datos" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Bases de Datos' ? 'selected' : '' }}>Bases de Datos</option>
                            <option value="Desarrollo Web" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Desarrollo Web' ? 'selected' : '' }}>Desarrollo Web</option>
                            <option value="Desarrollo Móvil" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Desarrollo Móvil' ? 'selected' : '' }}>Desarrollo Móvil</option>
                            <option value="IoT (Internet of Things)" {{ old('especialidad', $user->datosJuez?->especialidad) == 'IoT (Internet of Things)' ? 'selected' : '' }}>IoT (Internet of Things)</option>
                            <option value="Computación en la Nube" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Computación en la Nube' ? 'selected' : '' }}>Computación en la Nube</option>
                            <option value="Arquitectura de Software" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Arquitectura de Software' ? 'selected' : '' }}>Arquitectura de Software</option>
                            <option value="DevOps" {{ old('especialidad', $user->datosJuez?->especialidad) == 'DevOps' ? 'selected' : '' }}>DevOps</option>
                            <option value="Blockchain" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Blockchain' ? 'selected' : '' }}>Blockchain</option>
                            <option value="Otra" {{ old('especialidad', $user->datosJuez?->especialidad) == 'Otra' ? 'selected' : '' }}>Otra</option>
                        </select>
                        @error('especialidad')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Selecciona el área en la que tienes más experiencia</p>
                    </div>

                    <!-- Información adicional -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Información de cuenta</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Rol:</strong> {{ ucfirst($user->role) }}</p>
                            <p class="text-xs text-gray-500 mt-2">Para cambiar tu email o contraseña, contacta al administrador</p>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-8 flex items-center justify-end gap-4">
                    <a href="{{ route('juez.perfil.mostrar') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
