@extends('layouts.estudiante')

@section('title', 'Crear Equipo')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('estudiante.equipos.index') }}" class="text-blue-600 hover:text-blue-700 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Equipos
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Crear Nuevo Equipo</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('estudiante.equipos.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="nombre" class="block text-gray-700 font-semibold mb-2">
                    Nombre del Equipo <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       value="{{ old('nombre') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
                       required
                       placeholder="Ej: Los Innovadores">
                @error('nombre')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="descripcion" class="block text-gray-700 font-semibold mb-2">
                    Descripción
                </label>
                <textarea id="descripcion"
                          name="descripcion"
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('descripcion') border-red-500 @enderror"
                          placeholder="Describe brevemente tu equipo y sus objetivos...">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="max_integrantes" class="block text-gray-700 font-semibold mb-2">
                    Máximo de Integrantes <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="max_integrantes"
                       name="max_integrantes"
                       value="{{ old('max_integrantes', 5) }}"
                       min="2"
                       max="10"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_integrantes') border-red-500 @enderror"
                       required>
                <p class="text-gray-500 text-sm mt-1">Mínimo 2 integrantes, máximo 10</p>
                @error('max_integrantes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="proyecto_id" class="block text-gray-700 font-semibold mb-2">
                    Proyecto Asociado (Opcional)
                </label>
                <select id="proyecto_id"
                        name="proyecto_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('proyecto_id') border-red-500 @enderror">
                    <option value="">Sin proyecto asignado</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}" {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                            {{ $proyecto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('proyecto_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Nota importante:</p>
                        <p>Al crear este equipo, automáticamente serás designado como el líder. Podrás gestionar el equipo y sus miembros.</p>
                    </div>
                </div>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                    <i class="fas fa-check mr-2"></i>
                    Crear Equipo
                </button>
                <a href="{{ route('estudiante.equipos.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-semibold text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
