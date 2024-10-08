<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Mobile\Resources\LotResource;
use App\Models\V5\FgAsigl0;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MobileFavoritesController extends Controller
{
	public function index(Request $request, $codsession = null)
	{
		$lang = $request->user()?->idioma_cliweb ?? 'ES';
		App::setLocale($lang);

		$codCli = $request->user()?->cod_cliweb ?? '00012';

		$lots = FgAsigl0::joinFavorites($codCli)
			->activeLotAsigl0()
			->select(['num_hces1', 'lin_hces1', 'impsalhces_asigl0', 'sub_asigl0', 'ref_asigl0', 'implic_hces1', 'retirado_asigl0', 'cerrado_asigl0', 'lic_hces1', 'tipo_sub', 'compra_asigl0', 'impres_asigl0', 'remate_asigl0', 'ffin_asigl0', 'hfin_asigl0', 'subabierta_sub', 'fac_hces1'])
			->addSelect(['descweb_hces1', 'webfriend_hces1'])
			->when($lang != 'ES', function ($query) {
				$query->joinFghces1LangAsigl0()
					->addSelect('descweb_hces1_lang', 'webfriend_hces1_lang');
			})
			->orderBy('web_favorites.fecha', 'desc')
			->get();


		return LotResource::collection($lots);
	}
}
