<?php

namespace App\Services\User;

use App\Models\V5\FxCli;

class UserService
{

	public function getUserQueryByCodCli($codCli)
	{
		return FxCli::query()
			->leftJoinCliWebCli()
			->where('cod_cli', $codCli);
	}

	public function getUserQueryByEmail($email)
	{
		return FxCli::query()
			->leftJoinCliWebCli()
			->where('lower(email_cli)', strtolower($email));
	}

	public function getUserQueryByLicitCod($auctionCod, $licitCod)
	{
		return FxCli::query()
			->leftJoinCliWebCli()
			->joinLicitCli()
			->where([
				'sub_licit' => $auctionCod,
				'cod_licit' => $licitCod,
			]);
	}
}
