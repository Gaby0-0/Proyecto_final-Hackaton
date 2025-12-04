@extends('layouts.admin')

@section('title', 'Asignar Jueces')

@php
$pageTitle = 'Asignar Jueces a Equipos';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Jueces', 'url' => route('admin.jueces.index')],
    ['name' => 'Asignar']
];
@endphp

@section('content')
<!-- Panel de Asignación Aleatoria -->
<x-admin.card class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-random mr-2 text-blue-600"></i>
        Asignación Aleatoria de Jueces
    </h3>

    <form action="{{ route('admin.jueces.asignar-aleatorio') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                <select name="categoria" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria }}">{{ $categoria }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Evento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Evento</label>
                <select name="evento_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los eventos</option>
                    @foreach($eventos as $evento)
                        <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Número de jueces por equipo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jueces por Equipo</label>
                <input type="number"
                       name="num_jueces"
                       value="3"
                       min="1"
                       max="5"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Botón -->
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-random mr-2"></i>
                    Asignar Aleatoriamente
                </button>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        La asignación aleatoria distribuirá jueces de manera equitativa entre los equipos seleccionados.
                        Los jueces con menor carga de trabajo serán priorizados para mantener un balance justo.
                    </p>
                </div>
            </div>
        </div>
    </form>
</x-admin.card>

<!-- Estadísticas de Jueces -->
<x-admin.card class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-chart-bar mr-2 text-purple-600"></i>
        Estadísticas de Jueces
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-purple-600 font-medium">Total Jueces</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $jueces->count() }}</p>
                </div>
                <i class="fas fa-gavel text-3xl text-purple-300"></i>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-medium">Promedio Asignaciones</p>
                    <p class="text-2xl font-bold text-blue-900">
                        {{ $jueces->count() > 0 ? number_format($jueces->avg('equipos_asignados_count'), 1) : 0 }}
                    </p>
                </div>
                <i class="fas fa-balance-scale text-3xl text-blue-300"></i>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600 font-medium">Menor Carga</p>
                    <p class="text-2xl font-bold text-green-900">
                        {{ $jueces->min('equipos_asignados_count') ?? 0 }} equipos
                    </p>
                </div>
                <i class="fas fa-arrow-down text-3xl text-green-300"></i>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-orange-600 font-medium">Mayor Carga</p>
                    <p class="text-2xl font-bold text-orange-900">
                        {{ $jueces->max('equipos_asignados_count') ?? 0 }} equipos
                    </p>
                </div>
                <i class="fas fa-arrow-up text-3xl text-orange-300"></i>
            </div>
        </div>
    </div>
</x-admin.card>

<!-- Filtros para Equipos -->
<x-admin.card class="mb-6">
    <form method="GET" action="{{ route('admin.jueces.asignar') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Categoría</label>
            <select name="categoria" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                        {{ $categoria }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Evento</label>
            <select name="evento_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos los eventos</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                        {{ $evento->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-filter mr-2"></i>
                Filtrar
            </button>
        </div>
    </form>
</x-admin.card>

<!-- Lista de Equipos con Asignaciones -->
<x-admin.card>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Equipos y sus Jueces Asignados</h3>

    @if($equipos->count() > 0)
    <div class="space-y-4">
        @foreach($equipos as $equipo)
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-900">{{ $equipo->nombre }}</h4>
                    <p class="text-sm text-gray-500">Código: {{ $equipo->codigo }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <x-admin.badge variant="default">
                            {{ $equipo->proyecto->nombre ?? 'Sin proyecto' }}
                        </x-admin.badge>
                        <x-admin.badge variant="info">
                            {{ $equipo->proyecto->categoria ?? 'Sin categoría' }}
                        </x-admin.badge>
                    </div>
                </div>

                <!-- Form de Asignación Manual -->
                <form action="{{ route('admin.jueces.asignar-manual') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="equipo_id" value="{{ $equipo->id }}">
                    <select name="juez_id" required class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccionar juez...</option>
                        @foreach($jueces as $juez)
                            <option value="{{ $juez->id }}">
                                {{ $juez->name }} ({{ $juez->equipos_asignados_count }} equipos)
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus"></i>
                    </button>
                </form>
            </div>

            <!-- Jueces asignados -->
            @if($equipo->jueces->count() > 0)
            <div class="border-t pt-3">
                <p class="text-sm font-medium text-gray-700 mb-2">Jueces asignados:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($equipo->jueces as $juez)
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-purple-100 text-purple-800 rounded-full">
                        <span class="text-sm">{{ $juez->name }}</span>
                        <form action="{{ route('admin.jueces.desasignar') }}" method="POST" class="inline" onsubmit="return confirm('¿Desasignar este juez?')">
                            @csrf
                            <input type="hidden" name="juez_id" value="{{ $juez->id }}">
                            <input type="hidden" name="equipo_id" value="{{ $equipo->id }}">
                            <button type="submit" class="text-purple-600 hover:text-purple-900">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="border-t pt-3">
                <p class="text-sm text-gray-500 italic">Sin jueces asignados</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    @if($equipos->hasPages())
    <div class="mt-6">
        {{ $equipos->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <i class="fas fa-users-slash text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg">No hay equipos disponibles para asignar</p>
    </div>
    @endif
</x-admin.card>
@endsection
