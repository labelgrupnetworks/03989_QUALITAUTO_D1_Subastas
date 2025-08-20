<?php

return [
	'enable_cache' => true,
	'newsletter_table' => false,
	'newUrlLot' => false,
	'measure_query_time' => false,
	'seoVisit' => false,
	'useNft' => false,
	'notification_exceptions' => false, //enviar email o mensajes de errores
	'blog_wordpress' => null, //Redirección a blog wordpress.
	'enable_language_selector' => false, // Habilitar selector de idioma en el frontend
	'google_translate' => false, // Habilitar Google Translate en el frontend
	//'new_register' => true,
	'debug_erp' => false, //Imprimir logs de peticiones realizadas desde erp
	'specialists_model' => false, // La página de especialistas recide el modelo de especialistas o un array.
	'experts_in_valoration' => false, // Muestra los expertos en la vista de valoracion-articulos
	'use_articleCart' => false, // Utiliza carrito de compra, permite mantener la cookie
	'access_to_private_chanel' => false, // Acceso a canal privado de rrhh
	'new_blog' => false, // Utilización de blog por contenidos
	'WebServicePaidInvoice' => false, // Llama al webservice de factura pagada
	'WebServiceClient' => false, // Llama al webservice de modificar cliente
	'WebServiceCloseLot' => false, // Llama al webservice de cerrar lote
	'WebServiceOrder' => false, // llamamos al webservice de realizar orden
];
