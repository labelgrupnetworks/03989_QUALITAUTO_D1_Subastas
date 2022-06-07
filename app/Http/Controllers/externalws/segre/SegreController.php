<?php
namespace App\Http\Controllers\externalws\Segre;

use Request;
use Exception;
use stdClass;

use SimpleXMLElement;
use App\libs\EmailLib;
use Hamcrest\Arrays\IsArray;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class SegreController extends Controller
{
	public function QueueCall($parameters, $function){

		return $this->callWebService($parameters, $function);
	}

	function prueba( $parameters, $function){
		$this->callWebService( $parameters, $function);
	}

	protected function callWebService( $parameters, $function){

		try{

			$user = "UsLabelGrup";
			$password="MyPassw01!!";
			$method= "POST";
			$url = "https://subastassegre.es/API/ApiService.svc/Adjudicaciones";

			$clientGuzz = new Client();


			$response = $clientGuzz->request($method, $url,[
				'headers' => [
					'Content-Type' => 'text/plain',
					'Accept' => '*/*',
				  ],
				   'auth' => [$user, $password],
				   'verify' => false,
				   'body' => $parameters

				]);
				if(empty($response) || empty($response->getBody())){
					$this->ErrorLog($function ,json_encode($parameters), "");
					$this->sendEmailError($function,htmlspecialchars(json_encode($parameters)),"", true );
					return;
				}
				$body = json_decode($response->getBody());
				if($body->IdError != 0){
					$this->ErrorLog($function ,json_encode($parameters),$response->getBody()  );
					return;
				}
				\Log::info("petición con exito: ".print_r($body, true));
			return $body;


		}catch (Exception $e){
			\Log::info($e);
			# de momento no enviamos a Anselmo, si no a subastas
			$this->sendEmailError($function,"", $e->getMessage(),false );
		}

	}

	protected function  ErrorLog($function, $stringXML, $info ){
		\Log::info("Error web Service  Segre,  funcion $function,  info $info".$stringXML);

	}


	protected function sendEmailError($function,$request, $response, $sendExternalAdmin = false ){

		$email = new EmailLib('WS_ERROR');
		if(!empty($email->email)){

			if($sendExternalAdmin){
				$email->setTo("aizard@aquilum.es");
				$email->setBcc("rsanchez@labelgrup.com");
			}else{
				$email->setTo("rsanchez@labelgrup.com");
				$email->setBcc("subastas@labelgrup.com");
			}
			$email->setAtribute("FUNCTION", $function);
			$email->setAtribute("REQUEST", $request);
			$email->setAtribute("RESPONSE", $response);
			$email->send_email();
		}
	}





}
