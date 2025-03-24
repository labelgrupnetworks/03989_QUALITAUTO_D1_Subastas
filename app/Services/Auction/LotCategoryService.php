<?php

namespace App\Services\Auction;

use App\Models\V5\FxTsec;
use App\Support\Localization;
use Illuminate\Support\Facades\DB;

class LotCategoryService
{
	public function getSecciones($cod_sec)
	{
		$lang = Localization::getLocaleComplete();

		return FxTsec::query()
			->select('fxtsec.cod_tsec as cod_tsec', DB::raw('NVL(fxtsec_lang.des_tsec_lang, fxtsec.des_tsec) as des_tsec'), 'fxtsec.tipo_tsec')
			->join('fxsec', 'fxtsec.cod_tsec = fxsec.tsec_sec and fxtsec.gemp_tsec = fxsec.gemp_sec')
			->leftJoin('fxtsec_lang', function ($query) use ($lang) {
				return $query->on('fxtsec_lang.cod_tsec_lang', '=', 'fxtsec.cod_tsec')
					->on('fxtsec_lang.gemp_tsec_lang', '=', 'fxtsec.gemp_tsec');
			})
			->where([
				['fxtsec.web_tsec', 'S'],
				['fxsec.cod_sec',  $cod_sec]
			])
			->where(function ($query) use ($lang) {
				$query->where('fxtsec_lang.lang_tsec_lang', $lang)
					->orWhereNull('fxtsec_lang.lang_tsec_lang');
			})
			->get();
	}
}
