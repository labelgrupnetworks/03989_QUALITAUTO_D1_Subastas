<?php

return \App\libs\TradLib::getTranslations(Config::get('app.locale'),'001'); 
/*
include 'app.php';

$sql = "SELECT WEB_TRANSLATE_HEADERS.KEY_HEADER,WEB_TRANSLATE_KEY.KEY_TRANSLATE,WEB_TRANSLATE.WEB_TRANSLATION "
            . "FROM WEB_TRANSLATE_HEADERS "
            . "JOIN WEB_TRANSLATE_KEY ON (WEB_TRANSLATE_HEADERS.ID_HEADERS = WEB_TRANSLATE_KEY.ID_HEADERS_TRANSLATE AND WEB_TRANSLATE_KEY.ID_EMP = :emp) "
            . "JOIN WEB_TRANSLATE ON (WEB_TRANSLATE_KEY.ID_KEY = WEB_TRANSLATE.ID_KEY_TRANSLATE AND WEB_TRANSLATE.ID_EMP = :emp) "
            . "WHERE WEB_TRANSLATE.LANG = 'ES' order by key_header, key_translate";
             
             $params = array(
               'emp' => Config::get('app.emp'),
            );
    $data = CacheLib::useCache('translate_es',$sql, $params);           
    $translate = array(); 

    foreach ($data as $key => $value){
        if(empty($translate[$value->key_header])){
            $translate[$value->key_header] = array();
        }
         $translate[$value->key_header][$value->key_translate] = $value->web_translation;
    }

 return array_merge($lang, $translate);
*/    
