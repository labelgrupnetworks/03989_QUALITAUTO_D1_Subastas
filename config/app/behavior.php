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
	'order_by_filter' => 'precio_salida', //@todo - grid antiguo.
	'paginacion_grid_lotes' => 1,
	'pujas_enfirme' => false,
	'pujas_maximas_mostradas' => -1, //@todo - todos tienen -1
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


	/*
    |--------------------------------------------------------------------------
    |  Legacy / Pendiente de eliminar
    |--------------------------------------------------------------------------
    */
	'assessment_registered' => false,
	'auction_in_categories' => 'P', //@todo - nadie lo usa (tauler en ficha pero se puede borrar)
	'enable_general_auctions' => true, //@todo - se puede eliminar de aqui y de la db.
	'enable_historic_auctions' => true, //@todo - se puede eliminar de aqui y de la db.
	'enable_tr_auctions' => true, //@todo - se puede eliminar de aqui y de la db.
	'fecha_noindex_follow' => '10/11/2017', //@todo - Eliminar.
	'hide_sold_lot' => false, //@todo - No se esta usando.
	'hide_sold_lots_V' => false, //@todo - Todos a 0, eliminar.
	'keywords_search' => 0, //@todo - No se utiliza, eliminar de las db.
	'search_lots_cerrados' => false, //@todo - No he encontrado referencias en el códgio.
	'force_language_redirect' => false, //@todo - Mirar alcala/duran/gutinves y ves si se puede eliminar.
	'email_bid_confirmed' => '', //Antiguo email de confirmacion de puja
];
