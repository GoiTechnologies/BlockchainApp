@extends('layouts.app')

@section('content')
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
  background-color: #284c79;
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


<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<script src="https://kit.fontawesome.com/e838e8d238.js" crossorigin="anonymous"></script>

<div class="container">
    <div class="row">

        <div class="col-sm-3">
            <div class="card" style="min-height:370px;">
                <canvas class="header-bg" width="250" height="70" id="header-blur"></canvas>
                <div class="avatar">
                    <img src="" alt="" />
                </div>
                <div class="content">
                    <h3 class="text-white">Goi Blockchain <i class="fab fa-battle-net"></i></h3>
                      <hr>
                    <div class="text-left" style="padding:10px;">
                       <p class="text-white">User:<br>
                         {{$user->name}}<br>
                         Correo: {{$user->email}}<br>
                         Nick name: <b>{{$user->nick_name}}</b><br>
                         Registro: {{$user->created_at}}<br>
                         Balance:
                        <span class="badge badge-pill badge-success" style="background-color:#2ab720;">
                         $ {{number_format($user->saldo)}}
                         </span>
                       </p>
                     </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3" >
            <div class="card" style="min-height:370px;">
                <canvas class="header-bg" width="250" height="70" id="header-blur"></canvas>
                <div class="avatar">
                    <img src="" alt="" />
                </div>
                <div class="content">
                  <h3 class="text-white">Mi Wallet <i class="fas fa-qrcode"></i></h3>
                    <hr>
                  <div class="text-left" style="padding:15px;">
                    <p class="text-white">
                       {{$user->wallet}}</p><br>

                  </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3" >
            <div class="card" style="min-height:370px;">
                <canvas class="header-bg" width="250" height="70" id="header-blur"></canvas>
                <div class="avatar">
                    <img src="" alt="" />
                </div>
                <div class="content">
                  <h3 class="text-white">Solicitar Retiro <i class="fas fa-money-bill-wave"></i></h3>
                    <hr>
                  <div class="row text-center" style="padding:15px;">
                  <div class="col-md-6">
                    <button class="btn btn-danger"
                    style="width:100%; margin-bottom:15px;"
                    onclick="set_amount('20');"
                    style="background-color:#fff; width:100%;">
                      $20 MXN
                    </button>
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-danger"
                    style="width:100%; margin-bottom:15px;"
                    onclick="set_amount('50');"
                    style="background-color:#fff; width:100%;">
                      $50 MXN
                    </button>
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-danger"
                    style="width:100%; margin-bottom:15px;"
                    onclick="set_amount('100');"
                    style="background-color:#fff; width:100%;">
                      $100 MXN
                    </button>
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-danger"
                    style="width:100%; margin-bottom:15px;"
                    onclick="set_amount('200');"
                    style="background-color:#fff; width:100%;">
                      $200 MXN
                    </button>
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-danger"
                    style="width:100%;"
                    onclick="set_amount('500');"
                    style="background-color:#fff; width:100%;">
                      $500 MXN
                    </button>
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-danger"
                    style="width:100%;"
                    onclick="set_amount('1000');"
                    style="background-color:#fff; width:100%;">
                      $1000 MXN
                    </button>
                  </div>
                  </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card" style="min-height:370px;">
                <div class="content">
                  <div style="padding:15px;">
                  <div class="text-left">
                    <h4 class="text-white">
                    Host de Nodo: https://blockexplorer.club</h4>
                  </div>
                    <hr>
                    <button id="btn_qr" class="btn btn-danger"
                    style="width:100%;"
                    onclick="mostrar_QR('{{$user->wallet}}')">Mostrar QR de Wallet <span class="lnr lnr-dice"></span></button>
                    <br><br>
                    <input class="form-control" placeholder="0.0" id="qty_solicitar" type="number" style="width:35%;"/>
                    <button id="btn_qr" class="btn btn-danger"
                    style="width:60%; margin-left:40%; margin-top:-55px;"
                    onclick="solicitar_pago('{{$user->wallet}}')">Solicitar Pago</button>
                    <div id="placeHolder"></div>
                    <div id="placeHolder_solicitarpago"></div>
                    <br>
                    <button class="btn btn-danger btn-sm" style="width:100%; margin-bottom:15px;"
                    onclick="window.location.href = '/transacciones_blockchain';">Transacciones <span class="lnr lnr-book"></span></button>
                    <button class="btn btn-danger btn-sm"
                    onclick="window.location.href = '/blockchain_blocks';"
                    style="width:100%; margin-bottom:15px;" >Block Explorer <span class="lnr lnr-text-align-center"></span></button>
                    <button class="btn btn-success btn-sm"
                    onclick="blockchain_minero();"
                    style="width:100%; margin-bottom:15px; display:none;" disabled>
                    Minar Bloques <span class="lnr lnr-layers"></span></button>
                    <button class="btn btn-danger btn-sm"
                    style="width:100%;"
                    onclick="wallet_explore();">
                      Explorador de Wallet's <span class="lnr lnr-menu"></span></button>
                  </div>






                </div>
            </div>
        </div>
    </div>
</div>


<img class="src-image"  src="/g001.jpg"/>

<img class="src-image"  src="/g002.jpg"/>

<img class="src-image"  src="/g003.jpg"/>






<div class="container set_margin-top">
    <div class="row justify-content-center">




        <div class="col-md-12" id="div_master" style="">



            <div class="card">


                <div class="card-body">

                  <hr>
                  <h4>Cotizacion en MXN</h4>
                  <form method="post" action="/update_cotizacion">
                    @csrf
                  <input class="form-control text-center"
                  name="new_val" style="width:40%; margin-left:30%;"
                  type="number"
                   value="{{$cotizacion_mxn->value}}"/><br>
                   <button class="btn btn-success"
                   style="width:40%;"
                   type="submit"
                   >Actualizar Cotizacion</button>
                 </form>



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
                          <button class="btn btn-success"
                           onclick="request_blockchain_code();">Guardar Pass Code <span class="lnr lnr-thumbs-up"></span></button>
                        </div>
                       </div>
                   @else

                   @if($user->wallet != 0)

            @else
                      @if($user->saldo > 0)
                      <button class="btn btn-success" onclick="create_wallet();">Descargar Wallet <span class="lnr lnr-download"></span></button>
                      @else
                      <div class="row">
                        <div class="col-md-12">

                          @if($user->user_level == '1')
                            <input class="form-control" type="number"
                             id="initial_balance"
                            placeholder="Balance de la wallet"
                             min="1" value="25000000"  hidden />
                          @else
                          <input class="form-control"
                          type="number" id="initial_balance"
                          placeholder="Balance de la wallet" min="1"
                          value="0"  hidden />
                          @endif

                        </div>
                        <div class="col-md-12">
                        <button class="btn btn-success" onclick="add_balance();">Inicialiar Wallet</button>
                        </div>
                      </div>
                      @endif
                   @endif
                   @endif


                   @if($user->wallet != 0)
                   <hr>



                   <h
                   4 class="text-white">Enviar Pago</h4>

                     <button class="btn btn-danger"
                     style="margin-left:85%; margin-top:-60px;"
                      onclick="start_scan();">Leer QR
                      <span class="lnr lnr-frame-expand"></span></button>








                   <div class="row" id="transfer_div">
                     <div class="col-md-6">
                       <input placeholder="Destinatario (Wallet)" id="receiber_wallet" class="form-control"/>
                       </div>
                       <div class="col-md-4">
                       <input placeholder="Cantidad $" id="qty_to_send" class="form-control"/>
                       </div>
                       <div class="col-md-2">
                       <button class="btn btn-success"
                       onclick="send_transacction('{{$user->wallet}}'); $(this).remove();">Enviar <span class="lnr lnr-location"></span></button>
                       </div>
                   </div>
                   @endif



                   @if($user->user_level == '1')
                   <br>
                         <div class="alert alert-danger" role="alert">
                           <h4 class="alert-heading">Mineros Trabajando!</h4>
                           <p>Los mineros realizan el bolcado de memoria de transacciones, minando un bloque (minimo una operacion por bloque),
                           Puede programar un minado automatico (mediante la API).</p>
                           <hr>
                           <div class="row text-center">
                             <div class="col-md-3">

                               <button class="btn btn-success" onclick="miner_data();" data-toggle="modal" data-target="#exampleModal">
                                  <img style="width:100px;" src="/miner.png"/>
                               </button>
                             </div>
                             <div class="col-md-3">
                               <button class="btn btn-secondary">
                                  <img style="width:100px;" src="/miner_off.png"/>
                               </button>
                             </div>
                             <div class="col-md-3">
                               <button class="btn btn-secondary">
                                  <img style="width:100px;" src="/miner_off.png"/>
                               </button>
                             </div>
                             <div class="col-md-3">
                               <button class="btn btn-secondary">
                                  <img style="width:100px;" src="/miner_off.png"/>
                               </button>
                             </div>
                           </div>
                         </div>

                   @endif






                   <div class="alert alert-info" role="alert">
                     <h4 class="alert-heading">Administracion de Cajeros Automaticos!</h4>
                     <hr>
                     <div class="row">

                    <div class="col-md-8">
                     <p>
                       Visualiza los cajeros activos y en operacion asi como
                       la gestion de billetes para atender retiros y los saldos actuales en
                       cada dispositivo.<br>
                       Visualiza Alertas de Atención a cajeros (Retiro y Carga de
                       billetes en Caja de payout).
                     </p>
                     </div>

                     <div class="col-md-4">
                     <button class="btn btn-info" disabled
                     onclick="location.href = '/cajeros_admin';">
                        <img style="width:100%;" src="/caj1.png"/>
                     </button>
                     </div>

                     </div>


                   </div>









                </div>
            </div>
        </div>



        <div class="col-md-3" id="div_secondary">

          @if($user->user_level == '1')

          @else






          <div class="card">
              <div class="card-header">
                <h5>Mi Wallet QR es:</h5>
                Host de Nodo: {{$sistema->where('name','host_01')->first()->value}}
              </div>
              <div class="card-body">

                <div>
                  <button id="btn_qr" class="btn btn-danger"
                  style="width:100%;"
                  onclick="mostrar_QR('{{$user->wallet}}')">Mostrar QR de Wallet</button>

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


















<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Blockchain Miner #001</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        Identificador: 1<br>
        Minero Host: {{$sistema->where('name','host_01')->first()->value}}<br>
        Status: <span class="badge badge-pill badge-success"> Activo</span>
        Puerto: 3000<hr>
        <div id="miner_data">
        </div>
        <div class="text-center">
          <div id="placeHolder_miner"></div>
          <img style="width:100px;" src="/miner.png"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar [X]</button>
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





<script>

var mul_table = [
      512,512,456,512,328,456,335,512,405,328,271,456,388,335,292,512,
      454,405,364,328,298,271,496,456,420,388,360,335,312,292,273,512,
      482,454,428,405,383,364,345,328,312,298,284,271,259,496,475,456,
      437,420,404,388,374,360,347,335,323,312,302,292,282,273,265,512,
      497,482,468,454,441,428,417,405,394,383,373,364,354,345,337,328,
      320,312,305,298,291,284,278,271,265,259,507,496,485,475,465,456,
      446,437,428,420,412,404,396,388,381,374,367,360,354,347,341,335,
      329,323,318,312,307,302,297,292,287,282,278,273,269,265,261,512,
      505,497,489,482,475,468,461,454,447,441,435,428,422,417,411,405,
      399,394,389,383,378,373,368,364,359,354,350,345,341,337,332,328,
      324,320,316,312,309,305,301,298,294,291,287,284,281,278,274,271,
      268,265,262,259,257,507,501,496,491,485,480,475,470,465,460,456,
      451,446,442,437,433,428,424,420,416,412,408,404,400,396,392,388,
      385,381,377,374,370,367,363,360,357,354,350,347,344,341,338,335,
      332,329,326,323,320,318,315,312,310,307,304,302,299,297,294,292,
      289,287,285,282,280,278,275,273,271,269,267,265,263,261,259];


var shg_table = [
     9, 11, 12, 13, 13, 14, 14, 15, 15, 15, 15, 16, 16, 16, 16, 17,
  17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 18, 18, 18, 18, 18, 19,
  19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 20, 20, 20,
  20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 21,
  21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21,
  21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22,
  22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22,
  22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 23,
  23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23,
  23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23,
  23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23,
  23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24,
  24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24,
  24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24,
  24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24,
  24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24 ];


function stackBlurCanvasRGBA( canvas, top_x, top_y, width, height, radius )
{
if ( isNaN(radius) || radius < 1 ) return;
radius |= 0;

var context = canvas.getContext("2d");
var imageData;

try {
  try {
  imageData = context.getImageData( top_x, top_y, width, height );
  } catch(e) {

  // NOTE: this part is supposedly only needed if you want to work with local files
  // so it might be okay to remove the whole try/catch block and just use
  // imageData = context.getImageData( top_x, top_y, width, height );
  try {
    netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead");
    imageData = context.getImageData( top_x, top_y, width, height );
  } catch(e) {
    alert("Cannot access local image");
    throw new Error("unable to access local image data: " + e);
    return;
  }
  }
} catch(e) {
  alert("Cannot access image");
  throw new Error("unable to access image data: " + e);
}

var pixels = imageData.data;

var x, y, i, p, yp, yi, yw, r_sum, g_sum, b_sum, a_sum,
r_out_sum, g_out_sum, b_out_sum, a_out_sum,
r_in_sum, g_in_sum, b_in_sum, a_in_sum,
pr, pg, pb, pa, rbs;

var div = radius + radius + 1;
var w4 = width << 2;
var widthMinus1  = width - 1;
var heightMinus1 = height - 1;
var radiusPlus1  = radius + 1;
var sumFactor = radiusPlus1 * ( radiusPlus1 + 1 ) / 2;

var stackStart = new BlurStack();
var stack = stackStart;
for ( i = 1; i < div; i++ )
{
  stack = stack.next = new BlurStack();
  if ( i == radiusPlus1 ) var stackEnd = stack;
}
stack.next = stackStart;
var stackIn = null;
var stackOut = null;

yw = yi = 0;

var mul_sum = mul_table[radius];
var shg_sum = shg_table[radius];

for ( y = 0; y < height; y++ )
{
  r_in_sum = g_in_sum = b_in_sum = a_in_sum = r_sum = g_sum = b_sum = a_sum = 0;

  r_out_sum = radiusPlus1 * ( pr = pixels[yi] );
  g_out_sum = radiusPlus1 * ( pg = pixels[yi+1] );
  b_out_sum = radiusPlus1 * ( pb = pixels[yi+2] );
  a_out_sum = radiusPlus1 * ( pa = pixels[yi+3] );

  r_sum += sumFactor * pr;
  g_sum += sumFactor * pg;
  b_sum += sumFactor * pb;
  a_sum += sumFactor * pa;

  stack = stackStart;

  for( i = 0; i < radiusPlus1; i++ )
  {
    stack.r = pr;
    stack.g = pg;
    stack.b = pb;
    stack.a = pa;
    stack = stack.next;
  }

  for( i = 1; i < radiusPlus1; i++ )
  {
    p = yi + (( widthMinus1 < i ? widthMinus1 : i ) << 2 );
    r_sum += ( stack.r = ( pr = pixels[p])) * ( rbs = radiusPlus1 - i );
    g_sum += ( stack.g = ( pg = pixels[p+1])) * rbs;
    b_sum += ( stack.b = ( pb = pixels[p+2])) * rbs;
    a_sum += ( stack.a = ( pa = pixels[p+3])) * rbs;

    r_in_sum += pr;
    g_in_sum += pg;
    b_in_sum += pb;
    a_in_sum += pa;

    stack = stack.next;
  }


  stackIn = stackStart;
  stackOut = stackEnd;
  for ( x = 0; x < width; x++ )
  {
    pixels[yi+3] = pa = (a_sum * mul_sum) >> shg_sum;
    if ( pa != 0 )
    {
      pa = 255 / pa;
      pixels[yi]   = ((r_sum * mul_sum) >> shg_sum) * pa;
      pixels[yi+1] = ((g_sum * mul_sum) >> shg_sum) * pa;
      pixels[yi+2] = ((b_sum * mul_sum) >> shg_sum) * pa;
    } else {
      pixels[yi] = pixels[yi+1] = pixels[yi+2] = 0;
    }

    r_sum -= r_out_sum;
    g_sum -= g_out_sum;
    b_sum -= b_out_sum;
    a_sum -= a_out_sum;

    r_out_sum -= stackIn.r;
    g_out_sum -= stackIn.g;
    b_out_sum -= stackIn.b;
    a_out_sum -= stackIn.a;

    p =  ( yw + ( ( p = x + radius + 1 ) < widthMinus1 ? p : widthMinus1 ) ) << 2;

    r_in_sum += ( stackIn.r = pixels[p]);
    g_in_sum += ( stackIn.g = pixels[p+1]);
    b_in_sum += ( stackIn.b = pixels[p+2]);
    a_in_sum += ( stackIn.a = pixels[p+3]);

    r_sum += r_in_sum;
    g_sum += g_in_sum;
    b_sum += b_in_sum;
    a_sum += a_in_sum;

    stackIn = stackIn.next;

    r_out_sum += ( pr = stackOut.r );
    g_out_sum += ( pg = stackOut.g );
    b_out_sum += ( pb = stackOut.b );
    a_out_sum += ( pa = stackOut.a );

    r_in_sum -= pr;
    g_in_sum -= pg;
    b_in_sum -= pb;
    a_in_sum -= pa;

    stackOut = stackOut.next;

    yi += 4;
  }
  yw += width;
}


for ( x = 0; x < width; x++ )
{
  g_in_sum = b_in_sum = a_in_sum = r_in_sum = g_sum = b_sum = a_sum = r_sum = 0;

  yi = x << 2;
  r_out_sum = radiusPlus1 * ( pr = pixels[yi]);
  g_out_sum = radiusPlus1 * ( pg = pixels[yi+1]);
  b_out_sum = radiusPlus1 * ( pb = pixels[yi+2]);
  a_out_sum = radiusPlus1 * ( pa = pixels[yi+3]);

  r_sum += sumFactor * pr;
  g_sum += sumFactor * pg;
  b_sum += sumFactor * pb;
  a_sum += sumFactor * pa;

  stack = stackStart;

  for( i = 0; i < radiusPlus1; i++ )
  {
    stack.r = pr;
    stack.g = pg;
    stack.b = pb;
    stack.a = pa;
    stack = stack.next;
  }

  yp = width;

  for( i = 1; i <= radius; i++ )
  {
    yi = ( yp + x ) << 2;

    r_sum += ( stack.r = ( pr = pixels[yi])) * ( rbs = radiusPlus1 - i );
    g_sum += ( stack.g = ( pg = pixels[yi+1])) * rbs;
    b_sum += ( stack.b = ( pb = pixels[yi+2])) * rbs;
    a_sum += ( stack.a = ( pa = pixels[yi+3])) * rbs;

    r_in_sum += pr;
    g_in_sum += pg;
    b_in_sum += pb;
    a_in_sum += pa;

    stack = stack.next;

    if( i < heightMinus1 )
    {
      yp += width;
    }
  }

  yi = x;
  stackIn = stackStart;
  stackOut = stackEnd;
  for ( y = 0; y < height; y++ )
  {
    p = yi << 2;
    pixels[p+3] = pa = (a_sum * mul_sum) >> shg_sum;
    if ( pa > 0 )
    {
      pa = 255 / pa;
      pixels[p]   = ((r_sum * mul_sum) >> shg_sum ) * pa;
      pixels[p+1] = ((g_sum * mul_sum) >> shg_sum ) * pa;
      pixels[p+2] = ((b_sum * mul_sum) >> shg_sum ) * pa;
    } else {
      pixels[p] = pixels[p+1] = pixels[p+2] = 0;
    }

    r_sum -= r_out_sum;
    g_sum -= g_out_sum;
    b_sum -= b_out_sum;
    a_sum -= a_out_sum;

    r_out_sum -= stackIn.r;
    g_out_sum -= stackIn.g;
    b_out_sum -= stackIn.b;
    a_out_sum -= stackIn.a;

    p = ( x + (( ( p = y + radiusPlus1) < heightMinus1 ? p : heightMinus1 ) * width )) << 2;

    r_sum += ( r_in_sum += ( stackIn.r = pixels[p]));
    g_sum += ( g_in_sum += ( stackIn.g = pixels[p+1]));
    b_sum += ( b_in_sum += ( stackIn.b = pixels[p+2]));
    a_sum += ( a_in_sum += ( stackIn.a = pixels[p+3]));

    stackIn = stackIn.next;

    r_out_sum += ( pr = stackOut.r );
    g_out_sum += ( pg = stackOut.g );
    b_out_sum += ( pb = stackOut.b );
    a_out_sum += ( pa = stackOut.a );

    r_in_sum -= pr;
    g_in_sum -= pg;
    b_in_sum -= pb;
    a_in_sum -= pa;

    stackOut = stackOut.next;

    yi += width;
  }
}

context.putImageData( imageData, top_x, top_y );

}

function BlurStack()
{
this.r = 0;
this.g = 0;
this.b = 0;
this.a = 0;
this.next = null;
}

$( document ).ready(function() {
var BLUR_RADIUS = 40;
var sourceImages = [];

$('.src-image').each(function(){
  sourceImages.push($(this).attr('src'));
});

$('.avatar img').each(function(index){
  $(this).attr('src', sourceImages[index] );
});

var drawBlur = function(canvas, image) {
  var w = canvas.width;
  var h = canvas.height;
  var canvasContext = canvas.getContext('2d');
  canvasContext.drawImage(image, 0, 0, w, h);
  stackBlurCanvasRGBA(canvas, 0, 0, w, h, BLUR_RADIUS);
};


$('.card canvas').each(function(index){
  var canvas = $(this)[0];

  var image = new Image();
  image.src = sourceImages[index];

  image.onload = function() {
    drawBlur(canvas, image);
  }
});
});
</script>
@endsection
