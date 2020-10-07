@extends('layouts.app')

@section('content')



<div class="container" style="margin-top:5%;">
<div class="card text-center">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="#" onclick="block_01();">Cajero ID:1 MXN</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="block_02();">Stock de Billetes MXN</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="block_03();">Historial</a>
      </li>
    </ul>
  </div>
  <div class="card-body" style="min-height:500px;">





    <div id="block_01">
    <h4 class="card-title">Cajero Automatico No.1 MXN </h4>
    <hr>
    <div class="row">
      <div class="col-md-3">
        <img style="width:100%;" src="/caj_active.png"/>
      </div>

      <div class="col-md-3 text-left">
        <h5>Ubicacion
          <img
          width="20%"
          src="https://www.seekpng.com/png/detail/347-3471100_icono-ubicacion-vector-png-icono-de-ubicacion-png.png"/></h5>
        <hr>
        Domicilio: Ludwig Van Bethoven 5612, La Estancia 45030<br>
        Municipio: Zapopan, Jalisco, Mexico.
      </div>

      <div class="col-md-3 text-left">
        <h5>Divisas y Moneda
          <img
          width="24%"
          src="https://www.pngkey.com/png/detail/92-925395_stack-of-coins-clip-art-stack-of-coins.png"/>
        </h5>
        <hr>
        Criptomoneda: Thaler<br>
        Blockchain: https://blockexplorer.club.
        Moneda: MXN (Peso)
      </div>
      <div class="col-md-3 text-left">
        <h5>GroupConcept

          <img
          width="22%"
          src="https://conquestimaging.com/wp-content/uploads/2018/03/24-7-tech-support-active.png"/>

        </h5>
        <hr>
        <br><br>
        <a href="#" class="btn btn-primary"><b>(0) Sin Notificaciones</b></a>
      </div>



      <div class="col-md-6 text-center">

      </div>


      <div class="col-md-6 text-center">
        <br><br>
        <hr>
        <h3>Balance en Cajero: </h3><br>
          <h1 style="font-size:50px;"><a href="#" class="badge badge-success">$ 90.00 PESOS MXN</a></h1>

      </div>


    </div>
  </div>






  <div id="block_02" style="display:none;">
  <h4 class="card-title">Billetes Almacenados en Cajero No.1 MXN </h4>
  <hr>
  <div class="">


    <table class="table table-hover">
  <thead class="bg-dark text-white">
    <tr>
      <th scope="col">Denominacion $</th>
      <th scope="col">Descripcion</th>
      <th scope="col">Almacenados</th>
      <th scope="col">Estado</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">20</th>
      <td>Billete de Veinte Pesos Mexicanos</td>
      <td><h5><img width="15%" src="/20.png"> = 2</h5></td>
      <td><span class="badge badge-pill badge-success">Disponible</span></td>
    </tr>
    <tr>
      <th scope="row">50</th>
      <td>Billete de Cincuenta Pesos Mexicanos</td>
      <td><h5><img width="15%" src="/50.png"> = 1</h5></td>
      <td><span class="badge badge-pill badge-success">Disponible</span></td>
    </tr>
    <tr>
      <th scope="row">100</th>
      <td>Billete de Cien Pesos Mexicanos</td>
      <td><h5><img width="15%" src="/100.png"> = 0</h5></td>
      <td><span class="badge badge-pill badge-danger">No Disponible</span></td>
    </tr>
    <tr>
      <th scope="row">200</th>
      <td>Billete de Dos Cientos Pesos Mexicanos</td>
      <td><h5><img width="15%" src="/200.png"> = 0</h5></td>
      <td><span class="badge badge-pill badge-danger">No Disponible</span></td>
    </tr>
    <tr>
      <th scope="row">500</th>
      <td>Billete de Quinientos Pesos Mexicanos</td>
      <td><h5><img width="15%" src="/500.png"> = 0</h5></td>
      <td><span class="badge badge-pill badge-danger">No Disponible</span></td>
    </tr>
    <tr>
      <th scope="row">1000</th>
      <td>Billete de Quinientos Pesos Mexicanos</td>
      <td><h5><img width="15%" src="/1000.png"> = 0</h5></td>
      <td><span class="badge badge-pill badge-danger">No Disponible</span></td>
    </tr>
  </tbody>
</table>


  </div>
</div>





<div id="block_03" style="display:none;">
<h4 class="card-title">Historial Entradas de Billetes MXN </h4>
<hr>
<div class="">
  <table class="table table-hover">
<thead class="bg-dark text-white text-left">
  <tr>
    <th scope="col">Denominacion $</th>
    <th scope="col">Emisor</th>
    <th scope="col">Fecha </th>
    <th scope="col">Estado</th>
  </tr>
</thead>
<tbody class="text-left">

  @foreach($transacciones as $t)
  <tr>
    <th scope="row" class="text-center">{{$t->amount}}</th>
    <td><p style="font-size:8px;">Wallet:<br>{{$t->receiver}}</p></td>
    <td>{{$t->created_at}}</td>
    <td><span class="badge badge-pill badge-success">Exitoso</span></td>
  </tr>
  @endforeach
</tbody>
</table>
{{ $transacciones->links() }}
</div>
</div>




  </div>
</div>
</div>




<script>
function block_01(){
  $("#block_02").css("display","none");
  $("#block_03").css("display","none");
  $("#block_01").css("display","block");
}

function block_02(){
  $("#block_01").css("display","none");
  $("#block_03").css("display","none");
  $("#block_02").css("display","block");
}


function block_03(){
  $("#block_01").css("display","none");
  $("#block_02").css("display","none");
  $("#block_03").css("display","block");
}

</script>
@endsection
