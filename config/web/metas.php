<?php

return [
	'admin' => [
		'admin_active_auctions' => [
			'descripcion' => 'Subastas activas en el panel de administración',
			'type' => 'select_multiple',
			'values' => [
				'W' => 'W',
				'P' => 'P',
				'O' => 'O',
				'V' => 'V',
				'E' => 'E',
				'M' => 'M',
				'I' => 'I'
			],
		],
		'admin_upload_first_session' => [
			'descripcion' => 'Admin, preguntar o no por actualizar la primera sesión al actualizar datos de subasta',
			'type' => 'boolean'
		]
	],
	'behavior' => [
		'adjudicacion_reserva' => [
			'descripcion' => 'Adjudicar lote al llegar a precio de reserva',
			'type' => 'boolean'
		]
	],
	'display' => [
		'add_calendar_feature' => [
			'descripcion' => 'Mostrar botón para exportar subastas a calendarios',
			'type' => 'boolean'
		]
	],
	'global' => [
		'apikey' => [
			'descripcion' => 'API Key para llamadas a api interna',
			'type' => 'string'
		],
	],
	'mail' => [
		'admin_email' => [
			'descripcion' => 'Email del administrador',
			'type' => 'string'
		],
		'admin_email_administracion' => [
			'descripcion' => 'Email del administrador de administración',
			'type' => 'string'
		]
	]
];
