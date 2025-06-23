<?php

use App\Http\Controllers\AnsorenaValidateUnion;
use App\Http\Controllers\apirest\MailApiRestController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CookiesController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\CustomControllers;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\externalAggregator\Invaluable\House;
use App\Http\Controllers\externalws\vottun\VottunController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InvaluableController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\prueba;
use App\Http\Controllers\SubastaController;
use App\Http\Controllers\SubastaTiempoRealController;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\User\SubaliaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\V5\AppPushController;
use App\Http\Controllers\V5\ArticleController;
use App\Http\Controllers\V5\ArtistController;
use App\Http\Controllers\V5\AutoFormulariosController;
use App\Http\Controllers\V5\CartController;
use App\Http\Controllers\V5\ContactController;
use App\Http\Controllers\V5\DepositController;
use App\Http\Controllers\V5\FaqController;
use App\Http\Controllers\V5\GaleriaArte;
use App\Http\Controllers\V5\LotListController;
use App\Http\Controllers\V5\NodePhp;
use App\Http\Controllers\V5\PayArticleCartController;
use App\Http\Controllers\V5\PayShoppingCartController;
use App\Http\Controllers\ValoracionController;
use App\Http\Integrations\Tecalis\TecalisService;
use App\Providers\RoutingServiceProvider as Routing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Laravel\Socialite\Facades\Socialite;

require __DIR__ . '/redirect.php';
//require __DIR__ . '/test.php'; //Para mostrar momento de las consultas en el navegador

$locales = implode('|', array_keys(Config::get('app.locales', [])));

Route::get('/design', function () {
	return view('pages.design');
});

Route::get('/{lang}/img/load/{size}/{img}', [ImageController::class, 'return_image_lang']);
Route::get('/img/load/{size}/{img}', [ImageController::class, 'return_image']);
#load img url amigable
Route::get('/img_load/{size}/{num}/{lin}/{numfoto}/{friendly}', [ImageController::class, 'return_image_friend']);
Route::get('/img/converter/{imagePathToBase64Url}', [ImageController::class, 'converterImage']);

//redireccionamos de la raiz al idioma principal
Route::get('', function () {
	return redirect("/" . App::getLocale(), 301);
})->name('home.redirect');

Route::get(Routing::is_home(), [HomeController::class, 'index'])->name('home');
Route::any('prueba', [prueba::class, 'index'])->name('prueba');

Route::get('AnsorenaValidateUnion', [AnsorenaValidateUnion::class, 'validateUnion'])->name('AnsorenaValidateUnion');
Route::get('AnsorenaDecisionUnion', [AnsorenaValidateUnion::class, 'decisionUnion']);
Route::get('AnsorenaResultUnion', [AnsorenaValidateUnion::class, 'resultUnion']);


Route::get('send_new_password/{num_mails?}', [MailController::class, 'send_new_password']);

# Login @ UserController
Route::get(Routing::slug('login'), [UserController::class, 'login']);

Route::view(Routing::translateSeo('iniciar-sesion'), 'front::pages.login_page')->name('user.login-page');

Route::get(Routing::slugSeo('usuario-registrado'), [UserController::class, 'SuccessRegistered'])->name('user.registered');
Route::post(Routing::slug('login'), [UserController::class, 'login_post'])->name('post_login');
Route::post('/login_post_ajax', [UserController::class, 'login_post_ajax'])->name('user.login_post_ajax');
Route::post(Routing::slug('registro'), [UserController::class, 'registro'])->middleware('verify.captcha')->name('send_register');
Route::get(Routing::slug('logout'), [UserController::class, 'logout']);
Route::get(Routing::slug('password_recovery'), [UserController::class, 'passwordRecovery'])->name('user.password_recovery');
Route::post('/{lang}/send_password_recovery', [UserController::class, 'sendPasswordRecovery'])->name('user.send_password_recovery');
Route::post('/{lang}/ajax-send-password-recovery', [UserController::class, 'sendPasswordRecovery'])->name('user.ajax_send_password_recovery');

Route::post('/service/kyc/callback', [UserController::class, 'kycCallback'])->name('user.kyc_callback');

//registro en subalia
Route::get(Routing::slug('login') . "/subalia", [SubaliaController::class, 'index']);
Route::post(Routing::slug('login') . "/subalia/register", [SubaliaController::class, 'buscarCliente']);

//validacion o registro de usuarios que provienen de subalia
Route::post(Routing::slug('login') . "/subalia", [SubaliaController::class, 'validarSubaliaIndex']);
Route::post(Routing::slug('login') . "/subalia/valida", [SubaliaController::class, 'validarSubalia']);
Route::post('/{lang?}/register_subalia', [RegisterController::class, 'registerComplete']);

# Activar cuenta (Tauler)
Route::get(Routing::slug('activate_account'), [UserController::class, 'activateAcount']);

Route::post('/api-ajax/wallet/update', [UserController::class, 'updateWallet']);
Route::post('/api-ajax/wallet/create', [UserController::class, 'createWallet']);
Route::get('/api-ajax/wallet/back', [UserController::class, 'backVottumWallet'])->name('wallet.back');

Route::get('/{lang?}/email-recovery', [UserController::class, 'getPasswordRecovery'])->name('user.email-recovery');
Route::get('/{lang?}/email-validation', [UserController::class, 'getEmailValidation']);

# Logout & Login de Tiempo Real
Route::get(Routing::slug('login') . '/tr', [UserController::class, 'login']);
Route::post(Routing::slug('login') . '/tr', [UserController::class, 'login_post']);
Route::get(Routing::slug('logout') . '/tr', [UserController::class, 'logout']); // logout de tiempo real

# Subastas @ SubastaController
Route::get(Routing::slugSeo('indice-subasta') . '/{cod}-{texto}', [SubastaController::class, 'indice_subasta'])->where(array('cod' => '[0-9a-zA-Z]+'))->name('subasta.indice');

Route::post('/subasta/reproducciones', [SubastaController::class, 'reproducciones']);
Route::post('/subasta/megusta', [SubastaController::class, 'megusta']);
Route::post('/subasta/modal_images', [SubastaController::class, 'modalGridImages']);
Route::post('/subasta/modal_images_fullscreen', [SubastaController::class, 'modalImagesFullScreen'])->name('modal.images.fullscreen');

#lotes
Route::get(Routing::slugSeo('lote') . '/{cod}-{texto2}/{ref}-{texto}', [SubastaController::class, 'lote'])->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+'))->name('subasta.lote_old.ficha');
#NewLotes
Route::get(Routing::slugSeo('subasta-lote') . '/{texto}/{cod}-{ref}', [SubastaController::class, 'lote'])->where(array('cod' => '[0-9a-zA-Z]+'))->name('subasta.lote.ficha');

Route::get(Routing::translateSeo('subasta-actual'), [SubastaController::class, 'subasta_actual'])->name('subasta.actual');
Route::get(Routing::translateSeo('subasta-actual-online'), [SubastaController::class, 'subasta_actual_online'])->name('subasta.actual-online');
Route::get(Routing::translateSeo('presenciales'), [SubastaController::class, 'subastas_presenciales'])->name('subastas.presenciales');
Route::get(Routing::translateSeo('subastas-historicas'), [SubastaController::class, 'subastas_historicas'])->name('subastas.historicas');
Route::get(Routing::translateSeo('subastas-historicas-presenciales'), [SubastaController::class, 'subastas_historicas_presenciales'])->name('subastas.historicas_presenciales');
Route::get(Routing::translateSeo('subastas-historicas-online'), [SubastaController::class, 'subastas_historicas_online'])->name('subastas.historicas_online');
Route::get(Routing::translateSeo('subastas-online'), [SubastaController::class, 'subastas_online'])->name('subastas.online');
Route::get(Routing::translateSeo('subastas-permanentes'), [SubastaController::class, 'subastas_permanentes'])->name('subastas.permanentes');
Route::get(Routing::translateSeo('venta-directa'), [SubastaController::class, 'venta_directa'])->name('subastas.venta_directa');
Route::get(Routing::translateSeo('todas-subastas'), [SubastaController::class, 'listaSubastasSesiones'])->name('subastas.all');
Route::get(Routing::translateSeo('subastas-activas'), [SubastaController::class, 'subastas_activas'])->name('subastas.activas');
Route::get(Routing::translateSeo('subastas-especiales'), [SubastaController::class, 'subastas_especiales'])->name('subastas.especiales');

Route::get(Routing::translateSeo('haz-oferta'), [SubastaController::class, 'haz_oferta'])->name('subastas.haz_oferta');
Route::get(Routing::translateSeo('subasta-inversa'), [SubastaController::class, 'subasta_inversa'])->name('subastas.subasta_inversa');
Route::post('/api-ajax/sessions/files', [SubastaController::class, 'getAucSessionFiles'])->name('apiajax.sessions.files');

Route::get(Routing::slug('sub') . '/{status?}/{type?}', [SubastaController::class, 'listaSubastasSesiones'])->where(array('status' => '[A-Z]?', 'type' => '[A-Z]?'));

Route::post('/consult-lot/email', [MailController::class, 'emailConsultLot']);
Route::get('/{lang?}/accept_news', [MailController::class, 'acceptNews']);
Route::post('/api-ajax/info-lot-email', [MailController::class, 'sendInfoLot']);
Route::post('/api-ajax/ask-info-lot', [MailController::class, 'askInfoLot'])->middleware('verify.captcha');

Route::post('/api-ajax/save_order', [SubastaController::class, 'SaveOrders']);
Route::post('/api-ajax/delete_order', [SubastaController::class, 'DeleteOrders']);

Route::post('/api-ajax/exist-email', [UserController::class, 'existEmail']);
Route::post('/api-ajax/exist-nif', [UserController::class, 'existNif']);
Route::post('/api-ajax/cod-zip', [UserController::class, 'CodZip']);

Route::post('api-ajax/email_sobrepuja', [SubastaController::class, 'emailSobrepuja']);
/*reabrir lote */
Route::post('/api-ajax/open_lot', [SubastaTiempoRealController::class, 'openLot']);

# Lotes API Service
# Subastas en Tiempo Real
if (!empty(intval(Config::get('app.enable_tr_auctions')))) {

	Route::get(Routing::translateSeo('api/subasta') . 'streaming-test', function(){
		return view('front::pages.tiempo_real.streamin_test');
	});

	Route::get(Routing::translateSeo('api/subasta') . '{cod}-{texto}', [SubastaTiempoRealController::class, 'index'])->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+'));
	Route::get(Routing::translateSeo('api/subasta') . '{cod}-{texto}/{proyector}', [SubastaTiempoRealController::class, 'index'])->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+'));
}
Route::get('sendemailsobrepuja/{cod}/{licit}/{ref}/{orden_o_puja}', [SubastaTiempoRealController::class, 'sendEmailSobrepuja']);
Route::post('api/action/subasta-{cod}', [SubastaTiempoRealController::class, 'action'])->where(array('cod' => '[0-9a-zA-Z]+'))->middleware('measure.query.time')->name('api.action.subasta');
Route::post(Routing::slug('api') . '/comprar/subasta-{cod}', [SubastaTiempoRealController::class, 'comprar'])->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post(Routing::slug('api') . '/ol/subasta-{cod}', [SubastaTiempoRealController::class, 'ordenLicitacion'])->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post(Routing::slug('api') . '/contraofertar/subasta-{cod}', [SubastaTiempoRealController::class, 'contraOfertar'])->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post(Routing::slug('api') . '/check-contraofertar/subasta-{cod}', [SubastaTiempoRealController::class, 'preContraOfertar'])->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post(Routing::slug('api') . '/comprar-aux/subasta-{cod}', [SubastaTiempoRealController::class, 'comprarAux'])->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post('api-ajax/makeOffer', [SubastaTiempoRealController::class, 'makeOffer']);

Route::get('api-ajax' . Routing::slug('subasta') . '-{cod}/p-{page}', [SubastaController::class, 'subastaAjax'])->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+'));
Route::get('api-ajax' . Routing::slug('subasta') . '-{cod}-{texto2}/{ref}/{search?}', [SubastaController::class, 'lote'])->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));

Route::get('api-ajax/get_lote/{lang}/{cod}/{id_auc_sessions}/{ref}/{order}/{search?}', [SubastaController::class, 'getNextPreviousLotAjax'])->where(array('cod' => '[0-9a-zA-Z]+'));

Route::get('api-ajax/calculate_bids/{actual_bid}/{new_bid}', [SubastaTiempoRealController::class, 'calculateAvailableBids'])->where(array('actual_bid' => '[0-9]+', 'new_bid' => '[0-9]+',));
Route::get('api-ajax/favorites/{action}', [SubastaController::class, 'favorites'])->where(array('action' => '[a-zA-Z]+'));
Route::get('api-ajax/favorites-new/{action}', [SubastaController::class, 'favoritesNew'])->where(array('action' => '[a-zA-Z]+'));
Route::post('api-ajax/set_licit_lot', [SubastaTiempoRealController::class, 'setLicitLot']);
Route::post('api-ajax/activate_next', [SubastaTiempoRealController::class, 'ActiveNext']);
Route::post('api-ajax/jump_lots', [SubastaTiempoRealController::class, 'jumpLots']);
Route::post('api-ajax/baja_cli', [SubastaTiempoRealController::class, 'bajaCli']);
Route::post('api-ajax/get_baja_cli_sub', [SubastaTiempoRealController::class, 'getBajaCliSub']);
Route::post('api-ajax/get_clients_credit', [SubastaTiempoRealController::class, 'getClientsCreditBySub']);
Route::post('api-ajax/get_modified_paddles', [SubastaTiempoRealController::class, 'getModifiedPaddles']);
Route::post('api-ajax/add_lower_bid', [SubastaTiempoRealController::class, 'addLowerBid']);

/**
 * pedir el precio del envio
 * @todo - No veo que se esté utilizando - 20/08/2024
 */
Route::post('/api-ajax/get_shipment_rate', [DeliveryController::class, 'getShipmentRate']);
Route::post('/api-ajax/get_shipment_delivery', [DeliveryController::class, 'getShipmentDelivery']);

Route::post('api/status/subasta', [SubastaTiempoRealController::class, 'setStatus']);
Route::post('api/pause_lot', [SubastaTiempoRealController::class, 'pausarLote']);
Route::post('api/cancel_bid', [SubastaTiempoRealController::class, 'cancelarPuja']);
Route::post('api/cancel_order', [SubastaTiempoRealController::class, 'cancelarOrden']);
Route::post('api/cancelar_orden_user', [SubastaTiempoRealController::class, 'cancelarOrdenUser']);

Route::get(Routing::slug('chat') . '-{cod}-{lang}', [ChatController::class, 'getChat'])->where(array('cod' => '[0-9a-zA-Z]+'));
Route::post('api/chat', [ChatController::class, 'setChatArray']);
Route::post('api/chat/delete', [ChatController::class, 'deleteChat']);

Route::post('api/end_lot' . '-{cod}', [SubastaTiempoRealController::class, 'endLot'])->middleware('measure.query.time')->name('api.action.end_lot');

Route::get(Routing::slug('subastas-tiempo-real'), [SubastaController::class, 'listaSubastasSesionesTR']);

Route::post('/api-ajax/carousel', [ContentController::class, 'getAjaxCarousel']);
Route::post('/api-ajax/newcarousel', [ContentController::class, 'getAjaxNewCarousel']);
Route::post('/api-ajax/static-carousel', [ContentController::class, 'getAjaxStaticCarousel']);
Route::post('/api-ajax/add-sec-user', [UserController::class, 'changeFavTsec']);
Route::post('/api-ajax/lot_grid', [ContentController::class, 'getAjaxLotGrid']);

Route::post('/api-ajax/accept-cond-user', [UserController::class, 'AcceptConditionsUser']);

# Búsqueda
Route::get(Routing::slugSeo('busqueda') . '/{texto?}', [BusquedaController::class, 'index']);
Route::get(Routing::slugSeo('busqueda'), [BusquedaController::class, 'index'])->name('busqueda');
Route::get(Routing::slugSeo('busqueda') . '/{texto}/{page}', [BusquedaController::class, 'index']);

# Mail Composer via POST
Route::post('api-ajax/mail', [MailController::class, 'mailToAdmin'])->middleware('verify.captcha');
Route::post('api-ajax/mail-peticion-catalogo', [MailController::class, 'mailToAdminPeticionCatalogo']);

Route::get(Routing::slug('thanks'), function () {
	return View::make('front::generic.thanks');
});

# CMS / Gestor de contenido
Route::get(Routing::slugSeo('pagina', true) . '/{pagina}', [PageController::class, 'getPagina'])->name('staticPage');
Route::get('/article/{id}', [PageController::class, 'getArticle']);
Route::get(Routing::translateSeo('mapa-web'), [PageController::class, 'siteMapPage']);

//Soler esta utilizando un sistema de preguntas frequentes con csv.
//12-2024 No! ya no lo utiliza. revisar si puedo eliminar el antiguo sistema de preguntas frecuentes
if (Config::get("app.faqs_old", 0)) {
	Route::get(Routing::slugSeo('preguntas-frecuentes', true), [ContentController::class, 'faqs'])->name('faqs_page');
} else {
	Route::get(Routing::translateSeo('preguntas-frecuentes'), [FaqController::class, 'index'])->name('faqs_page');
}

require __DIR__ . '/user_panel.php';

/* nuevas version V3 */
Route::get(Routing::slugSeo('info-subasta', true) . '/{cod}-{texto}', [SubastaController::class, 'auction_info'])->name('urlAuctionInfo')->where(array('cod' => '[0-9a-zA-Z]+'));
Route::get('/lot/getfechafin', [SubastaController::class, 'getFechaFin']);

//TPV
Route::post('/response_redsys_multi_tpv/{tpvCode}', [PaymentsController::class, 'responseRedsysMultiTpv']);
Route::post('/gateway/{function}', [PaymentsController::class, 'index']);
Route::get('/gateway/returnPayPage', [PaymentsController::class, 'returnPayPage']);
Route::get('/sermepa/peticion.php', [PaymentsController::class, 'pagoDirecto']);
Route::get('/gateway/pasarela-pago', [PaymentsController::class, 'pagoDirecto']);
Route::get('/gateway/paypal-approve', [PaymentsController::class, 'pagoDirectoReturnPaypal'])->name('paypal_approve');
Route::get('/gateway/sendPayment', [PaymentsController::class, 'sendPayment']);
Route::post('/api-ajax/gastos_envio', [PaymentsController::class, 'gastosEnvio']);

//TPV carrito de la compra
Route::post('/shoppingCart/pay', [PayShoppingCartController::class, 'createPayment']);
Route::get('/shoppingCart/callRedsys', [PayShoppingCartController::class, 'callRedsys']);

// Valoraciones
Route::get(Routing::slug('valoracion-articulos-success'), [ValoracionController::class, 'ValoracionSuccess'])->name('valoracion-success');
Route::get(Routing::slugSeo('especialistas'), [EnterpriseController::class, 'index'])->name('especialistas');
Route::post('/{lang}/valoracion-articulos-adv', [ValoracionController::class, 'ValoracionArticulosAdv'])->middleware('verify.captcha');
Route::post('/valoracion/upload', [ValoracionController::class, 'uploadFile']);
Route::get('/{lang}/valoracion-{key}', [ValoracionController::class, 'GetValoracionGratuita'])->name('valoracion');
Route::get('/{lang}/valuation-{key}', [ValoracionController::class, 'GetValoracionGratuita']);

Route::get('/cron_load_cars_motorflash', [CronController::class, 'loadCarsMotorflash']);
Route::get('/web_cron_closelotws', [CronController::class, 'CloseLotsWebServiceCall']);
Route::get('/emailsadjudicaciones', [CronController::class, 'EmailsAdjudicaciones']);
Route::get('/send_resalelot', [CronController::class, 'emailsReSaleLots']);
Route::get('/send_lastcall', [CronController::class, 'lastCall']);
Route::get('/send_first_auction', [CronController::class, 'EmailFirstAuction']);
Route::get('/emailsadjudicaciones_generic', [CronController::class, 'EmailsAdjudicacionesGeneric']);
Route::get('/web_cron_closeauction', [CronController::class, 'EmailCloseAuction']);
Route::get('/web_cron_email_report', [CronController::class, 'cronEmailReports']);
Route::get('/lote_pending_pay', [CronController::class, 'LotePendingPay']);
Route::get('/lote_pending_collect', [CronController::class, 'LotePendingCollect']);
Route::get('/not-bidded-yet', [CronController::class, 'emailNotBiddedYet']);
Route::get('/email_cedente_amedida', [CronController::class, 'emailCedeneteAMedida']);
Route::get('/generateProductFeed', [CronController::class, 'generateProductFeed']);
Route::get('/email-cedente-amedida-error', [CronController::class, 'emailCedenteAmedidaError']);
Route::get('/update-divisa', [CronController::class, 'update_divisa']);

Route::get('/email_cancel_puja/{cod_sub}/{ref}/{cod_licit}', [MailController::class, 'emailCancelBid']);

Route::get('/generate_miniatures', [ImageController::class, 'generateMiniatures']);
Route::get('/regenerate_img', [ImageController::class, 'regenerate_images_table']);
Route::get('/new_generate_miniatures', [ImageController::class, 'generateImageLot']);

Route::get('/clear-cache', function () {
	Artisan::call('cache:clear');
});

Route::get('/{lang}/reload_lot', [SubastaController::class, 'reloadLot']);

Route::get('/email_fact_generated', [MailController::class, 'emailFacturaGenerated']);
Route::get('/disbandment_lot', [MailController::class, 'disbandment_lot']);

/* Blog */
Route::get(Routing::slugSeo('blog', true) . '/{key_categ?}', [NoticiasController::class, 'index'])->name('blog.index');
Route::get(Routing::slugSeo('blog', true) . '/{key_categ}/{key_news}', [NoticiasController::class, 'news'])->name('blog.news');
Route::get(Routing::slugSeo('mosaic-blog', true), [NoticiasController::class, 'museumPieces']);
Route::get(Routing::slugSeo('events', true), [NoticiasController::class, 'events'])->where(['lang' => $locales]);
Route::get(Routing::slugSeo('events', true) . '/{id}', [NoticiasController::class, 'event']);

Route::get(Routing::slugSeo('calendar'), [SubastaController::class, 'calendarController'])->name('calendar');

Route::post('api-ajax/updateDivisa', [UserController::class, 'savedDivisas']);

Route::post('/accept-all-cookies', [CookiesController::class, 'acceptAllCookies']);
Route::post('/reject-all-cookies', [CookiesController::class, 'rejectAllCookies']);
Route::post('/save-preferences-cookies', [CookiesController::class, 'setPreferencesCookies']);
Route::post('/add-configurations-cookies', [CookiesController::class, 'addConfigurationsCookies']);

/* Invaluable */
Route::get('/houses/token', [InvaluableController::class, 'token']);
Route::get('/houses/{houseUserName}/groups', [InvaluableController::class, 'groupSettings']);
Route::get('/houses/{houseUserName}/contacts', [InvaluableController::class, 'listContacts']);
Route::get('/houses/{houseUserName}/address', [InvaluableController::class, 'addresses']);
Route::get('/houses/{houseUserName}/channels', [InvaluableController::class, 'channels']);
Route::get('/houses/{houseUserName}/groups/{codSubasta}/session/{sessionID}/catalogs', [InvaluableController::class, 'catalogos']);
Route::get('/houses/{houseUserName}/lots', [InvaluableController::class, 'lots']);
Route::get('/houses/{houseUserName}/groups/{codSubasta}/session/{sessionID}/lots/{lotNumber}', [InvaluableController::class, 'deleteLot']);
Route::get('/houses/{houseUserName}/groups/{codSubasta}/session/{sessionID}/catalogs/lots/{lotNumber}', [InvaluableController::class, 'updateLot']);

/* Tabs tiempo real */
Route::get('/{lang}/historicTab/{cod_sub}/{session}', [SubastaTiempoRealController::class, 'historicTab']);
Route::get('/{lang}/favoritesTab/{cod_sub}/{licit}', [SubastaTiempoRealController::class, 'favoritesTab']);
Route::get('/{lang}/adjudicadosTab/{cod_sub}/{session}/{licit}', [SubastaTiempoRealController::class, 'adjudicadosTab']);
Route::get('/credit/{cod_sub}-{name}-{id_auc_sessions}', [SubastaTiempoRealController::class, 'creditPanel'])->name('creditPanel');
Route::post('/credit', [SubastaTiempoRealController::class, 'increaseCredit'])->name('increaseCredit');

/* Carrousel Tr */
Route::get('api-ajax/award_price/{cod_sub}/{ref_asigl0}', [SubastaTiempoRealController::class, 'getAwardPrice']);
Route::post('api-ajax/formulario-pujar', [SubastaController::class, 'getFormularioPujar']);
Route::post('api-ajax/enviar-formulario-pujar', [SubastaController::class, 'sendFormularioPujar']);
Route::post('api-ajax/accept-auction-conditions', [SubastaController::class, 'acceptAuctionConditions']);
Route::post('api-ajax/check-bid-conditions', 'SubastaController@checkBidConditions');

Route::get(Routing::translateSeo('contacto'), [ContactController::class, 'index'])->name('contact_page');
Route::get(Routing::slugSeo('administradores-concursales', true), [ContactController::class, 'admin']);
Route::post('contactSendmail', [ContactController::class, 'contactSendmail'])->middleware('verify.captcha')->name('contactSendmail');

Route::get(Routing::slugSeo('register', true), [RegisterController::class, 'index'])->name('register');

// Autoformularios
Route::post(Routing::slug('autoformulario-send'), [AutoFormulariosController::class, 'Send'])->middleware('verify.captcha')->name('autoformulario-send');
Route::get(Routing::slug('autoformulario-success'), [AutoFormulariosController::class, 'Success']);
Route::get(Routing::slug('tasaciones'), [AutoFormulariosController::class, 'Tasaciones']);
Route::get(Routing::translateSeo('workwithus', "/{key?}"), [AutoFormulariosController::class, 'workWidthUs']);

/**Api Emails */
Route::post('/{lang}/api/send-mail', [MailApiRestController::class, 'sendMail']);
Route::post('/{lang}/api/email-user-activation', [MailApiRestController::class, 'emailUserActivation']);
Route::post('/{lang}/api/email-access-visibility', [MailApiRestController::class, 'emailAccessToVisibility']);
Route::post('/{lang}/api/email-access-bids', [MailApiRestController::class, 'emailAccessToBids']);
Route::post('/{lang}/api/email-provisional-lot-award', [MailApiRestController::class, 'emailProvisionalLotAward']);
Route::post('/{lang}/api/email-complet-lot-report', [MailApiRestController::class, 'emailCompletLotReport']);
Route::post('/{lang}/api/email-when-change-file', [MailApiRestController::class, 'sendToUsersWithDepositWhenChangeFiles']);

/** SUBASTA - @Nuevo grid */
#listado de lotes por subasta
if (!empty(Config::get("app.gridLots")) && Config::get("app.gridLots") == "new") {
	#nuevo
	Route::get(Routing::slugSeo('subasta') . '/{texto?}_{cod}-{session}', [LotListController::class, 'getLotsList'])->name('urlAuction')->where(array('cod' => '[0-9a-zA-Z]+', 'session' => '[0-9]+'));
	#version antigua
	Route::get(Routing::slugSeo('subastaOld') . '/{cod}-{texto}', [SubastaController::class, 'index'])->where(array('cod' => '[0-9a-zA-Z]+'))->name('urlAuctionOld');
} else {
	#ver version nueva con URL test
	Route::get(Routing::slugSeo('subastaTest') . '/{texto}_{cod}-{session}', [LotListController::class, 'getLotsList'])->name('urlAuction')->where(array('cod' => '[0-9a-zA-Z]+', 'session' => '[0-9]+'));

	// #listado de lotes categorias y tematicos
	// [23/06/2025] - Eloy - Creo que no las usa nadie. Comentamos por el momento.
	// Route::get(Routing::slugSeo('subastas') . '/{key}/page-{page?}', [SubastaController::class, 'customizeLotListCategory']);
	// Route::get(Routing::slugSeo('subastas') . '/{key}/{subcategory?}', [SubastaController::class, 'customizeLotListCategory']);
	// Route::get(Routing::slugSeo('subastas') . '/{key}/{subcategory?}/page-{page}', [SubastaController::class, 'customizeLotListCategory']);

	// Route::get(Routing::slugSeo('tematicas') . '/{key}', [SubastaController::class, 'customizeLotListTheme']);
	// Route::get(Routing::slugSeo('tematicas') . '/{key}/page-{page?}', [SubastaController::class, 'customizeLotListTheme']);

	#antiguo
	Route::get(Routing::slugSeo('subasta') . '/{cod}-{texto}', [SubastaController::class, 'index'])->where(array('cod' => '[0-9a-zA-Z]+'))->name('urlAuctionOld');
	Route::get(Routing::slugSeo('subasta') . '/{cod}-{texto}/page-{page}', [SubastaController::class, 'index'])->where(array('cod' => '[0-9a-zA-Z]+', 'page' => '[0-9]+',));
}

#listado de lotes para todas las categorias
Route::get(Routing::slugSeo('subastas'), [LotListController::class, 'getLotsListAllCategories'])->name('allCategories');

#listado de lotes por categoria, añadiendo cualquier texto amigable, servirá para poder poner caracteristicas de manera amigable, pasandole luego la variable de la caracteristica, por ejemplo subastas_audi/texto-friendly?features[4]=117
Route::get(Routing::slugSeo('subastas') . "_{keycategory}/{texto}", [LotListController::class, 'getLotsListCategory'])->name('categoryTexFriendly');

#listado de lotes por categoria
Route::get(Routing::translateSeo('subastas', '') . "-{keycategory}", [LotListController::class, 'getLotsListCategory'])->name('category');

#listado de lotes por secciones
Route::get(Routing::translateSeo('subastas', "-{keycategory}/{keysection?}"), [LotListController::class, 'getLotsListSection'])->name('section');
#listado de lotes por subcsecciones
Route::get(Routing::translateSeo('subastas', "-{keycategory}/{keysection}/{keysubsection}"), [LotListController::class, 'getLotsListSubSection'])->name('subsection');

#carga lotes por ajax
Route::post('/{lang}/GetAjaxLots', [LotListController::class, 'GetAjaxLots'])->name('getAjaxLots');

#consultar si hay lotes históricos
Route::post('/{lang}/showHistoricLink', [LotListController::class, 'showHistoricLink']);

#Tienda de venta directa
Route::post('/addLotCart', [CartController::class, 'addLot'])->name('addLotCart');
Route::post('/deleteLotCart', [CartController::class, 'deleteLot'])->name('deleteLotCart');
Route::post('/shippingCostsCart', [CartController::class, 'shippingCostsCart']);

Route::get(Routing::translateSeo('artistas'), [ArtistController::class, 'index'])->name("artists");
Route::get(Routing::translateSeo('artista', '/{name}_{idArtist}'), [ArtistController::class, 'artist'])->name("artist")->where(array('idArtist' => '[0-9]+'));

/***** Articulos ******/
Route::get(Routing::translateSeo('articulos'), [ArticleController::class, 'index'])->name("articles");
Route::get(Routing::translateSeo('articulos', '_{family}'), [ArticleController::class, 'index'])->name("articles_family");
Route::get(Routing::translateSeo('articulos', '-{category}'), [ArticleController::class, 'index'])->name("articles-category");
Route::get(Routing::translateSeo('articulos', '-{category}/{subcategory}'), [ArticleController::class, 'index'])->name("articles-subcategory");
Route::get(Routing::translateSeo('articulo', '/{idArticle}-{friendly}'), [ArticleController::class, 'article'])->where('idArticle', '[0-9]+')->name("article");
Route::post(Routing::translateSeo('addArticleCart'), [ArticleController::class, 'addArticle'])->name('addArticleCart');
Route::post(Routing::translateSeo('changeUnitsArticleCart', '/{idArticle}/{units}'), [ArticleController::class, 'changeUnitsArticleCart'])->name('changeUnitsArticleCart');

Route::post('/deleteArticleCart', [ArticleController::class, 'deleteArticle'])->name('deleteArticleCart');
Route::post(Routing::translateSeo('getTallasColoresFicha'), [ArticleController::class, 'getTallasColoresFicha']);

Route::post('/articleCart/pay', [PayArticleCartController::class, 'createPayment']);
Route::get('/articleCart/callRedsys', [PayArticleCartController::class, 'callRedsys']);
#página de confirmación de compra
Route::post('/articleCart/returnpayup2', [PayArticleCartController::class, 'ReturnPayUP2']);
Route::post('/articleCart/returnPay', [PayArticleCartController::class, 'returnPay']);


Route::group(['prefix' => 'api'], function () {
	Route::post('{lang}/getArticles', [ArticleController::class, 'getArticles'])->name("getArticles");
	Route::post('{lang}/getOrtsec', [ArticleController::class, 'getOrtSec']);
	Route::post('{lang}/getSec', [ArticleController::class, 'getSec']);
	Route::post('{lang}/getTallasColores', [ArticleController::class, 'getTallasColores']);
	Route::post('{lang}/getMarcas', [ArticleController::class, 'getMarcas']);
	Route::post('{lang}/getFamilias', [ArticleController::class, 'getFamilias']);
});
/***** Fin Articulos ******/

/***** Galería de arte *****/

Route::get(Routing::translateSeo('exposicion') . '{texto}_{cod}-{reference}', [GaleriaArte::class, 'getGalery'])->name('exposicion')->where(array('cod' => '[0-9a-zA-Z]+', 'reference' => '[0-9]+'));
Route::get(Routing::translateSeo('exposiciones'), [GaleriaArte::class, 'exhibitons'])->name('exposiciones');
#la comento por que de momento no se usarálogin_post_ajax
# Route::get(Routing::translateSeo('exposiciones-anteriores'), [GaleriaArte::class, 'previousExhibitons'])->name('exposiciones-anteriores');
Route::get(Routing::translateSeo('artistas-galeria'), [GaleriaArte::class, 'artists'])->name('artistasGaleria');
Route::get(Routing::translateSeo('artista-galeria') . '{id_artist}', [GaleriaArte::class, 'artist'])->name('artistaGaleria')->where(array('id_artist' => '[0-9]+'));
Route::get(Routing::translateSeo('fondo-galeria'), [GaleriaArte::class, 'fondoGaleria'])->name('fondoGaleria');
Route::get(Routing::translateSeo('artista-fondo-galeria') . '{id_artist}', [GaleriaArte::class, 'artistFondoGaleria'])->name('artistaFondoGaleria')->where(array('id_artist' => '[0-9]+'));

/***** Fin Galería de arte *****/

Route::get('/{lang}/lot-file/{file}/{numhces}/{linhces}/download', [SubastaController::class, 'getDownloadLotFile'])->name('lot_file_download');

Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {
    $user = Socialite::driver('google')->user();
	dd($user);
    // $user->token
});

Route::get(Routing::translateSeo('remates-destacados',"/{codSub}"), [ContentController::class, 'rematesDestacados'])->name('rematesDestacados');

Route::post("/api/webhookvottun", [VottunController::class, 'webhook'])->name('webhookvottun');

#NFT
#pago individual del minteo que se pondrá en el correo
Route::get("mintnftpayment/{operationId}", [PayShoppingCartController::class, 'createMintPay'])->name('mintNftPayUrl');
#pago
Route::get("transfernftpayment/{operationsIds}", [PayShoppingCartController::class, 'createTransferPay'])->name('transferNftPayUrl');
#Comprobar escalados fuera de rango
Route::get("preciofueraescalado/{codSub}", [CustomControllers::class, 'preciosFueraEscalado']);

Route::get("/api-ajax/newsletters/{service}/{action}", [NewsletterController::class, 'checkCallback']);
Route::post("/api-ajax/newsletters/{service}/{action}", [NewsletterController::class, 'callbackUnsuscribe']);
Route::post('api-ajax/newsletter/{opcion}', [NewsletterController::class, 'setNewsletterAjax']);
Route::get("/{lang}/newsletter/{email}", [NewsletterController::class, 'configNewsletter'])->where('lang', 'es|en');
Route::get("/{lang}/newsletter-suscribe/{email}", [NewsletterController::class, 'suscribeOnlyToExternalService']);
Route::get("/{lang}/newsletter-unsuscribe/{email}", [NewsletterController::class, 'unsuscribeNewsletter'])->name('newsletter.unsuscribe');
Route::get("/{lang}/newsletter-migrate", [NewsletterController::class, 'migrateNewslettersToNewFormat']);
Route::get("/{lang}/newsletter-mailchimp-export", [NewsletterController::class, 'mailchimpExportCsv']);


/* SIMULACIÓN DE ENDPOINT DE PUSH */
//Route::get("/send_push", [AppPushController::class, 'pushTestSender']); //no existe el metodo
Route::get("api-ajax/push_app", [AppPushController::class, 'pushTestEndPoint']);
Route::post("api-ajax/push_app", [AppPushController::class, 'pushTestEndPoint']);

#Funciones nuevas de los  sockets en PHP
Route::post("/phpsock/actionv2", [NodePhp::class, 'actionV2']);
Route::post("/phpsock/cancelarbid", [NodePhp::class, 'cancelarBid']);
Route::post("/phpsock/endlot", [NodePhp::class, 'endLot']);
Route::post("/phpsock/cancelarorden", [NodePhp::class, 'cancelarOrden']);
Route::post("/phpsock/set_status_auction", [NodePhp::class, 'setStatusAuction']);
Route::post("/phpsock/set_message_chat", [NodePhp::class, 'setMessageChat']);
Route::post("/phpsock/delete_message_chat", [NodePhp::class, 'deleteMessageChat']);
Route::post("/phpsock/start_count_down", [NodePhp::class, 'startCountDown']);
Route::post("/phpsock/stop_count_down", [NodePhp::class, 'stopCountDown']);
Route::post("/phpsock/lot_pause", [NodePhp::class, 'lotPause']);
Route::post("/phpsock/fair_warning", [NodePhp::class, 'fairWarning']);
Route::post("/phpsock/jump_lot", [NodePhp::class, 'jumpLot']);
Route::post("/phpsock/open_bids", [NodePhp::class, 'openBids']);
Route::post("/phpsock/cancelar_orden_user", [NodePhp::class, 'cancelarOrdenUser']);

Route::get("/lot-qr-generate", [CustomControllers::class, 'lotQRGenerator'])->name('lotQRGenerator');

/* Depositos */
Route::post("deposit/pay", [DepositController::class, 'createPayment'])->name("payDeposit");
Route::get('/deposit/callRedsys', [DepositController::class, 'callRedsys'])->name("depositCallRedsys");
#direccion donde respondera redsys para que el usuario vea que se ha realizado el pago del deposito correctamente y luego redirigiendo al lote
Route::get('/{lang}/returnPayPageDeposit/{codSub}/{ref}', [DepositController::class, 'returnPayPageDeposit'])->name("returnPayPageDeposit");

/* INVALUABLE */
Route::get("carga-catalogo-invaluable/{codSub}/{reference}", [House::class, 'catalogs']);
Route::get("carga-lote-invaluable/{codSub}/{reference}/{ref}", [House::class, 'catalogLots']);
//responseRedsysMultiTpv

#Eventos SEO, permite pasar letras numeros y el simbolo _
Route::get("/seo_event/{event}", [CustomControllers::class, 'saveEvent'])->where(['event' => '[0-9a-zA-Z_]+']);

#Lleida Net, como n ose si devuelven post o get pngo lso dos
Route::get('/lleidanet/response_ocr', [CustomControllers::class, 'response_ocr']);
Route::post('/lleidanet/response_ocr', [CustomControllers::class, 'response_ocr']);

Route::get('/tecalis-test', [TecalisService::class, 'auth']);

Route::any('/tecalis/callback', function(Request $request){
	Log::debug("callback tecalis", ['request' => $request->all()]);
	return response()->json(['status' => 'ok']);
});
Route::any('/tecalis/redirect', function(Request $request){
	Log::debug("redirect tecalis", ['request' => $request->all()]);
	return response()->json(['status' => 'ok']);
});

/* Esto iba en el routes de la version 5.2 de laravel despues de incluir el routes/web */
require __DIR__ . '/custom.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/api_rest.php';
require __DIR__ . '/api_label.php';
require __DIR__ . '/web_service.php';

/* Rutas para llamar al 404 */
Route::get('404', function () {
	exit(View::make('front::errors.404'));
});

Route::post('404', function () {
	exit(View::make('front::errors.404'));
});

//Si no ha habido ningun resultado mostramos un 404
Route::get('{any}', function () {
	exit(View::make('front::errors.404'));
})->where('any', '.*');


Route::post('{any}', function () {
	exit(View::make('front::errors.404'));
})->where('any', '.*');

/* FIN 5.2 de laravel despues de incluir el routes/web */
