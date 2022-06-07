<?php

namespace App\Http\Controllers\admin;

use Request;
use Controller;
use View;
use Session;
use Redirect;
use Input;
use File;
use Config;

use App\Models\Content;

class AdminConfigController extends Controller
{
    public function index()
    {   
        $data['web_config'] =  array();
        $data['config_pago'] =  array();
        $data['fields_hidder'] = array('tr_show_pujas','tr_show_chat','tr_show_video','tr_show_buscador'
                                       ,'tr_show_adjudicaciones','tr_show_info','tr_show_aslot','tr_show_ordenes_licitacion');
        
        $content = new Content();
        
        $web_config_pago_temp = $content->configPagoWeb();     
        $web_config_tmp = $content->configWeb();
        
        foreach ($web_config_tmp as $config) {
            $data['web_config'][$config->key] = $config->value;
        }
        $data['config_pago'] = $web_config_pago_temp;
        return \View::make('admin::pages.config', array('data' => $data));
    }
    
    public function save(Request $request){
        
        #recojemos todos los valores
        $request_conf = Request::all(); 
        $content = new Content();
        
        $emp = $content->NumEmp();
        $max = $content->MaxConfWeb();
        $max = $max  + 1;
        $config = $content->WebConf($emp);
               
        foreach($config as  $obj){
            $web_config_array[] = $obj->key;
        }
        
        
        foreach($request_conf as $name_conf => $conf){
            #Comprovamos si la key existe en base de datos, si existe hace un update si no insert
            if(in_array($name_conf,$web_config_array)){
                $content->configWebUpdate($name_conf,$conf,$web_config_array,$max,$emp);
            }else{
                $description = trans('admin-app.config.'.$name_conf.'_desc');
                $content->configWebInsert($name_conf,$conf,$web_config_array,$max,$emp,$description);
                $max = $max  + 1;
            }
        }
        
    }
	
}
