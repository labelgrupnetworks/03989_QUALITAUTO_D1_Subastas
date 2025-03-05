<?php

namespace App\Exports\custom\valoralia;

use App\Exports\custom\BaseCustomExport;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgSub;
use DateTime;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LotAuctionsExport extends BaseCustomExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
	protected $name = 'Subastas';
	protected $downloadName = 'subastas.xlsx';

	public function query()
	{
		return FgAsigl0::query()
			->select([
				'fgsub.cod_sub',
				'fgsub.des_sub',
				'fgsub.subc_sub',
				'fgsub.dfec_sub',
				'fgsub.hfec_sub',
				'fgsub.panel_sub',
				'fgasigl0.ref_asigl0',
				'fxcli.nom_cli',
				'fgasigl0.impsalhces_asigl0',
				'fghces1.fac_hces1',
				'fghces1.cob_hces1',
			])
			->addSelect(DB::raw('(SELECT max(imp_asigl1) FROM fgasigl1 WHERE fgasigl1.ref_asigl1 = fgasigl0.ref_asigl0 AND fgasigl1.sub_asigl1 = fgasigl0.sub_asigl0 AND fgasigl1.emp_asigl1 = fgasigl0.emp_asigl0) as max_bid'))
			->addSelect(DB::raw('(SELECT max(himp_orlic) FROM fgorlic WHERE fgorlic.ref_orlic = fgasigl0.ref_asigl0 AND fgorlic.sub_orlic = fgasigl0.sub_asigl0 AND fgorlic.emp_orlic = fgasigl0.emp_asigl0) as max_orlic'))
			->joinFghces1Asigl0()
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->leftJoinOwnerWithHces1()
			->where('fgsub.subc_sub', '!=', FgSub::SUBC_SUB_ADMINISITRADOR)
			->orderBy('fgsub.hfec_sub', 'desc')
			->orderBy('fgsub.cod_sub', 'asc')
			->orderBy('fgasigl0.ref_asigl0', 'asc');
	}

	public function map($lot): array
	{
		return [
			$lot->cod_sub,
			$lot->des_sub,
			(new FgSub)->getSubcSubTypes()[strtoupper($lot->subc_sub)], //modificar por enum en L9
			Date::dateTimeToExcel(new DateTime($lot->dfec_sub)),
			Date::dateTimeToExcel(new DateTime($lot->hfec_sub)),
			$lot->panel_sub == 'S' ? 'Sí' : 'No',
			$lot->ref_asigl0,
			$lot->nom_cli,
			!empty($lot->impsalhces_asigl0) ? $lot->impsalhces_asigl0 : '0',
			max($lot->max_bid, $lot->max_orlic) ?? '0',
			$lot->fac_hces1 == 'S' ? 'Sí' : 'No',
			$lot->cob_hces1 == 'S' ? 'Sí' : 'No',
		];
	}

	public function headings(): array
	{
		return [
			'Código Subasta', 'Nombre Subasta', 'Estado Subasta', 'Fecha Apertura', 'Fecha Cierre', 'Informe Cierre', 'Lote', 'Propietario', 'Precio Salida', 'Puja Máxima', 'Facturado', 'Cobrado'
		];
	}

	public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_CURRENCY_EUR,
            'J' => NumberFormat::FORMAT_CURRENCY_EUR,
			'H' => NumberFormat::FORMAT_TEXT,
			'H' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
