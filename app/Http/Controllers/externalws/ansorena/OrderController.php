<?php
namespace App\Http\Controllers\externalws\ansorena;


use App\Http\Controllers\Controller;
use SimpleXMLElement;

use App\Models\V5\FgOrlic;

class OrderController extends AnsorenaController
{

	#Comuníca las ordenes de la subasta presencial, las online va por otro circuito
	public function createOrder($codCli, $codSub, $ref, $order){

			#no envio el importe (order) si no que lo saco de base de datos, asi está exactamente lo k hay registrado, por si acaso
			$xml = $this->createXMLOrder($codCli, $codSub, $ref, $order);

			$res = $this->callWebService($xml,"CreateOrden");
			# si ya existe, hacer la llamada a modificar.
			if( !empty($res) &&  $res->codigo=="OK" && $res->respuesta == "Error ya existe esta orden"){
				$res = $this->callWebService($xml,"ModOrden");
			}

	}




	private function createXMLOrder($codCli, $codSub, $ref, $order){

		//$codigoPersona = FxCli::SELECT("COD2_CLI")->where("cod_cli",$codCli)->first();
		//$idOrigen = FgAsigl0::SELECT("IDORIGEN_ASIGL0")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();
		#BUSCAR LA ORDEN REALIZADA PARA SACAR TODA LA INFO, TIPO DE ORDEN Y TELEFONOS
		$ordenActual = FgOrlic::SELECT(" IDORIGEN_ASIGL0, COD2_CLI, TIPOP_ORLIC, HIMP_ORLIC, FEC_ORLIC, TEL1_ORLIC, TEL2_ORLIC, TEL3_ORLIC")->JoinCli()->JoinAsigl0()->WHERE("COD_CLI", $codCli)->WHERE("SUB_ORLIC", $codSub)->WHERE("REF_ORLIC", $ref)->first();

		if(empty($ordenActual)){
			$textEmail = "No se ha podido realizar la puja, usuario o lote no existe<br> codcli: $codCli <br> codsub: $codSub <br> ref: $ref <br> order: $order  ";
			$this->sendEmailError("createOrder", $textEmail,""  );
			return;
		}



		##### Fuerzo esta subasta por que no tenemos otra manera de probarlo de momento
		#$codSub= "359";
		#$ordenActual->idorigen_asigl0= 26;
		#$ordenActual->cod2_cli = 5;
		############




		$order =  number_format(floatval($ordenActual->himp_orlic),2);

		$xml = new SimpleXMLElement("<root></root>");
		$xml->addChild("pIdOriginLot", $ordenActual->idorigen_asigl0);
		$xml->addChild("pIdAuction",  $codSub);
		$xml->addChild("pIdOriginClient",$ordenActual->cod2_cli);
		$xml->addChild("pOrder", $order );
		$xml->addChild("pDate",  date("m/d/Y h:i:s", strtotime($ordenActual->fec_orlic)));
		$xml->addChild("pType",$ordenActual->tipop_orlic  );
		$xml->addChild("pPhone1",$ordenActual->tel1_orlic  );
		$xml->addChild("pPhone2",$ordenActual->tel2_orlic  );
		$xml->addChild("pPhone3",$ordenActual->tel3_orlic  );
		return $xml;

	}




}
