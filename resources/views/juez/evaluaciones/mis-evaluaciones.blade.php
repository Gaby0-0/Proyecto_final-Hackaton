@extends('layouts.juez')

@section('title', 'Mis Evaluaciones')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mis Evaluaciones</h1>
                <p class="text-gray-600 mt-1">Historial completo de tus evaluaciones realizadas</p>
            </div>
            <div class="text-right">
                <p class="text-4xl font-bold text-purple-600">{{ $evaluaciones->total() }}</p>
                <p class="text-sm text-gray-600">Evaluaciones totales</p>
            </div>
        </div>
    </div>

    <!-- Lista de Evaluaciones -->
    @if($evaluaciones->count() > 0)
        <div class="space-y-4">
            @foreach($evaluaciones as $evaluacion)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Información del Equipo -->
                        <div class="md:col-span-5 p-6 border-r border-gray-100">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-lg mr-4 flex-shrink-0">
                                    {{ strtoupper(substr($evaluacion->equipo->nombre, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                        {{ $evaluacion->equipo->nombre }}
                                    </h3>
                                    @if($evaluacion->equipo->proyecto)
                                        <p class="text-sm text-gray-600 mb-2">
                                            <i class="fas fa-project-diagram mr-1"></i>
                                            {{ $evaluacion->equipo->proyecto->nombre }}
                                        </p>
                                    @endif
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $evaluacion->equipo->miembros->count() }} miembros
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $evaluacion->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Puntuación y Criterios -->
                        <div class="md:col-span-5 p-6 bg-gray-50">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Puntuación Total</p>
                                    <p class="text-3xl font-bold text-purple-600">
                                        {{ $evaluacion->puntuacion }}
                                        <span class="text-lg text-gray-500">/100</span>
                                    </p>
                                </div>
                            </div>

                            @if($evaluacion->criterio_innovacion || $evaluacion->criterio_funcionalidad || $evaluacion->criterio_presentacion || $evaluacion->criterio_impacto || $evaluacion->criterio_tecnico)
                                <div class="grid grid-cols-5 gap-2">
                                    @if($evaluacion->criterio_innovacion)
                                        <div class="text-center bg-white rounded p-2">
                                            <p class="text-xs text-gray-600 mb-1" title="Innovación">
                                                <i class="fas fa-lightbulb text-yellow-500"></i>
                                            </p>
                                            <p class="font-bold text-purple-600">{{ $evaluacion->criterio_innovacion }}</p>
                                        </div>
                                    @endif
                                    @if($evaluacion->criterio_funcionalidad)
                                        <div class="text-center bg-white rounded p-2">
                                            <p class="text-xs text-gray-600 mb-1" title="Funcionalidad">
                                                <i class="fas fa-cogs text-blue-500"></i>
                                            </p>
                                            <p class="font-bold text-purple-600">{{ $evaluacion->criterio_funcionalidad }}</p>
                                        </div>
                                    @endif
                                    @if($evaluacion->criterio_presentacion)
                                        <div class="text-center bg-white rounded p-2">
                                            <p class="text-xs text-gray-600 mb-1" title="Presentación">
                                                <i class="fas fa-presentation text-green-500"></i>
                                            </p>
                                            <p class="font-bold text-purple-600">{{ $evaluacion->criterio_presentacion }}</p>
                                        </div>
                                    @endif
                                    @if($evaluacion->criterio_impacto)
                                        <div class="text-center bg-white rounded p-2">
                                            <p class="text-xs text-gray-600 mb-1" title="Impacto">
                                                <i class="fas fa-bullseye text-red-500"></i>
                                            </p>
                                            <p class="font-bold text-purple-600">{{ $evaluacion->criterio_impacto }}</p>
                                        </div>
                                    @endif
                                    @if($evaluacion->criterio_tecnico)
                                        <div class="text-center bg-white rounded p-2">
                                            <p class="text-xs text-gray-600 mb-1" title="Técnico">
                                                <i class="fas fa-code text-indigo-500"></i>
                                            </p>
                                            <p class="font-bold text-purple-600">{{ $evaluacion->criterio_tecnico }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($evaluacion->comentarios)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-600 mb-1">Comentarios:</p>
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $evaluacion->comentarios }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Acciones -->
                        <div class="md:col-span-2 p-6 flex flex-col justify-center gap-2">
                            <a href="{{ route('juez.evaluaciones.show', $evaluacion->equipo) }}"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Ver Equipo
                            </a>
                            <a href="{{ route('juez.evaluaciones.editar', $evaluacion->equipo) }}"
                               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $evaluaciones->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No has realizado evaluaciones</h3>
            <p class="text-gray-500 mb-6">Comienza a evaluar equipos para que aparezcan aquí.</p>
            <a href="{{ route('juez.evaluaciones.index') }}"
               class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                <i class="fas fa-clipboard-check mr-2"></i>
                Ver Equipos a Evaluar
            </a>
        </div>
    @endif
</div>
@endsection
