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

    </head>
    <body style="width:99%;">

      <div class="row" >
        <div class="col-md-12" onclick="window.location.href = '/';"
        style="background-image:url('/api_green.jpg'); min-height:300px;">

        </div>

        <div class="col-md-12" style="padding:5%; margin-top:50px;">
              <h2>Documentación para API Blockchain Thaler</h2>
              <hr>
              <p>
                En este documento podra encontrar la informacion necesaria para
                realizar la comunicacion via API y poder acceder (Con sus claves de acceso)
                a la blockchain de Thaler Coin.<br>
                - Se requiere forzozamente certificado de Seguridad SSL para el solicitante.<br>
                - Sus Claves de acceso son proporcionadas via E-mail.

              </p><br><br>
              <h4>Indice de Servicios
                <br>
                <b class="text-success">Endpoint: https://goicoin.net/ + el servicio</b></h4>



              <li><a href="#01">01 - get_basic_info</a></li>
              <li><a href="#02">02 - set_basic_info</a></li>
              <li  id="01"><a href="#03">03 - get_wallets</a></li>
              <li><a href="#04">04 - get_last_20_transactions</a></li>
              <li><a href="#05">05 - get_last_20_transactions_recibidas</a></li>
              <li><a href="#06">06 - get_last_20_transactions_enviadas</a></li>
              <li><a href="#07">07 - service_send</a></li>
              <li><a href="#08">08 - thaler_rate</a></li>
              <li><a href="#09">09 - api_username</a></li>
              <li><a href="#10">10 - server_info</a></li>
              <li><a href="#11">11 - get_current_balance</a></li>
              <li><a href="#12">12 - transaction_query</a></li>

              <br><br><br>
              <br><br><br>
              <br><br><br>
              <br><br><br>

              <h2>01 - Obtener Información Basica del Usuario</h2>
              <b class="text-primary">Metodo POST<br>
              Url  https://goicoin.net/api/get_basic_info
              </b>
              <hr>
              <p>
                En este metodo regresa como resultado la informacion basica del usuario,
                en caso de no ser usuario manda error.
              </p>


              <div class="row">

                <div class="col-md-4"  >
                    <h5>
                      Request:<br>
                      {
                        <br>
                        "api_key" : "Clave Proporcionada" ,<br>
                        "wallet" : "Mi Wallet"<br>
                      }
                    </h5>
                </div>
                <div class="col-md-4">
                    <h5>
                      Response:<br>
                      {
                        <br>
                        "name" : "Usuario de Prueba" ,<br>
                        "email" : "tuemail@gmail.com",<br>
                        "balance" : "3000"<br>
                      }
                    </h5>
                </div>
                <div class="col-md-4">
                    <h5>
                      Error:<br>
                      {
                        <br>
                        "error" : "access denied"<br>
                      }
                    </h5>
                </div>
            </div>





            <br><br><br>
            <br><br><br>
            <br><br><br>
            <br><br><br>

              <h2 >02 - Modificar Información Basica del Usuario</h2>
              <b class="text-primary">Metodo POST<br>
              Url  https://goicoin.net/api/set_basic_info
              </b>
              <hr>
              <p>
                En este metodo regresa como resultado la informacion basica del usuario,
                en caso de no ser usuario manda error.
              </p>

              <div class="row" >

                <div class="col-md-4" id="02">
                    <h5>
                      Request:<br>
                      {
                        <br>
                        "api_key" : "Clave Proporcionada" ,<br>
                        "wallet" : "Mi Wallet",<br>
                        "new_name" : "Nuevo nombre",<br>
                        "new_email" : "Nuevo email"<br>
                      }
                    </h5>
                </div>
                <div class="col-md-4">
                    <h5>
                      Response:<br>
                      {
                        <br>
                        "update" : "success"<br>
                      }
                    </h5>
                </div>

                <div class="col-md-4">
                    <h5>
                      Error:<br>
                      {
                        <br>
                        "update" : "error"<br>
                      }
                    </h5>
                </div>
            </div>



















            <br><br><br>
            <br><br><br>
            <br><br><br>
            <br><br><br>

            <h2 >03 - Obtener de Wallets</h2>
            <b class="text-primary">Metodo POST<br>
            Url  https://goicoin.net/api/get_wallets
            </b>
            <hr>
            <p>
              En este metodo regresa como resultado las claves de  Wallet registradas y
              en operacion de la blockchain.
            </p>

            <div class="row">

              <div class="col-md-4" id="03">
                  <h5>
                    Request:<br>
                    {
                      <br>
                      "api_key" : "Clave Proporcionada" ,<br>
                      "service" : "wallets"<br>
                    }
                  </h5>
              </div>
              <div class="col-md-4">
                  <h5>
                    Response:<br>
                    {
                      <br>
                      "0" : "TEST044746474TTTTTTT000001",<br>
                      "1" : "TEST044746474TTTTTTT000002",<br>
                      "2" : "TEST044746474TTTTTTT000003",<br>
                      .<br>.<br>.<br>
                    }
                  </h5>
              </div>

              <div class="col-md-4">
                  <h5>
                    Error:<br>
                    {
                      <br>
                      "error" : "access denied"<br>
                    }
                  </h5>
              </div>
          </div>










          <br><br><br>
          <br><br><br>
          <br><br><br>
          <br><br><br>

          <h2  id="04">04 - Obtener Ultimas 20 Transacciones</h2>
          <b class="text-primary">Metodo POST<br>
          Url  https://goicoin.net/api/get_last_20_transactions
          </b>
          <hr>
          <p>
            En este metodo regresa como resultado las 20 transacciones ultimas
            en las que participa el usuario
          </p>

          <div class="row">

            <div class="col-md-4">
                <h5>
                  Request:<br>
                  {
                    <br>
                    "api_key" : "Clave Proporcionada" ,<br>
                    "wallet" : "Wallet blockchain" ,<br>
                    "service" : "transacctions_20"<br>
                  }
                </h5>
            </div>
            <div class="col-md-4">
                <h5>
                  Response:<br>
                [  <br>
                {
                    <br>
                    "id" : 45,<br>
                    "transmitter" : "TEST044746474TTTTTTT000002",<br>
                    "receiber" : "TEST044746474TTTTTTT000003",<br>
                    "amount" : 5,<br>
                    "created_at" : "2020-03-17 22:03:16",<br>
                    "updated_at" : "2020-03-17 22:03:16"<br>
                  } , {<br>
                  .<br>.<br>.<br>}<br>

                ]
                </h5>
            </div>

            <div  class="col-md-4">
                <h5>
                  Error:<br>
                  {
                    <br>
                    "error" : "access denied"<br>
                  }
                </h5>
            </div>
        </div>














        <br><br><br>
        <br><br><br>
        <br><br><br>
        <br><br><br>

        <h2  id="04">05 - Obtener Ultimas 20 Transacciones Recibidas</h2>
        <b class="text-primary">Metodo POST<br>
        Url  https://goicoin.net/api/get_last_20_transactions_recibidas
        </b>
        <hr>
        <p>
          En este metodo regresa como resultado las 20 transacciones ultimas
          en las que el usuario recibio
        </p>

        <div class="row">

          <div class="col-md-4">
              <h5>
                Request:<br>
                {
                  <br>
                  "api_key" : "Clave Proporcionada" ,<br>
                  "wallet" : "Wallet blockchain" ,<br>
                  "service" : "transacctions_20_recibidas"<br>
                }
              </h5>
          </div>
          <div class="col-md-4">
              <h5>
                Response:<br>
              [  <br>
              {
                  <br>
                  "id" : 45,<br>
                  "transmitter" : "TEST044746474TTTTTTT000002",<br>
                  "receiber" : "TEST044746474TTTTTTT000003",<br>
                  "amount" : 5,<br>
                  "created_at" : "2020-03-17 22:03:16",<br>
                  "updated_at" : "2020-03-17 22:03:16"<br>
                } , {<br>
                .<br>.<br>.<br>}<br>

              ]
              </h5>
          </div>

          <div  class="col-md-4">
              <h5>
                Error:<br>
                {
                  <br>
                  "error" : "access denied"<br>
                }
              </h5>
          </div>
      </div>


































              <br><br><br>
              <br><br><br>
              <br><br><br>
              <br><br><br>

              <h2  id="04">06 - Obtener Ultimas 20 Transacciones Enviadas</h2>
              <b class="text-primary">Metodo POST<br>
              Url  https://goicoin.net/api/get_last_20_transactions_enviadas
              </b>
              <hr>
              <p>
                En este metodo regresa como resultado las 20 transacciones ultimas
                en las que el usuario envio
              </p>

              <div class="row">

                <div class="col-md-4">
                    <h5>
                      Request:<br>
                      {
                        <br>
                        "api_key" : "Clave Proporcionada" ,<br>
                        "wallet" : "Wallet blockchain" ,<br>
                        "service" : "transacctions_20_enviadas"<br>
                      }
                    </h5>
                </div>
                <div class="col-md-4">
                    <h5>
                      Response:<br>
                    [  <br>
                    {
                        <br>
                        "id" : 45,<br>
                        "transmitter" : "TEST044746474TTTTTTT000002",<br>
                        "receiber" : "TEST044746474TTTTTTT000003",<br>
                        "amount" : 5,<br>
                        "created_at" : "2020-03-17 22:03:16",<br>
                        "updated_at" : "2020-03-17 22:03:16"<br>
                      } , {<br>
                      .<br>.<br>.<br>}<br>

                    ]
                    </h5>
                </div>

                <div  class="col-md-4">
                    <h5>
                      Error:<br>
                      {
                        <br>
                        "error" : "access denied"<br>
                      }
                    </h5>
                </div>
            </div>












                  <br><br><br>
                  <br><br><br>
                  <br><br><br>
                  <br><br><br>

                  <h2  id="05">07 - Transaccion, Enviar Thaler</h2>
                  <b class="text-primary">Metodo POST<br>
                  Url  https://goicoin.net/api/service_send
                  </b>
                  <hr>
                  <p>
                    En este metodo permite hacer el envio de criptomonedas en la blockchain de thaler.
                  </p>

                  <div class="row">

                    <div class="col-md-3">
                        <h5>
                          Request:<br>
                          {
                            <br>
                            "api_key" : "Clave Proporcionada" ,<br>
                            "wallet" : "Walllet operada" ,<br>
                            "amount" : "50" ,<br>
                            "recipient" : "Wallet receptora" ,<br>
                            "service" : "send"<br>
                          }
                        </h5>
                    </div>
                    <div class="col-md-4">
                        <h5>
                          Response:<br>
                         {
                            <br>
                            "message" : "Success!! The transacction has been complete."<br>
                          }


                        </h5>
                    </div>

                    <div  class="col-md-3">
                        <h5>
                          Error:<br>
                          {
                            <br>
                            "error" : "missing params"<br>
                          }
                        </h5>
                    </div>


                    <div  class="col-md-2">
                        <h5>
                          Error Balance:<br>
                          {
                            <br>
                            "error" : "You have not enough money for this transaction."<br>
                          }
                        </h5>
                    </div>


                </div>













                            <br><br><br>
                            <br><br><br>
                            <br><br><br>
                            <br><br><br>

                            <h2 >08 - Informacion de Moneda Thaler </h2>
                            <b class="text-primary">Metodo POST<br>
                            Url  https://goicoin.net/api/thaler_rate
                            </b>
                            <hr>
                            <p>
                              En este metodo regresa como resultado la tasa en la que se cotiza actualmente
                              la criptomoneda y la divisa.
                            </p>

                            <div class="row">

                              <div class="col-md-4" id="06">
                                  <h5>
                                    Request:<br>
                                    {
                                      <br>
                                      "api_key" : "Clave Proporcionada" ,<br>
                                      "service" : "rate"<br>
                                    }
                                  </h5>
                              </div>
                              <div class="col-md-4">
                                  <h5>
                                    Response:<br>
                                    {
                                      <br>
                                      "name" : "Thaler",<br>
                                      "currency" : "EUR",<br>
                                      "value" : "1.5"<br>
                                    }
                                  </h5>
                              </div>

                              <div class="col-md-4">
                                  <h5>
                                    Error:<br>
                                    {
                                      <br>
                                      "error" : "Invalid Api Key"<br>
                                    }
                                  </h5>
                              </div>
                          </div>

















                          <br><br><br>
                          <br><br><br>
                          <br><br><br>
                          <br><br><br>

                          <h2 >09 - Nombre de Usuario de API</h2>
                          <b class="text-primary">Metodo POST<br>
                          Url  https://goicoin.net/api/api_username
                          </b>
                          <hr>
                          <p>
                            En este metodo regresa como resultado el nombre del usuario
                            de la API de la blockchain.
                          </p>

                          <div class="row">

                            <div class="col-md-4" id="07">
                                <h5>
                                  Request:<br>
                                  {
                                    <br>
                                    "api_key" : "Clave Proporcionada" ,<br>
                                    "service" : "username"<br>
                                  }
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <h5>
                                  Response:<br>
                                  {
                                    <br>
                                    "name" : "Nombre Registrado para API"<br>
                                  }
                                </h5>
                            </div>

                            <div class="col-md-4">
                                <h5>
                                  Error:<br>
                                  {
                                    <br>
                                    "error" : "Invalid Api Key"<br>
                                  }
                                </h5>
                            </div>
                        </div>




















                                                  <br><br><br>
                                                  <br><br><br>
                                                  <br><br><br>
                                                  <br><br><br>

                                                  <h2 >10 - Información del Servidor</h2>
                                                  <b class="text-primary">Metodo POST<br>
                                                  Url  https://goicoin.net/api/server_info
                                                  </b>
                                                  <hr>
                                                  <p>
                                                    En este metodo regresa como resultado informacion del
                                                    servidor que expone la blockchain.
                                                  </p>

                                                  <div class="row">

                                                    <div class="col-md-4" id="10">
                                                        <h5>
                                                          Request:<br>
                                                          {
                                                            <br>
                                                            "api_key" : "Clave Proporcionada",<br>
                                                            "wallet": "Tu wallet"<br>
                                                          }

                                                        </h5>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h5>
                                                          Response:<br>


                                                          {
                                                            <br>
                                                            "host" : "www.servidor.example" ,<br>
                                                            "server_time" : "2020-20-22 05:00:00pm",<br>
                                                            "empresa" : "nombre de empresa",<br>
                                                            "blockchain" : "nombre de blockchain",<br>
                                                            "server_time" : "nombre de criptomoneda",<br>
                                                            "monedas" : "100000000",<br>
                                                          }
                                                        </h5>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <h5>
                                                          Error:<br>
                                                          {
                                                            <br>
                                                            "error" : "Invalid Api Key"<br>
                                                          }
                                                        </h5>
                                                    </div>
                                                </div>





                                                                                                  <br><br><br>
                                                                                                  <br><br><br>
                                                                                                  <br><br><br>
                                                                                                  <br><br><br>

                                                                                                  <h2 >11 - Obtener Balance Actual de Wallet</h2>
                                                                                                  <b class="text-primary">Metodo POST<br>
                                                                                                  Url  https://goicoin.net/api/get_current_balance
                                                                                                  </b>
                                                                                                  <hr>
                                                                                                  <p>
                                                                                                    En este metodo regresa como resultado informacion del
                                                                                                    balance del usuario wallet proporcionada.
                                                                                                  </p>

                                                                                                  <div class="row">

                                                                                                    <div class="col-md-4" id="11">
                                                                                                        <h5>
                                                                                                          Request:<br>
                                                                                                          {
                                                                                                            <br>
                                                                                                            "api_key" : "Clave Proporcionada",<br>
                                                                                                            "wallet": "Tu wallet"<br>
                                                                                                          }

                                                                                                        </h5>
                                                                                                    </div>
                                                                                                    <div class="col-md-4">
                                                                                                        <h5>
                                                                                                          Response:<br>


                                                                                                          {
                                                                                                            <br>
                                                                                                            "balance" : "9999" <br>
                                                                                                          }
                                                                                                        </h5>
                                                                                                    </div>

                                                                                                    <div class="col-md-4">
                                                                                                        <h5>
                                                                                                          Error:<br>
                                                                                                          {
                                                                                                            <br>
                                                                                                            "error" : "Invalid Api Key"<br>
                                                                                                          }
                                                                                                        </h5>
                                                                                                    </div>
                                                                                                </div>

















                                                                                                <br><br><br>
                                                                                                <br><br><br>
                                                                                                <br><br><br>
                                                                                                <br><br><br>

                                                                                                <h2 >12 - Consulta de Transaccion especifica</h2>
                                                                                                <b class="text-primary">Metodo POST<br>
                                                                                                Url  https://goicoin.net/api/transaction_query
                                                                                                </b>
                                                                                                <hr>
                                                                                                <p>
                                                                                                  En este metodo regresa como resultado informacion de
                                                                                                  la transaccion espesificando emisor, receptor y cantidad.
                                                                                                </p>

                                                                                                <div class="row">

                                                                                                  <div class="col-md-4" id="11">
                                                                                                      <h5>
                                                                                                        Request:<br>
                                                                                                        {

                                                                                                          <br>
                                                                                                          "api_key" : "Clave Proporcionada",<br>
                                                                                                          "wallet": "Tu wallet",<br>
                                                                                                          "receiver": "wallet que recibio",<br>
                                                                                                          "wallet": "50",<br>
                                                                                                        }

                                                                                                      </h5>
                                                                                                  </div>
                                                                                                  <div class="col-md-4">
                                                                                                      <h5>
                                                                                                        Response:<br>


                                                                                                        {
                                                                                                          <br>
                                                                                                          [<br>
                                                                                                          "id" : "9999", <br>
                                                                                                          "transmitter" : "wallet envio", <br>
                                                                                                          "receiver" : "wallet receptora", <br>
                                                                                                          "amount" : 50, <br>
                                                                                                          "created_at" : "2020-20-20 05:00:00", <br>
                                                                                                          "updated_at" : "2020-20-20 05:00:00" <br>
                                                                                                          ] , <br>...
                                                                                                        }
                                                                                                      </h5>
                                                                                                  </div>

                                                                                                  <div class="col-md-4">
                                                                                                      <h5>
                                                                                                        Error:<br>
                                                                                                        {
                                                                                                          <br>
                                                                                                          "error" : "Invalid Api Key"<br>
                                                                                                        }
                                                                                                      </h5>
                                                                                                  </div>
                                                                                              </div>














        </div>
      </div>
    </body>
</html>
