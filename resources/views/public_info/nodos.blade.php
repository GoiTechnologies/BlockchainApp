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
        <div class="text-center" style="margin-top:20%;" id="di">
          <img src="logo_green.png" width="70%"/>
          <hr style="color:#fff;">
            <h2>Master Nodo</h2>

            <img src="/nodos.png" width="35%" id="coin"/>


            <p>Ultima Actualización: {{ date("Y/m/d") }}<br>
            Encriptación e Intercambio de bloques (Nonce,Difficulty,Hash Rate)</p>
<div class="text-center">
            <table class="table">
  <thead>
    <tr>
      <th scope="col">Miembros</th>
      <th scope="col">{{$Miembros}}</th>
      <th scope="col">Wallet's</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Blockchain</th>
      <td>Pool</td>
      <td><a href="/documentacion">Doc.</a></td>
    </tr>
    <tr>
      <th scope="row">Nodos</th>
      <td>Server GOI</td>
      <td><a href="/documentacion">API.</a></td>

    </tr>
    <tr>
      <th scope="row">GitHub</th>
      <td>Node</td>
      <td><a href="https://github.com/ChrisQbit/Nodo-Maestro-Criptomoneda">Download</a></td>

    </tr>
  </tbody>
</table>
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

              $("#di").css("margin-top","20%");
              $("#coin").css("width","40%");
              $(".table").addClass("table-responsive");
              $(".table").css("margin-left","12%");
          } else {
              a = false;
          }
      }
      detec();

  </script>
