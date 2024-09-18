<?php


namespace App\Models\apirest;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;


class AuctionApiRest extends ApiRest{

    public function getSub($type_sub,$codsub,$idsession){


        $lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));

        $sql = DB::TABLE('fgsub sub')
                ->select('sub.COD_SUB cod_sub, sub.EMP_SUB, sub.SUBC_SUB, sub.tipo_sub, sub.SUBC_SUB, sub.tipo_sub,sub.subabierta_sub,sub.opcioncar_sub,
                       sub.subastatr_sub,sub.COMPRAWEB_SUB,
                       NVL(fgsublang.DES_SUB_LANG,  sub.DES_SUB) des_sub,
                       NVL(fgsublang.EXPOFECHAS_SUB_LANG,  sub.expofechas_sub) expofechas_sub,
                       NVL(fgsublang.EXPOHORARIO_SUB_LANG,  sub.expohorario_sub) expohorario_sub,
                       NVL(fgsublang.EXPOLOCAL_SUB_LANG,  sub.expolocal_sub) expolocal_sub,
                       NVL(fgsublang.SESFECHAS_SUB_LANG,  sub.sesfechas_sub) sesfechas_sub,
                       NVL(fgsublang.SESHORARIO_SUB_LANG,  sub.seshorario_sub) seshorario_sub,
                       NVL(fgsublang.SESLOCAL_SUB_LANG,  sub.seslocal_sub) seslocal_sub,
                       NVL(fgsublang.descdet_SUB_LANG,  sub.descdet_sub) descdet_sub,
                       sub.sesmaps_sub,sub.expomaps_sub');
                if(!empty($idsession)){
                    $sql->addSelect('auc.*')
                        ->join('"auc_sessions" auc', function ($join) {
                        $join->on('auc."auction"', '=', 'sub.COD_SUB')
                        ->on('auc."company"', '=', 'sub.EMP_SUB');
                    });
                }
                $sql->leftJoin('FGSUB_LANG fgsublang', function ($join) use($lang){
                    $join->on('sub.EMP_SUB', '=', 'fgsublang.EMP_SUB_LANG')
                    ->on('sub.COD_SUB', '=', 'fgsublang.COD_SUB_LANG')
                    ->where('fgsublang.LANG_SUB_LANG','=',$lang);
                })
               ->where('sub.EMP_SUB',Config::get('app.emp'));
               if(!empty($type_sub)) {
                    if(is_array($type_sub)){
                        $sql->whereIn('sub.subc_sub',$type_sub);
                    }else{
                         $sql->where('sub.subc_sub',$type_sub);
                    }
               }
               if(!empty($codsub)) {
                    $sql->where('sub.COD_SUB',$codsub);
               }
               if(!empty($idsession)) {
                    $sql->where('auc."id_auc_sessions"',$idsession);
               }
               return $sql->get();
    }


}
