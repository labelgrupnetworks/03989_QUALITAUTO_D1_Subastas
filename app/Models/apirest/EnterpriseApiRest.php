<?php


namespace App\Models\apirest;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;


class EnterpriseApiRest extends ApiRest{
   
    public function getVia(){
        $sql = "SELECT NVL(FGSG_LANG.DES_SG_LANG,  FGSG.DES_SG) DES_SG, COD_SG "
                      . "FROM FGSG "
                      . "LEFT JOIN FGSG_LANG ON (FGSG.COD_SG = FGSG_LANG.COD_SG_LANG AND LANG_SG_LANG = :lang) "
                      . "order by FGSG.des_sg asc";

        $params = array(
           'lang'      => \Tools::getLanguageComplete(Config::get('app.locale'))          
           );
              
        return DB::select($sql, $params);
    }
    
    public function getCountries($cod_pais = null){
        
        $lang = \Tools::getLanguageComplete(Config::get('app.locale')); 
        $sql = DB::table('FSPAISES')
                ->select('cod_paises, nvl(FSPAISES_LANG.DES_PAISES_LANG,FSPAISES.des_paises) des_paises')
                ->leftJoin('FSPAISES_LANG', function ($join) use($lang){
                    $join->on('COD_PAISES_LANG', '=', 'cod_paises')
                    ->where('LANG_PAISES_LANG', '=', $lang);
                });
        if(!empty($cod_pais)){
            return $sql->where('cod_paises',$cod_pais)->first();
        }else{
            return $sql->get();
        }
                
    }
    
    public function getTown($zip_code,$country){
        
        return DB::table('FSPOB')
        ->where('COD_POB',$zip_code)
        ->where('PAIS_POB',$country)
        ->first();
        
    }
    
    public function getParams(){
        return DB::TABLE('FSPARAMS')
                ->where('emp_params',Config::get('app.emp'))
                ->first();
    }
    
    public function getParamsSub(){
          return DB::TABLE('fgprmsub')
                ->where('emp_prmsub',Config::get('app.emp'))
                ->first();
    }
    
    public function getTsec(){
        return DB::TABLE('FXTSEC')
            ->where('GEMP_TSEC', Config::get('app.gemp'))
            ->get();
    }
    
    public function getRepresentative(){
         return DB::TABLE('fxper')
            ->select('cod_per,nom_per')
            ->where('BAJA_TEMP_PER','N')
            ->where('GEMP_PER', Config::get('app.gemp'))
            ->get();
    }
    
    public function getParamsApp(){
         return DB::TABLE('FGPRMAPP')
                ->where('emp_prmapp',Config::get('app.emp'))
                ->first();
    }
}
