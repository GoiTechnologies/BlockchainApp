<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sistema;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoEmail;
use Illuminate\Support\Facades\Hash;

class PublicInfoController extends Controller
{


  public function precio(Request $request)
  {
      $precio = 20;
      return view('public_info.precio')->with("precio",$precio);
  }

  public function soporte(Request $request)
  {
      $precio = 1;
      return view('public_info.soporte')->with("precio",$precio);
  }

  public function nodos(Request $request)
  {
      $members = DB::table('users')->get();
      //dd($members->count());

      return view('public_info.nodos')->with("Miembros",$members->count());
  }

  public function bloques(Request $request)
  {
      $members = DB::table('users')->get();
      //dd($members->count());
      

        $host = Sistema::where('name', 'host_01')->first();
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


      return view('public_info.bloques')->with("bloques",$response)->with("Miembros",$members->count());
  }



  public function reset_password(Request $request)
  {
        //dd($request->email);

        $member = DB::table('users')->where('email',$request->email)->first();

        $objDemo = new \stdClass();
        $objDemo->demo_one = 'https://goicoin.net/request_reset?ide='.$member->id;
        $objDemo->demo_two = 'Demo Two Value';
        $objDemo->sender = 'Goicoin Reset Password Notification';
        $objDemo->receiver = $member->name;

        Mail::to($request->email)->send(new DemoEmail($objDemo));

      return view('auth.success_mail_reset_password');
  }


  public function reset_password_form(Request $request)
  {
      return view('auth.reset_password_form')->with("ide",$request->ide);
  }

  public function finish_reset(Request $request)
  {
    if($request->p1 == $request->p2){
      DB::table('users')
            ->where('id',$request->ide)
            ->update(['password' =>Hash::make($request->p1)]);
       return view('auth.finish_reset')->with("ide",$request->ide);
    }
    return redirect('/login');
  }




}
