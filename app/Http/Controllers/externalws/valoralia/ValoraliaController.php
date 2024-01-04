<?php

namespace App\Http\Controllers\externalws\valoralia;

use Throwable;
use Illuminate\Support\Facades\Log;
use App\libs\EmailLib;
use App\Http\Controllers\Controller;

class ValoraliaController extends Controller
{
	public function QueueCall($arguments)
	{
		$parameters = $arguments['parameters'];
		$function = $arguments['function'];
		return $this->callWebService($parameters, $function);
	}

	function prueba($parameters, $function)
	{
		return $this->callWebService($parameters, $function);
	}

	protected function callWebService($parameters, $function)
	{
		try {
			$response = (new DiarioDeSubastasController())->{$function}($parameters);
			return $this->response($function, null, $response['message'] ?? '', true);
		} catch (WsLotException $exception) {
			return $this->response($function, $exception->getRequest(), $exception->getMessage(), false, $exception->getResponse());
		} catch (Throwable $exception) {
			return $this->response($function, $parameters, $exception->getMessage(), false);
		}
	}

	protected function sendEmailError($function, $request, $response, $sendExternalAdmin = false)
	{
		$email = new EmailLib('WS_ERROR');
		if (!empty($email->email)) {

			$email->setTo("enadal@labelgrup.com");
			$email->setAtribute("FUNCTION", $function);
			$email->setAtribute("REQUEST", $request);
			$email->setAtribute("RESPONSE", $response);
			$email->send_email();
		}
	}

	protected function customProperties()
	{
		return [
			//relaccion se secciones con sus tipos y subtipos
			'sections' => [
				'VI' => [
					'type' => 'Inmueble',
					'subtype' => 'Vivienda',
				],
				'LO' => [
					'type' => 'Inmueble',
					'subtype' => 'Local comercial',
				],
				'PG' => [
					'type' => 'Inmueble',
					'subtype' => 'Garaje',
				],
				'OC' => [
					'type' => 'Inmueble',
					'subtype' => 'Otros',
				],
				'TS' => [
					'type' => 'Inmueble',
					'subtype' => 'Solar',
				],
				'NI' => [
					'type' => 'Inmueble',
					'subtype' => 'Nave industrial',
				],
				'IS' => [
					'type' => 'Inmueble',
					'subtype' => 'Otros',
				],
				'UP' => [
					'type' => 'Unidad productiva',
					'subtype' => 'Unidades productivas',
				],
				'VH' => [
					'type' => 'Vehículo',
					'subtype' => 'Turismos',
				],
				'VD' => [
					'type' => 'Vehículo',
					'subtype' => 'Industriales',
				],
				'MA' => [
					'type' => 'Bien mueble',
					'subtype' => 'Maquinaria',
				],
				'MO' => [
					'type' => 'Bien mueble',
					'subtype' => 'Otros bienes y derechos',
				],
				'TR' => [
					'type' => 'Inmueble',
					'subtype' => 'Trastero',
				],
				'AD' => [
					'type' => 'Bien mueble',
					'subtype' => 'Otros bienes y derechos',
				],
			],
			//caracterisisitcas propias y sus ids
			'features' => [
				'calle' => 1,
				'localidad' => 2,
				'codigo_postal' => 3,
				'provincia' => 4,
				'comunidad_autonoma' => 5,
				'ref_catastral' => 6,
				'cargas' => 7,
				'visitable' => 8,
			],
		];
	}

	private function response($function, $request, $message, $isSuccess, $data = [])
	{
		if (!$isSuccess) {
			Log::error("Error web Service Valoralia, función $function", ['request' => $request, 'response' => $message, 'data' => $data]);
			$this->sendEmailError($function, json_encode($request), json_encode($message));
		} else {
			Log::debug("response $function ws", ['function' => $function, 'response' => $message]);
		}

		return [
			'message' => $message,
			'status' => $isSuccess ? 'success' : 'error',
			'data' => $data,
		];
	}
}
