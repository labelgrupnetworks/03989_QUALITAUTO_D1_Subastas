<?php

namespace App\Http\Controllers\admin\subasta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PdfController;
use App\libs\FormLib;
use App\Models\Subasta;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgSub;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Config;
use ZipArchive;

class AdminAuctionReportsController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'subastas']);
	}

	public function index(Request $request)
	{
		$auctions = FgSub::query()
			->select('cod_sub', 'des_sub')
			->when($request->cod_sub, function ($query, $cod_sub) {
				return $query->where('upper(cod_sub)', 'like', "%" . mb_strtoupper($cod_sub) . "%");
			})
			->when($request->des_sub, function ($query, $des_sub) {
				return $query->where('upper(des_sub)', 'like', "%" . mb_strtoupper($des_sub) . "%");
			})
			->where('tipo_sub', Fgsub::TIPO_SUB_ONLINE)
			->whereExists(function ($query) {
				$query->select('sub_asigl0')
					->from('fgasigl0')
					->whereRaw('sub_asigl0 = cod_sub')
					->where('cerrado_asigl0', 'S');
			})
			->orderBy($request->input('order', 'dfec_sub'), $request->input('order_dir', 'desc'))
			->paginate(30);


		$filters = (object)[
			'cod_sub' => FormLib::Text('cod_sub', 0, $request->cod_sub),
			'des_sub' => FormLib::Text('des_sub', 0, $request->des_sub)
		];

		return view('admin::pages.subasta.informes.index', ['auctions' => $auctions, 'filters' => $filters]);
	}

	public function generate(Request $request)
	{
		$cod_sub = $request->cod_sub;
		$lots = FgAsigl0::select('ref_asigl0')->where('sub_asigl0', $cod_sub)->pluck('ref_asigl0');
		$this->recreatePdfReports($cod_sub);
		foreach ($lots as $lot) {
			$this->recreatePdfLotReports($cod_sub, $lot);
		}

		return response()->json(['message' => 'Pdf created successfully.']);
	}

	public function download($cod_sub)
	{
		$emp = Config::get('app.emp');
		$zip = new ZipArchive();
		$zip_name = 'informes_subasta_' . $cod_sub . '.zip';
		$zip->open($zip_name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		$rootPath = public_path("reports/$emp/$cod_sub");

		$files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($rootPath),
			\RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($files as $name => $file) {
			if (!$file->isDir()) {
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);

				$zip->addFile($filePath, $relativePath);
			}
		}

		$zip->close();

		return response()->download($zip_name)->deleteFileAfterSend(true);
	}

	public function recreatePdfReports($cod_sub)
	{
		$subasta = new Subasta();
		$subasta->cod = $cod_sub;
		$subasta->page = 'all';
		$info = $subasta->getInfSubasta();

		$pdfController = new PdfController();

		$reportTitleBidsReport = trans(Config::get('app.theme') . '-app.reports.lots_report');
		$reportTitleAwardsReport = trans(Config::get('app.theme') . '-app.reports.awards_report');

		$pdfController->generateAuctionAwardsReportPdf($info, $reportTitleAwardsReport);
		$pdfController->generateAuctionBidsReportPdf($info, $reportTitleBidsReport);
		if (config('app.certificate_in_report', false)) {
			$pdfController->generateCertificateReportPdf($cod_sub);
		}

		//generamos y guardamos archivos
		$pdfController->savePdfs($info->cod_sub, null);
	}

	public function recreatePdfLotReports($cod_sub, $ref)
	{
		$subasta = new Subasta();
		$subasta->ref = $ref;
		$subasta->cod = $cod_sub;
		$subasta->lote = $ref;
		$subasta->page = 'all';

		$id_auc_sessions = $subasta->getIdAucSessionslote($subasta->cod, $subasta->ref);
		$get_pujas = $subasta->getPujas(false, $cod_sub);
		$inf_lot = head($subasta->getLote());
		$inf_lot->id_auc_sessions  = $id_auc_sessions;
		$inf_subasta = $subasta->getInfSubasta();
		$adjudicado = $subasta->get_csub(config('app.emp'));

		$pdfController = new PdfController();
		$propietary = null;

		if (!empty($inf_lot->prop_hces1)) {
			$propietary = FxCli::select('RSOC_CLI')->where('COD_CLI', $inf_lot->prop_hces1)->first();
		}

		$tableInfo = [
			trans(Config::get('app.theme') . '-app.reports.prop_hces1') => $propietary->rsoc_cli ?? 'No indicado',
			trans(Config::get('app.theme') . '-app.reports.lote_aparte') => $inf_lot->loteaparte_hces1 ?? '',
			trans(Config::get('app.theme') . '-app.reports.auction_code') => $inf_subasta->cod_sub,
			trans(Config::get('app.theme') . '-app.reports.lot_code') => $inf_lot->ref_asigl0,
			trans(Config::get('app.theme') . '-app.reports.date_start') => ToolsServiceProvider::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'd/m/Y'),
			trans(Config::get('app.theme') . '-app.reports.hour_start') => ToolsServiceProvider::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'H:i:s'),
			trans(Config::get('app.theme') . '-app.reports.date_end') => ToolsServiceProvider::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'd/m/Y'),
			trans(Config::get('app.theme') . '-app.reports.hour_end') => ToolsServiceProvider::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'H:i:s'),
		];

		$pdfController->setTableInfo($tableInfo);
		$pdfController->addBids($cod_sub, $ref);

		$pdfController->generateBidsPdf();
		$pdfController->generateClientsPdf();

		if(empty($adjudicado->licit_csub) && empty($adjudicado->himp_csub)){
			$pdfController->generateNotAwardLotPdf($propietary, $inf_lot->ref_asigl0);
		}
		else {
			$pdfController->generateAwardLotPdf($propietary->rsoc_cli ?? null, $inf_lot->ref_asigl0, $adjudicado->licit_csub, $adjudicado->himp_csub);
		}

		$pdfController->savePdfs($inf_subasta->cod_sub, $inf_lot->ref_asigl0);
	}

}
