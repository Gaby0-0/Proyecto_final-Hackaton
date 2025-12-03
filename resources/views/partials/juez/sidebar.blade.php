<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gradient-to-b from-purple-600 to-purple-800 px-6 pb-4">
    <div class="flex h-16 shrink-0 items-center">
        <h1 class="text-white text-xl font-bold">ConcursITO - Juez</h1>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('juez.dashboard') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('juez.dashboard') ? 'bg-purple-700 text-white' : 'text-purple-100 hover:text-white hover:bg-purple-700' }}">
                            <i class="fas fa-home text-lg w-6"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Equipos a Evaluar -->
                    <li>
                        <a href="{{ route('juez.evaluaciones.index') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('juez.evaluaciones.*') ? 'bg-purple-700 text-white' : 'text-purple-100 hover:text-white hover:bg-purple-700' }}">
                            <i class="fas fa-clipboard-list text-lg w-6"></i>
                            Evaluaciones
                        </a>
                    </li>

                    <!-- Mis Evaluaciones -->
                    <li>
                        <a href="{{ route('juez.mis-evaluaciones') }}"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('juez.mis-evaluaciones') ? 'bg-purple-700 text-white' : 'text-purple-100 hover:text-white hover:bg-purple-700' }}">
                            <i class="fas fa-star text-lg w-6"></i>
                            Mis Evaluaciones
                        </a>
                    </li>

                    <!-- Proyectos -->
                    <li>
                        <a href="#"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-purple-100 hover:text-white hover:bg-purple-700">
                            <i class="fas fa-project-diagram text-lg w-6"></i>
                            Proyectos
                        </a>
                    </li>

                    <!-- Eventos -->
                    <li>
                        <a href="#"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-purple-100 hover:text-white hover:bg-purple-700">
                            <i class="fas fa-calendar text-lg w-6"></i>
                            Eventos
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Perfil y Configuración -->
            <li class="mt-auto">
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="#"
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-purple-100 hover:text-white hover:bg-purple-700">
                            <i class="fas fa-user text-lg w-6"></i>
                            Mi Perfil
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-purple-100 hover:text-white hover:bg-purple-700">
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
