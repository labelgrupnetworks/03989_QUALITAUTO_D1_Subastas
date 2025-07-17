<?php

namespace App\Services\User;

use App\Models\V5\FxCli;
use App\Models\V5\FxCliWeb;
use Illuminate\Support\Facades\Config;

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

	/**
	 * Obtiene el token de cliente web de Subalia para una licitación específica.
	 *
	 * @param string $codLicit
	 * @return string|null
	 */
	public function getSubaliaTokenByLicit($codLicit) : ?string
	{
		return FxCliWeb::on('subalia')
			->withoutGlobalScopes(['emp'])
			->joinCliCliweb()
			->joinLicitCliweb()
			->where([
				'sub_licit' => '0',
				'cod_licit' => $codLicit,
				'emp_licit' => Config::get("app.APP_SUBALIA_EMP", '001'),
				'gemp_cliweb' => Config::get("app.APP_SUBALIA_GEMP", '01'),
			])
			->value('tk_cliweb');
	}
}
