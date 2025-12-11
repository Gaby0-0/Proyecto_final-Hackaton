<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'min:3', 'unique:equipos,nombre'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'proyecto_id' => ['nullable', 'exists:proyectos,id'],
            'max_integrantes' => ['required', 'integer', 'min:2', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del equipo es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos :min caracteres.',
            'nombre.max' => 'El nombre no puede tener más de :max caracteres.',
            'nombre.unique' => 'Ya existe un equipo con este nombre.',
            'descripcion.max' => 'La descripción no puede tener más de :max caracteres.',
            'proyecto_id.exists' => 'El proyecto seleccionado no existe.',
            'max_integrantes.required' => 'El número máximo de integrantes es obligatorio.',
            'max_integrantes.integer' => 'El número de integrantes debe ser un número entero.',
            'max_integrantes.min' => 'El equipo debe tener al menos :min integrantes.',
            'max_integrantes.max' => 'El equipo no puede tener más de :max integrantes.',
        ];
    }
}
