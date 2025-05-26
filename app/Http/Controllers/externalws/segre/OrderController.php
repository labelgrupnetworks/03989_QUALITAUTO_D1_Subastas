<?php

namespace App\Http\Controllers\externalws\segre;

use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgOrlic;
use App\Models\V5\FgLicit;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class OrderController extends SegreController
{

	#Comuníca las ordenes de la subasta presencial
	public function createOrder($codCli, $codSub, $ref, $order, $delete = false)
	{
		$fields = $this->orderFields($codCli, $codSub, $ref, $order, $delete);
		$res = $this->callWebService(json_encode($fields), "OrderNew");

		Log::info("Peticion a Segre: " . print_r($fields, true));
		Log::info("Respuesta a Segre: " . print_r($res, true));

		if (empty($res)) {
			return false;
		}

		if ($res->IdError != 0) {
			$paleta = FgLicit::SELECT("COD_LICIT")->WHERE("SUB_LICIT", $codSub)->WHERE("CLI_LICIT", $codCli)->first();
			FgOrlic::where("EMP_ORLIC", Config::get('app.emp'))->where("SUB_ORLIC", $codSub)->where("REF_ORLIC", $ref)->where("LICIT_ORLIC", $paleta->cod_licit)->where("HIMP_ORLIC", $order)->delete();
			return false;
		}

		return true;
	}

	public function deleteOrder($licit, $codSub, $ref, $order)
	{
		$paleta = FgLicit::SELECT("CLI_LICIT")->WHERE("SUB_LICIT", $codSub)->WHERE("COD_LICIT", $licit)->first();
		if (empty($paleta)) {

			$textEmail = "No se ha podido cancelar la orden,licitador no existe<br> codcli: $licit <br> codsub: $codSub <br> ref: $ref <br> order: $order  ";
			$this->sendEmailError("OrderCancel", $textEmail, "", true);
		}
		$delete = true;
		$fields = $this->orderFields($paleta->cli_licit, $codSub, $ref, $order, $delete);
		$res = $this->callWebService(json_encode($fields), "OrderCancel");

		Log::info(print_r($fields, true));
		Log::info(print_r($res, true));

		if (!empty($res)) {

			if ($res->IdError != 0) {
				$this->sendEmailError("deleteOrder", print_r($fields, true), print_r($res, true), true);
			}
		}
	}


	private function orderFields($codCli, $codSub, $ref, $order, $delete)
	{

		$codigoPersona = FxCli::SELECT("COD2_CLI")->where("cod_cli", $codCli)->first();
		$idOrigen = FgAsigl0::SELECT("IDORIGEN_ASIGL0")->WHERE("SUB_ASIGL0", $codSub)->WHERE("REF_ASIGL0", $ref)->first();
		$paleta = FgLicit::SELECT("COD_LICIT")->WHERE("SUB_LICIT", $codSub)->WHERE("CLI_LICIT", $codCli)->first();

		if (empty($codigoPersona) || empty($idOrigen)) {
			if ($delete) {
				$textEmail = "No se ha podido cancelar la orden, usuario o lote no existe<br> codcli: $codCli <br> codsub: $codSub <br> ref: $ref <br> order: $order  ";
				$this->sendEmailError("OrderCancel", $textEmail, "", true);
			} else {
				$textEmail = "No se ha podido realizar la orden, usuario o lote no existe<br> codcli: $codCli <br> codsub: $codSub <br> ref: $ref <br> order: $order  ";
				$this->sendEmailError("OrderNew", $textEmail, "", true);
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

		if (!$delete) {
			$orderItem->order = $order;
			$orderItem->codBidder = $paleta->cod_licit;
		}
		#cambios por puja telefónica.
		#si viene del delete no existirá orlic por que se ha borrado
		if (!$delete && $orlic->tipop_orlic == "T") {
			$orderItem->type = "T";
			$orderItem->phone1 = $orlic->tel1_orlic;
			$orderItem->phone2 = $orlic->tel2_orlic;
		} else {
			$orderItem->type = "O";
			$orderItem->phone1 = "";
			$orderItem->phone2 = "";
		}

		$orderList->items[] = $orderItem;

		return $orderList;
	}
}
