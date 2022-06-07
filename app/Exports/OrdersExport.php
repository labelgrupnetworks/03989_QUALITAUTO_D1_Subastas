<?php

namespace App\Exports;

use App\Models\V5\FgOrlic;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrdersExport implements FromQuery, WithHeadings, ShouldAutoSize
{

	use Exportable;

	protected $sub_orlic;
	public function __construct($id)
	{
		$this->sub_orlic = $id;

	}

	public function query()
	{
		$orders= FgOrlic::query();
		$orders->JoinAsigl0()
		->JoinCli()
		->JoinFghces1()
		->select("FGORLIC.sub_orlic", "FGASIGL0.ref_asigl0", "FGORLIC.tipop_orlic" , "FGORLIC.licit_orlic", "FGHCES1.descweb_hces1", "FXCLI.nom_cli", "FGORLIC.fec_orlic", "FGORLIC.himp_orlic", "FGORLIC.tel1_orlic")
		->where('FGORLIC.sub_orlic', $this->sub_orlic);

		return $orders;

	}

	public function headings(): array
	{
		return [
			trans('admin-app.fields.rn'),
			trans('admin-app.fields.cod_sub'),
			trans('admin-app.fields.lot.ref_asigl0'),
			trans('admin-app.fields.tipo_orden'),
			trans('admin-app.fields.lot.cod_cli'),
			trans('admin-app.fields.lot.desc_hces1'),
			trans('admin-app.fields.licit_csub'),
			trans('admin-app.fields.lot.fec_asigl1'),
			trans('admin-app.fields.himp_csub'),
			trans('admin-app.fields.tel1_cli'),
		];
	}
}
