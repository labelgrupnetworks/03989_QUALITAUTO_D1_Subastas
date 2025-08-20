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
	'logginWS' => false, //Logs del webservice
	'WebServiceClientNewsletter' => false,
	'seoEvent' => false, // Registra solo la primera visita del día, no cuenta la de lotes o subastas
	'seoUniqueVisit' => false, // Registra solo la primera visita del día, no cuenta la de lotes o subastas
	'api_debug_put' => false, // Habilitar logs en base de datos para peticiones PUT en la API
	'NoclobToVarchar' => false, // Evitar que se convierta clob en varchar aunque estemos en debug
	'WebServicePaidLots' => false, // Llama al webservice de lote pagado
	'WebServiceReservation' => false, // Se debe comunicar la reserva de un lote
	'WebServiceDeleteOrder' => false, // Comunica el borrado de ordenes
	'WebServiceBid' => false, // Comunica la puja de un lote
	'multi_company' => false, // Multiples empresas para controlar las newsletters
	'WebServicePaidtransactionNft' => false, // enviar notificacion de pago de minteo o transferencia pendiente de pagar por le usuario
];
