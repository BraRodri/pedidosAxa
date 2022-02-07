<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/app.js') }}" defer></script>
<script src="{{ asset('js/scripts.js') }}" defer></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.4/b-2.2.2/fh-3.2.1/r-2.2.9/sp-1.4.0/sl-1.3.4/datatables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $('#boton_cerrar_sesion').on('click', function() {
        swal({
            title: "Cerrar Sesión",
            text: "¿Estas seguro de cerrar la sesión actual?",
            icon: "warning",
            buttons: ["Cancelar", "Si, salir"],
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $('#form_cerrar_sesion').submit();
            }
        });
    });
 </script>

{{ $slot }}
