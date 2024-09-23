<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\V5\FgSub;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;

class MobileAuctionsController extends Controller
{
	# FALTA QUE SEA MULTIIDIOMA
	public function getActiveAuctions(Request $request)
	{
		#hacemos una consulta para conseguir las subastas activas poniendo S para definir que no están cerradas
		$sessions = $this->getAuctions('S', $request);

		//return $this->responseSuccsess("Active Auctions", $sessions);
		return response()->json($sessions);
	}

	public function getHistoricAuctions(Request $request)
	{

		#hacemos una consulta para conseguir las subastas históricas poniendo H
		$sessions = $this->getAuctions('H', $request);
		return response()->json($sessions);
	}


	private function getAuctions($status, $request)
	{
		$lang = $request->input('lang', 'ES');

		#mandamos query para conseguir todas las sesiones

		$sessions = FgSub::select('COD_SUB, SUBC_SUB, "id_auc_sessions", "reference",  TIPO_SUB, SUBC_SUB')
			->addSelect(' max(NVL("auc_sessions_lang"."name_lang","auc_sessions"."name")) as name')
			->addSelect(' max("auc_sessions"."start") as session_start')
			->addSelect(' max("auc_sessions"."end") as session_end')
			->join('"auc_sessions"', '"auc_sessions"."company" = FGSUB.EMP_SUB AND "auc_sessions"."auction" = FGSUB.COD_SUB')
			->leftJoin('"auc_sessions_lang"',
				' "auc_sessions_lang"."id_auc_session_lang" = "auc_sessions"."id_auc_sessions"   AND "auc_sessions"."company" = "auc_sessions_lang"."company_lang" AND "auc_sessions"."auction" = "auc_sessions_lang"."auction_lang" AND "auc_sessions_lang"."lang_auc_sessions_lang" = \'' . $lang . '\'')
			->join("fgasigl0", 'emp_asigl0 = EMP_SUB AND  SUB_ASIGL0= COD_SUB and ref_asigl0 >=  "init_lot"  and ref_asigl0 <=  "end_lot"')
			->where('subc_sub', '=', $status)
			->groupby('emp_sub , cod_sub , "reference",SUBC_SUB,"id_auc_sessions", TIPO_SUB')
			->orderBy('max("start")', 'desc')->get();

		#inicializamos el array de subastas
		$sessionsResponse = [];

		foreach ($sessions as  $session) {

			$sessionRes = [
				"codsession" => $session->id_auc_sessions,
				"title" => $session->name,
				"type" => $session->tipo_sub,
				"status" => $session->subc_sub,
				"image" => $this->getAuctionImage($session->cod_sub, $session->reference),
			];
			if ($session->subc_sub == "S") {
				$sessionRes["start"] =  $session->tipo_sub == "O" ? $session->session_end :  $session->session_start;
			}

			$sessionsResponse[] = $sessionRes;
		}

		return $sessionsResponse;
	}

	private function getAuctionImage($cod_sub, $reference)
	{
		#intentamos conseguir imagen de sesión
		$image_to_load = ToolsServiceProvider::url_img_session("subasta_large", $cod_sub, $reference);

		#si no existe conseguimos la imagen de la subasta\
		if (!file_exists($image_to_load) || filesize($image_to_load) < 500) {
			$image_to_load = ToolsServiceProvider::url_img_auction("subasta_large", $cod_sub);
		}

		return $image_to_load;
	}
}
