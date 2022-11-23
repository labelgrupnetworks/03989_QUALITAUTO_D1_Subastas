<?php

namespace App\Http\Controllers\webservice;

use App\Http\Controllers\apilabel\ApiLabelController;

use App\Http\Controllers\webservice\LogChangesController;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Controller;
use Config;
use Request;

use DB;
use Exception;


use Illuminate\Database\QueryException;


class WebServiceController extends ApiLabelController
{
	public  $parameters = array();
# Las funciones se crearan con nomenclatura camelCase, Nunca usar separador _  ya que se usará  para agrupar funciones a permisos
# por ejemplo nombreFuncion_APP podrá acceder cualquiera que tenga permisos de APP

	public function index($function){

		try{



			$functionExplode = explode("_", $function);
			if(!empty($functionExplode[1])){
				#la función está agrupada, iremos a su controlador y la ejecutaremos
				$rutaController = "App\Http\Controllers\webservice\WebService".$functionExplode[1]."Controller";
				$controller = new $rutaController();
				$controller->parameters = request("request");
				return $controller->{$functionExplode[0]}();


			}else{
				#ejecutamos la función en este controlador
				$this->parameters = request("request");
				return $this->{$function}();
			}


		}catch(\Exception $e){
			return $this->exceptionApi($e);
		}
	}

	public function logChanges(){
		try {
			$logchanges = new LogChangesController();

			$model =$this->parameters["model"];
			if(!empty($model)){

				return $logchanges->{"logChanges". ucfirst($model)}($this->parameters);
			}
		}catch(\Exception $e){
			return $this->exceptionApi($e);
		}
	}

	public function log(){
		try {
			$log = new LogController();

			$model = $this->parameters["model"];
			if(!empty($model)){
				#hacemos aquí la validación ya que así no hay que hacerla dentro de cada función
				$rules = array('startDate' => "date",'endDate' => "date" );
				$this->validator($this->parameters, $rules);
				$startDate = $this->parameters['startDate']?? null;
				$endDate = $this->parameterst['endDate']?? null;
				return $log->{"log". ucfirst($model)}($startDate, $endDate);
			}
		}catch(\Exception $e){
			return $this->exceptionApi($e);
		}
	}


}
