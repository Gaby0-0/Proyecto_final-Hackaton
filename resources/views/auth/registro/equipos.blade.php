<!DOCTYPE html>
<html lang="es" class="h-full bg-gradient-to-br from-blue-50 to-indigo-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unirse o Crear Equipo - ConcursITO</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="h-full">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-blue-900">ConcursITO</h1>
                <div class="mt-6">
                    <div class="flex justify-center items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-700">Cuenta</span>
                        </div>
                        <div class="w-16 h-1 bg-green-500"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-700">Datos</span>
                        </div>
                        <div class="w-16 h-1 bg-blue-600"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                <i class="fas fa-3"></i>
                            </div>
                            <span class="ml-2 text-sm font-medium text-blue-900">Equipo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-4xl" x-data="{ opcion: '' }">
            @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400"></i>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white py-8 px-4 shadow-2xl sm:rounded-lg sm:px-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">¡Último Paso!</h2>
                <p class="text-gray-600 mb-8">Puedes unirte a un equipo existente o crear tu propio equipo</p>

                <!-- Selector de opción -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <button @click="opcion = 'unirse'"
                            :class="opcion === 'unirse' ? 'ring-2 ring-blue-600 bg-blue-50' : 'ring-1 ring-gray-300'"
                            class="relative rounded-lg p-6 hover:bg-gray-50 transition-all cursor-pointer">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                                <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Unirse a un Equipo</h3>
                            <p class="text-sm text-gray-600 text-center">Tengo un código de equipo</p>
                        </div>
                    </button>

                    <button @click="opcion = 'crear'"
                            :class="opcion === 'crear' ? 'ring-2 ring-blue-600 bg-blue-50' : 'ring-1 ring-gray-300'"
                            class="relative rounded-lg p-6 hover:bg-gray-50 transition-all cursor-pointer">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                                <i class="fas fa-users text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Crear un Equipo</h3>
                            <p class="text-sm text-gray-600 text-center">Seré el líder del equipo</p>
                        </div>
                    </button>
                </div>

                <!-- Formulario Unirse a Equipo -->
                <div x-show="opcion === 'unirse'" x-transition class="space-y-6" style="display: none;">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    Solicita el código del equipo a tu líder de equipo. El código tiene 8 caracteres.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('registro.equipos.unirse') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                                Código del Equipo <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="codigo"
                                   name="codigo"
                                   value="{{ old('codigo') }}"
                                   maxlength="8"
                                   required
                                   placeholder="Ej: ABC12XYZ"
                                   class="uppercase appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-center text-2xl font-mono tracking-widest @error('codigo') border-red-300 @enderror">
                            @error('codigo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between">
                            <button type="button"
                                    @click="opcion = ''"
                                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver
                            </button>
                            <button type="submit"
                                    class="px-6 py-3 border border-transparent text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                Unirse al Equipo
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Formulario Crear Equipo -->
                <div x-show="opcion === 'crear'" x-transition class="space-y-6" style="display: none;">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex">
                            <i class="fas fa-info-circle text-green-600 mt-0.5"></i>
                            <div class="ml-3">
                                <p class="text-sm text-green-800">
                                    Al crear un equipo, te convertirás automáticamente en el líder. Recibirás un código para compartir con otros estudiantes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('registro.equipos.crear') }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Equipo <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="nombre"
                                       name="nombre"
                                       value="{{ old('nombre') }}"
                                       required
                                       placeholder="Ej: Los Innovadores"
                                       class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-300 @enderror">
                                @error('nombre')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción (Opcional)
                                </label>
                                <textarea id="descripcion"
                                          name="descripcion"
                                          rows="3"
                                          placeholder="Describe brevemente tu equipo..."
                                          class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('descripcion') }}</textarea>
                            </div>

                            <div>
                                <label for="max_integrantes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Máximo de Integrantes <span class="text-red-500">*</span>
                                </label>
                                <select id="max_integrantes"
                                        name="max_integrantes"
                                        required
                                        class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('max_integrantes', 5) == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'integrante' : 'integrantes' }}
                                        </option>
                                    @endfor
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Recuerda: El mínimo es 1 integrante (tú como líder)</p>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button"
                                    @click="opcion = ''"
                                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver
                            </button>
                            <button type="submit"
                                    class="px-6 py-3 border border-transparent text-white bg-green-600 rounded-md hover:bg-green-700">
                                Crear Equipo
                                <i class="fas fa-check ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Estado inicial -->
                <div x-show="opcion === ''" class="text-center py-8" style="display: block;">
                    <i class="fas fa-hand-pointer text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">Selecciona una opción para continuar</p>
                </div>
            </div>

            <!-- Opción de omitir -->
            <div class="mt-6 text-center">
                <a href="{{ route('estudiante.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    Omitir este paso (podrás hacerlo después)
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
