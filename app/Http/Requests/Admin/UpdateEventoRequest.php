<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'min:3'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:activo,programado,finalizado,cancelado'],
            'modalidad' => ['required', 'in:presencial,virtual,hibrida'],
            'max_equipos' => ['required', 'integer', 'min:1', 'max:1000'],
            'tipo' => ['nullable', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'nombre.min' => 'El nombre del evento debe tener al menos :min caracteres.',
            'nombre.max' => 'El nombre del evento no puede tener más de :max caracteres.',
            'descripcion.max' => 'La descripción no puede tener más de :max caracteres.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'estado.required' => 'El estado del evento es obligatorio.',
            'estado.in' => 'El estado debe ser: activo, programado, finalizado o cancelado.',
            'modalidad.required' => 'La modalidad es obligatoria.',
            'modalidad.in' => 'La modalidad debe ser: presencial, virtual o híbrida.',
            'max_equipos.required' => 'El número máximo de equipos es obligatorio.',
            'max_equipos.integer' => 'El número máximo de equipos debe ser un número entero.',
            'max_equipos.min' => 'Debe haber al menos :min equipo.',
            'max_equipos.max' => 'No puede haber más de :max equipos.',
            'tipo.max' => 'El tipo no puede tener más de :max caracteres.',
            'categoria.max' => 'La categoría no puede tener más de :max caracteres.',
        ];
    }
}
