<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AsignarJuecesAutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'especialidad' => ['nullable', 'string', 'max:255'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'especialidad.max' => 'La especialidad no puede tener más de :max caracteres.',
            'cantidad.required' => 'La cantidad de jueces es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'Debe asignar al menos :min juez.',
            'cantidad.max' => 'No puede asignar más de :max jueces.',
        ];
    }
}
