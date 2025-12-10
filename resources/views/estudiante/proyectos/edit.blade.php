@extends('layouts.estudiante')

@section('title', 'Gestionar Proyecto')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('estudiante.proyectos.index') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-project-diagram mr-2"></i>Mis Proyectos
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-500">Gestionar</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Encabezado -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="text-white">
                <h1 class="text-3xl font-bold mb-2">{{ $equipo->nombre }}</h1>
                <p class="text-blue-100">
                    <i class="fas fa-calendar mr-2"></i>{{ $evento->nombre }}
                    @if($evento->categoria)
                        <span class="ml-3 px-2.5 py-0.5 bg-white/20 rounded-full text-sm">{{ $evento->categoria }}</span>
                    @endif
                </p>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 bg-white/20 text-white rounded-lg text-sm">
                    {{ ucfirst($inscripcion->pivot->estado) }}
                </span>
                @if($esLider)
                    <p class="text-yellow-300 text-sm mt-2">
                        <i class="fas fa-crown mr-1"></i>Eres el líder
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información del Proyecto -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Información del Proyecto
                    </h2>
                    @if(!$esLider)
                        <span class="text-sm text-amber-600">
                            <i class="fas fa-lock mr-1"></i>Solo el líder puede editar
                        </span>
                    @endif
                </div>

                <form action="{{ route('estudiante.proyectos.update-info', ['equipo' => $equipo->id, 'evento' => $evento->id]) }}"
                      method="POST"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Título del Proyecto <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="proyecto_titulo"
                               value="{{ old('proyecto_titulo', $inscripcion->pivot->proyecto_titulo) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('proyecto_titulo') border-red-500 @enderror"
                               {{ !$esLider ? 'readonly' : 'required' }}
                               placeholder="Ej: Sistema de Gestión de Hackathon">
                        @error('proyecto_titulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción del Proyecto <span class="text-red-500">*</span>
                        </label>
                        <textarea name="proyecto_descripcion"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('proyecto_descripcion') border-red-500 @enderror"
                                  {{ !$esLider ? 'readonly' : 'required' }}
                                  placeholder="Describe tu proyecto, sus objetivos y características principales...">{{ old('proyecto_descripcion', $inscripcion->pivot->proyecto_descripcion) }}</textarea>
                        @error('proyecto_descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Notas del Equipo (opcional)
                        </label>
                        <textarea name="notas_equipo"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  {{ !$esLider ? 'readonly' : '' }}
                                  placeholder="Notas internas del equipo, ideas, pendientes...">{{ old('notas_equipo', $inscripcion->pivot->notas_equipo) }}</textarea>
                    </div>

                    @if($esLider)
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Guardar Información
                            </button>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Avances del Proyecto -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-tasks text-blue-600 mr-2"></i>
                        Avances del Proyecto
                    </h2>
                    @if($esLider)
                        <button type="button"
                                onclick="document.getElementById('modal-avance').classList.remove('hidden')"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Subir Avance
                        </button>
                    @endif
                </div>

                @if(empty($avances))
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-3"></i>
                        <p>No hay avances registrados</p>
                        @if($esLider)
                            <p class="text-sm mt-1">Sube tu primer avance para comenzar</p>
                        @endif
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($avances as $index => $avance)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $avance['descripcion'] }}</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-user mr-1"></i>{{ $avance['usuario'] }}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($avance['fecha'])->format('d/m/Y H:i') }}
                                        </p>
                                        <div class="mt-2">
                                            <a href="{{ Storage::url($avance['archivo']) }}"
                                               target="_blank"
                                               class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm">
                                                <i class="fas fa-file-download mr-1"></i>
                                                {{ $avance['archivo_nombre'] ?? 'Descargar archivo' }}
                                            </a>
                                        </div>
                                    </div>
                                    @if($esLider)
                                        <form action="{{ route('estudiante.proyectos.eliminar-avance', ['equipo' => $equipo->id, 'evento' => $evento->id, 'indice' => $index]) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este avance?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Proyecto Final -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-flag-checkered text-blue-600 mr-2"></i>
                        Proyecto Final
                    </h2>
                </div>

                @if($inscripcion->pivot->proyecto_final_url)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-green-800 font-medium mb-2">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Proyecto final entregado
                                </p>
                                <p class="text-sm text-green-700 mb-3">
                                    Fecha de entrega: {{ \Carbon\Carbon::parse($inscripcion->pivot->fecha_entrega_final)->format('d/m/Y H:i') }}
                                </p>
                                <a href="{{ Storage::url($inscripcion->pivot->proyecto_final_url) }}"
                                   target="_blank"
                                   class="inline-flex items-center text-green-700 hover:text-green-800 font-medium">
                                    <i class="fas fa-download mr-2"></i>
                                    Descargar proyecto final
                                </a>
                            </div>
                            @if($esLider)
                                <form action="{{ route('estudiante.proyectos.eliminar-final', ['equipo' => $equipo->id, 'evento' => $evento->id]) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Estás seguro de eliminar el proyecto final?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-amber-800 mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Proyecto final pendiente de entrega
                        </p>
                        @if($esLider)
                            <button type="button"
                                    onclick="document.getElementById('modal-final').classList.remove('hidden')"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Subir Proyecto Final
                            </button>
                        @else
                            <p class="text-sm text-amber-700">Solo el líder puede subir el proyecto final</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="space-y-6">
            <!-- Información del equipo -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-users text-blue-600 mr-2"></i>
                    Miembros del Equipo
                </h3>
                <div class="space-y-3">
                    @foreach($equipo->miembros as $miembro)
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-medium">
                                {{ strtoupper(substr($miembro->name, 0, 2)) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $miembro->name }}</p>
                                <p class="text-xs text-gray-500">{{ $miembro->email }}</p>
                            </div>
                            @if($miembro->pivot->rol_equipo === 'lider')
                                <span class="ml-auto text-yellow-500">
                                    <i class="fas fa-crown"></i>
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Información del evento -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-calendar text-blue-600 mr-2"></i>
                    Información del Evento
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Fecha de inicio</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Fecha de fin</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}</p>
                    </div>
                    @if($evento->descripcion)
                        <div>
                            <p class="text-gray-600 mb-1">Descripción</p>
                            <p class="text-gray-700">{{ $evento->descripcion }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Subir Avance -->
@if($esLider)
<div id="modal-avance" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Subir Avance</h3>
            <button type="button"
                    onclick="document.getElementById('modal-avance').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('estudiante.proyectos.subir-avance', ['equipo' => $equipo->id, 'evento' => $evento->id]) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción del Avance <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="descripcion"
                       required
                       value="{{ old('descripcion') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror"
                       placeholder="Ej: Primera versión del diseño">
                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Archivo <span class="text-red-500">*</span>
                </label>
                <input type="file"
                       name="archivo"
                       required
                       accept=".pdf,.doc,.docx,.zip,.rar,.pptx,.mp4,.avi"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('archivo') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Máx. 50MB. Formatos: PDF, DOC, DOCX, ZIP, RAR, PPTX, MP4, AVI</p>
                @error('archivo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="document.getElementById('modal-avance').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>
                    Subir Avance
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Subir Proyecto Final -->
<div id="modal-final" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Subir Proyecto Final</h3>
            <button type="button"
                    onclick="document.getElementById('modal-final').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('estudiante.proyectos.subir-final', ['equipo' => $equipo->id, 'evento' => $evento->id]) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-amber-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Importante:</strong> Este será tu proyecto final. Asegúrate de que esté completo antes de subirlo.
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Archivo del Proyecto Final <span class="text-red-500">*</span>
                </label>
                <input type="file"
                       name="proyecto_final"
                       required
                       accept=".pdf,.doc,.docx,.zip,.rar,.pptx,.mp4,.avi"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('proyecto_final') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Máx. 100MB. Formatos: PDF, DOC, DOCX, ZIP, RAR, PPTX, MP4, AVI</p>
                @error('proyecto_final')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="document.getElementById('modal-final').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>
                    Subir Proyecto Final
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
