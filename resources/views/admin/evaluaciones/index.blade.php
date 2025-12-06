@extends('layouts.admin')

@section('title', 'Gestión de Evaluaciones')

@php
$pageTitle = 'Evaluaciones';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Evaluaciones']
];
$pageActions = '';
@endphp

@section('content')
<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total de Evaluaciones</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalEvaluaciones }}</p>
            </div>
            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-star text-blue-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Promedio General</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ number_format($promedioGeneral ?? 0, 2) }}
                </p>
            </div>
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>
</div>

<!-- Filtros -->
<x-admin.card class="mb-6">
    <form method="GET" action="{{ route('admin.evaluaciones.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Evento</label>
            <select name="evento_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los eventos</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}" {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                        {{ $evento->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Equipo</label>
            <select name="equipo_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los equipos</option>
                @foreach($equipos as $equipo)
                    <option value="{{ $equipo->id }}" {{ request('equipo_id') == $equipo->id ? 'selected' : '' }}>
                        {{ $equipo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-filter mr-2"></i>
                Filtrar
            </button>
        </div>

        <div class="flex items-end">
            <a href="{{ route('admin.evaluaciones.index') }}" class="w-full px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors text-center">
                <i class="fas fa-times mr-2"></i>
                Limpiar
            </a>
        </div>
    </form>
</x-admin.card>

<!-- Tabla de Evaluaciones -->
<x-admin.card>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Equipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Evento
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Evaluador
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Puntuación
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($evaluaciones as $evaluacion)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $evaluacion->equipo->nombre ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $evaluacion->evento->nombre ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $evaluacion->evaluador->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold
                            @if($evaluacion->puntuacion >= 90) bg-green-100 text-green-800
                            @elseif($evaluacion->puntuacion >= 70) bg-blue-100 text-blue-800
                            @elseif($evaluacion->puntuacion >= 50) bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ number_format($evaluacion->puntuacion, 1) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $evaluacion->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.evaluaciones.show', $evaluacion->id) }}"
                               class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.evaluaciones.destroy', $evaluacion->id) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar esta evaluación?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">No hay evaluaciones registradas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($evaluaciones->hasPages())
    <div class="mt-4">
        {{ $evaluaciones->links() }}
    </div>
    @endif
</x-admin.card>
@endsection
