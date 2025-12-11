@extends('layouts.estudiante')

@section('title', 'Editar Perfil')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Editar Perfil</h1>
                <p class="text-gray-600 mt-1">Actualiza tu información personal y profesional</p>
            </div>
            <a href="{{ route('estudiante.perfil.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Cancelar
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mt-0.5 mr-2"></i>
                    <div>
                        <p class="font-semibold">Por favor corrige los siguientes errores:</p>
                        <ul class="list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('estudiante.perfil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Académica</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="numero_control" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Control <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="numero_control"
                                   name="numero_control"
                                   value="{{ old('numero_control', $user->datosEstudiante->numero_control ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('numero_control') border-red-500 @enderror"
                                   required>
                            @error('numero_control')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="carrera" class="block text-sm font-medium text-gray-700 mb-2">
                                Carrera <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="carrera"
                                   name="carrera"
                                   value="{{ old('carrera', $user->datosEstudiante->carrera ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('carrera') border-red-500 @enderror"
                                   required>
                            @error('carrera')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="semestre" class="block text-sm font-medium text-gray-700 mb-2">
                                Semestre <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="semestre"
                                   name="semestre"
                                   min="1"
                                   max="12"
                                   value="{{ old('semestre', $user->datosEstudiante->semestre ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('semestre') border-red-500 @enderror"
                                   required>
                            @error('semestre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Contacto</h3>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6">
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text"
                                   id="telefono"
                                   name="telefono"
                                   value="{{ old('telefono', $user->datosEstudiante->telefono ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Enlaces Profesionales</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="github" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fab fa-github mr-2"></i>GitHub
                            </label>
                            <input type="url"
                                   id="github"
                                   name="github"
                                   value="{{ old('github', $user->datosEstudiante->github ?? '') }}"
                                   placeholder="https://github.com/usuario"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('github') border-red-500 @enderror">
                            @error('github')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fab fa-linkedin mr-2"></i>LinkedIn
                            </label>
                            <input type="url"
                                   id="linkedin"
                                   name="linkedin"
                                   value="{{ old('linkedin', $user->datosEstudiante->linkedin ?? '') }}"
                                   placeholder="https://linkedin.com/in/usuario"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('linkedin') border-red-500 @enderror">
                            @error('linkedin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="portafolio" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-globe mr-2"></i>Portafolio
                            </label>
                            <input type="url"
                                   id="portafolio"
                                   name="portafolio"
                                   value="{{ old('portafolio', $user->datosEstudiante->portafolio ?? '') }}"
                                   placeholder="https://miportafolio.com"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('portafolio') border-red-500 @enderror">
                            @error('portafolio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('estudiante.perfil.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
