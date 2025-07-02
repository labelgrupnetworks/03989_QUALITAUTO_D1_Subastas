<?php

namespace App\Services\Auction;

use App\Models\V5\AucSessions;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgSub;
use App\Models\V5\FgSubInd;
use App\Support\Localization;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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

	protected function baseActiveSubsQuery(string $type)
	{
		return FgSub::query()
			->activeSub()
			->where('tipo_sub', $type)
			->when(Config::get('app.restrictVisibility'), function ($q) {
				$q->Visibilidadsubastas(Session::get('user.cod'));
			})
			->when(Config::get('app.agrsub', null), function ($q) {
				$q->where('agrsub_sub', Config::get('app.agrsub'));
			});
	}

	public function getActiveAuctionsToType($type)
	{
		$theme = Config::get('app.theme');
		$isAdmin = Session::get('user.admin');
		$keyCache = "auction.actives.{$theme}.{$type}";
		if ($isAdmin) {
			$keyCache = $keyCache . '.' . 'admin';
		}

		return Cache::remember(Config::get('cache.prefix') . $keyCache, 60, function () use ($type) {
			return $this->baseActiveSubsQuery($type)
				->when(Config::get('app.lang_sub_in_global', false) && !Localization::isDefaultLocale(), function ($query) {
					$query->joinLangSub();
				}, function ($query) {
					$query->addSelect('des_sub');
				})
				->addSelect('subc_sub', 'cod_sub', 'hfec_sub', 'hhora_sub')
				->get();
		});
	}

	public function getActiveSessionsToType($type)
	{
		$isAdmin = Session::get('user.admin');
		$keyCache = "auction.session_actives.{$type}";
		if ($isAdmin) {
			$keyCache = $keyCache . '.admin';
		}

		return Cache::remember(Config::get('cache.prefix') . $keyCache, 60, function () use ($type) {
			return $this->baseActiveSubsQuery($type)
				->addSelect('subc_sub', 'cod_sub', 'hfec_sub', 'hhora_sub')
				->addSelect('"end" as session_end')
				->simpleJoinSessionSub()
				->get();
		});
	}

	/**
	 * Añade a la URL de la subasta los parámetros de paginación necesarios para la sesión.
	 *
	 * @param string $originalUrl URL original de la subasta.
	 * @param float $sessionInitLot Lote inicial de la sesión.
	 * @param string $codSub Código de la subasta.
	 * @param int $lotsPerPage Número de lotes por página (opcional, por defecto 24).
	 * @return string URL modificada con los parámetros de paginación.
	 */
	public function addUrlPageSession($originalUrl, $sessionInitLot, $codSub, $lotsPerPage = 24) : string
	{
		$lotsInAuction = FgAsigl0::query()
			->select('count(ref_asigl0) cuantos')
			->where('SUB_ASIGL0', $codSub)
			->where('ref_asigl0', '<', $sessionInitLot)
			->value('cuantos');

		// +1 porque la página no empieza en 0 si no en 1
		$page = intdiv($lotsInAuction, $lotsPerPage) +1;
		return "$originalUrl?page=$page&total=$lotsPerPage#$codSub-$sessionInitLot";
	}

	public function isLastHistoryAuction($codSub)
	{
		$lastAuctionCod = FgSub::query()
			->where('subc_sub', FgSub::SUBC_SUB_HISTORICO)
			->orderBy('hfec_sub', 'desc')
			->orderBy('hhora_sub', 'desc')
			->value('cod_sub');

		if(!$lastAuctionCod) {
			return false;
		}

		return $lastAuctionCod == $codSub;
	}

	/**
	 * Comprueba si hay subastas activas en los próximos X minutos.
	 *
	 * @param int $minutes Número de minutos para comprobar.
	 * @param string $type Tipo de subasta a comprobar (por defecto, FgSub::TIPO_SUB_PRESENCIAL).
	 * @return bool Verdadero si hay subastas activas, falso en caso contrario.
	 */
	public function hasActiveAuctionsInXMinutes(int $minutes, string $type = FgSub::TIPO_SUB_PRESENCIAL): bool
	{
		return FgAsigl0::query()
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->where([
				['subc_sub', FgSub::SUBC_SUB_ACTIVO],
				['tipo_sub', $type],
				['cerrado_asigl0', 'N']
			])
			->whereRaw(DB::raw('auc."start" - (?/1440) < sysdate  and auc."end" > sysdate'), [$minutes])
			->exists();
	}
}
