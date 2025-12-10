<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white border-r border-gray-200 px-6 pb-4">
    <!-- Logo -->
    <div class="flex h-16 shrink-0 items-center border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-trophy text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">ConcursITO</h1>
                <p class="text-xs text-gray-500">Panel Admin</p>
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
                           class="group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            <i class="fas fa-home w-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Usuarios -->
                    <li>
                        <a href="{{ route('admin.usuarios.index') }}"
                           class="group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 {{ request()->routeIs('admin.usuarios.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            <i class="fas fa-users w-5 {{ request()->routeIs('admin.usuarios.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Usuarios
                        </a>
                    </li>

                    <!-- Equipos -->
                    <li>
                        <a href="{{ route('admin.equipos.index') }}"
                           class="group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 {{ request()->routeIs('admin.equipos.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            <i class="fas fa-user-group w-5 {{ request()->routeIs('admin.equipos.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Equipos
                        </a>
                    </li>

                    <!-- Eventos -->
                    <li>
                        <a href="{{ route('admin.eventos.index') }}"
                           class="group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 {{ request()->routeIs('admin.eventos.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            <i class="fas fa-calendar-days w-5 {{ request()->routeIs('admin.eventos.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Eventos
                        </a>
                    </li>

                    <!-- Proyectos -->
                    <li>
                        <a href="{{ route('admin.proyectos.index') }}"
                           class="group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 {{ request()->routeIs('admin.proyectos.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            <i class="fas fa-folder-open w-5 {{ request()->routeIs('admin.proyectos.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Proyectos
                        </a>
                    </li>

                    <!-- Evaluaciones -->
                    <li>
                        <a href="{{ route('admin.evaluaciones.index') }}"
                           class="group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 {{ request()->routeIs('admin.evaluaciones.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            <i class="fas fa-star w-5 {{ request()->routeIs('admin.evaluaciones.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Evaluaciones
                        </a>
                    </li>

                    <!-- Jueces -->
                    <li>
                        <a href="{{ route('admin.jueces.index') }}"
                           class="group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 {{ request()->routeIs('admin.jueces.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            <i class="fas fa-gavel w-5 {{ request()->routeIs('admin.jueces.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Jueces
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Sección Reportes -->
            <li>
                <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider">Reportes</div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <!-- Constancias -->
                    <li>
                        <a href="{{ route('admin.constancias.index') }}"
                           class="group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 {{ request()->routeIs('admin.constancias.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            <i class="fas fa-certificate w-5 {{ request()->routeIs('admin.constancias.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Constancias
                        </a>
                    </li>

                    <!-- Informes -->
                    <li>
                        <a href="{{ route('admin.informes.index') }}"
                           class="group flex gap-x-3 rounded-lg p-3 text-sm font-semibold leading-6 {{ request()->routeIs('admin.informes.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            <i class="fas fa-chart-bar w-5 {{ request()->routeIs('admin.informes.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}"></i>
                            Informes
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
</div>
