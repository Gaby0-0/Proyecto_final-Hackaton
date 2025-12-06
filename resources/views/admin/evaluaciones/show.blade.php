@extends('layouts.admin')

@section('title', 'Detalles de Evaluación')

@php
$pageTitle = 'Detalles de Evaluación';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Evaluaciones', 'url' => route('admin.evaluaciones.index')],
    ['name' => 'Detalles']
];
$pageActions = '';
@endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Panel principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Información de la Evaluación -->
        <x-admin.card>
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-star text-blue-600 mr-2"></i>
                Información de la Evaluación
            </h2>

            <div class="space-y-4">
                <div class="flex items-center justify-center py-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-2">Puntuación Final</p>
                        <p class="text-5xl font-bold
                            @if($evaluacion->puntuacion >= 90) text-green-600
                            @elseif($evaluacion->puntuacion >= 70) text-blue-600
                            @elseif($evaluacion->puntuacion >= 50) text-yellow-600
                            @else text-red-600
                            @endif">
                            {{ number_format($evaluacion->puntuacion, 1) }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">de 100 puntos</p>
                    </div>
                </div>

                @if($evaluacion->comentarios)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        <i class="fas fa-comment mr-1"></i>
                        Comentarios del Evaluador
                    </label>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded-lg whitespace-pre-line">{{ $evaluacion->comentarios }}</p>
                </div>
                @endif

                <div class="pt-4 border-t">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Fecha de Evaluación</label>
                    <p class="text-gray-900">
                        <i class="fas fa-calendar-check mr-2 text-blue-600"></i>
                        {{ $evaluacion->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </x-admin.card>

        <!-- Información del Equipo Evaluado -->
        <x-admin.card>
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-users text-blue-600 mr-2"></i>
                Equipo Evaluado
            </h2>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 text-lg mb-3">{{ $evaluacion->equipo->nombre }}</h3>

                @if($evaluacion->equipo->miembros->isNotEmpty())
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-3">
                            <i class="fas fa-user-friends mr-1"></i>
                            Miembros del Equipo ({{ $evaluacion->equipo->miembros->count() }})
                        </p>
                        <div class="space-y-2">
                            @foreach($evaluacion->equipo->miembros as $miembro)
                                <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                        {{ substr($miembro->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $miembro->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $miembro->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </x-admin.card>
    </div>

    <!-- Panel lateral -->
    <div class="space-y-6">
        <!-- Información del Evento -->
        <x-admin.card>
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                Evento
            </h3>
            <div>
                <p class="font-semibold text-gray-900">{{ $evaluacion->evento->nombre }}</p>
                @if($evaluacion->evento->categoria)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 mt-2">
                        {{ $evaluacion->evento->categoria }}
                    </span>
                @endif
            </div>
        </x-admin.card>

        <!-- Información del Evaluador -->
        <x-admin.card>
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                Evaluador
            </h3>
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-lg">
                    {{ substr($evaluacion->evaluador->name, 0, 2) }}
                </div>
                <div>
                    <p class="font-medium text-gray-900">{{ $evaluacion->evaluador->name }}</p>
                    <p class="text-sm text-gray-500">{{ $evaluacion->evaluador->email }}</p>
                </div>
            </div>
        </x-admin.card>

        <!-- Acciones -->
        <x-admin.card>
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-tasks text-blue-600 mr-2"></i>
                Acciones
            </h3>
            <div class="space-y-2">
                <a href="{{ route('admin.equipos.show', $evaluacion->equipo_id) }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-users mr-2"></i>
                    Ver Equipo
                </a>

                <a href="{{ route('admin.eventos.show', $evaluacion->evento_id) }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-calendar mr-2"></i>
                    Ver Evento
                </a>

                <a href="{{ route('admin.evaluaciones.index') }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Evaluaciones
                </a>

                <form action="{{ route('admin.evaluaciones.destroy', $evaluacion->id) }}"
                      method="POST"
                      onsubmit="return confirm('¿Está seguro de eliminar esta evaluación?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Eliminar Evaluación
                    </button>
                </form>
            </div>
        </x-admin.card>
    </div>
</div>
@endsection
