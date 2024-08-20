<?php

namespace App\Http\Controllers;

use App\Models\Subasta;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

/**
 * @deprecated - No veo que se utilice en ningún lugar - 16/08/2024
 * @see - La vista si que la utiliza Sala Retiro, pero sin pasar por aquí
 */
class FormsController extends Controller
{

	public $emp;
	public $gemp;

	public function __construct()
	{
		$this->emp = Config::get('app.emp');
		$this->gemp = Config::get('app.gemp');
	}

	public function index($cod_sub, $ref)
	{
		$subasta = new Subasta();
		$name = trans(Config::get('app.theme') . '-app.foot.consult_lot');
		$subasta->cod = $cod_sub;
		$subasta->lote = $ref;

		$inf_lot = head($subasta->getLote());
		if (!empty($inf_lot)) {
			$inf_lot->imagen = $subasta->getLoteImg($inf_lot);
		} else {
			exit(View::make('front::errors.404'));
		}
		$data = array(
			'lot' => $inf_lot,
			'name' => $name
		);
		return View::make('front::pages.consult_lot', array('data' => $data));
	}
}
