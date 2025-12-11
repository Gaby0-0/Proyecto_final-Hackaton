<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AsignarJuezManualRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'juez_id' => ['required', 'exists:users,id'],
            'equipo_id' => ['required', 'exists:equipos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'juez_id.required' => 'Debe seleccionar un juez.',
            'juez_id.exists' => 'El juez seleccionado no existe.',
            'equipo_id.required' => 'Debe seleccionar un equipo.',
            'equipo_id.exists' => 'El equipo seleccionado no existe.',
        ];
    }
}
