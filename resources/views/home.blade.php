<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pedidos - WMS - Netsuit</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/scripts.js') }}" defer></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.4/r-2.2.9/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.4/r-2.2.9/datatables.min.js"></script>

    </head>
    <body class="p-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-primary">
                        Cruze de Datos - LOG de WMS y Netsuit
                    </h4>
                </div>
                <div class="col-12 mt-4">
                    <div class="row">
                        @foreach ($array_datos[0] as $key => $value)
                            <div class="col-4 mb-4">
                                <div class="card border border-dark">
                                    <div class="card-header">
                                        <strong>Estado:</strong>
                                        {{ $value }}
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            Este estado cuenta con un total de <strong><span>{{ $array_datos[1][$value] }}</span></strong> registros.
                                        </p>
                                        <!--
                                        <a href="#" class="btn btn-dark text-right">Ver pedidos</a>
                                        -->
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-12 mt-5">
                            <table id="example" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>WMS</th>
                                        <th># Referencia</th>
                                        <th>Tipo Venta</th>
                                        <th>Horas</th>
                                        <th>Fecha</th>
                                        <th>Fecha Pro</th>
                                        <th>Estado</th></th>
                                        <th>Bodega</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($datos) > 0)
                                        @foreach ($datos as $key => $value)
                                            <tr>
                                                <td>
                                                    @if ($value['numdoc'] == isset($datos_wms[0][$key]))
                                                        SI
                                                        @else
                                                        NO
                                                    @endif
                                                </td>
                                                <td>{{ $value['numdoc'] }}</td>
                                                <td>{{ $value['tipo_venta'] }}</td>
                                                <td>{{ $value['horas_pro'] }}</td>
                                                <td>{{ $value['fecha'] }}</td>
                                                <td>{{ $value['fecaprov'] }}</td>
                                                <td>{{ $value['estado_combinado'] }}</td>
                                                <td>{{ $value['bodega'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-success openBtn" value="{{ $value['numdoc'] }}">
                                                        Ver Detalles
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>WMS</th>
                                        <th># Referencia</th>
                                        <th>Tipo Venta</th>
                                        <th>Horas</th>
                                        <th>Fecha</th>
                                        <th>Fecha Pro</th>
                                        <th>Estado</th></th>
                                        <th>Bodega</th>
                                        <th>Acciones</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalles del Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
