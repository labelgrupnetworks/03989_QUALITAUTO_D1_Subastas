<?php

return [
	'apikey' => null,
	'captcha_v3' => false,
	'codRecaptchaEmail' => '',
	'codRecaptchaEmailPublico' => '',
	'custom_login_url' => '',
	'facebook' => '',
	'googleplus' => null, //@todo - Eliminar
	'instagram' => '',
	'mailing_service' => null,
	'money' => 'EUR', //@todo - Revisar su uso y necesidad.
	'name' => 'Auctions', //@todo - Eliminar y mantener desde el .env
	'password_MD5' => '',
	'pinterest' => '',
	'session_timeout' => 3600,
	'strict_password_in_api' => false,
	'time_cache' => 5,
	'twitter' => '',
	'youtube' => '',
	'compresion_img' => 0.35, //si el indice de compresion de la imagen esta por encima de este valor se generará la imagen "real"
	'img_quality' => 75, // Marcará la calidad de la imagen jpg al generar las imagenes en la libreria image_generate. Calidad de la imagen (1-100)
	'config_cookies' => '{}', // Cookies de los clientes, @todo - Sistema antiguo, mirar de eliminar referencias a este.
	'linkedin' => '', //URL de LinkedIn
	'global_auction_types_var' => true, // Obtenemos para el header solamente el numero de subastas activas por tipo. Mejora la carga.
];
