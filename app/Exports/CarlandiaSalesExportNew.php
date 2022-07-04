<?php

namespace App\Exports;

use App\Providers\ToolsServiceProvider;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CarlandiaSalesExportNew implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithEvents
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

		$primeraFila = ['OFERTA', '', '', '', 'TIPO', 'VALOR DE MERCADO', 'PRECIOS  DE LA OFERTA FIJADOS POR EL VENDEDOR', '', 'PRECIO OFERTADO POR EL COMPRADOR', 'OFERTADO vs VALOR DE MERCADO', '', 'OFERTADO vs COMPRAR YA', '', 'OFERTADO vs RESERVA / MÍNIMO', '','Nº OFERTAS', 'Nº OFERANTES (LEADS)'];
		$segundaFila = ['Nº', 'DESCRIPCICIÓN', 'FECHA INICIO', 'DÍAS RESTANTES', '', '', 'COMPRAR YA', 'RESERVA O MÍNIMO', '', '%', '∆', '%', '∆', '%', '∆', '', ''];

		return [
			$primeraFila,
			$segundaFila
		];

		return [
			/* 'PROPIETARIO', */
			'Nº DE OFERTA',
			/* 'MATRÍCULA', */
			'DESCRIPCIÓN',
			'TIPO',
			'FECHA INICIO',
			'DÍAS RESTANTES',
			'COMPRAR YA',
			'RESERVA / MINIMO',
			'VALO DE MERCADO',
			'PRECIO OFERTADO POR EL COMPRADOR',
			'PRECIO OFERTADO RESPECTO COMPRAR YA %',
			'PRECIO OFERTADO RESPECTO RESERVA/MERCADO %',
			'PRECIO OFERTADO RESPECTO COMPRAR YA €',
			'PRECIO OFERTADO RESPECTO RESERVA/MERCADO €',
			'Nº DE OFERTAS',
			'LEADS'
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
				$lot->ref_asigl0,
				$lot->descweb_hces1,
				ToolsServiceProvider::getDateFormat($lot->fecalta_asigl0, 'Y-m-d H:i:s', 'd/m/Y'),
				now()->diffInDays($lot->ffin_asigl0),
				$lot->tipo_sub == 'O' ? 'S' : 'VD',
				$lot->pc_hces1,//ToolsServiceProvider::moneyFormat($lot->pc_hces1, $moneySimbol),
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


			return [
				/* $lot->rsoc_cli, */
				$lot->ref_asigl0,
				/* $lot->matricula, */
				$lot->descweb_hces1,
				$lot->tipo_sub == 'O' ? 'S' : 'VD',
				ToolsServiceProvider::getDateFormat($lot->fecalta_asigl0, 'Y-m-d H:i:s', 'd/m/Y'),
				now()->diffInDays($lot->ffin_asigl0),
				ToolsServiceProvider::moneyFormat($lot->comprar, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->reserva, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->pc_hces1, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->max_imp_asigl1, $moneySimbol),
				ToolsServiceProvider::moneyFormat(($lot->max_imp_asigl1 * 100) / $lot->comprar, '%', 0),
				ToolsServiceProvider::moneyFormat(($lot->max_imp_asigl1 * 100) / $lot->reserva, '%', 0),
				ToolsServiceProvider::moneyFormat($poWithCy, $moneySimbol),
				ToolsServiceProvider::moneyFormat($poWithRm, $moneySimbol),
				$lot->bids ?? 0,
				$lot->licits ?? 0
			];
		});
	}

	private function awardHeadings()
	{

		// '∆'
		$primeraFila = ['OFERTA', '', '', '', 'TIPO', 'VALOR DE MERCADO', 'PRECIOS  DE LA OFERTA FIJADOS POR EL VENDEDOR', '', 'PRECIO OFERTADO POR EL COMPRADOR', 'OFERTADO vs VALOR DE MERCADO', '', 'OFERTADO vs COMPRAR YA', '', 'OFERTADO vs RESERVA / MÍNIMO', '','Nº OFERTAS', 'Nº OFERANTES (LEADS)'];
		$segundaFila = ['Nº', 'DESCRIPCICIÓN', 'FECHA INICIO', 'DÍAS RESTANTES', '', '', 'COMPRAR YA', 'RESERVA O MÍNIMO', '', '%', '∆', '%', '∆', '%', '∆', '', ''];

		return [
			$primeraFila,
			$segundaFila
		];

		return [
			/* 'PROPIETARIO', */
			'Nº DE OFERTA',
			/* 'MATRÍCULA', */
			'DESCRIPCIÓN',
			'TIPO',
			'FECHA VENTA',
			'DÍAS NCESESARIOS PARA VENTA',
			'COMPRAR YA',
			'RESERVA / MINIMO',
			'VALOR DE MERCADO',
			'PRECIO VENTA',
			'TIPO DE VENTA',
			'PRECIO VENTA RESPECTO PRECIO MERCADO %',
			'PRECIO VENTA RESPECTO COMPRAR YA %',
			'PRECIO VENTA RESPECTO RESERVA/MERCADO',
			'Nº DE OFERTAS',
			'LEADS'
		];
	}

	private function awardLots($lots)
	{
		$theme = config('app.theme');
		$moneySimbol = trans("$theme-app.lot.eur");

		return $lots->map(function ($lot) use ($theme, $moneySimbol) {

			return [
				/* $lot->rsoc_cli, */
				$lot->ref_asigl0,
				/* $lot->matricula, */
				$lot->descweb_hces1,
				$lot->tipo_sub == 'O' ? 'S' : 'VD',
				ToolsServiceProvider::getDateFormat($lot->fecha_csub, 'Y-m-d H:i:s', 'd/m/Y'),
				(new Carbon($lot->fecalta_asigl0))->diffInDays($lot->fecha_csub),
				ToolsServiceProvider::moneyFormat($lot->comprar ?? 0, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->reserva, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->pc_hces1, $moneySimbol),
				ToolsServiceProvider::moneyFormat($lot->implic_hces1, $moneySimbol),
				mb_strtoupper(trans("$theme-app.lot.pujrep_$lot->pujrep_asigl1")),
				ToolsServiceProvider::moneyFormat(($lot->implic_hces1 * 100) / $lot->pc_hces1, '%', 0),
				ToolsServiceProvider::moneyFormat(($lot->implic_hces1 * 100) / $lot->comprar, '%', 0),
				ToolsServiceProvider::moneyFormat(($lot->implic_hces1 * 100) / $lot->reserva, '%', 0),
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
            'F' => NumberFormat::FORMAT_CURRENCY_EUR,
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

	public function registerEvents(): array
    {
        return [
            AfterSheet::class  => function(AfterSheet $event) {

				//merge de celdas horizontales y verticales
				$event->sheet->mergeCells('A1:D1');
				$event->sheet->mergeCells('E1:E2');
				$event->sheet->mergeCells('F1:F2');
				$event->sheet->mergeCells('G1:H1');
				$event->sheet->mergeCells('I1:I2');
				$event->sheet->mergeCells('J1:K1');
				$event->sheet->mergeCells('L1:M1');
				$event->sheet->mergeCells('N1:O1');
				$event->sheet->mergeCells('P1:P2');
				$event->sheet->mergeCells('Q1:Q2');

				//Alinear verticalmente y horizontalmente textos en una celda, wraptext permite texto en dos o más lineas
				$event->sheet->getDelegate()->getStyle('A1:Q2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
				$event->sheet->getDelegate()->getStyle('A1:Q2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setWrapText(true);

				$columns = ['F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q'];

				//Para añadir un ancho personalizado primero es necesario quitar el autosize
				$event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(false);
				$event->sheet->getDelegate()->getColumnDimension('B')->setWidth(60);

				foreach ($columns as $leterColumn) {
					$event->sheet->getDelegate()->getColumnDimension($leterColumn)->setAutoSize(false);
					$event->sheet->getDelegate()->getColumnDimension($leterColumn)->setWidth(12);
				}

				//En principio la segunda opción realizar autoHeight, pero no funciona. Forzamos a 50.
				$event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(50);
				//$event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(-1);

				//Obtengo el numero de la ultima fila
				$maxRow = $event->sheet->getDelegate()->getHighestRow();

				//Centro el texto de todas las celdas menos B
				$event->sheet->getDelegate()->getStyle("A3:A$maxRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
				$event->sheet->getDelegate()->getStyle("C3:Q$maxRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            },
        ];
    }

}
