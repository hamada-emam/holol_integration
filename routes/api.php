<?php

use App\Http\Controllers\Client\Imile\Customer\Order;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

$key = config('app.secre_key');
Route::post("/webhook_callback/$key", [Order::class, 'callback']);
