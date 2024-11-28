<?php

namespace App\Http\Controllers\admin\b2b;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminB2BLotsController extends Controller
{
	public function index(Request $request)
	{
		$userCod = Session::get('user.cod');

		$lots = FgAsigl0::query()
			->joinFghces1Asigl0()
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->where('FGSUB.AGRSUB_SUB', $userCod)
			->paginate(20);

		$tableParams = [
			'ref_asigl0' => 1,
			'impsalhces_asigl0' => 1,
			'cerrado_asigl0' => 1,
			'impres_asigl0' => 1,
		];

		$formulario = (object)[
			'ref_asigl0' => FormLib::Text('ref_asigl0', 0, $request->ref_asigl0),
			'impsalhces_asigl0' => FormLib::Text('impsalhces_asigl0', 0, $request->impsalhces_asigl0),
			'cerrado_asigl0' => FormLib::Select('cerrado_asigl0', 0, $request->cerrado_asigl0, ['N' => 'No', 'S' => 'Si']),
			'impres_asigl0' => FormLib::Text('impres_asigl0', 0, $request->impres_asigl0),
		];

		$data = [
			'lots' => $lots,
			'tableParams' => $tableParams,
			'formulario' => $formulario,
		];

		return view('admin::pages.b2b.lots.index', $data);
	}
}
