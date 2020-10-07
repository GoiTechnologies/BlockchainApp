<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>
		<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.js"></script>
  <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<script src="https://kit.fontawesome.com/e838e8d238.js" crossorigin="anonymous"></script>
</head>
<style>
.btn_custom{
		font-size:30px;
		height: 100px;
		width: 80%;
}
body{
  background-image: url('/fondo.jpg');
}
h2{
  color:#002c78;
}
</style>
<body>



<div class="container" style="margin-top:9%;">
	<div class="text-center">
		<div class="card" style="margin-top:4%;">
			<div class="card-header" style="background-color: transparent;">
				<h2 class="">Recibiendo: Ingresa (Abajo) los Billetes <i class="fas fa-money-bill-alt"></i> <hr>
					<img style="width:45%;" src="/logo_green.png"/><br>
          <img style="width:45%;" src="/bill.jpg"/>
        </h2>
			</div>
			<div class="card-body">

				<h2 style="font-size:48px;">Esperando tú Deposito... <br>${{$cantidad}} MXN</h2>


				<hr>
				<strong>Nota: Ingresa los billetes de uno por uno.</strong>

				<br>
			</div>
			<div class="card-footer">


        <form  action="/cancelar_operacion" method="post">
          @csrf
          <input class="form-control" name="ide" value="3" hidden/>
          <input class="form-control" name="wallet" value="{{$wallet_to}}" hidden/>
          <input class="form-control" name="cantidad" value="{{$cantidad}}" hidden/>

				<button class="btn btn-outline-danger btn_custom"
         type="submit">
	        <span class="lnr lnr-home"></span> Cancelar Operaciòn
	      </button>

      </form>


			</div>
		</div>
	</div>
</div>

<script>
let intervalId = window.setInterval(function(){
  get_transaction_state();
}, 5000);

function get_transaction_state(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
      $.ajax({
      type: "POST",
      url: '/api/check_transaction_pending',
      data:{
        'ide': '3'
      }
      ,
      success: function(respuesta) {
            //alert(respuesta.state);
            if(respuesta.state == '3'){
              window.clearInterval(intervalId);
              window.location = "https://goicoin.net/success_deposito?cantidad={{$cantidad}}&ide=3&response=goicoin&wallet={{$wallet_to}}";
            }
      }
      });
}



</script>
</body>
