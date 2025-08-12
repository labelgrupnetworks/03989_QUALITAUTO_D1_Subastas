<?php

return [
	'apikey' => null,
	'appIdVottun' => null, // ID de la aplicación en Vottun
	'auchouse_code ' => null, // Código de la casa de subastas para Subalia,
	'brandIdUP2' => null, // ID de la marca en UP2
	'brandIdUP2_test' => null, // ID de la marca en UP2 para pruebas
	'environmentUP2' => false, // Entorno de UP2
	'captcha_v3' => false, //Captcha v3 habilitado
	'captcha_v3_private' => '', //Clave privada de captcha v3
	'captcha_v3_public' => '', //Clave pública de captcha v3
	'captcha_v3_severity' => '0.6', //Captcha v3 puntuación mínima
	'codRecaptchaEmail' => '', //Clave privada de captcha v2
	'codRecaptchaEmailPublico' => '', //Clave pública de captcha v2
	'custom_login_url' => '', //URL personalizada de inicio de sesión
	'default_lang' => 'en', //creo que algún dia lo uso Tauler. @todo - Eliminar
	'deliverea_api_testpass' => null, //Contraseña de la API de Deliverea para pruebas
	'deliverea_api_testuser' => null, //Usuario de la API de Deliverea para pruebas
	'deliverea_sandbox' => null, //Sandbox de Deliverea
	'enable_cache' => true, //Habilita el uso de caché
	'facebook' => '', //Dirección URL del perfil en Facebook
	'fb_app_id' => '', //API de la cuenta vinculada de facebook. @todo - Eliminar.
	'google_analytics' => null, //ID de la cuenta de Google Analytics. @todo, modificar las vistas para utilizar el config.
	'googleplus' => null, //Dirección URL del perfil en Google+ @todo - Eliminar
	'img_lot' => '/img', //Ruta de las imágenes de los lotes @todo - Esta configurado en app.php
	'instagram' => '', //Dirección URL del perfil en Instagram
	'invaluable_API_password' => '', //password de la API de Invaluable
	'invaluable_API_URL' => '', //url de la API de Invaluable
	'invaluable_API_user' => '', //user de la API de Invaluable
	'invaluableHouse' => '', //código de la casa de subastas en invaluable
];
