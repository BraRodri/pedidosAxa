function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
      if (i==2){
        var lastcolor = letters[Math.floor(Math.random() * 16)];
        if ( color.search(lastcolor) >= 0 ){
          color += letters[Math.floor(Math.random() * 16)];
        }else{
          i--;
        }
      }
      else{
        color += letters[Math.floor(Math.random() * 16)];
      }
    }
    return color;
}

/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

//primer chart.js
$('#div_chart_1').hide();
let myChart_1;
//proceso de registro
$('#form_pedidos_clases').on('submit', function(e) {
    event.preventDefault();
    if ($('#form_pedidos_clases')[0].checkValidity() === false) {
        event.stopPropagation();
    } else {

        $('#div_chart_1').html('');

        // agregar data
        var $thisForm = $('#form_pedidos_clases');
        var formData = new FormData(this);

        //ruta
        var url = route('estadisticas.por.clases');

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

                $('#div_chart_1').show();
                var labels = respuesta.labes;
                var cantidades = respuesta.cantidades;

                swal('Datos obtenidos, pintando...', {
                    icon: "success",
                    button: false,
                    timer: 2000
                });

                $('#div_chart_1').append('<canvas id="myChart_1" height="600"></canvas>');

                setTimeout(function(){
                    pintarDatosPorClase(labels, cantidades);
                }, 2000);

            } else {
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
    $('#form_pedidos_clases').addClass('was-validated');

});

function pintarDatosPorClase(labels, cantidades){

    let delayed;
    var colores = [];
    labels.forEach(element => {
        colores.push((getRandomColor()));
    });

    // Pie Chart Example
    var ctx_1 = document.getElementById("myChart_1");
    var myChart_1 = new Chart(ctx_1, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Cantidad Por Clases",
                data: cantidades,
                backgroundColor: colores,
                borderWidth: 1
            }],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            maintainAspectRatio: false,
            animation: {
                onComplete: () => {
                  delayed = true;
                },
                delay: (context) => {
                  let delay = 0;
                  if (context.type === 'data' && context.mode === 'default' && !delayed) {
                    delay = context.dataIndex * 300 + context.datasetIndex * 100;
                  }
                  return delay;
                },
            }
        },
    });
}

/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

//segundo chart.js
$('#div_chart_2').hide();
//proceso de registro
$('#form_pedidos_clase_horas').on('submit', function(e) {
    event.preventDefault();
    if ($('#form_pedidos_clase_horas')[0].checkValidity() === false) {
        event.stopPropagation();
    } else {

        $('#div_chart_2').html('');

        // agregar data
        var $thisForm = $('#form_pedidos_clase_horas');
        var formData = new FormData(this);

        //ruta
        var url = route('estadisticas.por.clase.por.horas');

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

                $('#div_chart_2').show();
                var retorno = respuesta.retorno;
                var labels = respuesta.labels;

                swal('Datos obtenidos, pintando...', {
                    icon: "success",
                    button: false,
                    timer: 2000
                });

                $('#div_chart_2').append('<canvas id="myChart_2" height="500"></canvas>');

                setTimeout(function(){
                    pintarDatosPorClasePorHoras(labels, retorno);
                }, 2000);

            } else {
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
    $('#form_pedidos_clase_horas').addClass('was-validated');

});

function pintarDatosPorClasePorHoras(labels, retorno)
{
    let delayed;
    var data = [];
    retorno.forEach(element => {
        var informacion = {
            label: element.nombre,
            data: element.cantidades,
            borderColor: getRandomColor(),
            borderWidth: 2
        };
        data.push(informacion);
    });

    //Pie Chart Example
    var ctx_1 = document.getElementById("myChart_2");
    var myChart_1 = new Chart(ctx_1, {
        type: 'line',
        data: {
            labels: labels,
            datasets: data,
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                  position: 'top',
                },
                title: {
                  display: true,
                  text: 'Clases por Horas'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            maintainAspectRatio: false,
            animation: {
                onComplete: () => {
                  delayed = true;
                },
                delay: (context) => {
                  let delay = 0;
                  if (context.type === 'data' && context.mode === 'default' && !delayed) {
                    delay = context.dataIndex * 300 + context.datasetIndex * 100;
                  }
                  return delay;
                },
            }
        },
    });

}

/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

//tercer chart.js
$('#div_chart_3').hide();
//proceso de registro
$('#form_pedidos_fechas').on('submit', function(e) {
    event.preventDefault();
    if ($('#form_pedidos_fechas')[0].checkValidity() === false) {
        event.stopPropagation();
    } else {

        $('#div_chart_3').html('');

        // agregar data
        var $thisForm = $('#form_pedidos_fechas');
        var formData = new FormData(this);

        //ruta
        var url = route('estadisticas.getPedidosPorFechas');

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
            console.log(respuesta);
            if (!respuesta.error) {

                $('#div_chart_3').show();
                var labels = respuesta.labes;
                var cantidades = respuesta.cantidades;

                swal('Datos obtenidos, pintando...', {
                    icon: "success",
                    button: false,
                    timer: 2000
                });

                $('#div_chart_3').append('<canvas id="myChart_3" height="500"></canvas>');

                setTimeout(function(){
                    pintarDatosPorFechas3(labels, cantidades);
                }, 2000);

            } else {
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
    $('#form_pedidos_fechas').addClass('was-validated');

});

function pintarDatosPorFechas3(labels, cantidades)
{
    let delayed;
    var colores = [];
    labels.forEach(element => {
        colores.push((getRandomColor()));
    });

    // Pie Chart Example
    var ctx_1 = document.getElementById("myChart_3");
    var myChart_1 = new Chart(ctx_1, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Cantidad Por Fecha",
                data: cantidades,
                fill: false,
                borderColor: colores,
                backgroundColor: colores,
            }],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            maintainAspectRatio: false,
            animation: {
                onComplete: () => {
                  delayed = true;
                },
                delay: (context) => {
                  let delay = 0;
                  if (context.type === 'data' && context.mode === 'default' && !delayed) {
                    delay = context.dataIndex * 300 + context.datasetIndex * 100;
                  }
                  return delay;
                },
            }
        },
    });
}

/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
//cuarto chart.js
$('#div_chart_4').hide();
$('#form_pedidos_bodegas').on('submit', function(e) {
    event.preventDefault();
    if ($('#form_pedidos_bodegas')[0].checkValidity() === false) {
        event.stopPropagation();
    } else {

        $('#div_chart_4').html('');

        // agregar data
        var $thisForm = $('#form_pedidos_bodegas');
        var formData = new FormData(this);

        //ruta
        var url = route('estadisticas.getPedidosPorBodegas');

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

                $('#div_chart_4').show();
                var labels = respuesta.labels;
                var retorno = respuesta.retorno;

                swal('Datos obtenidos, pintando...', {
                    icon: "success",
                    button: false,
                    timer: 2000
                });

                $('#div_chart_4').append('<canvas id="myChart_4" height="500"></canvas>');

                setTimeout(function(){
                    pintarDatosPorBodegas(labels, retorno);
                }, 2000);

            } else {
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
    $('#form_pedidos_bodegas').addClass('was-validated');

});

function pintarDatosPorBodegas(labels, retorno)
{
    let delayed;
    var data = [];
    retorno.forEach(element => {
        var color = getRandomColor();
        var informacion = {
            label: element.nombre,
            data: element.cantidades,
            backgroundColor: color,
            borderColor: color,
            borderWidth: 2
        };
        data.push(informacion);
    });

    //Pie Chart Example
    var ctx_1 = document.getElementById("myChart_4");
    var myChart_1 = new Chart(ctx_1, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: data,
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                  position: 'top',
                },
                title: {
                  display: true,
                  text: 'Bodegas por fechas'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            maintainAspectRatio: false,
            animation: {
                onComplete: () => {
                  delayed = true;
                },
                delay: (context) => {
                  let delay = 0;
                  if (context.type === 'data' && context.mode === 'default' && !delayed) {
                    delay = context.dataIndex * 300 + context.datasetIndex * 100;
                  }
                  return delay;
                },
            }
        },
    });

}
