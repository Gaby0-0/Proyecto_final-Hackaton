@extends('layouts.estudiante')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('estudiante.evaluaciones.index') }}"
                   class="mr-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                        Evaluaciones: {{ $equipo->nombre }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Evento: {{ $evento->nombre }}
                    </p>
                </div>
            </div>
        </div>

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

        <!-- Información del Proyecto -->
        @if($proyecto && $proyecto->pivot->proyecto_titulo)
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-project-diagram text-blue-600 mr-2"></i>
                    Información del Proyecto
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Título</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $proyecto->pivot->proyecto_titulo }}</p>
                    </div>
                    @if($proyecto->pivot->proyecto_descripcion)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $proyecto->pivot->proyecto_descripcion }}</p>
                        </div>
                    @endif
                    @if($proyecto->pivot->proyecto_final_url)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">URL del Proyecto</label>
                            <a href="{{ $proyecto->pivot->proyecto_final_url }}"
                               target="_blank"
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                {{ $proyecto->pivot->proyecto_final_url }}
                                <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Estadísticas Generales -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                Resumen de Evaluaciones
            </h3>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 text-center">
                    <p class="text-sm font-medium text-blue-600">Promedio</p>
                    <p class="mt-2 text-3xl font-bold text-blue-900">
                        {{ number_format($estadisticas['promedio_general'], 1) }}
                    </p>
                    <p class="text-xs text-blue-600">de 100</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 text-center">
                    <p class="text-sm font-medium text-green-600">Máxima</p>
                    <p class="mt-2 text-3xl font-bold text-green-900">
                        {{ number_format($estadisticas['puntuacion_maxima'], 1) }}
                    </p>
                    <p class="text-xs text-green-600">puntos</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 text-center">
                    <p class="text-sm font-medium text-yellow-600">Mínima</p>
                    <p class="mt-2 text-3xl font-bold text-yellow-900">
                        {{ number_format($estadisticas['puntuacion_minima'], 1) }}
                    </p>
                    <p class="text-xs text-yellow-600">puntos</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 text-center">
                    <p class="text-sm font-medium text-purple-600">Evaluaciones</p>
                    <p class="mt-2 text-3xl font-bold text-purple-900">
                        {{ $estadisticas['total_evaluaciones'] }}
                    </p>
                    <p class="text-xs text-purple-600">recibidas</p>
                </div>
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-4 text-center col-span-2 sm:col-span-1">
                    <p class="text-sm font-medium text-indigo-600">Rango</p>
                    <p class="mt-2 text-xl font-bold text-indigo-900">
                        {{ number_format($estadisticas['puntuacion_maxima'] - $estadisticas['puntuacion_minima'], 1) }}
                    </p>
                    <p class="text-xs text-indigo-600">variación</p>
                </div>
            </div>
        </div>

        <!-- Promedios por Criterio -->
        @if($estadisticas['promedio_innovacion'] || $estadisticas['promedio_funcionalidad'] || $estadisticas['promedio_presentacion'] || $estadisticas['promedio_impacto'] || $estadisticas['promedio_tecnico'])
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-tasks text-blue-600 mr-2"></i>
                    Promedios por Criterio
                </h3>
                <div class="space-y-4">
                    @if($estadisticas['promedio_innovacion'])
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Innovación</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($estadisticas['promedio_innovacion'], 1) }}/20</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($estadisticas['promedio_innovacion'] / 20) * 100 }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if($estadisticas['promedio_funcionalidad'])
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Funcionalidad</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($estadisticas['promedio_funcionalidad'], 1) }}/20</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($estadisticas['promedio_funcionalidad'] / 20) * 100 }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if($estadisticas['promedio_presentacion'])
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Presentación</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($estadisticas['promedio_presentacion'], 1) }}/20</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ ($estadisticas['promedio_presentacion'] / 20) * 100 }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if($estadisticas['promedio_impacto'])
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Impacto</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($estadisticas['promedio_impacto'], 1) }}/20</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ ($estadisticas['promedio_impacto'] / 20) * 100 }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if($estadisticas['promedio_tecnico'])
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Técnico</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($estadisticas['promedio_tecnico'], 1) }}/20</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ ($estadisticas['promedio_tecnico'] / 20) * 100 }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Evaluaciones Individuales -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-list text-blue-600 mr-2"></i>
                Evaluaciones Individuales
            </h3>
            <div class="space-y-4">
                @foreach($evaluaciones as $evaluacion)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $evaluacion->evaluador->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $evaluacion->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ number_format($evaluacion->puntuacion, 1) }}
                                </p>
                                <p class="text-xs text-gray-500">de 100</p>
                            </div>
                        </div>

                        <!-- Criterios Individuales -->
                        @if($evaluacion->criterio_innovacion || $evaluacion->criterio_funcionalidad || $evaluacion->criterio_presentacion || $evaluacion->criterio_impacto || $evaluacion->criterio_tecnico)
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 mb-3">
                                @if($evaluacion->criterio_innovacion)
                                    <div class="bg-blue-50 rounded p-2 text-center">
                                        <p class="text-xs text-blue-600 font-medium">Innovación</p>
                                        <p class="text-sm font-bold text-blue-900">{{ $evaluacion->criterio_innovacion }}/20</p>
                                    </div>
                                @endif
                                @if($evaluacion->criterio_funcionalidad)
                                    <div class="bg-green-50 rounded p-2 text-center">
                                        <p class="text-xs text-green-600 font-medium">Funcionalidad</p>
                                        <p class="text-sm font-bold text-green-900">{{ $evaluacion->criterio_funcionalidad }}/20</p>
                                    </div>
                                @endif
                                @if($evaluacion->criterio_presentacion)
                                    <div class="bg-purple-50 rounded p-2 text-center">
                                        <p class="text-xs text-purple-600 font-medium">Presentación</p>
                                        <p class="text-sm font-bold text-purple-900">{{ $evaluacion->criterio_presentacion }}/20</p>
                                    </div>
                                @endif
                                @if($evaluacion->criterio_impacto)
                                    <div class="bg-yellow-50 rounded p-2 text-center">
                                        <p class="text-xs text-yellow-600 font-medium">Impacto</p>
                                        <p class="text-sm font-bold text-yellow-900">{{ $evaluacion->criterio_impacto }}/20</p>
                                    </div>
                                @endif
                                @if($evaluacion->criterio_tecnico)
                                    <div class="bg-red-50 rounded p-2 text-center">
                                        <p class="text-xs text-red-600 font-medium">Técnico</p>
                                        <p class="text-sm font-bold text-red-900">{{ $evaluacion->criterio_tecnico }}/20</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Comentarios -->
                        @if($evaluacion->comentarios)
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-comment text-gray-500 mr-1"></i>
                                    Comentarios:
                                </p>
                                <p class="text-sm text-gray-600">{{ $evaluacion->comentarios }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
