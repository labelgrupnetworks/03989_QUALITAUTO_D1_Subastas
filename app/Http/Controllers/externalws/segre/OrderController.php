<?php
namespace App\Http\Controllers\externalws\segre;


use App\Http\Controllers\Controller;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgOrlic;
use App\Models\V5\FgLicit;
use App\libs\EmailLib;

class OrderController extends SegreController
{

	#Comuníca las ordenes de la subasta presencial
	public function createOrder($codCli, $codSub, $ref, $order, $delete = false){

			$fields = $this->orderFields($codCli, $codSub, $ref, $order, $delete);
			$res = $this->callWebService(json_encode($fields),"OrderNew");


		\Log::info(print_r($fields,true));
		\Log::info(print_r($res,true));

			if(!empty($res)){

				if ($res->IdError != 0 ) {

					$paleta = FgLicit::SELECT("COD_LICIT")->WHERE("SUB_LICIT", $codSub)->WHERE("CLI_LICIT", $codCli)->first();
					FgOrlic::where("EMP_ORLIC", \Config::get('app.emp'))->where("SUB_ORLIC",$codSub)->where("REF_ORLIC",$ref)->where("LICIT_ORLIC", $paleta->cod_licit)->where("HIMP_ORLIC", $order)->delete();
					$email = new EmailLib('ORDER_FAIL');
					if(!empty($email->email)){
						$email->setUserByCod($codCli,true);
						$email->setAtribute("LOT_REF", $ref);
						$email->setAtribute("AUCTION_CODE", $codSub);
						$email->setAtribute("ORDER", $order);
						$email->send_email();
					}

					$this->sendEmailError("OrderNew",print_r($fields,true), print_r($res,true) );
				}
			}
	}

	public function deleteOrder($licit, $codSub, $ref, $order){
		$paleta = FgLicit::SELECT("CLI_LICIT")->WHERE("SUB_LICIT", $codSub)->WHERE("COD_LICIT", $licit)->first();
		if(empty($paleta)){

			$textEmail = "No se ha podido cancelar la orden,licitador no existe<br> codcli: $licit <br> codsub: $codSub <br> ref: $ref <br> order: $order  ";
			$this->sendEmailError("OrderCancel", $textEmail,""  );
		}
		$delete = true;
		$fields = $this->orderFields($paleta->cli_licit, $codSub, $ref, $order, $delete);
		$res = $this->callWebService(json_encode($fields),"OrderCancel");

	\Log::info(print_r($fields,true));
	\Log::info(print_r($res,true));

		if(!empty($res)){

			if ($res->IdError != 0 ) {
				$this->sendEmailError("deleteOrder",print_r($fields,true), print_r($res,true) );
			}

		}

	}


	private function orderFields($codCli, $codSub, $ref, $order, $delete){

		$codigoPersona = FxCli::SELECT("COD2_CLI")->where("cod_cli",$codCli)->first();
		$idOrigen = FgAsigl0::SELECT("IDORIGEN_ASIGL0")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();
		$paleta = FgLicit::SELECT("COD_LICIT")->WHERE("SUB_LICIT", $codSub)->WHERE("CLI_LICIT", $codCli)->first();

		if(empty($codigoPersona) || empty($idOrigen)){
			if($delete){
				$textEmail = "No se ha podido cancelar la orden, usuario o lote no existe<br> codcli: $codCli <br> codsub: $codSub <br> ref: $ref <br> order: $order  ";
				$this->sendEmailError("OrderCancel", $textEmail,""  );
			}else{
				$textEmail = "No se ha podido realizar la orden, usuario o lote no existe<br> codcli: $codCli <br> codsub: $codSub <br> ref: $ref <br> order: $order  ";
				$this->sendEmailError("OrderNew", $textEmail,""  );
			}

			return;
		}


			$orderList = new \StdClass();
			$orderList->idAuction = $codSub;
			$orderList->items = array();

			$orderItem = new \StdClass();
			$orderItem->idOriginLot = $idOrigen->idorigen_asigl0;
			$orderItem->idOriginClient = $codigoPersona->cod2_cli;
			if(!$delete){
				$orderItem->order = $order;
				$orderItem->codBidder = $paleta->cod_licit;
			}

			$orderList->items[] = $orderItem;

		return $orderList;

	}




}
