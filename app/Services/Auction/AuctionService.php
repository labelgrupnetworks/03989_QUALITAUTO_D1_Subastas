<?php

namespace App\Services\Auction;

use App\Models\V5\AucSessions;
use App\Models\V5\FgSub;
use App\Models\V5\FgSubInd;
use App\Support\Localization;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class AuctionService
{
	public function getAuctionIndexs($codSub, $idAucSession)
	{
		$locale = Config::get('app.locale');

		$indexs = FgSubInd::query()
			->select('fgsubind.*')
			->when($locale != Config::get('app.fallback_locale'), function ($query) {
				$query->joinLangSubInd()
					->addSelectDescriptionsLang();
			})
			->joinAucSessions()
			->where('sub_subind', $codSub)
			->when($idAucSession, function ($query) use ($idAucSession) {
				$query->where('"auc_sessions"."id_auc_sessions"', $idAucSession);
			})
			->orderBy('orden_subind')
			->get();

		return $indexs;
	}

	public function existsAuctionIndex($codSub, $idAucSession)
	{
		return FgSubInd::query()
			->joinAucSessions()
			->where('sub_subind', $codSub)
			->when($idAucSession, function ($query) use ($idAucSession) {
				$query->where('"auc_sessions"."id_auc_sessions"', $idAucSession);
			})
			->exists();
	}

	public function getFirstSessionByAuction($codSub)
	{
		return AucSessions::query()
			->whereAuction($codSub)
			->orderBy('"reference"')
			->first();
	}

	public function getActiveAuctionsToType($type)
	{
		$theme = Config::get('app.theme');
		$isAdmin = Session::get('user.admin');
		$keyCache = "auction.actives.{$theme}.{$type}";
		if($isAdmin) {
			$keyCache = $keyCache . '.' . 'admin';
		}

		return Cache::remember($keyCache, 60, function () use ($type) {
			return FgSub::query()
				->when(Config::get('app.lang_sub_in_global', false) && !Localization::isDefaultLocale(), function ($query) {
					$query->joinLangSub();
				}, function ($query) {
					$query->addSelect('des_sub');
				})
				->addSelect('subc_sub', 'cod_sub', 'hfec_sub', 'hhora_sub')
				->activeSub()
				->where('tipo_sub', $type)
				->when(Config::get("app.restrictVisibility"), function ($query) {
					$query->Visibilidadsubastas(Session::get('user.cod'));
				})
				->when(Config::get('app.agrsub', null), function ($query) {
					$query->where('agrsub_sub', Config::get('app.agrsub'));
				})
				->get();
		});
	}
}
