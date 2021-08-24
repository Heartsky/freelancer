<?php

use App\Jobs\SummaryBankAccountJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/debug/branch/{id}/{date}', function($id, $date){
    SummaryBankAccountJob::dispatch($id, new \DateTime($date));
    dd(1);
});
Route::get('/debug/bank/{id}/{date}', function($id, $date){
    \App\Jobs\SummaryAccountJob::dispatch($id, new \DateTime($date));
    dd(1);
});
Route::get('/debug/customer/{id}/{date}', function($id, $date){
    \App\Jobs\SummaryCustomerMonthlyJob::dispatch($id, new \DateTime($date));
    dd(1);
});

Route::get('/debug/customers/{date}', function( $date){
    \App\Jobs\SummaryCustomerJob::dispatch(new \DateTime($date));
    dd(1);
});


Route::get('/debug', function( ){

    dd(true);
});


