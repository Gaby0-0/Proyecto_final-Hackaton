@extends('layouts.juez')

@section('title', 'Equipos a Evaluar')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header con filtros -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Equipos a Evaluar</h1>
                <p class="text-gray-600 mt-1">Evalúa a los equipos basándote en sus proyectos</p>
            </div>
        </div>

        <!-- Filtros -->
        <form method="GET" action="{{ route('juez.evaluaciones.index') }}" class="mt-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar equipo</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nombre del equipo..."
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proyecto</label>
                    <select name="proyecto"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="">Todos los proyectos</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" {{ request('proyecto') == $proyecto->id ? 'selected' : '' }}>
                                {{ $proyecto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de equipos -->
    @if($equipos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($equipos as $equipo)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <!-- Header del equipo -->
                    <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-4 text-white">
                        <h3 class="text-lg font-bold">{{ $equipo->nombre }}</h3>
                        @if($equipo->proyecto)
                            <p class="text-purple-100 text-sm mt-1">
                                <i class="fas fa-project-diagram mr-1"></i>
                                {{ $equipo->proyecto->nombre }}
                            </p>
                        @endif
                    </div>

                    <!-- Contenido -->
                    <div class="p-4">
                        <!-- Descripción -->
                        @if($equipo->descripcion)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $equipo->descripcion }}</p>
                        @endif

                        <!-- Estadísticas -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center text-gray-600 mb-1">
                                    <i class="fas fa-users text-xs mr-1"></i>
                                    <span class="text-xs">Integrantes</span>
                                </div>
                                <p class="text-lg font-bold text-gray-900">{{ $equipo->miembros->count() }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center text-gray-600 mb-1">
                                    <i class="fas fa-star text-xs mr-1"></i>
                                    <span class="text-xs">Evaluaciones</span>
                                </div>
                                <p class="text-lg font-bold text-gray-900">{{ $equipo->evaluaciones->count() }}</p>
                            </div>
                        </div>

                        <!-- Estado de evaluación -->
                        @php
                            $miEvaluacion = $equipo->evaluaciones->where('evaluador_id', auth()->id())->first();
                        @endphp

                        @if($miEvaluacion)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                <p class="text-green-800 text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Ya evaluaste este equipo
                                </p>
                                <p class="text-green-600 text-xs mt-1">Puntuación: {{ $miEvaluacion->puntuacion }}/100</p>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                <p class="text-yellow-800 text-sm font-medium">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pendiente de evaluar
                                </p>
                            </div>
                        @endif

                        <!-- Acciones -->
                        <div class="flex gap-2">
                            <a href="{{ route('juez.evaluaciones.show', $equipo) }}"
                               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Ver Detalles
                            </a>
                            @if($miEvaluacion)
                                <a href="{{ route('juez.evaluaciones.editar', $equipo) }}"
                                   class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                    <i class="fas fa-edit mr-1"></i>
                                    Editar
                                </a>
                            @else
                                <a href="{{ route('juez.evaluaciones.crear', $equipo) }}"
                                   class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                    <i class="fas fa-clipboard-check mr-1"></i>
                                    Evaluar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $equipos->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay equipos disponibles</h3>
            <p class="text-gray-500">
                @if(request('search') || request('proyecto'))
                    No se encontraron equipos con los filtros aplicados.
                @else
                    Actualmente no hay equipos con proyectos asignados para evaluar.
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
