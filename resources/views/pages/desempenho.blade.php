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
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">Relatorio</h4>
            <p class="card-category" id='parrafo_relatorio'></p>
          </div>
          <div class="card-body table-responsive">
              <table class="table table-hover" id="tabla-relatorio">
                <thead class="text-warning">
                  <th>Período</th>
                  <th>Receita Líquida</th>
                  <th>Custo Fixo</th>
                  <th>Comissão</th>
                  <th>Lucro</th>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Dakota Rice</td>
                    <td>$36,738</td>
                    <td>Niger</td>
                  </tr>
                </tbody>
              </table>
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
                            console.log(response);
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
            $('#relatorio_div').css('display', 'none'); //muestro mediante id

        });
        $("#pizza").on( "click", function() {
            $('#relatorio_div').css('display', 'none'); //muestro mediante id

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

</script>
@endsection
