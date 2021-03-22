<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $filleble = [
        'nombre', 'descripcion', 'condicion'
    ];

    public $timestamps = false;

    //Un rol puede tener varios usuarios
    public function Users(){
        return $this->hasMany('App\User');
    }
}
