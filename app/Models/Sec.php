<?php

# Ubicacion del modelo
namespace App\Models;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

class Sec extends Model
{

    public function getOrtsecByKey($cod_sub,$key){
        $sql="SELECT
                ORTSEC0.EMP_ORTSEC0,ORTSEC0.SUB_ORTSEC0,ORTSEC0.LIN_ORTSEC0,
                NVL(LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0,
                NVL(LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0,
                NVL(LANG.META_DESCRIPTION_ORTSEC0_LANG,  ORTSEC0.META_DESCRIPTION_ORTSEC0) META_DESCRIPTION_ORTSEC0,
                NVL(LANG.META_TITULO_ORTSEC0_LANG,  ORTSEC0.META_TITULO_ORTSEC0) META_TITULO_ORTSEC0,
                NVL(LANG.META_CONTENIDO_ORTSEC0_LANG,  ORTSEC0.META_CONTENIDO_ORTSEC0) META_CONTENIDO_ORTSEC0

                FROM FGORTSEC0 ORTSEC0
                LEFT JOIN FGORTSEC0_LANG LANG ON LANG.EMP_ORTSEC0_LANG = ORTSEC0.EMP_ORTSEC0 AND LANG.SUB_ORTSEC0_LANG = SUB_ORTSEC0 AND LANG.LIN_ORTSEC0_LANG = ORTSEC0.LIN_ORTSEC0  AND LANG_ORTSEC0_LANG = :lang
                WHERE ORTSEC0.EMP_ORTSEC0 = :emp AND ORTSEC0.SUB_ORTSEC0 = :cod_sub and NVL(LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0)= :key";

         $params = array(
                'emp'       =>  Config::get('app.emp'),
                'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale')),
                'cod_sub'       =>  $cod_sub,
                'key'       =>  $key,

                );
         $ortsec = \CacheLib::useCache("ortsec_".$cod_sub."_".$key,$sql, $params);
        //$ortsec = DB::select($sql, $params);
        if (count($ortsec) > 0){
            return head($ortsec);
        }else{
            return NULL;
        }
    }


    public function getSecByKey($key){
        $sql="select
                COD_SEC,
                NVL(SEC_LANG.DES_SEC_LANG,  SEC.DES_SEC) DES_SEC,
                NVL(SEC_LANG.KEY_SEC_LANG,  SEC.KEY_SEC) KEY_SEC,
                NVL(SEC_LANG.META_DESCRIPTION_SEC_LANG,  SEC.META_DESCRIPTION_SEC) META_DESCRIPTION_SEC,
                NVL(SEC_LANG.META_TITULO_SEC_LANG,  SEC.META_TITULO_SEC) META_TITULO_SEC,
                NVL(SEC_LANG.META_CONTENIDO_SEC_LANG,  SEC.META_CONTENIDO_SEC) META_CONTENIDO_SEC

                from FXSEC SEC
                LEFT JOIN FXSEC_LANG SEC_LANG ON (SEC_LANG.CODSEC_SEC_LANG = SEC.COD_SEC AND  SEC_LANG.GEMP_SEC_LANG = SEC.GEMP_SEC  AND SEC_LANG.LANG_SEC_LANG = :lang)
                WHERE SEC.GEMP_SEC = :gemp and NVL(SEC_LANG.KEY_SEC_LANG,  SEC.KEY_SEC) = :key";

         $params = array(
                'gemp'       =>  Config::get('app.gemp'),
                'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale')),
                'key'       =>  $key,

                );
        $sec = \CacheLib::useCache("sec_$key",$sql, $params);
        //$sec = DB::select($sql, $params);
        if (count($sec) > 0){
            return head($sec);
        }else{
            return NULL;
        }
    }
    //ORDENAMOS POR ORDEN_ORTSEC POR QUE LA PRIMERA SIEMPRE ES LA DE TODOS LAS CATEGORIAS
     public function getOrtsecByCodSec($cod_sub,$cod_sec){
        $sql="SELECT
                ORTSEC0.EMP_ORTSEC0,ORTSEC0.SUB_ORTSEC0,ORTSEC0.LIN_ORTSEC0,
                NVL(LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0,
                NVL(LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0,
                NVL(LANG.META_DESCRIPTION_ORTSEC0_LANG,  ORTSEC0.META_DESCRIPTION_ORTSEC0) META_DESCRIPTION_ORTSEC0,
                NVL(LANG.META_TITULO_ORTSEC0_LANG,  ORTSEC0.META_TITULO_ORTSEC0) META_TITULO_ORTSEC0,
                NVL(LANG.META_CONTENIDO_ORTSEC0_LANG,  ORTSEC0.META_CONTENIDO_ORTSEC0) META_CONTENIDO_ORTSEC0
                FROM FGORTSEC0 ORTSEC0
                JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.EMP_ORTSEC1 = ORTSEC0.EMP_ORTSEC0 AND  ORTSEC1.SUB_ORTSEC1 = ORTSEC0.SUB_ORTSEC0 AND ORTSEC1.LIN_ORTSEC1 = ORTSEC0.LIN_ORTSEC0 )
                LEFT JOIN FGORTSEC0_LANG LANG ON LANG.EMP_ORTSEC0_LANG = ORTSEC0.EMP_ORTSEC0 AND LANG.SUB_ORTSEC0_LANG = SUB_ORTSEC0 AND LANG.LIN_ORTSEC0_LANG = ORTSEC0.LIN_ORTSEC0  AND LANG_ORTSEC0_LANG = :lang
                WHERE ORTSEC0.EMP_ORTSEC0 = :emp AND ORTSEC0.SUB_ORTSEC0 = :cod_sub
                AND ORTSEC1.SEC_ORTSEC1= :cod_sec
                ORDER BY ORDEN_ORTSEC0 DESC";

         $params = array(
                'emp'       =>  Config::get('app.emp'),
                'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale')),
                'cod_sub'       =>  $cod_sub,
                'cod_sec'       =>  $cod_sec,

                );

        $ortsec = DB::select($sql, $params);
        if (count($ortsec) > 0){
            return head($ortsec);
        }else{
            return NULL;
        }
    }

    public function getOrtsecByOrtsec($cod_sub,$lot_categories){
        $lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
        return DB::table('FGORTSEC0 ORTSEC0')
                ->select('ORTSEC0.EMP_ORTSEC0','ORTSEC0.SUB_ORTSEC0','ORTSEC0.LIN_ORTSEC0','NVL(LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0',
                        'NVL(LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0')
                ->join('FGORTSEC1 ORTSEC1', function($join){
                    $join->on('ORTSEC1.EMP_ORTSEC1', '=', 'ORTSEC0.EMP_ORTSEC0')
                         ->on('ORTSEC1.SUB_ORTSEC1', '=', 'ORTSEC0.SUB_ORTSEC0')
                         ->on('ORTSEC1.LIN_ORTSEC1', '=', 'ORTSEC0.LIN_ORTSEC0');
                })
                ->leftJoin('FGORTSEC0_LANG LANG', function($join) use($lang){
                    $join->on('LANG.EMP_ORTSEC0_LANG', '=', 'ORTSEC0.EMP_ORTSEC0')
                         ->on('LANG.SUB_ORTSEC0_LANG', '=', 'ORTSEC0.SUB_ORTSEC0')
                         ->on('LANG.LIN_ORTSEC0_LANG', '=', 'ORTSEC0.LIN_ORTSEC0')
                         ->on('LANG.LANG_ORTSEC0_LANG', '=',"'$lang'");

                })
                ->whereIn('ORTSEC0.LIN_ORTSEC0',$lot_categories)
                ->where('ORTSEC0.EMP_ORTSEC0',Config::get('app.emp'))
                ->where('ORTSEC0.SUB_ORTSEC0',$cod_sub)
                ->groupBy('ORTSEC0.EMP_ORTSEC0','ORTSEC0.SUB_ORTSEC0','ORTSEC0.LIN_ORTSEC0','NVL(LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0)',
                        'NVL(LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0)')
                ->get();
    }

}
