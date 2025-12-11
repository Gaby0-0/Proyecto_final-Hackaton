<?php

namespace App\Http\Requests\Juez;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'puntuacion' => ['required', 'numeric', 'min:0', 'max:100'],
            'comentarios' => ['nullable', 'string', 'max:2000'],
            'criterio_innovacion' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'criterio_funcionalidad' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'criterio_presentacion' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'criterio_impacto' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'criterio_tecnico' => ['nullable', 'numeric', 'min:0', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'puntuacion.required' => 'La puntuación es obligatoria.',
            'puntuacion.numeric' => 'La puntuación debe ser un número.',
            'puntuacion.min' => 'La puntuación mínima es :min.',
            'puntuacion.max' => 'La puntuación máxima es :max.',
            'comentarios.max' => 'Los comentarios no pueden tener más de :max caracteres.',
            'criterio_innovacion.numeric' => 'El criterio de innovación debe ser un número.',
            'criterio_innovacion.min' => 'El criterio de innovación mínimo es :min.',
            'criterio_innovacion.max' => 'El criterio de innovación máximo es :max.',
            'criterio_funcionalidad.numeric' => 'El criterio de funcionalidad debe ser un número.',
            'criterio_funcionalidad.min' => 'El criterio de funcionalidad mínimo es :min.',
            'criterio_funcionalidad.max' => 'El criterio de funcionalidad máximo es :max.',
            'criterio_presentacion.numeric' => 'El criterio de presentación debe ser un número.',
            'criterio_presentacion.min' => 'El criterio de presentación mínimo es :min.',
            'criterio_presentacion.max' => 'El criterio de presentación máximo es :max.',
            'criterio_impacto.numeric' => 'El criterio de impacto debe ser un número.',
            'criterio_impacto.min' => 'El criterio de impacto mínimo es :min.',
            'criterio_impacto.max' => 'El criterio de impacto máximo es :max.',
            'criterio_tecnico.numeric' => 'El criterio técnico debe ser un número.',
            'criterio_tecnico.min' => 'El criterio técnico mínimo es :min.',
            'criterio_tecnico.max' => 'El criterio técnico máximo es :max.',
        ];
    }
}
