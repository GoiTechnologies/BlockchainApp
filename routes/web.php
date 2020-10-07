<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api_reference', function () {
    return view('api');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/cajeros_admin', 'HomeController@cajeros_admin')->name('cajeros_admin');
Route::post('/update_cotizacion', 'HomeController@update_cotizacion')->name('update_cotizacion');


Route::get('/cajero_one', 'GET_POST_Controller@cajero_one')->name('cajero_one');
Route::get('/cajero_two', 'GET_POST_Controller@cajero_two')->name('cajero_two');
Route::get('/cajero_tree', 'GET_POST_Controller@cajero_tree')->name('cajero_tree');
Route::get('/cajero_four', 'GET_POST_Controller@cajero_four')->name('cajero_four');

Route::get('/transacciones_blockchain', 'HomeController@transacciones_blockchain')->name('transacciones_blockchain');
Route::get('/blockchain_blocks', 'HomeController@blockchain_blocks')->name('blockchain_blocks');
Route::post('/blockchain_find_block', 'HomeController@blockchain_find_block')->name('blockchain_find_block');


Route::post('/request_blockchain_code', 'GET_POST_Controller@request_blockchain_code')->name('request_blockchain_code');
Route::post('/request_wallet', 'GET_POST_Controller@request_wallet')->name('request_wallet');
Route::get('/wallet_explore', 'GET_POST_Controller@wallet_explore')->name('wallet_explore');
Route::post('/transaction', 'GET_POST_Controller@transaccion_me_to')->name('transaction');
Route::post('/add_balance', 'GET_POST_Controller@add_balance')->name('add_balance');
Route::post('/transactions_explorer', 'GET_POST_Controller@transactions_explorer')->name('transactions_explorer');
Route::post('/blockchain_blocks_back', 'GET_POST_Controller@blockchain_blocks_back')->name('blockchain_blocks_back');
Route::post('/blockchain_minero', 'GET_POST_Controller@blockchain_minero')->name('blockchain_minero');
Route::post('/miner_data', 'GET_POST_Controller@miner_data')->name('miner_data');

Route::post('/retiro_process', 'CajerosController@retiro_process')->name('retiro_process');


// Ruta anterior, manda la cantidad por unidad
Route::post('/deposito_process', 'CajerosController@deposito_process')->name('deposito_process');
// Ruta Nueva, manda la cantidad / cotizacion_mxn
Route::post('/deposito_process_dynamic', 'CajerosController@deposito_process_dynamic')->name('deposito_process_dynamic');
Route::post('/retiro_process_dynamic', 'CajerosController@retiro_process_dynamic')->name('retiro_process_dynamic');




Route::post('/compra_bitcoin', 'CajerosController@compra_bitcoin')->name('compra_bitcoin');
Route::post('/cancelar_operacion', 'CajerosController@cancelar_operacion')->name('cancelar_operacion');



Route::get('/success_deposito', 'CajerosController@success_deposito')->name('success_deposito');



Route::get('/test', function () {
    return view('cajeros.success_pages.success_deposito')
    ->with('user_id','2')->with('response','Error')
    ->with('cantidad','50')->with('wallet_to','TEST7474646hdyf7ryru7fuf');
});




Route::get('/precio', 'PublicInfoController@precio')->name('precio');
Route::get('/nodos', 'PublicInfoController@nodos')->name('nodos');
Route::get('/bloques', 'PublicInfoController@bloques')->name('bloques');
Route::get('/soporte', 'PublicInfoController@soporte')->name('soporte');

Route::post('/reset_pass', 'PublicInfoController@reset_password')->name('reset_pass');
Route::get('/request_reset', 'PublicInfoController@reset_password_form')->name('request_reset');
Route::post('/finish_reset', 'PublicInfoController@finish_reset')->name('finish_reset');
