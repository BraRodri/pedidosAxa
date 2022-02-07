<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    //
    public function index(Request $request)
    {

        $fecha = $request->fecha;
        if(empty($fecha)){
            $fecha = date('Y-m-d');
        }

        $data_factura = Factura::where('fecha', $fecha)->get();

        $cantidad_facturados = 0;
        $cantidad_no_facturados = 0;
        $cantidad_sin_dian = 0;
        foreach ($data_factura as $key => $value) {
            if($value->fecha_creacion_factura){
                $cantidad_facturados++;
            } else {
                $cantidad_no_facturados++;
            }

            if(!$value->fecha_respuesta_dian){
                $cantidad_sin_dian++;
            }
        }

        return view('pages.factura')->with([
            'fecha' => $fecha,
            'data_factura' => $data_factura,
            'cantidad_facturados' => $cantidad_facturados,
            'cantidad_no_facturados' => $cantidad_no_facturados,
            'cantidad_sin_dian' => $cantidad_sin_dian
        ]);
    }
}
