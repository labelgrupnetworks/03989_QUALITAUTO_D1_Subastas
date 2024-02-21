<?php

namespace App\Models;

/**
 * Description of Amedida
 *
 * @author LABEL-RSANCHEZ
 */

use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class Amedida
{
	public static function indice($cod_sub, $id_aucsession)
	{
		$completeLang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));

		$params = array(
			'emp'       =>  Config::get('app.emp'),
			'cod_sub'   =>  $cod_sub,
			'id_aucsession'   =>  $id_aucsession,
			'completeLang' => $completeLang
		);

		$sql = "select SUBIND.*, NVL(FGSUBIND_LANG.DES_SUBIND_LANG, SUBIND.DES_SUBIND) as DES_SUBIND, NVL(FGSUBIND_LANG.DESAUX_SUBIND_LANG, SUBIND.DESAUX_SUBIND) as DESAUX_SUBIND
					from FGSUBIND SUBIND
					left join FGSUBIND_LANG
						ON SUBIND.EMP_SUBIND = FGSUBIND_LANG.EMP_SUBIND_LANG AND SUBIND.SUB_SUBIND = FGSUBIND_LANG.SUB_SUBIND_LANG and SUBIND.SESION_SUBIND = FGSUBIND_LANG.SESION_SUBIND_LANG  and SUBIND.LIN_SUBIND = FGSUBIND_LANG.LIN_SUBIND_LANG and FGSUBIND_LANG.LANG_SUBIND_LANG = :completeLang
					join \"auc_sessions\"
						ON \"auc_sessions\".\"company\" = SUBIND.EMP_SUBIND AND \"auc_sessions\".\"auction\" =  SUBIND.SUB_SUBIND
					AND \"auc_sessions\".\"reference\" = SUBIND.SESION_SUBIND
					where EMP_SUBIND = :emp AND   \"auc_sessions\".\"id_auc_sessions\"= :id_aucsession AND  SUB_SUBIND = :cod_sub ORDER BY ORDEN_SUBIND";
		//ahora no va por id_auc_session directamente
		// $sql ="select * from FGSUBIND  where EMP_SUBIND = :emp AND   SESION_SUBIND= :id_aucsession AND  SUB_SUBIND = :cod_sub ORDER BY DREF_SUBIND,NIVEL_SUBIND,LIN_SUBIND";

		$sesiones = DB::select($sql, $params);
		return $sesiones;
	}
}
