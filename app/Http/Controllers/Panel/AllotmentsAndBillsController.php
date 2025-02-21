<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use App\Models\Address;
use App\Models\Facturas;
use App\Models\Payments;
use App\Models\Subasta;
use App\Models\User;
use App\Models\V5\FgCsub;
use App\Models\V5\FgSub;
use App\Models\V5\FsPaises;
use App\Models\V5\FxClid;
use App\Models\V5\FxDvc0;
use App\Models\V5\FxDvc0Seg;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AllotmentsAndBillsController extends Controller
{
	public function getDirectSaleAdjudicaciones($lang)
	{
		return $this->getAllAdjudicaciones($lang, true);
	}

	//Todos los lotes pgados y no pagados
	public function getAllAdjudicaciones($lang = null, $onlyDirectSales = false)
	{
		$seo = new \Stdclass();
		$seo->noindex_follow = true;

		$subasta = new Subasta();
		$parametrosSub = $subasta->getParametersSub();
		if (!Session::has('user')) {
			$url =  Config::get('app.url') . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . '?view_login=true';
			$data['data'] =  trans_choice(Config::get('app.theme') . '-app.user_panel.not-logged', 1, ['url' => $url]);
			$data['seo'] = $seo;
			return View::make('front::pages.not-logged', $data);
		}

		$emp  = Config::get('app.emp');
		$gemp  = Config::get('app.gemp');
		# Lista de códigos de licitacion del usuario en sesion

		$User = new User();
		$User->cod_cli = Session::get('user.cod');
		$User->itemsPerPage = 'all';
		$adjudicaciones = $User->getAdjudicacionesPagar('N');

		$user_cli = $User->getUser($User->cod_cli);

		$pago_controller = new PaymentsController();
		$pago_modelo = new Payments();

		$user_cod = $User->cod_cli;

		$addres = new Address();
		$addres->cod_cli = $User->cod_cli;
		$envio = $addres->getUserShippingAddress();

		$iva = $pago_controller->getIva($emp, date("Y-m-d"));
		$tipo_iva = $pago_controller->user_has_Iva($gemp, $user_cod);

		#adjudicaciones que estan pendientes de pagar por transferencia, y no pueden salir como no pagadas
		$adjudicaciones_transfer = array();
		$pathAlbaran = DIRECTORY_SEPARATOR . 'bills' . DIRECTORY_SEPARATOR . $emp . DIRECTORY_SEPARATOR;

		foreach ($adjudicaciones as $key => $adj) {
			$adj->formatted_imp_asigl1 = ToolsServiceProvider::moneyFormat($adj->himp_csub);
			$adj->imagen = $subasta->getLoteImg($adj);
			$adj->date = ToolsServiceProvider::euroDate($adj->fec_asigl1, $adj->hora_asigl1);
			$adj->imp_asigl1 = $adj->himp_csub;

			$adj->prefactura = $this->proformaInvoiceFile($adj->sub_csub, true);

			#si no debe llevar iva la subasta online
			if (Config::get("app.noIVAOnlineAuction") && $adj->tipo_sub == 'O') {
				$adj->base_csub_iva = 0;
			} else {
				$adj->base_csub_iva = $pago_controller->calculate_iva($tipo_iva->tipo, $iva, $adj->base_csub);
			}

			//Modificamos ref_asigl0 de . a _ porque si hay punto el js de calclulo de pagar no calcula bien
			$adj->ref_asigl0 = str_replace('.', '_', $adj->ref_asigl0);
			$adj->days_extras_alm = $this->days_extras_almacen($adj->fecha_csub);

			//Existen lotes en Tauler que no deben añadir el precio de exportación al pago, en object_types controlamos si se cobra o no
			//03/11/2021 - Eloy
			$exportacion = $subasta->hasExportLicense($adj->num_hces1, $adj->lin_hces1);

			$adj->licencia_exportacion = 0;
			if ($exportacion) {
				$envioPorDefecto = collect($envio)->where('codd_clid', 'W1')->first();
				$adj->licencia_exportacion = $pago_controller->licenciaDeExportacionPorPais($envioPorDefecto->codpais_clid ?? $user_cli->codpais_cli, $adj->himp_csub);
			}

			#quitar las adjudicaciones por transferencia y ponerlas en otro listado
			if ($adj->estado_csub0 == "T") {
				$adjudicaciones_transfer[] = $adj;
				unset($adjudicaciones[$key]);
			}
		}

		if (config('app.allotments_shopping_cart', false)) {
			$adjudicaciones = $adjudicaciones->filter(function ($item) use ($onlyDirectSales) {
				if ($onlyDirectSales) {
					return $item->tipo_sub == 'V';
				}
				return $item->tipo_sub != 'V';
			});
		}

		//Podemos saber que iva va a tener el cliente
		$user_cli->iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo, $iva);


		$sub = new Subasta();

		///**************************************** */
		$facturas = new Facturas();

		$User->itemsPerPage = null;

		$adjudicaciones_pag = $User->getAdjudicacionesPagar('S');

		$user_cli = $User->getUser($User->cod_cli);

		$user_cod = $User->cod_cli;

		$hoy = date("Y-m-d");

		foreach ($adjudicaciones_pag as $adj) {

			$adj->formatted_imp_asigl1 = ToolsServiceProvider::moneyFormat($adj->himp_csub);
			$adj->imagen = $sub->getLoteImg($adj);
			$adj->date = ToolsServiceProvider::euroDate($adj->fec_asigl1, $adj->hora_asigl1);
			$adj->imp_asigl1 = $adj->himp_csub;
			#si no debe llevar iva la subasta online
			if (Config::get("app.noIVAOnlineAuction") && $adj->tipo_sub == 'O') {
				$adj->base_csub_iva = 0;
			} else {
				$adj->base_csub_iva = $pago_controller->calculate_iva($tipo_iva->tipo, $iva, $adj->base_csub);
			}

			$extras = array();
			$adj->extras = $pago_modelo->getGastosExtrasLot($adj->sub_csub, $adj->ref_csub, $tipo = null, 'C');
			$adj->factura = $this->bills($adj->afral_csub, $adj->nfral_csub, true);
			$adj->serie = $adj->afral_csub;
			$adj->numero = $adj->nfral_csub;
			$adj->pending_fact = $facturas->pending_bills(false);
			$adj->days_extras_alm = $this->days_extras_almacen($adj->fecha_csub);

			//Existen lotes en Tauler que no deben añadir el precio de exportación al pago, en object_types controlamos si se cobra o no
			//03/11/2021 - Eloy
			$exportacion = $subasta->hasExportLicense($adj->num_hces1, $adj->lin_hces1);

			$adj->licencia_exportacion = 0;
			if ($exportacion) {
				$envioPorDefecto = collect($envio)->where('codd_clid', 'W1')->first();
				$adj->licencia_exportacion = $pago_controller->licenciaDeExportacionPorPais($envioPorDefecto->codpais_clid ?? $user_cli->codpais_cli, $adj->himp_csub);
			}
		}


		#si usan las tablas de WEB_GASTOS_ENVIO cargaremos las direcciones
		$address = NULL;
		if (Config::get("app.web_gastos_envio")) {
			$fxClid = new FxClid();
			$address = $fxClid->getForSelectHTML(Session::get('user.cod'));
		}

		$data = array(
			'address' => $address,
			'adjudicaciones' => $adjudicaciones,
			'adjudicaciones_transfer' => $adjudicaciones_transfer,
			'currency'       => $sub->getCurrency(),
			'envio'    => $envio,
			'user'  => $user_cli,
			'js_item' => $this->generatePreciosLotAdj($adjudicaciones),
			'price_exportacion' => floatval($parametrosSub->licexp_prmsub),
			'adjudicaciones_pag' => $adjudicaciones_pag,
			#al pasar le valor 1 devolvera el iva que hay que aplicar, por ejemplo 0,21
			'ivaAplicable' => $pago_controller->calculate_iva($tipo_iva->tipo, $iva, 1),
			'seo' => $seo,
			'onlyDirectSales' => $onlyDirectSales
		);

		return View::make('front::pages.panel.adjudicaciones', array('data' => $data));
	}

	public function getAdjudicacionesPendientePagoByProforma(Request $request, $lang, $apre, $npre)
	{
		if (!Session::has('user')) {
			$url =  Config::get('app.url') . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . '?view_login=true';
			$data = trans_choice(Config::get('app.theme') . '-app.user_panel.not-logged', 1, ['url' => $url]);
			return View::make('front::pages.not-logged', array('data' => $data));
		}

		$User = new User();
		$User->cod_cli = Session::get('user.cod');
		$User->itemsPerPage = 'all';

		$criteria = [
			'apre_csub' => $apre,
			'npre_csub' => $npre,
		];

		$adjudicaciones = $User->getAdjudicacionesPagar('N', null, $criteria);

		if ($adjudicaciones->count() == 0) {
			return view('errors.404');
		}

		$data = $this->getAdjudicacionesPendiente($User, $adjudicaciones);

		return View::make('front::pages.panel.adjudicaciones_subasta_pagar', ['data' => $data]);
	}

	public function getAdjudicacionesPendientePagoBySub($lang, $cod_sub)
	{
		if (!Session::has('user')) {
			$url =  Config::get('app.url') . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . '?view_login=true';
			$data = trans_choice(Config::get('app.theme') . '-app.user_panel.not-logged', 1, ['url' => $url]);
			return View::make('front::pages.not-logged', array('data' => $data));
		}

		$User = new User();
		$User->cod_cli = Session::get('user.cod');
		$User->itemsPerPage = 'all';

		$adjudicaciones = $User->getAdjudicacionesPagar('N', $cod_sub);

		if ($adjudicaciones->count() == 0) {
			return view('errors.404');
		}

		$data = $this->getAdjudicacionesPendiente($User, $adjudicaciones);

		return View::make('front::pages.panel.adjudicaciones_subasta_pagar', ['data' => $data]);
	}

	/**
	 * Generea un certificado del lote en el servidor del cliente
	 * No es posible recuperar el archivo de manera local
	 */
	public function generateAuthenticityCertificate()
	{
		if (!Session::get('user')) {
			abort(401);
		}

		$emp = Config::get('app.emp');
		$cod_sub = request('cod_sub', '');
		$ref_asigl0 = request('ref_asigl0', '');
		$url = Config::get('app.url_certificado');

		$path = "/img/certificado/$emp/$cod_sub/$ref_asigl0.pdf";

		$file = public_path(str_replace("\\", "/", $path));
		if (file_exists($file)) {
			return response($path, 200);
		}

		if (empty($cod_sub) || empty($ref_asigl0)) {
			return response('error', 500);
		}

		$body = [
			'Empresa' => $emp,
			'Subasta' => $cod_sub,
			'Referencia' => $ref_asigl0
		];

		try {
			$response = Http::post($url, $body);
		} catch (\Throwable $th) {
			Log::error("error al conectar con api de certificados");
			Log::error($th);
			return response($th, 500);
		}

		if ($response->status() != 200) {
			Log::error("error en la api de certificados");
			Log::error($response->status());
			return response($response->status(), 500);
		}

		if ($response->body()) {
			Log::info("generando certificado", ['body' => $response->body()]);
			if (file_exists($file)) {
				return response($path, 200);
			} else {
				return response('Not exist', 404);
			}
		}
	}

	/**
	 * Recibir o descargar albaran
	 * @todo refactorizar - Separar parte de controlador y logica de negocio para que no sea llamado desde otro metodo del controlador
	 */
	public function proformaInvoiceFile($cod_sub, $returnDataFile = false)
	{
		if (!Session::has('user')) {
			$url =  Config::get('app.url') . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . '?view_login=true';
			$data = trans_choice(Config::get('app.theme') . '-app.user_panel.not-logged', 1, ['url' => $url]);
			return View::make('front::pages.not-logged', array('data' => $data));
		}

		$emp = Config::get('app.emp');

		$cod_cli = Session::get('user.cod');

		$path = "bills/$emp/$cod_cli-$cod_sub.PDF";

		$file = public_path(str_replace("\\", "/", $path));

		if (!file_exists($file)) {
			if ($returnDataFile) {
				return false;
			}
			exit(View::make('front::errors.404'));
		}

		if ($returnDataFile) {
			return [
				'date' => date('d-m-Y', filemtime($file)),
				'filname' => $path
			];
		}

		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename="' . $cod_cli . '-' . $cod_sub . '-' . date('d-m-Y', filemtime($file)) . '.PDF"');
		readfile($path);
	}

	/**
	 *Saber si existe pdf de factruas
	 * @todo refactorizar - Separar parte de controlador y logica de negocio para que no sea llamado desde otro metodo del controlador
	 */
	public function bills($afral, $nfral, $exist_file = false)
	{
		$user = new User();

		$factura = new \stdClass();
		$factura->afral = $afral;
		$factura->nfral = $nfral;
		$factura->cod_cli = Session::get('user.cod');
		$factura->pdf = null;
		$factura->emp = Config::get('app.emp');
		$a = $user->getFactura($factura);

		if (!empty($a) && !empty($a->fich_dvc02)) {
			$factura->pdf = $a->fich_dvc02;
			$factura->date = date('d-m-Y', strtotime($a->fecha_dvc0));
			//$directorio = $user->getFxdir($factura->emp);
			$filename =  'bills/' . Config::get('app.emp') . '/' . $factura->pdf . '.PDF';
			$data = array(
				'date' => $factura->date,
				'filname' => $filename
			);
			if ($exist_file) {
				return $data;
			}
			header('Content-type: application/pdf');
			header('Content-Disposition: attachment; filename="' . $factura->afral . $factura->nfral . '-' . $factura->date . '.PDF"');
			readfile($filename);
		} else {
			if ($exist_file) {
				return false;
			} else {
				exit(View::make('front::errors.404'));
			}
		}
	}

	public function getShipment()
	{
		$auction = FgSub::select('dfec_sub')->where('cod_sub', request('cod_sub'))->first();

		$afral_csub = request('afral_csub', '');
		$nfral_csub = request('nfral_csub', '');

		$shipments = (new FxDvc0Seg())->getSeguimientoEnvío($afral_csub, $nfral_csub);

		$deliveryDate = FxDvc0Seg::getEstimatedDeliveryDate($auction->dfec_sub);

		return response(['shipments' => $shipments, 'delivery_date' => $deliveryDate], 200);
	}

	//Cargamos todas las facturas pendioente y no pendientes de pago
	public function allBills()
	{
		$seo = new \Stdclass();
		$seo->noindex_follow = true;

		if (!Session::has('user')) {
			$url =  Config::get('app.url') . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . '?view_login=true';
			$data['data'] = trans_choice(Config::get('app.theme') . '-app.user_panel.not-logged', 1, ['url' => $url]);
			$data['seo'] = $seo;
			return View::make('front::pages.not-logged', $data);
		}

		$User = new User();
		$facturas = new Facturas();
		$payments = new Payments();
		$paymentsCont = new PaymentsController();
		$User->cod_cli = Session::get('user.cod');
		$user_cli = $User->getUser($User->cod_cli);
		$facturas->cod_cli = Session::get('user.cod');

		$inf_fact = array();
		//Sacamos facturas pendiente de pago
		$pendientes = $facturas->pending_bills();
		$tipo_tv = array();
		$js_fact = array();
		if (!empty($pendientes)) {
			foreach ($pendientes as $val_pendiente) {
				$facturas->serie = $val_pendiente->anum_pcob;
				$facturas->numero = $val_pendiente->num_pcob;
				$fact_temp = $this->bills($val_pendiente->anum_pcob, $val_pendiente->num_pcob, true);

				$val_pendiente->date = $fact_temp['date'] ?? null;
				$val_pendiente->factura = $fact_temp['filname'] ?? null;
				//buscamos si la factura esta generada
				$tipo_fact = $facturas->bill_text_sub(substr($facturas->serie, 0, 1), substr($facturas->serie, 1));
				//Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
				if ($tipo_fact->tv_contav == 'T') {
					$inf_fact['T'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facturas->getFactTexto();
				} elseif ($tipo_fact->tv_contav == 'L' || $tipo_fact->tv_contav == 'P') {
					$inf_fact['S'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facturas->getFactSubasta();
				}
				//Sacamos de factura el precio
				$js_fact[$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = floatval($val_pendiente->imp_pcob);
				//Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
				$tipo_tv[$facturas->serie][$facturas->numero] = $tipo_fact->tv_contav;
			}
		}

		//buscamos facturas pagadas
		$pagado = $facturas->paid_bill();
		$inf_fact_pag = array();
		$tipo_tv_pag = array();

		if (!empty($pagado)) {
			foreach ($pagado as $fact_pag) {
				$fact_temp = $this->bills($fact_pag->afra_cobro1, $fact_pag->nfra_cobro1, true);
				$fact_pag->date = $fact_temp['date'] ?? null;
				$fact_pag->factura = $fact_temp['filname'] ?? null;
				$facturas->serie = $fact_pag->afra_cobro1;
				$facturas->numero = $fact_pag->nfra_cobro1;
				//Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
				if ($fact_pag->tv_contav == 'T') {
					$inf_fact_pag['T'][$facturas->serie][$facturas->numero] = $facturas->getFactTexto();
				} elseif ($fact_pag->tv_contav == 'L' || $fact_pag->tv_contav == 'P') {
					$inf_fact_pag['S'][$facturas->serie][$facturas->numero] = $facturas->getFactSubasta();
				}
				//Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
				$tipo_tv_pag[$facturas->serie][$facturas->numero] = $fact_pag->tv_contav;
			}
		}

		$data = array(
			'pending' => $pendientes,
			'inf_factura'   => $inf_fact,
			'user'  => $user_cli,
			'js_item'   => $js_fact,
			'tipo_tv'  => $tipo_tv,
			'inf_factura_pag' => $inf_fact_pag,
			'bills' => $pagado,
			'tipo_tv_pag'   => $tipo_tv_pag,
			'seo' => $seo
		);

		return View::make('front::pages.panel.allBills', array('data' => $data));
	}

	public function getInvoiceOverviewView(Request $request)
	{
		$emp = Config::get('app.emp');
		$gemp = Config::get('app.gemp');
		$seo = new \Stdclass();
		$seo->noindex_follow = true;

		if (!Session::has('user')) {
			$url =  Config::get('app.url') . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . '?view_login=true';
			$data['data'] = trans_choice(Config::get('app.theme') . '-app.user_panel.not-logged', 1, ['url' => $url]);
			$data['seo'] = $seo;
			return View::make('front::pages.not-logged', $data);
		}

		$cod_cli = session('user.cod');
		$yearsSelected = $request->input('years', [date('Y'), date('Y') - 1]);

		$datesIntervals = array_map(function ($year) {
			return [
				$year . '-01-01',
				$year . '-12-31'
			];
		}, $yearsSelected);

		//user data
		$userModel = User::factory()
			->setCodCli($cod_cli)
			->setItemsPerPage('all');

		$user =	$userModel->getUser();

		$addres = new Address();
		$addres->cod_cli = $user->cod_cli;
		$envio = $addres->getUserShippingAddress();

		//payments data
		/**
		 * @todo La siguiente sección no tiene sentido que sea un controlador.
		 * Por no modificar en exceso el código, se ha dejado así.
		 * Pero en cuanto se pueda se debería modificar a un servicio o modelo.
		 */
		$paymentController = new PaymentsController();
		$iva = $paymentController->getIva($emp, date("Y-m-d"));
		$tipo_iva = $paymentController->user_has_Iva($gemp, $user->cod_cli);
		$paymentController->setIva($iva)->setTipoIva($tipo_iva)->setUser($user);

		//allotments
		$pendingAllotmentsData = $this->getPendingAllotmentsData($user, $envio, $paymentController, $datesIntervals);
		$payedAllotmentsData = $this->getPayedAllotmentsData($user, $envio, $paymentController, $datesIntervals);

		//bills
		$pendingBillsData = $this->getPendingBillsData($user, $datesIntervals);
		$payedBillsData =  $this->getPayedBillsData($user, $datesIntervals);

		//extraer variables para acomodar datos
		//esto no se hace dentro de los metodos anteriores para mantener compativilidad con metodos antiguos
		['adjudicaciones' => $pendingAllotments] = $pendingAllotmentsData;
		['adjudicaciones_pag' => $payedAllotments] = $payedAllotmentsData;

		['pending' => $pendingBills] = $pendingBillsData;
		['bills' => $billsPayeds] = $payedBillsData;

		$billsIds = $billsPayeds->map(function ($item) {
			return [
				'afra' => $item->afra_cobro1,
				'nfra' => $item->nfra_cobro1
			];
		});

		$billsPayedFollowUps = FxDvc0Seg::getFollowUpByBills($billsIds);
		$billsPayeds->each(function ($item) use ($billsPayedFollowUps) {
			$followUp = $billsPayedFollowUps->where('anum_dvc0seg', $item->afra_cobro1)
				->where('num_dvc0seg', $item->nfra_cobro1)
				->first();

			$item->followUp = $followUp;
		});

		$profomaInvoicesPendings = $pendingAllotments->groupBy(function ($item) {
			if (empty($item->apre_csub) || empty($item->npre_csub)) {
				return $item->sub_csub;
			}
			return "$item->apre_csub-$item->npre_csub";
		});

		//gastos de envío de cada profoma
		$shippmentsCosts = $profomaInvoicesPendings->map(function ($item, $key) use ($paymentController) {
			$totalProforma = $item->sum('total_imp_invoice');
			$shippment = $paymentController->gastosEnvio($totalProforma);
			$total =  $shippment['imp'] + $shippment['iva'];
			return (object)[
				'id' => $key,
				'cost' => $total,
			];
		});

		//prefacturas pagadas pero aún no facturadas
		$profomaInvoicesPayeds = $payedAllotments->where('fac_csub', '!=', 'S')->groupBy(function ($item) {
			return "$item->apre_csub-$item->npre_csub";
		});

		//Las facturas tipo T (de texto) no se están mostrando.
		//En caso de necesitarlas clonar el pendingBills y obtener solo las de tipo T
		//ya que los campos obtenidos son distintos
		$billsPending = $pendingBills->where('tipo_tv', 'L');

		$invoicesYearsAvailables = FxDvc0::getInvoicesYearsAvailables($cod_cli, 'L');
		$profomaYearsAvailables = FgCsub::getYearsToAllAwardsAvailables($cod_cli);
		$yearsAvailables = $invoicesYearsAvailables->merge($profomaYearsAvailables)->unique()->sortDesc();

		$data = [
			'user' => $user,
			'seo' => $seo,
			'envio' => $envio,
			'profomaInvoicesPendings' => $profomaInvoicesPendings,
			'profomaInvoicesPayeds' => $profomaInvoicesPayeds,
			'billsPending' => $billsPending,
			'billsPayeds' => $billsPayeds,
			'yearsAvailables' => $yearsAvailables,
			'yearsSelected' => $yearsSelected,
			'isAjax' => $request->ajax(),
			'shippmentsCosts' => $shippmentsCosts,
		];

		if ($request->ajax()) {
			return view('front::pages.panel.summary.allotments_block', ['data' => $data])->render();
		}

		return view('front::pages.panel.adjudicaciones_facturas', ['data' => $data]);
	}

	private function getPayedAllotmentsData($user, $envio, $paymentController, $datesIntervals = [])
	{
		$adjudicaciones_pag = User::factory()->setCodCli($user->cod_cli)->getAdjudicacionesPagar('S', null, null, $datesIntervals);

		$bills = $this->getUniqueBills($adjudicaciones_pag);

		//Con este bloque, logramos eliminar la consulta dentro del for de adjudicaciones
		$referencesByAction = $adjudicaciones_pag->groupBy('sub_csub')
			->map(function ($adjudicaciones) {
				return $adjudicaciones->pluck('ref_asigl0');
			});

		$gastosExtra = (new Payments())->getGastosExtra($referencesByAction, 'C');

		$adjudicaciones_pag = collect($adjudicaciones_pag)->map(function ($adjudicacion) use ($user, $envio, $paymentController, $gastosExtra, $bills) {
			return $this->allotmentFormat($adjudicacion, $user, $envio, $paymentController, $gastosExtra, $bills);
		});

		return [
			'adjudicaciones_pag' => $adjudicaciones_pag,
			#al pasar le valor 1 devolvera el iva que hay que aplicar, por ejemplo 0,21
			'ivaAplicable' => $paymentController->calculate_iva($paymentController->tipo_iva->tipo, $paymentController->iva, 1),
		];
	}

	private function getPendingAllotmentsData($user, $envio, $paymentController, $datesIntervals = [])
	{
		$subasta = new Subasta();
		$parametrosSub = $subasta->getParametersSub();

		$adjudicaciones_pendientes = User::factory()->setCodCli($user->cod_cli)->getAdjudicacionesPagar('N', null, null, $datesIntervals);

		//Con este bloque, logramos eliminar la consulta dentro del for de adjudicaciones
		$referencesByAction = $adjudicaciones_pendientes->groupBy('sub_csub')
			->map(function ($adjudicaciones) {
				return $adjudicaciones->pluck('ref_asigl0');
			});

		$gastosExtra = (new Payments())->getGastosExtra($referencesByAction, 'C');
		$bills = $this->getUniqueBills($adjudicaciones_pendientes);

		$adjudicaciones_temporales = collect($adjudicaciones_pendientes)->map(function ($adjudicacion) use ($user, $envio, $paymentController, $gastosExtra, $bills) {
			return $this->allotmentFormat($adjudicacion, $user, $envio, $paymentController, $gastosExtra, $bills);
		});

		$adjudicaciones = $adjudicaciones_temporales->where('estado_csub0', '!=', 'T');
		#adjudicaciones que estan pendientes de pagar por transferencia, y no pueden salir como no pagadas
		$adjudicaciones_transfer = $adjudicaciones_temporales->where('estado_csub0', 'T');

		return [
			'adjudicaciones' => $adjudicaciones,
			'adjudicaciones_transfer' => $adjudicaciones_transfer,
			'js_item' => $this->generatePreciosLotAdj($adjudicaciones),
			'price_exportacion' => floatval($parametrosSub->licexp_prmsub),
		];
	}

	private function getUniqueBills($adjudicaciones)
	{
		$bills = collect();
		foreach ($adjudicaciones as $adjudicacion) {

			if ($bills->where('afral_csub', $adjudicacion->afral_csub)->where('nfral_csub')->isNotEmpty()) {
				continue;
			}

			$bills->push([
				'afral_csub' => $adjudicacion->afral_csub,
				'nfral_csub' => $adjudicacion->nfral_csub,
				'file' => $this->bills($adjudicacion->afral_csub, $adjudicacion->nfral_csub, true)
			]);
		}
		return $bills;
	}

	private function allotmentFormat($adjudicacion, $user, $envio, PaymentsController $paymentController, $gastos, $bills)
	{
		$subastaClass = new Subasta();

		$withNotIva = Config::get("app.noIVAOnlineAuction") && $adjudicacion->tipo_sub == 'O';

		$adjudicacionFormat = (object) array_merge(get_object_vars($adjudicacion), [
			'formatted_imp_asigl1' => ToolsServiceProvider::moneyFormat($adjudicacion->himp_csub),
			'imagen' => $subastaClass->getLoteImg($adjudicacion),
			'date' => ToolsServiceProvider::euroDate($adjudicacion->fec_asigl1, $adjudicacion->hora_asigl1),
			'imp_asigl1' => $adjudicacion->himp_csub,
			'base_csub_iva' => $withNotIva ? 0 : $paymentController->calculate_iva($paymentController->tipo_iva->tipo, $paymentController->iva, $adjudicacion->base_csub),
			//'extras' => (new Payments())->getGastosExtrasLot($adjudicacion->sub_csub,$adjudicacion->ref_csub, $tipo = null, 'C'),
			'extras' => $gastos->where('sub_asigl2', $adjudicacion->sub_csub)->where('ref_asigl2', $adjudicacion->ref_asigl0),
			'factura' => $bills->where('afral_csub', $adjudicacion->afral_csub)->where('nfral_csub')->first()->file ?? null,
			//'pending_fact' => (new Facturas())->pending_bills(false), //no le he encontrado sentido
			'days_extras_alm' => $this->days_extras_almacen($adjudicacion->fecha_csub),
			'prefactura' => $this->proformaInvoiceFile($adjudicacion->sub_csub, true),
			'ref_asigl0' => str_replace('.', '_', $adjudicacion->ref_asigl0)

		]);

		//Existen lotes en Tauler que no deben añadir el precio de exportación al pago, en object_types controlamos si se cobra o no
		$adjudicacionFormat->licencia_exportacion = 0;
		if ($adjudicacion->exportacion != 'N') {
			$envioPorDefecto = collect($envio)->where('codd_clid', 'W1')->first();
			$adjudicacionFormat->licencia_exportacion = $paymentController->licenciaDeExportacionPorPais($envioPorDefecto->codpais_clid ?? $user->codpais_cli, $adjudicacion->himp_csub);
		}

		$adjudicacionFormat->imp_invoice = $adjudicacion->himp_csub + $adjudicacion->base_csub + $adjudicacionFormat->base_csub_iva;
		$adjudicacionFormat->total_imp_invoice = $adjudicacionFormat->imp_invoice + $adjudicacionFormat->licencia_exportacion;

		return $adjudicacionFormat;
	}

	private function getPendingBillsData($user, $datesIntervals = [])
	{
		$facturas = new Facturas();
		$facturas->cod_cli = $user->cod_cli;

		$pendientes = $facturas->pending_bills(true, 'L', $datesIntervals);

		$sheets = $pendientes->map(function ($item) {
			return [
				'serie' => $item->anum_pcob,
				'line' => $item->num_pcob
			];
		})->toArray();

		$billFiles = $facturas->getBillsFilesFromMultipleSheets($sheets);
		$facturasSubastas = $facturas->getFacturaLotsByMultipleSheets($sheets);

		$inf_fact = [];
		$tipo_tv = [];
		$js_fact = [];

		foreach ($pendientes as $val_pendiente) {

			$facturas->serie = $val_pendiente->anum_pcob;
			$facturas->numero = $val_pendiente->num_pcob;

			$fact_temp = $billFiles->where('anum_dvc02', $val_pendiente->anum_pcob)->where('num_dvc02', $val_pendiente->num_pcob)->first();

			$val_pendiente->date = $fact_temp['date'] ?? null;
			$val_pendiente->factura = $fact_temp['filname'] ?? null;

			//buscamos si la factura esta generada
			$tipo_tv_contav = $val_pendiente->tv_contav;
			$val_pendiente->tipo_tv = $tipo_tv_contav;
			$totalPrice = 0;

			//Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
			if ($tipo_tv_contav == 'T') {

				$facturaTexto = $facturas->getFactTexto();
				$inf_fact['T'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facturaTexto;
				$val_pendiente->inf_fact = ['T'];
				$val_pendiente->inf_fact['T'] = $facturaTexto;

				foreach ($facturaTexto as $factura) {
					$totalPrice += $factura->total_dvc1 + round(($factura->total_dvc1 * $factura->iva_dvc1) / 100, 2);
				}
			} elseif ($tipo_tv_contav == 'L' || $tipo_tv_contav == 'P') {

				//$facutraSubasta = $facturas->getFactSubasta();
				$facturasSubasta = $facturasSubastas->where('anum_dvc1l', $val_pendiente->anum_pcob)->where('num_dvc1l', $val_pendiente->num_pcob); //->values()->all();
				$inf_fact['S'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facturasSubasta;

				$val_pendiente->inf_fact['S'] = $facturasSubasta->where('tl_dvc1l', 'P')->values()->all();
				if (!empty($val_pendiente->inf_fact['S'])) {
					$val_pendiente->cod_sub = $facturasSubasta->first()->sub_dvc1l ?? null;;
				}

				foreach ($facturasSubasta as $factura) {
					if ($tipo_tv_contav === 'P') {
						$totalPrice += (round(($factura->basea_dvc1l * $factura->iva_dvc1l) / 100, 2) + $factura->basea_dvc1l) - $factura->padj_dvc1l;
					}
					//=== L
					else {
						$linePrice = $factura->padj_dvc1l + $factura->basea_dvc1l + round(($factura->basea_dvc1l * $factura->iva_dvc1l) / 100, 2);
						$totalPrice += $linePrice;
					}
				}
			}

			$val_pendiente->total_price = $totalPrice;

			//Sacamos de factura el precio
			$js_fact[$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = floatval($val_pendiente->imp_pcob);
			//Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
			$tipo_tv[$facturas->serie][$facturas->numero] = $tipo_tv_contav;
		}

		return [
			'pending' => $pendientes,
			'inf_factura' => $inf_fact,
			'js_item'   => $js_fact,
			'tipo_tv'  => $tipo_tv,
		];
	}

	private function getPayedBillsData($user, $datesIntervals = [])
	{
		$facturas = new Facturas();
		$facturas->cod_cli = $user->cod_cli;

		$pagado = $facturas->paid_bill(false, 'L', $datesIntervals, true);
		$inf_fact_pag = array();
		$tipo_tv_pag = array();
		$totalPrice = 0;

		$sheets = $pagado->map(function ($item) {
			return [
				'serie' => $item->afra_cobro1,
				'line' => $item->nfra_cobro1
			];
		})->toArray();

		$billFiles = $facturas->getBillsFilesFromMultipleSheets($sheets);
		$facturasSubastas = $facturas->getFacturaLotsByMultipleSheets($sheets);

		foreach ($pagado as $fact_pag) {

			$fact_temp = $billFiles->where('anum_dvc02', $fact_pag->afra_cobro1)->where('num_dvc02', $fact_pag->nfra_cobro1)->first();
			$fact_pag->date = $fact_temp['date'] ?? null;
			$fact_pag->factura = $fact_temp['filname'] ?? null;
			$facturas->serie = $fact_pag->afra_cobro1;
			$facturas->numero = $fact_pag->nfra_cobro1;

			//Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
			if ($fact_pag->tv_contav == 'T') {
				//Las facturas de texto no están asociadas a lotes
				$facturaTexto = $facturas->getFactTexto();
				$fact_pag->cod_sub = null;
				$inf_fact_pag['T'][$facturas->serie][$facturas->numero] = $facturaTexto;
				$fact_pag->inf_fact = ['T'];
				$fact_pag->inf_fact['T'] = $facturaTexto;

				foreach ($facturaTexto as $factura) {
					$totalPrice += $factura->total_dvc1 + round(($factura->total_dvc1 * $factura->iva_dvc1) / 100, 2);
				}
			} elseif ($fact_pag->tv_contav == 'L' || $fact_pag->tv_contav == 'P') {

				//$facturasSubasta = $facturas->getFactSubasta();
				$facturasSubasta = $facturasSubastas->where('anum_dvc1l', $fact_pag->afra_cobro1)->where('num_dvc1l', $fact_pag->nfra_cobro1); //->values()->all();

				//Puede existir mas de una subasta en cada factura, pero ahora mismo solamente necesito la primera. (Eloy)
				$fact_pag->cod_sub = $facturasSubasta->first()->sub_dvc1l ?? null;

				$inf_fact_pag['S'][$fact_pag->afra_cobro1][$fact_pag->nfra_cobro1] = $facturasSubasta->values()->all();


				$fact_pag->inf_fact['S'] = $facturasSubasta->where('tl_dvc1l', 'P')->values()->all();
			}

			//Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
			$tipo_tv_pag[$fact_pag->afra_cobro1][$fact_pag->nfra_cobro1] = $fact_pag->tv_contav;
		}

		return [
			'inf_factura_pag' => $inf_fact_pag,
			'bills' => $pagado,
			'tipo_tv_pag'   => $tipo_tv_pag,
		];
	}

	/**
	 * @todo convertir en un metodo del servicio de adjudicaciones o del usuario
	 * @param User $User
	 * @param $adjudicaciones
	 * @return array
	 */
	private function getAdjudicacionesPendiente($User, $adjudicaciones)
	{
		$emp  = Config::get('app.emp');
		$gemp  = Config::get('app.gemp');
		$subasta = new Subasta();
		$parametrosSub = $subasta->getParametersSub();

		$user_cli = $User->getUser($User->cod_cli);

		$pago_controller = new PaymentsController();
		//$pago_modelo = new Payments();

		$user_cod = $User->cod_cli;

		$addres = new Address();
		$addres->cod_cli = $User->cod_cli;
		$envio = $addres->getUserShippingAddress();

		$iva = $pago_controller->getIva($emp, date("Y-m-d"));
		$tipo_iva = $pago_controller->user_has_Iva($gemp, $user_cod);

		/**
		 * Aunque solamente tenga una adjudicación, algunos metodos que la reciben esperan un array
		 * por lo que no se puede convertir en un objecto individual
		 * */
		foreach ($adjudicaciones as $adj) {
			$adj->formatted_imp_asigl1 = ToolsServiceProvider::moneyFormat($adj->himp_csub);
			$adj->imagen = $subasta->getLoteImg($adj);
			$adj->date = ToolsServiceProvider::euroDate($adj->fec_asigl1, $adj->hora_asigl1);
			$adj->imp_asigl1 = $adj->himp_csub;
			$adj->base_csub_iva = $pago_controller->calculate_iva($tipo_iva->tipo, $iva, $adj->base_csub);
			//Modificamos ref_asigl0 de . a _ porque si hay punto el js de calclulo de pagar no calcula bien
			$adj->ref_asigl0 = str_replace('.', '_', $adj->ref_asigl0);
			$adj->days_extras_alm = $this->days_extras_almacen($adj->fecha_csub);

			//Existen lotes en Tauler que no deben añadir el precio de exportación al pago, en object_types controlamos si se cobra o no
			//03/11/2021 - Eloy
			$exportacion = $subasta->hasExportLicense($adj->num_hces1, $adj->lin_hces1);

			$adj->licencia_exportacion = 0;
			if ($exportacion) {
				$envioPorDefecto = collect($envio)->where('codd_clid', 'W1')->first();
				$adj->licencia_exportacion = $pago_controller->licenciaDeExportacionPorPais($envioPorDefecto->codpais_clid ?? $user_cli->codpais_cli, $adj->himp_csub);
			}
		}
		//Podemos saber que iva va a tener el cliente
		$user_cli->iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo, $iva);

		//paises
		$countries = FsPaises::select('cod_paises', 'des_paises')->JoinLangPaises()->orderby("des_paises")->pluck('des_paises', 'cod_paises');

		//formas de pago
		/**
		 * @todo pendiente
		 */

		return [
			'adjudicaciones' => $adjudicaciones,
			'currency'       => $subasta->getCurrency(),
			'envio'    => $envio,
			'user'  => $user_cli,
			'js_item' => $this->generatePreciosLotAdj($adjudicaciones),
			'price_exportacion' => floatval($parametrosSub->licexp_prmsub),
			'countries' => $countries
		];
	}

	private function days_extras_almacen($fecha_csub)
	{
		$params_sub = new \App\Models\Subasta();
		$params = $params_sub->getParametersSub();
		$date = round((strtotime("now") - strtotime($fecha_csub)) / 86400) - $params->dayspagext_prmsub;
		$price_almacen = 0;
		if ($date > 0) {
			$price_almacen = $date * $params->imppagext_prmsub;
		}

		return array('price' => $price_almacen, 'date' => $date);
	}

	//generamos array que nos servira para los calculos de lotes pagados y no pagados
	private function generatePreciosLotAdj($adjudicaciones)
	{
		$sub = new Subasta();
		$pago_modelo = new Payments();
		$pago_controller = new PaymentsController();

		$iva = $pago_controller->getIva(Config::get('app.emp'), date("Y-m-d"));
		$tipo_iva = $pago_controller->user_has_Iva(Config::get('app.gemp'), Session::get('user.cod'));
		$iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo, $iva);

		//parametros de subasta, lo utilizamos por precio de seguro y de licencia exportacion
		$parametrosSub = $sub->getParametersSub();
		//creamos $lot_js donde va estar toda la informaion de precios de cada lote, iva, exportacion, seguro
		$lot_js = array();
		$price_exportacion =  floatval($parametrosSub->licexp_prmsub);
		$price_exportacion_iva = $pago_controller->calculate_iva($tipo_iva->tipo, $iva, $price_exportacion);
		foreach ($adjudicaciones as $key => $puj) {

			$price_lot = new \stdClass;
			$price_lot->himp = (float) $puj->himp_csub;
			$price_lot->base = (float) number_format($puj->base_csub, 2, '.', '');
			$price_lot->iva = (float) number_format($puj->base_csub_iva, 2, '.', '');
			$price_lot->tipo_sub = $puj->tipo_sub;

			//Nos dice si la direccion de envio que nos pone el cliente se puede mandar el lote
			$price_lot->transporte_lot = false;

			//precio de transporte y precio transporte iva
			$price_lot->transporte = 0;
			$price_lot->transporte_iva = 0;

			//Para saber si el lote tiene exportacion
			$price_lot->exportacion = $pago_controller->licenciaDeExportacion($puj->ref_csub, $puj->sub_csub);
			// exportacion_codpais esta a false si el cliete quiere mandarlo fuera ES lo sabemos
			$price_lot->exportacion_codpais = false;
			//cliente hace exportacion o empresa
			$price_lot->exportacion_opcion = true;
			//precio seguro y iva seguro
			$price_lot->precio_seguro = 0;
			$price_lot->iva_seguro = 0;

			// precio exporatcion del lote y iva

			$price_lot->precio_exportacion = $price_exportacion;
			$price_lot->iva_exportacion = $price_exportacion_iva;

			//llevar el calculo numero de lotes que se van a recojer o se envian
			$price_lot->recojer = 0;
			$price_lot->enviar = 0;

			$extra = array();
			$extra = $pago_modelo->getGastosExtrasLot($puj->cod_sub, $puj->ref_csub, 'E');
			$price_lot->extra = $extra;

			$price_lot->seguro = $pago_controller->calcSeguro($price_lot->himp + $price_lot->base + $price_lot->iva);

			//licencia de exportacion
			$price_lot->licencia_exportacion = floatval($puj->licencia_exportacion);

			//Guardamos el objeto en un array con codigo de subasta, lote y referencia del lote
			$lot_js[$puj->cod_sub]['lots'][$adjudicaciones[$key]->ref_asigl0] = $price_lot;
		}

		$typeSub = data_get($adjudicaciones, '0.tipo_sub', null);

		$iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo, $iva);
		if(Config::get("app.noIVAOnlineAuction") && $typeSub == 'O'){
			$iva_cli = 0;
		}

		//tenemos iva del cliente
		$lot_js['iva'] = floatval($iva_cli);

		return $lot_js;
	}
}
