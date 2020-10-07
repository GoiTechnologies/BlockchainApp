@extends('layouts.app')

@section('content')
<div class="container set_margin-top">
    <div class="row justify-content-center">
        <div class="col-md-9" id="div_master">
            <div class="card">
                <div class="card-header">
                  <h3>Bloques en Blockchain</h3>
                  <form method="post" action="/blockchain_find_block">
                    @csrf
                  <input class="form-control"
                  style="width:80%;" name="hash"
                  placeholder="Ingresa el hash y preciona 'Enter' para buscar..."/>

                  <button class="btn btn-primary" type="submit"
                  style="margin-left:82%; margin-top:-62px;">
                    Buscar Bloque <span class="lnr lnr-magnifier"></span>
                  </button>
                </form>

                </div>

                <div class="card-body" id="respuesta_div">



                </div>
            </div>
        </div>



        <div class="col-md-3" id="div_secondary">
          <div class="card">
              <div class="card-header">
                <a href="/home"><h5>Regresar</h5></a>


              </div>

          </div>
        </div>


    </div>
</div>
@endsection
