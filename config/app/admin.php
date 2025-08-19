<?php

return [
	'admin_active_auctions' => 'W,P,O,V,E,M,I',
	'admin_upload_first_session' => true,
	'camposNuevosArtista' => null,
	'newsletterFamilies' => '',
	'payAwards' => null,
	'useExtraInfo' => false,
	'use_fxsecmap_excel' => false,
	'useProviders' => false,
	'increment_endlot_online' => 60,
	'admin_default_auction_state' => null, //Tipo de subasta a filtrar por defecto
	'restrictAccessIfNotAdmin' => null, //Bloquer acceso a listado y ficha de subastas administrador sin ser usuario admin
	'admin_client_dni' => false, //Gestionar DNI desde la administración de clientes
	'measurementCodeInStock' => false, // muestra las medidas de la obra en el listado de stock (el valor es el ID de la caracteristica)
	'techniqueCodeInStock' => false, // muestra la técnica de la obra en el listado de stock (el valor es el ID de la caracteristica)
	'adminShowCreateDate' => false, // mostrar campo de create date del lote
	'stockIni' => 0, // valor inicial del stock al crear un lote

];
