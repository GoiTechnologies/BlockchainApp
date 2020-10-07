@extends('layouts.cajeros')

@section('content')
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
<style>
.btn{
  min-height:100px;
  width: 100%;
}
</style>


<div class="row text-center"
style="margin-top:5%; border:1px;">

    <div class="col-md-12 text-center"
    style="background-color:#002c78; height:150px; margin-bottom:50px;">
          <h2 style="color:#fff; margin-top:50px; font-size:38px;"
           id="action_title">Operaciones en Cajero #4 <i class="fas fa-user"></i></h2>
    </div>


    <div class="col-md-6">
      <a class="btn btn-danger" onclick="location.reload();"
       style="color:#fff; background-color:#002c78; font-size:38px; width:70%">
       <p style="margin-top:15px;">Red de Cajeros ATM <i class="fas fa-laptop-code"></i></p>
     </a>
       <br><br>
        <img width="50%" src="/logo_green.png"/>
        <hr>
        <img style="width:20%;" src="/coin_green2.png"/>


        <div id="camera_div" style="display:none;">
          <h3 class="text-success"><span class="badge badge-success">Escanea el QR Ahora !! </span></h3>
         <video id="preview" class="p-1 border" style="width:40%;"></video><br>
         <div class="btn-group btn-group-toggle mb-5" style="display:none;" data-toggle="buttons">
           <label class="btn btn-primary btn-sm active">
           <input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera
           </label>
           <label class="btn btn-secondary btn-sm" >
           <input type="radio" name="options"  value="2" autocomplete="off"> Back Camera
           </label>
         </div>
         </div>

    </div>
    <div class="col-md-6 text-right" id="div_action">
      <!--<a href="{{ url('/api_reference') }}" style="color:#fff;">API Documentación</a>-->
            <button style="font-size:38px;" onclick="retiro_step2()" class="btn btn-danger">Retiros de GOI
               <i class="fas fa-money-bill-wave"></i></button>
            <hr>
            <button style="font-size:38px;" onclick="deposito_step2()" class="btn btn-danger">Depositar GOI
               <i class="fas fa-money-bill-wave"></i></button>
            <hr>
            <button style="font-size:38px;" onclick="otras()"
             class="btn btn-danger">Operaciones con Bitcoin
              <i class="fab fa-bitcoin"></i></button>
              <hr>

              <a class="btn btn-danger"
               href= 'https://goicoin.net/cajero_four'>
                <p style="font-size:38px; font-size:38px;">Salir <i class="fas fa-times-circle"></i></p>
              </a>
    </div>




    <div class="col-md-6 text-right" id="retiro_step2_div" style="display:none;">
      <div id="retiros_scan">
            <button class="btn btn-danger" style="font-size:38px;"
            onclick="start_scan();">Scan <img style="width:5%;" src="/qr_scan.png"/>
          </button>
      </div>
      <div style="">
          <form id="form_bitcoin" action="/retiro_process_dynamic" method="post">
            @csrf
            <div class="container">

            <input class="form-control" name="wallet_to"
            id="receiber_wallet_cajero" placeholder="Escanea QR de Tu Wallet"
            style="font-size:38px; margin-top:30px;; width:100%; text-align:center;"/>


            <input name="id_user" value="4" placeholder="Page here to send." hidden="">
            <input name="currency" value="GOI" hidden="">

            <br><br>

            <input class="form-control" id="qty_to_send"
            name="amount_dlls" placeholder="Cantidad $$" value="0"
            style="font-size:38px; width:100%; text-align:center;" type="number" disabled/>

            <input class="form-control" id="ide"
            name="ide" value="4" type="number" hidden/>

            <br><br>
            <button class="btn btn-danger" id="btn_confirm"
            style="margin-left:-11%; width:100%; font-size:38px;" disabled="true"
             type="submit">
              Confirmar <span class="lnr lnr-checkmark-circle"></span>
            </button>
            <hr>
            <a class="btn btn-danger"
             href= 'https://goicoin.net/cajero_four'>
              <p style="margin-top:20px; font-size:38px;">Salir <i class="fas fa-times-circle"></i></p>
            </a>
          </div>
        </form>
      </div>
    </div>








    <div class="col-md-6 text-right" id="deposito_step2_div" style="display:none;">
      <div id="retiros_scan">
            <button class="btn btn-danger" style="font-size:24px;"
            onclick="start_scan();">Scanea QR de tu Wallet <img style="width:5%;" src="/qr_scan.png"/>
          </button>
      </div>
      <div>

            <input class="form-control" name="wallet_to"
            id="receiber_wallet_cajero_deposito" placeholder="Escanea QR de Tu Wallet"
            style="font-size:38px; margin-top:30px;; width:100%; text-align:center;" disabled/>
            <hr>
      </div>

      <div class="row" id="selector_billete" style="padding:10px;">

          <div class="col-md-4" style="padding-bottom:15px;">
            <button class="btn btn-danger" onclick="set_amount('20');" style="background-color:#fff; ">
              <img src="/20.png" width="85%"/>
            </button>
          </div>
          <div class="col-md-4" style="padding-bottom:15px;">
            <button class="btn btn-danger" onclick="set_amount('50');" style="background-color:#fff; ">
              <img src="/50.png" width="85%"/>
            </button>
          </div>
          <div class="col-md-4" style="padding-bottom:15px;">
            <button class="btn btn-danger" onclick="set_amount('100');" style="background-color:#fff; ">
              <img src="/100.png" width="90%"/>
            </button>
          </div>
          <div class="col-md-4" style="padding-bottom:15px;">
            <button class="btn btn-danger" onclick="set_amount('200');" style="background-color:#fff; ">
              <img src="/200.png" width="85%"/>
            </button>
          </div>
          <div class="col-md-4" style="padding-bottom:15px;">
            <button class="btn btn-danger" onclick="set_amount('500');" style="background-color:#fff; ">
              <img src="/500.png" width="85%"/>
            </button>
          </div>
          <div class="col-md-4" style="padding-bottom:15px;">
            <button class="btn btn-danger" onclick="set_amount('1000');" style="background-color:#fff; ">
              <img src="/1000.png" width="90%"/>
            </button>
          </div>


          <div class="col-md-12">
            <a class="btn btn-danger"
             href= 'https://goicoin.net/cajero_four'>
              <p style="margin-top:20px; font-size:38px;">Salir <i class="fas fa-times-circle"></i></p>
            </a>
          </div>


      </div>



    </div>











<div class="col-md-6 text-right" id="criptos" style="display:none;">
  <div class="row text-center" style="padding:30px;">

<div class="col-md-12" id="selec_prov">
  <span class="badge badge-primary" style="background-color:#002c78; padding:20px;">
    <h3>Seleccione su Proveedor <i class="fab fa-bitcoin"></i></h3><br> </span>
  <hr>
</div>







<div id="cople" class="col-md-12" style="width:100px;">
  <div class="row">
      <div class="col-md-6">
          <button class="btn btn-outline-danger" onclick="bitcoin('Bitso')">
            <img style="width:80%;" src="/bitso_logo.png"/>
          </button>
      </div>
      <div class="col-md-6">
        <button class="btn btn-outline-danger" onclick="bitcoin('Coinbase')">
          <img style="width:80%;" src="/coinbase_logo.png"/>
        </button>
      </div>
    </div>
</div>




<div id="acople" class="col-md-12" style="width:100px; display:none;">
  <div class="row">
      <div class="col-md-12">
        <div class="alert alert-success" role="alert">
            <h3>Comprar Bitcoin y  abonar a mi Wallet <br><b id="proveedor"></b></h3>
        </div>
      </div>
      <div class="col-md-12">

        <button class="btn btn-danger" style="font-size:24px;"
        onclick="start_scan();">Scanea QR de tu Wallet <img style="width:5%;" src="/qr_scan.png"/>
      </button>


      <form id="form_compra_bitcoin" action="/compra_bitcoin" method="post">
        @csrf
        <input class="form-control" name="wallet_bitcoin"
        id="receiber_wallet_cajero_bitcoin" placeholder="Escanea QR de Tu Wallet" disabled
        style="font-size:38px; margin-top:30px; width:100%; margin-bottom:15px; text-align:center;"/>

        <input value="4" name="ide" hidden>

        <select class="form-control text-center" name="cantidad"
        style="font-size:38px; min-height:60px;" id="qty_bitcoin" required disabled>
            <option value="">Seleccione el Monto... $</option>
            <option value="100">$100.00 MXN Pesos Mexicanos</option>
            <option value="200">$200.00 MXN Pesos Mexicanos</option>
            <option value="500">$500.00 MXN Pesos Mexicanos</option>
            <option value="1000">$1000.00 MXN Pesos Mexicanos</option>
        </select>

        <button class="btn btn-danger" id="btn_confirm_bitcoin"
        style="font-size:24px; margin-top:15px;" disabled
        type="submit">
        Comprar <i class="fas fa-money-bill-alt"></i>
      </button>
</form>


    </div>
</div>


  </div>
</div>






</div>















<script>
var timeOut;
function reset() {
    window.clearTimeout(timeOut);
    timeOut = window.setTimeout( "redir()" , 200000 );
}
function redir() {
    window.location = "https://goicoin.net/cajero_four";
}
window.onload = function() { setTimeout("redir()" , 540000) };
window.onmousemove = reset;
window.onkeypress = reset;





var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
scanner.addListener('scan',function(content){

if(content.includes("&")){
    $("#main-div").css("display","block");
    $("#camera_div").css("display","none");
  content = content.replace("&"," ");
  content = content.split(" ");
  $('#receiber_wallet_cajero').val(content[0]);
  $('#receiber_wallet_cajero_deposito').val(content[0]);
  $('#qty_to_send').val(content[1]);
  $('#qty_to_send').focus();
  $('#btn_confirm').prop( "disabled" , false );
  $('#btn_scan_02').remove();
  $('#receiber_wallet_cajero_bitcoin').val(content);
  $('#qty_bitcoin').prop( "disabled" , false );
  $('#btn_confirm_bitcoin').prop( "disabled" , false );
  $('#receiber_wallet_cajero_bitcoin').prop( "disabled" , false );
  $('#qty_to_send').prop( "disabled" , false );
  $('#receiber_wallet_cajero_deposito').prop( "disabled" , false );
}else{


  content = content.replace("bitcoin:"," ");
    $("#main-div").css("display","block");
    $("#camera_div").css("display","none");
    $('#receiber_wallet_cajero').val(content);
    $('#qty_to_send').focus();
    $('#btn_scan_02').remove();
    $('#btn_confirm').prop( "disabled" , false );
    $('#receiber_wallet_cajero_deposito').val(content);
    $('#receiber_wallet_cajero_bitcoin').val(content);
    $('#qty_bitcoin').prop( "disabled" , false );
    $('#btn_confirm_bitcoin').prop( "disabled" , false );
    $('#receiber_wallet_cajero_bitcoin').prop( "disabled" , false );
    $('#qty_to_send').prop( "disabled" , false );
    $('#receiber_wallet_cajero_deposito').prop( "disabled" , false );
}

});

function start_scan_thaler(){
$("#crito_step3_div").remove();
start_scan();
}

function start_scan(){
$("#main-div").css("display","none");
$("#camera_div").css("display","block");
$('html,body').animate({
     scrollTop: $("#camera_div").offset().top
 }, 'slow');
Instascan.Camera.getCameras().then(function (cameras){
  if(cameras.length>0){
    scanner.start(cameras[0]);
    $('[name="options"]').on('change',function(){
      if($(this).val()==1){
        if(cameras[0]!=""){
          scanner.start(cameras[0]);
        }else{
          alert('No Front camera found!');
        }
      }else if($(this).val()==2){
        if(cameras[1]!=""){
          scanner.start(cameras[1]);
        }else{
          alert('No Back camera found!');
        }
      }
    });
  }else{
    console.error('No cameras found.');
    alert('No cameras found.');
  }
}).catch(function(e){
  console.error(e);
  alert(e);
});
}


function set_amount(am){
  $("#selector_billete").remove();
  if(verify_wallet1()){
        // codigo de exito mandar a recibir en billetero y abonar el deposito en wallet
        wallet = $("#receiber_wallet_cajero_deposito").val();
        cantidad = am;
        //alert("Gois: "+am+" Wallet: "+wallet);

        var form = document.createElement("form");
        var element1 = document.createElement("input");
        var element2 = document.createElement("input");
        var element3 = document.createElement("input");
        var element4 = document.createElement("input");

        form.method = "POST";
        form.action = "/deposito_process_dynamic";

        element1.value='{{ csrf_token() }}';
        element1.name="_token";
        element1.style.visibility = "hidden";
        form.appendChild(element1);
        element2.value='4';
        element2.name="ide";
        element2.style.visibility = "hidden";
        form.appendChild(element2);
        element3.value=am;
        element3.name="cantidad";
        element3.style.visibility = "hidden";
        form.appendChild(element3);
        element4.value=wallet;
        element4.name='wallet';
        element4.style.visibility = "hidden";
        form.appendChild(element4);
        document.body.appendChild(form);

        form.submit();
  }
}
function verify_wallet1(){
  wallet = $("#receiber_wallet_cajero_deposito").val();
  if(wallet == ""){
    $("#receiber_wallet_cajero_deposito").focus();
    return false;
  }
  return true;
}

function retiro_step2(){
  $("#div_action").remove();
  $("#retiro_step2_div").css("display","block");
  $("#action_title").empty();
  $("#action_title").append("Retiro de Efectivo ATM <i class='fas fa-money-bill-wave'></i>");
}
function deposito_step2(){
  $("#div_action").remove();
  $("#deposito_step2_div").css("display","block");
  $("#action_title").empty();
  $("#action_title").append("Deposito de Efectivo ATM <i class='fas fa-money-bill-wave'></i>");
}

function otras(){
  $("#div_action").remove();
  $("#criptos").css("display","block");
  $("#action_title").empty();
  $("#action_title").append("Operaciones de Exchange <i class='fas fa-exchange-alt'></i>");

}


function bitcoin(prov){
  $("#cople").css("display","none");
  $("#acople").css("display","block");
  $("#proveedor").append(prov);
  $('#selec_prov').remove();
}
</script>
@endsection
