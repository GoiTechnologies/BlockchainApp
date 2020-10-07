@extends('layouts.app')

@section('content')
<script src="https://kit.fontawesome.com/e838e8d238.js" crossorigin="anonymous"></script>
<style>
.src-image {
  display: none;
}

.card {
  overflow: hidden;
  position: relative;
  border: 1px solid #CCC;
  border-radius: 8px;
  text-align: center;
  padding: 0;
  background-color: #000;
  color: rgb(136, 172, 217);
}

.card .header-bg {
  /* This stretches the canvas across the entire hero unit */
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 70px;
  border-bottom: 1px #FFF solid;
  /* This positions the canvas under the text */
  z-index: 1;
}
.card .avatar {
  position: relative;
  margin-top: 15px;
  z-index: 100;
}

.card .avatar img {
  width: 100px;
  height: 100px;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius: 50%;
  border: 5px solid rgba(0,0,30,0.8);
}
</style>
<div class="container set_margin-top">
    <div class="row justify-content-center">
        <div class="col-md-8" id="div_master">
            <div class="card">
                <div class="card-header text-left text-white">
                  <h5>Carnet de Identidad Blockchain </h5>
                  <div id="placeHolder"></div>


                  <div style="background-color:#fff; color:#000; padding:10px;">

                  Nombre<br>{{$user->name}}
                  <hr>




                  Correo: {{$user->email}}<br>
                  Nick name: <b>{{$user->nick_name}}</b><br>
                  Registro: {{$user->created_at}}<br>
                  <h5><span class="badge badge-success" style="font-size:20px;">
                    GOI Balance:
                    $ {{$user->saldo}}

                  </span>
                </h5>
                  </div>


                </div>

                <div class="card-body">







<p>
  <a class="btn btn-outline-primary"
  style="width:100%; color:#fff; font-size:18px;"
  data-toggle="collapse" href="#collapseExample"
  role="button" aria-expanded="false" aria-controls="collapseExample">
    Mostrar / Ocultar Wallet <i class="fas fa-wallet"></i>
  </a>
</p>
<div class="collapse" id="collapseExample">
  <div class="card card-body" style="padding:10px;
   background-color:#fff; color:#000;">
    @if($user->blockchain_passcode != null)
     <p><br>
      {{$user->wallet}}
     </p>
     @endif
  </div>
</div>


                   <hr>
                   @if($user->blockchain_passcode == null)
                       <div class="alert alert-danger" role="alert">
                         <b style="font-weight: bold; font-size:22px;">No Tienes Wallet!! </b>
                         <br>Para crear tu wallet, primero registra un codigo de seguridad a continuación.
                       </div>

                       <div class="row">
                         <div class="col-md-6">
                           <input placeholder="Ingresa un password seguro." id="pass_code" type="password" class="form-control"/>
                         </div>
                        <div class="col-md-6">
                          <button class="btn btn-outline-success"
                          style="color:#fff; font-size:18px;"
                          onclick="request_blockchain_code();">
                          Guardar Pass Code <i class="fas fa-user-lock"></i></button>
                        </div>
                       </div>
                   @else

                   @if($user->wallet != 0)
                     <span><button class="btn btn-outline-primary"
                       style="width:100%; color:#fff; font-size:18px;"
                        onclick="wallet_explore();">Explorador de Wallet's
                        <i class="fas fa-project-diagram"></i></button></span>
                   @else
                      @if($user->wallet == 0)
                      <button class="btn btn-outline-primary"
                      style="color:#fff; font-size:18px;"
                      onclick="create_wallet();">
                      Descargar Wallet <i class="fas fa-wallet"></i>
                      <i class="fas fa-download"></i> </button>
                      @else
                      <div class="row">
                        <div class="col-md-12">
                        <input class="form-control"
                        type="number" id="initial_balance"
                         placeholder="Balance de la wallet"
                          min="0" value="0"  hidden />
                        </div>
                        <div class="col-md-12">
                        <button class="btn btn-outline-primary"
                        style="color:#fff; font-size:18px;"
                        onclick="add_balance();">
                          Click para Inicializar Wallet <i class="fas fa-user-lock"></i></button>
                        </div>
                      </div>
                      @endif
                   @endif
                   @endif


                   @if($user->wallet != 0)
                   <hr>

                 <div class="row" style="margin-top:100px;">

                   <div class="col-md-8">
                     <span class="badge badge-success">
                    <h4 class="text-white" style="margin-top:5px;">
                      -- Enviar CriptoMonedas <i class="fas fa-coins" style="margin-right:10px;"></i>

                    </h4>
                    </span>
                    <hr>
                    </div>

                   <div class="col-md-4">
                    <button class="btn btn-outline-primary"
                    style="width:100%; font-size:18px; color:#fff; "
                     onclick="start_scan();">
                    <b>Scaner QR <span class="lnr lnr-frame-expand"></span></b>
                    </button>
                    </div>

                    <div class="col-md-12">
                    <hr>
                    </div>
                 </div>

                   <div class="row" id="transfer_div" >
                     <div class="col-md-6" style="padding-bottom:10px;">
                       <input placeholder="Destinatario (Wallet)" id="receiber_wallet" class="form-control"/>
                       </div>
                       <div class="col-md-4" style="padding-bottom:10px;">

                       <input placeholder="Cantidad $" id="qty_to_send" class="form-control"/>
                       </div>
                       <div class="col-md-2" style="padding-bottom:10px;">
                       <button class="btn btn-outline-success"
                       style="width:100%; color:#fff;"
                        onclick="send_transacction('{{$user->wallet}}'); $(this).remove();">
                        Enviar <span class="lnr lnr-location"></span></button>
                       </div>
                   </div>
                   @endif



                   @if($user->user_level != '1')
                   <br>
                         <div class="alert alert-success" role="alert" style="display:none;">
                           <h4 class="alert-heading">Tienes #1 Mineros Trabajando!</h4>
                           <p>Los mineros realizan el bolcado de memoria de transacciones, minando un bloque (minimo una operacion por bloque),
                           se recomienda realizar la mina 1 vez a la semana. Puede programar un minado automatico (En Desarrollo).</p>
                           <hr>
                           <div class="row text-center">
                             <div class="col-md-3">

                               <button class="btn btn-outline-success" onclick="miner_data();" data-toggle="modal" data-target="#exampleModal">
                                  <img style="width:100px;" src="/miner.png"/>
                               </button>
                             </div>
                             <div class="col-md-3">
                               <button class="btn btn-outline-secondary">
                                  <img style="width:100px;" src="/miner_off.png"/>
                               </button>
                             </div>
                             <div class="col-md-3">
                               <button class="btn btn-outline-secondary">
                                  <img style="width:100px;" src="/miner_off.png"/>
                               </button>
                             </div>
                             <div class="col-md-3">
                               <button class="btn btn-outline-secondary">
                                  <img style="width:100px;" src="/miner_off.png"/>
                               </button>
                             </div>
                           </div>
                         </div>

                   @endif


                </div>
            </div>
        </div>



        <div class="col-md-4" id="div_secondary">

          @if($user->user_level != '1')
          <div class="card">
              <div class="card-header">
                <img src="logo_green_b.png" width="100%" style="margin-bottom:80px;"/>

              <span class="badge badge-warning">
                  <h4 style="margin-top:5px;">-- Retiros en Cajeros <i class="fas fa-laptop-code"></i></h4></span>
              </div>
              <div class="card-body">

                <div>


                  <button id="btn_qr" class="btn btn-outline-primary"
                  style="width:100%; color:#fff; font-size:18px;"
                  onclick="mostrar_QR('{{$user->wallet}}')">Mostrar QR de Wallet
                  <i class="fas fa-qrcode"></i>
                </button>
                <br>



                  <br><br>



                  <span class="badge badge-success"
                  style="font-size:18px; margin-bottom:10px;">Retiro en Efectivo <i class="fas fa-share"></i></span>


                  <select class="form-control"
                  id="qty_solicitar" style="width:100%; margin-bottom:10px; font-size:16px;">
                    <option value="20">Seleccione el Monto</option>
                    <option value="20">20.00 Pesos MXN</option>
                    <option value="50">50.00 Pesos MXN</option>
                    <option value="100">100.00 Pesos MXN</option>
                    <option value="200">200.00 Pesos MXN</option>
                    <option value="500">500.00 Pesos MXN</option>
                    <option value="1000">1000.00 Pesos MXN</option>
                  </select>



                  <button id="btn_qr" class="btn btn-outline-primary"
                  style="width:100%; color:#fff; font-size:18px;"
                  onclick="solicitar_pago('{{$user->wallet}}')">Solicitar Retiro <i class="fas fa-money-bill-wave"></i></button>




                  <div id="placeHolder_solicitarpago"></div>
                  <br>





                  <button class="btn btn-outline-primary btn-sm"
                  onclick="window.location.href = '/blockchain_blocks';"
                  style="width:100%; margin-top:100px; color:#fff; font-size:18px;" >Block Explorer
                  <span class="lnr lnr-text-align-center"></span></button>

                  <div style="display:none;">
                  <button class="btn btn-outline-primary btn-sm" style="width:100%;"
                  onclick="window.location.href = '/transacciones_blockchain';">Transacciones</button>
                  <hr>
                  <button class="btn btn-outline-primary btn-sm"
                  onclick="window.location.href = '/blockchain_blocks';"
                  style="width:100%;" >Blocks Explorer </button>

                  <hr>
                  <button class="btn btn-outline-success btn-sm"
                  onclick="blockchain_minero();"
                  style="width:100%;" >Minar Bloques</button>
                </div>


                </div>
                <hr>
                @foreach($transacciones as $t)
                <p style="color:#fff;">
                Ultima Transación:
                {{ $t->receiver }}<br>
                Cantidad:
                {{ $t->amount }}
                </p>
                <hr>
                @endforeach

              </div>
          </div>
          @else






          <div class="card">
              <div class="card-header">
                <h5>Mi Wallet QR es:</h5>
                Host de Nodo: https://blockexplorer.club
              </div>
              <div class="card-body">

                <div>
                  <button id="btn_qr" class="btn btn-outline-primary"
                  style="width:100%;"
                  onclick="mostrar_QR('{{$user->wallet}}')">Mostrar QR de Wallet</button>



                  <div id="placeHolder"></div>
              </div>
          </div>

          @endif



        </div>

        <div class="col-md-12" id="results_div" style="display:none; margin-top:50px;">
            <span class="badge badge-pill badge-primary" style="font-size:22px;">Wallet's en la Blockchain</span>
            <hr>
            <div id="wallet_explore_div" style="max-width:100%; overflow: auto;">
            </div>
        </div>

    </div>
</div>



</div>
























<div class="col container text-center" id="camera_preview" style="display:none;">

  <div class="col-sm-12">
    <video id="preview" class="p-1 border" style="width:20%;"></video>
  </div>
  <script type="text/javascript">
    var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
    scanner.addListener('scan',function(content){

      if(content.includes("&")){
      content = content.replace("&"," ");
      content = content.split(" ");
      $('#receiber_wallet').val(content[0]);
      $('#qty_to_send').val(content[1]);
      $('#qty_to_send').focus();
      $("#camera_preview").css('display','none');
      $('html, body').animate({
          scrollTop: $("#transfer_div").offset().top
      }, 2000);
    }else{
      $('#receiber_wallet').val(content);
      $('#qty_to_send').focus();
      $("#camera_preview").css('display','none');
      $('html, body').animate({
          scrollTop: $("#transfer_div").offset().top
      }, 2000);
    }

      //window.location.href=content;
    });


  function start_scan(){
    $("#camera_preview").css('display','block');
    $('html, body').animate({
        scrollTop: $("#preview").offset().top
    }, 2000);
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


mostrar_QR('{{$user->wallet}}');
$("#logo_img").css("height","20px");
$("#logo_img").css("width","160px");




var a = '';
function detec() {

    if (navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)) {
        a = true;
        $("#d_header").remove();
        $("#main_c").css("background-color","#000");
        $("#main_c").css("color","#fff");
        $("#main_c2").css("background-color","#000");
        $("#main_c2").css("color","#fff");
    } else {
      $("#placeHolder").css("width", "15%");
      $("#placeHolder").css("margin-bottom", "20px");
        a = false;
    }
}
detec();




  </script>
  <div class="btn-group btn-group-toggle mb-5" data-toggle="buttons">
    <label class="btn btn-primary btn-sm active">
    <input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera
    </label>
    <label class="btn btn-secondary btn-sm">
    <input type="radio" name="options" value="2" autocomplete="off"> Back Camera
    </label>
  </div>
</div>


@endsection
