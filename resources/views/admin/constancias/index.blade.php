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

<!-- Eventos sin constancias -->
@if($eventosSinConstancias->count() > 0)
<x-admin.card class="mb-6 bg-yellow-50 border-yellow-200">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-yellow-900 mb-2">Eventos finalizados sin constancias</h3>
            <p class="text-sm text-yellow-700 mb-4">Los siguientes eventos han finalizado pero aún no se han generado constancias:</p>
            <div class="space-y-3">
                @foreach($eventosSinConstancias as $evento)
                <div class="bg-white rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $evento->nombre }}</h4>
                        <p class="text-sm text-gray-500">Finalizado el {{ $evento->fecha_fin->format('d/m/Y') }}</p>
                    </div>
                    <form action="{{ route('admin.constancias.generar-evento', $evento) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-certificate mr-2"></i>
                            Generar Constancias
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin.card>
@endif

<!-- Eventos con constancias -->
<div class="space-y-6">
    @forelse($eventosFinalizados as $evento)
    <x-admin.card>
        <div class="border-b border-gray-200 pb-4 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $evento->nombre }}</h3>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            Finalizado: {{ $evento->fecha_fin->format('d/m/Y') }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-certificate mr-1"></i>
                            {{ $evento->constancias_count }} constancias
                        </span>
                    </div>
                </div>
                <form action="{{ route('admin.constancias.generar-evento', $evento) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        <i class="fas fa-redo mr-2"></i>
                        Regenerar
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabs para dividir por tipo -->
        <div x-data="{ tab: 'todas' }" class="mb-4">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button @click="tab = 'todas'"
                            :class="tab === 'todas' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Todas ({{ $evento->constancias->count() }})
                    </button>
                    <button @click="tab = 'ganadores'"
                            :class="tab === 'ganadores' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Ganadores ({{ $evento->constancias->where('tipo', 'ganador')->count() }})
                    </button>
                    <button @click="tab = 'participantes'"
                            :class="tab === 'participantes' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Participantes ({{ $evento->constancias->where('tipo', 'participante')->count() }})
                    </button>
                    <button @click="tab = 'jueces'"
                            :class="tab === 'jueces' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Jueces ({{ $evento->constancias->where('tipo', 'juez')->count() }})
                    </button>
                </nav>
            </div>

            <!-- Tabla de Todas -->
            <div x-show="tab === 'todas'" class="overflow-x-auto mt-4">
                @include('admin.constancias.partials.tabla-constancias', ['constancias' => $evento->constancias])
            </div>

            <!-- Tabla de Ganadores -->
            <div x-show="tab === 'ganadores'" style="display: none;" class="overflow-x-auto mt-4">
                @include('admin.constancias.partials.tabla-constancias', ['constancias' => $evento->constancias->where('tipo', 'ganador')])
            </div>

            <!-- Tabla de Participantes -->
            <div x-show="tab === 'participantes'" style="display: none;" class="overflow-x-auto mt-4">
                @include('admin.constancias.partials.tabla-constancias', ['constancias' => $evento->constancias->where('tipo', 'participante')])
            </div>

            <!-- Tabla de Jueces -->
            <div x-show="tab === 'jueces'" style="display: none;" class="overflow-x-auto mt-4">
                @include('admin.constancias.partials.tabla-constancias', ['constancias' => $evento->constancias->where('tipo', 'juez')])
            </div>
        </div>
    </x-admin.card>
    @empty
    <x-admin.card>
        <div class="py-12 text-center">
            <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">No hay eventos finalizados con constancias</p>
        </div>
    </x-admin.card>
    @endforelse
</div>
@endsection
