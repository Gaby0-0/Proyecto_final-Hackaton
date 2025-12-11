<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 glass-effect px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <!-- Botón menú móvil -->
    <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-dark-900 hover:text-primary-500 transition-colors lg:hidden">
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
                   class="block h-full w-full border-0 bg-transparent py-0 pl-10 pr-0 text-dark-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm"
                   placeholder="Buscar..."
                   type="search"
                   name="search">
        </form>

        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <!-- Notificaciones -->
            @php
                $notificaciones = \App\Models\Notificacion::with(['equipo', 'evento'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
                $notificacionesNoLeidas = $notificaciones->where('leida', false)->count();
            @endphp

            <div x-data="{ open: false }" class="relative">
                <button type="button"
                        @click="open = !open"
                        class="-m-2.5 p-2.5 text-gray-600 hover:text-primary-500 transition-colors relative">
                    <span class="sr-only">Ver notificaciones</span>
                    <i class="fas fa-bell text-xl"></i>
                    @if($notificacionesNoLeidas > 0)
                        <span class="absolute top-1 right-1 flex items-center justify-center h-5 w-5 rounded-full gradient-primary ring-2 ring-white shadow-lg">
                            <span class="text-xs font-bold text-white">{{ $notificacionesNoLeidas }}</span>
                        </span>
                    @endif
                </button>

                <!-- Dropdown notificaciones -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     style="display: none;"
                     class="absolute right-0 z-10 mt-2.5 w-80 origin-top-right rounded-xl bg-white py-2 shadow-2xl ring-1 ring-black/5 focus:outline-none">
                    <div class="px-4 py-3 border-b border-gray-200 gradient-primary rounded-t-xl">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i class="fas fa-bell"></i>
                            Notificaciones
                        </h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        @forelse($notificaciones as $notificacion)
                            <a href="{{ route('admin.notificaciones.marcar-leida', $notificacion) }}"
                               class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition-colors {{ $notificacion->leida ? 'bg-white' : 'bg-secondary-50' }}">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-circle text-xs mt-1 {{ $notificacion->leida ? 'text-gray-300' : 'text-secondary-500' }}"></i>
                                    <div class="flex-1">
                                        <p class="text-sm {{ $notificacion->leida ? 'text-gray-700' : 'text-dark-900 font-medium' }}">
                                            {{ $notificacion->mensaje }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notificacion->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <i class="fas fa-bell-slash text-4xl text-gray-300 mb-2"></i>
                                <p class="text-sm text-gray-500">No hay notificaciones</p>
                            </div>
                        @endforelse
                    </div>
                    @if($notificacionesNoLeidas > 0)
                    <div class="px-4 py-2 border-t border-gray-200">
                        <form action="{{ route('admin.notificaciones.marcar-todas-leidas') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-secondary-600 hover:text-secondary-700 font-medium w-full text-left transition-colors">
                                <i class="fas fa-check-double mr-1"></i>
                                Marcar todas como leídas
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Separador -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

            <!-- Perfil usuario -->
            <div x-data="{ open: false }" class="relative">
                <button type="button"
                        @click="open = !open"
                        class="-m-1.5 flex items-center p-1.5 hover:bg-primary-50 rounded-xl transition-all">
                    <span class="sr-only">Abrir menú de usuario</span>
                    <div class="h-9 w-9 rounded-xl gradient-primary flex items-center justify-center shadow-md">
                        <span class="text-sm font-bold text-white">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </span>
                    </div>
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-3 text-sm font-semibold leading-6 text-dark-900" aria-hidden="true">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </span>
                        <i class="fas fa-chevron-down ml-2 text-xs text-gray-500"></i>
                    </span>
                </button>

                <!-- Dropdown perfil -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     style="display: none;"
                     class="absolute right-0 z-10 mt-2.5 w-56 origin-top-right rounded-xl bg-white py-2 shadow-2xl ring-1 ring-black/5 focus:outline-none">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <p class="text-sm font-bold text-dark-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'admin@admin.com' }}</p>
                    </div>
                    <a href="{{ route('admin.perfil.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-secondary-50 hover:text-secondary-700 transition-colors">
                        <i class="fas fa-user w-5 text-secondary-500"></i>
                        Tu perfil
                    </a>
                    <div class="border-t border-gray-200 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-primary-700 hover:bg-primary-50 transition-colors">
                            <i class="fas fa-sign-out-alt w-5 text-primary-500"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
