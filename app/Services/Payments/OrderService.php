<?php

namespace App\Services\Payments;

use App\Models\V5\FgDvc1l;
use App\Models\V5\FxDvc0Dir;
use App\Providers\ToolsServiceProvider;

class OrderService
{
	public function getOrderDetails($serie, $number)
	{
		$orderLines = FgDvc1l::where([
			'anum_dvc1l' => $serie,
			'num_dvc1l' => $number
		])->get();

		$lots = $orderLines->where('tl_dvc1l', 'P')
			->sortBy('ref_dvc1l')
			->map(function ($line) {
				return [
					'reference' => $line->ref_dvc1l,
					'award_price' => $line->padj_dvc1l,
					'award_price_format' => ToolsServiceProvider::moneyFormat($line->padj_dvc1l, false, 2),
				];
			})->values();

		$shippingCosts = $orderLines->where('sec_dvc1l', 'G')->sum('basea_dvc1l');
		$exportLicense = $orderLines->where('sec_dvc1l', 'LE')->sum('basea_dvc1l');
		$financeCharge = $orderLines->where('sec_dvc1l', 'CF')->sum('basea_dvc1l');
		$commission = $orderLines->where('tl_dvc1l', 'P')->sum('basea_dvc1l');
		$total = $lots->sum('award_price') + $shippingCosts + $exportLicense + $financeCharge + $commission;

		return [
			'lots' => $lots,
			'commission' => ToolsServiceProvider::moneyFormat($commission, false, 2),
			'shipping_costs' => ToolsServiceProvider::moneyFormat($shippingCosts, false, 2),
			'export_license' => ToolsServiceProvider::moneyFormat($exportLicense, false, 2),
			'finance_charge' => ToolsServiceProvider::moneyFormat($financeCharge, false, 2),
			'total' => ToolsServiceProvider::moneyFormat($total, false, 2),
		];
	}

	public function getOrderShippingAddress($serie, $number)
	{
		return FxDvc0Dir::getDirectionByIds($serie, $number);
	}
}
