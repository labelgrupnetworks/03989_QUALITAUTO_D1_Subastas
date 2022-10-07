<?php


Route::get(\Routing::redirect_lot(), 'RedirectController@redirect_lot');
Route::get(\Routing::redirect_page(), 'RedirectController@redirect_page');


/**
 * Redireccion a blog en wordpress
 */
if (Config::get('app.blog_wordpress')) {
	Route::permanentRedirect('/es/blog', '/blog');
	Route::permanentRedirect('/en/news', '/blog/en');
	Route::permanentRedirect('/en/blog', '/blog/en');
}
