<?php

namespace App\Services\Auction;

use App\Models\V5\FgSubInd;
use Illuminate\Support\Facades\Config;

class AuctionService
{
	public function getAuctionIndexs($codSub, $idAucSession)
	{

		//Config::set('app.locale', 'en');
		$locale = Config::get('app.locale');

		$indexs = FgSubInd::query()
			->select('fgsubind.*')
			->when($locale != Config::get('app.fallback_locale'), function ($query) {
				$query->joinLangSubInd()
					->addSelectDescriptionsLang();
			})
			->joinAucSessions()
			->where('sub_subind', $codSub)
			->where('"auc_sessions"."id_auc_sessions"', $idAucSession)
			->orderBy('orden_subind')
			->get();

		return $indexs;
	}

	public function existsAuctionIndex($codSub, $idAucSession)
	{
		return FgSubInd::query()
			->joinAucSessions()
			->where('sub_subind', $codSub)
			->where('"auc_sessions"."id_auc_sessions"', $idAucSession)
			->exists();
	}


}
