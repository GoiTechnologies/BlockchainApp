<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Blockchain App</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #000;
                color: #fff;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
        </style>
    </head>
    <body class="container">
        <div class="text-center" style="margin-top:10%;" id="di">
          <img src="logo_green.png" width="70%"/>
          <hr style="color:#fff;">
            <h2>Soporte Tecnico GOI</h2>
            <p>Ultima Actualización: {{ date("Y/m/d") }}</p>

            <div class="card container text" style="padding:50px;">


          <div class="row">

            <div class="col-md-8" style="padding:20px;">
              <h2 class="text-primary">Reportar un Problema !!</h2>
              <hr>
              <form>
              <h4 class="text-primary">Email:</h4>
              <input class="form-control" placeholder="mail ejemplo: tumaiul@example.com"
               type="text"/>
               <h4 class="text-primary">Telefono:</h4>
               <input class="form-control" placeholder="(A 10 Digitos) 3334445599"
                type="number"/>
                <h4 class="text-primary">Detalle:</h4>
                <textarea class="form-control text-center" onfocus="$this.empty()" rows="5">
                  Describe brevemente el problema, un asesor dara seguimiento...
                  Soporte GOI Wallet
              </textarea>
              <br>
              <button class="btn btn-outline-primary" style="width:100%;">
                  Enviar Reporte
              </button>
            </form>
          </div>
          <div class="col-md-4">
            <img id="wsup" style="margin-top:30%;" src="https://www.pime.com.mx/image/contenidos/pyme23.png"
          </div>
          </div>

            </div>



        </div>
    </body>
</html>
<script>
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

              $("#di").css("margin-top","10%");
              $("#coin").css("width","40%");
              $("#wsup").css("width","60%");
          } else {
              a = false;
          }
      }
      detec();

      $('textarea').focus(function() {
         $(this).val('');
      });
  </script>
