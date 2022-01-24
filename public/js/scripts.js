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
        }
    });
} );

$('.openBtn').on('click',function(){
    var url_consulta = 'consulta/' + $(this).val();
    swal("Consultado información, espere...", {
        button: false,
        timer: 2000
    });
    $('.modal-body').load(url_consulta, function(){
        $('#myModal').modal('show');
    });
});
