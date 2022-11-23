<?php
namespace App\Http\Controllers\externalws\durannft;


use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\externalws\vottun\VottunController;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\Payments;
use App\Models\V5\WebPayCart;
use App\Models\V5\FxPcob;
use App\Models\V5\Web_Artist;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Jobs\SoapJob;
use Config;
use App\libs\EmailLib;
class PendingOperitionPaid extends DuranNftController
{

/* ESTA A MEDIAS
SE HA PARADO EL TEMA POR QUE FALTA REUNION PARA VER QUE QUIEREN HACER
*/
#la operación hace referencia al id de la operación de transaccion (transferencia o minteo)
	public function informPendingPaid($operationId, $type){
		try{

				if(!empty($operationId)){

					$xml =	$this->infoXml($operationId, $type);

					$res = $this->callWebService($xml,"Wbcrearpagonft");

					if(!empty($res) && $res->resultado == 0){

					}

					#el resultado 1 está controlado en duran controller y el 0 es k todo ok
					if(empty($res) || $res->resultado >1){

						$this->sendEmailError("wbGrabarVenta",htmlspecialchars($xml->asXML()), print_r($res,true),true );
					}
				}
			}catch(\Exception $e){
				   \Log::info($e);
			}

	}

	public function responsePaid($res){
		#por si fuera necesario tratar la respuesta
	}


	private function infoXml($operationId, $type){
		$asigl0 = new Fgasigl0();
		$asigl0 = $asigl0->JoinFghces1Asigl0()->JoinNFT();

		if($type == "mint"){
			$asigl0 = $asigl0->select("NUM_HCES1, LIN_HCES1,PROP_HCES1, NETWORK_NFT")->
			where("MINT_ID_NFT", $operationId);
		}elseif($type == "transfer") {
			$asigl0 = $asigl0->select("CLIFAC_CSUB, NETWORK_NFT")->
			JoinCSubAsigl0()->where("TRANSFER_ID_NFT", $operationId);
		}

		$transaction = $asigl0->first();

		$xml = new SimpleXMLElement("<root></root>");
		$xml->addChild("idtransaccion", $transaction->network_nft ."-". $operationId);
		$xml->addChild("referencia",  $transaction->num_hces1 ."-".  $transaction->lin_hces1);

		$xml->addChild("fechacreacion", date("Y-m-d h:i:s") );




		if($type == "mint"){
			$client = FxCli::select("cod2_cli")->where("cod_cli", $transaction->prop_hces1 )->first();
			$xml->addChild("cliente",$client->cod2_cli );
			$xml->addChild("concepto", "Minteo");
			$xml->addChild("tipo", 1);

		}elseif($type == "transfer") {
			$client = FxCli::select("cod2_cli")->where("cod_cli", $transaction->clifac_csub )->first();
			$xml->addChild("cliente",  $client->cod2_cli );
			$xml->addChild("concepto", "Transferencia");
			$xml->addChild("tipo", 2);
			
		}
		#etherum
		if($transaction->network_NFT == '4' || $transaction->network_NFT == '5'){
			$xml->addChild("red",1);
		}elseif($transaction->network_NFT == '137'){ #polygon
			$xml->addChild("red",2);
		}elseif($transaction->network_NFT == '43113' || $transaction->network_NFT == '43114'){ #avalanche
			$xml->addChild("red",3);
		}




		/* Pendiente que nso pasen los valores Vottun*/
		$xml->addChild("coste", 100);# sin iva
		$xml->addChild("comision", 10 );#sin iva
		$xml->addChild("iva", 21 );#importe del iva (el real no el que tenga el usuario)
		$xml->addChild("tipoiva",21);
		$xml->addChild("total",121);

		return $xml;

	}


}
