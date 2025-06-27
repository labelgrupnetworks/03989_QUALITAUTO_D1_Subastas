<?php

namespace App\Services\Payments;

use App\Models\Enums\FgDvc1lSecEnum;
use App\Models\Enums\FgDvc1lTlDvc1lEnum;
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

		$lots = $orderLines->where('tl_dvc1l', FgDvc1lTlDvc1lEnum::PRODUCT->value)
			->sortBy('ref_dvc1l')
			->map(function ($line) {
				return [
					'reference' => $line->ref_dvc1l,
					'award_price' => $line->padj_dvc1l,
					'award_price_format' => ToolsServiceProvider::moneyFormat($line->padj_dvc1l, false, 2),
				];
			})->values();

		//Gastos
		$costLines = $orderLines->where('tl_dvc1l', FgDvc1lTlDvc1lEnum::COST->value);

		$shippingCosts = $costLines->where('sec_dvc1l', FgDvc1lSecEnum::SHIPPING->value)->sum('basea_dvc1l');
		$shippingCostsIva = $costLines->where('sec_dvc1l', FgDvc1lSecEnum::SHIPPING->value)->max('iva_dvc1l');
		$shippingCostsIvaValue = $shippingCosts * ($shippingCostsIva / 100);

		$exportLicense = $costLines->where('sec_dvc1l', FgDvc1lSecEnum::EXPORT_LICENSE->value)->sum('basea_dvc1l');
		$financeCharge = $costLines->where('sec_dvc1l', FgDvc1lSecEnum::FINANCE_CHARGE->value)->sum('basea_dvc1l');

		$commission = $orderLines->where('tl_dvc1l', FgDvc1lTlDvc1lEnum::PRODUCT->value)->sum('basea_dvc1l');
		$ivaCommission = $orderLines->where('tl_dvc1l', FgDvc1lTlDvc1lEnum::PRODUCT->value)->max('iva_dvc1l');
		$ivaCommissionValue = $commission * ($ivaCommission / 100);

		$total = $lots->sum('award_price') + $shippingCosts + $exportLicense + $financeCharge + $commission + $ivaCommissionValue + $shippingCostsIvaValue;

		return [
			'lots' => $lots,
			'commission' => ToolsServiceProvider::moneyFormat($commission, false, 2),
			'commission_iva' => ToolsServiceProvider::moneyFormat($ivaCommissionValue, false, 2),
			'shipping_costs' => ToolsServiceProvider::moneyFormat($shippingCosts, false, 2),
			'shipping_costs_iva' => ToolsServiceProvider::moneyFormat($shippingCostsIvaValue, false, 2),
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
