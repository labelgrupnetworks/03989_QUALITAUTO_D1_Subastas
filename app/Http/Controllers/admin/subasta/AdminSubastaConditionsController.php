<?php

namespace App\Http\Controllers\admin\subasta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgSubConditions;
use App\Providers\ToolsServiceProvider;

class AdminSubastaConditionsController extends Controller
{
	function __construct()
	{
		view()->share(['menu' => 'subastas']);
	}

	private function getCollectionQuery(Request $request)
	{
		return FgSubConditions::query()
			->with(['client:rsoc_cli,cod_cli', 'auction:des_sub,cod_sub'])
			->when($request->get('id_subconditions'), function ($query) use ($request) {
				return $query->where('id_subconditions', $request->id_subconditions);
			})
			->when($request->get('cli_subconditions'), function ($query) use ($request) {
				return $query->where('cli_subconditions', $request->cli_subconditions);
			})
			->when($request->get('emp_subconditions'), function ($query) use ($request) {
				return $query->where('emp_subconditions', $request->emp_subconditions);
			})
			->when($request->get('cod_subconditions'), function ($query) use ($request) {
				return $query->where('cod_subconditions', $request->cod_subconditions);
			})
			->when($request->get('from_fechacreacion_subconditions'), function ($query) use ($request) {
				return $query->where('fechacreacion_subconditions', '>=', $request->from_fechacreacion_subconditions);
			})
			->when($request->get('to_fechacreacion_subconditions'), function ($query) use ($request) {
				return $query->where('fechacreacion_subconditions', '<=', $request->to_fechacreacion_subconditions);
			})
			->when($request->get('des_sub'), function ($query) use ($request) {
				return $query->whereHas('auction', function ($query) use ($request) {
					return $query->where('lower(des_sub)', 'like', '%' . mb_strtolower($request->des_sub) . '%');
				});
			})
			->when($request->get('rsoc_cli'), function ($query) use ($request) {
				return $query->whereHas('client', function ($query) use ($request) {
					return $query->where('lower(rsoc_cli)', 'like', '%' . mb_strtolower($request->rsoc_cli) . '%');
				});
			})
			->orderBy($request->get('order_sub_conditions', 'fechacreacion_subconditions'), $request->get('order_type_sub_conditions', 'desc'));
	}

	function index(Request $request)
	{
		$subConditions = $this->getCollectionQuery($request)->paginate(30);

		$tableFilters = (object)[
			'id_subconditions' => FormLib::text("id_subconditions", 0, $request->id_subconditions),
			'cod_subconditions' => FormLib::text("cod_subconditions", 0, $request->cod_subconditions),
			'des_sub' => FormLib::text('des_sub', 0, $request->des_sub),
			'cli_subconditions' => FormLib::text('cli_subconditions', 0, $request->cli_subconditions),
			'rsoc_cli' => FormLib::text("rsoc_cli", 0, $request->cod_subconditions),
			'from_fechacreacion_subconditions' => FormLib::Date('from_fechacreacion_subconditions', 0, $request->from_fechacreacion_subconditions),
			'to_fechacreacion_subconditions' => FormLib::Date('to_fechacreacion_subconditions', 0, $request->to_fechacreacion_subconditions),
		];

		$tableParams = [
			'id_subconditions' => 1,
			'cod_subconditions' => 1,
			'des_sub' => 1,
			'cli_subconditions' => 1,
			'rsoc_cli' => 1,
			'from_fechacreacion_subconditions' => 1,
			'to_fechacreacion_subconditions' => 1,
		];

		return view("admin::pages.subasta.sub_conditions.index", compact('subConditions', 'tableFilters', 'tableParams'));
	}

	function download(Request $request)
	{
		$subConditions = $this->getCollectionQuery($request)->get()->map(function ($item) {
			return [
				trans('admin-app.fields.id_subconditions') => $item->id_subconditions,
				trans('admin-app.fields.cod_subconditions') => $item->cod_subconditions,
				trans('admin-app.fields.des_sub') => $item->auction->des_sub,
				trans('admin-app.fields.cli_subconditions') => $item->cli_subconditions,
				trans('admin-app.fields.rsoc_cli') => $item->client->rsoc_cli,
				trans('admin-app.fields.fechacreacion_subconditions') => $item->fechacreacion_subconditions
			];
		});

		return ToolsServiceProvider::exportCollectionToExcel($subConditions, 'subasta_condiciones_' . now()->format('d-m-Y-Hi') . '.xlsx');
	}
}
