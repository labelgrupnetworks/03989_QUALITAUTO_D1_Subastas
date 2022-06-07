<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\libs;
use Config;

//use DB;
/**
 * Description of Str_lib
 *
 * @author LABEL-RSANCHEZ
 */
class CacheLib {



    public static  function getCache($keyname){

        if ( Config::get('app.enable_cache') && \Cache::has($keyname)){
            return \Cache::get($keyname);
        }else{
            return false;
        }
    }
    //$expiresAt se pasa en minutos
    public static  function putCache($keyname, $data, $expiresAt = NULL){

        if (Config::get('app.enable_cache')){
            if( empty($expiresAt) && !empty(Config::get('app.time_cache'))){
                $expiresAt = Config::get('app.time_cache') ;
            }else{
                $expiresAt = 60; # un minuto
            }
            \Cache::put($keyname, $data, $expiresAt);

        }else{
            return false;
        }
    }
    //si se pone com oexpiresAt un 0 no se hace el cache
    public static function useCache($keyname, $sql, $params = array(), $expiresAt = NULL ){
		$langThemeEmp = "_".Config::get('app.locale')."_".Config::get('app.theme')."_".Config::get('app.emp');
        $keyname = $keyname.$langThemeEmp;

        $data = CacheLib::getCache($keyname);
        //si estamos en debug o se fuerza la carga mediante el expires
         if (( (env('APP_DEBUG') &&  !in_array($keyname, ['translate'.$langThemeEmp, 'WEB_SEO_ROUTES'.$langThemeEmp])) || $expiresAt === 0 ) ){

              $data = \DB::select($sql, $params);

         }
         //si no hay datos
         elseif ( ($data === false )){
			// \Log::info("guardando cache $keyname");

            $data = \DB::select($sql, $params);
            CacheLib::putCache($keyname, $data,$expiresAt);
        }else{
           ;//  \Log::info("usando cache $keyname");
		}

		#evitamos que devuelva vacio
		if(empty($data)){
			$data=array();
		}

        return $data;
    }

}
