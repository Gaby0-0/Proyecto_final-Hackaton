@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@php
$pageTitle = 'Gestión de Usuarios';
$breadcrumbs = [
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Usuarios']
];
$pageActions = '<a href="' . route('admin.usuarios.create') . '" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
    <i class="fas fa-plus mr-2"></i>
    Nuevo Usuario
</a>';
@endphp

@section('content')
<!-- Filtros -->
<x-admin.card class="mb-6">
    <form method="GET" action="{{ route('admin.usuarios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Nombre o email..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
            <select name="rol" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="todos">Todos los roles</option>
                <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="juez" {{ request('rol') == 'juez' ? 'selected' : '' }}>Juez</option>
                <option value="estudiante" {{ request('rol') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
            <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="todos">Todos los estados</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
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

<!-- Tabla de Usuarios -->
<x-admin.card>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Usuario
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Rol
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Último Acceso
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($usuarios as $usuario)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">
                                        {{ substr($usuario->nombre ?? $usuario->name ?? 'U', 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $usuario->nombre ?? $usuario->name }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $usuario->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                        $rolColors = [
                            'Administrador' => 'primary',
                            'admin' => 'primary',
                            'Juez' => 'info',
                            'juez' => 'info',
                            'Estudiante' => 'success',
                            'estudiante' => 'success',
                        ];
                        $rol = $usuario->rol ?? $usuario->role ?? 'estudiante';
                        @endphp
                        <x-admin.badge :variant="$rolColors[$rol] ?? 'default'">
                            {{ ucfirst($rol) }}
                        </x-admin.badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                        $estado = $usuario->estado ?? 'Activo';
                        @endphp
                        <x-admin.badge :variant="$estado === 'Activo' ? 'success' : 'danger'">
                            {{ $estado }}
                        </x-admin.badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $usuario->ultimo_acceso ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.usuarios.show', $usuario->id) }}" class="text-blue-600 hover:text-blue-900" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este usuario?')">
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
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg mb-4">No hay usuarios registrados</p>
                            <x-admin.button :href="route('admin.usuarios.create')" icon="fas fa-plus">
                                Crear Primer Usuario
                            </x-admin.button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($usuarios) && method_exists($usuarios, 'links'))
    <div class="mt-4">
        {{ $usuarios->links() }}
    </div>
    @endif
</x-admin.card>
@endsection
