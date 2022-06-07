<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;

class Category extends Model
{
     public $emp ;
     public $gemp ;

    public function __construct()
    {
        $this->emp = Config::get('app.emp');
        $this->gemp = Config::get('app.gemp');
    }

    public function getSecciones($cod_sec){


              $sql = "select FXTSEC.cod_tsec cod_tsec, NVL(FXTSEC_LANG.DES_TSEC_LANG, FXTSEC.DES_TSEC) des_tsec, tipo_tsec
                    from FXTSEC
                    join fxsec on (fxtsec.cod_tsec = fxsec.tsec_sec and FXTSEC.GEMP_TSEC = fxsec.GEMP_SEC)
                    left join FXTSEC_LANG on (FXTSEC_LANG.COD_TSEC_LANG = FXTSEC.COD_TSEC and FXTSEC_LANG.GEMP_TSEC_LANG = FXTSEC.GEMP_TSEC and FXTSEC_LANG.LANG_TSEC_LANG = :lang)
                    where FXTSEC.WEB_TSEC = 'S'
                    and fxsec.cod_sec = :sec
                    and FXTSEC.GEMP_TSEC = :gemp
                    order by FXTSEC.DES_TSEC asc";

             $data =  DB::select($sql,
                                        array(
                                            'sec'   => $cod_sec,
                                            'gemp'       => Config::get('app.gemp'),
                                            'lang'      => \Tools::getLanguageComplete(Config::get('app.locale'))
                                            )
                                        );
            return $data;

    }

    public function getCategSubCateg($cache_sql = false,$all_categ_sub){
        $sql = "SELECT
        COD_SEC,ORTSEC1.lin_ortsec1 as lin_ortsec1 ,NVL(SEC_LANG.DES_SEC_LANG,  SEC.DES_SEC) DES_SEC  ,NVL(SEC_LANG.KEY_SEC_LANG,  SEC.KEY_SEC) KEY_SEC ,NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0 , NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0
        FROM FXSEC SEC
        LEFT JOIN FXSEC_LANG SEC_LANG ON (SEC_LANG.CODSEC_SEC_LANG = SEC.COD_SEC AND  SEC_LANG.GEMP_SEC_LANG = SEC.GEMP_SEC  AND SEC_LANG.LANG_SEC_LANG = :lang)
        JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = :emp )
        JOIN FGORTSEC0 ORTSEC0 ON (ORTSEC0.sub_ORTSEC0 =ORTSEC1.sub_ORTSEC1 AND ORTSEC0.EMP_ORTSEC0 = ORTSEC1.EMP_ORTSEC1  and ORTSEC0.LIN_ORTSEC0 =ORTSEC1.LIN_ORTSEC1)
        LEFT JOIN FGORTSEC0_LANG ORTSEC0_LANG ON (ORTSEC0_LANG.sub_ORTSEC0_LANG = ORTSEC1.sub_ORTSEC1 AND ORTSEC0_LANG.EMP_ORTSEC0_LANG = ORTSEC1.EMP_ORTSEC1  and ORTSEC0_LANG.LIN_ORTSEC0_LANG =ORTSEC1.LIN_ORTSEC1  AND ORTSEC0_LANG.LANG_ORTSEC0_LANG = :lang)
        WHERE
        ORTSEC1.LIN_ORTSEC1 != '10'
        AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = :gemp AND ORTSEC1.SUB_ORTSEC1 = :cod_sub
        GROUP BY COD_SEC,ORTSEC1.ORDEN_ORTSEC1,ORTSEC1.lin_ortsec1, NVL(SEC_LANG.DES_SEC_LANG,  SEC.DES_SEC), NVL(SEC_LANG.KEY_SEC_LANG,  SEC.KEY_SEC),NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) , NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0)
        ORDER BY ORTSEC1.ORDEN_ORTSEC1 ASC";


             $params =  array(
                            'cod_sub'   => $all_categ_sub,
                            'emp'       => Config::get('app.emp'),
                            'gemp'       => Config::get('app.gemp'),
                            'lang'      => \Tools::getLanguageComplete(Config::get('app.locale'))
                            );

             if($cache_sql){
                //quitamos espacios en blanco
                $name_cache = "CategSubCateg_".$all_categ_sub.'_'.\Tools::getLanguageComplete(Config::get('app.locale'));

                $res = \CacheLib::useCache($name_cache,$sql, $params);
            }else{
                $res = DB::select($sql, $params);
            }

            return $res;

    }

}
