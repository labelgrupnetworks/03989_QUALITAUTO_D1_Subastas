<?php

namespace App\Http\Controllers\admin\V5;

use Illuminate\Support\Facades\DB;
use Controller;
use View;
use Session;
use Route;
use Input;

use App\Models\V5\Web_Faq;
use App\Models\V5\Web_FaqCat;

use App\libs\FormLib;
use App\libs\MessageLib;

class AdminFaqController extends Controller
{

	// Administrar la información de las casas de subastas

	/*
     * Pendiente mirar de guardar en alguna variable el idioma actual
     */

	public function index($lang = 'es')
	{


		$data = array("faqs" => array(), "catsU" => array());

		$a = WEB_Faq::where("LANG_FAQ", strtoupper($lang))->orderBy('position', 'asc')->get();

		foreach ($a as $item) {
			if (!isset($data['faqs'][$item->cod_faqcat]))
				$data['faqs'][$item->cod_faqcat] = array();
			$data['faqs'][$item->cod_faqcat][] = $item;
		}

		$cats = WEB_FaqCat::where("LANG_FAQCAT", strtoupper($lang))->orderBy('position', 'asc')->get();

		foreach ($cats as $item) {
			if ($item->parent_faqcat == 0) {
				$data['catsU'][$item->cod_faqcat] = array(
					"info" => $item,
					"items" => array()
				);
			}
		}

		foreach ($cats as $item) {
			if ($item->parent_faqcat != 0) {
				$data['catsU'][$item->parent_faqcat]['items'][$item->cod_faqcat] = $item;
			}
		}

		$data['lang'] = $lang;

		return \View::make('admin::pages.V5.admin-blades.faqList', array('data' => $data));
	}

	public function edit($lang = 'es', $cod_faq = 0)
	{

		$data = array("info" => array());

		$cats = WEB_FAQCAT::getInlineOrderedFaqCat();

		if ($cod_faq) {
			$aux = WEB_FAQ::where("COD_FAQ", $cod_faq)->get();
			foreach ($aux as $item) {
				$data['info'] = $item;
			}
		}

		$vSelect = "";
		if (isset($data['info']['cod_faqcat'])) {
			$vSelect = $data['info']['cod_faqcat'];
		}
		if (isset($_GET['cat'])) {
			$vSelect = $_GET['cat'];
		}

		$data['formulario'] = FormLib::GetForm("WEB_FAQ", array("EMP_FAQ" => \Config::get("app.main_emp"), "COD_FAQ" => $cod_faq, "LANG_FAQ" => strtoupper($lang)));

		$data['formulario']['COD_FAQCAT'] = FormLib::Select("COD_FAQCAT", 0, $vSelect, $cats);

		$data['formulario']['SUBMIT'] = FormLib::Submit("Enviar", "formWEB_FAQ");

		return \View::make('admin::pages.V5.admin-blades.faqEdit', array('data' => $data));
	}

	function editRun($lang = 'es')
	{

		$data = Input::all();

		$info = [
			'EMP_FAQ' => \Config::get("app.main_emp"),
			'COD_FAQ' => $data['COD_FAQ'],
			'COD_FAQCAT' => $data['COD_FAQCAT'],
			'TITULO_FAQ' => $data['TITULO_FAQ'],
			'DESC_FAQ' => $data['DESC_FAQ'],
			'LANG_FAQ' => strtoupper($lang),

		];


		if (!$info['COD_FAQ']) {
			$nextCode = WEB_FAQ::max("COD_FAQ") + 1;
			$info['COD_FAQ'] = $nextCode;

			$newPosition = WEB_FAQ::where('COD_FAQCAT', $data['COD_FAQCAT'])->max("POSITION") + 1;
			$info['POSITION'] = $newPosition;

			WEB_FAQ::insert($info);
		} else {
			WEB_FAQ::where('EMP_FAQ', $info['EMP_FAQ'])->where('COD_FAQ', $info['COD_FAQ'])->where('LANG_FAQ', $info['LANG_FAQ'])->update($info);
		}


		return MessageLib::successMessage("Datos guardados");
	}

	function Delete()
	{

		$info = input::all();
		if (!empty($info['cod'])) {
			WEB_FAQ::where("cod_faq", $info['cod'])->delete();
		}

		return MessageLib::successMessage("Datos eliminados");
	}

	function categoriesNewRun($lang = 'es')
	{

		$info = input::all();

		$newId = WEB_FAQCAT::max("COD_FAQCAT") + 1;
		$newPosition = WEB_FAQCAT::where('PARENT_FAQCAT', $info['parent'])
			->max("POSITION") + 1;

		$data = [
			'EMP_FAQCAT' => \Config::get("app.main_emp"),
			'NOMBRE_FAQCAT' => $info['new'],
			'LANG_FAQCAT' => strtoupper($lang),
			'PARENT_FAQCAT' => $info['parent'],
			'COD_FAQCAT' => $newId,
			'POSITION' => $newPosition
		];
		WEB_FAQCAT::insert($data);

		return MessageLib::successMessage("Datos guardados");
	}

	function categoriesDelete()
	{

		$info = input::all();
		if (!empty($info['cod'])) {
			WEB_FAQCAT::where("cod_faqcat", $info['cod'])->delete();
		}

		return MessageLib::successMessage("Datos eliminados");
	}

	function categoriesEdit($lang = 'es', $cod)
	{

		$data = array("info" => array());
		$cats = array();

		$val = WEB_FAQCAT::where("COD_FAQCAT", $cod)->where("LANG_FAQCAT", strtoupper($lang))->first()->parent_faqcat;

		$a = WEB_FAQCAT::where("COD_FAQCAT", "!=", $cod)->where("PARENT_FAQCAT", 0)->where("LANG_FAQCAT", strtoupper($lang))->get();

		foreach ($a as $item) {
			$cats[$item->cod_faqcat] = $item->nombre_faqcat;
		}

		$data['formulario'] = FormLib::GetForm("WEB_FAQCAT", array("EMP_FAQCAT" => \Config::get("app.main_emp"), "COD_FAQCAT" => $cod, "LANG_FAQCAT" => strtoupper($lang)));

		$data['formulario']['PARENT_FAQCAT'] = FormLib::Select("PARENT_FAQCAT", 0, $val, $cats);
		$data['formulario']['COD_FAQCAT'] = FormLib::Hidden("COD_FAQCAT", 1, $cod);

		$data['formulario']['SUBMIT'] = FormLib::Submit("Enviar", "formWEB_FAQCAT");

		return \View::make('admin::pages.V5.admin-blades.faqCategoriesEdit', array('data' => $data));
	}

	function categoriesEditRun($lang = 'es')
	{

		$info = input::all();

		if ($info['PARENT_FAQCAT'] == "-") {
			$info['PARENT_FAQCAT'] = "0";
		}

		WEB_FAQCAT::where("cod_faqcat", $info['COD_FAQCAT'])
			->where("lang_faqcat", strtoupper($lang))
			->update(['NOMBRE_FAQCAT' => $info['NOMBRE_FAQCAT'], 'PARENT_FAQCAT' => $info['PARENT_FAQCAT']]);

		return MessageLib::successMessage("Datos modificados");
	}


	function saveOrder($lang = 'es')
	{

		$inputs = input::all();
		$isCategory = $inputs['category'];

		foreach ($inputs['order'] as $key => $cod) {

			if ($isCategory == "true") {

				WEB_FaqCat::where("LANG_FAQCAT", strtoupper($lang))
					->where('COD_FAQCAT', $cod)
					->update([
						'POSITION' => $key
					]);
			} else {

				WEB_FAQ::where("LANG_FAQ", strtoupper($lang))
					->where('COD_FAQ', $cod)
					->update([
						'POSITION' => $key
					]);
			}
		}

		return MessageLib::successMessage("Orden modificado");
	}
}
