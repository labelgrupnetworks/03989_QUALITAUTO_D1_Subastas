<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

# Ubicacion del modelo
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use DB;
use Config;


use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;

class Page extends Model
{

    public $lang;
    public $id;
    public $emp;
    public $name;
    public $content;
    public $webmetad;
    public $webmetat;
    public $webnoindex;

    public function __construct()
    {
        $this->emp =Config::get('app.main_emp');
    }

     public function getPagina($lang,$key)
   {
       $sql="SELECT * FROM WEB_PAGE WHERE KEY_WEB_PAGE = :key AND LANG_WEB_PAGE = :lang  AND EMP_WEB_PAGE = :emp";
       $bindings =  array(				#cogemos la empresa auxiliar y si no existe la actual, de esta manera sl ocontenidos solo deben estar en la empresa principal
                        'emp'       =>   Config::get('app.main_emp'),
                        'lang'   => strtoupper($lang),
                        'key'       => $key,
                    );

       $res = DB::select($sql, $bindings);

       if(!empty($res)){
           return head($res);
       }else{
            return null;
       }

   }

   public function allPage($emp){
        return DB::table('WEB_PAGE')
        ->where('emp_web_page',$emp)
        ->whereNull('MANAGEABLE_WEB_PAGE')
        ->orderby('NAME_WEB_PAGE','asc')
        ->get();
   }

   public function getPage($emp,$id){
        return DB::table('WEB_PAGE')
        ->where('emp_web_page',$emp)
        ->where('id_web_page',$id)
        ->first();
   }

   public function updatePage(){
        DB::table('WEB_PAGE')
            ->where('EMP_WEB_PAGE',$this->emp)
            ->where('ID_WEB_PAGE',$this->id)
            ->update(['NAME_WEB_PAGE' => $this->name,'CONTENT_WEB_PAGE' => $this->content,'WEBMETAD_WEB_PAGE' => $this->webmetad,'WEBMETAT_WEB_PAGE' =>  $this->webmetat,'WEBNOINDEX_WEB_PAGE' =>  $this->webnoindex]);
   }
}
