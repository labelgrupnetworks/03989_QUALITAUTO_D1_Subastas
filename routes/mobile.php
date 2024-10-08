<?php

use App\Http\Controllers\Mobile\MobileAuctionsController;
use App\Http\Controllers\Mobile\MobileAuthController;
use App\Http\Controllers\Mobile\MobileCategoriesController;
use App\Http\Controllers\Mobile\MobileFavoritesController;
use App\Http\Controllers\Mobile\MobileLotsController;
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

	Route::get('auctions', [MobileAuctionsController::class, 'index'])->name('mobile.auctions.index');
	Route::get('auctions/{codsession}', [MobileAuctionsController::class, 'show'])->name('mobile.auctions.show');

	Route::get('categories', [MobileCategoriesController::class, 'index'])->name('mobile.categories.index');

	Route::get('auctions/{codsession}/lots', [MobileLotsController::class, 'index'])->name('mobile.auctions.lots.index');
	Route::get('auctions/{codauction}/lots/{lotref}', [MobileLotsController::class, 'show'])->name('mobile.auctions.lots.show');
	//all lots withou auction
	Route::get('lots', [MobileLotsController::class, 'index'])->name('mobile.lots.index');

	//lotes favoritos
	Route::get('favorites', [MobileFavoritesController::class, 'index'])->name('mobile.lots.favorites');
});
