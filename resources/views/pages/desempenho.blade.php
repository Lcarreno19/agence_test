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
          <div class="card-body">

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

        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        });
        $("#relatorio").on( "click", function() {
            $('#relatorio_div').css('display', 'block'); //muestro mediante id

        });
        $("#grafico").on( "click", function() {
            $('#relatorio_div').css('display', 'none'); //muestro mediante id

        });
        $("#pizza").on( "click", function() {
            $('#relatorio_div').css('display', 'none'); //muestro mediante id

        });
    });
    function getSelectValues(select) {
        var result = [];
        var options = select && select.options;
        var opt;

        for (var i=0, iLen=options.length; i<iLen; i++) {
            opt = options[i];

            if (opt.selected) {
                result.push(opt.value || opt.text);
            }
        }
        return result;
    }
</script>
@endsection
