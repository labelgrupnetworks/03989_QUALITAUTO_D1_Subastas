<?php

namespace App\Services\Auction;

use App\Models\V5\FxAlm;

class LotDeliveryService
{
	/**
	 * Get the warehouse information by its ID.
	 *
	 * @param string $warehouseId
	 * @return FxAlm|null
	 */
	public function getWarehouseById($warehouseId) :?FxAlm
	{
		return FxAlm::query()
			->select('cod_alm', 'obs_alm', 'horario_alm', 'maps_alm', 'cp_alm', 'dir_alm', 'pob_alm', 'tel_alm', 'email_alm', 'codpais_alm')
			->where('cod_alm', $warehouseId)
			->first();
	}
}
