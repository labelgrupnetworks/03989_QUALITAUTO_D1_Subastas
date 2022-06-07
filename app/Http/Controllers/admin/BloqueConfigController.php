<?php

namespace App\Http\Controllers\admin;
use DB;
use Request;
use Controller;
use View;
use Session;
use Redirect;
use Input;
use File;
use Config;
use \Cache;
use \Carbon\Carbon;
use App\Models\Bloques;

class BloqueConfigController extends Controller
{

    //Ver todos los Bloques que hay
    public function index()
    {   
        $content = new Bloques();
        $data = $content->tableBloque();
        return \View::make('admin::pages.bloque',array('data' => $data));
    }
    
    //Ver la informacion del bloque si no existe todo vacio
    public function SeeBloque($id = NULL){
        $content = new Bloques();
        $bloque = $content->infBloque($id);
        if(!count($bloque)>0){
            $bloque =null;
            
        }
      
        return \View::make('admin::pages.editBloque',array('bloque' => $bloque[0]));
    }
    
    //Editar el bloque
     public function EditBloque(){

        $content = new Bloques();

        $type=Request::input('type');
        $title=Request::input('title');
        $consulta=Request::input('consulta');
        $enabled_temp=Request::input('enabled');
        $id=Request::input('id');
        $key_name=Request::input('key_name');
        $cache = Request::input('cache');

        //Comprobamos que no haya injection sql 
        $val_injection = $this->injectionSQL($consulta);
        //Si no hay update o hace un insert dependiendo de si existe
        
        if(!$val_injection){
            if($enabled_temp=='on'){
                $enabled = 1;
            }else{
                $enabled = 0;
            }
            
            if($id < 1){
               $id_bloque = $content->NewBloque($type,$title,$consulta,$enabled,$key_name,$cache);
            }else{
                $content->UpdateBloque($type,$title,$consulta,$enabled,$key_name,$id,$cache);
            }
            
            $claves_temp=$this->sqlClaves($consulta);
            
            if(!$claves_temp){
                $value =DB::select($consulta);
                if($cache >= 1){
                    $expiresAt = Carbon::now()->addMinutes($cache);
                    Cache::put($key_name, $value, $expiresAt);
                }
                $num_resultados = count($value);
                return array($num_resultados,$id);
            }else{
                return("claves");
            }
            
        }else{
            return("injection");
        }
     }
     
     function injectionSQL($consulta){
        $injection['in'] =  array('delete','insert','created','drop','alter','update');
        $val_injection= false;
        
        
        foreach ($injection['in'] as $valor){
            $consulta_temp=stripos($consulta, $valor);
            if($consulta_temp !== false){
                $val_injection = true;
                break;
            }
        }
        return  $val_injection;
     }
     
     function sqlClaves($consulta){
        $injection['claves'] =  array('{','}');
        $val_injection= false;
        foreach ($injection['claves'] as $valor){
            $consulta_temp=stripos($consulta, $valor);
            if($consulta_temp !== false){
                $val_injection = true;
                break;
            }
        }
        return  $val_injection;
     }
        
    

}
