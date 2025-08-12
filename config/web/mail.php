<?php

return [
	'admin_email' => 'admin@example.com',
	'admin_email_administracion' => '',
	'copies_emails' => false, //Habilita el envio de copias de los emails al correo definido en copies_emails_mailbox,
	'copies_emails_mailbox' => '', //Correo al que se envian las copias de los emails, si copies_emails es true
	'debug_to_email' => '', //Correo al que se envian los errores de depuracion cuando el modo debug esta habilitado
	'emailApiError' => '', //Correo que va a recibir el correo de error en la API
	'emailsCopyApiError' => '', //Correos que van a recibir copia de del error en la API, deben separarse por comas
	'email_tasacion_client' => false, //Habilita que el usuario reciba un correo de confirmación del envío de la tasación
	'enable_email_buy_user' => false, //Habilita que el usuario reciba un correo de confirmación al comprar un lote
	'enable_emails' => true, //Habilita el envío de correos electrónicos.
	'from_email' => '', //From email @todo - Limpiar y dejar solamente el del env y mail.php principal (mail.from.address)
];
