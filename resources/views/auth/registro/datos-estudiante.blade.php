<!DOCTYPE html>
<html lang="es" class="h-full bg-gradient-to-br from-blue-50 to-indigo-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Datos de Estudiante - ConcursITO</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                        <div class="w-16 h-1 bg-blue-600"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                <i class="fas fa-2"></i>
                            </div>
                            <span class="ml-2 text-sm font-medium text-blue-900">Datos</span>
                        </div>
                        <div class="w-16 h-1 bg-gray-300"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                <i class="fas fa-3"></i>
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Equipo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="bg-white py-8 px-4 shadow-2xl sm:rounded-lg sm:px-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Completa tus Datos de Estudiante</h2>

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

                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Por favor corrige los siguientes errores:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('registro.datos-estudiante.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Número de Control -->
                        <div class="sm:col-span-2">
                            <label for="numero_control" class="block text-sm font-medium text-gray-700">
                                Número de Control <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text"
                                       id="numero_control"
                                       name="numero_control"
                                       value="{{ old('numero_control') }}"
                                       required
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('numero_control') border-red-300 @enderror">
                                @error('numero_control')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Carrera -->
                        <div class="sm:col-span-2">
                            <label for="carrera" class="block text-sm font-medium text-gray-700">
                                Carrera <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <select id="carrera"
                                        name="carrera"
                                        required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('carrera') border-red-300 @enderror">
                                    <option value="">Selecciona tu carrera</option>
                                    <option value="Ingeniería en Sistemas Computacionales" {{ old('carrera') == 'Ingeniería en Sistemas Computacionales' ? 'selected' : '' }}>Ingeniería en Sistemas Computacionales</option>
                                    <option value="Ingeniería Industrial" {{ old('carrera') == 'Ingeniería Industrial' ? 'selected' : '' }}>Ingeniería Industrial</option>
                                    <option value="Ingeniería Electrónica" {{ old('carrera') == 'Ingeniería Electrónica' ? 'selected' : '' }}>Ingeniería Electrónica</option>
                                    <option value="Ingeniería Mecánica" {{ old('carrera') == 'Ingeniería Mecánica' ? 'selected' : '' }}>Ingeniería Mecánica</option>
                                    <option value="Ingeniería Mecatrónica" {{ old('carrera') == 'Ingeniería Mecatrónica' ? 'selected' : '' }}>Ingeniería Mecatrónica</option>
                                    <option value="Licenciatura en Administración" {{ old('carrera') == 'Licenciatura en Administración' ? 'selected' : '' }}>Licenciatura en Administración</option>
                                </select>
                                @error('carrera')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Semestre -->
                        <div>
                            <label for="semestre" class="block text-sm font-medium text-gray-700">
                                Semestre
                            </label>
                            <div class="mt-1">
                                <select id="semestre"
                                        name="semestre"
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecciona</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('semestre') == $i ? 'selected' : '' }}>{{ $i }}° Semestre</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700">
                                Teléfono
                            </label>
                            <div class="mt-1">
                                <input type="tel"
                                       id="telefono"
                                       name="telefono"
                                       value="{{ old('telefono') }}"
                                       placeholder="10 dígitos"
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">
                                Fecha de Nacimiento
                            </label>
                            <div class="mt-1">
                                <input type="date"
                                       id="fecha_nacimiento"
                                       name="fecha_nacimiento"
                                       value="{{ old('fecha_nacimiento') }}"
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="sm:col-span-2">
                            <label for="direccion" class="block text-sm font-medium text-gray-700">
                                Dirección
                            </label>
                            <div class="mt-1">
                                <textarea id="direccion"
                                          name="direccion"
                                          rows="3"
                                          class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('direccion') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <p class="text-sm text-gray-600">
                            <span class="text-red-500">*</span> Campos obligatorios
                        </p>
                        <button type="submit"
                                class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Continuar
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
