<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Mobile\Resources\AuctionCollection;
use App\Http\Controllers\Mobile\Resources\AuctionResource;
use App\Models\V5\AucSessionsFiles;
use App\Models\V5\FgSub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class MobileAuctionsController extends Controller
{
	public function index(Request $request)
	{
		$lang = $request->user()?->idioma_cliweb ?? 'ES';
		App::setLocale($lang);

		$request->validate([
			'status' => 'in:S,H',
		]);

		$status = $request->input('status', 'S');
		#mandamos query para conseguir todas las sesiones

		$sessions = FgSub::query()
			->select('FgSub.COD_SUB, FgSub.SUBC_SUB, "auc_sessions"."id_auc_sessions", "auc_sessions"."reference",  FgSub.TIPO_SUB')
			->addSelect('NVL("auc_sessions_lang"."name_lang","auc_sessions"."name") as name')
			->addSelect('"auc_sessions"."start" as session_start')
			->addSelect('"auc_sessions"."end" as session_end')
			->join('"auc_sessions"', '"auc_sessions"."company" = FGSUB.EMP_SUB AND "auc_sessions"."auction" = FGSUB.COD_SUB')
			->leftJoin('"auc_sessions_lang"',
				' "auc_sessions_lang"."id_auc_session_lang" = "auc_sessions"."id_auc_sessions"   AND "auc_sessions"."company" = "auc_sessions_lang"."company_lang" AND "auc_sessions"."auction" = "auc_sessions_lang"."auction_lang" AND "auc_sessions_lang"."lang_auc_sessions_lang" = \'' . $lang . '\'')
			->where(function($query) {
				$query->whereRaw('exists (select 1 from fgasigl0 where fgasigl0.emp_asigl0 = FgSub.EMP_SUB AND fgasigl0.SUB_ASIGL0 = FgSub.COD_SUB and fgasigl0.ref_asigl0 >= "auc_sessions"."init_lot"  and ref_asigl0 <= "auc_sessions"."end_lot")');
			})
			->where('FgSub.subc_sub', '=', $status)
			->orderBy('"auc_sessions"."start"', 'desc')
			->get();
			//->paginate(3);

		//return new AuctionCollection($sessions);
		return AuctionResource::collection($sessions);
	}

	public function show(Request $request, $codsession)
	{
		$lang = $request->user()?->idioma_cliweb ?? 'ES';
		App::setLocale($lang);

		#comprobamos campo id_auc_sessions
		//$this->missFields(['codsession']);

		#hacemos query para recoger datos de la subasta
		$session = FgSub::select("SUBC_SUB")
			->joinLangSub()
			->joinSessionSub()
			->where('"id_auc_sessions"', $codsession)
			->first();

		#si no existe la sesion devolvemos error
		if (!$session) {
			return $this->responseError("session don't exist");
		}

		#hacemos query para recoger archivos de la subasta
		$files = AucSessionsFiles::where('"auction"', $session->cod_sub)->get();

		$filesArray = [];

		#si existen archivos los guardamos en un array
		if ($files) {
			$session->files = [];
			#recorremos los archivos
			foreach ($files as $file) {
				$filesArray[] = [
					"title" => $file->description,
					"type" => $file->type_file,
					"url" => Config::get('app.url') . $file->public_file_path
				];
			}

			$session->files = $filesArray;
		}

		return new AuctionResource($session);
	}


}
