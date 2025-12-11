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
                class="px-4 py-2 gradient-primary text-white rounded-xl hover:shadow-lg transition-all flex items-center gap-2 shadow-md">
            <i class="fas fa-envelope"></i>
            Enviar Informe por Email
        </button>

        <div x-show="open"
             @click.away="open = false"
             x-transition
             style="display: none;"
             class="fixed inset-0 bg-dark-900/80 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl" @click.stop>
                <h3 class="text-lg font-bold text-dark-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-envelope text-primary-500"></i>
                    Enviar Informe por Email
                </h3>
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
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button"
                                @click="open = false"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 gradient-primary text-white rounded-lg hover:shadow-lg transition-all shadow-md">
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
                <p class="text-sm text-gray-600 font-medium">Total Eventos</p>
                <p class="text-3xl font-bold text-primary-600">{{ $totalEventos }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $eventosActivos }} activos | {{ $eventosFinalizados }} finalizados
                </p>
            </div>
            <div class="h-12 w-12 gradient-primary rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-calendar-days text-white text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <!-- Equipos -->
    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Equipos</p>
                <p class="text-3xl font-bold text-secondary-600">{{ $totalEquipos }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $equiposActivos }} activos
                </p>
            </div>
            <div class="h-12 w-12 gradient-secondary rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <!-- Usuarios -->
    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Usuarios</p>
                <p class="text-3xl font-bold text-dark-800">{{ $totalEstudiantes + $totalJueces + $totalAdmins }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $totalEstudiantes }} estudiantes | {{ $totalJueces }} jueces
                </p>
            </div>
            <div class="h-12 w-12 gradient-dark rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-user-friends text-white text-xl"></i>
            </div>
        </div>
    </x-admin.card>

    <!-- Evaluaciones -->
    <x-admin.card>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Evaluaciones</p>
                <p class="text-3xl font-bold text-primary-600">{{ $totalEvaluaciones }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    Promedio: {{ number_format($promedioEvaluacionesGeneral ?? 0, 2) }}/100
                </p>
            </div>
            <div class="h-12 w-12 gradient-primary rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-star text-white text-xl"></i>
            </div>
        </div>
    </x-admin.card>
</div>

<!-- Constancias -->
<x-admin.card class="mb-6">
    <h3 class="text-lg font-bold text-dark-900 mb-4 flex items-center gap-2">
        <i class="fas fa-certificate text-primary-500"></i>
        Constancias Emitidas
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-gray-200">
            <i class="fas fa-certificate text-4xl text-dark-600 mb-2"></i>
            <p class="text-2xl font-bold text-dark-900">{{ $totalConstancias }}</p>
            <p class="text-sm text-gray-600 font-medium">Total</p>
        </div>
        <div class="text-center p-4 bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl border-2 border-primary-200">
            <i class="fas fa-trophy text-4xl text-primary-600 mb-2"></i>
            <p class="text-2xl font-bold text-primary-900">{{ $constanciasGanadores }}</p>
            <p class="text-sm text-primary-700 font-medium">Ganadores</p>
        </div>
        <div class="text-center p-4 bg-gradient-to-br from-secondary-50 to-secondary-100 rounded-xl border-2 border-secondary-200">
            <i class="fas fa-users text-4xl text-secondary-600 mb-2"></i>
            <p class="text-2xl font-bold text-secondary-900">{{ $constanciasParticipantes }}</p>
            <p class="text-sm text-secondary-700 font-medium">Participantes</p>
        </div>
        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border-2 border-purple-200">
            <i class="fas fa-award text-4xl text-purple-600 mb-2"></i>
            <p class="text-2xl font-bold text-purple-900">{{ $constanciasJueces }}</p>
            <p class="text-sm text-purple-700 font-medium">Jueces</p>
        </div>
    </div>
</x-admin.card>

<!-- Eventos Próximos -->
@if($eventosProximos->count() > 0)
<x-admin.card class="mb-6">
    <h3 class="text-lg font-bold text-dark-900 mb-4 flex items-center gap-2">
        <i class="fas fa-calendar-plus text-secondary-500"></i>
        Próximos Eventos
    </h3>
    <div class="space-y-3">
        @foreach($eventosProximos as $evento)
        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-secondary-50 to-secondary-100/50 rounded-xl border border-secondary-200">
            <div>
                <h4 class="font-medium text-dark-900">{{ $evento->nombre }}</h4>
                <p class="text-sm text-gray-600">
                    <i class="fas fa-calendar mr-1 text-secondary-500"></i>
                    {{ $evento->fecha_inicio->format('d/m/Y') }} - {{ $evento->fecha_fin->format('d/m/Y') }}
                </p>
            </div>
            <a href="{{ route('admin.eventos.show', $evento) }}"
               class="px-3 py-1 gradient-secondary text-white text-sm rounded-lg hover:shadow-lg transition-all shadow-md">
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
    <h3 class="text-lg font-bold text-dark-900 mb-4 flex items-center gap-2">
        <i class="fas fa-calendar-check text-dark-600"></i>
        Eventos Recientes Finalizados
    </h3>
    <div class="space-y-3">
        @foreach($eventosRecientes as $evento)
        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
            <div>
                <h4 class="font-medium text-dark-900">{{ $evento->nombre }}</h4>
                <p class="text-sm text-gray-600">
                    <i class="fas fa-calendar mr-1 text-dark-500"></i>
                    Finalizado: {{ $evento->fecha_fin->format('d/m/Y') }}
                </p>
            </div>
            <a href="{{ route('admin.eventos.show', $evento) }}"
               class="px-3 py-1 gradient-dark text-white text-sm rounded-lg hover:shadow-lg transition-all shadow-md">
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
    <h3 class="text-lg font-bold text-dark-900 mb-4 flex items-center gap-2">
        <i class="fas fa-trophy text-primary-500"></i>
        Top 10 Equipos por Evaluaciones
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="gradient-dark">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Equipo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Evaluaciones</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Promedio</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($topEquipos as $equipo)
                <tr class="hover:bg-primary-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-dark-900">{{ $equipo->nombre }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-secondary-100 text-secondary-800 border border-secondary-200">
                            {{ $equipo->evaluaciones_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-sm font-bold text-primary-600">
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
    <h3 class="text-lg font-bold text-dark-900 mb-4 flex items-center gap-2">
        <i class="fas fa-gavel text-secondary-500"></i>
        Top 10 Jueces Más Activos
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="gradient-dark">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Juez</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Evaluaciones Realizadas</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($juecesActivos as $juez)
                <tr class="hover:bg-secondary-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-dark-900">{{ $juez->name }}</div>
                        <div class="text-sm text-gray-500">{{ $juez->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-primary-100 text-primary-800 border border-primary-200">
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
