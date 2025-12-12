@extends('layouts.admin')

@section('title', 'Editar Juez')

@php
$pageTitle = 'Editar Juez';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Jueces', 'url' => route('admin.jueces.index')],
    ['name' => 'Editar']
];
@endphp

@section('content')
<x-admin.card>
    <form action="{{ route('admin.jueces.update', $juez->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre de usuario -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre de Usuario <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $juez->name) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre Completo -->
            <div class="md:col-span-2">
                <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre Completo
                </label>
                <input type="text"
                       id="nombre_completo"
                       name="nombre_completo"
                       value="{{ old('nombre_completo', $juez->datosJuez?->nombre_completo ?? $juez->name) }}"
                       placeholder="Ej: Dr. Juan Pérez García"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre_completo') border-red-500 @enderror">
                @error('nombre_completo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Incluye títulos y grados académicos si lo deseas</p>
            </div>

            <!-- Especialidad -->
            <div class="md:col-span-2">
                <label for="especialidad" class="block text-sm font-medium text-gray-700 mb-2">
                    Especialidad
                </label>
                <select id="especialidad"
                        name="especialidad"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('especialidad') border-red-500 @enderror">
                    <option value="">Selecciona una especialidad</option>
                    <option value="Desarrollo de Software" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Desarrollo de Software' ? 'selected' : '' }}>Desarrollo de Software</option>
                    <option value="Inteligencia Artificial" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Inteligencia Artificial' ? 'selected' : '' }}>Inteligencia Artificial</option>
                    <option value="Ciencia de Datos" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Ciencia de Datos' ? 'selected' : '' }}>Ciencia de Datos</option>
                    <option value="Ciberseguridad" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Ciberseguridad' ? 'selected' : '' }}>Ciberseguridad</option>
                    <option value="Redes y Telecomunicaciones" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Redes y Telecomunicaciones' ? 'selected' : '' }}>Redes y Telecomunicaciones</option>
                    <option value="Bases de Datos" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Bases de Datos' ? 'selected' : '' }}>Bases de Datos</option>
                    <option value="Desarrollo Web" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Desarrollo Web' ? 'selected' : '' }}>Desarrollo Web</option>
                    <option value="Desarrollo Móvil" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Desarrollo Móvil' ? 'selected' : '' }}>Desarrollo Móvil</option>
                    <option value="IoT (Internet of Things)" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'IoT (Internet of Things)' ? 'selected' : '' }}>IoT (Internet of Things)</option>
                    <option value="Computación en la Nube" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Computación en la Nube' ? 'selected' : '' }}>Computación en la Nube</option>
                    <option value="Arquitectura de Software" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Arquitectura de Software' ? 'selected' : '' }}>Arquitectura de Software</option>
                    <option value="DevOps" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'DevOps' ? 'selected' : '' }}>DevOps</option>
                    <option value="Blockchain" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Blockchain' ? 'selected' : '' }}>Blockchain</option>
                    <option value="Otra" {{ old('especialidad', $juez->datosJuez?->especialidad) == 'Otra' ? 'selected' : '' }}>Otra</option>
                </select>
                @error('especialidad')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="md:col-span-2">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Correo Electrónico <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $juez->email) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Nueva Contraseña
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Dejar en blanco para mantener la contraseña actual</p>
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmar Contraseña
                </label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Estado Activo -->
            <div class="md:col-span-2">
                <div class="flex items-center">
                    <input type="checkbox"
                           id="activo"
                           name="activo"
                           value="1"
                           {{ old('activo', $juez->datosJuez?->activo) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="activo" class="ml-2 block text-sm text-gray-700">
                        Cuenta activa (el juez podrá iniciar sesión)
                    </label>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="mt-8 flex items-center justify-end gap-4">
            <a href="{{ route('admin.jueces.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Actualizar Juez
            </button>
        </div>
    </form>
</x-admin.card>
@endsection
