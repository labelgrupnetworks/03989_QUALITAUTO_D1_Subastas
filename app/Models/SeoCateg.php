<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use Config;
use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Routing;
use App\Models\Content;

class SeoCateg extends Model
{   
    public $id;
    public $codsec;
    public $gemp;
    public $lang;
    public $webname;
    public $webfriend;
    public $webmetad;
    public $webmetat;
    public $webcont;

    public function getSeocateg($gemp){
      return DB::TABLE('FXSEC')
              ->select('cod_sec','des_sec')
              ->JOIN('FXTSEC', function ($join){
                $join->on('FXTSEC.GEMP_TSEC', '=', 'FXSEC.GEMP_SEC')
                        ->on('FXTSEC.COD_TSEC','=', 'FXSEC.TSEC_SEC');
                 
            })
            ->WHERE('WEB_TSEC', '=', 'S')
              ->where('GEMP_SEC', $gemp)
              ->get();
      
    
    }
    
    public function infCategSeo($cod_sec,$gemp){
        return DB::TABLE('WEB_SEO_SEC')
              ->where('GEMPSEC_SEO_SEC', $gemp)
              ->where('CODSEC_SEO_SEC', $cod_sec)
              ->get();
    }
    
      public function infCategSeoWebFriend($webfriend,$gemp){
        return DB::TABLE('WEB_SEO_SEC')
              ->select('WEB_SEO_SEC.*')
              ->where('GEMPSEC_SEO_SEC', $gemp)
              ->where('webfriend_SEO_SEC', $webfriend)
              ->first();
    }
    
    public function NameCodSeo($cod_sec,$gemp){

        return DB::TABLE('FXSEC')
                ->select('cod_sec','des_sec')
                ->where('GEMP_SEC', $gemp)
                ->where('cod_sec', $cod_sec)
                ->first();
      
    }
    
    public function updateSeo($content){

        DB::TABLE('WEB_SEO_SEC')
                ->where('id_seo_sec',$content->id)
                ->where('gempsec_seo_sec',$content->gemp)
                ->where('codsec_seo_sec',$content->codsec)
                ->where('codlang_seo_sec',$content->lang)
                ->update(['webname_seo_sec' => $content->webname,
                    'webfriend_seo_sec' => $content->webfriend,
                    'webmetad_seo_sec' => $content->webmetad,
                    'webmetat_seo_sec' => $content->webmetat,
                    'webcont_seo_sec' => $content->webcont
                        ]);
        
    }
    
    public function maxSeo(){
        return DB::TABLE('WEB_SEO_SEC')->max('id_seo_sec');
    }
    
    public function insertSeo($content){
        DB::TABLE('WEB_SEO_SEC')
                ->insert([
                    'id_seo_sec'=>$content->id,
                    'gempsec_seo_sec'=>$content->gemp,
                    'codlang_seo_sec'=>$content->lang,
                    'webname_seo_sec' => $content->webname,
                    'webfriend_seo_sec' => $content->webfriend,
                    'webmetad_seo_sec' => $content->webmetad,
                    'webmetat_seo_sec' => $content->webmetat,
                    'webcont_seo_sec' => $content->webcont,
                    'codsec_seo_sec' =>$content->codsec]);
    }
    
     public function getAllWebSec($gemp, $lang){           
        
        $data = DB::table('WEB_SEO_SEC')
        ->select("/*+ INDEX (FXSEC FXSEC_PK) */ WEB_SEO_SEC.codsec_seo_sec,WEB_SEO_SEC.webname_seo_sec,WEB_SEO_SEC.webfriend_seo_sec") 
        ->JOIN('FXSEC', function ($join){
                $join->on('FXSEC.GEMP_SEC', '=', 'WEB_SEO_SEC.GEMPSEC_SEO_SEC')
                        
                        ->on('FXSEC.COD_SEC','=', 'WEB_SEO_SEC.CODSEC_SEO_SEC');
            })
        ->JOIN('FXTSEC', function ($join){
                $join->on('FXTSEC.GEMP_TSEC', '=', 'WEB_SEO_SEC.GEMPSEC_SEO_SEC')
                        ->on('FXTSEC.COD_TSEC','=', 'FXSEC.TSEC_SEC');
                        
                 
            })
            
            ->WHERE('FXTSEC.WEB_TSEC', '=', 'S')            
            ->WHERE('WEB_SEO_SEC.CODLANG_SEO_SEC', '=', strtoupper($lang))                    
            ->where('WEB_SEO_SEC.GEMPSEC_SEO_SEC', $gemp)
            ->orderBy('WEB_SEO_SEC.WEBNAME_SEO_SEC','asc')
           
        ->get();
       
        return $data;
    }
        
}