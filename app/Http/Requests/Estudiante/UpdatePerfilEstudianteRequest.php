<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePerfilEstudianteRequest extends FormRequest
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
        $userId = Auth::id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$userId],
            'numero_control' => ['nullable', 'string', 'max:50'],
            'carrera' => ['nullable', 'string', 'max:255'],
            'semestre' => ['nullable', 'integer', 'min:1', 'max:12'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'github' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'portafolio' => ['nullable', 'url', 'max:255'],
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
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'numero_control.string' => 'El número de control debe ser un texto válido.',
            'numero_control.max' => 'El número de control no puede tener más de :max caracteres.',
            'carrera.string' => 'La carrera debe ser un texto válido.',
            'carrera.max' => 'La carrera no puede tener más de :max caracteres.',
            'semestre.integer' => 'El semestre debe ser un número entero.',
            'semestre.min' => 'El semestre debe ser al menos :min.',
            'semestre.max' => 'El semestre no puede ser mayor a :max.',
            'telefono.string' => 'El teléfono debe ser un texto válido.',
            'telefono.max' => 'El teléfono no puede tener más de :max caracteres.',
            'github.url' => 'El enlace de GitHub debe ser una URL válida.',
            'github.max' => 'El enlace de GitHub no puede tener más de :max caracteres.',
            'linkedin.url' => 'El enlace de LinkedIn debe ser una URL válida.',
            'linkedin.max' => 'El enlace de LinkedIn no puede tener más de :max caracteres.',
            'portafolio.url' => 'El enlace del portafolio debe ser una URL válida.',
            'portafolio.max' => 'El enlace del portafolio no puede tener más de :max caracteres.',
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
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'numero_control' => 'número de control',
            'carrera' => 'carrera',
            'semestre' => 'semestre',
            'telefono' => 'teléfono',
            'github' => 'GitHub',
            'linkedin' => 'LinkedIn',
            'portafolio' => 'portafolio',
        ];
    }
}
