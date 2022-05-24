@extends('layouts.app', ['activePage' => 'table', 'titlePage' => __('Desempenho')])
<head>

<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
</head>
@section('content')

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">Gráfico de desempenho</h4>
            <p class="card-category"> Em formação de desempenho</p>
          </div>
          <div class="card-body">
            <form action="{{ route('desempenho.filter') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="exampleFormControlSelect2">Consultores</label>
                            <select data-placeholder="Comece a digitar um nome para filtrar..." multiple class="chosen-select form-control" name="test" id="multiple_consultores">
                                <option value=""></option>
                                @foreach($consultores as $consultor)
                                    <option value="{{ $consultor->nombre_usuario}}"> {{ $consultor->nombre_usuario}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="label-control">Desde</label>
                            <input type="date" class="form-control" value=" " id="fecha_desde" name="fecha_desde"/>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="label-control">Hasta</label>
                            <input type="date" class="form-control" value=" " id="fecha_hasta" name="fecha_hasta"/>
                        </div>
                    </div>
                </div>

                <button class="btn btn-danger mr-4" type="button" id="relatorio">
                    <i class="material-icons">archive</i>
                    <span>Relatorio</span>
                    <div class="ripple-container"></div><div class="ripple-container"></div>
                </button>
                <button class="btn btn-success mr-4" type="button" id="grafico">
                    <i class="material-icons">book</i>
                    <span>Grafico</span>
                    <div class="ripple-container"></div>
                </button>
                <button class="btn btn-warning mr-4" type="button" id="pizza">
                    <i class="material-icons">book</i>
                    <span>Pizza</span>
                    <div class="ripple-container"></div>
                </button>

            </form>
          </div>
        </div>
      </div>
      <div class="col-md-12" id='relatorio_div' style="display: none;">

      </div>

      <div class="col-md-12" id='pizza_div' style="display: none;">
        <div class="row">
            <div class="col-md-12">

                <canvas id="miGrafico"></canvas>

            </div>
        </div>
      </div>

      <div class="col-md-12" id='barra_div' style="display: none;">
        <div class="row">
            <div class="col-md-12">

                <canvas id="miGraficoBarra"></canvas>

            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('myjs')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <!-- Chart JS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js" integrity="sha256-JG6hsuMjFnQ2spWq0UiaDRJBaarzhFbUxiUTxQDA9Lk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js" integrity="sha256-XF29CBwU1MWLaGEnsELogU6Y6rcc5nCkhhx89nFMIDQ=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js" integrity="sha256-J2sc79NPV/osLcIpzL3K8uJyAD7T5gaEFKlLDM18oxY=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" integrity="sha256-CfcERD4Ov4+lKbWbYqXD6aFM9M51gN4GUEtDhkWABMo=" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        });

        $("#relatorio").on( "click", function() {

            if (validacionFecha(0) > 0) {

                if (validacionFecha(1) > 0) {

                    $('#relatorio_div').css('display', 'block'); //muestro mediante id
                    $('#relatorio_div div').remove();

                    var combo_selected = $(".chosen-select").val(); // valores del combo
                    var desde = $("#fecha_desde").val();
                    var hasta = $("#fecha_hasta").val();
                    var URL = {!! json_encode(url('consultar-relatorio')) !!}; //URL del servicio

                    // Comienzo la peticion Ajax al back
                    $.ajax({
                        type:'POST',
                        url: URL,
                        dataType: 'json',
                        data:{consultores:combo_selected,desde:desde, hasta:hasta},
                        success:function(response){
                            $(function() {
                                $.each(response.result, function(i, item) {
                                    var html = "";
                                    html = '<div class="card"><div class="card-header card-header-primary"><h4 class="card-title ">'+item.consultor+'</h4><p class="card-category"></p></div><div class="card-body table-responsive"><table class="table table-hover" id="tabla-'+item.consultor+'"><thead class="text-warning"><th>Período</th><th>Receita Líquida</th><th>Custo Fixo</th><th>Comissão</th><th>Lucro</th></thead><tbody>';
                                    //var $tr = $('<tr>').append(
                                        //$('<td>').text(item.receita_liquida),
                                        //$('<td>').text(item.custo_fixo),
                                        //$('<td>').text(item.comissao)
                                    //); //.appendTo('#records_table');
                                    html += "<tr><td>" + desde+" / " + hasta +"<td>" + item.receita_liquida + "<td>" + item.custo_fixo + "<td>" + item.comissao + "<td>" + item.lucro + "</tr>"
                                    html += '</tbody></table></div></div>';
                                    $((html)).appendTo("#relatorio_div");
                                });
                            });
                            //location.reload();
                        }
                    });
                } else {

                    alert('Por favor colocar una fecha Final');
                }
            } else {

                alert('Por favor colocar una fecha Inicial');
            }

        });
        $("#grafico").on( "click", function() {

            if (validacionFecha(0) > 0) {

                if (validacionFecha(1) > 0) {

                    $('#relatorio_div').css('display', 'none'); //muestro mediante id
                    $('#pizza_div').css('display', 'none'); //muestro mediante id
                    $('#barra_div').css('display', 'block'); //muestro mediante id

                    miBarra();
                } else {

                    alert('Por favor colocar una fecha Final');
                }
            } else {

                alert('Por favor colocar una fecha Inicial');
            }
        });
        $("#pizza").on( "click", function() {

            if (validacionFecha(0) > 0) {

                if (validacionFecha(1) > 0) {
                    $('#relatorio_div').css('display', 'none'); //muestro mediante id
                    $('#pizza_div').css('display', 'block'); //muestro mediante id
                    miPizza();
                } else {

                    alert('Por favor colocar una fecha Final');
                }
            } else {

                alert('Por favor colocar una fecha Inicial');
            }
        });
    });

    function validacionFecha(param){
        if (param > 0) {

            s= $('#fecha_hasta').val();
        } else {

            s= $('#fecha_desde').val();
        }
        var bits = s.split('-');
        var d = new Date(bits[0] + '/' + bits[1] + '/' + bits[2]);
        if (d == 'Invalid Date') {
            return 0;
        } else {
            return 1;
        }
    }

    function miPizza(){

        var URL = {!! json_encode(url('consultar-pizza')) !!}; //URL del servicio
        var combo_selected = $(".chosen-select").val(); // valores del combo
        var desde = $("#fecha_desde").val();
        var hasta = $("#fecha_hasta").val();
        $.ajax({
            url: URL,
            dataType: 'json',
            type:'POST',
            data:{consultores:combo_selected,desde:desde, hasta:hasta},
            success: function(data) {
                var nombre = [];
                var stock = [];
                var color = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'];
                var bordercolor =  ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'];

                for (var i in data.result) {
                    nombre.push(data.result[i].consultor);
                    stock.push(data.result[i].receita_liquida);
                }

                var chartdata = {
                    labels: nombre,
                    datasets: [{
                        label: nombre,
                        backgroundColor: color,
                        borderColor: color,
                        borderWidth: 2,
                        hoverBackgroundColor: color,
                        hoverBorderColor: bordercolor,
                        data: stock
                    }]
                };

                var mostrar = $("#miGrafico");

                var grafico = new Chart(mostrar, {
                    type: 'doughnut',
                    data: chartdata,
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        });
    }

    function miBarra(){

        var URL = {!! json_encode(url('consultar-pizza')) !!}; //URL del servicio
        var combo_selected = $(".chosen-select").val(); // valores del combo
        var desde = $("#fecha_desde").val();
        var hasta = $("#fecha_hasta").val();
        $.ajax({
            url: URL,
            dataType: 'json',
            type:'POST',
            data:{consultores:combo_selected,desde:desde, hasta:hasta},
            success: function(data) {
                var nombre = [];
                var stock = [];
                var color = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'];
                var bordercolor =  ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'];
                console.log(data);

                for (var i in data.result) {
                    nombre.push(data.result[i].consultor);
                    stock.push(data.result[i].receita_liquida);
                }

                var chartdata = {
                    labels: nombre,
                    datasets: [{
                        label: nombre,
                        backgroundColor: color,
                        borderColor: color,
                        borderWidth: 2,
                        hoverBackgroundColor: color,
                        hoverBorderColor: bordercolor,
                        data: stock
                    }]
                };

                var mostrar = $("#miGraficoBarra");

                var grafico = new Chart(mostrar, {
                    type: 'bar',
                    data: chartdata,
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        });
    }
</script>
@endsection
