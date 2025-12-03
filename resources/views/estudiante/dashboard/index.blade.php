@extends('layouts.estudiante')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Saludo -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">¡Bienvenido/a, {{ $user->name }}!</h1>
        <p class="text-blue-100">
            @if($user->datosEstudiante)
                {{ $user->datosEstudiante->carrera }}
                @if($user->datosEstudiante->semestre)
                    - {{ $user->datosEstudiante->semestre }}° Semestre
                @endif
            @else
                Completa tus datos para una mejor experiencia
            @endif
        </p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-2xl text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Mis Equipos</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ $user->equipos()->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-blue-50 px-6 py-3">
                <a href="{{ route('estudiante.equipos.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Ver equipos <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-crown text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Líder en</dt>
                            <dd class="text-3xl font-bold text-gray-900">{{ $user->equiposComoLider()->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 px-6 py-3">
                <span class="text-sm text-green-600 font-medium">
                    Equipos que lideras
                </span>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-2xl text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Eventos</dt>
                            <dd class="text-3xl font-bold text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-purple-50 px-6 py-3">
                <span class="text-sm text-purple-600 font-medium">
                    Próximamente
                </span>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Evaluaciones</dt>
                            <dd class="text-3xl font-bold text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-50 px-6 py-3">
                <span class="text-sm text-yellow-600 font-medium">
                    Próximamente
                </span>
            </div>
        </div>
    </div>

    @if($user->equipos()->count() == 0)
    <!-- Call to Action - Sin Equipos -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-8 sm:p-10">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-users text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">¡Comienza tu Aventura!</h3>
                <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                    Aún no formas parte de ningún equipo. Crea tu propio equipo y conviértete en líder,
                    o únete a un equipo existente con el código que te proporcione tu compañero.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('estudiante.equipos.create') }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all">
                        <i class="fas fa-plus mr-2"></i>
                        Crear Equipo
                    </a>
                    <a href="{{ route('estudiante.equipos.index') }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 shadow-lg hover:shadow-xl transition-all">
                        <i class="fas fa-user-plus mr-2"></i>
                        Unirse a Equipo
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Mis Equipos -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-users text-blue-600 mr-2"></i>
                    Mis Equipos
                </h3>
                <a href="{{ route('estudiante.equipos.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Ver todos <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($user->equipos()->limit(3)->get() as $equipo)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between mb-3">
                            <h4 class="font-semibold text-gray-900 text-lg">{{ $equipo->nombre }}</h4>
                            @php
                                $esLider = $equipo->miembros->where('id', $user->id)->first()->pivot->rol_equipo == 'lider';
                            @endphp
                            @if($esLider)
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-crown"></i> Líder
                                </span>
                            @endif
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-users w-4 mr-2"></i>
                                <span>{{ $equipo->miembros->count() }} / {{ $equipo->max_integrantes }} miembros</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-code w-4 mr-2"></i>
                                <span class="font-mono">{{ $equipo->codigo }}</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('estudiante.equipos.show', $equipo) }}"
                               class="block text-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Guía Rápida -->
    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg shadow-lg p-6 border border-indigo-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
            Guía Rápida
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                        1
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Forma tu Equipo</h4>
                    <p class="text-sm text-gray-600 mt-1">Crea o únete a un equipo para participar en eventos</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                        2
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Participa en Eventos</h4>
                    <p class="text-sm text-gray-600 mt-1">Inscribe tu equipo en hackatones y competencias</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                        3
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Gana Reconocimientos</h4>
                    <p class="text-sm text-gray-600 mt-1">Obtén constancias y premios por tu participación</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Perfil -->
    @if($user->datosEstudiante)
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                Mi Información
            </h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Número de Control:</span>
                    <p class="font-medium text-gray-900">{{ $user->datosEstudiante->numero_control }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Carrera:</span>
                    <p class="font-medium text-gray-900">{{ $user->datosEstudiante->carrera }}</p>
                </div>
                @if($user->datosEstudiante->telefono)
                <div>
                    <span class="text-sm text-gray-500">Teléfono:</span>
                    <p class="font-medium text-gray-900">{{ $user->datosEstudiante->telefono }}</p>
                </div>
                @endif
                @if($user->datosEstudiante->semestre)
                <div>
                    <span class="text-sm text-gray-500">Semestre:</span>
                    <p class="font-medium text-gray-900">{{ $user->datosEstudiante->semestre }}°</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
