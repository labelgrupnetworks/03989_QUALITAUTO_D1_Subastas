<?php

/*
|--------------------------------------------------------------------------
| Layout - ApiRest
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'apilabel', 'namespace' => 'apilabel'], function () {

	Route::GET('/test', 'test@index');
});

Route::group(['prefix' => 'apilabel', 'namespace' => 'apilabel', 'middleware' => ['apilabel']], function () {

	#LICITADORES
	Route::GET('/bidder', 'BidderController@getBidder');
	Route::POST('/bidder', 'BidderController@postBidder');
	# no se permite modificar paletas, deben eliminarlas y crearlas de nuevo
	//Route::PUT('/payment', 'BidderController@putBidder');
	Route::DELETE('/bidder', 'BidderController@deleteBidder');

	#PAGOS
	Route::GET('/payment', 'PaymentController@getPayment');
	Route::POST('/payment', 'PaymentController@postPayment');
	Route::PUT('/payment', 'PaymentController@putPayment');
	Route::DELETE('/payment', 'PaymentController@deletePayment');

	#BIDS
	Route::GET('/bid', 'BidController@getBid');

	#SUBASTAS
	Route::GET('/auction', 'AuctionController@getAuction');
	Route::POST('/auction', 'AuctionController@postAuction');
	Route::PUT('/auction', 'AuctionController@putAuction');
	Route::DELETE('/auction', 'AuctionController@deleteAuction');

	#ADJUDICADOS
	Route::GET('/award', 'AwardController@getAward');
	Route::POST('/award', 'AwardController@postAward');
	#igual que la llamada POST
	Route::PUT('/award', 'AwardController@postAward');
	Route::DELETE('/award', 'AwardController@deleteAward');



	#CLIENT
	Route::GET('/client', 'ClientController@getClient');
	Route::POST('/client', 'ClientController@postClient');
	Route::PUT('/client', 'ClientController@putClient');
	Route::DELETE('/client', 'ClientController@deleteClient');

	#ORDERS
	Route::GET('/order', 'OrderController@getOrder');
	Route::POST('/order', 'OrderController@postOrder');
	Route::DELETE('/order', 'OrderController@deleteOrder');
	#igual que la llamada POST
	Route::PUT('/order', 'OrderController@postOrder');

	#FEATUREVALUE
	Route::GET('/featurevalue', 'FeatureValueController@getFeatureValue');
	Route::POST('/featurevalue', 'FeatureValueController@postFeatureValue');
	Route::PUT('/featurevalue', 'FeatureValueController@putFeatureValue');
	Route::DELETE('/featurevalue', 'FeatureValueController@deleteFeatureValue');

	#IMG
	Route::POST('/img', 'ImgController@postImg');
	Route::DELETE('/img', 'ImgController@deleteImg');

	#LOT
	Route::GET('/lot', 'LotController@getLot');
	Route::POST('/lot', 'LotController@postLot');
	Route::PUT('/lot', 'LotController@putLot');
	Route::DELETE('/lot', 'LotController@deleteLot');


	#CATEGORY
	Route::GET('/category', 'CategoryController@getCategory');
	Route::POST('/category', 'CategoryController@postCategory');
	Route::PUT('/category', 'CategoryController@putCategory');
	Route::DELETE('/category', 'CategoryController@deleteCategory');

	#SUBCATEGORY
	Route::GET('/subcategory', 'SubCategoryController@getSubCategory');
	Route::POST('/subcategory', 'SubCategoryController@postSubCategory');
	Route::PUT('/subcategory', 'SubCategoryController@putSubCategory');
	Route::DELETE('/subcategory', 'SubCategoryController@deleteSubCategory');

	#SUBCATEGORY
	Route::GET('/deposit', 'DepositController@getDeposit');
	Route::POST('/deposit', 'DepositController@postDeposit');
	Route::PUT('/deposit', 'DepositController@putDeposit');
	Route::DELETE('/deposit', 'DepositController@deleteDeposit');

	#INVALUABLE
		#put y post hacen lo mismo, crear y editar
	Route::POST('/invaluable_catalog', 'InvaluableController@catalog');
	Route::PUT('/invaluable_catalog', 'InvaluableController@catalog');
	Route::POST('/invaluable_lot', 'InvaluableController@lot');
	Route::PUT('/invaluable_lot', 'InvaluableController@lot');
	Route::DELETE('/invaluable_lot', 'InvaluableController@deleteLot');
});
