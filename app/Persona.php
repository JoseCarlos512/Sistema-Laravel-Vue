<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'nombre', 'tipo_documento', 'num_documento', 'direccion', 'telefono', 'email'
    ];

    public function proveedor(){
        return $this->hasOne('App\Proveedor');
    }

    // Una persona esta relacionada de manera directa con un usuario
    public function usuario(){
        return $this->hasOne('App\User');
    }
}
