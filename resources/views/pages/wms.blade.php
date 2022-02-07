<x-app-layout>

    @section('pagina')
        Control WMS
    @endsection

    <div class="container-fluid px-4 ">
        <div class="row">
            <div class="col-12">
                <div class="d-flex mb-3">
                    <div class="me-auto p-2">
                        <h4 class="text-primary">
                            Control WMS
                        </h4>
                    </div>
                    <div class="p-2">
                        <button type="button" class="btn btn-primary" onclick="window.print();">
                            <i class="bi bi-printer-fill"></i> Imprimir pagina
                        </button>
                    </div>
                </div>

                <form action="{{ route('wms') }}" method="POST" class="row row-cols-lg-auto g-3 align-items-center mt-3 mb-3">
                    @csrf
                    <label>Filtro por fecha: </label>
                    <div class="col-12">
                        <input type="date" class="form-control" name="fecha" value="{{ $fecha }}" required>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
                <p class="mb-5">
                    <strong>Cantidad de registro en WMS:</strong> {{ $total }}
                </p>
                <div class="col-12 mt-4">
                    <div class="row">

                        @foreach ($datos_enviar as $key => $value)
                            <div class="col-lg-3 col-md-6 col-12 mb-4">
                                <div class="card border border-dark">
                                    <div class="card-header">
                                        <strong>Estado:</strong>
                                        {{ $value['estado'] }}
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            Este estado cuenta con un total de <strong><span>{{ $value['cantidad'] }}</span></strong> registros.
                                        </p>

                                        <ul>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (06:00 / 6:59 am):</strong>  <br>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_1'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_1'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_1'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (07:00 / 7:59 am): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_2'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_2'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_2'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (08:00 / 8:59 am): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_3'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_3'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_3'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (09:00 / 09:59 am): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_4'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_4'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_4'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (10:00 / 10:59 am): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_5'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_5'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_5'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (11:00 / 11:59 am): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_6'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_6'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_6'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (12:00 / 12:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_7'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_7'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_7'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (13:00 / 13:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_8'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_8'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_8'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (14:00 / 14:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_9'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_9'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_9'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (15:00 / 15:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_10'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_10'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_10'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (16:00 / 16:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_11'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_11'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_11'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (17:00 / 17:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_12'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_12'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_12'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (18:00 / 18:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_13'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_13'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_13'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (19:00 / 19:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_14'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_14'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_14'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (20:00 / 20:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_15'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_15'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_15'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (21:00 / 21:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_16'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_16'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_16'] }}</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong class="text-primary">Rango fecha: (22:00 / 22:59 pm): </strong>
                                                <ul>
                                                    <li><strong>Cantidad pedidos:</strong> {{ $value['data']['fecha_17'] }}</li>
                                                    <li><strong>Cantidad items:</strong> {{ $value['data_items']['item_17'] }}</li>
                                                    <li><strong>Cantidad solicitada:</strong> {{ $value['data_solicitada']['solicitado_17'] }}</li>
                                                </ul>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="js">
    </x-slot>

</x-app-layout>

