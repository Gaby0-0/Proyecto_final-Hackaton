<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Estudiante - ConcursITO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <h1 class="text-xl font-bold text-gray-900">ConcursITO - Mi Espacio</h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-700 mr-4">{{ $user->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido -->
        <main class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                @endif

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">¡Bienvenido/a, {{ $user->name }}!</h2>
                    <p class="mt-2 text-gray-600">Panel de Estudiante - Gestiona tus equipos y proyectos</p>
                </div>

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users text-3xl text-blue-600"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Mis Equipos</dt>
                                        <dd class="text-3xl font-bold text-gray-900">0</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-project-diagram text-3xl text-green-600"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Proyectos</dt>
                                        <dd class="text-3xl font-bold text-gray-900">0</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar-alt text-3xl text-purple-600"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Eventos Disponibles</dt>
                                        <dd class="text-3xl font-bold text-gray-900">0</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-8">
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                                <i class="fas fa-users text-2xl text-blue-600"></i>
                            </div>
                            <h3 class="ml-4 text-lg font-semibold text-gray-900">Equipos</h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Forma equipos con tus compañeros para participar en hackatones y competencias.
                        </p>
                        <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Crear Equipo
                        </button>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                                <i class="fas fa-project-diagram text-2xl text-green-600"></i>
                            </div>
                            <h3 class="ml-4 text-lg font-semibold text-gray-900">Proyectos</h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Gestiona los proyectos de tus equipos y sube tu trabajo para ser evaluado.
                        </p>
                        <button class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-folder-open mr-2"></i>Ver Proyectos
                        </button>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                                <i class="fas fa-calendar-alt text-2xl text-purple-600"></i>
                            </div>
                            <h3 class="ml-4 text-lg font-semibold text-gray-900">Eventos</h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Explora hackatones y competencias disponibles para participar con tu equipo.
                        </p>
                        <button class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>Explorar Eventos
                        </button>
                    </div>
                </div>

                <!-- Información -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            ¡Comienza tu viaje en ConcursITO!
                        </h3>
                        <div class="mt-2 text-sm text-gray-500">
                            <p>Tu cuenta ha sido creada exitosamente. Ahora puedes:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Formar o unirte a equipos con otros estudiantes</li>
                                <li>Registrar tu equipo en eventos y competencias</li>
                                <li>Subir y gestionar proyectos de tu equipo</li>
                                <li>Ver las evaluaciones y resultados de tus proyectos</li>
                                <li>Obtener constancias de participación</li>
                            </ul>
                        </div>
                        <div class="mt-5">
                            <button class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-rocket mr-2"></i>
                                Crear mi primer equipo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
