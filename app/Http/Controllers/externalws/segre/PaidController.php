<?php
namespace App\Http\Controllers\externalws\segre;


use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub0;
use App\Models\V5\WebPayCart;
use App\Models\V5\FxPcob0;
use App\Jobs\SoapJob;
use Config;
use App\libs\EmailLib;
class PaidController extends SegreController
{


	public function informPaid($merchantID){
		\Log::info("notificar a Segre Pago factura ".$merchantID );
		#OJO QUE SE PUEDEN PAGAR MAS DE UNA FACTURA A LA VEZ, debemos ver como hacemos esto, si ehacer un foreach de facturas y enviarlas todas
		if(!empty($merchantID)){
			$type = substr($merchantID,0,1) ;
			#pago de lotes subasta Online
			if($type == 'P'){

				#segre no tiene este servicio

			}
			#pago de Tienda
			elseif($type == 'T'){
				#segre no tiene este servicio
			}
			#Pago facturas
			elseif($type == 'F'){
				# de momento no tenemos como hacerlo
				$payments =	$this->invoicesInfo($merchantID);
				foreach($payments as $payment){
					$res = $this->callWebService(json_encode($payment),"PaymentConfirmation");
					\Log::info("call PaymentConfirmation");
					\Log::info(print_r($payment,true));
					\Log::info(print_r($res,true));
				}


				return;
			}


		}





	}
	public function responsePaid($res){
		#por si fuera necesario tratar la respuesta
	}



	private function invoicesInfo( $idTransaction){


		$pagos = FxPcob0::select("IMP_COBRO1, COD2_CLI, SERIE_PCOB1, NUMERO_PCOB1, IMP_COBRO1")->where("IDTRANS_PCOB0", $idTransaction)->joincli()->joinpcob1()->JoinCobro1()->get();
#Falta conseguir la informacion de que metodo de pago usan
#pueden venir varios pagos

		#no hay pago
		if(count($pagos)==0){
			\Log::info("Transaccion no encontrada, $idTransaction");
			return ;
		}

		$payments = array();

		foreach($pagos as $pago){
			$pay = new \Stdclass();
			$pay->Serial = $pago->serie_pcob1;
			$pay->Number = $pago->numero_pcob1;
			$pay->idOriginCli = $pago->cod2_cli;
			$pay->Amount = $pago->imp_cobro1;
			$pay->Paid = 'S';
			$pay->PaymentDate = date("Y-m-d H:i:s");
			$payments[] = $pay;
		}



		return $payments;
	}


}
