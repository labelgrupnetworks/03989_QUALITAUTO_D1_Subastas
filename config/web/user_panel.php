<?php

return [
	'DeleteOrders' => false, //Pueden borrar ordenes de la subasta W antes de que empiece en el panel de usuario,
	'delivery_address' => false, //Permite gestionar múltiples direcciones de entrega.
	'fpag_default' => 0, // Define el valor por defecto de la forma de pago del cliente, este valor se guardará en el campo FPAG_CLI de la tabla FXCLI.
	'fpag_foreign_default' => null, //Valor por defecto de la forma de pago del cliente no español
	'login_acces_web' => 0, //Login obligatorio para acceder a la web. Nadie lo tiene activado, @todo - eliminar.
	'login_attempts' => 0, //Número de intentos de login fallidos antes de bloquear la cuenta
	'login_attempts_timeout' => 60, //Tiempo en segundos antes de permitir nuevos intentos de login
	'makePreferences' => 0, //Habilita el uso de preferencias
	'modal_login' => 0, //Abrir modal cuando quiere acceder en el panel sin logearse
	'new_favorites_panel' => 0, //En el campo se pone el nombre de la traducción para que si existe este config redirija a la nueva página de favoritos. El valor es "new-favorites"
	'panel_password_recovery' => 1, //Recuperar contraseña el link del email de redirige a un panel para modificar contraseña @todo - todos en 1.
	'pasarela_web' => false, //Habilita el pago individual de facturas.
	'registerCheckerF' => '', //Activa una serie de validaciones a nivel de servidor en el registro para pre_emp F. Ejemplo: pais,usuario,last_name,cpostal,codigoVia,direccion,provincia,nif
	'registerCheckerJ' => '', //Activa una serie de validaciones a nivel de servidor en el registro para pre_emp J. Ejemplo: pais,usuario,last_name,cpostal,codigoVia,direccion,provincia,nif
	'registration_disabled' => false, //Deshabilitar el registro de nuevos usuarios
	'regtype' => 1, //Tipo de registro (Valores: 1 - Registro en ERP Y WEB, 2 - Registro en ERP y WEB pero con validación desde ERP, 3 - Envío de email con los datos del registro, 4 - Pendiente de activar por api)
	'token_security' => 'medium', //Marca la seguridad del toke, medium se generará un token diferente cada día, hard se generará un token cada inicio de sesión.
	'userPanelCIFandCC' => null, //CIF y CC del panel de usuario @todo - Revisar si es necesario.
	'user_panel_group_subasta' => 1, //	En el panel, mis pujas esten agrupadas por subastas. @todo - Todos lo tienen a 1
	'userPanelMySales' => null, //Habilita el panel de mis ventas.
	'web_gastos_envio' => null, //Usa la tabla web_gastos_envio para calcular los gastos de envio
	'paymentUP2' => null, // Habilita el uso de UP2 para pagos. (valores: '0' - deshabilitado, 'UP2' - habilitado). paymentRedsys tiene prioridad sobre este
	'paymentPaypal' => false, //Habilitar pago con Paypal
	'PayTransfer' => false, //Habilitar pago por transferencia bancaria
];
