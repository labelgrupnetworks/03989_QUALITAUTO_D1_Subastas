<?php

namespace App\Models\delivery;

class Delivery_default extends DeliveryService
{
	function getBasePrice() {
		return false;
	}

	function getCodesProvider() {
		return false;
	}

	function getShipmentsRates($warehouse,  $destinationCountryCode, $destinationZipCode, $lot) {
		return false;
	}

	function getTax() {
		return false;
	}

	function newShipment($custom_dir,$warehouse,$carrier_code, $service_code, $lot, $shipping_client_ref) {
		return false;
	}

	function setInsurance($insurance) {}
	function setTax($tax) {}

    function getError() {
		return false;
	}

    function getSuccess() {
		return false;
	}

}
