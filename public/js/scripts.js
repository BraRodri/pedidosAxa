window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

(function() {
    'use strict';
    window.addEventListener('load', function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();

  $('#div_content_resultado').hide();

$(document).ready(function() {
    $('#example').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.4/i18n/es_es.json"
        },
        "order": [[ 0, "acs" ]],
        "pageLength" : 25,
    });
    $('#tabla_productos_anulados').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.4/i18n/es_es.json"
        }
    });
} );

$('.openBtn').on('click',function(){
    //var url_consulta = '{{ route("consultar") }}?id='+$(this).val();
    var url_consulta = route('consultar', $(this).val());
    swal("Consultado información, espere...", {
        button: false,
        timer: 2000
    });
    $('.modal-body').load(url_consulta, function(){
        setTimeout(function(){
            $('#myModal').modal('show');
        },2000);

    });
});
