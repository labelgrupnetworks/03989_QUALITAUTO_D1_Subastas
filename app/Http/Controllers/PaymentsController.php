<?php

namespace App\Http\Controllers;

use View;
use Config;
use Session;
use Request;

use App\Models\Payments;
use App\Models\delivery\Delivery;
use App\Models\User;
use \JasonGrimes\Paginator;
use Illuminate\Support\Facades\Storage;
use App\Models\Subasta;
use App\Models\Bloques;
use App\Models\Facturas;
use App\Models\Enterprise;
use Spipu\Html2Pdf\Html2Pdf;
use App\libs\EmailLib;
use App\libs\GastosEnvioLib;
use Illuminate\Support\Facades\DB;
use App\libs\RedsysAPI;

use App\Models\V5\FgAsigl0;
use App\Models\V5\FxClid;
use App\Http\Controllers\V5\PayShoppingCartController;
use App\libs\PayPalV2API;
use App\Models\V5\FsParams;
use App\Models\V5\FgCsub0;
use App\Models\V5\FgCsub;

class PaymentsController extends Controller
{
	public function index($function)
	{

		if ($function == 'pagoDirectoReturn') {

			if (Config::get('app.paymentRedsys') ) {
				$this->pagoDirectoReturnRedsys();
			}
			elseif (Config::get('app.paymentUP2') == 'UP2') {
				$this->pagoDirectoReturnUP2();
			}
		} elseif ($function == 'pagarLotesWeb') {
			$res = $this->pagarLotesWeb();
			return $res;
		} elseif ($function == 'pagarFacturasWeb') {
			$res = $this->pagarFacturasWeb();
			return $res;
		} elseif ($function == 'returnPayPage') {
			$link = $this->returnPayPage();
			header("Location: " . $link . "", true, 301);
			exit();
		}
	}

	public function pagarLotesWeb()
	{
		$conGastosEnvio = true;
		$subasta = new Subasta();
		$pago = new Payments();
		$deliverea = null;
		$emp  = Config::get('app.emp');
		$gemp  = Config::get('app.gemp');
		$hoy = date("Y-m-d");
		$importeLotes=0;
		$precio = (int) 0;
		$tax = (int) 0;
		$tax_temp = (int) 0;
		$envio = (int) 0;
		$gastos_envio = (int) 0;

		$jsonLot = array();
		//parametros de subasta, lo utilizamos por precio de seguro y de licencia exportacion
		$parametrosSub = $subasta->getParametersSub();



		//Creamos apre
		$apre = "R" . date("y");
		//Nos devuelve npre
		$npre = $this->contador2_ora('c06', 'R');

		$user_cod = Session::get('user.cod');

		$res_error = array(
			"status" => "error"
		);

		$carrito = $_POST['carrito'];

		//Miramos si tiene iva el cliente
		$iva = $this->getIva($emp, $hoy);

		$tipo_iva = $this->user_has_Iva($gemp, $user_cod);
		$imp_gastosErp = array('imp' => 0, 'iva' => 0);
		$price_exportacion_total = 0;

		foreach ($carrito as $sub => $value) {
			foreach ($value as $ref => $value) {

				$price_exportacion = 0;
				$transporte = 0;

				$shopping_cart = $_POST['carrito'][$sub][$ref];
				//Haya algun lote para pagar

				//delete linias asiglo2 de ese lote y de esa subasta
				if (!$pago->deleteAsigl2($ref, $sub, $emp)) {
					return $res_error;
				}

				if (!empty($shopping_cart['pagar'])) {

					$inf_env_lic = new \stdClass(); //create a new
					$inf_env_lic->openv = null;
					$inf_env_lic->infenv = null;
					$inf_env_lic->liceexp = 'N';
					$inf_env_lic->tasas = 'N';
					$inf_env_lic->fecharec = null;

					// comprobamos que exista y cojemos inf del lote
					$inf_lot = $pago->getPrice($ref, $sub, $emp, $user_cod);
					if (empty($inf_lot)) {
						return $res_error;
					}
					$inf_lot = head($inf_lot);


					$increment_asigl2 = $pago->getIncrementGastosExtras($ref, $sub, $emp);

					$iva_cli = $this->hasIvaReturnIva($tipo_iva->tipo, $iva);

					// calculamos iva del lote
					#Duran solo las online pasan por este circuito por eso no hace falta comparar si la subasta es de tipo online
					if(\Config::get("app.noIVAOnlineAuction") ){
						$tax = $tax +  0;
					}else{
						$tax = $tax + $this->calculate_iva($tipo_iva->tipo, $iva, $inf_lot->base_csub);
					}

					//Cogemos gastos extas si ya tenia una prefactura hecha
					$gastosErp = $pago->getPrefacturaGenerated($sub, $ref);
					if (Config::get('app.take_gastos_prefact') && !empty($gastosErp->apre_csub) && !empty($gastosErp->npre_csub) && $gastosErp->prefac_csub == 'S' && $imp_gastosErp['imp'] == 0) {
						$imp_gastosErp['imp'] = $gastosErp->impgas_csub0;
						$imp_gastosErp['iva'] = $this->calculate_iva($tipo_iva->tipo, $iva, $imp_gastosErp['imp']);
						$pago->updatePreFactB($gastosErp->npre_csub, $gastosErp->apre_csub);
					}

					//calculamos el precio del envio del lote
					$delete_peticion_delivery = false;
					$precio = $precio + $inf_lot->himp_csub + $inf_lot->base_csub;

					$jsonLot[$sub][$ref]['extras'] = array();


					if (!empty($shopping_cart['envios'])) {
						if (!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1) {
							#Envio por agencia, se usa en Duran, se calculan los gastos de la tabla WEB_GASTOS_ENVIO de momento solo lo usa Duran

							if ($shopping_cart['envios'] == '5' && request('envio_' . $sub)=="1" ) {
								#debe existir una direccion de envio
								$address = request('clidd_' . $sub);

								if (empty($address)) {
									return $res_error;
								}

								$inf_env_lic->fecharec = null;
								$inf_env_lic->infenv =$address;
								#guardamos todos los datos de la dirección de envio, ya que si solo guardamos el código la info asociada  puede variar en el tiempo
								$inf_env_lic = $this->addresInfo($inf_env_lic);
								$delete_peticion_delivery = true;
								$increment_asigl2++;
								#guardamos el extra de gastos de envio, posteriormente le pondremos el valor
								$jsonLot[$sub][$ref]['extras'][] = $this->jsonGastosExtrasLot($emp, $sub, $ref, $increment_asigl2, "gastos envio", 0, 0, 0, 'EN', $parametrosSub->secemb_prmsub);


							}
							//Envio por deliverea
							elseif ($shopping_cart['envios'] == '4') {
								$deliverea = new Delivery();
								$enviar = $deliverea->getCsube($emp, $sub, $ref);
								if (empty($enviar)) {
									return $res_error;
								}

								$transporte = $enviar->imp_csube;
								$tax = $tax + $enviar->impiva_csube;
								$envio = $envio + $enviar->imp_csube;
								$increment_asigl2++;
								$iva_tmp = round(($enviar->impiva_csube * 100) / $enviar->imp_csube);
								$jsonLot[$sub][$ref]['extras'][] = $this->jsonGastosExtrasLot($emp, $sub, $ref, $increment_asigl2, trans(\Config::get('app.theme') . '-app.mis_compras.transport_packing'), $enviar->imp_csube, $enviar->impiva_csube, $iva_tmp, 'EN', $parametrosSub->secemb_prmsub);
								$inf_env_lic->fecharec = null;
								$inf_env_lic->infenv = $enviar->nom_csube;

								//Envio por una tercera persona
							} else if ($shopping_cart['envios'] == '2' || $shopping_cart['envios'] == '3') {

								if (empty($shopping_cart['inf_envios'])) {
									return $res_error;
								}
								$inf_env_lic->fecharec = null;
								$inf_env_lic->infenv = $shopping_cart['inf_envios'] . " |codPais| " . $shopping_cart['exportacion'];
								$delete_peticion_delivery = true;
								//El licitado va a buscar el lote
							} else if ($shopping_cart['envios'] == '1') {
								$inf_env_lic->infenv = Session::get('user.name');
								$inf_env_lic->fecharec = !empty($shopping_cart['fecharec']) ? $shopping_cart['fecharec'] : null;
								$delete_peticion_delivery = true;

								#Dirección envio
								$address = request('clidd');
								if(!empty($address))
								{
									$inf_env_lic->tarifa = request("shipping") ;
									$inf_env_lic->fecharec = null;
									$inf_env_lic->infenv =$address;
									#guardamos todos los datos de la dirección de envio, ya que si solo guardamos el código la info asociada  puede variar en el tiempo
									#TAULER: En el caso de ser recogida en tienda, solamente añadimos ese dato como dirección
									if(!empty(request("shipping")) && request("shipping") == "recoger"){
										$inf_env_lic = $this->addressStorePickup($inf_env_lic);
									}
									else{
										$inf_env_lic = $this->addresInfo($inf_env_lic);
									}
								}
							} else {
								$inf_env_lic->fecharec = null;
								$inf_env_lic->infenv = '';
								$delete_peticion_delivery = true;
							}
						}
						$inf_env_lic->openv = $shopping_cart['envios'];
					} else {
						return $res_error;
					}

					$gastos_envio = $gastos_envio + $inf_lot->himp_csub + $inf_lot->base_csub;

					if ($delete_peticion_delivery && $deliverea) {
						$deliverea->deleteCsube($emp, $sub, $ref);
					}

					//Lote tiene exportacion y lo quiere mandar fuera de ES
					if (!empty($shopping_cart['exportacion']) && !empty($shopping_cart['type_exportacion'])) {

						$paise_exportacion = $shopping_cart['exportacion'];

						$lic_exportacion = $this->licenciaDeExportacion($ref, $sub);
						$tasa_exportacion = $this->tasasExportacion($ref, $sub);

						if (($paise_exportacion != 'ES' && $lic_exportacion == 1) || ($lic_exportacion == 2 && !in_array($paise_exportacion, \Tools::PaisesEUR()))) {
							$price_exportacion = floatval($parametrosSub->licexp_prmsub);
							$iva_exportacion = $this->calculate_iva($tipo_iva->tipo, $iva, $price_exportacion);
							$precio = $precio + $price_exportacion;
							$tax = $tax + $iva_exportacion;

							$inf_env_lic->liceexp = $shopping_cart['type_exportacion'];

							if ($inf_env_lic->liceexp == 'S') {
								$increment_asigl2++;
								$jsonLot[$sub][$ref]['extras'][] = $this->jsonGastosExtrasLot($emp, $sub, $ref, $increment_asigl2, trans(\Config::get('app.theme') . '-app.mis_compras.spend_lincence_export'), $price_exportacion, $iva_exportacion, $iva_cli, 'EX', $parametrosSub->secexp_prmsub);
							}
						}

						if (($paise_exportacion != 'ES' && $tasa_exportacion == 1) || ($tasa_exportacion == 2 && !in_array($paise_exportacion, \Tools::PaisesEUR()))) {
							$inf_env_lic->tasas  = 'S';
						}
					}
					//Exportacion de tauler, que se calcula según pais de envio
					else if(!empty($shopping_cart['exportacion']) && $shopping_cart['exportacion'] != 'ES' && Config::get('app.licencia_exportacion', 0)){

						$lote = FgAsigl0::select('numhces_asigl0', 'linhces_asigl0')->where('sub_asigl0', $sub)->where('ref_asigl0', $ref)->first();
						$exportacion = $subasta->hasExportLicense($lote->numhces_asigl0, $lote->linhces_asigl0);
						$price_exportacion = 0;
						if($exportacion){
							$price_exportacion = $this->licenciaDeExportacionPorPais($shopping_cart['exportacion'], $inf_lot->himp_csub);
						}

						$price_exportacion_total += $price_exportacion;

						$increment_asigl2++;
						$jsonLot[$sub][$ref]['extras'][] = $this->jsonGastosExtrasLot($emp, $sub, $ref, $increment_asigl2, trans(\Config::get('app.theme') . '-app.mis_compras.spend_lincence_export'), $price_exportacion, 0, $iva_cli, 'EX', $parametrosSub->secexp_prmsub);

					}


					//Tiene seguro y  se manda el lote por delivera
					if (!empty($shopping_cart['seguro']) && !empty($shopping_cart['envios']) && $shopping_cart['envios'] == '4') {

						$seguro_temp =  $inf_lot->himp_csub + $inf_lot->base_csub + $this->calculate_iva($tipo_iva->tipo, $iva, $inf_lot->base_csub);

						$precio_seguro = $this->calcSeguro($seguro_temp);

						$iva_seguro = $this->calculate_iva($tipo_iva->tipo, $iva, $precio_seguro);

						$precio = $precio + $precio_seguro;
						$tax = $tax + $iva_seguro;
						$increment_asigl2++;
						$jsonLot[$sub][$ref]['extras'][] = $this->jsonGastosExtrasLot($emp, $sub, $ref, $increment_asigl2, trans(\Config::get('app.theme') . '-app.mis_compras.insurance_shipping'), $precio_seguro, $iva_seguro, $iva_cli, 'SE', $parametrosSub->secseg_prmsub);
					}
					#tiene seguro y envio del lote calculado con la nueva tabla web_gastos_envio
					if (!empty($shopping_cart['seguro']) && !empty($shopping_cart['envios']) && $shopping_cart['envios'] == '5') {
						#necesitamos el pimporte de los lotes para calcular el precio del seguro
						#Duran solo las online pasan por este circuito por eso no hace falta comparar si la subasta es de tipo online
						if(\Config::get("app.noIVAOnlineAuction") ){
							$ivaEnvios =  0;
						}else{
							$ivaEnvios = $this->calculate_iva($tipo_iva->tipo, $iva, $inf_lot->base_csub);
						}

						$importeLotes += $inf_lot->himp_csub + $inf_lot->base_csub + $ivaEnvios ;

						$increment_asigl2++;
						$jsonLot[$sub][$ref]['extras'][] = $this->jsonGastosExtrasLot($emp, $sub, $ref, $increment_asigl2, trans(\Config::get('app.theme') . '-app.mis_compras.insurance_shipping'),0, 0, $iva_cli, 'SE', $parametrosSub->secseg_prmsub);

					}


					$inf_env_lic->emp = $emp;
					$inf_env_lic->sub = $sub;
					$inf_env_lic->ref = $ref;
					$inf_env_lic->paymethod = request("paymethod");
					$jsonLot[$sub][$ref]['inf'] = $inf_env_lic;

					//Gastos extras que vienen del ERP
					$extra = array();
					$extra = $pago->getGastosExtrasLot($sub, $ref, 'E');
					foreach ($extra as $key_exta => $value_extra) {
						$precio = $precio + $value_extra->imp_asigl2;
						$tax = $tax + $value_extra->impiva_asigl2;
					}
					$pago->updateApreNpre($ref, $sub, $apre, $npre, $emp);
				}
			}
		}

		//Calculamos gastos de envio
		#Eloy - 21/10/2021: Nos hemos dado cuenta que en ERP añaden el iva y la licencia de exportación para calcular los gastos de envío
		# y en Web no lo estabamos haciendo.

		$envio_temp  = $this->gastosEnvio($gastos_envio + $tax + $price_exportacion_total, $sub);

		#si ha elegido el envio no urgente, ponemos el precio del envio no urgente como principal
		if(!empty(request("shipping")) && request("shipping") == "min"){
			$envio_temp['iva'] =  $envio_temp['iva_min'];
			$envio_temp['imp'] =  $envio_temp['imp_min'];
		}
		#Tauler tiene la posibilidad de recogida en tienda, y en este caso el gasto es 0
		elseif(!empty(request("shipping")) && request("shipping") == "recoger"){
			$envio_temp['iva'] =  0;
			$envio_temp['imp'] =  0;
		}

		/*
		//el importe de gastos de envio es superior que los gastos que tenia en su prefactuiura cojemos los gastos
		if ($imp_gastosErp['imp'] > $envio_temp['imp']) {
			$tax = $imp_gastosErp['iva'] + $tax;
			$envio = $envio + $imp_gastosErp['imp'];
		} else {
			*/
			#si hay gastos de envio guardamos en cada lote lo que le correspondería por gastos de envio

			#guarda los costes de gasto de envio en el primer lote que tenga gastos de envio
			$this->setCostInExtraInfo($jsonLot, "EN", $envio_temp['imp'], $envio_temp['iva'] );

			#si no tienen los gastos de envio nuevo o si los tienen y han marcado que quiere el envio
			if (!\Config::get("app.web_gastos_envio") || request('envio_' . $sub)=="1"){
				$tax = $tax + $envio_temp['iva'] ;
				$envio = $envio + $envio_temp['imp'];
			}


			#calculamos seguro de envío, se calcula en base al coste de los lotes y el % indicado en el campo porcentaje_seguro_envio
			if(!empty(request("seguro_". $sub)) && !empty(\Config::get('app.porcentaje_seguro_envio'))){
				$importeSeguro = round($importeLotes * \Config::get('app.porcentaje_seguro_envio')/100,2);
				$taxSeguro = $this->calculate_iva($tipo_iva->tipo, $iva, $importeSeguro);
				$this->setCostInExtraInfo($jsonLot, "SE", $importeSeguro, $taxSeguro);
				#se lo sumamos a las tasas y al envio, el seguro no tiene un apartado concreto
				$tax += $taxSeguro;
				$envio += $importeSeguro;
			}

		#ya no se puede comprobar el coste de la prefactura por que ahora en tauler pueden elegir dos preciso de envio
		#}

		$jsonLot = json_encode($jsonLot);




	//	echo "total: " . ($precio + $envio + $tax);die();
		$token = '';
		//generamos token
		$token = $this->generate_token();

		$pago->insertPreFactura($emp, $apre, $npre, $user_cod, $precio, $envio, $tax, $token, $jsonLot, $price_exportacion_total);

		$tipo = 'P';
		$paymethod ="";
		if(!empty(request("paymethod"))){
			$paymethod = "&paymethod=".request("paymethod") ;
		}
		$url = Config::get('app.url') . '/gateway/pasarela-pago?anum=' . $apre . '&num=' . $npre . '&tipo=' . $tipo . '&emp=' . $emp . '&tk=' . $token.$paymethod;



		$res = array(
			"status" => "success",
			"msg" => $url

		);
		return $res;
	}
	#busca en jsonlot cuantos extras hay del tipo $type y guarda el importe en el primero que haya
	public function setCostInExtraInfo($jsonLot, $type, $imp, $iva)
	{
		foreach ($jsonLot as $keySub => $subasta) {
			foreach ($subasta as $keyLot => $lote) {
				#Buscamos solo el campo extras del array
				if (!empty($lote['extras'])) {
					foreach ($lote['extras'] as $key => $extra) {
						if ($extra->TIPO_ASIGL2 == $type) {
							$jsonLot[$keySub][$keyLot]['extras'][$key]->IMP_ASIGL2 = $imp;
							$jsonLot[$keySub][$keyLot]['extras'][$key]->IMPIVA_ASIGL2 = $iva;
							return;
						}
					}
				}
			}
		}
	}

	//Generar url para pagar facturas
	public function pagarFacturasWeb()
	{





		$fact = new Facturas();
		$res_error = array(
			"status" => "error"
		);
		$all_bills = Request::input('factura');
		$fact->cod_cli = Session::get('user.cod');
		$fact->anum = "F" . date("y");
		//Codgio de factura
		$fact->num = $this->contador2_ora('c07', 'F');
		$facturas = array();
		//Cojemos facturas a pagar
		foreach ($all_bills as $key_anum => $bill_anum) {
			foreach ($bill_anum as $key_num => $bill_num) {
				$fact->serie = $key_anum;
				$fact->numero = $key_num;

				//Comprovamos que existan si no error
				$exist_fact = $fact->pending_bills(false);
				if (empty($exist_fact)) {
					return $res_error;
				}

				$fact->efec = $exist_fact->efec_pcob;
				$fact->imp = $fact->imp + $exist_fact->pendiente_pcob;
				//Merge de facturas con su nuevo codigo
				if (!$fact->mergeFXPCOB1()) {
					return $res_error;
				}
			}
		}

/* ya no va por este circuito
		#Si han elegido el pago por transferencia reenviamos a la página que mostrará el texto
		if(!empty(request("paymethod")) && request("paymethod") == "transfer" ){
			$importe = base64_encode($fact->imp);
			$control = md5($importe.$fact->cod_cli);
			$url = route("transferpayment", ["lang" => \Config::get("app.locale")])."?control=$control&trans=".$importe;
			$res = array(
				"status" => "success",
				"msg" => $url

			);
			return $res;
		}
*/
		$fact->tk = $this->generate_token();
		if (!$fact->insertFact()) {
			return $res_error;
		}

		$tipo = 'F';
		$paymethod="";

		if(!empty(request("paymethod"))){
			$paymethod = "&paymethod=". request("paymethod");
		}

		$url = Config::get('app.url') . '/gateway/pasarela-pago?anum=' . $fact->anum . '&num=' . $fact->num . '&tipo=' . $tipo . '&emp=' . \Config('app.emp') . '&tk=' . $fact->tk .$paymethod;
		$res = array(
			"status" => "success",
			"msg" => $url

		);
		return $res;
	}

	//Generar token de seguridad para los pagos
	public function generate_token()
	{
		$token = '';

		$keys  = array_merge(range(0, 9), range('a', 'z'));

		for ($i = 0; $i < 20; $i++) {
			$token .= $keys[array_rand($keys)];
		}

		return $token;
	}


	function ErrorCancelPay($apre, $npre, $emp, $gemp)
	{
		$pago = new Payments();
		//$pago->updatePreFactB($npre,$apre);
		//Updateamos npre, apre y ponemos que es N(no se ha pagado)
		//$pago->ErrorPaymentsSub($apre,$npre,$emp,$gemp);
		//Eliminamos la prefactura creada
		//$pago->ErrorPaymentsSub0($apre,$npre,$emp,$gemp);
	}


	function pagoDirecto()
	{


		if (empty($_GET['anum']) || empty($_GET['num']) || empty($_GET['emp']) || empty($_GET['tipo']) ||  empty($_GET['tk'])) {
			exit(\View::make('front::errors.404'));
		}


		$anum = $_GET['anum'];
		$num = $_GET['num'];
		$emp = $_GET['emp'];
		$tipo = $_GET['tipo'];
		$tk = $_GET['tk'];

		$pay = new Payments();
		$fact = new Facturas();
		$fechaactual = date("Y-m-d H:i:s");
		#antes habia rand(5, 15) pero fallaba porque redssys solo acepta 12 caracteres y se generaban a veces 13, cuando daba numeros del 10 al 15
		#$ordenTrans = $tipo . rand(1, 9) . time(); //lo comento por que ha fallado en tauler ampliamso el nuemro de valores random para hacer mas improbable que falle
		$ordenTrans = $tipo . rand(10, 99).substr(time(),1,9);
		//Comprovamos el tipo
		if ($tipo == 'P') {
			//Comprobamos que el pago que quieren hacer exista y que no este pagada
			$data = $pay->getFGCSUB0($emp, $num, $anum, $tk);

			if (!empty($data) && $data[0]->estado_csub0 == 'N' &&  $data[0]->fac_csub == 'N') {
				//Asiganmos el nuevo codigo de prefactura a los lotes
				$pay->updateTransaction($anum, $num, $emp, $ordenTrans);
				$prefact = head($data);
				//Generamos codigo de prefactuea
				$pay->newCSUB0_EXT($emp, $anum, $num, $ordenTrans, $fechaactual);

				#marcamos el pago como pendiente de recibir la transferencia (estado T), para que el lote no se pueda volver a pagar de otrra manera
				if(!empty(request("paymethod")) && request("paymethod") == "transfer" ){
					DB::table('fgcsub0')
					->where('apre_csub0',$anum)
					  ->where('npre_csub0',$num)
					  ->where('emp_csub0',$emp)
					->update(['estado_csub0' => "T"]);
				}


				#Si han elegido el pago por transferencia hacemos la llamada al webservice
				if(!empty(request("paymethod")) && request("paymethod") == "transfer" && Config::get('app.WebServicePaidLots') ){

						$theme  = Config::get('app.theme');
						$rutaPaidController = "App\Http\Controllers\\externalws\\$theme\PaidController";

						$paidController = new $rutaPaidController();
						$paidController->informPaid($ordenTrans);

				}
				#Si han elegido el pago por transferencia reenviamos a la página que mostrará el texto
				if(!empty(request("paymethod")) && request("paymethod") == "transfer" ){
					$data["importe"] = $prefact->imptotal;
					$data["idtrans"] = $ordenTrans;

					return \View::make('front::pages.panel.transferpayment', $data);

				}
				elseif(!empty(request("paymethod")) && request("paymethod") == "paypal"){

					$div = FsParams::select('div_params')->first();
					$currency = $div->div_params;
					if($div->div_params == 'US$'){
						$currency = 'USD';
					}
					//$currency = request("currency", 'EUR');
					return (new PayPalV2API())->handlePayment($prefact->imptotal, $ordenTrans, $currency);
				}elseif (Config::get('app.paymentRedsys')) {

					$varsRedsys = $this->requestRedsys($prefact->imptotal, $ordenTrans, '/gateway/pagoDirectoReturn');
					#reenviamso al formulario
					return \View::make('front::pages.panel.RedsysForm', $varsRedsys);

				}elseif (Config::get('app.paymentUP2') == 'UP2') {
					//Peticion universal pay
					$return_token = $this->tokenPasarelaUP2($prefact, $ordenTrans, $emp, $tipo);
					if (!empty($return_token) && $return_token['result'] == 'success') {
						//Peticion universal pay redireccion para el pago
						$this->requestPasarelaUP2($return_token['merchantId'], $return_token['token']);
					} else {
						\Log::error($return_token);
						//$pay->updatePreFactB($num,$anum);
						//$this->error_email($return_token);
						exit(\View::make('front::errors.404'));
					}
				}
			} else {
				exit(\View::make('front::errors.404'));
			}
		} else if ($tipo == 'F') {

			//Comprobamos que exista el codigo de pago y que no este pagada
			$factura = $fact->getFXPCOB0($emp, $num, $anum, $tk);
			if (empty($factura) || ($factura->estado_pcob0 != 'N')) {
				exit(\View::make('front::errors.404'));
			}

			$factura->imptotal = $factura->imp_pcob0;
			$fact->updateFact($anum, $num, $emp, $ordenTrans);
			$fact->newPCOB0_EXT($emp, $anum, $num, $ordenTrans, $fechaactual);

			#Si han elegido el pago por transferencia reenviamos a la página que mostrará el texto
			if(!empty(request("paymethod")) && request("paymethod") == "transfer" ){
				$data["importe"] = $factura->imptotal;
				$data["idtrans"] = $ordenTrans;

				return \View::make('front::pages.panel.transferpayment', $data);

			}elseif(!empty(request("paymethod")) && request("paymethod") == "paypal"){

				$div = FsParams::select('div_params')->first();
				$currency = $div->div_params;
				if($div->div_params == 'US$'){
					$currency = 'USD';
				}
				return (new PayPalV2API())->handlePayment($factura->imptotal, $ordenTrans, $currency);

			}elseif (Config::get('app.paymentRedsys')) {

				$varsRedsys = $this->requestRedsys($factura->imptotal, $ordenTrans, '/gateway/pagoDirectoReturn');
				#reenviamso al formulario
				return \View::make('front::pages.panel.RedsysForm', $varsRedsys);

			}elseif (Config::get('app.paymentUP2') == 'UP2') {

				$return_token = $this->tokenPasarelaUP2($factura, $ordenTrans, $emp, $tipo);
				if (!empty($return_token) && $return_token['result'] == 'success') {
					$this->requestPasarelaUP2($return_token['merchantId'], $return_token['token']);
				} else {
					\Log::info('return token UP2' . print_r($return_token, true));
					$this->error_email('tokenPasarelaUP2');
					exit(\View::make('front::errors.404'));
				}
			}

		}
	}


	function register_payment($pasarela, $importe)
	{
		$rCurl = curl_init();
		curl_setopt($rCurl, CURLOPT_URL, "http://tpv.labelgrup.com/api/index.php?tipo=pago&plataforma=" . $pasarela . "&importe=" . $importe);
		curl_setopt($rCurl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($rCurl, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
		curl_setopt($rCurl, CURLOPT_POST, false);
		curl_exec($rCurl);
		curl_close($rCurl);
	}

	function correo_payment($factura, $importe, $order_id, $nom_cli)
	{
		if (!empty(Config::get('app.admin_email_administracion'))) {
			$email_admin = Config::get('app.admin_email_administracion');
		} else {
			$email_admin = Config::get('app.admin_email');
		}

		$email = new EmailLib('INVOICE_PAY_ADMIN');

		if (!empty($email->email)) {

			$email->setInvoice_code($factura);
			$email->setPrice($importe);
			$email->setName($nom_cli);
			$email->setOrder_id($order_id);
			$email->setTo($email_admin, 'Admin');

			if(!empty(config('app.admin_email_administracion_cc', ''))){
				$emails = array_map('trim', explode(',', config('app.admin_email_administracion_cc', '')));
				foreach ($emails as $email_cc) {
					$email->setCc($email_cc);
				}
			}

			$email->send_email();
		}
	}

	//Busca iva actual
	public function getIva($emp, $hoy)
	{
		$pago = new Payments();
		$cod_iva_temp = $pago->getPrmgt($emp);
		$cod_iva = 0;

		if (!empty($cod_iva_temp)) {
			$cod_iva = $cod_iva_temp[0]->tiva_prmgt;
		}
		//Miramos el Iva que hay

		$iva_temp = $pago->getIVA($hoy, $cod_iva);


		/* 01/09/2020 lo comento por que no le veo ningun sentido
		if (!empty($iva_temp)) {
			$iva_temp = $pago->getIVACOD($cod_iva);
		}
		*/
		if (!empty($iva_temp[0])) {
			$iva = $iva_temp[0]->iva_iva;

		} else {
			$iva = 0;
		}
		return $iva;
	}
	//Iva del cliente
	public function user_has_Iva($gemp, $user_cod)
	{
		$pago = new Payments();
		$tipo_iva = new \stdClass();
		$tipo_iva->tipo = 0;
		$tipo_iva->pais = '*';
		//Miramos si tiene iva este cliente
		$tipo_iva_temp = $pago->getTAX($gemp, $user_cod);

		if (!empty($tipo_iva_temp)) {
			$tipo_iva->tipo = $tipo_iva_temp[0]->iva_cli;
			$tipo_iva->pais = $tipo_iva_temp[0]->codpais_cli;
		} else {
			$tipo_iva->pais = 'ES';
		}

		return $tipo_iva;
	}

	function error_email($e)
	{
		$emailOptions = array(
			'user'      => Config::get('app.name'),
			'email'     => Config::get('app.debug_to_email'),
		);

		$emailOptions['to'] = $emailOptions['email'];
		$emailOptions['subject'] = "Error passarela de pago";
		$emailOptions['content'] = "Error passarela de pago cliente:" . Config::get('app.name') . "<br>" . $e;
		if (\Tools::sendMail('notification', $emailOptions)) {
		}
	}

	function email_inf_purchase($inf_client, $lots)
	{
		$sub = new SubastaController();
		$subasta = new Subasta();
		$pago = new Payments();

		$inf_client = head($inf_client);
		$hoy = date("Y-m-d");
		$iva = $this->getIva(\Config::get('app.emp'), $hoy);
		//Email a logistica y comprador
		\App::setLocale(strtolower($inf_client->idioma_cli));
		$adjudicaciones = array();

		$imp_total = 0;
		foreach ($lots as $key => $lot) {

			$user_cod = $lot->clifac_csub;
			$tipo_iva = $this->user_has_Iva(\Config::get('app.gemp'), $user_cod);
			$subasta->cod = $lot->sub_csub;
			$subasta->lote = $lot->ref_csub;
			$adjudicaciones[$subasta->cod][$subasta->lote] = head($subasta->getLote(false, false));
			//Ponemos id_auc_sessions a nulo por que el array puede tener 2 subastas diferentes el id de session se assigna en getLote
			$subasta->id_auc_sessions = null;
			#Duran solo las online pasan por este circuito por eso no hace falta comparar si la subasta es de tipo online
			if(\Config::get("app.noIVAOnlineAuction") ){
				$adjudicaciones[$subasta->cod][$subasta->lote]->base_csub_iva = 0;
			}else{
				$adjudicaciones[$subasta->cod][$subasta->lote]->base_csub_iva = $this->calculate_iva($tipo_iva->tipo, $iva, $lot->base_csub);
			}
			$adjudicaciones[$subasta->cod][$subasta->lote]->extras = $pago->getGastosExtrasLot($lot->sub_csub, $lot->ref_csub, null, 'C');
			$adjudicaciones[$subasta->cod][$subasta->lote]->shipping_method = $this->content_email_purchase($lot);

			foreach ($adjudicaciones[$subasta->cod][$subasta->lote]->extras as $extras) {
				$imp_total = $imp_total + $extras->imp_asigl2 + $extras->impiva_asigl2;
			}

			$imp_total = $imp_total + $adjudicaciones[$subasta->cod][$subasta->lote]->base_csub_iva + $lot->himp_csub + $lot->base_csub;
		}

		$email = new EmailLib('LOT_PAY');
		if (!empty($email->email)) {
			$email->setUserByCod($inf_client->cod_cli, true);
			$email->setUrl(\Config::get('app.url') . \Routing::slug('user/panel/info'));
			$email->setPrice($imp_total);
			$email->send_email();
		}
	}

	function email_signaturit($inf_cli, $lots)
	{

		$parametros = new Enterprise();
		$user = new User();
		$subasta = new Subasta();
		$pago = new Payments();
		$send_email = false;
		$inf_cli = head($inf_cli);
		$user->cod_cli = $inf_cli->cli_csub0;
		$inf_client = $user->getUserByCodCli();
		$inf_client = head($inf_client);

		if (empty($inf_client->email_cli)) {
			return;
		}
		$dir = '';
		if (!empty($inf_client->sg_cli)) {
			$dir .= ' ' . $inf_client->sg_cli;
		}
		if (!empty($inf_client->dir_cli)) {
			$dir .= ' ' . $inf_client->dir_cli;
		}
		if (!empty($inf_client->pob_cli)) {
			$dir .= ' ' . $inf_client->pob_cli;
		}
		if (!empty($inf_client->cp_cli)) {
			$dir .= ' (' . $inf_client->cp_cli . ')';
		}
		\App::setLocale(strtolower($inf_client->idioma_cli));
		$text_lot = '';

		foreach ($lots as $lot) {
			$subasta->cod = $lot->sub_csub;
			$subasta->lote = $lot->ref_csub;
			$lot->inf_lot = head($subasta->getLote(false, false));
			if ($lot->licexp_csub == 'C') {
				$text_lot .= '- ' . $lot->inf_lot->titulo_hces1 . '<br>';
				$send_email = true;
			}
		}

		if (!$send_email) {
			return;
		}

		$inf_client->nom_cli = ucwords(mb_strtolower($inf_client->nom_cli));
		$param = $parametros->getParameters();
		$texto = '<div style="margin:50px 30px;"><div style="margin-bottom:30px;"><img src="' . \Config::get('app.url') . '/themes/' . \Config::get('app.theme') . '/assets/img/logo.png"></div>';
		$texto .= trans_choice(\Config::get('app.theme') . '-app.emails.email_inf_exportacion', 1, [
			'day' => date("d"), 'month' => date("m"), 'year' => date("Y"), 'name' => $inf_client->nom_cli, 'nif' => $inf_client->cif_cli, 'dir' => $dir, 'inf_lot' => $text_lot
		]);
		$texto .= '</div>';

		$html2pdf = new Html2Pdf();
		$html2pdf->writeHTML($texto);
		$pdf = $html2pdf->output('pdf.pdf', 'S');
		$signaturit = '';

		if (!empty($param->comfirma_prmgt)) {
			$signaturit = $param->comfirma_prmgt;
		}

		$emailOptions['name_adjunto'] = 'exportacion_bienes_' . str_slug($inf_client->nom_cli, '_') . '_' . strtotime("now");
		$emailOptions['to'] = strtolower($inf_client->email_cli) . $signaturit;
		$emailOptions['user'] = $inf_client->nom_cli;
		$emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.email_asunto_exportacion');
		$emailOptions['adjunto'] = $pdf;
		$emailOptions['content'] = trans_choice(\Config::get('app.theme') . '-app.emails.email_texto_exportacion', 1, ['name' => $inf_client->nom_cli]);
		$emailOptions['signaturit'] = true;

		if (\Tools::sendMail('notification', $emailOptions) == true) {
			\Log::info('Send Email Signaturit <br>');
		}
	}

	function content_email_purchase($lot)
	{
		$msg = '';
		$delivery = new Delivery();
		$subasta = new Subasta();
		if ($lot->openv_csub == 1) {
			$subasta->cod = $lot->sub_csub;
			$subasta->lote = $lot->ref_csub;

			$inf_alm = $subasta->getAlmLot();
			$msg = trans_choice(\Config::get('app.theme') . '-app.mis_compras.email_collect_free', 1, ['ubic' => !empty($inf_alm) ? $inf_alm[0]->obs_alm : '']) . '</br>';

			$fecharec = '';
			if (!empty($lot->fecharec_csub)) {
				$fecharec = date('d/m/Y', strtotime($lot->fecharec_csub));
			}
			$msg .= trans(\Config::get('app.theme') . '-app.mis_compras.collect_day_personally') . ' ' . $fecharec . '<br>';
		} else if ($lot->openv_csub == 2) {
			$msg = trans_choice(\Config::get('app.theme') . '-app.mis_compras.email_another_person', 1, ['dni' => $lot->infoenv_csub]) . '</br>';
		} else if ($lot->openv_csub == 3) {
			$msg = trans_choice(\Config::get('app.theme') . '-app.mis_compras.email_transportista', 1, []) . '</br>';
		} else if ($lot->openv_csub == 4) {
			$send_lot = $delivery->getCsube(Config::get('app.emp'), $lot->sub_csub, $lot->ref_csub);
			$dir = '';
			if (!empty($send_lot)) {
				$dir =  ucwords(strtolower($send_lot->dir_csube . ' ' . $send_lot->cp_csube . ' ' . $send_lot->pob_csube));
			}
			$msg = trans_choice(\Config::get('app.theme') . '-app.mis_compras.email_send_lot', 1, ['ubic' => $dir]) . '</br>';
		} else if ($lot->openv_csub == 5) {
			$msg = trans_choice(\Config::get('app.theme') . '-app.mis_compras.contact_company', 1, []) . '</br>';
		}
		if ($lot->licexp_csub == 'S') {
			$msg .= trans_choice(\Config::get('app.theme') . '-app.mis_compras.exportacion', 1, []) . '</br>';
		}

		return $msg;
	}
	#
	function requestRedsys($amount, $ordenTrans, $merchantURL ){


		$url =  Config::get('app.url');
		#método de pago, por defecto targeta
		$payMethod = 'C';
		if( !empty(request("paymethod")) && request("paymethod")=="bizum" ){
						$payMethod = 'z';
		}elseif( !empty(request("paymethod")) && request("paymethod")=="transfer" ){
			$payMethod = 'R';
		}


		#Redsys recomienda que no haya letras en los 4 primeros digitos de la idorden, por lo que substituimos la F y P por 0 y 1 respectivamente
		$ordenTrans = str_replace(["F","P","T", "M"], [0,1,2,3], $ordenTrans);

		$miObj = new RedsysAPI;

		$miObj->setParameter("DS_MERCHANT_AMOUNT",round($amount * 100,0));#para euros las dos ultimas cifras se consideran decimales, por lo que hay que mltiplicarlo por 100
		$miObj->setParameter("DS_MERCHANT_ORDER",$ordenTrans);
		$miObj->setParameter("DS_MERCHANT_MERCHANTCODE",Config::get('app.MerchandCodeRedsys'));
		$miObj->setParameter("DS_MERCHANT_CURRENCY","978");#moneda, 978 es Euros
		$miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",0); #tipo transaccion
		$miObj->setParameter("DS_MERCHANT_TERMINAL",Config::get('app.TerminalRedsys'));
		$miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url . $merchantURL);
		$miObj->setParameter("DS_MERCHANT_URLOK", $url.Config::get('app.PaymentUrlOK'));
		$miObj->setParameter("DS_MERCHANT_URLKO", $url.Config::get('app.PaymentUrlKO'));
		$miObj->setParameter("DS_MERCHANT_PAYMETHODS",$payMethod);


		#nombre del comercio
		#$miObj->setParameter("DS_MERCHANT_MERCHANTNAME",$payMethod);

		//Datos de configuración

		// Se generan los parámetros de la petición
		$varsRedsys["version"] = "HMAC_SHA256_V1";
		$varsRedsys["params"] = $miObj->createMerchantParameters();
		$varsRedsys["signature"] = $miObj->createMerchantSignature(Config::get('app.KeyRedsys')); #Clave recuperada de CANALES

		#log con la información que se envia a redsys
		\Log::info(json_encode($miObj->vars_pay));
		\Log::info(json_encode($varsRedsys));

		return $varsRedsys;
	}


	public function universalPay2Vars($codSub,$tipo ){
		$up2Vars = array();
		if (!env('APP_DEBUG') && Config::get('app.environmentUP2')) {
			$up2Vars["url_pay"] = 'https://api.universalpay.es/token';
			$up2Vars["merchantId"] = Config::get('app.merchantIdUP2');
			$up2Vars["brandId"] = Config::get('app.brandIdUP2');
			$up2Vars["password"] = Config::get('app.passwordUP2');

			#modificamos estos valores si el cliente tiene multicuenta y la subasta es multicuenta
			if( ($tipo =="P" || $tipo == "T" ) && Config::get('app.multiPasarela') && !empty($codSub)){
				$auctions = explode(',',Config::get('app.multiPasarela'));

				if(in_array($codSub, $auctions) ){
					$up2Vars["merchantId"] = Config::get('app.merchantIdUP2_'.$codSub);
					$up2Vars["brandId"] = Config::get('app.brandIdUP2_'.$codSub);
					$up2Vars["password"] = Config::get('app.passwordUP2_'.$codSub);
				}
			}

		} else {
			$up2Vars["url_pay"] = 'https://api.test.universalpay.es/token';

			#valores normales
			$up2Vars["merchantId"] = Config::get('app.merchantIdUP2_test');
			$up2Vars["brandId"] = Config::get('app.brandIdUP2_test');
			$up2Vars["password"] = Config::get('app.passwordUP2_test');




		}


		return $up2Vars;
	}

	//Peticion token universal pay
	public function tokenPasarelaUP2($prefact, $ordenTrans, $emp, $tipo)
	{
		$pay = new Payments();
		$fact = new Facturas();
		#funcion que devuelve los datos de universal pay v2
		$up2Vars = $this->universalPay2Vars($prefact->sub_csub,$tipo );


		if (strpos($prefact->email_cli, ';') > 0) {
			$email = explode(";", $prefact->email_cli);
			if (!empty($email[0])) {
				$prefact->email_cli = trim($email[0]);
			}
		}


		$time = strtotime("now") * 1000;
		$url = Config::get('app.url');
		$fields = array(
			'customerLastName' => trim($prefact->nom_cli),
			'customerId' => $ordenTrans,
			'merchantTxId' => $ordenTrans,
			'customerEmail' => trim($prefact->email_cli),
			'merchantReference' =>  $prefact->cod_cli,
			'amount' => floatval($prefact->imptotal),
			'brandId' => $up2Vars["brandId"],
			'merchantId' => $up2Vars["merchantId"],
			'password' =>$up2Vars["password"],
			'action' => 'PURCHASE',
			'language' => !empty($prefact->idioma_cli) ? strtolower($prefact->idioma_cli) : 'es',
			'timestamp' => $time,
			'allowOriginUrl' => $url,
			'channel' => 'ECOM',
			'currency' => 'EUR',
			'country' => !empty($prefact->codpais_cli) ? $prefact->codpais_cli : 'ES',
			'merchantNotificationUrl' => $url . '/gateway/pagoDirectoReturn',
			'merchantLandingPageUrl' => $url . '/gateway/returnPayPage',
			'specinProcessWithoutCvv2' => 'false',
			'forceSecurePayment' => 'true',
		);

		//Lo necesita Soler al tener cuentas unificadas
		if(config('app.bankMidUP2', false)){
			$fields["bankMid"] = config('app.bankMidUP2');
		}



		//url-ify the data for the POST
		$fields_string = http_build_query($fields);
		\Log::info("URL de pago: ".$up2Vars["url_pay"]." ?$fields_string");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $up2Vars["url_pay"] );
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		if ($tipo == 'P') {
			$pay->updateRequest(json_encode($fields), $ordenTrans, $emp);
			$pay->updateReturn(json_encode($response), $ordenTrans, $emp);
		} elseif ($tipo == 'F') {
			$fact->updateRequest(json_encode($fields), $ordenTrans, $emp);
			$fact->updateReturn(json_encode($response), $ordenTrans, $emp);
		}

		return json_decode($response, true);
	}

	//Peticion de pago
	public function requestPasarelaUP2($merchantId, $token)
	{

		if (!env('APP_DEBUG') && Config::get('app.environmentUP2')) {
			$url_pay = 'https://cashierui.universalpay.es/ui/cashier';
		} else {
			$url_pay = 'https://cashierui.test.universalpay.es/ui/cashier';
		}

		$header = "Location: $url_pay?merchantId=$merchantId"
			. "&token=$token"
			. "&integrationMode=standalone&paymentSolutionId=500";
		\Log::info("Request Universal Pay: " . $header);
		header($header);
		exit;
	}

	//Respuesta de pago
	public function pagoDirectoReturnUP2()
	{

		$post = $_POST;

		\Log::info("Pago: " . print_r($post, true));
		$merchantID = $post['merchantTxId'];
		$amount = $post['amount'];
		$status = $post['status'];

		if ($status == 'CAPTURED') {
			//Label registra los pagos de los clientes
			if (!env('APP_DEBUG') && (bool) Config::get('app.environmentUP2') === true) {
				$this->register_payment("UniversalPay", $amount);
			}
			$tipoPago = substr($merchantID,0,1);

			#es un pago del carrito de la compra
			if($tipoPago == "T"){
				#Llamamos a la funcion de cart controller para que lo procese todo
				$payShoppingCart = new PayShoppingCartController();
				$payShoppingCart->returnPay($merchantID);
				return;
			}


			$this->pagoDirectoReturn($merchantID, $amount, $post);
		}
	}

	public function pagoDirectoReturnRedsys()
	{
		try{
			$redsys = new RedsysAPI;
			$request = request()->all();

			$version = $request["Ds_SignatureVersion"];
			$datos = $request["Ds_MerchantParameters"];
			$signatureRecibida = $request["Ds_Signature"];

			$kc = Config::get('app.KeyRedsys');//Clave recuperada de CANALES
			$firma = $redsys->createMerchantSignatureNotif($kc,$datos);
			$decodec = $redsys->decodeMerchantParameters($datos);
			$returnedVars = json_decode($decodec);
			#datos decodificados
			\Log::info(print_r($returnedVars, true));

			#Si la información es válida
			if ($firma === $signatureRecibida){
				$respuesta = $returnedVars->Ds_Response;

				#las respuesta 0000 a 0099 son de transaccion autorizada, el resto no lleva 2 ceros al principio
				if(substr($respuesta, 0,2) == "00"){

					if($returnedVars->Ds_Order){
						#el merchand id se ha modificado en el envio para no enviar letras así que hay que hacer el proceso inverso y recuperar las letras 0=F, 1=P
						$tipoPago = substr($returnedVars->Ds_Order,0,1);

						$merchantId =NULL;
						if($tipoPago == '0'){
							#cambiamos el primer digito si es 0 por F
							$merchantId = "F".substr($returnedVars->Ds_Order,1);
						}elseif($tipoPago == '1'){
							$merchantId = "P".substr($returnedVars->Ds_Order,1);

						}elseif($tipoPago == '2'){#pago tiendaonline
							$merchantId = "T".substr($returnedVars->Ds_Order,1);
							#Llamamos a la funcion de cart controller para que lo procese todo
							$payShoppingCart = new PayShoppingCartController();
							$payShoppingCart->returnPay($merchantId);
							return;
						}elseif($tipoPago == '3'){
							#cambiamos el primer digito si es 3 por M que es el de coste minteo
							$merchantId = "M".substr($returnedVars->Ds_Order,1);
						}
						#dividimos por cien por que al ser € se ha multiplicado antes por 100, Redsys no trabaja con decimales
						$amount = $returnedVars->Ds_Amount/100;
						#paso la info a la función de pago directo
						$this->pagoDirectoReturn($merchantId, $amount, $returnedVars);
					}else{
						\Log::error("No viene informado el Ds_Order");
					}
				}else{
					if($respuesta=="9915"){
						\Log::error("El usuario ha cancelado el pago");
					}else{
						\Log::error("No se ha podido completar el pago correctamente, respuesta: $respuesta");
					}

				}

			}else{
				\Log::error("No concuerda la firma con los datos enviados en Redsys" );
			}
		}catch( \Exception $e ){
				\Log::error("Excepcion en pago con redsys \n" . $e);

			return;
		}

	}

	public function pagoDirectoReturnPaypal()
	{
		try {
			return (new PayPalV2API())->handleApproval();

		} catch (\Exception $e) {
			\Log::error("Excepcion en pago con paypal \n" . $e);
			return redirect(config('app.UP2_cancel'));
		}
	}

	public function pagoDirectoReturn($merchantID, $amount, $post)
	{
		$pago = new Payments();
		$deliverea = null;
		$subasta = new Subasta();
		$fact = new Facturas();
		$user = new User();
		$enterprise = new Enterprise();
		// El id del pago tiene una letra delante P(prefactura) o F(factura)
		try {


			//Guardamos historico dependiendo si es prefactura o factura
			if (substr($merchantID, 0, 1) == 'P') {
				//buscamos el max id
				$max_lin = $pago->maxLin();
				//Informacion de las transacciones
				$fact = $pago->getInfTransExt($merchantID);
				//guardamos en historico
				$pago->insertHistTrans($amount, $fact, $post, $max_lin);
			} else if (substr($merchantID, 0, 1) == 'F') {
				//Informacion de las transacciones
				$factura = $fact->getInfFactExt($merchantID);
				if (empty($factura)) {
					return;
				}
				//guardamos en historico
				$fact->insertHistTrans($amount, $factura, $post);
			}

			//Prefacturas
			if (substr($merchantID, 0, 1) == 'P') {



				#Notificar a casas de subastas por webservice que se ha pagado los lotes
				if(Config::get('app.WebServicePaidLots')){

					$theme  = Config::get('app.theme');
					$rutaPaidController = "App\Http\Controllers\\externalws\\$theme\PaidController";

					$paidController = new $rutaPaidController();
					$paidController->informPaid($merchantID);
				}



				//updateamos el pago si todo es correcto
				$pago->updateTrans($amount, $merchantID, $fact);

				#Código para modificar los Apre y Npre de la csub para evitar que lso códigos actuales no coincidan con lso del pago, por ejemplo si abren varias pasarelas de pago y pagan en la primera
					$extra_bbdd = FgCsub0::select("EXTRAINF_CSUB0, APRE_CSUB0, NPRE_CSUB0")->where("IDTRANS_CSUB0",$merchantID)->first();
					if(!empty($extra_bbdd)){
						$extraInfo = json_decode($extra_bbdd->extrainf_csub0);
						if(!empty($extraInfo)){
							foreach($extraInfo as $cod_sub => $subasta){
								foreach($subasta as $ref => $infoLot){
									FgCsub::where("sub_csub", $cod_sub)->where("ref_csub", $ref)->update(["apre_csub" =>  $extra_bbdd->apre_csub0, "npre_csub" => $extra_bbdd->npre_csub0]);
								}
							}
						}
					}
				//Lotes que se han pagado en esta factura
				$lots = array();
				$lots = $pago->getLotsFact($fact->apre_csub0ext, $fact->npre_csub0ext);

				if (empty($lots)) {
					return;
				}

				$extrainf = $pago->getEXTRAINF($fact->apre_csub0ext, $fact->npre_csub0ext);
				$extrainf = json_decode($extrainf->extrainf_csub0);

				foreach ($lots as $lot) {

					if (!empty($extrainf) && !empty($extrainf->{$lot->sub_csub}->{$lot->ref_csub}->extras)) {
						foreach ($extrainf->{$lot->sub_csub}->{$lot->ref_csub}->extras as $value_extra) {
							$pago->insertGastosExtrasLot($value_extra);
						}
					}
					//Gastos extras ponemos que se han cobrado
					$pago->updateGastosExtrasLot($lot);

					if (!empty($extrainf) && !empty($extrainf->{$lot->sub_csub}->{$lot->ref_csub}->inf)) {
						$pago->infEnvExp($extrainf->{$lot->sub_csub}->{$lot->ref_csub}->inf);
					}

					if (!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1) {
						//Avisamos a deliverea
						$send_lot_deliverea = array();
						$deliverea = new Delivery();
						$send_lot_deliverea = $deliverea->getCsube($lot->emp_csub, $lot->sub_csub, $lot->ref_csub);

						if (!empty($send_lot_deliverea)) {
							$deliverea->newShipment($lot->emp_csub, $lot->sub_csub, $lot->ref_csub);
						}
					}
				}

				//Informacion del cliente del pago
				$inf_client = $pago->getFGCSUB0(Config::get('app.emp'), $fact->npre_csub0ext, $fact->apre_csub0ext);
				$factura = $fact->apre_csub0ext . '/' . $fact->npre_csub0ext;
				//Correo admin del pago
				$this->correo_payment($factura, $amount, $merchantID, $inf_client[0]->nom_cli);
				//   if(!empty($inf_client) &&( Config::get('app.email_pagado_logistica') || Config::get('app.email_comprador'))){
				if (!empty($inf_client)) {
					//Enviar email al cliente confirmando pago y a logistica
					$this->email_inf_purchase($inf_client, $lots);
					//Enviar email de signaturit
					//$this->email_signaturit($inf_client,$lots);
				}
				//factura
			} else if (substr($merchantID, 0, 1) == 'F') {
				//Updateamos como cobrada
				$fact->updateTransCob($amount, $merchantID, $factura);
				$fact->anum = $factura->anum_pcob0_ext;
				$fact->num = $factura->num_pcob0_ext;

				//Buscamos factura pagada

				$inf_fact =  $fact->getFactCob();

				if (empty($inf_fact)  && count($inf_fact) == 0) {
					return;
				}

				//generamos codigo del pago
				$anum_cob = "C" . date("y");
				$num_cob = $this->contador2_ora('c01', 'C');

				//Bucamos que el usuario exista
				$user->cod_cli = $inf_fact[0]->cod_pcob1;
				$inf_client = $user->getUserByCodCli();
				if (empty($inf_client)) {
					return;
				}

				$inf_client = head($inf_client);



				$date = date("Y/m/d");
				$params = $enterprise->getParam();
				$fact->cod_cli = $inf_fact[0]->cod_pcob1;
				//Insertamos la cabezerca
				$fact->insertCOBRO0($anum_cob, $num_cob, $params, $date);

				//Buscamos la linia max de la cabezera
				$max_lin = $fact->maxCOBRO1($anum_cob, $num_cob);

				#No se pueden poner debajo por que se eliminan los pcobs
				#Notificar a casas de subastas por webservice que se ha pagado una facura
				if(Config::get('app.WebServicePaidInvoice')){

					$theme  = Config::get('app.theme');
					$rutaPaidController = "App\Http\Controllers\\externalws\\$theme\PaidController";

					$paidController = new $rutaPaidController();

					$paidController->informPaid($merchantID);
				}


				foreach ($inf_fact as $bill_pay) {
					$fact->serie = $bill_pay->serie_pcob1;
					$fact->numero_pcob1 = $bill_pay->numero_pcob1;
					$fact->efec = $bill_pay->efecto_pcob1;
					$max_lin = $max_lin + 1;
					//Insertamos cada linia con la cabezera asiganda
					$fact->insertCOBRO1($anum_cob, $num_cob, $bill_pay, $params, $date, $max_lin);
					//Borramos facturas de pendiente de cobro
					$fact->deletePCOB($bill_pay);
					//updateamos el precio que se ha cobgrado
					$fact->updateCOBRO0($anum_cob, $num_cob);
				}
				//Llamamos a la funcion de cerrar Factura
				$fact->closeCobro($anum_cob, $num_cob);
				//enviamos correo al administrador
				$this->correo_payment($inf_fact[0]->serie_pcob1 . "/" . $inf_fact[0]->numero_pcob1, $amount, $merchantID, $inf_client->nom_cli);
				//enviamos email de factura pagada
				$this->email_bills_pay($inf_client, $inf_fact, $amount);


			}
		} catch (\Exception $e) {
			$this->error_email($e);
		}
	}

	public function returnPayPage()
	{
		\Log::info("Return " . print_r($_POST, true));



		if (!empty(Request::input('result')) && Request::input('result') == 'success') {
			$link = Config::get('app.url') . Config::get('app.UP2_return');
		} else {
			$link = Config::get('app.url') . Config::get('app.UP2_cancel');
		}
		header("Location: " . $link . "", true, 301);
		exit();
	}

	#solo sirve si pagan todos los lotes de la subasta, no pueden usarla de otra manera
	public function web_gastos_envio($cod_sub){
		$cod_cli = Session::get('user.cod');
		$codd_clid = request('clidd_' . $cod_sub);
		/* lo quito por que si no n ofunciona
		$envio = request('envio_' . $cod_sub, 1);#por defecto es 1, deben marcar que lo van a buscar para quye sea 0
		//si  han marcado que quieren recojerlo el valor de envio será 0

		if($envio != 1){
			return 0;
		}
*/




		if(empty($cod_sub) || empty($cod_cli)){
			return -1;
		}


		#lotes adjudicados al ususario
		$fgAsigl0 = new FgAsigl0();
		#ordenamos por referencia por que debemos hacer grupos de 5 y debemos seguir un criterio
		$lotes = $fgAsigl0->select("REF_ASIGL0, PESO_HCES1, PESOVOL_HCES1, SEC_HCES1, LIN_ORTSEC1")->joinCSubAsigl0()->JoinFghces1Asigl0()->JoinFgOrtsec1Asigl0()->where("FGCSUB.SUB_CSUB",$cod_sub)->where("FGCSUB.CLIFAC_CSUB",$cod_cli)->orderby("REF_ASIGL0")->get();

		###########	QUERY DE TEST ###################
		#$lotes =$fgAsigl0->select("REF_ASIGL0, PESO_HCES1, PESOVOL_HCES1, SEC_HCES1, LIN_ORTSEC1")->JoinFghces1Asigl0()->JoinFgOrtsec1Asigl0()->where("FGASIGL0.SUB_asigl0",'582')->wherein("FGASIGL0.REF_ASIGL0", array(657,353, 372,23,621))->orderby("REF_ASIGL0")->get();

		return $this->calc_web_gastos_envio ($lotes,$codd_clid);

	}

	public function  calc_web_gastos_envio ($lotes,$codd_clid){
		$cod_cli = Session::get('user.cod');
		$direccionEnvio = FxClid::select("CP_CLID, CODPAIS_CLID")->WHERE("CODD_CLID", $codd_clid)->where("cli_clid", $cod_cli)->first();

		if(empty($direccionEnvio) || empty($direccionEnvio->cp_clid) || empty($direccionEnvio->codpais_clid)){
			#no hay datos de direccion por lo que no se puede enviar
			return -1;
		}
		#código postal del envio
		$cp = $direccionEnvio->cp_clid;
		$codCountry =$direccionEnvio->codpais_clid;
		$gastosEnvioLib =  new GastosEnvioLib();
		$casosParticulares = $this->gastosEnvioCasosParticulares($lotes,$direccionEnvio );

		#si devuelve un valor
		if($casosParticulares != -1){
			return $casosParticulares;
		}

		#Numero máximo de lotes, pongo diezmil para que sea un numero lo suficientemente alto como para que todos los lotes vayan juntos
		$numMaxLotes = Config::get("app.max_lot_web_gastos_envio")?? 10000;
		#agrupamos los lotes
		$grupo_envios= array();
		$i = 0;
		$cont=0;
		#si el lote pertenece a alguna de las familias se debe enviar en solitario
		$familia_envio_individual = array();
		if(!empty(Config::get("app.envio_individual_web_gastos_envio"))){
			$familia_envio_individual = explode(',', Config::get("app.envio_individual_web_gastos_envio"));
		}

		$envios_individuales = array();
		foreach($lotes as $lote){
			if(in_array($lote->lin_ortsec1, $familia_envio_individual) ){
				$envios_individuales[]= $lote;
			}else{
				$grupo_envios[$i][]= $lote;
				$cont++;
				if ($cont == $numMaxLotes){
					$i++;
					$cont=0;
				}
			}

		}

		#añadimos los lotes que se envian de manera individual,dentro de cada envio habra un array de un solo lote
		foreach($envios_individuales as $envio_individual){
				$grupo_envios[]= array($envio_individual);
		}

		$imp=0;

		#petición de gastos de envio
		foreach($grupo_envios as $grupo){
			$peso = 0;
			$cmsLineales = 0;
			foreach($grupo as $lote){
				$peso += $lote->peso_hces1;
				$cmsLineales += $lote->pesovol_hces1;
			}
			$gastosEnvio = $gastosEnvioLib->calculate($codCountry,$cp, $peso, $cmsLineales);

			#no hay precio por lo que no se puede enviar
			if($gastosEnvio == -1){
				#con que haya un envio que no se pueda realizar se indica que el envio n oes posible
				return -1;
			}else{
				$imp += $gastosEnvio;
			}
		}

		return $imp;
	}


	#calculos particulares
	public function gastosEnvioCasosParticulares($lotes,$direccionEnvio ){


		if(\Config::get('app.theme')=='duran'){
			/* 8.	CASO PARTICULAR LIBROS: Si todos los lotes del carrito son libros, los centímetros lineales totales no superan los 100 cms.
			lineales y el peso total no supera los 2 Kg., entonces, el importe será de 9 euros para la zona de Madrid y 16 euros para el resto de zonas.
			(no tenemos en cuenta la tarifa de las tablas).
			*/
			$libros = true;
			$peso = 0;
			$cmsLineales = 0;
			foreach($lotes as $lote ){
				$peso += $lote->peso_hces1;
				$cmsLineales += $lote->pesovol_hces1;
				if($lote->lin_ortsec1 !=327){ #categoria libros
					$libros = false;
					break;
				}
			}
			#deben ser todos libros y la dirección ser dentro de españa
			if($libros && $direccionEnvio->codpais_clid == 'ES' && $peso <= 2 && $cmsLineales <=100 ){
				if(substr($direccionEnvio->cp_clid,0,3) == '280'){
					return 9;
				}
				#si es baleares o canarias se calcula con las tablas
				elseif(substr($direccionEnvio->cp_clid,0,1) == '7' ||  substr($direccionEnvio->cp_clid,0,2) == '35' ||  substr($direccionEnvio->cp_clid,0,2) == '38')
				{
					return -1;
				}else{
					return 12;
				}
			}

			return -1;
		}


	}


	public function gastosEnvio($precio = null, $cod_sub = null)
	{
		#si usan las tablas de WEB_GASTOS_ENVIO
		if(Config::get("app.web_gastos_envio")){

			$gastosEnvio = $this->web_gastos_envio(request('cod_sub', $cod_sub) );
			#-1 indica que no es enviable
			if($gastosEnvio == -1){
				$res = array(
					'imp' => -1,
					'iva' => 0
				);
			}else{

				$tipo_iva = $this->user_has_Iva(Config::get('app.gemp'), Session::get('user.cod'));
				$iva = $this->getIva(Config::get('app.emp'), date("Y-m-d"));
				$ivaGastosEnvio = $this->calculate_iva($tipo_iva->tipo, $iva, $gastosEnvio);


				$res = array(
					'imp' => floatval($gastosEnvio),
					'iva' => floatval($ivaGastosEnvio)

				);
			}
			return $res ;
		}



		$user = new User();
		$user->cod_cli = Session::get('user.cod');
		$inf_client = $user->getUserByCodCli('N');

		$imp_env = 0;
		$iva_env = 0;

		$envio = array();
		$envio = 0;

		//mirar si se cobran los gastos de envio
		$cod_sub = request('cod_sub', $cod_sub);
		$free_shipping_costs = Config::get("app.free_shipping_costs");
		if (!empty($cod_sub) && !empty($free_shipping_costs)) {

			if (in_array($cod_sub, explode("|", $free_shipping_costs))) {
				$res = array(
					'imp' => 0,
					'iva' => 0,
					'imp_min' => 0,
					'iva_min' => 0

				);
				return $res;
			}
		}

		if (!empty($inf_client) && $inf_client[0]->envcorr_cli == 'S') {

			if (!empty($precio)) {
				$base_himp = $precio;
			} else {
				$base_himp = Request::input('precio_envio');
			}
			//si viene la info de internet

			$iva = $this->getIva(Config::get('app.emp'), date("Y-m-d"));

			$tipo_iva = $this->user_has_Iva(Config::get('app.gemp'), Session::get('user.cod'));

			$pago = new Payments();

			$existGasimp = $pago->existGasimp($tipo_iva);
			$pais = $tipo_iva->pais;
			$tipo = $tipo_iva->tipo;

			$cp_cli = 0;
			if (isset($inf_client[0]->cp_cli)){
				$cp_cli = $inf_client[0]->cp_cli;
			}
			if (!empty(request('clidd'))){
				#falta recargar el js cuando cambian de dirección y ver que pasa cuando cambian de dirección y van a pagar
				$direccionEnvio = FxClid::select("CP_CLID, CODPAIS_CLID")->WHERE("CODD_CLID", request('clidd'))->where("cli_clid", $user->cod_cli)->first();

				if(!empty($direccionEnvio)){
					$pais= $direccionEnvio->codpais_clid?? $pais;
					$cp_cli= $direccionEnvio->cp_clid?? $cp_cli;
					//No estaba comprovando si existian gastos de envío por el pais de la dirección
					$existGasimp = $pago->existGasimp((object) ['tipo' => $tipo_iva->tipo, 'pais' => $pais]);
				}
			}


			if (!empty($existGasimp)) {
				$envio_temp = $pago->getGastoEnvio(\Config::get('app.emp'), $base_himp, $tipo, $pais, $cp_cli);
				$envio_min = $pago->getGastoEnvio(\Config::get('app.emp'), $base_himp, $tipo, $pais, $cp_cli, true);

			} else {
				$pais = '*';
				$envio_temp = $pago->getGastoEnvio(\Config::get('app.emp'), $base_himp, $tipo, $pais, $cp_cli);

				if (empty($envio_temp)) {
					$tipo = 'D';
					$pais = $tipo_iva->pais;
					$envio_temp = $pago->getGastoEnvio(\Config::get('app.emp'), $base_himp, $tipo, $pais, $cp_cli);
					if (empty($envio_temp)) {
						$tipo = 'D';
						$pais = '*';
						$envio_temp = $pago->getGastoEnvio(\Config::get('app.emp'), $base_himp, $tipo, $pais, $cp_cli);
					}
				}
			}

			if (!empty($envio_temp)) {
				$envio = head($envio_temp)->imp_gasimp;
				$imp_env = $envio;
				$tax = $this->calculate_iva($tipo_iva->tipo, $iva, $envio);
				$iva_env = $tax;
			}
		}
		$res = array(
			'imp' => floatval($imp_env),
			'iva' => floatval($iva_env)
		);

		#si hay envio min tambien
		$imp_min=0;
		$iva_min =0;
		if(!empty($envio_min)){
			$imp_min = head($envio_min)->imp_gasimp;
			$iva_min = $this->calculate_iva($tipo_iva->tipo, $iva, $imp_min);
		}
		$res["imp_min"]=  floatval($imp_min);
		$res["iva_min"]=  floatval($iva_min);

		return $res;
	}
	// Calculo de la iva dependiendo de si el iva del cliente es 1 o 2
	public function calculate_iva($tipo_iva = 0, $iva, $price)
	{

		$tax = 0;
		if ($tipo_iva == '1' || $tipo_iva == '2') {
			$tax = round((($price * $iva) / 100), 2);
		}

		return $tax;
	}
	//Devuelve el iva dependiendo de la iva del cliente
	public function hasIvaReturnIva($tipo_iva, $iva)
	{
		$value = 0;
		if ($tipo_iva == '1' || $tipo_iva == '2') {
			$value = $iva;
		}

		return $value;
	}

	public function licenciaDeExportacion($ref, $sub)
	{
		/*
         *
        Se pagan los gastos de gestion cuando se cumpla:
        -Un lote tiene más de cien años y se exporta fuera de españa
        -Un lote tiene entre 50 y 100 años se exporta fuera de europa y pertenece a las categorias de
        Dibujos, Grabados, Fotografias, Acuarelas, Aguadas, Pasteles, Esculturas, Cuadros
        */
		$payments = new Payments();
		$subasta = new Subasta();
		$subasta->cod = $sub;
		$subasta->lote = $ref;
		$exportacion = 0;
		$inf_lot = $subasta->getLote(false, false);
		if (empty($inf_lot)) {
			return $exportacion;
		}
		$inf_lot = head($inf_lot);
		$year = $subasta->get_year($inf_lot->num_hces1, $inf_lot->lin_hces1);

		if (empty($year) || !is_numeric($year)) {
			return $exportacion;
		}

		$anyo = date("Y") - $year;

		if ($anyo >= 100) {
			$exportacion = 1;
		} else {
			$lic_exportacion = $payments->licExportacion($anyo, $inf_lot->sec_hces1);

			if (!empty($lic_exportacion)) {
				$exportacion = 2;
			}
		}

		return $exportacion;
	}

	/**
	 * Obtener valor de licencia de exportacion, según pais de envío del cliente
	 * Necesario config licencia_exportacion para activarse
	 *
	 * Se pagan cuando el envio es fuera de Europa y se calcula por tramos segun importe
	 */
	public function licenciaDeExportacionPorPais($codpais_clid = "ES", $importeAdjudicacion)
	{

		if(!Config::get('app.licencia_exportacion', 0) || $codpais_clid == 'ES' || empty($codpais_clid)){
			return 0;
		}

		$exp = DB::select(
			"select CALCULAR_LICENCIA_EXPORTACION(:empresa,:codPais,:importeAdj) as exportacion from dual",
			array(
				'empresa'    => Config::get('app.emp'),
				'codPais'        => $codpais_clid,
				'importeAdj'    => $importeAdjudicacion
			)
		);

		if(!empty($exp[0])){
			return floatval($exp[0]->exportacion);
		}

		return 0;
	}

	public function tasasExportacion($ref, $sub)
	{
		/*
         * Se pagan tasas cuando se cumpla:
            -Un lote tiene más de cien años y se exporta fuera de europa
            -Un lote tiene entre 50 y 100 años se exporta fuera de europa y su valor supera:

                Dibujos, grabados y fotografías 15.000
                Acuarelas, aguadas y pasteles 30.00
                Esculturas 50.000
                Cuadros 150.000
         */

		$payments = new Payments();
		$subasta = new Subasta();
		$subasta->cod = $sub;
		$subasta->lote = $ref;
		$tasas = 0;
		$inf_lot = $subasta->getLote(false, false);

		if (empty($inf_lot)) {
			return $tasas;
		}
		$inf_lot = head($inf_lot);
		if (empty($inf_lot->year) || !is_numeric($inf_lot->year)) {
			return $tasas;
		}
		$año = date("Y") - $inf_lot->year;
		if ($año >= 100) {
			$tasas = 1;
		} else {
			$lic_exportacion = $payments->licExportacion($año, $inf_lot->sec_hces1, $inf_lot->himp_csub);
			if (!empty($lic_exportacion)) {
				$tasas = 2;
			}
		}
		return $tasas;
	}

	public function contador2_ora($codigo, $letra)
	{

		$data = \DB::select(
			"select CONTADOR2_ORA(:codigo,:emp,SYSDATE,:letra) as cont from dual",
			array(
				'emp'       => \Config::get('app.emp'),
				'codigo' => $codigo,
				'letra' => $letra
			)
		);
		return head($data)->cont;
	}
	//Email de pago de factura
	public function email_bills_pay($inf_client, $inf_factura, $amount)
	{

		$facturas = new Facturas();
		$imp_total = 0;
		$tipo_tv = array();
		$inf_fact = array();
		foreach ($inf_factura as $fact_pag) {

			$facturas->serie = $fact_pag->serie_pcob1;
			$facturas->numero = $fact_pag->numero_pcob1;
			$tipo_fact = $facturas->bill_text_sub(substr($facturas->serie, 0, 1), substr($facturas->serie, 1));
			if ($tipo_fact->tv_contav == 'T') {
				$inf_fact['T'][$facturas->serie][$facturas->numero] = $facturas->getFactTexto();
			} elseif ($tipo_fact->tv_contav == 'L' || $tipo_fact->tv_contav == 'P') {
				$inf_fact['S'][$facturas->serie][$facturas->numero] = $facturas->getFactSubasta();
			}

			$tipo_tv[$facturas->serie][$facturas->numero] = $tipo_fact->tv_contav;
		}

		if (!empty(Config::get('app.admin_email_administracion'))) {
			$email_admin = Config::get('app.admin_email_administracion');
		} else {
			$email_admin = Config::get('app.admin_email');
		}


		$email = new EmailLib('INVOICE_PAY_USER');
		if (!empty($email->email)) {
			$email->setUserByCod($inf_client->cod_cli, true);
			$email->setUrl(\Config::get('app.url') . \Routing::slug('user/panel/myBills'));
			$email->setPrice(\Tools::moneyFormat($amount, false, 2));
			$email->setBill($facturas->serie . '/' . $facturas->numero);
			$email->send_email();
		}
	}
	//Calculo de seguro
	public function calcSeguro($seguro_temp)
	{

		$subasta = new Subasta();

		//parametros de subasta, lo utilizamos por precio de seguro y de licencia exportacion
		$parametrosSub = $subasta->getParametersSub();

		$porcentage_seguro = floatval($parametrosSub->seguro_prmsub);
		$pricemin_seguro = floatval($parametrosSub->preciominseguro_prmsub);

		$precio_seguro = round((($seguro_temp * $porcentage_seguro) / 100), 2);
		if ($pricemin_seguro > $precio_seguro) {
			$precio_seguro = $pricemin_seguro;
		}

		return $precio_seguro;
	}
	// transformamos a json los valores para guardar en bbdd para cuando el cliente page sabemos los lotes pagados
	function jsonGastosExtrasLot($emp, $sub, $ref, $lin, $desc, $price, $iva_price, $iva_cli, $tipo, $sec, $origen = 'W')
	{

		$estrasLot = new \stdClass();
		$estrasLot->EMP_ASIGL2 = $emp;
		$estrasLot->SUB_ASIGL2 = $sub;
		$estrasLot->REF_ASIGL2 = $ref;
		$estrasLot->LIN_ASIGL2 = $lin;
		$estrasLot->DESC_ASIGL2 = $desc;
		$estrasLot->IMP_ASIGL2 = $price;
		$estrasLot->IVA_ASIGL2 = $iva_cli;
		$estrasLot->IMPIVA_ASIGL2 = $iva_price;
		$estrasLot->ORIGEN_ASIGL2 = $origen;
		$estrasLot->ESTADO_ASIGL2 = 'C';
		$estrasLot->TIPO_ASIGL2 = $tipo;
		$estrasLot->SEC_ASIGL2 = $sec;

		return $estrasLot;
	}

	public function addresInfo($inf_env_lic){
		$cod_cli = Session::get('user.cod');
		if(!empty($cod_cli) && !empty($inf_env_lic->infenv)){
			$direccionEnvio = FxClid::select("CP_CLID, CODPAIS_CLID, DIR_CLID, DIR2_CLID, SG_CLID, POB_CLID, TEL1_CLID, PRO_CLID")->WHERE("CODD_CLID",$inf_env_lic->infenv)->where("cli_clid", $cod_cli)->first();
			if(!empty($direccionEnvio)){
				$inf_env_lic->paisenv= $direccionEnvio->codpais_clid;
				$inf_env_lic->provenv= $direccionEnvio->pro_clid;
				$inf_env_lic->pobenv= $direccionEnvio->pob_clid;
				$inf_env_lic->direnv= $direccionEnvio->sg_clid . ' ' . $direccionEnvio->dir_clid . $direccionEnvio->dir2_clid;
				$inf_env_lic->cpenv= $direccionEnvio->cp_clid;
				$inf_env_lic->telenv= $direccionEnvio->tel1_clid;
			}
		}


		return $inf_env_lic;
	}

	public function addressStorePickup($inf_env_lic)
	{
		$inf_env_lic->paisenv = '';
		$inf_env_lic->provenv = '';
		$inf_env_lic->pobenv = '';
		$inf_env_lic->direnv = 'RECOGIDA EN TIENDA';
		$inf_env_lic->cpenv = '';
		$inf_env_lic->telenv = '';
		return $inf_env_lic;
	}
}
