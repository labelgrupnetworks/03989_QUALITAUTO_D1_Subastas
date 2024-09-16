<?php

namespace App\Http\Controllers\V5;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\V5\ArticleController;
use App\libs\EmailLib;
use App\Models\articles\FgArt0;
use App\Models\articles\FgPedc0;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PayArticleCartController extends Controller
{
	public function createPayment()
	{
		$res = array(
			"status" => "error",
			"msgError" => "generic"
		);

		$cod_cli = Session::get('user.cod');
		#generamos la información a guardar.
		$inf = new \stdClass();
		$articleController = new ArticleController();

		$articles  = $articleController->loadArticleCart();
		$units = $articleController->articleCart;
		$importeLotes = 0;

		$paymethod = request("paymethod");
		$comments = request("comments") . "\n\n";
		#si hay que grabar un anillo
		$grabados = request("grabados");

		if (is_array($grabados)) {
			foreach ($grabados as $idArt => $grabadoArticulo) {
				foreach ($grabadoArticulo as $grabadoUnidad) {
					$comments .= "Grabado $idArt-" . $articles[$idArt]->model_art0 . ": " . $grabadoUnidad . "\n";
				}
			}
		}

		#cargamos el iva
		$cartController = new CartController();
		$iva =  $cartController->ivaAplicable();
		$idPedido = FgPedc0::select("nvl(max(NUMFIC_PEDC0),0) +1 as NUMFIC")->first();
		$hash = strtoupper(md5(time()));
		#el resultado debe ser de 17 carcateres
		$numOrder = substr($hash, 0, 16 - (strlen($idPedido->numfic))) . "-" . $idPedido->numfic;
		#poner order
		$user = FxCli::select("CP_CLI, POB_CLI, PRO_CLI, TEL1_CLI, CIF_CLI")->WHERE("COD_CLI", $cod_cli)->first();

		DB::select(
			"call Crea_pedido.CREA_CAPCELERA(:gemp, :emp, :idPed, :codCli, :cp, :pob, :prov, :telf, :obs, :nif, :codDir, :transport, :payment, to_char(sysdate,'YYYY-MM-DD  HH24:MI:ss'), :numOrder)",
			array(
				'gemp'        => Config::get('app.gemp'),
				'emp'        => Config::get('app.emp'),
				'idPed'    => $idPedido->numfic,
				'codCli'        => $cod_cli,
				'cp'     => $user->cp_cli,
				'pob'     => $user->pob_cli,
				'prov'     => $user->pro_cli,
				'telf'     => $user->tel1_cli,
				'obs'     =>  $comments,
				'nif'     => $user->cif_cli,
				'codDir'	=> '00',
				'transport' => '',
				'payment'	=> $paymethod,
				'numOrder'		=> $numOrder
			)
		);

		foreach ($articles as $article) {

			$impUnit = round($article->pvp_art + ($article->pvp_art * $iva), 2);
			$importeLotes += $impUnit * $units[$article->id_art];

			DB::select(
				"call Crea_pedido.CREA_LINIES(:idPed, :emp, :seccio, :codi, :cant, to_char(sysdate,'YYYY-MM-DD'),:descuento)",
				array(
					'idPed'    => $idPedido->numfic,

					'emp'        => Config::get('app.emp'),
					'seccio'        => $article->sec_art,
					'codi'     => $article->cod_art,
					'cant'     =>  $units[$article->id_art],
					'descuento' => 0
				)
			);
			#quitamos el lote del carrito
			#para las pruebas no quitamos las cosas del carrito
			unset($articleController->articleCart[$article->id_art]);
		}

		#salvamos para que se actualice el carrito en memoria
		$articleController->saveArticleCart();
		$importeTotal = $importeLotes;

		#CREAMOS EL ID DE LA TRANSACCION, Los articulos no llevaran letra, en universal pay usaremso el numOrden como id de transaccion para dar más seguridad, ya que el otro id se comprate al hacer transferencia
		$idtranspaycart =  $idPedido->numfic;

		#es necesario para que las cookies se actualicen y se pueda vaciar correctamente el carrito
		response();

		#Si han elegido el pago por transferencia reenviamos a la página que mostrará el texto
		if ((!empty(request("paymethod")) && request("paymethod") == "transfer")) {

			//llamada a la funcion que cierra los lotes y los adjudica y llama al webservice si hace falta
			#$this->returnPay($idtranspaycart);
			$importe = base64_encode($inf->total);
			$control =  md5($importe . Session::get('user.cod'));
			$idtrans =  $idtranspaycart;
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

			$url = Config::get('app.url') . '/articleCart/callRedsys?idTrans=' . $numOrder . $paymethod;

			$res = array(
				"status" => "success",
				"location" => $url
			);
		} elseif (Config::get('app.paymentUP2') == 'UP2') {
			//Peticion universal pay
			$return_token = $this->tokenPasarelaUP2($cod_cli, $numOrder, $importeTotal);
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

		return $res;
	}



	#Carga el formulariode redsys
	public function callRedsys()
	{
		$paymentcontroller = new PaymentsController();
		$idTrans = request("idTrans");
		$pedido = FgPedc0::where("ORDEN_PEDC0", $idTrans)->first();

		//$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();
		if (empty($pedido)) {
			abort(404);
		}

		//El nº de pedido para redsys debe ser de entre 3 y 12 caracteres sin simbolos
		$numfic = str_pad($pedido->numfic_pedc0, 3, "0", STR_PAD_LEFT);
		$idToRedsys = "C{$numfic}";

		//dd($pedido, $idTrans);
		Log::info("Dentro de llamada a redsys");
		$varsRedsys = $paymentcontroller->requestRedsys($pedido->total_pedc0, $idToRedsys, '/gateway/pagoDirectoReturn');

		//dd($varsRedsys);
		#reenviamos al formulario
		return view('front::pages.panel.RedsysForm', $varsRedsys);
	}

	#llamada que hace redsys para indicarnos que transaccion se ha pagado
	#tambien se llama si el pago es por transferencia, en el momento de elegir ese tipo de pago
	public function returnPay($idTrans)
	{
		Log::info("Dentro de returnPay", ['idtrans' => $idTrans]);
		$idTrans = ltrim($idTrans, '0');
		FgPedc0::where("numfic_pedc0", $idTrans)
			->update([
				"ACCEPPTO_PEDC0" => "S",
				"FECACCPTO_PEDC0" => date("Y-m-d H:i:s")
			]);

		$pedido = FgPedc0::where("numfic_pedc0", $idTrans)->first();
		$this->sendPayMail($pedido->orden_pedc0);
	}


	//Peticion token universal pay
	public function tokenPasarelaUP2($cod_cli, $numOrder, $importeTotal, $notificationUrl = '/articleCart/returnpayup2', $codSub = null)
	{
		$client = FxCli::where("gemp_cli", Config::get("app.gemp"))->where("cod_cli", $cod_cli)->SelectBasicCli()->JoinCliWebCli()->addSelect("codpais_cli")->first();

		$paymentcontroller = new PaymentsController();
		$tipo = substr($numOrder, 0, 1);

		$up2Vars = $paymentcontroller->universalPay2Vars($codSub, $tipo);

		$time = strtotime("now") * 1000;
		$url = Config::get('app.url');
		$fields = array(
			'customerLastName' => trim($client->nom_cli),
			'customerId' => $numOrder,
			'merchantTxId' => $numOrder,
			'customerEmail' => trim($client->email_cli),
			'merchantReference' =>  $cod_cli,
			'amount' => floatval($importeTotal),
			'brandId' => $up2Vars["brandId"],
			'merchantId' => $up2Vars["merchantId"],
			'password' => $up2Vars["password"],
			'action' => 'PURCHASE',
			'language' => !empty($client->idioma_cli) ? strtolower($client->idioma_cli) : 'es',
			'timestamp' => $time,
			'allowOriginUrl' => $url,
			'channel' => 'ECOM',
			'currency' => 'EUR',
			'country' =>  $client->codpais_cli ?? 'ES',
			'merchantNotificationUrl' => $url . $notificationUrl,
			'merchantLandingPageUrl' => $url . '/gateway/returnPayPage',
			'specinProcessWithoutCvv2' => 'false',
			'forceSecurePayment' => 'true',
		);



		//url-ify the data for the POST
		$fields_string = http_build_query($fields);
		Log::info("URL de pago: " . $up2Vars["url_pay"] . "?$fields_string");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $up2Vars["url_pay"]);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		Log::info("Request: " . json_encode($fields));
		Log::info("Return: " . json_encode($response));

		return json_decode($response, true);
	}

	public function ReturnPayUP2()
	{
		$post = $_POST;
		Log::info("Pago: " . print_r($post, true));

		if ($post['status'] == 'CAPTURED') {
			if (!empty($post['merchantTxId'])) {
				FgPedc0::where("ORDEN_PEDC0", $post['merchantTxId'])
					->update([
						"ACCEPPTO_PEDC0" => "S",
						"FECACCPTO_PEDC0" => date("Y-m-d H:i:s")
					]);
			}
			#enviar emails de pago
			$this->sendPayMail($post['merchantTxId']);
		}

		# falta enviar email de articulos pagados al admin
		#falta enviar email de articulos pagados al usuario
		/*
		[merchantTxId] => 122
		[txId] => 12147274
		[currency] => EUR
		[amount] => 3500
		[result] => success
		*/
	}

	public function sendPayMail($numOrder)
	{
		$FgArt0 = new FgArt0();
		#envio de email de compra
		$pedido = $FgArt0->select("COD_PEDC0,MODEL_ART0, COD_ART, IMP_PEDC1, CANT_PEDC1,  IVA_PEDC1, IMPIVA_PEDC1, BASE_PEDC0, IMPIVA_PEDC0, TOTAL_PEDC0, OBS_PEDC0 ")
			->JoinArt()->JoinFgPedc1()->JoinFgPedc0()

			->where("ORDEN_PEDC0", $numOrder)->get();

		if (count($pedido) > 0) {
			$articulos = array();

			foreach ($pedido as $articuloPedido) {


				$articulo = $articuloPedido->toArray();
				$articulo["variantes"] = array();
				$variantes = FgArt0::select("VALOR_VALVARIANTE, NAME_VARIANTE")->joinart()->JoinLineasVariantes()->JoinValVariantes()->JoinVariantes()->where("COD_ART", $articulo["cod_art"])->get();
				foreach ($variantes as $variante) {
					$articulo["variantes"][$variante->name_variante] = $variante->valor_valvariante;
				}
				$articulos[] = $articulo;
			}
			#cliente

			$articulosBody = View('emails.articulos.articulos', array('articulos' => $articulos))->render();


			$email = new EmailLib('ARTICLES_PAY');
			if (!empty($email->email)) {
				$email->setUserByCod($pedido[0]->cod_pedc0, true);
				$email->setPrice(ToolsServiceProvider::moneyFormat($pedido[0]->total_pedc0, trans(Config::get('app.theme') . '-app.lot.eur'), 2));
				$email->setHtml($articulosBody);
				$email->setAtribute("COMMENT", nl2br($pedido[0]->obs_pedc0));
				$email->send_email();
			}

			$email = new EmailLib('ARTICLES_PAY_ADMIN');
			if (!empty($email->email)) {
				$setTo = Config::get('app.admin_email_venta_articulo') ??  Config::get('app.admin_email');
				Log::info($setTo);
				#definimos el usuario para mostrar datos, pero el email no va para el
				$email->setUserByCod($pedido[0]->cod_pedc0, false);
				$email->setTo($setTo);
				$email->setPrice(ToolsServiceProvider::moneyFormat($pedido[0]->total_pedc0, trans(Config::get('app.theme') . '-app.lot.eur'), 2));
				$email->setHtml($articulosBody);
				$email->setAtribute("COMMENT", nl2br($pedido[0]->obs_pedc0));
				$email->send_email();
			}
		}
	}



	/*
	public function loadInfoLotsCart(){
		$this->loadCart();
		$fgasigl0 = new FgAsigl0();
		#cogemos los lotes
		foreach($this->shoppingCart as $cod_sub => $articles){
			$fgasigl0 = $fgasigl0->orWhere(function($query) use  ($cod_sub, $articles) {
				$query->where("sub_asigl0", $cod_sub)
				->whereIn("ref_asigl0", array_keys($articles));
			});

		}
		$fgasigl0 = $fgasigl0->JoinFghces1Asigl0()->JoinFgOrtsec1Asigl0()->LeftJoinAlm();
		$articles = $fgasigl0->select("SUB_ASIGL0, REF_ASIGL0, IMPSALHCES_ASIGL0, IDORIGEN_ASIGL0 ,COML_HCES1 , DESCWEB_HCES1, NUM_HCES1, LIN_HCES1,  SEC_HCES1, LIN_ORTSEC1, DES_ALM, ALM_HCES1, DIR_ALM")->get();
		return $articles;

	}
	*/
}
