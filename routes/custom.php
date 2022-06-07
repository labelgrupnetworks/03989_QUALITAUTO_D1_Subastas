<?php

#ALCALA
// Comprar catálogo

Route::get('/{lang?}/comprar-catalogo', 'V5\AutoFormulariosController@ComprarCatalogo');
//Route::post('/catalogoSendmail', 'ComprarCatalogoController@Sendmail');


#SOLER, GUTINVEST,
// DEPARTAMENTOS
Route::get(\Routing::slugSeo('departamentos', true), 'PageController@getDepartment');
Route::get(\Routing::translateSeo('departamento') . '{text}', 'EnterpriseController@department')->name('department');


#TAULER
//Landings
Route::get(\Routing::slugSeo('vender-monedas'), 'ValoracionController@Tasacion');
Route::get(\Routing::slugSeo('tasar-monedas-antiguas'), 'ValoracionController@tasarMonedasAntiguas');
Route::get(\Routing::slugSeo('libros-numismatica'), 'ValoracionController@Books');
Route::get(\Routing::slugSeo('accesorios-numismatica'), 'ValoracionController@Numismatica');
Route::get(\Routing::slugSeo('subasta-numismatica'), 'ValoracionController@SubastaNumismaticaPrimavera');


#VICO
// Estaticas con banner
Route::view(\Routing::slugSeo('tienda-online'), 'front::pages.bannerPage', ['data' => ['name_web_page' => 'Tienda Online', 'banner' => 'tienda-banner']]);
Route::view(\Routing::slugSeo('servicios-numismatica'), 'front::pages.bannerPage', ['data' => ['name_web_page' => 'Servicios', 'banner' => 'servicios-banner']]);


#DURAN
// Landing de login
Route::get(\Routing::slug('login-landing'), 'UserController@loginLanding');
Route::post('/api-ajax/external-login', 'UserController@encryptLogin');
Route::post('/ordenTelefonica', 'externalws\duran\PujaTelefonicaController@createTelefonica');
Route::post('/verBotonOrdenTelefonica', 'externalws\duran\PujaTelefonicaController@wbVerBotonTelefono');
Route::get('/cancelreserveWs', 'CronController@CancelReservationWS');
Route::get('/consentimiento', 'externalws\duran\ConsentimientoControler@createConsentimiento');

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

#Carlandia
#carga con Cron
Route::get('/loadCarsMotorflash', 'CronController@loadCarsMotorflash');
Route::get('/dynamicAds', 'CronController@dynamicAds');
# TPV
Route::get('/carlandia/generatePayment/{payLink}', 'V5\CarlandiaPayController@generatePay')->name('carlandiaGeneratePay');
#recibimos la confirmación de redsys
Route::post('/carlandia/confirmPayment', 'V5\CarlandiaPayController@confirmPayment');
