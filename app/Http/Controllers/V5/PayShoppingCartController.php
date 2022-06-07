<?php

namespace App\Http\Controllers\V5;

use App\Http\Controllers\V5\CartController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use App\Models\V5\FxClid;
use App\Models\V5\WebPayCart;
use App\Models\Subasta;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgHces1;
use View;
use Config;
use Session;
use Request;
use DB;

class PayShoppingCartController extends Controller

{



	public function createPayment(){

		$res = array(
			"status" => "error",
			"msgError" => "generic"
		);

		$paymentcontroller = new PaymentsController();

		$cod_cli = Session::get('user.cod');



		#generamos la información a guardar.
		$inf = new \stdClass();
		$cartController = new CartController();
		$iva =  $cartController->ivaAplicable();

		#checkeamos los lotes antes de confirmar compra
		if(! $cartController->checkToBuy()){
			#faltan productos, hay que avisar al usuario y refrescar la web
			$res["msgError"] = "lotsLost";
			\Log::info("faltan lotes");
			return $res;
		}


		$lots  = $cartController->loadLotsCart();

		$importeLotes = 0;

		$inf->paymethod= request("paymethod");
		$inf->comments = request("comments");
		$inf->lots = array();
		foreach($lots as $lot){

			#mas comision
			$comision = round($lot->impsalhces_asigl0 * $lot->coml_hces1 / 100,2);
			#mas iva de la  comision
			$ivacomision = round($comision * $iva,2)  ;
			#precio salida `comision + iva comision
			$importeLotes += $lot->impsalhces_asigl0 + $comision + $ivacomision ;

			#guardamos la información de los lotes comprados
			$lotInfo = new \stdClass();
			$lotInfo->cod_sub = $lot->sub_asigl0;
			$lotInfo->ref = $lot->ref_asigl0;
			$lotInfo->importe =  $lot->impsalhces_asigl0;

			if(\Config::get("app.TaxForEuropean")){
				$lotInfo->importeSinIva = 	$lot->impSinIva;
				$lotInfo->iva = $lot->iva;

			}
			$inf->lots[] = $lotInfo;

			#quitamos el lote del carrito sin cancelar las reservas en base de datos ni en webservice
			unset($cartController->shoppingCart[$lot->sub_asigl0][$lot->ref_asigl0]) ;

		}
		#salvamos para que se actualice el carrito en memoria
		$cartController->saveCart();
		$importeTotal = $importeLotes;
		# si hay envio

		if(!empty(request("envio_carrito"))){
			$clidd = request("clidd_carrito");
			$inf->envio = 1;
			$envio = $paymentcontroller->calc_web_gastos_envio ($lots,$clidd);

			#direccion envio
			$cod_cli = Session::get('user.cod');
			if(!empty($cod_cli) && !empty($clidd)){
				$direccionEnvio = FxClid::select("CP_CLID, CODPAIS_CLID, DIR_CLID, POB_CLID, TEL1_CLID, PRO_CLID")->WHERE("CODD_CLID",$clidd)->where("cli_clid", $cod_cli)->first();
				if(!empty($direccionEnvio)){
					$inf->pais = $direccionEnvio->codpais_clid;
					$inf->provincia = $direccionEnvio->pro_clid;
					$inf->poblacion = $direccionEnvio->pob_clid;
					$inf->direccion = $direccionEnvio->dir_clid;
					$inf->cp = $direccionEnvio->cp_clid;
					$inf->telefono = $direccionEnvio->tel1_clid;
				}
			}

			#se supone que no debería haber llegado si no es transportable
			if($envio  > -1){
				$inf->gastosEnvio = $envio ;
				#iva gastos de envio
				$inf->ivaGastosEnvio =  round($inf->gastosEnvio * $iva,2);
				$importeTotal += round($inf->gastosEnvio + $inf->ivaGastosEnvio,2);
			}else{
				$inf->envio = 0;
			}

			if(!empty(request("seguro_carrito"))){
				$inf->seguro = 1;
				$inf->importeSeguro = round($importeLotes * \Config::get('app.porcentaje_seguro_envio')/100,2);
				$inf->ivaSeguro =  round($inf->importeSeguro * $iva,2);
				$importeTotal += $inf->importeSeguro + $inf->ivaSeguro;
			}

		}

		#es un campo meramente informativo no se realizan calculos,
		if(!empty(request("seguro_carrito_info"))){
			$inf->seguro = 1;
		}

		$inf->total = $importeTotal;
		$webpayCart["CLI_PAYCART"] = $cod_cli;
		$webpayCart["EMP_PAYCART"] = \Config::get("app.emp");

		#CREAMOS EL ID DE LA TRANSACCION, LA LETRA QUE IDENTIFICARÁ LSO PAGOS DE TIENDASERÁ LA T
		$webpayCart["IDTRANS_PAYCART"] = "T" . rand(1, 9) . time();
		$webpayCart["DATE_PAYCART"] = date("Y-m-d H:i:s");
		$webpayCart["INFO_PAYCART"] = json_encode($inf) ;
		WebPayCart::insert($webpayCart);

		\Log::info( json_encode($inf));
		\Log::info(" importeTotal: $importeTotal ");

		#Si han elegido el pago por transferencia reenviamos a la página que mostrará el texto
		if(!empty(request("paymethod")) && request("paymethod") == "transfer" ){

			//llamada a la funcion que cierra los lotes y los adjudica y llama al webservice si hace falta
			$this->returnPay($webpayCart["IDTRANS_PAYCART"]);
			$importe = base64_encode($inf->total);
			$control =  md5($importe.Session::get('user.cod'));
			$idtrans =  $webpayCart["IDTRANS_PAYCART"];
			$url = route("transferpayment", ["lang" => \Config::get("app.locale")])."?control=$control&trans=$importe&idtrans=$idtrans";

			$res = array(
				"status" => "success",
				"location" => $url

			);
			return $res;


		}elseif (Config::get('app.paymentRedsys')) {
			if(!empty(request("paymethod"))){
				$paymethod = "&paymethod=". request("paymethod");
			}

			$url = Config::get('app.url') . '/shoppingCart/callRedsys?idTrans=' . $webpayCart["IDTRANS_PAYCART"].$paymethod ;

			$res = array(
				"status" => "success",
				"location" => $url
			);

		}
		#es necesario para que las cookies se actualicen y se pueda vaciar correctamente el carrito
		response();
		return $res;
	}



	#Carga el formulariode redsys
	public function callRedsys(){

		$paymentcontroller = new PaymentsController();
		$idTrans = request("idTrans");
		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();

		if(empty($transaccion)){
			exit (\View::make('front::errors.404'));
		}
		$info = json_decode($transaccion->info_paycart);
		\Log::info("Dentro de llamada a redsys");

		$varsRedsys = $paymentcontroller->requestRedsys($info->total, $idTrans,'/gateway/pagoDirectoReturn');




		#reenviamos al formulario
		return \View::make('front::pages.panel.RedsysForm', $varsRedsys);
	}

	#llamada que hace redsys para indicarnos que transaccion se ha pagado
	#tambien se llama si el pago es por transferencia, en el momento de elegir ese tipo de pago
	public function returnPay($idTrans){
		\Log::info("Dentro de Return Pay $idTrans");
		#codigo para pruebas
		//http://www.newsubastas.test/shoppingCart/returnPay?idTrans=T81607077859
		//$idTrans = request("idTrans");

		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();
		if(empty($transaccion)){
			\Log::info("Error en pasarela de pago de tienda online, $idTrans no se encuentra en base de datos ");
			return;
		}

		#MARCAMOS EL PEDIDO COMO PAGAGO
		WebPayCart::where("IDTRANS_PAYCART", $idTrans)->update(["PAID_PAYCART" => "S"]);



		$info = json_decode($transaccion->info_paycart);

		if(empty($info ) || empty($info->lots) ){
			\Log::info("Error en pasarela de pago de tienda online, no hay lotes asociados al id $idTrans   ");
			return;
		}
		$subasta = new Subasta();
		$subasta->cli_licit = $transaccion->cli_paycart;


		$subasta->type_bid = 'W';
		#de momento no es necesario modificar la info de la transaccion, solo si algun producto tiene stock
		$updateInfo = false;
		foreach($info->lots as $keyLot => $lot) {
			#control de stock si es necesario
			$stock = FgAsigl0::select("CONTROLSTOCK_HCES1, nvl(STOCK_HCES1,0) STOCK_HCES1, NUM_HCES1, LIN_HCES1")->JoinFghces1Asigl0()->where("SUB_ASIGL0", $lot->cod_sub)->where("REF_ASIGL0", $lot->ref)->first();

			if(!empty($stock) && $stock->controlstock_hces1 == 'S' ){
					#DESCONTAMOS 1 EL STOCK
					\Log::info("entrando en stock");
					$update =["stock_hces1" =>  $stock->stock_hces1 -1];
					FgHces1::where("NUM_HCES1", $stock->num_hces1)->where("LIN_HCES1", $stock->lin_hces1)->update($update);
					#se le otorga otra referencia para que el original no se adjudique y se adjudique la copia
					$lot->ref = $this->duplicarObra($lot->cod_sub,$lot->ref);
					#modificamos la referencia para que se tenga en la transacción la misma referencia que se ha vendido
					$info->lots[$keyLot]->ref = $lot->ref;
					$updateInfo = true;
					#MODIFICAR LA REFERENCIA EN INFO Y AL FINA LDE TODO SUSTITUIR LA INFO ORIGINAL POR LA NUEVA, ASI SE REGISTRAN BIEN LSO LOTES VENDIDOS
			}

			//datos para hacer la puja
			#HAY QUE COJER LA SUBASTA DE CADA LOTE YA QUE PUEDEN PERTENECER A SUBASTAS DIFERENTES
			$subasta->cod =  $lot->cod_sub;
			$checklicit = $subasta->checkLicitador();

			$subasta->licit =head($checklicit)->cod_licit;
			$subasta->imp = $lot->importe;
			$subasta->ref = $lot->ref;

			//debe ir a true para que no compruebe que este cerrado
			$result = $subasta->addPuja(TRUE);

			\Log::info("adjudicando lote". $lot->cod_sub ."   ". $lot->ref);

			$a=DB::select("call CERRARLOTE(:subasta, :ref, :emp, :user_rp, :redondeo)",
			array(
				'subasta'    => $lot->cod_sub,
				'ref'        => $lot->ref,
				'emp'        => Config::get('app.emp'),
				'user_rp'     => 'admin',
				'redondeo'     => 2
				)
			);
		}
		# si es necesario updatar la información de la transacción
		if($updateInfo){
			WebPayCart::where("IDTRANS_PAYCART", $idTrans)->update(["info_paycart" =>  json_encode($info)]);

		}

		if(Config::get('app.WebServicePaidInvoice')){

			$theme  = Config::get('app.theme');
			$rutaPaidController = "App\Http\Controllers\\externalws\\$theme\PaidController";

			$paidController = new $rutaPaidController();

			$paidController->informPaid($idTrans);
		}



	}

	public function duplicarObra($codSub,$ref){

		$obra = FgAsigl0::where("sub_asigl0", $codSub)->where("ref_asigl0", $ref)->first();
		$obra->oculto_asigl0 = "S";
		$obra->ref_asigl0 = FgAsigl0::select("nvl(max(ref_asigl0),0) +1 as ref_asigl0")->where("sub_asigl0", $codSub)->first()->ref_asigl0;
		#nuevo id_origen para que no este duplicado y de fallo al guardar el original
		$obra->idorigen_asigl0 = $obra->sub_asigl0."-".$obra->ref_asigl0;

		FgAsigl0::create($obra->toArray());
		return $obra->ref_asigl0;
	}

/*
	public function loadInfoLotsCart(){
		$this->loadCart();
		$fgasigl0 = new FgAsigl0();
		#cogemos los lotes
		foreach($this->shoppingCart as $cod_sub => $lots){
			$fgasigl0 = $fgasigl0->orWhere(function($query) use  ($cod_sub, $lots) {
				$query->where("sub_asigl0", $cod_sub)
				->whereIn("ref_asigl0", array_keys($lots));
			});

		}
		$fgasigl0 = $fgasigl0->JoinFghces1Asigl0()->JoinFgOrtsec1Asigl0()->LeftJoinAlm();
		$lots = $fgasigl0->select("SUB_ASIGL0, REF_ASIGL0, IMPSALHCES_ASIGL0, IDORIGEN_ASIGL0 ,COML_HCES1 , DESCWEB_HCES1, NUM_HCES1, LIN_HCES1,  SEC_HCES1, LIN_ORTSEC1, DES_ALM, ALM_HCES1, DIR_ALM")->get();
		return $lots;

	}
	*/
}

?>
