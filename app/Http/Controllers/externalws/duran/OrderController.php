<?php
namespace App\Http\Controllers\externalws\duran;


use App\Http\Controllers\Controller;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;

class OrderController extends DuranController
{

	#Comuníca las ordenes de la subasta presencial, las online va por otro circuito
	public function createOrder($codCli, $codSub, $ref, $order, $delete = false){


			$xml = $this->createXMLOrder($codCli, $codSub, $ref, $order);
			if($delete){
				#tiene la misma estructura que hacer puja por eso podemso usar el mismo createOrder

				$res = $this->callWebService($xml,"wbQuitarPuja");
			}else{
				$res = $this->callWebService($xml,"wbHacerPuja");
			}


			$stringXML = str_replace('<?xml version="1.0"?>', "", $xml->asXML());
			if(!empty($res)){
				if ($res->resultado == 2 || $res->resultado == 3) {
					if($delete){
						$service="wbQuitarPuja";
					}else{
						$service="wbHacerPuja";
					}

					$this->sendEmailError($service,htmlspecialchars($stringXML), print_r($res,true) );
				}
			}
	}
	public function deleteOrder($licit, $codSub, $ref, $order){
		#debemos cojer el código usuario ya que nos llega el licitador
		$codCli = FxCli::SELECT("COD_CLI")->join("FGLICIT","EMP_LICIT ='" . \Config::get("app.emp") ."' AND CLI_LICIT = COD_CLI  " )->WHERE("SUB_licit", $codSub)->where("cod_licit",$licit)->first();
		if(empty($codCli) ){
			$textEmail = "No se ha podido realizar la cancelación de la orden, el usuario no existe<br> licit: $licit <br> codsub: $codSub <br> ref: $ref <br> order: $order  ";
			$this->sendEmailError("deleteOrder", $textEmail,""  );
			return;
		}
		$this->createOrder($codCli->cod_cli, $codSub, $ref, $order, true);
	}



	private function createXMLOrder($codCli, $codSub, $ref, $order){

		$codigoPersona = FxCli::SELECT("COD2_CLI")->where("cod_cli",$codCli)->first();
		$idOrigen = FgAsigl0::SELECT("IDORIGEN_ASIGL0")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();

		if(empty($codigoPersona) || empty($idOrigen)){
			$textEmail = "No se ha podido realizar el la puja, usuario o lote no existe<br> codcli: $codCli <br> codsub: $codSub <br> ref: $ref <br> order: $order  ";
			$this->sendEmailError("createOrder", $textEmail,""  );
			die();
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
		$xml->addChild("tipooferta", 1);
		$xml->addChild("usuario", 41);
		$xml->addChild("subasta",  $codSub);
		$pujas = $xml->addChild('pujas');
		$puja = $pujas->addChild('puja');
		$puja->addChild("lote",$lote );
		$puja->addChild("bis",$bis );
		$puja->addChild("codigoarticulo",$codigoarticulo );
		$puja->addChild("codigopersona",$codigoPersona->cod2_cli);
		$puja->addChild("valor",$order );
		$puja->addChild("momento",date("Y-m-d H:i:s"));



		return $xml;

	}





}
