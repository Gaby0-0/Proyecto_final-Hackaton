<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gradient-to-b from-blue-600 to-blue-800 px-6 pb-4">
    <div class="flex h-16 shrink-0 items-center">
        <h1 class="text-white text-xl font-bold">ConcursITO</h1>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('estudiante.dashboard') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('estudiante.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:text-white hover:bg-blue-700' }}">
                            <i class="fas fa-home text-lg w-6"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Mis Equipos -->
                    <li>
                        <a href="{{ route('estudiante.equipos.index') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('estudiante.equipos.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:text-white hover:bg-blue-700' }}">
                            <i class="fas fa-users text-lg w-6"></i>
                            Mis Equipos
                        </a>
                    </li>

                    <!-- Proyectos -->
                    <li>
                        <a href="{{ route('estudiante.proyectos.index') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('estudiante.proyectos.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:text-white hover:bg-blue-700' }}">
                            <i class="fas fa-project-diagram text-lg w-6"></i>
                            Proyectos
                        </a>
                    </li>

                    <!-- Eventos -->
                    <li>
                        <a href="{{ route('estudiante.eventos.index') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('estudiante.eventos.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:text-white hover:bg-blue-700' }}">
                            <i class="fas fa-calendar text-lg w-6"></i>
                            Eventos
                        </a>
                    </li>

                    <!-- Evaluaciones -->
                    <li>
                        <a href="{{ route('estudiante.evaluaciones.index') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('estudiante.evaluaciones.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:text-white hover:bg-blue-700' }}">
                            <i class="fas fa-star text-lg w-6"></i>
                            Evaluaciones
                        </a>
                    </li>

                    <!-- Constancias -->
                    <li>
                        <a href="{{ route('estudiante.constancias.index') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('estudiante.constancias.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:text-white hover:bg-blue-700' }}">
                            <i class="fas fa-certificate text-lg w-6"></i>
                            Constancias
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Perfil y Configuración -->
            <li class="mt-auto">
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('estudiante.perfil.index') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('estudiante.perfil.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:text-white hover:bg-blue-700' }}">
                            <i class="fas fa-user text-lg w-6"></i>
                            Mi Perfil
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-blue-100 hover:text-white hover:bg-blue-700">
                                <i class="fas fa-sign-out-alt text-lg w-6"></i>
                                Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
