<?php

namespace App\Http\Controllers\externalws\bogota;

use App\Http\Controllers\Controller;
use App\libs\EmailLib;
use Illuminate\Support\Facades\Log;

class BogotaController extends Controller
{
	public function QueueCall($parameters, $function)
	{
		return $this->callWebService($parameters, $function);
	}

	function prueba($parameters, $function)
	{
		return $this->callWebService($parameters, $function);
	}

	protected function callWebService($parameters, $function)
	{
		return (new ZohoController())->{$function}($parameters);
	}

	protected function errorLog($function, $parameters, $response)
	{
		Log::info("Error web Service Bogota, función $function", ['request' => $parameters, 'response' => $response]);
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
}
