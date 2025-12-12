<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;

class TransferirLiderazgoRequest extends FormRequest
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
            'nuevo_lider_id' => ['required', 'integer', 'exists:users,id'],
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
            'nuevo_lider_id.required' => 'Debes seleccionar un nuevo líder.',
            'nuevo_lider_id.integer' => 'El identificador del nuevo líder debe ser un número válido.',
            'nuevo_lider_id.exists' => 'El usuario seleccionado no existe.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nuevo_lider_id' => 'nuevo líder',
        ];
    }
}
