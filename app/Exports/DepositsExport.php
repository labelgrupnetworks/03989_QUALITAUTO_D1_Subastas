<?php

namespace App\Exports;

use App\Models\V5\FgDeposito;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class DepositsExport extends StringValueBinder implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithCustomValueBinder
{
	use Exportable;

	private $query;

	function __construct($query)
	{
		$this->query = $query;
	}

	public function query()
	{
		return $this->query;
	}

	/**
	 * @param FgDeposito $deposit
	 */
	public function map($deposit): array
	{
		return [
			$deposit->rn,
			$deposit->sub_deposito,
			$deposit->ref_deposito,
			$deposit->rsoc_cli,
			$deposit->nom_cli,
			$deposit->estado,
			$deposit->importe_deposito,
			$deposit->fecha_deposito,
			$deposit->cli_deposito,
		];
	}

	public function headings(): array
	{
		return [
			trans("admin-app.fields.rn"),
			trans("admin-app.fields.sub_deposito"),
			trans("admin-app.fields.ref_deposito"),
			trans("admin-app.fields.rsoc_cli"),
			trans('admin-app.fields.nom_cli'),
			trans('admin-app.fields.estado_deposito'),
			trans("admin-app.fields.importe_deposito"),
			trans("admin-app.fields.fecha_deposito"),
			trans("admin-app.fields.cli_deposito"),
		];
	}
}
