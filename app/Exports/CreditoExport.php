<?php

namespace App\Exports;

use App\Models\V5\FgCreditoSub;
use App\Providers\ToolsServiceProvider;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CreditoExport implements FromQuery, WithHeadings, ShouldAutoSize
{

	use Exportable;

	public function __construct($cli_creditosub, $sub_creditosub, $fecha_creditosub)
    {
		$this->cli_creditosub = $cli_creditosub;
		$this->sub_creditosub = $sub_creditosub;
		$this->fecha_creditosub = $fecha_creditosub;
	}

	public function query()
	{

		$fgCreditoSubs = FgCreditoSub::query();
		if (!empty($this->cli_creditosub)) {
			$fgCreditoSubs->where([
				['upper(cli_creditosub)', 'like',  "%" . mb_strtoupper($this->cli_creditosub) . "%", 'or'],
				['upper(rsoc_cli)', 'like',  "%" . mb_strtoupper($this->cli_creditosub) . "%", 'or'],
			]);
		}
		if ($this->sub_creditosub) {
			$fgCreditoSubs->where('sub_creditosub', '=', $this->sub_creditosub);
		}
		if ($this->fecha_creditosub) {
			$fgCreditoSubs->where('fecha_creditosub', '>=', ToolsServiceProvider::getDateFormat($this->fecha_creditosub, 'Y-m-d', 'Y/m/d'));
		}

		$fgCreditoSubs = $fgCreditoSubs
			->select('FGCREDITOSUB.CLI_CREDITOSUB', 'FXCLI.RSOC_CLI', 'FGCREDITOSUB.SUB_CREDITOSUB', 'FGCREDITOSUB.ACTUAL_CREDITOSUB', 'FGCREDITOSUB.NUEVO_CREDITOSUB', 'FXCLI.RIES_CLI', 'FXCLI.RIESMAX_CLI', 'FGCREDITOSUB.FECHA_CREDITOSUB')
			->join('FXCLI', 'FXCLI.COD_CLI = FGCREDITOSUB.CLI_CREDITOSUB')
			->join('FXCLIWEB', 'FXCLIWEB.GEMP_CLIWEB = FXCLI.GEMP_CLI AND FXCLIWEB.EMP_CLIWEB = FGCREDITOSUB.EMP_CREDITOSUB AND FXCLI.COD_CLI = FXCLIWEB.COD_CLIWEB')
			->orderBy("fecha_creditosub", "desc");

		return $fgCreditoSubs;
	}

	public function headings(): array
    {
        return [
			trans("admin-app.fields.rn"),
			trans("admin-app.fields.cli_creditosub"),
			trans("admin-app.fields.cli_creditosub"),
			trans("admin-app.fields.sub_creditosub"),
			trans("admin-app.fields.actual_creditosub"),
			trans("admin-app.fields.nuevo_creditosub"),
			trans("admin-app.fields.ries_cli"),
			trans("admin-app.fields.riesmax_cli"),
			trans("admin-app.fields.fecha_creditosub")
        ];
	}

}
