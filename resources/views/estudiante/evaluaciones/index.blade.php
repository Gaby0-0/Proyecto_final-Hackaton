@extends('layouts.estudiante')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Mis Evaluaciones
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Consulta las calificaciones de tus equipos en los diferentes eventos
                </p>
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

        @if(count($evaluacionesPorEquipo) === 0)
            <!-- Empty State -->
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-star text-gray-400 text-6xl mb-4"></i>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No tienes evaluaciones</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Tus equipos aún no han sido evaluados en ningún evento.
                </p>
                <div class="mt-6">
                    <a href="{{ route('estudiante.proyectos.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-project-diagram mr-2"></i>
                        Ver mis proyectos
                    </a>
                </div>
            </div>
        @else
            <!-- Lista de Evaluaciones -->
            <div class="space-y-6">
                @foreach($evaluacionesPorEquipo as $item)
                    <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="sm:flex sm:items-start sm:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <h3 class="text-xl font-semibold text-gray-900">
                                            {{ $item['equipo']->nombre }}
                                        </h3>
                                        <span class="ml-3 inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $item['evento']->nombre }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $item['evento']->descripcion }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <!-- Promedio General -->
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-blue-600">Promedio General</p>
                                            <p class="mt-1 text-3xl font-bold text-blue-900">
                                                {{ number_format($item['promedio'], 1) }}
                                            </p>
                                        </div>
                                        <div class="bg-blue-200 rounded-full p-3">
                                            <i class="fas fa-star text-blue-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-blue-600">
                                        de 100 puntos
                                    </p>
                                </div>

                                <!-- Total Evaluaciones -->
                                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-green-600">Evaluaciones Recibidas</p>
                                            <p class="mt-1 text-3xl font-bold text-green-900">
                                                {{ $item['total_evaluaciones'] }}
                                            </p>
                                        </div>
                                        <div class="bg-green-200 rounded-full p-3">
                                            <i class="fas fa-users text-green-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-green-600">
                                        {{ $item['total_evaluaciones'] == 1 ? 'juez' : 'jueces' }}
                                    </p>
                                </div>

                                <!-- Acción -->
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('estudiante.evaluaciones.ver', ['equipo' => $item['equipo']->id, 'evento' => $item['evento']->id]) }}"
                                       class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        <i class="fas fa-eye mr-2"></i>
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
