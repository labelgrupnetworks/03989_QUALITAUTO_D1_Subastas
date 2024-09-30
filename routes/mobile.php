<?php

use App\Http\Controllers\Mobile\MobileAuctionsController;
use App\Http\Controllers\Mobile\MobileAuthController;
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

Route::get('login', function () {
	return response()->json(['message' => 'Unauthenticated'], 401);
})->name('login');

Route::post('/login', [MobileAuthController::class, 'login']);  // Ruta de login

Route::group(['middleware' => 'auth:sanctum'], function () { // Rutas protegida
	Route::get('/user', [MobileAuthController::class, 'user']);

	Route::get('auctions', [MobileAuctionsController::class, 'auctions'])->name('mobile.auctions');
	Route::get('auction/{codsession}', [MobileAuctionsController::class, 'auction'])->name('mobile.auction');
});
