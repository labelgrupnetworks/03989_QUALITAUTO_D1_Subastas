<?php
namespace App\Http\Controllers\externalws\duran;


use App\Http\Controllers\Controller;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;
use App\Jobs\SoapJob;
use Config;
class CloseLotController extends DuranController
{


	public function createCloseLot($codSub, $ref){

			$xml = $this->createXMLCloseLot( $codSub, $ref);
			$theme  = \Config::get('app.theme');
			#dejar las dobles barras si no no va,
			$rutaController = "App\Http\Controllers\\externalws\\$theme\CloseLotController";
			#encolamos la petición, el xml debe pasarse como texto no como objeto
			\Log::info($xml->asXML());
			SoapJob::dispatch($xml->asXML(), "wbResultados", $rutaController, "responseCloseLot")->onQueue( Config::get('app.queue_env'));
	}
	public function responseCloseLot($res){
		#por si fuera necesario tratar la respuesta
	}



	private function createXMLCloseLot( $codSub, $ref){


		$idOrigen = FgAsigl0::SELECT("IDORIGEN_ASIGL0")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();
		$adjudicacion = FgCsub::SELECT("COD2_CLI, HIMP_CSUB, PUJREP_ASIGL1 ")->LeftJoinCli()->JoinWinnerBid()->WHERE("SUB_CSUB", $codSub)->WHERE("REF_CSUB", $ref)	->first();

		if( empty($idOrigen)){
			$textEmail = "No se ha podido realizar la llamada a  wbResultado, usuario o lote no existe<br> codsub: $codSub <br> ref: $ref   ";
			$this->sendEmailError("wbResultados", $textEmail,""  );
			return;
		}

		$codigoarticulo = substr($idOrigen->idorigen_asigl0,-7);

		$explodeOrder = explode(".",$ref);
		$lote = $explodeOrder[0];
		if(count($explodeOrder) > 1){
			$bis = str_replace(array("1","2","3", "4", "5"), array("A", "B", "C", "D", "E"), $explodeOrder[1]);
		}else{
			$bis = "";
		}

		$xml = new SimpleXMLElement("<root></root>");
		$xml->addChild("subasta",  $codSub);
		$xml->addChild("lote",$lote );
		$xml->addChild("bis",$bis );
		$xml->addChild("codigoarticulo",$codigoarticulo );

		if(!empty($adjudicacion)){
			$codigoPersona = $adjudicacion->cod2_cli;
			$remate = $adjudicacion->himp_csub;

			# la adjudicación de internacional se hace sin paleta
			if($adjudicacion->pujrep_asigl1 == "I" ){
				#si es internacional
				$clave=14;
			}
			#si no tenemos el cliente es por que se habrá adjudicado a la paleta 9999
			elseif(empty($adjudicacion->cod2_cli) ){
				$clave=19;
			}
			elseif($adjudicacion->pujrep_asigl1 == "S"  ){
				#si es puja sala
				$clave=6;
			}
			elseif($adjudicacion->pujrep_asigl1 == "E" || $adjudicacion->pujrep_asigl1 == "O"  ){
				#si viene de las ordenes previas a la subasta
				$clave=5;
			}elseif($adjudicacion->pujrep_asigl1 == "T" ){
				#si es telefonica
				$clave=10;
			}elseif($adjudicacion->pujrep_asigl1 == "W" ){
				#si es de la web
				$clave=18;
			}
			elseif($adjudicacion->pujrep_asigl1 == "R" ){
				#precio reserva, se simula la puja de reserva
				$clave=4;
			}

		}else{
			$codigoPersona = 0;
			$remate = 0;
			$clave=3;
		}

		$xml->addChild("codigopersona",$codigoPersona);
		$xml->addChild("remate",$remate);
		$xml->addChild("clave",$clave);



		return $xml;

	}




}
