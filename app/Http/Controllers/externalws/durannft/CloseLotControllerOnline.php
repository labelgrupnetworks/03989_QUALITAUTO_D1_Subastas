<?php

namespace App\Http\Controllers\externalws\durannft;
use Config;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;
use App\Jobs\SoapJob;
class CloseLotControllerOnline extends DuranNftController
{


	public function createCloseLot($codSub, $ref){


			$xml = $this->createXMLCloseLot( $codSub, $ref);

			//Al ejecutarse por cron hacemos la llamada directamente, noes necesario las colas
			$res = $this->callWebService($xml,"wbResultadoso");

			#enviar email de adjudicacion
			$mailController = new MailController();
			$mailController->sendEmailCerradoGeneric(\Config::get("app.emp"), $codSub, $ref);


		/*
			$theme  = \Config::get('app.theme');
			#dejar las dobles barras si no no va,
			$rutaController = "App\Http\Controllers\\externalws\\$theme\CloseLotControllerOnline";
			#encolamos la petición, el xml debe pasarse como texto no como objeto
			SoapJob::dispatch($xml->asXML(), "wbResultadoso", $rutaController, "responseCloseLot")->onQueue( Config::get('app.queue_env'));
		*/
	}
	public function responseCloseLot($res){
		#por si fuera necesario tratar la respuesta
	}



	private function createXMLCloseLot( $codSub, $ref){


		$lot = FgAsigl0::SELECT("IDORIGEN_ASIGL0, FFIN_ASIGL0, HFIN_ASIGL0, COMLHCES_ASIGL0, NUMHCES_ASIGL0, LINHCES_ASIGL0 ")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();
		$adjudicacion = FgCsub::SELECT("COD2_CLI, HIMP_CSUB, PUJREP_ASIGL1 ")->JoinCli()->JoinWinnerBid()->WHERE("SUB_CSUB", $codSub)->WHERE("REF_CSUB", $ref)	->first();

		if( empty($lot)){
			$textEmail = "No se ha podido realizar la llamada a  wbResultado, usuario o lote no existe<br> codsub: $codSub <br> ref: $ref   ";
			$this->sendEmailError("wbResultados", $textEmail,""  );
			return;
		}

		$codigoarticulo = substr($lot->idorigen_asigl0,-7);



		$xml = new SimpleXMLElement("<root></root>");
		$xml->addChild("codigoarticulo",$codigoarticulo );
		$xml->addChild("referencia",$lot->idorigen_asigl0);
		$xml->addChild("tipocorretaje",$lot->comlhces_asigl0);
		$xml->addChild("momentocierre", date("Y-m-d",strtotime($lot->ffin_asigl0))." ". date("H:i:s",strtotime($lot->hfin_asigl0)));

		if(!empty($adjudicacion)){
			$codigoPersona = $adjudicacion->cod2_cli;
			$importepuja = $adjudicacion->himp_csub;

			$corretaje = ($lot->comlhces_asigl0 /100) * $adjudicacion->himp_csub;
			$total = $importepuja + $corretaje;
			#se ha vendido el lote
			$resultado = 1;

		}else{
			$resultado = 0;
			$codigoPersona = 0;
			$importepuja = 0;
			$corretaje = 0;
			$total = 0;
		}

		$xml->addChild("resultado",$resultado);
		$xml->addChild("codigopersona",$codigoPersona);
		$xml->addChild("importepuja",$importepuja);

		$xml->addChild("corretaje",$corretaje);
		$xml->addChild("total",$total);



		return $xml;

	}




}
