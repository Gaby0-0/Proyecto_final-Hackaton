@extends('layouts.juez')

@section('title', 'Completar Perfil')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Encabezado -->
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">
                Completa tu perfil
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Para continuar, necesitamos que completes tu información de juez
            </p>
        </div>

        <!-- Alerta informativa -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Esta información nos ayudará a asignarte proyectos según tu especialidad y a generar tus credenciales correctamente.
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white shadow-lg rounded-lg">
            <form action="{{ route('juez.perfil.guardar') }}" method="POST" class="p-8 space-y-6">
                @csrf

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
                           autofocus
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
                        <option value="Desarrollo de Software" {{ old('especialidad', $user->especialidad) == 'Desarrollo de Software' ? 'selected' : '' }}>Desarrollo de Software</option>
                        <option value="Inteligencia Artificial" {{ old('especialidad', $user->especialidad) == 'Inteligencia Artificial' ? 'selected' : '' }}>Inteligencia Artificial</option>
                        <option value="Ciencia de Datos" {{ old('especialidad', $user->especialidad) == 'Ciencia de Datos' ? 'selected' : '' }}>Ciencia de Datos</option>
                        <option value="Ciberseguridad" {{ old('especialidad', $user->especialidad) == 'Ciberseguridad' ? 'selected' : '' }}>Ciberseguridad</option>
                        <option value="Redes y Telecomunicaciones" {{ old('especialidad', $user->especialidad) == 'Redes y Telecomunicaciones' ? 'selected' : '' }}>Redes y Telecomunicaciones</option>
                        <option value="Bases de Datos" {{ old('especialidad', $user->especialidad) == 'Bases de Datos' ? 'selected' : '' }}>Bases de Datos</option>
                        <option value="Desarrollo Web" {{ old('especialidad', $user->especialidad) == 'Desarrollo Web' ? 'selected' : '' }}>Desarrollo Web</option>
                        <option value="Desarrollo Móvil" {{ old('especialidad', $user->especialidad) == 'Desarrollo Móvil' ? 'selected' : '' }}>Desarrollo Móvil</option>
                        <option value="IoT (Internet of Things)" {{ old('especialidad', $user->especialidad) == 'IoT (Internet of Things)' ? 'selected' : '' }}>IoT (Internet of Things)</option>
                        <option value="Computación en la Nube" {{ old('especialidad', $user->especialidad) == 'Computación en la Nube' ? 'selected' : '' }}>Computación en la Nube</option>
                        <option value="Arquitectura de Software" {{ old('especialidad', $user->especialidad) == 'Arquitectura de Software' ? 'selected' : '' }}>Arquitectura de Software</option>
                        <option value="DevOps" {{ old('especialidad', $user->especialidad) == 'DevOps' ? 'selected' : '' }}>DevOps</option>
                        <option value="Blockchain" {{ old('especialidad', $user->especialidad) == 'Blockchain' ? 'selected' : '' }}>Blockchain</option>
                        <option value="Otra" {{ old('especialidad', $user->especialidad) == 'Otra' ? 'selected' : '' }}>Otra</option>
                    </select>
                    @error('especialidad')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Selecciona el área en la que tienes más experiencia</p>
                </div>

                <!-- Botón de envío -->
                <div class="pt-4">
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>
                        Completar Perfil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
