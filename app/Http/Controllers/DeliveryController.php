<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PaymentsController;
use App\Models\delivery\Delivery_default;
use App\Models\delivery\Delivery;
use App\Providers\ToolsServiceProvider;
use App\Services\User\UserAddressService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Session;

/**
 * Description of DeliveryController
 *
 * @author LABEL-RSANCHEZ
 */
class DeliveryController  extends Controller
{
	//put your code here

	public function getShipmentRate($cod_sub = null, $ref = null, $zip_code = null, $country_code = null)
	{

		$status = NULL;
		$msg = "";
		$price = NULL;
		$licencia_exp = false;

		$cod_sub   = !empty(Input::get('cod_sub')) ? Input::get('cod_sub') : $cod_sub;
		$ref = !empty(Input::get('ref')) ? Input::get('ref') : $ref;
		$destinationZipCode  = !empty(Input::get('zip_code')) ? Input::get('zip_code') : $zip_code;
		$destinationCountryCode   = !empty(Input::get('country_code')) ? Input::get('country_code') : $country_code;
		$lang = !empty(Input::get('lang')) ? Input::get('lang') : App::getLocale();
		$emp = Config::get('app.emp');

		App::setLocale(strtolower($lang));
		$delivery = new Delivery(new Delivery_default());
		$value_delivery = $delivery->getShipmentsRates($emp, $cod_sub, $ref, $destinationCountryCode, $destinationZipCode);

		if (empty($value_delivery) || empty($delivery->getBasePrice())) {
			$res = array(
				'status' => 'error',
				'msg' => trans(Config::get('app.theme') . '-app.msg_deliverea.no_price_return')
			);
		} else {
			/* calculo de licencias de exportación */

			$payment = new PaymentsController();
			$cod_lic_exp = $payment->licenciaDeExportacion($ref, $cod_sub);

			$lic_exp = false;

			if ($cod_lic_exp == 1 && $destinationCountryCode != 'ES') {
				$lic_exp = true;
			} else {

				$countries = ToolsServiceProvider::PaisesEUR();
				//si el código es 2 y el pais no es de la union europea
				if ($cod_lic_exp == 2 && !in_array($destinationCountryCode, $countries)) {
					$lic_exp = true;
				}
			}

			$price = $delivery->getBasePrice() + $delivery->getTaxPrice();

			$res = array(
				'status' => 'success',
				'price' => ToolsServiceProvider::moneyFormat($price, false, 2),
				'msg' => trans(Config::get('app.theme') . '-app.msg_deliverea.shipping_cost'),
				'licencia_export'    => $lic_exp
			);
		}
		return $res;
	}

	public function getShipmentDelivery()
	{
		$delivery = new Delivery(new Delivery_default());
		$userId = Session::get('user.cod');
		$cod_dir = Input::get('cod_dir');
		$cod_sub = Input::get('sub');
		$ref = Input::get('ref');
		$emp = Config::get('app.emp');

		$res = array(
			'status' => 'error',
			'embalaje' => 0,
			'transporte' => 0,
			'iva_transporte' => 0,
			'embalaje_iva' => 0,
			'cod_pais'   => null,
			'iva' => 0

		);

		$addressService = new UserAddressService();

		$custom_dir = $addressService->getUserAddressById($userId, $cod_dir);

		if (empty($custom_dir)) {
			return $res;
		}

		$custom_dir->email_clid = !empty($custom_dir->email_clid) ? $custom_dir->email_clid : Session::get('user.usrw');
		$destinationCountryCode = $custom_dir->codpais_clid;
		$destinationZipCode = $custom_dir->cp_clid;
		$value_delivery = $delivery->getShipmentsRates($emp, $cod_sub, $ref, $destinationCountryCode, $destinationZipCode);

		if (empty($value_delivery)) {
			$res['error'] = $delivery->getError();
			return $res;
		}
		$imp = $delivery->getBasePrice();
		$imp_iva = $delivery->getTaxPrice();

		$carrier_code = $delivery->getCodesProvider()->carrier_code;
		$service_code = $delivery->getCodesProvider()->service_code;

		if ($delivery->setCsube($cod_sub, $ref, $custom_dir, $carrier_code, $service_code, $imp, $imp_iva) == true) {
			$res['status'] = 'success';
			$res['iva_transporte'] = (float)$imp_iva;
			$res['transporte'] = (float)$imp;
			$res['cod_pais'] = $custom_dir->codpais_clid;
			$res['iva'] = $delivery->getTax();
		}

		return $res;
	}
}
