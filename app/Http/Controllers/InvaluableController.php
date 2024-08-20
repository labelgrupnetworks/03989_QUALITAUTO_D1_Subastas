<?php

namespace App\Http\Controllers;

use App\Http\Controllers\externalAggregator\Invaluable\House;

class InvaluableController extends Controller
{
	protected $house;

	public function __construct(House $house)
	{
		$this->house = $house;
	}

	public function token()
	{

		return $this->house->getToken();
	}

	public function groupSettings($houseUserName)
	{

		return $this->house->groupSettings($houseUserName);
	}
	public function listContacts($houseUserName)
	{

		return $this->house->listContacts($houseUserName);
	}
	public function addresses($houseUserName)
	{

		return $this->house->addresses($houseUserName);
	}

	public function channels($houseUserName)
	{

		return $this->house->channels($houseUserName);
	}

	public function catalogos($houseUserName, $codSubasta, $sessionID)
	{

		$result = $this->house->catalogs($houseUserName, $codSubasta, $sessionID);

		$response = json_decode($result->getContent());

		return ($response->success) ? $response->message : $response->error;
	}

	public function lots($houseUserName)
	{

		return $this->house->lots($houseUserName);
	}

	public function deleteLot($houseUserName, $codSubasta, $sessionID, $lotNumber)
	{

		$result =  $this->house->deletelot($houseUserName, $codSubasta, $sessionID, $lotNumber);

		$response = json_decode($result->getContent());

		return ($response->success) ? $response->message : $response->error;
	}

	public function updateLot($houseUserName, $codSubasta, $sessionID, $lotNumber)
	{

		$result =  $this->house->updateLot($houseUserName, $codSubasta, $sessionID, $lotNumber);

		$response = json_decode($result->getContent());

		return ($response->success) ? $response->message : $response->error;
	}
}
