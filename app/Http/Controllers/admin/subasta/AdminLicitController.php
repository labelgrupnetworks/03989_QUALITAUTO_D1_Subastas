<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\Http\Controllers\apilabel\AwardController;
use App\Http\Controllers\apilabel\LotController;
use App\Http\Controllers\apilabel\OrderController;
use App\libs\FormLib;
use App\Models\V5\FgLicit;
use App\Models\V5\FgSub;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider as Tools;
use Illuminate\Support\Facades\Config;

class AdminLicitController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'subastas']);
	}

	/**
	 * Mostrar página incial
	 * */
	function index($isRender = false, $idauction = null){

		/**Formulario de busqueda */

		$data['formulario'] = array();
		if(!empty($idauction)){
			$data['formulario']['subasta'] = FormLib::TextReadOnly("idauction", 1, $idauction);
			$data['formularioCreate'] = "/admin/licit/create?idauction=$idauction";
		}
		else{
			$adminSubastaController = new SubastaController();
			$subastas = $adminSubastaController->getSelectSubastas(false);

			$list = array();
			foreach($subastas as $subasta){
				$list[$subasta->id] = "$subasta->id - $subasta->html";
			}

			$data['formulario']['subasta'] = FormLib::Select("SUB_LICIT", 1, '',$list);
			$data['formularioCreate'] = "/admin/licit/create";
		}

		$data['formulario']['SUBMIT'] = FormLib::Submit('Buscar', 'whereLicits');

		if($isRender){
			return \View::make('admin::pages.subasta.licitadores.table', $data)->render();
		}
		return \View::make('admin::pages.subasta.licitadores.index', $data);
	}

	/**
	 * Mostrar formulario para crear uno nuevo
	 * */
	function create(){

		$idauction = request('idauction');
		/**Formulario de creación */
		$data['formularioId'] = "createLicits";
		$data['formularioAction'] = "/admin/licit/store";

		$data['formulario'] = array();

		if(!empty($idauction)){
			$data['formulario']['subasta'] = FormLib::TextReadOnly("idauction", 1, $idauction);
			$data['formularioCreate'] = "/admin/licit/create?idauction=$idauction";
		}
		else{
			$adminSubastaController = new SubastaController();
			$subastas = $adminSubastaController->getSelectSubastas(false);

			$list = array();
			foreach($subastas as $subasta){
				$list[$subasta->id] = "$subasta->id - $subasta->html";
			}

			$data['formulario']['subasta'] = FormLib::Select("idauction", 1, '',$list);
			$data['formulario']['SUBMIT'] = FormLib::Submit('Guardar', 'createLicits');
			$data['formularioCreate'] = "/admin/licit/create";
		}

		$data['formulario']['cliente'] = FormLib::Select2WithAjax('cod_cli', 0, '', '', route('client.list'), trans_choice('admin-app.title.client', 1));//FormLib::Select2("cod_cli", 1, '');

		$data['formulario']['nombre'] = FormLib::Text("username");
		$data['formulario']['nLicitador'] = FormLib::Text("nLicitador");

		return \View::make('admin::pages.subasta.licitadores.edit', $data);
	}

	/**
	 * Mostrar item
	 * */
	function show(){

		$idauction = request('SUB_LICIT');
		if(empty($idauction)){
			return response('Error', 400);
		}

		$licits = FgLicit::select('sub_licit', 'cod_licit', 'cod2_cli', 'rsoc_cli', 'cli_licit')
							->joinCli()
							->where("SUB_LICIT", $idauction)
							->get();


		return response($licits, 200);

	}

	/**
	 * Formulario con item
	 * */
	function edit(){}

	/**
	 * Guardar con item
	 * */
	function store(){

		$idAuction = request('idauction');
		$cod_cli = request('cod_cli');
		$cod_licit = request('nLicitador');
		$username = request('username');

		$licitTemp = FgLicit::select('cod_licit', 'rsoc_cli', 'cli_licit')
							->joinCli()
							->where("SUB_LICIT", $idAuction)
							->where('CLI_LICIT', $cod_cli)
							->first();

		if((!$licitTemp) || (!empty($cod_licit))){

			if(empty($cod_licit)){//comprovem si la pelata del formulari esta buida si ho esta li donem una
				$cod_licit = FgLicit::select("max(cod_licit) max_cod_licit")->joinCli()
					->where("sub_licit",$idAuction)
					->where("cod_licit", "<", Config::get('app.subalia_min_licit'))
					->first()->max_cod_licit +1;
			}else{
				$exist_licit = FgLicit::select("cod_licit")->joinCli()->where("sub_licit",$idAuction )->where("cod_licit",$cod_licit)->first();

				if($exist_licit || ($cod_licit==Config::get('app.dummy_bidder'))){
					return redirect()->back()
					->with(['errors' => [0 => 'No se puede crear, ya esta en uso'] ]);
				}
			}
			if(empty($username)){
				$username = FxCli::SelectBasicCli()->where('COD_CLI', $cod_cli)->first()->rsoc_cli;
			}

			$licit = array(
					"sub_licit" => $idAuction,
					"cli_licit" => $cod_cli,
					"cod_licit" => $cod_licit,
					"rsoc_licit" => $username,
				);

			$licitTemp = FgLicit::create($licit);

			return redirect()->back()
			->with(['success' => [0 => 'Licitador creado correctamente'] ]);
		}
		else{
			return redirect()->back()
			->with(['errors' => [0 => 'El cliente ya tiene numero de licitador para esta subasta']]);
		}

	}

	/**
	 * Actualizar item
	 * */
	function update(){}

	/**
	 * Eliminar item
	 * */
	function destroy(){}

}
