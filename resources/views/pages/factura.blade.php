<x-app-layout>

    @section('pagina')
        Control Facura
    @endsection



        <div class="container-fluid px-4 ">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-primary">
                        Torre de Control - Control de Factura
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Control de Factura</li>
                        </ol>
                    </nav>

                    <form action="{{ route('factura') }}" method="POST" class="row row-cols-lg-auto g-3 align-items-center mt-3 mb-3">
                        @csrf
                        <label>Filtro por fecha: </label>
                        <div class="col-12">
                            <input type="date" class="form-control" name="fecha" value="{{ $fecha }}" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </form>

                    <p>
                        Cantidad registros: {{ count($data_factura) }} <br>
                        <!--Cantidad facturados: {{ $cantidad_facturados }} <br>
                        Cantidad no facturados aun: {{ $cantidad_no_facturados }} <br> -->
                        Cantidad sin Respuesta de la DIAN: {{ $cantidad_sin_dian }}
                    </p>
                </div>
                <div class="col-12 ">
                    <div class="row">

                        <div class="col-12 mt-5">
                            <table id="example_factura" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Facturado</th>
                                        <th># Factura</th>
                                        <th># Referencia</th>
                                        <th>Fecha</th>
                                        <th>Fecha Factura</th>
                                        <th>Fecha Dian</th>
                                        <th>Bodega</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($data_factura) > 0)
                                        @foreach ($data_factura as $item)
                                            <tr>
                                                <td>
                                                    @if ($item->fecha_creacion_factura)
                                                        <span class="text-success"> SI </span>
                                                        @else
                                                        <span class="text-danger"> NO </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $item->id_factura }}
                                                </td>
                                                <td>
                                                    {{ $item->num_pedido }}
                                                </td>
                                                <td>
                                                    {{ $item->fecha }}
                                                </td>
                                                <td>
                                                    {{ $item->fecha_creacion_factura }}
                                                </td>
                                                <td>
                                                    {{ $item->fecha_respuesta_dian }}
                                                </td>
                                                <td>
                                                    {{ $item->bodega }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-success openBtn" value="{{ $item->num_pedido }}">
                                                        Ver Detalles
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Facturado</th>
                                        <th># Factura</th>
                                        <th># Referencia</th>
                                        <th>Fecha</th>
                                        <th>Fecha Factura</th>
                                        <th>Fecha Dian</th>
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
        <script>
            $(document).ready(function() {
                $('#example_factura').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.11.4/i18n/es_es.json"
                    },
                    "order": [[ 0, "acs" ]],
                    "pageLength" : 50,
                });
            } );
        </script>
    </x-slot>

</x-app-layout>
