<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of Amedida
 *
 * @author LABEL-RSANCHEZ
 */

use Illuminate\Database\Eloquent\Model;
use DB;

use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;
use Routing;
use App\Providers\ToolsServiceProvider;

class Enterprise {
    //put your code here

    public function getEmpre(){
        return DB::table('fsempres')
                ->where('cod_emp',Config::get('app.emp'))
                ->where('gemp_emp',Config::get('app.gemp'))
                ->first();
    }

    public function getVia(){
        $sql = "SELECT NVL(FGSG_LANG.DES_SG_LANG,  FGSG.DES_SG) DES_SG, COD_SG "
                      . "FROM FGSG "
                      . "LEFT JOIN FGSG_LANG ON (FGSG.COD_SG = FGSG_LANG.COD_SG_LANG AND LANG_SG_LANG = :lang) "
                      . "order by FGSG.des_sg asc";

             $params = array(
                'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))
                );

              return DB::select($sql, $params);

    }

    public function getCountries(){


        $sql = "SELECT cod_paises, nvl(FSPAISES_LANG.DES_PAISES_LANG,FSPAISES.des_paises) des_paises
                        FROM FSPAISES
                        LEFT JOIN FSPAISES_LANG ON (FSPAISES_LANG.COD_PAISES_LANG = FSPAISES.cod_paises AND FSPAISES_LANG.LANG_PAISES_LANG = :lang)
                        ORDER BY nvl(FSPAISES_LANG.DES_PAISES_LANG,FSPAISES.des_paises) ASC";
        $params = array(
                'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))
                );

              return DB::select($sql, $params);
    }

    public function infEspecialistas(){


         $sql = "select ESP1.LIN_ESPECIAL1, ESP1.NOM_ESPECIAL1, ESP1.ORDEN_ESPECIAL1, ESP1.EMAIL_ESPECIAL1, NVL(ESP1_LANG.DESC_ESPECIAL1_LANG, ESP1.DESC_ESPECIAL1) DESC_ESPECIAL1, NVL(ESP1_LANG.PER_ESPECIAL1_LANG, ESP1.PER_ESPECIAL1) PER_ESPECIAL1,
		 		NVL(ESPI0_LANG.TITULO_ESPECIAL0_LANG,  ESP0.TITULO_ESPECIAL0) TITULO_ESPECIAL0, ESP0.ORDEN_ESPECIAL0 from FGESPECIAL0 ESP0
                INNER JOIN FGESPECIAL1 ESP1 ON ESP1.LIN_ESPECIAL1 = ESP0.LIN_ESPECIAL0 AND ESP0.EMP_ESPECIAL0 = ESP1.EMP_ESPECIAL1
                LEFT JOIN FGESPECIAL0_LANG ESPI0_LANG ON ESP0.LIN_ESPECIAL0 = ESPI0_LANG.LIN_ESPECIAL0_LANG AND ESP0.EMP_ESPECIAL0 = ESPI0_LANG.EMP_ESPECIAL0_LANG AND ESPI0_LANG.LANG_ESPECIAL0_LANG  = :lang
				LEFT JOIN FGESPECIAL1_LANG ESP1_LANG ON ESP1_LANG.LIN_ESPECIAL1_LANG = ESP1.LIN_ESPECIAL1 AND ESP1_LANG.EMP_ESPECIAL1_LANG = ESP1.EMP_ESPECIAL1 AND ESP1_LANG.PER_ESPECIAL1_LANG = ESP1.PER_ESPECIAL1 AND ESP1_LANG.LANG_ESPECIAL1_LANG  = :lang
                WHERE ESP0.EMP_ESPECIAL0 = :emp order by ESP0.ORDEN_ESPECIAL0, ESP1.ORDEN_ESPECIAL1";

            $bindings = array(
                    'emp'             => Config::get('app.emp'),
                    'lang'      => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))
                    );
        return DB::select($sql, $bindings);
    }

    public function getAlmacen($cod_alm){

        $almacen = DB::select("select cod_alm, obs_alm,nvl(horario_alm_lang,horario_alm) horario_alm,maps_alm,cp_alm, dir_alm, pob_alm, tel_alm, email_alm,codpais_alm from fxalm
                    LEFT JOIN FXALM_LANG ON (EMP_ALM = EMP_ALM_LANG AND COD_ALM_LANG = COD_ALM AND LANG_ALM_LANG = :lang)
                    where emp_alm = :emp
                    and cod_alm = :cod_alm",
                array(
                        'emp'       => Config::get('app.emp'),
                        'cod_alm'   => $cod_alm,
                        'lang' => ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))
                        )
                    );
        if(count($almacen) > 0){
            return head($almacen);
        }else{
            return NULL;
        }
    }

    public function getParameters(){
        $parameters = DB::select("select * from fxprmgt
                    where emp_prmgt = :emp",
                array(
                        'emp'       => Config::get('app.emp')
                     )
                    );
        if(count($parameters) > 0){
            return head($parameters);
        }else{
            return NULL;
        }
    }

    public function getParam(){
        $parameters = DB::select("select * from fxparam1
                    where emp_param1 = :emp",
                array(
                        'emp'       => Config::get('app.emp')
                     )
                    );
        if(count($parameters) > 0){
            return head($parameters);
        }else{
            return NULL;
        }
    }


    public function getIva($cod_iva){
        $iva = DB::select("select * from fsiva
                    where cod_iva = :cod_iva",
                array(
                        'cod_iva'       => $cod_iva
                     )
                    );
        if(count($iva) > 0){
            return head($iva);
        }else{
            return NULL;
        }
    }

     public function getEmbalaje($cod_embalajes){

        $embalaje = DB::select("select * from fsembalajes where emp_embalajes = :emp and  cod_embalajes = :cod_embalajes",
                array(
                        'emp'       => Config::get('app.emp'),
                        'cod_embalajes'       => $cod_embalajes
                     )
                    );
        if(count($embalaje) > 0){
            return head($embalaje);
        }else{
            return NULL;
        }
    }

    public function getDivisa(){
        return DB::table('FSDIV')
            ->select('cod_div','des_div','impd_div','symbolhtml_div')
            ->get();
    }
}
