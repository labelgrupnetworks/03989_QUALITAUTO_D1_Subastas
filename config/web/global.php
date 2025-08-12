<?php

return [
	'apikey' => null, //Clave para peticiones a la api interna
	'captcha_v3' => false, //Captcha v3 habilitado
	'codRecaptchaEmail' => '', //Clave privada de captcha v2
	'codRecaptchaEmailPublico' => '', //Clave pública de captcha v2
	'custom_login_url' => '', //URL personalizada de inicio de sesión
	'facebook' => '', //Dirección URL del perfil en Facebook
	'googleplus' => null, //Dirección URL del perfil en Google+ @todo - Eliminar
	'instagram' => '', //Dirección URL del perfil en Instagram
	'mailing_service' => null, //Servicio externo de emails (opciones: MailchimpService)
	'money' => 'EUR', //Moneda web... @todo - Revisar su uso y necesidad.
	'name' => 'Auctions', //Nombre de aplicación - @todo - Eliminar y mantener desde el .env
	'password_MD5' => '', //Código para codificar el password del usuario
	'pinterest' => '', //Dirección URL del perfil en Pinterest
	'session_timeout' => 3600, //Tiempo en segundos de inactividad
	'strict_password_in_api' => false, //Validacion estricta de contraseña con minimo un simbolo, numero, mayuscula, minuscula y 8 caracteres
	'time_cache' => 5, //Tiempo de la cache en minutos (Tengo dudas de que sean minutos)
	'twitter' => '', //Dirección URL del perfil en Twitter
	'youtube' => '', //Dirección URL del perfil en Youtube
];
