@extends('layouts.estudiante')

@section('title', 'Mis Equipos')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gestión de Equipos</h1>
        <a href="{{ route('estudiante.equipos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Crear Equipo
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Mis Equipos -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mis Equipos</h2>
        @if($misEquipos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($misEquipos as $equipo)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-bold text-gray-900">{{ $equipo->nombre }}</h3>
                            @php
                                $esLider = $equipo->miembros->where('id', auth()->id())->first()->pivot->rol_equipo == 'lider';
                            @endphp
                            @if($esLider)
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-crown"></i> Líder
                                </span>
                            @endif
                        </div>

                        @if($equipo->descripcion)
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($equipo->descripcion, 100) }}</p>
                        @endif

                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-users mr-2"></i>
                            <span>{{ $equipo->miembros->count() }} / {{ $equipo->max_integrantes }} miembros</span>
                        </div>

                        @if($equipo->proyecto)
                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                <i class="fas fa-project-diagram mr-2"></i>
                                <span>{{ $equipo->proyecto->nombre }}</span>
                            </div>
                        @endif

                        <div class="flex space-x-2">
                            <a href="{{ route('estudiante.equipos.show', $equipo) }}" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded text-center text-sm">
                                Ver Detalles
                            </a>
                            @if(!$esLider)
                                <form action="{{ route('estudiante.equipos.salir', $equipo) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded text-sm" onclick="return confirm('¿Estás seguro de que quieres salir de este equipo?')">
                                        Salir
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-600 mb-4">Aún no eres parte de ningún equipo</p>
                <a href="{{ route('estudiante.equipos.create') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    Crea tu primer equipo o únete a uno existente
                </a>
            </div>
        @endif
    </div>

    <!-- Unirse a un Equipo con Código -->
    <div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Unirse a un Equipo</h2>
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="max-w-2xl mx-auto">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-key text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">¿Tienes un código de equipo?</h3>
                    <p class="text-gray-600">
                        Solicita el código de 8 caracteres a tu líder de equipo para unirte
                    </p>
                </div>

                <form action="{{ route('estudiante.equipos.unirse-codigo') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                            Código del Equipo
                        </label>
                        <input type="text"
                               id="codigo"
                               name="codigo"
                               maxlength="8"
                               required
                               placeholder="ABC12XYZ"
                               class="uppercase appearance-none block w-full px-6 py-4 border-2 border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center text-3xl font-mono tracking-widest @error('codigo') border-red-500 @enderror">
                        @error('codigo')
                            <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-all">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Unirse al Equipo
                    </button>
                </form>

                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">¿No tienes código?</p>
                            <p>Los códigos son proporcionados por los líderes de equipo. También puedes crear tu propio equipo y obtener un código para compartir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
