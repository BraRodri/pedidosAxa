<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WmsController extends Controller
{
    //
    public function index(Request $request)
    {

        $fecha = $request->fecha;
        if(empty($fecha)){
            $fecha = date('Y-m-d');
        }

        $total = 0;
        $datos_enviar = array();

        //total
        $sql_total = "SELECT count(*)
            FROM salidas
            WHERE salidas.fecha = '".$fecha."'";
        $result_total = DB::connection('pgsql2')->select($sql_total);
        $total = $result_total[0]->count;

        //para ruta
        $sql_ruta = "SELECT
            salidas.total_items,
            salidas.total_solicitada,
            salidas.cantidad_ruta AS cantidad_estado,
            ultimaruta(salidas.punto, salidas.salida) as ultima_fecha_estado
            FROM salidas
            WHERE salidas.fecha between '".$fecha."' and '".$fecha."'"
        ;
        $consulta_ruta = DB::connection('pgsql2')->select($sql_ruta);
        $retorno_ruta = $this->obtenerDatos($fecha, $consulta_ruta, 'En ruta');
        array_push($datos_enviar, $retorno_ruta);

        //para alistamiento
        $sql_alistada = "SELECT
            salidas.total_items,
            salidas.total_solicitada,
            salidas.total_alistada AS cantidad_estado,
            ultimoalistamiento(salidas.punto, salidas.salida) as ultima_fecha_estado
            FROM salidas
            WHERE salidas.fecha between '".$fecha."' and '".$fecha."'"
        ;
        $consulta_alistamiento = DB::connection('pgsql2')->select($sql_alistada);
        $retorno_alistamiento = $this->obtenerDatos($fecha, $consulta_alistamiento, 'En alistamiento');
        array_push($datos_enviar, $retorno_alistamiento);

        //para despacho
        $sql_despacho = "SELECT
            salidas.total_items,
            salidas.total_solicitada,
            salidas.total_despachada AS cantidad_estado,
            ultimodespacho(salidas.punto, salidas.salida) as ultima_fecha_estado
            FROM salidas
            WHERE salidas.fecha between '".$fecha."' and '".$fecha."'"
        ;
        $consulta_despacho = DB::connection('pgsql2')->select($sql_despacho);
        $retorno_despacho = $this->obtenerDatos($fecha, $consulta_despacho, 'En despacho');
        array_push($datos_enviar, $retorno_despacho);

        //para entrega
        $sql_entrega = "SELECT
            salidas.total_items,
            salidas.total_solicitada,
            salidas.total_entregada AS cantidad_estado,
            ultimaentrega(salidas.punto, salidas.salida) as ultima_fecha_estado
            FROM salidas
            WHERE salidas.fecha between '".$fecha."' and '".$fecha."'"
        ;
        $consulta_entrega = DB::connection('pgsql2')->select($sql_entrega);
        $retorno_entrega = $this->obtenerDatos($fecha, $consulta_entrega, 'En entrega');
        array_push($datos_enviar, $retorno_entrega);

        //dd($datos_enviar);

        return view('pages.wms')->with([
            'fecha' => $fecha,
            'total' => $total,
            'datos_enviar' => $datos_enviar
        ]);
    }

    public function obtenerDatos($fecha, $consulta_wms, $estado_texto)
    {

        $estado = 0;

        $cantidad_fecha_1 = 0;
        $cantidad_fecha_2 = 0;
        $cantidad_fecha_3 = 0;
        $cantidad_fecha_4 = 0;
        $cantidad_fecha_5 = 0;
        $cantidad_fecha_6 = 0;
        $cantidad_fecha_7 = 0;
        $cantidad_fecha_8 = 0;
        $cantidad_fecha_9 = 0;
        $cantidad_fecha_10 = 0;
        $cantidad_fecha_11 = 0;
        $cantidad_fecha_12 = 0;
        $cantidad_fecha_13 = 0;
        $cantidad_fecha_14 = 0;
        $cantidad_fecha_15 = 0;
        $cantidad_fecha_16 = 0;
        $cantidad_fecha_17 = 0;

        $total_cantidad_items_1 = 0;
        $total_cantidad_items_2 = 0;
        $total_cantidad_items_3 = 0;
        $total_cantidad_items_4 = 0;
        $total_cantidad_items_5 = 0;
        $total_cantidad_items_6 = 0;
        $total_cantidad_items_7 = 0;
        $total_cantidad_items_8 = 0;
        $total_cantidad_items_9 = 0;
        $total_cantidad_items_10 = 0;
        $total_cantidad_items_11 = 0;
        $total_cantidad_items_12 = 0;
        $total_cantidad_items_13 = 0;
        $total_cantidad_items_14 = 0;
        $total_cantidad_items_15 = 0;
        $total_cantidad_items_16 = 0;
        $total_cantidad_items_17 = 0;

        $total_cantidad_solicitada_1 = 0;
        $total_cantidad_solicitada_2 = 0;
        $total_cantidad_solicitada_3 = 0;
        $total_cantidad_solicitada_4 = 0;
        $total_cantidad_solicitada_5 = 0;
        $total_cantidad_solicitada_6 = 0;
        $total_cantidad_solicitada_7 = 0;
        $total_cantidad_solicitada_8 = 0;
        $total_cantidad_solicitada_9 = 0;
        $total_cantidad_solicitada_10 = 0;
        $total_cantidad_solicitada_11 = 0;
        $total_cantidad_solicitada_12 = 0;
        $total_cantidad_solicitada_13 = 0;
        $total_cantidad_solicitada_14 = 0;
        $total_cantidad_solicitada_15 = 0;
        $total_cantidad_solicitada_16 = 0;
        $total_cantidad_solicitada_17 = 0;

        if(count($consulta_wms) > 0){
            foreach ($consulta_wms as $key => $value) {

                //consultar estado actual
                $total_estado = $value->cantidad_estado;

                if($total_estado != 0){
                    $estado++;
                }

                //cantidades items y solicitadas
                $cantidad_item = $value->total_items;
                $cantidad_solicitada = $value->total_solicitada;

                //fechas convertidas
                $fecha_convertida_final = strtotime(date("Y-m-d H:i", strtotime($value->ultima_fecha_estado)));

                $fecha_1_inicial = strtotime(date("$fecha 06:00"));
                $fecha_1_final = strtotime(("$fecha 06:59"));

                $fecha_2_inicial = strtotime(date("$fecha 07:00"));
                $fecha_2_final = strtotime(("$fecha 07:59"));

                $fecha_3_inicial = strtotime(date("$fecha 8:00"));
                $fecha_3_final = strtotime(("$fecha 08:59"));

                $fecha_4_inicial = strtotime(date("$fecha 9:00"));
                $fecha_4_final = strtotime(("$fecha 09:59"));

                $fecha_5_inicial = strtotime(date("$fecha 10:00"));
                $fecha_5_final = strtotime(("$fecha 10:59"));

                $fecha_6_inicial = strtotime(date("$fecha 11:00"));
                $fecha_6_final = strtotime(("$fecha 11:59"));

                $fecha_7_inicial = strtotime(date("$fecha 12:00"));
                $fecha_7_final = strtotime(("$fecha 12:59"));

                $fecha_8_inicial = strtotime(date("$fecha 13:00"));
                $fecha_8_final = strtotime(("$fecha 13:59"));

                $fecha_9_inicial = strtotime(date("$fecha 14:00"));
                $fecha_9_final = strtotime(("$fecha 14:59"));

                $fecha_10_inicial = strtotime(date("$fecha 15:00"));
                $fecha_10_final = strtotime(("$fecha 15:59"));

                $fecha_11_inicial = strtotime(date("$fecha 16:00"));
                $fecha_11_final = strtotime(("$fecha 16:59"));

                $fecha_12_inicial = strtotime(date("$fecha 17:00"));
                $fecha_12_final = strtotime(("$fecha 17:59"));

                $fecha_13_inicial = strtotime(date("$fecha 18:00"));
                $fecha_13_final = strtotime(("$fecha 18:59"));

                $fecha_14_inicial = strtotime(date("$fecha 19:00"));
                $fecha_14_final = strtotime(("$fecha 19:59"));

                $fecha_15_inicial = strtotime(date("$fecha 20:00"));
                $fecha_15_final = strtotime(("$fecha 20:59"));

                $fecha_16_inicial = strtotime(date("$fecha 21:00"));
                $fecha_16_final = strtotime(("$fecha 21:59"));

                $fecha_17_inicial = strtotime(date("$fecha 22:00"));
                $fecha_17_final = strtotime(("$fecha 23:59"));

                if($this->fechas($fecha_convertida_final, $fecha_1_inicial, $fecha_1_final)){
                    $cantidad_fecha_1++;
                    $total_cantidad_items_1 += $cantidad_item;
                    $total_cantidad_solicitada_1 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_2_inicial, $fecha_2_final)){
                    $cantidad_fecha_2++;
                    $total_cantidad_items_2 += $cantidad_item;
                    $total_cantidad_solicitada_2 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_3_inicial, $fecha_3_final)){
                    $cantidad_fecha_3++;
                    $total_cantidad_items_3 += $cantidad_item;
                    $total_cantidad_solicitada_3 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_4_inicial, $fecha_4_final)){
                    $cantidad_fecha_4++;
                    $total_cantidad_items_4 += $cantidad_item;
                    $total_cantidad_solicitada_4 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_5_inicial, $fecha_5_final)){
                    $cantidad_fecha_5++;
                    $total_cantidad_items_5 += $cantidad_item;
                    $total_cantidad_solicitada_5 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_6_inicial, $fecha_6_final)){
                    $cantidad_fecha_6++;
                    $total_cantidad_items_6 += $cantidad_item;
                    $total_cantidad_solicitada_6 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_7_inicial, $fecha_7_final)){
                    $cantidad_fecha_7++;
                    $total_cantidad_items_7 += $cantidad_item;
                    $total_cantidad_solicitada_7 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_8_inicial, $fecha_8_final)){
                    $cantidad_fecha_8++;
                    $total_cantidad_items_8 += $cantidad_item;
                    $total_cantidad_solicitada_8 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_9_inicial, $fecha_9_final)){
                    $cantidad_fecha_9++;
                    $total_cantidad_items_9 += $cantidad_item;
                    $total_cantidad_solicitada_9 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_10_inicial, $fecha_10_final)){
                    $cantidad_fecha_10++;
                    $total_cantidad_items_10 += $cantidad_item;
                    $total_cantidad_solicitada_10 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_11_inicial, $fecha_11_final)){
                    $cantidad_fecha_11++;
                    $total_cantidad_items_11 += $cantidad_item;
                    $total_cantidad_solicitada_11 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_12_inicial, $fecha_12_final)){
                    $cantidad_fecha_12++;
                    $total_cantidad_items_12 += $cantidad_item;
                    $total_cantidad_solicitada_12 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_13_inicial, $fecha_13_final)){
                    $cantidad_fecha_13++;
                    $total_cantidad_items_13 += $cantidad_item;
                    $total_cantidad_solicitada_13 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_14_inicial, $fecha_14_final)){
                    $cantidad_fecha_14++;
                    $total_cantidad_items_14 += $cantidad_item;
                    $total_cantidad_solicitada_14 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_15_inicial, $fecha_15_final)){
                    $cantidad_fecha_15++;
                    $total_cantidad_items_15 += $cantidad_item;
                    $total_cantidad_solicitada_15 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_16_inicial, $fecha_16_final)){
                    $cantidad_fecha_16++;
                    $total_cantidad_items_16 += $cantidad_item;
                    $total_cantidad_solicitada_16 += $cantidad_solicitada;
                }
                if($this->fechas($fecha_convertida_final, $fecha_17_inicial, $fecha_17_final)){
                    $cantidad_fecha_17++;
                    $total_cantidad_items_17 += $cantidad_item;
                    $total_cantidad_solicitada_17 += $cantidad_solicitada;
                }

            }
        }

        $data_cantidad = array(
            'fecha_1' => $cantidad_fecha_1,
            'fecha_2' => $cantidad_fecha_2,
            'fecha_3' => $cantidad_fecha_3,
            'fecha_4' => $cantidad_fecha_4,
            'fecha_5' => $cantidad_fecha_5,
            'fecha_6' => $cantidad_fecha_6,
            'fecha_7' => $cantidad_fecha_7,
            'fecha_8' => $cantidad_fecha_8,
            'fecha_9' => $cantidad_fecha_9,
            'fecha_10' => $cantidad_fecha_10,
            'fecha_11' => $cantidad_fecha_11,
            'fecha_12' => $cantidad_fecha_12,
            'fecha_13' => $cantidad_fecha_13,
            'fecha_14' => $cantidad_fecha_14,
            'fecha_15' => $cantidad_fecha_15,
            'fecha_16' => $cantidad_fecha_16,
            'fecha_17' => $cantidad_fecha_17
        );

        $data_item = array(
            'item_1' => $total_cantidad_items_1,
            'item_2' => $total_cantidad_items_2,
            'item_3' => $total_cantidad_items_3,
            'item_4' => $total_cantidad_items_4,
            'item_5' => $total_cantidad_items_5,
            'item_6' => $total_cantidad_items_6,
            'item_7' => $total_cantidad_items_7,
            'item_8' => $total_cantidad_items_8,
            'item_9' => $total_cantidad_items_9,
            'item_10' => $total_cantidad_items_10,
            'item_11' => $total_cantidad_items_11,
            'item_12' => $total_cantidad_items_12,
            'item_13' => $total_cantidad_items_13,
            'item_14' => $total_cantidad_items_14,
            'item_15' => $total_cantidad_items_15,
            'item_16' => $total_cantidad_items_16,
            'item_17' => $total_cantidad_items_17
        );

        $data_solicitados = array(
            'solicitado_1' => $total_cantidad_solicitada_1,
            'solicitado_2' => $total_cantidad_solicitada_2,
            'solicitado_3' => $total_cantidad_solicitada_3,
            'solicitado_4' => $total_cantidad_solicitada_4,
            'solicitado_5' => $total_cantidad_solicitada_5,
            'solicitado_6' => $total_cantidad_solicitada_6,
            'solicitado_7' => $total_cantidad_solicitada_7,
            'solicitado_8' => $total_cantidad_solicitada_8,
            'solicitado_9' => $total_cantidad_solicitada_9,
            'solicitado_10' => $total_cantidad_solicitada_10,
            'solicitado_11' => $total_cantidad_solicitada_11,
            'solicitado_12' => $total_cantidad_solicitada_12,
            'solicitado_13' => $total_cantidad_solicitada_13,
            'solicitado_14' => $total_cantidad_solicitada_14,
            'solicitado_15' => $total_cantidad_solicitada_15,
            'solicitado_16' => $total_cantidad_solicitada_16,
            'solicitado_17' => $total_cantidad_solicitada_17
        );

        $retorno = array(
            'estado' => $estado_texto,
            'cantidad' => $estado,
            'data' => $data_cantidad,
            'data_items' => $data_item,
            'data_solicitada' => $data_solicitados
        );

        return $retorno;

    }

    public function fechas($fecha_1, $fecha_inicial, $fecha_final){

        if (($fecha_1 >= $fecha_inicial) && ($fecha_1 <= $fecha_final)){
            return true;
        } else {
            return false;
        }

    }

}
