<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarRolEquipoRequest extends FormRequest
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
            'rol_especifico' => ['required', 'string', 'max:255'],
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
            'rol_especifico.required' => 'El rol específico es obligatorio.',
            'rol_especifico.string' => 'El rol específico debe ser un texto válido.',
            'rol_especifico.max' => 'El rol específico no puede tener más de :max caracteres.',
        ];
    }
}
