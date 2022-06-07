<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\V5\FgCsub;

class WinnersExport implements FromQuery, WithHeadings, ShouldAutoSize
{

	use Exportable;

	public function __construct($cod_sub)
    {
        $this->cod_sub = $cod_sub;
	}

	public function query()
	{
		return FgCsub::select('ref_asigl1', 'descweb_hces1', 'licit_csub', 'cod_cli', 'cod2_cli')
			->addselect(\DB::raw("NVL(FGLICIT.RSOC_LICIT,  nom_cli) nom_cli"))
			->addselect('email_cli', 'cif_cli', 'tel1_cli', 'himp_csub')
			->joinWinnerBid()
			->joinAsigl0()
			->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0')
			->joinCli()
			->joinFgLicit()
			->where('sub_csub', $this->cod_sub)
			->orderBy('ref_asigl1');
	}

	public function headings(): array
	{
		return [
			trans('admin-app.fields.rn'),
			trans('admin-app.fields.ref_asigl1'),
			trans('admin-app.fields.descweb_hces1'),
			trans('admin-app.fields.licit_csub'),
			trans('admin-app.fields.cod_cli'),
			trans('admin-app.fields.cod2_cli'),
			trans('admin-app.fields.nom_cli'),
			trans('admin-app.fields.email_cli'),
			trans('admin-app.fields.cif_cli'),
			trans('admin-app.fields.tel1_cli'),
			trans('admin-app.fields.himp_csub')
		];
	}
}
