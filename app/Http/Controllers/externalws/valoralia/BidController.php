<?php
namespace App\Http\Controllers\externalws\valoralia;

use App\Jobs\UniversalJob;

class BidController extends ValoraliaController
{

	/**
	 * @param string $licit
	 * @param string $codSub
	 * @param string $ref
	 * @param string $bid
	 * @param string $tipo tipo de puja(automatica, normal)
	 * @param string $metodo orden o puja
	 * @param string $delete es si la han borrad, solo se pueden borrar las ordenes
	 */
	public function createBid($licit, $codSub, $ref, $bid, $tipo, $metodo, $delete = false)
	{
		$arguments = [
			'function' => 'updateStatusAuction',
			'parameters' => [
				'codSub' => $codSub,
				'refAsigl0' => $ref
			],
		];

		//with job
		UniversalJob::dispatch(self::class, 'QueueCall', $arguments)->onQueue(config('app.queue_env'));

		//without job
		//$this->QueueCall($arguments);
	}
}
