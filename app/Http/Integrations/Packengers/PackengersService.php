<?php

namespace App\Http\Integrations\Packengers;

class PackengersService
{
	public static function getAuctionExportFile($codSub)
	{
		return new PackengersExport($codSub);
	}
}
