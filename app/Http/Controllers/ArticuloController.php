<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Articulo;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $articulos = Articulo::join('categorias','articulos.id_categoria','=','categorias.id')
            ->select('articulos.id','articulos.id_categoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria',
                     'articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id','desc')->paginate(3);
        }else{
            $articulos = Articulo::join('categorias','articulos.id_categoria','=','categorias.id')
            ->select('articulos.id','articulos.id_categoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria',
                     'articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.id','desc')->paginate(3);
        }
        // Declaramos un array que me devuelva todas las categorias
        //$categorias = Categoria::paginate(5);

        //No se como agrego datos de la paginacion
        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem()
            ],
            'articulos' => $articulos
        ];
    }

    public function listarArticulo(Request $request){
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $articulos = Articulo::join('categorias','articulos.id_categoria','=','categorias.id')
            ->select('articulos.id','articulos.id_categoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria',
                     'articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id','desc')->paginate(10);
        }else{
            $articulos = Articulo::join('categorias','articulos.id_categoria','=','categorias.id')
            ->select('articulos.id','articulos.id_categoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria',
                     'articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.id','desc')->paginate(10);
        }
        // Declaramos un array que me devuelva todas las categorias
        //$categorias = Categoria::paginate(5);

        return [ 'articulos' => $articulos ];
    }
    
    public function listarPDF() {
        $articulos = Articulo::join('categorias','articulos.id_categoria','=','categorias.id')
        ->select('articulos.id','articulos.id_categoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria',
                    'articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
        ->orderBy('articulos.nombre','desc')->get();

        $count = Articulo::count();

        $pdf = \PDF::loadView('pdf.articulospdf',['articulos'=>$articulos, 'count'=>$count]);
        return $pdf->download('articulos.pdf');
    }

    public function buscarArticulo(Request $request) {
        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo', '=', $filtro)
        ->select('id', 'nombre')
        ->orderBy('nombre', 'asc')->take(1)->get();

        return ['articulos' => $articulos];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');
        $articulo = new Articulo();
        $articulo->id_categoria = $request->id_categoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock; 
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->save(); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        if(!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->id_categoria = $request->id_categoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock; 
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->save(); 
        
    }

    public function desactivar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save(); 
    }

    public function activar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save(); 
    }
}
