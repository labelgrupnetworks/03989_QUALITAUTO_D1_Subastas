<?php

namespace App\Http\Controllers\V5;

use App\Http\Controllers\Controller;
use App\Http\Controllers\externalws\vottun\VottunController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\V5\CartController;
use App\Http\Controllers\V5\PayArticleCartController;
use App\libs\EmailLib;
use App\Models\Subasta;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;
use App\Models\V5\FgHces1;
use App\Models\V5\FgNft;
use App\Models\V5\FxCli;
use App\Models\V5\FxClid;
use App\Models\V5\WebPayCart;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class PayShoppingCartController extends Controller
{

	public function createPayment()
	{

		$res = array(
			"status" => "error",
			"msgError" => "generic"
		);

		$codSub = request("codSub");

		$paymentcontroller = new PaymentsController();

		$cod_cli = Session::get('user.cod');



		#generamos la información a guardar.
		$inf = new \stdClass();
		$cartController = new CartController();
		$iva =  $cartController->ivaAplicable();

		#checkeamos los lotes antes de confirmar compra
		if (! $cartController->checkToBuy()) {
			#faltan productos, hay que avisar al usuario y refrescar la web
			$res["msgError"] = "lotsLost";
			Log::info("faltan lotes");
			return $res;
		}


		$lots  = $cartController->loadLotsCart($codSub);

		$importeLotes = 0;

		$inf->paymethod = request("paymethod");
		$inf->comments = request("comments");
		$inf->lots = array();
		foreach ($lots as $lot) {

			#mas comision
			$comision = round($lot->impsalhces_asigl0 * $lot->coml_hces1 / 100, 2);
			#mas iva de la  comision
			$ivacomision = round($comision * $iva, 2);
			#precio salida `comision + iva comision
			$importeLotes += $lot->impsalhces_asigl0 + $comision + $ivacomision;

			#guardamos la información de los lotes comprados
			$lotInfo = new \stdClass();
			$lotInfo->cod_sub = $lot->sub_asigl0;
			$lotInfo->ref = $lot->ref_asigl0;
			$lotInfo->importe =  $lot->impsalhces_asigl0;

			if (Config::get("app.TaxForEuropean")) {
				$lotInfo->iva = $lot->iva;
			}
			$inf->lots[] = $lotInfo;

			#quitamos el lote del carrito sin cancelar las reservas en base de datos ni en webservice

			unset($cartController->shoppingCart[$lot->sub_asigl0][$lot->ref_asigl0]);
		}
		#salvamos para que se actualice el carrito en memoria
		$cartController->saveCart();
		$importeTotal = $importeLotes;
		# si hay envio

		if (!empty(request("envio_carrito"))) {
			$clidd = request("clidd_carrito");
			$inf->envio = 1;
			$envio = $paymentcontroller->calc_web_gastos_envio($lots, $clidd);

			#direccion envio
			$cod_cli = Session::get('user.cod');
			if (!empty($cod_cli) && !empty($clidd)) {
				$direccionEnvio = FxClid::select("CP_CLID, CODPAIS_CLID, DIR_CLID, POB_CLID, TEL1_CLID, PRO_CLID")->WHERE("CODD_CLID", $clidd)->where("cli_clid", $cod_cli)->first();
				if (!empty($direccionEnvio)) {
					$inf->pais = $direccionEnvio->codpais_clid;
					$inf->provincia = $direccionEnvio->pro_clid;
					$inf->poblacion = $direccionEnvio->pob_clid;
					$inf->direccion = $direccionEnvio->dir_clid;
					$inf->cp = $direccionEnvio->cp_clid;
					$inf->telefono = $direccionEnvio->tel1_clid;
				}
			}

			#se supone que no debería haber llegado si no es transportable
			if ($envio  > -1) {
				$inf->gastosEnvio = $envio;
				#iva gastos de envio
				$inf->ivaGastosEnvio =  round($inf->gastosEnvio * $iva, 2);
				$importeTotal += round($inf->gastosEnvio + $inf->ivaGastosEnvio, 2);
			} else {
				$inf->envio = 0;
			}

			if (!empty(request("seguro_carrito"))) {
				$inf->seguro = 1;
				$inf->importeSeguro = round($importeLotes * Config::get('app.porcentaje_seguro_envio') / 100, 2);
				$inf->ivaSeguro =  round($inf->importeSeguro * $iva, 2);
				$importeTotal += $inf->importeSeguro + $inf->ivaSeguro;
			}
		}

		#es un campo meramente informativo no se realizan calculos,
		if (!empty(request("seguro_carrito_info"))) {
			$inf->seguro = 1;
		}

		$inf->total = $importeTotal;
		$webpayCart["CLI_PAYCART"] = $cod_cli;
		$webpayCart["EMP_PAYCART"] = Config::get("app.emp");

		#CREAMOS EL ID DE LA TRANSACCION, LA LETRA QUE IDENTIFICARÁ LSO PAGOS DE TIENDASERÁ LA T
		$webpayCart["IDTRANS_PAYCART"] = "T" . rand(1, 9) . time();
		$webpayCart["DATE_PAYCART"] = date("Y-m-d H:i:s");
		$webpayCart["INFO_PAYCART"] = json_encode($inf);
		WebPayCart::insert($webpayCart);

		Log::info(json_encode($inf));
		Log::info(" importeTotal: $importeTotal ");

		#Si han elegido el pago por transferencia reenviamos a la página que mostrará el texto
		if (!empty(request("paymethod")) && request("paymethod") == "transfer") {

			//llamada a la funcion que cierra los lotes y los adjudica y llama al webservice si hace falta
			$this->returnPay($webpayCart["IDTRANS_PAYCART"]);
			$importe = base64_encode($inf->total);
			$control =  md5($importe . Session::get('user.cod'));
			$idtrans =  $webpayCart["IDTRANS_PAYCART"];
			$url = route("transferpayment", ["lang" => Config::get("app.locale")]) . "?control=$control&trans=$importe&idtrans=$idtrans";

			$res = array(
				"status" => "success",
				"location" => $url

			);
			return $res;
		} elseif (Config::get('app.paymentRedsys')) {
			if (!empty(request("paymethod"))) {
				$paymethod = "&paymethod=" . request("paymethod");
			}

			$url = Config::get('app.url') . '/shoppingCart/callRedsys?idTrans=' . $webpayCart["IDTRANS_PAYCART"] . $paymethod;

			$res = array(
				"status" => "success",
				"location" => $url
			);
		} elseif (Config::get('app.paymentUP2') == 'UP2') {


			//Peticion universal pay
			#usamos el creador de payArticle
			$payArticle = new  PayArticleCartController();
			# le pasamos la dirección /gateway/pagoDirectoReturn para que haga al pagar vaya a Paymentscontroller
			$return_token = $payArticle->tokenPasarelaUP2($cod_cli, $webpayCart["IDTRANS_PAYCART"], $importeTotal, "/gateway/pagoDirectoReturn", $codSub);

			if (!empty($return_token) && $return_token['result'] == 'success') {

				if (!env('APP_DEBUG') && Config::get('app.environmentUP2')) {
					$url_pay = 'https://cashierui.universalpay.es/ui/cashier';
				} else {
					$url_pay = 'https://cashierui.test.universalpay.es/ui/cashier';
				}
				$url = $url_pay . "?merchantId=" . $return_token['merchantId'] . "&token=" . $return_token['token'] . "&integrationMode=standalone&paymentSolutionId=500";

				Log::info("Request Universal Pay: " . $url);
				$res = array(
					"status" => "success",
					"location" => $url
				);
			} else {
				$res = array(
					"status" => "error"
				);
			}
		}
		#es necesario el response() para que las cookies se actualicen y se pueda vaciar correctamente el carrito
		response();
		return $res;
	}

	#Carga el formulariode redsys
	public function callRedsys()
	{

		$paymentcontroller = new PaymentsController();
		$idTrans = request("idTrans");
		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();

		if (empty($transaccion)) {
			exit(View::make('front::errors.404'));
		}
		$info = json_decode($transaccion->info_paycart);
		Log::info("Dentro de llamada a redsys");

		$varsRedsys = $paymentcontroller->requestRedsys($info->total, $idTrans, '/gateway/pagoDirectoReturn');

		#reenviamos al formulario
		return View::make('front::pages.panel.RedsysForm', $varsRedsys);
	}

	#llamada que hace redsys/ universal pay para indicarnos que transaccion se ha pagado
	#tambien se llama si el pago es por transferencia, en el momento de elegir ese tipo de pago
	public function returnPay($idTrans)
	{
		Log::info("Dentro de Return Pay $idTrans");
		#codigo para pruebas
		//http://www.newsubastas.test/shoppingCart/returnPay?idTrans=T81607077859
		//$idTrans = request("idTrans");

		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();
		if (empty($transaccion)) {
			Log::info("Error en pasarela de pago de tienda online, $idTrans no se encuentra en base de datos ");
			return;
		}

		#MARCAMOS EL PEDIDO COMO PAGAGO
		WebPayCart::where("IDTRANS_PAYCART", $idTrans)->update(["PAID_PAYCART" => "S"]);




		$tipoPago = substr($idTrans, 0, 1);

		if ($tipoPago == "T") {
			$info = json_decode($transaccion->info_paycart);
			if (empty($info) || empty($info->lots)) {
				Log::info("Error en pasarela de pago de tienda online, no hay lotes asociados al id $idTrans   ");
				return;
			}
			$subasta = new Subasta();
			$subasta->cli_licit = $transaccion->cli_paycart;


			$subasta->type_bid = 'W';
			#de momento no es necesario modificar la info de la transaccion, solo si algun producto tiene stock
			$updateInfo = false;

			foreach ($info->lots as $keyLot => $lot) {
				#control de stock si es necesario
				$stock = FgAsigl0::select("CONTROLSTOCK_HCES1, nvl(STOCK_HCES1,0) STOCK_HCES1, NUM_HCES1, LIN_HCES1, DESCWEB_HCES1, REF_ASIGL0")->JoinFghces1Asigl0()->where("SUB_ASIGL0", $lot->cod_sub)->where("REF_ASIGL0", $lot->ref)->first();
				$mailLots[] = $stock;
				if (!empty($stock) && $stock->controlstock_hces1 == 'S') {
					#DESCONTAMOS 1 EL STOCK
					Log::info("entrando en stock");
					$update = ["stock_hces1" =>  $stock->stock_hces1 - 1];
					FgHces1::where("NUM_HCES1", $stock->num_hces1)->where("LIN_HCES1", $stock->lin_hces1)->update($update);
					#se le otorga otra referencia para que el original no se adjudique y se adjudique la copia
					$lot->ref = $this->duplicarObra($lot->cod_sub, $lot->ref);
					#modificamos la referencia para que se tenga en la transacción la misma referencia que se ha vendido
					$info->lots[$keyLot]->ref = $lot->ref;
					$updateInfo = true;
					#MODIFICAR LA REFERENCIA EN INFO Y AL FINA LDE TODO SUSTITUIR LA INFO ORIGINAL POR LA NUEVA, ASI SE REGISTRAN BIEN LSO LOTES VENDIDOS
				}

				//datos para hacer la puja
				#HAY QUE COJER LA SUBASTA DE CADA LOTE YA QUE PUEDEN PERTENECER A SUBASTAS DIFERENTES
				$subasta->cod =  $lot->cod_sub;
				$checklicit = $subasta->checkLicitador();

				$subasta->licit = head($checklicit)->cod_licit;
				$subasta->imp = $lot->importe;
				$subasta->ref = $lot->ref;

				//debe ir a true para que no compruebe que este cerrado
				$result = $subasta->addPuja(TRUE);

				Log::info("adjudicando lote" . $lot->cod_sub . "   " . $lot->ref);

				$a = DB::select(
					"call CERRARLOTE(:subasta, :ref, :emp, :user_rp, :redondeo)",
					array(
						'subasta'    => $lot->cod_sub,
						'ref'        => $lot->ref,
						'emp'        => Config::get('app.emp'),
						'user_rp'     => 'admin',
						'redondeo'     => 2
					)
				);



				#Despues de crear la adjudicación debemos marcarla cómo pagada.
				FgCsub::where("SUB_CSUB", $lot->cod_sub)->where("REF_CSUB", $lot->ref)->update(["AFRAL_CSUB" => "L00"]);
			}
			# si es necesario updatar la información de la transacción
			if ($updateInfo) {
				WebPayCart::where("IDTRANS_PAYCART", $idTrans)->update(["info_paycart" =>  json_encode($info)]);
			}
			#falta hacer el envio del email
			$this->sendEmailPaid($idTrans);



			#es un pago especial, puede ser un pago de minteo NFT o un pago de transferencia de NFT

		} elseif ($tipoPago == "M") {

			$info = json_decode($transaccion->info_paycart);

			if ($info->reason == "mint") {
				foreach ($info->lots as $keyLot => $lot) {
					#guardamos el id de transaccion en la tabla de NFt para que se sepa que está pagado
					FgNft::where("NUMHCES_NFT", $lot->num)->where("LINHCES_NFT", $lot->lin)->update(["PAY_MINT_NFT" => $idTrans]);
					#FALTA LLAMADA A WEBSERVICE DE DURAN INDICANDO QUE EL AUTOR A PAGADO EL MINTEO DEL NFT
				}
			} elseif ($info->reason == "transfer") {
				foreach ($info->lots as $keyLot => $lot) {
					#guardamos el id de transaccion en la tabla de NFt para que se sepa que está pagado
					FgNft::where("NUMHCES_NFT", $lot->num)->where("LINHCES_NFT", $lot->lin)->update(["PAY_TRANSFER_NFT" => $idTrans]);
					#FALTA LLAMADA A WEBSERVICE DE DURAN INDICANDO QUE EL Cliente ha pagado la transacción
				}
			}
		}

		if (Config::get('app.WebServicePaidInvoice')) {

			$theme  = Config::get('app.theme');
			$rutaPaidController = "App\Http\Controllers\\externalws\\$theme\PaidController";

			$paidController = new $rutaPaidController();

			$paidController->informPaid($idTrans);
		}
	}

	public function duplicarObra($codSub, $ref)
	{

		$obra = FgAsigl0::where("sub_asigl0", $codSub)->where("ref_asigl0", $ref)->first();
		$obra->oculto_asigl0 = "S";
		$obra->ref_asigl0 = FgAsigl0::select("nvl(max(ref_asigl0),0) +1 as ref_asigl0")->where("sub_asigl0", $codSub)->first()->ref_asigl0;
		#nuevo id_origen para que no este duplicado y de fallo al guardar el original
		$obra->idorigen_asigl0 = $obra->sub_asigl0 . "-" . $obra->ref_asigl0;

		FgAsigl0::create($obra->toArray());
		return $obra->ref_asigl0;
	}

	#generar pago de coste de transferencia, puede venir mas de una transferencia
	#http://www.newsubastas.test/mintpayment/7de69f65-f697-40bd-b7bc-fddaaa6b515b
	public function createMintPay($operationsIds)
	{
		return $this->createTransactionPay("MINT", $operationsIds);
	}

	#generar pago de coste de transferencia, puede venir mas de una transferencia
	#http://www.newsubastas.test/transferpayment/b040c87d-7772-460a-aafb-7efb9484db6d_b8e4f247-34eb-4c04-9599-0263b2fe7a21_
	public function createTransferPay($operationsIds)
	{
		return $this->createTransactionPay("TRANSFER", $operationsIds);
	}

	public function createTransactionPay($type, $operationsIds)
	{
		/*
		if(!Session::has('user')){
			#nostramos página con mensaje de error
			return View::make('front::pages.not-logged',["data" =>trans(Config::get('app.theme').'-app.user_panel.not-logged') ] );
		}
		*/
		$vottunController = new VottunController();
		#networks de pago
		$payNetworks = explode(",", str_replace(" ", "", Config::get("app.nftPayNetwork")));
		$transactionsId =  explode("_", $operationsIds);

		$asigl0 = new Fgasigl0();
		$asigl0 = $asigl0->JoinFghces1Asigl0()->JoinNFT()->select("NUMHCES_NFT, LINHCES_NFT,  DESCWEB_HCES1 ")->

			#networks de pago, si no son de pago no se deberá cobrar
			wherein("NETWORK_NFT", $payNetworks)->where("ES_NFT_ASIGL0", "S");

		if ($type == "TRANSFER") {
			$asigl0 = $asigl0->addselect("TRANSFER_ID_NFT,COST_TRANSFER_NFT COST, PAY_TRANSFER_NFT,CLIFAC_CSUB")->JoinCSubAsigl0()->
				#where("CLIFAC_CSUB",Session::get('user.cod'))->
				#si está pendiente de pago
				where("PAY_TRANSFER_NFT", "P")->
				#id de las transferencias
				wherein("TRANSFER_ID_NFT", $transactionsId)->
				# si la transferencia tiene importe es que se debe pagar, puede estar pagada o no pero es la manera de saber que nft mostrar en este listado
				where("COST_TRANSFER_NFT", ">", 0);
		} elseif ($type == "MINT") {
			$asigl0 = $asigl0->addselect("MINT_ID_NFT,COST_MINT_NFT COST, PAY_MINT_NFT,PROP_HCES1")->where("PAY_MINT_NFT", "P")->wherein("MINT_ID_NFT", $transactionsId)->where("COST_MINT_NFT", ">", 0);
		}

		$transactions = $asigl0->get();

		#si no se recupera ninguna transaccion o no se recuperan todas las transferencias que se han pedido
		if (count($transactions) == 0  || count($transactions) != count($transactionsId)) {
			Log::info("Error en pago generar pago de transferencia, no coinciden el numero de transferecnia con el numero de id's facilitado." . print_r($transferId, true) . print_r($transfers->toArray(), true));
			#nostramos página con mensaje de error
			return View::make('front::pages.not-logged', ["data" => trans(Config::get('app.theme') . '-app.user_panel.error_pay_transfer_nft')]);
		}

		$info = new \Stdclass();
		$info->paymethod = "creditcard";
		$webpayCart = array();
		if ($type == "TRANSFER") {
			$webpayCart["CLI_PAYCART"] = $transactions[0]->clifac_csub;
			$info->comments = "pago transferencia NFT";
			$info->reason = "transfer";
		} elseif ($type == "MINT") {
			$webpayCart["CLI_PAYCART"] = $transactions[0]->prop_hces1;
			$info->comments = "pago minteo NFT";
			$info->reason = "mint";
		}

		$info->lots = [];
		$info->total = 0;
		foreach ($transactions as $transaction) {
			$lot = new \Stdclass();
			$lot->num =  $transaction->numhces_nft;
			$lot->lin =  $transaction->linhces_nft;
			$info->lots[] = $lot;
			# el coste de la transferencia se ha cargado en la tabla FGNFT en el momento de hacer el webhook
			$info->total += $transaction->cost;
		}


		$webpayCart["EMP_PAYCART"] = Config::get("app.emp");

		#CREAMOS EL ID DE LA TRANSACCION, LA LETRA QUE IDENTIFICARÁ LOS PAGOS DE MINTEO Y TRANSFERENCIA SERÁ LA M
		$webpayCart["IDTRANS_PAYCART"] = "M" . rand(1, 9) . time();
		$webpayCart["DATE_PAYCART"] = date("Y-m-d H:i:s");
		$webpayCart["INFO_PAYCART"] = json_encode($info);
		WebPayCart::insert($webpayCart);


		$url = Config::get('app.url') . '/shoppingCart/callRedsys?idTrans=' . $webpayCart["IDTRANS_PAYCART"] . "&paymethod=creditcard";
		return redirect($url);
	}

	public function sendEmailPaid($idTrans)
	{
		#pendiente de hacer que envie email con los datos de la compra, tanto al comprador como al admin
		$email = new EmailLib('SHOPPING_CART_PAY');
		if (empty($email->email)) {
			return false;
		}

		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();
		$info = json_decode($transaccion->info_paycart, true);

		//información de los lotes
		['total' => $total, 'lots' => $lots] = $info;
		$lots = collect($lots);

		$fgasigl0 = FgAsigl0::select('descweb_hces1', 'num_hces1', 'lin_hces1', 'impsalhces_asigl0')->joinFghces1Asigl0();

		$lots->groupBy('cod_sub') //agrupamos por subasta
			->each(function ($lotsPerAuction, $cod_sub) use ($fgasigl0) { //para cada subasta
				$fgasigl0->orWhere(function ($query) use ($lotsPerAuction, $cod_sub) {
					$query->where("sub_asigl0", $cod_sub)
						->whereIn("ref_asigl0", $lotsPerAuction->pluck('ref'));
				});
			});

		$lotsQuery = $fgasigl0->get();
		$cliente = FxCli::select("NOM_CLI,  EMAIL_CLI ")->where("cod_cli", $transaccion->cli_paycart)->first();

		//montar tabla html con info de los lotes
		$html = view('front::emails.paid_lot', ['lots' => $lotsQuery, 'info' => $info, 'cliente' => $cliente])->render();



		//enviar email a usuario y admin
		$email->setUserByCod($transaccion->cli_paycart, true);
		$email->setPrice($total);
		$email->setAtribute('HTML', $html);
		$email->setBcc(config('app.admin_email_administracion'));
		$email->send_email();

		return true;
		/*
		$fgasigl0 = new FgAsigl0();
		$auctions = array();
		foreach($info->lots as $keyLot => $lot) {
			if(empty($auctions[$lot->cod_sub])){
				$auctions[$lot->cod_sub] = array();
			}
			$auctions[$lot->cod_sub][] = $lot->ref;
		}

		foreach($auctions as $cod_sub => $lots){

			$fgasigl0 = $fgasigl0->orWhere(function($query) use  ($cod_sub, $lots) {
				$query->where("sub_asigl0", $cod_sub)
				->whereIn("ref_asigl0", $lots);
			});

		}


		$fgasigl0 = $fgasigl0->JoinFghces1Asigl0()->JoinFgOrtsec1Asigl0()->JoinSubastaAsigl0();

		$lots = $fgasigl0->select(" SUB_ASIGL0, DES_SUB, REF_ASIGL0, IMPSALHCES_ASIGL0,COML_HCES1 , DESCWEB_HCES1, NUM_HCES1, LIN_HCES1, PESO_HCES1, PESOVOL_HCES1, SEC_HCES1, LIN_ORTSEC1, PERMISOEXP_HCES1")->get();

		dd($lots);
		*/
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
