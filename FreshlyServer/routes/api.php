<?php

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

//Get Orders
Route::get('orders', [\App\Http\Controllers\OrderController::class, 'getOrder']);
//Update State
Route::put('update/{id_order}', [\App\Http\Controllers\OrderController::class, 'updateState']);
