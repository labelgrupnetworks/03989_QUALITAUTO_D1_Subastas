<?php

namespace App\Exports;

use App\Models\V5\FgAsigl1;
use App\Services\admin\Auction\BidsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;


class BidsExport extends StringValueBinder implements FromQuery, ShouldQueue, WithChunkReading, ShouldAutoSize, WithHeadings, WithMapping
{
	use Exportable;

	public $filters;

	public function __construct($filters)
	{
		$this->filters = (object)$filters;
	}

	public function query()
	{
		return (new BidsService)->getBidsQueryFromFilters($this->filters);
	}

	//map
	public function map($row): array
	{
		return [
			$row->sub_asigl1,
			$row->ref_asigl1,
			$row->lin_asigl1,
			FgAsigl1::pujrepTypes()[$row->pujrep_asigl1] ?? $row->pujrep_asigl1,
			FgAsigl1::types()[$row->type_asigl1] ?? $row->type_asigl1,
			$row->licit_asigl1,
			$row->imp_asigl1,
			$row->fec_asigl1,
			$row->nom_cli,
			$row->descweb_hces1,
			$row->cod2_cli,
			$row->idorigen_asigl0,
			$row->retirado_asigl0,
			$row->ffin_asigl0
		];
	}

	/**
	 * Define el tamaño del chunk a 1000 registros.
	 */
	public function chunkSize(): int
	{
		return 1000;
	}

	public function headings(): array
	{
		$headings = [];
		$headingsKeys = [
			'sub_asigl1',
			'ref_asigl1',
			'lin_asigl1',
			'pujrep_asigl1',
			'type_asigl1',
			'licit_asigl1',
			'imp_asigl1',
			'fec_asigl1',
			'nom_cli',
			'descweb_hces1',
			'cod2_cli',
			'idorigen_asigl0',
			'retirado_asigl0',
			'ffin_asigl0'
		];

		foreach ($headingsKeys as $key) {
			$headings[] = trans("admin-app.fields.{$key}");
		}

		return $headings;
	}
}
