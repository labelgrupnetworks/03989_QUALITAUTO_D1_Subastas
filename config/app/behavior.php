<?php

return [
	'adjudicacion_reserva' => 0,
	'auto_licit' => true,
	'dummy_bidder' => '9999',
	'ArtistCode' => 0,
	'automatic_blocking_licit_cancel_bids' => 10,
	'cd_time' => 3,
	'DeleteOrdersAnyTime' => false,
	'distance_to_play_favs' => 3,
	'dontRemoveBooksOrders' => true,
	'escalado_libre' => false,
	'escalado_libre_pujas' => 0,
	'force_correct_price' => true,
	'not_force_correct_price_tiempo_real' => true,
	'gridHistoricoVentas' => false,
	'group_auction_in_search' => true,
	'hide_not_sell_lot_historical' => false,
	'increase_time_add' => 60,
	'increase_time_launch' => 360,
	'loteFavoritoProximo' => 0,
	'maxium_time_increment' => 0,
	'max_orders_and_bids_ries_cli' => false,
	'max_orders_ries_cli' => false,
	'not_force_correct_price_tiempo_real' => true,
	'notice_over_bid' => false,
	'number_bids_lotlist' => false,
	'paginacion_grid_lotes' => 1,
	'pujas_enfirme' => false,
	'restrictVisibility' => false,
	'search_engine' => false,
	'search_multiple_words' => true,
	'send_email_cancel_bid' => 2,
	'send_email_lot_increment_bid_to_all_users_with_deposit' => false,
	'send_valid_deposit_notification' => false,
	'default_minuts_pause' => 5,
	'search_fields' => '', //Utilizado por Alcala para campos a buscar. @todo grid antiguo
	'search_fields_no_lang' => '', //Utilizado por Alcala para campos a buscar. @todo grid antiguo
	'notifyIfYouNotWinner' => false, //Notificar si la orden no supera la maxima
	'shoppingCart' => null, //Carrito de la compra en las subastas V
	'date_regenerate_image' => null, //fecha en formato Y-m-d, Si una imagen es anterior a esta fecha se regenerara
	'default_order' => 'ref', // Ordern por defecto para el listado de lotes
	'permanent_auction' => false, //Mostrar lotes en listado de permanentes solamente si ha iciado la fecha del lote
	'mail_prop_online' => false, //Enviar mail a propietario al adjudicar lote online
	'hide_return_lot' => false, //No mostrar lotes retirados y devueltos en grid
	'buy_historic' => false, // Permitir la compra de lotes históricos
	'fecha_noindex_follow' => '10/11/2017', //@todo - Eliminar de Empty y bases de datos
	'goGridIfOnlyOneAuction' => false, //Ir a grid de lotes si solo hay una subasta,
	'show_only_not_awarded' => false, // Cuando el filtro de no vendidos está activo, no mostrar devueltos o retirados
	'diferenciarOrdenTelefonicaWeb' => false, // Se marcará la orden telefonica web como una X en vez de una T
	'trAuctionConditions' => false, // Mostrar frase de condiciones en TR y popup al pujar
	'scaleFromPrice' => false, // Escalado en base al precio inicial del lote @todo - No lo tiene nadie, pero vale la pena mantener la lógica.
	'uniqueArtCategory' => '', // indica que solo hay una única categoria en articulos, por lo que los enlaces deben ir siempre a esa
	'getDummyApiBid' => false, // La llamada getBids de la API devuelve todas las pujas, incluso las del licitador 9999



	/*
    |--------------------------------------------------------------------------
    |  Legacy / Pendiente de eliminar
    |--------------------------------------------------------------------------
    */
	'order_by_filter' => 'precio_salida', //@todo - grid antiguo.
	'pujas_maximas_mostradas' => -1, //@todo - todos tienen -1
	'force_language_redirect' => false, //@todo - Mirar alcala/duran/gutinvest y ves si se puede eliminar.
	'email_bid_confirmed' => '', //Antiguo email de confirmacion de puja. Alcala, gutinvest y soporte.
];
