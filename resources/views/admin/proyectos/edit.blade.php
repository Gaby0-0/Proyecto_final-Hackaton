@extends('layouts.admin')

@section('title', 'Editar Proyecto')

@php
$pageTitle = 'Editar Proyecto';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Proyectos', 'url' => route('admin.proyectos.index')],
    ['name' => 'Editar']
];
@endphp

@section('content')
<x-admin.card>
    <form action="{{ route('admin.proyectos.update', $proyecto->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Proyecto <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nombre"
                       value="{{ old('nombre', $proyecto->nombre) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror"
                       placeholder="Ej: Sistema de Gestión de Hackathon">
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Categoría
                </label>
                <select name="categoria"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('categoria') border-red-500 @enderror">
                    <option value="">Seleccionar categoría</option>
                    <option value="Desarrollo Web" {{ old('categoria', $proyecto->categoria) === 'Desarrollo Web' ? 'selected' : '' }}>Desarrollo Web</option>
                    <option value="Desarrollo Móvil" {{ old('categoria', $proyecto->categoria) === 'Desarrollo Móvil' ? 'selected' : '' }}>Desarrollo Móvil</option>
                    <option value="Inteligencia Artificial" {{ old('categoria', $proyecto->categoria) === 'Inteligencia Artificial' ? 'selected' : '' }}>Inteligencia Artificial</option>
                    <option value="Internet of Things (IoT)" {{ old('categoria', $proyecto->categoria) === 'Internet of Things (IoT)' ? 'selected' : '' }}>Internet of Things (IoT)</option>
                    <option value="Ciencia de Datos" {{ old('categoria', $proyecto->categoria) === 'Ciencia de Datos' ? 'selected' : '' }}>Ciencia de Datos</option>
                    <option value="Ciberseguridad" {{ old('categoria', $proyecto->categoria) === 'Ciberseguridad' ? 'selected' : '' }}>Ciberseguridad</option>
                    <option value="Blockchain" {{ old('categoria', $proyecto->categoria) === 'Blockchain' ? 'selected' : '' }}>Blockchain</option>
                    <option value="Realidad Virtual/Aumentada" {{ old('categoria', $proyecto->categoria) === 'Realidad Virtual/Aumentada' ? 'selected' : '' }}>Realidad Virtual/Aumentada</option>
                    <option value="Otro" {{ old('categoria', $proyecto->categoria) === 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
                @error('categoria')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fechas -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Inicio
                    </label>
                    <input type="date"
                           name="fecha_inicio"
                           value="{{ old('fecha_inicio', $proyecto->fecha_inicio?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_inicio') border-red-500 @enderror">
                    @error('fecha_inicio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Fin
                    </label>
                    <input type="date"
                           name="fecha_fin"
                           value="{{ old('fecha_fin', $proyecto->fecha_fin?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_fin') border-red-500 @enderror">
                    @error('fecha_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Descripción -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea name="descripcion"
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror"
                          placeholder="Describe el proyecto, sus objetivos y alcance...">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tecnologías -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tecnologías Sugeridas
                </label>
                <textarea name="tecnologias"
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tecnologias') border-red-500 @enderror"
                          placeholder="Ej: React, Node.js, MongoDB, TailwindCSS...">{{ old('tecnologias', $proyecto->tecnologias) }}</textarea>
                @error('tecnologias')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Tecnologías recomendadas para implementar el proyecto</p>
            </div>

            <!-- Requisitos -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Requisitos y Consideraciones
                </label>
                <textarea name="requisitos"
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('requisitos') border-red-500 @enderror"
                          placeholder="Requisitos técnicos, funcionales o de diseño...">{{ old('requisitos', $proyecto->requisitos) }}</textarea>
                @error('requisitos')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Advertencia si hay equipos asociados -->
        @if($proyecto->equipos()->count() > 0)
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-amber-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-amber-700">
                        <strong>Atención:</strong> Este proyecto tiene {{ $proyecto->equipos()->count() }} equipo(s) asociado(s).
                        Los cambios afectarán a todos los equipos vinculados.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.proyectos.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Actualizar Proyecto
            </button>
        </div>
    </form>
</x-admin.card>
@endsection
