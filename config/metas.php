<?php

return [
	'admin' => [
		'admin_active_auctions' => [
			'description' => 'Subastas activas en el panel de administración',
			'type' => 'select_multiple',
			'values' => [
				'W' => 'W - Presenciales',
				'P' => 'P - Presenciales',
				'O' => 'O - Online',
				'V' => 'V - Venta Directa',
				'E' => 'E - Especiales',
				'M' => 'M - ¿?',
				'I' => 'I - Inversas'
			],
		],
		'admin_upload_first_session' => [
			'description' => 'Admin, preguntar o no por actualizar la primera sesión al actualizar datos de subasta',
			'type' => 'boolean'
		],
		'camposNuevosArtista' => [
			'description' => 'Para ver los nuevos campos de artista. Ejemplo: phone|email|idexternal. Lo usa Duran.',
			'type' => 'string'
		],
		'is_concursal' => [
			'description' => 'Eliminar',
			'type' => 'boolean'
		],
		'newsletterFamilies' => [
			'description' => 'Familias de newsletter en admin. Ejemplo: "2,3,4,5,6,7"',
			'type' => 'string'
		],
		'payAwards' => [
			'description' => 'Muestra en el admin el checkbox de marcar como pagado adjudicaciones',
			'type' => 'boolean'
		],
		'useExtraInfo' => [
			'description' => 'Editar información extra desde admin lotes',
			'type' => 'boolean'
		],
		'use_fxsecmap_excel' => [
			'description' => 'Uso de la tabla FXSECMAP para asignar la correcta sección',
			'type' => 'boolean'
		],
		'useProviders' => [
			'description' => 'Usar proveedores como propietarios de lote',
			'type' => 'boolean'
		],
		'increment_endlot_online' => [
			'description' => 'En la carga de lotes por excel, añade el valor en segundos al cierre de cada lote a partir del fin de subasta',
			'type' => 'integer'
		]
	],
	'behavior' => [
		'adjudicacion_reserva' => [
			'description' => 'Adjudicar lote al llegar a precio de reserva',
			'type' => 'boolean'
		],
		'auto_licit' => [
			'description' => 'Asigna un licitador de modo automático al entrar en una subasta',
			'type' => 'boolean'
		],
		'dummy_bidder' => [
			'description' => 'Codigo de licitador del licitador que recibe las pujas rápidas donde no da tiempo a asignar licitador, para la subasta en tiempo real a través solo del gestor',
			'type' => 'string'
		],
		'ArtistCode' => [
			'description' => 'Identifica id de categoria artistas',
			'type' => 'integer'
		],
		'automatic_blocking_licit_cancel_bids' => [
			'description' => 'Bloqueo automático de un licitador si realiza más de X cancelaciones de puja durante una subasta de tiempo real',
			'type' => 'integer'
		],
		'cd_time' => [
			'description' => 'En la subasta en tiempo real establece los segundos que pasan entre lote y lote al clickar sobre pasar lote',
			'type' => 'integer'
		],
		'DeleteOrdersAnyTime' => [
			'description' => 'Permite borrar ordenes aunque haya pasado el periodo de ordenes',
			'type' => 'boolean'
		],
		'distance_to_play_favs' => [
			'description' => 'Distancia entre el lote actual y los lotes agregados a favoritos para que suene la alarma de lote próximo',
			'type' => 'integer'
		],
		'dontRemoveBooksOrders' => [
			'description' => 'Evita que se borren ordenes de tipo P o tipo O',
			'type' => 'boolean'
		],
		'escalado_libre' => [
			'description' => 'Permite el escalado libre de ordenes',
			'type' => 'boolean'
		],
		'escalado_libre_pujas' => [
			'description' => 'Con valor 0 no deja hacer pujas libres, con valor 1 deja hacer pujas libres antes de la subasta, con valor 2 deja hacer pujas libres en todo momento',
			'type' => 'integer'
		],
		'force_correct_price' => [
			'description' => 'Fuerza el precio de salida al precio correcto si este está fuera del escalado',
			'type' => 'boolean'
		],
		'not_force_correct_price_tiempo_real' => [
			'description' => 'Hace que en tiempo real no se fuerce el precio de salida',
			'type' => 'boolean'
		],
		'gridHistoricoVentas' => [
			'description' => 'Ver el histórico de lotes vendidos en el grid de subastas',
			'type' => 'boolean'
		],
		'group_auction_in_search' => [
			'description' => 'Agrupa la busqueda en la en por subastas, en vez de mostrar los lotes',
			'type' => 'boolean'
		],
		'hide_not_sell_lot_historical' => [
			'description' => 'Ocultar los lotes no vendidos en las subastas historicas',
			'type' => 'boolean'
		],
		'increase_time_add' => [
			'description' => 'Cuando se lance un evento de incremento del tiempo restante se sumará los segundos que se indican en el campo',
			'type' => 'integer'
		],
		'increase_time_launch' => [
			'description' => 'Lanzará el evento de incrementar el tiempo de un lote si el tiempo que queda es menor o igual al valor del campo, el tiempo se mide en segundos',
			'type' => 'integer'
		],
		'loteFavoritoProximo' => [
			'description' => 'Enviar mail cuando se acerca un lote favorito',
			'type' => 'integer'
		],
		'maxium_time_increment' => [
			'description' => 'Tiempo maximo de extension en un lote, marcado en segundos',
			'type' => 'integer'
		],
		'max_orders_and_bids_ries_cli' => [
			'description' => 'Limite de puja con la suma de las puja máximas (puja u orden)',
			'type' => 'boolean'
		],
		'max_orders_ries_cli' => [
			'description' => 'Bloquear orden si la suma de estas supera el ries_cli del cliente',
			'type' => 'boolean'
		],
		'notice_over_bid' => [
			'description' => 'Avisa al usurio con un mensaje en la web cuando hace una orden y esta orden no supera una orden anterior',
			'type' => 'boolean'
		],
		'number_bids_lotlist' => [
			'description' => 'Realizar consultas para mostrar pujas y licitadores en grid y panel de usuario',
			'type' => 'boolean'
		],
		'order_by_filter' => [
			'description' => 'Ordenar los lotes por (estimacion, precio_salida)',
			'type' => 'string'
		],
		'paginacion_grid_lotes' => [
			'description' => 'Si tiene valor 0 funcionara con scroll, si tiene valor 1 funcionará con paginación',
			'type' => 'integer'
		],
		'pujas_enfirme' => [
			'description' => 'Habilitar pujas en firme',
			'type' => 'boolean'
		],
		'pujas_maximas_mostradas' => [
			'description' => 'Establece las pujas máximas que se mostraran por lote (-1 Muestra todas las pujas de los lotes)',
			'type' => 'integer'
		],
		'restrictVisibility' => [
			'description' => 'Limita la visibilidad de los lotes y subastas mediante la tabla FGVISIBILIDAD',
			'type' => 'boolean'
		],
		'search_engine' => [
			'description' => 'Hace busquedas por indice de texto con campo search_hces1',
			'type' => 'boolean'
		],
		'search_multiple_words' => [
			'description' => 'Permite buscar las palabras por separado en los buscadores de la web y el de lotes',
			'type' => 'boolean'
		],
		'send_email_cancel_bid' => [
			'description' => 'Envia email de se ha cancelado una puja, con valor 1 envia al usuario que se le ha cancelado la puja, con valor 2 envia tambien al resto de usuarios',
			'type' => 'integer'
		],
		'send_email_lot_increment_bid_to_all_users_with_deposit' => [
			'description' => 'Al realizarse una puja, enviar email a todos los usuarios que tengan deposito por ese lote',
			'type' => 'boolean'
		],
		'send_valid_deposit_notification' => [
			'description' => 'Enviar notificación al crear o editar un deposito a valido',
			'type' => 'boolean'
		],
		'default_minuts_pause' => [
			'description' => 'Minutos que apareceran por defecto en los clientes cuando quieran pausar una subasta',
			'type' => 'integer'
		],
		'assessment_registered' => [
			'description' => 'Permitir o no acceder a tasaciones sin estar registrado',
			'type' => 'boolean'
		],
		'auction_in_categories' => [
			'description' => 'Agrupa las subastas tipo O y tipo P en categorias, no se puede acceder a ellas mediante la subasta, solo por categorias',
			'type' => 'string'
		],
		'enable_general_auctions' => [
			'description' => 'Habilita las subastas generales',
			'type' => 'boolean'
		],
		'enable_historic_auctions' => [
			'description' => 'Habilita las subastas históricas',
			'type' => 'boolean'
		],
		'enable_tr_auctions' => [
			'description' => 'Habilita las subastas tr',
			'type' => 'boolean'
		],
		'fecha_noindex_follow' => [
			'description' => 'Fecha de lotes de subastas más antiguas que no se van a noindex follow',
			'type' => 'string'
		],
		'hide_sold_lot' => [
			'description' => 'Oculta los lotes vendidos, se tiene en cuenta en las queries',
			'type' => 'boolean'
		],
		'hide_sold_lots_V' => [
			'description' => 'Ocultar lotes vendidos de las subastas tipo venta',
			'type' => 'boolean'
		],
		'keywords_search' => [
			'description' => 'Búsqueda por palabras clave',
			'type' => 'integer'
		],
		'search_lots_cerrados' => [
			'description' => 'Buscar por lotes abiertos y cerrados',
			'type' => 'boolean'
		],
		'force_language_redirect' => [
			'description' => 'Forzamos a que las redirecciones que hacemos para mantener las url de la web antigua sean solo en un idioma',
			'type' => 'boolean'
		]
	],
	'display' => [
		'add_calendar_feature' => [
			'description' => 'Mostrar botón para exportar subastas a calendarios',
			'type' => 'boolean'
		],
		'awarded' => [
			'description' => 'Mostrar o no si un lote ha sido adjudicado en listado de lotes',
			'type' => 'boolean'
		],
		'default_theme' => [
			'description' => 'Theme por defecto. Valores posibles: v1, v2',
			'type' => 'string'
		],
		'default_lang' => [
			'description' => 'Idioma por defecto',
			'type' => 'string'
		],
		'deleteBids' => [
			'description' => 'Permitir o no eliminar pujas de un lote en la subasta en tiempo real cuando se quiere reabrir un lote',
			'type' => 'boolean'
		],
		'exchange' => [
			'description' => 'Habilita el mostrar divisas en la web',
			'type' => 'boolean'
		],
		'gridLots' => [
			'description' => 'Habilita nuevo grid de lotes en la web, valores posibles: false, "new"',
			'type' => 'string'
		],
		'img_lot' => [
			'description' => 'Ruta de las imágenes de los lotes',
			'type' => 'string'
		],
		'new_image_folders' => [
			'description' => 'Habilita el uso de nuevas carpetas para las imágenes de los lotes',
			'type' => 'boolean'
		],
		'max_bids' => [
			'description' => 'Número de pujas mostradas en historico',
			'type' => 'integer'
		],
		'pagination_bills' => [
			'description' => 'Número máximo de facturas mostradas en la paginación',
			'type' => 'integer'
		],
		'tranferCount' => [
			'description' => 'Nº de cuenta para las transferencias, solamente se utiliza en traducciones',
			'type' => 'string'
		],
		'tr_show_adjudicaciones' => [
			'description' => 'Mostramos bloque de buscador de adjudicaciones en tiempo real',
			'type' => 'boolean'
		],
		'tr_show_automatic_auction' => [
			'description' => 'Tiempo real subasta automatica',
			'type' => 'boolean'
		],
		'tr_show_buscador' => [
			'description' => 'Mostramos bloque de buscador de items en tiempo real',
			'type' => 'boolean'
		],
		'tr_show_canel_bid_client' => [
			'description' => 'Tiempo real usuario pueda cancelar pujas',
			'type' => 'boolean'
		],
		'tr_show_chat' => [
			'description' => 'Mostramos bloque de chat en subasta tiempo real',
			'type' => 'boolean'
		],
		'tr_show_estimate_price' => [
			'description' => 'Mostrar precio estimado en tiempo real',
			'type' => 'boolean'
		],
		'tr_show_ordenes_licitacion' => [
			'description' => 'Mostramos bloque de ordenes en tiempo real (solo admin)',
			'type' => 'boolean'
		],
		'tr_show_pujas' => [
			'description' => 'Mostramos bloque de pujas en subasta tiempo real',
			'type' => 'boolean'
		],
		'tr_show_streaming' => [
			'description' => 'Mostramos bloque de streaming en tiempo real',
			'type' => 'boolean'
		]
	],
	'features' => [
		'enable_cache' => [
			'description' => 'Habilita el uso de caché',
			'type' => 'boolean'
		],
		'newsletter_table' => [
			'description' => 'Habilita las tabla de newsletter',
			'type' => 'boolean'
		],
		'newUrlLot' => [
			'description' => 'Nueva url friendly del lote',
			'type' => 'boolean'
		],
		'measure_query_time' => [
			'description' => 'Habilita la medición del tiempo de consultas en las acciones en tiempo real',
			'type' => 'boolean'
		],
		'seoVisit' => [
			'description' => 'Habilitar seguimiento de visitas SEO',
			'type' => 'boolean'
		],
		'useNft' => [
			'description' => 'Habilitar uso de NFT',
			'type' => 'boolean'
		]
	],
	'global' => [
		'apikey' => [
			'description' => 'API Key para llamadas a api interna',
			'type' => 'string'
		],
		'captcha_v3' => [
			'description' => 'Captcha v3 habilitado',
			'type' => 'boolean'
		],
		'codRecaptchaEmail' => [
			'description' => 'Clave privada de captcha v2',
			'type' => 'string'
		],
		'codRecaptchaEmailPublico' => [
			'description' => 'Clave pública de captcha v2',
			'type' => 'string'
		],
		'custom_login_url' => [
			'description' => 'URL personalizada de inicio de sesión',
			'type' => 'string'
		],
		'facebook' => [
			'description' => 'Dirección URL del perfil en Facebook',
			'type' => 'string'
		],
		'googleplus' => [
			'description' => 'Dirección URL del perfil en Google+',
			'type' => 'string'
		],
		'instagram' => [
			'description' => 'Dirección URL del perfil en Instagram',
			'type' => 'string'
		],
		'mailing_service' => [
			'description' => 'Servicio externo de emails (opciones: MailchimpService)',
			'type' => 'string'
		],
		'money' => [
			'description' => 'Moneda web',
			'type' => 'string'
		],
		'name' => [
			'description' => 'Nombre de aplicación',
			'type' => 'string'
		],
		'password_MD5' => [
			'description' => 'Código para codificar el password del usuario',
			'type' => 'string'
		],
		'pinterest' => [
			'description' => 'Dirección URL del perfil en Pinterest',
			'type' => 'string'
		],
		'session_timeout' => [
			'description' => 'Tiempo en segundos de inactividad',
			'type' => 'integer'
		],
		'strict_password_in_api' => [
			'description' => 'Validación estricta de contraseña con mínimo un símbolo, número, mayúscula, minúscula y 8 caracteres',
			'type' => 'boolean'
		],
		'time_cache' => [
			'description' => 'Tiempo de la cache en minutos',
			'type' => 'integer'
		],
		'twitter' => [
			'description' => 'Dirección URL del perfil en Twitter',
			'type' => 'string'
		],
		'youtube' => [
			'description' => 'Dirección URL del perfil en Youtube',
			'type' => 'string'
		]
	],
	'mail' => [
		'admin_email' => [
			'description' => 'Email del administrador',
			'type' => 'string'
		],
		'admin_email_administracion' => [
			'description' => 'Email del administrador de administración',
			'type' => 'string'
		],
		'copies_emails' => [
			'description' => 'Habilita el envio de copias de los emails al correo definido en copies_emails_mailbox',
			'type' => 'boolean'
		],
		'copies_emails_mailbox' => [
			'description' => 'Correo al que se envian las copias de los emails, si copies_emails es true',
			'type' => 'string'
		],
		'debug_to_email' => [
			'description' => 'Correo al que se envian los errores de depuracion cuando el modo debug esta habilitado',
			'type' => 'string'
		],
		'emailApiError' => [
			'description' => 'Correo que va a recibir el correo de error en la API',
			'type' => 'string'
		],
		'emailsCopyApiError' => [
			'description' => 'Correos que van a recibir copia de del error en la API, deben separarse por comas',
			'type' => 'string'
		],
		'email_tasacion_client' => [
			'description' => 'Habilita que el usuario reciba un correo de confirmación del envío de la tasación',
			'type' => 'boolean'
		],
		'enable_email_buy_user' => [
			'description' => 'Habilita que el usuario reciba un correo de confirmación al comprar un lote',
			'type' => 'boolean'
		],
		'enable_emails' => [
			'description' => 'Habilita el envío de correos electrónicos',
			'type' => 'boolean'
		],
		'from_email' => [
			'description' => 'From email',
			'type' => 'string'
		],
		'max_email_cron' => [
			'description' => 'Número máximo de correos electrónicos a enviar por cron',
			'type' => 'integer'
		],
		'utm_email' => [
			'description' => 'Código UTM para los emails',
			'type' => 'string'
		]
	],
	'services' => [
		'brandIdUP2' => [
			'description' => 'ID de la marca en UP2',
			'type' => 'string'
		],
		'brandIdUP2_test' => [
			'description' => 'ID de la marca en UP2 para pruebas',
			'type' => 'string'
		],
		'environmentUP2' => [
			'description' => 'Entorno de UP2',
			'type' => 'boolean'
		],
		'merchantIdUP2' => [
			'description' => 'ID del comerciante en UP2',
			'type' => 'string'
		],
		'merchantIdUP2_test' => [
			'description' => 'ID del comerciante en UP2 para pruebas',
			'type' => 'string'
		],
		'passwordUP2' => [
			'description' => 'Contraseña de la API de UP2',
			'type' => 'string'
		],
		'passwordUP2_test' => [
			'description' => 'Contraseña de la API de UP2 para pruebas',
			'type' => 'string'
		],
		'UP2_cancel' => [
			'description' => 'URL de cancelación de pago. No solo se utiliza para UP2',
			'type' => 'string'
		],
		'UP2_return' => [
			'description' => 'URL de retorno de UP2. No solo se utiliza para UP2',
			'type' => 'string'
		],
		'captcha_v3_private' => [
			'description' => 'Clave privada de captcha v3',
			'type' => 'string'
		],
		'captcha_v3_public' => [
			'description' => 'Clave pública de captcha v3',
			'type' => 'string'
		],
		'captcha_v3_severity' => [
			'description' => 'Captcha v3 puntuación mínima',
			'type' => 'string'
		],
		'deliverea_api_testpass' => [
			'description' => 'Contraseña de la API de Deliverea para pruebas',
			'type' => 'string'
		],
		'deliverea_api_testuser' => [
			'description' => 'Usuario de la API de Deliverea para pruebas',
			'type' => 'string'
		],
		'deliverea_sandbox' => [
			'description' => 'Sandbox de Deliverea',
			'type' => 'boolean'
		],
		'fb_app_id' => [
			'description' => 'API de la cuenta vinculada de facebook',
			'type' => 'string'
		],
		'google_analytics' => [
			'description' => 'ID de la cuenta de Google Analytics',
			'type' => 'string'
		],
		'invaluable_API_password' => [
			'description' => 'Password de la API de Invaluable',
			'type' => 'string'
		],
		'invaluable_API_URL' => [
			'description' => 'URL de la API de Invaluable',
			'type' => 'string'
		],
		'invaluable_API_user' => [
			'description' => 'User de la API de Invaluable',
			'type' => 'string'
		],
		'invaluableHouse' => [
			'description' => 'Código de la casa de subastas en Invaluable',
			'type' => 'string'
		],
		'mailchimp_api_key' => [
			'description' => 'Clave de la API de Mailchimp',
			'type' => 'string'
		],
		'mailchimp_list_id' => [
			'description' => 'ID de la lista de Mailchimp',
			'type' => 'string'
		],
		'mailchimp_server_prefix' => [
			'description' => 'Prefijo del servidor de Mailchimp',
			'type' => 'string'
		],
		'paypalClientId' => [
			'description' => 'ID de cliente de Paypal',
			'type' => 'string'
		],
		'paypalClientSecret' => [
			'description' => 'Secreto de cliente de Paypal',
			'type' => 'string'
		],
		'appIdVottun' => [
			'description' => 'ID de la aplicación en Vottun',
			'type' => 'string'
		],
		'urlIpfsVottun' => [
			'description' => 'Url de IPFS de Vottun',
			'type' => 'string'
		],
		'urlNftVottun' => [
			'description' => 'Url de NFT de Vottun',
			'type' => 'string'
		],
		'urlPowVottun' => [
			'description' => 'Url de Proof of Work de Vottun',
			'type' => 'string'
		],
		'urlToPackengers' => [
			'description' => 'Url de Packengers',
			'type' => 'string'
		],
		'zoho_client_id' => [
			'description' => 'ID del cliente de Zoho',
			'type' => 'string'
		],
		'zoho_client_secret' => [
			'description' => 'Secreto del cliente de Zoho',
			'type' => 'string'
		],
		'zoho_grant_code' => [
			'description' => 'Código de un solo uso para zoho',
			'type' => 'string'
		],
		'zoho_organization_id' => [
			'description' => 'Organización de CRM Zoho, solo para API de subscriptions (invoices)',
			'type' => 'string'
		],
		'zoho_refresh_token' => [
			'description' => 'Código para obtener token de acceso',
			'type' => 'string'
		],
		'auchouse_code' => [
			'description' => 'Código de la casa de subastas para Subalia',
			'type' => 'string'
		],
		'subalia_cli' => [
			'description' => 'Código de cliente que representa subalia',
			'type' => 'string'
		],
		'subalia_key' => [
			'description' => 'Key para la comunicación con subalia',
			'type' => 'string'
		],
		'subalia_min_licit' => [
			'description' => 'Valor mínimo que tendrá un licitador en subalia',
			'type' => 'integer'
		],
		'ps_sb_auth_key' => [
			'description' => 'Key de encriptación para prestashop',
			'type' => 'string'
		],
		'ps_shop_path' => [
			'description' => 'URL de la tienda de prestashop',
			'type' => 'string'
		],
		'ps_ws_auth_key' => [
			'description' => 'Clave de acceso al webService de prestashop',
			'type' => 'string'
		]
	],
	'user' => [
		'catalogo_newsletter' => [
			'description' => 'De manera provisional, en ansorena galeria, necesitamos guardar en newsletter20 la suscripción a catálogo',
			'type' => 'boolean'
		],
		'coregistroSubalia' => [
			'description' => 'Activar el coregistro con Subalia',
			'type' => 'boolean'
		],
		'DeleteOrders' => [
			'description' => 'Pueden borrar ordenes de la subasta W antes de que empiece en el panel de usuario',
			'type' => 'boolean'
		],
		'delivery_address' => [
			'description' => 'Permite gestionar múltiples direcciones de entrega',
			'type' => 'boolean'
		],
		'fpag_default' => [
			'description' => 'Define el valor por defecto de la forma de pago del cliente, este valor se guardará en el campo FPAG_CLI de la tabla FXCLI',
			'type' => 'integer'
		],
		'fpag_foreign_default' => [
			'description' => 'Valor por defecto de la forma de pago del cliente no español',
			'type' => 'integer'
		],
		'login_acces_web' => [
			'description' => 'Login obligatorio para acceder a la web',
			'type' => 'boolean'
		],
		'login_attempts_timeout' => [
			'description' => 'Tiempo en segundos antes de permitir nuevos intentos de login',
			'type' => 'integer'
		],
		'login_attempts' => [
			'description' => 'Número de intentos de login fallidos antes de bloquear la cuenta',
			'type' => 'integer'
		],
		'makePreferences' => [
			'description' => 'Habilita el uso de preferencias',
			'type' => 'boolean'
		],
		'modal_login' => [
			'description' => 'Abrir modal cuando quiere acceder en el panel sin logearse',
			'type' => 'boolean'
		],
		'new_favorites_panel' => [
			'description' => 'En el campo se pone el nombre de la traducción para que si existe este config redirija a la nueva página de favoritos',
			'type' => 'string'
		],
		'panel_password_recovery' => [
			'description' => 'Recuperar contraseña el link del email de redirige a un panel para modificar contraseña',
			'type' => 'boolean'
		],
		'pasarela_web' => [
			'description' => 'Habilita el pago individual de facturas',
			'type' => 'boolean'
		],
		'paymentPaypal' => [
			'description' => 'Habilitar pago con Paypal',
			'type' => 'boolean'
		],
		'paymentUP2' => [
			'description' => 'Habilita el uso de UP2 para pagos (valores: "0" - deshabilitado, "UP2" - habilitado)',
			'type' => 'string'
		],
		'PayTransfer' => [
			'description' => 'Habilitar pago por transferencia bancaria',
			'type' => 'boolean'
		],
		'registerCheckerF' => [
			'description' => 'Activa una serie de validaciones a nivel de servidor en el registro para pre_emp F',
			'type' => 'string'
		],
		'registerCheckerJ' => [
			'description' => 'Activa una serie de validaciones a nivel de servidor en el registro para pre_emp J',
			'type' => 'string'
		],
		'registration_disabled' => [
			'description' => 'Deshabilitar el registro de nuevos usuarios',
			'type' => 'boolean'
		],
		'regtype' => [
			'description' => 'Tipo de registro (1-Registro en ERP Y WEB, 2-Registro con validación ERP, 3-Envío de email con datos, 4-Pendiente activar por API)',
			'type' => 'integer'
		],
		'token_security' => [
			'description' => 'Marca la seguridad del token, medium se generará un token diferente cada día, hard se generará un token cada inicio de sesión',
			'type' => 'string'
		],
		'user_panel_group_subasta' => [
			'description' => 'En el panel, mis pujas están agrupadas por subastas',
			'type' => 'boolean'
		],
		'userPanelCIFandCC' => [
			'description' => 'CIF y CC del panel de usuario',
			'type' => 'boolean'
		],
		'userPanelMySales' => [
			'description' => 'Habilita el panel de mis ventas',
			'type' => 'boolean'
		],
		'web_gastos_envio' => [
			'description' => 'Usa la tabla web_gastos_envio para calcular los gastos de envío',
			'type' => 'boolean'
		],
		'ps_activate' => [
			'description' => 'Habilitar registro desde plataforma externa',
			'type' => 'boolean'
		],
		'name_without_coma' => [
			'description' => 'En registro guarda nombre y apellidos seguidos',
			'type' => 'boolean'
		],
		'strtodefault_register' => [
			'description' => 'Permitir guardar datos de registro en el formato escrito. Por defecto será en mayúsculas',
			'type' => 'boolean'
		]
	]
];
