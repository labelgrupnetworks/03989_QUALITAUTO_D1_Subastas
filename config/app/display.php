<?php

return [
	'add_calendar_feature' => 0,
	'awarded' => 1,
	'default_theme' => '',
	'default_lang' => 'en', //@todo - Eliminar
	'deleteBids' => 1,
	'exchange' => 1,
	'gridLots' => false,
	'img_lot' => '/img', //@todo - Esta configurado en app.php
	'new_image_folders' => true, //@todo - Todos los tienen en 1. Eliminar lógica antigua
	'max_bids' => 4,
	'pagination_bills' => 8,
	'tranferCount' => '',
	'tr_show_adjudicaciones' => true,
	'tr_show_automatic_auction' => true,
	'tr_show_buscador' => true,
	'tr_show_canel_bid_client' => false,
	'tr_show_chat' => true,
	'tr_show_estimate_price' => true,
	'tr_show_ordenes_licitacion' => true,
	'tr_show_pujas' => true,
	'tr_show_streaming' => false,
	'video_home' => false, //Activar video/matteport home
	'date_regenerate_image' => null, //fecha en formato Y-m-d, Si una imagen es anterior a esta fecha se regenerara
	'news_relacionadas' => 3, //Número de noticias relacionadas a mostrar
	'paginate_blog' => 16, //Número de entradas por página en el blog
	'img_quality' => 75, // Marcará la calidad de la imagen jpg al generar las imagenes en la libreria image_generate. Calidad de la imagen (1-100)
	'impsalhces_asigl0' => false, //En la parilla de lotes se vera precio salida "impsalhces_asigl0"
	'estimacion' => false, // En la parilla de lotes se vera estimacion "imptas_asigl0 - imptash_asigl0"
	'ref_asigl0' => false, // En la parilla de lotes se vera referencia del lote "ref_asigl0"
	'descweb_hces1' => false, // En la parilla de lotes se vera descripcion web "descweb_hces1"
	'desc_hces1' => false, // En la parilla de lotes se vera descripcion "desc_hces1"
	'titulo_hces1' => false, // En la parilla de lotes se vera titulo "titulo_hces1"
	'enable_language_selector' => false, // Habilitar selector de idioma en el frontend
	'icon_multiple_images' => false, // Muestra un icono en la lista de lotes indicando que ese lote tiene multiples imagenes
	'google_translate' => false, // Habilitar Google Translate en el frontend
	'config_cookies' => '{}', // Cookies de los clientes, @todo - Sistema antiguo, mirar de eliminar referencias a este.
];
