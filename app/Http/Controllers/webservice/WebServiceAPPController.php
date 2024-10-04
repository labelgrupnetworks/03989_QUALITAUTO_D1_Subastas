<?php

namespace App\Http\Controllers\webservice;

use App\Http\Controllers\admin\usuario\AdminClienteController;
use App\Http\Controllers\apilabel\ApiLabelException;
use App\Http\Controllers\apilabel\ClientController;
use App\Http\Controllers\SubastaTiempoRealController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\V5\LotListController;
use App\Http\Controllers\webservice\WebServiceController;
use App\Models\Favorites;
use App\Models\Subasta;
use App\Models\User;
use App\Models\V5\AppUsersToken;
use App\Models\V5\AucSessionsFiles;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;
use App\Models\V5\FgLicit;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgSub;
use App\Models\V5\FsPaises;
use App\Models\V5\FxCli;
use App\Models\V5\FxCliWeb;
use App\Models\V5\Web_Favorites;
use App\Models\V5\Web_Images_Size;
use App\Providers\ToolsServiceProvider;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use stdClass;

class WebServiceAPPController  extends WebServiceController
{


	#REGISTRO
	public function checkInUseNif()
	{
		$user = new User();
		$user->nif = mb_strtoupper(trim($this->parameters['nif']));
		$exist = $user->getUserByNif("N");


		if (empty($exist)) {

			return $this->responseSuccsess();
		} else {
			#COMPROBAMOS SI TIENE USUARIO WEB, SI YA TIENE USUARIO WEB NO DEBE PODER CONTINUAR
			$cliWeb = FxCliWeb::select("cod_cliweb")->where('cod_cliweb', $exist[0]->cod_cli)->first();
			if (empty($cliWeb)) {
				return $this->responseSuccsess();
			} else {
				return $this->responseError("NIF in use");
			}
		}
	}

	public function translate()
	{
		$translate = \App\libs\TradLib::getAppMobileTranslation(explode(",", env('LOCALES_KEYS', 'es')));
		return $this->responseSuccsess("translate", $translate);
	}



	public function checkInUseEmail()
	{
		$user = new User();
		$email = mb_strtoupper(trim($this->parameters['email']));
		$exist = $user->EmailExist($email, Config::get('app.emp'), Config::get('app.gemp'));

		if (empty($exist)) {
			return $this->responseSuccsess();
		} else {
			return $this->responseError("Email in use");
		}
	}

	public function addressFields()
	{
		try {
			$file = "register/address-fields.json";
			$data = $this->loadthemeFiles($file);

			$addressFields = json_decode($data, true);

			return $this->responseSuccsess("Address fields", $addressFields);
		} catch (\Exception $e) {
			return $this->exceptionApi($e);
		}
	}

	public function personalInfoFields()
	{
		try {
			$file = "register/personal-info-fields.json";
			$data = $this->loadthemeFiles($file);
			$personalInfoFields = json_decode($data, true);

			return $this->responseSuccsess("Personal fields", $personalInfoFields);
		} catch (\Exception $e) {
			return $this->exceptionApi($e);
		}
	}

	public function createClient()
	{
		$adminClientController = new  AdminClienteController();
		$client = new stdClass();
		$client->idorigincli = $adminClientController->newCod2Cli();

		$this->missFields(["password", "lastname", "name"]);

		$client->password = $adminClientController->passwordEncrypt($this->parameters["password"]);

		if (Config::get('app.name_without_coma', 0)) {
			$client->name = trim($this->parameters['name']) . ' ' . trim($this->parameters['lastname']);
		} else {
			$client->name = trim($this->parameters['lastname']) . ', ' . trim($this->parameters['name']);
		}

		$fields = $this->parameters;
		#quitamos los campos ya tratados
		unset($fields["password"]);
		unset($fields["lastname"]);
		unset($fields["name"]);
		# automatizamos el resto de campos de manera que la APP usará el mismo nombre en los campos que la API, así puedo recogerlos automaticamente
		foreach ($fields as $field => $value) {
			$client->{$field} = $value;
		}

		$clientController = new  clientController();
		return $clientController->createClient([(array)$client]);
	}

	#FIN REGISTRO

	#MODIFICAR DATOS DEL CLIENTE
	public function updateClient()
	{
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
			if (Config::get('app.name_without_coma', 0)) {
				$client->name = trim($this->parameters['name']) . ' ' . trim($this->parameters['lastname']);
			} else {
				$client->name = trim($this->parameters['lastname']) . ', ' . trim($this->parameters['name']);
			}
		}

		$fields = $this->parameters;

		#quitamos los campos ya tratados
		unset($fields["password"]);
		unset($fields["lastname"]);
		unset($fields["name"]);

		# automatizamos el resto de campos de manera que la APP usará el mismo nombre en los campos que la API, así puedo recogerlos automaticamente
		foreach ($fields as $field => $value) {
			$client->{$field} = $value;
		}

		$clientController = new clientController();
		return $clientController->updateClient([(array)$client]);
	}

	#FIN MODIFICAR DATOS DEL CLIENTE



	/**
	 * @deprecated
	 */
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
	private function loadthemeFiles($file)
	{

		$default_path = storage_path("app/app_movile/default/") . $file;
		$theme_path = storage_path("app/app_movile/themes/" . Config::get('app.theme') . "/") . $file;
		#si no existe el archivo en theme cargamos el generico

		if (file_exists($theme_path)) {
			return file_get_contents($theme_path);
		} else {
			return file_get_contents($default_path);
		}
	}

	private function missFields($fields, $numItem = 1)
	{
		$messageBag = new MessageBag();
		foreach ($fields as $field) {
			if (empty($this->parameters[$field])) {
				$messageBag->add("Error", trans('apilabel-app.validation.required', ["attribute" => $field]));
			}
		}

		if ($messageBag->isNotEmpty()) {
			$errorsItem["item_" . $numItem] = $messageBag;
			throw new ApiLabelException(trans('apilabel-app.errors.validation'), $errorsItem);
		}
	}

	private function cantModifiedFields($fields, $numItem = 1)
	{
		$messageBag = new MessageBag();
		foreach ($fields as $field) {
			if (!empty($this->parameters[$field])) {
				$messageBag->add("Error", trans('apilabel-app.validation.not_be_modified', ["attribute" => $field]));
			}
		}

		if ($messageBag->isNotEmpty()) {
			$errorsItem["item_" . $numItem] = $messageBag;
			throw new ApiLabelException(trans('apilabel-app.errors.validation'), $errorsItem);
		}
	}


	public function countries()
	{
		$langs = explode(",", env('LOCALES_KEYS', 'es'));
		$countriesResponse = [];
		foreach ($langs as $lang) {
			$lang = trim($lang);
			$countriesResponse[$lang] = [];
			$countries = FsPaises::select("cod_paises", "nvl(FSPAISES_LANG.DES_PAISES_LANG,FSPAISES.des_paises) des_paises")
				->leftJoin('FSPAISES_LANG', function ($join) use ($lang) {
					$join->on("FSPAISES_LANG.COD_PAISES_LANG", "=", "FSPAISES.cod_paises")
						->on("FSPAISES_LANG.LANG_PAISES_LANG", "=", "'" . ToolsServiceProvider::getLanguageComplete($lang) . "'");
				})->orderby("des_paises_lang")->get();

			foreach ($countries as $country) {
				$countriesResponse[$lang] += [$country->cod_paises => $country->des_paises];
			}
		}

		return $this->responseSuccsess("Countries", $countriesResponse);
	}

	public function getUserInfo()
	{
		$codcli = $this->parameters['codcli'];
		$token = $this->parameters['token'];

		$this->missFields(["codcli", "token"]);

		$user = FxCliWeb::select('NOM_CLIWEB, TEL1_CLI, FECNAC_CLI, CIF_CLI, CODPAIS_CLI, PRO_CLI, SG_CLI, concat(DIR_CLI, DIR2_CLI) as address, POB_CLI, CP_CLI, IDIOMA_CLI')
			->joinCliCliweb()->where("COD_CLI", $codcli)->where("TK_CLIWEB", $token)->first();

		if (empty($user)) {
			return $this->responseError("User not exist");
		}

		$nameAndLastname = explode(",", $user->nom_cliweb);

		$userResponse = [
			#si no hay dos elementos es que no hay coma, por lo que no podemos seprarlos en apellido y nombre
			"name" => $nameAndLastname[1] ??  $nameAndLastname[0],
			"lastname" => empty($nameAndLastname[1]) ? "" : $nameAndLastname[0],
			"phone" => $user->tel1_cli,
			"birthdate" => $user->fecnac_cli,
			"idnumber" => $user->cif_cli,
			"language" => $user->idioma_cli,
			"country" => $user->codpais_cli,
			"province" => $user->pro_cli,
			"street" => $user->sg_cli,
			"address" => $user->address,
			"city" => $user->pob_cli,
			"zipcode" => $user->cp_cli,
		];

		return $this->responseSuccsess("User Info", $userResponse);
	}

	/**
	 * @deprecated
	 */
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
	/**
	 * @deprecated
	 */
	public function getActiveAuctions()
	{
		#hacemos una consulta para conseguir las subastas activas poniendo S para definir que no están cerradas
		$sessions = $this->getAuctions('S');
		return $this->responseSuccsess("Active Auctions", $sessions);
	}

	/**
	 * @deprecated
	 */
	public function getHistoricAuctions()
	{

		#hacemos una consulta para conseguir las subastas históricas poniendo H
		$sessions = $this->getAuctions('H');
		return $this->responseSuccsess("Historic Auctions", $sessions);
	}


	/**
	 * @deprecated
	 */
	private function getAuctions($status)
	{
		#guardamos el locale actual
		$localeTemp = App::getLocale();
		#si el parametro lang viene vacio por defecto ponemos ES
		$this->parameters["lang"] = isset($this->parameters["lang"]) ? $this->parameters["lang"] : "ES";


		#mandamos query para conseguir todas las sesiones

		$sessions = FgSub::select('COD_SUB, SUBC_SUB, "id_auc_sessions", "reference",  TIPO_SUB, SUBC_SUB')
			->addSelect(' max(NVL("auc_sessions_lang"."name_lang","auc_sessions"."name")) as name')
			->addSelect(' max("auc_sessions"."start") as session_start')
			->addSelect(' max("auc_sessions"."end") as session_end')
			->join('"auc_sessions"', '"auc_sessions"."company" = FGSUB.EMP_SUB AND "auc_sessions"."auction" = FGSUB.COD_SUB')
			->leftJoin('"auc_sessions_lang"', ' "auc_sessions_lang"."id_auc_session_lang" = "auc_sessions"."id_auc_sessions"   AND "auc_sessions"."company" = "auc_sessions_lang"."company_lang" AND "auc_sessions"."auction" = "auc_sessions_lang"."auction_lang" AND "auc_sessions_lang"."lang_auc_sessions_lang" = \'' . $this->parameters["lang"] . '\'')
			->join("fgasigl0", 'emp_asigl0 = EMP_SUB AND  SUB_ASIGL0= COD_SUB and ref_asigl0 >=  "init_lot"  and ref_asigl0 <=  "end_lot"')
			->where('subc_sub', '=', $status)
			->groupby('emp_sub , cod_sub , "reference",SUBC_SUB,"id_auc_sessions", TIPO_SUB')
			->orderBy('max("start")', 'desc')->get();

		#inicializamos el array de subastas
		$sessionsResponse = [];

		foreach ($sessions as  $session) {

			$sessionRes = [
				"codsession" => $session->id_auc_sessions,
				"title" => $session->name,
				"type" => $session->tipo_sub,
				"status" => $session->subc_sub,
				"image" => $this->getAuctionImage($session->cod_sub, $session->reference),
			];
			if ($session->subc_sub == "S") {
				$sessionRes["start"] =  $session->tipo_sub == "O" ? $session->session_end :  $session->session_start;
			}

			$sessionsResponse[] = $sessionRes;
		}



		return $sessionsResponse;
	}

	/**
	 * @deprecated
	 */
	private function getAuctionImage($cod_sub, $reference)
	{
		#intentamos conseguir imagen de sesión
		$image_to_load = ToolsServiceProvider::url_img_session("subasta_large", $cod_sub, $reference);

		#si no existe conseguimos la imagen de la subasta\
		if (!file_exists($image_to_load) || filesize($image_to_load) < 500) {
			$image_to_load = ToolsServiceProvider::url_img_auction("subasta_large", $cod_sub);
		}

		return $image_to_load;
	}

	/**
	 * @deprecated
	 */
	public function getAuction()
	{
		#guardamos el locale actual en una variable
		$localeTemp = App::getLocale();
		#si el parametro lang viene vacio por defecto ponemos ES
		$this->parameters["lang"] = isset($this->parameters["lang"]) ? $this->parameters["lang"] : "ES";
		App::setLocale($this->parameters["lang"]);

		#comprobamos campo id_auc_sessions
		$this->missFields(['codsession']);

		#hacemos query para recoger datos de la subasta
		$session = FgSub::select("SUBC_SUB")->joinLangSub()->joinSessionSub()
			->where('"id_auc_sessions"', $this->parameters['codsession'])->first();

		#si no existe la sesion devolvemos error
		if (!$session) {
			return $this->responseError("session don't exist");
		}

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
					"url" => Config::get('app.url') . '/files' . $file->path,
				];
			}
		}

		#guarda los datos en el array comprobando la fecha
		$sessionArray = [
			"codsession" => $session->id_auc_sessions,
			"title" => $session->name,
			"type" => $session->tipo_sub,
			"image" => $this->getAuctionImage($session->cod_sub, $session->reference),
			"files" => $filesArray,
			"description" => $session->description,
		];

		#en historica no tienen start
		if ($session->subc_sub == "S") {
			$sessionArray["start"] = $session->tipo_sub == "O" ? $session->session_end :  $session->session_start;
		}

		#restauramos el locale
		App::setLocale($localeTemp);

		return $this->responseSuccsess("Auction Info", $sessionArray);
	}

	public function numLotsAuction()
	{

		#comprobamos campo id_auc_sessions
		$this->missFields(['codsession']);

		$fgasigl0 = new FgAsigl0();
		$fgasigl0 = $fgasigl0->select("COUNT(REF_ASIGL0) AS CUANTOS, MIN(REF_ASIGL0) MINLOT, MAX(REF_ASIGL0) MAXLOT")
			->Where('"id_auc_sessions"', $this->parameters['codsession'])->ActiveLotAsigl0()->groupby('"id_auc_sessions"')
			->first();
		$numlots = ["numlots" => $fgasigl0->cuantos, "firstlot" => $fgasigl0->minlot, "lastlot" => $fgasigl0->maxlot];

		return $this->responseSuccsess("Num lots Auction", $numlots);
	}

	public function getCategoriesAuction()
	{
		$this->missFields(['codsession']);

		$lang = isset($this->parameters['language']) || !empty($this->parameters['language']) ? ToolsServiceProvider::getLanguageComplete($this->parameters['language']) : ToolsServiceProvider::getLanguageComplete(App::getLocale());


		$fgasigl0 = new FgAsigl0();
		$fgasigl0 = $fgasigl0->select("COD_SEC,max(nvl(DES_SEC_LANG,DES_SEC)) DES_SEC,max(nvl(DES_ORTSEC0_LANG,DES_ORTSEC0)) DES_ORTSEC0,LIN_ORTSEC0")
			->where('"id_auc_sessions"', $this->parameters['codsession'])->activeLotAsigl0()
			->joinSecAsigl0()
			->joinFgOrtsecAsigl0()
			->leftjoin("FGORTSEC0_LANG", "EMP_ORTSEC0_LANG = EMP_ORTSEC0 AND LIN_ORTSEC0_LANG = LIN_ORTSEC0 AND SUB_ORTSEC0_LANG = SUB_ORTSEC0  AND LANG_ORTSEC0_LANG ='" . $lang . "'")
			->leftjoin("FXSEC_LANG", "GEMP_SEC_LANG = GEMP_SEC_LANG AND CODSEC_SEC_LANG = COD_SEC AND  LANG_SEC_LANG ='" . $lang . "'")
			->groupby("cod_sec")->groupby("lin_ortsec0");

		/* if(!empty($this->parameters['language'])){
				$lang =  ToolsServiceProvider::getLanguageComplete($this->parameters['language']);
				$fgasigl0 = 	$fgasigl0->leftjoin("FGORTSEC0_LANG","EMP_ORTSEC0_LANG = EMP_ORTSEC0 AND LIN_ORTSEC0_LANG = LIN_ORTSEC0 AND SUB_ORTSEC0_LANG = SUB_ORTSEC0  AND LANG_ORTSEC0_LANG ='" . $lang."'");
				$fgasigl0 = 	$fgasigl0->leftjoin("FXSEC_LANG","GEMP_SEC_LANG = GEMP_SEC_LANG AND CODSEC_SEC_LANG = COD_SEC AND  LANG_SEC_LANG ='" . $lang."'");
			} */

		$sections =  $fgasigl0->get();
		$categories = array();
		foreach ($sections as $section) {
			if (empty($categories[$section->lin_ortsec0])) {
				$categories[$section->lin_ortsec0]["name"] = $section->des_ortsec0;
				$categories[$section->lin_ortsec0]["sections"] = array();
			}
			$categories[$section->lin_ortsec0]["sections"][$section->cod_sec] =  $section->des_sec;
		}
		return $this->responseSuccsess("Categories Auction", $categories);
	}

	public function getLotsAuction()
	{

		#comprobamos campo id_auc_sessions
		$this->missFields(['codsession']);
		return $this->getLots($this->parameters['codsession']);
	}

	public function getLots($codSession = NULL)
	{
		$actualPage = $this->parameters['actualpage'] ?? 1;
		$lotsPerPage = $this->parameters['lotsperpage'] ?? 20;

		$subastaObj        = new Subasta();



		$oldLang = Config::get("locale");
		if (!empty($this->parameters['language'])) {
			App::setLocale($this->parameters['language']);
		}

		$lotListController = new LotListController();
		$fgasigl0 = new FgAsigl0();
		if (!empty($codSession)) {
			$fgasigl0 = $fgasigl0->Where('"id_auc_sessions"', $codSession);
		} else {
			#si es el listado de todos los lotes y no se ha elegido un orden lo ordenamos por
			$requestFilters['order'] = "nameweb";
		}

		$fgasigl0 = $fgasigl0->ActiveLotAsigl0();


		$filters = $this->getInputFilters($this->parameters['filters'] ?? []);



		$fgasigl0 = $lotListController->setFilters($fgasigl0, $filters);

		$lots =   $fgasigl0
			->select("NUM_HCES1, LIN_HCES1 ,IMPSALHCES_ASIGL0 , SUB_ASIGL0, REF_ASIGL0, IMPLIC_HCES1, RETIRADO_ASIGL0, CERRADO_ASIGL0, LIC_HCES1, TIPO_SUB,COMPRA_ASIGL0, IMPRES_ASIGL0, REMATE_ASIGL0, FFIN_ASIGL0, HFIN_ASIGL0, SUBABIERTA_SUB,FAC_HCES1 ")
			->addSelect(" NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1) AS TITLE,   NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1")
			->JoinFghces1LangAsigl0()
			->skip(($actualPage - 1) *  $lotsPerPage)
			->take($lotsPerPage)
			->get();
		/*
				(CASE WHEN ffin_asigl0 IS NOT NULL AND hfin_asigl0 IS NOT NULL
				THEN REPLACE(TO_DATE(TO_CHAR(p.ffin_asigl0, 'DD/MM/YY') || ' ' || p.hfin_asigl0, 'DD/MM/YY HH24:MI:SS'), '-', '/')
				ELSE null END) close_at
				*/

		$lotes = array();
		foreach ($lots as $key => $lot) {

			$lote = array();
			$lote["codauction"] = $lot->sub_asigl0;
			$lote["lotref"] = $lot->ref_asigl0;
			$lote["title"] = strip_tags($lot->title);
			$lote["image"] = ToolsServiceProvider::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1);
			$lote["price"] = $lot->impsalhces_asigl0;

			$lote["retired"] = ($lot->retirado_asigl0 == 'S');
			#devuelto , lo ponemso como no disponible
			$lote["notavailable"] = ($lot->fac_hces1 == 'D' || $lot->fac_hces1 == 'R' || $lot->cerrado_asigl0 == 'D') ? true : false;
			$lote["close"] = ($lot->cerrado_asigl0 == 'S');
			$lote["sold"] = ($lot->cerrado_asigl0 == 'S' && $lot->implic_hces1 > 0);
			$lote["soldprice"] = ($lot->remate_asigl0 == 'S' && $lote["sold"]) ? $lot->implic_hces1 : null;
			$lote["typeauction"] = $lot->tipo_sub;
			$lote["forsale"] =  ($lot->tipo_sub == 'V' && !$lote["close"]);

			$lote["enddate"] = in_array($lot->tipo_sub, ['O', 'P']) ?  $lot->ffin_asigl0 : null;
			#si esta cerrado  y no vendido ,pero tiene opcion de compra lo mostraremso como un venta directa
			if ($lote["close"]  && !$lote["sold"]  && $lot->compra_asigl0 == 'S'  &&  (in_array($lot->tipo_sub, ['W', 'O', 'P']))) {
				$lote["close"] = false;
				$lote["forsale"] = true;
			}

			if ($lot->tipo_sub == 'W' && $lot->subabierta_sub == 'O' && $lot->cerrado_asigl0 != 'S') {
				$subastaObj->lote  = $lot->ref_asigl0;
				$subastaObj->ref = $lot->ref_asigl0;
				$subastaObj->cod   = $lot->sub_asigl0;
				$ordenes = $subastaObj->getOrdenes();
				$subastaObj->sin_pujas = false;

				$lote["actualbid"] =  $subastaObj->price_open_auction($lot->impsalhces_asigl0, $ordenes);
			} elseif (($lot->tipo_sub == 'O' || $lot->tipo_sub == 'P' || ($lot->tipo_sub == 'W' && $lot->subabierta_sub == 'P')) && $lot->cerrado_asigl0 != 'S') {
				$lote["actualbid"] = $lot->implic_hces1;
			} else {
				$lote["actualbid"] = null;
			}
			$lotes[] = $lote;
		}

		App::setLocale($oldLang);
		return $this->responseSuccsess("Lots Info", $lotes);
	}

	#generamos las variables por cada input que se espera
	public function getInputFilters($requestFilters = array())
	{
		$filters = array();
		//lots per page

		#orden de los lotes
		$filters["order"] = $requestFilters['order'] ?? null; //
		#busqueda por texto
		$filters["description"] = $requestFilters['description'] ?? null; //request('description', $search);
		#filtro pot categorias
		$filters["category"] = $requestFilters['category'] ?? null; //request('category', $category ?? Config::get("app.default_category") );
		#filtro pot secciones
		$filters["section"] = $requestFilters['section'] ?? null; //request('section', $section);
		#filtro pot secciones
		$filters["subsection"] = $requestFilters['subsection'] ?? null; //request('subsection',$subsection);
		#filtro por tipo de subasta
		$filters["typeSub"] = $requestFilters['typeSub'] ?? null; //request('typeSub',$typeSub);
		#filtro por referencia el lote
		$filters["reference"] =  $requestFilters['reference'] ?? null; //request('reference');

		#filtro por caracteristica
		$filters['features'] = $requestFilters['features'] ?? null; //request('features');

		#filtro lotes vendidos
		$filters['award'] = $requestFilters['award'] ?? null; //request('award');
		#filtro lotes no vendidos
		$filters['noAward'] = $requestFilters['noAward'] ?? null; //request('noAward');
		#filtro lotes en curso
		$filters['liveLots'] = $requestFilters['liveLots'] ?? null; //request('liveLots');

		#debemos inicializar la variable
		$filters['myLotsProperty'] = null;
		$filters['myLotsClient'] = null;


		#filtro de precios
		$filters['prices'] = $requestFilters['prices'] ?? null; //request('prices');

		return $filters;
	}

	public function getLot()
	{
		#comprobamos campo id_auc_sessions
		$this->missFields(['codauction', 'lotref']);


		$oldLang = Config::get("locale");
		if (!empty($this->parameters['language'])) {
			App::setLocale($this->parameters['language']);
		}
		$subastaObj        = new Subasta();
		$subastaObj->cod   = $this->parameters['codauction'];
		$subastaObj->lote  = $this->parameters['lotref'];
		$subastaObj->ref  = $this->parameters['lotref'];
		$subastaObj->page = 1;
		$subastaObj->itemsPerPage = 1000;

		$where = FALSE;
		$lot = $subastaObj->getLote($where, true, true);
		if (empty($lot) || $lot[0]->subc_sub == 'N') {
			#devolver error
		}


		/**** creamos licitador ****/
		if (!empty($this->parameters['codcli'])) {
			# es necesario crear el dummy por si no existe
			$subastaObj->checkDummyLicitador();
			$subastaObj->cli_licit = $this->parameters['codcli'];
			#necesitamos el usuario para poder indicar el rsoc
			$user                = new User();
			$user->cod_cli       = $this->parameters['codcli'];
			$usuario = $user->getUser();
			$subastaObj->rsoc      = $usuario->rsoc_cli ?? $usuario->nom_cli;
			# Si tienen numero de ministerio asignado, creamos ministerio como licitador
			if (Config::get('app.ministeryLicit', false)) {
				$subastaObj->checkOrInstertMinisteryLicitador(Config::get('app.ministeryLicit'), 'Ministerio');
			}

			//recogemos el licitador o lo creamos si no existe
			$licit = $subastaObj->checkLicitador();
		}
		/**** FIN creamos licitador ****/

		$lot = $lot[0];

		$lote = array();
		$lote["codlicit"] = (!empty($licit)) ? $licit[0]->cod_licit : null;
		$lote["typeauction"] = $lot->tipo_sub;
		$lote["codauction"] = $lot->sub_asigl0;

		$lote["lotref"] = $lot->ref_asigl0;
		$lote["title"] = strip_tags($lot->descweb_hces1);
		$lote["description"] = $lot->desc_hces1;


		$lote["price"] = $lot->impsalhces_asigl0;

		$lote["retired"] = ($lot->retirado_asigl0 == 'S');
		$lote["close"] = ($lot->cerrado_asigl0 == 'S');
		$lote["sold"] = ($lote["close"] && $lot->implic_hces1 > 0);
		$lote["soldprice"] = ($lot->remate_asigl0 == 'S' && $lote["sold"]) ? $lot->implic_hces1 : null;

		$lote["forsale"] = ($lot->tipo_sub == 'V' && !$lote["close"]);
		if ($lote["close"]  && !$lote["sold"]  &&  $lot->compra_asigl0 == 'S'   &&  (in_array($lot->tipo_sub, ['W', 'O', 'P']))) {
			$lote["close"] = false;
			$lote["forsale"] = true;
		}

		$lote["images"] = $subastaObj->getLoteImages($lot);
		$sizes = Web_Images_Size::getSizes();
		foreach ($lote["images"] as $keyImg => $image) {
			$lote["images"][$keyImg] = Config::get('app.url') . '/img/thumbs/' . $sizes['lote_medium'] . '/' . Config::get('app.emp') . '/' . $lot->num_hces1 . '/' . $image;
		}
		#tipo de puja (order,bid,buy)
		$lote["typebid"] = "order";
		#IMPORTE actual si lo tiene
		#subasta abierta ordenes

		if ($lot->tipo_sub == 'W' && $lot->subabierta_sub == 'O') {
			$ordenes = $subastaObj->getOrdenes();

			$subastaObj->sin_pujas = false;
			$lote["actualbid"] =  $subastaObj->price_open_auction($lot->impsalhces_asigl0, $ordenes);
			$lote["showbids"] = false;
			$lote["bids"] = null;
			if (count($ordenes) > 0) {
				#si es el propietario de la orden mas alta, solo por que es subasta abierta
				$lote["winner"] =	head($ordenes)->cod_licit == $lote["codlicit"];
			} else {
				$lote["winner"] = false;
			}
		} elseif ($lot->tipo_sub == 'O' || $lot->tipo_sub == 'P' || ($lot->tipo_sub == 'W' && $lot->subabierta_sub == 'P')) {
			$lote["actualbid"] =    ($lot->implic_hces1 > 0) ? $lot->implic_hces1 : null;
			$lote["showbids"] = true;
			$lote["bids"] = $subastaObj->getPujas();
			$lote["typebid"] = "bid";
			#si es el propietario de la orden mas alta
			if (count($lote["bids"]) > 0) {
				$lote["winner"] =	(head($lote["bids"])->cod_licit == $lote["codlicit"]);
			}
		} else {
			$lote["actualbid"] = null;
			$lote["showbids"] = false;
			$lote["bids"] = null;
			$lote["winner"] = false;
		}

		#listado de siguientes pujas, 3 para online 20 para
		# si no hay puja actual es que será la primera puja
		$first_ol = empty($lote["actualbid"]);
		#tres pujas siguientes, o 20 si son ordenes
		$numNextBids = $lote["typebid"] == "bid" ?  3 : 20;
		$lote["nextbids"][0] =  $subastaObj->NextScaleBid($lot->impsalhces_asigl0, $lote["actualbid"], $first_ol);
		for ($x = 1; $x < $numNextBids; $x++) {
			$lote["nextbids"][$x] =  $subastaObj->NextScaleBid($lot->impsalhces_asigl0, $lote["nextbids"][$x - 1]);
		}

		#Fecha a mostrar
		if ($lot->tipo_sub == 'W') {
			$lote["enddate"] = $lot->start;
			$lote["realtime"] = ToolsServiceProvider::url_real_time_auction($lot->cod_sub, $lot->name, $lot->id_auc_sessions);
		} elseif ($lot->tipo_sub == 'O' || $lot->tipo_sub == 'P') {
			$lote["enddate"] = substr($lot->ffin_asigl0, 0, 10) . " " . $lot->hfin_asigl0;
		} elseif ($lot->tipo_sub == 'V') {
			$lote["enddate"] = $lot->end;
		}

		#si esta cerrado  y no vendido ,pero tiene opcion de compra lo mostraremos como una venta directa
		if ($lote["close"]  && !$lote["sold"]  && $lot->compra_asigl0 == 'S'  &&  (in_array($lot->tipo_sub, ['W', 'O', 'P']))) {
			$lote["forsale"] = true;
		}
		$subastaObj->session_reference = $lot->reference;
		$lote["nextlot"] = $subastaObj->getNextPreviousLot("NEXT", $lot->orden_hces1, "order");
		$lote["previouslot"] = $subastaObj->getNextPreviousLot("PREVIOUS", $lot->orden_hces1, "order");
		#solo lotes por encima del precio indicado en la subasta
		$lote["phoneorders"] = (!empty($lot->ordentel_sub) && $lot->ordentel_sub <= $lot->impsalhces_asigl0);

		/* PENDIENTES */




		$lote["cancelOrder"] = Config::get("app.DeleteOrders") ? true : false;

		App::setLocale($oldLang);
		return $this->responseSuccsess("Lot Info", $lote);
	}
	#funcion que devuleve el listado de siguientes pujas
	public function AvailableBids()
	{
		$str = new subastaTiempoRealController();
		return $str->calculateAvailableBids($this->parameters['nextbid'], $this->parameters['userbid'], $this->parameters['codsub']);
	}


	public function buyLot()
	{

		$this->missFields(['codauction', 'lotref', 'codcli']);

		$subastaTiempoRealController = new subastaTiempoRealController();
		#código de licitrador no se envia por que solo se utilizaba si eras gestor y en la app no dejaremos ser gestor, por lo que gestor tampoco se envia
		$res = $subastaTiempoRealController->comprarLote($this->parameters['codauction'], $this->parameters['lotref'],  $this->parameters['codcli']);
		Log::info(print_r($res, true));
		if ($res["status"] == "success") {
			return $this->responseSuccsess($res["msg"]);
		} else {
			return $this->responseError($res["msg_1"]);
		}


		//$subastaTiempoRealController->comprarLote($cod_sub, $ref, $cod_licit, $cod_user,$gestor  );
	}




	public function createOrder()
	{
		# va a ser necesario tratar las respuestas, ya que el programa devuelve otro formato, por lo que la recogemos y las tratamos
		#comprobamos campo id_auc_sessions
		$this->missFields(['codauction', 'lotref', 'order', 'codcli']);

		$subastaTiempoRealController = new subastaTiempoRealController();
		#la variable ortherphone originalmente viene de js y no se puede enviar un booleano, lo envia como texto
		if (!empty($this->parameters['ortherphone']) && $this->parameters['ortherphone']) {
			$this->missFields(['phone1']);

			$ortherphone = "true";
			$tel1 = $this->parameters['phone1'];
			$tel2 = $this->parameters['phone2'] ?? "";
		} else {
			$ortherphone = "false";
			$tel1 = null;
			$tel2 = null;
		}

		$res = $subastaTiempoRealController->crearOrdenLicitacion($this->parameters['codauction'], $this->parameters['lotref'], $this->parameters['order'], $this->parameters['codcli'], $tel1, $tel2, $ortherphone);
		if ($res["status"] == "success") {
			$openPrice = null;
			if (!empty($res["open_price"])) {
				$openPrice = new Stdclass();
				$openPrice->actualbid = $res["open_price"];
				$openPrice->winner = $res["winner"];
			}


			return $this->responseSuccsess($res["msg"], $openPrice);
		} else {
			return $this->responseError($res["msg_1"]);
		}
	}


	public function getbids()
	{
		$this->missFields(['codcli']);
		$favorites = false;
		$sizes = Web_Images_Size::getSizes();
		$sub = new Subasta();
		$sub->licit = $this->parameters['codcli'];

		$sub->page  = 'all';
		$sub->cod = null;
		$all_pujas = array();

		$all_pujas_temp = $sub->getAllBidsAndOrders($favorites, true);
		$subastas = [];
		foreach ($all_pujas_temp as $temp_pujas) {
			if ($temp_pujas->tipo_sub != 'V') {
				if (empty($subastas[$temp_pujas->cod_sub])) {
					$sub->cod = $temp_pujas->cod_sub;
					$subasta = $sub->getInfSubasta();
					#si es subasta abierta
					$subastas[$temp_pujas->cod_sub] = (object) ["title" => $subasta->des_sub, "type" => $subasta->tipo_sub, "open" => $subasta->subabierta_sub];
				}
			}
		}

		foreach ($all_pujas_temp as $key_inf => $temp_pujas) {
			#las venta directa no se envian
			if ($temp_pujas->tipo_sub == 'V') {
				break;
			}

			$lot = (object) ["lotref" => $temp_pujas->ref_asigl0, "title" => $temp_pujas->descweb_hces1, "price" => $temp_pujas->impsalhces_asigl0,  "biddate" => $temp_pujas->date];
			$lot->image = Config::get('app.url') . '/img/thumbs/' . $sizes['lote_medium'] . '/' . Config::get('app.emp') . '/' . $temp_pujas->num_hces1 . '/' . $temp_pujas->imagen;


			$subasta = $subastas[$temp_pujas->cod_sub];
			if (empty($all_pujas[$temp_pujas->cod_sub]['auction'])) {
				$all_pujas[$temp_pujas->cod_sub]['auction'] = $subasta;
			}


			#Puja Actual
			# si no hay implic o si la subasta es presencial abierta
			if (empty($temp_pujas->implic_hces1) ||  $subasta->type == 'W' &&  $subasta->open == 'N') {
				$lot->actualbid = null;
			} else {
				$lot->actualbid = $temp_pujas->implic_hces1;
			}

			#ganador
			if (($temp_pujas->cod_licit == $temp_pujas->licit_winner_bid && ($subasta->type == 'O' || $subasta->open == 'P')) || ($temp_pujas->cod_licit == $temp_pujas->licit_winner_order && ($subasta->type == 'W' && $subasta->open == 'O'))) {
				$lot->winner = true;
			} else {
				$lot->winner = false;
			}

			#puja telefonica
			if ($temp_pujas->tipop_orlic == 'T') {
				$lot->phoneorder = true;
			} else {
				$lot->phoneorder = false;
			}

			#opción de borrar orden
			if (Config::get("app.DeleteOrders") && (empty($temp_pujas->implic_hces1)  ||  $temp_pujas->imp >  $temp_pujas->implic_hces1)) {
				$lot->deleteorder = true;
			} else {
				$lot->deleteorder = false;
			}

			$all_pujas[$temp_pujas->cod_sub]['bids'][] = $lot;
		}
		return $this->responseSuccsess("Listado de pujas", $all_pujas);
	}

	public function getawards()
	{
		$this->missFields(['codcli']);
		$lots = FgCsub::select("REF_ASIGL0, SUB_ASIGL0, NUM_HCES1, LIN_HCES1, DESCWEB_HCES1, HIMP_CSUB")->JoinAsigl0()->JoinFghces1()->where("CLIFAC_CSUB", $this->parameters['codcli'])->orderby("FECHA_CSUB", "DESC")->get();
		$awards = [];
		$sizes = Web_Images_Size::getSizes();
		foreach ($lots as $lot) {
			//['codauction','lotref','order','codcli']

			$award =  (object) ['codauction' => $lot->sub_asigl0, 'lotref' => $lot->ref_asigl0, "title" => $lot->descweb_hces1, "price" => $lot->himp_csub];
			$award->image = Config::get('app.url') . '/img/thumbs/' . $sizes['lote_medium'] . '/' . Config::get('app.emp') . '/' . $lot->num_hces1 . '/' . Config::get('app.emp') . '-' . $lot->num_hces1 . '-' . $lot->lin_hces1 . '.jpg';
			$awards[$lot->sub_asigl0]["awards"][] = $award;
		}
		$sub = new Subasta();
		foreach ($awards as $key => $lotsAward) {
			$sub->cod = $key;
			$subasta = $sub->getInfSubasta();

			$awards[$key]['auction'] = (object) ["title" => $subasta->des_sub, "type" => $subasta->tipo_sub, "open" => $subasta->subabierta_sub];
		}
		return $this->responseSuccsess("Listado de adjudicados", $awards);
	}

	public function getfavorites()
	{
		$this->missFields(['codcli']);
		$sizes = Web_Images_Size::getSizes();
		$sub = new Subasta();

		$User = new User();

		$User->cod_cli = $this->parameters['codcli'];;
		$codigos_licitador = array();
		foreach ($User->getLicitCodes() as $key) {
			$codigos_licitador[$key->sub_licit] = $key->cod_licit;
		}

		# Obtenemos los codigos de licitador en formato string para el IN(xxx)

		$lista_codigos = '';
		$coma = '';
		foreach ($codigos_licitador as $key => $value) {
			$lista_codigos .= $coma . "'" . $key . "-" . $value . "'";
			$coma = ',';
		}
		$all_favorites = array();
		$favs = array();
		$fav  = new Favorites(false, false);
		if (!empty($lista_codigos)) {
			$fav->list_licit = $lista_codigos;
			$fav->page  = 'all';
			$favs = $fav->getFavsByLicits();
		}

		if (!empty($favs['data'])) {
			foreach ($favs['data'] as $favorites) {

				$lot = (object) ["lotref" => $favorites->ref_asigl0, "title" => $favorites->descweb_hces1, "price" => $favorites->impsalhces_asigl0];
				$lot->image = Config::get('app.url') . '/img/thumbs/' . $sizes['lote_medium'] . '/' . Config::get('app.emp') . '/' . $favorites->num_hces1 . '/' . $favorites->imagen;

				#Puja Actual
				# si no hay implic o si la subasta es presencial abierta
				if (!empty($favorites->pujas[0]) && !empty($favorites->pujas[0]->imp_asigl1)) {
					$lot->actualbid = $favorites->pujas[0]->imp_asigl1;
				} else {
					$lot->actualbid = null;
				}

				#ganador

				if (!empty($favorites->pujas[0]) && !empty($codigos_licitador[$favorites->cod_sub]) && $codigos_licitador[$favorites->cod_sub] == $favorites->pujas[0]->cod_licit) {
					$lot->winner = true;
				} else {
					$lot->winner = false;
				}
				$all_favorites[$favorites->cod_sub]['favorites'][] = $lot;
			}
			foreach ($all_favorites as $key_inf => $value) {
				$sub->cod = $key_inf;
				$subasta = $sub->getInfSubasta();
				$all_favorites[$key_inf]['auction'] = (object) ["title" => $subasta->des_sub, "type" => $subasta->tipo_sub, "open" => $subasta->subabierta_sub];
			}
		}
		return $this->responseSuccsess("Listado de favoritos", $all_favorites);
	}

	public function addfavorites()
	{
		$this->missFields(['codauction', 'lotref', 'codcli']);
		try {
			$codSub = $this->parameters['codauction'];
			$ref =  $this->parameters['lotref'];
			$codCli =  $this->parameters['codcli'];

			$favorite = Web_Favorites::where("ID_SUB", $codSub)->where("ID_REF", $ref)->where("COD_CLI", $codCli)->first();
			if (!empty($favorite)) {
				return $this->responseError(trans(Config::get('app.theme') . '-app.msg_error.already_added_to_fav'));
			} else {
				$licitador = FgLicit::SELECT("COD_LICIT")->where("SUB_LICIT", $codSub)->where("CLI_LICIT", $codCli)->first();
				Web_Favorites::create(["ID_SUB" => $codSub, "ID_REF" => $ref, "COD_CLI" => $codCli, "ID_LICIT" => $licitador->cod_licit ?? null, "FECHA" => date("Y-m-d H:i:s")]);
				return $this->responseSuccsess(trans(Config::get('app.theme') . '-app.msg_success.fav_added'));
			}
		} catch (Exception $e) {
			return $this->responseError(trans(Config::get('app.theme') . '-app.msg_error.add_to_fav'));
		}
	}

	public function removefavorites()
	{
		$this->missFields(['codauction', 'lotref', 'codcli']);
		try {
			$codSub = $this->parameters['codauction'];
			$ref =  $this->parameters['lotref'];
			$codCli =  $this->parameters['codcli'];

			Web_Favorites::where("ID_SUB", $codSub)->where("ID_REF", $ref)->where("COD_CLI", $codCli)->delete();
			return $this->responseSuccsess(trans(Config::get('app.theme') . '-app.msg_success.deleted_fav_success'));
		} catch (Exception $e) {
			return $this->responseError(trans(Config::get('app.theme') . '-app.msg_error.delete_fav_error'));
		}
	}

	public function createtoken()
	{


		$this->missFields(['codcli', 'tokenapp', 'soapp']);
		$fields = ["GEMP_USERS_TOKEN" => Config::get("app.gemp"), "CLI_USERS_TOKEN" => $this->parameters['codcli'], "SO_USERS_TOKEN" => $this->parameters['soapp'], "TOKEN_USERS_TOKEN" => $this->parameters['tokenapp'], "DATE_USERS_TOKEN" => date("Y-m-d H:i:s")];
		$existToken = AppUsersToken::where("GEMP_USERS_TOKEN", Config::get("app.gemp"))->where("CLI_USERS_TOKEN", $this->parameters['codcli'])->where("SO_USERS_TOKEN", $this->parameters['soapp'])->first();
		if (empty($existToken)) {
			AppUsersToken::create($fields);
		} else {
			AppUsersToken::where("GEMP_USERS_TOKEN", Config::get("app.gemp"))->where("CLI_USERS_TOKEN", $this->parameters['codcli'])->where("SO_USERS_TOKEN", $this->parameters['soapp'])->update($fields);
		}
		return $this->responseSuccsess("Token creado");
	}
}
