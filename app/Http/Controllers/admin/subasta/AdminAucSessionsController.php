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
