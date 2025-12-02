<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    //
    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'rol_id',
        'control',
        'carrera'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
