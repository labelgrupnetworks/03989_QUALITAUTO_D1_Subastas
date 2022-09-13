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
		$this->callWebService($parameters, $function);
	}

	protected function callWebService($parameters, $function)
	{
		//$apiKey = config('app.zoho_api_key');
		$apiKey = '1003.a1c70826ffffe77364be3e836eac66e8.d3194b509770e42de930596fe0098895';
		try {

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://www.zohoapis.eu/crm/v2/functions/$function/actions/execute?auth_type=apikey&zapikey=$apiKey",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false, //commentar en produccion
				CURLOPT_SSL_VERIFYHOST => false, //commentar en produccion
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array('arguments' => json_encode($parameters))
			));

			$responseJson = curl_exec($curl);

			curl_close($curl);
			$response = json_decode($responseJson, true);
			//{"code":"success","details":{"output": "Contacto creado correctamente. conctactId: null","output_type":"string","id":"130292000000008001"},"message":"function executed successfully"}

			if(empty($response) || $response['code'] != 'success') {
				$this->ErrorLog($function, $parameters, $response ?? '');
				$this->sendEmailError($function, htmlspecialchars(json_encode($parameters)), htmlspecialchars($responseJson));
				return false;
			}

			//cuando error:
			//"{"code":"INVALID_DATA","details":{"expected_data_type":"date","maximum_length":20,"api_name":"Date_of_Birth"},"message":"invalid data","status":"error"}"

			$responseOutput = json_decode($response['details']['output'], true);
			if(empty($responseOutput) || !empty($responseOutput['status'])) {
				$this->ErrorLog($function, $parameters, $responseOutput ?? '');
				$this->sendEmailError($function, htmlspecialchars(json_encode($parameters)), htmlspecialchars($response['details']['output'] ?? ''));
				return false;
			}


			Log::info("petición $function enviada con exito", $responseOutput);
			return $responseOutput;

		} catch (\Throwable $th) {
			Log::info($th->getMessage());
			$this->sendEmailError($function, "", $th->getMessage());
			return false;
		}
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
