<?php

namespace App\Exports;

use App\Models\V5\FgAsigl1;
use App\Models\V5\FgAsigl1_Aux;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PujasExport implements FromQuery, WithHeadings, ShouldAutoSize
{

	use Exportable;

	public function __construct($sub_asigl0)
	{
		$this->sub_asigl0 = $sub_asigl0;
	}

	public function query()
	{
		$pujas = FgAsigl1::joinCli()
				->joinFghces1Asigl0()
				->addselect('SUB_ASIGL0',"ref_asigl0", "lin_asigl1", "imp_asigl1", "fec_asigl1",  "COD_CLI", "RSOC_CLI", 'licit_asigl1', 'cod2_cli', 'TYPE_ASIGL1',  "DESCWEB_HCES1")
				->where('SUB_ASIGL1', $this->sub_asigl0);

		if (Config::get('app.lower_bids', false)) {

			$pujasInferiores = FgAsigl1_Aux::joinFghces1Asigl0()
				->joinCli()
				->select('SUB_ASIGL0', 'REF_ASIGL0', 'LIN_ASIGL1', 'IMP_ASIGL1', 'FEC_ASIGL1', 'COD_CLI', 'RSOC_CLI', 'licit_asigl1', 'cod2_cli','TYPE_ASIGL1', 'DESCWEB_HCES1')
				->where("SUB_ASIGL1", $this->sub_asigl0);

			$pujas->unionAll($pujasInferiores);
		}

		return $pujas->orderBy(3, 'desc');
	}

	public function headings(): array
	{
		return [
			trans('admin-app.fields.rn'),
			trans('admin-app.fields.lot.sub_asigl0'),
			trans('admin-app.fields.lot.ref_asigl0'),
			trans('admin-app.fields.lot.lin_asigl1'),
			trans('admin-app.fields.lot.imp_asigl1'),
			trans('admin-app.fields.lot.fec_asigl1'),
			trans('admin-app.fields.lot.cod_cli'),
			trans('admin-app.fields.lot.rsoc_cli'),
			trans('admin-app.fields.licit_asigl1'),
			trans('admin-app.fields.cod2_cli'),
			trans('admin-app.fields.lot.type_asigl1'),
			trans('admin-app.fields.lot.desc_hces1'),
		];
	}
}
