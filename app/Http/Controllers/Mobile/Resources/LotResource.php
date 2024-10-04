<?php

namespace App\Http\Controllers\Mobile\Resources;

use App\Models\Subasta;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class LotResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'codauction' => $this->sub_asigl0,
			'lotref' => $this->ref_asigl0,
			'title' => strip_tags($this->descweb_hces1_lang ?? $this->descweb_hces1),
			'image' => ToolsServiceProvider::url_img('lote_medium', $this->num_hces1, $this->lin_hces1),
			'price' => $this->impsalhces_asigl0,
			'retired' => $this->retirado_asigl0 == 'S',
			'notavailable' => $this->isNotAvailable(),
			'close' => $this->isClose(),
			'sold' => $this->isSold(),
			'soldprice' => $this->canShowPrice() ? $this->implic_hces1 : null,
			'typeauction' => $this->tipo_sub,
			'forsale' => $this->canSale(),
			'enddate' => in_array($this->tipo_sub, ['O', 'P']) ? $this->ffin_asigl0 : null,
			'actualbid' => (float) $this->getActualBid(),
			'links' => [
				'self' => route('mobile.auctions.lots.show', ['codauction' => $this->sub_asigl0, 'lotref' => $this->ref_asigl0]),
			],
		];
	}

	private function isNotAvailable()
	{
		return $this->fac_hces1 == 'D' || $this->fac_hces1 == 'R' || $this->cerrado_asigl0 == 'D';
	}

	private function isClose()
	{
		if ($this->cerrado_asigl0 == 'S' && empty($this->implic_hces1) && $this->compra_asigl0 == 'S' && in_array($this->tipo_sub, ['W', 'O', 'P'])) {
			return false;
		}

		return $this->cerrado_asigl0 == 'S';
	}

	private function isSold()
	{
		return $this->isClose() && $this->implic_hces1 > 0;
	}

	private function canShowPrice()
	{
		return $this->remate_asigl0 == 'S' && $this->isSold();
	}

	private function canSale()
	{
		if ($this->cerrado_asigl0 == 'S' && empty($this->implic_hces1) && $this->compra_asigl0 == 'S' && in_array($this->tipo_sub, ['W', 'O', 'P'])) {
			return true;
		}

		return !$this->isClose() && $this->tipo_sub == 'V';
	}

	private function getActualBid()
	{
		if ($this->tipo_sub == 'W' && $this->subabierta_sub == 'O' && $this->cerrado_asigl0 != 'S') {
			$subastaObj = new Subasta();
			$subastaObj->lote  = $this->ref_asigl0;
			$subastaObj->ref = $this->ref_asigl0;
			$subastaObj->cod   = $this->sub_asigl0;
			$ordenes = $subastaObj->getOrdenes();
			$subastaObj->sin_pujas = false;

			return  $subastaObj->price_open_auction($this->impsalhces_asigl0, $ordenes);
		}

		if (($this->tipo_sub == 'O' || $this->tipo_sub == 'P' || ($this->tipo_sub == 'W' && $this->subabierta_sub == 'P')) && $this->cerrado_asigl0 != 'S') {
			return $this->implic_hces1;
		}

		return null;
	}
}
