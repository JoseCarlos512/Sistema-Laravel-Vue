<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'id', 'contacto', 'telefono_contacto'
    ];

    public $timestamps = false;

    // relacion proveedores -> persona
    // puedo ser persona pero no proveedor
    // persona es mas grande
    //Proveedor pertenece a persona
    public function persona(){
        return $this->belongsto('App\Persona');
    }
}
