<?php

namespace App\Http\Controllers;

use App\Models\Subasta;
use App\Models\User;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgCsub;
use App\Models\V5\FgHces1;
use App\Models\V5\FgOrlic;
use App\Models\V5\FxCli;
use App\Models\V5\Web_Cancel_Log;
use App\Http\Controllers\V5\GaleriaArte;
use App\Models\V5\FgAsigl1Mt;
use App\Models\V5\Web_Artist;
use App\Models\V5\FgSub;
use App\Providers\ToolsServiceProvider as Tools;
use Illuminate\Support\Facades\Config;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{

	const PUBLIC_PATH = DIRECTORY_SEPARATOR . 'reports' . DIRECTORY_SEPARATOR;

	protected $tableInfo;
	protected $bids;
	public $licits;
	private $awards;
	protected $pdfs = array();
	protected $pathPdfsSaved = array();

	public function __construct()
	{
	}


	public function pdfExhibition($codSub, $reference ){
		$galeriaArte = new GaleriaArte();

		$fgsub = new Fgsub();
		$auction = $fgsub->getInfoSub(  $codSub, $reference);

		\Tools::exit404IfEmpty($auction);
		if($auction->tipo_sub !='E'){
		exit(\View::make('front::errors.404'));
		}

		$fgasigl0 = new FgAsigl0();

		$lots = $fgasigl0->select('FGHCES1.NUM_HCES1, FGHCES1.LIN_HCES1, IMPSALHCES_ASIGL0,  DESCWEB_HCES1, REF_ASIGL0,   DES_SUB, DFEC_SUB, HFEC_SUB,IDVALUE_CARACTERISTICAS_HCES1')
						->leftjoin('FGCARACTERISTICAS_HCES1', "FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0 AND FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 = '". \Config::get("app.ArtistCode")."'" )
						->where("COD_SUB", $codSub)
						#ordenamos por orden, pero tambien tenemos en cuenta la referencia ya que por defecto el orden esta a nully rompia la ordenacion
						->ActiveLotAsigl0()->orderby("nvl(orden_hces1,ref_hces1), nvl(orden_hces1,99999999999) ")->get();


		$caracteristicasTmp = $fgasigl0->select('NUM_HCES1, LIN_HCES1, ID_CARACTERISTICAS, nvl(VALUE_CARACTERISTICAS_VALUE,VALUE_CARACTERISTICAS_HCES1 ) VALUE_CARACTERISTICAS')
							->LeftJoinCaracteristicasAsigl0()
							->where("COD_SUB", $codSub)
							->ActiveLotAsigl0()->get();
		$caracteristicas = array();

		foreach($caracteristicasTmp as $caracteristica){

			$numLin = $caracteristica->num_hces1 ."_". $caracteristica->lin_hces1;
			if(empty($caracteristicas[$numLin] )){
				$caracteristicas[$numLin] = array();
			}
			if($caracteristica->id_caracteristicas == 1){
				$caracteristicas[$numLin] [ $caracteristica->id_caracteristicas] = $galeriaArte->explodeComillas($caracteristica->value_caracteristicas ) ;

			}else{
				$caracteristicas[$numLin] [ $caracteristica->id_caracteristicas] = $caracteristica->value_caracteristicas;
			}
		}
		#buscamos los artistas de la exposición
		$idArtists = [];
		foreach($lots as $lot){
			if(empty($idArtists[$lot->idvalue_caracteristicas_hces1])){
				$idArtists[$lot->idvalue_caracteristicas_hces1]=$lot->idvalue_caracteristicas_hces1;
			}
		}

		$artists = [];

		if (count($idArtists) > 0){
			$web_artist = new WEB_ARTIST();
			$artists = $web_artist->select("NAME_ARTIST, ID_ARTIST")->LeftJoinLang()->wherein("WEB_ARTIST.ID_ARTIST", $idArtists)->get();

			if(\Config::get("app.ArtistNameSurname")){
				$artists =	 $galeriaArte->nameSurname($artists );
			}
		}

		$data["artists"] = $artists;
		$data["caracteristicas"] = $caracteristicas;
		$data['lots'] = $lots;
		$data['auction'] = $auction;



		$pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
		$pdf =$pdf->loadView('front::reports.expoArt', $data);



		return $pdf->download("Expo-".$codSub.".pdf");
	}

	public function generateBidsPdf()
	{

		$reportTitle = trans(\Config::get('app.theme') . '-app.reports.bid_report');
		$titleTable = trans(\Config::get('app.theme') . '-app.reports.bid_detail');

		$tableContent = [];

		if (empty($this->bids)) {
			$tableContent[] = [
				trans(\Config::get('app.theme') . '-app.reports.licit') => '',
				trans(\Config::get('app.theme') . '-app.reports.cli_name') => '',
				trans(\Config::get('app.theme') . '-app.reports.imp_asigl1') => '',
				trans(\Config::get('app.theme') . '-app.reports.bid_date') => ''
			];
		} else {
			foreach ($this->bids as $key => $bid) {
				$tableContent[] = [
					trans(\Config::get('app.theme') . '-app.reports.licit') => $bid->cod_licit . ' - ' . $this->licits[$bid->cod_licit]->cli_licit,
					trans(\Config::get('app.theme') . '-app.reports.cli_name') => substr($this->licits[$bid->cod_licit]->nom_cli, 0, 25),
					trans(\Config::get('app.theme') . '-app.reports.imp_asigl1') => Tools::moneyFormat($bid->imp_asigl1) . ' €',
					trans(\Config::get('app.theme') . '-app.reports.bid_date') => Tools::getDateFormat($bid->bid_date, 'Y-m-d H:i:s', 'd/m/Y H:i:s')
				];
			}
		}


		$this->addPdf($this->generateGenericPdf('front::reports.report1', $reportTitle, $this->tableInfo, $titleTable, $tableContent), $reportTitle);
	}

	public function generateClientsPdf()
	{

		$reportTitle = trans(\Config::get('app.theme') . '-app.reports.client_report');
		$titleTable = trans(\Config::get('app.theme') . '-app.reports.lot_detail');

		$reportBidderTitle = trans(\Config::get('app.theme') . '-app.reports.bidder_report');

		$collection = collect($this->bids);

		$bidsByLicit = $collection->mapToGroups(function ($item) {
			return [$item->cod_licit => $item];
		});

		foreach ($bidsByLicit as $cod_licit => $bids) {

			$tableContent = [];
			foreach ($bids as $bid) {

				$tableContent[] = [
					trans(\Config::get('app.theme') . '-app.reports.licit') => $bid->cod_licit . ' - ' . $this->licits[$bid->cod_licit]->cli_licit,
					trans(\Config::get('app.theme') . '-app.reports.cli_name') => substr($this->licits[$bid->cod_licit]->nom_cli, 0, 25),
					trans(\Config::get('app.theme') . '-app.reports.lot_code') => $bid->ref_asigl1,
					trans(\Config::get('app.theme') . '-app.reports.imp_asigl1') => Tools::moneyFormat($bid->imp_asigl1) . ' €',
					trans(\Config::get('app.theme') . '-app.reports.bid_date') => Tools::getDateFormat($bid->bid_date, 'Y-m-d H:i:s', 'd/m/Y H:i:s')
				];
			}

			$this->addPdf($this->generateGenericPdf('front::reports.report1', $reportTitle, $this->tableInfo, $titleTable, $tableContent), $reportTitle . "_$cod_licit");
			$this->addPdf($this->generateGenericPdf('front::reports.report1', $reportBidderTitle, $this->tableInfo, $titleTable, $tableContent), $reportBidderTitle . "_$cod_licit");
		}
	}

	public function generateAwardLotPdf($propetary, $ref_asigl0, $cod_licit, $import)
	{
		//'content_lot_award' => 'De la subasta de la Sociedad :prop <br> El lote nº :lot ha sido adjudicado provionalmente a la sociedad :award<br>Por un importe de :imp'
		$reportTitle = trans(\Config::get('app.theme') . '-app.reports.award_report');

		$bidders = [];
		if(config('app.withMultipleBidders', false)){

			$bids = is_array($this->bids) ? collect($this->bids) : $this->bids;
			$bid = $bids->where('imp_asigl1', $import)->where('cod_licit', $cod_licit)->first();

			$bidders = FgAsigl1Mt::query()->where([
				['sub_asigl1mt', $bid->cod_sub],
				['ref_asigl1mt', $bid->ref_asigl1],
				['lin_asigl1mt', $bid->lin_asigl1]
			])->get()->toArray();
		}

		$data = [
			'reportTitle' => $reportTitle,
			'prop' => $propetary,
			'lot' => $ref_asigl0,
			'award' => $this->licits[$cod_licit]->nom_cli,
			'imp' => Tools::moneyFormat($import),
			'bidders' => $bidders
		];


		$this->addPdf(PDF::loadView('front::reports.award_lot', $data), $reportTitle);
	}

	public function generateNotAwardLotPdf($propetary, $ref_asigl0)
	{

		//'content_lot_award' => 'De la subasta de la Sociedad :prop <br> El lote nº :lot ha sido adjudicado provionalmente a la sociedad :award<br>Por un importe de :imp'

		$reportTitle = trans(\Config::get('app.theme') . '-app.reports.award_report');
		$content = trans(\Config::get('app.theme') . '-app.reports.content_lot_notaward', [
			'prop' => $propetary,
			'lot' => $ref_asigl0
		]);

		$this->addPdf($this->generateGenericPdf('front::reports.report1', $reportTitle, $this->tableInfo, '', [], $content), $reportTitle);
	}

	public function generateAuctionBidsReportPdf($inf_subasta, $reportTitle)
	{

		//titulo de la tabla
		$titleTable = trans(\Config::get('app.theme') . '-app.reports.lots_detail');

		//pujas e info de licitador
		$subasta = new Subasta();
		$subasta->cod = $inf_subasta->cod_sub;
		$this->bids = $subasta->getPujasWithAuction();

		//obtenemos todas las referencias de la subasta, y de ahí la mas baja y la mas alta
		$referencias = $this->bids->pluck('ref_asigl1')->unique();

		$lotesSinPujas = FgAsigl0::select('ref_asigl0 as ref_asigl1')->where('sub_asigl0', $inf_subasta->cod_sub)->whereNotIn('ref_asigl0', $referencias)->orderBy('ref_asigl0')->get();
		$todos = $this->bids->concat($lotesSinPujas);

		$rangoLotes = $todos->count() > 1 ? $todos->min('ref_asigl1') . ' - ' . $todos->max('ref_asigl1') : $todos->min('ref_asigl1');

		//datos del propietario del primer lote #Esto esta muy personalizado para inbusa, si se utiliza en otro cliente se necesaitaran realizar modificaciones
		$owner = FgHces1::select('loteaparte_hces1')->getOwner()->where('SUB_HCES1', $inf_subasta->cod_sub)->first();

		//cabecera del pdf
		$tableInfo = [
			trans(\Config::get('app.theme') . '-app.reports.prop_hces1') => $owner->rsoc_cli ?? '',
			trans(\Config::get('app.theme') . '-app.reports.lote_aparte') => $owner->loteaparte_hces1 ?? '',
			trans(\Config::get('app.theme') . '-app.reports.auction_code') => $inf_subasta->cod_sub,
			trans(\Config::get('app.theme') . '-app.reports.lots_code') => $rangoLotes,
			trans(\Config::get('app.theme') . '-app.reports.date_start') => Tools::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'd/m/Y'),
			trans(\Config::get('app.theme') . '-app.reports.hour_start') => Tools::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'H:i:s'),
			trans(\Config::get('app.theme') . '-app.reports.date_end') => Tools::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'd/m/Y'),
			trans(\Config::get('app.theme') . '-app.reports.hour_end') => Tools::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'H:i:s'),
		];

		//generamos contanido de la tabla
		$tableContent = [];
		if (count($referencias) == 0) {
			$tableContent[] = [
				trans(\Config::get('app.theme') . '-app.reports.licit') => '',
				trans(\Config::get('app.theme') . '-app.reports.cli_name') => '',
				trans(\Config::get('app.theme') . '-app.reports.lot_name') => '',
				trans(\Config::get('app.theme') . '-app.reports.imp_asigl1') => '',
				trans(\Config::get('app.theme') . '-app.reports.bid_date') => ''
			];
		}

		foreach ($todos->sortBy('ref_asigl1') as $key => $bid) {

			if(empty($bid->licit_asigl1)){
				$tableContent[] = [
					trans(\Config::get('app.theme') . '-app.reports.lot_name') => $bid->ref_asigl1,
					'not_bids' => mb_strtoupper(trans(\Config::get('app.theme') . '-app.lot_list.no_bids'))
				];
				continue;
			}

			//nºpujador, sociedad (nom_cli), nº lote, cantidad €, fecha
			$tableContent[] = [
				trans(\Config::get('app.theme') . '-app.reports.lot_name') => $bid->ref_asigl1,
				trans(\Config::get('app.theme') . '-app.reports.licit') => $bid->cod_licit . ' - ' . $bid->cli_licit,
				trans(\Config::get('app.theme') . '-app.reports.cli_name') => substr($bid->nom_cli, 0, 25)/* $bid->nom_cli */,
				trans(\Config::get('app.theme') . '-app.reports.imp_asigl1') => Tools::moneyFormat($bid->imp_asigl1) . ' €',
				trans(\Config::get('app.theme') . '-app.reports.bid_date') => Tools::getDateFormat($bid->fec_asigl1, 'Y-m-d H:i:s', 'd/m/Y H:i:s')
			];
		}

		//guardamos pdf en la clase
		$this->addPdf($this->generateGenericPdf('front::reports.report1', $reportTitle, $tableInfo, $titleTable, $tableContent), $reportTitle);
	}

	public function generateAuctionAwardsReportPdf($inf_subasta, $reportTitle)
	{
		$theme = config('app.theme');
		//titulo de la tabla
		$titleTable = trans("$theme-app.reports.awards_detail");

		$adjudicaciones = $this->getAdjudicaciones($inf_subasta->cod_sub);

		//obtenemos todas las referencias de la subasta, y de ahí la mas baja y la mas alta
		$referenciasAdjudicadas = $adjudicaciones->pluck('ref')->unique();

		$lotesNoAdjudicados = FgAsigl0::select('ref_asigl0 as ref')->where('sub_asigl0', $inf_subasta->cod_sub)->whereNotIn('ref_asigl0', $referenciasAdjudicadas)->orderBy('ref_asigl0')->get();
		$todos = $adjudicaciones->concat($lotesNoAdjudicados);

		if (count($referenciasAdjudicadas) == 0) {
			$rangoLotes = '1';
		} else {
			$rangoLotes = $referenciasAdjudicadas->count() > 1 ? $todos->min('ref') . ' - ' . $todos->max('ref') : $referenciasAdjudicadas[0];
		}
		//datos del propietario del primer lote
		#Esto esta muy personalizado para inbusa, si se utiliza en otro cliente se necesaitaran realizar modificaciones
		$owner = FgHces1::select('loteaparte_hces1')->getOwner()->where('SUB_HCES1', $inf_subasta->cod_sub)->first();

		//cabecera del pdf
		$tableInfo = [
			trans("$theme-app.reports.prop_hces1") => $owner->rsoc_cli ?? '',
			trans("$theme-app.reports.lote_aparte") => $owner->loteaparte_hces1 ?? '',
			trans("$theme-app.reports.auction_code") => $inf_subasta->cod_sub,
			trans("$theme-app.reports.lots_code") => $rangoLotes ?? '',
			trans("$theme-app.reports.date_start") => Tools::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'd/m/Y'),
			trans("$theme-app.reports.hour_start") => Tools::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'H:i:s'),
			trans("$theme-app.reports.date_end") => Tools::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'd/m/Y'),
			trans("$theme-app.reports.hour_end") => Tools::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'H:i:s'),
		];

		$awards = [];
		foreach ($todos->sortBy('ref') as $adjudicacion) {

			$withMultipleBidders = config('app.withMultipleBidders', false);

			$award = [
				'ref' => $adjudicacion->ref,
				'is_award' => !empty($adjudicacion->licit_csub),
				'licit' => $adjudicacion->licit_csub . ' - ' . $adjudicacion->clifac_csub,
				'name' => substr($adjudicacion->nom_cli, 0, 25),
				'import' => Tools::moneyFormat($adjudicacion->himp_csub, '€'),
				'date' => Tools::getDateFormat($adjudicacion->fec_asigl1, 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
				'ratio' => $withMultipleBidders ? "100 %" : null
			];

			$hasMultiple = false;
			$multiple = [];

			if($withMultipleBidders){

				$multipleBidder = FgAsigl1Mt::query()->where([
					['sub_asigl1mt', $inf_subasta->cod_sub],
					['ref_asigl1mt', $adjudicacion->ref],
					['lin_asigl1mt', $adjudicacion->lin_asigl1]
				])->get();

				$hasMultiple = $multipleBidder->isNotEmpty();

				if($hasMultiple){

					foreach ($multipleBidder as $bidder) {
						$multiple[] = [
							'ref' => $adjudicacion->ref,
							'is_award' => !empty($adjudicacion->licit_csub),
							'licit' => $adjudicacion->licit_csub . ' - ' . $adjudicacion->clifac_csub,
							'name' => substr($bidder->nom_asigl1mt . ' ' . $bidder->apellido_asigl1mt, 0, 25),
							'import' => Tools::moneyFormat($adjudicacion->himp_csub * $bidder->ratio_asigl1mt / 100, '€'),
							'date' => Tools::getDateFormat($adjudicacion->fec_asigl1, 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
							'ratio' => $bidder->ratio_asigl1mt . ' %'
						];
					}
				}
			}

			if($hasMultiple) {
				array_push($awards, ...$multiple);
			}
			else{
				$awards[] = $award;
			}
		}

		$data = [
			'reportTitle' => $reportTitle,
			'tablaSubasta' => $tableInfo,
			'titleTable' => $titleTable,
			'awards' => $awards
		];

		$this->addPdf(PDF::loadView('front::reports.award', $data), $reportTitle);
	}

	public function generateWithNotAward($inf_subasta, $inf_lot)
	{

		if (!empty($inf_lot->prop_hces1)) {
			$propietary = FxCli::select('RSOC_CLI')->where('COD_CLI', $inf_lot->prop_hces1)->first();
		}

		$tableInfo = [
			trans(\Config::get('app.theme') . '-app.reports.prop_hces1') => $propietary->rsoc_cli ?? '',
			trans(\Config::get('app.theme') . '-app.reports.lote_aparte') => $inf_lot->loteaparte_hces1 ?? '',
			trans(\Config::get('app.theme') . '-app.reports.auction_code') => $inf_subasta->cod_sub,
			trans(\Config::get('app.theme') . '-app.reports.lot_code') => $inf_lot->ref_asigl0,
			trans(\Config::get('app.theme') . '-app.reports.date_start') => Tools::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'd/m/Y'),
			trans(\Config::get('app.theme') . '-app.reports.hour_start') => Tools::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'H:i:s'),
			trans(\Config::get('app.theme') . '-app.reports.date_end') => Tools::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'd/m/Y'),
			trans(\Config::get('app.theme') . '-app.reports.hour_end') => Tools::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'H:i:s'),
		];
		$this->setTableInfo($tableInfo);
		$this->generateBidsPdf();
		$this->generateNotAwardLotPdf($propietary->rsoc_cli, $inf_lot->ref_asigl0);
	}

	public function generateCertificateReportPdf($codSub)
	{
		$theme = config('app.theme');
		$awards = $this->getAdjudicaciones($codSub);

		if(!$awards){
			return;
		}

		$auto = FgHces1::select('loteaparte_hces1')->getOwner()->where('SUB_HCES1', $codSub)->first()->loteaparte_hces1 ?? "";
		$auctionName = FgSub::select('des_sub')->where('cod_sub', $codSub)->first()->des_sub ?? "";

		$date = now()->locale('es_ES')->isoFormat('D [de] MMMM [de] YYYY');

		$data = [
			'auctionName' => $auctionName,
			'auto' => $auto,
			'nowDate' => $date,
			'awards' => $awards
		];

		$this->addPdf(PDF::loadView('front::reports.certificate', $data), trans("$theme-app.reports.certificate_report"));
	}

	public function testPdf(string $view, string $reportTitle, array $tableInfo, string $titleTable, array $tableContent, string $content = '')
	{
		$data = [
			'reportTitle' => $reportTitle,
			'tablaSubasta' => $tableInfo,
			'titleTable' => $titleTable,
			'tableContent' => $tableContent,
			'content' => $content
		];

		return Pdf::loadView($view, $data)->stream('archivo.pdf');
	}

	public function generateGenericPdf(string $view, string $reportTitle, array $tableInfo, string $titleTable, array $tableContent, string $content = '')
	{

		$data = [
			'reportTitle' => $reportTitle,
			'tablaSubasta' => $tableInfo,
			'titleTable' => $titleTable,
			'tableContent' => $tableContent,
			'content' => $content
		];

		return Pdf::loadView($view, $data);
	}

	public function generateRawPdf(string $view, array $data)
	{
		return Pdf::loadView($view, $data);
	}

	public function setTableInfo(array $tableInfo)
	{
		$this->tableInfo = $tableInfo;
	}

	public function addPdf($pdf, string $name)
	{
		$this->pdfs[$name] = $pdf;
	}

	public function getPdfs($keys = [])
	{
		if (empty($keys)) {
			return $this->pdfs;
		}

		$pdfs = [];
		foreach ($this->pdfs as $key => $value) {
			if (in_array($key, $keys)) {
				$pdfs[$key] = $value;
			}
		}
		return $pdfs;
	}

	public function getPathsPdfs($keys = [])
	{

		if (empty($keys)) {
			return $this->pathPdfsSaved;
		}

		$pathPdfsSaved = [];
		foreach ($this->pathPdfsSaved as $key => $value) {
			if (in_array($key, $keys)) {
				$pathPdfsSaved[$key] = $value;
			}
		}
		return $pathPdfsSaved;
	}

	public function savePdfs($cod_sub, $ref = null)
	{

		$path = getcwd() . self::PUBLIC_PATH . Config::get('app.emp') . DIRECTORY_SEPARATOR . $cod_sub . DIRECTORY_SEPARATOR;

		if (!empty($ref)) {
			$path .= $ref . DIRECTORY_SEPARATOR;
		}

		if (!is_dir($path)) {
			@mkdir($path, 0775, true);
			chmod($path, 0775);
		}

		foreach ($this->pdfs as $key => $value) {
			$this->pathPdfsSaved[$key] = $path . '' . "$key.pdf";
			$value->save($path . '' . "$key.pdf");
		}
	}

	public function setBids($bids, $searchLicitsInfo = false)
	{

		if (empty($bids)) {
			return;
		}

		if ($searchLicitsInfo) {
			//creamos un array con los pujadores no adjudicados del lote
			$user = new User();
			$user->cod = $bids[0]->cod_sub;
			$licits = [];
			foreach ($bids as $bids_value) {

				//si no ha ganado nadie o el que gano noes el pujador actual  y el licitador n oes el dummy
				if ((Config::get('app.dummy_bidder') != $bids_value->cod_licit)) {
					$user->licit = $bids_value->cod_licit;
					$licits[$bids_value->cod_licit] = $user->getUserByLicit()[0];
					$isJuridic = FxCli::select('rsoc_cli')
						->where([
							['cod_cli', $bids_value->cod_licit],
							['fisjur_cli', 'J']
						])
						->first();
					if ($isJuridic) {
						$licits[$bids_value->cod_licit]->nom_cli = $isJuridic->rsoc_cli;
					}
				}
			}
			$this->licits = $licits;
		}

		$this->bids = $bids;
	}

	public function setLicits($licits, $cod_sub)
	{
		$user = new User();
		$user->cod = $cod_sub;
		foreach ($licits as $key => $value) {
			$user->licit = $value;
			$this->licits[$key] = $user->getUserByLicit()[0];
		}
	}

	private function getAdjudicaciones($codSub)
	{
		if(!empty($this->awards)){
			return $this->awards;
		}

		$this->awards = FgCsub::select('licit_csub', 'clifac_csub', 'nom_cli', 'cif_cli', 'ref_csub as ref', 'himp_csub', 'lin_asigl1', 'fec_asigl1')
			->joinWinnerBid()
			->joinCli()
			->where('SUB_CSUB', $codSub)
			->orderBy('ref_csub')
			->get();

		return $this->awards;
	}


	public function generateCompletLotReport($cod_sub, $ref, $name_file)
	{
		$data = $this->lotInfo($cod_sub, $ref);
		$this->addPdf($this->generateRawPdf('front::reports.complete_report', $data), $name_file);
	}

	public function lotInfo($cod_sub, $ref)
	{
		$lote = FgAsigl0::select('FXCLI.RSOC_CLI', 'auc."orders_start"', 'auc."orders_end"', 'FGHCES1.DESCWEB_HCES1', 'FGSUB.TIPO_SUB')
			->addSelect('FGASIGL0.SUB_ASIGL0', 'FGASIGL0.REF_ASIGL0', 'FGASIGL0.FINI_ASIGL0', 'FGASIGL0.HINI_ASIGL0', 'FGASIGL0.FFIN_ASIGL0', 'FGASIGL0.HFIN_ASIGL0')
			->joinFghces1Asigl0()
			->joinSessionAsigl0()
			->joinSubastaAsigl0()
			->join('FXCLI', 'FXCLI.COD_CLI = FGHCES1.PROP_HCES1 AND FXCLI.GEMP_CLI = ' . config('app.gemp'))
			->where([
				["SUB_ASIGL0", $cod_sub],
				["REF_ASIGL0", $ref]
			])
			->first();



		$pujas = FgAsigl1::select('licit_asigl1 as licit', 'imp_asigl1 as imp', 'fec_asigl1 as fehca', 'nom_cli', 'cod_cli')
			->where([
				["SUB_ASIGL1", $cod_sub],
				["REF_ASIGL1", $ref]
			])
			->joinCli()
			->orderBy('lin_asigl1', "desc")
			->get();

		$ordenes = FgOrlic::select('licit_orlic as licit', 'himp_orlic as imp', 'fec_orlic as fecha', 'hora_orlic', 'nom_cli', 'cod_cli')
			->where([
				["SUB_ORLIC", $cod_sub],
				["REF_ORLIC", $ref]
			])
			->joinCli()
			->orderBy('himp_orlic', "desc")
			->orderBy('fec_orlic', "asc")
			->get();

		$logOrdenesPujas = Web_Cancel_Log::select('id_licit as licit', 'fecha', 'imp', 'nom_cli', 'cod_cli')
			->joinCli()
			->where([
				['id_sub', $cod_sub],
				['lote', $ref]
			])
			->orderBy('fecha')
			->get();

		$clientesOrdenesValidas = $ordenes->pluck('cod_cli');
		$historicos = $ordenes->concat($logOrdenesPujas)->map(function($item) use ($clientesOrdenesValidas){
			$item->is_deleted = !$clientesOrdenesValidas->contains($item->cod_cli);
			return $item;
		});

		if($lote->tipo_sub != 'W'){
			$historicos = $historicos->concat($pujas);
		}
		$historicos = $historicos->sortByDesc('fecha');

		return compact('lote', 'pujas', 'ordenes', 'historicos');
	}



	public function testPdfWithData()
	{
		$lote = (object)[
			'prop_hces1' => '000001',
			'loteaparte_hces1' => 'test',
			'cod_sub' => 'test',
			'ref_asigl0' => '5000'
		];

		$subasta = (object)[
			'start' => '2021-01-01 09:00:00',
			'end' => '2021-01-01 09:00:00',
			'cod_sub' => 'test'
		];

		$bid = (object) [
			'cod_licit' => 1234,
			'imp_asigl1' => 5000000,
			'bid_date' => '2021-06-05 13:00:00'
		];
		$bids = array_fill(0, 20, $bid);

		$text60 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit sit.';
		$text40 = 'Lorem ipsum dolor sit amet orci aliquam.';

		$licit = (object) ['cli_licit' => 123456, 'nom_cli' => $text40];
		$licits = [1234 => $licit];

		$this->licits = $licits;
		$this->setBids($bids, false);
		$this->generateWithNotAward($subasta, $lote);
		$this->savePdfs($subasta->cod_sub, $lote->ref_asigl0);

		echo 'Created';
	}
}
