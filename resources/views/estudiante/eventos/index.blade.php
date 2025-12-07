@extends('layouts.estudiante')

@section('title', 'Eventos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Eventos del Hackaton</h1>
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

    <!-- Eventos en Curso en los que Estoy Inscrito -->
    @if($eventosInscritos->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-running text-blue-600 mr-2"></i>
                Mis Eventos en Curso
            </h2>
            <p class="text-gray-600 mb-4 text-sm">Eventos que se están realizando actualmente y en los que estás participando</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventosInscritos as $evento)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden border-l-4 border-green-500">
                        <!-- Header del evento -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-4 text-white">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold">{{ $evento->nombre }}</h3>
                                @php
                                    $estadoBadge = match($evento->estado_inscripcion) {
                                        'pendiente' => 'bg-yellow-400 text-yellow-900',
                                        'inscrito' => 'bg-green-200 text-green-900',
                                        'participando' => 'bg-blue-200 text-blue-900',
                                        default => 'bg-gray-200 text-gray-900'
                                    };
                                    $estadoTexto = match($evento->estado_inscripcion) {
                                        'pendiente' => 'Pendiente',
                                        'inscrito' => 'Inscrito',
                                        'participando' => 'Participando',
                                        default => 'Estado desconocido'
                                    };
                                @endphp
                                <span class="text-xs px-2 py-1 rounded-full {{ $estadoBadge }}">
                                    {{ $estadoTexto }}
                                </span>
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="p-4">
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-1"><i class="fas fa-users text-blue-500 mr-2"></i><strong>Equipo:</strong> {{ $evento->equipo_inscrito->nombre }}</p>
                                <p class="text-sm text-gray-600"><i class="fas fa-calendar text-blue-500 mr-2"></i><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}</p>
                            </div>

                            <a href="{{ route('estudiante.eventos.show', $evento) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-lg font-medium transition-colors">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Lista de Eventos Disponibles -->
    <div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-calendar-plus text-green-600 mr-2"></i>
            Eventos Abiertos a Inscripciones
        </h2>
        <p class="text-gray-600 mb-4 text-sm">Eventos que están abiertos para inscripciones y tienen cupo disponible</p>
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
