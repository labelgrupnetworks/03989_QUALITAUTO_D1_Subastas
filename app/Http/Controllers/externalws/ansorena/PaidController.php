<?php
namespace App\Http\Controllers\externalws\ansorena;

use Config;
use App\Models\V5\FgCsub0;
use App\Models\V5\FxPcob;
use SimpleXMLElement;



class PaidController extends AnsorenaController
{


	public function informPaid($idTransaction){

			$xmlArray = $this->createXMLInformPaid( $idTransaction);
			if(count($xmlArray) == 0){
				return;
			}
			foreach($xmlArray as $xml){
				//Al ejecutarse por cron hacemos la llamada directamente, no es necesario las colas
				$res = $this->callWebService($xml,"ModPagos");
			}


	}
	public function responseCloseLot($res){
		#por si fuera necesario tratar la respuesta
	}



	private function createXMLInformPaid( $idTransaction){

		//ver tipo de transaccion
		$tipo = substr($idTransaction,0,1) ;

		#si es una factura
		if ($tipo == "F"){
			$pagos = FxPcob::select("IMP_PCOB, COD2_CLI, ANUM_PCOB, NUM_PCOB")->where("IDTRANS_PCOB0", $idTransaction)->joincli()->joinpcob1()->joinpcob0()->get();
		}else{
			\Log::info("tipo de pago no contemplado en webservice Ansorena");
		}

		#esto sería para ver cobros de lotes si la factura fuera del tipo P, pero ansorena no tiene
		/*
		$pago = FGCSUB0::select("IDORIGEN_ASIGL0, COD2_CLI")->where("IDTRANS_CSUB0", $idTransaction)->JoinCsub()->joinAsigl0()->JoinCli()->first();
		*/


		#no hay pago
		if(count($pagos) == 0){
			\Log::info("Transaccion no encontrada, $idTransaction");
			return ;
		}
		$xmlArray = array();
		foreach ($pagos as $pago){
			$amount = number_format(floatval($pago->imp_pcob),2);

			$xml = new SimpleXMLElement("<root></root>");
			$xml->addChild("pSerial", $pago->anum_pcob);
			$xml->addChild("pNumber",  $pago->num_pcob);
			$xml->addChild("pIdOriginCli",$pago->cod2_cli);
			$xml->addChild("pAmount", $amount);
			$xml->addChild("pPaid","TRUE");
			$xml->addChild("pDate",  date("m/d/Y h:i:s"));
			$xmlArray[] =$xml;

		}
		#dos decimales


		return $xmlArray;
	}




}
