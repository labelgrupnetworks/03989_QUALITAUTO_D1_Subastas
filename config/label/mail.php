<?php

return [
	'admin_email' => 'admin@example.com',
	'admin_email_administracion' => '',
	'copies_emails' => false,
	'copies_emails_mailbox' => '',
	'debug_to_email' => '',
	'emailApiError' => '',
	'emailsCopyApiError' => '',
	'email_tasacion_client' => false,
	'enable_email_buy_user' => false,
	'enable_emails' => true,
	//'from_email' => '', //@todo - Limpiar y dejar solamente el del env y mail.php principal (mail.from.address)
	'max_email_cron' => 100,
	'utm_email' => '', //@todo - No lo tiene nadie.
	'accounting_email_admin' => '', //Email admin que un lote se ha comprado
	'admin_email_administracion_cc' => '', // Copia de email invoice_pay_admin @todo - Mirar si es posible usar el campo bcc de la tabla sin modificar comportamiento
	'cc_email_valoracion' => '', // Copia oculta. @todo - Idem que admin_email_administracion_cc
	'admin_email_autoformulario' => '', // Cuenta destino de emails desde autoformularios. @todo - Añadir a tabla para poder borrar, al resto añadir valor de app.admin_email
];
