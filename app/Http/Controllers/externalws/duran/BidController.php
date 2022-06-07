<?php
namespace App\Http\Controllers\externalws\duran;


use App\Http\Controllers\Controller;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgSub;
use App\Jobs\SoapJob;
use Config;
class BidController extends DuranController
{

# tipo es el tipo de puja(automatica, normal) metodo es si es orden o puja, delete es si la han borrad, solo sep ueden borrar las ordenes
	public function createBid($licit, $codSub, $ref, $bid, $tipo, $metodo, $delete = false){

		$sub = FgSub::where("COD_SUB", $codSub)->select("TIPO_SUB")->first();

		#la subasta debe ser tipo Online
		if(!empty($sub) && $sub->tipo_sub !="O"){
			return ;

		}

		$xml = $this->createXMLBid($licit, $codSub, $ref, $bid, $tipo,$metodo, $delete);



		#ya no se usa la llamada directa, ahora va por la colas
		#$res = $this->callWebService($xml,"wbpujaso");
		$theme  = \Config::get('app.theme');
		#dejar las dobles barras si no no va,
		$rutaController = "App\Http\Controllers\\externalws\\$theme\BidController";
		#encolamos la petición, el xml debe pasarse como texto no como objeto

		SoapJob::dispatch($xml->asXML(), "wbpujaso", $rutaController, "responsecreateBid")->onQueue(Config::get('app.queue_env'));

	}

	public function responsecreateBid($res){
		#por si fuera necesario tratar la respuesta
	}


	public function deleteOrder($licit, $codSub, $ref, $bid){


		$this->createBid($licit, $codSub, $ref, $bid, "", "ORDER", true);
	}



	private function createXMLBid($licit, $codSub, $ref, $bid,  $tipo, $metodo, $delete ){

		$codigoPersona = FxCli::SELECT("COD2_CLI")->join("FGLICIT","EMP_LICIT ='" . \Config::get("app.emp") ."' AND CLI_LICIT = COD_CLI  " )->WHERE("SUB_licit", $codSub)->where("cod_licit",$licit)->first();

		$idOrigen = FgAsigl0::SELECT("IDORIGEN_ASIGL0, NUMHCES_ASIGL0, LINHCES_ASIGL0")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();

		if(empty($codigoPersona) || empty($idOrigen)){
			$textEmail = "No se ha podido realizar  la puja, usuario o lote no existe<br> licit: $licit <br> codsub: $codSub <br> ref: $ref <br> bid: $bid  ";
			$this->sendEmailError("createBid", $textEmail,""  );
			return;
		}

			$codigoarticulo = substr($idOrigen->idorigen_asigl0,-7);

			$explodeBid = explode(".",$ref);
			$lote = $explodeBid[0];
			if(count($explodeBid) > 1){
				$bis = str_replace(array("1","2","3", "4", "5"), array("A", "B", "C", "D", "E"), $explodeBid[1]);
			}else{
				$bis = "";
			}

		$xml = new SimpleXMLElement("<root></root>");
		$pujas = $xml->addChild('pujas');
		$puja = $pujas->addChild('puja');
		$puja->addChild("referencia", $idOrigen->idorigen_asigl0 );
		$puja->addChild("codigoarticulo",$codigoarticulo );
		$puja->addChild("codigopersona",$codigoPersona->cod2_cli);
		$puja->addChild("importe",$bid );
		$puja->addChild("momento",date("Y-m-d H:i:s"));
		$deautopuja = 0;
		#Si es autopuja
		if($tipo == "A"){
			$deautopuja = 1;
		}
		$puja->addChild("deautopuja",$deautopuja );
		$esautopuja = 0;
		#Si es orden
		if($metodo == "ORDEN"){
			$esautopuja = 1;
		}
		$puja->addChild("esautopuja",$esautopuja );
		if($delete){
			$puja->addChild("anulada", 1 );
			$momentoAnulada = date("Y-m-d H:i:s");
		}else{
			$puja->addChild("anulada",0 );
			$momentoAnulada = "";
		}

		$puja->addChild("momentoanulada",$momentoAnulada);

		return $xml;

	}




}
