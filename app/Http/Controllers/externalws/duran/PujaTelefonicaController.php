<?php
namespace App\Http\Controllers\externalws\duran;


use App\Http\Controllers\Controller;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use Request;
use Session;
class PujaTelefonicaController extends DuranController
{

	#Comuníca la puja telefónica
	public function createTelefonica(){
		$codCli = Session::get('user.cod');

		#el usuario debe estra logeado si no, no se hace nada
		if(empty($codCli)){
			$textEmail = "No se ha podido realizar la orden telefónica, El usuario no está logeado  ";
			$this->sendEmailError("createOrdertelefónica", $textEmail,""  );
			return ["status"=> "error" ];
		}
		$codSub = request("cod_sub");
		$ref = request("ref");
		$comments= request("comments");
		$telefono1 = request("tel1");
		$telefono2 = request("tel2");

		$xml = $this->createXMLTelefonica($codCli, $codSub, $ref, $comments, $telefono1,$telefono2 );

		$res = $this->callWebService($xml,"wbTelefono");

		$stringXML = str_replace('<?xml version="1.0"?>', "", $xml->asXML());
		if(!empty($res)){


			if ($res->resultado == 0) {
				return ["status"=> "success" ];

			}else{
					$service="wbTelefono";

				$this->sendEmailError($service,htmlspecialchars($stringXML), print_r($res,true) );
				return ["status"=> "error" ];
			}
		}

		return ["status"=> "error" ];
	}

	private function createXMLTelefonica($codCli, $codSub, $ref, $comments, $telefono1,$telefono2){

		$codigoPersona = FxCli::SELECT("COD2_CLI")->where("cod_cli",$codCli)->first();
		$idOrigen = FgAsigl0::SELECT("IDORIGEN_ASIGL0,IMPSALHCES_ASIGL0")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();

		if(empty($codigoPersona) || empty($idOrigen)){
			$textEmail = "No se ha podido realizar  la puja, usuario o lote no existe<br> codcli: $codCli <br> codsub: $codSub <br> ref: $ref  ";
			$this->sendEmailError("createOrder", $textEmail,""  );
			return;
		}

			$codigoarticulo = substr($idOrigen->idorigen_asigl0,-7);

			$explodeOrder = explode(".",$ref);
			$lote = $explodeOrder[0];
			if(count($explodeOrder) > 1){
				$bis =  str_replace(array("1","2","3", "4", "5"), array("A", "B", "C", "D", "E"), $explodeOrder[1]);
			}else{
				$bis = "";
			}

		$xml = new SimpleXMLElement("<root></root>");
		$xml->addChild("amb", 'A');
		$xml->addChild("codigotelefono", '');
		$xml->addChild("subasta",  $codSub);
		$xml->addChild("lote",$lote );
		$xml->addChild("bis",$bis );
		$xml->addChild("codigoarticulo",$codigoarticulo );
		$xml->addChild("codigopersona",$codigoPersona->cod2_cli );
		$xml->addChild("salida",$idOrigen->impsalhces_asigl0);
		$xml->addChild("telefono1",$telefono1);
		$xml->addChild("telefono2",$telefono2);
		$xml->addChild("telefono3",'');
		$xml->addChild("idioma",1);
		#$xml->addChild("pujahasta",$codigoPersona->cod2_cli);
		$xml->addChild("notas",$comments);




		return $xml;

	}

	public function wbVerBotonTelefono() {

		$codSub = request("cod_sub");
		$ref = request("ref");

		$xml = $this->createXMLVerBotonTelefonica($codSub, $ref);

		$res = $this->callWebService($xml,"wbBotonTelefono");

		$stringXML = str_replace('<?xml version="1.0"?>', "", $xml->asXML());
		if(!empty($res)){
			if ($res->resultado == 0 ) {
				if ($res->habilitado == 1) {
					return ["status" => "success" ];
				} else {

					return ["status" => "no" ];
				}
			} else {
				$service="wbBotonTelefono";
				$this->sendEmailError($service,htmlspecialchars($stringXML), print_r($res,true) );
				return ["status"=> "error" ];
			}
		}

		return ["status"=> "error" ];
	}

	private function createXMLVerBotonTelefonica($codSub, $ref){

		$idOrigen = FgAsigl0::SELECT("IDORIGEN_ASIGL0,IMPSALHCES_ASIGL0")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();

		if(empty($idOrigen)){
			$textEmail = "No se ha podido realizar  la puja, usuario o lote no existe <br> codsub: $codSub <br> ref: $ref  ";
			$this->sendEmailError("createOrder", $textEmail,""  );
			return;
		}

			$codigoarticulo = substr($idOrigen->idorigen_asigl0,-7);

		$xml = new SimpleXMLElement("<root></root>");
		$xml->addChild("subasta",  $codSub);
		$xml->addChild("codigoarticulo",$codigoarticulo );

		return $xml;

	}


}
