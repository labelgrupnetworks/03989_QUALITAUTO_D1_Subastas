<?php

namespace App\Http\Controllers\admin\contenido;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request as Input;
use App\libs\FormLib;
use App\libs\BannerLib;
use App\libs\MessageLib;
use Illuminate\Http\Request;
use App\Models\WebNewbannerModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\libs\CacheLib;
use App\Models\V5\Web_Blog_Lang;
use App\Models\V5\Web_Content_Page;
use App\Models\WebNewbannerItemModel;
use App\Models\WebNewbannerTipoModel;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;

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

		$this->middleware(function($request, $next) {
			if($key = $request->input('key')) {
				$this->refreshBannerCache($key);
			}
			return $next($request);
		}, ['only' => ['nuevoItemBloque', 'borraItemBloque', 'estadoItemBloque', 'guardaItemBloque', 'ordenaBloque', 'activar']]);
	}

	public function index(Request $request)
	{
		$data = array('menu' => 2);

		$ubicacionesProhibidas = [WebNewbannerModel::UBICACION_EVENTO, WebNewbannerModel::UBICACION_MUSEO, WebNewbannerModel::UBICACION_BLOG];
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

	public function nuevo(Request $request)
	{
		$isIframe = $request->has('to_frame');

		$tipos = WebNewbannerTipoModel::query()
			->when($isIframe, function($query) {
				return $query->whereIn('id', [1, 2, 3, 4, 5, 16, 21]);
			})
			->get();

		$data = [
			'menu' => 2,
			'nombre' => $isIframe ? FormLib::TextReadOnly("nombre", 1, "bl_{$request->rel_id}_{$request->id_content}") : FormLib::Text("nombre", 1),
			'tipo' => FormLib::Hidden("tipo_banner", 1),
			'SUBMIT' => FormLib::Submit("Continuar", "nuevoBanner"),
			'tipos' => $tipos,
			'is_iframe' => $isIframe
		];

		if($isIframe){
			$data['ubicacion'] = FormLib::Hidden("ubicacion", 1, $request->get('ubicacion'));
			$data['id_content'] = FormLib::Hidden("id_content", 1, $request->get('id_content'));
		};

		$view = $isIframe ? 'admin::pages.contenido.banner._nuevo' : 'admin::pages.contenido.banner.nuevo';

		return View::make($view, $data);
	}

	public function nuevo_run(Request $request)
	{
		$data =  $request->all();

		//$tipo = WebNewbannerTipoModel::where("ID",$data['tipo_banner'])->first();
		$newid = WebNewbannerModel::withoutGlobalScopes()->orderBy("ID", "desc")->first();
		if (empty($newid)) {
			$newid = 1;
		} else {
			$newid = $newid->id + 1;
		}

		$isIframe = $request->has('to_frame');

		$bannerData = [
			"ID" => $newid,
			"EMPRESA" => Config::get("app.main_emp"),
			"ACTIVO" => $isIframe ? 1 : 0,
			"KEY" => $data['nombre'],
			"ID_WEB_NEWBANNER_TIPO" => $data['tipo_banner']
		];

		if($isIframe) {
			$bannerData['UBICACION'] = $request->get('ubicacion');
		}

		$id = WebNewbannerModel::insertGetId($bannerData);

		$urlToRedirect = "/admin/newbanner/editar/$id";

		if($isIframe) {
			$webContentPage = Web_Content_Page::query()
				->where('id_content_page', $request->get('id_content'))
				->first();

			$webContentPage->type_id_content_page = $id;
			$webContentPage->save();

			//Ya que los banners ya controlan por si mismo los idiomas, en los contenidos
			//solo permitimos crearlos en español y crearemos una copia en el idioma que corresponda
			if($webContentPage->table_rel_content_page == Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG) {

				$webBlogLang = Web_Blog_Lang::where([
					['id_web_blog_lang', $webContentPage->rel_id_content_page],
					['lang_web_blog_lang', 'ES']
				])->first();

				$idsOtherLangs = Web_Blog_Lang::where([
					['idblog_web_blog_lang', $webBlogLang->idblog_web_blog_lang],
					['lang_web_blog_lang', '!=', 'ES']
				])->pluck('id_web_blog_lang');


				foreach ($idsOtherLangs as $idOtherLang) {

					$maxOrder = Web_Content_Page::where([
						['table_rel_content_page', Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG],
						['rel_id_content_page', $idOtherLang],
						['type_content_page', Web_Content_Page::TYPE_CONTENT_PAGE_BANNER]
					])->max('order_content_page');

					Web_Content_Page::create([
						'table_rel_content_page' => Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG,
						'rel_id_content_page' => $idOtherLang,
						'type_content_page' => Web_Content_Page::TYPE_CONTENT_PAGE_BANNER,
						'type_id_content_page' => $id,
						'order_content_page' => $maxOrder + 1
					]);
				}

			}



			$urlToRedirect .= "?to_frame=1";
		}

		return redirect($urlToRedirect);
	}

	public function editar(Request $request, $id = 0)
	{
		if (empty($id)) {
			return "Error";
		}

		$isIframe = $request->has('to_frame');

		$data = array('menu' => 2);

		$data['token'] = Formlib::Hidden("_token", 1, csrf_token());
		$data['id'] = Formlib::Hidden("id", 1, $id);

		$data['banner'] = WebNewbannerModel::where("id", $id)->first();
		if (empty($data['banner'])) {
			return "404";
		}

		if ($data['banner']->empresa != Config::get("app.main_emp")) {
			return "Error de empresa";
		}

		$tipo = WebNewbannerTipoModel::where("id", $data['banner']->id_web_newbanner_tipo)->first();
		$data['bloques'] = explode(",", $tipo->bloques);


		$data['nombre'] = FormLib::Text("nombre", 1, $data['banner']->key);
		$data['orden'] = FormLib::Int("orden", 1, $data['banner']->orden);
		$data['activo'] = FormLib::Bool("activo", 0, $data['banner']->activo);
		$data['descripcion'] = FormLib::Textarea("descripcion", 0, $data['banner']->descripcion);
		$data['ubicacion'] = $isIframe ? FormLib::TextReadOnly("ubicacion", 0, $data['banner']->ubicacion) : FormLib::Text("ubicacion", 1, $data['banner']->ubicacion);

		$ubicaciones = DB::select("SELECT DISTINCT(ubicacion) FROM WEB_NEWBANNER");

		foreach ($ubicaciones as $item) {
			$a = explode(",", $item->ubicacion);
			foreach ($a as $a2) {
				$c[trim($a2)] = trim($a2);
			}
		}
		unset($c[0]);
		$data['ubicaciones'] = implode(",", $c);
		$data['is_iframe'] = $isIframe;

		$view = $isIframe ? 'admin::pages.contenido.banner._editar' : 'admin::pages.contenido.banner.editar';

		return View::make($view, $data);
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

	private function getValidExtension($path)
	{
		foreach (['webp', 'jpg', 'gif'] as $extension) {
			if(is_file("$path.$extension")){
				return $extension;
			}
		}
		return null;
	}


	function listaItemsBloque()
	{
		$data = Input::all();

		$info['info'] = DB::table("WEB_NEWBANNER_ITEM")->where("ID_WEB_NEWBANNER", $data['id'])->where("BLOQUE", $data['index'])->where("LENGUAJE", "ES")->orderBy("ORDEN", "ASC")->get()->toArray();

		$info['banner'] = DB::table("WEB_NEWBANNER")->where("id", $data['id'])->first();
		$tipos = DB::table("WEB_NEWBANNER_TIPO")->where("id", $info['banner']->id_web_newbanner_tipo)->first();
		$tipos = explode(",", $tipos->bloques);
		$info['tipo'] = $tipos[$data['index']];

		foreach ($info['info'] as $item) {

			$path = str_replace("\\", "/", $this->PATH_IMG . $item->id_web_newbanner . "/" . $item->id . "/ES");
			$extension = $this->getValidExtension($path);

			$item->imagen = !$extension ? "/img/noFoto.png" : $this->PUBLIC_PATH_IMG . $item->id_web_newbanner . "/" . $item->id . "/ES.$extension";

			$item->imagen=ToolsServiceProvider::urlAssetsCache($item->imagen);
		}

		return View::make('admin::pages.contenido.banner.itemBlockBannerSnippet', $info);
	}

	function nuevoItemBloque(Request $request)
	{
		$calculoId = DB::table("WEB_NEWBANNER_ITEM")->max("ID");

		if (empty($calculoId)) {
			$calculoId = 0;
		}

		foreach (Config::get("app.locales") as $lang => $textLang) {

			DB::table("WEB_NEWBANNER_ITEM")->insert([
				"ID" => $calculoId + 1,
				"ID_WEB_NEWBANNER" => $request->input('id'),
				"BLOQUE" => $request->input('index'),
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

	function estadoItemBloque(Request $request)
	{
		WebNewbannerItemModel::where('id', $request->input('id'))
			->update([
				'activo' => !empty($request->input('activo'))
			]);
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

		foreach (Config::get("app.locales") as $lang => $textLang) {

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
		$formulario[$lang]['key'] = Formlib::Hidden("key", 1, $info['banner']->key);

		$info['formulario'] = $formulario;

		return View::make('admin::pages.contenido.banner.itemBlockBannerForm', $info);
	}

	function guardaItemBloque(Request $request)
	{
		$theme = config('app.theme');
		$mainEmp = config('app.main_emp');
		$id = $request->input('id_ES');
		$parentId = $request->input('id_web_newbanner_ES');

		$langs = array_map('mb_strtoupper', array_keys(config('app.locales')));
		foreach ($langs as $lang) {

			$update = [
				'texto' => $request->input("texto_$lang", ""),
				'ventana_nueva' => (int)$request->has("ventana_nueva_$lang"),
				'url' => $request->input("url_$lang", ""),
			];

			WebNewbannerItemModel::query()
				->where([
					['lenguaje', $lang],
					['id', $id],
				])
				->update($update);

			$direcoryPath = str_replace("\\", "/","/img/banner/$theme/$mainEmp/$parentId/$id");
			if (!is_dir(public_path($direcoryPath))) {
				mkdir(public_path($direcoryPath), 0775, true);
				chmod(public_path($direcoryPath), 0775);
			}

			if($image = request()->file("imagen_$lang")) {
				$this->saveImage($image, "$lang", $direcoryPath, false);
			}

			if($imageMobile = request()->file("imagen_mobile_$lang", $image)) {
				$this->saveImage($imageMobile, "{$lang}_mobile", $direcoryPath, true);
			}
		}

		return back()->with(['success' =>array(trans('admin-app.title.updated_ok'))]);
	}

	public function saveImage(UploadedFile $image, string $fileName, string $direcoryPath, bool $isMobile)
	{
		$extension = "webp";

		if($image->getMimeType() == "image/gif") {
			copy($image->getRealPath(), public_path("$direcoryPath/$fileName.gif"));
			@unlink(public_path("$direcoryPath/$fileName.$extension"));
			return;
		}

		$path = public_path("$direcoryPath/$fileName.$extension");

		$imageSave = Image::make($image);
		if($isMobile) {
			$imageSave->resize(800, null, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});
		}
		$imageSave->save($path, 90, $extension);
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

		$this->refreshBannerCache($data['nombre']);
		echo "OK";
	}

	function borrar($id = 0)
	{
		if (empty($id)) {
			die("No se puede eliminar");
		}

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

	/**
	 * devuelve la primera imagen que encuentra de un banner
	 */
	public function bannerImage($banners)
	{
		$rutaImg = "img/banner/".Config::get('app.theme') ."/". Config::get('app.main_emp');
		$nameImg = strtoupper(Config::get('app.locale'));

		$idsBanners = array();
		foreach($banners as $banner){
			$idsBanners[] = $banner->id;
		}

		$items = WebNewbannerItemModel::select("ID , ID_WEB_NEWBANNER ")
			->wherein("ID_WEB_NEWBANNER", $idsBanners)
			->orderby("ID_WEB_NEWBANNER,ID")
			->get();

		$images = array();
		foreach($items as $item){
			$idBanner = $item->id_web_newbanner;
			$idItem = $item->id;

			# si aun no tenemos una imagen para ese baner
			if(empty($images[$idBanner])){

				$path = "$rutaImg/$idBanner/$idItem/$nameImg";
				$extension = $this->getValidExtension($path);

				$images[$idBanner] = (!$extension)
					? "/img/noFoto.png"
					: "/$path.$extension";
			}
		}
		return $images;
	}

	public function orderBanner()
	{
		$order = request("order");
		$ubicacion = request("ubicacion");
			foreach ($order as $key => $id) {
				WebNewbannerModel::where("UBICACION",$ubicacion)
						->where('ID', $id)
						->update(['ORDEN' => $key]);
			}

		return MessageLib::successMessage("Orden modificado");
	}

	private function refreshBannerCache($key)
	{
		CacheLib::forgetCache(BannerLib::banerCacheName($key));
	}

}
