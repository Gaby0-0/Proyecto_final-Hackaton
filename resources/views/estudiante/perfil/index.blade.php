@extends('layouts.estudiante')

@section('title', 'Mi Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">Mi Perfil</h1>
            <a href="{{ route('estudiante.perfil.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
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
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 h-32"></div>

            <!-- Contenido del perfil -->
            <div class="px-8 pb-8">
                <!-- Avatar e información básica -->
                <div class="relative -mt-16 mb-6">
                    <div class="flex items-end gap-x-5">
                        <div class="flex-shrink-0">
                            <div class="h-32 w-32 rounded-full border-4 border-white bg-blue-600 flex items-center justify-center text-4xl font-bold text-white">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 pb-2">
                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ $user->name }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-graduation-cap mr-1"></i>
                                {{ $user->datosEstudiante->carrera ?? 'Estudiante' }}
                            </p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Cuenta Activa
                                </span>
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

                        <!-- Número de Control -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-id-card mr-2"></i>
                                Número de Control
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->datosEstudiante->numero_control ?? 'No especificado' }}</dd>
                        </div>

                        <!-- Carrera -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-book mr-2"></i>
                                Carrera
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->datosEstudiante->carrera ?? 'No especificada' }}</dd>
                        </div>

                        <!-- Semestre -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-calendar mr-2"></i>
                                Semestre
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->datosEstudiante->semestre ?? 'No especificado' }}</dd>
                        </div>

                        <!-- Teléfono -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-phone mr-2"></i>
                                Teléfono
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->datosEstudiante->telefono ?? 'No especificado' }}</dd>
                        </div>

                        <!-- Fecha de registro -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Miembro desde
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y') }}</dd>
                        </div>
                    </dl>

                    <!-- Enlaces profesionales -->
                    @if($user->datosEstudiante && ($user->datosEstudiante->github || $user->datosEstudiante->linkedin || $user->datosEstudiante->portafolio))
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-500 mb-3">Enlaces Profesionales</h3>
                            <div class="flex flex-wrap gap-3">
                                @if($user->datosEstudiante->github)
                                    <a href="{{ $user->datosEstudiante->github }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                        <i class="fab fa-github mr-2"></i>
                                        GitHub
                                    </a>
                                @endif
                                @if($user->datosEstudiante->linkedin)
                                    <a href="{{ $user->datosEstudiante->linkedin }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fab fa-linkedin mr-2"></i>
                                        LinkedIn
                                    </a>
                                @endif
                                @if($user->datosEstudiante->portafolio)
                                    <a href="{{ $user->datosEstudiante->portafolio }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                        <i class="fas fa-globe mr-2"></i>
                                        Portafolio
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Mis Equipos -->
                @if($user->equipos->isNotEmpty())
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Equipos</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @foreach($user->equipos as $equipo)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900">{{ $equipo->nombre }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                            {{ $equipo->pivot->rol === 'lider' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($equipo->pivot->rol) }}
                                        </span>
                                    </p>
                                    @if($equipo->pivot->rol_especifico)
                                        <p class="text-sm text-gray-500 mt-2">{{ $equipo->pivot->rol_especifico }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
