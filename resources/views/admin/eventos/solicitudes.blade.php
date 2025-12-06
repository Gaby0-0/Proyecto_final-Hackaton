@extends('layouts.admin')

@section('title', 'Solicitudes de Inscripción')

@php
$pageTitle = 'Solicitudes de Inscripción - ' . $evento->nombre;
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Eventos', 'url' => route('admin.eventos.index')],
    ['name' => $evento->nombre, 'url' => route('admin.eventos.show', $evento)],
    ['name' => 'Solicitudes']
];
@endphp

@section('content')
<div class="mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $evento->nombre }}</h2>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span class="inline-flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
                    </span>
                    @if($evento->categoria)
                        <span class="inline-flex items-center">
                            <i class="fas fa-tag mr-2"></i>
                            {{ $evento->categoria }}
                        </span>
                    @endif
                    <span class="inline-flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        {{ $equiposAprobados->count() }} / {{ $evento->max_equipos }} equipos inscritos
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.eventos.show', $evento) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver al Evento
            </a>
        </div>
    </div>
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

<!-- Solicitudes Pendientes -->
<x-admin.card class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-900">Solicitudes Pendientes</h3>
        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
            {{ $solicitudesPendientes->count() }} pendientes
        </span>
    </div>

    @if($solicitudesPendientes->count() > 0)
        <div class="space-y-4">
            @foreach($solicitudesPendientes as $equipo)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h4 class="text-lg font-bold text-gray-900">{{ $equipo->nombre }}</h4>
                                <span class="ml-3 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                    Pendiente
                                </span>
                            </div>

                            @if($equipo->descripcion)
                                <p class="text-gray-600 text-sm mb-3">{{ $equipo->descripcion }}</p>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users mr-2"></i>
                                    <span>{{ $equipo->miembros->count() }} integrantes</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-2"></i>
                                    <span>Solicitud: {{ \Carbon\Carbon::parse($equipo->pivot->fecha_inscripcion)->format('d/m/Y') }}</span>
                                </div>

                                @if($equipo->proyecto)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-project-diagram mr-2"></i>
                                        <span>{{ $equipo->proyecto->nombre }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Integrantes -->
                            <div class="mt-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Integrantes:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($equipo->miembros as $miembro)
                                        <span class="inline-flex items-center bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                                            @if($miembro->pivot->rol_equipo === 'lider')
                                                <i class="fas fa-crown text-yellow-600 mr-1"></i>
                                            @endif
                                            {{ $miembro->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="ml-4 flex flex-col space-y-2">
                            @if($evento->tieneCupoDisponible())
                                <form action="{{ route('admin.eventos.aprobar-solicitud', [$evento, $equipo]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-medium whitespace-nowrap" onclick="return confirm('¿Aprobar la solicitud de este equipo?')">
                                        <i class="fas fa-check mr-1"></i>
                                        Aprobar
                                    </button>
                                </form>
                            @else
                                <span class="bg-gray-100 text-gray-600 px-4 py-2 rounded text-sm text-center">
                                    Cupo lleno
                                </span>
                            @endif

                            <form action="{{ route('admin.eventos.rechazar-solicitud', [$evento, $equipo]) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-medium whitespace-nowrap" onclick="return confirm('¿Rechazar la solicitud de este equipo?')">
                                    <i class="fas fa-times mr-1"></i>
                                    Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-600">No hay solicitudes pendientes</p>
        </div>
    @endif
</x-admin.card>

<!-- Equipos Aprobados -->
<x-admin.card>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-900">Equipos Inscritos</h3>
        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
            {{ $equiposAprobados->count() }} inscritos
        </span>
    </div>

    @if($equiposAprobados->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($equiposAprobados as $equipo)
                <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                    <div class="flex items-center mb-2">
                        <h4 class="text-lg font-bold text-gray-900">{{ $equipo->nombre }}</h4>
                        <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                            @if($equipo->pivot->estado === 'inscrito')
                                Inscrito
                            @elseif($equipo->pivot->estado === 'participando')
                                Participando
                            @elseif($equipo->pivot->estado === 'finalizado')
                                Finalizado
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center text-sm text-gray-600 mb-2">
                        <i class="fas fa-users mr-2"></i>
                        <span>{{ $equipo->miembros->count() }} integrantes</span>
                    </div>

                    @if($equipo->proyecto)
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <i class="fas fa-project-diagram mr-2"></i>
                            <span>{{ $equipo->proyecto->nombre }}</span>
                        </div>
                    @endif

                    <!-- Líder del equipo -->
                    @php
                        $lider = $equipo->miembros->where('pivot.rol_equipo', 'lider')->first();
                    @endphp
                    @if($lider)
                        <div class="flex items-center text-xs text-gray-600 bg-white rounded px-2 py-1">
                            <i class="fas fa-crown text-yellow-600 mr-1"></i>
                            <span>{{ $lider->name }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-600">No hay equipos inscritos aún</p>
        </div>
    @endif
</x-admin.card>
@endsection
