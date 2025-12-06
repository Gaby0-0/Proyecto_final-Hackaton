@extends('layouts.estudiante')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Mis Constancias
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Tus constancias de participación y reconocimientos
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
                <h3 class="mt-2 text-lg font-medium text-gray-900">No tienes constancias aún</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Las constancias se generarán cuando participes en eventos.
                </p>
                <div class="mt-6">
                    <a href="{{ route('estudiante.eventos.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-calendar mr-2"></i>
                        Ver Eventos Disponibles
                    </a>
                </div>
            </div>
        @else
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-certificate text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total de Constancias
                                    </dt>
                                    <dd class="text-lg font-bold text-gray-900">
                                        {{ $constancias->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-trophy text-yellow-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        De Ganador
                                    </dt>
                                    <dd class="text-lg font-bold text-gray-900">
                                        {{ $constanciasGanador->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        De Participante
                                    </dt>
                                    <dd class="text-lg font-bold text-gray-900">
                                        {{ $constanciasParticipante->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Constancias -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($constancias as $constancia)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border-t-4
                        {{ $constancia->tipo === 'ganador' ? 'border-yellow-500' : 'border-blue-500' }}">

                        <!-- Icono y tipo -->
                        <div class="bg-gradient-to-br {{ $constancia->tipo === 'ganador' ? 'from-yellow-50 to-yellow-100' : 'from-blue-50 to-blue-100' }} p-6 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full {{ $constancia->tipo === 'ganador' ? 'bg-yellow-500' : 'bg-blue-600' }} mb-4">
                                <i class="fas {{ $constancia->tipo === 'ganador' ? 'fa-trophy' : 'fa-certificate' }} text-white text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold {{ $constancia->tipo === 'ganador' ? 'text-yellow-900' : 'text-blue-900' }}">
                                {{ $constancia->tipo === 'ganador' ? '🏆 GANADOR' : 'CONSTANCIA' }}
                            </h3>
                            <p class="text-sm {{ $constancia->tipo === 'ganador' ? 'text-yellow-700' : 'text-blue-700' }} mt-1">
                                {{ $constancia->tipo === 'ganador' ? 'Primer Lugar' : 'Participación' }}
                            </p>
                        </div>

                        <!-- Contenido -->
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $constancia->evento->nombre }}
                            </h4>

                            @if($constancia->equipo)
                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <i class="fas fa-users w-5 text-blue-600"></i>
                                    <span class="ml-2 font-medium">{{ $constancia->equipo->nombre }}</span>
                                </div>
                            @endif

                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt w-5 text-blue-600"></i>
                                    <span class="ml-2">{{ $constancia->evento->fecha_inicio->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-certificate w-5 text-blue-600"></i>
                                    <span class="ml-2 font-mono text-xs">{{ $constancia->numero_folio }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-check w-5 text-blue-600"></i>
                                    <span class="ml-2">Emitida: {{ $constancia->fecha_emision->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <p class="text-sm text-gray-700 italic mb-4">
                                {{ $constancia->descripcion }}
                            </p>

                            <!-- Botones -->
                            <div class="flex gap-2">
                                <a href="{{ route('estudiante.constancias.show', $constancia) }}"
                                   class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver
                                </a>
                                <a href="{{ route('estudiante.constancias.descargar', $constancia) }}"
                                   class="flex-1 text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-download mr-1"></i>
                                    Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
