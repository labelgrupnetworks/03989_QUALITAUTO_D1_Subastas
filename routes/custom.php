<?php

use App\Http\Controllers\V5\CarlandiaPayController;
use App\Providers\RoutingServiceProvider;

#ALCALA
// Comprar catálogo



Route::get('/{lang?}/comprar-catalogo', 'V5\AutoFormulariosController@ComprarCatalogo');
//Route::post('/catalogoSendmail', 'ComprarCatalogoController@Sendmail');


#SOLER, GUTINVEST,
// DEPARTAMENTOS
Route::get(\Routing::slugSeo('departamentos', true), 'PageController@getDepartment');
Route::get(\Routing::translateSeo('departamento') . '{text}', 'EnterpriseController@department')->name('department');
Route::get(\Routing::translateSeo('video-subastas'), 'CustomControllers@videoAuctions');
Route::get(\Routing::translateSeo('equipo'), 'EnterpriseController@team')->name('enterprise.team');


#TAULER
//Landings
Route::get(\Routing::slugSeo('vender-monedas'), 'ValoracionController@Tasacion');
Route::get(\Routing::slugSeo('tasar-monedas-antiguas'), 'ValoracionController@tasarMonedasAntiguas');
Route::get(\Routing::slugSeo('libros-numismatica'), 'ValoracionController@Books');
Route::get(\Routing::slugSeo('accesorios-numismatica'), 'ValoracionController@Numismatica');
Route::get(\Routing::slugSeo('subasta-numismatica'), 'ValoracionController@SubastaNumismaticaPrimavera');


#VICO
// Estaticas con banner
Route::view(\Routing::translateSeo('tienda-online'), 'front::pages.bannerPage', ['data' => ['name_web_page' => 'foot.direct_sale', 'banner' => 'tienda-banner']]);
Route::view(\Routing::slugSeo('servicios-numismatica'), 'front::pages.bannerPage', ['data' => ['name_web_page' => 'Servicios', 'banner' => 'servicios-banner']]);


#DURAN
// Landing de login
Route::get(\Routing::slug('login-landing'), 'UserController@loginLanding');
Route::post('/api-ajax/external-login', 'UserController@encryptLogin');
Route::post('/ordenTelefonica', 'externalws\duran\PujaTelefonicaController@createTelefonica');
Route::post('/verBotonOrdenTelefonica', 'externalws\duran\PujaTelefonicaController@wbVerBotonTelefono');
Route::get('/cancelreserveWs', 'CronController@CancelReservationWS');
Route::get('/consentimiento', 'externalws\duran\ConsentimientoControler@createConsentimiento');

Route::get(RoutingServiceProvider::translateSeo('landing-subastas', '') . '{keySubSection}', 'V5\LotListController@getCustomListSubSection')->name('landing-subastas');


#DURAN-GALLERY
Route::view(\Routing::slug('dmg'), 'front::pages.dmg');

#SEGRE
//Login
Route::post('/custom_login', 'UserController@customLogin');


#PACKANGERS
//Exportación en Excel de subasta
Route::get('/exportPackengers/{codSub}', 'CustomControllers@exportPackengers');

#ANSORENA
Route::view(\Routing::slugSeo('ventas-destacadas'), 'front::pages.ventas_destacadas');
Route::get('/'. Config::get('app.locale') .'/private-chanel/login', 'CustomControllers@privateChanelLogin')->name('private_chanel.login');
Route::post('/'. Config::get('app.locale') .'/private-chanel/login', 'CustomControllers@loginInPrivateChanel')->name('private_chanel.login.send');
Route::post('/'. Config::get('app.locale') .'/private-chanel/form', 'CustomControllers@sendPrivateChanelForm')->name('private_chanel.form');

#Carlandia
#carga con Cron
Route::get('/loadCarsMotorflash', 'CronController@loadCarsMotorflash');
Route::get('/dynamicAds', 'CronController@dynamicAds');
# TPV
Route::get('/carlandia/generatePayment/{payLink}', 'V5\CarlandiaPayController@generatePay')->name('carlandiaGeneratePay');
#recibimos la confirmación de redsys
Route::post('/carlandia/confirmPayment', 'V5\CarlandiaPayController@confirmPayment');

Route::get(\Routing::slugSeo('coches-contraoferta'), 'V5\LotListController@getLotsListAllCategories');
Route::get(\Routing::slugSeo('coches-subasta'), 'V5\LotListController@getLotsListAllCategories');

#aceptar contraofertas por el concesionario
Route::get('aceptacion-contraoferta', 'V5\CarlandiaPayController@aceptarContraoferta')->name('aceptacion-contraoferta');
Route::post('contraoferta-aceptada', 'V5\CarlandiaPayController@contraofertaAceptada');


#SALARETIRO
Route::get('/exportar-a-excel-lotes/{codSub}', 'CustomControllers@exportarLotes');

#SEGRE
/* Hacer una ruta que llegue a la blade pages.newsletter */
Route::get('/'.\Config::get('app.locale').'/catalog-newsleter', function () {
	return view('front::pages.catalog_newsletter');
})->name('catalogos_newsletter');


Route::get('/auth/zoho', 'externalws\bogota\ZohoController@getTokensWithGrantCode')->name('auth.zoho');
Route::get('/zoho/export', 'externalws\bogota\ZohoController@exportClientsToZoho')->name('zoho.export');


