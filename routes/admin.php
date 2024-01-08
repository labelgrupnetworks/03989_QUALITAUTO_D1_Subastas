<?php

use App\Http\Controllers\admin\AdminConfigController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
/*
|--------------------------------------------------------------------------
| Layout - BackOffice
|--------------------------------------------------------------------------
*/
# Añadido excepcional ya que no funcionaba el admin

Route::get('/admin', 'AdminHomeController@index');
Route::group(['prefix' => 'admin', 'namespace' => 'admin'], function () {

	Route::group(['middleware' => ['adminAuth', 'SessionTimeout:' . Config::get('app.admin_session_timeout')]], function () {

		Route::get('/', 'AdminHomeController@index');
		Route::get('/sliders/{tab?}', 'AdminSlidersController@index');

		//lo comento por que antes estaba así pero jaume lo tiene diferente 2017_09_18
		//Routes: Route::get('/config', 'AdminConfigController@index');

		Route::get('/config', 'AdminConfigController@index');
		Route::get('/cms', 'CmsConfigController@index');

		Route::get('/bloque', 'BloqueConfigController@index');
		Route::get('/bloque/name/{id?}', 'BloqueConfigController@SeeBloque');

		Route::get('/resources', 'ResourceController@index');
		Route::get('/resources/name/{id?}', 'ResourceController@SeeResources');

		Route::get('/banner', 'BannerController@index');
		Route::get('/banner/name/{id?}', 'BannerController@SeeBanner');

		Route::get('/auc-index', 'AucIndexController@index');
		Route::get('/auc-index/name/{id?}', 'AucIndexController@SeeAuxIndex');

		Route::get('/auc-index-menu', 'AucIndexMenuController@index');

		Route::get('/seo-familias-sessiones', 'SeoFamiliasSessionesController@index');
		Route::get('/seo-familias-sessiones/name/{id?}', 'SeoFamiliasSessionesController@SeeFamilySessionsSeo');

		Route::get('/seo-categories', 'SeoCategoriesController@index');
		Route::get('/seo-categories/name/{cod_sec?}', 'SeoCategoriesController@InfCategSeo');

		Route::get('/traducciones/{head}/{lang}', 'TraduccionesController@index');
		Route::get('/traducciones', 'TraduccionesController@getTraducciones');

		Route::get('/traducciones/search', 'TraduccionesController@search');

		Route::get('/content', 'ContentController@index');
		Route::get('/content/name/{id}', 'ContentController@getPage');
		Route::get('/email_clients', 'AdminEmailsController@index');
		Route::get('/email_log', 'AdminEmailsController@showLog')->name('adminemails.showlog');

		Route::get('/blog-admin', 'BlogController@getBlogs');
		Route::get('/blog-admin/name/{id?}', 'BlogController@index');
		Route::get('/category-blog', 'BlogController@getCategoryBlog');
		Route::get('/category-blog/name/{id?}', 'BlogController@seeCategoryBlog');

		Route::post('/contenido/blog-category', 'contenido\AdminBlogCategoryController@store')->name('admin.contenido.blog-category.store');
		Route::get('/contenido/blog-category/{id}/edit', 'contenido\AdminBlogCategoryController@edit')->name('admin.contenido.blog-category.edit');
		Route::post('/contenido/blog-category/{id}', 'contenido\AdminBlogCategoryController@update')->name('admin.contenido.blog-category.update');
		Route::put('/contenido/blog-category/{id}/enabled', 'contenido\AdminBlogCategoryController@changeIsEnabled')->name('admin.contenido.blog-category.enabled');
		Route::put('/contenido/blog-category/order', 'contenido\AdminBlogCategoryController@updateOrder')->name('admin.contenido.blog-category.order');
		Route::delete('/contenido/blog-category/{id}', 'contenido\AdminBlogCategoryController@destroy')->name('admin.contenido.blog-category.delete');

		Route::get('/contenido/blog', 'contenido\AdminBlogController@index')->name('admin.contenido.blog.index');
		Route::get('/contenido/blog/create', 'contenido\AdminBlogController@create')->name('admin.contenido.blog.create');
		Route::post('/contenido/blog', 'contenido\AdminBlogController@store')->name('admin.contenido.blog.store');
		Route::get('/contenido/blog/{id}', 'contenido\AdminBlogController@show')->name('admin.contenido.blog.show');
		Route::get('/contenido/blog/{id}/edit', 'contenido\AdminBlogController@edit')->name('admin.contenido.blog.edit');
		Route::put('/contenido/blog/{id}', 'contenido\AdminBlogController@update')->name('admin.contenido.blog.update');
		Route::delete('/contenido/blog/{id}', 'contenido\AdminBlogController@destroy')->name('admin.contenido.blog.destroy');

		Route::post('/contenido/blog/{id}/image', 'contenido\AdminBlogController@storeFrontResourceBlog')->name('admin.contenido.blog.image');
		Route::put('/contenido/blog/{id}/enabled', 'contenido\AdminBlogController@changeIsEnabledBlog')->name('admin.contenido.blog.enabled');

		Route::get('/contenido/content/import', 'contenido\AdminContentPageController@importHtmlToWebBlogLang')->name('admin.contentido.content.import');
		Route::post('/contenido/content/{id}/assets', 'contenido\AdminContentPageController@uploadAsset')->name('admin.contentido.content.assets');
		Route::post('/contenido/content/{id}/block', 'contenido\AdminContentPageController@store')->name('admin.contentido.content.store');
		Route::post('/contenido/content/{id}/block/{id_content}/resource', 'contenido\AdminContentPageController@setResource')->name('admin.contentido.content.resource');
		Route::put('/contenido/content/{id}/block/{id_content}', 'contenido\AdminContentPageController@update')->name('admin.contentido.content.update');
		Route::put('/contenido/content/{id}/block/{id_content}/order', 'contenido\AdminContentPageController@order')->name('admin.contentido.content.order');
		Route::delete('/contenido/content/{id}/block/{id_content}', 'contenido\AdminContentPageController@destroy')->name('admin.contentido.content.destroy');

		Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

		//TODO: V5
		Route::group(['namespace' => 'V5'], function () {

			Route::group(['prefix' => 'faqs'], function () {
				Route::get('/{lang}/edit/{cod_faq?}', 'AdminFaqController@edit');
				Route::post('/{lang}/editRun', 'AdminFaqController@editRun');
				Route::post('/delete', 'AdminFaqController@delete');
				Route::post('/{lang}/order', 'AdminFaqController@saveOrder');
				Route::get('/{lang?}/', 'AdminFaqController@index');
				Route::post('/{lang}/categories/newRun', 'AdminFaqController@categoriesNewRun');
				Route::post('/categories/delete', 'AdminFaqController@categoriesDelete');
				Route::get('/{lang}/categories/edit/{cod}', 'AdminFaqController@categoriesEdit');
				Route::post('/{lang}/categories/editRun', 'AdminFaqController@categoriesEditRun');
			});
			Route::get('/enable_register', 'AdminEnableRegisterController@index');
			Route::post('/enable_register', 'AdminEnableRegisterController@save');
			Route::get('/favorites', 'AdminFavoritesControler@index');
		});

		Route::group(['prefix' => 'newbanner'], function () {
			Route::get('/', "contenido\BannerController@index");
			Route::get('/download', "contenido\BannerController@download");
			Route::get('/ubicacionhome', "contenido\BannerController@ubicacionHome");
			Route::get('/nuevo', "contenido\BannerController@nuevo");
			Route::post('/nuevo_run', "contenido\BannerController@nuevo_run");
			Route::get('/editar/{id}', "contenido\BannerController@editar");
			Route::get('/borrar/{id}', "contenido\BannerController@borrar");
			Route::post('/activar', "contenido\BannerController@activar");
			Route::post('/editar_run', "contenido\BannerController@editar_run");
			Route::post('/listaItemsBloque', "contenido\BannerController@listaItemsBloque");
			Route::post('/nuevoItemBloque', "contenido\BannerController@nuevoItemBloque");
			Route::post('/editaItemBloque', "contenido\BannerController@editaItemBloque");
			Route::post('/guardaItemBloque', "contenido\BannerController@guardaItemBloque");
			Route::post('/borraItemBloque', "contenido\BannerController@borraItemBloque");
			Route::post('/estadoItemBloque', "contenido\BannerController@estadoItemBloque");
			Route::post('/vistaPrevia', "contenido\BannerController@vistaPrevia");
			Route::post('/ordenaBloque', "contenido\BannerController@ordenaBloque");
			Route::post('/orderbanner', "contenido\BannerController@orderBanner");
		});

		Route::resource('event', 'contenido\EventsController')->except(['show', 'destroy']);

		Route::post('/static-pages/images', 'contenido\AdminPagesController@uploadImage')->name('static-pages.upload_image');
		Route::resource('static-pages', 'contenido\AdminPagesController');

		/**Deshabilitado hasta que este arreglado y testeado 100% */
		Route::group(['prefix' => 'email'], function () {
			Route::get('/', 'configuracion\EmailController@index');
			Route::get('/editar/{cod}', 'configuracion\EmailController@edit');
			Route::post('/guardar', 'configuracion\EmailController@guardar');
			Route::post('/guardarEmail', 'configuracion\EmailController@guardarEmail');
			Route::get('/plantilla', 'configuracion\EmailController@plantilla');
			Route::post('/guardarPlantilla', 'configuracion\EmailController@guardarPlantilla');
		});

		Route::get('/email', 'EmailController@index');
		Route::get('/email/ver/{cod_email}', 'EmailController@getEmail');

		Route::group(['prefix' => 'escalado'], function () {
			Route::get('/', 'configuracion\EscaladoController@index');
			Route::post('/save', 'configuracion\EscaladoController@save');
		});

		Route::group(['prefix' => 'configuracion'], function () {
			Route::get('/', 'configuracion\GeneralController@index');
			Route::post('/save', 'configuracion\GeneralController@save')->name('admin.configuracion.save');

			Route::get('credito/export', 'configuracion\AdminCreditoController@export')->name('credito.export');
			Route::get('credito/subasta', 'configuracion\AdminCreditoController@getCreditData')->name('credito.subasta');
			Route::resource('credito', 'configuracion\AdminCreditoController')->except(['show']);


			//Route::get('credito/subasta', 'configuracion\AdminCreditoController@getCreditData')->name('credito.subasta');
		});

		#CONTENIDO
		Route::group(['prefix' => 'calendar'], function () {
			Route::get('/', 'contenido\AdminCalendarController@index');
			#Route::get('/show', 'contenido\AdminCalendarController@show');
			#Route::get('/create', 'contenido\AdminCalendarController@create');
			#Route::post('/store', 'contenido\AdminCalendarController@store');
			Route::get('/edit', 'contenido\AdminCalendarController@edit');
			Route::post('/update', 'contenido\AdminCalendarController@update');
			Route::post('/delete', 'contenido\AdminCalendarController@destroy');
		});


		// CLIENTES
		Route::group(['prefix' => 'favoritos'], function () {
			Route::get('/', "usuario\FavoritosController@index");
		});
		Route::group(['prefix' => 'cliente'], function () {
			Route::get('/', 'usuario\ClienteController@index');
			Route::get('/nuevo', 'usuario\ClienteController@edit');
			Route::get('/edit/{id}', 'usuario\ClienteController@edit');
			Route::post('/edit_run', 'usuario\ClienteController@edit_run');
			Route::post('/bajaCliente', 'usuario\ClienteController@bajaCliente');
			Route::post('/reactivarCliente', 'usuario\ClienteController@reactivarCliente');
			Route::get('/export', 'usuario\ClienteController@export')->name('cliente.export');
		});


		// SUBASTAS

		Route::group(['prefix' => 'subasta'], function () {
			Route::get('/', "subasta\SubastaController@index");
			Route::get('/nuevo', 'subasta\SubastaController@edit');
			Route::get('/edit/{id}', 'subasta\SubastaController@edit')->name('subasta.edit');
			Route::post('/edit_run', 'subasta\SubastaController@edit_run');
			Route::post('/borrarSubasta', 'subasta\SubastaController@borrarSubasta');
			Route::post('/ficherosSubasta/{subasta}', 'subasta\SubastaController@ficherosSubasta');
			Route::post('/borrarFicherosSubasta', 'subasta\SubastaController@borrarFicherosSubasta');
			Route::post('/borrarPuja', 'subasta\SubastaController@borrarPuja');
			Route::post('/borrarOrden', 'subasta\SubastaController@borrarOrden');
			Route::post('/guardaEscalado', 'subasta\SubastaController@guardaEscalado')->name('guardarEscaladoSubastas');
			Route::get('/list', 'subasta\SubastaController@getSelectSubastas');
			Route::get('/select2list', 'subasta\SubastaController@getSelect2List')->name('subastas.select2');
		});
		Route::group(['prefix' => 'lote'], function () {
			Route::get('/nuevo/{id}', 'subasta\SubastaController@editLote');
			Route::get('/edit/{subasta}/{id}', 'subasta\SubastaController@editLote');
			Route::post('/borrar/{subasta}/{id}', 'subasta\SubastaController@borrarLote');
			Route::post('/edit_run', 'subasta\SubastaController@editLote_run');
			Route::post('/borrarImagenLote', 'subasta\SubastaController@borrarImagenLote');
			Route::get('/file/{id}', 'subasta\SubastaController@lotFile');
			Route::post('/excelRun', 'subasta\SubastaController@subirExcel');
			Route::post('/fileImport/{type}', 'subasta\SubastaController@lotFileImport');
			Route::post('/excelImg', 'subasta\SubastaController@createExcelImage');
			Route::post('/addImg/', 'subasta\SubastaController@addImage')->name('addLotImage');
			Route::get('/list', 'subasta\SubastaController@getSelectLotes');
			Route::get('/listFondoGaleria', 'subasta\SubastaController@getSelectLotesFondoGaleria')->name('lotListFondoGaleria');
			Route::post('/addfile', 'subasta\SubastaController@addLoteFile');
			Route::post('/addvideo', 'subasta\SubastaController@addLoteVideo');
			Route::post('/deletefile', 'subasta\SubastaController@deleteLoteFile');
			Route::post('/deletevideo', 'subasta\SubastaController@deleteLoteVideo');
			Route::get('/export/{cod_sub}', 'subasta\SubastaController@export')->name('lote.export');
			Route::post('/send_end_lot_ws', 'subasta\SubastaController@send_end_lot_ws');


		});
		Route::group(['prefix' => 'sesion'], function () {
			Route::get('/nuevo/{reference}', 'subasta\AdminAucSessionsController@oldEdit');
			Route::get('/edit/{subasta}/{reference}', 'subasta\AdminAucSessionsController@oldEdit');
			Route::post('/borrar/{cod_sub}/{reference}', 'subasta\AdminAucSessionsController@destroy');
			Route::post('/update', 'subasta\AdminAucSessionsController@oldUpdate');
			Route::post('/addfile', 'subasta\AdminAucSessionsFilesController@store');
			Route::post('/deletefile', 'subasta\AdminAucSessionsFilesController@destroy');
		});

		Route::group(['prefix' => 'orders'], function () {
			Route::get('/excel/{idAuction}', 'subasta\AdminOrderController@excel')->name('orders.excel');
			Route::post('/import/{idAuction}', 'subasta\AdminOrderController@import')->name('orders.import');
			Route::get('/export/{idAuction}', 'subasta\AdminOrderController@export')->name('orders.export');
			Route::post('/delete-selection/{idAuction}', 'subasta\AdminOrderController@deleteSelection')->name('orders.delete_selection');
			Route::post('/send_ws', 'subasta\AdminOrderController@send_ws');
		});
		Route::resource('orders', 'subasta\AdminOrderController')->except(['show'])->parameters(['orders' => 'idAuction']);

		Route::group(['prefix' => 'award'], function () {
			Route::get('/', 'subasta\AdminAwardController@index')->name('award.index');
			Route::get('/show', 'subasta\AdminAwardController@show');
			Route::get('/create/{idAuction?}', 'subasta\AdminAwardController@create')->name('award.create');
			Route::post('/store', 'subasta\AdminAwardController@store');
			Route::get('/edit', 'subasta\AdminAwardController@edit')->name('award.edit');
			Route::post('/update', 'subasta\AdminAwardController@update');
			Route::post('/delete', 'subasta\AdminAwardController@destroy');
			Route::post('/export/{idAuction?}', 'subasta\AdminAwardController@export')->name('award.export');
		});

		Route::group(['prefix' => 'not-award'], function () {
			Route::get('/', 'subasta\AdminNotAwardController@index')->name('not_award.index');
			Route::post('/export/{idAuction?}', 'subasta\AdminNotAwardController@export')->name('not_award.export');
		});


		Route::group(['prefix' => 'client'], function () {
			Route::get('/list', 'subasta\SubastaController@getSelectClients');
			Route::get('/select2list', 'subasta\SubastaController@getSelect2ClientList')->name('client.list');
		});
		Route::group(['prefix' => 'licit'], function () {
			Route::get('/', 'subasta\AdminLicitController@index');
			Route::get('/show', 'subasta\AdminLicitController@show');
			Route::get('/create', 'subasta\AdminLicitController@create');
			Route::post('/store', 'subasta\AdminLicitController@store');
			Route::get('/edit', 'subasta\AdminLicitController@edit');
			Route::post('/update', 'subasta\AdminLicitController@update');
			Route::get('/list', 'subasta\SubastaController@getSelectLicits');
			Route::post('/export-licits', 'subasta\SubastaController@exportLicits');
		});


		Route::group(['prefix' => 'category'], function () {
			Route::get('/', 'subasta\AdminCategoryController@index');
			#Route::get('/show', 'subasta\AdminCategoryController@show');
			#Route::get('/create', 'subasta\AdminCategoryController@create');
			#Route::post('/store', 'subasta\AdminCategoryController@store');
			Route::get('/edit', 'subasta\AdminCategoryController@edit');
			Route::post('/update', 'subasta\AdminCategoryController@update');
			Route::post('/delete', 'subasta\AdminCategoryController@destroy');

			/* Preferencias */
			Route::get('/preferences/download', 'subasta\AdminSubCategoryController@downloadPreferencesExcel');
		});

		Route::group(['prefix' => 'subcategory'], function () {
			Route::get('/', 'subasta\AdminSubCategoryController@index');
			#Route::get('/show', 'subasta\AdminSubCategoryController@show');
			#Route::get('/create', 'subasta\AdminSubCategoryController@create');
			#Route::post('/store', 'subasta\AdminSubCategoryController@store');
			Route::get('/edit', 'subasta\AdminSubCategoryController@edit');
			Route::post('/update', 'subasta\AdminSubCategoryController@update');
			Route::post('/delete', 'subasta\AdminSubCategoryController@destroy');
		});

		Route::get('user_newsletter/export/', 'usuario\AdminNewsletterClientController@export')->name('user_newsletter.export');
		Route::resource('user_newsletter', 'usuario\AdminNewsletterClientController')->only(['index', 'show', 'destroy']);

		Route::get('newsletter/export/', 'usuario\AdminNewsletterController@export')->name('newsletter.export');
		Route::resource('newsletter', 'usuario\AdminNewsletterController');

		Route::post('clientes/delete-with-filters', 'usuario\AdminClienteController@destroyWithFilters')->name('clientes.destroy_with_filters');
		Route::post('clientes/delete-selection', 'usuario\AdminClienteController@destroySelections')->name('clientes.destroy_selections');
		Route::post('clientes/update-with-filters', 'usuario\AdminClienteController@updateWithFilters')->name('clientes.update_with_filters');
		Route::post('clientes/update-selection', 'usuario\AdminClienteController@updateSelections')->name('clientes.update_selections');
		Route::post('clientes/baja-tmp-cli', 'usuario\AdminClienteController@modificarBajaTemporal');
		Route::post('clientes/export', 'usuario\AdminClienteController@export')->name('clientes.export');
		Route::post('clientes/send_ws', 'usuario\AdminClienteController@send_ws');
		Route::resource('clientes', 'usuario\AdminClienteController');
		Route::post('clientes/{cod_cli}/dni', 'usuario\AdminClienteFilesController@storeDni')->name('admin.clientes.dni.store');
		Route::resource('clientes.files', 'usuario\AdminClienteFilesController')->only(['store', 'show', 'destroy']);

		Route::post('subastas/deposito/update-filters', 'subasta\AdminDepositoController@updateWithFilters')->name('subastas.deposit.update_filters');
		Route::post('subastas/deposito/update-selection', 'subasta\AdminDepositoController@updateSelections')->name('subastas.deposit.update_selection');
		Route::resource('deposito', 'subasta\AdminDepositoController')->except(['show', 'destroy']);

		Route::resource('visibilidad', 'subasta\AdminVisibilidadController')->except(['show']);

		Route::get('winners/export/{cod_sub}', 'subasta\AdminWinnerController@winnersExport')->name('winners.export');
		Route::resource('artist', 'V5\AdminArtistController')->except(['show']);
		Route::post('artist/activar', 'V5\AdminArtistController@activar');
		Route::post('artist/updatearticles', 'V5\AdminArtistController@updateArticles');
		Route::post('artist/loadarticles', 'V5\AdminArtistController@loadArticles');
		Route::post('artist/createarticle', 'V5\AdminArtistController@createArticle');
		Route::post('artist/deletearticle', 'V5\AdminArtistController@deleteArticle');

		Route::post('subastas/update/image', 'subasta\AdminSubastaGenericController@updateImage')->name('subastas.update.image');
		Route::resource('subastas', 'subasta\AdminSubastaGenericController');

		Route::get('stock', 'subasta\AdminStockController@index')->name('subastas.stock.index');;
		Route::get('stock/printExcel', 'subasta\AdminStockController@excel')->name('subastas.stock.printExcel');

		Route::post('subastas_concursales/update/image', 'subasta\AdminSubastaConcursalController@updateImage')->name('subastas_concursales.update.image');
		Route::resource('subastas_concursales', 'subasta\AdminSubastaConcursalController')->parameters([
			'subastas_concursales' => 'subasta'
		]);

		Route::resource('subastas.sesiones', 'subasta\AdminAucSessionsController');

		Route::post('subastas/{cod_sub}/pujas/delete-selection', 'subasta\AdminPujasController@deleteSelection')->name('subastas.pujas.delete_selection');

		Route::get('subastas/{cod_sub}/lotes/select2list/', 'subasta\AdminLotController@getSelect2List')->name('subastas.lotes.select2');
		Route::post('subastas/lote/addFeature', 'subasta\AdminLotController@addFeature');

		Route::get('features/{idFeature}/{idFeatureValue}', 'subasta\AdminLotController@createOrEditMultilanguageFeature')->name('multilanguage_features');
		Route::post('features', 'subasta\AdminLotController@storeMultilanguageFeature')->name('multilanguage_features.post');


		Route::get('subastas/lotes/printPdf/{codSub}', 'subasta\AdminLotController@pdfExhibition')->name('subastas.lotes.printPdf');
		Route::get('subastas/lotes/printExcel/{codSub}', 'subasta\AdminLotController@excelExhibition')->name('subastas.lotes.printExcel');
		Route::get('subastas/{cod_sub}/lotes/order', 'subasta\AdminLotController@getOrder')->name('subastas.lotes.order_edit');
		Route::post('subastas/{cod_sub}/lotes/order', 'subasta\AdminLotController@saveOrder')->name('subastas.lotes.order_store');
		Route::post('subastas/lotes/clonelot/{lotRef}', 'subasta\AdminLotController@cloneLot')->name('subastas.lotes.cloneLot');


		Route::get('subastas/lotes/order/destacados', 'subasta\AdminLotController@getOrderDestacada')->name('subastas.lotes.order_destacadas_edit');
		Route::post('subastas/lotes/order/destacados', 'subasta\AdminLotController@saveOrderDestacada')->name('subastas.lotes.order_destacadas_store');

		//Route::get('subastas/{cod_sub}/lotes/{ref_asigl0}/publish-nft', 'subasta\AdminLotController@publishNft')->name('subastas.lotes.publish_nft');
		Route::get('subastas/{cod_sub}/lotes/{ref_asigl0}/unpublish-nft', 'subasta\AdminLotController@unpublishNft')->name('subastas.lotes.unpublish_nft');

		Route::get('nfts/', 'subasta\AdminNftController@index')->name('nft.index');
		Route::get('nfts/{numhces}/{linhces}/file', 'subasta\AdminNftController@showFile')->name('nft.show.file');
		Route::post('nfts/mint', 'subasta\AdminNftController@mint')->name('nft.mint');
		Route::post('nfts/transfer', 'subasta\AdminNftController@transfer')->name('nft.transfer');
		Route::post('nfts/state', 'subasta\AdminNftController@state')->name('nft.state');

		Route::post('subastas/{cod_sub}/lotes/delete-selection', 'subasta\AdminLotController@deleteSelection')->name('subastas.lotes.delete_selection');
		Route::post('subastas/{cod_sub}/lotes/stockRemove-selection', 'subasta\AdminLotController@stockRemoveSelection')->name('subastas.lotes.stockRemove_selection');
		Route::post('subastas/{cod_sub}/lotes/setToSellSelection', 'subasta\AdminLotController@setToSellSelection')->name('subastas.lotes.setToSellSelection');

		Route::post('subastas/{cod_sub}/lotes/{ref_asigl0}/export/{service?}', 'subasta\AdminLotController@export')->name('subastas.lotes.export');
		Route::post('subastas/{cod_sub}/lotes/export/{service?}', 'subasta\AdminLotController@multipleExport')->name('subastas.lotes.multiple_export');

		Route::resource('subastas.lotes', 'subasta\AdminLotController')->except(['show'])->parameters([
			'subastas' => 'cod_sub',
		]);

		Route::get('subastas_concursales/{cod_sub}/lotes_concursales/order', 'subasta\AdminLoteConcursalController@getOrder')->name('subastas_concursales.lotes_concursales.order_edit');
		Route::post('subastas_concursales/{cod_sub}/lotes_concursales/order', 'subasta\AdminLoteConcursalController@saveOrder')->name('subastas_concursales.lotes_concursales.order_store');
		Route::resource('subastas_concursales.lotes_concursales', 'subasta\AdminLoteConcursalController')->except(['show'])->parameters([
			'subastas_concursales' => 'cod_sub',
			'lotes_concursales' => 'lote'
		]);

		Route::get('subastas/{num_hces1}/{lin_hces1}/files/create', 'subasta\AdminLotFilesController@create')->name('subastas.lotes.files.create');
		Route::post('subastas/{num_hces1}/{lin_hces1}/files', 'subasta\AdminLotFilesController@store')->name('subastas.lotes.files.store');
		Route::get('subastas/lotes/files/{fgHces1File}/', 'subasta\AdminLotFilesController@show')->name('subastas.lotes.files.show');
		Route::get('subastas/lotes/files/{fgHces1File}/edit', 'subasta\AdminLotFilesController@edit')->name('subastas.lotes.files.edit');
		Route::post('subastas/lotes/files/order', 'subasta\AdminLotFilesController@updateOrder')->name('subastas.lotes.files.update_order');
		Route::post('subastas/lotes/files/update-selection', 'subasta\AdminLotFilesController@updateSelection')->name('subastas.lotes.files.update-selection');
		Route::post('subastas/lotes/files/delete-selection', 'subasta\AdminLotFilesController@deleteSelection')->name('subastas.lotes.files.delete-selection');
		Route::post('subastas/lotes/files/{fgHces1File}', 'subasta\AdminLotFilesController@update')->name('subastas.lotes.files.update');
		Route::post('subastas/lotes/files/{fgHces1File}/status', 'subasta\AdminLotFilesController@status')->name('subastas.lotes.files.status');
		Route::delete('subastas/lotes/files/{fgHces1File}', 'subasta\AdminLotFilesController@destroy')->name('subastas.lotes.files.destroy');

		Route::get('subasta-conditions', 'subasta\AdminSubastaConditionsController@index')->name('subasta_conditions.index');
		Route::get('subasta-conditions/download', 'subasta\AdminSubastaConditionsController@download')->name('subasta_conditions.download');

		Route::post('bi/ajax', 'bi\AdminBiController@lotsInfo')->name('bi.reload');
		Route::post('bi/allcategories', 'bi\AdminBiController@lotsAwardForCategory');
		Route::post('bi/auction-modal-info', 'bi\AdminBiController@getAuctionInfo');
		Route::resource('bi', 'bi\AdminBiController')->only(['index']);

		Route::get('bi/cedentes/{cod_cli}', 'bi\AdminBiCedentesController@show')->name('bi_cedentes.show');
		Route::get('bi/cedentes/{cod_cli}/json', 'bi\AdminBiCedentesController@getShow')->name('bi_cedentes.show_data');
		Route::get('bi/cedentes', 'bi\AdminBiCedentesController@index')->name('bi_cedentes.index');

		Route::get('bi/reports/','bi\AdminBiReports@index' )->name('bi_reports');
		Route::get('bi/report/{report}','bi\AdminBiReports@report' )->name('bi_report');

		Route::get('providers/list', 'facturacion\AdminProviderController@getSelectProviders')->name('provider.list');
		Route::resource('providers', 'facturacion\AdminProviderController')->except(['show']);

		Route::resource('bills', 'facturacion\AdminBillController')->except(['show']);
		Route::resource('pedidos', 'facturacion\AdminPedidosController')->except(['show']);
		Route::post('pedidos/importeBasePedido', 'facturacion\AdminPedidosController@importeBasePedido');

		Route::get('articulos', 'AdminArticlesController@index')->name('articles.index');
		Route::get('articles/order', 'AdminArticlesController@getOrder')->name('articles.order_edit');
		Route::post('articles/order', 'AdminArticlesController@saveOrder')->name('articles.order_store');

		Route::get('/genericImport', 'CmsConfigController@getImportFile');
		Route::post('/genericImport', 'CmsConfigController@ImportFile');

		#ver imagenes de lotes de la subasta para comprobar que no faltam
		Route::get("listado_imagenes_subasta/{cod_sub}", 'subasta\AdminLotController@listadoImagenesSubasta')->name('listado_imagenes_subasta');

		Route::resource('emails', 'contenido\AdminEmailsController')->only(['index', 'edit', 'update']);

		Route::post('admin-config', [AdminConfigController::class, 'saveConfigurationSession']);

		Route::get('subastas/load_catalog_invaluable/{codsub}/{reference?}', 'subasta\AdminSubastaGenericController@loadInvaluableCatalog')->name('loadCatalogInvaluable');

		Route::get('subastas/load_lot_invaluable/{codsub}/{reference}/{ref}', 'subasta\AdminLotController@loadInvaluableLot')->name('loadLotInvaluable');

		Route::get('jobs', 'configuracion\AdminJobsController@index')->name('admin.jobs.index');
		Route::get('jobs/pending/{id}', 'configuracion\AdminJobsController@showPendingJob')->name('admin.jobs.pending');
		Route::get('jobs/failed/{id}', 'configuracion\AdminJobsController@showFailedJob')->name('admin.jobs.failed');
		Route::post('jobs/failed/{id}', 'configuracion\AdminJobsController@reesendFailedJob')->name('admin.jobs.failed_retry');

		Route::get('cache', 'configuracion\AdminCacheController@index')->name('admin.cache.index');
		Route::post('cache', 'configuracion\AdminCacheController@action')->name('admin.cache.action');

	});


	Route::post('/sliders/upload', 'AdminSlidersController@uploadFile');
	Route::post('/sliders/save', 'AdminSlidersController@save');
	Route::post('/sliders/delete', 'AdminSlidersController@deleteFile');
	Route::post('/config/save', 'AdminConfigController@save');
	Route::post('/content/save', 'ContentController@savedPage');
	Route::post('/traducciones/save', 'TraduccionesController@SavedTrans');
	Route::post('/seo-categories/edit', 'SeoCategoriesController@SavedCategSeo');
	Route::post('/seo-familias-sessiones/edit', 'SeoFamiliasSessionesController@SavedFamilySessionsSeo');
	Route::post('/auc-index-menu/save', 'AucIndexMenuController@save');
	Route::post('/traducciones/new', 'TraduccionesController@NewTrans');
	Route::post('/bloque/edit', 'BloqueConfigController@EditBloque');
	Route::post('/auc-index/edit', 'AucIndexController@EditAuIndex');
	Route::post('/banner/edit', 'BannerController@EditBanner');
	Route::post('/resources/edit', 'ResourceController@EditResources');
	Route::post('/resources/delete', 'ResourceController@DeleteResource');
	Route::post('/category-blog/edit', 'BlogController@EditBlogCategory');
	Route::post('/blog/edit', 'BlogController@EditBlog');






	Route::group(['middleware' => ['web']], function () {

		Route::get('/login', 'AdminUserController@login');
		Route::post('/login', 'AdminUserController@login_post');
		Route::get('/logout', 'AdminUserController@logout');
	});

	#ruta a la cual se debe poder acceder desde fuera del admin y sin estar logeado ya que la llamada la hace subalia
	Route::post('orders/subalia_send_ws', 'subasta\AdminOrderController@subalia_send_ws');
});
