<?php

namespace App\Models\delivery;

abstract class DeliveryService
{
	abstract function getBasePrice();
	abstract function getCodesProvider();
	abstract function getShipmentsRates($warehouse,  $destinationCountryCode, $destinationZipCode, $lot);
	abstract function getTax();
	abstract function newShipment($custom_dir,$warehouse,$carrier_code, $service_code, $lot, $shipping_client_ref);
	abstract function setInsurance($insurance);
	abstract function setTax($tax);
    abstract function getError();
    abstract function getSuccess();
}
