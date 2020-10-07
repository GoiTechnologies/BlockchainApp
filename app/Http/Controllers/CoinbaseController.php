<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoinbaseController extends Controller
{

  // Funcion para obtener timestamp en server coinbase *************************
        public function get_time_coinbase(){
          $API_KEY = '8YaPo635LTVgT8mf';
          $API_SECRET = 'D9XEasxqWqsCTRtAjkfbZiHwlnqNq79g';
          $resourse = 'time';
          $body = '';
          $timestamp = time();
          $message = $timestamp . 'GET' . $resourse . $body;
          $signature = hash_hmac('sha256', $message, $API_SECRET);
          $version = '2019-11-15';
          $headers = array(
                              'CB-ACCESS-SIGN: ' . $signature,
                              'CB-ACCESS-TIMESTAMP: ' . $timestamp,
                              'CB-ACCESS-KEY: ' . $API_KEY,
                              'CB-VERSION: ' . $version
                          );
          $api_url = 'https://api.coinbase.com/v2/'.$resourse;
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $api_url);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, 1);
          $data = curl_exec($ch);
          if(curl_errno($ch))
          {
              echo "Errore: " . curl_error($ch);
          }
          else
          {
              $new = json_decode($data);
              return $new->data->iso;
          }
          curl_close($ch);
        }
    //FIN **********************************************************************




// Obtener la notificacion de cuenta coinbase **********************************
  public function coinbase_user(Request $request){
       $time = $this->get_time_coinbase();
       $API_KEY = 'Kdwr9uDjgk2VYSOu';
       $API_SECRET = 'WBDMqxbbukoXQdDVAFKUZDwgwwj5ZIB3';
       $resourse = '/v2/user';
       $body = '';
       $timestamp = strtotime($time);
       $message = $timestamp . 'GET' . $resourse . $body;
       $signature = hash_hmac('sha256', $message, $API_SECRET);
       $version = '2019-11-15';
       $headers = array(
                           'CB-ACCESS-SIGN: ' . $signature,
                           'CB-ACCESS-TIMESTAMP: ' . $timestamp,
                           'CB-ACCESS-KEY: ' . $API_KEY,
                           'CB-VERSION: ' . $version
                       );

       $api_url = 'https://api.coinbase.com'.$resourse;
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $api_url);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POST, 1);
       $data = curl_exec($ch);
       if(curl_errno($ch))
       { echo "Errore: " . curl_error($ch); }
       else
       {  return json_decode($data, true); }
       curl_close($ch);
     }
// FIN *************************************************************************








// Obtener informacion de la Wallet de Coinbase ********************************
public function wallet_coinbase(Request $request){
        $time = $this->get_time_coinbase();
        $API_KEY = 'Kdwr9uDjgk2VYSOu';
        $API_SECRET = 'WBDMqxbbukoXQdDVAFKUZDwgwwj5ZIB3';
        $resourse = '/v2/accounts';
        $body = '';
        $timestamp = strtotime($time);
        $message = $timestamp . 'GET' . $resourse . $body;
        $signature = hash_hmac('sha256', $message, $API_SECRET);
        $version = '2019-11-15';
        $headers = array(
                            'CB-ACCESS-SIGN: ' . $signature,
                            'CB-ACCESS-TIMESTAMP: ' . $timestamp,
                            'CB-ACCESS-KEY: ' . $API_KEY,
                            'CB-VERSION: ' . $version
                        );

        $api_url = 'https://api.coinbase.com'.$resourse;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        $data = curl_exec($ch);
        if(curl_errno($ch))
        {   echo "Errore: " . curl_error($ch); }
        else
        {   return json_decode($data, true); }
        curl_close($ch);
      }
// FIN *************************************************************************








// Obtiene el ultimo valor del BITCOIN desde coinbase
public function bitcoin_price(){
  $time = $this->get_time_coinbase();
  $API_KEY = 'Kdwr9uDjgk2VYSOu';
  $API_SECRET = 'WBDMqxbbukoXQdDVAFKUZDwgwwj5ZIB3';
  $resourse = '/v2/prices/BTC-USD/buy';
  $body = '';
  $timestamp = strtotime($time);
  $message = $timestamp . 'GET' . $resourse . $body;
  $signature = hash_hmac('sha256', $message, $API_SECRET);
  $version = '2019-11-15';
  $headers = array(
                      'CB-ACCESS-SIGN: ' . $signature,
                      'CB-ACCESS-TIMESTAMP: ' . $timestamp,
                      'CB-ACCESS-KEY: ' . $API_KEY,
                      'CB-VERSION: ' . $version
                  );
  $api_url = 'https://api.coinbase.com'.$resourse;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, 1);
  $data = curl_exec($ch);
  if(curl_errno($ch))
  {   echo "Errore: " . curl_error($ch);  }
  else
  {  $data = json_decode($data, true);
      return $data["data"]["amount"]; }
  curl_close($ch);
}
// FIN ************************************************************************













// Enviar bitcoin desde coinbase ***********************************************
// Metodo para enviar Bitcoin desde el inicio FORMULARIO
public function send_to_wallet_from_coinbase_cajero_final(Request $request){
  //Obtengo valor de dolar de base de datos
  if(isset($request->moneda) && $request->moneda == "EUR"){
    // Si viene en euros multiplicamos para convertir pesos a euros
    // Despues mas adelante se saca calculo en bitcoin en base a este
      $amount = $amount * $this->valor_euro();
  }
    $randomNumber = rand(100,750000);
    $amount = $request->amount_dlls;
    $dollar = DB::table('sistemas')->where('name','tipo_de_cambio')->first();
    $amount = $amount / $dollar->valor;
    $amount =  number_format($amount, 8, '.', '');
  // Aqui debreria restar la comision del exchange para mostrar ejemplo
  // Mando 2 - 0.15(comision antra) - .05 (comision exchange) SOLO PARA MOSTRAR (VIEW) FRONTEND
  if(isset($request->id_terminal)){
    $device = DB::table('ZTERMINAL_users')->where('id',$request->id_terminal)->first();
    $coinbase_user = DB::table('ZTERMINAL_coibase_users')->where('id',$device->id_user)->first();
  }else{
    $coinbase_user = DB::table('ZTERMINAL_coibase_users')->where('id',$request->id_user)->first();
    $device = DB::table('ZTERMINAL_users')->where('id_user',$coinbase_user->id)->where('tipo','portal_pc')->first();
  }
  $btc = $this->bitcoin_price();
  $amount = $amount / $btc;
  $amount = number_format($amount, 8, '.', '');
  $cantidad_menos_comicion = $amount - $this->porcentaje($amount,$device->commission,8);
  $cantidad_menos_comicion = number_format($cantidad_menos_comicion, 8, '.', '');
  $wallet = "";
  $content_type = "application/json";
  if($device){
      $user = DB::table('ZTERMINAL_coibase_users')->where('id',$device->id_user)->first();
      if($user){
        $api = $user->api_key;
        $secret = $user->secret_key;
        $wallet = $user->wallet;
      }else{ return response()->json( "No keys" , 401); }
  }else{
    return response()->json( "User not found!!" , 401);
  }
  $time = $this->get_time_coinbase();
  $API_KEY = $api;
  $API_SECRET = $secret;
  $resourse = '/v2/accounts/'.$coinbase_user->id_coinbase.'/transactions';
  $random = $this->generateRandomString();
  $body = '{"type": "send" ,"to": "'.$request->wallet_to.'","amount": "'.$cantidad_menos_comicion.'","currency":"'.$request->currency
    .'","idem": "'.$random.'-0c09","to_financial_institution":"1","financial_institution_website":"https://bitso.com"}';
  $timestamp = strtotime($time);
  $message = $timestamp . 'POST' . $resourse . $body;
  $signature = hash_hmac('sha256', $message, $API_SECRET);
  $version = '2019-11-15';
  $headers = array(
                      'CB-ACCESS-SIGN: ' . $signature,
                      'CB-ACCESS-TIMESTAMP: ' . $timestamp,
                      'CB-ACCESS-KEY: ' . $API_KEY,
                      'CB-VERSION: ' . $version,
                      'Content-Type: application/json',
                                        'Connection: Keep-Alive'
                  );
  $api_url = 'https://api.coinbase.com'.$resourse;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
  $data = curl_exec($ch);

  if(curl_errno($ch))
  {
      $new_transaction = DB::table('ZTERMINALES_prepagos')->insert(
          ['account_from' => $user->id_coinbase , 'wallet_to' => $request->wallet_to ,
          'json_response' => "ERROR:".curl_error($ch) , 'currency' => $request->currency ,
           'amount' => $amount ]);

             if(isset($request->moneda) && $request->moneda == "EUR"){
               return view('TERMINALES.success_pages.send_bitcoin_to_bitso_euro')->with('qty',number_format($request->amount_dlls, 5, '.', ''))
               ->with('wallet',$request->wallet_to)->with('referencia',$randomNumber)->with('state',0)
               ->with('currency','BTC');
             }else{
               return view('TERMINALES.success_pages.send_bitcoin_to_bitso')->with('qty',number_format($request->amount_dlls, 5, '.', ''))
               ->with('wallet',$request->wallet_to)->with('referencia',$randomNumber)->with('state',0)
               ->with('currency','BTC');
             }
  }else{
      $new_transaction = DB::table('ZTERMINALES_prepagos')->insert(
          ['account_from' => $user->id_coinbase , 'wallet_to' => $request->wallet_to ,
          'json_response' => "EXITO:".$data , 'currency' => $request->currency ,
           'amount' => $amount ]);

                $new_ticket = DB::table('ZTERMINALES_printer_memory')->insert(
                    ['receptor' => $request->wallet_to , 'no_referencia' => $randomNumber, 'id_user' => $request->id_user,
                     'status' => '1' , 'currency' => $request->currency , 'sitio' => 'https://groupconcept.global' ,
                     'cantidad' => $amount, 'empresa' => 'GROUP CONCEPT GLOBAL','tipo' => 'Compra Bitcoin'   ]);

              if(isset($request->moneda) && $request->moneda == "EUR"){
                return view('TERMINALES.success_pages.send_bitcoin_to_bitso_euro')->with('qty', $amount )
                ->with('wallet',$request->wallet_to)->with('referencia',$randomNumber)->with('state',1)
                ->with('currency','BTC');
              }else{
                return view('TERMINALES.success_pages.send_bitcoin_to_bitso')->with('qty', $amount )
                ->with('wallet',$request->wallet_to)->with('referencia',$randomNumber)->with('state',1)
                ->with('currency','BTC');
              }
  }
  curl_close($ch);
  }









// Metodo para enviar Bitcoin desde el inicio FORMULARIO
  public function send_to_wallet_from_coinbase_cajero_final(Request $request){
    //Obtengo valor de dolar de base de datos

    if(isset($request->moneda) && $request->moneda == "EUR"){
      // Si viene en euros multiplicamos para convertir pesos a euros
      // Despues mas adelante se saca calculo en bitcoin en base a este
        $amount = $amount * $this->valor_euro();
    }
      $randomNumber = rand(100,750000);
      $amount = $request->amount_dlls;
      $dollar = DB::table('sistemas')->where('name','tipo_de_cambio')->first();
      $amount = $amount / $dollar->valor;
      $amount =  number_format($amount, 8, '.', '');






    // Aqui debreria restar la comision del exchange para mostrar ejemplo
    // Mando 2 - 0.15(comision antra) - .05 (comision exchange) SOLO PARA MOSTRAR (VIEW) FRONTEND

    if(isset($request->id_terminal)){
      $device = DB::table('ZTERMINAL_users')->where('id',$request->id_terminal)->first();
      $coinbase_user = DB::table('ZTERMINAL_coibase_users')->where('id',$device->id_user)->first();
    }else{
      $coinbase_user = DB::table('ZTERMINAL_coibase_users')->where('id',$request->id_user)->first();
      $device = DB::table('ZTERMINAL_users')->where('id_user',$coinbase_user->id)->where('tipo','portal_pc')->first();
    }
    $btc = $this->bitcoin_price();
    $amount = $amount / $btc;
    $amount = number_format($amount, 8, '.', '');
    $cantidad_menos_comicion = $amount - $this->porcentaje($amount,$device->commission,8);
    $cantidad_menos_comicion = number_format($cantidad_menos_comicion, 8, '.', '');
    $wallet = "";
    $content_type = "application/json";
    if($device){
        $user = DB::table('ZTERMINAL_coibase_users')->where('id',$device->id_user)->first();
        if($user){
          $api = $user->api_key;
          $secret = $user->secret_key;
          $wallet = $user->wallet;
        }else{ return response()->json( "No keys" , 401); }
    }else{
      return response()->json( "User not found!!" , 401);
    }



    $time = $this->get_time_coinbase();
    $API_KEY = $api;
    $API_SECRET = $secret;
    $resourse = '/v2/accounts/'.$coinbase_user->id_coinbase.'/transactions';
    $random = $this->generateRandomString();
    $body = '{"type": "send" ,"to": "'.$request->wallet_to.'","amount": "'.$cantidad_menos_comicion.'","currency":"'.$request->currency
      .'","idem": "'.$random.'-0c09","to_financial_institution":"1","financial_institution_website":"https://bitso.com"}';
    $timestamp = strtotime($time);
    $message = $timestamp . 'POST' . $resourse . $body;
    $signature = hash_hmac('sha256', $message, $API_SECRET);
    $version = '2019-11-15';
    $headers = array(
                        'CB-ACCESS-SIGN: ' . $signature,
                        'CB-ACCESS-TIMESTAMP: ' . $timestamp,
                        'CB-ACCESS-KEY: ' . $API_KEY,
                        'CB-VERSION: ' . $version,
                        'Content-Type: application/json',
                                          'Connection: Keep-Alive'
                    );
    $api_url = 'https://api.coinbase.com'.$resourse;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
    $data = curl_exec($ch);

    if(curl_errno($ch))
    {
        $new_transaction = DB::table('ZTERMINALES_prepagos')->insert(
            ['account_from' => $user->id_coinbase , 'wallet_to' => $request->wallet_to ,
            'json_response' => "ERROR:".curl_error($ch) , 'currency' => $request->currency ,
             'amount' => $amount ]);

               if(isset($request->moneda) && $request->moneda == "EUR"){
                 return view('TERMINALES.success_pages.send_bitcoin_to_bitso_euro')->with('qty',number_format($request->amount_dlls, 5, '.', ''))
                 ->with('wallet',$request->wallet_to)->with('referencia',$randomNumber)->with('state',0)
                 ->with('currency','BTC');
               }else{
                 return view('TERMINALES.success_pages.send_bitcoin_to_bitso')->with('qty',number_format($request->amount_dlls, 5, '.', ''))
                 ->with('wallet',$request->wallet_to)->with('referencia',$randomNumber)->with('state',0)
                 ->with('currency','BTC');
               }



    }else{
        $new_transaction = DB::table('ZTERMINALES_prepagos')->insert(
            ['account_from' => $user->id_coinbase , 'wallet_to' => $request->wallet_to ,
            'json_response' => "EXITO:".$data , 'currency' => $request->currency ,
             'amount' => $amount ]);

                  $new_ticket = DB::table('ZTERMINALES_printer_memory')->insert(
                      ['receptor' => $request->wallet_to , 'no_referencia' => $randomNumber, 'id_user' => $request->id_user,
                       'status' => '1' , 'currency' => $request->currency , 'sitio' => 'https://groupconcept.global' ,
                       'cantidad' => $amount, 'empresa' => 'GROUP CONCEPT GLOBAL','tipo' => 'Compra Bitcoin'   ]);

                if(isset($request->moneda) && $request->moneda == "EUR"){
                  return view('TERMINALES.success_pages.send_bitcoin_to_bitso_euro')->with('qty', $amount )
                  ->with('wallet',$request->wallet_to)->with('referencia',$randomNumber)->with('state',1)
                  ->with('currency','BTC');
                }else{
                  return view('TERMINALES.success_pages.send_bitcoin_to_bitso')->with('qty', $amount )
                  ->with('wallet',$request->wallet_to)->with('referencia',$randomNumber)->with('state',1)
                  ->with('currency','BTC');
                }

    }
    curl_close($ch);
  }
// FIN *************************************************************************






// Obtine las cuentas desde coinbase *******************************************
public function accounts_coinbase(Request $request){

      $data = json_decode($request->getContent(), true);
      $user = DB::table('ZTERMINAL_coibase_users')->where('id',$data["user"] )->first();
      if(!$user){
        return response()->json("User Doesnt Found!!", 200);
      }
      $time = $this->get_time_coinbase();
      $API_KEY = $user->api_key;
      $API_SECRET = $user->secret_key;
      $resourse = '/v2/accounts';
      $body = '';
      $timestamp = strtotime($time);
      $message = $timestamp . 'GET' . $resourse . $body;
      $signature = hash_hmac('sha256', $message, $API_SECRET);
      $version = '2019-11-15';
      $headers = array(
                          'CB-ACCESS-SIGN: ' . $signature,
                          'CB-ACCESS-TIMESTAMP: ' . $timestamp,
                          'CB-ACCESS-KEY: ' . $API_KEY,
                          'CB-VERSION: ' . $version
                      );

      $api_url = 'https://api.coinbase.com'.$resourse;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $api_url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, 1);

      $data = curl_exec($ch);

      if(curl_errno($ch))
      {
          echo "Errore: " . curl_error($ch);
      }
      else
      {

          $data = json_decode($data, true);
          if(isset($data["data"])) return $data["data"]; else return $data["errors"];
      }
      curl_close($ch);
    }
// FIN *************************************************************************

}
