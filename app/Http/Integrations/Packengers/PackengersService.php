<?php

namespace App\Http\Integrations\Packengers;

class PackengersService
{
	public static function getAuctionExportFile($codSub)
	{
		return new PackengersAuctionExport($codSub);
	}

	public static function getAuctionSessionExportFile($idAucSession)
	{
		return new PackengersSessionExport($idAucSession);
	}
}
