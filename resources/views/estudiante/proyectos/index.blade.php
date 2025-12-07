@extends('layouts.estudiante')

@section('title', 'Mis Proyectos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-project-diagram text-blue-600 mr-3"></i>
            Mis Proyectos Activos
        </h1>
        <p class="text-gray-600 mt-2">Gestiona los proyectos de tus equipos en eventos activos donde estás participando</p>
    </div>

    @if($equipos->isEmpty())
        <!-- Estado vacío -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="flex flex-col items-center">
                <i class="fas fa-project-diagram text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes proyectos activos</h3>
                <p class="text-gray-500 mb-6">Inscríbete a un evento activo con tu equipo para empezar a trabajar en proyectos</p>
                <a href="{{ route('estudiante.eventos.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Ver Eventos Disponibles
                </a>
            </div>
        </div>
    @else
        <!-- Lista de equipos y sus proyectos -->
        <div class="space-y-6">
            @foreach($equipos as $equipo)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Encabezado del equipo -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-users text-white text-2xl mr-3"></i>
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ $equipo->nombre }}</h3>
                                    <p class="text-blue-100 text-sm">
                                        {{ $equipo->miembros->count() }} miembros
                                        @if($equipo->usuarioEsLider(auth()->id()))
                                            <span class="ml-2 px-2 py-0.5 bg-yellow-400 text-yellow-900 text-xs rounded-full">
                                                <i class="fas fa-crown mr-1"></i>Líder
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-white/20 text-white text-sm rounded-full">
                                {{ $equipo->codigo }}
                            </span>
                        </div>
                    </div>

                    <!-- Proyectos del equipo -->
                    <div class="p-6">
                        @if($equipo->eventos->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <p>Este equipo no está inscrito en ningún evento</p>
                                <a href="{{ route('estudiante.eventos.index') }}" class="text-blue-600 hover:text-blue-700 mt-2 inline-block">
                                    <i class="fas fa-plus mr-1"></i>Inscribir en evento
                                </a>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($equipo->eventos as $evento)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <h4 class="text-lg font-semibold text-gray-900">{{ $evento->nombre }}</h4>
                                                    <span class="ml-3 px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $evento->pivot->estado === 'inscrito' ? 'bg-blue-100 text-blue-800' : '' }}
                                                        {{ $evento->pivot->estado === 'participando' ? 'bg-green-100 text-green-800' : '' }}
                                                        {{ $evento->pivot->estado === 'finalizado' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                        {{ ucfirst($evento->pivot->estado) }}
                                                    </span>
                                                    @if($evento->categoria)
                                                        <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            {{ $evento->categoria }}
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($evento->pivot->proyecto_titulo)
                                                    <div class="mb-3">
                                                        <p class="text-sm text-gray-600 mb-1">
                                                            <i class="fas fa-project-diagram mr-1"></i>
                                                            <strong>Proyecto:</strong> {{ $evento->pivot->proyecto_titulo }}
                                                        </p>
                                                        @if($evento->pivot->proyecto_descripcion)
                                                            <p class="text-sm text-gray-500 ml-5">
                                                                {{ Str::limit($evento->pivot->proyecto_descripcion, 150) }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @else
                                                    <p class="text-sm text-amber-600 mb-3">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        Proyecto no configurado
                                                    </p>
                                                @endif

                                                <!-- Información de avances y entrega -->
                                                <div class="flex gap-4 text-sm text-gray-600">
                                                    @php
                                                        $avances = $evento->pivot->avances ? count(json_decode($evento->pivot->avances, true)) : 0;
                                                    @endphp
                                                    <span>
                                                        <i class="fas fa-tasks mr-1"></i>
                                                        {{ $avances }} avance{{ $avances !== 1 ? 's' : '' }}
                                                    </span>

                                                    @if($evento->pivot->proyecto_final_url)
                                                        <span class="text-green-600">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Proyecto final entregado
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Proyecto final pendiente
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="ml-4">
                                                <a href="{{ route('estudiante.proyectos.edit', ['equipo' => $equipo->id, 'evento' => $evento->id]) }}"
                                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-edit mr-2"></i>
                                                    Gestionar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
