@extends('layouts.estudiante')

@section('title', 'Detalles del Evento')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('estudiante.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="{{ route('estudiante.eventos.index') }}" class="hover:text-blue-600">Eventos</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-medium">{{ $evento->nombre }}</li>
        </ol>
    </nav>

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

    <!-- Información del Evento -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $evento->nombre }}</h1>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="inline-flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}
                        </span>
                        <span class="inline-flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span class="capitalize">{{ $evento->modalidad }}</span>
                        </span>
                    </div>
                </div>
                @if($evento->categoria)
                    <span class="bg-white bg-opacity-25 text-white px-4 py-2 rounded-lg font-medium">
                        {{ $evento->categoria }}
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6">
            <!-- Descripción -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Descripción</h2>
                <p class="text-gray-700 leading-relaxed">{{ $evento->descripcion ?? 'Sin descripción disponible.' }}</p>
            </div>

            <!-- Información Adicional -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center text-gray-600 mb-1">
                        <i class="fas fa-calendar-check mr-2"></i>
                        <span class="text-sm font-medium">Inicio</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center text-gray-600 mb-1">
                        <i class="fas fa-calendar-times mr-2"></i>
                        <span class="text-sm font-medium">Fin</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y H:i') }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center text-gray-600 mb-1">
                        <i class="fas fa-users mr-2"></i>
                        <span class="text-sm font-medium">Equipos</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">{{ $evento->equiposAprobados->count() }} / {{ $evento->max_equipos }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center text-gray-600 mb-1">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span class="text-sm font-medium">Estado</span>
                    </div>
                    @if($evento->estaDisponibleParaInscripcion())
                        <p class="text-lg font-semibold text-green-600">Disponible</p>
                    @else
                        <p class="text-lg font-semibold text-red-600">No disponible</p>
                    @endif
                </div>
            </div>

            <!-- Información sobre la categoría -->
            @if($evento->categoria)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Importante sobre las categorías</p>
                            <p>Este evento pertenece a la categoría "{{ $evento->categoria }}". Tu equipo solo puede inscribirse a un evento por categoría.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Mis Equipos e Inscripción -->
    @if($equiposConEstado->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Inscribir Equipos</h2>

            @foreach($equiposConEstado as $item)
                @php
                    $equipo = $item['equipo'];
                    $yaInscrito = $item['yaInscrito'];
                    $puedeInscribirse = $item['puedeInscribirse'];
                    $esLider = $item['esLider'];
                    $estadoInscripcion = $item['estadoInscripcion'];
                @endphp

                <div class="border rounded-lg p-4 mb-4 {{ $yaInscrito ? 'bg-gray-50 border-gray-300' : 'border-gray-200' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h3 class="text-lg font-bold text-gray-900 mr-2">{{ $equipo->nombre }}</h3>
                                @if($esLider)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                        <i class="fas fa-crown"></i> Líder
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                <i class="fas fa-users mr-2"></i>
                                <span>{{ $equipo->miembros->count() }} miembros</span>
                            </div>

                            @if($yaInscrito)
                                @php
                                    $estadoBadge = match($estadoInscripcion) {
                                        'pendiente' => 'bg-yellow-100 text-yellow-800',
                                        'inscrito' => 'bg-green-100 text-green-800',
                                        'participando' => 'bg-blue-100 text-blue-800',
                                        'finalizado' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    $estadoTexto = match($estadoInscripcion) {
                                        'pendiente' => 'Solicitud Pendiente',
                                        'inscrito' => 'Inscrito',
                                        'participando' => 'Participando',
                                        'finalizado' => 'Finalizado',
                                        default => 'Desconocido'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $estadoBadge }}">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ $estadoTexto }}
                                </span>
                            @elseif(!$puedeInscribirse)
                                <div class="flex items-start text-sm text-red-700 bg-red-50 rounded p-2">
                                    <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                                    <span>Este equipo ya está inscrito en otro evento de la categoría "{{ $evento->categoria }}"</span>
                                </div>
                            @endif
                        </div>

                        <div class="ml-4">
                            @if($yaInscrito)
                                @if($esLider && $estadoInscripcion === 'pendiente')
                                    <form action="{{ route('estudiante.eventos.cancelar', $evento) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="equipo_id" value="{{ $equipo->id }}">
                                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded text-sm font-medium" onclick="return confirm('¿Estás seguro de que quieres cancelar la inscripción?')">
                                            Cancelar Inscripción
                                        </button>
                                    </form>
                                @endif
                            @elseif($puedeInscribirse && $evento->estaDisponibleParaInscripcion())
                                @if($esLider)
                                    <form action="{{ route('estudiante.eventos.inscribir', $evento) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="equipo_id" value="{{ $equipo->id }}">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-medium" onclick="return confirm('¿Confirmas que deseas inscribir a tu equipo en este evento?')">
                                            <i class="fas fa-paper-plane mr-1"></i>
                                            Inscribir Equipo
                                        </button>
                                    </form>
                                @else
                                    <span class="text-sm text-gray-600 italic">Solo el líder puede inscribir</span>
                                @endif
                            @elseif(!$evento->estaDisponibleParaInscripcion())
                                <span class="text-sm text-gray-600 italic">Evento no disponible</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">No tienes equipos</h3>
                    <p class="text-yellow-700 mb-3">Para inscribirte a este evento, primero debes crear o unirte a un equipo.</p>
                    <a href="{{ route('estudiante.equipos.index') }}" class="inline-flex items-center bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-users mr-2"></i>
                        Ir a Mis Equipos
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Equipos Inscritos -->
    @if($evento->equiposAprobados->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Equipos Participantes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($evento->equiposAprobados as $equipo)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                        <h3 class="font-bold text-gray-900 mb-2">{{ $equipo->nombre }}</h3>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-users mr-2"></i>
                            <span>{{ $equipo->miembros->count() }} integrantes</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
