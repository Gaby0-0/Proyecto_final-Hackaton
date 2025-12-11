<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EstablecerGanadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'equipo_id' => ['required', 'exists:equipos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'equipo_id.required' => 'Debe seleccionar un equipo ganador.',
            'equipo_id.exists' => 'El equipo seleccionado no existe.',
        ];
    }
}
