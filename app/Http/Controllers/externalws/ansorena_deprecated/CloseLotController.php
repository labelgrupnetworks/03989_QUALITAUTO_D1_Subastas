<?php
namespace App\Http\Controllers\externalws\ansorena;

use Config;
use App\Http\Controllers\Controller;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;

use App\Jobs\SoapJob;
class CloseLotController extends AnsorenaController
{


	public function createCloseLot($codSub, $ref){

			$xml = $this->createXMLCloseLot( $codSub, $ref);
			if(empty($xml)){
				return;
			}
			$theme  = \Config::get('app.theme');
			#dejar las dobles barras si no no va,
			$rutaController = "App\Http\Controllers\\externalws\\$theme\CloseLotController";
			#encolamos la petición, el xml debe pasarse como texto no como objeto

			SoapJob::dispatch($xml->asXML(), "CreateAdjudicacion", $rutaController)->onQueue( Config::get('app.queue_env'));



	}

	public function createCloseLotOnline($codSub, $ref){

		$xml = $this->createXMLCloseLot( $codSub, $ref);
		if(empty($xml)){
			return;
		}

		//Al ejecutarse por cron  los lotes cerrados de la online hacemos la llamada directamente, no es necesario las colas
		$res = $this->callWebService($xml,"CreateAdjudicacion");

}
	public function responseCloseLot($res){
		#por si fuera necesario tratar la respuesta
	}



	private function createXMLCloseLot( $codSub, $ref){


		$lot = FgAsigl0::SELECT("IDORIGEN_ASIGL0, FFIN_ASIGL0, HFIN_ASIGL0, COMLHCES_ASIGL0, NUMHCES_ASIGL0, LINHCES_ASIGL0 ")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();
		$adjudicacion = FgCsub::SELECT("COD2_CLI, nvl(HIMP_CSUB,0) HIMP_CSUB, nvl(BASE_CSUB,0) BASE_CSUB, FECHA_CSUB,PUJREP_ASIGL1 ")->LeftJoinCli()->JoinWinnerBid()->WHERE("SUB_CSUB", $codSub)->WHERE("REF_CSUB", $ref)->first();

		#no hay adjudicacion no hay que enviar nada
		if(empty($adjudicacion)){
			\Log::Info("no hay adjudicacion, no se puede enviar nada");
			return ;
		}

		if( empty($lot)){
			$textEmail = "No se ha podido realizar la llamada a  CreateAdjudicacion”, lote no existe<br> codsub: $codSub <br> ref: $ref   ";
			$this->sendEmailError("wbResultados", $textEmail,""  );
			return;
		}

		##### Fuerzo esta subasta por que no tenemos otra manera de probarlo de momento
		#$codSub= "359";
		#$lot->idorigen_asigl0 = 106;
		############
		$idOrigenClient = $adjudicacion->cod2_cli?? "GENERICO";


		$bid =  number_format(floatval($adjudicacion->himp_csub),2);
		$commission = number_format(floatval($adjudicacion->base_csub),2);

		$xml = new SimpleXMLElement("<root></root>");
		$xml->addChild("pIdOriginLot", $lot->idorigen_asigl0);
		$xml->addChild("pIdAuction",  $codSub);
		$xml->addChild("pIdOriginClient",$idOrigenClient);
		$xml->addChild("pBid",$bid );
		$xml->addChild("pCommission",$commission  );
		$xml->addChild("pDate",  date("m/d/Y h:i:s", strtotime($adjudicacion->fecha_csub)));
		$xml->addChild("pInvoice","" );
		$xml->addChild("pSerialPay","" );
		$xml->addChild("pNumberPay","" );

		return $xml;


	}




}
