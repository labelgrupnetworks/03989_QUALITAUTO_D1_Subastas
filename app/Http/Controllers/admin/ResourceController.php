<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Resources;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

class ResourceController extends Controller
{

	//Ver todos los Recursos que hay
	public function index()
	{

		$content = new Resources();
		if (!empty($_GET["see"]) && $_GET["see"] == 'A') {
			$value_where = "and WEB_RESOURCE.type IN ('A')";
		} elseif (!empty($_GET["see"]) && $_GET["see"] == 'C') {
			$value_where = "and WEB_RESOURCE.type IN ('C')";
		} else {
			$value_where = "and WEB_RESOURCE.type IN ('I','H')";
		}
		if (!empty($_GET["crs"])) {
			$value_where .= " and COD_BANNER_SEC = '" . $_GET["crs"] . "'";
		}

		$data['inf'] = $content->tableResource($value_where);
		return View::make('admin::pages.resource', array('data' => $data));
	}

	//Ver la informacion del bloque si no existe todo vacio
	public function SeeResources($id = NULL)
	{
		$content = new Resources();

		$bloque = $content->infResource($id);
		if (!count($bloque) > 0) {
			$bloque = null;
		}
		return View::make('admin::pages.editResource', array('bloque' => $bloque[0] ?? null));
	}

	public function EditResources()
	{
		$content = new Resources();
		$enabled_temp = 'off';
		$new_windows_temp = 'off';
		$name = Request::input('name');
		$url_link = Request::input('url_link');
		$new_windows_temp = Request::input('new_windows');
		$type = Request::input('type');
		$enabled_temp = Request::input('enabled');
		$id = Request::input('id');
		$file_url = Request::input('file_url');
		$cache = Request::input('cache');
		$cod_sec = Request::input('cod_sec');

		if ($type == 'H' || $type == 'A') {
			$html = Request::input('html');
		} elseif ($type == 'C') {
			$html = Request::input('fecha');
		} else {
			$html = Request::input('text_html');
		}
		if (empty($cache)) {
			$cache = 0;
		}

		if ($enabled_temp == 'on') {
			$enabled = 1;
		} else {
			$enabled = 0;
		}
		if ($new_windows_temp == 'on') {
			$new_windows = 1;
		} else {
			$new_windows = 0;
		}

		if (empty($url_link)) {
			$url_link = null;
		}

		if ($id < 1) {
			$id = $content->newResource($name, $url_link, $new_windows, $type, $enabled, $html, $file_url, $cache, $cod_sec);
			return $id;
		} else {
			//no se modificara la seccion por lo que no se evnia
			$content->updateResource($name, $url_link, $new_windows, $type, $enabled, $id, $html, $file_url, $cache);
			return $id;
		}
	}

	public function DeleteResource()
	{
		$content = new Resources();
		$id_delete = Request::input('id_resource');
		$content->delete_Resource($id_delete);
		$content->delete_ResourceBanner($id_delete);
	}
}
