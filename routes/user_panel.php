<?php
# Obligatorio estar registrado.
Route::group(['middleware' => ['userAuth', 'SessionTimeout:' . Config::get('app.admin_session_timeout')]], function () {

	# Panel de usuario
	#Deprecated revisar
	//Route::get('{lang}/user/panel', 'UserController@panel');

	# Lista de pujas
	#Deprecated revisar
	/*Route::get('{lang}/user/panel/bids', 'UserController@bidsList');
		Route::get('{lang}/user/panel/bids'.'/page/{page}', 'UserController@bidsList');*/

	# Lista de Adjudicaciones
	# Deprecated revisar
	//Route::get('{lang}/user/panel/allotments', 'UserController@getAdjudicaciones');
	//Route::get('{lang}/user/panel/allotments'.'/page/{page}', 'UserController@getAdjudicaciones');

	# Lista de Favoritos
	Route::get('{lang}/user/panel/favorites', 'UserController@getFavoritos')->name('panel.favorites');
	Route::get('{lang}/user/panel/favorites' . '/page/{page}', 'UserController@getFavoritos');

	# Lista de Ventas cedente
	Route::get('{lang}/user/panel/sales', 'UserController@getSales')->name('panel.sales');
	Route::post('{lang}/user/panel/sales-info/', 'UserController@getInfoSales')->name('panel.salesInfo');
	Route::post('{lang}/user/panel/sales-facturas/', 'UserController@getFacturasPropietarioLineas')->name('panel.salesFactura');
	#CARLANDIA
	//Mis vehiculos en venta
	Route::get('{lang}/user/panel/my-active-sales', 'V5\CarlandiaSalesController@getActiveSales')->name('panel.active-sales');
	Route::get('{lang}/user/panel/my-sales', 'V5\CarlandiaSalesController@getAwardSales')->name('panel.award-sales');
	Route::get('{lang}/user/panel/my-sales-download', 'V5\CarlandiaSalesController@getDownloadSales')->name('panel.download-sales');


	#Lista de Temas Favoritos
	Route::get('{lang}/user/panel/themesfavorites', 'UserController@getTemaFavoritos');
	Route::post('/panel/save/favorites', 'UserController@savedTemaFavoritos');


	Route::get('{lang}/user/panel/addresses/{cod_sub?}', 'User\AddressController@index')->name('panel.addresses');

	# Informacion de usuario

	Route::post('api-ajax/client/update', 'UserController@updateClientInfo');
	Route::post('api-ajax/client/update/password', 'UserController@updatePassword');

	Route::get('/factura/{afral}-{nfral}', 'UserController@bills');
	Route::get('/prefactura/{cod_sub}', 'UserController@proformaInvoiceFile');

	Route::get('/{lang}/user/panel/modification-orders', 'UserController@ordersClient');

	Route::post('api-ajax/shipping_costs', 'PaymentsController@shippingCosts');

	#Lista de Temas Favoritos
	Route::get('{lang}/user/panel/pending_bills', 'UserController@getPendingBills');


	# Para Carlandia
	Route::get('{lang}/user/panel/pre-awards', 'UserController@preAwards')->name('panel.pre_awards');
	Route::get('{lang}/user/panel/counteroffers', 'UserController@getCounterOffers')->name('panel.counteroffers');
});
Route::get('{lang}/user/panel/info', 'UserController@accountInfo')->name('panel.account_info');

# Lista de Ordenes de licitacion
Route::get('{lang}/user/panel/orders', 'UserController@orderbidsList')->name('panel.orders');
Route::get('{lang}/user/panel/orders' . '/page/{page}', 'UserController@orderbidsList');

Route::get('{lang}/user/panel/allotments/outstanding', 'UserController@getAdjudicacionesPendientePago');
Route::get('{lang}/user/panel/allotments/paid', 'UserController@getAdjudicacionesPagadas');
Route::get('{lang}/user/panel/allotments/shopping-cart', 'UserController@getDirectSaleAdjudicaciones')->name('panel.allotment.diectsale');
Route::get('{lang}/user/panel/allotments/{cod_sub}', 'UserController@getAdjudicacionesPendientePagoBySub')->name('panel.allotment.sub');
Route::get('{lang}/user/panel/allotments', 'UserController@getAllAdjudicaciones')->name('panel.allotments');
Route::post('{lang}/user/panel/allotments/certificate', 'UserController@generateAuthenticityCertificate')->name('panel.allotment.certifiacte');


Route::post('{lang}/user/panel/shipment', 'UserController@getShipment')->name('panel.shipment');

Route::get('{lang}/user/panel/bills', 'UserController@allBills')->name('panel.bills');
Route::get('{lang}/user/panel/allotments-bills', 'UserController@getAllAllotmentsAndBills');

#carrito de la compra
Route::get('{lang}/user/panel/showShoppingCart', 'V5\CartController@showShoppingCart')->name('showShoppingCart');
Route::get('{lang}/user/panel/payShowShoppingCart', 'V5\CartController@payShowShoppingCart')->name('payShowShoppingCart');

#Carrito de articulos
Route::get('{lang}/user/panel/showArticleCart', 'V5\ArticleController@showArticleCart')->name('showArticleCart');
Route::get('{lang}/user/panel/payShowArticleCart', 'V5\ArticleController@payArticleCart')->name('payShowArticleCart');

#Pedidos de articulos
Route::get('{lang}/user/panel/showShoppingOrders', 'V5\ArticleController@showShoppingOrders')->name('panel.shopping_orders');

#pago por transferencia

Route::get('{lang}/user/panel/transferpayment', function () {
	exit(\View::make('front::pages.panel.transferpayment'));
})->name("transferpayment");

#NFT
Route::get('user/panel/loadPendingPayTransferNft', 'UserController@nftTransferPay');


#dejar esto al final, no se quien lo puso ni que  sentido tiene que coja cualquier valor
Route::get('{lang}/user/panel/{value}', 'UserController@myBills');
Route::post('/change-passw-user', 'UserController@changePassw');
