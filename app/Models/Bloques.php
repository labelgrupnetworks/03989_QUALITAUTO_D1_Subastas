<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Cache;
use Illuminate\Http\Request;
use \Carbon\Carbon;

use DB;
use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;

class Bloques extends Model
{
	public $emp ;

        public function __construct()
        {
            $this->emp = Config::get('app.emp');
        }

        #Ver los bloques
        public function tableBloque(){
            $table = DB::table('WEB_BLOCK')
                    ->select('title','id_web_block')
                    ->where('ID_EMP',$this->emp)
                    ->orderBy('title','desc')
                    ->get();
            return $table;
        }
        #Crear un nuevo bloque
        public function NewBloque($type,$title,$consulta,$enabled,$key_name,$cache){
            $max_id = DB::table('WEB_BLOCK')->max('ID_WEB_BLOCK');
            $max_id = $max_id +1;
			$bindings = array(
				"max_id" => $max_id,
				"key_name" => $key_name,
				"title" => $title,
				"type" => $type,
				"consulta" => $consulta,
				"enabled" => $enabled,
				"emp" => $this->emp,
				"cache" => $cache,
			);
			DB::select("INSERT INTO WEB_BLOCK (ID_WEB_BLOCK,KEY_NAME,TITLE,TYPE,PRODUCTS,ENABLED,ID_EMP,TIME_CACHE) "
						. "VALUES (:max_id,:key_name, :title, :type , :consulta , :enabled ,:emp,:cache)", $bindings);

            return $max_id;
        }
        #Updatear un bloque
        public function UpdateBloque($type,$title,$consulta,$enabled,$key_name,$id,$cache){
            /*DB::select("update WEB_BLOCK set KEY_NAME = '$key_name', TITLE = '$title', TYPE = '$type', PRODUCTS = '$consulta', ENABLED = '$enabled', UPDATE_DATE=SYSDATE"
                    . " WHERE ID_WEB_BLOCK = $id and ID_EMP ='".$this->emp."'");*/
            DB::table('WEB_BLOCK')
            ->where('ID_WEB_BLOCK',$id)
            ->where('ID_EMP',$this->emp)
            ->update(['KEY_NAME' => $key_name,'TITLE' => $title,'TYPE' => $type,'PRODUCTS' => $consulta,'ENABLED' =>$enabled,'TIME_CACHE' => $cache]);
        }


        public function infBloque($id){
            $value = DB::table('WEB_BLOCK')
                    ->where('ID_WEB_BLOCK',$id)
                    ->where('ID_EMP',$this->emp)
                    ->get();
            return $value;
        }

        public function getBlockByKeyname($keyname){
             $resources = DB::table('WEB_BLOCK')
                    ->select('WEB_BLOCK.*')
                    ->where('WEB_BLOCK.KEY_NAME',$keyname)
                    ->where('WEB_BLOCK.ENABLED',1)
                    ->where('WEB_BLOCK.ID_EMP',$this->emp)
                    ->first();

            return $resources;
        }

        public function getResultBlockByKeyname($keyname, $replace = array()){
           try{
                $res = $this->getBlockByKeyname($keyname);
                $key_cache = $keyname;
                if (!empty($res) && !empty($res->products) ){
                    $sql = $res->products;
                    $cache = $res->time_cache;
                    if(!$this->sqldanger($sql)){

                        foreach($replace as $key=>$value ){
                            $sql = str_replace("[".$key."]", $value, $sql);

                            $key_cache.=$key."_".$value;

                        }


                        if(!empty($cache) && $cache > 0){
                            $res = \CacheLib::useCache($key_cache,$sql);
                        }else{
                            $res = DB::select($sql);
                        }

                        return $res;
                    }
                }
                return NULL;
           } catch (\Exception $e) {
                \Log::emergency('Error blockByKeyname: $keyname'.$e);
                return NULL;
            }
        }

        public function sqldanger($sql){
            $dangerous =  array('delete','insert','created','drop','alter','update');
            $sql = strtolower($sql);

            foreach ($dangerous as $danger){
                if( stripos($sql, $danger) !== false){
                    return true;
                }
            }
            return false;
        }


}
