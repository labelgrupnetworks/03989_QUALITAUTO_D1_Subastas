<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\apilabel\FeatureValueController;
use App\Http\Controllers\apilabel\ImgController;
use App\Http\Controllers\apilabel\LotController;
use App\Http\Controllers\Controller;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgOrlic;
use Illuminate\Http\Request;
use App\libs\FormLib;
use App\Models\V5\FxCli;
use App\Models\V5\FxSec;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;
use App\Http\Requests\admin\UpdateLoteApiRequest;
use App\Models\V5\FgHces1;
use App\Models\V5\FgSub;
use App\Models\V5\FgCaracteristicas;
use App\Models\V5\FgCaracteristicas_Value;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Models\V5\FgCaracteristicas_Hces1_Lang;
use App\Models\V5\FgCaracteristicas_Value_Lang;
use App\Models\V5\FgHces1_Lang;
use App\Models\V5\FgHcesmt;
use App\Models\V5\FxPro;

use App\Http\Controllers\PdfController;
use App\Http\Controllers\CustomControllers;
use App\Models\V5\FgNft;
use App\Models\V5\FgNftNetwork;
use App\Models\V5\FxAlm;

use App\Http\Controllers\externalws\vottun\VottunController;
use App\Providers\ToolsServiceProvider;
use Exception;
use Illuminate\Support\Facades\Log;

class AdminLotController extends Controller
{

	protected $emp;
	protected $isRender;
	protected $resource_name;
	protected $parent_name;

	const PREVIOUS_LOT = 1;
	const NEXT_LOT = 2;

	public function __construct($isRender = false)
	{
		$this->isRender = $isRender;
		$this->emp = Config::get('app.emp');
		$this->resource_name = 'lotes';
		$this->parent_name = 'subastas';
		view()->share(['menu' => 'subastas']);
	}

	public function index(Request $request, $cod_sub)
	{

		$tipo_sub = FgSub::where('cod_sub', $cod_sub)->first()->tipo_sub;

		$lotes = FgAsigl0::query();
		if ($request->ref_asigl0) {
			$lotes->where('ref_asigl0', '=', $request->ref_asigl0);
		}
		if ($request->idorigen_asigl0) {
			$lotes->where('upper(idorigen_asigl0)', 'like', "%" . mb_strtoupper($request->idorigen_asigl0) . "%");
		}
		if ($request->cerrado_asigl0) {
			$lotes->where('cerrado_asigl0', '=', $request->cerrado_asigl0);
		}
		if ($request->impsalhces_asigl0) {
			$lotes->where('impsalhces_asigl0', '=', $request->impsalhces_asigl0);
		}
		if ($request->destacado_asigl0) {
			$lotes->where('destacado_asigl0', '=', $request->destacado_asigl0);
		}
		if ($request->retirado_asigl0) {
			$lotes->where('retirado_asigl0', '=', $request->retirado_asigl0);
		}
		if ($request->oculto_asigl0) {
			$lotes->where('oculto_asigl0', '=', $request->oculto_asigl0);
		}
		if ($request->impres_asigl0) {
			$lotes->where('impres_asigl0', '=', $request->impres_asigl0);
		}
		if ($request->impres_asigl0) {
			$lotes->where('impres_asigl0', '=', $request->impres_asigl0);
		}
		if ($request->imptas_asigl0) {
			$lotes->where('imptas_asigl0', '=', $request->imptas_asigl0);
		}
		if ($request->imptash_asigl0) {
			$lotes->where('imptash_asigl0', '=', $request->imptash_asigl0);
		}
		if ($request->comlhces_asigl0) {
			$lotes->where('comlhces_asigl0', '=', $request->comlhces_asigl0);
		}
		if ($request->comphces_asigl0) {
			$lotes->where('comphces_asigl0', '=', $request->comphces_asigl0);
		}
		if ($request->prop_hces1) {
			$lotes->where('prop_hces1', '=', $request->prop_hces1);
		}
		if ($request->descweb_hces1) {
			$lotes->where('upper(descweb_hces1)', 'like', "%" . mb_strtoupper($request->descweb_hces1) . "%");
		}
		if ($request->fini_asigl0) {
			$lotes->where('fini_asigl0', '>=' ,$request->fini_asigl0);
		}
		if ($request->ffin_asigl0) {
			$lotes->where('ffin_asigl0', '<=',$request->ffin_asigl0);
		}

		$select = ['SUB_ASIGL0', 'REF_ASIGL0', 'IDORIGEN_ASIGL0', 'CERRADO_ASIGL0', 'IMPSALHCES_ASIGL0', 'impres_asigl0', 'imptas_asigl0', 'imptash_asigl0', 'comlhces_asigl0', 'comphces_asigl0', 'DESTACADO_ASIGL0', 'RETIRADO_ASIGL0', 'OCULTO_ASIGL0', 'NUMHCES_ASIGL0', 'LINHCES_ASIGL0', 'PROP_HCES1', 'DESCWEB_HCES1', 'fini_asigl0', 'ffin_asigl0'];

		$tableParams = [
			'sub_asigl0' => 0, 'ref_asigl0' => Config::get('external_id', 1), 'idorigen_asigl0' => Config::get('external_id', 0), 'prop_hces1' => 1,
			'descweb_hces1' => 0, 'artist_name' => 0, 'impsalhces_asigl0' => 1, 'max_puja' => 1, 'max_orden' => 1, 'impres_asigl0' => 0, 'imptas_asigl0' => 0, 'imptash_asigl0' => 0,
			'comlhces_asigl0' => 0, 'comphces_asigl0' => 0, 'cerrado_asigl0' => 1, 'destacado_asigl0' => 1, 'retirado_asigl0' => 1, 'oculto_asigl0' => 1,
			'fini_asigl0' => 0, 'ffin_asigl0' => 0
		];

		$lotes = $lotes->select($select)
			->joinFghces1Asigl0()
			->where('SUB_ASIGL0', $cod_sub);

		if(config('app.ArtistCode', false)){
			$lotes = $lotes->withArtist()
			->when($request->artist_name, function($query, $artist) {
				//return $query->havingRaw("upper(LISTAGG(FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE, ', ')) LIKE upper('%$artists%')");
				return $query->where('upper(FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE)', 'like', "%" . mb_strtoupper($artist) . "%");
			});
		}

		$lotes = $lotes->orderBy($request->filled('order') ? $request->order : 'ref_asigl0', $request->filled('order_dir') ? $request->order_dir : 'asc')
			->paginate(30, '*', 'lotesPage');


		$fgAsigl0 = new FgAsigl0();
		$lotesRef = $lotes->pluck('ref_asigl0');

		$ordenes = FgOrlic::where('sub_orlic', $cod_sub)->whereIn('ref_orlic', $lotesRef)->get();
		$pujas = FgAsigl1::where('sub_asigl1', $cod_sub)->whereIn('ref_asigl1', $lotesRef)->get();

		$propietarios = null;
		if(config('app.useProviders', 0)){
			$propietarios = FxPro::select('cod_pro', 'nom_pro')->pluck('nom_pro', 'cod_pro');
		}
		else{
			$propietarios = FxCli::select('cod_cli', 'rsoc_cli')->pluck('rsoc_cli', 'cod_cli');
		}

		$formulario = (object)[
			'ref_asigl0' => FormLib::Text('ref_asigl0', 0, $request->ref_asigl0, '', ''),
			'idorigen_asigl0' => FormLib::Text('idorigen_asigl0', 0, $request->idorigen_asigl0, '', ''),
			'sub_asigl0' => FormLib::Text('sub_asigl0', 0, $request->sub_asigl0, '', ''),
			'prop_hces1' => FormLib::Select2WithAjax('prop_hces1', 0, $request->prop_hces1, '', route('client.list'), trans('admin-app.placeholder.owner')),
			'descweb_hces1' => FormLib::Text('descweb_hces1', 0, $request->descweb_hces1, '', ''),
			'artist_name' => FormLib::Text('artist_name', 0, $request->artists_name),
			'impsalhces_asigl0' => FormLib::Text('impsalhces_asigl0', 0, $request->impsalhces_asigl0, '', ''),
			'impres_asigl0' => FormLib::Text('impres_asigl0', 0, $request->impres_asigl0, '', ''),
			'imptas_asigl0' => FormLib::Text('imptas_asigl0', 0, $request->imptas_asigl0, '', ''),
			'imptash_asigl0' => FormLib::Text('imptash_asigl0', 0, $request->imptash_asigl0, '', ''),
			'comlhces_asigl0' => FormLib::Text('comlhces_asigl0', 0, $request->comlhces_asigl0, '', ''),
			'comphces_asigl0' => FormLib::Text('comphces_asigl0', 0, $request->comphces_asigl0, '', ''),
			'cerrado_asigl0' => FormLib::Select('cerrado_asigl0', 0, $request->cerrado_asigl0, ['S' => 'Si', 'N' => 'No']),
			'destacado_asigl0' => FormLib::Select('destacado_asigl0', 0, $request->destacado_asigl0, ['S' => 'Si', 'N' => 'No']),
			'retirado_asigl0' => FormLib::Select('retirado_asigl0', 0, $request->retirado_asigl0, ['S' => 'Si', 'N' => 'No']),
			'oculto_asigl0' => FormLib::Select('oculto_asigl0', 0, $request->oculto_asigl0, ['S' => 'Si', 'N' => 'No']),
			'fini_asigl0' => FormLib::Date('fini_asigl0', 0, $request->fini_asigl0),
			'ffin_asigl0' => FormLib::Date('ffin_asigl0', 0, $request->ffin_asigl0),
		];

		//retorna la vista completa o solamente la tabla
		$render = $this->isRender;
		$resource_name = $this->resource_name;
		$parent_name = $this->parent_name;

		$data = compact('cod_sub', 'lotes', 'pujas', 'ordenes', 'formulario', 'render', 'tableParams', 'propietarios', 'resource_name', 'parent_name', 'tipo_sub');

		if ($render) {
			return view('admin::pages.subasta.lotes._table', $data)->render();
		}

		return view('admin::pages.subasta.lotes.index', $data);
	}

	public function create($cod_sub)
	{
		$fgAsigl0 = new FgAsigl0();
		$fgAsigl0->ref_asigl0 = FgAsigl0::select('ref_asigl0')->where('sub_asigl0', $cod_sub)->max('ref_asigl0') + 1;
		$fgsub = FgSub::select('dfec_sub', 'hfec_sub', 'dhora_sub', 'hhora_sub')->where('cod_sub', $cod_sub)->first();

		$fgAsigl0->fini_asigl0 = $fgsub->dfec_sub;
		$fgAsigl0->hini_asigl0 = $fgsub->dhora_sub;
		$fgAsigl0->ffin_asigl0 = $fgsub->hfec_sub;
		$fgAsigl0->hfin_asigl0 = $fgsub->hhora_sub;

		$resource_name = $this->resource_name;
		$parent_name = $this->parent_name;

		$formulario = (object) $this->basicFormCreateFgAsigl0($fgAsigl0, $cod_sub);
		$formulario = $this->addTranslationsForm($formulario);

		if(config('app.useExtraInfo', false)){
			$this->addExtrasToForm($formulario, $fgAsigl0);
		}

		return view('admin::pages.subasta.lotes.create', compact('formulario', 'fgAsigl0', 'cod_sub', 'resource_name', 'parent_name'));
	}

	public function store(UpdateLoteApiRequest $request, $cod_sub)
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

			if($request->get('es_nft_asigl0') == 'S'){
				FgAsigl0::where([['ref_asigl0', $request->reflot], ['sub_asigl0', $cod_sub]])
				->update([
					'es_nft_asigl0' => $request->es_nft_asigl0,
					'oculto_asigl0' => 'S'
				]);
			}

			DB::commit();

			if ($request->has('images')) {

				$json = $this->saveImages($request->file('images'), $request->idorigin);
				$result = json_decode($json);
				if ($result->status == 'ERROR') {
					return redirect(route("$this->parent_name.$this->resource_name.edit", ['subasta' => $cod_sub, 'lote' => 1]))->with(['warning' => $json, 'success' => array(trans('admin-app.title.created_ok'))]);
				}
			}

			return redirect(route("$this->parent_name.$this->resource_name.edit", ['subasta' => $cod_sub, 'lote' => $request->reflot]))->with(['success' => array(trans('admin-app.title.created_ok'))]);
		} catch (\Throwable $th) {
			DB::rollBack();
			return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
		}
	}

	public function edit($cod_sub, $ref_asigl0)
	{
		$render = request('render', false);

		$fgAsigl0 = FgAsigl0::joinFghces1Asigl0()->where([['ref_asigl0', $ref_asigl0], ['sub_asigl0', $cod_sub]])->first();

		if (!$fgAsigl0) {
			abort(404);
		}

		//Todos los lotes necesitan un idorigen para poder ser actualizados, así forzamos a que los tengan
		if (!$fgAsigl0->idorigen_asigl0 || !$fgAsigl0->idorigen_hces1) {
			$this->addIdOrigin($cod_sub, $fgAsigl0->ref_asigl0, $fgAsigl0->numhces_asigl0, $fgAsigl0->linhces_asigl0);
		}

		$images = $this->getImagesFgAsigl0($fgAsigl0);
		$files = $this->getFilesFgAsigl0($fgAsigl0);
		$videos = $this->getVideosFgAsigl0($fgAsigl0);

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
		$formulario->files['files'] = FormLib::File('files[]', 0, 'multiple="true"');
		$formulario->videos['files'] = FormLib::File('videos[]', 0, 'multiple="true"');

		$formulario->submit = FormLib::Submit('Actualizar', 'loteUpdate');

		$this->addTranslationsForm($formulario, $lotTranslates);

		if(config('app.useExtraInfo', false)){
			$this->addExtrasToForm($formulario, $fgAsigl0);
		}

		if(config('app.useNft', false) && $fgAsigl0->es_nft_asigl0 == 'S'){
			$this->addNftToForm($formulario, $fgAsigl0);
		}

		$features = FgCaracteristicas::getAllFeatures();

		$featuresValues = FgCaracteristicas_Value::SelectAllForInput();
		$featuresHces1 = FgCaracteristicas_Hces1::getByLot($fgAsigl0->numhces_asigl0, $fgAsigl0->linhces_asigl0);
		$featuresHces1Lang = FgCaracteristicas_Hces1_Lang::getByLot($fgAsigl0->numhces_asigl0, $fgAsigl0->linhces_asigl0);

		$data = compact('formulario', 'fgAsigl0', 'cod_sub', 'images', 'files', 'videos', 'anterior', 'siguiente', 'render', 'features', 'featuresValues', 'featuresHces1','featuresHces1Lang');
		return view('admin::pages.subasta.lotes.edit', $data);
	}

	public function update(UpdateLoteApiRequest $request, $cod_sub, $ref_asigl0)
	{
		$response = ['success' => [], 'warning' => [], 'errors' => []];

		$fgAsigl0 = FgAsigl0::joinFghces1Asigl0()->where([['ref_asigl0', $ref_asigl0], ['sub_asigl0', $cod_sub]])->first();

		if (!$fgAsigl0) {
			abort(404);
		}

		$lotControler = new LotController();

		$lot = $request->validated();

		# dentro de la funcion hay un webconfig que si está activo le asigna el 100% de la propiedad del lote al propitario
		$this->assignOwner($lot,$cod_sub, $ref_asigl0);

		//Eliminar saltos de linea que puedan venir de una importacion en excel
		$lot['description'] = preg_replace("~[\r\n]~", "", $lot['description']);

		$lot["features"] = $this->requetsFeatures();

		//idiomas
		$lot["languages"] = $this->requestLangs($request);

		#se pasa como array
		$json = $lotControler->updateLot([$lot]);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return back()->withErrors(['errors' => [$json]])->withInput();
		}

		//Nft
		$resultNftProcess = null;
		if($request->es_nft_asigl0 == 'S') {
			$resultNftProcess = $this->nftProcess($request, $fgAsigl0);
			if($resultNftProcess->status == 'error') {
				$response['errors']['nft'] = $resultNftProcess->message;
			}
		}else{ #si ya está publicado y han decidido mintearlo
			if(!empty($request->mint_nft)){
				$resultNftMint =  $this->mintNFT($fgAsigl0->sub_asigl0, $fgAsigl0->ref_asigl0);
				if($resultNftMint->status == 'error') {
					$response['errors']['nft'] = $resultNftMint->message;
				}
			}
		}

		//files
		if (!empty($request->file('files'))) {
			$this->saveFiles($fgAsigl0, ...$request->file('files'));
		}

		//videos
		if (!empty($request->file('videos'))) {
			$this->saveVideos($fgAsigl0, ...$request->file('videos'));
		}

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
			//return back()->withErrors(['errors' => [$json]])->withInput();
			$response['warning']['Images'] = $json;
			$response['success'][] = trans('admin-app.title.updated_ok');
			return redirect(route("$this->parent_name.$this->resource_name.edit", ['subasta' => $cod_sub, 'lote' => $ref_asigl0]))
					->with($response);
		}

		$response['success'][] = trans('admin-app.title.updated_ok');

		return back()->with($response);
	}

	public function destroy($cod_sub, $ref_asigl0)
	{

		$fgAsigl0 = FgAsigl0::joinFghces1Asigl0()->where([['ref_asigl0', $ref_asigl0], ['sub_asigl0', $cod_sub]])->first();

		if (!$fgAsigl0) {
			abort(404);
		}

		$lot = ['idorigin' => $fgAsigl0->idorigen_asigl0];
		$json = (new LotController())->eraseLot($lot);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return back()->withErrors(['errors' => [$json]])->withInput();
		}

		return redirect(route("$this->parent_name.show", ['cod_sub' => $cod_sub]))->with(['success' => array(trans('admin-app.title.deleted_ok'))]);
	}

	public function publishNft($cod_sub, $ref_asigl0)
	{
		$lote = FgAsigl0::select("NUMHCES_ASIGL0, LINHCES_ASIGL0")->where("SUB_ASIGL0", $cod_sub)->where("REF_ASIGL0", $ref_asigl0)->first();

		$vottun = new VottunController();
		$res = $vottun->uploadFile($lote->numhces_asigl0, $lote->linhces_asigl0);

		if($res->status == "success"){
			$res = $vottun->uploadMetadata($lote->numhces_asigl0, $lote->linhces_asigl0);
		}

		return $res;
		//return response()->json($res);
	}

	public function mintNFT($cod_sub, $ref_asigl0)
	{
		$lote = FgAsigl0::select("NUMHCES_ASIGL0, LINHCES_ASIGL0")->where("SUB_ASIGL0", $cod_sub)->where("REF_ASIGL0", $ref_asigl0)->first();


		$res = (new VottunController())->mint($lote->numhces_asigl0, $lote->linhces_asigl0);

		return $res;

	}

	public function unpublishNft($cod_sub, $ref_asigl0)
	{
		$lote = FgAsigl0::select("NUMHCES_ASIGL0, LINHCES_ASIGL0")->where("SUB_ASIGL0", $cod_sub)->where("REF_ASIGL0", $ref_asigl0)->first();
		if(!$lote){
			abort(404);
		}

		$nft = FgNft::where([["numhces_nft", $lote->numhces_asigl0], ["linhces_nft", $lote->linhces_asigl0]])->first();
		if(!$nft){
			return back()->withErrors(['errors' => ['El nft no existe']])->withInput();
		}

		if($nft->mint_id_nft){
			return back()->withErrors(['errors' => ['No se puede despublicar un NFT ya mintado']])->withInput();
		}

		FgNft::where([["numhces_nft", $lote->numhces_asigl0], ["linhces_nft", $lote->linhces_asigl0]])
			->update([
				"hashfile_nft" => null,
				"hashmetadata_nft" => null,
			]);

		return back()->with(['success' => array(trans('admin-app.title.updated_ok'))]);
	}

	public function getOrder($cod_sub)
	{

		$lots = FgAsigl0::select('ref_asigl0', 'descweb_hces1', 'orden_hces1', 'num_hces1', 'lin_hces1')
		->joinFghces1Asigl0()->where('sub_asigl0', $cod_sub)->orderby('orden_hces1')->orderby('ref_asigl0')->get();

		$parent_name = $this->parent_name;

		return view('admin::pages.subasta.lotes.order', compact('cod_sub', 'lots', 'parent_name'));

	}

	public function saveOrder(Request $request, $cod_sub)
	{

		$lots = FgAsigl0::select('num_hces1','lin_hces1' ,'orden_hces1')
			->joinFghces1Asigl0()->where('sub_asigl0', $cod_sub)->get();
		$order = collect($request->numLin)->flip();

		foreach ($lots as $lot) {

			$lot->orden_hces1 = $order[$lot->num_hces1.'-'.$lot->lin_hces1] + 1;

			//Comprueba si el modelo a sido modificado para no lanzar más actualizaciones de las necesarias
			if($lot->isDirty()){
				FgHces1::where([
					['num_hces1', $lot->num_hces1],
					['lin_hces1', $lot->lin_hces1]
				])->update([
					'orden_hces1' => $lot->orden_hces1
				]);
			}
		}

		return back()->with(['success' => array(trans('admin-app.title.updated_ok'))]);
	}

	protected function requetsFeatures()
	{
		$featuresKeys = FgCaracteristicas::getAllFeatures();
		$featureSelect = request("feature_select");
		$featureInput = request("feature_input");
		$featureInputLang = request("feature_input_lang");

		$features = array();
		foreach ($featuresKeys as $key => $featureKey) {
			#si no viene ningun valor no lo agregamos, ya que si no creará los campos vacios
			if (!empty($featureSelect[$key]) || !empty($featureInput[$key])) {
				$feature = array();
				$feature["idfeature"] = $key;
				$feature["idvaluefeature"] = $featureSelect[$key] ?? "";
				$feature["value"] = $featureInput[$key] ?? "";
				$features[] = $feature;
			}

			#multiidioma
			foreach(config('app.locales') as $lang => $nameLang){
				if(!empty($featureInputLang[$lang]) && !empty($featureInputLang[$lang][$key])){
					$feature = array();
					$feature["idfeature"] = $key;
					$feature["idvaluefeature"] =  "";
					$feature["value"] = $featureInputLang[$lang][$key];
					$feature["lang"] = $lang;
					$features[] = $feature;
				}
			}


		}

		return $features;
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
					'search' => $request->get('search_lang')[$key],
				];
			}
		}

		return $languages;
	}

	private function nftProcess(Request $request, FgAsigl0 $fgAsigl0)
	{
		$nft = FgNft::where([
			['numhces_nft', $fgAsigl0->num_hces1],
			['linhces_nft', $fgAsigl0->lin_hces1]
		])->first();

		//Si ya tiene hash no debemos hacer nada
		if($nft && !empty($nft->hashfile_nft)){
			return false;
		}

		$path_nft = '';
		if($request->hasFile('file_nft')){
			$emp = config('app.emp');

			//Estará fuera del public
			$path = "nft/$emp";
			$webPath = storage_path("app/$path");

			if (!file_exists($webPath))
            {
				try {
					mkdir($webPath, 0775, true);
					chmod($webPath,0775);

				} catch (Exception $e) {
					# Controlar el error en el log
				   Log::info( $e->getMessage());
				}
            }

			$file = $request->file('file_nft');
			$nameFile = $emp."_".$fgAsigl0->num_hces1."_".$fgAsigl0->lin_hces1.".".$file->getClientOriginalExtension();

			$path_nft = $path."/".$nameFile;

			$file->move($webPath, $nameFile);
		}

		//Si existe y viene el valor a N eliminamos info de NFT
		if($nft && $request->get('es_nft_asigl0', 'N') == 'N'){
			$this->deleteNft($request, $fgAsigl0);
			return (object)['status' => 'success'];
		}

		//Si existe realizamos update
		if($nft){
			return $this->updateNft($request, $fgAsigl0, $path_nft);
		}

		//Si no existe y es nft viene a S lo creamos
		if($request->get('es_nft_asigl0', 'N') == 'S'){
			$this->createNft($request, $fgAsigl0, $path_nft);
			return (object)['status' => 'success'];
		}

		return (object)['status' => 'error', 'message' => 'No se pudo procesar el archivo'];
	}

	protected function updateNft(Request $request, FgAsigl0 $fgAsigl0, $path_nft)
	{
		FgAsigl0::where([['ref_asigl0', $fgAsigl0->ref_asigl0], ['sub_asigl0', $fgAsigl0->sub_asigl0]])
				->update([
					'es_nft_asigl0' => $request->es_nft_asigl0
				]);
		$update = [
			'name_nft' => $request->name_nft,
			'description_nft' => $request->description_nft,
			'created_nft' => $request->created_nft,

			'media_type_nft' => $request->media_type_nft,
			'network_nft' => $request->network_nft,
			'total_tokens_nft' => $request->total_tokens_nft,
			'n_of_token_nft' => $request->n_of_token_nft,
			'artista_nft' => $request->artista_nft,
		];

		if(!empty( $path_nft)){
			$update['path_nft'] = $path_nft;
		}

		FgNft::where([
			['numhces_nft', $fgAsigl0->num_hces1],
			['linhces_nft', $fgAsigl0->lin_hces1]
		])->update($update);

		if(!empty($request->publish_nft)){
			return $this->publishNft($fgAsigl0->sub_asigl0, $fgAsigl0->ref_asigl0);
		}

		return (object)['status' => 'success'];
	}

	/**
	 * Crear Nft
	 * Al crear un nft el lote se debe dejar como oculto por defecto
	 */
	protected function createNft(Request $request, FgAsigl0 $fgAsigl0, $path_nft)
	{
		FgAsigl0::where([['ref_asigl0', $fgAsigl0->ref_asigl0], ['sub_asigl0', $fgAsigl0->sub_asigl0]])
				->update([
					'es_nft_asigl0' => $request->es_nft_asigl0,
					'oculto_asigl0' => 'S'
				]);

		FgNft::create([
			'numhces_nft' => $fgAsigl0->num_hces1,
			'linhces_nft' => $fgAsigl0->lin_hces1,
			'name_nft' => $request->name_nft,
			'description_nft' => $request->description_nft,
			'created_nft' => $request->created_nft,
			'path_nft' => $path_nft,
			'media_type_nft' => $request->media_type_nft,
			'network_nft' => $request->network_nft,
			'total_tokens_nft' => $request->total_tokens_nft,
			'n_of_token_nft' => $request->n_of_token_nft,
			'artista_nft' => $request->artista_nft,
		]);
	}

	protected function deleteNft(Request $request, FgAsigl0 $fgAsigl0)
	{
		FgAsigl0::where([['ref_asigl0', $fgAsigl0->ref_asigl0], ['sub_asigl0', $fgAsigl0->sub_asigl0]])
				->update([
					'es_nft_asigl0' => $request->es_nft_asigl0,
				]);

		FgNft::where([
			['numhces_nft', $fgAsigl0->num_hces1],
			['linhces_nft', $fgAsigl0->lin_hces1]
		])->delete();
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

		if(count($itemImages) > 0){

			return $imgController->createImg($itemImages);
		}else{
			return   json_encode(['status' => "SUCCESS" ]);
		}

	}

	protected function getImagesFgAsigl0($fgAsigl0)
	{
		$imagenes = array();
		$pathWithoutPosition = "/img/$this->emp/$fgAsigl0->numhces_asigl0/$this->emp-$fgAsigl0->numhces_asigl0-$fgAsigl0->linhces_asigl0";
		$path = "$pathWithoutPosition.jpg";

		if (is_file(str_replace("\\", "/", getcwd() . $path))) {
			$imagenes[] = $path;
		}

		for ($t = 1; $t < 20; $t++) {

			$pos = str_pad($t, 2, 0, STR_PAD_LEFT);
			if (is_file(str_replace("\\", "/", getcwd() . $pathWithoutPosition . "_$pos.jpg"))) {
				$imagenes[] = $pathWithoutPosition . "_$pos.jpg";
			}
		}

		return $imagenes;
	}

	protected function getFilesFgAsigl0($fgAsigl0)
	{
		$path = "/files/$this->emp/$fgAsigl0->num_hces1/$fgAsigl0->lin_hces1/files/";
		$files = [];
		if (is_dir(getcwd() . $path)) {
			$files = array_diff(scandir(getcwd() . $path), ['.', '..']);
		}

		return $files;
	}

	protected function saveFiles($fgAsigl0, UploadedFile ...$files)
	{
		$relativePath = "/files/$this->emp/$fgAsigl0->num_hces1/$fgAsigl0->lin_hces1/files/";
		$path = getcwd() . $relativePath;

		if (!is_dir(str_replace("\\", "/", $path))) {
			mkdir(str_replace("\\", "/", $path), 0775, true);
		}

		foreach ($files as $file) {
			$newfile = str_replace("\\", "/", $path . '/' . $file->getClientOriginalName());

			copy($file->getPathname(), $newfile);

			/* FgHces1Files::create([
				'numhces_hces1_files' => $fgAsigl0->num_hces1,
				'linhces_hces1_files' => $fgAsigl0->lin_hces1,
				'lang_hces1_files' => null,
				'path_hces1_files' => $relativePath . $file->getClientOriginalName(),
				'external_url_hces1_files' => null,
				'name_hces1_files' => $file->getClientOriginalName(),
				'description_hces1_files' => 'test',
				'order_hces1_files' => 1,
				'image_hces1_files' => null,
				'is_active_hces1_files' => 'S',
				'permission_hces1_files' => 'N',
			]); */
		}
	}

	protected function getVideosFgAsigl0($fgAsigl0)
	{
		$path = "/files/videos/$this->emp/$fgAsigl0->num_hces1/$fgAsigl0->lin_hces1";
		$files = [];
		if (is_dir(getcwd() . $path)) {
			$files = array_slice(scandir(getcwd() . $path), 2);
		}

		return $files;
	}

	protected function saveVideos($fgAsigl0, UploadedFile ...$files)
	{
		$path = getcwd() . "/files/videos/$this->emp/$fgAsigl0->num_hces1/$fgAsigl0->lin_hces1";

		if (!is_dir(str_replace("\\", "/", $path))) {
			mkdir(str_replace("\\", "/", $path), 0775, true);
		}

		foreach ($files as $file) {

			$newfile = str_replace("\\", "/", $path . '/' . $file->getClientOriginalName());
			copy($file->getPathname(), $newfile);
		}
	}

	protected function basicFormCreateFgAsigl0(FgAsigl0 $fgAsigl0, $cod_sub)
	{
		$propietario = null;
		$withProvider = config('app.useProviders', 0);
		$withNft = config('app.useNft', 0);

		#cojemos por defecto el de la subasta
		if( $withProvider && empty($fgAsigl0->prop_hces1) ){
			$fxsub = FgSub::select("agrsub_sub")->where("COD_SUB", $cod_sub)->first();
			$fgAsigl0->prop_hces1 = $fxsub->agrsub_sub;
		}

		if (!empty($fgAsigl0->prop_hces1) && !$withProvider) {
			$propietario = FxCli::select('RSOC_CLI')->where('COD_CLI', $fgAsigl0->prop_hces1)->first();
		}
		elseif(!empty($fgAsigl0->prop_hces1) && $withProvider){

			$propietario = FxPro::select('NOM_PRO, MARGEN_PRO')->where('COD_PRO', $fgAsigl0->prop_hces1)->first();
			#cargamos la comisión del proveedor
			if(!empty($propietario)){
				$fgAsigl0->comphces_asigl0 = $propietario->margen_pro;
			}

		}

		//en las subastas presenciales la fecha del lote es indiferente
		$type = FgSub::where('cod_sub', $cod_sub)->value('tipo_sub');
		$datesRequired = (int)($type !== FgSub::TIPO_SUB_PRESENCIAL);

		$ownerForm = FormLib::Select2WithAjax('owner', 0, old('owner', $fgAsigl0->prop_hces1), (!empty($propietario)) ? $propietario->rsoc_cli : '', route('client.list'), trans('admin-app.placeholder.owner'));
		if($withProvider){
			$ownerForm = FormLib::Select2WithAjax('owner', 0, old('owner', $fgAsigl0->prop_hces1), (!empty($propietario)) ? $propietario->nom_pro : '', route('provider.list'), trans('admin-app.placeholder.owner'));
		}

		$basicForm =
		[
			'hiddens' => [
				'idauction' => FormLib::Hidden('idauction', 1, $cod_sub),
			],
			'id' => [
				'reflot' => FormLib::TextReadOnly('reflot', 1, old('reflot', $fgAsigl0->ref_asigl0), 'maxlength="999999"'),
				'idorigin' => FormLib::TextReadOnly('idorigin', 1, old('idorigin', $fgAsigl0->idorigen_asigl0 ?? "$cod_sub-$fgAsigl0->ref_asigl0"), 'maxlength="30"'),
			],
			'imagen' => [
				'image' => FormLib::File('images[]', 0, 'multiple="true" accept=".jpg, .jpeg, .png"'),
			],
			'info' => [
				'owner' => $ownerForm,
				'idsubcategory' => FormLib::select("idsubcategory", 1, $fgAsigl0->sec_hces1, FxSec::GetActiveFxSec()),
				"warehouse" => '',
				'title' => FormLib::Text('title', 1, old('title', strip_tags($fgAsigl0->descweb_hces1))),
				'order' => FormLib::Text('order', 0, old('order', $fgAsigl0->orden_hces1)),
				'description' => FormLib::TextAreaTiny('description', 0, old('description', $fgAsigl0->desc_hces1)),
				'extrainfo' => FormLib::TextAreaTiny('extrainfo', 0, old('extrainfo', $fgAsigl0->descdet_hces1)),
				'search' => FormLib::Textarea('search', 0, $fgAsigl0->search_hces1),
			],
			'estados' => [
				'highlight' => FormLib::Select('highlight', 1, old('highlight', $fgAsigl0->destacado_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				'retired' => FormLib::Select('retired', 1, old('retired', $fgAsigl0->retirado_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				'close' => FormLib::Select('close', 1, old('close', $fgAsigl0->cerrado_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				'soldprice' => FormLib::Select('soldprice', 1, old('soldprice', $fgAsigl0->remate_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				'buyoption' => FormLib::Select('buyoption', 1, old('buyoption', $fgAsigl0->compra_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				'hidden' => FormLib::Select('hidden', 1, old('hidden', $fgAsigl0->oculto_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				'disclaimed' => FormLib::Select('disclaimed', 1, old('disclaimed', $fgAsigl0->desadju_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
			],
			'fechas' => [
				'startdate' => FormLib::Date("startdate", $datesRequired, old('startdate', $fgAsigl0->fini_asigl0)),
				'starthour' => FormLib::Hour("starthour", $datesRequired, old('starthour', $fgAsigl0->hini_asigl0), 'step="1"'),
				'enddate' => FormLib::Date("enddate", $datesRequired, old('enddate', $fgAsigl0->ffin_asigl0)),
				'endhour' => FormLib::Hour("endhour", $datesRequired, old('endhour', $fgAsigl0->hfin_asigl0), 'step="1"')
			],
			'precios' => [
				'startprice' => FormLib::Int('startprice', 1, old('startprice', $fgAsigl0->impsalhces_asigl0 ?? 0)),
				'costprice' => FormLib::Int('costprice', 1, old('costprice', $fgAsigl0->pc_hces1 ?? 0)),
				'lowprice' => FormLib::Int('lowprice', 0, old('lowprice', $fgAsigl0->imptas_asigl0 ?? 0)),
				'highprice' => FormLib::Int('highprice', 0, old('highprice', $fgAsigl0->imptash_asigl0 ?? 0)),
				'reserveprice' => FormLib::Int('reserveprice', 0, old('reserveprice', $fgAsigl0->impres_asigl0 ?? 0)),
				'biddercommission' => FormLib::Int('biddercommission', 0, old('biddercommission', $fgAsigl0->comlhces_asigl0 ?? 0)),
				'ownercommission' => FormLib::Int('ownercommission', 0, old('ownercommission', $fgAsigl0->comphces_asigl0 ?? 0))
			],
			'otros' => [
				'width' => FormLib::Int('width', 0, old('width', $fgAsigl0->ancho_hces1 ?? 0)),
				'numberobjects' => FormLib::Int('numberobjects', 0, old('numberobjects', $fgAsigl0->nobj_hces1 ?? 0)),
			],
			'iframe' => [
				'htmlcontent' => FormLib::TextArea('htmlcontent', 0, old('htmlcontent', $fgAsigl0->contextra_hces1))
			],
			'submit' => FormLib::Submit('Guardar', 'loteStore')
		];

		if(config("app.adminHideDescription")){
			unset($basicForm["info"]["description"]);
			$basicForm["hiddens"]["description"] =  FormLib::Hidden('description', 1, "_");
		}

		$almacenes = FxAlm::select('des_alm', 'cod_alm')->where('baja_tmp_alm', 'N')->pluck('des_alm', 'cod_alm')->toArray();

		if(empty($almacenes)){
			unset($basicForm["info"]["warehouse"]);
		}
		else{
			$basicForm["info"]["warehouse"] = FormLib::Select('warehouse', 0, old('warehouse', $fgAsigl0->alm_hces1 ?? null), $almacenes, '', '', true);
		}

		if($withNft) {

			$basicForm['estados'] =  ['es_nft_asigl0' => FormLib::Select('es_nft_asigl0', 0, old('es_nft_asigl0', $fgAsigl0->es_nft_asigl0), ['S' => 'Si', 'N' => 'No'], '', '', false)] + $basicForm['estados'];

			//Si ya esta publicado y es nft, no se puede cambiar el estado
			if($fgAsigl0->es_nft_asigl0 == 'S'){
				$nftInfo = FgNft::where([['numhces_nft', $fgAsigl0->num_hces1],['linhces_nft', $fgAsigl0->lin_hces1]])->first() ?? new FgNft();
				if($nftInfo->hashfile_nft){
					$basicForm['estados']['es_nft_asigl0'] = FormLib::Readonly('es_nft_asigl0', 0, 'Publicado');
				}
			}
		}

		return $basicForm;
	}

	protected function addTranslationsForm($formulario, $lotTranslates = null)
	{
		$languages = array_keys(config('app.locales'));

		$formulario->translates = [];

		foreach ($languages as $lang) {

			if ($lang == 'es') {
				continue;
			}

			$language_complete = config("app.language_complete.$lang");

			if ($lotTranslates) {
				$lotTranslate = $lotTranslates->where('lang_hces1_lang', $language_complete)->first();
			} else {
				$lotTranslates = new FgHces1_Lang();
			}

			$formulario->translates[$lang] = [
				'title' => FormLib::Text('title_lang[]', 0, old('title_lang[]', strip_tags($lotTranslate->descweb_hces1_lang ?? ''))),
				'description' => FormLib::TextAreaTiny('description_lang[]', 0, old('description_lang[]', $lotTranslate->desc_hces1_lang ?? ''), '', '', 300, true),
				'search' => FormLib::Textarea('search_lang[]', 0, $lotTranslate->search_hces1_lang ?? ''),
			];
		}

		return $formulario;
	}

	protected function addExtrasToForm($formulario, $fgAsigl0)
	{
		$almacenes = FxAlm::select('des_alm', 'cod_alm')->where('baja_tmp_alm', 'N')->pluck('des_alm', 'cod_alm');
		$labelOptions = is_array(trans("admin-app.labels")) ? trans("admin-app.labels") : [];

		$formulario->extras = [
			"idexternal" => FormLib::Text('idexternal', 0, old('idexternal', $fgAsigl0->ubi_hces1), 'maxlength="30"'),
			"warehouse" => FormLib::Select('warehouse', 0, old('warehouse', $fgAsigl0->alm_hces1 ?? null), $almacenes ?? [], '', '', true),
			'extrainfo' => FormLib::Textarea('extrainfo', 0, old('extrainfo', $fgAsigl0->descdet_hces1), '', '', '3'),
			"label" => FormLib::Select('label', 0, old('label', $fgAsigl0->oferta_asigl0 ?? null), $labelOptions ?? [], '', '', false),
			"withstock" => FormLib::Select('withstock', 0, old('withstock', $fgAsigl0->controlstock_hces1 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
			"stock" => FormLib::Int('stock', 0, old('stock', $fgAsigl0->stock_hces1 ?? 0), 'minlength="1"'),
			"weight" => FormLib::Int('weight', 0, old('weight', $fgAsigl0->peso_hces1 ?? 0), 'minlength="1"'),
			"high" => FormLib::Int('high', 0, old('high', $fgAsigl0->alto_hces1 ?? 0), 'minlength="1"'),
			"width" => FormLib::Int('width', 0, old('width', $fgAsigl0->ancho_hces1 ?? 0), 'minlength="1"'),
			"thickness" => FormLib::Int('thickness', 0, old('thickness', $fgAsigl0->grueso_hces1 ?? 0), 'minlength="1"'),
		];

		unset($formulario->info['extrainfo']);
		unset($formulario->info['warehouse']);

		return $formulario;
	}

	protected function addNftToForm($formulario, $fgAsigl0)
	{
		$nftInfo = FgNft::where([
			['numhces_nft', $fgAsigl0->num_hces1],
			['linhces_nft', $fgAsigl0->lin_hces1]
		])->first() ?? new FgNft();

		$nftNetworks = FgNftNetwork::pluck('name_nft_network', 'id_nft_network');

		$mediaTpes = [
			'png' => 'png',
			'jpg' => 'jpg',
			'gif' => 'gif',
			'svg' => 'svg',
			'mp4' => 'mp4',
			'webm' => 'webm',
			'mp3' => 'mp3',
			'ogg' => 'ogg',
			'glb' => 'glb',
			'gltf' => 'gltf',
		];

		//Para controlar el poder o no publicar el nft
		$formulario->publish_nft = $fgAsigl0->es_nft_asigl0 == 'S' && empty($nftInfo->hashfile_nft);
		$formulario->unpublish_nft = $fgAsigl0->es_nft_asigl0 == 'S' && $nftInfo->hashfile_nft && !$nftInfo->mint_id_nft;

		if(!empty($nftInfo->hashfile_nft)) {
			$formulario->nft = [
				//'es_nft_asigl0' => FormLib::Readonly('es_nft_asigl0', 0, 'Publicado'),
				'name_nft' => FormLib::Readonly('name_nft', 0, $nftInfo->name_nft),
				'description_nft' => FormLib::Readonly('description_nft', 0, $nftInfo->description_nft),
				'created_nft' => FormLib::Readonly('created_nft', 0, $nftInfo->created_nft),
				'artista_nft' => FormLib::Readonly('artista_nft', 0, $nftInfo->artista_nft),
				'media_type_nft' => FormLib::Readonly('media_type_nft', 0, $nftInfo->media_type_nft),
				'network_nft' => FormLib::Readonly('network_nft', 0, !empty($nftNetworks[$nftInfo->network_nft]) ? $nftNetworks[$nftInfo->network_nft] : "" ),
				'total_tokens_nft' => FormLib::Readonly('total_tokens_nft', 0, $nftInfo->total_tokens_nft),
				'n_of_token_nft' => FormLib::Readonly('n_of_token_nft', 0, $nftInfo->n_of_token_nft),
				'IPFS_file' => FormLib::Link($nftInfo->hashfile_nft, $nftInfo->hashfile_nft),
				'IPFS_metadata' => FormLib::Link($nftInfo->hashmetadata_nft, $nftInfo->hashmetadata_nft),
			];
			return $formulario;
		}

		$formulario->nft = [
			//'es_nft_asigl0' => FormLib::Select('es_nft_asigl0', 0, old('es_nft_asigl0', $fgAsigl0->es_nft_asigl0), ['N' => 'No', 'S' => 'Si'], '', '', false),
			'name_nft' => FormLib::Text('name_nft', 1, old('name_nft', $nftInfo->name_nft), 'maxlength="255"'),
			'description_nft' => FormLib::Textarea('description_nft', 0, old('description_nft', $nftInfo->description_nft), 'maxlength="4000"', '', '3'),
			'created_nft' => FormLib::Date("created_nft", 0, old('created_nft', $nftInfo->created_nft)),
			'artista_nft' => FormLib::Text('artista_nft', 0, old('artista_nft', $nftInfo->artista_nft), 'maxlength="255"'),
			'file_nft' => FormLib::FileWithValue('file_nft', 0, '', route('nft.show.file', ['numhces' => $nftInfo->numhces_nft, 'linhces' => $nftInfo->linhces_nft])),
			'media_type_nft' => FormLib::Select('media_type_nft', 0, old('media_type_nft', $nftInfo->media_type_nft), $mediaTpes),
			'network_nft' => FormLib::Select('network_nft', 1, old('network_nft', $nftInfo->network_nft), $nftNetworks),
			'total_tokens_nft' => FormLib::Int('total_tokens_nft', 0, old('total_tokens_nft', $nftInfo->total_tokens_nft)),
			'n_of_token_nft' => FormLib::Int('n_of_token_nft', 0, old('n_of_token_nft', $nftInfo->n_of_token_nft)),
		];

		return $formulario;
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

	function getSelect2List($cod_sub)
	{
		$query =  mb_strtoupper(request('q'));

		if (!empty($query)) {

			$where = [
				['upper(REF_HCES1)', 'LIKE', "%$query%", 'or'],
				['upper(TITULO_HCES1)', 'LIKE', "%$query%", 'or'],
				['upper(DESCWEB_HCES1)', 'LIKE', "%$query%", 'or']
			];

			$fgAsigl0 = FgAsigl0::JoinFghces1Asigl0()->select('REF_HCES1 as id', 'nvl(TITULO_HCES1, DESCWEB_HCES1) as html')->where('sub_asigl0', $cod_sub)->where($where)->orderby('REF_HCES1')->get();

			return response()->json($fgAsigl0);
		}

		return response();
	}

	#Recibe una descripción de carcateristica,devuelve su ID, la crea si no existe
	function addFeature()
	{
		if (!empty(request("newValue"))) {
			return FgCaracteristicas_Value::addFeature(request("idFeature"), request("newValue"));
		}
		return [];
	}

	function createOrEditMultilanguageFeature($idFeature, $idcar_caracteristicas_value)
	{
		$feature = FgCaracteristicas::where('id_caracteristicas', $idFeature)->first();

		if (!$feature) {
			return response('error', 404);
		}

		if(!empty($idcar_caracteristicas_value)){
			$featureValue = FgCaracteristicas_Value::where('id_caracteristicas_value', $idcar_caracteristicas_value)->first();
			$featureValueLangs = FgCaracteristicas_Value_Lang::where('idcarval_car_val_lang', $idcar_caracteristicas_value)->get();
		}
		else{
			$featureValue = new FgCaracteristicas_Value();
			$featureValueLangs = [];
		}

		$modal = view('admin::pages.subasta.lotes._modal_features', compact('feature', 'featureValue', 'featureValueLangs'))->render();
		return response($modal);
	}

	function storeMultilanguageFeature(Request $request)
	{
		if (empty($request->feature_input)) {
			return abort(404);
		}

		//Con el valor de la caracteristica, obtenemos Id, y lo crea si no existe
		$idcar_caracteristicas_value = FgCaracteristicas_Value::addFeature($request->id_caracteristica, $request->feature_input);
		//Recuperamos todas las traducciones de este id
		$featuresValueLang = FgCaracteristicas_Value_Lang::where('idcarval_car_val_lang', $idcar_caracteristicas_value['idFeatureValue'])->get();

		$langs = $request->feature_lang_lang;

		$newFeaturesLangs = [];
		$updateFeaturesLangs = [];

		foreach ($request->feature_lang_value as $key => $value) {

			//Con las traducciones ya cargadas, buscamos en la traduccion concreta
			$featureLang = $featuresValueLang->where('lang_car_val_lang', config("app.language_complete.$langs[$key]"))->first();

			//Si no tenemos valor, no añadimos nada
			//@todo valorar si borrar ¿?
			if (empty($value)) {
				continue;
			}

			//Los diferenciamos en si son nuevos o no, para cargarlos en un metodo de api u otro
			if (empty($featureLang)) {
				$newFeaturesLangs[] = [
					'idfeaturevalue' => $idcar_caracteristicas_value['idFeatureValue'],
					'value' => $value,
					'lang' => $langs[$key]
				];
			} else {
				$updateFeaturesLangs[] = [
					'idfeaturevalue' => $idcar_caracteristicas_value['idFeatureValue'],
					'value' => $value,
					'lang' => $langs[$key]
				];
			}
		}

		//Guardamos y/o editamos las traducciones
		//@todo En caso de error en api??
		$apiError = false;
		if (!empty($newFeaturesLangs)) {
			$json = (new FeatureValueController())->createFeatureValue($newFeaturesLangs);
			$result = json_decode($json);
			if ($result->status == 'ERROR') {
				$apiError = true;
			}
		}
		if (!empty($updateFeaturesLangs)) {
			$json = (new FeatureValueController())->updateFeatureValue($updateFeaturesLangs);
			$result = json_decode($json);
			if ($result->status == 'ERROR') {
				$apiError = true;
			}
		}

		//Devolvemos array con id de caracteristicaValue y si en nueva no
		return response($idcar_caracteristicas_value);
	}

	function assignOwner($lot,$cod_sub, $ref_asigl0){
		if(!empty($lot["owner"]) && config("app.owner100x100")){
			$numLin = FgAsigl0::select("numhces_asigl0,linhces_asigl0")->where("SUB_ASIGL0", $cod_sub)->where("REF_ASIGL0",$ref_asigl0 )->first();
			$hcesmt = FgHcesmt::select("count(*) as num")->where("NUM_HCESMT", $numLin->numhces_asigl0)->where("LIN_HCESMT",$numLin->linhces_asigl0)->first();
			#siempre hay que crear o updatar por que pueden haber cambiado el usuario propietario
			$nuevoProp= array(
				"emp_hcesmt"=> config("app.emp"),
				"num_hcesmt" =>  $numLin->numhces_asigl0,
				"lin_hcesmt" => $numLin->linhces_asigl0,
				"ratio_hcesmt" =>100,
				"cli_hcesmt" => $lot["owner"]
			);
			if($hcesmt->num ==0){
				FgHcesmt::create($nuevoProp);
			}else{
				FgHcesmt::where("NUM_HCESMT", $numLin->numhces_asigl0)->where("LIN_HCESMT",$numLin->linhces_asigl0)->update($nuevoProp);
			}
		}
	}

	public function pdfExhibition($codSub, $reference='001'){
		$pdfController = new PdfController( );
		return $pdfController->pdfExhibition($codSub,$reference);

	}

	public function excelExhibition($codSub, $reference='001'){
		$excelController = new CustomControllers();
		return $excelController->excelExhibition($codSub,$reference);
	}

	public function getOrderDestacada()
	{
		$emp = config('app.emp');

		$lots = FgAsigl0::select('sub_asigl0', 'ref_asigl0', 'descweb_hces1', 'orden_destacado_asigl0')
		->joinFghces1Asigl0()->where('destacado_asigl0', 'S')->where('emp_asigl0', $emp)
		->orderby('orden_destacado_asigl0')->orderby('sub_asigl0')->orderby('ref_asigl0')->get();

		$parent_name = $this->parent_name;

		return view('admin::pages.subasta.lotes.order_destacadas', compact('lots', 'parent_name'));

	}

	public function saveOrderDestacada(Request $request)
	{

		$emp = config('app.emp');


		$lots = FgAsigl0::select('sub_asigl0','ref_asigl0' ,'orden_destacado_asigl0')
		->where('destacado_asigl0', 'S')->where('emp_asigl0', $emp)->get();
		$order = collect($request->ref)->flip();


		foreach ($lots as $lot) {

			$lot->orden_destacado_asigl0 = $order[$lot->sub_asigl0.'-'.$lot->ref_asigl0] + 1;

			//Comprueba si el modelo a sido modificado para no lanzar más actualizaciones de las necesarias
			if($lot->isDirty()){
				FgAsigl0::where([
					['sub_asigl0', $lot->sub_asigl0],
					['ref_asigl0', $lot->ref_asigl0]
				])->update([
					'orden_destacado_asigl0' => $lot->orden_destacado_asigl0
				]);
			 }
		}

		return back()->with(['success' => array(trans('admin-app.title.updated_ok'))]);
	}

	public function listadoImagenesSubasta($cod_sub){

		$lotes = FgAsigl0::select("num_hces1,lin_hces1, ref_asigl0, totalfotos_hces1")->JoinFghces1Asigl0()
		->JoinSubastaAsigl0()->JoinSessionAsigl0()->where("sub_asigl0",$cod_sub)->orderby("ref_asigl0")->get();
		$subasta = new \App\Models\Subasta();
		foreach($lotes as $key => $lote){
			$lotes[$key]->images = $subasta->getLoteImages($lote);
			echo "<div style='float:left;text-align:center; margin:10px'> Lote: ".$lote->ref_asigl0 ."<br>";
			if($lotes[$key]->totalfotos_hces1 > 0) {
				foreach($lotes[$key]->images as $keyImage => $image){
					echo '<img src="'. ToolsServiceProvider::url_img("lote_small", $lote->num_hces1, $lote->lin_hces1, $keyImage) . '" height="100px" >';
				}
			}
			echo "</div>";

		}
	}

	public function cloneLot(Request $request)
	{

		#Hace las querys para obtener los datos del lote origen
		$oldLot = FgAsigl0::select()->where("sub_asigl0", $request->auctionSource)->where("ref_asigl0", $request->lotToDuplicate)->first();
		$lotHces1 = FgHces1::select()->where("sub_hces1", $request->auctionSource)->where("ref_hces1", $request->lotToDuplicate)->first();
		$newRefAsigl0 = FgAsigl0::where('sub_asigl0', $request->newAuction)->max("ref_asigl0") + 1;

		#Clona los datos del lote
		$newLot = clone $oldLot;

		# Modifica los datos del lote nuevo
		# Asigl0
		$newLot->sub_asigl0 = $request->newAuction;
		$newLot->idorigen_asigl0 = $request->newAuction."-".$newRefAsigl0;
		$newLot->ref_asigl0 = "$newRefAsigl0";
		$newLot->fini_asigl0 = null;
		$newLot->ffin_asigl0 = null;
		$newLot->oculto_asigl0 = "S";
		# Hces1
		$lotHces1->sub_hces1 = $request->newAuction;
		$lotHces1->idorigen_hces1 = $request->newAuction."-".$newRefAsigl0;
		$lotHces1->ref_hces1 = "$newRefAsigl0";
		$lotHces1->implic_hces1 = "0";
		$lotHces1->lic_hces1 = "N";

		# Modifica los datos del lote origen
		$oldLot->cerrado_asigl0 = "S";
		$oldLot->oculto_asigl0 = "S";

		# Pasa los datos a Array para poder hacer insert y update
		$oldLotArray = $oldLot->toArray();
		$newLotArray = $newLot->toArray();
		$lotHces1Array = $lotHces1->toArray();

		# Actualiza los datos del lote origen
		FgAsigl0::where("sub_asigl0", $request->auctionSource)->where("ref_asigl0", $request->lotToDuplicate)->update($oldLotArray);
		FgHces1::where("num_hces1", $lotHces1->num_hces1)->where("lin_hces1", $lotHces1->lin_hces1)->update($lotHces1Array);

		# Insertar el nuevo lote en base de datos
		FgAsigl0::insert($newLotArray);

		# Redirección a la ventana de edición del lote
		return redirect(route('subastas.lotes.edit', ['subasta' => $request->newAuction, 'lote' => $newRefAsigl0]))->with(['warning' => array(trans('admin-app.title.warning_cloned_lot'))]);
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

	public function deleteSelection(Request $request, $cod_sub)
	{

		$lots = FgAsigl0::select('idorigen_asigl0, ref_asigl0', 'sub_asigl0', 'numhces_asigl0 ', 'linhces_asigl0')
			->where('sub_asigl0', $cod_sub)
			->whereIn('ref_asigl0', $request->lots)
			->get();
			//->pluck('idorigen_asigl0');

		if($lots->isEmpty()) {
			return response()->json(['error' => 'No se encontraron lotes para eliminar'], 400);
		}

		//obtenemos el id origen de todos los lotes, y si no lo tiene, lo creamos
		$idToDelete = $lots->map(function($lot) {
			if(!$lot->idorigen_asigl0){
				return $this->addIdOrigin($lot->sub_asigl0, $lot->ref_asigl0, $lot->numhces_asigl0, $lot->linhces_asigl0);
			}
			return $lot->idorigen_asigl0;
		});

		$apiLotController = new LotController();

		//eliminamos los lotes
		$idToDelete->map(function($id) use ($apiLotController) {
			$apiLotController->eraseLot(['idorigin' => $id]);
		});

		return response()->json(['success' => true], 200);
	}

}
