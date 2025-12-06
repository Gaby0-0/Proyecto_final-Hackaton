@extends('layouts.juez')

@section('title', 'Mis Evaluaciones')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Eventos Asignados</h1>
            <p class="text-gray-600 mt-1">Selecciona un evento para evaluar los equipos participantes</p>
        </div>

        @if($eventosAsignados->count() > 0)
            <!-- Lista de eventos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventosAsignados as $evento)
                    <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow overflow-hidden border-t-4 border-purple-600">
                        <!-- Header del evento -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6">
                            <h3 class="text-lg font-bold text-purple-900">{{ $evento->nombre }}</h3>
                            <p class="text-sm text-purple-700 mt-1">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $evento->fecha_inicio->format('d/m/Y') }}
                            </p>
                        </div>

                        <!-- Contenido -->
                        <div class="p-6">
                            <!-- Descripción -->
                            @if($evento->descripcion)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $evento->descripcion }}</p>
                            @endif

                            <!-- Estadísticas -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-purple-50 rounded-lg p-3 text-center">
                                    <div class="flex items-center justify-center text-purple-600 mb-1">
                                        <i class="fas fa-users text-sm"></i>
                                    </div>
                                    <p class="text-xs text-purple-700">Equipos</p>
                                    <p class="text-lg font-bold text-purple-900">{{ $evento->equipos_con_proyecto }}</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3 text-center">
                                    <div class="flex items-center justify-center text-green-600 mb-1">
                                        <i class="fas fa-check-circle text-sm"></i>
                                    </div>
                                    <p class="text-xs text-green-700">Evaluados</p>
                                    <p class="text-lg font-bold text-green-900">{{ $evento->equipos_evaluados }}</p>
                                </div>
                            </div>

                            <!-- Progreso -->
                            @if($evento->equipos_con_proyecto > 0)
                                @php
                                    $porcentaje = ($evento->equipos_evaluados / $evento->equipos_con_proyecto) * 100;
                                @endphp
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-medium text-gray-700">Progreso</span>
                                        <span class="text-xs font-bold text-gray-900">{{ number_format($porcentaje, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full transition-all duration-300" style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <!-- Estado -->
                            @if($evento->equipos_evaluados >= $evento->equipos_con_proyecto && $evento->equipos_con_proyecto > 0)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                    <p class="text-green-800 text-sm font-medium text-center">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Todos los equipos evaluados
                                    </p>
                                </div>
                            @elseif($evento->equipos_evaluados > 0)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                    <p class="text-yellow-800 text-sm font-medium text-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        En progreso
                                    </p>
                                </div>
                            @else
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                    <p class="text-blue-800 text-sm font-medium text-center">
                                        <i class="fas fa-hourglass-start mr-1"></i>
                                        Sin evaluar
                                    </p>
                                </div>
                            @endif

                            <!-- Botón de acción -->
                            <a href="{{ route('juez.evaluaciones.evento', $evento) }}"
                               class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                                <i class="fas fa-clipboard-list mr-2"></i>
                                Ver Equipos
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes eventos asignados</h3>
                <p class="text-gray-500">
                    Actualmente no tienes eventos asignados para evaluar. Contacta al administrador si crees que esto es un error.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
