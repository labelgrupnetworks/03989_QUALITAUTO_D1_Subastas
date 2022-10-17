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
	public function informPendingPaid($operationId){
		try{

				if(!empty($operationId)){

					$xml =	$this->infoXml($operationId);

					$res = $this->callWebService($xml,"Wbcrearpagonft");

					if(!empty($res) && $res->resultado == 0){
						/* PONER IDORIGEN EN ASIGL0 para que luego puedan marcarlo como pagado */
						foreach($res->articulosnuevos->articulo as $articulo){
							$idorigen = (string) $articulo->codigoarticulo;
							$subRef = explode("-",$articulo->referencia);
							FgAsigl0::where("sub_asigl0", $subRef[0])->where("ref_asigl0", $subRef[1])->update(["idorigen_asigl0" => $idorigen]);

						}
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


	private function infoXml($operationId){
		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $operationId)->first();
		$info = json_decode($transaccion->info_paycart);


		$cli = FxCli::select("cod2_cli,cod_cli")->where("cod_cli",$transaccion->cli_paycart )->first();
		if(empty($cli)){
			\Log::info("No hay cliente con el cod_cli ". $transaccion->cli_paycart );
			return ;
		}

		#pongo el valor * 100 ya que el iva lo devolverá con decimales
		$ivaUser = \Tools::TaxForEuropean($cli->cod_cli) *100;

		$payments = new Payments();
		$ivaGeneral = $payments->getIVA(date('Y-m-d H:i:s'),'01');
		if(count($ivaGeneral) > 0){
			$ivaGeneral = $ivaGeneral[0]->iva_iva/100;
		}else{
			$ivaGeneral = 0;
		}


		$xml = new SimpleXMLElement("<root></root>");

		$vottunComission = \Config::get("app.VottunComission")/100;
		#la comisión esta incluida en el precio que han pagado por lo que se debe restar para obtener el importe real de la transaccion
		$comision = round($info->total / (1+  $vottunComission),2);
		$total = $info->total + $comision;
		$operationId = rand();#cre oque da error por que se ha repetido el identificador, pongo esto para las pruebas
		$xml->addChild("identificador",  $operationId);
		$xml->addChild("cliente",  $cli->cod2_cli );
		$xml->addChild("fechacreacion", date("Y-m-d h:i:s") );
		$xml->addChild("coste",  $info->total);# sin iva
		$xml->addChild("comision", $comision );#sin iva
		$xml->addChild("iva", round($total * $ivaGeneral,2) );#importe del iva (el real no el que tenga el usuario)
		$xml->addChild("tipoiva",$ivaUser);
		$xml->addChild("total",$total + round($total * $ivaUser/100 ,2) );





		if($info->reason == "mint"){
			$xml->addChild("concepto", "Minteo");
			$xml->addChild("tipo", 1);
			$xml->addChild("numeropedido", ""); #pedido que da origen al pago
		}else{
			$xml->addChild("concepto", "Transferencia");
			$xml->addChild("tipo", 2);
			$xml->addChild("numeropedido", "Poneraqui el valor del pedido");
		}
		$xml->addChild("red",1); #Pendiente de saber que red es
		return $xml;
/*
fechacreacion		fecha hora	yyyy-mm-dd hh:mm:ss
importe		real		importe a pagar. Separador decimal: “.”
concepto		texto (255)
tipo			int		1-minteo; 2-traspaso nft
numeropedido		texto (20)	pedido que da origen al pago

*/



	}


}
