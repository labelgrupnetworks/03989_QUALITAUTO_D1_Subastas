<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\CustomControllers;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\externalws\bogota\ZohoController;
use App\Http\Controllers\externalws\duran\ConsentimientoControler;
use App\Http\Controllers\externalws\duran\PujaTelefonicaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\V5\AutoFormulariosController;
use App\Http\Controllers\V5\LotListController;
use App\Providers\RoutingServiceProvider as Routing;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

#ALCALA
// Comprar catálogo
Route::get('/{lang?}/comprar-catalogo', [AutoFormulariosController::class, 'ComprarCatalogo']);

#SOLER, GUTINVEST,
// DEPARTAMENTOS
Route::get(Routing::slugSeo('departamentos', true), [PageController::class, 'getDepartment']);
Route::get(Routing::translateSeo('departamento') . '{text}', [EnterpriseController::class, 'department'])->name('department');
Route::get(Routing::translateSeo('video-subastas'), [CustomControllers::class, 'videoAuctions']);
Route::get(Routing::translateSeo('equipo'), [EnterpriseController::class, 'team'])->name('enterprise.team');

//SUBARNA
Route::get(Routing::translateSeo('about-us'), 'EnterpriseController@aboutUsPage')->name('landing-about-us');

#VICO
// Estaticas con banner
Route::view(Routing::translateSeo('tienda-online'), 'front::pages.bannerPage', ['data' => ['name_web_page' => 'foot.direct_sale', 'banner' => 'tienda-banner']]);
Route::view(Routing::slugSeo('servicios-numismatica'), 'front::pages.bannerPage', ['data' => ['name_web_page' => 'Servicios', 'banner' => 'servicios-banner']]);

#DURAN
// Landing de login
Route::get(Routing::slug('login-landing'), [UserController::class, 'loginLanding']);
Route::post('/api-ajax/external-login', [UserController::class, 'encryptLogin']);
Route::post('/ordenTelefonica', [PujaTelefonicaController::class, 'createTelefonica']);
Route::post('/verBotonOrdenTelefonica', [PujaTelefonicaController::class, 'wbVerBotonTelefono']);
Route::get('/cancelreserveWs', [CronController::class, 'CancelReservationWS']);
Route::get('/consentimiento', [ConsentimientoControler::class, 'createConsentimiento']);

Route::get(Routing::translateSeo('landing-subastas', '') . '{keySubSection}', [LotListController::class, 'getCustomListSubSection'])->name('landing-subastas');

#DURAN-GALLERY
Route::view(Routing::slug('dmg'), 'front::pages.dmg');

#SEGRE
//Login
//@todo creo que se puede eliminar, preguntar a Rubén 05/09/2024
Route::post('/custom_login', [UserController::class, 'customLogin']);

Route::get('/exportPackengers/{codSub}', [CustomControllers::class, 'exportPackengers']);
Route::get('/export/{service}/session/{idAucSession}', [CustomControllers::class, 'exportSession']);


#ANSORENA
Route::view(Routing::slugSeo('exposicion_actual'), 'front::pages.landing_galery.exposicion_actual');
Route::view(Routing::translateSeo('ventas-destacadas'), 'front::pages.ventas_destacadas')->name('custom.ventas-destacadas');
Route::get('/' . Config::get('app.locale') . '/private-chanel/login', [CustomControllers::class, 'privateChanelLogin'])->name('private_chanel.login');
Route::post('/' . Config::get('app.locale') . '/private-chanel/login', [CustomControllers::class, 'loginInPrivateChanel'])->name('private_chanel.login.send');
Route::post('/' . Config::get('app.locale') . '/private-chanel/form', [CustomControllers::class, 'sendPrivateChanelForm'])->name('private_chanel.form');

#SALARETIRO
Route::get('/exportar-a-excel-lotes/{codSub}', [CustomControllers::class, 'exportarLotes']);

#Bogota
Route::get('/auth/zoho', [ZohoController::class, 'getTokensWithGrantCode'])->name('auth.zoho');

#ALMONEDA
Route::post('/api-ajax/lots-destacados-grid', [ContentController::class, 'getAjaxGridLotesDestacados']);

#Carlandia
#carga con Cron
// No tenemos a Carlandia
// Route::get('/loadCarsMotorflash', [CronController::class, 'loadCarsMotorflash']);
// Route::get('/dynamicAds', [CronController::class, 'dynamicAds']);

