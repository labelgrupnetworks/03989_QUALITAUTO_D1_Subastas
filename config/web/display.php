<?php

return [
	'add_calendar_feature' => 0,
	'awarded' => 1, //Mostrar o no si un lote ha sido adjudicado en listado de lotes.
	'default_theme' => '', //Theme por defecto. Valores posibles: v1, v2.
	'default_lang' => 'en', //creo que algún dia lo uso Tauler. @todo - Eliminar
	'deleteBids' => 1, //Permitir o no eliminar pujas de un lote en la subasta en tiempo real cuando se quiere reabrir un lote.
	'exchange' => 1, //Habilita el mostrar divisas en la web.
	'gridLots' => false, //Habilita nuevo grid de lotes en la web, valores posibles: false, "new".
	'img_lot' => '/img', //Ruta de las imágenes de los lotes @todo - Esta configurado en app.php
	'new_image_folders' => true, //Habilita el uso de nuevas carpetas para las imágenes de los lotes. @todo - Todos los tienen en 1. Eliminar lógica antigua
	'max_bids' => 4, //Número de pujas mostradas en historico.
	'pagination_bills' => 8, //Número máximo de facturas mostradas en la paginación.
	'transferCount' => '', //Nº de cuenta para las transferencias, solamente se utiliza en traducciones.
	'tr_show_adjudicaciones' => true, //Mostramos bloque de buscador de adjudicaciones en tiempo real
	'tr_show_automatic_auction' => true, //Tiempo real subasta automatica
	'tr_show_buscador' => true, //Mostramos bloque de buscador de items en tiempo real
	'tr_show_canel_bid_client' => false, //Tiempo real usuario pueda cancelar pujas
	'tr_show_chat' => true, //Mostramos bloque de chat en subasta tiempo real
	'tr_show_estimate_price' => true, //Mostrar precio estimado en tiempo real
	'tr_show_ordenes_licitacion' => true, //Mostramos bloque de ordenes en tiempo real (solo admin)
	'tr_show_pujas' => true, //Mostramos bloque de pujas en subasta tiempo real
	'tr_show_streaming' => false, //Mostramos bloque de streaming en tiempo real
];
