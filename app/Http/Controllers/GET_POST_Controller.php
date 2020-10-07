<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth;
use App\Sistema;
use App\User;
use App\Transacciones;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class GET_POST_Controller extends Controller
{
  public function request_blockchain_code(Request $request)
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = \Auth::user();
    if($user){
            $data = [  'pass' => $request->pass  ];
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
            $us = User::find($user->id);
            $us->blockchain_passcode = $response->{'password'};
            $us->save();
            return response()->json(['message' => 'Success!! Blockchain Passcode Updated.'], 200);
    }
      return response()->json(['message' => 'Unauthenticated.'], 401);
  }



  public function request_wallet()
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = \Auth::user();
    if($user->wallet == 0){
            $data = [  'nick_name' => $user->nick_name ,
            'pass' => $user->blockchain_passcode , 'balance' => $user->saldo ];
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
            $us = User::find($user->id);
            $us->wallet = $response->{'wallet_key'};
            $us->save();
            return response()->json(['message' => 'Success!! This user has been get a wallet.'], 200);
    }
    return response()->json(['message' => 'This user already have a wallet assigned.'], 200);
  }



  public function wallet_explore()
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = \Auth::user();
    if($user->wallet != 0){
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
            return response($response, 200);
    }
    return response()->json(['message' => 'Unauthenticated.'], 401);
  }





  public function transaccion_me_to(Request $request)
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = \Auth::user();
    if($user->saldo < $request->amount){ return response()->json(['message' => 'You have not enough money for this transaction.'], 200); }
    if($request->recipient == $user->wallet){ return response()->json(['message' => 'Cant posible send transactions to yourself.'], 200); }
    if($user->wallet != 0){
            $data = [  'ide' => (int)$request->ide , 'recipient' => $request->recipient , 'amount' => floatval($request->amount) ];
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
            if($this->update_balance($request->recipient,floatval($request->amount))){
            $trans = new Transacciones();
            $trans->transmitter = $user->wallet;
            $trans->receiver = $request->recipient;
            $trans->amount = $request->amount;
            $trans->save();
            }

            $m2 = DB::table('users')->where('wallet',$request->recipient)->first();
            $r = $this->notifier($request->amount,$user->email,$m2->email,$request->recipient,$user->wallet);
          //  $this->notifier($request->amount,'christian.padilla.muniz@gmail.com','goicoin2@gmail.com','046a0a0e65d7f75b2d5d9eb8900b05a3fe83b77556c22ea5933b3bac96536410c7502a97c33fe98e24307c2f6248db18a0a9865c8816f0b349b2f8b559ed152be2','04f1c9da3aa53999f1dbc5b1b28d75228733ba9893a60ee8b6bcd08b76a0b0c72cc8c79676869585923547a0b8dca4fbd40431a360843692a30edcae924a3e3ab9');


            return response()->json(['message' => 'Success!! The transacction has been complete.'], 200);
    }
    return response()->json(['message' => 'This user doesnt have wallet.'], 200);
  }







  public function update_balance($receptor,$cantidad)
  {
      $user = \Auth::user();
      $my_balance = User::find($user->id);
      $my_balance->saldo = $my_balance->saldo - $cantidad;
      $my_balance->save();

      $receptor_balance = User::where('wallet',$receptor)->first();
      $receptor_balance->saldo = $receptor_balance->saldo + $cantidad;
      $receptor_balance->save();
      return true;
  }







  public function add_balance(Request $request)
  {
      $user = \Auth::user();
      $my_balance = User::find($user->id);
      $my_balance->saldo = $request->ini;
      $my_balance->save();
      return response()->json(['message' => 'Success!! Balance added to this user.'], 200);
  }






  public function transactions_explorer()
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = \Auth::user();
    if($user->wallet != 0){
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host->value.'/transactions',
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
            return response($response, 200);
    }
    return response()->json(['message' => 'Unauthenticated.'], 401);
  }




  public function blockchain_blocks_back()
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = \Auth::user();
    if($user->wallet != 0){
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host->value.'/blocks',
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
            return response($response, 200);
    }
    return response()->json(['message' => 'Unauthenticated.'], 401);
  }






  public function miner_data()
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = \Auth::user();
    if($user->wallet != 0){
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host->value.'/miner_balance',
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
            return response($response, 200);
    }
    return response()->json(['message' => 'Unauthenticated.'], 401);
  }



  public function blockchain_minero()
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = \Auth::user();
    if($user->wallet != 0){
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $host->value.'/mine/transactions',
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
            if(strpos($response,"There are no unconfirmed transactions")){ return 0; }
            return 1;
    }
    return response()->json(['message' => 'ERROR: When try to mine block, contact support!!'], 401);
  }














  public function transaccion_cajero_to_user(Request $request)
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = User::where('id',1);
    if($user->saldo < $request->amount){ return response()->json(['message' => 'Payout not enough money for this transaction.'], 200); }
    if($request->recipient == $user->wallet){ return response()->json(['message' => 'Cant posible send transactions to yourself.'], 200); }
    if($user->wallet != 0){
            $data = [  'ide' => (int)$request->ide , 'recipient' => $request->recipient , 'amount' => floatval($request->amount) ];
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
            if($this->update_balance($request->recipient,floatval($request->amount))){
            $trans = new Transacciones();
            $trans->transmitter = $user->wallet;
            $trans->receiver = $request->recipient;
            $trans->amount = $request->amount;
            $trans->save();
            }
            $m2 = DB::table('users')->where('wallet',$request->recipient)->first();
            $r = $this->notifier($request->amount,$user->email,$m2->email,$request->recipient,$user->wallet);

            return response()->json(['message' => 'Success!! The transacction has been complete.'], 200);
    }
    return response()->json(['message' => 'This user doesnt have wallet.'], 200);
  }
















  // 1 - Metodo que se llama en cada transaccion para IPN
    public function notifier($amount,$m1,$m2,$wall1,$wall2)
      {
        $referRANDOM = "GOICOIN00000".rand(1, 100000000);
        $dte = Carbon::now()->toDateTimeString();
        $ipnData = 'Unique ID: '.$referRANDOM.' Authorized Date: '.
        $dte.' Transaction State: Success Receiver: '.$wall1.' Sender: '.$wall2.' Currency: GOI'.
        ' Fiat Currency: USD';
             // 1.- Intento notificar a URL proporcionada
              $stats = $this->make_connection_notification($ipnData);
              if($stats == 200){
                // 2.- Si notifica mando el mail
                $mail_noty = $this->mail_ipn($referRANDOM,$m1,$m2,$amount,$dte,$wall1,$wall2);
                  // 3.- Si se envio el mail guardo en base datos todo OK
                    if($mail_noty){
                      // Guardamos exito
                      $stat = DB::table('ipn')->insert([
                          'id_ipn' => $referRANDOM,
                          'status' => 'success',
                          'mail_status' => 'success',
                          'intents' => 1,
                          'ipn_data' => $ipnData,
                          'amount' => $amount
                      ]);
                      return $stat;
                    }else{
                      // Guardamos con error de mail
                      $stat = DB::table('ipn')->insert([
                          'id_ipn' => $referRANDOM,
                          'status' => 'success',
                          'intents' => 1,
                          'ipn_data' => $ipnData,
                          'amount' => $amount
                      ]);
                      return $stat;
                    }
              }else{
                // Guardamos en base de datos con error en notificacion WEB
                $stat = DB::table('ipn')->insert([
                    'id_ipn' => $referRANDOM,
                    'intents' => 1,
                    'ipn_data' => $ipnData,
                    'amount' => $amount
                ]);
                return $stat;
               }
      }


  // Concecion a URL de notificacion proporcionada y obtengo el codigo de respuesta
    public function make_connection_notification($ipn_Data)
      {
        $data = array('api_Data' => $ipn_Data);
        $payload = json_encode($data);
        // Prepare new cURL resource
        ini_set('memory_limit', '-1');
        $ch = curl_init('https://goicoin.net/api/url_receptora');
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


  // Metodo que envia el email de transaccion
      public function mail_ipn($referRANDOM,$mail1,$mail2,$am,$date,$w1,$w2)
      {
        $user = DB::table('users')->where('id',1)->first();
        $type = "Transaction Amount :";  $cant = $am;
        $mail = new PHPMailer(true);                            // Passing `true` enables exceptions
        try {
          // Server settings
          $mail->SMTPDebug = 0;                                	// Enable verbose debug output
          $mail->isSMTP();                                     	// Set mailer to use SMTP
          $mail->Host = 'smtp.gmail.com';												// Specify main and backup SMTP servers
          $mail->SMTPAuth = true;                              	// Enable SMTP authentication
          $mail->Username = "goicoin2@gmail.com";             // SMTP username
          $mail->Password = "cygipbnwihwygvwb";              // SMTP password
          $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
          $mail->Port = 587;                                    // TCP port to connect to
          //Recipients
          $mail->setFrom('goicoin2@gmail.com', 'Green Oceans Notififier');
          $mail->addAddress($mail1, 'Appreciable Customer');	// Add a recipient, Name is optional
          $mail->addReplyTo('goicoin2@gmail.com', 'Asistent');
          $mail->addCC($mail2);
          //Attachments (optional)
          // $mail->addAttachment('/var/tmp/file.tar.gz');			// Add attachments
          // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');	// Optional name
          //Content
          $mail->isHTML(true); 																	// Set email format to HTML
          $mail->Subject = '[1] Movimientos en tu Wallet Green Oceans';
          $mail->Body    = 'Correo de Notificacion Instantanea.<br><h2><hr>'
          .$type.' : '.$cant.' GOI(s)</h2>'.
          '<br><p>Unique ID: '.$referRANDOM.'</p>'.
          '<p>Uthorized Date: '.$date.'</p>'.
          '<p>Transaction State: Success</p>'.
          '<p>Reciver Wallet: '.$w1.'</p>'.
          '<p>Sender Wallet: '.$w2.'</p>'.
          '<p>Currency: GOI</p>'.
          '<p>Fiat Currency: USD</p>'.
          '<img style="width:25%;" src="https://goicoin.net/coin_green2.png"/>';						// message
          $mail->send();
          return true;
          // SI notifica Guardamos notificador registro ipn con notificacion lista
        } catch (Exception $e) {
          return false;
          // Si da error ingresamos en base de datos como error al notificar y poner intento
        }
      }




      public function cajero_one()
      {
          return view('cajeros.one');
      }
      public function cajero_two()
      {
          return view('cajeros.two');
      }
      public function cajero_tree()
      {
          return view('cajeros.tree');
      }
      public function cajero_four()
      {
          return view('cajeros.four');
      }
}
