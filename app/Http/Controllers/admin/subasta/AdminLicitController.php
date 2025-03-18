<?php

namespace App\Http\Controllers\admin\subasta;

use App\Exports\CollectionExport;
use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgLicit;
use App\Models\V5\FgSub;
use App\Models\V5\FxCli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class AdminLicitController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'subastas']);
	}

	function index(Request $request)
	{
		$auctionsPluck = FgSub::query()
			->where('SUBC_SUB', '<>', 'N')
			->orderBy('dfec_sub', 'desc')
			->pluck('des_sub', 'cod_sub');

		$licits = FgLicit::query()
			->select('sub_licit', 'cod_licit', 'cod2_cli', 'rsoc_licit', 'cli_licit')
			->joinCli()
			->when(
				$request->input('auction'),
				function ($query) use ($request) {
					$query->where('sub_licit', $request->input('auction'));
				},
				function ($query) use ($auctionsPluck) {
					$query->where('sub_licit', $auctionsPluck->keys()->first());
				}
			)
			->orderBy('cod_licit', 'asc')
			->get();

		$auctionSelected = $request->input('auction', $auctionsPluck->keys()->first());

		$data = [
			'licits' => $licits,
			'auctions' => $auctionsPluck,
			'auctionSelected' => $auctionSelected
		];

		return View::make('admin::pages.subasta.licitadores.index', $data);
	}

	/**
	 * Mostrar formulario para crear uno nuevo
	 * */
	function create(Request $request)
	{
		$auction = $request->input('auction');

		$actualMaxLicit = FgLicit::newCodLicit($auction);

		$formulario = [
			//'subasta' => FormLib::Select('auction', 1, $auction, $auctionsPluck),
			'subasta' => FormLib::TextReadOnly('idauction', 1, $auction),
			'cliente' => FormLib::Select2WithAjax('cod_cli', 0, '', '', route('client.list'), trans_choice('admin-app.title.client', 1)),
			'cod_licit' => FormLib::Text("cod_licit", 1, $actualMaxLicit, 'required'),
		];

		$data = [
			'formulario' => $formulario,
		];

		return View::make('admin::pages.subasta.licitadores.edit', $data);
	}

	function store(Request $request)
	{
		$request->validate([
			'cod_cli' => 'required',
			'idauction' => 'required',
			'cod_licit' => 'required',
		]);

		$licitTemp = FgLicit::query()
			->select('cod_licit', 'rsoc_cli', 'cli_licit')
			->joinCli()
			->where("sub_licit", $request->input('idauction'))
			->where('cli_licit', $request->input('cod_cli'))
			->first();

		if ($licitTemp) {
			return redirect()->back()
				->with(['errors' => [0 => 'El cliente ya tiene numero de licitador para esta subasta']]);
		}

		$existCodLicit = FgLicit::query()
			->where([
				['sub_licit', $request->input('idauction')],
				['cod_licit', $request->input('cod_licit')]
			])
			->exists();

		if ($existCodLicit) {
			return redirect()->back()
				->with(['errors' => [0 => 'No se puede crear, la paleta ya esta en uso']]);
		}

		$client = FxCli::query()
			->select('rsoc_cli', 'nom_cli', 'fisjur_cli')
			->where('cod_cli', $request->input('cod_cli'))
			->first();

		$clientName = $client->fisjur_cli == FxCli::TIPO_FISJUR_JURIDICA
			? $client->rsoc_cli ?? $client->nom_cli
			: $client->nom_cli;

		FgLicit::create([
			'sub_licit' => $request->input('idauction'),
			'cli_licit' => $request->input('cod_cli'),
			'cod_licit' => $request->input('cod_licit'),
			'rsoc_licit' => $clientName,
		]);

		return redirect()->back()
			->with(['success' => [0 => 'Licitador creado correctamente']]);
	}


	/**
	 * Eliminar item
	 * */
	function destroy() {}

	function exportLicits(Request $request)
	{
		$idAuction = $request->input('auction');

		$defautlAuction = FgSub::query()
			->where('SUBC_SUB', '<>', 'N')
			->orderBy('dfec_sub', 'desc')
			->value('cod_sub');

		$licits = FgLicit::query()
			->select('sub_licit', 'cod_licit', 'cod2_cli', 'rsoc_licit', 'cli_licit')
			->joinCli()
			->when(
				$idAuction,
				function ($query) use ($idAuction) {
					$query->where('sub_licit', $idAuction);
				},
				function ($query) use ($defautlAuction) {
					$query->where('sub_licit', $defautlAuction->cod_sub);
				}
			)
			->orderBy('cod_licit', 'asc')
			->get();

		if (empty($licits)) {
			return redirect()->back()->with(['errors' => [0 => 'No hay datos para exportar']]);
		}

		$fileName = "licitadores_$idAuction" . "_" . date('d-m-Y_H-i-s');

		return Excel::download(new CollectionExport($licits), "$fileName.xlsx");
	}
}
