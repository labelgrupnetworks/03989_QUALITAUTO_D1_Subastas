<?php


namespace App\Models\apirest;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;


class LotApiRest extends ApiRest{
   
    public function getWarehouse($cod_alm = null){
        
        $lang = \Tools::getLanguageComplete(Config::get('app.locale'));
        
        $sql = DB::TABLE('FXALM')
               ->select('cod_alm, obs_alm,nvl(horario_alm_lang,horario_alm) horario_alm,maps_alm,cp_alm, dir_alm, pob_alm, tel_alm, email_alm,codpais_alm,des_alm')
                ->leftJoin('FXALM_LANG', function ($join) use($lang){
                   $join->on('EMP_ALM', '=', 'EMP_ALM_LANG')
                   ->on('COD_ALM_LANG', '=', 'COD_ALM')
                   ->where('LANG_ALM_LANG','=',$lang);
               })
               ->where('emp_alm',Config::get('app.emp'))
               ->orderBy('des_alm');
               if(!empty($cod_alm)){
                    return $sql->where('cod_alm',$cod_alm)
                    ->first();
               }else{
                   return $sql->get();
               }        
              
    }
    
    public function getSeccions($sec = null){
        $sql = DB::table('FXSEC')
        ->select("cod_sec,des_sec,tsec_sec,form_sec,comi_sec")        
        ->JOIN('FXTSEC', function ($join){
            $join->on('FXTSEC.GEMP_TSEC', '=', 'FXSEC.GEMP_SEC')
                 ->on('FXTSEC.COD_TSEC','=', 'FXSEC.TSEC_SEC')
                 ->on('FXTSEC.GEMP_TSEC','=', 'FXSEC.GEMP_SEC');
        })
        //->WHERE('WEB_TSEC', 'S')
        ->WHERE('GEMP_SEC',Config::get('app.gemp'))
        ->orderBy('DES_SEC','asc');
        if(!empty($sec)){
            $sql->where('COD_SEC',$sec);
        }
        return $sql->get();
        
    }
    
    public function getObjectTypes(){
        
        return DB::table('"object_types"')
        ->select('"id_object_types","name", "code"') 
        ->WHERE('"company"', '=', Config::get('app.emp'))
        ->get();
    }
    
    public function generateLin($numhces){
        $lin = DB::table('FGHCES1')
        ->where('num_hces1',$numhces)
        ->where('emp_hces1',Config::get('app.emp'))->count();
        
        $lin = $lin + 1;

        return $lin;
    }
    
    public function insertLot($lot){
        DB::table('FGHCES1')->insert([
            ['emp_hces1' => Config::get('app.emp'), 'num_hces1' => $lot->num_hces1,'lin_hces1' => $lot->lin_hces1,'sec_hces1'=>$lot->sec_hces1, 
             'titulo_hces1' => $lot->titulo_hces1, 'alm_hces1' => $lot->alm_hces1, 'nobj_hces1' => $lot->nobj_hces1,
             'tipoobj_hces1' => $lot->tipoobj_hces1, 'impsal_hces1' => $lot->impsal_hces1,'impres_hces1' => $lot->impres_hces1, 'imptas_hces1' => $lot->imptas_hces1,
             'imptash_hces1' => $lot->imptash_hces1, 'desc_hces1' => $lot->desc_hces1,'sub_hces1' => $lot->sub_hces1,'prop_hces1' => $lot->prop_hces1,
             'id_hces1' => $lot->id_hces1,'pc_hces1'=>$lot->pc_hces1,'comp_hces1'=>$lot->comp_hces1,'coml_hces1'=>$lot->coml_hces1]
        ]);
    }

}
