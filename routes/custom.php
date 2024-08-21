<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\CustomControllers;
use App\Http\Controllers\EnterpriseController;
use App\Providers\RoutingServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

#ALCALA
// Comprar catálogo
Route::get('/{lang?}/comprar-catalogo', 'V5\AutoFormulariosController@ComprarCatalogo');

#SOLER, GUTINVEST,
// DEPARTAMENTOS
Route::get(RoutingServiceProvider::slugSeo('departamentos', true), 'PageController@getDepartment');
Route::get(RoutingServiceProvider::translateSeo('departamento') . '{text}', [EnterpriseController::class, 'department'])->name('department');
Route::get(RoutingServiceProvider::translateSeo('video-subastas'), [CustomControllers::class, 'videoAuctions']);
Route::get(RoutingServiceProvider::translateSeo('equipo'), [EnterpriseController::class, 'team'])->name('enterprise.team');

/**
 * TAULER - Landings
 * @todo No se esta utilizando (20/08/2024)
 */
Route::get(RoutingServiceProvider::slugSeo('vender-monedas'), 'ValoracionController@Tasacion');
Route::get(RoutingServiceProvider::slugSeo('libros-numismatica'), 'ValoracionController@Books');
Route::get(RoutingServiceProvider::slugSeo('accesorios-numismatica'), 'ValoracionController@Numismatica');
Route::get(RoutingServiceProvider::slugSeo('subasta-numismatica'), 'ValoracionController@SubastaNumismaticaPrimavera');
Route::get(RoutingServiceProvider::slugSeo('servicios', true), 'ServicesController@index');
Route::get('/{lang}/servicios/encapsulacion', 'ServicesController@encapsulacion');
Route::get('/{lang}/servicios/fotografias', function () {
	return View::make('pages.servicios.fotografias');
});
Route::post('/{lang}/servicios/valoracion-fotografia', 'ServicesController@valoracionFotografia');
Route::post('/{lang}/servicios/valoracion-encapsulacion', 'ServicesController@valoracionEncapsulacion');
Route::get('/{lang}/numismatica-madrid', 'Landing\LandingController@landing');

#VICO
// Estaticas con banner
Route::view(RoutingServiceProvider::translateSeo('tienda-online'), 'front::pages.bannerPage', ['data' => ['name_web_page' => 'foot.direct_sale', 'banner' => 'tienda-banner']]);
Route::view(RoutingServiceProvider::slugSeo('servicios-numismatica'), 'front::pages.bannerPage', ['data' => ['name_web_page' => 'Servicios', 'banner' => 'servicios-banner']]);

#DURAN
// Landing de login
Route::get(RoutingServiceProvider::slug('login-landing'), 'UserController@loginLanding');
Route::post('/api-ajax/external-login', 'UserController@encryptLogin');
Route::post('/ordenTelefonica', 'externalws\duran\PujaTelefonicaController@createTelefonica');
Route::post('/verBotonOrdenTelefonica', 'externalws\duran\PujaTelefonicaController@wbVerBotonTelefono');
Route::get('/cancelreserveWs', [CronController::class, 'CancelReservationWS']);
Route::get('/consentimiento', 'externalws\duran\ConsentimientoControler@createConsentimiento');

Route::get(RoutingServiceProvider::translateSeo('landing-subastas', '') . '{keySubSection}', 'V5\LotListController@getCustomListSubSection')->name('landing-subastas');


#DURAN-GALLERY
Route::view(RoutingServiceProvider::slug('dmg'), 'front::pages.dmg');

#SEGRE
//Login
Route::post('/custom_login', 'UserController@customLogin');


#PACKANGERS
//Exportación en Excel de subasta

#ANSORENA
Route::view(RoutingServiceProvider::slugSeo('exposicion_actual'), 'front::pages.landing_galery.exposicion_actual');
Route::view(RoutingServiceProvider::slugSeo('ventas-destacadas'), 'front::pages.ventas_destacadas');
Route::get('/' . Config::get('app.locale') . '/private-chanel/login', [CustomControllers::class, 'privateChanelLogin'])->name('private_chanel.login');
Route::post('/' . Config::get('app.locale') . '/private-chanel/login', [CustomControllers::class, 'loginInPrivateChanel'])->name('private_chanel.login.send');
Route::post('/' . Config::get('app.locale') . '/private-chanel/form', [CustomControllers::class, 'sendPrivateChanelForm'])->name('private_chanel.form');

#Carlandia
#carga con Cron
Route::get('/loadCarsMotorflash', [CronController::class, 'loadCarsMotorflash']);
Route::get('/dynamicAds', [CronController::class, 'dynamicAds']);
# TPV
Route::get('/carlandia/generatePayment/{payLink}', 'V5\CarlandiaPayController@generatePay')->name('carlandiaGeneratePay');
#recibimos la confirmación de redsys
Route::post('/carlandia/confirmPayment', 'V5\CarlandiaPayController@confirmPayment');

Route::get(RoutingServiceProvider::slugSeo('coches-contraoferta'), 'V5\LotListController@getLotsListAllCategories');
Route::get(RoutingServiceProvider::slugSeo('coches-subasta'), 'V5\LotListController@getLotsListAllCategories');

#aceptar contraofertas por el concesionario
Route::get('aceptacion-contraoferta', 'V5\CarlandiaPayController@aceptarContraoferta')->name('aceptacion-contraoferta');
Route::post('contraoferta-aceptada', 'V5\CarlandiaPayController@contraofertaAceptada');


#SALARETIRO
Route::get('/exportar-a-excel-lotes/{codSub}', [CustomControllers::class, 'exportarLotes']);

#SEGRE
/* Hacer una ruta que llegue a la blade pages.newsletter */
Route::get('/' . Config::get('app.locale') . '/catalog-newsleter', function () {
	return view('front::pages.catalog_newsletter');
})->name('catalogos_newsletter');


Route::get('/auth/zoho', 'externalws\bogota\ZohoController@getTokensWithGrantCode')->name('auth.zoho');
Route::get('/zoho/export', 'externalws\bogota\ZohoController@exportClientsToZoho')->name('zoho.export');


#ALMONEDA
Route::post('/api-ajax/lots-destacados-grid', [ContentController::class, 'getAjaxGridLotesDestacados']);
