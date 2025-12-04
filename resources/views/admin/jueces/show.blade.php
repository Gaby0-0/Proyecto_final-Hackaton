@extends('layouts.admin')

@section('title', 'Detalles del Juez')

@php
$pageTitle = 'Detalles del Juez';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Jueces', 'url' => route('admin.jueces.index')],
    ['name' => 'Detalles']
];
$pageActions = '<a href="' . route('admin.jueces.edit', $juez->id) . '" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
    <i class="fas fa-edit mr-2"></i>
    Editar Juez
</a>';
@endphp

@section('content')
<!-- Información del Juez -->
<x-admin.card class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Personal</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Nombre de Usuario</label>
            <p class="text-base text-gray-900">{{ $juez->name }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Nombre Completo</label>
            <p class="text-base text-gray-900">{{ $juez->nombre_completo ?? 'No especificado' }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Correo Electrónico</label>
            <p class="text-base text-gray-900">{{ $juez->email }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Especialidad</label>
            <p class="text-base text-gray-900">
                @if($juez->especialidad)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $juez->especialidad }}
                    </span>
                @else
                    <span class="text-gray-400">No especificada</span>
                @endif
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Estado</label>
            <p class="text-base text-gray-900">
                @if($juez->activo)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Activo
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-1"></i> Inactivo
                    </span>
                @endif
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Fecha de Registro</label>
            <p class="text-base text-gray-900">{{ $juez->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Equipos Asignados</label>
            <p class="text-base text-gray-900">
                <x-admin.badge variant="info">
                    {{ $juez->equiposAsignados->count() }} equipos
                </x-admin.badge>
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Eventos Asignados</label>
            <p class="text-base text-gray-900">
                <x-admin.badge variant="success">
                    {{ $juez->eventosAsignados->count() }} eventos
                </x-admin.badge>
            </p>
        </div>
    </div>
</x-admin.card>

<!-- Equipos Asignados -->
<x-admin.card class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Equipos Asignados</h3>

    @if($juez->equiposAsignados->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Equipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Proyecto
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Categoría
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha Asignación
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($juez->equiposAsignados as $equipo)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $equipo->nombre }}</div>
                        <div class="text-sm text-gray-500">Código: {{ $equipo->codigo }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $equipo->proyecto->nombre ?? 'Sin proyecto' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-admin.badge variant="default">
                            {{ $equipo->proyecto->categoria ?? 'N/A' }}
                        </x-admin.badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                        $estado = $equipo->pivot->estado;
                        $colorEstado = [
                            'asignado' => 'warning',
                            'evaluado' => 'success',
                            'pendiente' => 'danger'
                        ];
                        @endphp
                        <x-admin.badge :variant="$colorEstado[$estado] ?? 'default'">
                            {{ ucfirst($estado) }}
                        </x-admin.badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($equipo->pivot->fecha_asignacion)->format('d/m/Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-2"></i>
        <p class="text-gray-500">Este juez no tiene equipos asignados aún</p>
    </div>
    @endif
</x-admin.card>

<!-- Evaluaciones Realizadas -->
<x-admin.card>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Evaluaciones Realizadas</h3>

    @if($juez->evaluaciones->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Equipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Puntuación
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($juez->evaluaciones as $evaluacion)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $evaluacion->equipo->nombre ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $evaluacion->puntuacion ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $evaluacion->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <i class="fas fa-star text-4xl text-gray-300 mb-2"></i>
        <p class="text-gray-500">Este juez no ha realizado evaluaciones aún</p>
    </div>
    @endif
</x-admin.card>
@endsection
