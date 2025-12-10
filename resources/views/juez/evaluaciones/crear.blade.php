@extends('layouts.juez')

@section('title', 'Evaluar Equipo')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('juez.evaluaciones.evento', $evento) }}" class="text-purple-600 hover:text-purple-700 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Equipos del Evento
        </a>
    </div>

    <!-- Header del Equipo -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-6 text-white">
            <h1 class="text-3xl font-bold mb-2">Evaluar: {{ $equipo->nombre }}</h1>
            @if($inscripcion && $inscripcion->pivot->proyecto_titulo)
                <div class="flex items-center text-purple-100">
                    <i class="fas fa-project-diagram mr-2"></i>
                    <span class="text-lg">{{ $inscripcion->pivot->proyecto_titulo }}</span>
                </div>
            @endif
        </div>

        <!-- Resumen del Equipo -->
        <div class="p-6 bg-gray-50">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-gray-600 text-sm mb-1">Integrantes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $equipo->miembros->count() }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm mb-1">Líder</p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $equipo->miembros->where('pivot.rol_equipo', 'lider')->first()->name ?? 'N/A' }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm mb-1">Código</p>
                    <p class="text-lg font-mono font-bold text-purple-600">{{ $equipo->codigo }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm mb-1">Evaluaciones</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $equipo->evaluaciones->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Proyecto -->
    @if($inscripcion)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 text-white">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-project-diagram mr-2"></i>
                Información del Proyecto
            </h2>
        </div>

        <div class="p-6">
            <!-- Título y Descripción -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $inscripcion->pivot->proyecto_titulo }}</h3>
                @if($inscripcion->pivot->proyecto_descripcion)
                    <p class="text-gray-700 leading-relaxed">{{ $inscripcion->pivot->proyecto_descripcion }}</p>
                @else
                    <p class="text-gray-500 italic">Sin descripción</p>
                @endif
            </div>

            <!-- Proyecto Final -->
            @if($inscripcion->pivot->proyecto_final_url)
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-900 mb-1">
                            <i class="fas fa-check-circle mr-2"></i>
                            Proyecto Final Entregado
                        </p>
                        @if($inscripcion->pivot->fecha_entrega_final)
                            <p class="text-xs text-green-700">
                                Entregado: {{ \Carbon\Carbon::parse($inscripcion->pivot->fecha_entrega_final)->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                    <a href="{{ Storage::url($inscripcion->pivot->proyecto_final_url) }}"
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Ver Proyecto
                    </a>
                </div>
            </div>
            @else
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    El equipo aún no ha entregado el proyecto final
                </p>
            </div>
            @endif

            <!-- Avances -->
            @if(!empty($avances))
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-tasks mr-2 text-purple-600"></i>
                    Avances del Proyecto ({{ count($avances) }})
                </h4>
                <div class="space-y-3">
                    @foreach($avances as $index => $avance)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <h5 class="font-semibold text-gray-900">{{ $avance['titulo'] ?? 'Avance #' . ($index + 1) }}</h5>
                            <span class="text-xs text-gray-500">
                                {{ isset($avance['fecha']) ? \Carbon\Carbon::parse($avance['fecha'])->format('d/m/Y') : 'Sin fecha' }}
                            </span>
                        </div>
                        @if(isset($avance['descripcion']))
                            <p class="text-sm text-gray-700 mb-2">{{ $avance['descripcion'] }}</p>
                        @endif
                        @if(isset($avance['url']) && $avance['url'])
                            <a href="{{ $avance['url'] }}"
                               target="_blank"
                               class="inline-flex items-center text-sm text-purple-600 hover:text-purple-700 font-medium">
                                <i class="fas fa-link mr-1"></i>
                                Ver avance
                            </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <p class="text-sm text-gray-600 text-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    No hay avances registrados para este proyecto
                </p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Formulario de Evaluación -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Formulario de Evaluación</h2>
            <p class="text-gray-600">Evalúa cada criterio del 0 al 20. La puntuación total será sobre 100.</p>
        </div>

        <form action="{{ route('juez.evaluaciones.store', ['evento' => $evento, 'equipo' => $equipo]) }}" method="POST" x-data="evaluacionForm()">
            @csrf

            <!-- Criterios de Evaluación -->
            <div class="space-y-6 mb-6">
                <!-- Innovación -->
                <div class="border-b pb-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <label class="block text-lg font-semibold text-gray-900 mb-1">
                                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                Innovación
                            </label>
                            <p class="text-sm text-gray-600">Originalidad y creatividad de la solución propuesta</p>
                        </div>
                        <div class="text-right ml-4">
                            <span x-text="criterio_innovacion" class="text-3xl font-bold text-purple-600">0</span>
                            <span class="text-gray-500">/20</span>
                        </div>
                    </div>
                    <input type="range"
                           name="criterio_innovacion"
                           x-model="criterio_innovacion"
                           min="0"
                           max="20"
                           step="0.5"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                    @error('criterio_innovacion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Funcionalidad -->
                <div class="border-b pb-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <label class="block text-lg font-semibold text-gray-900 mb-1">
                                <i class="fas fa-cogs text-blue-500 mr-2"></i>
                                Funcionalidad
                            </label>
                            <p class="text-sm text-gray-600">Cumplimiento de objetivos y requisitos del proyecto</p>
                        </div>
                        <div class="text-right ml-4">
                            <span x-text="criterio_funcionalidad" class="text-3xl font-bold text-purple-600">0</span>
                            <span class="text-gray-500">/20</span>
                        </div>
                    </div>
                    <input type="range"
                           name="criterio_funcionalidad"
                           x-model="criterio_funcionalidad"
                           min="0"
                           max="20"
                           step="0.5"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                    @error('criterio_funcionalidad')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Presentación -->
                <div class="border-b pb-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <label class="block text-lg font-semibold text-gray-900 mb-1">
                                <i class="fas fa-presentation text-green-500 mr-2"></i>
                                Presentación
                            </label>
                            <p class="text-sm text-gray-600">Calidad de la demostración y comunicación del proyecto</p>
                        </div>
                        <div class="text-right ml-4">
                            <span x-text="criterio_presentacion" class="text-3xl font-bold text-purple-600">0</span>
                            <span class="text-gray-500">/20</span>
                        </div>
                    </div>
                    <input type="range"
                           name="criterio_presentacion"
                           x-model="criterio_presentacion"
                           min="0"
                           max="20"
                           step="0.5"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                    @error('criterio_presentacion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Impacto -->
                <div class="border-b pb-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <label class="block text-lg font-semibold text-gray-900 mb-1">
                                <i class="fas fa-bullseye text-red-500 mr-2"></i>
                                Impacto
                            </label>
                            <p class="text-sm text-gray-600">Relevancia y potencial impacto de la solución</p>
                        </div>
                        <div class="text-right ml-4">
                            <span x-text="criterio_impacto" class="text-3xl font-bold text-purple-600">0</span>
                            <span class="text-gray-500">/20</span>
                        </div>
                    </div>
                    <input type="range"
                           name="criterio_impacto"
                           x-model="criterio_impacto"
                           min="0"
                           max="20"
                           step="0.5"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                    @error('criterio_impacto')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Técnico -->
                <div class="border-b pb-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <label class="block text-lg font-semibold text-gray-900 mb-1">
                                <i class="fas fa-code text-indigo-500 mr-2"></i>
                                Aspectos Técnicos
                            </label>
                            <p class="text-sm text-gray-600">Calidad del código, arquitectura y buenas prácticas</p>
                        </div>
                        <div class="text-right ml-4">
                            <span x-text="criterio_tecnico" class="text-3xl font-bold text-purple-600">0</span>
                            <span class="text-gray-500">/20</span>
                        </div>
                    </div>
                    <input type="range"
                           name="criterio_tecnico"
                           x-model="criterio_tecnico"
                           min="0"
                           max="20"
                           step="0.5"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                    @error('criterio_tecnico')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Puntuación Total -->
            <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-semibold text-gray-900 mb-1">Puntuación Total</p>
                        <p class="text-sm text-gray-600">Suma de todos los criterios</p>
                    </div>
                    <div class="text-right">
                        <span x-text="puntuacionTotal" class="text-5xl font-bold text-purple-600">0</span>
                        <span class="text-2xl text-gray-500">/100</span>
                    </div>
                </div>
            </div>

            <!-- Campo oculto para puntuación total -->
            <input type="hidden" name="puntuacion" :value="puntuacionTotal">

            <!-- Comentarios -->
            <div class="mb-6">
                <label class="block text-lg font-semibold text-gray-900 mb-2">
                    Comentarios y Retroalimentación
                </label>
                <p class="text-sm text-gray-600 mb-3">Proporciona comentarios constructivos para el equipo (opcional)</p>
                <textarea name="comentarios"
                          rows="5"
                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                          placeholder="Escribe tus comentarios aquí...">{{ old('comentarios') }}</textarea>
                @error('comentarios')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Evaluación
                </button>
                <a href="{{ route('juez.evaluaciones.evento', $evento) }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function evaluacionForm() {
    return {
        criterio_innovacion: 0,
        criterio_funcionalidad: 0,
        criterio_presentacion: 0,
        criterio_impacto: 0,
        criterio_tecnico: 0,

        get puntuacionTotal() {
            return parseFloat(this.criterio_innovacion) +
                   parseFloat(this.criterio_funcionalidad) +
                   parseFloat(this.criterio_presentacion) +
                   parseFloat(this.criterio_impacto) +
                   parseFloat(this.criterio_tecnico);
        }
    }
}
</script>
@endpush
