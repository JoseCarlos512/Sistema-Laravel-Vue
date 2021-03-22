<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //protected  $table = 'categorias'
    //protected $primaryKey = 'id';
    protected $fillable = ['nombre', 'descripcion', 'condicion'];

    //Una categoria contiene a muchos articulos
    public function articulos(){
        return $this->hasMany('App\Articulo');
    }
}
