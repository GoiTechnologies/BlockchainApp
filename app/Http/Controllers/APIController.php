<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Sistema;
use App\User;
use App\Transacciones;
use Illuminate\Support\Facades\DB;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;

class APIController extends Controller
{
  public function exchange_pass(Request $request)
  {
      $pass = Hash::make($request->pass);
      return response()->json(['pass_code' => $pass], 200);
  }

  public function wallet_explore_api(Request $request)
  {
    $host = Sistema::where('name', 'host_01')->first();
    $user = User::where('id',$request->id_user)->first();
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
















    public function transaccion_cajero_to_user(Request $request)
    {
      $host = Sistema::where('name', 'host_01')->first();
      $user = User::find(1);
      if($user->saldo < $request->amount){ return response()->json(['message' => 'Payout not enough money for this transaction.'], 200); }
      if($request->recipient == $user->wallet){ return response()->json(['message' => 'Cant posible send transactions to yourself.'], 200); }
      if($user->wallet != 0){
              $data = [
              'ide' => $user->id ,
              'recipient' => $request->recipient ,
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


    public function update_balance($receptor,$cantidad)
    {
        $my_balance = User::find(1);
        $my_balance->saldo = $my_balance->saldo - $cantidad;
        $my_balance->save();

        $receptor_balance = User::where('wallet',$receptor)->first();
        $receptor_balance->saldo = $receptor_balance->saldo + $cantidad;
        $receptor_balance->save();
        return true;
    }






    public function get_basic_info(Request $request)
    {
      // VALIDACION 1 Si no vienen los campos necesarios
      $data = json_decode($request->getContent(), true);
      if(!isset($data["api_key"]) || !isset($data["wallet"]) ){
          return response()->json(['error' => 'missing params'], 200);
      }
      // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
      $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
      if($user){
        // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
        $user2 = User::where('wallet',$data["wallet"])->first();
        if($user2){
          return response()->json([
            'name' => $user2->name,
            'email' => $user2->email,
            'balance' => $user2->saldo.""
          ], 200);
        }else{
          return response()->json(['error' => 'access denied'], 200);
        }
      }else{
        return response()->json(['error' => 'access denied'], 200);
      }
    }








    public function set_basic_info(Request $request)
    {
      // VALIDACION 1 Si no vienen los campos necesarios
      $data = json_decode($request->getContent(), true);
      if(!isset($data["api_key"]) || !isset($data["wallet"]) ){
          return response()->json(['error' => 'missing params'], 200);
      }
      // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
      $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
      if($user){
        // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
        $user2 = User::where('wallet',$data["wallet"])->first();
        if($user2){

          $state = DB::table('users')
            ->where('wallet',$data["wallet"])
            ->update(['name' => $data["new_name"] , 'email' => $data["new_email"] ]);
          if($state){
            return response()->json(['update' => 'success'], 200);
          }
          return response()->json(['update' => 'error'], 200);
        }else{
          return response()->json(['update' => 'error'], 200);
        }
      }else{
        return response()->json(['update' => 'error'], 200);
      }
    }















    public function get_wallets(Request $request)
    {
      // VALIDACION 1 Si no vienen los campos necesarios
      $data = json_decode($request->getContent(), true);
      if(!isset($data["api_key"]) || !isset($data["wallet"]) ){
          return response()->json(['error' => 'missing params'], 200);
      }
      // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
      $users = DB::table('users')->get();
      $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
      if($user){
        // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
        $cont = 0;
        $json = "{";
        foreach($users as $ui){
          if($ui->wallet != ""){
            if($cont > 0){
              $json .= ',"'.$cont.'":"'.$ui->wallet.'"';
              $cont++;
            }else{
              $json .= '"'.$cont.'":"'.$ui->wallet.'"';
              $cont++;
            }
          }
        }
        $json .= "}";
        return response( $json , 200 );
      }else{
        return response()->json(['update' => 'error'], 200);
      }
    }







    public function get_last_20_transactions(Request $request)
    {
      // VALIDACION 1 Si no vienen los campos necesarios
      $data = json_decode($request->getContent(), true);
      if(!isset($data["api_key"]) || !isset($data["wallet"]) ){
          return response()->json(['error' => 'missing params'], 200);
      }
      // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
      $users = DB::table('users')->get();
      $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
      if($user){
        // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
        $trans = DB::table('transacciones')
        ->where('transmitter',$data["wallet"])
        ->OrWhere('receiver',$data["wallet"])
        ->orderBy('id', 'desc')->get();

        return response()->json($trans, 200);

      }else{
        return response()->json(['error' => 'access denied'], 200);
      }
    }





















    public function service_send(Request $request)
    {
      // VALIDACION 1 Si no vienen los campos necesarios
      $data = json_decode($request->getContent(), true);
      if(!isset($data["api_key"]) || !isset($data["wallet"]) || !isset($data["amount"])
       || !isset($data["recipient"]) || !isset($data["service"]) || $data["service"] != "send" ){
          return response()->json(['error' => 'missing params'], 200);
      }
      // VALIDO QUE EL RECEPTOR EXISTA
      $recep = DB::table('users')->where("wallet",$data["recipient"])->first();
      if(!$recep){ return response()->json(['error' => 'missing recipient'], 200); }
      // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
      $user = DB::table('users')->where("wallet",$data["wallet"])->first();
      $user_api = DB::table('api_users')->where('api_key',$data["api_key"])->first();
      if($user_api){
        // VALIDACION 3 Si encuentro el ID del usuario (Wallet)
            if($user){
                      $host = Sistema::where('name', 'host_01')->first();
                      if($user->saldo < floatval($data["amount"]) ){ return response()->json(['message' => 'You have not enough money for this transaction.'], 200); }
                      if($data["recipient"] == $user->wallet){ return response()->json(['message' => 'Cant posible send transactions to yourself.'], 200); }
                      if($data["wallet"] != 0){
                              $data2 = [  'ide' => (int)$user->id - 1 , 'recipient' => $data["recipient"] , 'amount' => floatval($data["amount"]) ];
                              $curl = curl_init();
                              curl_setopt_array($curl, array(
                                CURLOPT_URL => $host->value.'/transaction_me_to',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30000,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => json_encode($data2),
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
                              if($this->update_balance_3params($data["wallet"],$data["recipient"],floatval($data["amount"]))){
                              $trans = new Transacciones();
                              $trans->transmitter = $user->wallet;
                              $trans->receiver = $data["recipient"];
                              $trans->amount = $data["amount"];
                              $trans->save();
                              }
                              // al enviar aqui llano el notificador IPN
                              // Obtengo mail de la wallet
                              $m2 = DB::table('users')->where('wallet',$data["recipient"])->first();
                              $r = $this->notifier($data["amount"],$user->email,$m2->email,$data["recipient"],$user->wallet);



                              return response()->json(['message' => 'Success!! The transacction has been complete.'], 200);
                      }
                      return response()->json(['message' => 'This user doesnt have wallet.'], 200);
                    }
            }else{
              return response()->json(['error' => 'access denied'], 200);
            }
        return response()->json($trans, 200);
    }






    public function update_balance_3params($emisor,$receptor,$cantidad)
    {
        $user = DB::table('users')->where("wallet",$emisor)->first();
        $my_balance = User::find($user->id);
        $my_balance->saldo = $my_balance->saldo - $cantidad;
        $my_balance->save();

        $receptor_balance = User::where('wallet',$receptor)->first();
        $receptor_balance->saldo = $receptor_balance->saldo + $cantidad;
        $receptor_balance->save();
        return true;
    }

















        public function thaler_rate(Request $request)
        {
          // VALIDACION 1 Si no vienen los campos necesarios
          $data = json_decode($request->getContent(), true);
          if(!isset($data["api_key"]) || !isset($data["service"]) ){
              return response()->json(['error' => 'missing params'], 200);
          }
          // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
          $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
          if($user){
            // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
            $rate = DB::table('public_info')->where("variable","Thaler")->first();
              return response()->json([
                'name' => $rate->variable,
                'currency' => $rate->text,
                'value' => $rate->valor.""
              ], 200);

          }else{
            return response()->json(['error' => 'Invalid Api Key'], 200);
          }
        }







        public function api_username(Request $request)
        {
          // VALIDACION 1 Si no vienen los campos necesarios
          $data = json_decode($request->getContent(), true);
          if(!isset($data["api_key"]) || !isset($data["service"]) ){
              return response()->json(['error' => 'missing params'], 200);
          }
          // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
          $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
          if($user){
            // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
            $rate = DB::table('api_users')->where("api_key",$data["api_key"])->first();
              return response()->json([
                'name' => $rate->usuario
              ], 200);

          }else{
            return response()->json(['error' => 'Invalid Api Key'], 200);
          }
        }













        public function get_last_20_transactions_recibidas(Request $request)
        {
          // VALIDACION 1 Si no vienen los campos necesarios
          $data = json_decode($request->getContent(), true);
          if(!isset($data["api_key"]) || !isset($data["wallet"]) ){
              return response()->json(['error' => 'missing params'], 200);
          }
          // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
          $users = DB::table('users')->get();
          $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
          if($user){
            // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
            $trans = DB::table('transacciones')
            ->OrWhere('receiver',$data["wallet"])
            ->orderBy('id', 'desc')->get();

            return response()->json($trans, 200);

          }else{
            return response()->json(['error' => 'access denied'], 200);
          }
        }

        public function get_last_20_transactions_enviadas(Request $request)
        {
          // VALIDACION 1 Si no vienen los campos necesarios
          $data = json_decode($request->getContent(), true);
          if(!isset($data["api_key"]) || !isset($data["wallet"]) ){
              return response()->json(['error' => 'missing params'], 200);
          }
          // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
          $users = DB::table('users')->get();
          $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
          if($user){
            // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
            $trans = DB::table('transacciones')
            ->where('transmitter',$data["wallet"])
            ->orderBy('id', 'desc')->get();

            return response()->json($trans, 200);

          }else{
            return response()->json(['error' => 'access denied'], 200);
          }
        }


        public function server_info(Request $request)
        {
          // VALIDACION 1 Si no vienen los campos necesarios
          $data = json_decode($request->getContent(), true);
          if(!isset($data["api_key"]) || !isset($data["wallet"]) ){
              return response()->json(['error' => 'missing params'], 200);
          }
          // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
          $users = DB::table('users')->get();
          $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
          if($user){
            // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
            $recep = DB::table('users')->where("wallet",$data["wallet"])->first();
            if(!$recep){ return response()->json(['error' => 'access denied'], 200); }
            $t=time();

            return response()->json([
              "host" => "https://blockexplorer.club/api",
              "server_time" => date("Y-m-d",$t)." ".date("h:i:sa")."",
              "empresa" => "group concept",
              "blockchain" => "thaler chain",
              "criptomoneda" => "thaler coin",
              "monedas" => "100000000"
            ], 200);

          }else{
            return response()->json(['error' => 'access denied'], 200);
          }
        }

















        public function get_current_balance(Request $request)
        {
          // VALIDACION 1 Si no vienen los campos necesarios
          $data = json_decode($request->getContent(), true);
          if(!isset($data["api_key"]) || !isset($data["wallet"]) ){
              return response()->json(['error' => 'missing params'], 200);
          }
          // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
          $userW = DB::table('users')->where('wallet',$data["wallet"])->first();
          $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
          if($user){
            // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
            $recep = DB::table('users')->where("wallet",$data["wallet"])->first();
            if(!$recep){ return response()->json(['error' => 'access denied'], 200); }
            if(!$userW){ return response()->json(['error' => 'access denied'], 200); }


            return response()->json([
              "balance" => $userW->saldo.""
            ], 200);

          }else{
            return response()->json(['error' => 'access denied'], 200);
          }
        }























        public function transaction_query(Request $request)
        {
          // VALIDACION 1 Si no vienen los campos necesarios
            $data = json_decode($request->getContent(), true);
          if(!isset($data["api_key"]) || !isset($data["wallet"]) || !isset($data["receiver"]) || !isset($data["amount"] ) ){
              return response()->json(['error' => 'missing params'], 200);
          }
          // VALIDACION 2 Si la llave es correcta buscamos en base de datos al usuario
          $users = DB::table('users')->get();
          $user = DB::table('api_users')->where('api_key',$data["api_key"])->first();
          if($user){
            // VALIDACION 3 Si encuentra usuario regresa datos si no regresa error
            $trans = DB::table('transacciones')
            ->where('transmitter',$data["wallet"])
            ->Where('receiver',$data["receiver"])
            ->Where('amount',$data["amount"].".00")
            ->orderBy('id', 'desc')->get();
            return response()->json($trans, 200);
          }else{
            return response()->json(['error' => 'access denied'], 200);
          }
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
      $data = array('ipnData' => $ipn_Data);
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



    // Tarea para hacer 3 intentos de notificacion
        public function ipn_reintent()
        {
           // Aqui traigo los registros con error y reintento notifier sin base de datos
           $notis = DB::table('ipn')->where('status','failed')->get();
           $conter_success = 0;
           $conter_fail = 0;
           foreach($notis as $n){
             $code = $this->make_connection_notification($n->ipn_data);
             if($code == 200){
               $conter_success = $conter_success + 1;
               // update como status success he intento
               $new_intnt = $n->intents + 1;
               $affected = DB::table('ipn')
              ->where('id', $n->id)
              ->update(['intents' => $new_intnt , 'status' => 'success']);
             }else{
               $conter_fail = $conter_fail + 1;
               // update he intento
               $new_intnt = $n->intents + 1;
               $affected = DB::table('ipn')
              ->where('id', $n->id)
              ->update(['intents' => $new_intnt]);
             }
           }
           return response()->json(['success' => $conter_success , 'fails' => $conter_fail ], 200);
        }




// Simulacion de URL IPN proporcionada para notificaciones
    public function url_receptora(Request $request)
    {
        return response()->json(['message' => 'notificacion recibida'  ], 200);
    }




    public function check_transaction_pending(Request $request){
      $data = json_decode($request->getContent(), true);

      // Validar  $data["api_key"]
        $stat = DB::table('zterminales_memory_payout')
        ->where('cajero_id', $request->ide)->where('state', 1)->first();
        if($stat){
          return response()->json([
            'cantidad' => "".$stat->cantidad,
            'moneda' => "".$stat->moneda,
            'state' => "".$stat->state
                  ], 200);
        }else{
          return response()->json([
            'cantidad' => "0",
            'moneda' => 'SUCCESS',
            'state' => "3"
                  ], 200);
        }
      }



      public function check_retiro_pending(Request $request){
        $data = json_decode($request->getContent(), true);
        // Validar  $data["api_key"]
        $stat = DB::table('retiros_memory_payout')
        ->where('cajero_id', $data["ide"])->where('state', 1)->first();
        if($stat){
          $stat2 = DB::table('retiros_memory_payout')
                ->where('state', '1')
                ->update(array('state' => '0' , 'response' => 'retiro exitoso' ));
          return response()->json([
            'cantidad' => ''.$stat->cantidad,
            'moneda' => 'MXN',
            'state' => ''.$stat->state
                  ], 200);
        }else{
          return response()->json([
            'cantidad' => "0",
            'moneda' => 'SUCCESS',
            'state' => "3"
                  ], 200);
        }
      }



  public function printer_memory(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        // Validar  $data["api_key"]
        $memory = DB::table('ZTERMINALES_printer_memory')->where('status','1')
        ->where('id_user',$data["cajero_Id"])->first();
        if($memory){
          $state = DB::table('ZTERMINALES_printer_memory')
                  ->where('status', 1)
                  ->update(['status' => 0]);
        }
        return response()->json($memory, 200);
    }




    public function change_state_memory(Request $request){
      $data = json_decode($request->getContent(), true);
      // Validar  $data["api_key"]
       $state = DB::table('ZTERMINALES_memory_payout')
               ->where('state', 1)->where('cajero_id', $data["ide"])
               ->update(['state' => 0]);

      if(!$state){
        $state = DB::table('ZTERMINALES_memory_payout')
                ->where('state', 1)->where('cajero_id', $request->ide)
                ->update(['state' => 0]);
      }
         return response()->json([
           'state' => $state  ], 200);
     }
}
