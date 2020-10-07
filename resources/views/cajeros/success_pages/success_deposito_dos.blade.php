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
</style>
<body>



<div class="container" style="margin-top:9%;">
	<div class="text-center">
		<div class="card" style="margin-top:4%;">
			<div class="card-header" style="background-color: transparent;">
				<h1 class="text-success" style="font-size:44px;">
          Deposito en efectivo a Wallet con Exito. <i class="fas fa-check-circle"></i><hr>
					<img style="width:65%;" src="/logo_green.png"/></h1>


			</div>
			<div class="card-body">
				<h1 style="color:#002c78; font-size:48px;">Tu deposito por: <br>
          ${{$cantidad}}.00 Pesos MXN</h1>
				<hr>
				<strong>Deposito abonado a tu Wallet: <br>
        <p style="font-size:10px;">{{$wallet_to}}</p></strong>

				<br>
			</div>
			<div class="card-footer">
				<button class="btn btn-success btn_custom"
         onclick="window.location.href = 'https://goicoin.net/cajero_two';"
         type="submit">
	        Finalizar Operación <i class="fas fa-check-circle"></i>
	      </button>


			</div>
		</div>
	</div>
</div>

<script>
let intervalId = window.setInterval(function(){
  window.location.replace("https://goicoin.net/cajero_two");
}, 50000);

</script>
</body>
