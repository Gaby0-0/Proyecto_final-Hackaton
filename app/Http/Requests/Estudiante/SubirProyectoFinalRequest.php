<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;

class SubirProyectoFinalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proyecto_final' => ['required', 'file', 'max:102400', 'mimes:pdf,doc,docx,zip,rar,pptx,mp4,avi'],
        ];
    }

    public function messages(): array
    {
        return [
            'proyecto_final.required' => 'Debe seleccionar el archivo del proyecto final.',
            'proyecto_final.file' => 'El archivo seleccionado no es válido.',
            'proyecto_final.max' => 'El archivo no puede ser mayor a 100 MB.',
            'proyecto_final.mimes' => 'El archivo debe ser de tipo: PDF, DOC, DOCX, ZIP, RAR, PPTX, MP4 o AVI.',
        ];
    }
}
