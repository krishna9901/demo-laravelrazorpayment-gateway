<?php

use Illuminate\Support\Facades\Route;
Use App\Http\Controllers;

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


Route::get('product',[App\Http\Controllers\RazorpayController::class,'index']);
Route::post('razorpay-payment',[App\Http\Controllers\RazorpayController::class,'store'])->name('razorpay.payment.store');