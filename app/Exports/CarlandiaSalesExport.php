<?php

namespace App\Exports;

use App\Providers\ToolsServiceProvider;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class CarlandiaSalesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
	public function __construct($lots, $isActive)
	{
		$this->active = $isActive;
		$lotsToExcelFormat = $isActive ? $this->activeLots($lots) : $this->awardLots($lots);
		$this->lots = collect($lotsToExcelFormat);
	}

	public function collection()
	{
		return $this->lots;
	}

	public function headings(): array
	{
		return $this->active ? $this->activeHeadings() : $this->awardHeadings();
	}

	private function activeHeadings()
	{
		return [
			'VENDEDOR',
			'OFERTA Nº',
			'DESCRIPCIÓN',
			'TIPO',
			'FECHA PUBLICACIÓN',
			'DÍAS RESTANTES',
			'VALOR DE MERCADO',
			'PRECIO COMPRAR YA',
			'PRECIO MINIMO/RESERVA',
			'PRECIO OFERTADO',
			'OFERTADO vs MERCADO (%)',
			'OFERTADO vs MERCADO (€)',
			'OFERTADO vs COMPRAR YA (%)',
			'OFERTADO vs COMPRAR YA (€)',
			'OFERTADO vs MINIMO/RESERVA (%)',
			'OFERTADO vs MINIMO/RESERVA (€)',
			'# DE OFERTAS',
			'# DE OFERANTES (LEADS)'
		];
	}

	private function activeLots($lots)
	{
		$theme = config('app.theme');
		$moneySimbol = trans("$theme-app.lot.eur");

		return $lots->map(function ($lot) use ($moneySimbol) {

			$poWithCy = $lot->max_imp_asigl1 - $lot->comprar;
			$poWithRm = $lot->max_imp_asigl1 - $lot->reserva;
            $poWithPm = $lot->max_imp_asigl1 - $lot->pc_hces1;

			return [
				$lot->rsoc_cli,
				$lot->ref_asigl0,
				$lot->descweb_hces1,
				$lot->tipo_sub == 'O' ? 'S' : 'VD',
				ToolsServiceProvider::getDateFormat($lot->fecalta_asigl0, 'Y-m-d H:i:s', 'd/m/Y'),
				now()->diffInDays($lot->ffin_asigl0),
				ToolsServiceProvider::moneyFormat($lot->pc_hces1, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->comprar, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->reserva, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->max_imp_asigl1, $moneySimbol),
				ToolsServiceProvider::moneyFormat(($lot->max_imp_asigl1 * 100) / $lot->pc_hces1, '%', 0),
				ToolsServiceProvider::moneyFormat($poWithPm, $moneySimbol),
				ToolsServiceProvider::moneyFormat(($lot->max_imp_asigl1 * 100) / $lot->comprar, '%', 0),
				ToolsServiceProvider::moneyFormat($poWithCy, $moneySimbol),
				ToolsServiceProvider::moneyFormat(($lot->max_imp_asigl1 * 100) / $lot->reserva, '%', 0),
				ToolsServiceProvider::moneyFormat($poWithRm, $moneySimbol),
				$lot->bids ?? 0,
				$lot->licits ?? 0
			];
		});
	}

	private function awardHeadings()
	{
		return [
			'VENDEDOR',
			'OFERTA Nº',
			'DESCRIPCIÓN',
			'TIPO',
			'FECHA VENTA',
			'DÍAS NECESESARIOS PARA VENTA',
			'VALOR DE MERCADO',
			'PRECIO COMPRAR YA',
			'PRECIO MINIMO/RESERVA',
			'PRECIO VENTA',
			'TIPO DE VENTA',
			'VENTA vs MERCADO (%)',
			'VENTA vs MERCADO (€)',
			'VENTA vs COMPRAR YA (%)',
			'VENTA vs COMPRAR YA (€)',
			'VENTA vs MINIMO/RESERVA (%)',
			'VENTA vs MINIMO/RESERVA (€)',
			'# DE OFERTAS',
			'# DE OFERANTES (LEADS)'
		];
	}

	private function awardLots($lots)
	{
		$theme = config('app.theme');
		$moneySimbol = trans("$theme-app.lot.eur");

		return $lots->map(function ($lot) use ($theme, $moneySimbol) {

			$poWithCy = $lot->implic_hces1 - $lot->comprar;
            $poWithRm = $lot->implic_hces1 - $lot->reserva;
            $poWithPm = $lot->implic_hces1 - $lot->pc_hces1;

			return [
				$lot->rsoc_cli,
				$lot->ref_asigl0,
				$lot->descweb_hces1,
				$lot->tipo_sub == 'O' ? 'S' : 'VD',
				ToolsServiceProvider::getDateFormat($lot->fecha_csub, 'Y-m-d H:i:s', 'd/m/Y'),
				(new Carbon($lot->fecalta_asigl0))->diffInDays($lot->fecha_csub),
				ToolsServiceProvider::moneyFormat($lot->pc_hces1, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->comprar ?? 0, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->reserva, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->implic_hces1, $moneySimbol),
				mb_strtoupper(trans("$theme-app.lot.pujrep_$lot->pujrep_asigl1")),
				ToolsServiceProvider::moneyFormat(($lot->implic_hces1 * 100) / $lot->pc_hces1, '%', 0),
				ToolsServiceProvider::moneyFormat($poWithPm, $moneySimbol),
				ToolsServiceProvider::moneyFormat(($lot->implic_hces1 * 100) / $lot->comprar, '%', 0),
				ToolsServiceProvider::moneyFormat($poWithCy, $moneySimbol),
				ToolsServiceProvider::moneyFormat(($lot->implic_hces1 * 100) / $lot->reserva, '%', 0),
				ToolsServiceProvider::moneyFormat($poWithRm, $moneySimbol),
				$lot->bids ?? 0,
				$lot->licits ?? 0
			];
		});
	}

	public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT,
            'L' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT,
            'N' => NumberFormat::FORMAT_TEXT,
            'O' => NumberFormat::FORMAT_TEXT,
            'P' => NumberFormat::FORMAT_TEXT,
            'Q' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
