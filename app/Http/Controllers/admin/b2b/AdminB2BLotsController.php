<?php

namespace App\Http\Controllers\admin\b2b;

use App\Http\Controllers\admin\subasta\AdminLotController;
use App\Http\Controllers\admin\subasta\AdminLoteConcursalController;
use App\Http\Controllers\apilabel\ImgController;
use App\Http\Controllers\apilabel\LotController;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\UpdateLoteApiRequest;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgHces1;
use App\Models\V5\FgHces1_Lang;
use App\Models\V5\FgHces1Files;
use App\Models\V5\FgSub;
use App\Models\V5\FxSec;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class AdminB2BLotsController extends Controller
{
	protected $emp;

	public function __construct()
	{
		$this->emp = Config::get('app.emp');
	}

	public function index(Request $request)
	{
		$userCod = Session::get('user.cod');

		$lots = FgAsigl0::query()
			->select([
				'sub_asigl0',
				'ref_asigl0',
				'cerrado_asigl0',
				'impsalhces_asigl0',
				'impres_asigl0',
				'retirado_asigl0',
				'fini_asigl0',
				'ffin_asigl0',
				'numhces_asigl0',
				'linhces_asigl0',
				'descweb_hces1',
			])
			->addSelect([
				'max_puja' => FgAsigl1::query()
					->select('imp_asigl1')
					->whereColumn('emp_asigl1', 'fgasigl0.emp_asigl0')
					->whereColumn('sub_asigl1', 'fgasigl0.sub_asigl0')
					->whereColumn('ref_asigl1', 'fgasigl0.ref_asigl0')
					->orderByDesc('imp_asigl1')
					->limit(1)
			])
			->joinFghces1Asigl0()
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->where('agrsub_sub', $userCod)
			->when($request->ref_asigl0, function ($query, $ref_asigl0) {
				$query->where('ref_asigl0', $ref_asigl0);
			})
			->when($request->impsalhces_asigl0, function ($query, $impsalhces_asigl0) {
				$query->where('impsalhces_asigl0', $impsalhces_asigl0);
			})
			->when($request->cerrado_asigl0, function ($query, $cerrado_asigl0) {
				$query->where('cerrado_asigl0', $cerrado_asigl0);
			})
			->orderBy($request->input('order') ?? 'ref_asigl0', $request->input('order_dir', 'asc'))
			->paginate(20);

		$tableParams = [
			'img_lot' => 1,
			'ref_asigl0' => 1,
			'descweb_hces1' => 1,
			'cerrado_asigl0' => 1,
			'retirado_asigl0' => 1,
			'impsalhces_asigl0' => 1,
			'impres_asigl0' => 1,
			'max_puja' => 1,
			'fechainicio_format' => 1,
			'fechafin_format' => 1,
		];

		$numberOfColumns = count(array_filter($tableParams));

		$formulario = (object)[
			'ref_asigl0' => FormLib::Text('ref_asigl0', 0, $request->ref_asigl0),
			'impsalhces_asigl0' => FormLib::Text('impsalhces_asigl0', 0, $request->impsalhces_asigl0),
			'cerrado_asigl0' => FormLib::Select('cerrado_asigl0', 0, $request->cerrado_asigl0, ['N' => 'No', 'S' => 'Si'])
		];

		$data = [
			'lots' => $lots,
			'tableParams' => $tableParams,
			'formulario' => $formulario,
			'numberOfColumns' => $numberOfColumns,
		];

		return view('admin::pages.b2b.lots.index', $data);
	}

	public function create()
	{
		$ownerCod = Session::get('user.cod');
		$cod_sub = FgSub::where('agrsub_sub', $ownerCod)->value('cod_sub');

		$fgAsigl0 = new FgAsigl0();
		$fgAsigl0->ref_asigl0 = FgAsigl0::query()
			->select('ref_asigl0')
			->where('sub_asigl0', $cod_sub)
			->max('ref_asigl0') + 1;
		$fgsub = FgSub::select('dfec_sub', 'hfec_sub', 'dhora_sub', 'hhora_sub')->where('cod_sub', $cod_sub)->first();

		$fgAsigl0->fini_asigl0 = $fgsub->dfec_sub;
		$fgAsigl0->hini_asigl0 = $fgsub->dhora_sub;
		$fgAsigl0->ffin_asigl0 = $fgsub->hfec_sub;
		$fgAsigl0->hfin_asigl0 = $fgsub->hhora_sub;

		$formulario = (object) $this->basicFormCreateFgAsigl0($fgAsigl0, $cod_sub);

		if (config('app.useExtraInfo', false)) {
			$this->addExtrasToForm($formulario, $fgAsigl0);
		}

		return view('admin::pages.b2b.lots.create', [
			'formulario' => $formulario,
			'cod_sub' => $cod_sub,
			'fgAsigl0' => $fgAsigl0,
		]);
	}

	public function store(UpdateLoteApiRequest $request)
	{
		$lotControler = new LotController();

		try {
			DB::beginTransaction();

			$lot = $request->validated();

			//idiomas
			$lot["languages"] = $this->requestLangs($request);

			$json = $lotControler->createLot([$lot]);

			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				DB::rollBack();
				return back()->withErrors(['errors' => [$json]])->withInput();
			}

			DB::commit();

			if ($request->has('images')) {

				$json = $this->saveImages($request->file('images'), $request->idorigin);
				$result = json_decode($json);
				if ($result->status == 'ERROR') {
					return redirect(route('admin.b2b.lots'))->with(['warning' => $json, 'success' => array(trans('admin-app.title.created_ok'))]);
				}
			}


			if (!empty($request->file('files'))) {

				$fgAsigl0 = FgAsigl0::query()
					->joinFghces1Asigl0()
					->where([
						['ref_asigl0', $request->input('reflot')],
						['sub_asigl0', $request->input('idauction')]
					])
					->first();

				$this->saveFiles($fgAsigl0, ...$request->file('files'));
			}

			return redirect(route('admin.b2b.lots'))->with(['success' => array(trans('admin-app.title.created_ok'))]);
		} catch (\Throwable $th) {
			DB::rollBack();
			return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
		}
	}

	public function edit($ref_asigl0)
	{
		$owenerCod = Session::get('user.cod');

		$fgAsigl0 = FgAsigl0::query()
			->joinFghces1Asigl0()
			->joinSubastaAsigl0()
			->JoinSessionAsigl0()
			->where([
				['ref_asigl0', $ref_asigl0],
				['agrsub_sub', $owenerCod]
			])
			->first();

		if (!$fgAsigl0) {
			abort(404);
		}

		$cod_sub = $fgAsigl0->sub_asigl0;

		//Todos los lotes necesitan un idorigen para poder ser actualizados, así forzamos a que los tengan
		if (!$fgAsigl0->idorigen_asigl0 || !$fgAsigl0->idorigen_hces1) {
			$this->addIdOrigin($cod_sub, $fgAsigl0->ref_asigl0, $fgAsigl0->numhces_asigl0, $fgAsigl0->linhces_asigl0);
		}

		$images = $this->getImagesFgAsigl0($fgAsigl0);

		$files = FgHces1Files::getAllFilesByLot($fgAsigl0->numhces_asigl0, $fgAsigl0->linhces_asigl0);

		//$videos = $this->getVideosFgAsigl0($fgAsigl0);

		$lotes = FgAsigl0::select('ref_asigl0')
			->where('sub_asigl0', $cod_sub)
			->orderBy('ref_asigl0')
			->pluck('ref_asigl0')->toArray();

		$lotTranslates = FgHces1_Lang::where([
			['num_hces1_lang', $fgAsigl0->numhces_asigl0],
			['lin_hces1_lang', $fgAsigl0->linhces_asigl0]
		])->get();

		$current = array_search($ref_asigl0, $lotes);
		$anterior = $this->adjacentElement($lotes, $current, AdminLoteConcursalController::PREVIOUS_LOT);
		$siguiente = $this->adjacentElement($lotes, $current, AdminLoteConcursalController::NEXT_LOT);

		$formulario = (object) $this->basicFormCreateFgAsigl0($fgAsigl0, $cod_sub);
		$formulario->id['reflot'] = FormLib::TextReadOnly('reflot', 0, $fgAsigl0->ref_asigl0);
		$formulario->id['idorigin'] = FormLib::TextReadOnly('idorigin', 0, old('idorigin', $fgAsigl0->idorigen_asigl0 ?? "$cod_sub-$fgAsigl0->ref_asigl0"));
		//$formulario->videos['files'] = FormLib::File('videos[]', 0, 'multiple="true"');

		$formulario->submit = FormLib::Submit('Actualizar', 'loteUpdate');

		$data = [
			'formulario' => $formulario,
			'fgAsigl0' => $fgAsigl0,
			'cod_sub' => $cod_sub,
			'images' => $images,
			'files' => $files,
			//'videos' => $videos,
			'anterior' => $anterior,
			'siguiente' => $siguiente,
		];

		return view('admin::pages.b2b.lots.edit', $data);
	}

	public function update(UpdateLoteApiRequest $request, $ref_asigl0)
	{
		$response = ['success' => [], 'warning' => [], 'errors' => []];
		$owenerCod = Session::get('user.cod');

		$fgAsigl0 = FgAsigl0::query()
			->joinFghces1Asigl0()
			->joinSubastaAsigl0()
			->where([
				['ref_asigl0', $ref_asigl0],
				['agrsub_sub', $owenerCod]
			])
			->first();

		if (!$fgAsigl0) {
			abort(404);
		}

		$lotControler = new LotController();

		$lot = $request->validated();

		//Eliminar saltos de linea que puedan venir de una importacion en excel
		$lot['description'] = preg_replace("~[\r\n]~", "", $lot['description']);
		$lot["languages"] = $this->requestLangs($request);

		#se pasa como array
		$json = $lotControler->updateLot([$lot]);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return back()->withErrors(['errors' => [$json]])->withInput();
		}

		$this->saveUserInfoInUpdatedLots($fgAsigl0->sub_asigl0, [$ref_asigl0]);

		if (!empty($request->file('files'))) {
			$this->saveFiles($fgAsigl0, ...$request->file('files'));
		}

		//videos
		// if (!empty($request->file('videos'))) {
		// 	$this->saveVideos($fgAsigl0, ...$request->file('videos'));
		// }

		//imagenes
		$imagesb64 = [];
		if (!empty($request->images_url)) {
			foreach ($request->images_url as $image) {

				//$url = $_SERVER['APP_URL'] . $image;
				$url = public_path(explode('?', $image)[0]);

				$imagesb64[] = base64_encode(Image::make($url)->encode()->encoded);
			}
		}

		if (!empty($request->file('images'))) {
			foreach ($request->file('images') as $image) {
				$imagesb64[] = base64_encode(Image::make($image->path())->encode()->encoded);
			}
		}

		$json = $this->saveImages($imagesb64, $request->idorigin, true);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			$response['warning']['Images'] = $json;
		}

		$response['success'][] = trans('admin-app.title.updated_ok');

		return back()->with($response);
	}

	protected function basicFormCreateFgAsigl0(FgAsigl0 $fgAsigl0, $cod_sub)
	{
		//en las subastas presenciales la fecha del lote es indiferente
		//$type = FgSub::where('cod_sub', $cod_sub)->value('tipo_sub');
		$type = FgSub::TIPO_SUB_ONLINE; //por el momento solo se permiten subastas online
		$datesRequired = (int)($type !== FgSub::TIPO_SUB_PRESENCIAL);

		$basicForm =
			[
				'hiddens' => [
					'idauction' => FormLib::Hidden('idauction', 1, $cod_sub),
					'idsubcategory' => FormLib::Hidden("idsubcategory", 1, FxSec::query()->value('cod_sec')),
				],
				'id' => [
					'reflot' => FormLib::TextReadOnly('reflot', 1, old('reflot', $fgAsigl0->ref_asigl0), 'maxlength="999999999"'),
					'idorigin' => FormLib::TextReadOnly('idorigin', 1, old('idorigin', $fgAsigl0->idorigen_asigl0 ?? "$cod_sub-$fgAsigl0->ref_asigl0"), 'maxlength="30"'),
				],
				'imagen' => [
					'image' => FormLib::File('images[]', 0, 'multiple="true" accept=".jpg, .jpeg, .png"'),
				],
				'info' => [
					'title' => FormLib::Text('title', 1, old('title', strip_tags($fgAsigl0->descweb_hces1))),
					'description' => FormLib::TextAreaTiny('description', 0, old('description', $fgAsigl0->desc_hces1)),
					//'extrainfo' => FormLib::TextAreaTiny('extrainfo', 0, old('extrainfo', $fgAsigl0->descdet_hces1)),
				],
				'estados' => [
					'retired' => FormLib::Select('retired', 1, old('retired', $fgAsigl0->retirado_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
					'close' => FormLib::Select('close', 1, old('close', $fgAsigl0->cerrado_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
					'soldprice' => FormLib::Select('soldprice', 1, old('soldprice', $fgAsigl0->remate_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
					'buyoption' => FormLib::Select('buyoption', 1, old('buyoption', $fgAsigl0->compra_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				],
				'fechas' => [
					'startdate' => FormLib::Date("startdate", $datesRequired, old('startdate', $fgAsigl0->fini_asigl0)),
					'starthour' => FormLib::Hour("starthour", $datesRequired, old('starthour', $fgAsigl0->hini_asigl0), 'step="1"'),
					'enddate' => FormLib::Date("enddate", $datesRequired, old('enddate', $fgAsigl0->ffin_asigl0)),
					'endhour' => FormLib::Hour("endhour", $datesRequired, old('endhour', $fgAsigl0->hfin_asigl0), 'step="1"')
				],
				'precios' => [
					'startprice' => FormLib::Int('startprice', 1, old('startprice', $fgAsigl0->impsalhces_asigl0 ?? 0)),
					'reserveprice' => FormLib::Int('reserveprice', 0, old('reserveprice', $fgAsigl0->impres_asigl0 ?? 0)),
					'lowprice' => FormLib::Int('lowprice', 0, old('lowprice', $fgAsigl0->imptas_asigl0 ?? 0)),
					'highprice' => FormLib::Int('highprice', 0, old('highprice', $fgAsigl0->imptash_asigl0 ?? 0)),
				],
				'files' => [
					'files' => FormLib::File('files[]', 0, 'multiple="true"'),
				],
				'submit' => FormLib::Submit('Guardar', 'loteStore')
			];

		return $basicForm;
	}

	protected function requestLangs($request)
	{
		$languages = [];

		if (!$request->has('lang')) {
			return $languages;
		}

		$inputsLanguages = $request->get('lang');

		foreach ($inputsLanguages as $key => $value) {
			#si no viene ningun valor no lo agregamos, ya que si no creará los campos vacios
			if (!empty($request->get('title_lang')[$key]) || !empty($request->get('description_lang')[$key]) || !empty($request->get('search_lang')[$key])) {

				$languages[] = [
					'lang' => $value,
					'title' => $request->get('title_lang')[$key],
					'description' => $request->get('description_lang')[$key],
					'extrainfo' => $request->get('extrainfo_lang')[$key],
					'search' => $request->get('search_lang')[$key],
				];
			}
		}

		return $languages;
	}

	protected function saveImages($images, $idorigin, $isEncode = false)
	{
		$itemImages = [];

		foreach ($images as $key => $image) {
			$item = ($isEncode)
				? ['img64' => $image]
				: ['img64' => base64_encode(Image::make($image->path())->encode()->encoded)];

			$itemImages[] = [
				'idoriginlot' => $idorigin,
				'order' => $key,
			] + $item;
		}
		$imgController = new ImgController();


		if (count($itemImages) > 0) {
			return $imgController->createImg($itemImages);
		}

		return json_encode(['status' => "SUCCESS"]);
	}

	public function addIdOrigin($cod, $ref, $numhces, $linhces)
	{
		$newIdOrigen = "$cod-$ref";

		FgAsigl0::where([
			['ref_asigl0', $ref],
			['sub_asigl0', $cod]
		])->update(
			['idorigen_asigl0' => $newIdOrigen]
		);

		FgHces1::where([
			['num_hces1', $numhces],
			['lin_hces1', $linhces],
			['sub_hces1', $cod],
		])->update(
			['idorigen_hces1' => $newIdOrigen]
		);

		return $newIdOrigen;
	}

	protected function getImagesFgAsigl0($fgAsigl0)
	{
		//dd($fgAsigl0);
		$path = "/img/$this->emp/$fgAsigl0->numhces_asigl0/";
		$systemPath = getcwd() . $path;

		$images = is_dir($systemPath) ? array_diff(scandir($systemPath), ['.', '..']) : [];

		$validImages = array_filter($images, function ($image) use ($fgAsigl0) {
			$imageName = "{$this->emp}-{$fgAsigl0->numhces_asigl0}-{$fgAsigl0->linhces_asigl0}";
			$isThisLine = strpos($image, "{$imageName}.") !== false || strpos($image, "{$imageName}_") !== false;

			$isHidden = strpos($image, "-NV");

			return !$isHidden && $isThisLine;
		});

		$paths = array_map(function ($image) use ($path) {
			return $path . $image;
		}, $validImages);

		return $paths;
	}

	protected function adjacentElement(array $array, $currentKey, $position = AdminLotController::PREVIOUS_LOT)
	{
		if (!isset($array[$currentKey])) {
			return false;
		}

		if ($position == AdminLotController::PREVIOUS_LOT) {
			end($array);
		}

		do {
			$key = array_search(current($array), $array);

			switch ($position) {
				case AdminLotController::NEXT_LOT:
					$element = next($array);
					break;

				default:
					$element = prev($array);
					break;
			}
		} while ($key != $currentKey);

		return $element;
	}

	public static function saveUserInfoInUpdatedLots($cod_sub, $refLots)
	{
		$userSession = Session::get('user');

		$update = [
			'usr_update_asigl0' => strval($userSession['usrw']),
			'date_update_asigl0' => date('Y-m-d H:i:s'),
		];

		FgAsigl0::where('sub_asigl0', $cod_sub)->whereIn('ref_asigl0', $refLots)->update($update);
	}

	public function destroy($ref_asigl0)
	{
		$ownerCod = Session::get('user.cod');
		$fgAsigl0 = FgAsigl0::query()
			->joinFghces1Asigl0()
			->joinSubastaAsigl0()
			->where([
				['ref_asigl0', $ref_asigl0],
				['agrsub_sub', $ownerCod]
			])
			->first();

		if (!$fgAsigl0) {
			abort(404);
		}

		$lot = ['idorigin' => $fgAsigl0->idorigen_asigl0];
		$json = (new LotController())->eraseLot($lot);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return back()->withErrors(['errors' => [$json]])->withInput();
		}

		return back()->with(['success' => [trans('admin-app.title.deleted_ok')]]);
	}

	protected function saveFiles($fgAsigl0, UploadedFile ...$files)
	{
		$relativePath = "/$this->emp/$fgAsigl0->num_hces1/$fgAsigl0->lin_hces1/files/";
		$path = getcwd() . "/files/$relativePath";

		if (!is_dir(str_replace("\\", "/", $path))) {
			mkdir(str_replace("\\", "/", $path), 0775, true);
		}

		foreach ($files as $file) {
			$newfile = str_replace("\\", "/", $path . '/' . $file->getClientOriginalName());

			copy($file->getPathname(), $newfile);
		}
	}
}
