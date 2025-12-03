<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionGlobal extends Model
{
    protected $table = 'configuracion_global';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
    ];

    // Método helper para obtener valor de configuración
    public static function obtener($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    // Método helper para establecer valor de configuración
    public static function establecer($clave, $valor, $descripcion = null)
    {
        return self::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'descripcion' => $descripcion,
            ]
        );
    }
}
