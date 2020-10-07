<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth;
use App\Sistema;
use App\Transacciones;
use App\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = \Auth::user();
        // Genera el nick_name en el primier inicio cuando no lo tiene
        $sistema = Sistema::all();
        $cotizacion_mxn = DB::table('sistemas')->where('name','cotizacion_mxn')->first();
        $transacciones = Transacciones::where('transmitter',$user->wallet)->orwhere('receiver',$user->wallet)->orderBy('id','desc')->paginate(1);
        if($user->user_level == '1'){
          return view('home')->with('user',$user)->with('cotizacion_mxn',$cotizacion_mxn)
          ->with('sistema',$sistema)->with('transacciones',$transacciones);
        }else{
          return view('home_user')->with('user',$user)
          ->with('sistema',$sistema)->with('transacciones',$transacciones);
        }

    }

    public function transacciones_blockchain()
    {
        $user = \Auth::user();
        // Genera el nick_name en el primier inicio cuando no lo tiene
        $sistema = Sistema::all();
        $transacciones = Transacciones::where('transmitter',$user->wallet)->orwhere('receiver',$user->wallet)->orderBy('id','desc')->paginate(1);
        return view('transactions_blockchain')->with('user',$user)
        ->with('sistema',$sistema)->with('transacciones',$transacciones);
    }


    public function blockchain_blocks()
    {
        $user = \Auth::user();
        // Genera el nick_name en el primier inicio cuando no lo tiene
        $sistema = Sistema::all();
        $transacciones = Transacciones::where('transmitter',$user->wallet)
        ->orwhere('receiver',$user->wallet)->orderBy('id','desc')->paginate(1);
        return view('blockchain_blocks')->with('user',$user)
        ->with('sistema',$sistema)->with('transacciones',$transacciones);
    }
    public function blockchain_find_block(Request $request)
    {
        $user = \Auth::user();
        // Genera el nick_name en el primier inicio cuando no lo tiene
        $sistema = Sistema::all();
        $transacciones = Transacciones::where('transmitter',$user->wallet)->orwhere('receiver',$user->wallet)->orderBy('id','desc')->paginate(1);
        return view('blockchain_find_block')->with('user',$user)->with('hash',$request->hash)
        ->with('sistema',$sistema)->with('transacciones',$transacciones);
    }





    public function cajeros_admin(Request $request)
    {
        $user = \Auth::user();
        // Genera el nick_name en el primier inicio cuando no lo tiene
        $sistema = Sistema::all();
        $transacciones = Transacciones::where('transmitter',$user->wallet)
        ->orwhere('receiver',$user->wallet)->orderBy('id','desc')->paginate(5);
        return view('cajeros_admin')->with('user',$user)->with('hash',$request->hash)
        ->with('sistema',$sistema)->with('transacciones',$transacciones);
    }





    public function update_cotizacion(Request $request)
    {
      if($request->new_val > 0){
       DB::table('sistemas')
              ->where('name', 'cotizacion_mxn')
              ->update(['value' => $request->new_val]);
      }
        return redirect('/home');
    }


}
