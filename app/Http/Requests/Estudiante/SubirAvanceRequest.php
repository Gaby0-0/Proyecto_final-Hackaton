<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;

class SubirAvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descripcion' => ['required', 'string', 'max:255', 'min:5'],
            'archivo' => ['required', 'file', 'max:51200', 'mimes:pdf,doc,docx,zip,rar,pptx,mp4,avi,jpg,jpeg,png'],
        ];
    }

    public function messages(): array
    {
        return [
            'descripcion.required' => 'La descripción del avance es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos :min caracteres.',
            'descripcion.max' => 'La descripción no puede tener más de :max caracteres.',
            'archivo.required' => 'Debe seleccionar un archivo para subir.',
            'archivo.file' => 'El archivo seleccionado no es válido.',
            'archivo.max' => 'El archivo no puede ser mayor a 50 MB.',
            'archivo.mimes' => 'El archivo debe ser de tipo: PDF, DOC, DOCX, ZIP, RAR, PPTX, MP4, AVI, JPG, JPEG o PNG.',
        ];
    }
}
