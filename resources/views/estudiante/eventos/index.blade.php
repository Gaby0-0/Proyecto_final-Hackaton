@extends('layouts.estudiante')

@section('title', 'Eventos Disponibles')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Eventos Disponibles</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Información sobre equipos -->
    @if($misEquipos->count() === 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">No tienes equipos</h3>
                    <p class="text-yellow-700 mb-3">Para inscribirte a un evento, primero debes crear o unirte a un equipo.</p>
                    <a href="{{ route('estudiante.equipos.index') }}" class="inline-flex items-center bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-users mr-2"></i>
                        Ir a Mis Equipos
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Mis Equipos e Inscripciones -->
    @if($misEquipos->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mis Equipos e Inscripciones</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($misEquipos as $equipo)
                    <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $equipo->nombre }}</h3>
                            @php
                                $esLider = $equipo->miembros->where('id', auth()->id())->first()->pivot->rol_equipo == 'lider';
                            @endphp
                            @if($esLider)
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-crown"></i> Líder
                                </span>
                            @endif
                        </div>

                        @if($equipo->eventos->count() > 0)
                            <div class="mt-3 space-y-2">
                                <p class="text-sm font-semibold text-gray-700">Inscripciones:</p>
                                @foreach($equipo->eventos as $evento)
                                    @php
                                        $estadoBadge = match($evento->pivot->estado) {
                                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                                            'inscrito' => 'bg-green-100 text-green-800',
                                            'participando' => 'bg-blue-100 text-blue-800',
                                            'finalizado' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        $estadoTexto = match($evento->pivot->estado) {
                                            'pendiente' => 'Pendiente',
                                            'inscrito' => 'Inscrito',
                                            'participando' => 'Participando',
                                            'finalizado' => 'Finalizado',
                                            default => 'Desconocido'
                                        };
                                    @endphp
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">{{ Str::limit($evento->nombre, 30) }}</span>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $estadoBadge }}">
                                            {{ $estadoTexto }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 mt-3">Sin inscripciones aún</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Lista de Eventos Disponibles -->
    <div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Todos los Eventos</h2>
        @if($eventosDisponibles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventosDisponibles as $evento)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                        <!-- Header del evento con categoría -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold">{{ $evento->nombre }}</h3>
                                @if($evento->categoria)
                                    <span class="bg-white bg-opacity-25 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $evento->categoria }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <span>{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <!-- Contenido del evento -->
                        <div class="p-4">
                            @if($evento->descripcion)
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($evento->descripcion, 100) }}</p>
                            @endif

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                                    <span class="capitalize">{{ $evento->modalidad }}</span>
                                </div>

                                @if($evento->tipo)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-tag mr-2 w-4"></i>
                                        <span>{{ $evento->tipo }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users mr-2 w-4"></i>
                                    <span>
                                        {{ $evento->equipos_aprobados_count }} / {{ $evento->max_equipos }} equipos
                                        @if($evento->tieneCupoDisponible())
                                            <span class="text-green-600 font-medium">(Cupo disponible)</span>
                                        @else
                                            <span class="text-red-600 font-medium">(Cupo lleno)</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="flex items-center text-sm">
                                    @php
                                        $disponible = $evento->estaDisponibleParaInscripcion();
                                    @endphp
                                    @if($disponible)
                                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Disponible
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            No disponible
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('estudiante.eventos.show', $evento) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-lg font-medium transition-colors">
                                Ver Detalles e Inscribirse
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-600 mb-2">No hay eventos disponibles en este momento</p>
                <p class="text-gray-500 text-sm">Vuelve más tarde para ver los nuevos eventos</p>
            </div>
        @endif
    </div>
</div>
@endsection
