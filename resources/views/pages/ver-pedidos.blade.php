<x-app-layout>

    @section('pagina')
        Pedidos
    @endsection

    @routes

    <div class="container-fluid px-4 ">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-primary">
                        Torre de Control - WMS y Netsuite
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item" aria-current="page">Estado</li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $estado }}</li>
                        </ol>
                    </nav>
                    <p>
                        <strong>Fecha:</strong> {{ $fecha }} <br>
                        <strong>Bodega:</strong> {{ $bodega }}
                    </p>
                </div>
                <div class="col-12 mt-4">
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-4">
                            <div class="card border border-dark h-100">
                                <div class="card-header">
                                    <strong>Estado:</strong>
                                    {{ $estado }}
                                </div>
                                <div class="card-body">
                                    <p>
                                        Este estado cuenta con un total de <strong><span>{{ $resultado_cont_estads }}</span></strong> registros. <br>
                                        <strong>En WMS:</strong> {{ $datos_wms }} <br>
                                        <strong>No encontrados:</strong> {{ $resultado_cont_estads - $datos_wms }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <table id="example" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>WMS</th>
                                        <th># Referencia</th>
                                        <th>Fecha Aprob</th>
                                        <th>Tiempo Aprob</th>
                                        <th>Fecha Llegada WMS</th>
                                        <th>Tiempo Netsuite a WMS</th>
                                        <th>Fecha final WMS</th>
                                        <th>Fecha Fulfillment</th>
                                        <th>Tiempo Fulfillment</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($datos) > 0)
                                        @foreach ($datos as $key => $value)
                                            <tr class="@if($value['existe_wms'] == 'NO') bg-danger text-white @endif">
                                                <td class="@if($value['existe_wms'] == 'NO') bg-danger text-white @endif">
                                                    {{ $value['existe_wms'] }}
                                                </td>
                                                <td>{{ $value['documento_referencia'] }}</td>
                                                <td>{{ $value['fecha_aprobacion'] }}</td>
                                                <td>{{ $value['horas_aprobacion'] }}</td>
                                                <td>{{ $value['fecha_insercion_wms'] }}</td>
                                                <td>{{ $value['tiempo_netsuit_wms'] }}</td>
                                                <td>{{ $value['fecha_final_wms'] }}</td>
                                                <td>{{ $value['fecha_fullfilent'] }}</td>
                                                <td>{{ $value['tiempo_fullfilent'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-success openBtn" value="{{ $value['documento_referencia'] }}">
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
                                        <th>Fecha Aprob</th>
                                        <th>Horas Aprob</th>
                                        <th>Fecha Llegada WMS</th>
                                        <th>Tiempo Netsuite a WMS</th>
                                        <th>Fecha final WMS</th>
                                        <th>Fecha Fulfillment</th>
                                        <th>Tiempo Fulfillment</th>
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
            <div class="modal-dialog modal-xl modal-dialog-centered">
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

    <x-slot name="js">
    </x-slot>

</x-app-layout>
