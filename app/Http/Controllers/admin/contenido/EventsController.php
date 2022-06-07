<?php

namespace App\Http\Controllers\admin\contenido;

use Illuminate\Support\Facades\DB;
use View;
use Illuminate\Support\Facades\Request as Input;
use App\libs\FormLib;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebNewbannerModel;
use Illuminate\Support\Facades\Config;


class EventsController extends Controller
{

	public $PATH_IMG = "";
	public $PUBLIC_PATH_IMG = "";

	function __construct()
	{

		//Metodo para eliminar directorio desde codigo y poder crearlo manualmente desde ftp, descomentar solo si es necesario reiniciarla
		//$path = str_replace("\\", "/", getcwd() . "/img/banner");
		//$this->eliminar_directorio($path);

		$this->PUBLIC_PATH_IMG = "/img/banner/" . Config::get('app.theme') . "/" .  Config::get("app.emp") . "/";
		$this->PATH_IMG = getcwd() . $this->PUBLIC_PATH_IMG;
	}

	public function index(Request $request)
	{
		$webNewBanners = WebNewbannerModel::where('UBICACION', $request->get('ubicacion'))->orderBy('orden', 'asc')->get();
		return View::make('admin::pages.contenido.eventos.index', compact('webNewBanners'));
	}

	public function create(Request $request)
	{

		$newid = WebNewbannerModel::orderBy("ID", "desc")->first();
		if (empty($newid)) {
			$newid = 1;
		} else {
			$newid = $newid->id + 1;
		}

		$id = WebNewbannerModel::insertGetId([
			"ID" => $newid,
			"EMPRESA" => \Config::get("app.emp"),
			"ACTIVO" => 0,
			"KEY" => $request->get('ubicacion'),//WebNewbannerModel::UBICACION_EVENTO,
			"ID_WEB_NEWBANNER_TIPO" => WebNewbannerModel::WEB_NEWBANNER_TIPO_EVENTO,
			"UBICACION" => $request->get('ubicacion')

		]);

		return redirect(route('event.edit', ['id' => $id] ));

	}

	public function edit($id)
	{
		$webNewBanner = WebNewbannerModel::where('ID', $id)->first();

		$formulario = [
			'token' => Formlib::Hidden("_token", 1, csrf_token()),
			'id' => Formlib::Hidden("id", 1, $id),
			'nombre' => FormLib::Hidden("nombre", 1, $webNewBanner->ubicacion),
			'orden' => FormLib::Int("orden", 1, $webNewBanner->orden),
			'activo' => FormLib::Bool("activo", 0, $webNewBanner->activo),
			'descripcion' => FormLib::Textarea("descripcion", 0, $webNewBanner->descripcion),
		];

		$bloques = ['imagen'];

		return View::make('admin::pages.contenido.eventos.editar', ['webNewBanner' => $webNewBanner, 'formulario' => $formulario, 'bloques' => $bloques]);
	}

	/**
	 * Guarda los items individuales
	 */
	public function store()
	{
		$data = Input::all();
		$theme = Config::get('app.theme');

		$id = $data['id_ES'];

		foreach (\Config::get("app.locales") as $lang => $textLang) {

			if (!isset($data['ventana_nueva_' . strtoupper($lang)])) {
				$data['ventana_nueva_' . strtoupper($lang)] = 0;
			} else {
				$data['ventana_nueva_' . strtoupper($lang)] = 1;
			}

			DB::table("WEB_NEWBANNER_ITEM")->where("lenguaje", strtoupper($lang))->where("id", $id)->update([
				"texto" => $data['texto_' . strtoupper($lang)],
				"url" => $data['url_' . strtoupper($lang)],
				"ventana_nueva" => $data['ventana_nueva_' . strtoupper($lang)]
			]);
		}

		$path = str_replace("\\", "/", $this->PATH_IMG . $data['id_web_newbanner_ES'] . "/" . $id);
		if (!is_dir($path)) {
			mkdir($path, 0775, true);
			chmod($path, 0775);
		}

		foreach ($_FILES as $k => $item) {

			if (!empty($item['tmp_name'])) {

				$idioma = str_replace("imagen_mobile_", "", $k);
				$idioma = str_replace("imagen_", "", $idioma);
				$extension = explode(".", $item['name']);
				$extension = $extension[sizeof($extension) - 1];

				$size = getimagesize($item['tmp_name']);
				if ($size[0] > 3000) {
					$w = 3000;
					$h = $size[1] * 3000 / $size[0];
				} else {
					$w = $size[0];
					$h = $size[1];
				}

				if ($extension == "png") {
					$src_image = imagecreatefrompng($item['tmp_name']);
				} elseif ($extension == "jpg" || $extension == "jpeg") {
					$src_image = imagecreatefromjpeg($item['tmp_name']);
				}

				$dst_image = imagecreatetruecolor($w, $h);

				$blanco = imagecolorallocate($src_image, 255, 255, 255);
				imagefill($dst_image, 0, 0, $blanco);

				imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);

				$mobile = "";
				if (strpos($k, "mobile") > 0)
					$mobile = "_mobile";

				imagejpeg($dst_image, $path . "/" . $idioma . $mobile . ".jpg", 85);
			}
		}

		return redirect(route('event.edit', ['id' => $data['id_web_newbanner_ES']]));

	}
	public function update($id)
	{
		$data = Input::all();

		$activo = 0;
		if (request()->has('activo')) {
			$activo = 1;
		}

		DB::table("WEB_NEWBANNER")->where("id", request('id'))->update([
			"ACTIVO" => $activo,
			"DESCRIPCION" => $data['descripcion'],
			"ORDEN" => $data['orden'],
		]);

		return back()->with(['success' =>array(trans('admin-app.title.updated_ok'))]);
	}

}
