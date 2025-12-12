@extends('layouts.admin')

@section('title', 'Gestión de Jueces')

@php
$pageTitle = 'Gestión de Jueces';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Jueces']
];
$pageActions = '<a href="' . route('admin.jueces.create') . '" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors mr-2">
    <i class="fas fa-plus mr-2"></i>
    Nuevo Juez
</a>
<a href="' . route('admin.jueces.asignar') . '" class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
    <i class="fas fa-random mr-2"></i>
    Asignar Jueces
</a>';
@endphp

@section('content')
<!-- Filtros -->
<x-admin.card class="mb-6">
    <form method="GET" action="{{ route('admin.jueces.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Nombre, email o especialidad..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Especialidad</label>
            <select name="especialidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todas</option>
                @foreach($especialidades as $esp)
                    <option value="{{ $esp }}" {{ request('especialidad') == $esp ? 'selected' : '' }}>
                        {{ $esp }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
            <select name="activo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos</option>
                <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
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

<!-- Tabla de Jueces -->
<x-admin.card>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Juez
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Especialidad
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Asignaciones
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jueces as $juez)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">
                                        {{ strtoupper(substr($juez->nombre_completo ?? $juez->name, 0, 2)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $juez->nombre_completo ?? $juez->name }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $juez->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($juez->datosJuez?->especialidad)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $juez->datosJuez->especialidad }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400 italic">Sin especialidad</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex justify-center gap-2">
                            <x-admin.badge variant="info">
                                {{ $juez->equipos_asignados_count }} equipos
                            </x-admin.badge>
                            <x-admin.badge variant="success">
                                {{ $juez->eventos_asignados_count }} eventos
                            </x-admin.badge>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($juez->datosJuez?->activo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Activo
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.jueces.show', $juez->id) }}" class="text-blue-600 hover:text-blue-900" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.jueces.edit', $juez->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.jueces.destroy', $juez->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este juez? Se eliminarán todas sus asignaciones.')">
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
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-gavel text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg mb-4">No hay jueces registrados</p>
                            <a href="{{ route('admin.jueces.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Crear Primer Juez
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($jueces->hasPages())
    <div class="mt-4">
        {{ $jueces->links() }}
    </div>
    @endif
</x-admin.card>
@endsection
