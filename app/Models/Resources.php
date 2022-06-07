<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;

class Resources extends Model
{
        public $emp ;

        public function __construct()
        {
            $this->emp = Config::get('app.main_emp');
        }

        #Ver todos los recursos
        public function tableResource($value_where = null){



            $sql = "Select  (select id_web_resource from web_resource_banner where  web_resource_banner.id_web_resource = WEB_RESOURCE.id_web_resource  group by id_web_resource) id_web_resource_banner , WEB_RESOURCE.id_web_resource , WEB_RESOURCE.title, WEB_RESOURCE.CONTENT
                    from WEB_RESOURCE

                    where WEB_RESOURCE.ID_EMP = :emp
                    $value_where
                    ORDER BY WEB_RESOURCE.title asc ";





            $bindings = array(
                    'emp'            => $this->emp,
                    );
        $table =  DB::select($sql, $bindings);

            return $table;
        }


        public function infResource($id){

            $value = DB::table('WEB_RESOURCE')
                    ->where('ID_WEB_RESOURCE',$id)
                    ->where('ID_EMP',$this->emp)
                    ->get();
            return $value;
        }

         public function getResourceKey($value_where = null){

              if(!empty($value_where) && ($value_where == 'A')){
                  $value_where = "and WEB_RESOURCE.type IN ('A') ";
              }

            if(!empty($value_where)){
                $sql = "Select *"
                        . "from WEB_RESOURCE "
                        . "where WEB_RESOURCE.ID_EMP = :emp "
                        . $value_where
                        . "and ROWNUM <= 10 "
                        . "ORDER BY WEB_RESOURCE.ID_WEB_RESOURCE asc ";


                $bindings = array(
                        'emp'            => $this->emp,
                        );
                $table =  DB::select($sql, $bindings);
            }else{
                $table = null;
            }

            return $table;
        }

        #Crear un nuevo Recuros
        public function newResource($name,$url_link,$new_windows,$type,$enabled,$html,$file_url,$cache,$cod_sec){

            $max_id = DB::table('WEB_RESOURCE')->max('ID_WEB_RESOURCE');
            $max_id = $max_id +1;

            DB::table('WEB_RESOURCE')->insert(['ID_WEB_RESOURCE' => $max_id,'TITLE'=>$name,'URL_RESOURCE'=>$file_url,'URL_LINK'=>$url_link,
                'NEW_WINDOW'=>$new_windows,'TYPE'=>$type,'CONTENT'=>$html,'ENABLED'=>$enabled,'ID_EMP'=>$this->emp,'CREATION_DATE'=>date("Y-m-d H:i:s"),
                'UPDATE_DATE'=>date("Y-m-d H:i:s"),'TIME_CACHE'=>$cache,'COD_BANNER_SEC'=>$cod_sec]);

            return $max_id;
        }
        #Updatear un recurso
        public function updateResource($name,$url_link,$new_windows,$type,$enabled,$id,$html,$file_url,$cache){
           $sql = "update WEB_RESOURCE set TITLE = :title, URL_RESOURCE = :url_resource, URL_LINK = :url_link, NEW_WINDOW = :new_window, TYPE= :type, CONTENT= :content,"
                    . "ENABLED = :enabled, TIME_CACHE = :cache, UPDATE_DATE= SYSDATE WHERE ID_WEB_RESOURCE = :id and ID_EMP = :emp";

           $params = array(
               'title' => $name,
               'url_resource' => $file_url,
               'url_link' => $url_link,
               'new_window' => $new_windows,
               'type' => $type,
               'content' => $html,
               'enabled' => $enabled,
               'id' => $id,
               'emp' => $this->emp,
               'cache'  => $cache,
            );

           DB::select($sql,$params);


        }

        public function delete_Resource($id){
            DB::table('WEB_RESOURCE')
            ->where('ID_WEB_RESOURCE',$id)
            ->where('ID_EMP',$this->emp)
            ->delete();
        }

        public function delete_ResourceBanner($id){
            DB::table('WEB_RESOURCE_BANNER')
            ->where('ID_WEB_RESOURCE',$id)
            ->delete();
        }



}
