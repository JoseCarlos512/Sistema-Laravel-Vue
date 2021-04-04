<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    public function __invoke(Request $request)
    {   
        $anio = date('Y');
        $ingresos = DB::table('ingresos as i')
        ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
                 DB::raw('YEAR(i.fecha_hora) as anio'),
                 DB::raw('SUM(i.total) as total'))
        ->whereYear('i.fecha_hora', '=', $anio)
        ->where('estado', '=', 'Registrado')
        ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
        ->get();

        $ventas = DB::table('ventas as v')
        ->select(DB::raw('MONTH(v.fecha_hora) as mes'),
                 DB::raw('YEAR(v.fecha_hora) as anio'),
                 DB::raw('SUM(v.total) as total'))
        ->whereYear('v.fecha_hora', '=', $anio)
        ->where('estado', '=', 'Registrado')
        ->groupBy(DB::raw('MONTH(v.fecha_hora)'),DB::raw('YEAR(v.fecha_hora)'))
        ->get();

        return ['ingresos' => $ingresos, 'ventas' => $ventas, 'anio' => $anio];
    }

}

/*
 <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    public function __invoke(Request $request)
    {   
        $anio = date('Y');
        $ingresos = DB::table('ingresos as i')
        ->select(DB::raw('(CASE WHEN MONTH(i.fecha_hora) = 1 THEN "Enero"
                                WHEN MONTH(i.fecha_hora) = 2 THEN "Febrero" 
                                WHEN MONTH(i.fecha_hora) = 3 THEN "Marzo"
                                WHEN MONTH(i.fecha_hora) = 4 THEN "Abril"
                                WHEN MONTH(i.fecha_hora) = 5 THEN "Mayo"
                                WHEN MONTH(i.fecha_hora) = 6 THEN "Junio"
                                WHEN MONTH(i.fecha_hora) = 7 THEN "Julio"
                                WHEN MONTH(i.fecha_hora) = 8 THEN "Agosto"
                                WHEN MONTH(i.fecha_hora) = 9 THEN "Septiembre"
                                WHEN MONTH(i.fecha_hora) = 10 THEN "Octubre"
                                WHEN MONTH(i.fecha_hora) = 11 THEN "Noviembre"
                                ELSE "Diciembre" END) AS mes'),
                 DB::raw('YEAR(i.fecha_hora) as anio'),
                 DB::raw('SUM(i.total) as total'))
        ->whereYear('i.fecha_hora', '=', $anio)
        ->where('estado', '=', 'Registrado')
        ->groupBy(1,2))
        ->get();

        $ventas = DB::table('ventas as v')
        ->select(DB::raw('MONTH(v.fecha_hora) as mes'),
                 DB::raw('YEAR(v.fecha_hora) as anio'),
                 DB::raw('SUM(v.total) as total'))
        ->whereYear('v.fecha_hora', '=', $anio)
        ->where('estado', '=', 'Registrado')
        ->groupBy(DB::raw('MONTH(v.fecha_hora)'),DB::raw('YEAR(v.fecha_hora)'))
        ->get();

        return ['ingresos' => $ingresos, 'ventas' => $ventas, 'anio' => $anio];
    }

}
 */
