<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreJuezRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nombre_completo' => ['required', 'string', 'max:255', 'min:3'],
            'especialidad' => ['required', 'string', 'max:255'],
            'cedula_profesional' => ['nullable', 'string', 'max:255', 'regex:/^[A-Z0-9]+$/'],
            'institucion' => ['nullable', 'string', 'max:255'],
            'experiencia' => ['nullable', 'string', 'max:2000'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\-\+\s\(\)]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.min' => 'El nombre completo debe tener al menos :min caracteres.',
            'especialidad.required' => 'La especialidad es obligatoria.',
            'cedula_profesional.regex' => 'La cédula profesional solo puede contener letras mayúsculas y números.',
            'experiencia.max' => 'La experiencia no puede tener más de :max caracteres.',
            'telefono.regex' => 'El teléfono solo puede contener números, guiones, paréntesis y espacios.',
        ];
    }
}
