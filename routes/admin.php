<?php
#region imports
use App\Http\Controllers\admin\AdminArticlesController;
use App\Http\Controllers\admin\AdminConfigController;
use App\Http\Controllers\admin\AdminSlidersController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\b2b\{
	AdminB2BBidsController,
	AdminB2BLotsController,
	AdminB2BUsersController,
	AdminB2BVisibilitiesController,
	AdminB2BAwardsController,
    AdminB2BCompaniesController
};
use App\Http\Controllers\admin\BlogController;
use App\Http\Controllers\admin\BloqueConfigController;
use App\Http\Controllers\admin\configuracion\AdminMesuresController;
use App\Http\Controllers\admin\contenido\AdminEmailsController;
use App\Http\Controllers\admin\contenido\BannerController as ContenidoBannerController;
use App\Http\Controllers\admin\EmailController;
use App\Http\Controllers\admin\subasta\AdminBidsController;
use App\Http\Controllers\admin\subasta\AdminLicitController;
use App\Http\Controllers\admin\subasta\AdminOperadoresController;
use App\Http\Controllers\admin\subasta\AdminOrderController;
use App\Http\Controllers\admin\TraduccionesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
#endregion


# Añadido excepcional ya que no funcionaba el admin

Route::view('/admin', 'admin::pages.home')->name('admin.home');

Route::group(['prefix' => 'admin', 'namespace' => 'admin'], function () {

	Route::group(['middleware' => ['adminAuth', 'SessionTimeout:' . Config::get('app.admin_session_timeout')]], function () {

		Route::view('/', 'admin::pages.home');

		Route::get('/bloque', [BloqueConfigController::class, 'index']);
		Route::get('/bloque/name/{id?}', [BloqueConfigController::class, 'SeeBloque']);
		Route::post('/bloque/edit', [BloqueConfigController::class, 'EditBloque']);

		Route::get('/traducciones/{head}/{lang}', [TraduccionesController::class, 'index']);
		Route::get('/traducciones', [TraduccionesController::class, 'getTraducciones']);
		Route::get('/traducciones/search', [TraduccionesController::class, 'search']);
		Route::post('/traducciones/save', [TraduccionesController::class, 'SavedTrans']);
		Route::post('/traducciones/new', [TraduccionesController::class, 'NewTrans']);

		Route::get('/content', [App\Http\Controllers\admin\ContentController::class, 'index'])->name('content.index');
		Route::get('/content/name/{id}', [App\Http\Controllers\admin\ContentController::class, 'getPage'])->name('content.page');
		Route::post('/content/save', [App\Http\Controllers\admin\ContentController::class, 'savedPage']);

		Route::get('/email_log', [App\Http\Controllers\admin\AdminEmailsController::class, 'showLog'])->name('adminemails.showlog');

		Route::get('/blog-admin', [BlogController::class, 'getBlogs']);
		Route::get('/blog-admin/name/{id?}', [BlogController::class, 'index']);
		Route::get('/category-blog', [BlogController::class, 'getCategoryBlog']);
		Route::get('/category-blog/name/{id?}', [BlogController::class, 'seeCategoryBlog']);
		Route::post('/category-blog/edit', [BlogController::class, 'EditBlogCategory']);
		Route::post('/blog/edit', [BlogController::class, 'EditBlog']);

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
				Route::get('/{lang?}/', 'AdminFaqController@index')->name('admin.faqs.index');
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
			Route::get('/', [ContenidoBannerController::class, 'index'])->name('newbanner.index');
			Route::get('/download', [ContenidoBannerController::class, 'download']);
			Route::get('/ubicacionhome', [ContenidoBannerController::class, 'ubicacionHome']);
			Route::get('/nuevo', [ContenidoBannerController::class, 'nuevo']);
			Route::post('/nuevo_run', [ContenidoBannerController::class, 'nuevo_run']);
			Route::get('/editar/{id}', [ContenidoBannerController::class, 'editar'])->name('newbanner.edit');
			Route::get('/borrar/{id}', [ContenidoBannerController::class, 'borrar']);
			Route::post('/activar', [ContenidoBannerController::class, 'activar']);
			Route::post('/editar_run', [ContenidoBannerController::class, 'editar_run']);
			Route::post('/listaItemsBloque', [ContenidoBannerController::class, 'listaItemsBloque']);
			Route::post('/nuevoItemBloque', [ContenidoBannerController::class, 'nuevoItemBloque']);
			Route::post('/editaItemBloque', [ContenidoBannerController::class, 'editaItemBloque']);
			Route::post('/guardaItemBloque', [ContenidoBannerController::class, 'guardaItemBloque']);
			Route::post('/guardaItemViewBloque', [ContenidoBannerController::class, 'guardaItemViewBloque']);
			Route::post('/borraItemBloque', [ContenidoBannerController::class, 'borraItemBloque']);
			Route::post('/estadoItemBloque', [ContenidoBannerController::class, 'estadoItemBloque']);
			Route::post('/vistaPrevia', [ContenidoBannerController::class, 'vistaPrevia']);
			Route::post('/ordenaBloque', [ContenidoBannerController::class, 'ordenaBloque']);
			Route::post('/orderbanner', [ContenidoBannerController::class, 'orderBanner']);
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

		Route::get('/email', [EmailController::class, 'index']);
		Route::get('/email/ver/{cod_email}', [EmailController::class, 'getEmail']);

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
			Route::get('/', 'contenido\AdminCalendarController@index')->name('calendar.index');
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
			Route::post('/borrarPuja', 'subasta\SubastaController@borrarPuja'); //se utiliza
			Route::get('/list', 'subasta\SubastaController@getSelectSubastas');
			Route::get('/select2list', 'subasta\SubastaController@getSelect2List')->name('subastas.select2');
		});

		Route::group(['prefix' => 'lote'], function () {

			Route::post('/borrarImagenLote', 'subasta\SubastaController@borrarImagenLote'); //se utiliza
			Route::get('/file/{id}', 'subasta\SubastaController@lotFile')->name('admin.lote.getimport'); //para subir excel
			Route::post('/fileImport/{type}', 'subasta\SubastaController@lotFileImport'); //guardar excel
			Route::post('/excelImg', 'subasta\SubastaController@createExcelImage'); //excel?
			Route::post('/addImg/', 'subasta\SubastaController@addImage')->name('addLotImage'); //excel??
			Route::get('/list', 'subasta\SubastaController@getSelectLotes');
			Route::get('/listFondoGaleria', 'subasta\SubastaController@getSelectLotesFondoGaleria')->name('lotListFondoGaleria');
			Route::get('/export/{cod_sub}', 'subasta\SubastaController@export')->name('lote.export'); //se utiliza para descargar excel
			Route::post('/send_end_lot_ws', 'subasta\SubastaController@send_end_lot_ws');
		});
		Route::group(['prefix' => 'sesion'], function () {
			Route::post('/addfile', 'subasta\AdminAucSessionsFilesController@store'); //revisar si es necesario
			Route::post('/deletefile', 'subasta\AdminAucSessionsFilesController@destroy'); //revisar si es necesario
		});

		Route::group(['prefix' => 'orders'], function () {
			Route::get('/excel/{idAuction}', 'subasta\AdminOrderController@excel')->name('orders.excel');
			Route::post('/import/{idAuction}', 'subasta\AdminOrderController@import')->name('orders.import');
			Route::get('/export/{idAuction}', 'subasta\AdminOrderController@export')->name('orders.export');
			Route::post('/delete-selection/{idAuction}', 'subasta\AdminOrderController@deleteSelection')->name('orders.delete_selection');
			Route::post('/send_ws', 'subasta\AdminOrderController@send_ws');
			Route::post('/delete-with-filters', 'subasta\AdminOrderController@destroyWithFilters')->name('orders.destroy_with_filters');
			Route::post('/delete-selection', 'subasta\AdminOrderController@destroySelections')->name('orders.destroy_selections');
			Route::post('/add-bidding-agent', [AdminOrderController::class, 'addBiddingAgent'])->name('orders.add_bidding_agent');
		});
		Route::resource('orders', 'subasta\AdminOrderController')->except(['show'])->parameters(['orders' => 'idAuction']);

		Route::group(['prefix' => 'bids'], function () {
			Route::get('/', [AdminBidsController::class, 'index'])->name('admin.bids.index');
		});

		Route::group(['prefix' => 'award'], function () {
			Route::get('/', 'subasta\AdminAwardController@index')->name('award.index');
			Route::get('/show', 'subasta\AdminAwardController@show');
			Route::get('/create/{idAuction?}', 'subasta\AdminAwardController@create')->name('award.create');
			Route::post('/store', 'subasta\AdminAwardController@store');
			Route::get('/edit', 'subasta\AdminAwardController@edit')->name('award.edit');
			Route::post('/update', 'subasta\AdminAwardController@update');
			Route::post('/delete', 'subasta\AdminAwardController@destroy')->name('award.delete');
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
			Route::get('/', [AdminLicitController::class, 'index'])->name('admin.licit.index');
			Route::get('/create', [AdminLicitController::class, 'create'])->name('admin.licit.create');
			Route::post('/store', [AdminLicitController::class, 'store'])->name('admin.licit.store');
			Route::get('/export-licits', [AdminLicitController::class, 'exportLicits'])->name('admin.licit.export');
		});

		Route::group(['prefix' => 'category'], function () {
			Route::get('/', 'subasta\AdminCategoryController@index')->name('category.index');
			Route::get('/edit', 'subasta\AdminCategoryController@edit')->name('category.edit');
			Route::post('/update', 'subasta\AdminCategoryController@update');
			Route::post('/delete', 'subasta\AdminCategoryController@destroy');

			/* Preferencias */
			Route::get('/preferences/download', 'subasta\AdminSubCategoryController@downloadPreferencesExcel');
		});

		Route::group(['prefix' => 'subcategory'], function () {
			Route::get('/', 'subasta\AdminSubCategoryController@index')->name('subcategory.index');
			#Route::get('/show', 'subasta\AdminSubCategoryController@show');
			#Route::get('/create', 'subasta\AdminSubCategoryController@create');
			#Route::post('/store', 'subasta\AdminSubCategoryController@store');
			Route::get('/edit', 'subasta\AdminSubCategoryController@edit')->name('subcategory.edit');
			Route::post('/update', 'subasta\AdminSubCategoryController@update');
			Route::post('/delete', 'subasta\AdminSubCategoryController@destroy');
		});

		Route::get('/subasta/reports', 'subasta\AdminAuctionReportsController@index')->name('subasta.reports.index');
		Route::post('/subasta/reports/generate', 'subasta\AdminAuctionReportsController@generate')->name('subasta.reports.generate');
		Route::get('/subasta/reports/download/{cod_sub}', 'subasta\AdminAuctionReportsController@download')->name('subasta.reports.download');

		Route::get('/subasta/custom-reports/download/{id}', 'subasta\AdminCustomExports@download')->name('subasta.custom-reports.download');

		Route::get('user_newsletter/export/', 'usuario\AdminNewsletterClientController@export')->name('user_newsletter.export');
		Route::get('user_newsletter/catalog', 'usuario\AdminNewsletterClientController@showCatalogSuscriptors')->name('user_newsletter.catalog');
		Route::resource('user_newsletter', 'usuario\AdminNewsletterClientController')->only(['index', 'show', 'destroy']);

		Route::get('newsletter/export/', 'usuario\AdminNewsletterController@export')->name('newsletter.export');
		Route::resource('newsletter', 'usuario\AdminNewsletterController');

		Route::get('clientes/data', 'usuario\AdminClienteController@data')->name('admin.clientes.data');

		Route::post('clientes/delete-with-filters', 'usuario\AdminClienteController@destroyWithFilters')->name('clientes.destroy_with_filters');
		Route::post('clientes/delete-selection', 'usuario\AdminClienteController@destroySelections')->name('clientes.destroy_selections');
		Route::post('clientes/update-with-filters', 'usuario\AdminClienteController@updateWithFilters')->name('clientes.update_with_filters');
		Route::post('clientes/update-selection', 'usuario\AdminClienteController@updateSelections')->name('clientes.update_selections');
		Route::post('clientes/baja-tmp-cli', 'usuario\AdminClienteController@modificarBajaTemporal');
		Route::post('clientes/export', 'usuario\AdminClienteController@export')->name('clientes.export');
		Route::get('clientes/{cod_cli}/representados', 'usuario\AdminRepresentadosController@index')->name('clientes.representados');

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

		Route::post('subastas/update-with-filters', 'subasta\AdminSubastaGenericController@updateWithFilters')->name('subastas.update_with_filters');
		Route::post('subastas/update-selection', 'subasta\AdminSubastaGenericController@updateSelections')->name('subastas.update_selections');
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

		Route::post('subastas/bids/delete-with-filters', 'subasta\AdminPujasController@destroyWithFilters')->name('subastas.pujas.destroy_with_filters');
		Route::post('subastas/bids/delete-selection', 'subasta\AdminPujasController@destroySelections')->name('subastas.pujas.destroy_selections');

		Route::post('subastas/lotes/delete-with-filters', 'subasta\AdminLotController@destroyWithFilters')->name('subastas.lotes.destroy_with_filters');
		Route::post('subastas/lotes/delete-selection', 'subasta\AdminLotController@destroySelections')->name('subastas.lotes.destroy_selections');
		Route::post('subastas/lotes/update-with-filters', 'subasta\AdminLotController@updateWithFilters')->name('subastas.lotes.update_with_filters');
		Route::post('subastas/lotes/update-selection', 'subasta\AdminLotController@updateSelections')->name('subastas.lotes.update_selections');
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

		Route::post('operadores', [AdminOperadoresController::class, 'store'])->name('subastas.operadores.store');
		Route::get('subastas/{cod_sub}/operadores/print-bid-paddles', [AdminOperadoresController::class, 'printBidPaddles'])->name('subastas.operadores.print_bid_paddles');
		Route::get('subastas/{cod_sub}/operadores/print-bid-paddles-by-reference', [AdminOperadoresController::class, 'printBidPaddlesByReference'])->name('subastas.operadores.print_bid_paddles_by_reference');
		Route::get('subastas/{cod_sub}/operadores/print-bid-paddles-by-operator', [AdminOperadoresController::class, 'printBidPaddlesByOperator'])->name('subastas.operadores.print_bid_paddles_by_operator');
		Route::get('subastas/{cod_sub}/operadores', [AdminOperadoresController::class, 'index'])->name('subastas.operadores.index');

		Route::get('subastas/{num_hces1}/{lin_hces1}/files/create', 'subasta\AdminLotFilesController@create')->name('subastas.lotes.files.create');
		Route::post('subastas/{num_hces1}/{lin_hces1}/files', 'subasta\AdminLotFilesController@store')->name('subastas.lotes.files.store');
		Route::get('subastas/lotes/files/{fgHces1File}/', 'subasta\AdminLotFilesController@show')->name('subastas.lotes.files.show');
		Route::get('subastas/lotes/files/{fgHces1File}/edit', 'subasta\AdminLotFilesController@edit')->name('subastas.lotes.files.edit');
		Route::post('subastas/lotes/files/order', 'subasta\AdminLotFilesController@updateOrder')->name('subastas.lotes.files.update_order');
		Route::post('subastas/lotes/files/update-selection', 'subasta\AdminLotFilesController@updateSelection')->name('subastas.lotes.files.update-selection');
		Route::post('subastas/lotes/files/delete-selection', 'subasta\AdminLotFilesController@deleteSelection')->name('subastas.lotes.files.delete-selection');
		Route::post('subastas/lotes/files/{fgHces1File}', 'subasta\AdminLotFilesController@update')->name('subastas.lotes.files.update');
		Route::post('subastas/lotes/files/{fgHces1File}/status', 'subasta\AdminLotFilesController@status')->name('subastas.lotes.files.status');
		Route::delete('subastas/lotes/files/oldDestroy', 'subasta\AdminLotFilesController@oldDestroy')->name('subastas.lotes.files.oldDestroy');
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

		Route::get('bi/reports/', 'bi\AdminBiReports@index')->name('bi_reports');
		Route::get('bi/report/{report}', 'bi\AdminBiReports@report')->name('bi_report');

		Route::get('providers/list', 'facturacion\AdminProviderController@getSelectProviders')->name('provider.list');
		Route::resource('providers', 'facturacion\AdminProviderController')->except(['show']);

		Route::resource('bills', 'facturacion\AdminBillController')->except(['show']);
		Route::resource('pedidos', 'facturacion\AdminPedidosController')->except(['show']);
		Route::post('pedidos/importeBasePedido', 'facturacion\AdminPedidosController@importeBasePedido');

		Route::get('articulos', [AdminArticlesController::class, 'index'])->name('articles.index');
		Route::get('articles/order', [AdminArticlesController::class, 'getOrder'])->name('articles.order_edit');
		Route::post('articles/order', [AdminArticlesController::class, 'saveOrder'])->name('articles.order_store');

		#ver imagenes de lotes de la subasta para comprobar que no faltam
		Route::get("listado_imagenes_subasta/{cod_sub}", 'subasta\AdminLotController@listadoImagenesSubasta')->name('listado_imagenes_subasta');

		Route::post('emails/send', [AdminEmailsController::class, 'sendEmail'])->name('admin.emails.send');
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

		Route::get('disk-status', 'configuracion\AdminDiskStatusController@index')->name('admin.disk-status.index');
		Route::get('disk-status/folder', 'configuracion\AdminDiskStatusController@getDirectoryInPath')->name('admin.disk-status.folder');

		Route::get('thumbs', 'configuracion\AdminThumbsController@index')->name('admin.thumbs.index');
		Route::post('thumbs/lots', 'configuracion\AdminThumbsController@getLots')->name('admin.thumbs.lots');
		Route::post('thumbs/generate', 'configuracion\AdminThumbsController@generateThumbs')->name('admin.thumbs.generate');

		Route::get('mesures', [AdminMesuresController::class, 'index'])->name('admin.mesures.index');
		Route::get('mesures-json', [AdminMesuresController::class, 'analizeLogFile'])->name('admin.mesures.index-json');


		Route::group(['prefix' => 'test-auction'], function () {
			Route::get('/', 'configuracion\AdminTestAuctions@index')->name('admin.test-auctions.index');
			Route::get('/create-auction/{idauction}', 'configuracion\AdminTestAuctions@createAuction')->name('admin.test-auctions.create');
			Route::get('/reset-auction/{idauction}', 'configuracion\AdminTestAuctions@resetAuction')->name('admin.test-auctions.reset');
			Route::get('/create-lots/{idauction}', 'configuracion\AdminTestAuctions@createLots')->name('admin.test-auctions.create-lots');
			Route::get('/reset-lots/{idauction}', 'configuracion\AdminTestAuctions@resetLots')->name('admin.test-auctions.reset-lots');
		});

		Route::get('/contenido/uploads', 'contenido\AdminUploadsController@index')->name('admin.contenido.uploads.index');
		Route::post('/contenido/uploads', 'contenido\AdminUploadsController@upload')->name('admin.contenido.uploads.upload');
		Route::delete('/contenido/uploads/delete/{fileName}', 'contenido\AdminUploadsController@delete')->name('admin.contenido.uploads.delete');
		Route::put('/contenido/uploads/update/{fileName}', 'contenido\AdminUploadsController@update')->name('admin.contenido.uploads.update');

		Route::group(['prefix' => 'b2b'], function () {

			Route::get('/companies', [AdminB2BCompaniesController::class, 'index'])->name('admin.b2b.companies');
			Route::get('/companies/create', [AdminB2BCompaniesController::class, 'create'])->name('admin.b2b.companies.create');
			Route::post('/companies', [AdminB2BCompaniesController::class, 'store'])->name('admin.b2b.companies.store');
			Route::get('/companies/{idCli}', [AdminB2BCompaniesController::class, 'edit'])->name('admin.b2b.companies.edit');
			Route::put('/companies/{idCli}', [AdminB2BCompaniesController::class, 'update'])->name('admin.b2b.companies.update');
			Route::put('/companies/{idCli}/status', [AdminB2BCompaniesController::class, 'status'])->name('admin.b2b.companies.status');

			Route::get('/users', [AdminB2BUsersController::class, 'index'])->name('admin.b2b.users');
			Route::get('/users/create', [AdminB2BUsersController::class, 'create'])->name('admin.b2b.users.create');
			Route::post('/users', [AdminB2BUsersController::class, 'store'])->name('admin.b2b.users.store');
			Route::get('/users/{id}', [AdminB2BUsersController::class, 'edit'])->name('admin.b2b.users.edit');
			Route::put('/users/{id}', [AdminB2BUsersController::class, 'update'])->name('admin.b2b.users.update');
			Route::post('/users/import', [AdminB2BUsersController::class, 'import'])->name('admin.b2b.users.import');
			Route::post('/users/notify', [AdminB2BUsersController::class, 'notify'])->name('admin.b2b.users.notify');
			Route::post('/users/notify-selection', [AdminB2BUsersController::class, 'notifySelection'])->name('admin.b2b.users.notify-selection');
			Route::delete('/users/all', [AdminB2BUsersController::class, 'destroyAll'])->name('admin.b2b.users.destroy-all');
			Route::delete('/users/selection', [AdminB2BUsersController::class, 'destroySelection'])->name('admin.b2b.users.destroy-selection');


			Route::get('/lots', [AdminB2BLotsController::class, 'index'])->name('admin.b2b.lots');
			Route::get('/lots/create', [AdminB2BLotsController::class, 'create'])->name('admin.b2b.lots.create');
			Route::post('/lots', [AdminB2BLotsController::class, 'store'])->name('admin.b2b.lots.store');
			Route::get('/lots/{ref_asigl0}', [AdminB2BLotsController::class, 'edit'])->name('admin.b2b.lots.edit');
			Route::put('/lots/{ref_asigl0}', [AdminB2BLotsController::class, 'update'])->name('admin.b2b.lots.update');
			Route::delete('/lots/{ref_asigl0}', [AdminB2BLotsController::class, 'destroy'])->name('admin.b2b.lots.destroy');

			Route::get('/visibility', [AdminB2BVisibilitiesController::class, 'index'])->name('admin.b2b.visibility');
			Route::get('/visibility/create', [AdminB2BVisibilitiesController::class, 'create'])->name('admin.b2b.visibility.create');
			Route::post('/visibility', [AdminB2BVisibilitiesController::class, 'store'])->name('admin.b2b.visibility.store');
			Route::post('/visibility/showOrHideEveryone', [AdminB2BVisibilitiesController::class, 'showOrHideEveryone'])->name('admin.b2b.visibility.showOrHideEveryone');
			Route::get('/visibility/{id}', [AdminB2BVisibilitiesController::class, 'edit'])->name('admin.b2b.visibility.edit');
			Route::put('/visibility/{id}', [AdminB2BVisibilitiesController::class, 'update'])->name('admin.b2b.visibility.update');
			Route::delete('/visibility/{id}', [AdminB2BVisibilitiesController::class, 'destroy'])->name('admin.b2b.visibility.destroy');

			Route::get('/bids', [AdminB2BBidsController::class, 'index'])->name('admin.b2b.bids');
			Route::get('/awards', [AdminB2BAwardsController::class, 'index'])->name('admin.b2b.awards');
		});
	});

	Route::post('/sliders/upload', [AdminSlidersController::class, 'uploadFile']);
	Route::post('/sliders/save', [AdminSlidersController::class, 'save']);
	Route::post('/sliders/delete', [AdminSlidersController::class, 'deleteFile']);

	Route::group(['middleware' => ['web']], function () {
		Route::get('/login', [AdminUserController::class, 'login']);
		Route::post('/login', [AdminUserController::class, 'login_post']);
		Route::get('/logout', [AdminUserController::class, 'logout'])->name('admin.logout');
	});

	#ruta a la cual se debe poder acceder desde fuera del admin y sin estar logeado ya que la llamada la hace subalia
	Route::post('orders/subalia_send_ws', 'subasta\AdminOrderController@subalia_send_ws');
});
