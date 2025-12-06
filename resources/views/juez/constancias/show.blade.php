@extends('layouts.juez')

@section('title', 'Ver Reconocimiento')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <!-- Botones de acción -->
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ route('juez.constancias.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver
        </a>
        <button onclick="window.print()"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
            <i class="fas fa-print mr-2"></i>
            Imprimir
        </button>
    </div>

    <!-- Reconocimiento -->
    <div id="constancia" class="bg-white shadow-2xl rounded-lg overflow-hidden print:shadow-none">
        <!-- Borde decorativo superior -->
        <div class="h-4 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600"></div>

        <!-- Contenido del reconocimiento -->
        <div class="p-12 bg-white" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB4PSIwIiB5PSIwIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiPjxjaXJjbGUgY3g9IjIiIGN5PSIyIiByPSIxIiBmaWxsPSIjZjNlOGZmIiBvcGFjaXR5PSIwLjQiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjcGF0dGVybikiLz48L3N2Zz4='); background-size: 40px 40px;">

            <!-- Encabezado -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2" style="font-family: 'Georgia', serif;">
                    RECONOCIMIENTO
                </h1>
                <div class="flex items-center justify-center gap-2 text-gray-600 mb-4">
                    <div class="h-px bg-gradient-to-r from-transparent via-purple-400 to-transparent w-32"></div>
                    <i class="fas fa-award text-purple-600 text-3xl"></i>
                    <div class="h-px bg-gradient-to-r from-transparent via-purple-400 to-transparent w-32"></div>
                </div>
                <p class="text-xl text-purple-700 font-semibold">Juez Evaluador</p>
            </div>

            <!-- Cuerpo del reconocimiento -->
            <div class="mb-8 text-center px-8">
                <p class="text-lg text-gray-700 mb-6">El presente reconocimiento se otorga a:</p>

                <h2 class="text-3xl font-bold text-purple-900 mb-6" style="font-family: 'Georgia', serif; border-bottom: 2px solid #9333ea; display: inline-block; padding-bottom: 4px;">
                    {{ $constancia->usuario->nombre_completo ?? $constancia->usuario->name }}
                </h2>

                <p class="text-lg text-gray-700 mb-4">
                    Por su
                    <span class="font-bold text-purple-600">valiosa colaboración como juez evaluador</span>
                    en el evento:
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                    "{{ $constancia->evento->nombre }}"
                </h3>

                <p class="text-md text-gray-600 italic mb-8">
                    {{ $constancia->descripcion }}
                </p>

                <div class="bg-purple-50 border-l-4 border-purple-600 p-4 mb-8">
                    <p class="text-sm text-purple-900">
                        Agradecemos su dedicación, profesionalismo y compromiso en la evaluación de los proyectos participantes, contribuyendo al desarrollo académico y formación de los estudiantes.
                    </p>
                </div>

                <!-- Fecha y lugar -->
                <div class="flex justify-center gap-12 mb-8">
                    <div class="text-center">
                        <i class="fas fa-calendar-alt text-purple-600 mb-2"></i>
                        <p class="text-sm text-gray-600">Fecha del evento</p>
                        <p class="font-semibold text-gray-800">
                            {{ $constancia->evento->fecha_inicio->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-certificate text-purple-600 mb-2"></i>
                        <p class="text-sm text-gray-600">Fecha de emisión</p>
                        <p class="font-semibold text-gray-800">
                            {{ $constancia->fecha_emision->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

                <!-- Folio -->
                <div class="mt-8 pt-6 border-t border-gray-300">
                    <p class="text-sm text-gray-600">Folio del reconocimiento</p>
                    <p class="text-lg font-mono font-bold text-purple-900">
                        {{ $constancia->numero_folio }}
                    </p>
                </div>
            </div>

            <!-- Firma -->
            <div class="mt-12 pt-8">
                <div class="flex justify-center">
                    <div class="text-center">
                        <div class="mb-2 border-t-2 border-gray-800 w-64 mx-auto"></div>
                        <p class="font-semibold text-gray-800">Dirección del Instituto Tecnológico de Oaxaca</p>
                        <p class="text-sm text-gray-600">Organizador del evento</p>
                    </div>
                </div>
            </div>

            <!-- Sello oficial (decorativo) -->
            <div class="mt-8 flex justify-center">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full border-4 border-purple-900 flex items-center justify-center bg-purple-50">
                        <div class="text-center">
                            <i class="fas fa-university text-purple-900 text-2xl mb-1"></i>
                            <p class="text-xs font-bold text-purple-900">ITO</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borde decorativo inferior -->
        <div class="h-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>
    </div>
</div>

<style>
    @media print {
        body {
            background: white;
        }
        .print\:hidden {
            display: none !important;
        }
        .print\:shadow-none {
            box-shadow: none !important;
        }
        #constancia {
            page-break-inside: avoid;
        }
    }
</style>
@endsection
