<?php

namespace App\Http\Controllers\webservice;

use App\Http\Controllers\apilabel\ApiLabelController;

use App\Http\Controllers\webservice\LogChangesController;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
#para APP
use App\Models\User;
use App\Models\V5\FxCliWeb;
use App\Http\Controllers\UserController;

#fin controllers para APP
use Controller;
use Config;
use Request;

use DB;
use Exception;


use Illuminate\Database\QueryException;


class WebServiceController extends ApiLabelController
{
private $parameters = array();
# Las funciones se crearan con nomenclatura camelCase, Nunca usar separador _ ya que se usará  para agrupar funciones a permisos
# y una vez lleguemos al controlador se eliminará la parte derecha de _

	public function index($functionTmp){

		try{
			$this->parameters = request("request");
			$functionExplode = explode("_", $functionTmp);
			if(!empty($functionExplode[1])){
				#si es compuesto cogemos la funcion, está en la parte izquierda del separador _
				$function = $functionExplode[0];
			}else{
				#si no es compuesto cogemos la funcion que pasan
				$function =$functionTmp;
			}

			return $this->{$function}();
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

	#FUNCIONES APP
		#REGISTRO
		public function checkInUseNif(){



			$user = new User();
			$user->nif = mb_strtoupper(trim($this->parameters['nif']));
			$exist = $user->getUserByNif("N");


			if(empty($exist)){

				return $this->responseSuccsess();
			}else{
				#COMPROBAMOS SI TIENE USUARIO WEB, SI YA TIENE USUARIO WEB NO DEBE PODER CONTINUAR
				$cliWeb = FxCliWeb::select("cod_cliweb")->where('cod_cliweb', $exist[0]->cod_cli)->first();
				if(empty($cliWeb)){
					return $this->responseSuccsess();
				}else{
					return $this->responseError("NIF in use");
				}


			}
		}

		public function checkInUseEmail(){


			$user = new User();
			$email = mb_strtoupper(trim($this->parameters['email']));
			$exist = $user->EmailExist($email,Config::get('app.emp'),Config::get('app.gemp'));

			if(empty($exist)){
				return $this->responseSuccsess();
			}else{
				return $this->responseError("Email in use");
			}
		}

		public function addressFields(){
			try{
				$lang = $this->parameters['lang'];
				$data = file_get_contents("app_movile/register/address.fields_".$lang.".json");
				$addressFields = json_decode($data, true);
				#si hiciera falta añadir campo para un cliente lo hacemos sobre el objeto addressFields
				return $this->responseSuccsess("Address fields for $lang", $addressFields);
			}catch(\Exception $e){
				return $this->exceptionApi($e);
			}
		}

		public function personalInfoFields(){
			try{
				$lang = $this->parameters['lang'];
				$data = file_get_contents("app_movile/register/personal_info.fields_".$lang.".json");
				$personalInfoFields = json_decode($data, true);
				#si hiciera falta añadir campo para un cliente lo hacemos sobre el objeto personalInfoFields
				return $this->responseSuccsess("Address fields for $lang", $personalInfoFields);
			}catch(\Exception $e){
				return $this->exceptionApi($e);
			}
		}
		#FIN REGISTRO



	#FIN FUNCIONES APP


}
