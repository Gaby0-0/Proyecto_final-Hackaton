@extends('layouts.admin')

@section('title', 'Informes y Estadísticas')

@php
$pageTitle = 'Informes y Estadísticas';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Informes']
];
@endphp

@section('content')
<!-- Botón para enviar por email -->
<div class="mb-6 flex justify-end">
    <div x-data="{ open: false }">
        <button @click="open = !open"
                type="button"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
            <i class="fas fa-envelope"></i>
            Enviar Informe por Email
        </button>

        <div x-show="open"
             @click.away="open = false"
             x-transition
             style="display: none;"
             class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 w-full max-w-md" @click.stop>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Enviar Informe por Email</h3>
                <form action="{{ route('admin.informes.enviar-email') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ auth()->user()->email }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button"
                                @click="open = false"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas Generales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Eventos -->
    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Eventos</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalEventos }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $eventosActivos }} activos | {{ $eventosFinalizados }} finalizados
                </p>
            </div>
            <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-days text-blue-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <!-- Equipos -->
    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Equipos</p>
                <p class="text-3xl font-bold text-green-600">{{ $totalEquipos }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $equiposActivos }} activos
                </p>
            </div>
            <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <!-- Usuarios -->
    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Usuarios</p>
                <p class="text-3xl font-bold text-purple-600">{{ $totalEstudiantes + $totalJueces + $totalAdmins }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $totalEstudiantes }} estudiantes | {{ $totalJueces }} jueces
                </p>
            </div>
            <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-friends text-purple-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <!-- Evaluaciones -->
    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Evaluaciones</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $totalEvaluaciones }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    Promedio: {{ number_format($promedioEvaluacionesGeneral ?? 0, 2) }}/100
                </p>
            </div>
            <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-star text-yellow-600 text-xl"></i>
            </div>
        </div>
    </x-admin.card>
</div>

<!-- Constancias -->
<x-admin.card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Constancias Emitidas</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <i class="fas fa-certificate text-4xl text-gray-600 mb-2"></i>
            <p class="text-2xl font-bold text-gray-900">{{ $totalConstancias }}</p>
            <p class="text-sm text-gray-600">Total</p>
        </div>
        <div class="text-center p-4 bg-yellow-50 rounded-lg">
            <i class="fas fa-trophy text-4xl text-yellow-600 mb-2"></i>
            <p class="text-2xl font-bold text-yellow-900">{{ $constanciasGanadores }}</p>
            <p class="text-sm text-yellow-700">Ganadores</p>
        </div>
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <i class="fas fa-users text-4xl text-green-600 mb-2"></i>
            <p class="text-2xl font-bold text-green-900">{{ $constanciasParticipantes }}</p>
            <p class="text-sm text-green-700">Participantes</p>
        </div>
        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <i class="fas fa-award text-4xl text-purple-600 mb-2"></i>
            <p class="text-2xl font-bold text-purple-900">{{ $constanciasJueces }}</p>
            <p class="text-sm text-purple-700">Jueces</p>
        </div>
    </div>
</x-admin.card>

<!-- Eventos Próximos -->
@if($eventosProximos->count() > 0)
<x-admin.card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Próximos Eventos</h3>
    <div class="space-y-3">
        @foreach($eventosProximos as $evento)
        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
            <div>
                <h4 class="font-medium text-gray-900">{{ $evento->nombre }}</h4>
                <p class="text-sm text-gray-600">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $evento->fecha_inicio->format('d/m/Y') }} - {{ $evento->fecha_fin->format('d/m/Y') }}
                </p>
            </div>
            <a href="{{ route('admin.eventos.show', $evento) }}"
               class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                Ver
            </a>
        </div>
        @endforeach
    </div>
</x-admin.card>
@endif

<!-- Eventos Recientes -->
@if($eventosRecientes->count() > 0)
<x-admin.card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Eventos Recientes Finalizados</h3>
    <div class="space-y-3">
        @foreach($eventosRecientes as $evento)
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div>
                <h4 class="font-medium text-gray-900">{{ $evento->nombre }}</h4>
                <p class="text-sm text-gray-600">
                    <i class="fas fa-calendar mr-1"></i>
                    Finalizado: {{ $evento->fecha_fin->format('d/m/Y') }}
                </p>
            </div>
            <a href="{{ route('admin.eventos.show', $evento) }}"
               class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                Ver
            </a>
        </div>
        @endforeach
    </div>
</x-admin.card>
@endif

<!-- Top Equipos -->
@if($topEquipos->count() > 0)
<x-admin.card class="mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Top 10 Equipos por Evaluaciones</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Evaluaciones</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Promedio</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($topEquipos as $equipo)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $equipo->nombre }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $equipo->evaluaciones_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-sm font-bold text-gray-900">
                            {{ number_format($equipo->promedio_evaluaciones ?? 0, 2) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin.card>
@endif

<!-- Jueces Activos -->
@if($juecesActivos->count() > 0)
<x-admin.card>
    <h3 class="text-lg font-bold text-gray-900 mb-4">Top 10 Jueces Más Activos</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Juez</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Evaluaciones Realizadas</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($juecesActivos as $juez)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $juez->name }}</div>
                        <div class="text-sm text-gray-500">{{ $juez->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $juez->evaluaciones_realizadas_count }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin.card>
@endif
@endsection
