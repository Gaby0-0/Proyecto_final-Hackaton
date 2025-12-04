@extends('layouts.admin')

@section('title', 'Asignar Jueces - ' . $evento->nombre)

@php
$pageTitle = 'Asignar Jueces al Evento';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Eventos', 'url' => route('admin.eventos.index')],
    ['name' => $evento->nombre, 'url' => route('admin.eventos.show', $evento->id)],
    ['name' => 'Asignar Jueces']
];
@endphp

@section('content')
<!-- Información del Evento -->
<x-admin.card class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $evento->nombre }}</h2>
            <p class="text-gray-600 mt-1">{{ $evento->descripcion }}</p>
            <div class="flex gap-4 mt-3">
                <span class="text-sm text-gray-500">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $evento->fecha_inicio->format('d/m/Y') }} - {{ $evento->fecha_fin->format('d/m/Y') }}
                </span>
                <span class="text-sm">
                    @if($evento->estado == 'activo')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Activo
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ ucfirst($evento->estado) }}
                        </span>
                    @endif
                </span>
            </div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $juecesAsignados->count() }}</div>
            <div class="text-sm text-gray-500">Jueces Asignados</div>
        </div>
    </div>
</x-admin.card>

<!-- Asignación Automática -->
<x-admin.card class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Asignación Automática</h3>
    <form action="{{ route('admin.eventos.asignar-jueces-auto', $evento->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @csrf
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Especialidad</label>
            <select name="especialidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todas las especialidades</option>
                @foreach($jucesPorEspecialidad->keys() as $especialidad)
                    <option value="{{ $especialidad }}">{{ $especialidad }} ({{ $jucesPorEspecialidad[$especialidad]->count() }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad</label>
            <input type="number" name="cantidad" value="3" min="1" max="10" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-random mr-2"></i>
                Asignar Automáticamente
            </button>
        </div>
    </form>
</x-admin.card>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Jueces Asignados -->
    <x-admin.card>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Jueces Asignados ({{ $juecesAsignados->count() }})</h3>

        @if($juecesAsignados->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-user-tie text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No hay jueces asignados a este evento</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($juecesAsignados as $juez)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                <span class="text-white font-medium text-sm">
                                    {{ strtoupper(substr($juez->nombre_completo ?? $juez->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $juez->nombre_completo ?? $juez->name }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $juez->especialidad }}</div>
                            </div>
                        </div>
                        <form action="{{ route('admin.eventos.quitar-juez', [$evento->id, $juez->id]) }}" method="POST"
                              onsubmit="return confirm('¿Estás seguro de desasignar a este juez?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Desasignar">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </x-admin.card>

    <!-- Jueces Disponibles -->
    <x-admin.card>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Jueces Disponibles</h3>

        @if($jucesPorEspecialidad->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-400 mb-3"></i>
                <p class="text-gray-500">No hay jueces activos disponibles</p>
                <a href="{{ route('admin.jueces.create') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-800">
                    <i class="fas fa-plus mr-1"></i> Crear nuevo juez
                </a>
            </div>
        @else
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach($jucesPorEspecialidad as $especialidad => $juecesGrupo)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ $especialidad }}</h4>
                        <div class="space-y-2">
                            @foreach($juecesGrupo as $juez)
                                @if(!$juecesAsignados->contains('id', $juez->id))
                                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                                <span class="text-white font-medium text-xs">
                                                    {{ strtoupper(substr($juez->nombre_completo ?? $juez->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $juez->nombre_completo ?? $juez->name }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $juez->eventosAsignados->count() }} eventos asignados
                                                </div>
                                            </div>
                                        </div>
                                        <form action="{{ route('admin.eventos.agregar-juez', $evento->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="juez_id" value="{{ $juez->id }}">
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Asignar">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-admin.card>
</div>
@endsection
