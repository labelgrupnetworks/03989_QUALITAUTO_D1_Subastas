<?php

namespace App\Services\Auction;

use App\Models\V5\FgHces1;
use App\Models\V5\FgOrtsec0;
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

	public function getAuctionSubCategories($aucionId)
	{
		return FgHces1::query()
			->select('sec_hces1')
			->leftJoinFghces1Asigl0()
			->where('sub_asigl0', $aucionId)
			->distinct()
			->pluck('sec_hces1');
	}

	public function getAuctionCategories($aucionId)
	{
		$subCategories = $this->getAuctionSubCategories($aucionId)->toArray();

		return FgOrtsec0::query()
			->select('lin_ortsec0', 'key_ortsec0', 'orden_ortsec0')
			->selectRaw('COALESCE(fgortsec0_lang.des_ortsec0_lang, fgortsec0.des_ortsec0) as des_ortsec0')
			->joinOrtsec1FgOrtsec0()
			->joinSecOrtsec0()
			->joinLangFgOrtsec0()
			->whereIn('sec_ortsec1', $subCategories)
			->where('sub_ortsec0', 0)
			->distinct()
			->get()
			->sortBy('orden_ortsec0')
			->values();
	}
}
