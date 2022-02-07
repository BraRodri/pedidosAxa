<?php

namespace App\Http\Controllers;

use App\Models\CPDAPedidos;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EstadisticasController extends Controller
{
    //
    public function index()
    {
        $fecha = date('Y-m-d');

        //consultar todas las bodegas
        $consulta_bodegas = array();
        $result_bodegas = $this->obtenerBodegas();
        foreach ($result_bodegas as $key => $bodega_for) {
            array_push($consulta_bodegas, $bodega_for->bodega);
        }
        $resultado_bodegas = array_unique($consulta_bodegas);

        //clases
        $datos_clases = array();
        $clases = CPDAPedidos::select('clase')->get();
        foreach ($clases as $key => $value) {
            array_push($datos_clases, $value->clase);
        }
        $clases_retorno = array_unique($datos_clases);
        //dd($clases_retorno);

        return view('pages.estadisticas')->with([
            'fecha' => $fecha,
            'clases' => $clases_retorno,
            'resultado_bodegas' => $resultado_bodegas
        ]);
    }

    public function obtenerBodegas()
    {
        $datos = CPDAPedidos::select('bodega')->get();
        return $datos;
    }

    public function getPedidosClases(Request $request)
    {

        $error = false;
        $mensaje = '';

        $fecha = $request->fecha;
        $datos_clases = array();

        $datos_netsuite = CPDAPedidos::where([
            ['fecha', '=', $fecha]
        ])->get();

        if($datos_netsuite){

            foreach ($datos_netsuite as $key => $value) {
                array_push($datos_clases, $value->clase);
            }

            $labes = array_values(array_unique($datos_clases));
            $cantidades = array_values(array_count_values($datos_clases));

        } else {
            $error = true;
            $mensaje = 'Error! Se presento un problema al obtener datos, intenta de nuevo.';
        }

        return json_encode(array('error' => $error, 'mensaje' => $mensaje, "labes" => $labes, "cantidades" => $cantidades));

    }

    public function getPedidosClasePorHoras(Request $request)
    {
        $error = false;
        $mensaje = '';

        $fecha = $request->fecha;
        $clases = $request->clase;

        $datos_clases = array();

        foreach ($clases as $key => $clase_for) {

            $datos_netsuite = CPDAPedidos::where([
                ['fecha', '=', $fecha],
                ['clase', '=', $clase_for]
            ])->get();

            if($datos_netsuite){

                $cantida_1 = 0;
                $cantida_2 = 0;
                $cantida_3 = 0;
                $cantida_4 = 0;
                $cantida_5 = 0;
                $cantida_6 = 0;
                $cantida_7 = 0;
                $cantida_8 = 0;
                $cantida_9 = 0;

                $fecha_1_inicial = strtotime(date("$fecha 00:00"));
                $fecha_1_final = strtotime(("$fecha 05:59"));

                $fecha_2_inicial = strtotime(date("$fecha 06:00"));
                $fecha_2_final = strtotime(("$fecha 07:59"));

                $fecha_3_inicial = strtotime(date("$fecha 08:00"));
                $fecha_3_final = strtotime(("$fecha 09:59"));

                $fecha_4_inicial = strtotime(date("$fecha 10:00"));
                $fecha_4_final = strtotime(("$fecha 11:59"));

                $fecha_5_inicial = strtotime(date("$fecha 12:00"));
                $fecha_5_final = strtotime(("$fecha 13:59"));

                $fecha_6_inicial = strtotime(date("$fecha 14:00"));
                $fecha_6_final = strtotime(("$fecha 15:59"));

                $fecha_7_inicial = strtotime(date("$fecha 16:00"));
                $fecha_7_final = strtotime(("$fecha 17:59"));

                $fecha_8_inicial = strtotime(date("$fecha 18:00"));
                $fecha_8_final = strtotime(("$fecha 19:59"));

                $fecha_9_inicial = strtotime(date("$fecha 20:00"));
                $fecha_9_final = strtotime(("$fecha 23:59"));

                foreach ($datos_netsuite as $key => $value) {

                    $fecha_convertida_aprobacion = strtotime(date("Y-m-d H:i", strtotime($this->savefecha($value->fecaprov))));

                    $cantida_1 += $this->fechas($fecha_convertida_aprobacion, $fecha_1_inicial, $fecha_1_final);
                    $cantida_2 += $this->fechas($fecha_convertida_aprobacion, $fecha_2_inicial, $fecha_2_final);
                    $cantida_3 += $this->fechas($fecha_convertida_aprobacion, $fecha_3_inicial, $fecha_3_final);
                    $cantida_4 += $this->fechas($fecha_convertida_aprobacion, $fecha_4_inicial, $fecha_4_final);
                    $cantida_5 += $this->fechas($fecha_convertida_aprobacion, $fecha_5_inicial, $fecha_5_final);
                    $cantida_6 += $this->fechas($fecha_convertida_aprobacion, $fecha_6_inicial, $fecha_6_final);
                    $cantida_7 += $this->fechas($fecha_convertida_aprobacion, $fecha_7_inicial, $fecha_7_final);
                    $cantida_8 += $this->fechas($fecha_convertida_aprobacion, $fecha_8_inicial, $fecha_8_final);
                    $cantida_9 += $this->fechas($fecha_convertida_aprobacion, $fecha_9_inicial, $fecha_9_final);

                    ///$mensaje .= date("$fecha 06:00") . '<br>' . date("Y-m-d H:i", strtotime($this->savefecha($value->fecaprov))) . '<br>';

                }

                $cantidades = array(
                    $cantida_1,
                    $cantida_2,
                    $cantida_3,
                    $cantida_4,
                    $cantida_5,
                    $cantida_6,
                    $cantida_7,
                    $cantida_8,
                    $cantida_9
                );

                $data_retorno = array(
                    'nombre' => $clase_for,
                    'cantidades' => $cantidades
                );

                array_push($datos_clases, $data_retorno);

            } else {
                $error = true;
                $mensaje = 'Error! Se presento un problema al obtener datos, intenta de nuevo.';
            }

        }

        $labels = array(
            '00:00 a 05:59',
            '06:00 a 07:59',
            '08:00 a 09:59',
            '10:00 a 11:59',
            '12:00 a 13:59',
            '14:00 a 15:59',
            '16:00 a 17:59',
            '18:00 a 19:59',
            '20:00 a 23:59'
        );

        return json_encode(array('error' => $error, 'mensaje' => $mensaje, "retorno" => $datos_clases, 'labels' => $labels));
    }

    public function getPedidosPorFechas(Request $request)
    {
        $error = false;
        $mensaje = '';

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        $cantidades = array();

        $labes = $this->generateDateRange($fecha_inicio, $fecha_fin);
        foreach ($labes as $key => $value) {

            $datos_netsuite = CPDAPedidos::where('fecha', $value)->count();
            array_push($cantidades, $datos_netsuite);

        }

        return json_encode(array('error' => $error, 'mensaje' => $mensaje, "labes" => $labes, "cantidades" => $cantidades));
    }

    public function getPedidosPorBodegas(Request $request)
    {
        $bodegas = $request->bodega_select;
        $fecha_inicial = $request->fecha_inicio;
        $fecha_final = $request->fecha_fin;

        $error = false;
        $mensaje = '';

        $datos_clases = array();

        $array_dentro = array();

        $labes = $this->generateDateRange($fecha_inicial, $fecha_final);
        foreach ($bodegas as $key => $bodega_for) {

            $cantidades = array();
            foreach ($labes as $key => $value) {

                $datos_netsuite = CPDAPedidos::where([
                    ['bodega', '=', $bodega_for],
                    ['fecha', '=', $value]
                ])->count();

                $cantidades[] = $datos_netsuite;

            }

            $return = array(
                'nombre' => $bodega_for,
                'cantidades' => $cantidades
            );

            $datos_clases[] = $return;

        }

        //array_push($datos_clases, $array_dentro);

        return json_encode(array('error' => $error, 'mensaje' => $mensaje, "retorno" => $datos_clases, 'labels' => $labes));

    }

    function savefecha($vfecha)
    {
        $fch=explode("/",$vfecha);
        $separar = explode(" ",$fch[2]);
        $tfecha=$separar[0]."/".$fch[1]."/".$fch[0].' '.date("H:i", strtotime($separar[1] . ' ' . $separar[2]));
        return $tfecha;
    }

    public function generateDateRange($start_date, $end_date)
    {
        $dates = [];

        $period = CarbonPeriod::create($start_date, $end_date);
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    public function fechas($fecha_1, $fecha_inicial, $fecha_final)
    {

        if (($fecha_1 >= $fecha_inicial) && ($fecha_1 <= $fecha_final)){
            return 1;
        }

    }

}
