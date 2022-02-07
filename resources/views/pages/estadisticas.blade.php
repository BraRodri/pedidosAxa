<x-app-layout>

    @section('pagina')
        Estadisticas
    @endsection

    <div class="container-fluid px-4 ">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h4 class="text-primary">
                    Estadisticas - Graficos
                </h4>
            </div>

            <div class="col-lg-12 col-12 mt-4">
                <div class="card border border-dark">
                    <div class="card-body">

                        <div class="d-flex mb-3">
                            <div class="me-auto p-2">
                                <h5>Pedidos por Bodega y rango de fechas</h5>
                            </div>
                            <div class="p-2">
                                <button class="btn btn-dark" onclick="printCanvas('myChart_4');">Imprimir</button>
                            </div>
                        </div>

                        <form id="form_pedidos_bodegas" method="POST" class="row row-cols-lg-auto g-3 align-items-center mt-3 mb-3 needs-validation" novalidate>
                            @csrf

                            <div class="col-lg-6 col-12">
                                <label>Bodega: </label>
                                <select class="form-control js-example-basic-single_2" name="bodega_select[]" multiple required>
                                    @foreach ($resultado_bodegas as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-6">
                                <label>Fecha desde: </label>
                                <input type="date" class="form-control" name="fecha_inicio" value="{{ $fecha }}" required>
                            </div>
                            <div class="col-lg-2 col-6">
                                <label>Fecha hasta: </label>
                                <input type="date" class="form-control" name="fecha_fin" value="{{ $fecha }}" required>
                            </div>

                            <div class="col-12">
                                <br>
                                <button type="submit" class="btn btn-primary">Generar</button>
                                <button type="button" class="btn btn-danger" onclick="cerarCanvas('#div_chart_4');"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </form>

                        <div class="mt-5" id="div_chart_4">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-12 col-12 mt-4">
                <div class="card border border-dark">
                    <div class="card-body">

                        <div class="d-flex mb-3">
                            <div class="me-auto p-2">
                                <h5>Pedidos por rango de fechas (Netsuite)</h5>
                            </div>
                            <div class="p-2">
                                <button class="btn btn-dark" onclick="printCanvas('myChart_3');">Imprimir</button>
                            </div>
                        </div>

                        <form id="form_pedidos_fechas" method="POST" class="row row-cols-lg-auto g-3 align-items-center mt-3 mb-3 needs-validation" novalidate>
                            @csrf

                            <div class="col-lg-4 col-6">
                                <label>Fecha desde: </label>
                                <input type="date" class="form-control" name="fecha_inicio" value="{{ $fecha }}" required>
                            </div>
                            <div class="col-lg-4 col-6">
                                <label>Fecha hasta: </label>
                                <input type="date" class="form-control" name="fecha_fin" value="{{ $fecha }}" required>
                            </div>

                            <div class="col-12">
                                <br>
                                <button type="submit" class="btn btn-primary">Generar</button>
                                <button type="button" class="btn btn-danger" onclick="cerarCanvas('#div_chart_3');"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </form>

                        <div class="mt-5" id="div_chart_3">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-12 col-12 mt-4">
                <div class="card border border-dark">
                    <div class="card-body">

                        <div class="d-flex mb-3">
                            <div class="me-auto p-2">
                                <h5>Pedidos por Clases</h5>
                            </div>
                            <div class="p-2">
                                <button class="btn btn-dark" onclick="printCanvas('myChart_1');">Imprimir</button>
                            </div>
                        </div>

                        <form id="form_pedidos_clases" method="POST" class="row row-cols-lg-auto g-3 align-items-center mt-3 mb-3 needs-validation" novalidate>
                            @csrf

                            <div class="col-lg-4 col-12">
                                <label>Filtro por fecha: </label>
                                <input type="date" class="form-control" name="fecha" value="{{ $fecha }}" required>
                            </div>

                            <div class="col-12">
                                <br>
                                <button type="submit" class="btn btn-primary">Generar</button>
                                <button type="button" class="btn btn-danger" onclick="cerarCanvas('#div_chart_1');"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </form>

                        <div class="mt-5" id="div_chart_1">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-12 col-12 mt-4">
                <div class="card border border-dark">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="me-auto p-2">
                                <h5>Pedidos por Clase y Por Horas (Fecha Aprobación - Netsuite)</h5>
                            </div>
                            <div class="p-2">
                                <button class="btn btn-dark" onclick="printCanvas('myChart_2');">Imprimir</button>
                            </div>
                        </div>
                        <form id="form_pedidos_clase_horas" method="POST" class="row row-cols-lg-auto g-3 align-items-center mt-3 mb-3 needs-validation" novalidate>
                            @csrf

                            <div class="col-lg-4 col-12">
                                <label>Filtro por fecha: </label>
                                <input type="date" class="form-control" name="fecha" value="{{ $fecha }}" required>
                            </div>

                            <div class="col-lg-6 col-12">
                                <label>Filtro por Clase: </label>
                                <select class="form-control js-example-basic-single" name="clase[]" id="clase" multiple required>
                                    <option value="">- Seleccione -</option>
                                    @foreach ($clases as $key => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <br>
                                <button type="submit" class="btn btn-primary">Generar</button>
                                <button type="button" class="btn btn-danger" onclick="cerarCanvas('#div_chart_2');"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </form>

                        <div class="mt-5" id="div_chart_2">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-slot name="js">
        <script src="{{ asset('js/estadisticas.js') }}" defer></script>

        <script>

            $(document).ready(function() {
                $('.js-example-basic-single').select2({
                    placeholder: '- Seleccione -',
                    allowClear: true
                });

                $('.js-example-basic-single_2').select2({
                    placeholder: '- Seleccione -',
                    allowClear: true
                });
            });

            function printCanvas(data){
                var dataUrl = document.getElementById(data).toDataURL(); //attempt to save base64 string to server using this var
                var windowContent = '<!DOCTYPE html>';
                windowContent += '<html>'
                windowContent += '<head><title>Estadistica</title></head>';
                windowContent += '<body>'
                windowContent += '<img src="' + dataUrl + '">';
                windowContent += '</body>';
                windowContent += '</html>';
                var printWin = window.open('','','width=1000,height=700');
                printWin.document.open();
                printWin.document.write(windowContent);
                printWin.document.close();
                printWin.focus();
                printWin.print();
                printWin.close();
            }

            function cerarCanvas(data){
                $(data).html('');
            }
        </script>
    </x-slot>

</x-app-layout>
