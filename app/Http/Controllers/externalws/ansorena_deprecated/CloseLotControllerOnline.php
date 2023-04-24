<?php
namespace App\Http\Controllers\externalws\ansorena;


class CloseLotControllerOnline extends AnsorenaController
{
	public function createCloseLot($codSub, $ref){
		$closeLot = new CloseLotController();
			$closeLot->createCloseLotOnline($codSub, $ref);
	}

}
