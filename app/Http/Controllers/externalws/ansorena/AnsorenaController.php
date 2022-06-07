<?php

namespace App\Http\Controllers\externalws\ansorena;

use Request;
use SimpleXMLElement;
use App\Http\Controllers\Controller;
use Hamcrest\Arrays\IsArray;
use App\libs\EmailLib;

class AnsorenaController extends Controller
{
	public function QueueCall($xml, $function)
	{
		$xml =  str_replace('<?xml version="1.0"?>', "", $xml);
		$xmlElement = new SimpleXMLElement($xml);
		return $this->callWebService($xmlElement, $function);
	}


	public function callWebService($xml, $function)
	{
		try {
			#traduccion del xml para poder mostrarlo por log
			$stringXML =  str_replace('<?xml version="1.0"?>', "", $xml->asXML());

			$url = 'http://212.36.76.223:7047/NAVBC/WS/ANSORENA/Codeunit/WSLabelGroup';
			$config = new \matejsvajger\NTLMSoap\Common\NTLMConfig([
				'domain'   => \Config::get("app.DomainWebService"),
				'username' =>\Config::get("app.UserWebService"),
				'password' => \Config::get("app.PasswordWebService")
			]);

			$soapClient = new \matejsvajger\NTLMSoap\Client($url, $config);


			$res = $soapClient->$function($xml);


			if (empty($res) || empty($res->return_value)) {

				$this->ErrorLog($function, $stringXML, print_r($res,true));
				$this->sendEmailError($function, htmlspecialchars($stringXML), print_r($res,true), true);
				return;
			}
			#temporal
			$objectRes = json_decode($res->return_value);



			if (empty($objectRes->codigo) || $objectRes->codigo != "OK") {

				$this->ErrorLog($function, $stringXML, print_r($res,true));
				$this->sendEmailError($function, htmlspecialchars($stringXML), print_r($res,true), true);
				return;
			}


			\Log::info("Peticion: " . $stringXML);
			\Log::info("Respuesta Ansorena " . print_r($objectRes, true));


			return $objectRes;
		} catch (\SoapFault $fault) {
				/***** respuesta  *****/

				$this->ErrorLog($function, $stringXML, "SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})");



				/***** respuesta  *****/
			return ;
		}
	}

	protected function  ErrorLog($function, $stringXML, $info)
	{
		\Log::info("Error web Service  Ansorena,  funcion $function,  info $info" . $stringXML);
	}

	#funcion bñásica de crear XMl , si algun modelo requiere algo diferente se implementara en el modelo
	protected function createXML($fields)
	{
		$xml = new SimpleXMLElement("<root></root>");
		foreach ($fields as $key => $field) {
			#el & no se transforma lo que provoca errores, hay que codificarlo
			$field= str_replace("&","&amp;", $field);
			$xml->addChild($key, $field);
		}
		return $xml;
	}

	protected function sendEmailError($function, $request, $response, $sendExternalAdmin = false)
	{
		\Log::info("sendemail error" . $request);
		$email = new EmailLib('API_ERROR');
		if (!empty($email->email)) {

			if ($sendExternalAdmin) {
				$email->setTo("jmanuel@pedroansorena.com");
				$email->setBcc("rsanchez@labelgrup.com");
			} else {
				$email->setTo("rsanchez@labelgrup.com");
			}
			$email->setAtribute("FUNCTION", $function);
			$email->setAtribute("REQUEST", $request);
			$email->setAtribute("RESPONSE", $response);
			$email->send_email();
		}
	}

	#sirve para generar campos con un numero de carcateres
	protected function generar_valores_ejemplo($val, $numChar)
	{
		$text = substr($val, 0, $numChar);
		#rellenamos el espacio que falta con A
		for ($i = strlen($text); $i < $numChar; $i++) {
			$text .= "A";
		}
		return $text;
	}
}
