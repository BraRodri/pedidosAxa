<x-app-layout>

    @section('pagina')
        Inicio
    @endsection

        <div class="container-fluid px-4 ">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-primary me-auto">
                        <strong>Torre de Control - WMS y Netsuite</strong>
                    </h4>
                    <h5>
                        Bodega actual: <span class="text-primary">{{ $bodega }}</span>
                    </h5>
                    <h6>
                        Bienvenido a la Torre de Control, cruce de datos.
                    </h6>
                    <form action="{{ route('home') }}" method="POST" class="row row-cols-lg-auto g-3 align-items-center mt-3 mb-3">
                        @csrf

                        <div class="col-lg-4 col-6">
                            <label>Filtro por Bodega: </label>
                            <select class="form-control" name="bodega_select" required>
                                @php
                                    $selected = $bodega == 'todas' ? 'selected' : '';
                                @endphp
                                <option value="todas" {{ $selected }}>- Todas las bodegas -</option>
                                @foreach ($resultado_bodegas as $item)
                                    @php
                                        $selected = $bodega == $item ? "selected" : '';
                                    @endphp
                                    <option value="{{ $item }}" {{ $selected }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-6">
                            <label>Filtro por Fecha: </label>
                            <input type="date" class="form-control" name="fecha" value="{{ $fecha }}" required>
                        </div>

                        <div class="col-12">
                            <br>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                    <p>
                        <strong>Datos obtenidos:</strong> {{ $cantidad_netsuit }}<br>
                        <strong>Datos en WMS:</strong> {{ $cantidad_wms }} <br>
                        <strong>NO en WMS:</strong> {{ $cantidad_netsuit - $cantidad_wms }}
                    </p>
                </div>
                <hr>
                <div class="col-12 mt-4">
                    <h4>Busqueda rapida de pedidos:</h4>
                    <form id="form_busqueda_pedido" method="POST" class="row row-cols-lg-auto g-3 align-items-center mt-2 mb-5 needs-validation" novalidate>
                        @csrf
                        <label>Escribe Número de Referencia del pedido: </label>
                        <div class="col-12">
                            <input type="text" class="form-control" name="num" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </form>
                    <div id="div_result_busqueda" class="mb-5">
                        <table class="table table-striped table-hover">
                            <thead>
                                <th># Referencia</th>
                                <th>Estado</th>
                                <th>Fecha Aprobación</th>
                                <th>Tiempo</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody id="tbodyProducto">
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="col-12 mt-4">
                    <div class="row">
                        @foreach ($array_datos[0] as $key => $value)
                            <div class="col-lg-4 col-12 mb-4">
                                <div class="card border border-dark h-100">
                                    <div class="card-header">
                                        <strong>Estado:</strong>
                                        {{ $value }}
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            Este estado cuenta con un total de <strong><span>{{ $array_datos[1][$value] }}</span></strong> registros.
                                        </p>

                                        <form action="{{ route('verPedidos') }}" method="POST">
                                            @csrf
                                            <input name="bodega" value="{{ $bodega }}" hidden>
                                            <input name="fecha" value="{{ $fecha }}" hidden>
                                            <input name="estado" value="{{ $value }}" hidden>
                                            <button type="submit" class="btn btn-dark text-right">
                                                Ver pedidos
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

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

            $('#div_result_busqueda').hide();

            $('#form_busqueda_pedido').on('submit', function(e) {
                $('#div_result_busqueda').hide();
                event.preventDefault();
                if ($('#form_busqueda_pedido')[0].checkValidity() === false) {
                    event.stopPropagation();
                } else {

                    // agregar data
                    var $thisForm = $('#form_busqueda_pedido');
                    var formData = new FormData(this);

                    //ruta
                    var url = route('consultarPedido');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "POST",
                        encoding:"UTF-8",
                        url: url,
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType:'json',
                        beforeSend:function(){
                            swal("Obteniendo datos, espere porfavor...", {
                                button: false,
                                timer: 2000
                            });
                        }
                    }).done(function(respuesta){
                        //console.log(respuesta);
                        if (!respuesta.error) {

                            $('#div_result_busqueda').show();

                            swal('Datos obtenidos, pintando...', {
                                icon: "success",
                                button: false,
                                timer: 2000
                            });
                            $("#tbodyProducto").html(respuesta.data);

                        } else {
                            $("#tbodyProducto").html('');
                            setTimeout(function(){
                                swal(respuesta.mensaje, {
                                    icon: "error",
                                    button: false,
                                    timer: 3000
                                });
                            },2000);
                        }
                    }).fail(function(resp){
                        console.log(resp);
                    });

                }
                $('#form_busqueda_pedido').addClass('was-validated');

            });

            function abrirModal(id){
                var url_consulta = route('consultar', id);
                swal("Consultado información, espere...", {
                    button: false,
                    timer: 2000
                });
                $('.modal-body').load(url_consulta, function(){
                    setTimeout(function(){
                        $('#myModal').modal('show');
                    },2000);

                });
            }

        </script>
    </x-slot>

</x-app-layout>


