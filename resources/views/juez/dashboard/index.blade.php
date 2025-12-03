@extends('layouts.juez')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Encabezado de Bienvenida -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Bienvenido/a, {{ auth()->user()->name }}
        </h1>
        <p class="text-gray-600">Panel de Juez - Evaluación de Proyectos</p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Total de Equipos -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-2xl text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Equipos Disponibles</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalEquipos }}</p>
                        <p class="text-xs text-gray-500 mt-1">Con proyectos asignados</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3">
                <a href="{{ route('juez.evaluaciones.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Ver todos los equipos <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Evaluaciones Realizadas -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Evaluaciones Realizadas</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $misEvaluaciones }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total completadas</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3">
                <a href="{{ route('juez.mis-evaluaciones') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                    Ver mis evaluaciones <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Pendientes -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-2xl text-orange-600"></i>
                        </div>
                    </div>
                    <div class="ml-5 flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Pendientes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $pendientes }}</p>
                        <p class="text-xs text-gray-500 mt-1">Por evaluar</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3">
                <a href="{{ route('juez.evaluaciones.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                    Evaluar ahora <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('juez.evaluaciones.index') }}"
               class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-clipboard-check text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 group-hover:text-purple-700">Evaluar Equipos</h3>
                    <p class="text-sm text-gray-600">Revisa y evalúa los proyectos de los equipos</p>
                </div>
                <i class="fas fa-arrow-right text-purple-600 ml-4"></i>
            </a>

            <a href="{{ route('juez.mis-evaluaciones') }}"
               class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-star text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 group-hover:text-blue-700">Mis Evaluaciones</h3>
                    <p class="text-sm text-gray-600">Revisa tu historial de evaluaciones</p>
                </div>
                <i class="fas fa-arrow-right text-blue-600 ml-4"></i>
            </a>
        </div>
    </div>

    <!-- Información del Sistema -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-lg shadow-sm p-6 text-white">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-3xl text-purple-200"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold mb-2">Sistema de Evaluación</h3>
                <p class="text-purple-100 mb-3">Como juez, puedes evaluar los proyectos de los equipos utilizando los siguientes criterios:</p>
                <ul class="space-y-1 text-purple-100">
                    <li><i class="fas fa-lightbulb mr-2"></i>Innovación (0-20 puntos)</li>
                    <li><i class="fas fa-cogs mr-2"></i>Funcionalidad (0-20 puntos)</li>
                    <li><i class="fas fa-presentation mr-2"></i>Presentación (0-20 puntos)</li>
                    <li><i class="fas fa-bullseye mr-2"></i>Impacto (0-20 puntos)</li>
                    <li><i class="fas fa-code mr-2"></i>Aspectos Técnicos (0-20 puntos)</li>
                </ul>
                <p class="text-purple-100 mt-3 text-sm">La puntuación total será sobre 100 puntos.</p>
            </div>
        </div>
    </div>
</div>
@endsection
