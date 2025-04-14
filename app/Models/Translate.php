<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class Translate extends Model
{
    public function getTranslate($emp,$head,$lang){

        $sql = "SELECT WEB_TRANSLATE_HEADERS.KEY_HEADER,WEB_TRANSLATE_KEY.KEY_TRANSLATE,WEB_TRANSLATE.WEB_TRANSLATION,WEB_TRANSLATE.ID_TRANSLATE,WEB_TRANSLATE.ID_KEY_TRANSLATE "
            . "FROM WEB_TRANSLATE_HEADERS "
            . "JOIN WEB_TRANSLATE_KEY ON (WEB_TRANSLATE_HEADERS.ID_HEADERS = WEB_TRANSLATE_KEY.ID_HEADERS_TRANSLATE AND WEB_TRANSLATE_KEY.ID_EMP = :emp) "
            . "LEFT JOIN WEB_TRANSLATE ON (WEB_TRANSLATE_KEY.ID_KEY = WEB_TRANSLATE.ID_KEY_TRANSLATE AND WEB_TRANSLATE.ID_EMP = :emp AND WEB_TRANSLATE.LANG = :lang) "
            . "WHERE  "
            . " WEB_TRANSLATE_HEADERS.ID_EMP = :emp "
            . "and WEB_TRANSLATE_HEADERS.KEY_HEADER= :head "
            . "ORDER BY WEB_TRANSLATE_HEADERS.KEY_HEADER desc";

             $params = array(
				'head' => $head,
				'lang' => $lang,
				'emp' => $emp,
            );


         $trans = DB::select($sql, $params);
         $res = array();
         foreach($trans as $tra){
             $res[$tra->key_translate] = $tra;
         }

         return $res;

    }

    public function headersTrans(){
       return DB::TABLE('WEB_TRANSLATE_HEADERS')
         ->where('ID_EMP',Config::get('app.main_emp'))
        ->get();
    }

    public function updateTrans($id,$value,$emp,$user,$fechaactual,$lang){

        DB::table('WEB_TRANSLATE')
            ->where('ID_EMP',$emp)
            ->where('ID_KEY_TRANSLATE',$id)
            ->where('LANG',$lang)
            ->update(['WEB_TRANSLATION' => $value,'DATE_MODIFICACION' => $fechaactual,'USER_MODIFICACION' => $user]);

    }

    public function deleteTrans($id_key_translate,$emp,$lang){

        DB::table('WEB_TRANSLATE')
            ->where('ID_EMP',$emp)
            ->where('ID_KEY_TRANSLATE',$id_key_translate)
            ->where('LANG',$lang)
            ->delete();
    }

    public function idKey($emp,$key,$lang){
        return DB::table('WEB_TRANSLATE')
        ->where('ID_EMP',$emp)
        ->where('LANG',$lang)
        ->where('ID_KEY_TRANSLATE',$key)
        ->first()
        ;
    }

    public function idKeyTranslate($emp, $keyTranslate){
        return DB::table('WEB_TRANSLATE_KEY')
            ->where('ID_EMP', $emp)
            ->where('KEY_TRANSLATE', $keyTranslate)
            ->where('ID_EMP',$emp)
            ->value('ID_KEY');
    }


     public function idKeyTranslateHeader($emp, $keyTranslate, $idHeader ){
        return DB::table('WEB_TRANSLATE_KEY')
            ->where('ID_EMP', $emp)
            ->where('ID_HEADERS_TRANSLATE', $idHeader)
            ->where('KEY_TRANSLATE', $keyTranslate)
            ->value('ID_KEY');
    }

    public function maxIdTranslate($emp){
        return DB::table('WEB_TRANSLATE')
        ->max('ID_TRANSLATE');
    }

    public function insertTrans($id_key,$id,$value,$emp,$user,$fechaactual,$lang){

        DB::table('WEB_TRANSLATE')
          ->insert([
            ['id_key_translate' => $id_key, 'id_translate' => $id,'web_translation'=>$value, 'id_emp' => $emp, 'user_modificacion' => $user, 'date_modificacion' => $fechaactual, 'lang' => $lang]
        ]);
    }

    public function idHeaders($key,$emp){
        return DB::table('WEB_TRANSLATE_HEADERS')
        ->select('ID_HEADERS')
        ->where('ID_EMP',$emp)
        ->where('KEY_HEADER',$key)
        ->first();
    }

    public function MaxHeaders($emp){
        return DB::table('WEB_TRANSLATE_KEY')
        ->max('ID_KEY');
    }

    public function insertKey($id,$id_headers,$emp,$key_translate){
        DB::table('WEB_TRANSLATE_KEY')
          ->insert([
            ['ID_KEY' => $id, 'ID_HEADERS_TRANSLATE' => $id_headers,'ID_EMP'=>$emp, 'KEY_TRANSLATE' => $key_translate]
        ]);
    }

    public function maxIdHeader($emp){
        return DB::table('WEB_TRANSLATE_HEADERS')
        ->max('ID_HEADERS');
    }


    public function insertHeader($id_header, $id_emp, $key_header){
        DB::table('WEB_TRANSLATE_HEADERS')
            ->insert([
                ['ID_HEADERS' => $id_header, 'ID_EMP' => $id_emp, 'KEY_HEADER' => $key_header]
        ]);
    }

}
