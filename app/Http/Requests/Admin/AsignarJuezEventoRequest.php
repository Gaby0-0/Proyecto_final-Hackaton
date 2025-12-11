<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AsignarJuezEventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'juez_id' => ['required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'juez_id.required' => 'Debe seleccionar un juez.',
            'juez_id.exists' => 'El juez seleccionado no existe.',
        ];
    }
}
