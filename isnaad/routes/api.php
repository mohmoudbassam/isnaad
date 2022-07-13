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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix'=>'webhook'
],function (){
    Route::post('Mkhdoom', 'API\UpdateStatusController@Mkhdoom_status');
     Route::post('Tamex', 'API\UpdateStatusController@Tamex_status');
    Route::get('Aramex/{tr_no}/{status_code}', 'API\UpdateStatusController@Aramex_status');
    Route::post('GetOrder', 'API\UpdateStatusController@GetOrder');
});
