<?php

namespace App\Http\Controllers\externalws\valoralia;

class WsLotController extends ValoraliaController
{
	public function upsertLot($arguments)
	{
		$function = "upsertAuction";
		$arguments['customProperties'] = $this->customProperties();

		return $this->callWebService($arguments, $function);
	}
}



