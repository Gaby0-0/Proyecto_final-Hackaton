@extends('layouts.juez')

@section('title', 'Mis Constancias')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Mis Reconocimientos
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Reconocimientos por tu participación como juez evaluador
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

        @if($constancias->count() === 0)
            <!-- Empty State -->
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-certificate text-gray-400 text-6xl mb-4"></i>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No tienes reconocimientos</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Los reconocimientos se generarán cuando participes como juez evaluador en eventos.
                </p>
            </div>
        @else
            <!-- Lista de Constancias -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($constancias as $constancia)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border-t-4 border-purple-600">
                        <!-- Icono y tipo -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-600 mb-4">
                                <i class="fas fa-award text-white text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-purple-900">RECONOCIMIENTO</h3>
                            <p class="text-sm text-purple-700 mt-1">Juez Evaluador</p>
                        </div>

                        <!-- Contenido -->
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $constancia->evento->nombre }}
                            </h4>

                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt w-5 text-purple-600"></i>
                                    <span class="ml-2">{{ $constancia->evento->fecha_inicio->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-certificate w-5 text-purple-600"></i>
                                    <span class="ml-2 font-mono text-xs">{{ $constancia->numero_folio }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-check w-5 text-purple-600"></i>
                                    <span class="ml-2">Emitida: {{ $constancia->fecha_emision->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <p class="text-sm text-gray-700 italic mb-4">
                                {{ $constancia->descripcion }}
                            </p>

                            <!-- Botón para ver -->
                            <a href="{{ route('juez.constancias.show', $constancia) }}"
                               class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Reconocimiento
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
