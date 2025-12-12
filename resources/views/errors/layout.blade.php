<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full">
        <div class="text-center">
            <!-- Error Code -->
            <div class="mb-8">
                <h1 class="text-9xl font-extrabold text-gray-800">
                    @yield('code')
                </h1>
            </div>

            <!-- Icon -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-32 h-32 bg-white rounded-full shadow-lg">
                    <i class="@yield('icon') text-6xl text-gray-700"></i>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-lg shadow-lg p-8 md:p-12 mb-8">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    @yield('title')
                </h2>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    @yield('message')
                </p>

                <!-- Additional Info -->
                @hasSection('additional-info')
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 text-left rounded">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div class="text-sm text-gray-700">
                            @yield('additional-info')
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @yield('actions')
                </div>
            </div>

            <!-- Footer Info -->
            <div class="text-center text-gray-500 text-sm">
                <p>Si crees que esto es un error, por favor contacta al administrador del sistema.</p>
                <p class="mt-2">
                    <i class="fas fa-clock mr-2"></i>
                    {{ now()->format('d/m/Y H:i:s') }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>
