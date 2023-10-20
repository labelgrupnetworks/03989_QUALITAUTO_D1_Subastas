<?php
namespace App\Http\Controllers\externalws\durangallery;

use Request;
use Exception;
use SoapFault;
use SoapClient;
use SoapVar;
use SimpleXMLElement;
use App\libs\EmailLib;
use Hamcrest\Arrays\IsArray;
use App\Http\Controllers\Controller;

class DuranGalleryController extends Controller
{
	public function QueueCall($xml, $function){
		$xml =  str_replace('<?xml version="1.0"?>', "", $xml);
		$xmlElement = new SimpleXMLElement($xml);
		return $this->callWebService($xmlElement, $function);
	}


	protected function callWebService($xml, $function){

		try{

			$endpoint = \Config::get("app.endpointWS") ; #"http://213.0.23.84/sa3/publico.svc";
			$wsdlFile= $endpoint."?wsdl";
			//Creación del cliente SOAP
			$soapClient = new SoapClient($wsdlFile,array(
			'location'=>$endpoint,
			'trace'=>true,
			'exceptions'=>true));

			$stringXML = str_replace('<?xml version="1.0"?>', "", $xml->asXML());
			$sequencia = new \stdClass();
			$sequencia->parametro= new SoapVar($stringXML,XSD_STRING);
			$sequencia->funcion= new SoapVar($function,XSD_STRING);



			$res = $soapClient->AccesoWb($sequencia);

			if(empty($res->AccesoWbResult)){
				$this->ErrorLog($function ,$stringXML, $soapClient->__getLastResponse());
				$this->sendEmailError($function,htmlspecialchars($stringXML), $soapClient->__getLastResponse(), true );
				return;
			}

			$xmlRes =  new SimpleXMLElement($res->AccesoWbResult);

			if( request("debug") || env('APP_DEBUG') ){

				\Log::info("Peticion:<p>".htmlspecialchars($stringXML)."</p>");
				\Log::info("<p> respuesta </p>");
				\Log::info( print_r($xmlRes,true));

			}

			if($xmlRes->resultado != 0){
				$this->ErrorLog($function ,$stringXML,  print_r($xmlRes,true));
				#si el valor es 1 ha habido un error no controlado y enviamos email a tecnico y nosotros
				if($xmlRes->resultado == 1){
					$this->sendEmailError($function,htmlspecialchars($stringXML), print_r($xmlRes,true),true );
				}
			}




			return $xmlRes;
		}catch (SoapFault $e){
			echo "Error".$e;
			$this->sendEmailError($function,"", $e->faultstring,true );
			$this->ErrorLog($function ,$stringXML?? "",  "");
		}catch (Exception $e){
			echo "Error".$e;
			$this->sendEmailError($function,"", $e->getMessage(),true );
			$this->ErrorLog($function ,$stringXML?? "",  "");
		}

	}

	protected function  ErrorLog($function, $stringXML, $info ){
		\Log::info("Error web Service  Duran,  funcion $function,  info $info".$stringXML);

	}

	#funcion bñásica de crear XMl , si algun modelo requiere algo diferente se implementara en el modelo
	protected function createXML($fields){
		$xml = new SimpleXMLElement("<root></root>");

		foreach($fields as $key => $field){
			#el & no se transforma lo que provoca errores, hay que codificarlo
			$field= str_replace("&","&amp;", $field);
			$xml->addChild($key, $field);
		}
		return $xml;

	}

	protected function sendEmailError($function,$request, $response, $sendExternalAdmin = false ){

		$email = new EmailLib('WS_ERROR');
		if(!empty($email->email)){

			if($sendExternalAdmin){
				$email->setTo("jmanuel@pedroduran.com");
				$email->setBcc("rsanchez@labelgrup.com");
			}else{
				$email->setTo("rsanchez@labelgrup.com");
			}
			$email->setAtribute("FUNCTION", $function);
			$email->setAtribute("REQUEST", $request);
			$email->setAtribute("RESPONSE", $response);
			$email->send_email();
		}
	}





}
