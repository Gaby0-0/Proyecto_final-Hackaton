@extends('layouts.admin')

@section('title', 'Gestión de Eventos')

@php
$pageTitle = 'Gestión de Eventos';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Eventos']
];
$pageActions = '<a href="' . route('admin.eventos.create') . '" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
    <i class="fas fa-plus mr-2"></i>
    Nuevo Evento
</a>';
@endphp

@section('content')
<!-- Filtros -->
<x-admin.card class="mb-6">
    <form method="GET" action="{{ route('admin.eventos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Nombre del evento..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
            <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="programado" {{ request('estado') == 'programado' ? 'selected' : '' }}>Programado</option>
                <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
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

<!-- Tabla de Eventos -->
<x-admin.card>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Evento
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fechas
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Equipos
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Modalidad
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($eventos as $evento)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $evento->nombre }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $evento->tipo ? ucfirst($evento->tipo) : 'Sin tipo' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <i class="fas fa-calendar mr-1 text-gray-400"></i>
                            @if($evento->fecha_inicio)
                                {{ $evento->fecha_inicio->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-arrow-right mr-1 text-gray-400"></i>
                            @if($evento->fecha_fin)
                                {{ $evento->fecha_fin->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php
                        $equiposInscritos = isset($evento->equipos_count) ? $evento->equipos_count : 0;
                        $maxEquipos = isset($evento->max_equipos) && $evento->max_equipos ? $evento->max_equipos : 50;
                        $porcentaje = $maxEquipos > 0 ? ($equiposInscritos / $maxEquipos) * 100 : 0;
                        @endphp
                        <div class="text-sm font-medium text-gray-900">
                            {{ $equiposInscritos }} / {{ $maxEquipos }}
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min($porcentaje, 100) }}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php
                        $iconoModalidad = [
                            'presencial' => 'fa-building',
                            'virtual' => 'fa-laptop',
                            'hibrida' => 'fa-globe'
                        ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas {{ $iconoModalidad[$evento->modalidad] ?? 'fa-question' }} mr-1"></i>
                            {{ ucfirst($evento->modalidad) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.eventos.show', $evento->id) }}"
                               class="text-blue-600 hover:text-blue-900"
                               title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.eventos.asignar-jueces', $evento->id) }}"
                               class="text-purple-600 hover:text-purple-900"
                               title="Asignar Jueces">
                                <i class="fas fa-user-tie"></i>
                            </a>
                            <a href="{{ route('admin.eventos.edit', $evento->id) }}"
                               class="text-indigo-600 hover:text-indigo-900"
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.eventos.destroy', $evento->id) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar este evento? Se eliminarán todas las inscripciones.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-900"
                                        title="Eliminar">
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
                            <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg mb-4">No hay eventos registrados</p>
                            <a href="{{ route('admin.eventos.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Crear Primer Evento
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($eventos->hasPages())
    <div class="mt-4">
        {{ $eventos->links() }}
    </div>
    @endif
</x-admin.card>
@endsection
