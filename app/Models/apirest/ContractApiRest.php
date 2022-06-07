<?php


namespace App\Models\apirest;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;


class ContractApiRest extends ApiRest{
    
    public function getNumContract($dnumaut,$hnumaut){
        return DB::TABLE('fghces0')
               ->where('emp_hces0',Config::get('app.emp'))
               ->whereBetween('num_hces0',[$dnumaut,$hnumaut])
               ->max('num_hces0');
    }
    
    public function getSitua(){
        return DB::TABLE('FXSITUA')
               ->select('COD_SITUA','DES_SITUA','TDOC_SITUA')
               ->where('EMP_SITUA',Config::get('app.emp'))
               ->where('TDOC_SITUA','HC')
               ->get();
    }
    
    //Recoger informacion contrato
    public function getContract($codcli,$idcontract = null){
        $sql = DB::TABLE('FGHCES0')
               ->where('emp_hces0',Config::get('app.emp'))
               ->where('prop_hces0',$codcli);
               if(!empty($idcontract)){
                   $sql->where('num_hces0',$idcontract);
               }
               return $sql->get();

    }
    
    //Generar contrato
    public function setContract($newcontract){
        try{
            DB::table('FGHCES0')->insert([
                ['emp_hces0' =>Config::get('app.emp'), 
                'num_hces0' => $newcontract->num_hces0,
                'sub_hces0' => $newcontract->sub_hces0, 
                'fec_hces0' => date('Y-m-d'),
                'prop_hces0'=>$newcontract->prop_hces0,
                'rsoc_hces0'=>$newcontract->rsoc_hces0,
                'cerrado_hces0'=>'N','usra_hces0'=>$newcontract->usra_hces0,
                'feca_hces0'=>date('Y-m-d'),'tsec_hces0'=>$newcontract->tsec_hces0,
                'representante_hces0'=>$newcontract->representante_hces0,
                'situa_hces0'=>$newcontract->situa_hces0,
                'obs_hces0'=>$newcontract->obsdet_hces0]
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function updateContract($newcontract){
            try{
                DB::table('FGHCES0')
                ->where('num_hces0', $newcontract->num_hces0)
                ->update([
                    'fecm_hces0' => date('Y-m-d'), 
                    'obs_hces0' => $newcontract->obs_hces0,
                    'tsec_hces0' => $newcontract->tsec_hces0, 
                    'situa_hces0' => $newcontract->situa_hces0, 
                    'sub_hces0' => $newcontract->sub_hces0]);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
}