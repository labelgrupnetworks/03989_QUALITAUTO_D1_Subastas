<?php
namespace App\Http\Controllers\externalws\segre;


use App\Http\Controllers\Controller;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;
use App\Jobs\SoapJob;
use Config;
Use stdClass;
class CloseLotController extends SegreController
{
	/*******
	 * TESTEO DE PETICION, poner en prueba y lanzar
	 *
	 * $rutaPaidController = "App\Http\Controllers\\externalws\\segre\CloseLotController";
		$paidController = new $rutaPaidController();
		$paidController->createCloseLot("00000141",1);
	 *
	 */


	public function createCloseLot($codSub, $ref){

		$parameters = $this->getParameters($codSub, $ref);
		$theme  = \Config::get('app.theme');
		#dejar las dobles barras si no no va,
		$rutaController = "App\Http\Controllers\\externalws\\$theme\CloseLotController";

		if(!empty($parameters)){
			\Log::info("peticion close lot de parametros: ". print_r($parameters, true));
			SoapJob::dispatch($parameters, "Adjudicaciones", $rutaController, "responseCloseLot")->onQueue( Config::get('app.queue_env'));
		}else{
			\Log::info("Lote $codSub  $ref no adjudicado");
		}

		#petición directa sin pasar por el sistema de colas
		#$this->callWebService( $parameters, "Adjudicaciones");

	}
	public function responseCloseLot($res){
		#por si fuera necesario tratar la respuesta
	}



	private function getParameters( $codSub, $ref){


		$idOrigen = FgAsigl0::SELECT("IDORIGEN_ASIGL0")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();
		$adjudicacion = FgCsub::SELECT("COD2_CLI, HIMP_CSUB, LICIT_CSUB, PUJREP_ASIGL1 ")->LeftJoinCli()->JoinWinnerBid()->WHERE("SUB_CSUB", $codSub)->WHERE("REF_CSUB", $ref)	->first();

		if( empty($idOrigen)){
			$textEmail = "No se ha podido realizar la llamada a  wbResultado, usuario o lote no existe<br> codsub: $codSub <br> ref: $ref   ";
			$this->sendEmailError("wbResultados", $textEmail,""  );
			return;
		}

		$parameters = new stdClass();
		$parameters->idAuction= $codSub;
		$item = new stdClass();
		$item->IdOriginLot = $idOrigen->idorigen_asigl0;

		//Si no se ha adjudicado, el type es R
		if(empty($adjudicacion)){
			$item->type = "R";
			$item->IdOriginClient = null;
			$item->paleta = null;
			$item->bid =0;
		}else{

			$item->IdOriginClient = $adjudicacion->cod2_cli;
			$item->bid = $adjudicacion->himp_csub;
			$item->paleta = $adjudicacion->licit_csub;

			# I internacional | S Sala | E Orden previa desde ERP | O Orden previa desde Web | T telefonica | W Web
			$item->type = $adjudicacion->pujrep_asigl1;



		}
		$parameters->items[]=$item;
		return json_encode($parameters);

	}




}
