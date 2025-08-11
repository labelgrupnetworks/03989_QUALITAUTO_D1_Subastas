<?php

use App\Http\Controllers\Panel\AllotmentsAndBillsController;
use App\Http\Controllers\Panel\FavoritesController;
use App\Http\Controllers\Panel\OrdersController;
use App\Http\Controllers\Panel\RepresentedController;
use App\Http\Controllers\Panel\SalesController;
use App\Http\Controllers\Panel\SummaryController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\V5\ArticleController;
use App\Http\Controllers\V5\CartController;
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
	// Diseño de panel de Tauler antiguo, No se estan utilizando (14/08/2024)
	// Route::post('{lang}/user/panel/sales-info/', [SalesController::class, 'getInfoSales'])->name('panel.salesInfo');
	// Route::post('{lang}/user/panel/sales-facturas/', [SalesController::class, 'getFacturasPropietarioLineas'])->name('panel.salesFactura');
	Route::get('{lang}/user/panel/sales/active', [SalesController::class, 'getSalesToActiveAuctions'])->name('panel.sales.active');
	Route::get('{lang}/user/panel/sales/finish', [SalesController::class, 'invoiceSalesOfFinishAuctions'])->name('panel.sales.finish');
	Route::get('{lang}/user/panel/sales/pending-assign', [SalesController::class, 'getLotsSalesPendingToBeAssign'])->name('panel.sales.pending-assign');

	# Informacion de usuario
	Route::get('{lang}/user/panel/addresses/{cod_sub?}', [AddressController::class, 'index'])->name('panel.addresses');
	Route::post('api-ajax/client/update', [UserController::class, 'updateClientInfo']);
	Route::post('api-ajax/client/update/password', [UserController::class, 'updatePassword']);

	Route::get('/factura/{afral}-{nfral}', [AllotmentsAndBillsController::class, 'bills']);
	Route::get('/prefactura/{cod_sub}', [AllotmentsAndBillsController::class, 'proformaInvoiceFile']);

	Route::get('/{lang}/user/panel/modification-orders', [OrdersController::class, 'ordersClient']);

	# Preferencias
	Route::get('{lang}/user/panel/preferences', [UserController::class, 'getPreferencesAndFamily'])->name('panel.preferences');
	Route::post('user/panel/preferences', [UserController::class, 'getSubfamilyForPreferences'])->name('panel.preferences_subfamily');
	Route::post('{lang}/user/panel/preferences/create', [UserController::class, 'setPreferences'])->name('panel.create_preferences');
	Route::post('{lang}/user/panel/preferences/delete', [UserController::class, 'deletePreferences'])->name('panel.delete_preferences');

	Route::get('{lang}/user/panel/represented', [RepresentedController::class, 'showList'])->name('panel.represented.list');
	Route::post('{lang}/user/panel/represented/create', [RepresentedController::class, 'create'])->name('panel.represented.create');
	Route::post('{lang}/user/panel/represented/update', [RepresentedController::class, 'update'])->name('panel.represented.update');
	Route::post('{lang}/user/panel/represented/toggle-status', [RepresentedController::class, 'toggleStatus'])->name('panel.represented.toggle-status');
	Route::post('{lang}/user/panel/represented/delete', [RepresentedController::class, 'delete'])->name('panel.represented.delete');

});

//fuera de userAuth para mostrar pagina que solicite el inicio de sesion
Route::get('{lang}/user/panel/summary/', [SummaryController::class, 'summary'])->name('panel.summary');

Route::get('{lang}/user/panel/info', [UserController::class, 'accountInfo'])->name('panel.account_info');
Route::get('/{lang}/seeShippingAddress', [AddressController::class, 'seeShippingAddress']);
Route::post('/change_address_shipping', [AddressController::class, 'updateShippingAddress']);
Route::post('/delete_address_shipping', [AddressController::class, 'deleteShippingAddress']);
Route::post('/api-ajax/add_favorite_address_shipping', [AddressController::class, 'FavoriteShippingAddress']);

# Lista de Ordenes de licitacion
Route::get('{lang}/user/panel/orders', [OrdersController::class, 'orderbidsList'])->name('panel.orders');
//solamente se utiliza con la vista por lotes, pero nadie la usa (14/08/2024)
Route::get('{lang}/user/panel/orders' . '/page/{page}', [OrdersController::class, 'orderbidsList']);

Route::prefix('{lang}/user/panel/allotments')->group(function () {
	Route::get('/shopping-cart', [AllotmentsAndBillsController::class, 'getDirectSaleAdjudicaciones'])->name('panel.allotment.diectsale');
	Route::get('/proforma/{apre}-{npre}', [AllotmentsAndBillsController::class, 'getAdjudicacionesPendientePagoByProforma'])->name('panel.allotment.proforma');
	Route::get('/{cod_sub}', [AllotmentsAndBillsController::class, 'getAdjudicacionesPendientePagoBySub'])->name('panel.allotment.sub');
	Route::get('/', [AllotmentsAndBillsController::class, 'getAllAdjudicaciones'])->name('panel.allotments');
	Route::post('/certificate', [AllotmentsAndBillsController::class, 'generateAuthenticityCertificate'])->name('panel.allotment.certifiacte');
});

Route::post('{lang}/user/panel/shipment', [AllotmentsAndBillsController::class, 'getShipment'])->name('panel.shipment');
Route::get('{lang}/user/panel/bills', [AllotmentsAndBillsController::class, 'allBills'])->name('panel.bills');
Route::get('{lang}/user/panel/allotments-bills', [AllotmentsAndBillsController::class, 'getInvoiceOverviewView'])->name('panel.allotment-bills');

#carrito de la compra
Route::get('{lang}/user/panel/showShoppingCart', [CartController::class, 'showShoppingCart'])->name('showShoppingCart');
Route::get('{lang}/user/panel/payShowShoppingCart', [CartController::class, 'payShowShoppingCart'])->name('payShowShoppingCart');

#Carrito de articulos
Route::get('{lang}/user/panel/showArticleCart', [ArticleController::class, 'showArticleCart'])->name('showArticleCart');
Route::get('{lang}/user/panel/payShowArticleCart', [ArticleController::class, 'payArticleCart'])->name('payShowArticleCart');

#Pedidos de articulos
Route::get('{lang}/user/panel/showShoppingOrders', [ArticleController::class, 'showShoppingOrders'])->name('panel.shopping_orders');

#pago por transferencia

Route::get('{lang}/user/panel/transferpayment', function () {
	exit(View::make('front::pages.panel.transferpayment'));
})->name("transferpayment");

#NFT
Route::get('user/panel/loadPendingPayTransferNft', [UserController::class, 'nftTransferPay']);

Route::post('/change-passw-user', [UserController::class, 'changePassw'])->name('user.change-passw');
