@extends('layouts.app')

@section('content')
<div class="container set_margin-top">
    <div class="row justify-content-center">
        <div class="col-md-9" id="div_master">
            <div class="card">
                <div class="card-header">
                  <h3>Transacciones en Blockchain</h3>
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
