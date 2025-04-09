<?php

namespace App\Http\View\Composers;

use App\Models\Subasta;
use App\Services\Content\EnterpriseParamsService;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class GlobalComposer
{
	static $auctionTypes;
	static $company;

	//variable utilizada antes, la mantenemos mientras queden clientes por migrar y actualizar
	static $subastas;

	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	public function compose(View $view)
	{
		//obtenemos solamente el numero de subastas activas por tipo
		if (Config::get('app.global_auction_types_var', true)) {
			if (!self::$auctionTypes) {
				self::$auctionTypes = Subasta::getAuctionTypesCount();
			}
		}

		if (!self::$company) {
			self::$company = (new EnterpriseParamsService)->getCompany();
		}

		return $view->with('global', [
			'subastas' => self::$subastas ?? collect([]),
			'auctionTypes' => self::$auctionTypes ?? collect([]),
			'company' => self::$company
		]);
	}
}
