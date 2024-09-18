<?php

use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Providers\RoutingServiceProvider;

Route::get(RoutingServiceProvider::redirect_lot(), [RedirectController::class, 'redirect_lot']);
Route::get(RoutingServiceProvider::redirect_page(), [RedirectController::class, 'redirect_page']);

/**
 * Redireccion a blog en wordpress
 */
if (Config::get('app.blog_wordpress')) {
	Route::permanentRedirect('/es/blog', '/blog');
	Route::permanentRedirect('/en/news', '/blog/en');
	Route::permanentRedirect('/en/blog', '/blog/en');
}
