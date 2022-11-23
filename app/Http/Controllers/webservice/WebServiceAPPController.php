<?php

namespace App\Http\Controllers\webservice;

use Config;
use App\Http\Controllers\webservice\WebServiceController;
use App\Models\User;
use App\Models\V5\FxCliWeb;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\usuario\AdminClienteController;
use stdClass;
use App\Http\Controllers\apilabel\ClientController;
use App\Models\V5\FsPaises;
use App\Http\Controllers\apilabel\ApiLabelException;

use Illuminate\Support\MessageBag;
class WebServiceAPPController  extends WebServiceController{


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

		public function translate(){
			$translate = \App\libs\TradLib::getAppMobileTranslation(explode(",",env('LOCALES_KEYS', 'es')));
			return $this->responseSuccsess("translate", $translate);
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
				$file="register/address.fields.json";
				$data = $this->loadthemeFiles($file);

				$addressFields = json_decode($data, true);

				return $this->responseSuccsess("Address fields", $addressFields);
			}catch(\Exception $e){
				return $this->exceptionApi($e);
			}
		}

		public function personalInfoFields(){
			try{
				$file="register/personal_info.fields.json";
				$data = $this->loadthemeFiles($file);
				$personalInfoFields = json_decode($data, true);

				return $this->responseSuccsess("Personal fields", $personalInfoFields);
			}catch(\Exception $e){
				return $this->exceptionApi($e);
			}
		}

		public function createClient(){
			$adminClientController = new  AdminClienteController();
			$client = new stdClass();
			$client->idorigincli = $adminClientController->newCod2Cli();

			$this->missFields(["password", "lastname", "name"]);

			$client->password = $adminClientController->passwordEncrypt($this->parameters["password"]);

			if(Config::get('app.name_without_coma', 0)){
				$client->name = trim($this->parameters['name']) . ' ' . trim($this->parameters['lastname']);
			}
			else{
				$client->name = trim($this->parameters['lastname']). ', '. trim($this->parameters['name']);
			}

			$fields = $this->parameters;
			#quitamos los campos ya tratados
			unset($fields["password"]);
			unset($fields["lastname"]);
			unset($fields["name"]);
			# automatizamos el resto de campos de manera que la APP usará el mismo nombre en los campos que la API, así puedo recogerlos automaticamente
			foreach($fields as $field =>$value){
				$client->{$field} = $value;
			}

			$clientController = new  clientController();
			return $clientController->createClient([(array)$client]);

		}
		#FIN REGISTRO




		public function login()
		{
			$this->missFields(["password", "email"]);
			$email = $this->parameters['email'];
			$password = $this->parameters['password'];

			$user = (new UserController())->GetInfUser($email, $password);

			if (empty($user)) {
				return $this->responseError("User not exist");
			}
			$userModel = new User();
			$userModel->cod = $user->cod_cliweb;

			$token = $userModel->updateToken();

			$userResponse = [
				"codcli" => $user->cod_cliweb,
				"name" => $user->nom_cliweb,
				"email" => $user->email_cliweb,
				"token" => $token
			];

			return $this->responseSuccsess("SUCCESS", $userResponse);
		}



		#intenta cargar el archivo del theme y si no está lo carga del default
		private function loadthemeFiles($file){

			$default_path = storage_path("app/app_movile/default/").$file;
			$theme_path = storage_path("app/app_movile/themes/".Config::get('app.theme')."/").$file;
			#si no existe el archivo en theme cargamos el generico

			if(file_exists($theme_path)){
				return file_get_contents($theme_path);
			}else{
				return file_get_contents($default_path);
			}
		}

		private function missFields($fields, $numItem=1){
			$messageBag = new MessageBag();
			foreach($fields as $field){
				if(empty($this->parameters[$field])){
					$messageBag->add("Error",trans('apilabel-app.validation.required',["attribute" => $field]) );
				}
			}

			if($messageBag->isNotEmpty()){
				$errorsItem["item_".$numItem] =$messageBag;
				throw new ApiLabelException(trans('apilabel-app.errors.validation'),$errorsItem);
			}
		}


		public function countries(){
			$langs = explode(",",env('LOCALES_KEYS', 'es'));
			$countriesResponse = [];
			foreach ($langs as $lang) {
				$lang = trim($lang);
				$countriesResponse[$lang] = [];
				$countries = FsPaises::select("cod_paises", "nvl(FSPAISES_LANG.DES_PAISES_LANG,FSPAISES.des_paises) des_paises")
				->leftJoin('FSPAISES_LANG', function ($join) use ($lang) {
					$join   ->on("FSPAISES_LANG.COD_PAISES_LANG", "=", "FSPAISES.cod_paises")
							->on("FSPAISES_LANG.LANG_PAISES_LANG", "=", "'".\Tools::getLanguageComplete($lang)."'");
				})->orderby("des_paises_lang")->get();

				foreach($countries as $country){
					$countriesResponse[$lang] += [$country->cod_paises => $country->des_paises];
				}
			}

			return $this->responseSuccsess("Countries", $countriesResponse);
		}

		public function getUserInfo() {
			$codcli = $this->parameters['codcli'];
			$token = $this->parameters['token'];

			$this->missFields(["codcli", "token"]);

			$user = FxCliWeb::select('NOM_CLIWEB, TEL1_CLI, FECNAC_CLI, CIF_CLI, PAIS_CLI, PRO_CLI, SG_CLI, concat(dir_cli, dir2_cli) as address, POB_CLI, CP_CLI')
			->joinCliCliweb()->where("COD_CLI", $codcli)->where("TK_CLIWEB", $token)->first();

			if (empty($user)) {
				return $this->responseError("User not exist");
			}

			$nameAndLastname = explode(",", $user->nom_cliweb);

			$userResponse = [
				#si no hay dos elementos es que no hay coma, por lo que no podemos seprarlos en apellido y nombre
				"name" => $nameAndLastname[1]??  $nameAndLastname[0] ,
				"lastname" => empty($nameAndLastname[1]) ? "" : $nameAndLastname[0],
				"phone" => $user->tel1_cli,
				"birthdate" => $user->fecnac_cli,
				"idnumber" => $user->cif_cli,
				"country" => $user->pais_cli,
				"province" => $user->pro_cli,
				"street" => $user->sg_cli,
				"address" => $user->dir_cli,
				"city" => $user->pob_cli,
				"zipcode" => $user->cp_cli,
			];

			return $this->responseSuccsess("User Info", $userResponse);
		}

}
