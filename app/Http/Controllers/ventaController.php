<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Venta;
use App\DetalleVenta;

class ventaController extends Controller
{
    public function index(Request $request){
        //if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')  
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto', 'ventas.total',
            'ventas.estado','personas.nombre','users.usuario')
            ->orderBy('ventas.id', 'desc')->paginate(3);
        }else{
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')  
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto', 'ventas.total',
            'ventas.estado','personas.nombre','users.usuario')
            ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ventas.id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total'         => $ventas->total(),
                'current_page'  => $ventas->currentPage(),
                'per_page'      => $ventas->perPage(),
                'last_page'     => $ventas->lastPage(),
                'from'          => $ventas->firstItem(),
                'to'            => $ventas->lastItem()
            ],
            'ventas' => $ventas
        ];
    }

    public function obtenerCabecera(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;

        $ingreso = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')  
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto', 'ingresos.total',
        'ingresos.estado','personas.nombre as proveedor','users.usuario')
        ->where('ingresos.id','=',$id)
        ->orderBy('ingresos.id', 'desc')->take(1)->get();
        

        return ['ingreso' => $ingreso];
    }
    
    public function obtenerDetalles(Request $request){
        

        $id = $request->id;

        $detalles = DetalleIngreso::join('articulos','detalle_ingresos.idarticulo','=','articulos.id')
        ->select('detalle_ingresos.cantidad','detalle_ingresos.precio','articulos.nombre as articulo')
        ->where('detalle_ingresos.idingreso','=',$id)
        ->orderBy('detalle_ingresos.id', 'desc')->get();
        
        return ['detalles' => $detalles];
    }

    public function store(Request $request){
        if(!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            //Zona Horaria con la clase carbon
            $mytime = Carbon::now('America/Lima');

            $venta = new Venta();
            $venta->idcliente = $request->idcliente;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->serie_comprobante = $request->serie_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora = $mytime->toDateString();
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->estado = 'Registrado';
            $venta->save(); 

            $detalles = $request->data; //Array de detalles
            // Retorno todos los elementos

            foreach($detalles as $ep=>$det){
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();
            }
            
            DB::commit();
             
        } catch (Exception $e) {
            DB::rollBack();
        } 
        
    }

    public function desactivar(Request $request){
        if(!$request->ajax()) return redirect('/');
        $ingreso = Venta::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save(); 
    }

}
