<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AsignarJuezAleatorioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoria' => ['nullable', 'string', 'max:255'],
            'evento_id' => ['nullable', 'exists:eventos,id'],
            'num_jueces' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'categoria.max' => 'La categoría no puede tener más de :max caracteres.',
            'evento_id.exists' => 'El evento seleccionado no existe.',
            'num_jueces.required' => 'El número de jueces es obligatorio.',
            'num_jueces.integer' => 'El número de jueces debe ser un número entero.',
            'num_jueces.min' => 'Debe asignar al menos :min juez.',
            'num_jueces.max' => 'No puede asignar más de :max jueces por equipo.',
        ];
    }
}
