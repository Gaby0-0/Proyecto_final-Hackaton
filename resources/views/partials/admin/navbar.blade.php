<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <!-- Botón menú móvil -->
    <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
        <span class="sr-only">Abrir sidebar</span>
        <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- Separador -->
    <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <!-- Búsqueda -->
        <form class="relative flex flex-1" action="#" method="GET">
            <label for="search-field" class="sr-only">Buscar</label>
            <i class="fas fa-search pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400 pl-3 flex items-center"></i>
            <input id="search-field"
                   class="block h-full w-full border-0 py-0 pl-10 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm"
                   placeholder="Buscar..."
                   type="search"
                   name="search">
        </form>

        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <!-- Notificaciones -->
            <div x-data="{ open: false }" class="relative">
                <button type="button"
                        @click="open = !open"
                        class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 relative">
                    <span class="sr-only">Ver notificaciones</span>
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>

                <!-- Dropdown notificaciones -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     style="display: none;"
                     class="absolute right-0 z-10 mt-2.5 w-80 origin-top-right rounded-lg bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                            <p class="text-sm text-gray-900">Nuevo equipo registrado</p>
                            <p class="text-xs text-gray-500 mt-1">Hace 2 horas</p>
                        </a>
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                            <p class="text-sm text-gray-900">Evaluación completada</p>
                            <p class="text-xs text-gray-500 mt-1">Hace 4 horas</p>
                        </a>
                    </div>
                    <div class="px-4 py-2 border-t border-gray-200">
                        <a href="#" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Ver todas</a>
                    </div>
                </div>
            </div>

            <!-- Separador -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

            <!-- Perfil usuario -->
            <div x-data="{ open: false }" class="relative">
                <button type="button"
                        @click="open = !open"
                        class="-m-1.5 flex items-center p-1.5 hover:bg-gray-50 rounded-lg">
                    <span class="sr-only">Abrir menú de usuario</span>
                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <span class="text-xs font-medium text-white">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </span>
                    </div>
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </span>
                        <i class="fas fa-chevron-down ml-2 text-xs text-gray-400"></i>
                    </span>
                </button>

                <!-- Dropdown perfil -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     style="display: none;"
                     class="absolute right-0 z-10 mt-2.5 w-56 origin-top-right rounded-lg bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'admin@admin.com' }}</p>
                    </div>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-user w-5 text-gray-400"></i>
                        Tu perfil
                    </a>
                    <a href="{{ route('admin.configuracion.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-cog w-5 text-gray-400"></i>
                        Configuración
                    </a>
                    <div class="border-t border-gray-200 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt w-5 text-red-400"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
