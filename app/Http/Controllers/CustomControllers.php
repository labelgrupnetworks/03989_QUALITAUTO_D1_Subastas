<?php

namespace App\Http\Controllers;

use App\Exports\ViewExcelExport;
use App\Providers\ToolsServiceProvider;
use App\Models\V5\FgAsigl0;
use App\Http\Controllers\V5\GaleriaArte;
use App\libs\EmailLib;
use App\libs\FormLib;
use App\Models\V5\Web_Artist;
use App\Models\V5\FgSub;
use App\Models\V5\FgPujas;
use App\Models\V5\FgPujasSub;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel as Excel;
use App\Models\Subasta;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\libs\SeoLib;
class CustomControllers extends Controller
{
	function exportPackengers($codSub)
	{
		$gemp = \Config::get('app.gemp');
		$dataForExport = FgAsigl0::select(
			" sub_asigl0  || '-' || ref_asigl0 as id",
			'ANCHO_HCES1 as length',
			'GRUESO_HCES1 as depth',
			'ALTO_HCES1 as height',
			"DES_UMED as metrics_unit",
			'IMPSALHCES_ASIGL0 as value',
			"'Eur' as currency",
			"'Aquí va photo_url' as photo_url",
			"'Aquí va lot_url' as lot_url",
			"NOM_CLI as owner_name",
			'DIR_ALM as picking_address',
			'CODPAIS_ALM as picking_country',
			'POB_ALM as picking_city',
			'CP_ALM as picking_zipcode',
			"nvl(DESCWEB_HCES1, TITULO_HCES1) || ' <br>' || DESC_HCES1 as description",
			'REF_ASIGL0 as lot_number',
			'DES_SUB as catalog_name',
			'SUB_ASIGL0 as calalog_reference',
			'"start" as catalog_date',
			'COD_SUB as cod_sub',
			'"id_auc_sessions" as id_auc_sessions',
			'"name" as name',
			'NUM_HCES1 as num_hces1',
			'WEBFRIEND_HCES1 as webfriend_hces1',
			'LIN_HCES1 as lin_hces1'
		)
			->joinFghces1Asigl0()
			->leftJoinAlm()
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->leftjoin('FXCLI', "FXCLI.GEMP_CLI = $gemp AND FXCLI.COD_CLI = FGHCES1.PROP_HCES1")
			->join('FXUMED', "FXUMED.GEMP_UMED = $gemp AND FXUMED.COD_UMED = nvl(FGHCES1.ALTOUMED_HCES1, 1)")
			->where('SUB_ASIGL0', $codSub)
			->orderBy('lot_number', 'asc')
			->get();

		$fileName = "Packengers_" . $codSub;


		foreach ($dataForExport as $key => $value) {
			$url_friendly = \Tools::url_lot($value->cod_sub, $value->id_auc_sessions, $value->name, $value->lot_number, $value->num_hces1, $value->webfriend_hces1, $value->description);
			$dataForExport[$key]["lot_url"] = $url_friendly;
			$dataForExport[$key]["photo_url"] = \Tools::url_img('lote_medium', $value->num_hces1, $value->lin_hces1);

			#quitar código html en la descripción
			$dataForExport[$key]["description"] = strip_tags($value->description);

			/* Borrar variables innecesarias debajo del catalog_date */
			unset($dataForExport[$key]["cod_sub"]);
			unset($dataForExport[$key]["id_auc_sessions"]);
			unset($dataForExport[$key]["name"]);
			unset($dataForExport[$key]["num_hces1"]);
			unset($dataForExport[$key]["webfriend_hces1"]);
			unset($dataForExport[$key]["lin_hces1"]);
		}


		return ToolsServiceProvider::exportCollectionToExcel($dataForExport, $fileName);
	}



	public function excelExhibition($codSub, $reference, $stock = false)
	{
		$galeriaArte = new GaleriaArte();

		if(!empty($codSub)){
			$fgsub = new Fgsub();
			$auction = $fgsub->getInfoSub($codSub, $reference);

			\Tools::exit404IfEmpty($auction);
			if ($auction->tipo_sub != 'E') {
				exit(\View::make('front::errors.404'));
			}
		}else{
			$auction =null;
		}


		$fgasigl0 = new FgAsigl0();



		if($stock){
			$lots =  $fgasigl0->select('FGHCES1.NUM_HCES1, FGHCES1.LIN_HCES1, IMPSALHCES_ASIGL0,  DESCWEB_HCES1, REF_ASIGL0,   STOCK_HCES1,OBSDET_HCES1,FECALTA_ASIGL0, DES_ALM, SUB_ASIGL0, OBSDET_HCES1, FECALTA_ASIGL0, PC_HCES1')
			->JoinFghces1Asigl0()->LeftJoinAlm()
			->where("stock_hces1",">",0)->orderby("sub_asigl0,ref_asigl0")->get();
		}else{
			$lots =  $fgasigl0->select('FGHCES1.NUM_HCES1, FGHCES1.LIN_HCES1, IMPSALHCES_ASIGL0,  DESCWEB_HCES1, REF_ASIGL0,   DES_SUB, DFEC_SUB, HFEC_SUB,IDVALUE_CARACTERISTICAS_HCES1')
			->leftjoin('FGCARACTERISTICAS_HCES1', "FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0 AND FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 = '" . \Config::get("app.ArtistCode") . "'")
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

			if (\Config::get("app.ArtistNameSurname")) {
				$artists =	 $galeriaArte->nameSurname($artists);
			}
		}

		if($stock){

			$fileName = 'stock';

			$export = new ViewExcelExport("expoStockExcel",compact('artists','caracteristicas','lots'));
			return Excel::download($export, "$fileName.xlsx");

		}else{

			$fileName = $codSub . '_Exhibition';

			$export = new ViewExcelExport("expoArtExcel",compact('artists','caracteristicas','lots','auction'));
			return Excel::download($export, "$fileName.xlsx");
		}

	}

	public function videoAuctions () {

		$isAdmin = (bool) session('user.admin');

		$subastaReciente = FgSub::select('des_sub', 'dfec_sub')
			->joinSessionSub()
			->when($isAdmin, function ($query) {
				return $query->whereIn('SUBC_SUB', ['S', 'A']);
			}, function ($query) {
				return $query->where('SUBC_SUB', 'S');
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
			->where("EMP_ASIGL0", \Config::get('app.emp'))
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
		$dominio = \Config::get('app.url');
		$codSub = request()->codSub;
		$lots = FgAsigl0::select("SUB_ASIGL0 as cod_sub", "REF_ASIGL0", "NUM_HCES1", "LIN_HCES1", "WEBFRIEND_HCES1", "TITULO_HCES1", '"name"', '"id_auc_sessions"')
			->joinFghces1Asigl0()
			->joinSessionAsigl0()
			->where("EMP_ASIGL0", \Config::get('app.emp'))
			->where("SUB_ASIGL0", $codSub)
			->orderby("REF_ASIGL0")
			->get();



			foreach ($lots as $lot) {
				$url = "";

				$webfriend = !empty($lot->webfriend_hces1)? $lot->webfriend_hces1 :  str_slug($lot->titulo_hces1);
				$url_vars ="";
				$url_friendly = \Routing::translateSeo('lote').$lot->cod_sub."-".str_slug($lot->name).'-'.$lot->id_auc_sessions."/".$lot->ref_asigl0.'-'.$lot->num_hces1.'-'.$webfriend.$url_vars;
				$url = $dominio.$url_friendly;

				$lotsArrayToExport[$lot->cod_sub.'-'.$lot->ref_asigl0] = [
					'Referencia' => $lot->ref_asigl0,
					'Título' => $lot->titulo_hces1,
					'URL' => $url
				];

			}

			$lotsCollectForExport = collect($lotsArrayToExport);
			$filename = \Config::get('app.theme').'_'.$codSub.'_Lotes';


		return ToolsServiceProvider::exportCollectionToExcel($lotsCollectForExport, $filename);
	}

	public function preciosFueraEscalado($codSub){

		$scales =FgPujasSub::select("imp_pujassub  imp_pujas, puja_pujassub  puja_pujas")->where("SUB_PUJASSUB", $codSub)->orderby("imp_pujas")->get();

		if (empty($scales)){
			$scales =FgPujas::orderby("imp_pujas")->get();
		}

		$rangos = [];
		foreach($scales as $scale) {
			$rangos[$scale->imp_pujas] = $scale->puja_pujas;
		}

		#comprobar si los rangos estan bien hechos
		$rangoAnterior = 0;
		foreach($rangos as $maxImp => $scale){
			#calculamos la diferencia de un rango con el otro
			$valor = $maxImp - $rangoAnterior;
			$resto = $valor % $scale;
			#Si hay resto es que los rangos no son correctos
			if($resto > 0){
				echo "Error en rango de escalado desde " . \Tools::moneyFormat($rangoAnterior,"€")." hasta " . \Tools::moneyFormat($maxImp,"€")." , no se puede alcanzar " . \Tools::moneyFormat($maxImp,"€")." sumando de " . \Tools::moneyFormat($scale,"€")."  en " . \Tools::moneyFormat($scale,"€")." desde " . \Tools::moneyFormat($rangoAnterior,"€")." <br> <br> ";
			}
			$rangoAnterior = $maxImp;
		}


		$lots = FgAsigl0::SELECT("REF_ASIGL0, IMPSALHCES_ASIGL0  ")->where("SUB_ASIGL0", $codSub)->get();

		if(count($lots) ==0 ){
			echo "No hay lotes en la subasta seleccionada ";
			die();
		}
		$lotesFueraRango = "LOTES FUERA DE ESCALADO:<br><br> <ul>";
		$hay = false;
		foreach($lots as $lot){
			$rangoAnterior = 0;
			foreach($rangos as $maxImp => $scale){
				#estamos en el rango de escalado correcto

				if($lot->impsalhces_asigl0 < $maxImp ){
					#restamos para dejar solo la parte de escalados
					$valor = $lot->impsalhces_asigl0  - $rangoAnterior;
					$resto = $valor % $scale;
					#Si hay resto es que no esta dentro de la escala
					if($resto > 0){
						$hay = true;
						$lotesFueraRango .= "<li>Ref: ".$lot->ref_asigl0." Importe " . \Tools::moneyFormat($lot->impsalhces_asigl0,"€")."  erroneo <ul><li> Importe Correcto:". \Tools::moneyFormat($lot->impsalhces_asigl0 - $resto,"€")." o " .\Tools::moneyFormat($lot->impsalhces_asigl0 - $resto + $scale,"€")." </li></ul></li>";
					}
					break;
				}
				$rangoAnterior = $maxImp;
			}
		}
		$lotesFueraRango .= "</ul>";

		if(!$hay){
			echo "No hay lotes fuera de escalado";
		}else{
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

		$url_lot = ToolsServiceProvider::url_lot($cod_sub, $result->id_session,'' ,$ref, $result->num_hces, $result->friendly, $result->titulo);

		$qr = QrCode::format('svg')->size(100)->generate($url_lot);

		return $qr;

	}

	public function privateChanelLogin()
	{
		if(!Config::get('app.access_to_private_chanel', false)){
			return abort(404);
		}

		return view('pages.private_chanel.login');
	}

	public function loginInPrivateChanel(Request $request)
	{
		$isUser = $request->input('user') === Config::get('app.privatechanel_user');
		$isPassword = Hash::check($request->input('password'), Config::get('app.privatechanel_hash_password'));

		if(!$isUser || !$isPassword){
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
		$emailLib->setTo('luis.gasset@ansorena.com;jaimemato@ansorena.com' . ($impechment == 'bc' ? ';carloszolle@ansorena.com' : ''));
		$emailLib->send_email();

		return redirect(route('home'))->withErrors(['success' => 'Formulario enviado correctamente.']);
	}

	#funcion para guardar cualquier evento pasado por ajax, de esta manera permitimos que se creen eventos para acciones en la web, cómo por ejemplo descargar el catalogo
	public function saveEvent($event){
		# EJEMPLO URL PARA GUARDAR EVENTO DE DESCARGA DE CATALOGO : seo_event/CATALOG

		SeoLib::saveEvent($event);
	}
}
