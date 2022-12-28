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


		\Log::info("Peticion a Segre: " .print_r($fields,true));
		\Log::info("Respuesta a Segre: " .print_r($res,true));

			if(!empty($res)){

				if ($res->IdError != 0 ) {
					$paleta = FgLicit::SELECT("COD_LICIT")->WHERE("SUB_LICIT", $codSub)->WHERE("CLI_LICIT", $codCli)->first();
					FgOrlic::where("EMP_ORLIC", \Config::get('app.emp'))->where("SUB_ORLIC",$codSub)->where("REF_ORLIC",$ref)->where("LICIT_ORLIC", $paleta->cod_licit)->where("HIMP_ORLIC", $order)->delete();
					#prefiero notificarlo en la pantalla y no enviar email
					/*

					$email = new EmailLib('ORDER_FAIL');
					if(!empty($email->email)){
						$email->setUserByCod($codCli,true);
						$email->setAtribute("LOT_REF", $ref);
						$email->setAtribute("AUCTION_CODE", $codSub);
						$email->setAtribute("ORDER", $order);
						$email->send_email();
					}

					$this->sendEmailError("OrderNew",print_r($fields,true), print_r($res,true) );
					asunto email
					Su orden máxima no ha podido registrase en Subastas Segre
					diseño email
					Estimada/o [*NAME*],
						<p> Le informamos que  su orden máxima para el lote [*LOT_REF*] de la subasta [*AUCTION_CODE*]. con un importe de  [*ORDER*]  no ha podido registrase en el sistema de Subastas Segre</p>

						<p>Puede ponerse en contacto con nosotros en el teléfono 915 159 584 o por email en la dirección <a href="mailto:info@subastassegre.es"> info@subastassegre.es</a></p>.
					*/
					return false;
				}
				return true;
			}else{
				return false;
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

		$orlic = FgOrlic::WHERE("SUB_ORLIC", $codSub)->WHERE("LICIT_ORLIC", $paleta->cod_licit)->WHERE("REF_ORLIC", $ref)->first();

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
			#cambios por puja telefónica.
			#si viene del delete no existirá orlic por que se ha borrado
			if(!$delete && $orlic->tipop_orlic == "T"){
				$orderItem->type = "T";
				$orderItem->phone1 = $orlic->tel1_orlic;
				$orderItem->phone2 = $orlic->tel2_orlic;
			}else{
				$orderItem->type = "O";
				$orderItem->phone1 = "";
				$orderItem->phone2 = "";
			}


			#

			$orderList->items[] = $orderItem;

		return $orderList;

	}




}
