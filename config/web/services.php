<?php

return [
	'brandIdUP2' => null, // ID de la marca en UP2
	'brandIdUP2_test' => null, // ID de la marca en UP2 para pruebas
	'environmentUP2' => false, // Entorno de UP2
	'merchantIdUP2' => null, // ID del comerciante en UP2
	'merchantIdUP2_test' => null, // ID del comerciante en UP2 para pruebas
	'passwordUP2' => null, // Contraseña de la API de UP2
	'passwordUP2_test' => null, // Contraseña de la API de UP2 para pruebas
	'UP2_cancel' => '/es/pagina/pago-cancelado', // URL de cancelación de pago. No solo se utiliza para UP2
	'UP2_return' => '/es/pagina/pago-realizado', // URL de retorno de UP2. No solo se utiliza para UP2
	'captcha_v3_private' => '', //Clave privada de captcha v3
	'captcha_v3_public' => '', //Clave pública de captcha v3
	'captcha_v3_severity' => '0.6', //Captcha v3 puntuación mínima
	'deliverea_api_testpass' => null, //Contraseña de la API de Deliverea para pruebas
	'deliverea_api_testuser' => null, //Usuario de la API de Deliverea para pruebas
	'deliverea_sandbox' => null, //Sandbox de Deliverea
	'fb_app_id' => '', //API de la cuenta vinculada de facebook. @todo - Eliminar.
	'google_analytics' => null, //ID de la cuenta de Google Analytics. @todo, modificar las vistas para utilizar el config.
	'invaluable_API_password' => '', //password de la API de Invaluable
	'invaluable_API_URL' => '', //url de la API de Invaluable
	'invaluable_API_user' => '', //user de la API de Invaluable
	'invaluableHouse' => '', //código de la casa de subastas en invaluable
	'mailchimp_api_key' => null, //Clave de la API de Mailchimp
	'mailchimp_list_id' => null, //ID de la lista de Mailchimp
	'mailchimp_server_prefix' => null, //Prefijo del servidor de Mailchimp
	'paypalClientId' => null, //ID de cliente de Paypal
	'paypalClientSecret' => null, //Secreto de cliente de Paypal
	'appIdVottun' => null, // ID de la aplicación en Vottun
	'urlIpfsVottun' => null, //Url de IPFS de Vottun
	'urlNftVottun' => null, //Url de NFT de Vottun
	'urlPowVottun' => null, //Url de Proof of Work de Vottun
	'urlToPackengers' => '', //Url de Packengers
	'zoho_client_id' => null, //ID del cliente de Zoho
	'zoho_client_secret' => null, //Secreto del cliente de Zoho
	'zoho_grant_code' => null, //código de un solo uso para zoho
	'zoho_organization_id' => null, //organizacion de crm zoho, solo para api de subscriptions (invoices)
	'zoho_refresh_token' => null, //código para obtener token de acceso
	'auchouse_code' => null, // Código de la casa de subastas para Subalia,
	'subalia_cli' => null, //Codigo de cliente que representa subalia (dato anterior 00000015)
	'subalia_key' => null, //Key para la comunicación con subalia, A partir de ella se genera un hash que sirve como seguridad para las peticiones por api/post a Subalia
	'subalia_min_licit' => 100000, //Valor mínimo que tendra un licitador en subalia
];
