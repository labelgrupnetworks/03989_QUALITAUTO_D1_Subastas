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

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Providers\ToolsServiceProvider;

class Enterprise {

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
