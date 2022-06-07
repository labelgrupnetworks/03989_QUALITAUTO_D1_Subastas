<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;
/**
 * Description of AucIndex
 *
 * @author LABEL-RSANCHEZ
 */
class AucIndexMenu {
    //put your code here
    public $emp ;
        
    public function __construct()
    {
        $this->emp = Config::get('app.emp');
    }

    public function getAucMenu(){
        $data = DB::table('WEB_AUC_INDEX')
                ->where('ID_EMP',$this->emp) 
                ->where('TYPE','P') 
                ->orderBy('orden','asc')
                ->get();
        return $data;        
    }
    
    public function getAucMenuNotParent(){
        $data = DB::table('WEB_AUC_INDEX')
                ->where('ID_EMP',$this->emp) 
                ->where('TYPE','!=','P') 
                ->orderBy('orden','asc')
                ->get();
        return $data;        
    }

    public function saveMenuOrder($id_parent,$i){
            DB::table('WEB_AUC_INDEX')
            ->where('ID_WEB_AUC_INDEX',$id_parent)
            ->update(['ORDEN' => $i]);
                   
    }
    
    public function saveMenuParent($id_parent,$id_children,$i){
            DB::table('WEB_AUC_INDEX')
            ->where('ID_WEB_AUC_INDEX',$id_children)
            ->update(['ORDEN' => $i,'PARENT' => $id_parent]);
                   
    }
    
    public function getMenuParent($id){
         $data = DB::table('WEB_AUC_INDEX')
            ->select('TYPE')
            ->where('ID_WEB_AUC_INDEX',$id)
            ->first();
         return $data;
    }
    

}
