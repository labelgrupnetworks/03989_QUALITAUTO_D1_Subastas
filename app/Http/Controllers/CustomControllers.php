<?php

namespace App\Http\Controllers;

use App\Exports\ViewExcelExport;
use App\Http\Controllers\V5\GaleriaArte;
use App\Http\Integrations\Packengers\PackengersService;
use App\libs\EmailLib;
use App\libs\SeoLib;
use App\Models\Subasta;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgPujas;
use App\Models\V5\FgPujasSub;
use App\Models\V5\FgSub;
use App\Models\V5\Web_Artist;
use App\Providers\RoutingServiceProvider;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel as Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CustomControllers extends Controller
{

	function exportSession(Request $request, $service, $idAucSession)
	{
		$type = $request->input('type', 'csv');
		//$exportFile = PackengersService::getAuctionExportFile($codSub);

		$exportService = match ($service) {
			'packengers' => PackengersService::getAuctionSessionExportFile($idAucSession),
			default => null
		};

		if (!$exportService) {
			return abort(404);
		}

		return match($type) {
			'xlsx', 'excel' => $exportService->download("{$service}_{$idAucSession}.xlsx"),
			default => $exportService->download("{$service}_{$idAucSession}.csv"),
		};
	}

	/**
	 * Lo utiliza ERP. Si se quiere modifcar la ruta es necesario avisarles.
	 */
	function exportPackengers(Request $request, $codSub)
	{
		$type = $request->input('type', 'csv');
		$exportFile = PackengersService::getAuctionExportFile($codSub);

		return match($type) {
			'xlsx', 'excel' => $exportFile->download("Packengers_$codSub.xlsx"),
			default => $exportFile->download("Packengers_$codSub.csv"),
		};
	}


	/**
	 * @todo Solamente se utiliza desde controladores de admin.
	 * Mover allí.
	 */
	public function excelExhibition($codSub, $reference, $stock = false)
	{
		$galeriaArte = new GaleriaArte();

		if (!empty($codSub)) {
			$fgsub = new Fgsub();
			$auction = $fgsub->getInfoSub($codSub, $reference);

			ToolsServiceProvider::exit404IfEmpty($auction);
			if ($auction->tipo_sub != 'E') {
				exit(View::make('front::errors.404'));
			}
		} else {
			$auction = null;
		}


		$fgasigl0 = new FgAsigl0();



		if ($stock) {
			$lots =  $fgasigl0->select('FGHCES1.NUM_HCES1, FGHCES1.LIN_HCES1, IMPSALHCES_ASIGL0,  DESCWEB_HCES1, REF_ASIGL0,   STOCK_HCES1,OBSDET_HCES1,FECALTA_ASIGL0, DES_ALM, SUB_ASIGL0, OBSDET_HCES1, FECALTA_ASIGL0, PC_HCES1')
				->JoinFghces1Asigl0()->LeftJoinAlm()
				->where("stock_hces1", ">", 0)->orderby("sub_asigl0,ref_asigl0")->get();
		} else {
			$lots =  $fgasigl0->select('FGHCES1.NUM_HCES1, FGHCES1.LIN_HCES1, IMPSALHCES_ASIGL0,  DESCWEB_HCES1, REF_ASIGL0,   DES_SUB, DFEC_SUB, HFEC_SUB,IDVALUE_CARACTERISTICAS_HCES1')
				->leftjoin('FGCARACTERISTICAS_HCES1', "FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0 AND FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 = '" . Config::get("app.ArtistCode") . "'")
				->where("COD_SUB", $codSub)->ActiveLotAsigl0()->orderby("orden_hces1,ref_hces1")->get();
		}

		$fgasigl0 = new FgAsigl0();
		$fgasigl0 = $fgasigl0->select('NUM_HCES1, LIN_HCES1, ID_CARACTERISTICAS, nvl(VALUE_CARACTERISTICAS_VALUE,VALUE_CARACTERISTICAS_HCES1 ) VALUE_CARACTERISTICAS')
			->LeftJoinCaracteristicasAsigl0()
			->ActiveLotAsigl0();


		$fgasigl0 = $fgasigl0->where("COD_SUB", $codSub);


		$caracteristicasTmp = $fgasigl0->get();
		$caracteristicas = array();

		foreach ($caracteristicasTmp as $caracteristica) {

			$numLin = $caracteristica->num_hces1 . "_" . $caracteristica->lin_hces1;
			if (empty($caracteristicas[$numLin])) {
				$caracterizsticas[$numLin] = array();
			}
			if ($caracteristica->id_caracteristicas == 1) {
				$caracteristicas[$numLin][$caracteristica->id_caracteristicas] = $galeriaArte->explodeComillas($caracteristica->value_caracteristicas);
			} else {
				$caracteristicas[$numLin][$caracteristica->id_caracteristicas] = $caracteristica->value_caracteristicas;
			}
		}
		#buscamos los artistas de la exposición
		$idArtists = [];
		foreach ($lots as $lot) {
			if (empty($idArtists[$lot->idvalue_caracteristicas_hces1])) {
				$idArtists[$lot->idvalue_caracteristicas_hces1] = $lot->idvalue_caracteristicas_hces1;
			}
		}

		$artists = [];

		if (count($idArtists) > 0) {
			$web_artist = new WEB_ARTIST();
			$artists = $web_artist->select("NAME_ARTIST, ID_ARTIST")->LeftJoinLang()->wherein("WEB_ARTIST.ID_ARTIST", $idArtists)->get();

			if (Config::get("app.ArtistNameSurname")) {
				$artists =	 $galeriaArte->nameSurname($artists);
			}
		}

		if ($stock) {

			$fileName = 'stock';

			$export = new ViewExcelExport("expoStockExcel", compact('artists', 'caracteristicas', 'lots'));
			return Excel::download($export, "$fileName.xlsx");
		} else {

			$fileName = $codSub . '_Exhibition';

			$export = new ViewExcelExport("expoArtExcel", compact('artists', 'caracteristicas', 'lots', 'auction'));
			return Excel::download($export, "$fileName.xlsx");
		}
	}

	public function videoAuctions()
	{

		$isAdmin = (bool) session('user.admin');

		$subastaReciente = FgSub::select('des_sub', 'dfec_sub')
			->joinSessionSub()
			->when($isAdmin, function ($query) {
				return $query->whereIn('SUBC_SUB', ['S', 'A']);
			}, function ($query) {
				return $query->where('SUBC_SUB', 'S');
			})
			->when(Config::get('app.agrsub', null), function ($query) {
				return $query->where('agrsub_sub', Config::get('app.agrsub'));
			})
			//orden ascendente solo para probar subasta, dejar en desc cuando este en producción
			//->orderby("session_start", "asc")
			->orderby("session_start", "desc")
			->first();

		if (!$subastaReciente) {
			return view('front::pages.video_auction', ['videoSorted' => [], 'subastaReciente' => new FgSub()]);
		}

		$lots = FgAsigl0::select("SUB_ASIGL0", "REF_ASIGL0", "NUM_HCES1", "LIN_HCES1")
			->joinFghces1Asigl0()
			->where("EMP_ASIGL0", Config::get('app.emp'))
			->where("SUB_ASIGL0", $subastaReciente->cod_sub)
			->orderBy('ref_asigl0')
			->get();

		$subasta = new Subasta();
		$videos = [];

		foreach ($lots as $lot) {
			$video = $subasta->getLoteVideos($lot);
			if (!empty($video)) {
				$videos[$lot->ref_asigl0] = $video[0];
			}
		}

		/* $videoSorted = collect($videos)->flatten()->sortBy(function($video) {
			return last(explode("/", $video));
		}); */

		return view('front::pages.video_auction', compact('videos', 'subastaReciente'));
	}

	public function exportarLotes()
	{
		$dominio = Config::get('app.url');
		$codSub = request()->codSub;
		$lots = FgAsigl0::select("SUB_ASIGL0 as cod_sub", "REF_ASIGL0", "NUM_HCES1", "LIN_HCES1", "WEBFRIEND_HCES1", "TITULO_HCES1", '"name"', '"id_auc_sessions"')
			->joinFghces1Asigl0()
			->joinSessionAsigl0()
			->where("EMP_ASIGL0", Config::get('app.emp'))
			->where("SUB_ASIGL0", $codSub)
			->orderby("REF_ASIGL0")
			->get();



		foreach ($lots as $lot) {
			$url = "";

			$webfriend = !empty($lot->webfriend_hces1) ? $lot->webfriend_hces1 :  str_slug($lot->titulo_hces1);
			$url_vars = "";
			$url_friendly = RoutingServiceProvider::translateSeo('lote') . $lot->cod_sub . "-" . str_slug($lot->name) . '-' . $lot->id_auc_sessions . "/" . $lot->ref_asigl0 . '-' . $lot->num_hces1 . '-' . $webfriend . $url_vars;
			$url = $dominio . $url_friendly;

			$lotsArrayToExport[$lot->cod_sub . '-' . $lot->ref_asigl0] = [
				'Referencia' => $lot->ref_asigl0,
				'Título' => $lot->titulo_hces1,
				'URL' => $url
			];
		}

		$lotsCollectForExport = collect($lotsArrayToExport);
		$filename = Config::get('app.theme') . '_' . $codSub . '_Lotes';


		return ToolsServiceProvider::exportCollectionToExcel($lotsCollectForExport, $filename);
	}

	public function preciosFueraEscalado($codSub)
	{

		$scales = FgPujasSub::select("imp_pujassub  imp_pujas, puja_pujassub  puja_pujas")->where("SUB_PUJASSUB", $codSub)->orderby("imp_pujas")->get();

		if (empty($scales)) {
			$scales = FgPujas::orderby("imp_pujas")->get();
		}

		$rangos = [];
		foreach ($scales as $scale) {
			$rangos[$scale->imp_pujas] = $scale->puja_pujas;
		}

		#comprobar si los rangos estan bien hechos
		$rangoAnterior = 0;
		foreach ($rangos as $maxImp => $scale) {
			#calculamos la diferencia de un rango con el otro
			$valor = $maxImp - $rangoAnterior;
			$resto = $valor % $scale;
			#Si hay resto es que los rangos no son correctos
			if ($resto > 0) {
				echo "Error en rango de escalado desde " . ToolsServiceProvider::moneyFormat($rangoAnterior, "€") . " hasta " . ToolsServiceProvider::moneyFormat($maxImp, "€") . " , no se puede alcanzar " . ToolsServiceProvider::moneyFormat($maxImp, "€") . " sumando de " . ToolsServiceProvider::moneyFormat($scale, "€") . "  en " . ToolsServiceProvider::moneyFormat($scale, "€") . " desde " . ToolsServiceProvider::moneyFormat($rangoAnterior, "€") . " <br> <br> ";
			}
			$rangoAnterior = $maxImp;
		}


		$lots = FgAsigl0::SELECT("REF_ASIGL0, IMPSALHCES_ASIGL0  ")->where("SUB_ASIGL0", $codSub)->get();

		if (count($lots) == 0) {
			echo "No hay lotes en la subasta seleccionada ";
			die();
		}
		$lotesFueraRango = "LOTES FUERA DE ESCALADO:<br><br> <ul>";
		$hay = false;
		foreach ($lots as $lot) {
			$rangoAnterior = 0;
			foreach ($rangos as $maxImp => $scale) {
				#estamos en el rango de escalado correcto

				if ($lot->impsalhces_asigl0 < $maxImp) {
					#restamos para dejar solo la parte de escalados
					$valor = $lot->impsalhces_asigl0  - $rangoAnterior;
					$resto = $valor % $scale;
					#Si hay resto es que no esta dentro de la escala
					if ($resto > 0) {
						$hay = true;
						$lotesFueraRango .= "<li>Ref: " . $lot->ref_asigl0 . " Importe " . ToolsServiceProvider::moneyFormat($lot->impsalhces_asigl0, "€") . "  erroneo <ul><li> Importe Correcto:" . ToolsServiceProvider::moneyFormat($lot->impsalhces_asigl0 - $resto, "€") . " o " . ToolsServiceProvider::moneyFormat($lot->impsalhces_asigl0 - $resto + $scale, "€") . " </li></ul></li>";
					}
					break;
				}
				$rangoAnterior = $maxImp;
			}
		}
		$lotesFueraRango .= "</ul>";

		if (!$hay) {
			echo "No hay lotes fuera de escalado";
		} else {
			echo $lotesFueraRango;
		}
	}

	public function lotQRGenerator()
	{

		$cod_sub = request('cod_sub');
		$ref = request('ref');

		$result = FgAsigl0::select("sub_asigl0 as cod_sub", "ref_asigl0 as ref", "num_hces1 as num_hces", '"id_auc_sessions" as id_session', "descweb_hces1 as titulo", "WEBFRIEND_HCES1 as friendly")
			->joinFghces1Asigl0()
			->joinSessionAsigl0()
			->where("sub_asigl0", $cod_sub)
			->where("ref_asigl0", $ref)
			->first();

		$url_lot = ToolsServiceProvider::url_lot($cod_sub, $result->id_session, '', $ref, $result->num_hces, $result->friendly, $result->titulo);

		$qr = QrCode::format('svg')->size(100)->generate($url_lot);

		return $qr;
	}

	public function privateChanelLogin()
	{
		if (!Config::get('app.access_to_private_chanel', false)) {
			return abort(404);
		}

		return view('pages.private_chanel.login');
	}

	public function loginInPrivateChanel(Request $request)
	{
		$isUser = $request->input('user') === Config::get('app.privatechanel_user');
		$isPassword = Hash::check($request->input('password'), Config::get('app.privatechanel_hash_password'));

		if (!$isUser || !$isPassword) {
			return back()->withErrors(['user' => 'Usuario o contraseña incorrectos.']);
		}

		return view('pages.private_chanel.form');
	}

	public function sendPrivateChanelForm(Request $request)
	{
		$impechment = $request->input('impeachment');
		$message = $request->input('message');

		$impechmentType = $impechment === 'ce' ? 'Código ético' : 'Blanqueo de capitales';

		$emailLib = new EmailLib('IMPECHMENT_ADMIN');
		if (empty($emailLib->email)) {
			return redirect(route('home'))->withErrors(['success' => 'Formulario enviado correctamente.']);
		}

		$emailLib->setAtribute('IMPECHMENT_TYPE', $impechmentType);
		$emailLib->setAtribute('MESSAGE', $message);
		$emailLib->setTo('luis.gasset@ansorena.com;jaimemato@ansorena.com');
		$emailLib->send_email();

		return redirect(route('home'))->withErrors(['success' => 'Formulario enviado correctamente.']);
	}

	#funcion para guardar cualquier evento pasado por ajax, de esta manera permitimos que se creen eventos para acciones en la web, cómo por ejemplo descargar el catalogo
	public function saveEvent($event)
	{
		# EJEMPLO URL PARA GUARDAR EVENTO DE DESCARGA DE CATALOGO : seo_event/CATALOG

		SeoLib::saveEvent($event);
	}

	public function response_ocr(Request $request)
	{
		Log::info(print_r($request->all(), true));
	}
}
