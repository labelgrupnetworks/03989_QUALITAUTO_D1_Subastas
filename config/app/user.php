<?php

return [
	'catalogo_newsletter' => null,
	'coregistroSubalia' => false,
	'DeleteOrders' => false,
	'delivery_address' => false,
	'fpag_default' => 0,
	'fpag_foreign_default' => null,
	'login_acces_web' => 0, //@todo - eliminar.
	'login_attempts_timeout' => 60,
	'login_attempts' => 0,
	'makePreferences' => 0,
	'modal_login' => 0,
	'new_favorites_panel' => 0,
	'panel_password_recovery' => 1, //@todo - todos en 1.
	'pasarela_web' => false,
	'paymentPaypal' => false,
	'paymentUP2' => null,
	'PayTransfer' => false,
	'registerCheckerF' => '',
	'registerCheckerJ' => '',
	'registration_disabled' => false,
	'regtype' => 1,
	'token_security' => 'medium',
	'user_panel_group_subasta' => 1, //@todo - Todos lo tienen a 1
	'userPanelCIFandCC' => null, //@todo - Revisar si es necesario.
	'userPanelMySales' => null,
	'web_gastos_envio' => null,
	'ps_activate' => false, //@todo Revisar su uso.
	'name_without_coma' => false,
	'strtodefault_register' => false,
	'ries_cli' => 0, //@todo - Se utilizaba para el riesgo al registrarse, pero ya se puede eliminar
	'registro_user_w' => false, //Un usuario se registra en el codigo del cliente pone W delante ejemplo: W00001
	'no_user_change_info' => false, //No permite al usuario modificar sus datos
	'paymentRedsys' => false, //Habilitar pagos por redsys
	'multi_key_pass' => false, //EL password se encripta con una key para cada usuario
	'allotments_shopping_cart' => null, //Separar adjudicaciones por venta directa y resto
	'multiPasarela' => null, //Multiples pasarelas
];
