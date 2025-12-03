@extends('layouts.admin')

@section('title', 'Gestión de Equipos')

@section('breadcrumbs')
<nav class="flex mb-8" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-gray-900 font-medium">Equipos</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Equipos</h1>
            <p class="text-gray-600 mt-1">Gestiona los equipos de estudiantes</p>
        </div>
        <a href="{{ route('admin.equipos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Nuevo Equipo
        </a>
    </div>

    <!-- Filtros -->
    <x-admin.card>
        <form method="GET" action="{{ route('admin.equipos.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nombre del equipo..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Proyecto</label>
                <select name="proyecto" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Todos los proyectos</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}" {{ request('proyecto') == $proyecto->id ? 'selected' : '' }}>
                            {{ $proyecto->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
                <a href="{{ route('admin.equipos.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </x-admin.card>

    <!-- Tabla de Equipos -->
    <x-admin.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proyecto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Miembros</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Líder</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Creado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($equipos as $equipo)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-users text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $equipo->nombre }}</div>
                                        @if($equipo->descripcion)
                                            <div class="text-xs text-gray-500">{{ Str::limit($equipo->descripcion, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($equipo->proyecto)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $equipo->proyecto->nombre }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">Sin proyecto</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $equipo->miembros->count() }}
                                    <i class="fas fa-user ml-1"></i>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $lider = $equipo->miembros->where('pivot.rol_equipo', 'lider')->first();
                                @endphp
                                @if($lider)
                                    <div class="text-sm text-gray-900">{{ $lider->name }}</div>
                                @else
                                    <span class="text-gray-400 text-sm">Sin líder</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $equipo->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.equipos.show', $equipo) }}"
                                   class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.equipos.edit', $equipo) }}"
                                   class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.equipos.destroy', $equipo) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este equipo? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">No hay equipos registrados</p>
                                    <a href="{{ route('admin.equipos.create') }}"
                                       class="mt-4 text-blue-600 hover:text-blue-700 font-medium">
                                        Crear el primer equipo
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($equipos->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $equipos->links() }}
            </div>
        @endif
    </x-admin.card>
</div>
@endsection
