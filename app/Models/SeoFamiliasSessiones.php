<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;
use Routing;
use App\Models\Content;

class SeoFamiliasSessiones extends Model
{   
    public $id;
    public $id_auc;
    public $emp;
    public $lang;
    public $webname;
    public $webfriend;
    public $webmetad;
    public $webmetat;
    public $webcont;
    

    public function getSeoFamilySession($emp){
        print_r($emp);
      return DB::TABLE('WEB_AUC_INDEX')
              ->where('ID_EMP', $emp)
              ->where(function($query)
                {
                    $query->orwhere('type','S')
                          ->orwhere('type','F');
                })
              ->get();
    }
    
    public function SeeFamilySessionsSeo($id,$emp){
        return DB::TABLE('WEB_AUC_SEO')
              ->where('EMP_AUC_SEO', $emp)
              ->where('ID_AUC_INDEX_AUC_SEO', $id)
              ->get();
    }
    
    public function FamilySessionsSeoLang($id,$emp,$lang){
        return DB::TABLE('WEB_AUC_SEO')
              ->where('EMP_AUC_SEO', $emp)
              ->where('ID_AUC_INDEX_AUC_SEO', $id)
              ->where('CODLANG_AUC_SEO',$lang)
              ->get();
    }
    
    public function NameSessionsSeo($id,$emp){

        return DB::TABLE('WEB_AUC_INDEX')
              ->where('WEB_AUC_INDEX.ID_EMP', $emp)
              ->where('WEB_AUC_INDEX.ID_WEB_AUC_INDEX', $id)
              ->first();
      
    }
    
    public function updateSeo($content){

        DB::TABLE('WEB_AUC_SEO')
                ->where('id_auc_seo',$content->id)
                ->where('emp_auc_seo',$content->emp)
                ->where('id_auc_index_auc_seo',$content->id_auc)
                ->where('codlang_auc_seo',$content->lang)
                ->update(['webname_auc_seo' => $content->webname,
                    'webfriend_auc_seo' => $content->webfriend,
                    'webmetad_auc_seo' => $content->webmetad,
                    'webmetat_auc_seo' => $content->webmetat,
                    'webcont_auc_seo' => $content->webcont
                        ]);
        
    }
    
    public function maxSeo(){
        return DB::TABLE('WEB_AUC_SEO')->max('id_auc_seo');
    }
    
    public function insertSeo($content){
        DB::TABLE('WEB_AUC_SEO')
                ->insert([
                    'id_auc_seo'=>$content->id,
                    'emp_auc_seo'=>$content->emp,
                    'codlang_auc_seo'=>$content->lang,
                    'webname_auc_seo' => $content->webname,
                    'webfriend_auc_seo' => $content->webfriend,
                    'webmetad_auc_seo' => $content->webmetad,
                    'webmetat_auc_seo' => $content->webmetat,
                    'webcont_auc_seo' => $content->webcont,
                    'id_auc_index_auc_seo' =>$content->id_auc]);
    }
        
}