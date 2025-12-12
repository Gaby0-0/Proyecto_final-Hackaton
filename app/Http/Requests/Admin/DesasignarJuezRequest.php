<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DesasignarJuezRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'juez_id' => ['required', 'exists:users,id'],
            'equipo_id' => ['required', 'exists:equipos,id'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'juez_id.required' => 'El ID del juez es obligatorio.',
            'juez_id.exists' => 'El juez seleccionado no existe.',
            'equipo_id.required' => 'El ID del equipo es obligatorio.',
            'equipo_id.exists' => 'El equipo seleccionado no existe.',
        ];
    }
}
