<?php

return [
	'DeleteOrders' => false, //Pueden borrar ordenes de la subasta W antes de que empiece en el panel de usuario,
	'delivery_address' => false, //Permite gestionar múltiples direcciones de entrega.
	'fpag_default' => 0, // Define el valor por defecto de la forma de pago del cliente, este valor se guardará en el campo FPAG_CLI de la tabla FXCLI.
	'fpag_foreign_default' => null, //Valor por defecto de la forma de pago del cliente no español
];
