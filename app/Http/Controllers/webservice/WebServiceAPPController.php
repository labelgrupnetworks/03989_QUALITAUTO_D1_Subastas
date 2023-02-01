<?php

namespace App\Http\Controllers\webservice;

use Config;
use App\Http\Controllers\webservice\WebServiceController;
use App\Models\User;
use App\Models\V5\FxCliWeb;
use App\Models\V5\FxCli;
use App\Models\V5\FgSub;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\usuario\AdminClienteController;
use stdClass;
use App\Http\Controllers\apilabel\ClientController;
use App\Models\V5\FsPaises;
use App\Http\Controllers\apilabel\LotController;
use App\Http\Controllers\apilabel\ApiLabelException;
use App\Models\V5\AucSessionsFiles;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgHces1;
use App\Models\V5\FgOrtsec0;
use Illuminate\Support\MessageBag;
use PhpParser\Node\Stmt\Return_;

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

		#MODIFICAR DATOS DEL CLIENTE
		public function updateClient(){
			$adminClientController = new  AdminClienteController();
			$client = new stdClass();

			#comprobamos si han pasado el id del cliente en base de datos es cod2_cliweb
			$this->missFields(["codcli"]);

			#comprobamos si han pasado mail, si lo han pasado envía error
			$this->cantModifiedFields(["email"]);

			#hacemos query para conseguir el idorigincli
			$usuario = FxCli::select("cod2_cli as idorigincli")->where('cod_cli', $this->parameters['codcli'])->first();
			$this->parameters['idorigincli'] = $usuario->idorigincli;

			if (!empty($this->parameters["password"])) {
				$client->password = $adminClientController->passwordEncrypt($this->parameters["password"]);
			}

			#comprobamos si han pasado los parametros de nombre y apellidos si solo pasa uno de ellos no se actualiza
			if ((isset($this->parameters["name"]) && isset($this->parameters["lastname"])) || (!empty($this->parameters["name"]) && !empty($this->parameters["lastname"]))) {
				if(Config::get('app.name_without_coma', 0)){
					$client->name = trim($this->parameters['name']) . ' ' . trim($this->parameters['lastname']);
				} else {
					$client->name = trim($this->parameters['lastname']). ', '. trim($this->parameters['name']);
				}
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

			$clientController = new clientController();
			return $clientController->updateClient([(array)$client]);

		}

		#FIN MODIFICAR DATOS DEL CLIENTE



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

		private function cantModifiedFields($fields, $numItem=1){
			$messageBag = new MessageBag();
			foreach($fields as $field){
				if(!empty($this->parameters[$field])){
					$messageBag->add("Error",trans('apilabel-app.validation.not_be_modified',["attribute" => $field]) );
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

			$user = FxCliWeb::select('NOM_CLIWEB, TEL1_CLI, FECNAC_CLI, CIF_CLI, CODPAIS_CLI, PRO_CLI, SG_CLI, concat(DIR_CLI, DIR2_CLI) as address, POB_CLI, CP_CLI')
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
				"country" => $user->codpais_cli,
				"province" => $user->pro_cli,
				"street" => $user->sg_cli,
				"address" => $user->address,
				"city" => $user->pob_cli,
				"zipcode" => $user->cp_cli,
			];

			return $this->responseSuccsess("User Info", $userResponse);
		}

		public function getCategories()
		{
			#conseguimos las categorias
			$categories = FgOrtsec0::select('LIN_ORTSEC0, DES_ORTSEC0')->where("SUB_ORTSEC0", "0")->orderby("DES_ORTSEC0")->get();

			#las ponemos como clave valor poniendo lin_ortsec0 como clave y des_ortsec0 como valor
			$categoriesResponse = [];
			foreach ($categories as $category) {
				$categoriesResponse[] = [
					$category->lin_ortsec0 => $category->des_ortsec0
				];
			}

			return $this->responseSuccsess("Categories", $categoriesResponse);
		}

		# FALTA QUE SEA MULTIIDIOMA
		public function getActiveAuctions()
		{
			#hacemos una consulta para conseguir las subastas activas poniendo S para definir que no están cerradas
			$sessions = $this->getAuctions('S');
			return $this->responseSuccsess("Active Auctions", $sessions);

		}

		public function getHistoricAuctions()
		{

			#hacemos una consulta para conseguir las subastas históricas poniendo H
			$sessions = $this->getAuctions('H');
			return $this->responseSuccsess("Active Auctions", $sessions);

		}

		#BUSCA UNA ALTERNATIVA DE CALCULAR EL MIN Y MAX DE LOTES NO ME GUSTA EL GROUP BY
		private function getAuctions($status)
		{
			#mandamos query para conseguir todas las sesiones
			$sessions = FgAsigl0::select('SUB_ASIGL0 as cod_sub', '"id_auc_sessions"','subc_sub' ,'"reference"', '"name"', '"description"', 'tipo_sub', '"start" as session_start', '"end" as "session_end"')
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->groupBy('SUB_ASIGL0', '"id_auc_sessions"', '"reference"', '"name"', '"description"', 'tipo_sub', 'SUBC_SUB','"start"', '"end"')
			->where('subc_sub', '=', $status)
			->orderBy('session_start', 'desc')
			->get();

			#inicializamos el array de subastas
			$sessionsResponse = [];

			foreach ($sessions as  $session) {

				$sessionsResponse[] = [
					"codsession" => $session->id_auc_sessions,
					"title" => $session->name,
					"type" => $session->tipo_sub,
					"status" => $session->subc_sub,
					"start" =>  $session->tipo_sub == "O"? $session->session_end :  $session->session_start,
					"image" => $this->getAuctionImage($session->cod_sub, $session->reference),
					
				];


			}


			return $sessionsResponse;
		}

		private function getAuctionImage($cod_sub, $reference)
		{
			#intentamos conseguir imagen de sesión
			$image_to_load = \Tools::url_img_session("subasta_large", $cod_sub, $reference);

			#si no existe conseguimos la imagen de la subasta
			if (!file_exists($image_to_load) || filesize($image_to_load) < 500) {
				$image_to_load = \Tools::url_img_auction("subasta_large", $cod_sub);
			}

			return $image_to_load;
		}

		# FALTA QUE SEA MULTIIDIOMA, BUSCA UNA ALTERNATIVA DE CALCULAR EL MIN Y MAX DE LOTES, DE MANERA QUE NO TENGAS PROBLEMAS DE ARGAR LE CLOB DE DESCDET_SUB POR NECESIDAD DE HACER GROUP BY
		public function getAuction()
		{
			#comprobamos campo id_auc_sessions
			$this->missFields(['codsession']);

			#hacemos query para recoger datos de la subasta
			$session = FgAsigl0::select('SUB_ASIGL0 as cod_sub',  '"id_auc_sessions"', '"reference"', '"name"', '"description"', 'tipo_sub', 'SUBC_SUB', '"start" as session_start', '"end" as "session_end"', 'min(ref_asigl0) as init_lot', 'max(ref_asigl0) as end_lot')
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->groupBy('SUB_ASIGL0', '"id_auc_sessions"', '"reference"', '"name"', '"description"', 'tipo_sub', 'SUBC_SUB','"start"', '"end"')
			->where('"id_auc_sessions"', $this->parameters['codsession'])
			->first();

			#si no existe la sesion devolvemos error
			if (!$session) {
				return $this->responseError("session don't exist");
			}

			#conseguimos descripción de la subasta
			$description = FgSub::select('DESCDET_SUB')->where('COD_SUB', $session->cod_sub)->first();

			#hacemos query para recoger archivos de la subasta
			$files = AucSessionsFiles::where('"auction"', $session->cod_sub)->get();

			#conseguimos constante de tipos de archivos
			$TYPE_FILES = AucSessionsFiles::TYPE_FILES;

			#inicializamos arrays
			$filesArray = [];
			$sessionArray = [];

			#si existen archivos los guardamos en un array
			if ($files) {
				#recorremos los archivos
				foreach ($files as $file) {
					$filesArray[] = [
						"title" => $file->description,
						"type" => $TYPE_FILES[$file->type],
						"url" => Config::get('app.url').$file->path,
					];
				}
			}

			#conseguimos la imagen de la subasta
			$image_to_load = $this->getAuctionImage($session->cod_sub, $session->reference);

			#si la subasta es activa entra y comprueba si es online


					#si es online se guarda la fecha de fin de sesión
					$sessionArray[] = [
						"codsession" => $session->id_auc_sessions,
						"title" => $session->description,
						"type" => $session->tipo_sub,
						"image" => $image_to_load,
						"files" => $filesArray,
						#"init_lot" => $session->init_lot,
						#"end_lot" => $session->end_lot,
						"description" => $description->descdet_sub,
					];

					#en historica no tienen start
					if ($session->subc_sub == "S") {
						$sessionArray["start"] =$session->tipo_sub == "O"?$session->session_end :  $session->session_start;
					}



			return $this->responseSuccsess("Auction Info", $sessionArray);

		}

}
