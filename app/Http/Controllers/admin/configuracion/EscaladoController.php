<?php

namespace App\Http\Controllers\admin\configuracion;

use Illuminate\Support\Facades\Request as Input;
use App\libs\FormLib;
use App\Http\Controllers\Controller;
use App\Models\V5\FgPujas;
use Illuminate\Support\Facades\Config;

class EscaladoController extends Controller
{
	public function index()
	{

		$data = array();
		$data['escalado'] = array();

		$fgPujas = FgPujas::where("EMP_PUJAS", Config::get('app.emp'))->get();

		foreach ($fgPujas as $key => $item) {

			$data['escalado'][$key] = [
				'importe' => FormLib::Float("importe[]", 0, $item->imp_pujas),
				'puja' => FormLib::Float("puja[]", 0, $item->puja_pujas)
			];
		}

		return \View::make('admin::pages.configuracion.escalado.index', $data);
	}

	public function save()
	{

		$info = Input::all();

		FGPUJAS::where("emp_pujas", \Config::get("app.emp"))->delete();

		foreach ($info['importe'] as $key => $importe) {

			if (!empty($importe) && !empty($info['puja'][$key])) {

				FGPUJAS::insert([
					"emp_pujas" => \Config::get("app.emp"),
					"imp_pujas" => $importe,
					"puja_pujas" => $info['puja'][$key]
				]);
			}
		}

		$data = array("return" => "/admin/escalado");

		return \View::make('admin::pages.text', $data);
	}
}
