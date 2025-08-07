<?php

namespace App\Services\Content;

use App\Services\User\UserAddressService;
use App\Support\Localization;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @todo
 * Esta clase es utilizada solamente por Gutinvest, en la blade blocs.blade.php
 * Seguramente se puedan añadir las consultas a web_blocs y eliminar estos metodos.
 *
 * En caso de no poder eliminarlos, se deberia crear las consultas desde los modelos.
 */
class BlocSectorService
{
	public function getActiveBlocs($type = null, $subc = 'S')
	{
		$where = "";
		if (!empty($type)) {
			$where = " AND SUB.TIPO_SUB = :type ";
		}

		$sql = "SELECT COD_BLOC, DES_BLOC, COUNT(COD_BLOC) AS NUM_SUB FROM (
                    SELECT COD_BLOC, NVL(BLOC_LANG.DES_BLOC_LANG,BLOC.DES_BLOC) AS DES_BLOC FROM FGBLOC BLOC
                    JOIN FGSUBBLOC SBLOC ON  SBLOC.EMP_SUBBLOC = BLOC.EMP_BLOC AND SBLOC.COD_SUBBLOC = BLOC.COD_BLOC
                    JOIN FGSUB SUB ON SUB.EMP_SUB = SBLOC.EMP_SUBBLOC AND SUB.COD_SUB = SBLOC.SUB_SUBBLOC
                    JOIN \"auc_sessions\" AUC ON (AUC.\"auction\" = SUB.COD_SUB AND AUC.\"company\" =  SUB.EMP_SUB)
                    LEFT JOIN FGBLOC_LANG BLOC_LANG ON BLOC_LANG.EMP_BLOC_LANG = BLOC.EMP_BLOC AND BLOC_LANG.COD_BLOC_LANG = BLOC.COD_BLOC AND BLOC_LANG.LANG_BLOC_LANG = :lang
                    WHERE EMP_BLOC = :emp AND
                    AUC.\"start\" IS NOT NULL AND
                    AUC.\"end\" IS NOT NULL AND
                    AUC.\"init_lot\" IS NOT NULL AND
                    AUC.\"end_lot\" IS NOT NULL AND
                    SUB.SUBC_SUB = :subc
                    $where

                )

                GROUP BY COD_BLOC, DES_BLOC
                ORDER BY COD_BLOC ASC

                ";

		$bindings = array(
			'emp'      => Config::get('app.emp'),
			'lang'      => Localization::getLocaleComplete(),
			'subc'	=> $subc


		);
		if (!empty($type)) {
			$bindings['type'] = $type;
		}
		return DB::select($sql, $bindings);
	}
	//se supone que solo pueden tener un cod_subsector
	public function getAuctionBlocs($type = null, $subc = 'S')
	{
		$where = "";
		if (!empty($type)) {
			$where = " AND sub.TIPO_SUB = :type ";
		}

		//en historico ordenamos las nuevas antes
		$order_by = "orders_end ASC";
		if ($subc == 'H') {
			$order_by = "session_end DESC";
		}

		$sql = "SELECT COD_SUBBLOC,COD_SUBSECTOR,NUM_LOTS,cod_sub,PAIS_SUB, des_sub, orders_start, orders_end,  tipo_sub, reference,name, id_auc_sessions, session_start, session_end, emp_sub, subc_sub,expofechas_sub,expohorario_sub,expolocal_sub,sesfechas_sub,seshorario_sub,seslocal_sub,
            emp_sub ||  '_' || cod_sub || '_' ||    reference as file_code,upcatalogo,uppdfadjudicacion,uppreciorealizado
            FROM (
                SELECT SBLOC.COD_SUBBLOC, max(SSECTOR.COD_SUBSECTOR) COD_SUBSECTOR,sub.PAIS_SUB PAIS_SUB ,sub.COD_SUB cod_sub, sub.EMP_SUB, sub.SUBC_SUB, sub.tipo_sub,COUNT(lotes.REF_ASIGL0) AS NUM_LOTS,
                       NVL(fgsublang.DES_SUB_LANG,  sub.DES_SUB) des_sub,

                       NVL(fgsublang.EXPOFECHAS_SUB_LANG,  sub.expofechas_sub) expofechas_sub,
                       NVL(fgsublang.EXPOHORARIO_SUB_LANG,  sub.expohorario_sub) expohorario_sub,
                       NVL(fgsublang.EXPOLOCAL_SUB_LANG,  sub.expolocal_sub) expolocal_sub,
                       NVL(fgsublang.SESFECHAS_SUB_LANG,  sub.sesfechas_sub) sesfechas_sub,
                       NVL(fgsublang.SESHORARIO_SUB_LANG,  sub.seshorario_sub) seshorario_sub,
                       NVL(fgsublang.SESLOCAL_SUB_LANG,  sub.seslocal_sub) seslocal_sub,
                       NVL(auclang.\"name_lang\",  auc.\"name\") name,
                       auc.\"reference\" reference ,auc.\"id_auc_sessions\" id_auc_sessions, auc.\"start\" session_start, auc.\"end\" session_end,
                       auc.\"orders_start\" as orders_start,  auc.\"orders_end\" as orders_end,auc.\"upCatalogo\" upcatalogo,auc.\"uppdfadjudicacion\" uppdfadjudicacion, auc.\"upPrecioRealizado\" uppreciorealizado
                FROM FGSUB sub
                JOIN FGASIGL0 lotes ON (sub.COD_SUB = lotes.SUB_ASIGL0 AND lotes.EMP_ASIGL0 = :emp)
                JOIN \"auc_sessions\" auc ON (auc.\"auction\" = sub.cod_sub AND auc.\"company\" = :emp)
                LEFT JOIN \"auc_sessions_lang\" auclang ON (auclang.\"auction_lang\" = sub.cod_sub and auclang.\"company_lang\" = :emp and auclang.\"lang_auc_sessions_lang\" = :lang)
                LEFT JOIN FGSUB_LANG fgsublang ON (sub.EMP_SUB = fgsublang.EMP_SUB_LANG AND sub.COD_SUB = fgsublang.COD_SUB_LANG AND  fgsublang.LANG_SUB_LANG = :lang)
                JOIN FGSUBBLOC  SBLOC on SBLOC.EMP_SUBBLOC = sub.EMP_SUB AND SBLOC.SUB_SUBBLOC = sub.COD_SUB
                LEFT JOIN FGSUBSECTOR  SSECTOR on SSECTOR.EMP_SUBSECTOR = sub.EMP_SUB AND SSECTOR.SUB_SUBSECTOR = sub.COD_SUB

                WHERE
                    auc.\"start\" IS NOT NULL AND
                    auc.\"end\" IS NOT NULL AND
                    auc.\"init_lot\" <= lotes.REF_ASIGL0 AND
                    auc.\"end_lot\" >= lotes.REF_ASIGL0 AND
                    sub.SUBC_SUB = '$subc' AND
                    lotes.OCULTO_ASIGL0 = 'N' AND
                    sub.EMP_SUB = :emp
                    $where
                GROUP BY SBLOC.COD_SUBBLOC, sub.COD_SUB,sub.PAIS_SUB, NVL(fgsublang.DES_SUB_LANG,  sub.DES_SUB), sub.EMP_SUB, sub.SUBC_SUB,NVL(fgsublang.EXPOFECHAS_SUB_LANG,  sub.expofechas_sub),NVL(fgsublang.SESFECHAS_SUB_LANG,  sub.sesfechas_sub),NVL(fgsublang.EXPOLOCAL_SUB_LANG,  sub.expolocal_sub),NVL(fgsublang.EXPOHORARIO_SUB_LANG,  sub.expohorario_sub),NVL(fgsublang.SESFECHAS_SUB_LANG,  sub.sesfechas_sub),NVL(fgsublang.SESHORARIO_SUB_LANG,  sub.seshorario_sub),NVL(fgsublang.SESLOCAL_SUB_LANG,  sub.seslocal_sub), auc.\"orders_start\",  auc.\"orders_end\", sub.tipo_sub,NVL(auclang.\"name_lang\",  auc.\"name\"), auc.\"id_auc_sessions\",auc.\"reference\", auc.\"start\" , auc.\"end\",auc.\"upCatalogo\",auc.\"uppdfadjudicacion\",auc.\"upPrecioRealizado\"
               order by $order_by
            )
                ";

		$bindings = array(
			'emp' => Config::get('app.emp'),
			'lang' => Localization::getLocaleComplete()
		);
		if (!empty($type)) {
			$bindings['type'] = $type;
		}
		$auctions = DB::select($sql, $bindings);
		$blocs_auctions = array();

		$countries = (new UserAddressService)->getCountries();

		foreach ($auctions as $auction) {

			$auction->country_name = Str::title($countries->where('cod_paises', $auction->pais_sub)->first()->des_paises ?? null);

			if (empty($blocs_auctions[$auction->cod_subbloc])) {
				$blocs_auctions[$auction->cod_subbloc] = array();
			}
			//CREAMOS EL INDICE ALL PARA QUE NO SE REPITAN, POR ESO USAMOS EL id_auc_sessions COMO INDICE
			$blocs_auctions['ALL'][$auction->id_auc_sessions] = $auction;
			$blocs_auctions[$auction->cod_subbloc][$auction->id_auc_sessions] = $auction;
		}

		return $blocs_auctions;
	}

	public function getSectors(){

        $sql = "SELECT COD_SECTOR, NVL(DES_SECTOR_LANG,DES_SECTOR) DES_SECTOR FROM FGSECTOR SEC
                LEFT JOIN FGSECTOR_LANG SEC_LANG ON SEC_LANG.EMP_SECTOR_LANG = SEC.EMP_SECTOR AND  SEC_LANG.COD_SECTOR_LANG =SEC.COD_SECTOR AND SEC_LANG.LANG_SECTOR_LANG = :lang
                WHERE EMP_SECTOR = :emp";

        $bindings = array(
            'emp'      => Config::get('app.emp'),
            'lang'     => Localization::getLocaleComplete()

        );
        $sectors_tmp = DB::select($sql,$bindings);
        $sectors = array();
        foreach($sectors_tmp as $sector){
            $sectors[$sector->cod_sector] = $sector->des_sector;
        }

        return $sectors;
    }
}
