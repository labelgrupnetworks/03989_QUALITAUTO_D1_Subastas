<?php
# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;

use App\Models\Subasta;
use App\Providers\ToolsServiceProvider;

class Busqueda extends Model
{

  public $page;
  public $itemsPerPage;
        //solo subastas activas y lotes no cerrados, para que no se dupliquen los lotes tipo O con los de P
	public function buscar($text)
	{

            //lower(lotes.DESC_HCES1) LIKE '%' || :text || '%' OR

            //Si busca subastas abiertas y historico o solo subastas abiertas
            //Ordenamos los lotes por tipo de subasta y por si es historico
            if(Config::get('app.search_lots_cerrados') == true){
              $value =  "AND subastas.SUBC_SUB in ('S','H') ";
            }else{
                $value = "AND subastas.SUBC_SUB in ('S')  AND p.cerrado_asigl0 = 'N'";
            }
            $sql = "
                                   SELECT * FROM (
                                   SELECT rownum rn, p.ref_asigl0,implic_hces1,  p.numhces_asigl0, p.impsalhces_asigl0, p.cerrado_asigl0, p.remate_asigl0, p.compra_asigl0, p.imptash_asigl0,p.impres_asigl0,
                                     lotes.orden_hces1, lotes.fac_hces1, lotes.lic_hces1, lotes.lin_hces1, lotes.num_hces1, lotes.descdet_hces1, lotes.desc_hces1,p.imptas_asigl0,
                                    subastas.cod_sub, subastas.des_sub,  subastas.tipo_sub,  subastas.subc_sub,   auc.\"id_auc_sessions\" id_auc_sessions, auc.\"name\" name,
                                   (CASE WHEN p.ffin_asigl0 IS NOT NULL AND p.hfin_asigl0 IS NOT NULL
                                    THEN REPLACE(TO_DATE(TO_CHAR(p.ffin_asigl0, 'DD/MM/YY') || ' ' || p.hfin_asigl0, 'DD/MM/YY HH24:MI:SS'), '-', '/')
                                    ELSE null END) close_at,
                                    NVL(hces1lang.WEBFRIEND_HCES1_LANG, lotes.WEBFRIEND_HCES1) webfriend_hces1,
                                    NVL(hces1lang.TITULO_HCES1_LANG, lotes.titulo_hces1) titulo_hces1
                                    FROM FGASIGL0 p
                                   INNER JOIN FGHCES1 lotes
                                     ON (lotes.SUB_HCES1 = p.SUB_ASIGL0 AND lotes.NUM_HCES1 = p.NUMHCES_ASIGL0 AND lotes.LIN_HCES1 = p.LINHCES_ASIGL0 AND lotes.EMP_HCES1 = :emp)
                                   INNER JOIN FGSUB subastas
                                       ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)

                                   JOIN \"auc_sessions\" auc ON (auc.\"auction\" = subastas.COD_SUB AND auc.\"company\" = :emp)
                                   LEFT JOIN FGHCES1_LANG hces1lang ON (hces1lang.EMP_HCES1_LANG = :emp  AND hces1lang.NUM_HCES1_LANG=lotes.NUM_HCES1 and hces1lang.LIN_HCES1_LANG=lotes.LIN_HCES1 and hces1lang.LANG_HCES1_LANG= :lang)
                                     WHERE p.EMP_ASIGL0     = :emp
                                    AND lower(lotes.TITULO_HCES1) LIKE '%' || :text || '%'
                                    AND lotes.REF_HCES1 >= auc.\"init_lot\"
                                    AND lotes.REF_HCES1 <= auc.\"end_lot\"
                                    $value
                                    order by subc_sub desc, subastas.tipo_sub asc
                                    ) pu
                                     ". Subasta::getOffset($this->page, $this->itemsPerPage);


            $ret= DB::select($sql,
                                   array(
                                       'emp'       => Config::get('app.emp'),
                                       'text'      => strtolower ($text),
                                       'lang' => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale')),
                                       )
                             );


            return $ret;
	}



        /*
        public function buscar($text)
	{
            return DB::select("
                                   SELECT * FROM (
                                   SELECT rownum rn, p.*, subastas.*, lotes.*, cat.*, cat0.*, auc.\"id_auc_sessions\" id_auc_sessions, auc.\"name\" name,
                                   (CASE WHEN p.ffin_asigl0 IS NOT NULL AND p.hfin_asigl0 IS NOT NULL
                                    THEN REPLACE(TO_DATE(TO_CHAR(p.ffin_asigl0, 'DD/MM/YY') || ' ' || p.hfin_asigl0, 'DD/MM/YY HH24:MI:SS'), '-', '/')
                                    ELSE null END) close_at
                                    FROM FGASIGL0 p
                                   INNER JOIN FGHCES1 lotes
                                     ON (lotes.SUB_HCES1 = p.SUB_ASIGL0 AND lotes.REF_HCES1 = p.REF_ASIGL0 AND lotes.EMP_HCES1 = :emp)
                                   INNER JOIN FGSUB subastas
                                       ON (subastas.COD_SUB = p.SUB_ASIGL0 AND subastas.EMP_SUB = :emp)

                                  //Categorias
                                   LEFT JOIN FGORTSEC1 cat
                                       ON (cat.SEC_ORTSEC1 = lotes.SEC_HCES1 AND cat.SUB_ORTSEC1 = p.SUB_ASIGL0 AND cat.EMP_ORTSEC1 = :emp)
                                   LEFT JOIN FGORTSEC0 cat0
                                       ON (cat.LIN_ORTSEC1 = cat0.LIN_ORTSEC0 AND cat0.SUB_ORTSEC0 = p.SUB_ASIGL0 AND cat0.EMP_ORTSEC0 = :emp)
                                   JOIN \"auc_sessions\" auc ON (auc.\"auction\" = subastas.COD_SUB AND auc.\"company\" = :emp)

                                     WHERE p.EMP_ASIGL0     = :emp
                                     AND (replace(replace(lower(lotes.DESC_HCES1),'\b',' '), '\n', ' ') LIKE '%' || :text || '%' OR lower(lotes.TITULO_HCES1) LIKE '%' || :text || '%')
                                    AND lotes.REF_HCES1 >= auc.\"init_lot\"
                                    AND lotes.REF_HCES1 <= auc.\"end_lot\"
                                    ) pu
                                     ". Subasta::getOffset($this->page, $this->itemsPerPage),
                                   array(
                                       'emp'       => Config::get('app.emp'),
                                       'text'      => strtolower ($text),
                                       )
                             );
	}
        */

}
