@extends('layouts.admin')

@section('title', 'Detalles del Proyecto')

@php
$pageTitle = $proyecto->proyecto_titulo;
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Proyectos', 'url' => route('admin.proyectos.index')],
    ['name' => 'Detalles']
];
$pageActions = '';
@endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Panel principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Información del Proyecto -->
        <x-admin.card>
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Información del Proyecto
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Título del Proyecto</label>
                    <p class="text-gray-900 font-medium text-lg">{{ $proyecto->proyecto_titulo }}</p>
                </div>

                @if($proyecto->proyecto_descripcion)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Descripción</label>
                    <p class="text-gray-700 whitespace-pre-line">{{ $proyecto->proyecto_descripcion }}</p>
                </div>
                @endif

                @if($proyecto->proyecto_final_url)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        <i class="fas fa-link mr-1"></i>
                        Enlace del Proyecto
                    </label>
                    <a href="{{ $proyecto->proyecto_final_url }}"
                       target="_blank"
                       class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-2">
                        {{ $proyecto->proyecto_final_url }}
                        <i class="fas fa-external-link-alt text-sm"></i>
                    </a>
                </div>
                @endif

                @if($proyecto->notas_equipo)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        <i class="fas fa-sticky-note mr-1"></i>
                        Notas del Equipo
                    </label>
                    <p class="text-gray-700 whitespace-pre-line bg-gray-50 p-3 rounded-lg">{{ $proyecto->notas_equipo }}</p>
                </div>
                @endif

                <div class="pt-4 border-t">
                    @if($proyecto->fecha_entrega_final)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Fecha de Entrega</label>
                        <p class="text-gray-900">
                            <i class="fas fa-calendar-check mr-2 text-green-600"></i>
                            {{ \Carbon\Carbon::parse($proyecto->fecha_entrega_final)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </x-admin.card>

        <!-- Información del Equipo -->
        <x-admin.card>
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-users text-blue-600 mr-2"></i>
                Equipo Desarrollador
            </h2>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <h3 class="font-semibold text-gray-900 text-lg">{{ $proyecto->equipo_nombre }}</h3>
                    @if($proyecto->equipo_codigo)
                        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded font-mono">
                            {{ $proyecto->equipo_codigo }}
                        </span>
                    @endif
                </div>

                <!-- Miembros del equipo -->
                @if($miembros->isNotEmpty())
                    <div class="mt-3 pt-3 border-t">
                        <p class="text-sm font-medium text-gray-500 mb-3">
                            <i class="fas fa-user-friends mr-1"></i>
                            Miembros del Equipo ({{ $miembros->count() }})
                        </p>
                        <div class="space-y-2">
                            @foreach($miembros as $miembro)
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                            {{ substr($miembro->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $miembro->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $miembro->email }}</p>
                                        </div>
                                    </div>
                                    @if($miembro->rol_especifico)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $miembro->rol_especifico }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </x-admin.card>
    </div>

    <!-- Panel lateral -->
    <div class="space-y-6">
        <!-- Información del Evento -->
        <x-admin.card>
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                Evento
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Nombre del Evento</p>
                    <p class="font-semibold text-gray-900">{{ $proyecto->evento_nombre }}</p>
                </div>
                @if($proyecto->evento_categoria)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Categoría</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        {{ $proyecto->evento_categoria }}
                    </span>
                </div>
                @endif
            </div>
        </x-admin.card>

        <!-- Acciones -->
        <x-admin.card>
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-tasks text-blue-600 mr-2"></i>
                Acciones Rápidas
            </h3>
            <div class="space-y-2">
                @if($proyecto->proyecto_final_url)
                    <a href="{{ $proyecto->proyecto_final_url }}"
                       target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Ver Proyecto
                    </a>
                @endif

                <a href="{{ route('admin.equipos.show', $proyecto->equipo_id) }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-users mr-2"></i>
                    Ver Equipo
                </a>

                <a href="{{ route('admin.eventos.show', $proyecto->evento_id) }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-calendar mr-2"></i>
                    Ver Evento
                </a>

                <a href="{{ route('admin.proyectos.index') }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Proyectos
                </a>
            </div>
        </x-admin.card>

        <!-- Información del Sistema -->
        <x-admin.card>
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-info text-blue-600 mr-2"></i>
                Información
            </h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-xs text-blue-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    Los proyectos son entregados por los equipos al inscribirse en eventos. El administrador puede visualizarlos pero no modificarlos.
                </p>
            </div>
        </x-admin.card>
    </div>
</div>
@endsection
