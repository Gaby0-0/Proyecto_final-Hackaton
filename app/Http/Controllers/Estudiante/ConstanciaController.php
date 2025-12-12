<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Constancia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConstanciaController extends Controller
{
    // Mostrar lista de constancias del estudiante
    public function index()
    {
        $user = Auth::user();

        // Obtener constancias del usuario ordenadas por fecha
        $constancias = $user->constancias()
            ->with(['evento', 'equipo'])
            ->orderBy('fecha_emision', 'desc')
            ->get();

        // Separar por tipo
        $constanciasGanador = $constancias->where('tipo', 'ganador');
        $constanciasParticipante = $constancias->where('tipo', 'participante');

        return view('estudiante.constancias.index', compact('constancias', 'constanciasGanador', 'constanciasParticipante'));
    }

    // Ver detalle de una constancia
    public function show(Constancia $constancia)
    {
        $user = Auth::user();

        // Verificar que la constancia pertenece al usuario
        if ($constancia->user_id !== $user->id) {
            return redirect()->route('estudiante.constancias.index')
                ->with('error', 'No tienes acceso a esta constancia');
        }

        $constancia->load(['evento', 'equipo.miembros']);

        return view('estudiante.constancias.show', compact('constancia'));
    }

    // Descargar constancia (PDF)
    public function descargar(Constancia $constancia)
    {
        $user = Auth::user();

        // Verificar que la constancia pertenece al usuario
        if ($constancia->user_id !== $user->id) {
            return redirect()->route('estudiante.constancias.index')
                ->with('error', 'No tienes acceso a esta constancia');
        }

        // Marcar como descargada
        $constancia->marcarDescargada();

        $constancia->load(['evento', 'equipo', 'usuario']);
        $html = $this->generarHTMLConstancia($constancia);

        $pdf = Pdf::loadHTML($html)->setPaper('letter', 'portrait');

        return $pdf->stream('Constancia-'.$constancia->numero_folio.'.pdf', ['Attachment' => true]);

    }

    // Generar PDF de constancia
    private function generarPDF(Constancia $constancia)
    {
        $constancia->load(['evento', 'equipo', 'usuario']);

        // Generar HTML para el PDF
        $html = $this->generarHTMLConstancia($constancia);

        // Generar PDF usando DomPDF
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('letter', 'landscape');

        // Guardar el PDF en storage si no existe
        $filename = 'constancia-'.$constancia->numero_folio.'.pdf';
        $filepath = 'constancias/'.$filename;

        Storage::disk('public')->put($filepath, $pdf->output());

        // Actualizar la constancia con la ruta del archivo
        $constancia->update(['archivo_url' => $filepath]);

        // Descargar el PDF
        return $pdf->download($filename);
    }

    // Generar HTML de la constancia
    private function generarHTMLConstancia(Constancia $constancia)
    {
        $tipoTexto = match ($constancia->tipo) {
            'ganador' => 'GANADOR',
            'juez' => 'JUEZ EVALUADOR',
            default => 'PARTICIPANTE'
        };

        $lugarTexto = '';

        if ($constancia->tipo === 'ganador' && $constancia->lugar) {
            $lugarTexto = match ($constancia->lugar) {
                1 => '1er LUGAR',
                2 => '2do LUGAR',
                3 => '3er LUGAR',
                default => ''
            };
        }

        $bloqueLugar = $lugarTexto
            ? "<div class=\"lugar\">{$lugarTexto}</div>"
            : '';

        $descripcionParticipacion =
            $constancia->tipo === 'ganador'
                ? 'destacada participación como ganador'
                : ($constancia->tipo === 'juez'
                    ? 'valiosa labor como juez evaluador'
                    : 'valiosa participación'
                );

        $textoEquipo = $constancia->equipo
            ? "Como miembro del equipo: {$constancia->equipo->nombre}"
            : 'Como juez evaluador del evento';

        $bloqueProyecto = $constancia->proyecto_nombre
            ? "<div class=\"proyecto\">Proyecto: {$constancia->proyecto_nombre}</div>"
            : '';

        $fechaEmision = $constancia->fecha_emision
            ->locale('es')
            ->isoFormat('D [de] MMMM [de] YYYY');

        return <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <style>
                    @page {
                        size: letter landscape;
                        margin: 0;
                        
                    }
                    body {
                        font-family: 'Arial', sans-serif;
                        margin: 25px;
                        padding: 40px;
                        background: white;
                    }
                    .certificado {
                        max-height: 310px;
                        background: white;
                        padding: 30px;
                        border: 10px solid #4a5568;
                        box-shadow: 0 0 30px rgba(0,0,0,0.3);
                        text-align: center;
                        min-height: 590px;
                        position: relative;
                    }
                    .header {
                        border-bottom: 3px solid #667eea;
                        padding-bottom: 20px;
                        margin-bottom: 30px;
                        font-family: 'Georgia', serif;
                    }
                    h1 {
                        color: #667eea;
                        font-size: 48px;
                        margin: 0;
                        font-weight: bold;
                        text-transform: uppercase;
                        letter-spacing: 3px;
                    }
                    .tipo {
                        color: #764ba2;
                        font-size: 32px;
                        font-weight: bold;
                        margin: 20px 0;
                    }
                    .lugar {
                        color: #f59e0b;
                        font-size: 42px;
                        font-weight: bold;
                        margin: 15px 0;
                    }
                    .texto-principal {
                        font-size: 18px;
                        color: #4a5568;
                        margin: 30px 0;
                        line-height: 1;
                    }
                    .nombre {
                        font-size: 36px;
                        color: #2d3748;
                        font-weight: bold;
                        margin: 25px 0;
                        text-decoration: underline;
                    }
                    .equipo {
                        font-size: 22px;
                        color: #4a5568;
                        margin: 20px 0;
                        font-weight: bold;
                    }
                    .proyecto {
                        font-size: 18px;
                        color: #667eea;
                        margin: 15px 0;
                        font-style: italic;
                    }
                    .evento {
                        font-size: 24px;
                        color: #764ba2;
                        margin: 20px 0;
                        font-weight: bold;
                    }
                    .footer {
                        margin-top: 50px;
                        padding-top: 20px;
                        border-top: 2px solid #e2e8f0;
                        font-size: 14px;
                        color: #718096;
                    }
                    .folio {
                        position: absolute;
                        top: 20px;
                        right: 40px;
                        font-size: 12px;
                        color: #a0aec0;
                    }
                    .firma-linea {
                        border-top: 2px solid #2d3748;
                        width: 300px;
                        margin: 50px auto 10px;
                    }
                    .firma-texto {
                        font-size: 14px;
                        color: #4a5568;
                    }
                </style>
            </head>
            <body>
                <div class="certificado">
                    <div class="folio">Folio: {$constancia->numero_folio}</div>

                    <div class="header">
                        <h1>CONSTANCIA</h1>
                        <div class="tipo">DE {$tipoTexto}</div>
                        {$bloqueLugar}
                    </div>

                    <div class="texto-principal">
                        Se otorga la presente constancia a:
                    </div>

                    <div class="nombre">{$constancia->usuario->name}</div>

                    <div class="texto-principal">
                        Por su {$descripcionParticipacion} en el evento:
                    </div>

                    <div class="evento">{$constancia->evento->nombre}</div>

                    <div class="equipo">{$textoEquipo}</div>

                    {$bloqueProyecto}

                    <div class="firma-linea"></div>
                    <div class="firma-texto">Dirección del Hackaton</div>

                    <div class="footer">
                        Expedido el {$fechaEmision}
                    </div>
                </div>
            </body>
            </html>
            HTML;
    }
}
