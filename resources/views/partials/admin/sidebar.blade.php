<div class="flex grow flex-col gap-y-5 overflow-y-auto gradient-dark px-6 pb-4">
    <!-- Logo -->
    <div class="flex h-16 shrink-0 items-center border-b border-dark-700">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 gradient-primary rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-trophy text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white">ConcursITO</h1>
                <p class="text-xs text-primary-300">Panel Admin</p>
            </div>
        </div>
    </div>

    <!-- Navegación -->
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/50' : 'text-gray-300 hover:text-white hover:bg-dark-800' }}">
                            <i class="fas fa-home w-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-primary-400' }}"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Usuarios -->
                    <li>
                        <a href="{{ route('admin.usuarios.index') }}"
                           class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('admin.usuarios.*') ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/50' : 'text-gray-300 hover:text-white hover:bg-dark-800' }}">
                            <i class="fas fa-users w-5 {{ request()->routeIs('admin.usuarios.*') ? 'text-white' : 'text-gray-400 group-hover:text-primary-400' }}"></i>
                            Usuarios
                        </a>
                    </li>

                    <!-- Equipos -->
                    <li>
                        <a href="{{ route('admin.equipos.index') }}"
                           class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('admin.equipos.*') ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/50' : 'text-gray-300 hover:text-white hover:bg-dark-800' }}">
                            <i class="fas fa-user-group w-5 {{ request()->routeIs('admin.equipos.*') ? 'text-white' : 'text-gray-400 group-hover:text-primary-400' }}"></i>
                            Equipos
                        </a>
                    </li>

                    <!-- Eventos -->
                    <li>
                        <a href="{{ route('admin.eventos.index') }}"
                           class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('admin.eventos.*') ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/50' : 'text-gray-300 hover:text-white hover:bg-dark-800' }}">
                            <i class="fas fa-calendar-days w-5 {{ request()->routeIs('admin.eventos.*') ? 'text-white' : 'text-gray-400 group-hover:text-primary-400' }}"></i>
                            Eventos
                        </a>
                    </li>

                    <!-- Proyectos -->
                    <li>
                        <a href="{{ route('admin.proyectos.index') }}"
                           class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('admin.proyectos.*') ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/50' : 'text-gray-300 hover:text-white hover:bg-dark-800' }}">
                            <i class="fas fa-folder-open w-5 {{ request()->routeIs('admin.proyectos.*') ? 'text-white' : 'text-gray-400 group-hover:text-primary-400' }}"></i>
                            Proyectos
                        </a>
                    </li>

                    <!-- Evaluaciones -->
                    <li>
                        <a href="{{ route('admin.evaluaciones.index') }}"
                           class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('admin.evaluaciones.*') ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/50' : 'text-gray-300 hover:text-white hover:bg-dark-800' }}">
                            <i class="fas fa-star w-5 {{ request()->routeIs('admin.evaluaciones.*') ? 'text-white' : 'text-gray-400 group-hover:text-primary-400' }}"></i>
                            Evaluaciones
                        </a>
                    </li>

                    <!-- Jueces -->
                    <li>
                        <a href="{{ route('admin.jueces.index') }}"
                           class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('admin.jueces.*') ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/50' : 'text-gray-300 hover:text-white hover:bg-dark-800' }}">
                            <i class="fas fa-gavel w-5 {{ request()->routeIs('admin.jueces.*') ? 'text-white' : 'text-gray-400 group-hover:text-primary-400' }}"></i>
                            Jueces
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Sección Reportes -->
            <li>
                <div class="text-xs font-semibold leading-6 text-primary-400 uppercase tracking-wider flex items-center gap-2">
                    <div class="h-px flex-1 bg-dark-700"></div>
                    <span>Reportes</span>
                    <div class="h-px flex-1 bg-dark-700"></div>
                </div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <!-- Constancias -->
                    <li>
                        <a href="{{ route('admin.constancias.index') }}"
                           class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('admin.constancias.*') ? 'bg-secondary-500 text-white shadow-lg shadow-secondary-500/50' : 'text-gray-300 hover:text-white hover:bg-dark-800' }}">
                            <i class="fas fa-certificate w-5 {{ request()->routeIs('admin.constancias.*') ? 'text-white' : 'text-gray-400 group-hover:text-secondary-400' }}"></i>
                            Constancias
                        </a>
                    </li>

                    <!-- Informes -->
                    <li>
                        <a href="{{ route('admin.informes.index') }}"
                           class="group flex gap-x-3 rounded-xl p-3 text-sm font-semibold leading-6 transition-all {{ request()->routeIs('admin.informes.*') ? 'bg-secondary-500 text-white shadow-lg shadow-secondary-500/50' : 'text-gray-300 hover:text-white hover:bg-dark-800' }}">
                            <i class="fas fa-chart-bar w-5 {{ request()->routeIs('admin.informes.*') ? 'text-white' : 'text-gray-400 group-hover:text-secondary-400' }}"></i>
                            Informes
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
</div>
