<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - ConcursITO</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js para interactividad -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @stack('styles')
</head>
<body class="h-full bg-gradient-to-br from-gray-50 via-white to-primary-50/20" x-data="{ sidebarOpen: false }">
    <div class="min-h-full">
        <!-- Sidebar móvil -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="relative z-50 lg:hidden"
             role="dialog"
             aria-modal="true"
             style="display: none;">
            <div class="fixed inset-0 bg-dark-900/80 backdrop-blur-sm"></div>

            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     @click.away="sidebarOpen = false"
                     class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5 hover:scale-110 transition-transform">
                            <span class="sr-only">Cerrar sidebar</span>
                            <i class="fas fa-times text-white text-xl"></i>
                        </button>
                    </div>
                    @include('partials.admin.sidebar')
                </div>
            </div>
        </div>

        <!-- Sidebar Desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col shadow-2xl">
            @include('partials.admin.sidebar')
        </div>

        <div class="lg:pl-64">
            <!-- Navbar superior -->
            @include('partials.admin.navbar')

            <!-- Contenido principal -->
            <main class="py-6 px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb y acciones -->
                @if(isset($breadcrumbs) || isset($pageTitle) || isset($pageActions))
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            @if(isset($pageTitle))
                            <h1 class="text-2xl font-bold text-gray-900">{{ $pageTitle }}</h1>
                            @endif
                            @if(isset($breadcrumbs))
                            <nav class="flex mt-2" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2">
                                    @foreach($breadcrumbs as $breadcrumb)
                                    <li class="flex items-center">
                                        @if(!$loop->first)
                                        <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                                        @endif
                                        @if(isset($breadcrumb['url']) && !$loop->last)
                                        <a href="{{ $breadcrumb['url'] }}" class="text-sm text-gray-500 hover:text-gray-700">
                                            {{ $breadcrumb['name'] }}
                                        </a>
                                        @else
                                        <span class="text-sm font-medium text-gray-900">{{ $breadcrumb['name'] }}</span>
                                        @endif
                                    </li>
                                    @endforeach
                                </ol>
                            </nav>
                            @endif
                        </div>
                        @if(isset($pageActions))
                        <div class="flex gap-3">
                            {!! $pageActions !!}
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Alertas -->
                @if(session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 rounded-xl bg-gradient-to-r from-secondary-50 to-secondary-100/50 p-4 border border-secondary-200 shadow-lg shadow-secondary-100/50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg gradient-secondary flex items-center justify-center shadow-md">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-secondary-900">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-2 text-secondary-500 hover:bg-secondary-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 rounded-xl bg-gradient-to-r from-primary-50 to-primary-100/50 p-4 border border-primary-200 shadow-lg shadow-primary-100/50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg gradient-primary flex items-center justify-center shadow-md">
                            <i class="fas fa-exclamation-circle text-white"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-primary-900">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-2 text-primary-500 hover:bg-primary-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endif

                @if(session('warning'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 6000)"
                     class="mb-6 rounded-xl bg-gradient-to-r from-yellow-50 to-yellow-100/50 p-4 border border-yellow-200 shadow-lg shadow-yellow-100/50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-md">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-yellow-900">{{ session('warning') }}</p>
                        </div>
                        <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-2 text-yellow-500 hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Contenido de la página -->
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
