<?php

namespace App\Http\Controllers\admin\contenido;

use View;
use Illuminate\Support\Facades\Request as Input;
use App\libs\FormLib;
use App\libs\BannerLib;
use App\libs\MessageLib;
use Illuminate\Http\Request;
use App\Models\WebNewbannerModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\WebNewbannerItemModel;
use App\Models\WebNewbannerTipoModel;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Config;


class BannerController extends Controller
{

	public $PATH_IMG = "";
	public $PUBLIC_PATH_IMG = "";

	function __construct()
	{

		//Metodo para eliminar directorio desde codigo y poder crearlo manualmente desde ftp, descomentar solo si es necesario reiniciarla
		//$path = str_replace("\\", "/", getcwd() . "/img/banner");
		//$this->eliminar_directorio($path);

		$this->PUBLIC_PATH_IMG = "/img/banner/" . Config::get('app.theme') . "/" .  Config::get("app.main_emp") . "/";
		$this->PATH_IMG = getcwd() . $this->PUBLIC_PATH_IMG;
	}

	public function index(Request $request)
	{
		$data = array('menu' => 2);

		$ubicacionesProhibidas = [WebNewbannerModel::UBICACION_EVENTO,WebNewbannerModel::UBICACION_MUSEO];
		$banners = WebNewbannerModel::query();
		$banners = $banners->wherenotin("UBICACION", $ubicacionesProhibidas);

		$ubicacion = '';
		if($request->filled('ubicacion')){
			$banners = $banners->where('ubicacion', $request->get('ubicacion'));
			$ubicacion = $request->get('ubicacion');
		}

		$banners = $banners->orderby("ID, ORDEN")->get();

		$ubicaciones = WebNewbannerModel::select('ubicacion')->wherenotin("UBICACION", $ubicacionesProhibidas)->distinct()->pluck('ubicacion');

		#RECUPERAR IMAGENES DE ITEMS
		$images = $this->bannerImage($banners);

		return View::make('admin::pages.contenido.banner.index', compact('banners', 'images', 'ubicaciones', 'ubicacion'));
	}

	public function ubicacionHome()
	{

		$data = array('menu' => 2);

		$data['banners'] = WebNewbannerModel::where("UBICACION", "HOME")->orderby("ORDEN")->get();
		$data['images'] = $this->bannerImage($data['banners']);


		$data['ubicacion'] = "HOME";
		return View::make('admin::pages.contenido.banner.index', $data);
	}


	public function nuevo()
	{

		$data = array('menu' => 2);

		$data['nombre'] = FormLib::Text("nombre", 1);
		$data['tipo'] = FormLib::Hidden("tipo_banner", 1);
		$data['token'] = Formlib::hidden("_token", 1, csrf_token());
		$data['SUBMIT'] = FormLib::Submit("Continuar", "nuevoBanner");

		$data['tipos'] = WebNewbannerTipoModel::get();

		return View::make('admin::pages.contenido.banner.nuevo', $data);
	}

	public function nuevo_run()
	{

		$data = Input::all();

		//$tipo = WebNewbannerTipoModel::where("ID",$data['tipo_banner'])->first();
		$newid = WebNewbannerModel::withoutGlobalScopes()->orderBy("ID", "desc")->first();
		if (empty($newid)) {
			$newid = 1;
		} else {
			$newid = $newid->id + 1;
		}

		$id = WebNewbannerModel::insertGetId([
			"ID" => $newid,
			"EMPRESA" => \Config::get("app.main_emp"),
			"ACTIVO" => 0,
			"KEY" => $data['nombre'],
			"ID_WEB_NEWBANNER_TIPO" => $data['tipo_banner']
		]);

		return redirect("/admin/newbanner/editar/" . $id);
	}

	public function editar($id = 0)
	{

		if (empty($id)) {
			return "Error";
		}

		$data = array('menu' => 2);

		$data['token'] = Formlib::Hidden("_token", 1, csrf_token());
		$data['id'] = Formlib::Hidden("id", 1, $id);

		$data['banner'] = WebNewbannerModel::where("id", $id)->first();
		if (empty($data['banner'])) {
			return "404";
		}

		if ($data['banner']->empresa != \Config::get("app.main_emp")) {
			return "Error de empresa";
		}

		$tipo = WebNewbannerTipoModel::where("id", $data['banner']->id_web_newbanner_tipo)->first();
		$data['bloques'] = explode(",", $tipo->bloques);


		$data['nombre'] = FormLib::Text("nombre", 1, $data['banner']->key);
		$data['orden'] = FormLib::Int("orden", 1, $data['banner']->orden);
		$data['activo'] = FormLib::Bool("activo", 0, $data['banner']->activo);
		$data['descripcion'] = FormLib::Textarea("descripcion", 0, $data['banner']->descripcion);
		$data['ubicacion'] = FormLib::Text("ubicacion", 1, $data['banner']->ubicacion);

		$ubicaciones = DB::select("SELECT DISTINCT(ubicacion) FROM WEB_NEWBANNER");

		foreach ($ubicaciones as $item) {
			$a = explode(",", $item->ubicacion);
			foreach ($a as $a2) {
				$c[trim($a2)] = trim($a2);
			}
		}
		unset($c[0]);
		$data['ubicaciones'] = implode(",", $c);

		return View::make('admin::pages.contenido.banner.editar', $data);
	}

	public function activar(Request $request)
	{

		$id = $request->input('id', '0');
		$activo = $request->input('activo', '0');

		WebNewbannerModel::where('EMPRESA', Config::get("app.main_emp"))
			->where('ID', $id)
			->update([
				'ACTIVO' => $activo
			]);
	}

	function listaItemsBloque()
	{

		$data = Input::all();
		$theme = Config::get('app.theme');

		$info['info'] = DB::table("WEB_NEWBANNER_ITEM")->where("ID_WEB_NEWBANNER", $data['id'])->where("BLOQUE", $data['index'])->where("LENGUAJE", "ES")->orderBy("ORDEN", "ASC")->get()->toArray();

		$info['banner'] = DB::table("WEB_NEWBANNER")->where("id", $data['id'])->first();
		$tipos = DB::table("WEB_NEWBANNER_TIPO")->where("id", $info['banner']->id_web_newbanner_tipo)->first();
		$tipos = explode(",", $tipos->bloques);
		$info['tipo'] = $tipos[$data['index']];


		foreach ($info['info'] as $k => $item) {

			$path = str_replace("\\", "/", $this->PATH_IMG . $item->id_web_newbanner . "/" . $item->id . "/ES.jpg");
			if (is_file($path)) {
				$item->imagen = $this->PUBLIC_PATH_IMG . $item->id_web_newbanner . "/" . $item->id . "/ES.jpg";
			}else{
				$path = str_replace("\\", "/", $this->PATH_IMG . $item->id_web_newbanner . "/" . $item->id . "/ES.gif");
				if (is_file($path)) {
					$item->imagen = $this->PUBLIC_PATH_IMG . $item->id_web_newbanner . "/" . $item->id . "/ES.gif";
				} else {
					$item->imagen = "/img/noFoto.png";
				}

			}


			$item->imagen=ToolsServiceProvider::urlAssetsCache($item->imagen);
		}

		return View::make('admin::pages.contenido.banner.itemBlockBannerSnippet', $info);
	}

	function nuevoItemBloque()
	{

		$calculoId = DB::table("WEB_NEWBANNER_ITEM")->max("ID");

		if (empty($calculoId))
			$calculoId = 0;

		$data = Input::all();

		foreach (\Config::get("app.locales") as $lang => $textLang) {

			DB::table("WEB_NEWBANNER_ITEM")->insert([

				"ID" => $calculoId + 1,
				"ID_WEB_NEWBANNER" => $data['id'],
				"BLOQUE" => $data['index'],
				"LENGUAJE" => strtoupper($lang),
				"TEXTO" => "",
				"URL" => "",
				"VENTANA_NUEVA" => 0

			]);
		}
	}


	function borraItemBloque()
	{

		$data = Input::all();

		if (isset($data['id']) && !empty($data['id'])) {

			DB::table("WEB_NEWBANNER_ITEM")->where("id", $data['id'])->delete();
		}
	}

	function estadoItemBloque()
	{
		$data = Input::all();

		if (isset($data['id']) && !empty($data['id'])) {
			WebNewbannerItemModel::where("id", $data['id'])
				->update(['activo' =>  !empty($data['activo'])]);
		}
	}

	function editaItemBloque()
	{

		$data = Input::all();
		$info = array();

		$info_aux = DB::table("WEB_NEWBANNER_ITEM")->where("id", $data['id'])->get();
		foreach ($info_aux as $item) {
			$info[$item->lenguaje] = $item;
		}

		$info['banner'] = DB::table("WEB_NEWBANNER")->where("id", $info_aux[0]->id_web_newbanner)->first();
		$tipos = DB::table("WEB_NEWBANNER_TIPO")->where("id", $info['banner']->id_web_newbanner_tipo)->first();
		$tipos = explode(",", $tipos->bloques);
		$info['tipo'] = $tipos[$info_aux[0]->bloque];


		foreach (\Config::get("app.locales") as $lang => $textLang) {

			$lang = strtoupper($lang);
			$formulario[$lang] = array();

			$formulario[$lang]['id'] = FormLib::Hidden("id_" . strtoupper($lang), 1, $info[$lang]->id);
			$formulario[$lang]['id_web_banner'] = FormLib::Hidden("id_web_newbanner_" . strtoupper($lang), 1, $info[$lang]->id_web_newbanner);
			$formulario[$lang]['lenguaje'] = FormLib::Hidden("lenguaje_" . strtoupper($lang), 1, $info[$lang]->lenguaje);
			$formulario[$lang]['bloque'] = FormLib::Hidden("bloque_" . strtoupper($lang), 1, $info[$lang]->bloque);
			$formulario[$lang]['imagen'] = FormLib::File("imagen_" . strtoupper($lang), 0, "");
			$formulario[$lang]['imagen_mobile'] = FormLib::File("imagen_mobile_" . strtoupper($lang), 0, "");
			$formulario[$lang]['texto'] = FormLib::Text("texto_" . strtoupper($lang), 0, $info[$lang]->texto);
			$formulario[$lang]['texto2'] = FormLib::TextAreaSummer("texto_" . strtoupper($lang), false, $info[$lang]->texto);
			$formulario[$lang]['url'] = FormLib::Text("url_" . strtoupper($lang), 0, $info[$lang]->url);
			$formulario[$lang]['ventana_nueva'] = FormLib::Bool("ventana_nueva_" . strtoupper($lang), 0, $info[$lang]->ventana_nueva);
		}

		$formulario[$lang]['token'] = Formlib::Hidden("_token", 1, csrf_token());

		$info['formulario'] = $formulario;

		return View::make('admin::pages.contenido.banner.itemBlockBannerForm', $info);
	}

	function guardaItemBloque()
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
			$url =  $data['url_' . strtoupper($lang)]?? '';
			$ventana_nueva = $data['ventana_nueva_' . strtoupper($lang)]?? '';

			DB::table("WEB_NEWBANNER_ITEM")->where("lenguaje", strtoupper($lang))->where("id", $id)->update([
				"texto" => $data['texto_' . strtoupper($lang)],
				"url" => $url,
				"ventana_nueva" => $ventana_nueva
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

				$mobile = "";
				if (strpos($k, "mobile") > 0){
					$mobile = "_mobile";
				}


				if(strtoupper($extension) == "GIF") {
					/*
					$src_image = imagecreatefromgif($item['tmp_name']);
					imagegif($src_image,  $path . "/" . $idioma . $mobile . ".gif");
					*/
					rename($item['tmp_name'], $path . "/" . $idioma . $mobile . ".gif");
					#borramso la imgen jpg por si hubiera
					@unlink(  $path . "/" . $idioma . $mobile . ".jpg");
				}else{

						$size = getimagesize($item['tmp_name']);
					if ($size[0] > 3000) {
						$w = 3000;
						$h = $size[1] * 3000 / $size[0];
					} else {
						$w = $size[0];
						$h = $size[1];
					}


					if (strtoupper($extension) == "PNG") {
						$src_image = imagecreatefrompng($item['tmp_name']);
					} elseif (strtoupper($extension) == "JPG" || strtoupper($extension) == "JPEG" ) {
						$src_image = imagecreatefromjpeg($item['tmp_name']);
					}

					$dst_image = imagecreatetruecolor($w, $h);

					$blanco = imagecolorallocate($src_image, 255, 255, 255);
					imagefill($dst_image, 0, 0, $blanco);

					imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);






					imagejpeg($dst_image, $path . "/" . $idioma . $mobile . ".jpg", 85);
					#borramso la imgen gif por si hubiera
					@unlink(  $path . "/" . $idioma . $mobile . ".gif");
				}
			}
		}



		return redirect("/admin/newbanner/editar/" . $data['id_web_newbanner_ES']);
	}



	function editar_run()
	{

		$data = Input::all();

		if (!isset($data['activo'])) {
			$data['activo'] = 0;
		} else {
			$data['activo'] = 1;
		}

		DB::table("WEB_NEWBANNER")->where("id", $data['id'])->update([
			"KEY" => $data['nombre'],
			"ACTIVO" => $data['activo'],
			"DESCRIPCION" => $data['descripcion'],
			"ORDEN" => $data['orden'],
			"UBICACION" => $data['ubicacion']
		]);

		echo "OK";
	}



	function borrar($id = 0)
	{

		if (empty($id)) {
			die("No se puede eliminar");
		}
		$theme = Config::get('app.theme');

		DB::table("WEB_NEWBANNER_ITEM")->where("id_web_newbanner", $id)->delete();
		DB::table("WEB_NEWBANNER")->where("id", $id)->delete();
		if (is_dir(str_replace("\\", "/", $this->PATH_IMG . $id))) {
			$this->delete_directory(str_replace("\\", "/", $this->PATH_IMG . $id));
		}

		return back()->with(['success' =>array(trans('admin-app.title.updated_ok'))]);
	}

	function delete_directory($dirname)
	{
		if (is_dir($dirname))
			$dir_handle = opendir($dirname);
		if (!$dir_handle)
			return false;
		while ($file = readdir($dir_handle)) {
			if ($file != "." && $file != "..") {
				if (!is_dir($dirname . "/" . $file))
					unlink($dirname . "/" . $file);
				else
					$this->delete_directory($dirname . '/' . $file);
			}
		}
		closedir($dir_handle);
		rmdir($dirname);
		return true;
	}

	function eliminar_directorio($dir)
	{
		$result = false;
		if ($handle = opendir("$dir")) {
			$result = true;
			while ((($file = readdir($handle)) !== false) && ($result)) {
				if ($file != '.' && $file != '..') {
					if (is_dir("$dir/$file")) {
						$result = $this->eliminar_directorio("$dir/$file");
					} else {
						$result = unlink("$dir/$file");
					}
				}
			}
			closedir($handle);
			if ($result) {
				$result = rmdir($dir);
			}
		}
		return $result;
	}


	function vistaPrevia()
	{

		$info = Input::all();

		echo BannerLib::bannersPorKey($info['key']);
	}



	function ordenaBloque()
	{

		$info = Input::all();
		$info["orden"] = json_decode($info["orden"]);
		foreach ($info["orden"] as $orden => $item) {
			DB::table("web_newbanner_item")
				->where("id_web_newbanner", $info['id_web_banner'])
				->where("bloque", $info['bloque'])
				->where("id", $item)
				->update(["orden" => $orden]);
		}
	}
	#devuelve la primera imagen que encuentra de un banner
	public function bannerImage($banners){
		$rutaImg = "img/banner/".Config::get('app.theme') ."/". Config::get('app.emp') ;
		$nameImg = strtoupper(Config::get('app.locale')).".jpg";

		$idsBanners = array();
		foreach($banners as $banner){
			$idsBanners[] = $banner->id;
		}

		//$items = WebNewbannerModel::select("WEB_NEWBANNER_ITEM.ID , WEB_NEWBANNER_ITEM.ID_WEB_NEWBANNER ")->JoinBannerItem()->wherenotin("UBICACION",[WebNewbannerModel::UBICACION_EVENTO,WebNewbannerModel::UBICACION_MUSEO, WebNewbannerModel::UBICACION_HOME])->orderby("ID")->get();
		$items = WebNewbannerItemModel::select("ID , ID_WEB_NEWBANNER ")->wherein("ID_WEB_NEWBANNER",$idsBanners)->orderby("ID_WEB_NEWBANNER,ID")->get();



		$images = array();
		foreach($items as $item){
			$idBanner = $item->id_web_newbanner;
			$idItem = $item->id;

			# si aun no tenemos una imagen para ese baner
			if(empty($images[$idBanner])){
				$img = $rutaImg."/". $idBanner .  "/". $idItem . "/". $nameImg;


				if(file_exists($img) ){

					$images[$idBanner] ="/". $img;
				}
			}



		}
		return $images;
	}

	public function orderBanner(){


		$order = request("order");
		$ubicacion = request("ubicacion");
			foreach ($order as $key => $id) {
				WebNewbannerModel::where("UBICACION",$ubicacion)
						->where('ID', $id)
						->update(['ORDEN' => $key]);
			}

		return MessageLib::successMessage("Orden modificado");

	}

}
