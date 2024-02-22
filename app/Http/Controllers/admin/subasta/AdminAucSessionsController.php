<?php

namespace App\Http\Controllers\admin\subasta;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use App\Http\Requests\admin\AucSessionRequest;
use App\Providers\ToolsServiceProvider;
use App\Models\V5\AucSessions;
use App\Models\V5\AucSessions_Lang;
use App\Models\V5\AucSessionsFiles;
use App\Models\V5\FgSub;
use App\libs\FormLib;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AdminAucSessionsController extends Controller
{

	private $isRender;

	public function __construct($render = true)
	{
        $this->isRender = $render;
    }

	/**
	 * Mostrar página incial
	 * */
	function index($cod_sub)
	{
		$aucSessions = AucSessions::select('"id_auc_sessions"', '"reference"', '"auction"', '"name"', '"init_lot"', '"end_lot"', '"start"', '"end"')->where('"auction"', $cod_sub)->get();
		return $aucSessions;
	}

	function create($cod_sub)
	{
		$fgSub = FgSub::where('cod_sub', $cod_sub)->firstOrFail();

		$id_auc_sessions = AucSessions::withoutGlobalScopes()->max('"id_auc_sessions"') + 1;
		$reference = AucSessions::select('"reference"')->where('"auction"', $cod_sub)->max('"reference"') + 1;

		$aucSession = new AucSessions();
		$aucSessions_lang = new Collection([new AucSessions_Lang()]);

		$aucSession->id_auc_sessions = $id_auc_sessions;
		$aucSession->reference = str_pad($reference, 3, '0', STR_PAD_LEFT);

		$formulario = (object) $this->basicFormCreateAucSession($aucSession, $fgSub, $aucSessions_lang);

		return view('admin::pages.subasta.sesiones._create', compact('formulario', 'fgSub', 'aucSession'))->render();
	}

	/**
	 * Mostrar item
	 * */
	function show(){

	}

	function edit($cod_sub, $reference)
	{
		$fgSub = FgSub::where('cod_sub', $cod_sub)->firstOrFail();

		$aucSession = AucSessions::where('"auction"', $cod_sub)
			->where('"reference"', $reference)
			->first();

		$aucSessions_lang = AucSessions_Lang::where('"id_auc_session_lang"', $aucSession->id_auc_sessions)->get();

		$formulario = (object) $this->basicFormCreateAucSession($aucSession, $fgSub, $aucSessions_lang);
		return view('admin::pages.subasta.sesiones._edit', compact('formulario', 'fgSub', 'aucSession'))->render();
	}


	/**
	 * Formulario con item
	 * */
	function oldEdit($subasta, $reference = 0){

		$data = array("reference" => $reference, "subasta" => $subasta, 'menu' => 4);

		$session = AucSessions::where('"auction"', $subasta)->where('"reference"', $reference)->first();
		$sessions_langs = array();
		if (!empty($session)) {
			$sessions_langs = AucSessions_Lang::where('"id_auc_session_lang"', $session->id_auc_sessions)
				->where('"company_lang"', $session->company)
				->where('"auction_lang"', $session->auction)
				->where('"reference_lang"', $session->reference)
				->get();
		}

		$data['formulario'] = array();

		$data['formulario']['subasta'] = FormLib::ReadOnly("auction", 1, $subasta);
		if (empty($reference)) {
			$id = AucSessions::withoutGlobalScopes()->max('"id_auc_sessions"') + 1;
			$data['formulario']['id Sesión'] = FormLib::ReadOnly("id_auc_sessions", 1, $id);
		} else {
			$data['formulario']['id Sesión'] = FormLib::ReadOnly("id_auc_sessions", 1, $session->id_auc_sessions);
		}

		if (empty($reference)) {
			$id = AucSessions::where('"auction"', $subasta)->count() + 1;
			$id = (str_pad($id, 3, "0", STR_PAD_LEFT));
			$data['formulario']['referencia'] = FormLib::ReadOnly("reference", 1, $id);
		} else {
			$data['formulario']['referencia'] = FormLib::ReadOnly("reference", 1, $session->reference);
		}

		$data['formulario']['Inicio Sesión'] = FormLib::DateTime("start", 1, empty($session) ? '' : $session->start);
		$data['formulario']['Fin Sesión'] = FormLib::DateTime("end", 1, empty($session) ? '' : $session->end);
		$data['formulario']['Inicio Ordenes'] = FormLib::DateTime("orders_start", 1, empty($session) ? '' : $session->orders_start);
		$data['formulario']['Fin Ordenes'] = FormLib::DateTime("orders_end", 1, empty($session) ? '' : $session->orders_end);
		$data['formulario']['Lote Inicial'] = FormLib::Int("init_lot", 1, empty($session) ? '' : $session->init_lot);
		$data['formulario']['Lote Final'] = FormLib::Int("end_lot", 1, empty($session) ? '' : $session->end_lot);
		$data['formulario']['Imagen'] = FormLib::File('image_session', 0);


		//textos
		$data['formularioTexto'] = array();

		$language_complete = \Config::get("app.language_complete");
		$sessionsLang = array();

		foreach (Config::get("app.locales") as $lang => $langComplete) {
			foreach ($sessions_langs as $item) {
				if ($item->lang_auc_sessions_lang == $language_complete[mb_strtolower($lang)]) {
					$sessionsLang[$lang] = $item;
				}
			}
		}

		foreach (Config::get("app.locales") as $lang => $langComplete) {
			$data['formularioTexto'][$lang] = array();

			if ($lang == 'es') {
				$data['formularioTextos'][$lang]['nombre'] = FormLib::Text("name[$lang]", 0, empty($session) ? '' : $session->name);
				$data['formularioTextos'][$lang]['descripcion'] = FormLib::TextArea("description[$lang]", 0, empty($session) ? '' : $session->description);
			} else {
				$data['formularioTextos'][$lang]['nombre'] = FormLib::Text("name[$lang]", 0, empty($sessionsLang[$lang]) ? '' : $sessionsLang[$lang]->name_lang);
				$data['formularioTextos'][$lang]['descripcion'] = FormLib::TextArea("description[$lang]", 0, empty($sessionsLang[$lang]) ? '' : $sessionsLang[$lang]->description_lang);
			}
		}

		$data['formulario']['SUBMIT'] = FormLib::Submit("Guardar", "edit");


		/**
		 * Formulario ficheros
		 * Mostrar solo cuando se tenga referencia
		 */
		if (!empty($reference)) {

			$typeFiles = [
				1 =>'Pdf',
				2 => 'Video',
				3 => 'Imagen',
				4 => 'Documento',
				5 => 'Enlace'
			];

			$langsFile = array();
			foreach (Config::get("app.locales") as $lang => $langComplete) {
				$langsFile[Config::get("app.language_complete")[$lang]] = $langComplete;
			}

			$data['formFile'] = array();
			$data['formFile']['file'] = FormLib::File("ficheroAdjunto", 0);
			$data['formFile']['description'] = FormLib::Text('description', 1);
			$data['formFile']['order'] = FormLib::Int('order', 0, 1);
			$data['formFile']['langFile'] = FormLib::Select('lang', 1, 'es-ES', $langsFile);
			$data['formFile']['typeFile'] = FormLib::Select('typefile', 1, 1, $typeFiles);
			$data['formFile']['img'] = FormLib::File("imagenFile", 0);
			$data['formFile']['url'] = FormLib::Text('url', 0);

			$data['formFile']['icons'] = [
				1 => '/img/icons/pdf.png',
				2 => '/img/icons/video.png',
				3 => '/img/icons/image.png',
				4 => '/img/icons/document.png',
				5 => '/img/icons/video.png',
			];

			$data['formFile']['files'] = AucSessionsFiles::where('"auction"', $subasta)
									->where('"reference"', $reference)
									->get();

		}


		return \View::make('admin::pages.subasta.sesiones.edit', $data);

	}

	/**
	 * Guardar con item
	 * */
	function store(AucSessionRequest $request, $cod_sub)
	{
		$auc_session_attributes = [
			'"auction"' => $cod_sub,
			//'"id_auc_sessions"' => $request->id_auc_sessions,
			'"reference"' => $request->reference,
			'"start"' => $request->start,
			'"end"' => $request->end,
			'"init_lot"' => $request->init_lot,
			'"end_lot"' => $request->end_lot,
			'"name"' => $request->name,
			'"description"' => $request->description,
			'"orders_start"' => $request->orders_start,
			'"orders_end"' => $request->orders_end,
		];

		try {

			DB::beginTransaction();
			$auc_session = AucSessions::query()->create($auc_session_attributes);
			$auc_session = AucSessions::where('"auction"', $cod_sub)->where('"id_auc_sessions"', $auc_session['"id_auc_sessions"'])->first();

			$image = '';
			if($request->has('image_session')){
				$image = $this->saveAuctionImage($request->file('image_session'), $cod_sub, $request->reference);
			}

			$auc_session->image = $image;

			$languages = array_diff(Config::get('app.locales'), ['es' => 'Español']);
			if (!empty($languages)) {
				$this->createOrSaveAucSession_lang($request, $auc_session, $languages);
			}
			//añadir los idiomas

			DB::commit();

			$aucSessions = AucSessions::select('"id_auc_sessions"', '"reference"', '"auction"', '"name"', '"init_lot"', '"end_lot"', '"start"', '"end"')->where('"auction"', $cod_sub)->get();
			return response(view('admin::pages.subasta.sesiones._table', ['aucSessions' => $aucSessions, 'cod_sub' => $cod_sub])->render());

		} catch (\Throwable $th) {
			DB::rollBack();

			//eliminar la foto de la sesion
			Log::error($th->getMessage());
			return response($th->getMessage(), 400);
		}
	}

	public function update(AucSessionRequest $request, $cod_sub, $reference)
	{

		$auc_info = AucSessions::where('"auction"', $cod_sub)->where('"reference"', $reference)->firstOrFail();

		$aucSession =  AucSessions::where('"auction"', $cod_sub)->where('"reference"', $reference)
			->update([
				'"start"' => $request->start,
				'"end"' => $request->end,
				'"init_lot"' => $request->init_lot,
				'"end_lot"' => $request->end_lot,
				'"name"' => $request->name,
				'"description"' => $request->description,
				'"orders_start"' => $request->orders_start,
				'"orders_end"' => $request->orders_end,
			]);


		if ($request->has('image_session')) {
			$this->saveAuctionImage($request->file('image_session'), $cod_sub, $request->reference);
		}

		$languages = array_diff(Config::get('app.locales'), ['es' => 'Español']);
		if (!empty($languages)) {
			$this->createOrSaveAucSession_lang($request, $auc_info, $languages);
		}

		$aucSessions = AucSessions::select('"id_auc_sessions"', '"reference"', '"auction"', '"name"', '"init_lot"', '"end_lot"', '"start"', '"end"')->where('"auction"', $cod_sub)->get();
		return response(view('admin::pages.subasta.sesiones._table', ['aucSessions' => $aucSessions, 'cod_sub' => $cod_sub])->render());
	}


	/**
	 * Actualizar item
	 * */
	function oldUpdate(){

		$pk = [
			'"auction"' => request("readonly__auction"),
			'"id_auc_sessions"' => request("readonly__id_auc_sessions"),
			'"reference"' => request("readonly__reference")
		];

		$names = request("name");
		$descriptions = request("description");

		$attributes = [
			'"start"' => new DateTime(request("start")),
			'"end"' => new DateTime(request("end")),
			'"orders_start"' => new DateTime(request("orders_start")),
			'"orders_end"' => new DateTime(request("orders_end")),
			'"init_lot"' => request("init_lot"),
			'"end_lot"' => request("end_lot"),
			'"name"' => $names['es'],
			'"description"' => $descriptions['es']
		];

		unset($names['es']);
		unset($descriptions['es']);
		unset($pk['"id_auc_sessions"']);

		$aucSessions = AucSessions::where($pk)->first();

		if ($aucSessions) {
			AucSessions::where($pk)->update($attributes);
		} else {
			AucSessions::create($pk + $attributes);
		}

		$aucSessions = AucSessions::where($pk)->first();

		//idiomas
		$pk_lang = [
			'"id_auc_session_lang"' => $aucSessions->id_auc_sessions,
			'"auction_lang"' => $pk['"auction"'],
			'"reference_lang"' => $pk['"reference"'],
		];

		$language_complete = Config::get("app.language_complete");
		foreach ($names as $key => $value) {

			$pk_lang['"lang_auc_sessions_lang"'] = $language_complete[$key];

			$attributes_lang = [
				'"name_lang"' => $value,
				'"description_lang"' => $descriptions[$key]
			];

			$aucSessions_lang = AucSessions_Lang::where($pk_lang)->first();

			if ($aucSessions_lang) {
				AucSessions_Lang::where($pk_lang)->update($attributes_lang);
			} else {
				AucSessions_Lang::create($pk_lang + $attributes_lang);
			}
		}

		if (!empty($_FILES) && !empty($_FILES['image_session']['tmp_name'])) {
			$this->guardarImagen($_FILES, $pk['"auction"'], $pk['"reference"']);
		}


		return redirect("/admin/subasta/edit/" . $pk['"auction"']);

	}

	/**
	 * Eliminar item
	 * */
	function destroy($cod_sub, $reference){

		AucSessions::where('"auction"', $cod_sub)
			->where('"reference"', $reference)
			->delete();

		AucSessions_Lang::where('"auction_lang"', $cod_sub)
			->where('"reference_lang"', $reference)
			->delete();

		//Tenemos que eliminar la foto de la sesion


		$aucSessions = AucSessions::select('"id_auc_sessions"', '"reference"', '"auction"', '"name"', '"init_lot"', '"end_lot"', '"start"', '"end"')->where('"auction"', $cod_sub)->get();

		return response(view('admin::pages.subasta.sesiones._table', ['aucSessions' => $aucSessions, 'cod_sub' => $cod_sub])->render());

		echo "OK";
	}

	public function guardarImagen($ficheros, $cod_sub, $cod_session)
	{

		$pathInicial = "/img/";
		$path = str_replace("\\", "/", getcwd() . $pathInicial);

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

				$dst_image = imagecreatetruecolor($w, $h);

				$blanco = imagecolorallocate($src_image, 255, 255, 255);
				imagefill($dst_image, 0, 0, $blanco);

			#	imagecopy($dst_image, $src_image, 0, 0, 0, 0, $size[0], $size[1]);
				imagecopyresampled($dst_image, $src_image, 0, 0,0, 0, $w, $h, $size[0], $size[1]);

				imagejpeg($dst_image, $path . "/SESSION_" . \Config::get('app.emp') . "_" . $cod_sub . "_" . $cod_session . ".JPEG");
				//imagejpeg($dst_image, $path . "/AUCTION_" . \Config::get('app.emp') . "_" . $cod_sub . "_" . $cod_session . ".JPEG");
			}
		}
	}

	public function saveAuctionImage(UploadedFile $image, string $auction, string $reference)
	{
		$emp = Config::get('app.emp', '001');

		$destinationPath = public_path("img/SESSION_$emp" . "_$auction" . "_" . $reference . ".JPEG");

		$img = Image::make($image->path());
		clearstatcache();

		if($img->width() > 2000){
			$img->resize(2000, null, function ($constraint) {
				$constraint->aspectRatio();
			});
		}

		$img->save($destinationPath);

		return ToolsServiceProvider::url_img_session('subasta_large', $auction, $reference);
	}

	public function createOrSaveAucSession_lang($request, $auc_session, $languages)
	{

		$fgAucSession_lang = AucSessions_Lang::where('"id_auc_session_lang"', $auc_session->id_auc_sessions)->get();

		foreach ($languages as $keyLang => $lang) {

			$completeLang = ToolsServiceProvider::getLanguageComplete($keyLang);
			$requestForThisLang = ($request->name_lang[$keyLang] || $request->description_lang[$keyLang]);

			if ($requestForThisLang) {

				//dd($auc_session);

				if ($fgAucSession_lang->where('lang_auc_sessions_lang', $completeLang)->count()) {

					AucSessions_Lang::where([
						['"id_auc_session_lang"', $auc_session->id_auc_sessions],
						['"lang_auc_sessions_lang"', $completeLang],
					])->update([
						'"name_lang"' => $request->name_lang[$keyLang],
						'"description_lang"' => $request->description_lang[$keyLang]
					]);
				} else {

					AucSessions_Lang::create([
						'"id_auc_session_lang"' => $auc_session->id_auc_sessions,
						'"auction_lang"' => $auc_session->auction,
						'"reference_lang"' => $auc_session->reference,
						'"lang_auc_sessions_lang"' => $completeLang,
						'"name_lang"' => $request->name_lang[$keyLang],
						'"description_lang"' => $request->description_lang[$keyLang],
					]);
				}
			}
		}
	}

	protected function basicFormCreateAucSession(AucSessions $aucSession, FgSub $fgSub, $auc_session_lang)
	{
		$formulario = [
			'imagen' => ['image_session' => FormLib::File("image_session", 0)],
			'textos' => [
				'id_auc_sessions' => FormLib::TextReadOnly("id_auc_sessions", 1, $aucSession->id_auc_sessions),
				'reference' => FormLib::TextReadOnly("reference", 1, $aucSession->reference),
				'name' => FormLib::Text('name', 1, $aucSession->name, 'maxlength="65"'),
				'description' => FormLib::Textarea('description', 1, old('description', $aucSession->description), 'maxlength="1000"'),
			],
			'fechas' => [
				'start' => FormLib::DateTimePicker("start", 1, $aucSession->start ?? $fgSub->desde_fecha_hora),
				'end' => FormLib::DateTimePicker("end", 1, $aucSession->end ?? $fgSub->hasta_fecha_hora),
				'orders_start' => FormLib::DateTimePicker("orders_start", 1, $aucSession->orders_start ?? $fgSub->desde_fecha_hora),
				'orders_end' => FormLib::DateTimePicker("orders_end", 1, $aucSession->orders_end ?? $fgSub->hasta_fecha_hora),
			],
			'lotes' => [
				'init_lot' => FormLib::Int("init_lot", 1, $aucSession->init_lot ?? 1),
				'end_lot' => FormLib::Int("end_lot", 1, $aucSession->end_lot ?? 9999)
			],
			'submit' => FormLib::Submit('Guardar', 'sessionStore')
		];

		$languages = array_diff(Config::get('app.locales'), ['es' => 'Español']);

		if(empty($languages)){
			return $formulario;
		}

		$auc_session_langArray = $auc_session_lang->mapWithKeys(function ($item) {
			return [$item->lang_auc_sessions_lang => $item];
		});

		$formulario['traducciones'] = [];

		foreach ($languages as $keyLang => $lang) {

			$completeLang = ToolsServiceProvider::getLanguageComplete($keyLang);



			$formulario['traducciones'][$keyLang] = [
				'name_lang' => FormLib::Text("name_lang[$keyLang]", 0, $auc_session_langArray[$completeLang]->name_lang ?? '', 'maxlength="65"'),
				'description_lang' => FormLib::Textarea("description_lang[$keyLang]", 0, $auc_session_langArray[$completeLang]->description_lang ?? '', 'maxlength="1000"')
			];
		}

		return $formulario;
	}

}
