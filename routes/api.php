<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('exchange_pass', 'APIController@exchange_pass')->name('exchange_pass');
Route::post('wallet_explore_api', 'APIController@wallet_explore_api')->name('wallet_explore_api');
Route::post('transaction_me_to_api', 'APIController@transaccion_cajero_to_user');

Route::post('printer_memory', 'APIController@printer_memory');













// API GROUP CONCEPT
Route::post('get_basic_info', 'APIController@get_basic_info');
Route::post('set_basic_info', 'APIController@set_basic_info');
Route::post('get_wallets', 'APIController@get_wallets');
Route::post('get_last_20_transactions', 'APIController@get_last_20_transactions');
Route::post('get_last_20_transactions_recibidas', 'APIController@get_last_20_transactions_recibidas');
Route::post('get_last_20_transactions_enviadas', 'APIController@get_last_20_transactions_enviadas');
Route::post('service_send', 'APIController@service_send');
Route::post('thaler_rate', 'APIController@thaler_rate');
Route::post('api_username', 'APIController@api_username');
Route::post('server_info', 'APIController@server_info');
Route::post('get_current_balance', 'APIController@get_current_balance');
Route::post('transaction_query', 'APIController@transaction_query');



Route::post('ipn', 'APIController@ipn');
Route::post('ipn_reintent', 'APIController@ipn_reintent');
Route::post('url_receptora', 'APIController@url_receptora');


Route::post('check_transaction_pending', 'APIController@check_transaction_pending');
Route::post('check_retiro_pending', 'APIController@check_retiro_pending');
Route::post('change_state_memory', 'APIController@change_state_memory');






// PASARELA Merchant API
Route::post('create_merchant', 'PasarelaAPIController@create_merchant');
Route::post('get_merchant_info', 'PasarelaAPIController@get_merchant_info');
Route::post('get_balance', 'PasarelaAPIController@get_balance');
Route::post('get_wallet', 'PasarelaAPIController@get_wallet');
Route::post('create_withdraw', 'PasarelaAPIController@create_whitdraw');
Route::post('get_withdraw_info', 'PasarelaAPIController@get_withdraw_info');
Route::post('get_tx_list', 'PasarelaAPIController@get_tx_list');
