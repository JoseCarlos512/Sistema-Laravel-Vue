<?php

namespace App;
use App\Articulo;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{   
    //Campos de la tabla articulos que van a recibir y almacenar valores
    protected $fillable =  [
        'id_categoria', 'codigo', 'nombre', 'precio_venta', 'stock', 'descripcion',
        'condicion'
    ];

    //Muchos articulos pertenecen una categoria
    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }
} 
