@extends('layouts.admin')

@section('title', 'Detalles del Evento')

@php
$pageTitle = $evento->nombre;
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Eventos', 'url' => route('admin.eventos.index')],
    ['name' => 'Detalles']
];
$solicitudesPendientes = $evento->equiposPendientes()->count();
$pageActions = '<a href="' . route('admin.eventos.solicitudes', $evento->id) . '" class="inline-flex items-center px-4 py-2.5 bg-yellow-600 text-white text-sm font-semibold rounded-lg hover:bg-yellow-700 transition-colors mr-2">
    <i class="fas fa-clipboard-list mr-2"></i>
    Gestionar Solicitudes' . ($solicitudesPendientes > 0 ? ' <span class="ml-2 bg-white text-yellow-600 px-2 py-0.5 rounded-full text-xs font-bold">' . $solicitudesPendientes . '</span>' : '') . '
</a>
<a href="' . route('admin.eventos.edit', $evento->id) . '" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors mr-2">
    <i class="fas fa-edit mr-2"></i>
    Editar Evento
</a>
<a href="' . route('admin.eventos.asignar-jueces', $evento->id) . '" class="inline-flex items-center px-4 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-colors">
    <i class="fas fa-user-tie mr-2"></i>
    Asignar Jueces
</a>';
@endphp

@section('content')
<!-- Información del Evento -->
<x-admin.card class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Evento</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Nombre del Evento</label>
            <p class="text-base text-gray-900">{{ $evento->nombre }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Tipo</label>
            <p class="text-base text-gray-900">{{ $evento->tipo ? ucfirst($evento->tipo) : 'No especificado' }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Categoría</label>
            <p class="text-base text-gray-900">
                @if($evento->categoria)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $evento->categoria }}
                    </span>
                @else
                    <span class="text-gray-500">Sin categoría</span>
                @endif
            </p>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-500 mb-1">Descripción</label>
            <p class="text-base text-gray-900">{{ $evento->descripcion ?? 'Sin descripción' }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Fecha de Inicio</label>
            <p class="text-base text-gray-900">
                <i class="fas fa-calendar mr-2 text-gray-400"></i>
                {{ $evento->fecha_inicio ? $evento->fecha_inicio->format('d/m/Y H:i') : 'N/A' }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Fecha de Fin</label>
            <p class="text-base text-gray-900">
                <i class="fas fa-calendar mr-2 text-gray-400"></i>
                {{ $evento->fecha_fin ? $evento->fecha_fin->format('d/m/Y H:i') : 'N/A' }}
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Estado</label>
            <p class="text-base text-gray-900">
                @php
                $colorEstado = [
                    'activo' => 'bg-green-100 text-green-800',
                    'programado' => 'bg-blue-100 text-blue-800',
                    'finalizado' => 'bg-gray-100 text-gray-800',
                    'cancelado' => 'bg-red-100 text-red-800'
                ];
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorEstado[$evento->estado] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($evento->estado) }}
                </span>
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Modalidad</label>
            <p class="text-base text-gray-900">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    {{ ucfirst($evento->modalidad) }}
                </span>
            </p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Cupo Máximo de Equipos</label>
            <p class="text-base text-gray-900">{{ $evento->max_equipos }} equipos</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Equipos Inscritos</label>
            <p class="text-base text-gray-900">
                {{ $evento->equipos->count() }} / {{ $evento->max_equipos }} equipos
                @php
                $porcentaje = $evento->max_equipos > 0 ? ($evento->equipos->count() / $evento->max_equipos) * 100 : 0;
                @endphp
            </p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($porcentaje, 100) }}%"></div>
            </div>
        </div>
    </div>
</x-admin.card>

<!-- Jueces Asignados -->
<x-admin.card class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Jueces Asignados ({{ $evento->jueces->count() }})</h3>
        <a href="{{ route('admin.eventos.asignar-jueces', $evento->id) }}"
           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            <i class="fas fa-plus mr-1"></i>
            Gestionar Jueces
        </a>
    </div>

    @if($evento->jueces->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($evento->jueces as $juez)
        <div class="bg-gray-50 rounded-lg p-4 flex items-center space-x-3">
            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                <span class="text-white font-medium text-sm">
                    {{ strtoupper(substr($juez->nombre_completo ?? $juez->name, 0, 2)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-900 truncate">
                    {{ $juez->nombre_completo ?? $juez->name }}
                </div>
                <div class="text-xs text-gray-500 truncate">
                    {{ $juez->especialidad ?? 'Sin especialidad' }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-8">
        <i class="fas fa-user-tie text-4xl text-gray-300 mb-2"></i>
        <p class="text-gray-500">No hay jueces asignados a este evento</p>
        <a href="{{ route('admin.eventos.asignar-jueces', $evento->id) }}"
           class="mt-3 inline-block text-blue-600 hover:text-blue-800">
            <i class="fas fa-plus mr-1"></i>
            Asignar jueces ahora
        </a>
    </div>
    @endif
</x-admin.card>

<!-- Equipos Inscritos -->
<x-admin.card>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Equipos Inscritos ({{ $evento->equipos->count() }})</h3>

    @if($evento->equipos->count() > 0)
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
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Miembros
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha Inscripción
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($evento->equipos as $equipo)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $equipo->nombre }}</div>
                        <div class="text-sm text-gray-500">{{ $equipo->codigo }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $equipo->proyecto->nombre ?? 'Sin proyecto' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $equipo->miembros->count() }} miembros
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php
                        $estado = $equipo->pivot->estado ?? 'inscrito';
                        $colorEstadoEquipo = [
                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                            'inscrito' => 'bg-green-100 text-green-800',
                            'participando' => 'bg-blue-100 text-blue-800',
                            'finalizado' => 'bg-gray-100 text-gray-800'
                        ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorEstadoEquipo[$estado] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $equipo->pivot->created_at ? \Carbon\Carbon::parse($equipo->pivot->created_at)->format('d/m/Y') : 'N/A' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <i class="fas fa-users text-4xl text-gray-300 mb-2"></i>
        <p class="text-gray-500">No hay equipos inscritos en este evento aún</p>
    </div>
    @endif
</x-admin.card>

<!-- Acciones Adicionales -->
<x-admin.card class="mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones del Evento</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Seleccionar Ganador Automático -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-trophy text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-yellow-900">Ganador Automático</h4>
                    <p class="text-xs text-yellow-700">
                        @if($evento->equipo_ganador_id)
                            🏆 {{ $evento->equipoGanador->nombre ?? 'Seleccionado' }}
                        @else
                            Seleccionar por mayor calificación
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.eventos.establecer-ganador-auto', $evento) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full px-3 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-award mr-1"></i>
                        @if($evento->equipo_ganador_id)
                            Recalcular
                        @else
                            Seleccionar
                        @endif
                    </button>
                </form>
                @if($evento->equipo_ganador_id)
                    <form action="{{ route('admin.eventos.quitar-ganador', $evento) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-3 py-2 bg-white border border-yellow-600 text-yellow-600 text-sm font-medium rounded-lg hover:bg-yellow-50 transition-colors">
                            <i class="fas fa-times mr-1"></i>
                            Quitar
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Generar Constancias -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-certificate text-green-600 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-green-900">Constancias</h4>
                    <p class="text-xs text-green-700">Generar para participantes, ganadores y jueces</p>
                </div>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.constancias.generar-evento', $evento) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-file-certificate mr-1"></i>
                        Generar
                    </button>
                </form>
                <a href="{{ route('admin.constancias.evento', $evento) }}"
                   class="flex-1 px-3 py-2 bg-white border border-green-600 text-green-600 text-sm font-medium rounded-lg hover:bg-green-50 transition-colors text-center">
                    <i class="fas fa-eye mr-1"></i>
                    Ver
                </a>
            </div>
        </div>
    </div>
</x-admin.card>
@endsection
