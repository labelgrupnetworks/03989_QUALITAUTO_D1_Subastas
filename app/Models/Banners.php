<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;

class Banners extends Model
{

    #Ver los banners
        public $emp ;

        public function __construct()
        {
            $this->emp =Config::get('app.main_emp');
        }

        public function tableBanners($value, $cod_sec = null){

            $t = DB::table('WEB_BANNER')
                    ->select('title','id_web_banner','key_name')
                    ->where('ID_EMP',$this->emp)
                    ->where('type_web_banner',$value)
                    ->orderBy('id_web_banner','desc');
           if(!empty($cod_sec)){
               $t->where('cod_sec_web_banner',$cod_sec);
           }
           $table =      $t->get() ;
            return $table;
        }

        public function GetBanners($id){

            $value = DB::table('WEB_BANNER')
                    ->where('ID_WEB_BANNER',$id)
                    ->where('ID_EMP',$this->emp)
                    ->first();
            return $value;

        }

        public function ResoucesBanners($id){

            $value = DB::table('WEB_RESOURCE_BANNER')
                    ->where('ID_WEB_BANNER',$id)
                    ->orderBy('orden','asc')
                    ->get();
            return $value;
        }

        public function GetResourceActivated($cod_banner_sec){
            $emp = Config::get('app.main_emp');

            $resources_tmp = DB::table('WEB_RESOURCE')
                    ->select('title','id_web_resource','type')
                    ->where('ENABLED',1)
                    ->where('ID_EMP',$this->emp)
                    ->where('COD_BANNER_SEC',$cod_banner_sec)
                    ->orderBy('id_web_resource','asc')
                    ->get();
            $resources = array();
            if (count($resources_tmp) > 0 ){
                foreach ($resources_tmp as $resource){
                   $resources[$resource->id_web_resource]  = $resource;
                }
            }


            return $resources;
        }

        #Crear un nuevo banner
        public function newBanners($name,$key_name,$enabled,$type,$cod_sec){
            $emp = Config::get('app.main_emp');

            $max_id = DB::table('WEB_BANNER')->max('ID_WEB_BANNER');
            $max_id = $max_id +1;
			$bindings = array(
				"max_id" => $max_id,
				"name" => $name,
				"key_name" => $key_name,
				"enabled" => $enabled,
				"type" => $type,
				"emp" => $emp,
				"cod_sec" => $cod_sec
	);

			DB::select("INSERT INTO WEB_BANNER (ID_WEB_BANNER,TITLE,ENABLED,KEY_NAME,ID_EMP,CREATION_DATE,UPDATE_DATE,TYPE_WEB_BANNER,COD_SEC_WEB_BANNER) "
			. "VALUES (:max_id, :name, :enabled,:key_name, :emp, SYSDATE,SYSDATE, :type, :cod_sec)", $bindings);

            return $max_id;
        }

        #Crear la relacion entre banner i resouce
        public function new_resouce_banner($id_max, $id_banner,$value,$orden){

            $bindings = array(
				"id_max" => $id_max,
				"value" => $value,
				"id_banner" => $id_banner,
				"orden" => $orden,
			);

			DB::select("INSERT INTO WEB_RESOURCE_BANNER (ID_WEB_RESOURCE_BANNER,ID_WEB_RESOURCE,ID_WEB_BANNER,ORDEN) "
						. "VALUES (:id_max,:value,:id_banner,:orden)", $bindings);
		}

        #ver el max banner resouce
        public function maxBannerResouce(){

            $max_id = DB::table('WEB_RESOURCE_BANNER')
                    ->max('ID_WEB_RESOURCE_BANNER');
            if(!isset($max_id)){
                $max_id=1;
            }
            RETURN $max_id;
        }

        public function updateBanners($name,$key_name,$enabled,$id,$type){

            $bindings = array(
				"id" => $id,
				"name" => $name,
				"key_name" => $key_name,
				"enabled" => $enabled,
				"type" => $type,
				"emp" => $this->emp,
	);

	DB::select("update WEB_BANNER "
	. "set TITLE = :name, KEY_NAME = :key_name, ENABLED = :enabled, UPDATE_DATE = SYSDATE, TYPE_WEB_BANNER = :type"
	. " WHERE ID_WEB_BANNER = :id and ID_EMP = :emp", $bindings);
        }

        public function deleteBannersResources($id){

            DB::select("DELETE "
                     . "FROM WEB_RESOURCE_BANNER "
                     . "WHERE ID_WEB_BANNER = :id ",array("id" => $id) );
        }

        //usamos cache
        public function getBannerByKeyname ($key_name_banner,$max_ban = 10){
            $keyname_cache = $key_name_banner."_banner_".Config::get('app.theme')."_".Config::get('app.main_emp');

            $data = \CacheLib::getCache($keyname_cache);
            if ($data === false){
                $data = DB::table('WEB_BANNER')
                    ->join('WEB_RESOURCE_BANNER','WEB_RESOURCE_BANNER.ID_WEB_BANNER','=','WEB_BANNER.ID_WEB_BANNER')
                    ->join('WEB_RESOURCE','WEB_RESOURCE.ID_WEB_RESOURCE','=','WEB_RESOURCE_BANNER.ID_WEB_RESOURCE')
                    ->select('WEB_RESOURCE.*')
                    ->where('WEB_BANNER.KEY_NAME',$key_name_banner)
                    ->where('WEB_BANNER.ENABLED',1)
                    ->where('WEB_RESOURCE.ENABLED',1)
                    ->where('WEB_BANNER.ID_EMP',$this->emp)
                    ->orderBy('WEB_RESOURCE_BANNER.ORDEN','asc')
                    ->limit($max_ban)
                    ->get();
                \CacheLib::putCache($keyname_cache, $data);
            }
            return $data;

        }

        public function get_banner_sections(){
            return DB::table('WEB_BANNER_SECTION')->get();

        }
          public function get_banner_section_by_cod($cod){
            return DB::table('WEB_BANNER_SECTION')
                    ->where('WEB_BANNER_SECTION.COD_BANNER_SECTION',$cod)
                    ->first();


        }

}
