<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProyectoInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proyecto_titulo' => ['required', 'string', 'max:255', 'min:5'],
            'proyecto_descripcion' => ['required', 'string', 'min:20', 'max:5000'],
            'notas_equipo' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'proyecto_titulo.required' => 'El título del proyecto es obligatorio.',
            'proyecto_titulo.min' => 'El título debe tener al menos :min caracteres.',
            'proyecto_titulo.max' => 'El título no puede tener más de :max caracteres.',
            'proyecto_descripcion.required' => 'La descripción del proyecto es obligatoria.',
            'proyecto_descripcion.min' => 'La descripción debe tener al menos :min caracteres.',
            'proyecto_descripcion.max' => 'La descripción no puede tener más de :max caracteres.',
            'notas_equipo.max' => 'Las notas del equipo no pueden tener más de :max caracteres.',
        ];
    }
}
