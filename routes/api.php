<?php

use App\Http\Controllers\Currency\CurrencyRateController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/rate', CurrencyRateController::class)
    ->middleware([
        'x-base-currency',
        'throttle:50',
    ]);

Route::resource('product', ProductController::class)
    ->only([
        'store',
    ]);
