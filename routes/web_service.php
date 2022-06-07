<?php

/*
|--------------------------------------------------------------------------
| Layout - 	webService
|--------------------------------------------------------------------------
*/



Route::group(['prefix' => 'webservice', 'namespace' => 'webservice', 'middleware' => ['webservice']], function () {

	#FUNCIONES
	Route::post('/{function}', 'WebServiceController@index');

});
