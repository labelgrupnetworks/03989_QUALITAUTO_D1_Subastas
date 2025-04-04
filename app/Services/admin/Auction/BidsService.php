<?php

namespace App\Services\admin\Auction;

use App\Models\V5\FgAsigl1;
use App\Models\V5\FgAsigl1_Aux;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BidsService
{
	public function getBidsQueryFromFilters($filters)
	{
		return $this->pujasInstance()
			->select([
				'sub_asigl1',
				"ref_asigl1",
				"lin_asigl1",
				'pujrep_asigl1',
				'type_asigl1',
				"licit_asigl1",
				"imp_asigl1",
				"fec_asigl1",
				"hora_asigl1",
				"fxcli.nom_cli",
				"fghces1.descweb_hces1",
				"cod2_cli",
				"fgasigl0.idorigen_asigl0",
				"fgasigl0.retirado_asigl0",
				"fgasigl0.ffin_asigl0",
				"fgasigl0.hfin_asigl0"
			])
			//asigl0_aux -> nos sirve para saber el tipo de puja, si es aux o no
			->when(Config::get('app.lower_bids', false), function ($query) {
				$query->addSelect('asigl0_aux');
			})
			->when($filters->sub_asigl1 ?? null, function ($query, $sub_asigl1) {
				$query->where('sub_asigl1', $sub_asigl1);
			})
			->when($filters->ref_asigl1 ?? null, function ($query, $ref_asigl1) {
				$query->where('ref_asigl1', $ref_asigl1);
			})
			->when($filters->lin_asigl1 ?? null, function ($query, $lin_asigl1) {
				$query->where('lin_asigl1', $lin_asigl1);
			})
			->when($filters->licit_asigl1 ?? null, function ($query, $licit_asigl1) {
				$query->where('licit_asigl1', $licit_asigl1);
			})
			->when($filters->imp_asigl1 ?? null, function ($query, $imp_asigl1) {
				$query->where('imp_asigl1', $imp_asigl1);
			})
			->when($filters->idorigen_asigl0 ?? null, function ($query, $idorigen_asigl0) {
				$query->where('upper(idorigen_asigl0)', 'like', "%" . mb_strtoupper($idorigen_asigl0) . "%");
			})
			->when($filters->nom_cli ?? null, function ($query, $nom_cli) {
				$query->where('upper(nom_cli)', 'like', "%" . mb_strtoupper($nom_cli) . "%");
			})
			->when($filters->descweb_hces1 ?? null, function ($query, $descweb_hces1) {
				$query->where('upper(descweb_hces1)', 'like', "%" . mb_strtoupper($descweb_hces1) . "%");
			})
			->when($filters->cod2_cli ?? null, function ($query, $cod2_cli) {
				$query->where('upper(cod2_cli)', 'like', "%" . mb_strtoupper($cod2_cli) . "%");
			})
			->when($filters->pujrep_asigl1 ?? null, function ($query, $pujrep_asigl1) {
				$query->where('pujrep_asigl1', $pujrep_asigl1);
			})
			->when($filters->type_asigl1 ?? null, function ($query, $type_asigl1) {
				$query->where('type_asigl1', $type_asigl1);
			})
			->when($filters->retirado_asigl0 ?? null, function ($query, $retirado_asigl0) {
				$query->where('retirado_asigl0', $retirado_asigl0);
			})
			->when($filters->ffin_asigl0 ?? null, function ($query, $ffin_asigl0) {
				$query->where('ffin_asigl0', $ffin_asigl0);
			})
			->when($filters->from_fec_asigl1 ?? null, function ($query, $from_fec_asigl1) {
				$query->where('fec_asigl1', '>=', ToolsServiceProvider::getDateFormat($from_fec_asigl1, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			})
			->when($filters->to_fec_asigl1 ?? null, function ($query, $to_fec_asigl1) {
				$query->where('fec_asigl1', '<=', ToolsServiceProvider::getDateFormat($to_fec_asigl1, 'Y-m-d', 'Y/m/d') . ' 23:59:59');
			})
			->orderby($filters->order_bids ?? 'fec_asigl1', $filters->order_bids_dir ?? 'desc');
	}

	private function pujasInstance()
	{
		//lower_bids -> inbusa y soporteconcursal
		if (Config::get('app.lower_bids', false)) {
			$asigl1_aux = FgAsigl1_Aux::withoutGlobalScopes(['emp'])->select('emp_asigl1', 'sub_asigl1', 'ref_asigl1', 'lin_asigl1', 'licit_asigl1', 'imp_asigl1', 'fec_asigl1', 'pujrep_asigl1', 'hora_asigl1', 'type_asigl1', 'usr_update_asigl1', 'date_update_asigl1', 'type_update_asigl1', "'SI' as asigl0_aux");
			$asigl1 = FgAsigl1::withoutGlobalScopes(['emp'])->select('emp_asigl1', 'sub_asigl1', 'ref_asigl1', 'lin_asigl1', 'licit_asigl1', 'imp_asigl1', 'fec_asigl1', 'pujrep_asigl1', 'hora_asigl1', 'type_asigl1', 'usr_update_asigl1', 'date_update_asigl1', 'type_update_asigl1', "'NO' as asigl0_aux");

			$pujasSql = $asigl1->unionAll($asigl1_aux)->toSql();

			#Se añade en la query el Left Join con FXCLI para que aparezcan todos las pujas
			return DB::table(DB::raw("($pujasSql) PUJAS"))
				->where('emp_asigl1', config('app.emp'))
				->join("FGLICIT", "EMP_LICIT = EMP_ASIGL1 AND SUB_LICIT = SUB_ASIGL1 AND COD_LICIT = LICIT_ASIGL1 ")
				->leftjoin("FXCLI", "GEMP_CLI = '" . Config::get("app.gemp") . "' AND COD_CLI = CLI_LICIT")
				->join("FGASIGL0", "EMP_ASIGL0 = EMP_ASIGL1 AND SUB_ASIGL0 = SUB_ASIGL1 AND REF_ASIGL0 = REF_ASIGL1 ")
				->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
		}

		#Se añade en la query el Left Join con FXCLI para que aparezcan todos las pujas
		return FgAsigl1::query()
			->leftJoinCli()
			->joinFghces1Asigl0();
	}
}
