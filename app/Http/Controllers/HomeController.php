<?php

namespace App\Http\Controllers;

use App\Models\CPDAPedidos;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function __construct()
    {
        //tiempo local
        date_default_timezone_set('America/Bogota');
        set_time_limit(8000000);
    }

    public function index(Request $request)
    {

        $fecha = $request->fecha;
        if(empty($fecha)){
            $fecha = date('Y-m-d');
        }

        $bodega = $request->bodega_select;
        if(empty($bodega)){
            $bodega = 'todas';
        }

        //arrays
        $array_datos = array();
        $consulta_estados = array();
        $array_numdocs = array();
        $consulta_bodegas = array();

        //consultar todas las bodegas
        $result_bodegas = $this->obtenerBodegas();
        foreach ($result_bodegas as $key => $bodega_for) {
            array_push($consulta_bodegas, $bodega_for->bodega);
        }

        //datos obtenidos de Netsuit
        $datos_netsuit = $this->consultarDatosNetsuit($fecha, $bodega);
        foreach ($datos_netsuit as $key => $value) {
            array_push($consulta_estados,  $value->estado_combinado);
            array_push($array_numdocs,  "'" . $value->numdoc. "'");
        }

        //datos obtenidos de WMS
        $numeros_referencias_separados = implode("," ,$array_numdocs);
        $datos_wms = $this->consultarWMS($numeros_referencias_separados);

        $resultado_estados = array_unique($consulta_estados);
        $resultado_cont_estads = array_count_values($consulta_estados);
        $resultado_bodegas = array_unique($consulta_bodegas);

        array_push($array_datos, $resultado_estados);
        array_push($array_datos, $resultado_cont_estads);

        $cantidad_netsuit = count($datos_netsuit);
        $cantidad_wms = $datos_wms;

        return view('pages.home')->with([
            'fecha' => $fecha,
            'array_datos' => $array_datos,
            'cantidad_netsuit' => $cantidad_netsuit,
            'cantidad_wms' => $cantidad_wms,
            'resultado_bodegas' => $resultado_bodegas,
            'bodega' => $bodega
        ]);

    }

    public function consultarWMS($nums_agrupados)
    {
        if(!empty($nums_agrupados)){
            $sql = "SELECT salidas.documento_referencia FROM salidas
            WHERE salidas.documento_referencia IN ($nums_agrupados)";
            $consulta_wms = DB::connection('pgsql2')->select($sql);
            return count($consulta_wms);
        } else {
            return 0;
        }

    }

    public function consultarDatosNetsuit($fecha, $bodega)
    {
        if($bodega == 'todas'){
            $datos = CPDAPedidos::where('fecha', $fecha)->get();
        } else {
            $datos = CPDAPedidos::where([
                ['bodega', '=', $bodega],
                ['fecha', '=', $fecha],
            ])->get();
        }

        return $datos;
    }

    public function consultar($id)
    {

        $imprimir = "";
        $imprimir_2 = "";

        $data = CPDAPedidos::where('numdoc', $id)->first();
        if($data){

            $imprimir .= "
                <h5 class='text-primary'>Datos Netsuit:</h5><hr>
                <h5><strong>Numero de Referencia:</strong> ". $id ."</h5>
                <div class='row'>
                    <div class='col-12'>
                        <ul>
                            <li>
                                <strong>Horas desde Aprobación:</strong> " . $data['horas_apro'] . "
                            </li>
                            <li>
                                <strong>Fecha Aprobación:</strong> " . $data['fecaprov'] . "
                            </li>
                            <li>
                                <strong>Quien cae venta:</strong> " . $data['quien_cae_venta'] . "
                            </li>
                            <li>
                                <strong>Estado:</strong> " . $data['estado_combinado'] . "
                            </li>
                            <li>
                                <strong>Bodega:</strong> " . $data['bodega'] . "
                            </li>
                            <li>
                                <strong>Metodo de Pago:</strong> " . $data['metodo_pago'] . "
                            </li>
                            <li>
                                <strong>Total:</strong> " . $data['total'] . "
                            </li>
                            <li>
                                <strong>Cliente:</strong> " . $data['cliente'] . "
                            </li>
                            <li>
                                <strong>Control WMS:</strong> " . $data['control_wms'] . "
                            </li>
                            <li>
                                <strong>Bodega Destino:</strong> " . $data['bodega_destino'] . "
                            </li>
                            <li>
                                <strong>Rol:</strong> " . $data['rol'] . "
                            </li>
                            <li>
                                <strong>Origen:</strong> " . $data['origen'] . "
                            </li>
                        </ul>
                    </div>
                    <div class='col-6'>
                        ".$imprimir_2."
                    </div>
                </div>

            ";

            if($data->control_wms == 1){

                //consulta para wms
                $sql_1 = "SELECT salidas.punto,
                    salidas.salida,
                    salidas.tipo_documento_referencia,
                    salidas.documento_referencia,
                    salidas.identificacion,
                    salidas.secuencia,
                    salidas.fecha_insercion,
                    salidas.fecha_final,
                    salidas.total_items,
                    salidas.total_solicitada,
                    salidas.total_anulada,
                    salidas.total_por_enrutar,
                    salidas.cantidad_ruta,
                    primeraruta(salidas.punto, salidas.salida) as primera_ruta,
                    ultimaruta(salidas.punto, salidas.salida) as ultima_ruta,
                    salidas.total_por_alistar,
                    salidas.total_alistada,
                    primeralistamiento(salidas.punto, salidas.salida) as primer_alistamiento,
                    ultimoalistamiento(salidas.punto, salidas.salida) as ultimo_alistamiento,
                    salidas.total_por_despachar,
                    salidas.total_despachada,
                    primerdespacho(salidas.punto, salidas.salida) as primer_despacho,
                    ultimodespacho(salidas.punto, salidas.salida) as ultimo_despacho,
                    salidas.total_por_entregar,
                    salidas.total_entregada,
                    primeraentrega(salidas.punto, salidas.salida) as primera_entrega,
                    ultimaentrega(salidas.punto, salidas.salida) as ultima_entrega
                    FROM salidas
                    WHERE salidas.documento_referencia = '".$id."'"
                ;

                $consulta_wms = DB::connection('pgsql2')->select($sql_1);
                if(count($consulta_wms) > 0){

                    $fecha_insercion_wms = $consulta_wms[0]->fecha_insercion;
                    $fecha_final_wms = $consulta_wms[0]->ultima_entrega;

                    if(!empty($data['fecha_fulfill'])){

                        $fecha_convertida_fulfillment = date("Y-m-d H:i", strtotime($this->savefecha($data['fecha_fulfill'])));
                        $fecha_final_convertida_wms = date("Y-m-d H:i", strtotime($fecha_final_wms));
                        $tiempo_wms_a_netsuite = $this->obtenerMinutosHoras($fecha_final_convertida_wms, $fecha_convertida_fulfillment);

                        $imprimir .= "
                            <ul>
                                <li>
                                    <strong>Fecha final WMS:</strong> " . $fecha_final_wms . "
                                </li>
                                <li>
                                    <strong>Fecha Fulfillment:</strong> " . $data['fecha_fulfill'] . "
                                </li>
                                <li>
                                    <strong>Tiempo:</strong> " . $tiempo_wms_a_netsuite . "
                                </li>
                            </ul>
                        ";

                    }

                    $factura_data = Factura::where('num_pedido', $data->numdoc)->first();
                    if($factura_data){

                        if(!empty($factura_data['fecha_creacion_factura'])){
                            $fecha_convertida_fulfillment = date("Y-m-d H:i", strtotime($this->savefecha($data['fecha_fulfill'])));
                            $fecha_final_convertida_factura = date("Y-m-d H:i", strtotime($this->savefecha($factura_data['fecha_creacion_factura'])));
                            $tiempo_factura = $this->obtenerMinutosHoras($fecha_convertida_fulfillment, $fecha_final_convertida_factura);

                            $fecha_convertida_aprobacion = date("Y-m-d H:i", strtotime($this->savefecha($data['fecaprov'])));
                            $fecha_final_convertida_factura = date("Y-m-d H:i", strtotime($this->savefecha($factura_data['fecha_creacion_factura'])));
                            $tiempo_final = $this->obtenerMinutosHoras($fecha_convertida_aprobacion, $fecha_final_convertida_factura);
                        } else {
                            $tiempo_factura = '0';
                            $tiempo_final = '0';
                        }

                        $imprimir .= "
                            <ul>
                                <li>
                                    <strong>Numero Facura:</strong> " . $factura_data['id_factura'] . "
                                </li>
                                <li>
                                    <strong>Fecha:</strong> " . $factura_data['fecha'] . "
                                </li>
                                <li>
                                    <strong>Fecha Creación:</strong> " . $factura_data['fecha_creacion_factura'] . "
                                </li>
                                <li>
                                    <strong>Tiempo Fulfillment a Factura:</strong> " . $tiempo_factura . "
                                </li>
                            </ul>


                            <ul>
                                <li>
                                    <strong>Tiempo desde aprobación a factura:</strong> " . $tiempo_final . "
                                </li>
                            </ul>
                        ";

                    }


                    //calcular tiempo de netsuit a wms
                    $fecha_convertida_netsuit = date("Y-m-d H:i", strtotime($this->savefecha($data['fecaprov'])));
                    $fecha_convertida_wms = date("Y-m-d H:i", strtotime($fecha_insercion_wms));
                    $minutos_netsuit_wms = $this->minutosTranscurridos($fecha_convertida_netsuit, $fecha_convertida_wms);
                    if($minutos_netsuit_wms > 60){
                        $horas_netsuit_wms = $minutos_netsuit_wms / 60;
                        $tiempo_netsuit_wms = round($horas_netsuit_wms, 2) . ' horas.';
                    } else {
                        $tiempo_netsuit_wms = $minutos_netsuit_wms . ' minutos.';
                    }

                    //consultar estado actual
                    $estado_actual = 'En ruta';
                    $total_ruta = $consulta_wms[0]->cantidad_ruta;
                    $total_alistada = $consulta_wms[0]->total_alistada;
                    $total_despachada = $consulta_wms[0]->total_despachada;
                    $total_entregada = $consulta_wms[0]->total_entregada;

                    if($total_ruta == 0){
                        $estado_actual = 'En ruta';
                    } elseif ($total_alistada == 0) {
                        $estado_actual = 'En alistamiento';
                    } elseif ($total_despachada == 0) {
                        $estado_actual = 'En despacho';
                    } elseif ($total_entregada == 0) {
                        $estado_actual = 'En entrega';
                    } else {
                        $estado_actual = 'Finalizado';
                    }

                    //fechas convertidas
                    $fecha_convertida_inicio_ruta = date("Y-m-d H:i", strtotime($consulta_wms[0]->primera_ruta));
                    $fecha_convertida_final_ruta = date("Y-m-d H:i", strtotime($consulta_wms[0]->ultima_ruta));

                    $fecha_convertida_inicio_alistamiento = date("Y-m-d H:i", strtotime($consulta_wms[0]->primer_alistamiento));
                    $fecha_convertida_final_alistamiento = date("Y-m-d H:i", strtotime($consulta_wms[0]->ultimo_alistamiento));

                    $fecha_convertida_inicio_despacho = date("Y-m-d H:i", strtotime($consulta_wms[0]->primer_despacho));
                    $fecha_convertida_final_despacho = date("Y-m-d H:i", strtotime($consulta_wms[0]->ultimo_despacho));

                    $fecha_convertida_inicio_entrega = date("Y-m-d H:i", strtotime($consulta_wms[0]->primera_entrega));
                    $fecha_convertida_final_entrega = date("Y-m-d H:i", strtotime($consulta_wms[0]->ultima_entrega));

                    //estado en ruta
                    $estado_ruta = '';
                    if($total_ruta != 0){
                        $tiempo_proceso = $this->obtenerMinutosHoras($fecha_convertida_wms, $fecha_convertida_inicio_ruta);
                        $tiempo_ruta = $this->obtenerMinutosHoras($fecha_convertida_inicio_ruta, $fecha_convertida_final_ruta);
                        $estado_ruta = 'Finalizado
                        <br>Tiempo del proceso anterior al actual: '.$tiempo_proceso.'
                        <br>Fecha Inicio: '.$fecha_convertida_inicio_ruta.'
                        <br>Fecha Final: '.$fecha_convertida_final_ruta.'
                        <br>Tiempo de ejecución: ' . $tiempo_ruta;
                    }

                    //estado en alistamiento
                    $estado_alistamiento = '';
                    if($total_alistada != 0){
                        $tiempo_proceso = $this->obtenerMinutosHoras($fecha_convertida_final_ruta, $fecha_convertida_inicio_alistamiento);
                        $tiempo_alistamiento = $this->obtenerMinutosHoras($fecha_convertida_inicio_alistamiento, $fecha_convertida_final_alistamiento);
                        $estado_alistamiento = 'Finalizado
                        <br>Tiempo del proceso anterior al actual: '.$tiempo_proceso.'
                        <br>Fecha Inicio: '.$fecha_convertida_inicio_alistamiento.'
                        <br>Fecha Final: '.$fecha_convertida_final_alistamiento.'
                        <br>Tiempo de ejecución: ' . $tiempo_alistamiento;
                    }

                    //estado en despacho
                    $estado_despacho = '';
                    if($total_despachada != 0){
                        $tiempo_proceso = $this->obtenerMinutosHoras($fecha_convertida_final_alistamiento, $fecha_convertida_inicio_despacho);
                        $tiempo_despacho = $this->obtenerMinutosHoras($fecha_convertida_inicio_despacho, $fecha_convertida_final_despacho);
                        $estado_despacho = 'Finalizado
                        <br>Tiempo del proceso anterior al actual: '.$tiempo_proceso.'
                        <br>Fecha Inicio: '.$fecha_convertida_inicio_despacho.'
                        <br>Fecha Final: '.$fecha_convertida_final_despacho.'
                        <br>Tiempo de ejecución: ' . $tiempo_despacho;
                    }

                    //estado en despacho
                    $estado_entrega = '';
                    if($total_entregada != 0){
                        $tiempo_proceso = $this->obtenerMinutosHoras($fecha_convertida_final_despacho, $fecha_convertida_inicio_entrega);
                        $tiempo_entrega = $this->obtenerMinutosHoras($fecha_convertida_inicio_entrega, $fecha_convertida_final_entrega);
                        $estado_entrega = 'Finalizado
                        <br>Tiempo del proceso anterior al actual: '.$tiempo_proceso.'
                        <br>Fecha Inicio: '.$fecha_convertida_inicio_entrega.'
                        <br>Fecha Final: '.$fecha_convertida_final_entrega.'
                        <br>Tiempo de ejecución: ' . $tiempo_entrega;
                    }

                    //validar si tiene productos anulados
                    $tabla_productos_anulados = '';
                    $productos_anulados = "<br><br><h5><strong>Información Productos:</strong></h5>
                            <p>
                                <strong>Cantidad items:</strong> ".$consulta_wms[0]->total_items." <br>
                                <strong>Cantidad solicitada:</strong> ".$consulta_wms[0]->total_solicitada."
                        ";

                    $total_anulados = $consulta_wms[0]->total_anulada;
                    if($total_anulados != 0){

                        $productos_anulados .= "
                            <br><strong>Cantidad anulada:</strong> ".$consulta_wms[0]->total_anulada."</p>
                        ";

                    } else {
                        $productos_anulados .= "
                            </p>
                        ";
                    }

                        $sql_2_prod = "SELECT salidas.punto,
                            salidas.salida,
                            detalles_salidas.articulo,
                            articulos.nombre,
                            detalles_salidas.cantidad_solicitada,
                            detalles_salidas.cantidad,
                            detalles_salidas.cantidad_ruta,
                            detalles_salidas.cantidad_por_enrutar,
                            detalles_salidas.cantidad_alistada,
                            detalles_salidas.cantidad_por_alistar,
                            detalles_salidas.cantidad_despachada,
                            detalles_salidas.cantidad_por_despachar,
                            detalles_salidas.cantidad_empacada,
                            detalles_salidas.cantidad_entregada,
                            detalles_salidas.cantidad_por_entregar,
                            detalles_salidas.cantidad_anulada
                            FROM salidas,
                            detalles_salidas, articulos
                            WHERE salidas.punto = detalles_salidas.punto
                            AND salidas.salida = detalles_salidas.salida
                            AND articulos.articulo = detalles_salidas.articulo
                            AND salidas.salida = '".$consulta_wms[0]->salida."'
                        ";

                        $consulta_wms_productos = DB::connection('pgsql2')->select($sql_2_prod);
                        //var_dump($consulta_wms[0]->punto);
                        if(count($consulta_wms_productos) > 0){

                            $tbody_tabla_productos = '';

                            foreach ($consulta_wms_productos as $key_produ => $value_productos) {
                                $tbody_tabla_productos .= '
                                <tr>
                                    <td>'.$value_productos->articulo.'</td>
                                    <td>'.$value_productos->nombre.'</td>
                                    <td>'.$value_productos->cantidad.'</td>
                                    <td>'.$value_productos->cantidad_solicitada.'</td>
                                    <td>'.$value_productos->cantidad_anulada.'</td>
                                    <td>'.$value_productos->cantidad_ruta.'</td>
                                    <td>'.$value_productos->cantidad_alistada.'</td>
                                    <td>'.$value_productos->cantidad_despachada.'</td>
                                    <td>'.$value_productos->cantidad_empacada.'</td>
                                    <td>'.$value_productos->cantidad_entregada.'</td>
                                </tr>';
                            }

                            $tabla_productos_anulados .= '
                                <h5 class="pt-4"><strong>Tabla Productos</strong></h5>
                                <table id="tabla_productos_anulados" class="table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Articulo</th>
                                            <th>Nombre</th>
                                            <th>Cant</th>
                                            <th>Cant Solicitada</th>
                                            <th>Cant Anulada</th>
                                            <th>Cant Ruta</th>
                                            <th>Cant Alistada</th>
                                            <th>Cant Despachada</th>
                                            <th>Cant Empacada</th>
                                            <th>Cant Entregada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        '.$tbody_tabla_productos.'
                                    </tbody>
                                </table>
                            ';
                        }

                    $imprimir .= "
                        <h5 class='text-primary pt-3'>Datos WMS:</h5><hr>
                        <div class='row'>
                            <div class='col-5'>
                                <h5><strong>Información principal:</strong></h5>
                                <ul>
                                    <li><strong>Punto:</strong> ".$consulta_wms[0]->punto."</li>
                                    <li><strong>Numero de Salida:</strong> ".$consulta_wms[0]->salida."</li>
                                    <li><strong>Tipo Documento Referencia:</strong> ".$consulta_wms[0]->tipo_documento_referencia."</li>
                                    <li><strong>Numero identificación:</strong> ".$consulta_wms[0]->identificacion."</li>
                                    <li><strong>Num Secuencia:</strong> ".$consulta_wms[0]->secuencia."</li>
                                </ul>

                                ".$productos_anulados."

                            </div>
                            <div class='col-7'>
                                <h5><strong>Información Estados:</strong></h5>
                                <ul>
                                    <li><strong>Estado Actual:</strong> ".$estado_actual."</li>
                                    <li><strong>Fecha Inserción:</strong> ".$consulta_wms[0]->fecha_insercion."</li>
                                    <li><strong>Tiempo de Netsuit a WMS:</strong> ".$tiempo_netsuit_wms."</li>
                                    <li><strong>En Ruta:</strong> ".$estado_ruta."</li>
                                    <li><strong>En Alistamiento:</strong> ".$estado_alistamiento."</li>
                                    <li><strong>En Despacho:</strong> ".$estado_despacho."</li>
                                    <li><strong>En Entrega:</strong> ".$estado_entrega."</li>
                                </ul>
                            </div>
                            <div class='col-12'>
                                ".$tabla_productos_anulados."
                            </div>
                        </div>
                    ";

                }

            } else {

                if(!empty($data['fecha_fulfill'])){
                    $imprimir .= "
                        <ul>
                            <li>
                                <strong>Fecha Fulfillment:</strong> " . $data['fecha_fulfill'] . "
                            </li>
                        </ul>
                    ";
                }

                $factura_data = Factura::where('num_pedido', $data->numdoc)->first();
                if($factura_data){

                    if(!empty($factura_data['fecha_creacion_factura'])){
                        $fecha_convertida_fulfillment = date("Y-m-d H:i", strtotime($this->savefecha($data['fecha_fulfill'])));
                        $fecha_final_convertida_factura = date("Y-m-d H:i", strtotime($this->savefecha($factura_data['fecha_creacion_factura'])));
                        $tiempo_factura = $this->obtenerMinutosHoras($fecha_convertida_fulfillment, $fecha_final_convertida_factura);

                        $fecha_convertida_aprobacion = date("Y-m-d H:i", strtotime($this->savefecha($data['fecaprov'])));
                        $fecha_final_convertida_factura = date("Y-m-d H:i", strtotime($this->savefecha($factura_data['fecha_creacion_factura'])));
                        $tiempo_final = $this->obtenerMinutosHoras($fecha_convertida_aprobacion, $fecha_final_convertida_factura);
                    } else {
                        $tiempo_factura = '0';
                        $tiempo_final = '0';

                    }

                    if(!empty($factura_data['fecha_respuesta_dian'])){
                        $fecha_convertida_factura = date("Y-m-d H:i", strtotime($this->savefecha($factura_data['fecha_creacion_factura'])));
                        $fecha_final_convertida_dian = date("Y-m-d H:i", strtotime($this->savefecha($factura_data['fecha_respuesta_dian'])));
                        $tiempo_respuesta_dian = $this->obtenerMinutosHoras($fecha_convertida_factura, $fecha_final_convertida_dian);
                    } else {
                        $tiempo_respuesta_dian = '0';
                    }

                    $imprimir .= "
                        <ul>
                            <li>
                                <strong>Numero Facura:</strong> " . $factura_data['id_factura'] . "
                            </li>
                            <li>
                                <strong>Fecha:</strong> " . $factura_data['fecha'] . "
                            </li>
                            <li>
                                <strong>Fecha Creación factura:</strong> " . $factura_data['fecha_creacion_factura'] . "
                            </li>
                            <li>
                                <strong>Tiempo Fulfillment a Factura:</strong> " . $tiempo_factura . "
                            </li>
                            <li>
                                <strong>Fecha respuesta Dian:</strong> " . $factura_data['fecha_respuesta_dian'] . "
                            </li>
                            <li>
                                <strong>Tiempo respuesta Dian:</strong> " . $tiempo_respuesta_dian . "
                            </li>
                        </ul>

                        <ul>
                            <li>
                                <strong>Tiempo desde aprobación a factura:</strong> " . $tiempo_final . "
                            </li>
                        </ul>
                    ";

                }

            }



        }

        /*
        $data = $this->consultarDatosNetsuit();
        foreach ($data as $key => $value) {
            if($value['numdoc'] == $id){
                $imprimir .= "
                    <h5 class='text-primary'>Datos Netsuit:</h5><hr>
                    <h5><strong>Numero de Referencia:</strong> ". $id ."</h5>
                    <ul>
                        </li>
                        <li>
                            <strong>Horas desde Aprobación:</strong> " . $value['horas_pro'] . "
                        </li>
                        <li>
                            <strong>Fecha Aprobación:</strong> " . $value['fecaprov'] . "
                        </li>
                        <li>
                            <strong>Quien cae venta:</strong> " . $value['quien_cae_venta'] . "
                        </li>
                        <li>
                            <strong>Estado:</strong> " . $value['estado_combinado'] . "
                        </li>
                        <li>
                            <strong>Bodega:</strong> " . $value['bodega'] . "
                        </li>
                        <li>
                            <strong>Metodo de Pago:</strong> " . $value['metodo_pago'] . "
                        </li>
                        <li>
                            <strong>Total:</strong> " . $value['total'] . "
                        </li>
                        <li>
                            <strong>Cliente:</strong> " . $value['cliente'] . "
                        </li>
                        <li>
                            <strong>Control WMS:</strong> " . $value['control_wms'] . "
                        </li>
                        <li>
                            <strong>Bodega Destino:</strong> " . $value['bodega_destino'] . "
                        </li>
                    </ul>
                ";

                //consulta para wms
                $sql_1 = "SELECT salidas.punto,
                    salidas.salida,
                    salidas.tipo_documento_referencia,
                    salidas.documento_referencia,
                    salidas.identificacion,
                    salidas.secuencia,
                    salidas.fecha_insercion,
                    salidas.fecha_final,
                    salidas.total_items,
                    salidas.total_solicitada,
                    salidas.total_anulada,
                    salidas.total_por_enrutar,
                    salidas.cantidad_ruta,
                    primeraruta(salidas.punto, salidas.salida) as primera_ruta,
                    ultimaruta(salidas.punto, salidas.salida) as ultima_ruta,
                    salidas.total_por_alistar,
                    salidas.total_alistada,
                    primeralistamiento(salidas.punto, salidas.salida) as primer_alistamiento,
                    ultimoalistamiento(salidas.punto, salidas.salida) as ultimo_alistamiento,
                    salidas.total_por_despachar,
                    salidas.total_despachada,
                    primerdespacho(salidas.punto, salidas.salida) as primer_despacho,
                    ultimodespacho(salidas.punto, salidas.salida) as ultimo_despacho,
                    salidas.total_por_entregar,
                    salidas.total_entregada,
                    primeraentrega(salidas.punto, salidas.salida) as primera_entrega,
                    ultimaentrega(salidas.punto, salidas.salida) as ultima_entrega
                    FROM salidas
                    WHERE salidas.documento_referencia = '".$id."'";

                $consulta_wms = DB::connection('pgsql2')->select($sql_1);
                //var_dump($consulta_wms[0]->punto);
                if(count($consulta_wms) > 0){

                    $fecha_insercion_wms = $consulta_wms[0]->fecha_insercion;

                    //calcular tiempo de netsuit a wms
                    $fecha_convertida_netsuit = date("Y-m-d H:i", strtotime($this->savefecha($value['fecaprov'])));
                    $fecha_convertida_wms = date("Y-m-d H:i", strtotime($fecha_insercion_wms));
                    $minutos_netsuit_wms = $this->minutosTranscurridos($fecha_convertida_netsuit, $fecha_convertida_wms);
                    if($minutos_netsuit_wms > 60){
                        $horas_netsuit_wms = $minutos_netsuit_wms / 60;
                        $tiempo_netsuit_wms = round($horas_netsuit_wms, 2) . ' horas.';
                    } else {
                        $tiempo_netsuit_wms = $minutos_netsuit_wms . ' minutos.';
                    }

                    //consultar estado actual
                    $estado_actual = 'En ruta';
                    $total_ruta = $consulta_wms[0]->cantidad_ruta;
                    $total_alistada = $consulta_wms[0]->total_alistada;
                    $total_despachada = $consulta_wms[0]->total_despachada;
                    $total_entregada = $consulta_wms[0]->total_entregada;

                    if($total_ruta == 0){
                        $estado_actual = 'En ruta';
                    } elseif ($total_alistada == 0) {
                        $estado_actual = 'En alistamiento';
                    } elseif ($total_despachada == 0) {
                        $estado_actual = 'En despacho';
                    } elseif ($total_entregada == 0) {
                        $estado_actual = 'En entrega';
                    } else {
                        $estado_actual = 'Finalizado';
                    }

                    //fechas convertidas
                    $fecha_convertida_inicio_ruta = date("Y-m-d H:i", strtotime($consulta_wms[0]->primera_ruta));
                    $fecha_convertida_final_ruta = date("Y-m-d H:i", strtotime($consulta_wms[0]->ultima_ruta));

                    $fecha_convertida_inicio_alistamiento = date("Y-m-d H:i", strtotime($consulta_wms[0]->primer_alistamiento));
                    $fecha_convertida_final_alistamiento = date("Y-m-d H:i", strtotime($consulta_wms[0]->ultimo_alistamiento));

                    $fecha_convertida_inicio_despacho = date("Y-m-d H:i", strtotime($consulta_wms[0]->primer_despacho));
                    $fecha_convertida_final_despacho = date("Y-m-d H:i", strtotime($consulta_wms[0]->ultimo_despacho));

                    $fecha_convertida_inicio_entrega = date("Y-m-d H:i", strtotime($consulta_wms[0]->primera_entrega));
                    $fecha_convertida_final_entrega = date("Y-m-d H:i", strtotime($consulta_wms[0]->ultima_entrega));

                    //estado en ruta
                    $estado_ruta = '';
                    if($total_ruta != 0){
                        $tiempo_proceso = $this->obtenerMinutosHoras($fecha_convertida_wms, $fecha_convertida_inicio_ruta);
                        $tiempo_ruta = $this->obtenerMinutosHoras($fecha_convertida_inicio_ruta, $fecha_convertida_final_ruta);
                        $estado_ruta = 'Finalizado
                        <br>Tiempo del proceso anterior al actual: '.$tiempo_proceso.'
                        <br>Fecha Inicio: '.$fecha_convertida_inicio_ruta.'
                        <br>Fecha Final: '.$fecha_convertida_final_ruta.'
                        <br>Tiempo de ejecución: ' . $tiempo_ruta;
                    }

                    //estado en alistamiento
                    $estado_alistamiento = '';
                    if($total_alistada != 0){
                        $tiempo_proceso = $this->obtenerMinutosHoras($fecha_convertida_final_ruta, $fecha_convertida_inicio_alistamiento);
                        $tiempo_alistamiento = $this->obtenerMinutosHoras($fecha_convertida_inicio_alistamiento, $fecha_convertida_final_alistamiento);
                        $estado_alistamiento = 'Finalizado
                        <br>Tiempo del proceso anterior al actual: '.$tiempo_proceso.'
                        <br>Fecha Inicio: '.$fecha_convertida_inicio_alistamiento.'
                        <br>Fecha Final: '.$fecha_convertida_final_alistamiento.'
                        <br>Tiempo de ejecución: ' . $tiempo_alistamiento;
                    }

                    //estado en despacho
                    $estado_despacho = '';
                    if($total_despachada != 0){
                        $tiempo_proceso = $this->obtenerMinutosHoras($fecha_convertida_final_alistamiento, $fecha_convertida_inicio_despacho);
                        $tiempo_despacho = $this->obtenerMinutosHoras($fecha_convertida_inicio_despacho, $fecha_convertida_final_despacho);
                        $estado_despacho = 'Finalizado
                        <br>Tiempo del proceso anterior al actual: '.$tiempo_proceso.'
                        <br>Fecha Inicio: '.$fecha_convertida_inicio_despacho.'
                        <br>Fecha Final: '.$fecha_convertida_final_despacho.'
                        <br>Tiempo de ejecución: ' . $tiempo_despacho;
                    }

                    //estado en despacho
                    $estado_entrega = '';
                    if($total_entregada != 0){
                        $tiempo_proceso = $this->obtenerMinutosHoras($fecha_convertida_final_despacho, $fecha_convertida_inicio_entrega);
                        $tiempo_entrega = $this->obtenerMinutosHoras($fecha_convertida_inicio_entrega, $fecha_convertida_final_entrega);
                        $estado_entrega = 'Finalizado
                        <br>Tiempo del proceso anterior al actual: '.$tiempo_proceso.'
                        <br>Fecha Inicio: '.$fecha_convertida_inicio_entrega.'
                        <br>Fecha Final: '.$fecha_convertida_final_entrega.'
                        <br>Tiempo de ejecución: ' . $tiempo_entrega;
                    }

                    //validar si tiene productos anulados
                    $tabla_productos_anulados = '';
                    $productos_anulados = "<br><br><h5><strong>Información Productos:</strong></h5>
                            <p>
                                <strong>Cantidad items:</strong> ".$consulta_wms[0]->total_items." <br>
                                <strong>Cantidad solicitada:</strong> ".$consulta_wms[0]->total_solicitada."
                        ";

                    $total_anulados = $consulta_wms[0]->total_anulada;
                    if($total_anulados != 0){

                        $productos_anulados .= "
                            <br><strong>Cantidad anulada:</strong> ".$consulta_wms[0]->total_anulada."</p>
                        ";

                    } else {
                        $productos_anulados .= "
                            </p>
                        ";
                    }

                    $sql_2_prod = "SELECT salidas.punto,
                            salidas.salida,
                            detalles_salidas.articulo,
                            articulos.nombre,
                            detalles_salidas.cantidad_solicitada,
                            detalles_salidas.cantidad,
                            detalles_salidas.cantidad_ruta,
                            detalles_salidas.cantidad_por_enrutar,
                            detalles_salidas.cantidad_alistada,
                            detalles_salidas.cantidad_por_alistar,
                            detalles_salidas.cantidad_despachada,
                            detalles_salidas.cantidad_por_despachar,
                            detalles_salidas.cantidad_empacada,
                            detalles_salidas.cantidad_entregada,
                            detalles_salidas.cantidad_por_entregar,
                            detalles_salidas.cantidad_anulada
                            FROM salidas,
                            detalles_salidas, articulos
                            WHERE salidas.punto = detalles_salidas.punto
                            AND salidas.salida = detalles_salidas.salida
                            AND articulos.articulo = detalles_salidas.articulo
                            AND salidas.salida = '".$consulta_wms[0]->salida."'
                        ";

                        $consulta_wms_productos = DB::connection('pgsql2')->select($sql_2_prod);
                        //var_dump($consulta_wms[0]->punto);
                        if(count($consulta_wms_productos) > 0){

                            $tbody_tabla_productos = '';

                            foreach ($consulta_wms_productos as $key_produ => $value_productos) {
                                $tbody_tabla_productos .= '
                                <tr>
                                    <td>'.$value_productos->articulo.'</td>
                                    <td>'.$value_productos->nombre.'</td>
                                    <td>'.$value_productos->cantidad.'</td>
                                    <td>'.$value_productos->cantidad_solicitada.'</td>
                                    <td>'.$value_productos->cantidad_anulada.'</td>
                                    <td>'.$value_productos->cantidad_ruta.'</td>
                                    <td>'.$value_productos->cantidad_alistada.'</td>
                                    <td>'.$value_productos->cantidad_despachada.'</td>
                                    <td>'.$value_productos->cantidad_empacada.'</td>
                                    <td>'.$value_productos->cantidad_entregada.'</td>
                                </tr>';
                            }

                            $tabla_productos_anulados .= '
                                <h5 class="pt-4"><strong>Tabla Productos</strong></h5>
                                <table id="tabla_productos_anulados" class="table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Articulo</th>
                                            <th>Nombre</th>
                                            <th>Cant</th>
                                            <th>Cant Solicitada</th>
                                            <th>Cant Anulada</th>
                                            <th>Cant Ruta</th>
                                            <th>Cant Alistada</th>
                                            <th>Cant Despachada</th>
                                            <th>Cant Empacada</th>
                                            <th>Cant Entregada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        '.$tbody_tabla_productos.'
                                    </tbody>
                                </table>
                            ';
                        }

                    $imprimir .= "
                        <h5 class='text-primary pt-3'>Datos WMS:</h5><hr>
                        <div class='row'>
                            <div class='col-5'>
                                <h5><strong>Información principal:</strong></h5>
                                <ul>
                                    <li><strong>Punto:</strong> ".$consulta_wms[0]->punto."</li>
                                    <li><strong>Numero de Salida:</strong> ".$consulta_wms[0]->salida."</li>
                                    <li><strong>Tipo Documento Referencia:</strong> ".$consulta_wms[0]->tipo_documento_referencia."</li>
                                    <li><strong>Numero identificación:</strong> ".$consulta_wms[0]->identificacion."</li>
                                    <li><strong>Num Secuencia:</strong> ".$consulta_wms[0]->secuencia."</li>
                                </ul>

                                ".$productos_anulados."

                            </div>
                            <div class='col-7'>
                                <h5><strong>Información Estados:</strong></h5>
                                <ul>
                                    <li><strong>Estado Actual:</strong> ".$estado_actual."</li>
                                    <li><strong>Fecha Inserción:</strong> ".$consulta_wms[0]->fecha_insercion."</li>
                                    <li><strong>Tiempo de Netsuit a WMS:</strong> ".$tiempo_netsuit_wms."</li>
                                    <li><strong>En Ruta:</strong> ".$estado_ruta."</li>
                                    <li><strong>En Alistamiento:</strong> ".$estado_alistamiento."</li>
                                    <li><strong>En Despacho:</strong> ".$estado_despacho."</li>
                                    <li><strong>En Entrega:</strong> ".$estado_entrega."</li>
                                </ul>
                            </div>
                            <div class='col-12'>
                                ".$tabla_productos_anulados."
                            </div>
                        </div>
                    ";

                }
            }
        }
        */

        print($imprimir);

    }

    public function cruzeDatos($datos_netsuit)
    {
        //arrays
        $retorno = array();
        //dd($datos_netsuit, $datos_wms);
        foreach ($datos_netsuit as $key => $value) {

            //datos wms
            $existe_wms = 'NO';
            $fecha_insercion_wms = '';
            $tiempo_netsuit_wms = '';
            $tiempo_fulfillment = '';
            $fecha_final_wms = '';

            if($value->control_wms == 1){
                $sql_1 = "SELECT salidas.documento_referencia, salidas.fecha_insercion,
                ultimaentrega(salidas.punto, salidas.salida) as ultima_entrega
                FROM salidas
                WHERE salidas.documento_referencia = '".$value['numdoc']."'";

                $consulta_existe = DB::connection('pgsql2')->select($sql_1);
                if(count($consulta_existe) > 0){

                    $existe_wms = 'SI';

                    $fecha_insercion_wms = $consulta_existe[0]->fecha_insercion;
                    $fecha_final_wms = $consulta_existe[0]->ultima_entrega;

                    //calcular tiempo de netsuit a wms
                    $fecha_convertida_netsuit = date("Y-m-d H:i", strtotime($this->savefecha($value['fecaprov'])));
                    $fecha_convertida_wms = date("Y-m-d H:i", strtotime($fecha_insercion_wms));
                    $tiempo_netsuit_wms = $this->obtenerMinutosHoras($fecha_convertida_netsuit, $fecha_convertida_wms);

                    //calcular tiempo de wms a netsuit
                    if(!empty($value['fecha_fulfill'])){
                        $fecha_convertida_fulfillment = date("Y-m-d H:i", strtotime($this->savefecha($value['fecha_fulfill'])));
                        $fecha_final_convertida_wms = date("Y-m-d H:i", strtotime($fecha_final_wms));
                        $tiempo_fulfillment = $this->obtenerMinutosHoras($fecha_final_convertida_wms, $fecha_convertida_fulfillment);
                    }

                }
            } else {

                $existe_wms = 'SIN WMS';

            }



            $data = array(
                'existe_wms' => $existe_wms,
                'documento_referencia' => $value['numdoc'],
                'fecha_aprobacion' => $value['fecaprov'],
                'horas_aprobacion' => $value['horas_apro'],
                'fecha_insercion_wms' => $fecha_insercion_wms,
                'tiempo_netsuit_wms' => $tiempo_netsuit_wms,
                'fecha_fullfilent' => $value['fecha_fulfill'],
                'fecha_final_wms' => $fecha_final_wms,
                'tiempo_fullfilent' => $tiempo_fulfillment
            );

            array_push($retorno, $data);

        }

        return $retorno;

    }

    function savefecha($vfecha)
    {
        $fch=explode("/",$vfecha);
        $separar = explode(" ",$fch[2]);
        $tfecha=$separar[0]."/".$fch[1]."/".$fch[0].' '.date("H:i", strtotime($separar[1] . ' ' . $separar[2]));
        return $tfecha;
    }

    function minutosTranscurridos($fecha_i,$fecha_f)
    {
        $minutos = (strtotime($fecha_i)-strtotime($fecha_f))/60;
        $minutos = abs($minutos); $minutos = floor($minutos);
        return $minutos;
    }

    public function obtenerMinutosHoras($fecha_1, $fecha_2)
    {
        $tiempo_netsuit_wms = '';
        $minutos_netsuit_wms = $this->minutosTranscurridos($fecha_1, $fecha_2);
        if($minutos_netsuit_wms > 60){
            $horas_netsuit_wms = $minutos_netsuit_wms / 60;
            $tiempo_netsuit_wms = round($horas_netsuit_wms, 1) . ' horas.';
        } else {
            $tiempo_netsuit_wms = $minutos_netsuit_wms . ' minutos.';
        }
        return $tiempo_netsuit_wms;
    }

    public function verPedidos(Request $request)
    {

        $estado = $request->estado;
        $bodega = $request->bodega;
        $fecha = $request->fecha;

        //arrays
        $array_numdocs = array();

        //datos obtenidos de Netsuit
        if($bodega == 'todas'){
            $datos_netsuit = CPDAPedidos::where([
                ['estado_combinado', '=', $estado],
                ['fecha', '=', $fecha]
            ])->get();
        } else {
            $datos_netsuit = CPDAPedidos::where([
                ['estado_combinado', '=', $estado],
                ['fecha', '=', $fecha],
                ['bodega', '=', $bodega],
            ])->get();
        }

        foreach ($datos_netsuit as $key => $value) {
            array_push($array_numdocs,  "'" . $value->numdoc. "'");
        }

        //datos obtenidos de WMS
        $numeros_referencias_separados = implode("," ,$array_numdocs);
        $datos_wms = $this->consultarWMS($numeros_referencias_separados);

        //cruze de datos
        $cruze = $this->cruzeDatos($datos_netsuit);

        return view('pages.ver-pedidos')->with([
            'estado' => $estado,
            'resultado_cont_estads' => count($datos_netsuit),
            'datos_wms' => $datos_wms,
            'datos' => $cruze,
            'bodega' => $bodega,
            'fecha' => $fecha
        ]);
    }

    public function consultarPedido(Request $request)
    {

        $error = false;
        $mensaje = '';

        $numero_ref = $request->num;
        $retorno = "";

        $data = CPDAPedidos::where('numdoc', $numero_ref)->first();
        if($data){

            $retorno = "
                <tr>
                    <td>
                        ".$data->numdoc."
                    </td>
                    <td>
                        ".$data->estado_combinado."
                    </td>
                    <td>
                        ".$data->fecaprov."
                    </td>
                    <td>
                        ".$data->horas_apro."
                    </td>
                    <td>
                        <button type='button' class='btn btn-success openBtn' value=". $data->numdoc ." onclick='abrirModal(".$data->numdoc.");'>
                            Ver Detalles
                        </button>
                    </td>
                </tr>
            ";

        } else {
            $error = true;
            $mensaje = "Error! No se encuentra el pedido con numero de Ref #" . $numero_ref . " en nuestra base de datos.";
        }

        return json_encode(array('error' => $error, 'mensaje' => $mensaje, "data" => $retorno));

    }

    public function obtenerBodegas()
    {
        $datos = CPDAPedidos::select('bodega')->get();
        return $datos;
    }

}
