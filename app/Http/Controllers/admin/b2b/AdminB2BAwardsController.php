<?php

namespace App\Http\Controllers\admin\b2b;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgSub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminB2BAwardsController extends Controller
{
	public function index(Request $request)
	{
		$ownerCod = Session::get('user.cod');
		$auctionsCods = FgSub::query()
			->where('agrsub_sub', $ownerCod)
			->pluck('cod_sub');

		$awards = FgAsigl0::query()
			->select([
				'sub_asigl0',
				'ref_asigl0',
				'descweb_hces1',
				'himp_csub',
				//'base_csub',
				'impsalhces_asigl0',
				'fecha_csub',
				'nom_cli',
				'email_cli',
				'cod_cli'
			])
			->joinFghces1Asigl0()
			->joinCSubAsigl0()
			->leftJoinCliWithCsub()
			->leftjoin('FXCLID', "FXCLID.GEMP_CLID = FXCLI.GEMP_CLI AND FXCLID.CLI_CLID = FXCLI.COD_CLI AND CODD_CLID = 'W1'")
			->whereNotNull('clifac_csub')
			->whereIn('sub_asigl0', $auctionsCods)
			->orderBy($request->input('order_awards') ?? 'sub_asigl0', $request->input('order_awards_dir', 'desc'))
			->paginate(20);

		$formulario = (object)[
			'sub_asigl0' => !empty($idauction) ? FormLib::TextReadOnly('sub_asigl0', 0, $idauction) : FormLib::text('sub_asigl0', 0, $request->sub_asigl0 ?? ''),
			'ref_asigl0' => FormLib::text("ref_asigl0", 0, $request->ref_asigl0),
			'descweb_hces1' => FormLib::text("descweb_hces1", 0, $request->descweb_hces1),
			'impsalhces_asigl0' => FormLib::text('impsalhces_asigl0', 0, $request->impsalhces_asigl0),
			'himp_csub' => FormLib::text("himp_csub", 0, $request->himp_csub),
			//'base_csub' => FormLib::text('base_csub', 0, $request->base_csub),
			'nom_cli' => FormLib::text("nom_cli", 0, $request->nom_cli),
			'email_cli' => FormLib::text("email_cli", 0, $request->email_cli),
			'cod_cli' => FormLib::text("cod_cli", 0, $request->cod_cli),
			'afral_csub' => FormLib::text("afral_csub", 0, $request->afral_csub),
		];

		$tableParams = [
			'sub_asigl0' => 1,
			'ref_asigl0' => 1,
			'descweb_hces1' => 1,
			'impsalhces_asigl0' => 1,
			'himp_csub' => 1,
			//'base_csub' => 1,
			'fecha_csub' => 1,
			'nom_cli' => 1,
			'email_cli' => 1,
			'cod_cli' => 1,
		];

		$numberOfColumns = count(array_filter($tableParams));

		$data = [
			'awards' => $awards,
			'formulario' => $formulario,
			'tableParams' => $tableParams,
			'numberOfColumns' => $numberOfColumns,
		];

		return view('admin::pages.b2b.awards.index', $data);
	}
}
