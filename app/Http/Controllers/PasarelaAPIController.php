<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sistema;
use App\User;
use App\Transacciones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Str;

class PasarelaAPIController extends Controller
{
    //

    public function create_merchant(Request $request){

    // Creo Merchant ID
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $merchant_id = substr(str_shuffle(str_repeat($pool, 5)), 0, 10);
    // Validamos  PArametros
    if(!isset($request->email) || !isset($request->password)){
      return response()->json(['message' => 'Unauthenticated.'], 401);
    }
    // Genero API KEY
    $passcode = $this->request_blockchain_code($merchant_id);
    // Genero Nueva Wallet
    $new_wallet = $this->request_wallet($merchant_id,$passcode);
    //dd($merchant_id,$request->email,$request->password);
    //Generamos nuevo merchant  user
      $new_merchant = DB::table('users')->insert([
          'name' => $merchant_id,
          'nick_name' => $merchant_id,
          'email' => $request->email,
          'password' => bcrypt($request->password),
          'wallet' => $new_wallet,
          'saldo' => '0',
          'currency' => 'mxn',
          'application' => 'merchant',
          'blockchain_passcode' => $passcode,
          'user_level' => '3',
      ]);
      if($new_merchant){
        return response()->json(['merchant_id' => $merchant_id ,
        'wallet' => $new_wallet , 'api_key' => $passcode], 200);
      }
      return response()->json(['message' => 'Unauthenticated.'], 401);
    }

   public function get_merchant_info(Request $request)
    {
      if(!isset($request->email) || !isset($request->password)){
        return response()->json(['message' => 'Unauthenticated.'], 401);
      }
      $merchant = DB::table('users')->where('email',$request->email)->first();
      if (Hash::check($request->password, $merchant->password )) {
        return response()->json(['merchant_id' => $merchant->name ,
        'wallet' => $merchant->wallet , 'api_key' => $merchant->blockchain_passcode], 200);
      }
      return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    public function request_blockchain_code($pass)
    {
      $host = Sistema::where('name', 'host_01')->first();
              $data = [  'pass' => $pass  ];
              $curl = curl_init();
              curl_setopt_array($curl, array(
                CURLOPT_URL => $host->value.'/request_passcode',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                  // Set here requred headers
                    "accept: */*",
                    "accept-language: en-US,en;q=0.8",
                    "content-type: application/json",
                ),
              ));
              $response = json_decode(curl_exec($curl));
              $err = curl_error($curl);
              curl_close($curl);
              return $response->{'password'};
    }


    public function request_wallet($user,$blockchain_passcode)
    {
      $host = Sistema::where('name', 'host_01')->first();
              $data = [  'nick_name' => $user ,
              'pass' => $blockchain_passcode , 'balance' => '0' ];
              $curl = curl_init();
              curl_setopt_array($curl, array(
                CURLOPT_URL => $host->value.'/make_wallet',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                  // Set here requred headers
                    "accept: */*",
                    "accept-language: en-US,en;q=0.8",
                    "content-type: application/json",
                ),
              ));
              $response = json_decode(curl_exec($curl));
              $err = curl_error($curl);
              curl_close($curl);
              return $response->{'wallet_key'};
    }




    public function get_balance(Request $request)
    {
      if(!isset($request->api_key) || !isset($request->address)){
        return response()->json(['message' => 'Unauthenticated.'], 401);
      }
      $merchant = DB::table('users')->where('wallet',$request->address)
      ->where('blockchain_passcode',$request->api_key)->first();
      if($merchant){ return response()->json(['balance' => $merchant->saldo], 200); }
      return response()->json(['message' => 'Unauthenticated.'], 401);
    }



    public function get_wallet(Request $request)
    {
      if(!isset($request->api_key) || !isset($request->ipn_url)){
        return response()->json(['message' => 'Unauthenticated.'], 401);
      }
      $randUser = Str::random(10);
      $randPass = Str::random(10);
      $new_wallet = $this->request_wallet_merchant($randUser,$randPass);
      $new_merchant = DB::table('users')->insert([
          'name' => $randUser,
          'nick_name' => $randUser,
          'email' => $randUser.'@goicoin.net',
          'password' => bcrypt($randPass),
          'wallet' => $new_wallet,
          'saldo' => '0',
          'currency' => 'mxn',
          'application' => 'merchant',
          'blockchain_passcode' => $randPass,
          'user_level' => '3',
      ]);

      if($new_merchant){ return response()->json(['address' => $new_wallet], 200); }
      return response()->json(['message' => 'Unauthenticated.'], 401);
    }



    public function create_whitdraw(Request $request)
    {
      $array = $this->wallet_explore();
      $data = json_decode($array, true);
      //dd($data)
      if(!isset($request->api_key) || !isset($request->ipn_url) || !isset($request->amount) || !isset($request->address) ){
        return response()->json(['message' => 'Unauthenticated.'], 401);
      }
      $user = DB::table('users')->where('blockchain_passcode',$request->api_key)->first();
      $ide = 0; $ident = 0;
      foreach($data as $d){
        if($d["wallet"] == $user->wallet){
          $ident = $ide;
        }
        $ide++;
      }

      $host = Sistema::where('name', 'host_01')->first();
                $data = [  'ide' => $ident ,
                'recipient' => $request->address ,
                'amount' => floatval($request->amount) ];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $host->value.'/transaction_me_to',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30000,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => json_encode($data),
                  CURLOPT_HTTPHEADER => array(
                    // Set here requred headers
                      "accept: */*",
                      "accept-language: en-US,en;q=0.8",
                      "content-type: application/json",
                  ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if($this->update_balance($request->address,floatval($request->amount),$user->wallet )){
                $trans = new Transacciones();
                $trans->transmitter = $user->wallet;
                $trans->receiver = $request->address;
                $trans->amount = $request->amount;
                $trans->save();
                }
                $m2 = DB::table('users')->where('wallet',$request->address)->first();
                $r = $this->notifier($request->amount,
                $user->email,$m2->email,
                $request->recipient,$user->wallet, $request->ipn_url ,$trans->id);
              //  $this->notifier($request->amount,'christian.padilla.muniz@gmail.com','goicoin2@gmail.com','046a0a0e65d7f75b2d5d9eb8900b05a3fe83b77556c22ea5933b3bac96536410c7502a97c33fe98e24307c2f6248db18a0a9865c8816f0b349b2f8b559ed152be2','04f1c9da3aa53999f1dbc5b1b28d75228733ba9893a60ee8b6bcd08b76a0b0c72cc8c79676869585923547a0b8dca4fbd40431a360843692a30edcae924a3e3ab9');


                return response()->json(['id' => $r ,
                                          'amount' => $request->amount ], 200);

    }









    public function get_withdraw_info(Request $request)
    {
      if(!isset($request->api_key) || !isset($request->id)){
        return response()->json(['message' => 'Unauthenticated.'], 401);
      }
      $user = DB::table('users')->where('blockchain_passcode',$request->api_key)->first();
      if($user){
          $ipn = DB::table('ipn')->where('id_ipn',$request->id)->first();
          return response()->json($ipn, 200);
      }else{
        return response()->json(['message' => 'Unauthenticated.'], 401);
      }


    }






    public function get_tx_list(Request $request)
    {
      if(!isset($request->api_key) || !isset($request->address)){
        return response()->json(['message' => 'Unauthenticated.'], 401);
      }
      $user = DB::table('users')->where('blockchain_passcode',$request->api_key)->first();
      if($user){
          $ipn = DB::table('transacciones')->where('transmitter',$user->wallet)->get();
          return response()->json($ipn, 200);
      }else{
        return response()->json(['message' => 'Unauthenticated.'], 401);
      }


    }








    public function update_balance($receptor,$cantidad,$emisor)
    {
        $user = User::where('wallet',$emisor)->first();
        $my_balance = User::find($user->id);
        $my_balance->saldo = $my_balance->saldo - $cantidad;
        $my_balance->save();

        $receptor_balance = User::where('wallet',$receptor)->first();
        $receptor_balance->saldo = $receptor_balance->saldo + $cantidad;
        $receptor_balance->save();
        return true;
    }


    public function notifier($amount,$m1,$m2,$wall1,$wall2,$ipn,$id_trans)
      {
        $referRANDOM = $id_trans;
        $dte = Carbon::now()->toDateTimeString();
        $ipnData = 'Unique ID: '.$referRANDOM.' Authorized Date: '.
        $dte.' Transaction State: Success Receiver: '.$wall1.' Sender: '.$wall2.' Currency: GOI'.
        ' Fiat Currency: USD';
             // 1.- Intento notificar a URL proporcionada
              $stats = $this->make_connection_notification($ipnData,$ipn);
              if($stats == 200){
                // 2.- Si notifica mando el mail
                //$mail_noty = $this->mail_ipn($referRANDOM,$m1,$m2,$amount,$dte,$wall1,$wall2);
                  // 3.- Si se envio el mail guardo en base datos todo OK
                      // Guardamos con error de mail
                      $stat = DB::table('ipn')->insert([
                          'id_ipn' => $referRANDOM,
                          'status' => 'success',
                          'intents' => 1,
                          'ipn_data' => $ipnData,
                          'amount' => $amount
                      ]);
                      return $referRANDOM;

              }else{
                // Guardamos en base de datos con error en notificacion WEB
                $stat = DB::table('ipn')->insert([
                    'id_ipn' => $referRANDOM,
                    'intents' => 1,
                    'ipn_data' => $ipnData,
                    'amount' => $amount
                ]);
                return $referRANDOM;
               }
      }





      // Concecion a URL de notificacion proporcionada y obtengo el codigo de respuesta
        public function make_connection_notification($ipn_Data,$ipn)
          {
            $data = array('api_Data' => $ipn_Data);
            $payload = json_encode($data);
            // Prepare new cURL resource
            ini_set('memory_limit', '-1');
            if($ipn == "/api/url_receptora"){
              $ch = curl_init('https://goicoin.net/api/url_receptora');
            }else{
              $ch = curl_init($ipn);
            }
            $certificate_location = '/cacert.pem';
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            // Set HTTP Header for POST request
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($payload)));
            // Submit the POST request
            $result = curl_exec($ch);
            if(curl_errno($ch)){
            dd(curl_error($ch));
            }
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // Close cURL session handle
            curl_close($ch);
            $obj = json_decode($result);
            return $code;
          }



          public function wallet_explore()
          {
            $host = Sistema::where('name', 'host_01')->first();
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $host->value.'/wallet_explore',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30000,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "GET",
                      CURLOPT_HTTPHEADER => array(
                        // Set here requred headers
                          "accept: */*",
                          "accept-language: en-US,en;q=0.8",
                          "content-type: application/json",
                      ),
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    return $response;
          }




          public function request_wallet_merchant($rd1,$rd2)
          {
            $host = Sistema::where('name', 'host_01')->first();
                    $data = [  'nick_name' => $rd1 ,
                    'pass' => $rd2 , 'balance' => '0' ];
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => $host->value.'/make_wallet',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30000,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => json_encode($data),
                      CURLOPT_HTTPHEADER => array(
                        // Set here requred headers
                          "accept: */*",
                          "accept-language: en-US,en;q=0.8",
                          "content-type: application/json",
                      ),
                    ));
                    $response = json_decode(curl_exec($curl));
                    $err = curl_error($curl);
                    curl_close($curl);
                    return $response->{'wallet_key'};
          }

}
