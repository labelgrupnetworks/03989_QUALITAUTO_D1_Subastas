<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\admin\Content\OldBannerService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

/**
 * Utilizado principalmente por Gutinvest pero también por CSM, Soler y Alcala para los
 * recursos de norticias y articulos.
 * Si actualizamos esa parte podemos eliminar este controlador y los modelos relacionados
 */
class BannerController extends Controller
{

	//Ver todos los Recursos que hay

	public function index()
	{
		$content = new OldBannerService();
		if (!empty($_GET["see"]) && $_GET["see"] == 'N') {
			$value = $_GET["see"];
		} elseif (!empty($_GET["see"]) && $_GET["see"] == 'C') {
			$value = $_GET["see"];
		} else {
			$value = 'B';
		}
		$cbs = !empty($_GET["cbs"]) ? $_GET["cbs"] : null;

		$data['inf'] = $content->tableBanners($value, $cbs);
		$data['banner_section_name'] = "";
		if (!empty($cbs)) {
			$banner_section = $content->get_banner_section_by_cod($cbs);
			if (!empty($banner_section)) {
				$data['banner_section_name'] = $banner_section->des_banner_section;
			}
		}

		return View::make('admin::pages.banner', array('data' => $data));
	}

	public function SeeBanner($id = NULL)
	{
		$data['BannerResources'] = array();
		$content = new OldBannerService();
		$data['infBanner'] = $content->GetBanners($id);
		$data['BannerResources'] = $content->ResoucesBanners($id);
		$data['resourcechecked'] = array();
		foreach ($data['BannerResources'] as $value) {
			$data['resourcechecked'][] = $value->id_web_resource;
		}

		$cod_banner_sec = !empty($_GET["cbs"]) ? $_GET["cbs"] : null;
		if (!empty($data['infBanner'])) {
			$cod_banner_sec = $data['infBanner']->cod_sec_web_banner;
		}

		$data['Resources'] = $content->GetResourceActivated($cod_banner_sec);

		return View::make('admin::pages.editBanner', array('data' => $data));
	}

	public function EditBanner()
	{
		$enabled_temp = "";
		$orden = 1;

		$content = new OldBannerService();
		$id = Request::input('id');
		$name = Request::input('name');
		$key_name = Request::input('key_name');
		$resources = Request::input('resources');
		$enabled_temp = Request::input('enabled');
		$type = Request::input('type');
		$cod_sec = Request::input('cod_sec');

		if ($enabled_temp === 'on') {
			$enabled = 1;
		} else {
			$enabled = 0;
		}

		#El id es 0  es un nuevo Banner si no es que se tiene que modificar uno
		$id_max = $content->maxBannerResouce();
		$id_max = $id_max + 1;
		if ($id == 0) {

			$id = $content->newBanners($name, $key_name, $enabled, $type, $cod_sec);
		} else {
			//no se modifica la seccion a la que pertenece por eso n ose envia
			$content->updateBanners($name, $key_name, $enabled, $id, $type);
			$content->deleteBannersResources($id);
			Artisan::call('cache:clear');
		}

		#Bucle updatear las relaciones
		if (isset($resources)) {
			foreach ($resources as $value) {

				$content->new_resouce_banner($id_max, $id, $value, $orden);
				$id_max = $id_max + 1;
				$orden = $orden + 1;
			}
		}

		return $id;
	}
}
