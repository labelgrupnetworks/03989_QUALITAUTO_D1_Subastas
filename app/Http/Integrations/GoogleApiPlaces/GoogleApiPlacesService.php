<?php

namespace App\Http\Controllers\Integrations\GoogleApiPlaces;

class GoogleApiPlacesService
{
	public static function googleReviews($daysToReload)
	{
		$apiGoogle = new GoogleApiPlaces();
		return $apiGoogle->getReviews($daysToReload);
	}
}
