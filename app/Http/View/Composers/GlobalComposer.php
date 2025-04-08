<?php

namespace App\Http\View\Composers;

use App\Models\Subasta;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class GlobalComposer
{

	static $auctionTypes;
	static $subastas;

	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	public function compose(View $view)
	{
		//query original, menos optimizada pero se obtinene toda la info
		//cuando todos tengan las blades actualizadas se puede eliminar
		/**
		 * Clientes actualizados
		 * [x] - Duran
		 * [x] - Ansorena
		 */
		if(Config::get('app.global_auctions_var', true)) {
			if(!self::$subastas) {
				self::$subastas = Subasta::auctionsToViews();
			}
		}

		//obtenemos solamente el numero de subastas activas por tipo
		if (Config::get('app.global_auction_types_var', true)) {
			if(!self::$auctionTypes) {
				self::$auctionTypes = Subasta::getAuctionTypesCount();
			}
		}

		return $view->with('global', [
			'subastas' => self::$subastas ?? collect([]),
			'auctionTypes' => self::$auctionTypes ?? collect([]),
		]);
	}
}
