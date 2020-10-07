<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class CajerosController extends Controller
{
    //


    // retiros de efectivo en cajero
    public function retiro_process(Request $request)
    {
      // 1.- AQUI REALIZO EL ENVIO DE LA WALLET A LA MASTER
      // Y DESCUENTO CANTIDAD LLAMADO API BLOCKCHAIN
      $master = User::where('id',1) -> first();
      $randomNumber = rand(100,750000);
      $randomNumber = "GOI00".$randomNumber;
      //dd("retiro",$request,$master,$randomNumber);
      $url = 'https://goicoin.net/api/service_send';
      $certificate_location = '/cacert.pem';
      // Create a new cURL resource
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);
      // Setup request to send json via POST
      $data = array(
          'api_key' => 'M4ST3RK3Y00000001',
          'wallet' => $request->wallet_to,
          'amount' => $request->amount_dlls,
          'recipient' => $master->wallet,
          'service' => 'send',
      );
      $payload = json_encode($data);
      // Attach encoded JSON string to the POST fields
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      // Set the content type to application/json
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      // Return response instead of outputting
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Execute the POST request
      $result = curl_exec($ch);
      header("Access-Control-Allow-Origin: *");
      // Close cURL resource

      if (curl_errno($ch)) {
          $error_msg = curl_error($ch);
          dd($error_msg);
      }
      if($result){
        // 2.- REGISTRO EN LA MEMORIA DE RETIROS PARA
        // ACTIVAR EL PAYOUT Y REALIZAR EL PAGO
        // Toda operacion anterior se cancela
        $stat = DB::table('retiros_memory_payout')
              ->where('state', '1')
              ->update(array('state' => '0' , 'response' => 'cancelada' ));
        // Genero el registro de la nueva opèracion
        $stat = DB::table('retiros_memory_payout')->insert(
            ['cajero_id' => $request->id_user, 'user_id' => $request->id_user,'cantidad' => $request->amount_dlls.".00",'moneda' => $request->currency,
             'state' => '1','response' => 'null' , 'wallet_to' => $request->wallet_to ]
        );

        // Registro el ticket para impresora
        $new_ticket = DB::table('ZTERMINALES_printer_memory')->insert(
                    ['receptor' => $request->wallet_to , 'no_referencia' => $randomNumber,
                     'id_user' => $request->id_user,
                     'status' => '1' , 'currency' => $request->currency ,
                     'sitio' => 'https://goicoin.net' ,
                     'cantidad' => $request->amount_dlls, 'empresa' => 'Green Oceans Inc.',
                     'tipo' => 'Retiro Efectivo GOI'   ]);


        if($request->id_user == '1'){
          return view('cajeros.waiting_pages.retiros_view')
          ->with('user_id',$request->id_user)->with('response','goicoin')
          ->with('cantidad',$request->amount_dlls)->with('wallet_to',$request->wallet_to);
        }
        if($request->id_user == '2'){
          return view('cajeros.waiting_pages.retiros_view_dos')
          ->with('user_id',$request->id_user)->with('response','goicoin')
          ->with('cantidad',$request->amount_dlls)->with('wallet_to',$request->wallet_to);
        }
        if($request->id_user == '3'){
          return view('cajeros.waiting_pages.retiros_view_tres')
          ->with('user_id',$request->id_user)->with('response','goicoin')
          ->with('cantidad',$request->amount_dlls)->with('wallet_to',$request->wallet_to);
        }
        if($request->id_user == '4'){
          return view('cajeros.waiting_pages.retiros_view_cuatro')
          ->with('user_id',$request->id_user)->with('response','goicoin')
          ->with('cantidad',$request->amount_dlls)->with('wallet_to',$request->wallet_to);
        }

      }else{
        dd('error');
      }
    }

    // Depositos en cajero en efectico
    public function deposito_process(Request $request)
    {
      // 1.- AQUI REALIZO EL ENVIO DE LA WALLET A LA MASTER
      // Y DESCUENTO CANTIDAD LLAMADO API BLOCKCHAIN
      $master = User::where('id',1) -> first();
      $randomNumber = rand(100,750000);
      $randomNumber = "GOI00".$randomNumber;
      //dd("retiro",$request,$randomNumber);
      $url = 'https://goicoin.net/api/service_send';
      $certificate_location = '/cacert.pem';
      // Create a new cURL resource
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);
      // Setup request to send json via POST
      $data = array(
          'api_key' => 'M4ST3RK3Y00000001',
          'wallet' => $master->wallet,
          'amount' => $request->cantidad,
          'recipient' => $request->wallet,
          'service' => 'send',
      );
      $payload = json_encode($data);
      // Attach encoded JSON string to the POST fields
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      // Set the content type to application/json
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      // Return response instead of outputting
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Execute the POST request
      $result = curl_exec($ch);
      header("Access-Control-Allow-Origin: *");
      // Close cURL resource

      if (curl_errno($ch)) {
          $error_msg = curl_error($ch);
          dd($error_msg);
      }
      if($result){
        // 2.- REGISTRO EN LA MEMORIA DE RETIROS PARA
        // ACTIVAR EL PAYOUT Y COBRAR EL MONTO
        // Agrego a la memoria del payout para recibir pago
        $stat = DB::table('ZTERMINALES_memory_payout')
              ->where('state', '1')
              ->update(array('state' => '0' , 'response' => 'cancelada' ));

        $stat = DB::table('ZTERMINALES_memory_payout')->insert(
        ['cajero_id' => $request->ide, 'user_id' => $request->ide,
        'cantidad' => $request->cantidad.".00",'moneda' => 'MXN',
         'state' => '1','response' => 'goicoin' , 'wallet_to' => $request->wallet ]
        );

        // Registro el ticket para impresora
        $new_ticket = DB::table('ZTERMINALES_printer_memory')->insert(
                    ['receptor' => $request->wallet , 'no_referencia' => $randomNumber,
                     'id_user' => $request->ide,
                     'status' => '1' , 'currency' => 'GOI' ,
                     'sitio' => 'https://goicoin.net' ,
                     'cantidad' => $request->cantidad, 'empresa' => 'Green Oceans Inc.',
                     'tipo' => 'Deposito Efectivo GOI'   ]);

                if($request->ide == '1'){
                  return view('cajeros.waiting_pages.waiting_deposito')
                  ->with('user_id',$request->ide)->with('response','goicoin')
                  ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
                }
                if($request->ide == '2'){
                  return view('cajeros.waiting_pages.waiting_deposito_dos')
                  ->with('user_id',$request->ide)->with('response','goicoin')
                  ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
                }
                if($request->ide == '3'){
                  return view('cajeros.waiting_pages.waiting_deposito_tres')
                  ->with('user_id',$request->ide)->with('response','goicoin')
                  ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
                }
                if($request->ide == '4'){
                  return view('cajeros.waiting_pages.waiting_deposito_cuatro')
                  ->with('user_id',$request->ide)->with('response','goicoin')
                  ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
                }
      }else{
        dd('error');
      }
    }

    // Depositos en cajero en efectico
    public function compra_bitcoin(Request $request)
    {
        dd("compra bitcoin",$request);
    }















    // CANCELAR Resta las monedas de la wallet que compro
    public function cancelar_operacion(Request $request)
    {
      // 1.- AQUI REALIZO EL ENVIO DE LA WALLET A LA MASTER
      // Y DESCUENTO CANTIDAD LLAMADO API BLOCKCHAIN
      $master = User::where('id',1) -> first();
      //dd("retiro",$request,$randomNumber);
      $url = 'https://goicoin.net/api/service_send';
      $certificate_location = '/cacert.pem';
      // Create a new cURL resource
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);
      // Setup request to send json via POST
      $data = array(
          'api_key' => 'M4ST3RK3Y00000001',
          'wallet' => $request->wallet,
          'amount' => $request->cantidad,
          'recipient' => $master->wallet,
          'service' => 'send',
      );
      $payload = json_encode($data);
      // Attach encoded JSON string to the POST fields
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      // Set the content type to application/json
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      // Return response instead of outputting
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Execute the POST request
      $result = curl_exec($ch);
      header("Access-Control-Allow-Origin: *");
      // Close cURL resource

      if (curl_errno($ch)) {
          $error_msg = curl_error($ch);
          dd($error_msg);
      }
      if($result){
        // 2.- REGISTRO EN LA MEMORIA DE RETIROS PARA
        // ACTIVAR EL PAYOUT Y COBRAR EL MONTO

        $stat = DB::table('ZTERMINALES_memory_payout')
              ->where('state', '1')
              ->update(array('state' => '0' , 'response' => 'cancelada' ));

              $stat2 = DB::table('ZTERMINALES_printer_memory')
                    ->where('status', '1')
                    ->update(array('status' => '0'));

                    if($request->ide == '1'){
                      return redirect("/cajero_one");
                    }
                    if($request->ide == '2'){
                      return redirect("/cajero_two");
                    }
                    if($request->ide == '3'){
                      return redirect("/cajero_tree");
                    }


      }else{
        dd('error');
      }
    }
















    // Depositos en cajero en efectico
    public function success_deposito(Request $request)
    {
        //dd("success deposito",$request);
        if($request->ide == '1'){
          return view('cajeros.success_pages.success_deposito')
          ->with('user_id',$request->ide)->with('response','goicoin')
          ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
        }
        if($request->ide == '2'){
          return view('cajeros.success_pages.success_deposito_dos')
          ->with('user_id',$request->ide)->with('response','goicoin')
          ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
        }
        if($request->ide == '3'){
          return view('cajeros.success_pages.success_deposito_tres')
          ->with('user_id',$request->ide)->with('response','goicoin')
          ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
        }
        if($request->ide == '4'){
          return view('cajeros.success_pages.success_deposito_cuatro')
          ->with('user_id',$request->ide)->with('response','goicoin')
          ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
        }

    }













    // Depositos en cajero en efectico
    public function deposito_process_dynamic(Request $request)
    {
      //Aqui recupero el valor en peso de la cripto
      $var = DB::table('sistemas')->where('name','cotizacion_mxn')->first();
      $final_qty = $request->cantidad / $var->value;
      $final_qty = round($final_qty, 5);

      // 1.- AQUI REALIZO EL ENVIO DE LA WALLET A LA MASTER
      // Y DESCUENTO CANTIDAD LLAMADO API BLOCKCHAIN
      $master = User::where('id',1) -> first();
      $randomNumber = rand(100,750000);
      $randomNumber = "GOI00".$randomNumber;
      //dd("retiro",$request,$randomNumber);
      $url = 'https://goicoin.net/api/service_send';
      $certificate_location = '/cacert.pem';
      // Create a new cURL resource
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);
      // Setup request to send json via POST
      $data = array(
          'api_key' => 'M4ST3RK3Y00000001',
          'wallet' => $master->wallet,
          'amount' => $final_qty,
          'recipient' => $request->wallet,
          'service' => 'send',
      );
      $payload = json_encode($data);
      // Attach encoded JSON string to the POST fields
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      // Set the content type to application/json
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      // Return response instead of outputting
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Execute the POST request
      $result = curl_exec($ch);
      header("Access-Control-Allow-Origin: *");
      // Close cURL resource

      if (curl_errno($ch)) {
          $error_msg = curl_error($ch);
          dd($error_msg);
      }
      if($result){
        // 2.- REGISTRO EN LA MEMORIA DE RETIROS PARA
        // ACTIVAR EL PAYOUT Y COBRAR EL MONTO
        // Agrego a la memoria del payout para recibir pago
        $stat = DB::table('ZTERMINALES_memory_payout')
              ->where('state', '1')
              ->update(array('state' => '0' , 'response' => 'cancelada' ));

        $stat = DB::table('ZTERMINALES_memory_payout')->insert(
        ['cajero_id' => $request->ide, 'user_id' => $request->ide,
        'cantidad' => $request->cantidad.".00",'moneda' => 'MXN',
         'state' => '1','response' => 'goicoin' , 'wallet_to' => $request->wallet ]
        );

        // Registro el ticket para impresora
        $new_ticket = DB::table('ZTERMINALES_printer_memory')->insert(
                    ['receptor' => $request->wallet , 'no_referencia' => $randomNumber,
                     'id_user' => $request->ide,
                     'status' => '1' , 'currency' => 'GOI' ,
                     'sitio' => 'https://goicoin.net' ,
                     'cantidad' => $request->cantidad, 'empresa' => 'Green Oceans Inc.',
                     'tipo' => 'Deposito Efectivo GOI'   ]);

                if($request->ide == '1'){
                  return view('cajeros.waiting_pages.waiting_deposito')
                  ->with('user_id',$request->ide)->with('response','goicoin')
                  ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
                }
                if($request->ide == '2'){
                  return view('cajeros.waiting_pages.waiting_deposito_dos')
                  ->with('user_id',$request->ide)->with('response','goicoin')
                  ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
                }
                if($request->ide == '3'){
                  return view('cajeros.waiting_pages.waiting_deposito_tres')
                  ->with('user_id',$request->ide)->with('response','goicoin')
                  ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
                }
                if($request->ide == '4'){
                  return view('cajeros.waiting_pages.waiting_deposito_cuatro')
                  ->with('user_id',$request->ide)->with('response','goicoin')
                  ->with('cantidad',$request->cantidad)->with('wallet_to',$request->wallet);
                }
      }else{
        dd('error');
      }
    }









    public function retiro_process_dynamic(Request $request)
    {
      $var = DB::table('sistemas')->where('name','cotizacion_mxn')->first();
      $final_qty = $request->amount_dlls / $var->value;
      $final_qty = round($final_qty, 5);
      // 1.- AQUI REALIZO EL ENVIO DE LA WALLET A LA MASTER
      // Y DESCUENTO CANTIDAD LLAMADO API BLOCKCHAIN
      $master = User::where('id',1) -> first();
      $randomNumber = rand(100,750000);
      $randomNumber = "GOI00".$randomNumber;
      //dd("retiro",$request,$master,$randomNumber);
      $url = 'https://goicoin.net/api/service_send';
      $certificate_location = '/cacert.pem';
      // Create a new cURL resource
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);
      // Setup request to send json via POST
      $data = array(
          'api_key' => 'M4ST3RK3Y00000001',
          'wallet' => $request->wallet_to,
          'amount' => $final_qty,
          'recipient' => $master->wallet,
          'service' => 'send',
      );
      $payload = json_encode($data);
      // Attach encoded JSON string to the POST fields
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      // Set the content type to application/json
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      // Return response instead of outputting
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Execute the POST request
      $result = curl_exec($ch);
      header("Access-Control-Allow-Origin: *");
      // Close cURL resource

      if (curl_errno($ch)) {
          $error_msg = curl_error($ch);
          dd($error_msg);
      }
      if($result){
        // 2.- REGISTRO EN LA MEMORIA DE RETIROS PARA
        // ACTIVAR EL PAYOUT Y REALIZAR EL PAGO
        // Toda operacion anterior se cancela
        $stat = DB::table('retiros_memory_payout')
              ->where('state', '1')
              ->update(array('state' => '0' , 'response' => 'cancelada' ));
        // Genero el registro de la nueva opèracion
        $stat = DB::table('retiros_memory_payout')->insert(
            ['cajero_id' => $request->id_user, 'user_id' => $request->id_user,'cantidad' => $request->amount_dlls.".00",'moneda' => $request->currency,
             'state' => '1','response' => 'null' , 'wallet_to' => $request->wallet_to ]
        );

        // Registro el ticket para impresora
        $new_ticket = DB::table('ZTERMINALES_printer_memory')->insert(
                    ['receptor' => $request->wallet_to , 'no_referencia' => $randomNumber,
                     'id_user' => $request->id_user,
                     'status' => '1' , 'currency' => $request->currency ,
                     'sitio' => 'https://goicoin.net' ,
                     'cantidad' => $request->amount_dlls, 'empresa' => 'Green Oceans Inc.',
                     'tipo' => 'Retiro Efectivo GOI'   ]);


        if($request->id_user == '1'){
          return view('cajeros.waiting_pages.retiros_view')
          ->with('user_id',$request->id_user)->with('response','goicoin')
          ->with('cantidad',$request->amount_dlls)->with('wallet_to',$request->wallet_to);
        }
        if($request->id_user == '2'){
          return view('cajeros.waiting_pages.retiros_view_dos')
          ->with('user_id',$request->id_user)->with('response','goicoin')
          ->with('cantidad',$request->amount_dlls)->with('wallet_to',$request->wallet_to);
        }
        if($request->id_user == '3'){
          return view('cajeros.waiting_pages.retiros_view_tres')
          ->with('user_id',$request->id_user)->with('response','goicoin')
          ->with('cantidad',$request->amount_dlls)->with('wallet_to',$request->wallet_to);
        }
        if($request->id_user == '4'){
          return view('cajeros.waiting_pages.retiros_view_cuatro')
          ->with('user_id',$request->id_user)->with('response','goicoin')
          ->with('cantidad',$request->amount_dlls)->with('wallet_to',$request->wallet_to);
        }

      }else{
        dd('error');
      }
    }
}
