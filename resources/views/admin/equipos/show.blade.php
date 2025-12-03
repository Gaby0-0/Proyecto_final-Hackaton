@extends('layouts.admin')

@section('title', 'Detalles del Equipo')

@section('breadcrumbs')
<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('admin.equipos.index') }}" class="text-gray-600 hover:text-blue-600">Equipos</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-gray-900 font-medium">Detalles</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $equipo->nombre }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.equipos.edit', $equipo) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <form action="{{ route('admin.equipos.destroy', $equipo) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" onclick="return confirm('¿Estás seguro de eliminar este equipo?')">
                    <i class="fas fa-trash mr-2"></i>Eliminar
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Información General</h2>

                @if($equipo->descripcion)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Descripción</h3>
                        <p class="text-gray-600">{{ $equipo->descripcion }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-1">Integrantes</h3>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $equipo->miembros->count() }} / {{ $equipo->max_integrantes }}
                        </p>
                        @if($equipo->estaLleno())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Equipo lleno
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                <i class="fas fa-check-circle mr-1"></i> Espacios disponibles
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-1">Estado</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $equipo->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $equipo->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    @if($equipo->proyecto)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Proyecto</h3>
                            <p class="text-gray-900 font-medium">{{ $equipo->proyecto->nombre }}</p>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-1">Fecha de creación</h3>
                        <p class="text-gray-900">{{ $equipo->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </x-admin.card>

            <!-- Miembros -->
            <x-admin.card>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Miembros del Equipo</h2>
                <div class="space-y-3">
                    @forelse($equipo->miembros as $miembro)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-blue-600 font-bold text-lg">{{ strtoupper(substr($miembro->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $miembro->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $miembro->email }}</p>
                                </div>
                            </div>
                            @if($miembro->pivot->rol_equipo == 'lider')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-crown mr-1"></i> Líder
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-700">
                                    Miembro
                                </span>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No hay miembros en este equipo</p>
                    @endforelse
                </div>
            </x-admin.card>

            <!-- Eventos -->
            @if($equipo->eventos->count() > 0)
                <x-admin.card>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Eventos Inscritos</h2>
                    <div class="space-y-3">
                        @foreach($equipo->eventos as $evento)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $evento->nombre }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Fecha de inscripción: {{ \Carbon\Carbon::parse($evento->pivot->fecha_inscripcion)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $evento->pivot->estado == 'inscrito' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $evento->pivot->estado == 'aceptado' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $evento->pivot->estado == 'rechazado' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($evento->pivot->estado) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-admin.card>
            @endif

            <!-- Evaluaciones -->
            @if($equipo->evaluaciones->count() > 0)
                <x-admin.card>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Evaluaciones</h2>
                    <div class="space-y-3">
                        @foreach($equipo->evaluaciones as $evaluacion)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-900">Puntuación: {{ $evaluacion->puntuacion }}/100</p>
                                        @if($evaluacion->comentarios)
                                            <p class="text-sm text-gray-600 mt-1">{{ $evaluacion->comentarios }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">{{ $evaluacion->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-admin.card>
            @endif
        </div>

        <!-- Estadísticas -->
        <div class="lg:col-span-1">
            <x-admin.card>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Estadísticas</h2>
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Total Miembros</span>
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-blue-900">{{ $equipo->miembros->count() }}</p>
                    </div>

                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Espacios Libres</span>
                            <i class="fas fa-user-plus text-green-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-green-900">{{ $equipo->max_integrantes - $equipo->miembros->count() }}</p>
                    </div>

                    <div class="p-4 bg-purple-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Eventos</span>
                            <i class="fas fa-calendar text-purple-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-purple-900">{{ $equipo->eventos->count() }}</p>
                    </div>

                    <div class="p-4 bg-yellow-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Evaluaciones</span>
                            <i class="fas fa-star text-yellow-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-yellow-900">{{ $equipo->evaluaciones->count() }}</p>
                    </div>
                </div>
            </x-admin.card>
        </div>
    </div>
</div>
@endsection
