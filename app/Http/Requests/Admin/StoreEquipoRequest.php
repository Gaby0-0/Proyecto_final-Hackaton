<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:255', 'min:3', 'unique:equipos,nombre'],
            'proyecto_id' => ['nullable', 'exists:proyectos,id'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'max_integrantes' => ['required', 'integer', 'min:2', 'max:20'],
            'lider_id' => ['nullable', 'exists:users,id'],
            'miembros' => ['nullable', 'array', 'max:20'],
            'miembros.*' => ['exists:users,id', 'distinct'],
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
            'nombre.required' => 'El nombre del equipo es obligatorio.',
            'nombre.string' => 'El nombre del equipo debe ser un texto válido.',
            'nombre.max' => 'El nombre del equipo no puede tener más de :max caracteres.',
            'nombre.min' => 'El nombre del equipo debe tener al menos :min caracteres.',
            'nombre.unique' => 'Ya existe un equipo con este nombre.',
            'proyecto_id.exists' => 'El proyecto seleccionado no existe.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'descripcion.max' => 'La descripción no puede tener más de :max caracteres.',
            'max_integrantes.required' => 'El número máximo de integrantes es obligatorio.',
            'max_integrantes.integer' => 'El número máximo de integrantes debe ser un número entero.',
            'max_integrantes.min' => 'El equipo debe tener al menos :min integrantes.',
            'max_integrantes.max' => 'El equipo no puede tener más de :max integrantes.',
            'lider_id.exists' => 'El líder seleccionado no existe.',
            'miembros.array' => 'Los miembros deben ser un listado válido.',
            'miembros.max' => 'No puedes agregar más de :max miembros.',
            'miembros.*.exists' => 'Uno de los miembros seleccionados no existe.',
            'miembros.*.distinct' => 'No puedes seleccionar el mismo miembro más de una vez.',
        ];
    }
}
