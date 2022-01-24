<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //

    public function index()
    {

        //arrays
        $array_datos = array();
        $consulta_estados = array();
        $array_numdocs = array();

        //datos obtenidos de Netsuit
        $datos_netsuit = $this->consultarDatosNetsuit();
        foreach ($datos_netsuit as $key => $value) {
            array_push($consulta_estados,  $value['estado_combinado']);
            array_push($array_numdocs,  "'" . $value['numdoc']. "'");
        }

        //datos obtenidos de WMS
        $numeros_referencias_separados = implode("," ,$array_numdocs);
        $datos_wms = $this->consultarWMS($numeros_referencias_separados);
        //dd($datos_wms[0]);

        $resultado_estados = array_unique($consulta_estados);
        $resultado_cont_estads = array_count_values($consulta_estados);

        array_push($array_datos, $resultado_estados);
        array_push($array_datos, $resultado_cont_estads);

        return view('home')->with([
            'array_datos' => $array_datos,
            'datos' => $datos_netsuit,
            'datos_wms' => $datos_wms
        ]);
    }

    public function consultarWMS($nums_agrupados)
    {
        date_default_timezone_set('America/Bogota');
        $fecha_anterior = date("Y-m-d", strtotime(date('Y-m-d'). "-2 day"));
        $fecha_actual = date('Y-m-d');
        $array_datos = array();
        $array_datos_wms = array();

        $sql = "SELECT despachos.punto, despachos.despacho, despachos.fecha, salidas.salida,
            salidas.fecha, salidas.documento_referencia
            FROM despachos, despachos_salidas, salidas
            WHERE despachos.punto =  despachos_salidas.punto AND despachos.despacho = despachos_salidas.despacho
            AND despachos_salidas.punto = salidas.punto AND despachos_salidas.salida = salidas.salida
            AND despachos.interfaz='S' AND salidas.documento_referencia IN ($nums_agrupados) AND despachos.fecha BETWEEN '".$fecha_anterior."' AND '".$fecha_actual."'";

        //print($sql);
        $consulta_wms = DB::connection('pgsql2')->select($sql);
        if(count($consulta_wms) > 0){
            foreach ($consulta_wms as $key => $value) {

                $documento_referencia = $value->documento_referencia;
                $fecha_wms = $value->fecha;

                $data = array(
                    'documento_referencia' => $documento_referencia,
                    'fecha_wms' => $fecha_wms
                );

                array_push($array_datos_wms, $documento_referencia);

            }

            array_push($array_datos, $array_datos_wms);
            return $array_datos;

        } else {
            return null;
        }
    }

    public function consultarDatosNetsuit()
    {
        $response = Http::get('http://192.168.1.47/integracion/pda/netsuit.php');
        $type_result = $response->ok();
        if($type_result){
            $body_respuesta = $response->json();
        } else {
            $body_respuesta = array(
                'data' => ''
            );
        }
        return $body_respuesta;
    }

    public function consultar($id)
    {

        $array = array();
        $data = $this->consultarDatosNetsuit();
        foreach ($data as $key => $value) {
            if($value['numdoc'] == $id){
                $array_data = array(
                    'tipo_venta' => $value['tipo_venta'],
                    'horas_pro' => $value['horas_pro'],
                    'fecha' => $value['fecha'],
                    'fecaprov' => $value['fecaprov'],
                    'quien_cae_venta' => $value['quien_cae_venta'],
                    'estado' => $value['estado_combinado'],
                    'bodega' => $value['bodega'],
                    'metodo_pago' => $value['metodo_pago'],
                    'numdoc' => $value['numdoc'],
                    'total' => $value['total'],
                    'cliente' => $value['cliente'],
                    'control_wms' => $value['control_wms'],
                    'bodega_destino' => $value['bodega_destino']
                );

                array_push($array, $array_data);
            }
        }

        print "
            <h6><strong>Numero de Referencia:</strong> $id</h6>
            <ul>
                <li>
                    <strong>Tipo Venta:</strong> " . $array[0]['tipo_venta'] . "
                </li>
                <li>
                    <strong>Horas Promedio:</strong> " . $array[0]['horas_pro'] . "
                </li>
                <li>
                    <strong>Fecha:</strong> " . $array[0]['fecha'] . "
                </li>
                <li>
                    <strong>Fecha Prov:</strong> " . $array[0]['fecaprov'] . "
                </li>
                <li>
                    <strong>Quien cae venta:</strong> " . $array[0]['quien_cae_venta'] . "
                </li>
                <li>
                    <strong>Estado:</strong> " . $array[0]['estado'] . "
                </li>
                <li>
                    <strong>Bodega:</strong> " . $array[0]['bodega'] . "
                </li>
                <li>
                    <strong>Metodo de Pago:</strong> " . $array[0]['metodo_pago'] . "
                </li>
                <li>
                    <strong>Total:</strong> " . $array[0]['total'] . "
                </li>
                <li>
                    <strong>Cliente:</strong> " . $array[0]['cliente'] . "
                </li>
                <li>
                    <strong>Control WMS:</strong> " . $array[0]['control_wms'] . "
                </li>
                <li>
                    <strong>Bodega Destino:</strong> " . $array[0]['bodega_destino'] . "
                </li>
            </ul>
        ";

    }

}
