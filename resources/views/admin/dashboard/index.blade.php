@extends('layouts.admin')

@section('title', 'Dashboard')

@php
$pageTitle = 'Dashboard';
$breadcrumbs = [
    ['name' => 'Dashboard']
];
@endphp

@section('content')
<!-- Estadísticas principales -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <x-admin.stat-card
        title="Total Usuarios"
        :value="$totalUsuarios"
        icon="fas fa-users"
        :trend="$crecimientoUsuarios > 0 ? 'up' : ($crecimientoUsuarios < 0 ? 'down' : null)"
        :trendValue="abs($crecimientoUsuarios) . '%'"
        color="blue"
    />

    <x-admin.stat-card
        title="Equipos Activos"
        :value="$equiposActivos"
        icon="fas fa-user-group"
        :trend="$crecimientoEquipos > 0 ? 'up' : ($crecimientoEquipos < 0 ? 'down' : null)"
        :trendValue="abs($crecimientoEquipos) . '%'"
        color="green"
    />

    <x-admin.stat-card
        title="Eventos"
        :value="$eventosActivos"
        icon="fas fa-calendar-days"
        :trend="$crecimientoEventos > 0 ? 'up' : ($crecimientoEventos < 0 ? 'down' : null)"
        :trendValue="abs($crecimientoEventos) . '%'"
        color="purple"
    />

    <x-admin.stat-card
        title="Evaluaciones"
        :value="$evaluacionesPendientes"
        icon="fas fa-star"
        color="orange"
    />
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Eventos Recientes -->
    <div class="lg:col-span-2">
        <x-admin.card title="Eventos Recientes">
            @if($eventosRecientes->count() > 0)
            <div class="space-y-4">
                @foreach($eventosRecientes as $evento)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $evento->nombre }}</h4>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-clock text-xs mr-1"></i>
                                {{ $evento->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <x-admin.button
                        variant="outline"
                        size="sm"
                        :href="route('admin.eventos.show', $evento->id)">
                        Ver detalles
                    </x-admin.button>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No hay eventos registrados</p>
                <x-admin.button
                    class="mt-4"
                    :href="route('admin.eventos.create')"
                    icon="fas fa-plus">
                    Crear Evento
                </x-admin.button>
            </div>
            @endif
        </x-admin.card>
    </div>

    <!-- Actividad del Sistema -->
    <div>
        <x-admin.card title="Actividad Reciente">
            <div class="space-y-3">
                @forelse($actividadSistema as $actividad)
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        @if($actividad['icono'] === 'success')
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        @elseif($actividad['icono'] === 'error')
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation text-red-600 text-sm"></i>
                        </div>
                        @else
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-info text-blue-600 text-sm"></i>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">{{ $actividad['mensaje'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $actividad['tiempo'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <i class="fas fa-history text-3xl text-gray-300 mb-2"></i>
                    <p class="text-sm text-gray-500">No hay actividad reciente</p>
                </div>
                @endforelse
            </div>
        </x-admin.card>
    </div>
</div>

<!-- Distribución de Usuarios y Estadísticas Adicionales -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Usuarios por Rol -->
    <x-admin.card title="Distribución de Usuarios">
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-shield text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Administradores</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $usuariosPorRol['administradores'] }}</p>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $usuariosPorRol['total'] > 0 ? round(($usuariosPorRol['administradores'] / $usuariosPorRol['total']) * 100, 1) : 0 }}%
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Usuarios</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $usuariosPorRol['usuarios'] }}</p>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $usuariosPorRol['total'] > 0 ? round(($usuariosPorRol['usuarios'] / $usuariosPorRol['total']) * 100, 1) : 0 }}%
                </div>
            </div>
        </div>
    </x-admin.card>

    <!-- Estadísticas de Equipos -->
    <x-admin.card title="Estadísticas de Equipos">
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Equipos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $estadisticasEquipos['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Últimos 7 días</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $estadisticasEquipos['recientes'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-admin.card>
</div>

<!-- Acciones Rápidas -->
<div class="mt-6">
    <x-admin.card title="Acciones Rápidas">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.usuarios.create') }}"
               class="flex flex-col items-center justify-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-colors group">
                <i class="fas fa-user-plus text-3xl text-blue-600 mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-semibold text-blue-900">Nuevo Usuario</span>
            </a>

            <a href="{{ route('admin.equipos.create') }}"
               class="flex flex-col items-center justify-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-colors group">
                <i class="fas fa-users-plus text-3xl text-green-600 mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-semibold text-green-900">Nuevo Equipo</span>
            </a>

            <a href="{{ route('admin.eventos.create') }}"
               class="flex flex-col items-center justify-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-colors group">
                <i class="fas fa-calendar-plus text-3xl text-purple-600 mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-semibold text-purple-900">Nuevo Evento</span>
            </a>

            <a href="{{ route('admin.informes.index') }}"
               class="flex flex-col items-center justify-center p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg hover:from-orange-100 hover:to-orange-200 transition-colors group">
                <i class="fas fa-file-chart text-3xl text-orange-600 mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-semibold text-orange-900">Ver Informes</span>
            </a>
        </div>
    </x-admin.card>
</div>
@endsection
