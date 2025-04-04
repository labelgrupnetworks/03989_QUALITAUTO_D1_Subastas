<?php

namespace App\Http\Controllers\admin\subasta;

use App\Exports\custom\CustomExport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\StoreSubastasPost;
use App\Http\Requests\admin\UpdateSubastasPut;
use DateTime;
use Intervention\Image\Facades\Image;
use App\libs\FormLib;
use App\Providers\ToolsServiceProvider;
use App\Models\V5\FgSub;
use App\Models\V5\AucSessions;
use App\Models\V5\AucSessionsFiles;
use App\Models\V5\FgCaracteristicas_Value;
use App\Models\V5\FgLicit;
use App\Models\V5\FgPujasSub;
use App\Models\V5\FgSub_lang;
use App\Models\V5\FxPro;
use App\Models\V5\Web_Artist;
use App\Http\Controllers\externalAggregator\Invaluable\House;
use App\Models\V5\SubAuchouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

class AdminSubastaGenericController extends Controller
{
	protected $isGeneric;
	protected $resource_name;

	private $userSession;

	public function __construct()
	{
        $this->isGeneric = true;
		$this->resource_name = 'subastas';
		view()->share(['menu' => 'subastas']);
    }

    public function index(Request $request)
    {
		$defalutState = Config('app.admin_default_auction_state', null);
		$artists = [];

		$fgSubs = FgSub::query();
		$fgSubs = $this->fgsubQueryFilters($fgSubs, $request);

		$fgSubs = $fgSubs->select('COD_SUB', 'DES_SUB', 'SUBC_SUB', 'TIPO_SUB', 'DFEC_SUB', 'DHORA_SUB', 'HFEC_SUB', 'HHORA_SUB');


		if(config("app.ArtistInExibition", false)) {

			$fgSubs->leftJoin('WEB_ARTIST', 'EMP_ARTIST = EMP_SUB AND ID_ARTIST = VALORCOL_SUB')
			->when($request->artist_name, function($query, $artist_name){
				$query->where('upper(name_artist)', 'like', "%" . mb_strtoupper($artist_name) . "%");
			})
			->addSelect('VALORCOL_SUB');
		}

		$fgSubs = $fgSubs->orderBy(request('order', 'dfec_sub'), request('order_dir', 'desc'))
		->paginate(30);

		$fgSub = new FgSub();

		$formulario = (object)[
			'cod_sub' => FormLib::Text('cod_sub', 0, $request->cod_sub),
			'des_sub' => FormLib::Text('des_sub', 0, $request->des_sub),
			'subc_sub' => FormLib::Select('subc_sub', 0, $request->subc_sub ?? $defalutState, $fgSub->getSubcSubTypes()),
			'tipo_sub' => FormLib::Select('tipo_sub', 0, $request->tipo_sub, $fgSub->getTipoSubTypes()),
			'dfec_sub' => FormLib::Date('dfec_sub', 0, $request->dfec_sub),
			'hfec_sub' => FormLib::Date('hfec_sub', 0, $request->hfec_sub),
		];

		if(config("app.ArtistInExibition")){
			$formulario->artist_name = FormLib::Text('artist_name', 0, $request->artist_name);
			$artists = Web_Artist::pluck('name_artist', 'id_artist')->toArray();
		}

		$auchouse = SubAuchouse::getAuchouse();

		$resource_name = $this->resource_name;

		$exports = (new CustomExport)->getExportsNames();

		$dataToView = [
			'fgSubs' => $fgSubs,
			'formulario' => $formulario,
			'resource_name' => $resource_name,
			'exports' => $exports,
			'artists' => $artists,
			'auchouse' => $auchouse
		];

		return view('admin::pages.subasta.subastas.index', $dataToView);
    }

    public function create(): View
    {
		$fgSub = new FgSub();
		$formulario = (object) $this->basicFormCreateFgSub($fgSub);
		$this->addOrders($formulario, $fgSub);
		return view('admin::pages.subasta.subastas.create', compact('formulario', 'fgSub'));
    }

	public function store(StoreSubastasPost $request)
	{

		try {
			DB::beginTransaction();

			$fgSub = FgSub::create($request->except(['imagen_sub', 'force_overwritte']));

			$id_auc_session = AucSessions::withoutGlobalScopes()->max('"id_auc_sessions"') + 1;

			$auc_session_attributes = [
				'"auction"' => $fgSub->cod_sub,
				'"id_auc_sessions"' => $id_auc_session,
				'"reference"' => '001',
				'"start"' => new DateTime($request->dfec_sub . ' ' . $request->dhora_sub),
				'"end"' => new DateTime($request->hfec_sub . ' ' . $request->hhora_sub),
				'"init_lot"' => 1,
				'"end_lot"' => 99999,
				'"name"' => $fgSub->des_sub,
				'"description"' => mb_substr($fgSub->descdet_sub, 0, 1000,'UTF-8')
			];

			if($request->tipo_sub == FgSub::TIPO_SUB_PRESENCIAL){

				$auc_session_attributes['"orders_start"'] = new DateTime($request->dfecorlic_sub . ' ' . $request->dhoraorlic_sub);
				$auc_session_attributes['"orders_end"'] = new DateTime($request->hfecorlic_sub . ' ' . $request->hhoraorlic_sub);
			}

			AucSessions::create($auc_session_attributes);

			if($request->has('imagen_sub')){
				$image = $request->file('imagen_sub');
				$this->saveFgSubImage($image, $fgSub->cod_sub, true);
			}


			DB::commit();

			return redirect(route("$this->resource_name.index"))->with(['success' => array(trans('admin-app.title.updated_ok'))]);
		} catch (\Throwable $th) {
			DB::rollBack();
			return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
		}
	}

    public function show(Request $request, $cod_sub)
    {
        $fgSub = FgSub::where('cod_sub', $cod_sub)->first();

		if (!$fgSub) {
			abort(404);
		}

		//lotes
		$viewFgAsigl0 = ($this->isGeneric) ?  new AdminLotController(true) : new AdminLoteConcursalController(true);
		$viewFgAsigl0 = $viewFgAsigl0->index($request, $cod_sub);

		/**
		 * PUJAS
		 * Para poder realizar una union con las pujas inferiores, las columnas de esta primera deben tener el mismo nombre
		 * Y para poder ordenar, se debe hacer por el numero de columna y no por el nombre
		 */

		$adminPujasController = new AdminPujasController();
		$pujasTable = $adminPujasController->index($request, $cod_sub, $this->resource_name );


		$licitadores = FgLicit::where("SUB_LICIT", $cod_sub)
			->get()
			->keyBy('cod_licit');


		/**
		 * Tabla ordenes
		 * No aparecen en venta directa
		 */
		$ordersTable = '';
		if ($fgSub->tipo_sub != FgSub::TIPO_SUB_VENTA_DIRECTA) {
			$adminOrderController = new AdminOrderController(true);
			$ordersTable = $adminOrderController->index($request, $cod_sub);
		}

		/**Tabla adjudicaciones */
		$adminAwardController = new AdminAwardController();
		$awardsTable = $adminAwardController->index($request, true, $cod_sub, $fgSub->tipo_sub);

		/**Tabla de no adjudicados */
		$adminNotAwardController = new AdminNotAwardController();
		$notAwardsTable = $adminNotAwardController->index($request, true, $cod_sub);

		//ganadores licitadores
		//esta por revisar y mejorar
		$adminWinnerController = new AdminWinnerController();
		$winnersTable = $adminWinnerController->index($request, $cod_sub, $this->resource_name);

		$resource_name = $this->resource_name;

		return view("admin::pages.subasta.subastas.show", compact('fgSub', 'viewFgAsigl0', 'pujasTable', 'ordersTable', 'awardsTable', 'winnersTable', 'licitadores', 'resource_name', 'notAwardsTable'));
    }

    public function edit($cod_sub)
    {

        $fgSub = FgSub::where('cod_sub', $cod_sub)->first();

		if (!$fgSub) {
			return redirect()->back()->withErrors('not exist')
				->withInput();
		}

		//sessiones
		//$aucSessions = AucSessions::select('"id_auc_sessions"', '"reference"', '"name"', '"init_lot"', '"end_lot"')->where('"auction"', $cod_sub)->get();

		$adminAucSessionsController = new AdminAucSessionsController(false);
		$aucSessions = $adminAucSessionsController->index($fgSub->cod_sub);

		$formulario = (object) $this->basicFormCreateFgSub($fgSub);
		$formulario->textos['cod_sub'] = FormLib::TextReadOnly('cod_sub', 0, $fgSub->cod_sub);
		$formulario->submit = FormLib::Submit('Actualizar', 'subastaUpdate');

		//agregar campos formulario opciones
		$this->addOrders($formulario, $fgSub);

		//formulario de idiomas
		$fgSub_lang = FgSub_lang::select('lang_sub_lang', 'des_sub_lang', 'descdet_sub_lang', 'webmetat_sub_lang', 'webmetad_sub_lang', 'webfriend_sub_lang')->where('COD_SUB_LANG', $cod_sub)->get();
		$this->addFgSub_lanfForm($formulario, $fgSub_lang);

		//formulario escalados
		$fgPujasSubs = FgPujasSub::where('SUB_PUJASSUB', $cod_sub)->orderBy('LIN_PUJASSUB')->get();
		$this->addFgPujasSubForm($formulario, $fgPujasSubs);

		//Archivos de sesiones
		$aucSessionsFilesController = new AdminAucSessionsFilesController();
		$aucSessionsFilesController->addAucSessionsFilesForm($formulario, $aucSessions, $cod_sub);
		$aucSessionsFiles = AucSessionsFiles::where('"auction"', $cod_sub)->get();

		return view("admin::pages.subasta.subastas.edit", compact('fgSub', 'aucSessions', 'formulario', 'aucSessionsFiles'));
    }

	public function update(UpdateSubastasPut $request, $cod_sub)
	{

		$fgSub = FgSub::where('cod_sub', $cod_sub)->first();
		if (!$fgSub) {
			return redirect()->back()->withErrors('not exist')
				->withInput();
		}

		//recuperar parametros del request
		$var_req = $request->all();
		try {

			$update_array= array();
			DB::beginTransaction();

			foreach ($var_req as $key => $val){
				if (preg_match('/_sub$/',$key)){
					$update_array[$key]=$val;
				}
			}

			$update_array = $this->addUserUpdatedAucFields($update_array);

			FgSub::where('cod_sub', $cod_sub)->update(
				$update_array
			);

			if ($request->upload_first_session) {
				self::updateFirstSessions($request, [$cod_sub]);
			}

			//Actualizar o crear idiomas
			$languages = array_diff(Config::get('app.locales'), ['es' => 'Español']);
			if (!empty($languages)) {
				$this->createOrSaveFgSub_lang($request, $cod_sub, $languages);
			}

			//archivos de sesion
			if (request()->hasFile('ficheroAdjunto') || request('typefile') == AucSessionsFiles::TYPE_ENLACE) {
				$adminAucSessionsFilesController = new AdminAucSessionsFilesController();
				$adminAucSessionsFilesController->store($request);
			}

			$this->createFgPujasSub($request, $cod_sub);

			DB::commit();

			return redirect(route('subastas.edit', $cod_sub))->with(['success' => array(trans('admin-app.title.updated_ok'))]);
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::error($th);
			return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
		}
	}

    public function destroy($id)
    {
       abort(404);
	}

	public function updateImage(Request $request)
	{
		$fgSub = FgSub::where('COD_SUB', $request->cod_sub)->first();

		if (!$fgSub) {
			return redirect()
				->back()
				->withErrors('not exist')
				->withInput();
		}

		return $this->saveFgSubImage($request->file('imagen_sub'), $request->cod_sub, true, $request->force_overwritte);
	}

	public function saveFgSubImage(UploadedFile $image, string $cod_sub, bool $storeInAuction, bool $forceOverwritte = false){

		//$input['imagename'] = time().'.'.$image->extension();
		$emp = Config::get('app.emp', '001');

		$destinationPath = public_path("img/AUCTION_$emp" . "_$cod_sub.JPEG");

		$img = Image::make($image->path());
		clearstatcache();

		if($img->width() > 2000){
			$img->resize(2000, null, function ($constraint) {
				$constraint->aspectRatio();
			});
		}

		$img->save($destinationPath);

		//Si no existe imagen de sesion la guardamos. valorar implementar que pregunte si queremos o no machacar
		if($storeInAuction && !file_exists(str_replace("\\", "/", getcwd() . "/img/SESSION_$emp" . "_$cod_sub" . "_001.JPEG")) || $forceOverwritte){

			$destinationPath = public_path("img/SESSION_$emp" . "_$cod_sub" . "_001.JPEG");
			$img->save($destinationPath);

		}

		return ToolsServiceProvider::url_img_auction('subasta_large', $cod_sub);
	}

	protected function basicFormCreateFgSub(FgSub $fgSub)
	{


		$form= [
			'imagen' => ['imagen_sub' => FormLib::File("imagen_sub", 0)],
			'textos' => [
				'cod_sub' => FormLib::Text('cod_sub', 1, old('cod_sub', $fgSub->cod_sub), 'maxlength="8"'),
				'des_sub' => FormLib::Text('des_sub', 0, old('des_sub', $fgSub->des_sub ?? '&nbsp'), 'maxlength="255"'),
				#he quitado el max lenght 100 por que ansorena escribe textos de más de dosmill caracteres
				'descdet_sub' => FormLib::TextAreaTiny('descdet_sub', 0, old('descdet_sub', $fgSub->descdet_sub))
			],
			'estados' => [
				'tipo_sub' => FormLib::Select('tipo_sub', 1, old('tipo_sub', $fgSub->tipo_sub), $fgSub->getTipoSubTypes(), '', '', false),
				'subc_sub' => FormLib::Select('subc_sub', 1, old('subc_sub', $fgSub->subc_sub), $fgSub->getSubcSubTypes(), '', '', false),
				'subabierta_sub' => FormLib::Select('subabierta_sub', 1, old('subabierta_sub', $fgSub->subabierta_sub), $fgSub->getSubAbiertaTypes(), '', '', false),
				'opcioncar_sub' => FormLib::Select('opcioncar_sub', 1, old('opcioncar_sub', $fgSub->opcioncar_sub ?? 'N'), ['N' => trans('admin-app.general.not'),'S' => trans('admin-app.general.yes')], '', '', false),
			],
			'fechas' => [
				'dfec_sub' => FormLib::Date("dfec_sub", 1, old('dfec_sub', $fgSub->dfec_sub)),
				'dhora_sub' => FormLib::Hour("dhora_sub", 1, old('dhora_sub', $fgSub->dhora_sub)??'00:00'),
				'hfec_sub' => FormLib::Date("hfec_sub", 1, old('hfec_sub', $fgSub->hfec_sub)),
				'hhora_sub' => FormLib::Hour("hhora_sub", 1, old('hhora_sub', $fgSub->hhora_sub)??'23:')
			],
			'seo' => [
				'webmetat_sub' => FormLib::Text('webmetat_sub', 0, old('webmetat_sub', $fgSub->webmetat_sub)),
				'webmetad_sub' => FormLib::Textarea('webmetad_sub', 0, old('webmetad_sub', $fgSub->webmetad_sub)),
				'webfriend_sub' => FormLib::Text('webfriend_sub', 0, old('webfriend_sub', $fgSub->webfriend_sub))
			],
			'submit' => FormLib::Submit('Guardar', 'subastaStore')
		];

		//valoralia necesitaba un campo booleano, y este no lo utiliza nadie.
		if(Config::get('app.use_panel_sub')) {
			$form['estados']['panel_sub'] = FormLib::Select('panel_sub', 1, old('panel_sub', $fgSub->panel_sub ?? 'N'), ['N' => trans('admin-app.general.not'), 'S' => trans('admin-app.general.yes')], '', '', false);
		}

		#en subasta guardamos propietario para poderlo cargar luego en los lotes
		if(Config::get("app.useProviders")){

			$idProvider = "" ;
			$textProvider =  "" ;

			if(!empty($fgSub->agrsub_sub)){
				$fxpro = FxPro::select("COD_PRO, NOM_PRO")->where("COD_PRO",$fgSub->agrsub_sub )->first();
				$idProvider = $fxpro->cod_pro ;
				$textProvider =  $fxpro->nom_pro ;
			}

			$form['provider']['provider']  = FormLib::Select2WithAjax('agrsub_sub', 0, old('agrsub_sub', $idProvider ), $textProvider, route('provider.list'), trans('admin-app.placeholder.provider'));
		}

		if(Config::get("app.ArtistInExibition")){
			$artists = FgCaracteristicas_Value::where("IDCAR_CARACTERISTICAS_VALUE", \Config::get("app.ArtistCode"))->SelectInput();

			#USO EL CAMPO CCOS_SUB POR COGER UNO CQUE FUERA VARCHAR
			$form['provider']['autor'] = FormLib::Select('valorcol_sub', 0, old('valorcol_sub',$fgSub->valorcol_sub),  $artists , '','',true);
		}
		return $form;

	}

	protected function addOrders($formulario, $fgSub)
	{
		$option = ['N' => trans('admin-app.general.not'),'S' => trans('admin-app.general.yes')];

		$estados_extra = [
			'compraweb_sub' => FormLib::Select("compraweb_sub", 1, old('compraweb_sub', $fgSub->compraweb_sub ?? 'N'), $option, ''. '', false)
		];
		$data_extra = [
			'dfecorlic_sub' => FormLib::Date("dfecorlic_sub", 1, old('dfecorlic_sub', $fgSub->dfecorlic_sub)),
			'dhoraorlic_sub' => FormLib::Hour("dhoraorlic_sub", 1, old('dhoraorlic_sub', $fgSub->dhoraorlic_sub)),
			'hfecorlic_sub' => FormLib::Date("hfecorlic_sub", 1, old('hfecorlic_sub', $fgSub->hfecorlic_sub)),
			'hhoraorlic_sub' => FormLib::Hour("hhoraorlic_sub", 1, old('hhoraorlic_sub', $fgSub->hhoraorlic_sub)),
		];

		$formulario->estados += $estados_extra;
		$formulario->fechas += $data_extra;
	}

	protected function addFgSub_lanfForm($formulario, $fgSub_lang){

		$languages = array_diff(Config::get('app.locales'), ['es' => 'Español']);

		if (!empty($languages)) {

			$fgSub_langArray = $fgSub_lang->mapWithKeys(function ($item) {
				return [$item->lang_sub_lang => $item];
			});

			$formulario->traducciones = [];
			foreach ($languages as $keyLang => $lang) {

				$completeLang = ToolsServiceProvider::getLanguageComplete($keyLang);

				$formulario->traducciones[$keyLang] = [
					'des_sub_lang' => FormLib::Text("des_sub_lang[$keyLang]", 0, old("des_sub_lang")[$keyLang] ?? $fgSub_langArray[$completeLang]->des_sub_lang ?? '', 'maxlength="40"'),
					'descdet_sub_lang' => FormLib::TextAreaTiny("descdet_sub_lang[$keyLang]", 0, old("descdet_sub_lang")[$keyLang] ?? $fgSub_langArray[$completeLang]->descdet_sub_lang ?? ''),
					'webmetat_sub_lang' => FormLib::Text("webmetat_sub_lang[$keyLang]", 0, old("webmetat_sub_lang")[$keyLang] ?? $fgSub_langArray[$completeLang]->webmetat_sub_lang ?? ''),
					'webmetad_sub_lang' => FormLib::Textarea("webmetad_sub_lang[$keyLang]", 0, old("webmetad_sub_lang")[$keyLang] ?? $fgSub_langArray[$completeLang]->webmetad_sub_lang ?? ''),
					'webfriend_sub_lang' => FormLib::Text("webfriend_sub_lang[$keyLang]", 0, old("webfriend_sub_lang")[$keyLang] ?? $fgSub_langArray[$completeLang]->webfriend_sub_lang ?? '')
				];
			}
		}

	}

	protected function addFgPujasSubForm(object $formulario, Collection $fgPujasSubs)
	{
		$formulario->escalado = [];

		if($fgPujasSubs->isEmpty()){
			$formulario->escalado[] = [
				'imp_pujassub' => FormLib::Float("imp_pujassub[]", 0, 0),
				'puja_pujassub' => FormLib::Float("puja_pujassub[]", 0, 0)
			];
			return;
		}

		foreach ($fgPujasSubs as $fgPujasSub) {
			$formulario->escalado[] = [
				'imp_pujassub' => FormLib::Float("imp_pujassub[]", 0, $fgPujasSub->imp_pujassub),
				'puja_pujassub' => FormLib::Float("puja_pujassub[]", 0, $fgPujasSub->puja_pujassub)
			];
		}
	}

	public function createOrSaveFgSub_lang(Request $request, $cod_sub, $languages)
	{

		$fgSub_lang = FgSub_lang::where('COD_SUB_LANG', $cod_sub)->get();
		foreach ($languages as $keyLang => $lang) {

			$completeLang = ToolsServiceProvider::getLanguageComplete($keyLang);
			$requestForThisLang = ($request->des_sub_lang[$keyLang] || $request->descdet_sub_lang[$keyLang] || $request->webmetat_sub_lang[$keyLang] || $request->webmetad_sub_lang[$keyLang] || $request->webfriend_sub_lang[$keyLang]);

			if ($requestForThisLang) {

				if ($fgSub_lang->where('lang_sub_lang', $completeLang)->count()) {

					FgSub_lang::where([
						['COD_SUB_LANG', $cod_sub],
						['LANG_SUB_LANG', $completeLang],
					])->update([
						'des_sub_lang' => $request->des_sub_lang[$keyLang],
						'descdet_sub_lang' => $request->descdet_sub_lang[$keyLang],
						'webmetat_sub_lang' => $request->webmetat_sub_lang[$keyLang],
						'webmetad_sub_lang' => $request->webmetad_sub_lang[$keyLang],
						'webfriend_sub_lang' => $request->webfriend_sub_lang[$keyLang],
					]);
				} else {

					FgSub_lang::create([
						'cod_sub_lang' => $cod_sub,
						'lang_sub_lang' => $completeLang,
						'des_sub_lang' => $request->des_sub_lang[$keyLang],
						'descdet_sub_lang' => $request->descdet_sub_lang[$keyLang],
						'webmetat_sub_lang' => $request->webmetat_sub_lang[$keyLang],
						'webmetad_sub_lang' => $request->webmetad_sub_lang[$keyLang],
						'webfriend_sub_lang' => $request->webfriend_sub_lang[$keyLang],
					]);
				}
			}
		}
	}

	public function createFgPujasSub(Request $request, $cod_sub)
	{
		FgPujasSub::where("sub_pujassub", $cod_sub)->delete();

		$escalados = collect($request->imp_pujassub)->combine($request->puja_pujassub)->sortKeys();
		$index = 0;

		foreach ($escalados as $imp_pujassub => $puja_pujassub) {

			if (!empty($imp_pujassub) && !empty($puja_pujassub)) {
				FGPUJASSUB::create([
					"imp_pujassub" => $imp_pujassub,
					"puja_pujassub" => $puja_pujassub,
					"sub_pujassub" => $cod_sub,
					"lin_pujassub" => ++$index
				]);
			}
		}
	}

	public function loadInvaluableCatalog($codSub, $reference){


		$house = new House();
		$resJson = $house->catalogs( $codSub, $reference);
		$res = json_decode($resJson);
		return redirect(Route($this->resource_name.".edit",$codSub))->with(['success' => [$res->message]]);
	}

	#region filters

	private function fgsubQueryFilters($query, Request $request)
	{
		$defalutState = Config('app.admin_default_auction_state', null);

		if ($request->cod_sub) {
			$query->where('upper(cod_sub)', 'like', "%" . mb_strtoupper($request->cod_sub) . "%");
		}
		if ($request->des_sub) {
			$query->where('upper(des_sub)', 'like', "%" . mb_strtoupper($request->des_sub) . "%");
		}
		if ($request->subc_sub) {
			$query->where('subc_sub', '=', $request->subc_sub);
		}
		else if (is_null($request->subc_sub) && $defalutState) {
			$query->where('subc_sub', '=', $defalutState);
		}
		if ($request->tipo_sub) {
			$query->where('tipo_sub', '=', $request->tipo_sub);
		}
		if ($request->dfec_sub) {
			$query->where('dfec_sub', '>=', ToolsServiceProvider::getDateFormat($request->dfec_sub, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
		}
		if ($request->hfec_sub) {
			$query->where('hfec_sub', '<=', ToolsServiceProvider::getDateFormat($request->hfec_sub, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
		}
		return $query;
	}

	#endregion

	#region validate the fields

	private function validateEmptySelectionFields($fields)
	{
		$empty = true;
		foreach ($fields as $key => $value) {
			if (preg_match('/_select$/', $key) && !empty($value)) {
				$empty = false;
				return $empty;

			}
		}
		return $empty;
	}

	#endregion

	#region update with filters

	public function updateSelections(Request $request)
	{
		$ids = $request->input('ids', '');

		if (self::validateEmptySelectionFields($request->toArray())) {
			return response()->json(['success' => false, 'message' => trans("admin-app.error.no_data_form")], 500);
		}

		$request = self::erase_selectTextFromFields($request);

		self::updateAuctionsWithSelects($request, $ids);

		if ($request->upload_first_session) {
			self::updateFirstSessions($request, $ids);
		}

		return response()->json(['success' => true, 'message' => trans("admin-app.success.update_mass_auc")], 200);
	}

	public function updateWithFilters(Request $request)
	{
		$fgSub = FgSub::query();
		$fgSub = self::fgsubQueryFilters($fgSub, $request);
		$ids = ($fgSub->select('cod_sub')->get())->pluck('cod_sub')->toArray();

		if (self::validateEmptySelectionFields($request->all())) {
			return response()->json(['success' => false, 'message' => trans("admin-app.error.no_data_form")], 500);
		}

		$request = self::erase_selectTextFromFields($request);

		self::updateAuctionsWithSelects($request, $ids);

		if ($request->upload_first_session) {
			self::updateFirstSessions($request, $ids);
		}

		return response()->json(['success' => true, 'message' => trans("admin-app.success.update_mass_auc")], 200);
	}

	private function erase_selectTextFromFields(Request $request)
	{
		foreach ($request->all() as $key => $value) {
			if (preg_match('/_select$/', $key)) {
				$request->merge([str_replace('_select', '', $key) => $value]);
				unset($request[$key]);
			}
		}
		return $request;
	}

	private function updateAuctionsWithSelects(Request $request, array $cod_subs)
	{
		$update = [];
			if ($request->tipo_sub) {
				$update['TIPO_SUB'] = $request->tipo_sub;
			}
			if ($request->subc_sub) {
				$update['SUBC_SUB'] = $request->subc_sub;
			}
			if ($request->dfec_sub) {
				$update['DFEC_SUB'] = new DateTime($request->dfec_sub . ' ' . $request->dhora_sub);
			}
			if ($request->dhora_sub) {
				$update['DHORA_SUB'] = $request->dhora_sub;
			}
			if ($request->hfec_sub) {
				$update['HFEC_SUB'] = new DateTime($request->hfec_sub . ' ' . $request->hhora_sub);
			}
			if ($request->hhora_sub) {
				$update['HHORA_SUB'] = $request->hhora_sub;
			}
			if ($request->tipo_sub == FgSub::TIPO_SUB_PRESENCIAL) {
				if ($request->dfecorlic_sub) {
					$update['DFECORLIC_SUB'] = new DateTime($request->dfecorlic_sub . ' ' . $request->dhoraorlic_sub);
				}
				if ($request->dhoraorlic_sub) {
					$update['DHORAORLIC_SUB'] = $request->dhoraorlic_sub;
				}
				if ($request->hfecorlic_sub) {
					$update['HFECORLIC_SUB'] = new DateTime($request->hfecorlic_sub . ' ' . $request->hhoraorlic_sub);
				}
				if ($request->hhoraorlic_sub) {
					$update['HHORAORLIC_SUB'] = $request->hhoraorlic_sub;
				}
			}

			if (count($update) == 0) {
				return true;
			}

			$update = $this->addUserUpdatedAucFields($update);

			FgSub::whereIn('COD_SUB', $cod_subs)->update($update);

	}

	private function addUserUpdatedAucFields(array $update)
	{
		$this->userSession = Session::get('user');

		if ($this->userSession != null) {
			$update['USR_UPDATE_SUB'] = $this->userSession['usrw'];
			$update['DATE_UPDATE_SUB'] = new DateTime();
		}

		return $update;
	}

	private function updateFirstSessions(Request $request, array $cod_subs, bool $updateDescription = true)
	{
		$update = [];

		if ($request->dfec_sub && $request->dhora_sub) {
			$update['"start"'] = new DateTime($request->dfec_sub . ' ' . $request->dhora_sub);
		}
		if ($request->hfec_sub && $request->hhora_sub) {
			$update['"end"'] = new DateTime($request->hfec_sub . ' ' . $request->hhora_sub);
		}

		if ($updateDescription) {
			if ($request->des_sub) {
				$update['"name"'] = $request->des_sub;
			}
			if ($request->descdet_sub) {
				$update['"description"'] = mb_substr($request->descdet_sub, 0, 1000,'UTF-8');
			}
		}

		if ($request->tipo_sub == FgSub::TIPO_SUB_PRESENCIAL) {
			if ($request->dfecorlic_sub && $request->dhoraorlic_sub) {
				$update['"orders_start"'] = new DateTime($request->dfecorlic_sub . ' ' . $request->dhoraorlic_sub);
			}
			if ($request->hfecorlic_sub && $request->hhoraorlic_sub) {
				$update['"orders_end"'] = new DateTime($request->hfecorlic_sub . ' ' . $request->hhoraorlic_sub);
			}
		}

		if (count($update) == 0) {
			return true;
		}

		$update = $this->addUserUpdatedSessionFields($update);

		//session
		AucSessions::whereIn('"auction"', $cod_subs)->where('"reference"', '001')->update($update);

		return true;
	}

	private function addUserUpdatedSessionFields(array $update)
	{
		$this->userSession = Session::get('user');

		if ($this->userSession != null) {
			$update['"usr_update_sessions"'] = $this->userSession['usrw'];
			$update['"date_update_sessions"'] = new DateTime();
		}

		return $update;
	}

	#endregion


}
