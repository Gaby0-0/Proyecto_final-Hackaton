@extends('layouts.admin')

@section('title', 'Editar Equipo')

@section('breadcrumbs')
<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('admin.equipos.index') }}" class="text-gray-600 hover:text-blue-600">Equipos</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-gray-900 font-medium">Editar</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Editar Equipo: {{ $equipo->nombre }}</h1>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-admin.card>
        <form action="{{ route('admin.equipos.update', $equipo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Equipo <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre', $equipo->nombre) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nombre') border-red-500 @enderror"
                           required>
                    @error('nombre')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="descripcion"
                              name="descripcion"
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $equipo->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="max_integrantes" class="block text-sm font-medium text-gray-700 mb-2">
                        Máximo de Integrantes <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           id="max_integrantes"
                           name="max_integrantes"
                           value="{{ old('max_integrantes', $equipo->max_integrantes) }}"
                           min="2"
                           max="20"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_integrantes') border-red-500 @enderror"
                           required>
                    <p class="text-gray-500 text-sm mt-1">Entre 2 y 20 integrantes. Actual: {{ $equipo->miembros->count() }}</p>
                    @error('max_integrantes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="proyecto_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Proyecto Asociado
                    </label>
                    <select id="proyecto_id"
                            name="proyecto_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('proyecto_id') border-red-500 @enderror">
                        <option value="">Sin proyecto</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" {{ old('proyecto_id', $equipo->proyecto_id) == $proyecto->id ? 'selected' : '' }}>
                                {{ $proyecto->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('proyecto_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lider_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Líder del Equipo
                    </label>
                    @php
                        $liderActual = $equipo->miembros->where('pivot.rol_equipo', 'lider')->first();
                    @endphp
                    <select id="lider_id"
                            name="lider_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lider_id') border-red-500 @enderror">
                        <option value="">Sin líder</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('lider_id', optional($liderActual)->id) == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('lider_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="miembros" class="block text-sm font-medium text-gray-700 mb-2">
                        Miembros Adicionales
                    </label>
                    @php
                        $miembrosActuales = $equipo->miembros->where('pivot.rol_equipo', 'miembro')->pluck('id')->toArray();
                    @endphp
                    <select id="miembros"
                            name="miembros[]"
                            multiple
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('miembros') border-red-500 @enderror"
                            size="5">
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ in_array($usuario->id, old('miembros', $miembrosActuales)) ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-500 text-sm mt-1">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples</p>
                    @error('miembros')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </button>
                <a href="{{ route('admin.equipos.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
