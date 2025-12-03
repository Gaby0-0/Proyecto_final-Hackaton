@extends('layouts.estudiante')

@section('title', 'Detalles del Equipo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('estudiante.equipos.index') }}" class="text-blue-600 hover:text-blue-700 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Equipos
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $equipo->nombre }}</h1>
                        @if($esLider)
                            <span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">
                                <i class="fas fa-crown"></i> Eres el líder
                            </span>
                        @elseif($esMiembro)
                            <span class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                                <i class="fas fa-check"></i> Eres miembro
                            </span>
                        @endif
                    </div>

                    @if($esLider)
                        <form action="{{ route('estudiante.equipos.destroy', $equipo) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm" onclick="return confirm('¿Estás seguro de eliminar este equipo? Esta acción no se puede deshacer.')">
                                <i class="fas fa-trash mr-2"></i>
                                Eliminar Equipo
                            </button>
                        </form>
                    @endif
                </div>

                @if($equipo->descripcion)
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Descripción</h2>
                        <p class="text-gray-600">{{ $equipo->descripcion }}</p>
                    </div>
                @endif

                <!-- Código del Equipo (Destacado) -->
                @if($esMiembro)
                <div class="mb-6 bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-white">
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-3">
                            <i class="fas fa-key text-3xl mr-3"></i>
                            <h3 class="text-lg font-semibold">Código del Equipo</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 mb-3">
                            <p class="text-5xl font-bold font-mono tracking-widest">{{ $equipo->codigo }}</p>
                        </div>
                        <p class="text-blue-100 text-sm">
                            <i class="fas fa-info-circle mr-1"></i>
                            Comparte este código con otros estudiantes para que se unan al equipo
                        </p>
                        <button onclick="copiarCodigo('{{ $equipo->codigo }}')"
                                class="mt-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-2 rounded-lg text-sm transition-all">
                            <i class="fas fa-copy mr-2"></i>
                            Copiar Código
                        </button>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center text-gray-600 mb-1">
                            <i class="fas fa-users mr-2"></i>
                            <span class="text-sm">Integrantes</span>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $equipo->miembros->count() }} / {{ $equipo->max_integrantes }}
                        </p>
                    </div>

                    @if($equipo->proyecto)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center text-gray-600 mb-1">
                                <i class="fas fa-project-diagram mr-2"></i>
                                <span class="text-sm">Proyecto</span>
                            </div>
                            <p class="text-lg font-bold text-gray-900">{{ $equipo->proyecto->nombre }}</p>
                        </div>
                    @endif
                </div>

                <!-- Miembros del Equipo -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Miembros del Equipo</h2>
                    <div class="space-y-3">
                        @foreach($equipo->miembros as $miembro)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                        {{ strtoupper(substr($miembro->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $miembro->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $miembro->email }}</p>
                                    </div>
                                </div>
                                @if($miembro->pivot->rol_equipo == 'lider')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">
                                        <i class="fas fa-crown"></i> Líder
                                    </span>
                                @else
                                    <span class="bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded-full">
                                        Miembro
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Eventos del Equipo -->
            @if($equipo->eventos->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Eventos Inscritos</h2>
                    <div class="space-y-3">
                        @foreach($equipo->eventos as $evento)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="font-semibold text-gray-900">{{ $evento->nombre }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Estado:
                                    <span class="px-2 py-1 rounded-full text-xs
                                        {{ $evento->pivot->estado == 'inscrito' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $evento->pivot->estado == 'aceptado' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $evento->pivot->estado == 'rechazado' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($evento->pivot->estado) }}
                                    </span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Acciones -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Acciones</h2>

                @if(!$esMiembro)
                    @if(!$equipo->estaLleno())
                        <form action="{{ route('estudiante.equipos.unirse', $equipo) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-semibold">
                                <i class="fas fa-user-plus mr-2"></i>
                                Unirse al Equipo
                            </button>
                        </form>
                    @else
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Este equipo está lleno
                        </div>
                    @endif
                @else
                    @if(!$esLider)
                        <form action="{{ route('estudiante.equipos.salir', $equipo) }}" method="POST" class="mb-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-semibold" onclick="return confirm('¿Estás seguro de que quieres salir de este equipo?')">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Salir del Equipo
                            </button>
                        </form>
                    @else
                        <div class="bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded-lg mb-3">
                            <i class="fas fa-info-circle mr-2"></i>
                            Como líder, puedes gestionar el equipo desde el panel de administración
                        </div>
                    @endif
                @endif

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Información</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><i class="fas fa-calendar mr-2"></i> Creado: {{ $equipo->created_at->format('d/m/Y') }}</p>
                        <p><i class="fas fa-clock mr-2"></i> Última actualización: {{ $equipo->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copiarCodigo(codigo) {
    navigator.clipboard.writeText(codigo).then(function() {
        // Mostrar mensaje de éxito
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i>¡Copiado!';
        button.classList.add('bg-green-500');

        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-500');
        }, 2000);
    }, function(err) {
        alert('Error al copiar el código');
    });
}
</script>
@endpush
