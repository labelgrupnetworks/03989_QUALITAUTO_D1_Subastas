<?php

namespace App\Services\Content;

use App\Models\V5\FsEmpres;
use App\Models\V5\FsParams;

class EnterpriseParamsService
{
	public function getCompany()
	{
		return FsEmpres::first();
	}

	/**
	 * Obtiene la moneda principal de la empresa.
	 * @todo Es cacheable
	 * @return object
	 * - name: Nombre de la moneda (ej. EUR, COP, US$, PAB)
	 * - symbol: Símbolo de la moneda (ej. €, COP, US$, B/.)
	 */
	public function getCurrency()
	{
		$divisa = FsParams::active()->value('div_params');
		$symbol = match ($divisa) {
			'EUR' => '€',
			'COP' => 'COP',
			'US$' => 'US$',
			'PAB' => 'B/.',
			default => '€',
		};

		return (object)[
			'name' => $divisa,
			'symbol' => $symbol,
		];
	}
}
