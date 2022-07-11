<?php

/* Esto iba en el routes de la version 5.2 de laravel */

use App\Http\Controllers\Prueba;

require __DIR__ . '/redirect.php';

Route::get('/{lang}/img/load/{size}/{img}', 'ImageController@return_image_lang');
Route::get('/img/load/{size}/{img}', 'ImageController@return_image');
#load img url amigable
Route::get('/img_load/{size}/{num}/{lin}/{numfoto}/{friendly}', 'ImageController@return_image_friend');

/* fin routes version 5.2 de laravel */



# Nombres de espacios
View::addNamespace('front', [
	resource_path('/views/themes/' . App('config')['app']['theme']),
	resource_path('/views/default'),
]);
View::addNamespace('admin', realpath(base_path('resources/views/admin/' . Config::get('app.admin_theme'))));

//redireccionamos de la raiz al idioma principal
Route::get('', function () {
	return redirect("/" . \App::getLocale(), 301);
});
//Route::get('/{lang?}', 'HomeController@index');
Route::get(\Routing::is_home(), 'HomeController@index')->name('home');
Route::post('/accept_cookies', 'HomeController@accept_cookies');  // Función para aceptar las cookies legales
Route::get('prueba', 'prueba@index')->name('prueba');
Route::get('AnsorenaValidateUnion', 'AnsorenaValidateUnion@validateUnion')->name('AnsorenaValidateUnion');
Route::get('AnsorenaDecisionUnion', 'AnsorenaValidateUnion@decisionUnion');
Route::get('AnsorenaResultUnion', 'AnsorenaValidateUnion@resultUnion');


Route::get('/generate_images', 'prueba@generate_images');
Route::get('/{lang?}/module/labelsubastas/obtenersubasta', 'HomeController@obtenersubasta');

Route::get('send_new_password/{num_mails?}', 'MailController@send_new_password');
//Route::get(\Routing::slug('contact'), 'HomeController@contact');
//Route::get('about', 'HomeController@about');

# Login @ UserController
Route::get(\Routing::slug('login'), 'UserController@login');
Route::get(\Routing::slugSeo('usuario-registrado'), 'UserController@SuccessRegistered');
Route::post(\Routing::slug('login'), 'UserController@login_post');
Route::post('/login_post_ajax', 'UserController@login_post_ajax');
Route::post(\Routing::slug('registro'), 'UserController@registro');
Route::get(\Routing::slug('logout'), 'UserController@logout');
Route::get(\Routing::slug('password_recovery'), 'UserController@passwordRecovery');
Route::post('/{lang}/send_password_recovery', 'UserController@sendPasswordRecovery');
Route::post('/{lang}/ajax-send-password-recovery', 'UserController@sendPasswordRecovery');

//registro en subalia
Route::get(\Routing::slug('login') . "/subalia", 'User\SubaliaController@index');
Route::post(\Routing::slug('login') . "/subalia/register", 'User\SubaliaController@buscarCliente');

//validacion o registro de usuarios que provienen de subalia
Route::post(\Routing::slug('login') . "/subalia", 'User\SubaliaController@validarSubaliaIndex');
Route::post(\Routing::slug('login') . "/subalia/valida", 'User\SubaliaController@validarSubalia');
Route::post('/{lang?}/register_subalia', 'User\RegisterController@registerComplete');

# Activar cuenta (Tauler)
Route::get(\Routing::slug('activate_account'), 'UserController@activateAcount');

Route::get('/{lang}/seeShippingAddress', 'AddressController@seeShippingAddress');
Route::post('/change_address_shipping', 'AddressController@updateShippingAddress');
Route::post('/delete_address_shipping', 'AddressController@deleteShippingAddress');
Route::post('/api-ajax/add_favorite_address_shipping', 'AddressController@FavoriteShippingAddress');

Route::post('/api-ajax/wallet/update', 'UserController@updateWallet');
Route::post('/api-ajax/wallet/create', 'UserController@createWallet');
Route::get('/api-ajax/wallet/back', 'UserController@backVottumWallet')->name('wallet.back');


//Route::post('/send_password_recovery', 'UserController@sendPasswordRecovery');

Route::get('/{lang?}/email-recovery', 'UserController@getPasswordRecovery');
Route::get('/{lang?}/email-validation', 'UserController@getEmailValidation');
//lo comento por que no existe favorites en el controlador Usercontroller 2018_01_2018
// Route::get('/{lang?}/favorites', 'UserController@Favorites');
# Logout & Login de Tiempo Real
Route::get(\Routing::slug('login') . '/tr', 'UserController@login');
Route::post(\Routing::slug('login') . '/tr', 'UserController@login_post');
Route::get(\Routing::slug('logout') . '/tr', 'UserController@logout'); // logout de tiempo real

# Subastas @ SubastaController
# 2017/10/25 no se esta usando
#Route::get(\Routing::slug('subasta').'-{cod}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+'));
Route::get(\Routing::slugSeo('indice-subasta') . '/{cod}-{texto}', 'SubastaController@indice_subasta')->where(array('cod' => '[0-9a-zA-Z]+'));


Route::post('/subasta/reproducciones', 'SubastaController@reproducciones');
Route::post('/subasta/megusta', 'SubastaController@megusta');
Route::post('/subasta/modal_images', 'SubastaController@modalGridImages');
Route::post('/subasta/modal_images_fullscreen', 'SubastaController@modalImagesFullScreen')->name('modal.images.fullscreen');

// 2 URLS la segunda es adicional en caso de querer pasar el texto del titulo de la subasta por la url
#2017/10/26 creo que no se usan para nada
/*
            Route::get(\Routing::slug('subasta').'-{cod}'.\Routing::slug('category', true).'/{cat}-{texto}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+'));
            Route::get(\Routing::slug('subasta').'-{cod}-{texto_adicional}'.\Routing::slug('category', true).'/{cat}-{texto}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+'));
            */

#lotes

Route::get(\Routing::slugSeo('lote') . '/{cod}-{texto2}/{ref}-{texto}', 'SubastaController@lote')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));
//2017-11-08  no parece que se use
//Route::get(\Routing::slugSeo('lote').'/{cod}/{ref}-{texto}', 'SubastaController@lote')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));
#listado de lotes categorias y tematicos
Route::get(\Routing::slugSeo('subastas') . '/{key}/page-{page?}', 'SubastaController@customizeLotListCategory');
Route::get(\Routing::slugSeo('subastas') . '/{key}/{subcategory?}', 'SubastaController@customizeLotListCategory');
Route::get(\Routing::slugSeo('subastas') . '/{key}/{subcategory?}/page-{page}', 'SubastaController@customizeLotListCategory');

Route::get(\Routing::slugSeo('tematicas') . '/{key}', 'SubastaController@customizeLotListTheme');
Route::get(\Routing::slugSeo('tematicas') . '/{key}/page-{page?}', 'SubastaController@customizeLotListTheme');

Route::get(\Routing::translateSeo('subasta-actual'), 'SubastaController@subasta_actual')->name('subasta.actual');
Route::get(\Routing::translateSeo('subasta-actual-online'), 'SubastaController@subasta_actual_online')->name('subasta.actual-online');
Route::get(\Routing::translateSeo('presenciales'), 'SubastaController@subastas_presenciales')->name('subastas.presenciales');
Route::get(\Routing::translateSeo('subastas-historicas'), 'SubastaController@subastas_historicas')->name('subastas.historicas');
Route::get(\Routing::translateSeo('subastas-historicas-presenciales'), 'SubastaController@subastas_historicas_presenciales')->name('subastas.historicas_presenciales');
Route::get(\Routing::translateSeo('subastas-historicas-online'), 'SubastaController@subastas_historicas_online')->name('subastas.historicas_online');
Route::get(\Routing::translateSeo('subastas-online'), 'SubastaController@subastas_online')->name('subastas.online');;
Route::get(\Routing::translateSeo('subastas-permanentes'), 'SubastaController@subastas_permanentes')->name('subastas.permanentes');
Route::get(\Routing::translateSeo('venta-directa'), 'SubastaController@venta_directa')->name('subastas.venta_directa');
Route::get(\Routing::translateSeo('todas-subastas'), 'SubastaController@listaSubastasSesiones');
Route::get(\Routing::translateSeo('subastas-activas'), 'SubastaController@subastas_activas')->name('subastas.activas');

Route::get(\Routing::translateSeo('haz-oferta'), 'SubastaController@haz_oferta')->name('subastas.haz_oferta');
Route::get(\Routing::translateSeo('subasta-inversa'), 'SubastaController@subasta_inversa')->name('subastas.subasta_inversa');


Route::get(\Routing::slug('sub') . '/{status?}/{type?}', 'SubastaController@listaSubastasSesiones')->where(array('status' => '[A-Z]?', 'type' => '[A-Z]?'));
Route::get(\Routing::slugSeo('subastas-tematicas'), 'SubastaController@themeAuctionList');

Route::post('/consult-lot/email', 'MailController@emailConsultLot');
Route::get('/{lang?}/accept_news', 'MailController@acceptNews');
Route::post('/api-ajax/info-lot-email', 'MailController@sendInfoLot');
Route::post('/api-ajax/ask-info-lot', 'MailController@askInfoLot');

#2017/10/25 no se está usando
# Subastas venta directa @ SubastaController
/*
    if (!empty(intval(Config::get('app.enable_direct_sale_auctions')))) {
        Route::get(\Routing::slug('subasta/vt').'-{cod}-{texto}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+'));
        Route::get(\Routing::slug('subasta/vt').'-{cod}-{texto}/{page}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));
    }
        */
Route::get(\Routing::translateSeo('bid-admin'), 'SubastaController@bidAdmin');
Route::post('/api-ajax/save_order', 'SubastaController@SaveOrders');
Route::post('/api-ajax/delete_order', 'SubastaController@DeleteOrders');

Route::post('/api-ajax/exist-email', 'UserController@existEmail');
Route::post('/api-ajax/exist-nif', 'UserController@existNif');
Route::post('/api-ajax/cod-zip', 'UserController@CodZip');


Route::post('api-ajax/email_sobrepuja', 'SubastaController@emailSobrepuja');
/*reabrir lote */
Route::post('/api-ajax/open_lot', 'SubastaTiempoRealController@openLot');

# Lotes API Service
# Subastas en Tiempo Real
if (!empty(intval(Config::get('app.enable_tr_auctions')))) {
	Route::get(\Routing::translateSeo('api/subasta') . '{cod}-{texto}', 'SubastaTiempoRealController@index')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+'));
	Route::get(\Routing::translateSeo('api/subasta') . '{cod}-{texto}/{proyector}', 'SubastaTiempoRealController@index')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+'));
}
Route::get('sendemailsobrepuja/{cod}/{licit}/{ref}/{orden_o_puja}', 'SubastaTiempoRealController@sendEmailSobrepuja');
Route::post('api/action/subasta-{cod}', 'SubastaTiempoRealController@action')->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post(\Routing::slug('api') . '/comprar/subasta-{cod}', 'SubastaTiempoRealController@comprar')->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post(\Routing::slug('api') . '/ol/subasta-{cod}', 'SubastaTiempoRealController@ordenLicitacion')->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post(\Routing::slug('api') . '/contraofertar/subasta-{cod}', 'SubastaTiempoRealController@contraOfertar')->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post(\Routing::slug('api') . '/check-contraofertar/subasta-{cod}', 'SubastaTiempoRealController@preContraOfertar')->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post(\Routing::slug('api') . '/comprar-aux/subasta-{cod}', 'SubastaTiempoRealController@comprarAux')->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post('api-ajax/makeOffer', 'SubastaTiempoRealController@makeOffer');

////NGAMEZ ejemplos de abajo
//Route::get(\Routing::slug('subasta/hc').'-{cod}-{texto}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+'));
//Route::get(\Routing::slug('subasta/hc').'-{cod}-{texto}/{page}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));
////NGAMEZ original
//Route::get('api-ajax'. \Routing::slug('subasta').'-{cod}/p-{page}', 'SubastaController@subastaAjax')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+'));
//Route::get('api-ajax'. \Routing::slug('subasta').'-{cod}/{ref}/{search?}', 'SubastaController@lote')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));
Route::get('api-ajax' . \Routing::slug('subasta') . '-{cod}/p-{page}', 'SubastaController@subastaAjax')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+'));
Route::get('api-ajax' . \Routing::slug('subasta') . '-{cod}-{texto2}/{ref}/{search?}', 'SubastaController@lote')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));

Route::get('api-ajax/get_lote/{lang}/{cod}/{id_auc_sessions}/{ref}/{order}/{search?}', 'SubastaController@getNextPreviousLotAjax')->where(array('cod' => '[0-9a-zA-Z]+'));

Route::get('api-ajax/calculate_bids/{actual_bid}/{new_bid}', 'SubastaTiempoRealController@calculateAvailableBids')->where(array('actual_bid' => '[0-9]+', 'new_bid' => '[0-9]+',));
Route::get('api-ajax/favorites/{action}', 'SubastaController@favorites')->where(array('action' => '[a-zA-Z]+'));
Route::post('api-ajax/set_licit_lot', 'SubastaTiempoRealController@setLicitLot');
Route::post('api-ajax/activate_next', 'SubastaTiempoRealController@ActiveNext');
Route::post('api-ajax/jump_lots', 'SubastaTiempoRealController@jumpLots');
Route::post('api-ajax/baja_cli', 'SubastaTiempoRealController@bajaCli');
Route::post('api-ajax/get_baja_cli_sub', 'SubastaTiempoRealController@getBajaCliSub');
Route::post('api-ajax/get_clients_credit', 'SubastaTiempoRealController@getClientsCreditBySub');

Route::post('api-ajax/add_lower_bid', 'SubastaTiempoRealController@addLowerBid');

//pedir el precio del envio
Route::post('/api-ajax/get_shipment_rate', 'DeliveryController@getShipmentRate');
Route::post('/api-ajax/get_shipment_delivery', 'DeliveryController@getShipmentDelivery');



Route::post('api/status/subasta', 'SubastaTiempoRealController@setStatus');
Route::post('api/pause_lot', 'SubastaTiempoRealController@pausarLote');
Route::post('api/cancel_bid', 'SubastaTiempoRealController@cancelarPuja');
Route::post('api/cancel_order', 'SubastaTiempoRealController@cancelarOrden');
Route::post('api/cancelar_orden_user', 'SubastaTiempoRealController@cancelarOrdenUser');


//Route::post('api/resume_lot', 'SubastaTiempoRealController@pausarLote');

Route::get(\Routing::slug('chat') . '-{cod}-{lang}', 'ChatController@getChat')->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post('api/chat', 'ChatController@setChatArray');
Route::post('api/chat/delete', 'ChatController@deleteChat');


Route::post('api/end_lot' . '-{cod}', 'SubastaTiempoRealController@endLot');

//Route::get(\Routing::slug('subastas'), 'SubastaController@listaSubastasSesiones');
//solo una letra en mayusculas

Route::get(\Routing::slug('subastas-tiempo-real'), 'SubastaController@listaSubastasSesionesTR');

# Lotes de subastas en la home con paginación via ajax
# 2017_11_03 No se esta usando
//Route::get(\Routing::slug('subastashome'), 'HomeController@index');
//Route::get(\Routing::slug('subastashome').'/{page}/{is_home?}', 'HomeController@index');


Route::get(\Routing::slug('pujas') . '/{cod}/{lote}', 'SubastaController@getPujas'); //->where(array('cod' => '[0-9a-zA-Z]+', 'lote' => '[0-9]+'));
Route::get(\Routing::slug('ordenes') . '/{cod}/{lote}', 'SubastaController@getOrdenes'); //->where(array('cod' => '[0-9a-zA-Z]+', 'lote' => '[0-9]+'));

# Lote único
//Route::get(\Routing::slug('lote').'/{lote}', 'SubastaController@lote');

# Usuario @ UserController
#Deprecated revisar
/*Route::get(\Routing::slug('user/subastas'), 'UserController@getPujas');
    Route::get(\Routing::slug('user/subastas').'/{cod}', 'UserController@getPujas');
    Route::get(\Routing::slug('user/subastas').'/{cod}/{lote}', 'UserController@getPujas');
    Route::get(\Routing::slug('user/ordenes'), 'UserController@getOrdenes');
    Route::get(\Routing::slug('user/ordenes').'/{cod}', 'UserController@getOrdenes');
    Route::get(\Routing::slug('user/ordenes').'/{cod}/{lote}', 'UserController@getOrdenes');
    Route::get(\Routing::slug('user/adjudicaciones'), 'UserController@getAdjudicaciones');
    Route::get('user/licit', 'UserController@getLicitCodes');*/

# SetLang
//Route::get('lang', 'SetLangController@index');

# Histórico Grid
#Deprecates
/*if (!empty(intval(Config::get('app.enable_historic_auctions')))) {
        Route::get(\Routing::slug('historic'), 'SubastaController@displayHistorico');
        Route::get(\Routing::slug('historic').'/page/{page}', 'SubastaController@displayHistorico');

        Route::get(\Routing::slug('subasta/hc').'-{cod}-{texto}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+'));
        Route::get(\Routing::slug('subasta/hc').'-{cod}-{texto}/{page}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));
    }*/

# Newsletter
Route::post('api-ajax/newsletter/{opcion}', 'NewsletterController@setNewsletter');
Route::post('/api-ajax/carousel', 'ContentController@getAjaxCarousel');
Route::post('/api-ajax/newcarousel', 'ContentController@getAjaxNewCarousel');
Route::post('/api-ajax/add-sec-user', 'UserController@changeFavTsec');

Route::post('/api-ajax/accept-cond-user', 'UserController@AcceptConditionsUser');


# Búsqueda
Route::get(\Routing::slugSeo('busqueda') . '/redirect', 'BusquedaController@redirect');

Route::get(\Routing::slugSeo('busqueda') . '/{texto?}', 'BusquedaController@index');
Route::get(\Routing::slugSeo('busqueda'), 'BusquedaController@index');
Route::get(\Routing::slugSeo('busqueda') . '/{texto}/{page}', 'BusquedaController@index');

# Mail Composer via POST
Route::post('api-ajax/mail', 'MailController@mailToAdmin');
Route::post('api-ajax/mail-peticion-catalogo', 'MailController@mailToAdminPeticionCatalogo');



Route::get(\Routing::slug('thanks'), function () {
	return View::make('front::generic.thanks');
});

# Content Controller
# CMS / Gestor de contenido
#Route::get(\Routing::slug('pagina').'/{pagina}', 'ContentController@getPagina');
#  Route::get(\Routing::slugSeo('pagina',true).'/{pagina}', 'ContentController@getPagina');
Route::get(\Routing::slugSeo('pagina', true) . '/{pagina}', 'PageController@getPagina');
Route::get('/article/{id}', 'PageController@getArticle');
Route::get(\Routing::translateSeo('mapa-web'), 'PageController@siteMapPage');

//Soler esta utilizando un sistema de preguntas frequentes con csv.
if (\Config::get("app.faqs_old", 0)) {
	Route::get(\Routing::slugSeo('preguntas-frecuentes', true), 'ContentController@faqs')->name('faqs_page');
} else {
	Route::get(\Routing::translateSeo('preguntas-frecuentes'), 'V5\FaqController@index')->name('faqs_page');
}

require __DIR__ . '/user_panel.php';

/* nuevas version V3 */
Route::get(\Routing::slugSeo('info-subasta', true) . '/{cod}-{texto}', 'SubastaController@auction_info')->name('urlAuctionInfo')->where(array('cod' => '[0-9a-zA-Z]+'));

Route::get('/lot/getfechafin', 'SubastaController@getFechaFin');
// SE ha modificado el routes
//Route::get(\Routing::slug('subastas').'/{status?}/{type?}', 'SubastaController@listaSubastasSesiones')->where(array('status' => '[A-Z]?', 'type' => '[A-Z]?'));

// routes del shipment
Route::get('/delivery/getshipmentsrates', 'DeliveryController@getShipmentsRates');
Route::get('/delivery/newshipment', 'DeliveryController@newShipment');

//TPV
Route::post('/gateway/{function}', 'PaymentsController@index');
Route::get('/gateway/returnPayPage', 'PaymentsController@returnPayPage');
Route::get('/sermepa/peticion.php', 'PaymentsController@pagoDirecto');
Route::get('/gateway/pasarela-pago', 'PaymentsController@pagoDirecto');

Route::get('/gateway/paypal-approve', 'PaymentsController@pagoDirectoReturnPaypal')->name('paypal_approve');


Route::get('/gateway/sendPayment', 'PaymentsController@sendPayment');

Route::post('/api-ajax/gastos_envio', 'PaymentsController@gastosEnvio');

//TPV carrito de la compra
Route::post('/shoppingCart/pay', 'V5\PayShoppingCartController@createPayment');
Route::get('/shoppingCart/callRedsys', 'V5\PayShoppingCartController@callRedsys');







// Valoraciones
Route::post(\Routing::slug('valoracion-articulos'), 'ValoracionController@ValoracionArticulos');
Route::get(\Routing::slug('valoracion-articulos-success'), 'ValoracionController@ValoracionSuccess');
Route::get(\Routing::slugSeo('especialistas'), 'EnterpriseController@index');
Route::post('/{lang}/valoracion-articulos-adv', 'ValoracionController@ValoracionArticulosAdv');
Route::post('/valoracion/upload', 'ValoracionController@uploadFile');
Route::get('/{lang}/valoracion-{key}', 'ValoracionController@GetValoracionGratuita');
Route::get('/{lang}/valuation-{key}', 'ValoracionController@GetValoracionGratuita');


Route::get('/cron_load_cars_motorflash', 'CronController@loadCarsMotorflash');

Route::get('/web_cron_closelotws', 'CronController@CloseLotsWebServiceCall');
Route::get('/web_cron_xmlUrl', 'CronController@xmlURL');
Route::get('/web_cron_newxmlUrl', 'CronController@newXmlUrl');
Route::get('/emailsadjudicaciones', 'CronController@EmailsAdjudicaciones');
Route::get('/send_resalelot', 'CronController@emailsReSaleLots');
Route::get('/send_lastcall', 'CronController@lastCall');
Route::get('/send_first_auction', 'CronController@EmailFirstAuction');
Route::get('/emailsadjudicaciones_generic', 'CronController@EmailsAdjudicacionesGeneric');
Route::get('/web_cron_closeauction', 'CronController@EmailCloseAuction');
Route::get('/web_cron_email_report', 'CronController@cronEmailReports');

Route::get('/lote_pending_pay', 'CronController@LotePendingPay');
Route::get('/lote_pending_collect', 'CronController@LotePendingCollect');

Route::get('/not-bidded-yet', 'CronController@emailNotBiddedYet');
Route::get('/email_cedente_amedida', 'CronController@emailCedeneteAMedida');
Route::get('/generateProductFeed', 'CronController@generateProductFeed');
Route::get('/email-cedente-amedida-error', 'CronController@emailCedenteAmedidaError');

Route::get('/update-divisa', 'CronController@update_divisa');


Route::get('/email_cancel_puja/{cod_sub}/{ref}/{cod_licit}', 'MailController@emailCancelBid');



Route::get('/generate_miniatures', 'ImageController@generateMiniatures');
Route::get('/regenerate_img', 'ImageController@regenerate_images_table');

Route::get('/clear-cache', function () {
	Artisan::call('cache:clear');
});
Route::get('/{lang}/rechargefilters', 'SubastaController@rechargefilters');

Route::get('/{lang}/reload_lot', 'SubastaController@reloadLot');

Route::get('/email_fact_generated', 'MailController@emailFacturaGenerated');
Route::get('/disbandment_lot', 'MailController@disbandment_lot');

/* Blog */
Route::get(\Routing::slugSeo('blog', true) . '/{key_categ?}', 'NoticiasController@index');
Route::get(\Routing::slugSeo('blog', true) . '/{key_categ}/{key_news}', 'NoticiasController@news');
Route::get(\Routing::slugSeo('mosaic-blog', true), 'NoticiasController@mosaicBlog');

Route::get(\Routing::slugSeo('mosaic-blog', true), 'NoticiasController@museumPieces');
Route::get(\Routing::slugSeo('events', true), 'NoticiasController@events');
Route::get(\Routing::slugSeo('events', true) . '/{id}', 'NoticiasController@event');

Route::get(\Routing::slugSeo('calendar'), 'SubastaController@calendarController');


Route::post('api-ajax/updateDivisa', 'UserController@savedDivisas');


Route::get('/{lang}/cookies', 'CookiesController@getConfigCookies')->name('cookieConfig');
Route::post('/{lang}/cookies', 'CookiesController@setConfigCookies')->name('cookieConfig');
Route::post('/accept-all-cookies', 'CookiesController@acceptAllCookies');


/* Invaluable */

Route::get('/houses/token', 'InvaluableController@token');

Route::get('/houses/{houseUserName}/groups', 'InvaluableController@groupSettings');

Route::get('/houses/{houseUserName}/contacts', 'InvaluableController@listContacts');

Route::get('/houses/{houseUserName}/address', 'InvaluableController@addresses');

Route::get('/houses/{houseUserName}/channels', 'InvaluableController@channels');

Route::get('/houses/{houseUserName}/groups/{codSubasta}/session/{sessionID}/catalogs', 'InvaluableController@catalogos');

Route::get('/houses/{houseUserName}/lots', 'InvaluableController@lots');

Route::get('/houses/{houseUserName}/groups/{codSubasta}/session/{sessionID}/lots/{lotNumber}', 'InvaluableController@deleteLot');

Route::get('/houses/{houseUserName}/groups/{codSubasta}/session/{sessionID}/catalogs/lots/{lotNumber}', 'InvaluableController@updateLot');

Route::get('/landing', function () {
	return View::make('front::landings.landing');
});


/* Tabs tiempo real */

Route::get('/{lang}/historicTab/{cod_sub}/{session}', 'SubastaTiempoRealController@historicTab');
Route::get('/{lang}/favoritesTab/{cod_sub}/{licit}', 'SubastaTiempoRealController@favoritesTab');
Route::get('/{lang}/adjudicadosTab/{cod_sub}/{session}/{licit}', 'SubastaTiempoRealController@adjudicadosTab');

Route::get('/credit/{cod_sub}-{name}-{id_auc_sessions}', 'SubastaTiempoRealController@creditPanel')->name('creditPanel');
Route::post('/credit', 'SubastaTiempoRealController@increaseCredit')->name('increaseCredit');

/* Carrousel Tr */
Route::get('api-ajax/award_price/{cod_sub}/{ref_asigl0}', 'SubastaTiempoRealController@getAwardPrice');

Route::post('api-ajax/formulario-pujar', 'SubastaController@getFormularioPujar');
Route::post('api-ajax/enviar-formulario-pujar', 'SubastaController@sendFormularioPujar');
















//
//   Versión 5
//

//Contacto
#Route::get(\Routing::slugSeo('contacto',true), 'V5\ContactController@index');

Route::get(\Routing::translateSeo('contacto'), 'V5\ContactController@index')->name('contact_page');

Route::get(\Routing::slugSeo('administradores-concursales', true), 'V5\ContactController@admin');
Route::post('contactSendmail', 'V5\ContactController@contactSendmail');

//Route::get(\Routing::slugSeo('register',true), 'V5\UserAccessController@register');
Route::get(\Routing::slugSeo('register', true), 'User\RegisterController@index')->name('register');

// Autoformularios

Route::post(\Routing::slug('autoformulario-send'), 'V5\AutoFormulariosController@Send')->name('autoformulario-send');
Route::get(\Routing::slug('autoformulario-success'), 'V5\AutoFormulariosController@Success');

Route::get(\Routing::slug('tasaciones'), 'V5\AutoFormulariosController@Tasaciones');
Route::get(\Routing::slug('workwithus') . "/{key}", 'V5\AutoFormulariosController@workWidthUs');




// Services page
Route::get(\Routing::slugSeo('servicios', true), 'ServicesController@index');
Route::get('/{lang}/servicios/encapsulacion', 'ServicesController@encapsulacion');
Route::get('/{lang}/servicios/fotografias', function () {
	return View::make('pages.servicios.fotografias');
});
Route::post('/{lang}/servicios/valoracion-fotografia', 'ServicesController@valoracionFotografia');
Route::post('/{lang}/servicios/valoracion-encapsulacion', 'ServicesController@valoracionEncapsulacion');

Route::get('/{lang}/numismatica-madrid', 'Landing\LandingController@landing');

/**Api Emails */
Route::post('/{lang}/api/send-mail', 'apirest\MailApiRestController@sendMail');

Route::post('/{lang}/api/email-user-activation', 'apirest\MailApiRestController@emailUserActivation');
Route::post('/{lang}/api/email-access-visibility', 'apirest\MailApiRestController@emailAccessToVisibility');
Route::post('/{lang}/api/email-access-bids', 'apirest\MailApiRestController@emailAccessToBids');
Route::post('/{lang}/api/email-provisional-lot-award', 'apirest\MailApiRestController@emailProvisionalLotAward');
Route::post('/{lang}/api/email-complet-lot-report', 'apirest\MailApiRestController@emailCompletLotReport');

Route::post('/{lang}/api/email-when-change-file', 'apirest\MailApiRestController@sendToUsersWithDepositWhenChangeFiles');
/***************************************************************************************************************************************
    /***************************************************************************************************************************************
    /* 		SUBASTA    -    @Nuevo grid
    /***************************************************************************************************************************************
	/***************************************************************************************************************************************/

#listado de lotes por subasta
if (!empty(\Config::get("app.gridLots")) && \Config::get("app.gridLots") == "new") {
	#nuevo
	Route::get(\Routing::slugSeo('subasta') . '/{texto?}_{cod}-{session}', 'V5\LotListController@getLotsList')->name('urlAuction')->where(array('cod' => '[0-9a-zA-Z]+', 'session' => '[0-9]+'));
	#version antigua
	Route::get(\Routing::slugSeo('subastaOld') . '/{cod}-{texto}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+'));
} else {
	#ver version nueva con URL test
	Route::get(\Routing::slugSeo('subastaTest') . '/{texto}_{cod}-{session}', 'V5\LotListController@getLotsList')->name('urlAuction')->where(array('cod' => '[0-9a-zA-Z]+', 'session' => '[0-9]+'));

	#antiguo
	Route::get(\Routing::slugSeo('subasta') . '/{cod}-{texto}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+'));
	Route::get(\Routing::slugSeo('subasta') . '/{cod}-{texto}/page-{page}', 'SubastaController@index')->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));
}


#listado de lotes para todas las categorias
Route::get(\Routing::slugSeo('subastas'), 'V5\LotListController@getLotsListAllCategories')->name('allCategories');

#listado de lotes por categoria, añadiendo cualquier texto amigable, servirá para poder poner caracteristicas de manera amigable, pasandole luego la variable de la caracteristica, por ejemplo subastas_audi/texto-friendly?features[4]=117
Route::get(\Routing::slugSeo('subastas') . "_{keycategory}/{texto}", 'V5\LotListController@getLotsListCategory')->name('categoryTexFriendly');

#listado de lotes por categoria
Route::get(\Routing::slugSeo('subastas') . "-{category}", 'V5\LotListController@getLotsListCategory')->name('category');
#listado de lotes por secciones
Route::get(\Routing::translateSeo('subastas', "-{keycategory}/{keysection}"), 'V5\LotListController@getLotsListSection')->name('section');
#listado de lotes por subcsecciones
Route::get(\Routing::translateSeo('subastas', "-{keycategory}/{keysection}/{keysubsection}"), 'V5\LotListController@getLotsListSubSection')->name('subsection');
#buscador
#Route::get(\Routing::translateSeo('b', '/{description}'),'V5\LotListController@getLotsListSearch')->name('search');


#carga lotes por ajax
Route::post('/{lang}/GetAjaxLots', 'V5\LotListController@GetAjaxLots')->name('getAjaxLots');

#consultar si hay lotes históricos
Route::post('/{lang}/showHistoricLink', 'V5\LotListController@showHistoricLink');
#lote como está en subalia
//Route::get(\Routing::translateSeo('lote-{texto}_{idorigenhces1}_{codsub}'), 'subalia\LotControllerSubalia@showLotAuction')->name('urlLotAuction')->where(array('idorigenhces1' => '[0-9a-zA-Z\-]+','codsub' => '[0-9a-zA-Z]+'));
//Route::get(\Routing::translateSeo('lote-{texto}_{idorigenhces1}'), 'subalia\LotControllerSubalia@showLotCategory')->name('urlLot')->where(array('idorigenhces1' => '[0-9a-zA-Z\-]+'));

#Tienda de venta directa
Route::post('/addLotCart', 'V5\CartController@addLot')->name('addLotCart');
Route::post('/deleteLotCart', 'V5\CartController@deleteLot')->name('deleteLotCart');
Route::post('/shippingCostsCart', 'V5\CartController@shippingCostsCart');


Route::get(\Routing::translateSeo('artistas'), 'V5\ArtistController@index')->name("artists");
Route::get(\Routing::translateSeo('artista', '/{name}_{idArtist}'), 'V5\ArtistController@artist')->name("artist")->where(array('idArtist' => '[0-9]+'));

/***** Articulos ******/
Route::get(\Routing::translateSeo('articulos'), 'V5\ArticleController@index')->name("articles");
Route::get(\Routing::translateSeo('articulos', '_{family}'), 'V5\ArticleController@index')->name("articles_family");
Route::get(\Routing::translateSeo('articulos', '-{category}'), 'V5\ArticleController@index')->name("articles-category");
Route::get(\Routing::translateSeo('articulos', '-{category}/{subcategory}'), 'V5\ArticleController@index')->name("articles-subcategory");
Route::get(\Routing::translateSeo('articulo', '/{idArticle}-{friendly}'), 'V5\ArticleController@article')->name("article");
Route::post(\Routing::translateSeo('addArticleCart'), 'V5\ArticleController@addArticle')->name('addArticleCart');
Route::post(\Routing::translateSeo('changeUnitsArticleCart', '/{idArticle}/{units}'), 'V5\ArticleController@changeUnitsArticleCart')->name('changeUnitsArticleCart');

Route::post('/deleteArticleCart', 'V5\ArticleController@deleteArticle')->name('deleteArticleCart');
Route::post(\Routing::translateSeo('getTallasColoresFicha'), 'V5\ArticleController@getTallasColoresFicha');

Route::post('/articleCart/pay', 'V5\PayArticleCartController@createPayment');
Route::get('/articleCart/callRedsys', 'V5\PayArticleCartController@callRedsys');
#página de confirmación de compra
Route::post('/articleCart/returnpayup2', 'V5\PayArticleCartController@ReturnPayUP2');


Route::group(['prefix' => 'api'], function () {

	Route::post('{lang}/getArticles', 'V5\ArticleController@getArticles')->name("getArticles");
	//Route::post(\Routing::translateSeo('getArticles'), 'V5\ArticleController@getArticles')->name("getArticles");

	Route::post(\Routing::translateSeo('getOrtsec'), 'V5\ArticleController@getOrtSec');
	Route::post(\Routing::translateSeo('getSec'), 'V5\ArticleController@getSec');
	Route::post(\Routing::translateSeo('getTallasColores'), 'V5\ArticleController@getTallasColores');
	Route::post(\Routing::translateSeo('getMarcas'), 'V5\ArticleController@getMarcas');
	Route::post(\Routing::translateSeo('getFamilias'), 'V5\ArticleController@getFamilias');
});
/***** Fin Articulos ******/

/***** Galería de arte *****/

Route::get(\Routing::translateSeo('exposicion') . '{texto}_{cod}-{reference}', 'V5\GaleriaArte@getGalery')->name('exposicion')->where(array('cod' => '[0-9a-zA-Z]+', 'reference' => '[0-9]+'));
Route::get(\Routing::translateSeo('exposiciones'), 'V5\GaleriaArte@exhibitons')->name('exposiciones');
#la comento por que de momento no se usará
# Route::get(\Routing::translateSeo('exposiciones-anteriores'), 'V5\GaleriaArte@previousExhibitons')->name('exposiciones-anteriores');
Route::get(\Routing::translateSeo('artistas-galeria'), 'V5\GaleriaArte@artists')->name('artistasGaleria');
Route::get(\Routing::translateSeo('artista-galeria') . '{id_artist}', 'V5\GaleriaArte@artist')->name('artistaGaleria')->where(array('id_artist' => '[0-9]+'));
Route::get(\Routing::translateSeo('fondo-galeria'), 'V5\GaleriaArte@fondoGaleria')->name('fondoGaleria');
Route::get(\Routing::translateSeo('artista-fondo-galeria') . '{id_artist}', 'V5\GaleriaArte@artistFondoGaleria')->name('artistaFondoGaleria')->where(array('id_artist' => '[0-9]+'));

/***** Fin Galería de arte *****/

Route::get('/{lang}/lot-file/{file}/{numhces}/{linhces}/download', 'SubastaController@getDownloadLotFile')->name('lot_file_download');

/* Esto iba en el routes de la version 5.2 de laravel despues de incluir el routes/web */
require __DIR__ . '/custom.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/api_rest.php';
require __DIR__ . '/api_label.php';
require __DIR__ . '/web_service.php';


//Si no ha habido ningun resultado mostramos un 404
Route::get('{any}', function () {
	exit(\View::make('front::errors.404'));
})->where('any', '.*');


Route::post('{any}', function () {
	exit(\View::make('front::errors.404'));
})->where('any', '.*');

/* FIN 5.2 de laravel despues de incluir el routes/web */
