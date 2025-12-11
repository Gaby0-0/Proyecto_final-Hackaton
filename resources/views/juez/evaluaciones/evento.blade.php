@extends('layouts.juez')

@section('title', 'Equipos del Evento - ' . $evento->nombre)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('juez.evaluaciones.index') }}"
                   class="mr-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                        {{ $evento->nombre }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Equipos a evaluar en este evento
                    </p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Información del Evento -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Fecha</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Categoría</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ ucfirst($evento->categoria ?? 'N/A') }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Total Equipos</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $equipos->count() }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Evaluados</p>
                    <p class="text-lg font-semibold text-green-600">
                        {{ $equipos->filter(fn($e) => $e->evaluacion_existente)->count() }}
                    </p>
                </div>
            </div>
        </div>

        @if($equipos->count() === 0)
            <!-- Empty State -->
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-folder-open text-gray-400 text-6xl mb-4"></i>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No hay equipos con proyectos</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Este evento no tiene equipos que hayan subido proyectos para evaluar.
                </p>
            </div>
        @else
            <!-- Lista de Equipos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($equipos as $equipo)
                    <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Header del equipo -->
                        <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-4 text-white">
                            <h3 class="text-lg font-bold">{{ $equipo->nombre }}</h3>
                            <p class="text-purple-100 text-sm mt-1">
                                <i class="fas fa-users mr-1"></i>
                                {{ $equipo->miembros->count() }} integrantes
                            </p>
                        </div>

                        <!-- Contenido -->
                        <div class="p-4">
                            <!-- Información del Proyecto -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-project-diagram text-blue-600 mr-1"></i>
                                    Proyecto
                                </h4>
                                <p class="text-sm font-semibold text-gray-900">{{ $equipo->pivot->proyecto_titulo }}</p>
                                @if($equipo->pivot->proyecto_descripcion)
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $equipo->pivot->proyecto_descripcion }}</p>
                                @endif
                            </div>

                            <!-- Estado del Proyecto Final -->
                            @if($equipo->pivot->proyecto_final_url)
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Proyecto final entregado
                                        </span>
                                    </div>
                                    <a href="{{ Storage::url($equipo->pivot->proyecto_final_url) }}"
                                       target="_blank"
                                       class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                        <i class="fas fa-file-download mr-1"></i>
                                        Descargar proyecto
                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                </div>
                            @else
                                <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-3">
                                    <p class="text-red-800 text-xs font-medium">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Sin proyecto final
                                    </p>
                                    <p class="text-red-600 text-xs mt-1">
                                        Este equipo no ha subido su entrega final
                                    </p>
                                </div>
                            @endif

                            <!-- Estado de evaluación -->
                            @if($equipo->evaluacion_existente)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-green-800 text-sm font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Ya evaluado
                                            </p>
                                            <p class="text-green-600 text-xs mt-1">
                                                Puntuación: {{ number_format($equipo->evaluacion_existente->puntuacion, 1) }}/100
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($equipo->pivot->proyecto_final_url)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                    <p class="text-yellow-800 text-sm font-medium">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pendiente de evaluar
                                    </p>
                                </div>
                            @endif

                            <!-- Acciones -->
                            <div class="flex gap-2">
                                @if($equipo->pivot->proyecto_final_url)
                                    @if($equipo->evaluacion_existente)
                                        <a href="{{ route('juez.evaluaciones.editar', ['evento' => $evento, 'equipo' => $equipo]) }}"
                                           class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                            <i class="fas fa-edit mr-1"></i>
                                            Editar Evaluación
                                        </a>
                                    @else
                                        <a href="{{ route('juez.evaluaciones.crear', ['evento' => $evento, 'equipo' => $equipo]) }}"
                                           class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                            <i class="fas fa-clipboard-check mr-1"></i>
                                            Evaluar Ahora
                                        </a>
                                    @endif
                                @else
                                    <button disabled
                                            class="flex-1 bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm font-medium text-center cursor-not-allowed">
                                        <i class="fas fa-ban mr-1"></i>
                                        No disponible
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
