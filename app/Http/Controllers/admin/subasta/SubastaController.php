<?php

namespace App\Http\Controllers\admin\subasta;

use App\Exports\PujasExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\apilabel\LotController;
use App\Http\Controllers\apilabel\ImgController;

use Input;

use App\libs\FormLib;
use App\libs\Currency;
use App\libs\LoadLotFileLib;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelImport;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgHces1;
use App\Models\V5\FgSub;
use App\Models\V5\FgLicit;
use App\Models\V5\FxCli;
use App\Models\V5\FxSec;
use App\Models\V5\Web_Images_Size;
use Illuminate\Support\Facades\Config;
use App\libs\ImageGenerate;
use DateTime;

use App\Http\Controllers\admin\subasta\AdminAwardController;
use App\Http\Controllers\admin\subasta\AdminOrderController;
use App\Models\V5\AucSessions;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgAsigl1_Aux;
use App\Models\V5\FgCaracteristicas;
use App\Models\V5\FgCaracteristicas_Value;
use App\Models\V5\FgCsub;
use App\Models\V5\FgHces0;
use DateInterval;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SubastaController extends Controller
{

	public $fgCaracteristicas = null;

	public function index()
	{
		$data = array('menu' => 4);

		$subastas = DB::table("FgSub")->where("EMP_SUB", \Config::get("app.emp"))->orderBy("dfec_sub", "desc")->get();

		$data['subastas'] = $subastas;

		return \View::make('admin::pages.subasta.subasta.index', $data);
	}


	function edit($id = 0)
	{

		$data = array("id" => $id, 'menu' => 4);
		$language_complete = \Config::get("app.language_complete");

		if (!empty($id)) {

			$info = DB::table("fgsub")->where("COD_SUB", $id)->where("EMP_SUB", Config::get("app.emp"))->first();
			foreach (\Config::get("app.locales") as $k => $lang) {
				$info_{$k} = DB::table("FGSUB_LANG")->where("COD_SUB_LANG", $id)->where("LANG_SUB_LANG", $language_complete[mb_strtolower($k)])->where("EMP_SUB_LANG", \Config::get("app.emp"))->first();
			}
			$info_ES = $info;
		}


		// Construimos los campos de formulario

		$data['formulario'] = array();

		$data['formulario']['id'] = FormLib::Hidden("id", 1, $id);

		if (empty($id)) {
			$data['formulario']['codigo'] = FormLib::Text("COD_SUB", 1, '', 'maxlength="7"', 'Máximo 7 caracteres');
		} else {

			$data['formulario']['codigo'] = FormLib::ReadOnly("COD_SUB", 1, $id);
		}


		// Primera pestaña
		$subastasContratadas = explode(",", Config::get('app.admin_active_auctions', 'W,WP,O,V'));

		$tipos = [
			'W' => "Presencial",
			'WP' => "Presencial abierta con pujas",
			//'WO' => "Presencial abierta con ordenes",
			'O' => "Online",
			'V' => "Venta directa",
			'E' => "Especial",
			'P' => "Permanente",
		];

		$tiposActivos = [];
		foreach ($subastasContratadas as $value) {
			$tiposActivos[$value] = $tipos[$value];
		}

		$tipos_estado = [
			"S" => "Activo",
			"A" => "Activo administrador",
			"H" => "Histórico",
			"N" => "Inactivo",
			"C" => "Cerrado"
		];

		$estado_subalia = [
			"X" => "No se subirá a subalia",
			"N" => "No subido",
			"S" => "Subiendose",
			"A" => "Activo en subalia"
		];

		$tipoSub = '';
		if (!empty($info) && (empty($info->subabierta_sub) || $info->subabierta_sub == 'N')){
			$tipoSub = $info->tipo_sub;
		}
		else if(!empty($info)){
			$tipoSub = $info->tipo_sub.''.$info->subabierta_sub;
		}

		$data['formularioGeneral']['estado'] = FormLib::Select("SUBC_SUB", 1, isset($info->subc_sub) ? $info->subc_sub : '', $tipos_estado);
		$data['formularioGeneral']['tipo'] = FormLib::Select("TIPO_SUB", 1, !empty($tipoSub) ? $tipoSub : '', $tiposActivos);
		$data['formularioGeneral']['desde'] = FormLib::Date("DFEC_SUB", 1, isset($info->dfec_sub) ? $info->dfec_sub : '');
		$data['formularioGeneral']['hora desde'] = FormLib::Hour("DHORA_SUB", 1, isset($info->dhora_sub) ? $info->dhora_sub : '');
		$data['formularioGeneral']['hasta'] = FormLib::Date("HFEC_SUB", 1, isset($info->hfec_sub) ? $info->hfec_sub : '');
		$data['formularioGeneral']['hora hasta'] = FormLib::Hour("HHORA_SUB", 1, isset($info->hhora_sub) ? $info->hhora_sub : '');
		$data['formularioGeneral']['ordenes desde'] = FormLib::Date("DFECORLIC_SUB", 1, isset($info->dfecorlic_sub) ? $info->dfecorlic_sub : '');
		$data['formularioGeneral']['hora ordenes desde'] = FormLib::Hour("DHORAORLIC_SUB", 1, isset($info->dhoraorlic_sub) ? $info->dhoraorlic_sub : '');
		$data['formularioGeneral']['ordenes hasta'] = FormLib::Date("HFECORLIC_SUB", 1, isset($info->hfecorlic_sub) ? $info->hfecorlic_sub : '');
		$data['formularioGeneral']['hora ordenes hasta'] = FormLib::Hour("HHORAORLIC_SUB", 1, isset($info->hhoraorlic_sub) ? $info->hhoraorlic_sub : '');

		//$data['formularioGeneral']['estado subalia'] = FormLib::Select("SUBALIA_SUB", 1, isset($info->subalia_sub)?$info->subalia_sub:'',$estado_subalia);
		$data['formularioGeneral']['destacado'] = FormLib::Bool("DESTACADO_SUB", 0, (isset($info->destacado_sub) && $info->destacado_sub == "S") ? $info->destacado_sub : 0);
		$data['formularioGeneral']['compra web'] = FormLib::Bool("COMPRAWEB_SUB", 0, (isset($info->compraweb_sub) && $info->compraweb_sub == "S") ? $info->compraweb_sub : 0);
		$data['formularioGeneral']['opción carrito'] = FormLib::Bool("OPCIONCAR_SUB", 0, (isset($info->opcioncar_sub) && $info->opcioncar_sub == "S") ? $info->opcioncar_sub : 0);
		$data['formularioGeneral']['subir a Subalia'] = FormLib::Bool("UPSUBALIA_SUB", 0, (isset($info->upsubalia_sub) && $info->upsubalia_sub == "S") ? $info->upsubalia_sub : 0);

		$data['formularioGeneral']['Imagen'] = FormLib::File("IMAGEN_SUB", 0, isset($info->imagen_sub) ? $info->imagen_sub : '');

		if (!empty($id)) {

			$data['formularioTextos']['es']['titulo'] = FormLib::Text("DES_SUB", 0, isset($info->des_sub) ? $info->des_sub : '');
			$data['formularioTextos']['es']['descripción'] = FormLib::Textarea("DESCDET_SUB", 0, isset($info->descdet_sub) ? $info->descdet_sub : '');
			//$data['formularioTextos']['es']['notas'] = FormLib::Textarea("DESCCONTR_SUB", 0, isset($info->desccontr_sub)?$info->desccontr_sub:'');
			$data['formularioTextos']['es']['url'] = FormLib::Text("WEBFRIEND_SUB", 0, isset($info->webfriend_sub) ? $info->webfriend_sub : '');
			$data['formularioTextos']['es']['Meta título'] = FormLib::Text("WEBMETAT_SUB", 0, isset($info->webmetat_sub) ? $info->webmetat_sub : '');
			$data['formularioTextos']['es']['Meta descripción'] = FormLib::Text("WEBMETAD_SUB", 0, isset($info->webmetad_sub) ? $info->webmetad_sub : '');

			foreach (\Config::get("app.locales") as $k => $lang) {

				if ($k != "es") {
					$data['formularioTextos'][$k]['titulo'] = FormLib::Text("DES_SUB_" . $k, 0, isset($info_{$k}->des_sub_lang) ? $info_{$k}->des_sub_lang : '');
					$data['formularioTextos'][$k]['descripción'] = FormLib::Textarea("DESCDET_SUB_" . $k, 0, isset($info_{$k}->descdet_sub_lang) ? $info_{$k}->descdet_sub_lang : '');
					//$data['formularioTextos'][$k]['notas'] = FormLib::Textarea("DESCCONTR_SUB_".$k, 0, isset($info_{$k}->desccontr_sub_lang)?$info_{$k}->descontr_sub_lang:'');
					$data['formularioTextos'][$k]['url'] = FormLib::Text("WEBFRIEND_SUB_" . $k, 0, isset($info_{$k}->webfriend_sub_lang) ? $info_{$k}->webfriend_sub_lang : '');
					$data['formularioTextos'][$k]['Meta título'] = FormLib::Text("WEBMETAT_SUB_" . $k, 0, isset($info_{
						$k}->webmetat_sub_lang) ? $info_{
						$k}->webmetat_sub_lang : '');
					$data['formularioTextos'][$k]['Meta descripción'] = FormLib::Text("WEBMETAD_SUB_" . $k, 0, isset($info_{
						$k}->webmetad_sub_lang) ? $info_{
						$k}->webmetad_sub_lang : '');
				}
			}

			$data['ordenes'] = DB::table("FGORLIC")->where("EMP_ORLIC", \Config::get("app.emp"))->where("SUB_ORLIC", $id)->get();

			/**
			 * PUJAS
			 * @todo
			 * Para poder realizar una union con las pujas inferiores, las columnas de esta primera deben tener el mismo nombre
			 * Y para poder ordenar, se debe hacer por el numero de columna y no por el nombre
			 */

			if (Config::get('app.lower_bids', false)) {
				$pujasInferiores = FgAsigl1_Aux::select('emp_asigl1', 'sub_asigl1', 'ref_asigl1', 'lin_asigl1', 'licit_asigl1', 'imp_asigl1', 'fec_asigl1', 'pujrep_asigl1', 'hora_asigl1', 'type_asigl1', 'usr_update_asigl1', 'date_update_asigl1')
				->where("SUB_ASIGL1", $id);


				$data['pujas'] = FgAsigl1::union($pujasInferiores)
				->where("SUB_ASIGL1", $id)
				->orderBy(4, "desc")
				->orderBy(7, "desc")
				->orderBy(9, "desc")
				->get();
			}
			else{

				$data['pujas'] = FgAsigl1::where("SUB_ASIGL1", $id)
				->orderBy('lin_asigl1', "desc")
				->get();
			}



			$data['maxOrdenes'] = array();
			foreach ($data['ordenes'] as $key => $orden) {
				if (empty($data['maxOrdenes'][$orden->ref_orlic]) || $data['maxOrdenes'][$orden->ref_orlic] < $orden->himp_orlic) {
					$data['maxOrdenes'][$orden->ref_orlic] = $orden->himp_orlic;
				}
			}

			$data['maxPujas'] = array();
			foreach ($data['pujas'] as $key => $pujas) {
				if (empty($data['maxPujas'][$pujas->ref_asigl1]) ||  $data['maxPujas'][$pujas->ref_asigl1] < $pujas->imp_asigl1) {
					$data['maxPujas'][$pujas->ref_asigl1] = $pujas->imp_asigl1;
				}
			}

			$data['lotes'] = DB::table("FGASIGL0")->where("EMP_ASIGL0", \Config::get("app.emp"))->where("SUB_ASIGL0", $id)->get();

			$licitadores = DB::table("FGLICIT")->where("EMP_LICIT", \Config::get("app.emp"))->where("SUB_LICIT", $id)->get();
			$data['licitadores'] = array();
			foreach ($licitadores as $item) {
				$data['licitadores'][$item->cod_licit] = $item;
			}


			/**
			 * Tabla ordenes
			 * No aparece ni en abierta pujas ni en venta directa
			 */
			if($tipoSub != 'WP' && $tipoSub != 'V'){
				$adminOrderController = new AdminOrderController(true);
				$data['formularioOrdenes'] = $adminOrderController->index(request(), true, $id);
			}


			/**Tabla adjudicaciones */
			$adminAwardController = new AdminAwardController();
			$data['formularioAdjudicaciones'] = $adminAwardController->index(request(), true, $id);


			$currency = new Currency();
			$divisas = $currency->getAllCurrencies();

			$hces1 = DB::table("FGHCES1")->addSelect("num_hces1", "lin_hces1", "divisa_hces1")->where("EMP_HCES1", \Config::get("app.emp"))->where("SUB_HCES1", $id)->get();

			$data['currencies'] = array();
			foreach ($hces1 as $k => $item) {
				$data['currencies'][$item->num_hces1 . "-" . $item->lin_hces1] = $item;
			}

			foreach ($data['lotes'] as $item) {
				if (isset($data['currencies'][$item->numhces_asigl0 . "-" . $item->linhces_asigl0])) {
					$item->divisa_hces1 = $data['currencies'][$item->numhces_asigl0 . "-" . $item->linhces_asigl0]->divisa_hces1;
				}
				else {
					$item->divisa_hces1 = "EUR";
				}
				if(empty($item->divisa_hces1)){
					$item->divisa_hces1 = "EUR";
				}

				if ($item->divisa_hces1 != "EUR") {
					$item->impsalhces_asigl0 = $item->impsalhces_asigl0 * $divisas[$item->divisa_hces1]->impd_div;
					$item->impadj_asigl0 = $item->impsalhces_asigl0 * $divisas[$item->divisa_hces1]->impd_div;
				}
			}

			$data['sesiones'] = DB::table('"auc_sessions"')->where('"company"', \Config::get("app.emp"))->where('"auction"', $id)->get();

			$data['ganadores'] = array();

			//$ganadores = DB::table("FGCSUB")->where("EMP_CSUB", \Config::get("app.emp"))->where("sub_csub", $id)->get();

			$ganadores = FgCsub::select('ref_csub', 'licit_csub','fec_asigl1', 'himp_csub', 'clifac_csub', 'cod2_cli')
							->addselect(\DB::raw("NVL(FGLICIT.RSOC_LICIT,  nom_cli) nom_cli"))
							->joinWinnerBid()
							->joinFgLicit()
							->joinCli()->where('sub_csub', $id)->get();

			foreach ($ganadores as $item) {
				$data['ganadores'][$item->ref_csub] = $item;
			}
		}

		$data['simbolos'] = array();
		$data['simbolos']["EUR"] = "€";
		$data['simbolos']["USD"] = "$";

		$data['formulario']['SUBMIT'] = FormLib::Submit("Guardar", "edit");


		// Archivos

		$data['archivos'] = array();
		if (is_dir($newfile = str_replace("\\", "/", getcwd() . '/files/' . $data['id']))) {
			$directorio = opendir($newfile = str_replace("\\", "/", getcwd() . '/files/' . $data['id']));
			while ($archivo = readdir($directorio)) {
				if (is_dir($archivo)) //verificamos si es o no un directorio
				{
					continue;
				} else {
					$data['archivos'][] = $archivo;
				}
			}
		}





		// Escalados

		$data['escalado'] = array();
		$key = 0;

		$escalado = DB::table("FGPUJASSUB")->where("emp_pujassub", \Config::get('app.emp'))->where("sub_pujassub", $data['id'])->get();

		foreach ($escalado as $key => $item) {

			$a = FormLib::Float("importe" . $key, 0, $item->imp_pujassub);
			$b = FormLib::Float("puja" . $key, 0, $item->puja_pujassub);

			$data['escalado'][$key] = [
				"importe"   => $a,
				"puja"      => $b
			];
		}
		for ($t = $key + 1; $t < 10; $t++) {
			$a = FormLib::Float("importe" . $t, 0);
			$b = FormLib::Float("puja" . $t, 0);

			$data['escalado'][$t] = [
				"importe"   => $a,
				"puja"      => $b
			];
		}

		return \View::make('admin::pages.subasta.subasta.edit', $data);
	}

	function edit_run()
	{

		$data = Input::all();

		if (isset($data['readonly__COD_SUB']))
			$data['COD_SUB'] = $data['readonly__COD_SUB'];

		if (isset($data['TIPO_SUB'][1]))
			$data['SUBABIERTA'] = $data['TIPO_SUB'][1];

		$data['TIPO_SUB'] = $data['TIPO_SUB'][0];

		if (!empty($data['id'])) {

			$info = [
				"SUBC_SUB" => $data['SUBC_SUB'],
				"DES_SUB" => $data['DES_SUB'],
				"TIPO_SUB" => $data['TIPO_SUB'],
				"SUBABIERTA_SUB" => (!empty($data['SUBABIERTA'])) ? $data['SUBABIERTA'] : 'N',
				"DFEC_SUB" => $data['DFEC_SUB'],
				"HFEC_SUB" => $data['HFEC_SUB'],
				"DHORA_SUB" => $data['DHORA_SUB'],
				"HHORA_SUB" => $data['HHORA_SUB'],
				"DFECORLIC_SUB" => (!empty($data['DFECORLIC_SUB'])) ? $data['DFECORLIC_SUB'] : '',
				"HFECORLIC_SUB" => (!empty($data['HFECORLIC_SUB'])) ? $data['HFECORLIC_SUB'] : '',
				"DHORAORLIC_SUB" => (!empty($data['DHORAORLIC_SUB'])) ? $data['DHORAORLIC_SUB'] : '',
				"HHORAORLIC_SUB" => (!empty($data['HHORAORLIC_SUB'])) ? $data['HHORAORLIC_SUB'] : '',
				"DESTACADO_SUB" => (isset($data['DESTACADO_SUB'])) ? 'S' : 'N',
				"COMPRAWEB_SUB" => (isset($data['COMPRAWEB_SUB'])) ? 'S' : 'N',
				"OPCIONCAR_SUB" => (isset($data['OPCIONCAR_SUB'])) ? 'S' : 'N',
				"UPSUBALIA_SUB" => (isset($data['UPSUBALIA_SUB'])) ? 'S' : 'N',
				"DESCDET_SUB" => $data['DESCDET_SUB'],
				"WEBFRIEND_SUB" => $data['WEBFRIEND_SUB'],
				"WEBMETAT_SUB" => $data['WEBMETAT_SUB'],
				"WEBMETAD_SUB" => $data['WEBMETAD_SUB']
			];

			DB::table("FGSUB")->where("emp_sub", \Config::get("app.emp"))->where("cod_sub", $data['id'])->update($info);

			$whereAuction = [
				['"reference"', '001'],
				['"auction"', $data['id']],
			];

			$sessionName = AucSessions::select('"name"')->where($whereAuction)->first()->name;

			if(!$sessionName){
				AucSessions::where($whereAuction)->update([
					'"name"' => $data['DES_SUB'],
					'"description"' => $data['DESCDET_SUB'],
				]);
			}

			foreach (\Config::get("app.locales") as $lang => $langInfo) {

				if ($lang == 'es')
					continue;

				if (empty($data['WEBFRIEND_SUB_' . $lang])) {
					$data['WEBFRIEND_SUB_' . $lang] = \Tools::Seo_url($data['DES_SUB_' . $lang]);
				}
				if (empty($data['WEBMETAT_SUB_' . $lang])) {
					$data['WEBMETAT_SUB_' . $lang] = $data['DES_SUB_' . $lang];
				}
				if (empty($data['WEBMETAD_SUB_' . $lang])) {
					$data['WEBMETAD_SUB_' . $lang] = $data['DES_SUB_' . $lang];
				}

				$langinfo = [
					"DES_SUB_LANG" => $data['DES_SUB_' . $lang],
					"DESCDET_SUB_LANG" => $data['DESCDET_SUB_' . $lang],
					"WEBFRIEND_SUB_LANG" => $data['WEBFRIEND_SUB_' . $lang],
					"WEBMETAT_SUB_LANG" => $data['WEBMETAT_SUB_' . $lang],
					"WEBMETAD_SUB_LANG" => $data['WEBMETAD_SUB_' . $lang]
				];


				DB::table("FgSub_Lang")->where("LANG_SUB_LANG", \Config::get("app.language_complete")[$lang])->where("EMP_SUB_LANG", \Config::get("app.emp"))->where("COD_SUB_LANG", $data['COD_SUB'])->update($langinfo);
			}

			if (!empty($_FILES) && !empty($_FILES['IMAGEN_SUB']['tmp_name'])) {
				$this->guardarImagen($_FILES, $data['id']);
			}

			return redirect("/admin/subasta");
		} else {

			$data['COD_SUB'] = str_replace(" ", "", $data['COD_SUB']);
			$data['COD_SUB'] = trim($data['COD_SUB']);

			$info = [
				"COD_SUB" => $data['COD_SUB'],
				"EMP_SUB" => \Config::get("app.emp"),
				"TIPO_SUB" => $data['TIPO_SUB'],
				"SUBC_SUB" => $data['SUBC_SUB'],
				"SUBABIERTA_SUB" => (!empty($data['SUBABIERTA'])) ? $data['SUBABIERTA'] : 'N',
				"DFEC_SUB" => $data['DFEC_SUB'],
				"HFEC_SUB" => $data['HFEC_SUB'],
				"DHORA_SUB" => $data['DHORA_SUB'],
				"HHORA_SUB" => $data['HHORA_SUB'],
				"DFECORLIC_SUB" => (!empty($data['DFECORLIC_SUB'])) ? $data['DFECORLIC_SUB'] : '',
				"HFECORLIC_SUB" => (!empty($data['HFECORLIC_SUB'])) ? $data['HFECORLIC_SUB'] : '',
				"DHORAORLIC_SUB" => (!empty($data['DHORAORLIC_SUB'])) ? $data['DHORAORLIC_SUB'] : '',
				"HHORAORLIC_SUB" => (!empty($data['HHORAORLIC_SUB'])) ? $data['HHORAORLIC_SUB'] : '',
				"DESTACADO_SUB" => (isset($data['DESTACADO_SUB'])) ? 'S' : 'N',
				"COMPRAWEB_SUB" => (isset($data['COMPRAWEB_SUB'])) ? 'S' : 'N',
				"OPCIONCAR_SUB" => (isset($data['OPCIONCAR_SUB'])) ? 'S' : 'N',
				"UPSUBALIA_SUB" => (isset($data['UPSUBALIA_SUB'])) ? 'S' : 'N'
			];

			DB::table("FgSub")->insert($info);

			$nuevoIdSesion = DB::table('"auc_sessions"')->max('"id_auc_sessions"');
			$nuevoIdSesion++;

			$infoSession = [
				'"id_auc_sessions"' => $nuevoIdSesion,
				'"company"' => \Config::get("app.emp"),
				'"auction"' => $data['COD_SUB'],
				'"reference"' => "001",
				'"start"' => new DateTime($data['DFEC_SUB'] . ' ' . $data['DHORA_SUB']),
				'"end"' => new DateTime($data['HFEC_SUB'] . ' ' . $data['HHORA_SUB']),
				'"orders_start"' => new DateTime($info['DFECORLIC_SUB'] . ' ' . $info['DHORAORLIC_SUB']),
				'"orders_end"' => new DateTime($info['HFECORLIC_SUB'] . ' ' . $info['HHORAORLIC_SUB']),
				'"init_lot"' => 1,
				'"end_lot"' => 99999,
				'"status"' => "P"
			];

			DB::table('"auc_sessions"')->insert($infoSession);


			foreach (\Config::get("app.locales") as $k => $lang) {

				if ($k == 'es')
					continue;

				$langinfo = [
					"LANG_SUB_LANG" => \Config::get("app.language_complete")[$k],
					"COD_SUB_LANG" => $data['COD_SUB'],
					"EMP_SUB_LANG" => \Config::get("app.emp")
				];

				DB::table("FgSub_Lang")->insert($langinfo);
			}

			//AUCTION_codEmp_cod_sub.jpg"

			if (!empty($_FILES) && !empty($_FILES['IMAGEN_SUB']['tmp_name'])) {
				$this->guardarImagen($_FILES, $data['COD_SUB']);
			}

			return redirect("/admin/subasta/edit/" . $data['COD_SUB']);
		}
	}

	function editLote($subasta, $id = 0)
	{

		$data = array("id" => $id, 'menu' => 4);
		$aux_hces1 = [];

		if (!empty($id)) {
			$a = explode("-", $id);
			$num = $a[0];
			$lin = $a[1];
			$ref = $a[2];
			$info = DB::table("FGASIGL0")->where("EMP_ASIGL0", \Config::get("app.emp"))->where("SUB_ASIGL0", $subasta)->where("REF_ASIGL0", $a[2])->first();
			#modifico el código de kike por que NO debe ir por subasta y referencia
			#$aux_hces1 = DB::table("FGHCES1")->where("EMP_HCES1", \Config::get("app.emp"))->where("SUB_HCES1", $subasta)->where("REF_HCES1", $a[2])->first();
			$aux_hces1 = DB::table("FGHCES1")->where("EMP_HCES1", \Config::get("app.emp"))->where("NUM_HCES1", $num)->where("LIN_HCES1", $lin)->first();
			$data['num_hces1'] = $aux_hces1->num_hces1;
			$data['lin_hces1'] = $aux_hces1->lin_hces1;
		}


		// Construimos los campos de formulario

		$data['formulario'] = array();

		$data['formulario']['id'] = FormLib::Hidden("id", 1, $id);

		$data['formulario']['subasta'] = FormLib::ReadOnly("SUB_ASIGL0", 1, $subasta);
		if (empty($id)) {
			$data['formulario']['referencia'] = FormLib::Int("REF_ASIGL0", 1, '0');
			$data['formulario']['Id Origen'] = FormLib::Text("IDORIGEN_ASIGL0", 0, '');

		} else {
			$data['formulario']['referencia'] = FormLib::ReadOnly("REF_ASIGL0", 1, isset($a[2]) ? $a[2] : '');
			$data['formulario']['Id Origen'] = FormLib::Text("IDORIGEN_ASIGL0", 0, $info->idorigen_asigl0 ?? '');
		}
		#sacamos el listado de subcategorias
		$subcategories = FxSec::GetActiveFxSec();

		$propietario = null;
		if(!empty($aux_hces1->prop_hces1)){
			$propietario = FxCli::select('RSOC_CLI')->where('COD_CLI', $aux_hces1->prop_hces1)->first();
		}

		$data['formulario']['Subcategoria'] = FormLib::select("SEC_HCES1", 0, isset($aux_hces1->sec_hces1) ? $aux_hces1->sec_hces1 : '', $subcategories);
		$data['formulario']['Propietario'] = FormLib::Select2("PROP_HCES1", 0, $aux_hces1->prop_hces1 ?? '', (!empty($aux_hces1->prop_hces1) &&  $propietario) ? $propietario->rsoc_cli : '');
		$data['formulario']['Fecha inicio'] = FormLib::Date("FINI_ASIGL0", 1, isset($info->fini_asigl0) ? $info->fini_asigl0 : '');
		$data['formulario']['Hora inicio'] = FormLib::Hour("HINI_ASIGL0", 1, isset($info->hini_asigl0) ? $info->hini_asigl0 : '');
		$data['formulario']['Fecha fin'] = FormLib::Date("FFIN_ASIGL0", 1, isset($info->ffin_asigl0) ? $info->ffin_asigl0 : '');
		$data['formulario']['Hora fin'] = FormLib::Hour("HFIN_ASIGL0", 1, isset($info->hfin_asigl0) ? $info->hfin_asigl0 : '');
		$data['formulario']['Lote destacado'] = FormLib::Bool("DESTACADO_ASIGL0", 0, isset($info->destacado_asigl0) && $info->destacado_asigl0 == "S" ? $info->destacado_asigl0 : 0);
		$data['formulario']['Opción de compra'] = FormLib::Bool("COMPRA_ASIGL0", 0, isset($info->compra_asigl0) && $info->compra_asigl0 == "S" ? $info->compra_asigl0 : 0);
		$data['formulario']['Cerrado'] = FormLib::Bool("CERRADO_ASIGL0", 0, isset($info->cerrado_asigl0) && $info->cerrado_asigl0 == "S" ? $info->cerrado_asigl0 : 0);
		$data['formulario']['Retirado'] = FormLib::Bool("RETIRADO_ASIGL0", 0, isset($info->retirado_asigl0) && $info->retirado_asigl0 == "S" ? $info->retirado_asigl0 : 0);
		$data['formulario']['Oculto'] = FormLib::Bool("OCULTO_ASIGL0", 0, isset($info->oculto_asigl0) && $info->oculto_asigl0 == "S" ? $info->oculto_asigl0 : 0);
		$data['formulario']['Desadjudicado'] = FormLib::Bool("DESADJU_ASIGL0", 0, isset($info->desadju_asigl0) && $info->desadju_asigl0 == "S" ? $info->desadju_asigl0 : 0);
		$data['formulario']['Ver remate'] = FormLib::Bool("REMATE_ASIGL0", 0, isset($info->remate_asigl0) && $info->remate_asigl0 == "S" ? $info->remate_asigl0 : 0);
		$data['formulario']['Imagen 360'] = FormLib::Bool("IMG360_HCES1", 0, isset($aux_hces1->img360_hces1) && $aux_hces1->img360_hces1 == "S" ? $aux_hces1->img360_hces1 : 0);

		$data['formulario']['Url 360'] = FormLib::Hidden('CONTEXTRA_HCES1', 0, isset($aux_hces1->contextra_hces1)  ? $aux_hces1->contextra_hces1 : '');
		$data['formulario']['Url360'] = isset($aux_hces1->contextra_hces1)  ? $aux_hces1->contextra_hces1 : '';

		$divisas = array();
		$aux_divisas = DB::table("FSDIV")->get();
		foreach ($aux_divisas as $item) {
			$divisas[$item->cod_div] = $item;
			$divisas_select[$item->cod_div] = $item->des_div;
		}

		if (isset($aux_hces1->divisa_hces1)) {
			$info->divisa_hces1 = $aux_hces1->divisa_hces1;
		}

		$importeSalida = 0;
		$estimacionAlto = 0;
		$estimacionBajo = 0;

		if (isset($info->impsalhces_asigl0) && isset($info->divisa_hces1)) {
			$importeSalida = round($info->impsalhces_asigl0 * $divisas[$info->divisa_hces1]->impd_div);
		}
		if (isset($info->imptash_asigl0) && isset($info->divisa_hces1)) {
			$estimacionAlto = round($info->imptash_asigl0 * $divisas[$info->divisa_hces1]->impd_div);
		}
		if (isset($info->imptas_asigl0) && isset($info->divisa_hces1)) {
			$estimacionBajo = round($info->imptas_asigl0 * $divisas[$info->divisa_hces1]->impd_div);
		}

		$data['formulario']['Importe de salida'] = FormLib::Float("importeSalida", 1, $importeSalida);
		$data['formulario']['Estimación alto'] = FormLib::Text("estimacionAlto", 0, $estimacionAlto);
		$data['formulario']['Estimación bajo'] = FormLib::Text("estimacionBajo", 0, $estimacionBajo);

		$data['formulario']['Precio Reserva'] = FormLib::Float("IMPRES_ASIGL0", 0, round( ($info->impres_asigl0 ?? 0) * $divisas[$info->divisa_hces1 ?? 'EUR']->impd_div));
		//$data['formulario']['Deposito *no funcional'] = FormLib::Float("DEPOSITO_ASIGL0", 0, $info->deposito_asigl0 ?? 0);
		$data['formulario']['Comisión licitador'] = FormLib::Text("COMLHCES_ASIGL0", 0, $info->comlhces_asigl0 ?? 0);
		$data['formulario']['Comisión proveedor'] = FormLib::Text("COMPHCES_ASIGL0", 0, $info->comphces_asigl0 ?? 0);

		$data['formulario']['Divisa'] = FormLib::Select("DIVISA_HCES1", 1, isset($info->divisa_hces1) ? $info->divisa_hces1 : 'EUR', $divisas_select);

		if (!empty($id)) {
			$data['formulario']['num'] = FormLib::Hidden("NUMHCES_ASIGL0", 1, $info->numhces_asigl0);
			$data['formulario']['lin'] = FormLib::Hidden("LINHCES_ASIGL0", 1, $info->linhces_asigl0);
		}

		$data['formulario']['SUBMIT'] = FormLib::Submit("Guardar", "edit");

		$data['formularioLang'] = array();

		$infoLang = array();

		if (!empty($id)) {

			$infoLangData = DB::table("FGHCES1_LANG")
				->where("EMP_HCES1_LANG", \Config::get("app.emp"))
				->where("NUM_HCES1_LANG", $info->numhces_asigl0)
				->where("LIN_HCES1_LANG", $info->linhces_asigl0)->get();



			foreach ($infoLangData as $items) {
				$a = explode("-", $items->lang_hces1_lang);
				$infoLang[$a[0]] = new \stdClass();
				foreach ($items as $k => $value) {
					$infoLang[$a[0]]->{$k} = $value;
				}
			}
		}


		foreach (\Config::get("app.locales") as $lang => $name) {
			$data['formularioLang'][$lang] = array();

			if($lang != 'es'){

				$data['formularioLang'][$lang]["DESCWEB_HCES1_LANG"] = FormLib::Text("DESCWEB_HCES1_LANG_" . $lang, 0, isset($infoLang[$lang]->descweb_hces1_lang) ? $infoLang[$lang]->descweb_hces1_lang : '');
				$data['formularioLang'][$lang]["DESC_HCES1_LANG"] = FormLib::Textarea("DESC_HCES1_LANG_" . $lang, 0, isset($infoLang[$lang]->desc_hces1_lang) ? $infoLang[$lang]->desc_hces1_lang : '');
				$data['formularioLang'][$lang]["DESCDET_HCES1_LANG"] = FormLib::Textarea("DESCDET_HCES1_LANG_" . $lang, 0, isset($infoLang[$lang]->descdet_hces1_lang) ? $infoLang[$lang]->descdet_hces1_lang : '');
				$data['formularioLang'][$lang]["WEBFRIEND_HCES1_LANG"] = FormLib::Text("WEBFRIEND_HCES1_LANG_" . $lang, 0, isset($infoLang[$lang]->webfriend_hces1_lang) ? $infoLang[$lang]->webfriend_hces1_lang : '', 'maxlength="100"');
				$data['formularioLang'][$lang]["WEBMETAT_HCES1_LANG"] = FormLib::Text("WEBMETAT_HCES1_LANG_" . $lang, 0, isset($infoLang[$lang]->webmetat_hces1_lang) ? $infoLang[$lang]->webmetat_hces1_lang : '', 'maxlength="60"');
				$data['formularioLang'][$lang]["WEBMETAD_HCES1_LANG"] = FormLib::Text("WEBMETAD_HCES1_LANG_" . $lang, 0, isset($infoLang[$lang]->webmetad_hces1_lang) ? $infoLang[$lang]->webmetad_hces1_lang : '', 'maxlength="150"');

			}
			else{
				$data['formularioLang'][$lang]["DESCWEB_HCES1_LANG"] = FormLib::Text("DESCWEB_HCES1_LANG_" . $lang, 0, isset($aux_hces1->descweb_hces1) ? $aux_hces1->descweb_hces1 : '');
				$data['formularioLang'][$lang]["DESC_HCES1_LANG"] = FormLib::Textarea("DESC_HCES1_LANG_" . $lang, 0, isset($aux_hces1->desc_hces1) ? $aux_hces1->desc_hces1 : '');
				$data['formularioLang'][$lang]["DESCDET_HCES1_LANG"] = FormLib::Textarea("DESCDET_HCES1_LANG_" . $lang, 0, isset($aux_hces1->descdet_hces1) ? $aux_hces1->descdet_hces1 : '');
				$data['formularioLang'][$lang]["WEBFRIEND_HCES1_LANG"] = FormLib::Text("WEBFRIEND_HCES1_LANG_" . $lang, 0, isset($aux_hces1->webfriend_hces1) ? $aux_hces1->webfriend_hces1 : '', 'maxlength="100"');
				$data['formularioLang'][$lang]["WEBMETAT_HCES1_LANG"] = FormLib::Text("WEBMETAT_HCES1_LANG_" . $lang, 0, isset($aux_hces1->webmetat_hces1) ? $aux_hces1->webmetat_hces1 : '', 'maxlength="60"');
				$data['formularioLang'][$lang]["WEBMETAD_HCES1_LANG"] = FormLib::Text("WEBMETAD_HCES1_LANG_" . $lang, 0, isset($aux_hces1->webmetad_hces1) ? $aux_hces1->webmetad_hces1 : '', 'maxlength="150"');
			}



		}

		$data['imagenes'] = array();

		if (!empty($id)) {

			if (is_file(str_replace("\\", "/", getcwd() . "/img/" . \Config::get("app.emp") . "/" . $info->numhces_asigl0 . "/" . \Config::get("app.emp") . "-" . $info->numhces_asigl0 . "-" . $info->linhces_asigl0 . ".jpg"))) {

				$data['imagenes'][] = "/img/" . \Config::get("app.emp") . "/" . $info->numhces_asigl0 . "/" . \Config::get("app.emp") . "-" . $info->numhces_asigl0 . "-" . $info->linhces_asigl0 . ".jpg";
			}

			for ($t = 0; $t < 20; $t++) {

				if ($t < 10)
					$t = "0" . $t;

				if (is_file(str_replace("\\", "/", getcwd() . "/img/" . \Config::get("app.emp") . "/" . $info->numhces_asigl0 . "/" . \Config::get("app.emp") . "-" . $info->numhces_asigl0 . "-" . $info->linhces_asigl0 . "_" . $t . ".jpg"))) {

					$data['imagenes'][] = "/img/" . \Config::get("app.emp") . "/" . $info->numhces_asigl0 . "/" . \Config::get("app.emp") . "-" . $info->numhces_asigl0 . "-" . $info->linhces_asigl0 . "_" . $t . ".jpg";
				}
			}
		}

		$data['id'] = $id;
		$data['subasta'] = $subasta;
		$data['idOrigen'] = $info->idorigen_asigl0 ?? '';

		return \View::make('admin::pages.subasta.lote.edit', $data);
	}


	function editLote_run()
	{

		$data = Input::all();


		if (isset($data['readonly__SUB_ASIGL0'])) {
			$data['SUB_ASIGL0'] = $data['readonly__SUB_ASIGL0'];
		}


		if (isset($data['readonly__REF_ASIGL0'])) {
			$data['REF_ASIGL0'] = $data['readonly__REF_ASIGL0'];
		}

		$divisas = array();
		$aux_divisas = DB::table("FSDIV")->get();
		foreach ($aux_divisas as $item) {
			$divisas[$item->cod_div] = $item;
		}

		if (isset($_FILES) && !empty($_FILES) && !empty($data['id'])) {
			$this->guardarImagenLote($_FILES, $data['NUMHCES_ASIGL0'], $data['LINHCES_ASIGL0']);
		}

		$data['IMPSAL_HCES1'] = $data['importeSalida'] / $divisas[$data['DIVISA_HCES1']]->impd_div;
		$data['IMPTASH_ASIGL0'] = $data['estimacionAlto'] / $divisas[$data['DIVISA_HCES1']]->impd_div;
		$data['IMPTAS_ASIGL0'] = $data['estimacionBajo'] / $divisas[$data['DIVISA_HCES1']]->impd_div;

		if (!empty($data["id"])) {

			$booleans = array('COMPRA_ASIGL0', 'RETIRADO_ASIGL0', 'REMATE_ASIGL0', 'DESADJU_ASIGL0', 'OCULTO_ASIGL0', 'DESTACADO_ASIGL0', 'CERRADO_ASIGL0', 'IMG360_HCES1');

			foreach ($booleans as $boolean) {

				if (!isset($data[$boolean])) {
					$data[$boolean] = 'N';
				} else {
					$data[$boolean] = 'S';
				}
			}

			$infoHCES1 = [
				"IMPSAL_HCES1" => $data['IMPSAL_HCES1'],
				"DIVISA_HCES1" => $data['DIVISA_HCES1'],
				"DESTACADO_HCES1" => $data['DESTACADO_ASIGL0'],
				"IMG360_HCES1" => $data['IMG360_HCES1'],
				"CONTEXTRA_HCES1" => $data['CONTEXTRA_HCES1'],
				"SEC_HCES1" => $data['SEC_HCES1'],
				"DESCWEB_HCES1" => $data['DESCWEB_HCES1_LANG_es'] ?? '',
				"DESC_HCES1" => $data['DESC_HCES1_LANG_es'] ?? '',
				"DESCDET_HCES1" => $data['DESCDET_HCES1_LANG_es'] ?? '',
				"WEBFRIEND_HCES1" => $data['WEBFRIEND_HCES1_LANG_es'] ?? '',
				"WEBMETAT_HCES1" => $data['WEBMETAT_HCES1_LANG_es'] ?? '',
				"WEBMETAD_HCES1" => $data['WEBMETAD_HCES1_LANG_es'] ?? '',
				"IDORIGEN_HCES1" => $data['IDORIGEN_ASIGL0'] ?? '',
				"PROP_HCES1" => $data['PROP_HCES1'] ?? ''

			];

			DB::table("FGHCES1")->where("EMP_HCES1", \Config::get("app.emp"))->where("SUB_HCES1", $data['SUB_ASIGL0'])->where("REF_HCES1", $data['REF_ASIGL0'])->update($infoHCES1);


			if (empty($data['HINI_ASIGL0']))
				$data['HINI_ASIGL0'] = "00:00";
			if (empty($data['HFIN_ASIGL0']))
				$data['HFIN_ASIGL0'] = "00:00";

			$infoAsigl0 = [
				"IMPSALHCES_ASIGL0" => $data['IMPSAL_HCES1'],
				"FINI_ASIGL0" => $data['FINI_ASIGL0'],
				"HINI_ASIGL0" => $data['HINI_ASIGL0'],
				"FFIN_ASIGL0" => $data['FFIN_ASIGL0'],
				"HFIN_ASIGL0" => $data['HFIN_ASIGL0'],
				"FFIN_ORIGINAL_ASIGL0" => $data['FFIN_ASIGL0'],
				"HFIN_ORIGINAL_ASIGL0" => $data['HFIN_ASIGL0'],
				"IMPTASH_ASIGL0" => $data['IMPTASH_ASIGL0'],
				"IMPTAS_ASIGL0" => $data['IMPTAS_ASIGL0'],
				"DESTACADO_ASIGL0" => $data['DESTACADO_ASIGL0'],
				"COMPRA_ASIGL0" => $data['COMPRA_ASIGL0'],
				"CERRADO_ASIGL0" => $data['CERRADO_ASIGL0'],
				"RETIRADO_ASIGL0" => $data['RETIRADO_ASIGL0'],
				"OCULTO_ASIGL0" => $data['OCULTO_ASIGL0'],
				"DESADJU_ASIGL0" => $data['DESADJU_ASIGL0'],
				"REMATE_ASIGL0" => $data['REMATE_ASIGL0'],
				"IDORIGEN_ASIGL0" => $data['IDORIGEN_ASIGL0'] ?? '',
				"IMPRES_ASIGL0" => $data['IMPRES_ASIGL0'] ?? 0,
				"COMLHCES_ASIGL0" => $data['COMLHCES_ASIGL0'] ?? 0,
				"COMPHCES_ASIGL0" => $data['COMPHCES_ASIGL0'] ?? 0,
				//pendiente de crearse -> "DEPOSITO_ASIGL0" => $data['DEPOSITO_ASIGL0'] ?? 0,
			];

			$aux = explode("-", $data['id']);
			DB::table('FGASIGL0')->where("EMP_ASIGL0", \Config::get("app.emp"))->where("SUB_ASIGL0", $data['SUB_ASIGL0'])->where("REF_ASIGL0", $data['REF_ASIGL0'])->update($infoAsigl0);

			$language_complete = \Config::get("app.language_complete");

			foreach (\Config::get("app.locales") as $lang => $name) {

				$info = [
					"TITULO_HCES1_LANG" => substr($data['DESCWEB_HCES1_LANG_' . $lang], 0, 60),
					"DESCWEB_HCES1_LANG" => $data['DESCWEB_HCES1_LANG_' . $lang],
					"WEBMETAT_HCES1_LANG" => $data['WEBMETAT_HCES1_LANG_' . $lang],
					"WEBMETAD_HCES1_LANG" => $data['WEBMETAD_HCES1_LANG_' . $lang],
					"WEBFRIEND_HCES1_LANG" => \Tools::Seo_url($data['WEBFRIEND_HCES1_LANG_' . $lang]),
					"DESC_HCES1_LANG" => $data['DESC_HCES1_LANG_' . $lang],
					"DESCDET_HCES1_LANG" => $data['DESCDET_HCES1_LANG_' . $lang],
				];

				$existeRegistro = DB::table("FGHCES1_LANG")
					->where("EMP_HCES1_LANG", \Config::get("app.emp"))
					->where("LANG_HCES1_LANG", $language_complete[$lang])
					->where("NUM_HCES1_LANG", $data['NUMHCES_ASIGL0'])
					->where("LIN_HCES1_LANG", $data['LINHCES_ASIGL0'])->first();

				if ($existeRegistro) {

					DB::table("FGHCES1_LANG")
						->where("EMP_HCES1_LANG", \Config::get("app.emp"))
						->where("LANG_HCES1_LANG", $language_complete[$lang])
						->where("NUM_HCES1_LANG", $data['NUMHCES_ASIGL0'])
						->where("LIN_HCES1_LANG", $data['LINHCES_ASIGL0'])
						->update($info);
				} else {

					$info["EMP_HCES1_LANG"] = \Config::get("app.emp");
					$info["LANG_HCES1_LANG"] = $language_complete[$lang];
					$info["NUM_HCES1_LANG"] = $data['NUMHCES_ASIGL0'];
					$info["LIN_HCES1_LANG"] = $data['LINHCES_ASIGL0'];

					DB::table("FGHCES1_LANG")->insert($info);
				}
			}

			return redirect("/admin/subasta/edit/" . $data['SUB_ASIGL0']);
		} else {

			$booleans = array('COMPRA_ASIGL0', 'RETIRADO_ASIGL0', 'REMATE_ASIGL0', 'DESADJU_ASIGL0', 'OCULTO_ASIGL0', 'DESTACADO_ASIGL0', 'CERRADO_ASIGL0');

			foreach ($booleans as $boolean) {

				if (!isset($data[$boolean])) {
					$data[$boolean] = 'N';
				} else {
					$data[$boolean] = 'S';
				}
			}


			$num = FgHces0::getNumHces($data['SUB_ASIGL0']);
			//DB::table("FGHCES0")->where("emp_hces0", \Config::get("app.emp"))->max("num_hces0");
			//$num++;
			$lin = FgHces1::getLinHces($data['SUB_ASIGL0'],$num);
			//DB::table("FGHCES1")->where("emp_hces1", \Config::get("app.emp"))->where("num_hces1", $num)->max("lin_hces1");
			//$lin++;

			$infoHCES0 = [
				"EMP_HCES0" => \Config::get("app.emp"),
				"NUM_HCES0" => $num,
				"SUB_HCES0" => $data['SUB_ASIGL0'],
				"FEC_HCES0" => date("Y-m-d G:i:s")
			];

			DB::table("FGHCES0")->insert($infoHCES0);

			$infoHCES1 = [

				"EMP_HCES1" => \Config::get("app.emp"),
				"NUM_HCES1" => $num,
				"LIN_HCES1" => $lin,
				"SUB_HCES1" => $data['SUB_ASIGL0'],
				"REF_HCES1" => $data['REF_ASIGL0'],
				"IMPLIC_HCES1" => 0,
				"IMPSAL_HCES1" => $data['IMPSAL_HCES1'],
				"DIVISA_HCES1" => $data['DIVISA_HCES1'],
				"DESTACADO_HCES1" => $data['DESTACADO_ASIGL0'],
				"IMG360_HCES1" => $data['IMG360_HCES1'] ?? 'N',
				"CONTEXTRA_HCES1" => $data['CONTEXTRA_HCES1'],
				"SEC_HCES1" => $data['SEC_HCES1'],
				"DESC_HCES1" => $data['DESC_HCES1_LANG_es'] ?? '',
				"DESCDET_HCES1" => $data['DESCDET_HCES1_LANG_es'] ?? '',
				"WEBFRIEND_HCES1" => $data['WEBFRIEND_HCES1_LANG_es'] ?? '',
				"WEBMETAT_HCES1" => $data['WEBMETAT_HCES1_LANG_es'] ?? '',
				"WEBMETAD_HCES1" => $data['WEBMETAD_HCES1_LANG_es'] ?? '',
				"PROP_HCES1" => $data['PROP_HCES1'] ?? ''

			];

			DB::table("FGHCES1")->insert($infoHCES1);

			if (empty($data['HINI_ASIGL0']))
				$data['HINI_ASIGL0'] = "00:00";
			if (empty($data['HFIN_ASIGL0']))
				$data['HFIN_ASIGL0'] = "00:00";

			$infoAsigl0 = [
				"EMP_ASIGL0" => \Config::get("app.emp"),
				"SUB_ASIGL0" => $data['SUB_ASIGL0'],
				"REF_ASIGL0" => $data['REF_ASIGL0'],
				"NUMHCES_ASIGL0" => $num,
				"LINHCES_ASIGL0" => $lin,
				"IMPSALHCES_ASIGL0" => $data['IMPSAL_HCES1'],
				"IMPRES_ASIGL0" => $data['IMPRES_ASIGL0'] ?? 0,
				//pendiente de crearse -> "DEPOSITO_ASIGL0" => $data['DEPOSITO_ASIGL0'] ?? 0,
				"COMLHCES_ASIGL0" => $data['COMLHCES_ASIGL0'] ?? 0,
				"COMPHCES_ASIGL0" => $data['COMPHCES_ASIGL0'] ?? 0,
				"FINI_ASIGL0" => $data['FINI_ASIGL0'],
				"HINI_ASIGL0" => $data['HINI_ASIGL0'],
				"FFIN_ASIGL0" => $data['FFIN_ASIGL0'],
				"HFIN_ASIGL0" => $data['HFIN_ASIGL0'],
				"FFIN_ORIGINAL_ASIGL0" => $data['FFIN_ASIGL0'],
				"HFIN_ORIGINAL_ASIGL0" => $data['HFIN_ASIGL0'],
				"IMPTASH_ASIGL0" => $data['IMPTASH_ASIGL0'],
				"IMPTAS_ASIGL0" => $data['IMPTAS_ASIGL0'],
				"DESTACADO_ASIGL0" => $data['DESTACADO_ASIGL0'],
				"COMPRA_ASIGL0" => $data['COMPRA_ASIGL0'],
				"CERRADO_ASIGL0" => $data['CERRADO_ASIGL0'],
				"RETIRADO_ASIGL0" => $data['RETIRADO_ASIGL0'],
				"OCULTO_ASIGL0" => $data['OCULTO_ASIGL0'],
				"DESADJU_ASIGL0" => $data['DESADJU_ASIGL0'],
				"REMATE_ASIGL0" => $data['REMATE_ASIGL0'],
				"FECALTA_ASIGL0" => date("Y-m-d G:i:s"),
				"HORAALTA_ASIGL0" => date("G:i")
			];

			DB::table("FGASIGL0")->insert($infoAsigl0);

			return redirect("/admin/lote/edit/" . $data['SUB_ASIGL0'] . "/" . $num . "-" . $lin . "-" . $data['REF_ASIGL0']);
		}
	}

	/**
	 * Guardar con item
	 * */
	public function addLoteFile()
	{

		//obtener ficheros adjuntos
		$fichero = null;

		if (request()->hasFile('ficheroAdjunto')) {
			$fichero = request()->file('ficheroAdjunto');
		} else {
			return redirect()->back()
				->with(['errors' => [0 => 'Necesita adjuntar un fichero']]);
		}

		//datos
		$num_hces1 = request('num_hces1');
		$lin_hces1 = request('lin_hces1');


		$pathFiles = getcwd() . '/files/' . Config::get("app.emp") . "/$num_hces1/$lin_hces1/files/";
		$nameFile = $fichero->getClientOriginalName();

		if (!is_dir(str_replace("\\", "/", $pathFiles))) {
			mkdir(str_replace("\\", "/", $pathFiles), 0775, true);
		}

		$newfile = str_replace("\\", "/", $pathFiles . '/' . $nameFile);

		copy($fichero->getPathname(), $newfile);

		return redirect()->back()
			->with(['success' => [0 => 'Archivo añadido correctamente']]);
	}

	/**
	 * Eliminar archivo lote
	 * */
	function deleteLoteFile()
	{

		$num_hces1 = request('num_hces1');
		$lin_hces1 = request('lin_hces1');
		$file = request('file');

		if (empty($num_hces1) || empty($lin_hces1) || empty($file)) {
			return redirect()->back()
				->with(['errors' => [0 => 'Ha sucedido un error']]);
		}

		//Eliminar o no fichero...
		$pathFiles = getcwd() . '/files/' . Config::get("app.emp") . "/$num_hces1/$lin_hces1/files/$file";
		unlink(str_replace("\\", "/", $pathFiles));

		return redirect()->back()
			->with(['success' => [0 => 'Fichero eliminado']]);
	}


	public function addLoteVideo()
	{

		//obtener ficheros adjuntos
		$fichero = null;

		if (request()->hasFile('ficheroAdjunto')) {
			$fichero = request()->file('ficheroAdjunto');
		} else {
			return redirect()->back()
				->with(['errorsVideo' => [0 => 'Necesita adjuntar un fichero']]);
		}

		//datos
		$num_hces1 = request('num_hces1');
		$lin_hces1 = request('lin_hces1');


		$pathFiles = getcwd() . '/files/videos/' . Config::get("app.emp") . "/$num_hces1/$lin_hces1";
		$nameFile = $fichero->getClientOriginalName();

		if (!is_dir(str_replace("\\", "/", $pathFiles))) {
			mkdir(str_replace("\\", "/", $pathFiles), 0775, true);
		}

		$newfile = str_replace("\\", "/", $pathFiles . '/' . $nameFile);

		copy($fichero->getPathname(), $newfile);

		return redirect()->back()
			->with(['successVideo' => [0 => 'Video añadido correctamente']]);
	}

	/**
	 * Eliminar video lote
	 * */
	function deleteLoteVideo()
	{
		$num_hces1 = request('num_hces1');
		$lin_hces1 = request('lin_hces1');
		$file = request('file');

		if (empty($num_hces1) || empty($lin_hces1) || empty($file)) {
			return redirect()->back()
				->with(['errorsVideo' => [0 => 'Ha sucedido un error']]);
		}

		//Eliminar o no fichero...
		$pathFiles = getcwd() . '/files/videos/' . Config::get("app.emp") . "/$num_hces1/$lin_hces1/$file";
		unlink(str_replace("\\", "/", $pathFiles));

		return redirect()->back()
			->with(['successVideo' => [0 => 'Video eliminado']]);
	}



	public function borrarImagenLote()
	{


		$data = Input::all();

		if (!empty($data['url'])) {

			if (is_file(str_replace("\\", "/", getcwd() . $data['url']))) {
				unlink(str_replace("\\", "/", getcwd() . $data['url']));
			}
		}
	}


	public function guardarImagen($ficheros, $cod_sub, $cod_session = null)
	{

		$pathInicial = "/img/";
		$path = str_replace("\\", "/", getcwd() . $pathInicial);
		$countImage = 0;
		foreach ($ficheros as $k => $item) {

			if (!empty($item['tmp_name'])) {

				$extension = explode(".", $item['name']);
				$extension = $extension[sizeof($extension) - 1];

				$size = getimagesize($item['tmp_name']);
				if ($size[0] > 2000) {
					$w = 2000;
					$h = $size[1] * 2000 / $size[0];
				} else {
					$w = $size[0];
					$h = $size[1];
				}

				if ($extension == "png") {
					$src_image = imagecreatefrompng($item['tmp_name']);
				} elseif (in_array($extension, ["jpg", "jpeg", "JPEG", 'JPG'])) {
					$src_image = imagecreatefromjpeg($item['tmp_name']);
				}

				$dst_image = imagecreatetruecolor($w, $h);

				$blanco = imagecolorallocate($src_image, 255, 255, 255);
				imagefill($dst_image, 0, 0, $blanco);

				#imagecopy($dst_image, $src_image, 0, 0, 0, 0, $size[0], $size[1]);
				imagecopyresampled($dst_image, $src_image, 0, 0,0, 0, $w, $h, $size[0], $size[1]);
				$countImage++;
				$countImage = str_pad($countImage, 3, "0", STR_PAD_LEFT);

				//imagejpeg($dst_image, $path . "/SESSION_" . \Config::get('app.emp') . "_" . $cod_sub . "_" . $countImage . ".JPEG");
				imagejpeg($dst_image, $path . "/AUCTION_" . \Config::get('app.emp') . "_" . $cod_sub . ".JPEG");

				if(!file_exists($path . "/SESSION_" . \Config::get('app.emp') . "_" . $cod_sub . "_001.JPEG")){
					imagejpeg($dst_image, $path . "/SESSION_" . \Config::get('app.emp') . "_" . $cod_sub . "_001.JPEG");
				}
			}
		}
	}

	public function guardarImagenLote($ficheros, $num_hces1, $lin_hces1)
	{

		$path = str_replace("\\", "/", getcwd() . "/img/" . \Config::get('app.emp'));
		if (!is_dir($path)) {
			mkdir($path);
			chmod($path, 0755);
		}

		$pathInicial = "/img/" . \Config::get('app.emp') . "/" . $num_hces1;
		$path = str_replace("\\", "/", getcwd() . $pathInicial);
		if (!is_dir($path)) {
			mkdir($path);
			chmod($path, 0755);
		}

		$countImage = 0;
		foreach ($ficheros as $k => $item) {

			if (!empty($item['tmp_name'])) {

				$extension = explode(".", $item['name']);
				$extension = $extension[sizeof($extension) - 1];

				$size = getimagesize($item['tmp_name']);
				if ($size[0] > 2000) {
					$w = 2000;
					$h = $size[1] * 2000 / $size[0];
				} else {
					$w = $size[0];
					$h = $size[1];
				}

				if ($extension == "png") {
					$src_image = imagecreatefrompng($item['tmp_name']);
				} elseif (in_array($extension, ["jpg", "jpeg", "JPEG", 'JPG'])) {
					$src_image = imagecreatefromjpeg($item['tmp_name']);
				}

				$dst_image = imagecreatetruecolor($size[0], $size[1]);

				$blanco = imagecolorallocate($src_image, 255, 255, 255);
				imagefill($dst_image, 0, 0, $blanco);

				imagecopy($dst_image, $src_image, 0, 0, 0, 0, $size[0], $size[1]);
				$countImage++;

				if ($countImage < 10) {
					$count = '00' . $countImage;
				} else if ($countImage > 10 && $countImage < 100) {
					$count = '0' . $countImage;
				} else {
					$count = $countImage;
				}

				$newimage = \Config::get('app.emp') . "-" . $num_hces1 . "-" . $lin_hces1;

				if (is_file($path . "/" . $newimage . ".jpg")) {

					for ($t = 1; $t < 20; $t++) {
						if ($t < 10) {
							$t = "0" . $t;
						}

						$newimage = \Config::get('app.emp') . "-" . $num_hces1 . "-" . $lin_hces1 . "_" . $t;

						if (!is_file($path . "/" . $newimage . ".jpg")) {
							break;
						}
					}
				}

				imagejpeg($dst_image, $path . "/" . $newimage . ".jpg");
				$this->createThumbs(\Config::get('app.emp'), $num_hces1, $newimage.".jpg");

			}
		}
	}

	private function createPath($emp, $num,$path = "img"){
        $this->createFolder($path);
        $this->createFolder($path."/$emp");
        $this->createFolder($path."/$emp/$num");
    }

    private function createFolder($folderPath){
        if (!file_exists($folderPath))
        {
            mkdir($folderPath, 0775, true);
            chmod($folderPath,0775);
        }
    }

    private function createThumbs($emp_hces1, $num_hces1, $name_img){
        $sizes = Web_Images_Size::getSizes();
        $imageGenerate = new ImageGenerate();
        $path = "img/thumbs/";

        $this->createPath($emp_hces1, $num_hces1,  $path.$sizes['lote_small']);
        $imageGenerate->generateMini($name_img,$sizes['lote_small']);
        $this->createPath($emp_hces1, $num_hces1,  $path.$sizes['lote_medium']);
        $imageGenerate->generateMini($name_img,$sizes['lote_medium']);
        $this->createPath($emp_hces1, $num_hces1,  $path.$sizes['lote_medium_large']);

        $imageGenerate->generateMini($name_img,$sizes['lote_medium_large']);
    }

	function lotFile($subasta)
	{

		$data = array('subasta' => $subasta);

		return \View::make('admin::pages.subasta.lote.importLotFile', $data);
	}


	function lotFileImport($type){
		if($type=="Excel"){
			return $this->newSubirExcel();
		}elseif($type="Dapda"){
			return $this->loadFileXml("Dapda");
		}

	}

	function loadFileXml($type){
	 $idAuction = request()->input('subasta');
	 $file = Input::file('file');
	 $xml =  simplexml_load_file($file);
	 $loadFileLib = new	LoadLotFileLib($idAuction);
	 return $loadFileLib->loadMotorFlash($xml);
	}

	/**
	 * @todo comprobar que las cabeceras sean correctas
	 * @todo ¿comprobar el tipo de datos que me llega? si son de texto funcionan
	 */
	function newSubirExcel()
	{

		$idAuction = request()->input('subasta');
		$file = Input::file('file');
		$rows = Excel::toArray(new ExcelImport, $file)[0];

		$cabeceras = $this->orderTitlesExcel($rows[0]);

		$idOrigins = LoadLotFileLib::existingLots($idAuction);

		$datesFgSub = FgSub::select('dfec_sub', 'dhora_sub', 'hfec_sub', 'hhora_sub', 'tipo_sub')->where('cod_sub', $idAuction)->first();

		$maxReference = FgAsigl0::where('sub_asigl0', $idAuction)->max('ref_asigl0') ?? 0;

		$lots = array();
		$newLots = array();
		$updateLots = array();
		$addTime = 0;

		for ($i = 1; $i < count($rows); $i++) {

			if(empty(trim($rows[$i][0]))){
				continue;
			}

			$lot = $this->createLotObject($rows[$i], $cabeceras);

			$lot['idauction'] = $idAuction;

			$lot = $this->addFgSubDates($lot, $datesFgSub, $addTime);

			if($datesFgSub->tipo_sub == 'O'){
				$addTime += Config::get('app.increment_endlot_online', 60);
			}

			if(empty($lot['reflot'])){
				$lot['reflot'] = ++$maxReference;
			}

			if(empty($lot['idorigin'])){
				$lot['idorigin'] = $idAuction . "-" . $lot['reflot'];
			}

			$lots[] = $lot;

			$exist = (array_key_exists($lot['idorigin'], $idOrigins)) ? true : false;

			if ($exist) {
				$updateLots[] = $lot;
			} else {
				$newLots[] = $lot;
			}
		}

		$lotControler = new LotController();

		if (!empty($newLots)) {

			$json = $lotControler->createLot($newLots);
			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				Log::emergency($json);
				return response($json, 400);
			}
		}

		if (!empty($updateLots)) {

			$json = $lotControler->updateLot($updateLots);
			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				Log::emergency($json);
				return response($json, 400);
			}
		}

		$img = $this->createImgObject($lots);
		return response($img, 200);
	}

	function addFgSubDates($lot, $datesFgSub, $addTime){

		if(!empty($lot['startdate'])){
			return $lot;
		}

		$lot['startdate'] = date('Y-m-d', strtotime($datesFgSub['dfec_sub']));
		$lot['starthour'] = date('H:i:s', strtotime($datesFgSub['dhora_sub']));

		$dateEnd = new DateTime(date('Y-m-d', strtotime($datesFgSub['hfec_sub'])) . ' ' . $datesFgSub['hhora_sub']);

		if(!empty($addTime)){
			$dateEnd = $dateEnd->add(new DateInterval('PT'.$addTime."S"));
		}

		$lot['enddate'] = $dateEnd->format('Y-m-d');
		$lot['endhour'] = $dateEnd->format('H:i:s');
		return $lot;
	}

	function createExcelImage(){

		$item = array();
		$item['idoriginlot'] = request('idoriginlot');
		$item['order'] = request('order');
		$item['img'] = request('img');

		$array = array();
		$array[] = $item;

		$imgController = new ImgController();
		$json = $imgController->createImg($array);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return response($json, 400);
		}

		return response($json, 200);

	}

	function addImage(){

		$this->guardarImagenLote($_FILES, request('num_hces1'), request('lin_hces1'));
		return response('ok', 200);

	}

	function orderTitlesExcel($cabecera)
	{

		$array[] = array();
		foreach ($cabecera as $column => $value) {
			$array[$column] = $value;
		}
		return $array;
	}

	function createLotObject($rows, $propiedades)
	{
		$object = array();

		foreach ($rows as $key => $value) {
			if($propiedades[$key] == 'startdate' || $propiedades[$key] == 'enddate'){
				$object[$propiedades[$key]] = $this->transformDate($value);
			}
			elseif ($propiedades[$key] == 'starthour' || $propiedades[$key] == 'endhour') {
				$object[$propiedades[$key]] = $this->transformDate($value, 'H:i:s');
			}
			elseif(Str::contains($propiedades[$key], '/') && count(explode('/', $propiedades[$key])) == 3){

				[$relacion, $valorRelacion, $propiedadRelacion] = explode('/', $propiedades[$key]);

				/**
				 * en relacion lang valorRelacion es idioma (EN) y valor el campo relleno
				 */

				if($relacion == 'lang' && !empty($value)){
					$object['languages'][$valorRelacion][$relacion] = $valorRelacion;
					$object['languages'][$valorRelacion][$propiedadRelacion] = str_replace("\n","<br>", $value);
				}

			}
			elseif(Str::contains($propiedades[$key], '/') && count(explode('/', $propiedades[$key])) == 2){

				[$relacion, $idNameRelacion] = explode('/', $propiedades[$key]);

				if($relacion == 'feature' && !empty($value)){

					if(!$this->fgCaracteristicas){
						$this->fgCaracteristicas = FgCaracteristicas::select('name_caracteristicas', 'id_caracteristicas', 'filtro_caracteristicas','value_caracteristicas')->get();
						$this->featureValues = $this->existingFeatureValues();
					}

					$object['features'][$idNameRelacion] = $this->addFeaturePorperty($idNameRelacion, $value, $this->featureValues);
				}

			}
			elseif($propiedades[$key] == 'description'){
				$object[$propiedades[$key]] = str_replace("\n","<br>", $value);
			}
			else{
				$object[$propiedades[$key]] = $value;
			}

		}

		return $object;
	}

	public function addFeaturePorperty($featureName, $value, &$featurValues = array())
	{
		$feature = [];

		$caracteristica = $this->fgCaracteristicas->filter(function($item) use ($featureName){
			return mb_strtolower($item->name_caracteristicas) == mb_strtolower($featureName);
		})->first();

		if($caracteristica){

			if($caracteristica->value_caracteristicas == 'N'){
				$idvaluefeature = null;
				$newValue = $value;
			}
			else{
				$idvaluefeature = (FgCaracteristicas_Value::addFeature($caracteristica->id_caracteristicas, $value,  $featurValues))['idFeatureValue'];
				$newValue = null;
			}

			$feature['idfeature'] = $caracteristica->id_caracteristicas;
			$feature['idvaluefeature'] = $idvaluefeature;
			$feature['value'] = $newValue;

		}

		return $feature;
	}

	public function transformDate($value, $format = 'Y-m-d')
	{
		try {
			return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format($format);
		} catch (\ErrorException $e) {
			return date($format, strtotime($value));
		}
	}

	function createImgObject($lots)
	{
		$object = array();

		foreach ($lots as $lot) {

			if(empty($lot['img']) || trim($lot['img']) == ""){
				continue;
			}

			$stringImage = $lot['img'];
			$images = explode("|", $stringImage);

			foreach ($images as $key => $img) {
				$item = array();
				$item['idoriginlot'] = $lot['idorigin'];
				$item['order'] = $key;
				$item['img'] = trim($img);

				$object[] = $item;
			}
		}

		return $object;
	}

	function export($cod_sub){
		return (new PujasExport($cod_sub))->download("pujas_subasta_$cod_sub" . "_" . date("Ymd") . ".xlsx");
	}



	function subirExcel()
	{

		$data = Input::all();

		if (($gestor = fopen($_FILES['csv']['tmp_name'], "r")) !== FALSE) {

			$k = 0;
			$ficheros = array();
			while (($info = fgetcsv($gestor, 0, ";")) !== FALSE) {


				if ($k == 0) {
					$k++;
					continue;
				}

				$hayRegistro = DB::table("FGHCES1")->where("EMP_HCES1", \Config::get("app.emp"))->where("SUB_HCES1", $data['subasta'])->where("REF_HCES1", $info[1])->first();


				// Adaptamos la info

				$info[5] = explode(" ", $info[5])[0];
				$info[5] = str_replace(",", ".", str_replace(".", "", $info[5]));
				$info[6] = str_replace(",", ".", str_replace(".", "", $info[6]));
				$info[7] = str_replace(",", ".", str_replace(".", "", $info[7]));
				$info[8] = str_replace(",", ".", str_replace(".", "", $info[8]));
				$info[9] = str_replace(",", ".", str_replace(".", "", $info[9]));

				if ($info[5] == "") $info[5] = 0;
				if ($info[6] == "") $info[6] = 0;
				if ($info[7] == "") $info[7] = 0;
				if ($info[8] == "") $info[8] = 0;
				if ($info[9] == "") $info[9] = 0;


				// Obtenemos divisa y calculamos precios en euros y dolares

				$divisa = "EUR";
				if (!empty(($info[14]))) {
					$divisa = $info[14];
				}

				$currency = new Currency();
				$divisas = $currency->getAllCurrencies();

				if ($divisa != "EUR") {
					$info[5] = sprintf("%.2f", $divisas[$divisa]->impd_div * $info[5]);
					$info[6] = sprintf("%.2f", $divisas[$divisa]->impd_div * $info[6]);
					$info[7] = sprintf("%.2f", $divisas[$divisa]->impd_div * $info[7]);
					$info[8] = sprintf("%.2f", $divisas[$divisa]->impd_div * $info[8]);
					$info[9] = sprintf("%.2f", $divisas[$divisa]->impd_div * $info[9]);
				}


				$info[13] = substr($info[13], 0, 1);

				$info[2] = utf8_encode($info[2]);
				$info[4] = utf8_encode($info[4]);
				$info[15] = utf8_encode($info[15]);

				if (!empty($hayRegistro)) {

					$infoHCES1 = [
						"IMPSAL_HCES1" => $info[5],
						"DESTACADO_HCES1" => $info[11],
						"DIVISA_HCES1" => $divisa
					];

					DB::table("FGHCES1")->where("EMP_HCES1", \Config::get("app.emp"))->where("SUB_HCES1", $data['subasta'])->where("REF_HCES1", $info[1])->update($infoHCES1);


					if (empty($data['HINI_ASIGL0']))
						$data['HINI_ASIGL0'] = "00:00";
					if (empty($data['HFIN_ASIGL0']))
						$data['HFIN_ASIGL0'] = "00:00";
					$infoAsigl0 = [
						"IMPSALHCES_ASIGL0" => $info[5],
						"FINI_ASIGL0" => date('Y-m-d'),
						"HINI_ASIGL0" => $data['HINI_ASIGL0'],
						"FFIN_ASIGL0" => date('Y-m-d', (time() + (3600 * 24 * 30))),
						"HFIN_ASIGL0" => $data['HFIN_ASIGL0'],
						"FFIN_ORIGINAL_ASIGL0" => date('Y-m-d', (time() + (3600 * 24 * 30))),
						"HFIN_ORIGINAL_ASIGL0" => $data['HFIN_ASIGL0'],
						"IMPTASH_ASIGL0" => $info[6],
						"IMPTAS_ASIGL0" => $info[7],
						"DESTACADO_ASIGL0" => $info[11],
						"COMPRA_ASIGL0" => $info[12],
						"CERRADO_ASIGL0" => 'N',
						"RETIRADO_ASIGL0" => 'N',
						"OCULTO_ASIGL0" => 'N',
						"DESADJU_ASIGL0" => 'N',
						"REMATE_ASIGL0" => $info[13]
					];

					DB::table('FGASIGL0')->where("EMP_ASIGL0", \Config::get("app.emp"))->where("SUB_ASIGL0", $data['subasta'])->where("REF_ASIGL0", $info[1])->update($infoAsigl0);


					$infoHCES1 = [
						"TITULO_HCES1_LANG" => substr($info[2], 0, 60),
						"DESCWEB_HCES1_LANG" => $info[2],
						"WEBMETAT_HCES1_LANG" => substr($info[2], 0, 60),
						"WEBMETAD_HCES1_LANG" => substr($info[2], 0, 150),
						"WEBFRIEND_HCES1_LANG" => \Tools::Seo_url(substr($info[2], 0, 100)),
						"DESC_HCES1_LANG" => $info[4],
						"DESCDET_HCES1_LANG" => $info[15],
					];

					$existeRegistro = DB::table("FGHCES1_LANG")
						->where("EMP_HCES1_LANG", \Config::get("app.emp"))
						->where("LANG_HCES1_LANG", $info[3])
						->where("NUM_HCES1_LANG", $hayRegistro->num_hces1)
						->where("LIN_HCES1_LANG", $hayRegistro->lin_hces1)->first();

					if ($existeRegistro) {

						DB::table("FGHCES1_LANG")
							->where("EMP_HCES1_LANG", \Config::get("app.emp"))
							->where("LANG_HCES1_LANG", $info[3])
							->where("NUM_HCES1_LANG", $hayRegistro->num_hces1)
							->where("LIN_HCES1_LANG", $hayRegistro->lin_hces1)
							->update($infoHCES1);
					} else {

						$infoHCES1["EMP_HCES1_LANG"] = \Config::get("app.emp");
						$infoHCES1["LANG_HCES1_LANG"] = $info[3];
						$infoHCES1["NUM_HCES1_LANG"] = $hayRegistro->num_hces1;
						$infoHCES1["LIN_HCES1_LANG"] = $hayRegistro->lin_hces1;

						DB::table("FGHCES1_LANG")->insert($infoHCES1);
					}
					$num = $hayRegistro->num_hces1;
					$lin = $hayRegistro->lin_hces1;
				} else {

					$num = DB::table("FGHCES0")->where("emp_hces0", \Config::get("app.emp"))->max("num_hces0");
					$num++;
					$lin = DB::table("FGHCES1")->where("emp_hces1", \Config::get("app.emp"))->where("num_hces1", $num)->max("lin_hces1");
					$lin++;

					$infoHCES0 = [
						"EMP_HCES0" => \Config::get("app.emp"),
						"NUM_HCES0" => $num,
						"SUB_HCES0" => $data['subasta'],
						"FEC_HCES0" => date("Y-m-d G:i:s")
					];

					DB::table("FGHCES0")->insert($infoHCES0);

					$infoHCES1 = [

						"EMP_HCES1" => \Config::get("app.emp"),
						"NUM_HCES1" => $num,
						"LIN_HCES1" => $lin,
						"SUB_HCES1" => $data['subasta'],
						"REF_HCES1" => $info[1],
						"IMPLIC_HCES1" => 0,
						"IMPSAL_HCES1" => $info[5],
						"DESTACADO_HCES1" => $info['11'],
						"DIVISA_HCES1" => $divisa
					];

					DB::table("FGHCES1")->insert($infoHCES1);

					if (empty($data['HINI_ASIGL0']))
						$data['HINI_ASIGL0'] = "00:00";
					if (empty($data['HFIN_ASIGL0']))
						$data['HFIN_ASIGL0'] = "00:00";

					$infoAsigl0 = [
						"EMP_ASIGL0" => \Config::get("app.emp"),
						"SUB_ASIGL0" => $data['subasta'],
						"REF_ASIGL0" => $info['1'],
						"NUMHCES_ASIGL0" => $num,
						"LINHCES_ASIGL0" => $lin,
						"IMPSALHCES_ASIGL0" => $info['5'],
						"FINI_ASIGL0" => date('Y-m-d'),
						"HINI_ASIGL0" => $data['HINI_ASIGL0'],
						"FFIN_ASIGL0" => date('Y-m-d', (time() + (3600 * 24 * 30))),
						"HFIN_ASIGL0" => $data['HFIN_ASIGL0'],
						"FFIN_ORIGINAL_ASIGL0" => date('Y-m-d', (time() + (3600 * 24 * 30))),
						"HFIN_ORIGINAL_ASIGL0" => $data['HFIN_ASIGL0'],
						"IMPTASH_ASIGL0" => $info[6],
						"IMPTAS_ASIGL0" => $info[7],
						"DESTACADO_ASIGL0" => $info['11'],
						"COMPRA_ASIGL0" => $info['12'],
						"CERRADO_ASIGL0" => 'N',
						"RETIRADO_ASIGL0" => 'N',
						"OCULTO_ASIGL0" => 'N',
						"DESADJU_ASIGL0" => 'N',
						"REMATE_ASIGL0" => $info['13'],
						"FECALTA_ASIGL0" => date("Y-m-d G:i:s"),
						"HORAALTA_ASIGL0" => date("G:i")
					];

					DB::table("FGASIGL0")->insert($infoAsigl0);

					$infoHCES1 = [
						"TITULO_HCES1_LANG" => substr($info[2], 0, 60),
						"DESCWEB_HCES1_LANG" => $info[2],
						"WEBMETAT_HCES1_LANG" => substr($info[2], 0, 60),
						"WEBMETAD_HCES1_LANG" => substr($info[2], 0, 150),
						"WEBFRIEND_HCES1_LANG" => \Tools::Seo_url(substr($info[2], 0, 100)),
						"DESC_HCES1_LANG" => $info[4],
						"DESCDET_HCES1_LANG" => $info[15]
					];

					$existeRegistro = DB::table("FGHCES1_LANG")
						->where("EMP_HCES1_LANG", \Config::get("app.emp"))
						->where("LANG_HCES1_LANG", $info[3])
						->where("NUM_HCES1_LANG", $num)
						->where("LIN_HCES1_LANG", $lin)->first();

					if ($existeRegistro) {

						DB::table("FGHCES1_LANG")
							->where("EMP_HCES1_LANG", \Config::get("app.emp"))
							->where("LANG_HCES1_LANG", $info[3])
							->where("NUM_HCES1_LANG", $num)
							->where("LIN_HCES1_LANG", $lin)
							->update($infoHCES1);
					} else {

						$infoHCES1["EMP_HCES1_LANG"] = \Config::get("app.emp");
						$infoHCES1["LANG_HCES1_LANG"] = $info[3];
						$infoHCES1["NUM_HCES1_LANG"] = $num;
						$infoHCES1["LIN_HCES1_LANG"] = $lin;

						DB::table("FGHCES1_LANG")->insert($infoHCES1);
					}
				}

				if (!empty($info[10]) && $info[10] != "" && $info[10] != " ") {
					$images = explode("|", $info[10]);
					foreach ($images as $image) {
						$ficheros[$image . "---" . $num . "---" . $lin] = trim($image);
					}
					exec("rm -R " . str_replace("\\", "/", getcwd()) . "/img/" . \Config::get('app.emp') . "/" . $num . "/*");
				}

				$k++;
			}

			foreach ($ficheros as $kkk => $item) {

				$b = explode("---", $kkk);
				$num = $b[1];
				$lin = $b[2];

				$a = explode("/", $item);
				$a = $a[sizeof($a) - 1];
				$file_aux = file_get_contents($item);
				$fp = fopen(str_replace("\\", "/", getcwd()) . "/img/temp", "w");
				fwrite($fp, $file_aux);
				fclose($fp);
				$file[0] = array(
					"tmp_name" => str_replace("\\", "/", getcwd()) . "/img/temp",
					"name" => $a
				);

				$this->guardarImagenLote($file, $num, $lin);

				unlink(str_replace("\\", "/", getcwd()) . "/img/temp");
			}
		}

		return redirect("/admin/subasta/edit/" . $data['subasta']);
	}

	function borrarSubasta()
	{

		$data = Input::all();

		DB::table("FGSUB")->where("EMP_SUB", \Config::get("app.emp"))->where("COD_SUB", $data['item'])->delete();
		DB::table("FGSUB_LANG")->where("EMP_SUB_LANG", \Config::get("app.emp"))->where("COD_SUB_LANG", $data['item'])->delete();
		DB::table('"auc_sessions"')->where('"company"', \Config::get("app.emp"))->where('"auction"', $data['item'])->delete();
		DB::table("FGASIGL0")->where("EMP_ASIGL0", \Config::get("app.emp"))->where("SUB_ASIGL0", $data['item'])->delete();
		DB::table("FGHCES0")->where("EMP_HCES0", \Config::get("app.emp"))->where("SUB_HCES0", $data['item'])->delete();

		$lotes = DB::table("FGHCES1")->where("EMP_HCES1", \Config::get("app.emp"))->where("SUB_HCES1", $data['item'])->get();

		foreach ($lotes as $lote) {

			DB::table("FGHCES1_LANG")->where("EMP_HCES1_LANG", \Config::get("app.emp"))->where("NUM_HCES1_LANG", $lote->num_hces1)->where("LIN_HCES1_LANG", $lote->lin_hces1)->delete();
		}

		DB::table("FGHCES1")->where("EMP_HCES1", \Config::get("app.emp"))->where("SUB_HCES1", $data['item'])->delete();

		echo "OK";
	}




	function borrarLote($subasta, $id = 0)
	{

		$a = explode("-", $id);
		$num = $a[0];
		$lin = $a[1];
		$ref = $a[2];

		DB::table('FGASIGL0')->where("EMP_ASIGL0", \Config::get("app.emp"))->where("SUB_ASIGL0", $subasta)->where("REF_ASIGL0", $ref)->where("LINHCES_ASIGL0", $lin)->where("NUMHCES_ASIGL0", $num)->delete();


		DB::table("FGHCES1")->where("EMP_HCES1", \Config::get("app.emp"))->where("SUB_HCES1", $subasta)
			->where("NUM_HCES1", $num)
			->where("LIN_HCES1", $lin)
			->where("REF_HCES1", $ref)
			->delete();

		DB::table("FGHCES1_LANG")->where("EMP_HCES1_LANG", \Config::get("app.emp"))
			->where("NUM_HCES1_LANG", $num)
			->where("LIN_HCES1_LANG", $lin)
			->delete();

		echo "OK";
	}


	function ficherosSubasta($subasta)
	{

		if (!is_dir(str_replace("\\", "/", getcwd() . '/files/' . $subasta))) {
			mkdir(str_replace("\\", "/", getcwd() . '/files/' . $subasta));
		}

		$newfile = str_replace("\\", "/", getcwd() . '/files/' . $subasta . '/' . $_FILES['fichero_adjunto']['name']);

		copy($_FILES['fichero_adjunto']['tmp_name'], $newfile);

		return redirect("/admin/subasta/edit/" . $subasta);
	}


	function borrarFicherosSubasta()
	{


		$info = Input::all();

		unlink(str_replace("\\", "/", getcwd() . '/files/' . $info['subasta'] . '/' . $info['item']));

		echo "OK";
	}


	function borrarPuja()
	{

		$info = Input::all();

		$asigl0 = DB::table("FGASIGL0")->where("EMP_ASIGL0", \Config::get("app.emp"))->where("SUB_ASIGL0", $info['subasta'])->where("ref_asigl0", $info['ref'])->first();

		DB::table("FGASIGL1")->where("EMP_ASIGL1", \Config::get("app.emp"))->where("SUB_ASIGL1", $info['subasta'])->where("ref_asigl1", $info['ref'])->where("lin_asigl1", $info['lin'])->delete();

		$pujaMasAlta = DB::table("FGASIGL1")->where("EMP_ASIGL1", \Config::get("app.emp"))->where("SUB_ASIGL1", $info['subasta'])->where("ref_asigl1", $info["ref"])->orderBy("lin_asigl1", "desc")->first();


		if (!empty($pujaMasAlta)) {
			$nuevo_importe = $pujaMasAlta->imp_asigl1;
			$lic_hces1 = "S";
		} else {
			$nuevo_importe = 0;
			$lic_hces1 = "N";
		}
		DB::table("FGHCES1")->where("emp_hces1", \Config::get("app.emp"))->where("num_hces1", $asigl0->numhces_asigl0)->where("lin_hces1", $asigl0->linhces_asigl0)->update([
			"implic_hces1" => $nuevo_importe,
			"lic_hces1" => $lic_hces1
		]);



		echo "OK";
	}


	function borrarOrden()
	{

		$info = Input::all();

		DB::table("FGORLIC")->where("EMP_ORLIC", \Config::get("app.emp"))->where("SUB_ORLIC", $info['subasta'])->where("REF_ORLIC", $info['ref'])->where("LIN_ORLIC", $info['lin'])->delete();

		echo "OK";
	}


	public function guardaEscalado()
	{

		$info = Input::all();

		DB::table("FGPUJASSUB")->where("emp_pujassub", \Config::get("app.emp"))->where("sub_pujassub", $info['sub'])->delete();

		for ($t = 0; $t < 10; $t++) {
			if (!empty($info['importe' . $t]) && !empty($info['puja' . $t])) {
				DB::table("FGPUJASSUB")->insert([
					"emp_pujassub" => \Config::get("app.emp"),
					"imp_pujassub" => $info['importe' . $t],
					"puja_pujassub" => $info['puja' . $t],
					"sub_pujassub" => $info['sub'],
					"lin_pujassub" => $t + 1
				]);
			}
		}

		return redirect("/admin/subasta/edit/" . $info['sub']);
	}

	function getSelectSubastas($ajax = true)
	{

		$subastas = FgSub::select('COD_SUB as id', 'DES_SUB as html')
			->where('SUBC_SUB', '<>', 'N')->get();

		if($ajax){
			return response($subastas, 200);
		}

		return $subastas;
	}

	function getSelect2List()
	{

		$query =  mb_strtoupper(request('q'));

		if(!empty($query)){

			$where = [
				['upper(COD_SUB)', 'LIKE', "%$query%", 'or'],
				['upper(DES_SUB)', 'LIKE', "%$query%", 'or']
			];

			$fgSub = FgSub::select('COD_SUB as id', 'DES_SUB as html')->where($where)->get();

			return response()->json($fgSub);
		}

		return response();
	}


	function getSelectLotes()
	{

		$idauction = request('idauction');

		if(empty($idauction)){
			return response('Id subasta obligatorio', 400);
		}

		$where = [
			'idauction' => $idauction
		];

		$lotControler = new LotController();
		$json = $lotControler->showLot($where);
		$result = json_decode($json);

		if ($result->status == 'ERROR' || empty($result->items)) {
			return response($json, 400);
		}
		if (empty($result->items)) {
			return response('No items', 400);
		}

		$lots = [];
		foreach ($result->items as $key => $value) {
			$lots[$key] = [
				'id' => $value->reflot,
				'html' => "$value->reflot - $value->title"
			];
		}

		return response($lots, 200);
	}

	function getSelectLotesFondoGaleria()
	{
		$query = request('q');
		$lots = [];
		if(!empty($query)){

			$fgasigl0 = FgAsigl0::select("SUB_ASIGL0, REF_ASIGL0, DESCWEB_HCES1, VALUE_CARACTERISTICAS_VALUE, IMPSALHCES_ASIGL0")
						->JoinFghces1Asigl0()
						->joinFgCaracteristicasAsigl0()
						->joinFgCaracteristicasHces1Asigl0()
						->joinFgCaracteristicasValueAsigl0()
						->where("IDCAR_CARACTERISTICAS_VALUE", \Config::get("app.ArtistCode"))
						->whereRaw("( (upper(descweb_hces1) like ?)  OR (upper(value_caracteristicas_value) like ?) )",["%".mb_strtoupper($query)."%","%".mb_strtoupper($query)."%"])

						->where("pc_hces1",">",0)

						->where("impsalhces_asigl0",">",0)

						->get();


			foreach($fgasigl0 as $lot){
				$lots[] = [
					'id' => $lot->sub_asigl0."-".$lot->ref_asigl0,
					'html' =>   $lot->descweb_hces1." - ".$lot->value_caracteristicas_value . " - ". \Tools::moneyFormat($lot->impsalhces_asigl0,"€")
				];
			}

		}

		return response($lots, 200);
	}

	function getSelectClients(){

		$query =  mb_strtoupper(request('q'));

		if(!empty($query)){

			$where = [
				['RSOC_CLI', 'LIKE', "%$query%", 'or'],
				['COD2_CLI', '=', "$query", 'or'],
				['COD_CLI', '=', "$query", 'or']
			];

			$clients = FxCli::select('RSOC_CLI', 'COD_CLI')->where($where)->get();

			return response()->json($clients);
		}

		return response();

	}

	function getSelectLicits(){

		$idauction = request('idauction');

		if(empty($idauction)){
			return response('Id subasta obligatorio', 400);
		}

		$licitadores = FgLicit::select('cod_licit', 'rsoc_licit', 'cod2_cli')->joinCli()->where("SUB_LICIT", $idauction)->get();

		if (empty($licitadores)) {
			return response('No items', 400);
		}

		$licits = [];
		foreach ($licitadores as $key => $value) {
			$licits[$key] = [
				'id' => $value->cod_licit,
				'html' => "$value->cod_licit - $value->rsoc_licit"
			];
		}

		return response($licits, 200);
	}

	function getSelect2ClientList(){

		$query =  mb_strtoupper(request('q'));

		if(!empty($query)){

			$where = [
				['upper(RSOC_CLI)', 'LIKE', "%$query%", 'or'],
				['upper(COD2_CLI)', 'LIKE', "%$query%", 'or'],
				['upper(COD_CLI)', 'LIKE', "%$query%", 'or']
			];

			$clients = FxCli::select('RSOC_CLI as html', 'COD_CLI as id')->where($where)->get();

			return response()->json($clients);
		}

		return response();
	}

	function existingFeatureValues(){
		$featureValue = array();

		foreach (FgCaracteristicas_Value::get() as $feature){
			if( empty($featureValue[$feature->idcar_caracteristicas_value]) ){
				$featureValue[$feature->idcar_caracteristicas_value]=array();
			}
			$featureValue[$feature->idcar_caracteristicas_value][$feature->id_caracteristicas_value] = $feature->value_caracteristicas_value;
		}


		return $featureValue;
	}
}
