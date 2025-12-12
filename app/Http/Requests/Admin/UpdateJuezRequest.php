<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJuezRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $juezId = $this->route('juez') ? $this->route('juez')->id : null;

        return [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$juezId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'nombre_completo' => ['nullable', 'string', 'max:255', 'min:3'],
            'especialidad' => ['nullable', 'string', 'max:255'],
            'cedula_profesional' => ['nullable', 'string', 'max:255', 'regex:/^[A-Z0-9]+$/'],
            'institucion' => ['nullable', 'string', 'max:255'],
            'experiencia' => ['nullable', 'string', 'max:2000'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\-\+\s\(\)]+$/'],
            'activo' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'nombre_completo.min' => 'El nombre completo debe tener al menos :min caracteres.',
            'cedula_profesional.regex' => 'La cédula profesional solo puede contener letras mayúsculas y números.',
            'experiencia.max' => 'La experiencia no puede tener más de :max caracteres.',
            'telefono.regex' => 'El teléfono solo puede contener números, guiones, paréntesis y espacios.',
            'activo.boolean' => 'El estado activo debe ser verdadero o falso.',
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
            'name' => 'nombre de usuario',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'nombre_completo' => 'nombre completo',
            'especialidad' => 'especialidad',
            'cedula_profesional' => 'cédula profesional',
            'institucion' => 'institución',
            'experiencia' => 'experiencia',
            'telefono' => 'teléfono',
            'activo' => 'estado activo',
        ];
    }
}
