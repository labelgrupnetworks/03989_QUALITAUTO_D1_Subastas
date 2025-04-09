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

class Enterprise {

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
