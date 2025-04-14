<?php

namespace App\Http\Controllers\admin\subasta;

use App\Exports\PujasExport;
use App\Http\Controllers\apilabel\ImgController;
use App\Http\Controllers\apilabel\LotController;
use App\Http\Controllers\Controller;
use App\Imports\ExcelImport;
use App\libs\ImageGenerate;
use App\libs\LoadLotFileLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCaracteristicas_Value;
use App\Models\V5\FgCaracteristicas;
use App\Models\V5\FgLicit;
use App\Models\V5\FgSub;
use App\Models\V5\FxCli;
use App\Models\V5\FxSecMap;
use App\Models\V5\Web_Images_Size;
use App\Providers\ToolsServiceProvider;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SubastaController extends Controller
{

	public $fgCaracteristicas = null;
	public $featureValues = null;

	public function __construct()
	{
		$currentUrl = request()->route()->uri;
		Log::info('Route: ' . $currentUrl);
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

	private function guardarImagenLote($ficheros, $num_hces1, $lin_hces1)
	{

		$path = str_replace("\\", "/", getcwd() . "/img/" . Config::get('app.emp'));
		if (!is_dir($path)) {
			mkdir($path);
			chmod($path, 0755);
		}

		$pathInicial = "/img/" . Config::get('app.emp') . "/" . $num_hces1;
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

				$newimage = Config::get('app.emp') . "-" . $num_hces1 . "-" . $lin_hces1;

				if (is_file($path . "/" . $newimage . ".jpg")) {

					for ($t = 1; $t < 20; $t++) {
						if ($t < 10) {
							$t = "0" . $t;
						}

						$newimage = Config::get('app.emp') . "-" . $num_hces1 . "-" . $lin_hces1 . "_" . $t;

						if (!is_file($path . "/" . $newimage . ".jpg")) {
							break;
						}
					}
				}

				imagejpeg($dst_image, $path . "/" . $newimage . ".jpg");
				$this->createThumbs(Config::get('app.emp'), $num_hces1, $newimage . ".jpg");
			}
		}
	}

	private function createPath($emp, $num, $path = "img")
	{
		$this->createFolder($path);
		$this->createFolder($path . "/$emp");
		$this->createFolder($path . "/$emp/$num");
	}

	private function createFolder($folderPath)
	{
		if (!file_exists($folderPath)) {
			mkdir($folderPath, 0775, true);
			chmod($folderPath, 0775);
		}
	}

	private function createThumbs($emp_hces1, $num_hces1, $name_img)
	{
		$sizes = Web_Images_Size::getSizes();

		$imageGenerate = new ImageGenerate();
		$path = "img/thumbs/";

		$this->createPath($emp_hces1, $num_hces1,  $path . $sizes['lote_small']);
		$imageGenerate->generateMini($name_img, $sizes['lote_small']);
		$this->createPath($emp_hces1, $num_hces1,  $path . $sizes['lote_medium']);
		$imageGenerate->generateMini($name_img, $sizes['lote_medium']);
		$this->createPath($emp_hces1, $num_hces1,  $path . $sizes['lote_medium_large']);
		$imageGenerate->generateMini($name_img, $sizes['lote_medium_large']);
		if (!empty($sizes['lote_large'])) {
			$this->createPath($emp_hces1, $num_hces1,  $path . $sizes['lote_large']);
			$imageGenerate->generateMini($name_img, $sizes['lote_large']);
		}
		if (!empty($sizes['square_medium'])) {
			$this->createPath($emp_hces1, $num_hces1,  $path . $sizes['square_medium']);
			$imageGenerate->generateMini($name_img, $sizes['square_medium']);
		}
		if (!empty($sizes['square_large'])) {
			$this->createPath($emp_hces1, $num_hces1,  $path . $sizes['square_large']);
			$imageGenerate->generateMini($name_img, $sizes['square_large']);
		}
	}

	/**
	 * Subida en excel
	 */
	function lotFile($subasta)
	{
		$data = array('subasta' => $subasta);
		return View::make('admin::pages.subasta.lote.importLotFile', $data);
	}

	function lotFileImport($type)
	{
		if ($type == "Excel") {
			return $this->newSubirExcel();
		} elseif ($type = "Dapda") {
			return $this->loadFileXml("Dapda");
		}
	}

	private function loadFileXml($type)
	{
		$idAuction = request()->input('subasta');
		$file = Input::file('file');
		$xml =  simplexml_load_file($file);
		$loadFileLib = new	LoadLotFileLib($idAuction);
		return $loadFileLib->loadMotorFlash($xml);
	}

	private function newSubirExcel()
	{
		$idAuction = request()->input('subasta');
		$file = Input::file('file');
		$rows = Excel::toArray(new ExcelImport, $file)[0];

		$cabeceras = $this->orderTitlesExcel($rows[0]);

		$idOrigins = LoadLotFileLib::existingLots($idAuction);

		$datesFgSub = FgSub::select('dfec_sub', 'dhora_sub', 'hfec_sub', 'hhora_sub', 'tipo_sub')->where('cod_sub', $idAuction)->first();

		$maxReference = FgAsigl0::where('sub_asigl0', $idAuction)->max('ref_asigl0') ?? 0;

		$fxSecMapDataArray = [];
		if (Config::get('app.use_fxsecmap_excel')) {
			$fxSecMapDataArray = FxSecMap::getFxSecMapData();
		}

		$lots = array();
		$newLots = array();
		$updateLots = array();
		$addTime = 0;
		$reflots = array();

		for ($i = 1; $i < count($rows); $i++) {

			if (empty(trim($rows[$i][0]))) {
				continue;
			}

			$lot = $this->createLotObject($rows[$i], $cabeceras);

			if(empty($lot['idsubcategory']) && Config::get('app.default_idsubcategory')) {
				$lot['idsubcategory'] = Config::get('app.default_idsubcategory');
			}

			if (count($fxSecMapDataArray) > 0 && $lot['idsubcategory']) {
				$lot['idsubcategory'] = mb_strtoupper($lot['idsubcategory']);
				$existKeyInArray = array_key_exists($lot['idsubcategory'], $fxSecMapDataArray);
				$lot['idsubcategory'] = $existKeyInArray ? $fxSecMapDataArray[$lot['idsubcategory']] : $lot['idsubcategory'];
			}

			$lot['idauction'] = $idAuction;

			$lot = $this->addFgSubDates($lot, $datesFgSub, $addTime);

			if ($datesFgSub->tipo_sub == 'O') {
				$addTime += Config::get('app.increment_endlot_online', 60);
			}

			if (empty($lot['reflot'])) {
				$lot['reflot'] = ++$maxReference;
			}

			if (empty($lot['idorigin'])) {
				$lot['idorigin'] = $idAuction . "-" . $lot['reflot'];
			}

			$lots[] = $lot;

			$exist = (array_key_exists($lot['idorigin'], $idOrigins)) ? true : false;

			if ($exist) {
				$reflots[] = $lot['reflot'];
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

			AdminLotController::saveUserInfoInUpdatedLots($idAuction, $reflots);
		}

		$img = $this->createImgObject($lots);
		return response($img, 200);
	}

	private function addFgSubDates($lot, $datesFgSub, $addTime)
	{

		if (!empty($lot['startdate'])) {
			return $lot;
		}

		$lot['startdate'] = date('Y-m-d', strtotime($datesFgSub['dfec_sub']));
		$lot['starthour'] = date('H:i:s', strtotime($datesFgSub['dhora_sub']));

		$dateEnd = new DateTime(date('Y-m-d', strtotime($datesFgSub['hfec_sub'])) . ' ' . $datesFgSub['hhora_sub']);

		if (!empty($addTime)) {
			$dateEnd = $dateEnd->add(new DateInterval('PT' . $addTime . "S"));
		}

		$lot['enddate'] = $dateEnd->format('Y-m-d');
		$lot['endhour'] = $dateEnd->format('H:i:s');
		return $lot;
	}

	function createExcelImage()
	{

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

	function addImage()
	{

		$this->guardarImagenLote($_FILES, request('num_hces1'), request('lin_hces1'));
		return response('ok', 200);
	}

	private function orderTitlesExcel($cabecera)
	{
		return array_map('mb_strtolower', $cabecera);
	}

	private function createLotObject($rows, $propiedades)
	{
		$object = array();

		foreach ($rows as $key => $value) {
			if ($propiedades[$key] == 'startdate' || $propiedades[$key] == 'enddate') {
				$object[$propiedades[$key]] = $this->transformDate($value);
			} elseif ($propiedades[$key] == 'starthour' || $propiedades[$key] == 'endhour') {
				$object[$propiedades[$key]] = $this->transformDate($value, 'H:i:s');
			} elseif (Str::contains($propiedades[$key], '/') && count(explode('/', $propiedades[$key])) == 3) {

				[$relacion, $valorRelacion, $propiedadRelacion] = explode('/', $propiedades[$key]);

				/**
				 * en relacion lang valorRelacion es idioma (EN) y valor el campo relleno
				 */

				if ($relacion == 'lang' && !empty($value)) {
					$object['languages'][$valorRelacion][$relacion] = $valorRelacion;
					$object['languages'][$valorRelacion][$propiedadRelacion] = str_replace("\n", "<br>", $value);
				}
			} elseif (Str::contains($propiedades[$key], '/') && count(explode('/', $propiedades[$key])) == 2) {

				[$relacion, $idNameRelacion] = explode('/', $propiedades[$key]);

				if ($relacion == 'feature' && !empty($value)) {

					if (!$this->fgCaracteristicas) {
						$this->fgCaracteristicas = FgCaracteristicas::select('name_caracteristicas', 'id_caracteristicas', 'filtro_caracteristicas', 'value_caracteristicas')->get();
						$this->featureValues = $this->existingFeatureValues();
					}

					$object['features'][$idNameRelacion] = $this->addFeaturePorperty($idNameRelacion, $value, $this->featureValues);
				}
			} elseif ($propiedades[$key] == 'description') {
				$object[$propiedades[$key]] = str_replace("\n", "<br>", $value);
			} else {
				$object[$propiedades[$key]] = $value;
			}
		}

		return $object;
	}

	private function addFeaturePorperty($featureName, $value, &$featurValues = array())
	{
		$feature = [];

		$caracteristica = $this->fgCaracteristicas->filter(function ($item) use ($featureName) {
			return mb_strtolower($item->name_caracteristicas) == mb_strtolower($featureName);
		})->first();

		if ($caracteristica) {

			if ($caracteristica->value_caracteristicas == 'N') {
				$idvaluefeature = null;
				$newValue = $value;
			} else {
				$idvaluefeature = (FgCaracteristicas_Value::addFeature($caracteristica->id_caracteristicas, $value,  $featurValues))['idFeatureValue'];
				$newValue = null;
			}

			$feature['idfeature'] = $caracteristica->id_caracteristicas;
			$feature['idvaluefeature'] = $idvaluefeature;
			$feature['value'] = $newValue;
		}

		return $feature;
	}

	private function transformDate($value, $format = 'Y-m-d')
	{
		return date($format, strtotime($value));
		/* try {
			return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(strtotime($value)))->format($format);
			// return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format($format);
		} catch (\ErrorException $e) {
			return date($format, strtotime($value));
		} */
	}

	private function createImgObject($lots)
	{
		$object = array();

		foreach ($lots as $lot) {

			if (empty($lot['img']) || trim($lot['img']) == "") {
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

	function export($cod_sub)
	{
		return (new PujasExport($cod_sub))->download("pujas_subasta_$cod_sub" . "_" . date("Ymd") . ".xlsx");
	}


	function borrarPuja()
	{

		$info = Input::all();
		#si el tipo de puja es de la tabla auxiliar


		$asigl0 = DB::table("FGASIGL0")->where("EMP_ASIGL0", Config::get("app.emp"))->where("SUB_ASIGL0", $info['subasta'])->where("ref_asigl0", $info['ref'])->first();
		# si es una puja auxiliar borramos la puja auxiliar
		if ($info['asigl0Aux'] == "SI") {
			DB::table("FGASIGL1_AUX")->where("EMP_ASIGL1", Config::get("app.emp"))->where("SUB_ASIGL1", $info['subasta'])->where("ref_asigl1", $info['ref'])->where("lin_asigl1", $info['lin'])->delete();
		} else { # si es una puja normal, borramos la puja normal y actualizamos el implic
			DB::table("FGASIGL1")->where("EMP_ASIGL1", Config::get("app.emp"))->where("SUB_ASIGL1", $info['subasta'])->where("ref_asigl1", $info['ref'])->where("lin_asigl1", $info['lin'])->delete();

			$pujaMasAlta = DB::table("FGASIGL1")->where("EMP_ASIGL1", Config::get("app.emp"))->where("SUB_ASIGL1", $info['subasta'])->where("ref_asigl1", $info["ref"])->orderBy("lin_asigl1", "desc")->first();


			if (!empty($pujaMasAlta)) {
				$nuevo_importe = $pujaMasAlta->imp_asigl1;
				$lic_hces1 = "S";
			} else {
				$nuevo_importe = 0;
				$lic_hces1 = "N";
			}
			DB::table("FGHCES1")->where("emp_hces1", Config::get("app.emp"))->where("num_hces1", $asigl0->numhces_asigl0)->where("lin_hces1", $asigl0->linhces_asigl0)->update([
				"implic_hces1" => $nuevo_importe,
				"lic_hces1" => $lic_hces1
			]);
		}

		echo "OK";
	}

	function getSelectSubastas($ajax = true)
	{
		$subastas = FgSub::select('COD_SUB as id', 'DES_SUB as html')
			->where('SUBC_SUB', '<>', 'N')->get();

		if ($ajax) {
			return response($subastas, 200);
		}

		return $subastas;
	}

	function getSelect2List()
	{

		$query =  mb_strtoupper(request('q'));

		if (!empty($query)) {

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

		if (empty($idauction)) {
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

	#se usa para mirar los lotes que se pueden facturar
	function getSelectLotesFondoGaleria()
	{
		$query = request('q');
		$lots = [];
		if (!empty($query)) {

			$fgasigl0 = FgAsigl0::select("SUB_ASIGL0, REF_ASIGL0, DESCWEB_HCES1, VALUE_CARACTERISTICAS_VALUE, IMPSALHCES_ASIGL0")
				->JoinFghces1Asigl0()
				->joinFgCaracteristicasAsigl0()
				->joinFgCaracteristicasHces1Asigl0()
				->joinFgCaracteristicasValueAsigl0()
				#debemos comprobar que el lote no esta facturado
				->leftjoin('FGART', "FGART.EMP_ART = FGASIGL0.EMP_ASIGL0  AND FGART.NEWREF_ART = CONCAT(CONCAT(SUB_ASIGL0,'-'), REF_ASIGL0)")
				->leftjoin('FGPEDC1', "FGPEDC1.EMP_PEDC1 = FGASIGL0.EMP_ASIGL0  AND FGPEDC1.ART_PEDC1 = FGART.COD_ART")
				#QUEREMOS EVITAR LOS LOTES EN PEDIDOS
				->whereraw("LIN_PEDC1 IS NULL")

				->where("IDCAR_CARACTERISTICAS_VALUE", Config::get("app.ArtistCode"))
				->whereRaw("( (upper(descweb_hces1) like ?)  OR (upper(value_caracteristicas_value) like ?) )", ["%" . mb_strtoupper($query) . "%", "%" . mb_strtoupper($query) . "%"])
				->where("impsalhces_asigl0", ">", 0)
				->where("pc_hces1", ">", 0)

				->get();

			#habra duplicados por que hemos tenido que poner el join con articulos, pero al meterlo en el array nos cargamso los duplicadosp or que tendrabn el mism oindice
			foreach ($fgasigl0 as $lot) {
				$lots[$lot->sub_asigl0 . "-" . $lot->ref_asigl0] = [
					'id' => $lot->sub_asigl0 . "-" . $lot->ref_asigl0,
					'html' =>   $lot->descweb_hces1 . " - " . $lot->value_caracteristicas_value . " - " . ToolsServiceProvider::moneyFormat($lot->impsalhces_asigl0, "€")
				];
			}
		}

		return response($lots, 200);
	}

	function getSelectClients()
	{

		$query =  mb_strtoupper(request('q'));

		if (!empty($query)) {

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

	function getSelect2ClientList()
	{
		$query =  mb_strtoupper(request('q'));

		if (empty($query)) {
			return response();
		}

		$where = [
			['upper(RSOC_CLI)', 'LIKE', "%$query%", 'or'],
			['upper(COD2_CLI)', 'LIKE', "%$query%", 'or'],
			['upper(COD_CLI)', 'LIKE', "%$query%", 'or']
		];

		$clients = FxCli::select('RSOC_CLI as html', 'COD_CLI as id')
			->where($where)->where("BAJA_TMP_CLI", "N")
			->get();

		return response()->json($clients);
	}

	function existingFeatureValues()
	{
		$featureValue = array();

		foreach (FgCaracteristicas_Value::get() as $feature) {
			if (empty($featureValue[$feature->idcar_caracteristicas_value])) {
				$featureValue[$feature->idcar_caracteristicas_value] = array();
			}
			$featureValue[$feature->idcar_caracteristicas_value][$feature->id_caracteristicas_value] = $feature->value_caracteristicas_value;
		}

		return $featureValue;
	}

	#usamos esta función para poder llamar al web service desde el admin
	function send_end_lot_ws(Request $request)
	{

		$sub = Fgsub::select("tipo_sub")->where("cod_sub", $request->sub)->first();
		#por seguridad solo podrá ejecutar este código el usuari ode subastas
		if (Config::get('app.WebServiceClient') && (strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM')) {
			$theme  = Config::get('app.theme');
			if ($sub->tipo_sub == 'O') {
				$rutaLotController = "App\Http\Controllers\\externalws\\$theme\CloseLotControllerOnline";
			} else {
				$rutaLotController = "App\Http\Controllers\\externalws\\$theme\CloseLotController";
			}

			$lotController = new $rutaLotController();

			$lotController->createCloseLot($request->sub, $request->ref);
		}
	}


	/**
	 * Eliminar archivo lote
	 * En Valoralia se utiliza. Mirar como cambiar a controlador de archivos.
	 * */
	public function deleteLoteFile()
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
}
