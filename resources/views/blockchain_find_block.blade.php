@extends('layouts.app')

@section('content')
<div class="container set_margin-top">
    <div class="row justify-content-center">
        <div class="col-md-9" id="div_master">
            <div class="card">
                <div class="card-header">
                  <h3>Bloques en Blockchain</h3>
                  <input class="form-control"
                  style="width:80%;"
                  placeholder="Ingresa el hash y preciona 'Enter' para buscar..."/>

                  <button class="btn btn-primary"
                  style="margin-left:82%; margin-top:-62px;">
                    Buscar Bloque <span class="lnr lnr-magnifier"></span>
                  </button>

                  <span class="badge badge-success" style="width:100%;"><h5>Bloque Encontrado<br> Hash:<hr>{{$hash}}</h5></span>
                </div>

                <div class="card-body" id="respuesta_div">



                </div>
            </div>
        </div>



        <div class="col-md-3" id="div_secondary">
          <div class="card">
              <div class="card-header">
                <a href="/blockchain_blocks"><h5>Regresar</h5></a>


              </div>

          </div>
        </div>


    </div>
</div>
<script>
$(document).ready(function() {
      blockchain_find_block_back('{{$hash}}');
});
</script>
@endsection
