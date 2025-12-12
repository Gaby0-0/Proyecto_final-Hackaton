<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class DatosEstudianteRequest extends FormRequest
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
            'numero_control' => ['required', 'string', 'max:20', 'unique:datos_estudiante,numero_control', 'regex:/^[A-Z0-9]+$/'],
            'carrera' => ['required', 'string', 'max:255', 'min:3'],
            'semestre' => ['nullable', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\-\+\s\(\)]+$/'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today', 'after:1940-01-01'],
            'direccion' => ['nullable', 'string', 'max:500'],
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
            'numero_control.required' => 'El número de control es obligatorio.',
            'numero_control.string' => 'El número de control debe ser un texto válido.',
            'numero_control.max' => 'El número de control no puede tener más de :max caracteres.',
            'numero_control.unique' => 'Este número de control ya está registrado.',
            'numero_control.regex' => 'El número de control solo puede contener letras mayúsculas y números.',
            'carrera.required' => 'La carrera es obligatoria.',
            'carrera.string' => 'La carrera debe ser un texto válido.',
            'carrera.max' => 'La carrera no puede tener más de :max caracteres.',
            'carrera.min' => 'La carrera debe tener al menos :min caracteres.',
            'semestre.string' => 'El semestre debe ser un texto válido.',
            'semestre.max' => 'El semestre no puede tener más de :max caracteres.',
            'telefono.string' => 'El teléfono debe ser un texto válido.',
            'telefono.max' => 'El teléfono no puede tener más de :max caracteres.',
            'telefono.regex' => 'El teléfono solo puede contener números, guiones, paréntesis y espacios.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'fecha_nacimiento.after' => 'La fecha de nacimiento debe ser posterior a :date.',
            'direccion.string' => 'La dirección debe ser un texto válido.',
            'direccion.max' => 'La dirección no puede tener más de :max caracteres.',
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
            'numero_control' => 'número de control',
            'carrera' => 'carrera',
            'semestre' => 'semestre',
            'telefono' => 'teléfono',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'direccion' => 'dirección',
        ];
    }
}
