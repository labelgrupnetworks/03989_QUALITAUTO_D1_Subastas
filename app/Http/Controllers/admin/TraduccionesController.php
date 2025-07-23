<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\libs\TradLib;
use App\Models\V5\WebTranslateHeaders;
use App\Services\admin\Content\TranslateService;
use App\Support\Database\SessionOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class TraduccionesController extends Controller
{
	private $content;
	private $archiveLang;

	public function __construct()
	{
		$this->content = new TranslateService();
		view()->share(['menu' => 'translates']);
	}

	public function index($head, $lang)
	{
		$this->archiveLang = TradLib::getArchiveTranslations($lang);
		$trad = TradLib::getTranslations($lang);

		$traduccion = array();
		if (!empty($this->archiveLang[$head])) {
			$traduccion = $this->archiveLang[$head];
		}

		foreach ($traduccion as $key => $value) {
			$traduccion[$key] = (object) [
				'key_header' => $head,
				'key_translate' => $key,
				'web_translation' => $value
			];
		}

		// para las web_translate en español en caso de que existan
		foreach ($trad[$head] as $key => $value) {

			$data['original'][$key] = (object) [
				'key_header' => $head,
				'key_translate' => $key,
				'web_translation' => $value
			];
		}

		$data[$lang] = array_merge($traduccion, $this->content->getTranslate($head, $lang));
		$data['key'] = $head;
		$data['lang'] = $lang;
		$data['trans'] = true;

		$data['translateHeaders'] = WebTranslateHeaders::get();

		return View::make('admin::pages.traducciones', array('data' => $data));
	}


	/**
	 * Guardar traducción
	 * @pendiente
	 *
	 *  - En caso de borrar web_translate desde panel, esta no se borrara de la db.
	 *      (con esto controlo no guardar campos null a la db, pero por contra no puedo dejar un campo vacio una vez creado)
	 */
	public function SavedTrans(Request $request, TranslateService $translateService)
	{
		$lang = $request->input('lang');
		$data = [];

		//creamos array con los diferentes key_header, key_translate y web_translation
		foreach ($request->except('_token', 'lang', 'key_header') as $input_name => $value_old) {

			//extraemos del name el key_header y el key_tranlate
			$valores = explode("**", $input_name);
			$key_header = $valores[0];
			$key_translate = $valores[1];

			if (empty($data[$key_header])) {
				$data[$key_header] = [];
			}
			//reemplazamos lascomillassimples para que no den error en el js
			$web_translation = str_replace("'", "´", $value_old);

			$data[$key_header][$key_translate] = $web_translation;
		}

		//recorreos array constuido para crear o actualizar sus valores en db
		foreach ($data as $key_header => $key_translate) {

			$idHeader = $translateService->getIdHeaderToTranslateHeaderByKey($key_header);
			if (empty($idHeader)) {
				$idHeader = $translateService->insertWebTranslateHeader($key_header);
			}

			foreach ($key_translate as $key => $web_translation) {

				$id_key_translate = $translateService->idKeyTranslateHeader($key, $idHeader);

				//puede existir la key_translate pero no en el idioma actual, exist elimina esa posibilidad
				$exist = $translateService->idKey($id_key_translate, $lang);

				//si no existe
				if ((empty($id_key_translate) || empty($exist))) {
					//si el texto del input no esta vacio
					if (!empty($web_translation)) {
						$this->nuevaTranslate($id_key_translate, $key_header, $web_translation, $key, $lang);
					}
				}
				//Si existe
				else {
					//si el input no esta vacio actualiza
					if (!empty($web_translation)) {
						$translateService->updateTrans($id_key_translate, $web_translation, $lang);
					}
					//si esta vacio, borra
					else {
						$translateService->deleteTrans($id_key_translate, $lang);
					}
				}
			}
		}

		Artisan::call('cache:clear');

		try {
			Artisan::call('generate:jstranslates');
		} catch (\Exception $e) {
			Log::info("Artisan generate translate, not found." . $e);
		}
	}


	/**
	 * Si web_translation no existe lo crea
	 * en caso de no tener id_key_translate también lo crea
	 */
	public function nuevaTranslate($id_key_translate, $key_headers, $web_translation, $key_translate, $lang)
	{
		$id_headers = $this->content->getIdHeaderToTranslateHeaderByKey($key_headers);

		//en caso de no existir key, la crea. (Es posible que no existra translate en el idiomoa acutal pero si en otro)
		if (empty($id_key_translate)) {
			$id_key_translate = $this->content->insertKey($id_headers, $key_translate);
		}

		$this->content->insertTrans($id_key_translate, $web_translation, $lang);
	}


	public function NewTrans(Request $request, TranslateService $translateService)
	{
		$key_headers = $request->input('key_headers');
		$lang = $request->input('lang');
		$web_translation = $request->input('web_translation');
		$key_translate = $request->input('key_translate');

		$id_headers = $translateService->getIdHeaderToTranslateHeaderByKey($key_headers);

		$id_key = $translateService->insertKey($id_headers, $key_translate);

		$translateService->insertTrans($id_key, $web_translation, $lang);

		try {
			Artisan::call('generate:jstranslates');
		} catch (\Exception $e) {
			Log::info("Artisan generate translate, not found." . $e);
		}
	}

	/**
	 *
	 * @return type
	 * @pendiente
	 * Errores detectados:
	 *
	 * No mostrar texto en input si este solo esta en el otro idioma
	 */
	public function search(Request $request)
	{
		$data = [];
		if (!$request->has('lang') || !$request->has('web_translation')) {
			return view('admin::pages.traducciones_search', ['data' => $data]);
		}

		$webTranslation = $request->input('web_translation');
		$lang = $request->input('lang');

		$translates = TradLib::getTranslations($lang);

		$trad = SessionOptions::withLinguistic(function () use ($webTranslation, $lang, $translates) {
			return $this->searchTranslate($webTranslation, $translates, $lang);
		});

		$data = [
			'lang' => $lang,
			'web_translation' => $webTranslation,
			'trad' => $trad
		];


		return View::make('admin::pages.traducciones_search', array('data' => $data));
	}


	/**
	 * Metodo de headers a mostrar en index de traducciones
	 * @return type
	 */
	public function getTraducciones()
	{
		$traducciones = TradLib::getArchiveTranslations('es');
		$transKeys = array_keys($traducciones);
		$keyHeaders = array_map(function ($key) {
			return ['key_header' => $key];
		}, $transKeys);

		return View::make('admin::pages.traduccion', array('data' => $keyHeaders));
	}


	/**
	 * Busca la cadena sin tener en cuenta acentos, minusculas y/o mayusculas
	 * @param type $data cadena a buscar
	 * @param type $lang idimoa
	 * @param type $traducciones array con todas las traducciones cargadas
	 * @return type array elementos encontrados
	 *
	 * @pendiente
	 *  - Comprobar si son necesarios todos los atributos que se incluien en data
	 *  - El original debe mostrar por defecto el guardado en base de datos (hecho)
	 */
	public function searchTranslate($data, $traducciones, $lang)
	{

		$result = array();
		//recorre las traducciones
		foreach ($traducciones as $key_header => $array) {
			foreach ($array as $key_translate => $web_translation) {


				//busca en el web_translation original y el guardado en db
				$equal = strrpos($this->replacepreg($web_translation), $this->replacepreg($data));

				//guarda los datos necesarios
				if ($equal !== false) {

					$id_headers = "";
					$id_key_translate = "";
					$id_key_web = "";

					$id_headers = $this->content->getIdHeaderToTranslateHeaderByKey($key_header);

					if (!empty($id_headers)) {
						$id_key_translate = $this->content->idKeyTranslateHeader($key_translate, $id_headers);
						$id_key_web = $this->content->idKey($id_key_translate, $lang);
					}

					//Si no encuentro $id_headers guardo la key_header para que no produzca error
					if (!empty($id_headers) || !is_object($id_headers)) {
						$id_headers = (object) array("id_headers" => $key_header);
					}

					$datos = (object) array(
						"lang" => $lang,
						"web_translation" => $web_translation,
						"id_headers" => $id_headers->id_headers,
						"key_header" => $key_header,
						"id_key" => $id_key_web,
						"id_key_translate" => $id_key_translate,
						"id_headers_translate" => $id_headers->id_headers,
						"key_translate" => $key_translate,
					);

					$result[$id_headers->id_headers][$key_translate] = $datos;
				}
			}
		}
		return $result;
	}

	/**
	 * Retira los acentos y sustuye a minusculas
	 * @param string $value cadena a modificar
	 * @return string String cadena modificada
	 */
	public function replacepreg($value)
	{
		$patrones = array('/á|Á/', '/é|É/', '/í|Í/', '/ó|Ó/', '/ú|Ú/');
		$sustituciones = array('a', 'e', 'i', 'o', 'u');

		$value = preg_replace($patrones, $sustituciones, $value);
		$value = mb_strtolower($value);

		return $value;
	}
}
