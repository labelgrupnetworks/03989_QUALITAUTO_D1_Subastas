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
	'adminHideDescription' => false, // ocultar campo descripción por que no se va a usar
	'artistNameToFeature' => false, // Este condicional hace que se muestre o no se muestre el selector de FgCaracteristicas_Value @todo - Solo lo tiene ansorena y como false.
	'exportExcelExhibition' => false, // Condicional para que se muestre el botón de exportar por excel en el admin de ansorena gallery
	'exportPdfExhibition' => false, // Imprimir PDF de las obras que se muestran en la exposición
	'printExhibitionLabels' => '', // Config para que sea visible el botón de impresión en el index de subastas del admin y que guarde la URL de descarga del PDF
	'HideEditLotOptions' => '', // Quita las opciones marcadas al editar el lote @todo - solo lo utiliza ansorena galeria.
	'lotAdminCalcCostPrice' => false, // Calculará el precio de coste en base a al precio de salida y a la comision
	'owner100x100' => false, // Asignar al propietario el 100 de ratio de la obra en la tabla FGFAMART. @todo - Solo lo tiene ansorena gallery y desactivado
	'propHces0' => null, // Id de propietario por defecto al crear hoja de cesión (hces0). @todo - API
	'moveLot' => false, // Saca el modal de mover el lote @todo - Duran NFT
	'ApiNoErrorDeleteNotExistOrder' => false, //No mostrar la exepcion de error al eliminar si no existe le indice indicado @todo - API
	'show_operadores' => false, // Gestionar operadores
	'external_id' => false, // Uso de cod2_cli como identificador en el admin
	'admin_awards_params' => null, // Parametros personalizados en tabla adjudicaciones
	'admin_notawards_params' => null, // Parametros personalizados en tabla de lotes no adjudicados
	'featuresInAdmin' => false, // Carateristicas a mostrar en tabla de adjudicaciones
	'ShowEditLotOptions' => null, // Mostrar diferentes opciones en admin
	'surface_euro' => false, // Calcular importe m2 en excel adjudicaciones
	'admin_default_deposit_state' => null, // Estado por defecto a filtrar en tabla de depositos
	'lot_api_integrations' => false, // Integraciones de lotes con API externa
	'use_panel_sub' => false, // schema campos extra subasta

];
