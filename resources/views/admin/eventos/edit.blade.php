@extends('layouts.admin')

@section('title', 'Editar Evento')

@php
$pageTitle = 'Editar Evento';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Eventos', 'url' => route('admin.eventos.index')],
    ['name' => 'Editar']
];
@endphp

@section('content')
<x-admin.card>
    <form action="{{ route('admin.eventos.update', $evento->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre del Evento -->
            <div class="md:col-span-2">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Evento <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       value="{{ old('nombre', $evento->nombre) }}"
                       required
                       placeholder="Ej: Hackathon 2025"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror">
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="md:col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea id="descripcion"
                          name="descripcion"
                          rows="4"
                          placeholder="Describe el evento..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $evento->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha de Inicio -->
            <div>
                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Inicio <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local"
                       id="fecha_inicio"
                       name="fecha_inicio"
                       value="{{ old('fecha_inicio', $evento->fecha_inicio ? $evento->fecha_inicio->format('Y-m-d\TH:i') : '') }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_inicio') border-red-500 @enderror">
                @error('fecha_inicio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha de Fin -->
            <div>
                <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Fin <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local"
                       id="fecha_fin"
                       name="fecha_fin"
                       value="{{ old('fecha_fin', $evento->fecha_fin ? $evento->fecha_fin->format('Y-m-d\TH:i') : '') }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fecha_fin') border-red-500 @enderror">
                @error('fecha_fin')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select id="estado"
                        name="estado"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('estado') border-red-500 @enderror">
                    <option value="">Selecciona un estado</option>
                    <option value="activo" {{ old('estado', $evento->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="programado" {{ old('estado', $evento->estado) == 'programado' ? 'selected' : '' }}>Programado</option>
                    <option value="finalizado" {{ old('estado', $evento->estado) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="cancelado" {{ old('estado', $evento->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
                @error('estado')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Modalidad -->
            <div>
                <label for="modalidad" class="block text-sm font-medium text-gray-700 mb-2">
                    Modalidad <span class="text-red-500">*</span>
                </label>
                <select id="modalidad"
                        name="modalidad"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('modalidad') border-red-500 @enderror">
                    <option value="">Selecciona una modalidad</option>
                    <option value="presencial" {{ old('modalidad', $evento->modalidad) == 'presencial' ? 'selected' : '' }}>Presencial</option>
                    <option value="virtual" {{ old('modalidad', $evento->modalidad) == 'virtual' ? 'selected' : '' }}>Virtual</option>
                    <option value="hibrida" {{ old('modalidad', $evento->modalidad) == 'hibrida' ? 'selected' : '' }}>Híbrida</option>
                </select>
                @error('modalidad')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cupo Máximo de Equipos -->
            <div>
                <label for="max_equipos" class="block text-sm font-medium text-gray-700 mb-2">
                    Cupo Máximo de Equipos <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="max_equipos"
                       name="max_equipos"
                       value="{{ old('max_equipos', $evento->max_equipos) }}"
                       required
                       min="1"
                       placeholder="Ej: 50"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_equipos') border-red-500 @enderror">
                @error('max_equipos')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Número máximo de equipos que pueden participar</p>
            </div>

            <!-- Tipo -->
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Evento
                </label>
                <select id="tipo"
                        name="tipo"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tipo') border-red-500 @enderror">
                    <option value="">Selecciona un tipo</option>
                    <option value="hackathon" {{ old('tipo', $evento->tipo) == 'hackathon' ? 'selected' : '' }}>Hackathon</option>
                    <option value="competencia" {{ old('tipo', $evento->tipo) == 'competencia' ? 'selected' : '' }}>Competencia</option>
                    <option value="feria" {{ old('tipo', $evento->tipo) == 'feria' ? 'selected' : '' }}>Feria de Proyectos</option>
                    <option value="workshop" {{ old('tipo', $evento->tipo) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="otro" {{ old('tipo', $evento->tipo) == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
                @error('tipo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoría -->
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                    Categoría
                </label>
                <input type="text"
                       id="categoria"
                       name="categoria"
                       value="{{ old('categoria', $evento->categoria) }}"
                       placeholder="Ej: Desarrollo Web, IoT, Inteligencia Artificial"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('categoria') border-red-500 @enderror">
                @error('categoria')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Los equipos solo pueden inscribirse a un evento por categoría</p>
            </div>
        </div>

        <!-- Botones -->
        <div class="mt-8 flex items-center justify-end gap-4">
            <a href="{{ route('admin.eventos.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Actualizar Evento
            </button>
        </div>
    </form>
</x-admin.card>
@endsection
