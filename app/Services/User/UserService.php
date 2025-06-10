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
}
