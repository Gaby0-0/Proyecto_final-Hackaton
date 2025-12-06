@extends('layouts.admin')

@section('title', 'Gestión de Constancias')

@php
$pageTitle = 'Constancias';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Constancias']
];
$pageActions = '';
@endphp

@section('content')
<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total de Constancias</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalConstancias }}</p>
            </div>
            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-certificate text-blue-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Ganadores</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $constanciasGanador }}</p>
            </div>
            <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-trophy text-yellow-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Participantes</p>
                <p class="text-3xl font-bold text-green-600">{{ $constanciasParticipante }}</p>
            </div>
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Jueces</p>
                <p class="text-3xl font-bold text-purple-600">{{ $constanciasJuez }}</p>
            </div>
            <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-award text-purple-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>
</div>

<!-- Filtros -->
<x-admin.card class="mb-6">
    <form method="GET" action="{{ route('admin.constancias.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
            <select name="tipo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los tipos</option>
                <option value="ganador" {{ request('tipo') == 'ganador' ? 'selected' : '' }}>Ganador</option>
                <option value="participante" {{ request('tipo') == 'participante' ? 'selected' : '' }}>Participante</option>
                <option value="juez" {{ request('tipo') == 'juez' ? 'selected' : '' }}>Juez</option>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-filter mr-2"></i>
                Filtrar
            </button>
        </div>

        <div class="flex items-end">
            <a href="{{ route('admin.constancias.index') }}" class="w-full px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors text-center">
                <i class="fas fa-times mr-2"></i>
                Limpiar
            </a>
        </div>
    </form>
</x-admin.card>

<!-- Tabla de Constancias -->
<x-admin.card>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Usuario
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Evento
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Equipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha de Emisión
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($constancias as $constancia)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold mr-3">
                                {{ substr($constancia->usuario->name ?? 'N', 0, 2) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $constancia->usuario->name ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $constancia->usuario->email ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $constancia->evento->nombre ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $constancia->equipo->nombre ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($constancia->tipo === 'ganador')
                                bg-yellow-100 text-yellow-800
                            @elseif($constancia->tipo === 'juez')
                                bg-purple-100 text-purple-800
                            @else
                                bg-green-100 text-green-800
                            @endif">
                            @if($constancia->tipo === 'ganador')
                                <i class="fas fa-trophy mr-1"></i>
                            @elseif($constancia->tipo === 'juez')
                                <i class="fas fa-award mr-1"></i>
                            @else
                                <i class="fas fa-user-check mr-1"></i>
                            @endif
                            {{ ucfirst($constancia->tipo) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ \Carbon\Carbon::parse($constancia->fecha_emision)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.constancias.show', $constancia->id) }}"
                               class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.constancias.destroy', $constancia->id) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar esta constancia?')">
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
                            <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">No hay constancias registradas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($constancias->hasPages())
    <div class="mt-4">
        {{ $constancias->links() }}
    </div>
    @endif
</x-admin.card>
@endsection
