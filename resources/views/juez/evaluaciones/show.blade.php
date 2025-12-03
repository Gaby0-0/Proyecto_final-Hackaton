@extends('layouts.juez')

@section('title', 'Detalles del Equipo')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('juez.evaluaciones.index') }}" class="text-purple-600 hover:text-purple-700 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Evaluaciones
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Principal del Equipo -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información del Equipo -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-6 text-white">
                    <h1 class="text-3xl font-bold mb-2">{{ $equipo->nombre }}</h1>
                    @if($equipo->proyecto)
                        <div class="flex items-center text-purple-100">
                            <i class="fas fa-project-diagram mr-2"></i>
                            <span class="text-lg">{{ $equipo->proyecto->nombre }}</span>
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    @if($equipo->descripcion)
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-2">Descripción del Equipo</h2>
                            <p class="text-gray-600">{{ $equipo->descripcion }}</p>
                        </div>
                    @endif

                    @if($equipo->proyecto && $equipo->proyecto->descripcion)
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-2">Descripción del Proyecto</h2>
                            <p class="text-gray-600">{{ $equipo->proyecto->descripcion }}</p>
                        </div>
                    @endif

                    <!-- Estadísticas -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-600 mb-1">
                                <i class="fas fa-users mr-2"></i>
                                <span class="text-sm">Integrantes</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $equipo->miembros->count() }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-600 mb-1">
                                <i class="fas fa-star mr-2"></i>
                                <span class="text-sm">Evaluaciones</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">{{ $equipo->evaluaciones->count() }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center text-gray-600 mb-1">
                                <i class="fas fa-chart-line mr-2"></i>
                                <span class="text-sm">Promedio</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $equipo->evaluaciones->count() > 0 ? number_format($equipo->evaluaciones->avg('puntuacion'), 1) : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <!-- Miembros del Equipo -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Miembros del Equipo</h2>
                        <div class="space-y-3">
                            @foreach($equipo->miembros as $miembro)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                            {{ strtoupper(substr($miembro->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $miembro->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $miembro->email }}</p>
                                        </div>
                                    </div>
                                    @if($miembro->pivot->rol_equipo == 'lider')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">
                                            <i class="fas fa-crown"></i> Líder
                                        </span>
                                    @else
                                        <span class="bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded-full">
                                            Miembro
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Todas las Evaluaciones -->
            @if($equipo->evaluaciones->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Todas las Evaluaciones</h2>
                    <div class="space-y-4">
                        @foreach($equipo->evaluaciones as $evaluacion)
                            <div class="border rounded-lg p-4 {{ $evaluacion->evaluador_id == auth()->id() ? 'border-purple-300 bg-purple-50' : 'border-gray-200' }}">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                            {{ strtoupper(substr($evaluacion->evaluador->name ?? 'N/A', 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ $evaluacion->evaluador->name ?? 'Evaluador no disponible' }}
                                                @if($evaluacion->evaluador_id == auth()->id())
                                                    <span class="text-purple-600 text-sm">(Tu evaluación)</span>
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-600">{{ $evaluacion->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-purple-600">{{ $evaluacion->puntuacion }}</p>
                                        <p class="text-xs text-gray-500">puntos</p>
                                    </div>
                                </div>

                                <!-- Criterios -->
                                @if($evaluacion->criterio_innovacion || $evaluacion->criterio_funcionalidad || $evaluacion->criterio_presentacion || $evaluacion->criterio_impacto || $evaluacion->criterio_tecnico)
                                    <div class="grid grid-cols-5 gap-2 mb-3">
                                        @if($evaluacion->criterio_innovacion)
                                            <div class="text-center bg-white rounded p-2">
                                                <p class="text-xs text-gray-600">Innovación</p>
                                                <p class="font-bold text-purple-600">{{ $evaluacion->criterio_innovacion }}</p>
                                            </div>
                                        @endif
                                        @if($evaluacion->criterio_funcionalidad)
                                            <div class="text-center bg-white rounded p-2">
                                                <p class="text-xs text-gray-600">Funcionalidad</p>
                                                <p class="font-bold text-purple-600">{{ $evaluacion->criterio_funcionalidad }}</p>
                                            </div>
                                        @endif
                                        @if($evaluacion->criterio_presentacion)
                                            <div class="text-center bg-white rounded p-2">
                                                <p class="text-xs text-gray-600">Presentación</p>
                                                <p class="font-bold text-purple-600">{{ $evaluacion->criterio_presentacion }}</p>
                                            </div>
                                        @endif
                                        @if($evaluacion->criterio_impacto)
                                            <div class="text-center bg-white rounded p-2">
                                                <p class="text-xs text-gray-600">Impacto</p>
                                                <p class="font-bold text-purple-600">{{ $evaluacion->criterio_impacto }}</p>
                                            </div>
                                        @endif
                                        @if($evaluacion->criterio_tecnico)
                                            <div class="text-center bg-white rounded p-2">
                                                <p class="text-xs text-gray-600">Técnico</p>
                                                <p class="font-bold text-purple-600">{{ $evaluacion->criterio_tecnico }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if($evaluacion->comentarios)
                                    <div class="bg-white rounded p-3">
                                        <p class="text-sm text-gray-700">{{ $evaluacion->comentarios }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Panel de Acciones -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Acciones</h2>

                @if($miEvaluacion)
                    <!-- Ya evaluado -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <p class="text-green-800 font-medium mb-2">
                            <i class="fas fa-check-circle mr-1"></i>
                            Ya evaluaste este equipo
                        </p>
                        <p class="text-green-700 text-sm mb-1">Puntuación: <span class="font-bold">{{ $miEvaluacion->puntuacion }}/100</span></p>
                        <p class="text-green-600 text-xs">Evaluado {{ $miEvaluacion->created_at->diffForHumans() }}</p>
                    </div>

                    <a href="{{ route('juez.evaluaciones.editar', $equipo) }}"
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-semibold text-center block mb-3 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Evaluación
                    </a>
                @else
                    <!-- Sin evaluar -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <p class="text-yellow-800 font-medium mb-1">
                            <i class="fas fa-clock mr-1"></i>
                            Pendiente de evaluar
                        </p>
                        <p class="text-yellow-600 text-sm">Evalúa este equipo basándote en su proyecto</p>
                    </div>

                    <a href="{{ route('juez.evaluaciones.crear', $equipo) }}"
                       class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-semibold text-center block mb-3 transition-colors">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Evaluar Equipo
                    </a>
                @endif

                <a href="{{ route('juez.evaluaciones.index') }}"
                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-lg font-semibold text-center block transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Listado
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
