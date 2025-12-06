@extends('layouts.admin')

@section('title', 'Gestión de Proyectos')

@php
$pageTitle = 'Proyectos Entregados por Equipos';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Proyectos']
];
$pageActions = '';
@endphp

@section('content')
<!-- Filtros -->
<x-admin.card class="mb-6">
    <form method="GET" action="{{ route('admin.proyectos.index') }}" class="flex gap-4">
        <div class="flex-1">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Buscar por nombre o descripción..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
            <i class="fas fa-filter mr-2"></i>
            Filtrar
        </button>
    </form>
</x-admin.card>

<!-- Tabla de Proyectos -->
<x-admin.card>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Proyecto
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Equipo
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Evento / Categoría
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha de Entrega
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($proyectos as $proyecto)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-project-diagram text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $proyecto->proyecto_titulo }}
                                </div>
                                @if($proyecto->proyecto_descripcion)
                                    <div class="text-sm text-gray-500">
                                        {{ Str::limit($proyecto->proyecto_descripcion, 60) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $proyecto->equipo_nombre }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $proyecto->evento_nombre }}
                        </div>
                        @if($proyecto->evento_categoria)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                {{ $proyecto->evento_categoria }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($proyecto->fecha_entrega_final)
                            <div>
                                <i class="fas fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($proyecto->fecha_entrega_final)->format('d/m/Y H:i') }}
                            </div>
                        @else
                            <span class="text-gray-400 italic">No entregado</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.proyectos.show', $proyecto->pivot_id) }}" class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($proyecto->proyecto_final_url)
                                <a href="{{ $proyecto->proyecto_final_url }}" target="_blank" class="text-green-600 hover:text-green-900" title="Ver proyecto">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-project-diagram text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg mb-4">No hay proyectos entregados aún</p>
                            <p class="text-gray-400 text-sm">Los equipos entregarán sus proyectos al inscribirse en los eventos</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($proyectos->hasPages())
    <div class="mt-4">
        {{ $proyectos->links() }}
    </div>
    @endif
</x-admin.card>
@endsection
