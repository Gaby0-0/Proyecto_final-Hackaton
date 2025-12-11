<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;

class UnirseCodigoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => ['required', 'string', 'size:8', 'regex:/^[A-Z0-9]{8}$/'],
            'rol_especifico' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código del equipo es obligatorio.',
            'codigo.size' => 'El código del equipo debe tener exactamente :size caracteres.',
            'codigo.regex' => 'El código del equipo debe contener solo letras mayúsculas y números.',
            'rol_especifico.max' => 'El rol específico no puede tener más de :max caracteres.',
        ];
    }
}
