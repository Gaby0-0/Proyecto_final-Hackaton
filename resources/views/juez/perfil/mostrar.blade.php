@extends('layouts.juez')

@section('title', 'Mi Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">Mi Perfil</h1>
            <a href="{{ route('juez.perfil.editar') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Editar Perfil
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Información del perfil -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Banner superior -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 h-32"></div>

            <!-- Contenido del perfil -->
            <div class="px-8 pb-8">
                <!-- Avatar e información básica -->
                <div class="relative -mt-16 mb-6">
                    <div class="flex items-end space-x-5">
                        <div class="flex-shrink-0">
                            <div class="h-32 w-32 rounded-full border-4 border-white bg-gray-300 flex items-center justify-center text-4xl font-bold text-gray-600">
                                {{ substr($user->nombre_completo ?? $user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 pb-2">
                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ $user->nombre_completo ?? $user->name }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-briefcase mr-1"></i>
                                {{ $user->datosJuez?->especialidad ?? 'No especificada' }}
                            </p>
                            <div class="mt-2">
                                @if($user->activo)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Cuenta Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Cuenta Inactiva
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles del perfil -->
                <div class="border-t border-gray-200 pt-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <!-- Email -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-envelope mr-2"></i>
                                Correo Electrónico
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>

                        <!-- Nombre Completo -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-user mr-2"></i>
                                Nombre Completo
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->nombre_completo ?? 'No especificado' }}</dd>
                        </div>

                        <!-- Especialidad -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Especialidad
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->datosJuez?->especialidad ?? 'No especificada' }}</dd>
                        </div>

                        <!-- Rol -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-user-tag mr-2"></i>
                                Rol
                            </dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </dd>
                        </div>

                        <!-- Fecha de registro -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Miembro desde
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y') }}</dd>
                        </div>

                        <!-- Última actualización -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-clock mr-2"></i>
                                Última actualización
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Estadísticas -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h3>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <!-- Equipos asignados -->
                        <div class="bg-blue-50 overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-users text-2xl text-blue-600"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Equipos Asignados</dt>
                                            <dd class="text-2xl font-semibold text-gray-900">{{ $user->equiposAsignados->count() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Evaluaciones realizadas -->
                        <div class="bg-green-50 overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clipboard-check text-2xl text-green-600"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Evaluaciones</dt>
                                            <dd class="text-2xl font-semibold text-gray-900">{{ $user->evaluaciones->count() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Eventos asignados -->
                        <div class="bg-purple-50 overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar text-2xl text-purple-600"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Eventos</dt>
                                            <dd class="text-2xl font-semibold text-gray-900">{{ $user->eventosAsignados->count() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
