<?php

return [
	'adjudicacion_reserva' => 0,
	'ArtistCode' => 0, //Identifica id de categoria artistas, int
	'assessment_registered' => false, // Permitir o no acceder a tasaciones sin estar registrado. Nadie la esta usando.
	'auction_in_categories' => 'P', //agrupa las usbasta tipo O y tipo P en categorias, no se puede acceder a ellas mediante la subasta, solo por categorias. @todo - nadie lo usa (tauler en ficha pero se puede borrar)
	'auto_licit' => true, //Asigna un licitador de modo automático al entrar en una subasta, @todo - Todos en 1.
	'automatic_blocking_licit_cancel_bids' => 10, //Bloqueo automático de un licitador si realiza más de X cancelaciones de puja durante una subasta de tiempo real
	'catalogo_newsletter' => null, //De manera provisional, en ansorena galeria, necesitamos guardar en newsletter20 la suscripcion a catalogo
	'cd_time' => 3, //En la subasta en tiempo real establece los segundos que pasan entre lote y lote al clickar sobre pasar lote
	'coregistroSubalia' => false, //Activar el coregistro con Subalia
	'DeleteOrdersAnyTime' => false, //Permite borrar ordenes aunque haya pasado el periodo de ordenes
	'distance_to_play_favs' => 3, //Distancia entre el lote actual y los lotes agregados a favoritos para que suene la alarma de lote próximo
	'dontRemoveBooksOrders' => true, //Evita que se borren ordenes de tipo P o tipo O,
	'dummy_bidder' => '9999', //Codigo de licitador del licitador que recibe las pujas rápidas donde no da tiempo a asignar licitador, para la subasta en tiempo real a través solo del gestor
	'enable_general_auctions' => true, //Habilita las subastas generales @todo - se puede eliminar de aqui y de la db.
	'enable_historic_auctions' => true, //Habilita las subastas históricas @todo - se puede eliminar de aqui y de la db.
	'enable_tr_auctions' => true, //Habilita las subastas tr @todo - se puede eliminar de aqui y de la db.
	'escalado_libre' => false, //permite el scalado libre de ordenes
	'escalado_libre_pujas' => 0, //con valor 0 no deja hacer pujas libres, Con valor 1 deja hacer pujas libres antes de la subasta, con valor 2 deja hacer pujas libres en todo momento
	'fecha_noindex_follow' => '10/11/2017', //Fecha de lotes de subastas más antiguas que no se van a noindex follow. @todo - Eliminar.
	'force_correct_price' => true, //Fuerza el precio de salida al precio correcto si este está fuera del escalado
	'not_force_correct_price_tiempo_real' => true, //Hace que en tiempo real no se fuerce el precio de salida
	'force_language_redirect' => false, //Forzamos a que las redirecciones que hacemos para mantener las url de la web antigua sean solo en un idioma, así evitamos que de errores la web si aun no están montados todos los idiomas. valores: 0, es. @todo - Mirar alcala/duran/gutinves y ves si se puede eliminar.
	'gridHistoricoVentas' => false, //Ver el histórico de lotes vendidos en el grid de subastas,
	'group_auction_in_search' => true, //Agrupa la busqueda en la en por subastas, en vez de mostrar los lotes. Realmente solo sirve para listado antiguo de lotes.
	'hide_not_sell_lot_historical' => false, //ocultar los lotes no vendidos en las subastas historicas
	'hide_sold_lot' => false, //Oculta los lotes vendidos, se tiene en cuenta en las queries. @todo - No se esta usando.
	'hide_sold_lots_V' => false, //	ocultar lotes vendidos de las subastas tipo venta. @todo - Todos a 0, eliminar.
	'increase_time_add' => 60, //Cuando se lance un evento de incremento del tiempo restante se sumará los segundos que se indican en el campo
	'increase_time_launch' => 360, //Lanzará el evento de incrementar el tiempo de un lote si el tiempo que queda es menor o igual al valor del campo, el tiempo se mide en segundos
	'keywords_search' => 0, //@todo - No se utiliza, eliminar de las db.
];
