<?php

/*
|--------------------------------------------------------------------------
| Layout - ApiRest
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'apirest', 'namespace' => 'apirest'], function () {

	/*Users*/
	Route::post('/login-user', 'UserApiRestController@index');

	Route::post('/all-users', 'UserApiRestController@allUsers');

	Route::post('/set-user', 'UserApiRestController@setUser');

	Route::post('/get-user', 'UserApiRestController@getUser');

	/*Enterprise*/
	Route::post('/tracks', 'EnterpriseApiRestController@tracks');

	Route::post('/countrys', 'EnterpriseApiRestController@countrys');

	Route::post('/town', 'EnterpriseApiRestController@returnTown');
	Route::post('/get-repres', 'EnterpriseApiRestController@getRepres');

	/*Contrato*/
	Route::post('/new-contract', 'ContractApiRestController@newContract');

	Route::post('/get-contract/{idcontract?}', 'ContractApiRestController@getContract');
	Route::post('/update-contract', 'ContractApiRestController@updateContract');

	/*Lots*/
	Route::post('/set-lot', 'LotApiRestController@setLot');
	Route::post('/get-lot', 'LotApiRestController@getLot');

	/*Params*/
	Route::post('/params-app', 'EnterpriseApiRestController@paramsAPP');
});
