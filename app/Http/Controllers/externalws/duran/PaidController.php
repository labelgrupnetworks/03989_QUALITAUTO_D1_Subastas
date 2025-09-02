<?php

namespace App\Http\Controllers\externalws\duran;

use App\Http\Controllers\PaymentsController;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub0;
use App\Models\V5\WebPayCart;
use App\Models\V5\FxPcob;
use Illuminate\Support\Facades\Config;
use App\libs\EmailLib;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Log;

class PaidController extends DuranController
{

	public function informPaid($merchantID)
	{
		#OJO QUE SE PUEDEN PAGAR MAS DE UNA FACTURA A LA VEZ, debemos ver como hacemos esto, si ehacer un foreach de facturas y enviarlas todas
		if (!empty($merchantID)) {
			$type = substr($merchantID, 0, 1);
			#pago de lotes subasta Online
			if ($type == 'P') {

				$xml =	$this->merchantInfo($merchantID);
				$res = $this->callWebService($xml, "wbGrabarVenta");
				#el resultado 1 está controlado en dauran controller y el 0 es k todo ok
				if (empty($res) || $res->resultado > 1) {

					$this->sendEmailError("wbGrabarVenta", htmlspecialchars($xml->asXML()), print_r($res, true), true);
				}
				#provando

			}
			#pago de Tienda
			elseif ($type == 'T') {
				$xml =	$this->cartInfo($merchantID);
				$res = $this->callWebService($xml, "wbGrabarVenta");
				#el resultado 1 está controlado en dauran controller y el 0 es k todo ok
				if (empty($res) || $res->resultado > 1) {

					$this->sendEmailError("wbGrabarVenta", htmlspecialchars($xml->asXML()), print_r($res, true), true);
				}
			}
			#Pago facturas
			elseif ($type == 'F') {
				# de momento no tenemos como hacerlo
				$xml =	$this->invoicesInfo($merchantID);
				Log::info("Pago factura " . $merchantID);
				return;
			}
		}
	}
	public function responsePaid($res)
	{
		#por si fuera necesario tratar la respuesta
	}

	private function cartInfo($merchantID)
	{
		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $merchantID)->first();
		if (empty($transaccion)) {
			Log::info("No hay carrito pagado con el idtrans $merchantID");
			return;
		}
		$info_trans = json_decode($transaccion->info_paycart);

		$cli = FxCli::select("cod2_cli")->where("cod_cli", $transaccion->cli_paycart)->first();
		if (empty($cli)) {
			Log::info("No hay cliente con el cod_cli " . $transaccion->cli_paycart);
			return;
		}
		$info["codigopersona"] = $cli->cod2_cli;
		$info["numeroPedido"] = $merchantID;
		$info["formaEnvio"] = !empty($info_trans->envio) ?  $info_trans->envio : 2;  # 1 Enviar por agencia 	2 Recoger en la ubicación del lote
		$info["importeSeguro"] = !empty($info_trans->importeSeguro) ?  $info_trans->importeSeguro + $info_trans->ivaSeguro : 0;
		$info["seguro"] = !empty($info_trans->seguro) ?  $info_trans->seguro : 0;
		$info["importeEnvio"] = !empty($info_trans->gastosEnvio) ?  $info_trans->gastosEnvio + $info_trans->ivaGastosEnvio : 0;
		$info["pais"] = !empty($info_trans->pais) ?  $info_trans->pais : "";
		$info["direccion"] = !empty($info_trans->direccion) ?  $info_trans->direccion : "";
		$info["poblacion"] = !empty($info_trans->poblacion) ?  $info_trans->poblacion : "";
		$info["provincia"] = !empty($info_trans->provincia) ?  $info_trans->provincia : "";
		$info["cp"] = !empty($info_trans->cp) ?  $info_trans->cp : "";
		$info["telefono"] = !empty($info_trans->telefono) ?  $info_trans->telefono : "";
		#3-transferencia; 4-tarjeta; 6-bizum
		if ($info_trans->paymethod == "transfer") {
			$formaPago = 3;
		} elseif ($info_trans->paymethod == "bizum") {
			$formaPago = 6;
		} else {
			$formaPago = 4;
		}
		$info["formaPago"] = $formaPago;


		$info["modoVenta"] = 3; #5-subasta online; 3-venta directa online;

		#falta poner campo de texto
		$info["notasEnvio"] = htmlspecialchars($info_trans->comments);
		$info["lotes"] = array();
		#cargamos datos de los lotes
		$fgasigl0 = new FgAsigl0();
		$lots_array = array();
		foreach ($info_trans->lots as $lot) {
			$lots_array[] = $lot->ref;
		}
		#LA SUBASTA SIEMPRE ES LA 7500, LA DE TIENDA ONLINE
		$fgasigl0 = $fgasigl0->where("SUB_ASIGL0", "7500")->wherein("ref_asigl0", $lots_array)->JoinFghces1Asigl0()->leftjoinAlm();
		$lots = $fgasigl0->select("REF_ASIGL0, IDORIGEN_ASIGL0, DESCWEB_HCES1, DES_ALM, DIR_ALM, ALM_HCES1, IMPSALHCES_ASIGL0,  COML_HCES1,   SUB_ASIGL0, REF_ASIGL0, IMPSALHCES_ASIGL0,COML_HCES1 , DESCWEB_HCES1, PERMISOEXP_HCES1")->get();

		$importeTotal = 0;
		$ivaTotal = 0;
		foreach ($lots as $lot) {
			#no hay que tener en cuenta el iva de la comisión,

			$lote = array();
			$lote["codigoArticulo"] =  $lot->idorigen_asigl0;
			$lote["titulo"] = $lot->descweb_hces1;
			$lote["infoAlmacen"] = $lot->des_alm . ":" . $lot->alm_hces1 . "(" . $lot->dir_alm . ")";
			$lote["almacen"] = $lot->alm_hces1;
			$lote["remate"] = $lot->impsalhces_asigl0;
			$lote["tipocorretaje"] = $lot->coml_hces1;
			$lote["corretaje"] = round($lot->impsalhces_asigl0 * $lot->coml_hces1 / 100, 2);
			$lote["referencia"] = $lot->ref_asigl0;
			#falta el iva pero me ha dicho que no lo calcule
			$lote["total"] = $lote["remate"] +  $lote["corretaje"];
			$lote["iva"] =  0; #EN VENTA DIRECTA NO HAY COMISIÓN POR LO QUE BNO HAY IVA DE LA COMISION
			$lote["permisoExportacion"] =  $lot->permisoexp_hces1 == 'S' ? 1 : 0;

			$importeTotal += $lote["total"];
			$ivaTotal += $lote["iva"];
			$info["lotes"][] = $lote;
		}
		#importe total pagado, lo ponemso al final por que necesitamos calcularlo en base a los lotes
		$info["importeTotal"] = $importeTotal;
		$info["ivaTotal"] = $ivaTotal;


		$this->sendConfirmationMail($info);

		return $this->createXMLPaid($info);
	}


	private function merchantInfo($merchantID)
	{
		#el pedido puede constar de varios lotes
		$pedido = FgCsub0::select("COD_CLI, COD2_CLI, HIMP_CSUB, BASE_CSUB, IMP_CSUB0, IMPGAS_CSUB0, TAX_CSUB0, EXTRAINF_CSUB0, TIPO_SUB, IDORIGEN_ASIGL0, ALM_HCES1, COML_HCES1, DESCWEB_HCES1, DES_ALM, DIR_ALM, PERMISOEXP_HCES1 ")->JoinCsub()->joinCli()->JoinSub()->JoinAsigl0()->JoinHces1()->joinAlm()->where("IDTRANS_CSUB0", $merchantID)->get();

		if (count($pedido) == 0) {
			Log::info("No hay pedido con el idtrans $merchantID");
			return null;
		}

		$firstLot = $pedido[0];
		#los extras apareceran repetidos por cada elemento que haya en el array, así que para evitar duplicidades los sacamos solo del primero

		$extras = json_decode($firstLot->extrainf_csub0);
		#luego se modifica la forma de envio
		$formaenvio = 2; # 1 Enviar por agencia 	2 Recoger en la ubicación del lote
		$importeSeguro = 0;
		$seguro = 0;
		$importeEnvio = 0;


		$pais = "";
		$direccion = "";
		$poblacion = "";
		$provincia = "";
		$cp = "";

		#importe seguro si tiene, dirección de envio
		foreach ($extras as  $auction) {
			foreach ($auction as  $lot) {
				#solo el primer lote
				foreach ($lot as $keyInfo => $extraInfo) {
					if ($keyInfo == 'extras') {
						foreach ($extraInfo as $valInfo) {
							#si es el seguro
							if ($valInfo->TIPO_ASIGL2 == "SE") {
								$seguro = 1;
								$importeSeguro += $valInfo->IMP_ASIGL2 + $valInfo->IMPIVA_ASIGL2;
							}
							#Si es el envio
							if ($valInfo->TIPO_ASIGL2 == "EN") {
								#si hay envio marcamso a 1 la forma de envio
								$formaenvio = 1;
								$importeEnvio += $valInfo->IMP_ASIGL2 + $valInfo->IMPIVA_ASIGL2;
							}
						}
					}
					if ($keyInfo == 'inf') {
						$pais = $extraInfo->paisenv ?? '';
						$direccion = $extraInfo->direnv ?? '';
						$poblacion = $extraInfo->pobenv ?? '';
						$provincia = $extraInfo->provenv ?? '';
						$cp = $extraInfo->cpenv ?? '';
						$telefono = $extraInfo->telenv ?? '';
						#3-transferencia; 4-tarjeta; 6-bizum
						if ($extraInfo->paymethod == "transfer") {
							$formaPago = 3;
						} elseif ($extraInfo->paymethod == "bizum") {
							$formaPago = 6;
						} else {
							$formaPago = 4;
						}
					}
				}
			}
		}
		$info["numeroPedido"] = $merchantID;
		$info["formaEnvio"] = $formaenvio;
		$info["importeSeguro"] = $importeSeguro;
		$info["seguro"] = $seguro;
		$info["importeEnvio"] = $importeEnvio;
		$info["pais"] = $pais;
		$info["direccion"] = $direccion;
		$info["poblacion"] = $poblacion;
		$info["provincia"] = $provincia;
		$info["cp"] = $cp;
		$info["telefono"] = $telefono;
		$info["formaPago"] = $formaPago;
		$info["codigopersona"] = $firstLot->cod2_cli;

		$info["modoVenta"] =  $firstLot->tipo_sub == "O" ? "5" : "3"; #5-subasta online; 3-venta directa online;
		#falta poner campo de texto
		$info["notasEnvio"] = "";
		$info["lotes"] = array();
		#se calcula con el importe del lote + la comisión, sin tener en cuenta el iva


		$paymentsController = new PaymentsController();
		$iva = $paymentsController->getIva(Config::get("app.emp"),  date("Y-m-d"));

		$tipo_iva =  $paymentsController->user_has_Iva(Config::get("app.gemp"), $firstLot->cod_cli);
		#importe lotes sin IVA
		$importeTotal = 0;
		#importe lotes con IVA
		$ivaTotal = 0;
		#el pedido puede contener varios lotes
		foreach ($pedido as $lot) {
			#no hay que tener en cuenta el iva de la comisión, lo pidio josemanuel

			$lote = array();
			$lote["codigoArticulo"] =  $lot->idorigen_asigl0;
			$lote["titulo"] = $lot->descweb_hces1;
			$lote["infoAlmacen"] = $lot->des_alm . ":" . $lot->alm_hces1 . "(" . $lot->dir_alm . ")";
			$lote["almacen"] = $lot->alm_hces1;
			$lote["remate"] = $lot->himp_csub;
			$lote["tipocorretaje"] = $lot->coml_hces1;
			$lote["corretaje"] = $lot->base_csub;
			$lote["referencia"] = $lot->idorigen_asigl0;

			$lote["total"] = $lot->himp_csub + $lot->base_csub;
			$lote["iva"] = 0; // en la online no hay iva $paymentsController->calculate_iva($tipo_iva->tipo, $iva, $lot->base_csub);
			$lote["permisoExportacion"] =  $lot->permisoexp_hces1 == 'S' ? 1 : 0;

			$importeTotal += $lote["total"];
			$ivaTotal += $lote["iva"];
			$info["lotes"][] = $lote;
		}
		#importe total pagado, lo ponemso al final por que necesitamos calcularlo en base a los lotes
		$info["importeTotal"] = $importeTotal;
		$info["ivaTotal"] = $ivaTotal;
		#mandar email

		$this->sendConfirmationMail($info);

		return $this->createXMLPaid($info);
	}




	private function createXMLPaid($info)
	{


		$xml = new SimpleXMLElement("<root></root>");

		#de momento no hay campo
		$xml->addChild("importetotal", $info["importeTotal"]);
		$xml->addChild("codigopersona",  $info["codigopersona"]);
		$xml->addChild("seguro", $info["seguro"]);
		$xml->addChild("enviarfactura",  1); #siemrpe a 1

		$xml->addChild("importeseguro",  $info["importeSeguro"]);
		$xml->addChild("formaenvio", $info["formaEnvio"]);
		$xml->addChild("notasenvio",  $info["notasEnvio"]);
		$xml->addChild("importeenvio", $info["importeEnvio"]);
		$xml->addChild("modoventa",  $info["modoVenta"]);
		$xml->addChild("formapago",  $info["formaPago"]);
		$xml->addChild("numeropedido", $info["numeroPedido"]); #como no hay pedido ponemos el identioficador de merchantid

		#datos envio
		$xml->addChild("paisenvio", $info["pais"]);
		$xml->addChild("direccionenvio", $info["direccion"]);
		$xml->addChild("poblacionenvio", $info["poblacion"]);
		$xml->addChild("provinciaenvio", $info["provincia"]);
		$xml->addChild("cpenvio",  $info["cp"]);



		$articulos = $xml->addChild('articulos');

		#el pedido puede contener varios lotes
		foreach ($info["lotes"] as $lote) {
			$articulo = $articulos->addChild('articulo');

			$articulo->addAttribute("codigoarticulo", substr($lote["codigoArticulo"], -7));
			$articulo->addAttribute("almacen", $lote["almacen"]);
			$articulo->addAttribute("remate", $lote["remate"]);

			$articulo->addAttribute("tipocorretaje", $lote["tipocorretaje"]);

			$articulo->addAttribute("corretaje", $lote["corretaje"]);

			$articulo->addAttribute("referencia", $lote["referencia"]);
			#falta el iva pero me ha dicho que no lo calcule
			$articulo->addAttribute("total", $lote["total"]);
		}


		return $xml;
	}


	public function sendConfirmationMail($info)
	{
		$cliente = FxCli::select("NOM_CLI, DIR_CLI, DIR2_CLI, CP_CLI, POB_CLI, PRO_CLI, TEL1_CLI, PAIS_CLI, EMAIL_CLI ")->where("cod2_cli", $info["codigopersona"])->first();

		$totalPagar = $info["importeTotal"] + $info["ivaTotal"] + $info["importeEnvio"] + $info["importeSeguro"];

		$infoFacturación =  $cliente->nom_cli . "<br>" . $cliente->dir_cli . $cliente->dir2_cli . " <br> " . $cliente->pob_cli . ", " . $cliente->pro_cli . ", " . $cliente->cp_cli . "<br> " . $cliente->pais_cli . " <br> " . "T: " . $cliente->tel1_cli;

		$email = new EmailLib('NOTIFICAR_PAGO_DURAN');
		$email->setAtribute("INFO_FACTURACION", $infoFacturación);
		$email->setAtribute("NUM_PEDIDO", $info["numeroPedido"]);
		$email->setAtribute("TOTAL", ToolsServiceProvider::moneyFormat($info["importeSeguro"] + $info["importeEnvio"] + $info["importeTotal"] + $info["ivaTotal"], " €", 2));
		#3-transferencia; 4-tarjeta; 6-bizum
		if ($info["formaPago"] == 3) {
			$infoPago =  trans(Config::get('app.theme') . '-app.user_panel.pay_transfer') . "<br><br>" . trans(Config::get('app.theme') . '-app.user_panel.text_transfer', ["pago" => ToolsServiceProvider::moneyFormat($totalPagar, null, 2), "cuenta" => Config::get('app.tranferCount')]);
		} else if ($info["formaPago"] == 4) {
			$infoPago = trans(Config::get('app.theme') . '-app.user_panel.pay_creditcard');
		} else if ($info["formaPago"] == 6) {
			$infoPago = trans(Config::get('app.theme') . '-app.user_panel.pay_bizum');
		}
		$email->setAtribute("INFO_PAGO", $infoPago);

		if ($info["formaEnvio"] == 1) {
			$infoEnvio = $cliente->nom_cli . " <br> " . $info["direccion"] . " <br>  " . $info["poblacion"] . ", " . $info["provincia"] . ", " . $info["cp"] . " <br> " . $info["pais"] . " <br>  " . "T: " . $info["telefono"];
			$infoMetodoEnvio = trans(Config::get('app.theme') . '-app.user_panel.envio_agencia');
			$infoMetodoEnvio .= "<br><br>" . trans(Config::get('app.theme') . '-app.user_panel.gastos_envio') . ": " . ToolsServiceProvider::moneyFormat($info["importeEnvio"], " €", 2);
			if ($info["seguro"] == 1) {
				$infoMetodoEnvio .= "<br><br> " . trans(Config::get('app.theme') . '-app.user_panel.seguro_envio') . ": " . ToolsServiceProvider::moneyFormat($info["importeSeguro"], " €", 2);
			}
		} else {
			$infoEnvio = "";
			$infoMetodoEnvio = trans(Config::get('app.theme') . '-app.user_panel.recogida_producto') . "<br> " . trans(Config::get('app.theme') . '-app.user_panel.sala_almacen');
		}

		if (!empty($info["notasEnvio"])) {
			$infoEnvio .= "<br><br>" . trans(Config::get('app.theme') . '-app.global.coment') . ": <br> \" " . $info["notasEnvio"] . "\" ";
		}

		$email->setAtribute("INFO_ENVIO", $infoEnvio);
		$email->setAtribute("INFO_METODO_ENVIO", $infoMetodoEnvio);
		$infoLots = "";
		foreach ($info["lotes"] as $lote) {
			$permisoExportacion = $lote["permisoExportacion"] == 1 ? "<br><br>" . trans(Config::get('app.theme') . '-app.lot.permiso_exportacion') : "";
			$infoLots .= "<tr bgcolor=\"#efefef\"> ";
			$infoLots .= "<td><p style=\"padding-left: 5px;\"><strong>" . $lote["titulo"] . "</strong><br>" . $lote["infoAlmacen"] . $permisoExportacion . "</p></td>";
			$infoLots .= "<td style=\"text-align: center;\">" . $lote["codigoArticulo"] . "</td>";
			//$infoLots .= "<td style=\"text-align: center;\">1</td>";
			$infoLots .= "<td style=\"text-align: right;padding-right: 5px;\">" . ToolsServiceProvider::moneyFormat($lote["total"] + $lote["iva"], " €", 2) . "</td>";
			$infoLots .= " </tr> ";
		}
		#suma de lotes
		#$infoLots .= "<tr ><td> </td> <td> </td> <td bgcolor=\"#efefef\"  style=\"text-align: right;padding-right: 5px;\">  <strong>".ToolsServiceProvider::moneyFormat($info["importeTotal"] + $info["ivaTotal"]," €",2) ." </strong></td> </tr> ";

		$email->setAtribute("INFO_ARTICULOS", $infoLots);
		$email->setAtribute("TOTAL_ARTICULOS", ToolsServiceProvider::moneyFormat($info["importeTotal"] + $info["ivaTotal"], " €", 2));
		$email->setAtribute("GASTOS_ENVIO", ToolsServiceProvider::moneyFormat($info["importeEnvio"], " €", 2));
		$email->setAtribute("SEGURO_ENVIO", ToolsServiceProvider::moneyFormat($info["importeSeguro"], " €", 2));

		$email->setTo($cliente->email_cli, $cliente->nom_cli);
		$email->setBcc("ventaonline@duran-subastas.com");

		$email->send_email();
	}

	private function invoicesInfo($idTransaction)
	{


		$pago = FxPcob::select("IMP_PCOB, COD2_CLI, ANUM_PCOB, NUM_PCOB")->where("IDTRANS_PCOB0", $idTransaction)->joincli()->joinpcob1()->joinpcob0()->get();
		#Falta conseguir la informacion de que metodo de pago usan
		#pueden venir varios pagos

		#no hay pago
		if (empty($pago)) {
			Log::info("Transaccion no encontrada, $idTransaction");
			return;
		}

		$xml = new SimpleXMLElement("<root></root>");

		#de momento no hay campo
		$xml->addChild("numero",  "1");
		$xml->addChild("fecha",  "1");
		$xml->addChild("formapago",  "1");


		return $xml;
	}
}
