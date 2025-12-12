<?php

namespace App\Http\Requests\Juez;

use Illuminate\Foundation\Http\FormRequest;

class GuardarPerfilJuezRequest extends FormRequest
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
            'nombre_completo' => ['required', 'string', 'max:255'],
            'especialidad' => ['required', 'string', 'max:255'],
            'cedula_profesional' => ['nullable', 'string', 'max:255'],
            'institucion' => ['nullable', 'string', 'max:255'],
            'experiencia' => ['nullable', 'string'],
            'telefono' => ['nullable', 'string', 'max:20'],
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
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.string' => 'El nombre completo debe ser un texto válido.',
            'nombre_completo.max' => 'El nombre completo no puede tener más de :max caracteres.',
            'especialidad.required' => 'La especialidad es obligatoria.',
            'especialidad.string' => 'La especialidad debe ser un texto válido.',
            'especialidad.max' => 'La especialidad no puede tener más de :max caracteres.',
            'cedula_profesional.string' => 'La cédula profesional debe ser un texto válido.',
            'cedula_profesional.max' => 'La cédula profesional no puede tener más de :max caracteres.',
            'institucion.string' => 'La institución debe ser un texto válido.',
            'institucion.max' => 'La institución no puede tener más de :max caracteres.',
            'experiencia.string' => 'La experiencia debe ser un texto válido.',
            'telefono.string' => 'El teléfono debe ser un texto válido.',
            'telefono.max' => 'El teléfono no puede tener más de :max caracteres.',
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
            'nombre_completo' => 'nombre completo',
            'especialidad' => 'especialidad',
            'cedula_profesional' => 'cédula profesional',
            'institucion' => 'institución',
            'experiencia' => 'experiencia',
            'telefono' => 'teléfono',
        ];
    }
}
