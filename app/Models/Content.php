<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
use Config;
use \Cache;
use \Carbon\Carbon;

class Content extends Model
{
	public $tipo;
	public $lang;
	public $id;
	public $slug;
	public $anchor;
	public $parent;

	public function setContent($content, $content_type, $content_lang){
		$this->setContentType($content_type);
		$this->setContentLang($content_lang);

		//Creamos el content
	}

	private function setContentType($content_type){
		$found_content_type = $this->getContentType($content_type);
		//Si no existe el content_type lo creamos.
		//print_r($found_content_type); die;
	}

	private function setContentLang($content_lang){
		$found_content_lang = $this->getContentLang($content_lang);
		//Si no existe el content_lang lo creamos.
	}



	public function getContent()
	{
		$lang 	= "";
		$tipo 	= "";
		$id 	= "";
		$slug 	= "";
		$anchor = "";
		$parent = "";

		$bindings = array(
            'emp'       => Config::get('app.emp'),
        );

		if(!empty($this->tipo)) {
			$tipo = " AND wc.ID_CONTENT_TYPES = :tipo";
			$bindings['tipo'] = $this->tipo;
		}

		if(!empty($this->lang)) {
			$lang = " AND wcl.id_lang = :lang";
			$bindings['lang'] = $this->lang;
		}

		if(!empty($this->id)) {
			$id = " AND wc.ID_CONTENT = :id";
			$bindings['id'] = $this->id;
		}

		if(!empty($this->slug)) {
			$slug = " AND wcl.slug = :slug";
			$bindings['slug'] = $this->slug;
		}

		if(!empty($this->anchor)) {
			$anchor = " AND wc.anchor = :anchor";
			$bindings['anchor'] = $this->anchor;
		}

		/*
		# Opciones de Parent en caso de que no esté vacío
		if(isset($this->parent) || $this->parent == 0) {

			# Valor del parent en caso de que queramos null y 0 o bien solo 0
			if(strstr($this->parent, 'null')) {

				$strParent = explode(',', $this->parent)[0];
				$parent = " AND (wc.id_parent = :parent OR wc.id_parent is null)";

			} else {

				# Si el parent es null, significa que no hemos establecido el parametro al declarar el objeto
				# Con lo que cogeremos todos los contenidos en parent = NULL
				if(is_null($this->parent)) {
					$parent = " AND wc.id_parent is null";
				} else {
					$strParent = $this->parent;
					$parent = " AND wc.id_parent = :parent";
					$bindings['parent'] = $strParent;
				}

			}

		} */

		/**
	    * @param  parent=0, parent='0,null', parent=int
	    */

		if(isset($this->parent)) {

			# Solo mostraremos los Parent = 0
			if($this->parent == 0) {
				$parent = " AND wc.id_parent = 0";
			}

			if(is_numeric($this->parent) && $this->parent > 0) {
				$parent = " AND wc.id_parent = :parent";
				$bindings['parent'] = $this->parent;
			}

			# Si el parent es null, significa que no hemos establecido el parametro al declarar el objeto
			# Con lo que cogeremos todos los contenidos en parent = NULL
			if(is_null($this->parent)) {
				$parent = " AND wc.id_parent is null";
			}

			# Valor del parent en caso de que queramos null y 0 o bien solo 0
			if($this->parent == '0,null') {
				$parent = " AND (wc.id_parent = 0 OR wc.id_parent is null)";
			}
		}



		$sql = "SELECT * FROM WEB_CONTENT wc
					LEFT JOIN WEB_CONTENT_LANG wcl
						ON (wc.id_content = wcl.id_content)
			        LEFT JOIN WEB_CONTENT_TYPES wct
			          ON (wc.ID_CONTENT_TYPES = wct.ID_CONTENT_TYPE)
				WHERE wc.status = 1 AND wc.id_emp = :emp  ". $lang . $tipo . $id . $slug . $anchor . $parent . " ORDER BY wc.POSITION ASC";



                $key =$this->slug;

                if(!empty(Config::get('app.time_cache'))){
                     $expiresAt = Carbon::now()->addMinutes(Config::get('app.time_cache'));
                    if (Cache::has($key)){
                        $res = Cache::get($key);
                    } else {
                        $res = DB::select($sql, $bindings);
                        Cache::put($key, $res, $expiresAt);
                    }
                }else{
                    $res = DB::select($sql, $bindings);
                }
        return $res;
	}

	private function getContentType($content_type){
		$result = '';
	    $res = DB::select("SELECT id_content_type FROM WEB_CONTENT_TYPES
            WHERE
                TYPE = :content_type",
                array('content_type' => $content_type)
        );

        if (empty($res[0])){
        	$result = FALSE;
        }else{
        	$result = $res[0]->id_content_type;
        }

	   return $result;
	}

	private function getContentLang($content_lang){

	}

        #Select de la configuracion
 	public function configWeb(){
            $params = Config::get('app.emp');
            $web_config = DB::table('WEB_CONFIG')
                        ->select("KEY","VALUE")
                        ->where('EMP',$params)
                        ->get();
            //$web_config = DB::select("SELECT key,value,info FROM WEB_CONFIG");
            return $web_config;
        }

        #Recoger cuales son de pago
        public function configPagoWeb(){
             return $params = Config::get('app.config_general_admin');
        }

        #Guardar configuracion de la web
        public function configWebUpdate($name_conf,$conf,$web_config_array,$max,$emp){
            DB::table('WEB_CONFIG')
            ->where('EMP',$emp)
            ->where('KEY',$name_conf)
            ->update(['VALUE' => $conf]);
        }

        #Si no la encuentra crea una nueva configuracon
        public function configWebInsert($name_conf,$conf,$web_config_array, $max, $emp, $description){
            $bindings = array(
				"max" => $max,
				"name_conf" => $name_conf,
				"conf" => $conf,
				"emp" => $emp,
				"description" => $description,
				);
			DB::select("INSERT INTO WEB_CONFIG (ID_WEB_CONFIG,KEY,VALUE,EMP,INFO) VALUES (:max,:name_conf, :conf, :emp, :description)", $bindings );
        }

        #Busca el id maximo de WEB CONFIG
        public function MaxConfWeb(){
            $max_id = DB::table('WEB_CONFIG')
                        ->max('ID_WEB_CONFIG');
            return $max_id;
        }

        #Busca Numero de empresa
        public function NumEmp(){
            return $params = Config::get('app.emp');
        }

        #Busca todas las Keys de WEB Config
        public function WebConf($emp){
            $web_config = DB::table('WEB_CONFIG')
                        ->select("KEY")
                        ->where('EMP',$emp)
                        ->get();
            return $web_config;
        }


}
