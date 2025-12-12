<?php

namespace App\Http\Controllers\Juez;

use App\Http\Controllers\Controller;
use App\Models\Constancia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConstanciaController extends Controller
{
    // Mostrar lista de constancias del juez
    public function index()
    {
        $user = Auth::user();

        // Obtener constancias del juez ordenadas por fecha
        $constancias = $user->constancias()
            ->with(['evento'])
            ->where('tipo', 'juez')
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return view('juez.constancias.index', compact('constancias'));
    }

    // Ver detalle de una constancia
    public function show(Constancia $constancia)
    {
        $user = Auth::user();

        // Verificar que la constancia pertenece al juez
        if ($constancia->user_id !== $user->id) {
            return redirect()->route('juez.constancias.index')
                ->with('error', 'No tienes acceso a esta constancia');
        }

        $constancia->load(['evento', 'usuario']);

        return view('juez.constancias.show', compact('constancia'));
    }

    // Descargar constancia (PDF)
    public function descargar(Constancia $constancia)
    {
        $user = Auth::user();

        // Verificar que la constancia pertenece al juez
        if ($constancia->user_id !== $user->id) {
            return redirect()->route('juez.constancias.index')
                ->with('error', 'No tienes acceso a esta constancia');
        }

        // Marcar como descargada
        $constancia->marcarDescargada();

        // Si ya existe archivo generado, descargarlo
        if ($constancia->archivo_url && Storage::disk('public')->exists($constancia->archivo_url)) {
            return Storage::disk('public')->download($constancia->archivo_url, 'Constancia-'.$constancia->numero_folio.'.pdf');
        }

        // Si no existe archivo, generar PDF
        return $this->generarPDF($constancia);
    }

    // Generar PDF de constancia
    private function generarPDF(Constancia $constancia)
    {
        $constancia->load(['evento', 'usuario']);

        // Usar el mismo generador de HTML que el controlador de estudiante
        $html = $this->generarHTMLConstancia($constancia);

        // Generar PDF usando DomPDF
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('letter', 'landscape');

        // Guardar el PDF en storage
        $filename = 'constancia-'.$constancia->numero_folio.'.pdf';
        $filepath = 'constancias/'.$filename;

        Storage::disk('public')->put($filepath, $pdf->output());

        // Actualizar la constancia con la ruta del archivo
        $constancia->update(['archivo_url' => $filepath]);

        // Descargar el PDF
        return $pdf->download($filename);
    }

    // Generar HTML de la constancia (mismo método que en Estudiante\ConstanciaController)
    private function generarHTMLConstancia(Constancia $constancia)
    {
        $tipoTexto = 'JUEZ EVALUADOR';
        $fechaEmision = $constancia->fecha_emision->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                @page {
                    margin: 1cm;
                }
                body {
                    font-family: 'Arial', sans-serif;
                    margin: 0;
                    padding: 40px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }
                .certificado {
                    background: white;
                    padding: 60px;
                    border: 15px solid #4a5568;
                    box-shadow: 0 0 30px rgba(0,0,0,0.3);
                    text-align: center;
                    min-height: 500px;
                    position: relative;
                }
                .header {
                    border-bottom: 3px solid #667eea;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
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
                .texto-principal {
                    font-size: 18px;
                    color: #4a5568;
                    margin: 30px 0;
                    line-height: 1.8;
                }
                .nombre {
                    font-size: 36px;
                    color: #2d3748;
                    font-weight: bold;
                    margin: 25px 0;
                    text-decoration: underline;
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
                </div>

                <div class="texto-principal">
                    Se otorga la presente constancia a:
                </div>

                <div class="nombre">{$constancia->usuario->name}</div>

                <div class="texto-principal">
                    Por su valiosa labor como juez evaluador en el evento:
                </div>

                <div class="evento">{$constancia->evento->nombre}</div>

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
