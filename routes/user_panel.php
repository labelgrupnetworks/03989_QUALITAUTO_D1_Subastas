<?php

use App\Http\Controllers\Panel\FavoritesController;
use App\Http\Controllers\Panel\OrdersController;
use App\Http\Controllers\Panel\SalesController;
use App\Http\Controllers\Panel\SummaryController;
use App\Http\Controllers\V5\CarlandiaSalesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

# Obligatorio estar registrado.
Route::group(['middleware' => ['userAuth', 'SessionTimeout:' . Config::get('app.admin_session_timeout')]], function () {

	# Resumen
	Route::prefix('{lang}/user/panel/summary')->group(function () {
		Route::get('/active-sales', [SummaryController::class, 'summaryActiveSales'])->name('panel.summary.active-sales');
		Route::get('/finish-sales', [SummaryController::class, 'summaryFinishSales'])->name('panel.summary.finish-sales');
		Route::get('/pending-sales', [SummaryController::class, 'summaryPendingToBeAssigned'])->name('panel.summary.pending-sales');
		Route::get('/favorites', [SummaryController::class, 'favoritesCarrousel']);
	});

	# Lista de Favoritos
	Route::get('{lang}/user/panel/favorites', [FavoritesController::class, 'getFavoritos'])->name('panel.favorites');
	Route::get('{lang}/user/panel/favorites/page/{page}', [FavoritesController::class, 'getFavoritos']); //No se esta utilizando (14/08/2024)
	Route::get('{lang}/user/panel/themesfavorites', [FavoritesController::class, 'getTemaFavoritos'])->name('panel.themesfavorites');
	Route::post('/panel/save/favorites', [FavoritesController::class, 'savedTemaFavoritos'])->name('panel.save.favorites');

	# Lista de Ventas cedente
	Route::get('{lang}/user/panel/sales', [SalesController::class, 'getSales'])->name('panel.sales');
	// Diseño de panel de Tauler antiguo, No se esta utilizando (14/08/2024)
	// Route::post('{lang}/user/panel/sales-info/', [SalesController::class, 'getInfoSales'])->name('panel.salesInfo');
	// Route::post('{lang}/user/panel/sales-facturas/', [SalesController::class, 'getFacturasPropietarioLineas'])->name('panel.salesFactura');
	Route::get('{lang}/user/panel/sales/active', [SalesController::class, 'getSalesToActiveAuctions'])->name('panel.sales.active');
	Route::get('{lang}/user/panel/sales/finish', [SalesController::class, 'invoiceSalesOfFinishAuctions'])->name('panel.sales.finish');
	Route::get('{lang}/user/panel/sales/pending-assign', [SalesController::class, 'getLotsSalesPendingToBeAssign'])->name('panel.sales.pending-assign');

	#CARLANDIA
	//Mis vehiculos en venta
	Route::get('{lang}/user/panel/my-active-sales', [CarlandiaSalesController::class, 'getActiveSales'])->name('panel.active-sales');
	Route::get('{lang}/user/panel/my-sales', [CarlandiaSalesController::class, 'getAwardSales'])->name('panel.award-sales');
	Route::get('{lang}/user/panel/my-sales-download', [CarlandiaSalesController::class, 'getDownloadSales'])->name('panel.download-sales');

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

	# Preferencias
	Route::get('{lang}/user/panel/preferences', 'UserController@getPreferencesAndFamily')->name('panel.preferences');
	Route::post('user/panel/preferences', 'UserController@getSubfamilyForPreferences')->name('panel.preferences_subfamily');
	Route::post('{lang}/user/panel/preferences/create', 'UserController@setPreferences')->name('panel.create_preferences');
	Route::post('{lang}/user/panel/preferences/delete', 'UserController@deletePreferences')->name('panel.delete_preferences');
});

//fuera de userAuth para mostrar pagina que solicite el inicio de sesion
Route::get('{lang}/user/panel/summary/', [SummaryController::class, 'summary'])->name('panel.summary');

Route::get('{lang}/user/panel/info', 'UserController@accountInfo')->name('panel.account_info');

# Lista de Ordenes de licitacion
Route::get('{lang}/user/panel/orders', [OrdersController::class, 'orderbidsList'])->name('panel.orders');
//solamente se utiliza con la vista por lotes, pero nadie la usa (14/08/2024)
Route::get('{lang}/user/panel/orders' . '/page/{page}', [OrdersController::class, 'orderbidsList']);

Route::get('{lang}/user/panel/allotments/outstanding', 'UserController@getAdjudicacionesPendientePago');
Route::get('{lang}/user/panel/allotments/paid', 'UserController@getAdjudicacionesPagadas');
Route::get('{lang}/user/panel/allotments/shopping-cart', 'UserController@getDirectSaleAdjudicaciones')->name('panel.allotment.diectsale');
Route::get('{lang}/user/panel/allotments/proforma/{apre}-{npre}', 'UserController@getAdjudicacionesPendientePagoByProforma')->name('panel.allotment.proforma');
Route::get('{lang}/user/panel/allotments/{cod_sub}', 'UserController@getAdjudicacionesPendientePagoBySub')->name('panel.allotment.sub');
Route::get('{lang}/user/panel/allotments', 'UserController@getAllAdjudicaciones')->name('panel.allotments');
Route::post('{lang}/user/panel/allotments/certificate', 'UserController@generateAuthenticityCertificate')->name('panel.allotment.certifiacte');


Route::post('{lang}/user/panel/shipment', 'UserController@getShipment')->name('panel.shipment');

Route::get('{lang}/user/panel/bills', 'UserController@allBills')->name('panel.bills');
Route::get('{lang}/user/panel/allotments-bills', 'UserController@getInvoiceOverviewView')->name('panel.allotment-bills');

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
	exit(View::make('front::pages.panel.transferpayment'));
})->name("transferpayment");

#NFT
Route::get('user/panel/loadPendingPayTransferNft', 'UserController@nftTransferPay');


#dejar esto al final, no se quien lo puso ni que  sentido tiene que coja cualquier valor
Route::get('{lang}/user/panel/{value}', 'UserController@myBills');
Route::post('/change-passw-user', 'UserController@changePassw');
