<?php
namespace App\Http\Controllers;


use Redirect;
//use Controller;

//opcional
use Illuminate\Support\Facades\DB;
use Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request as Input;
use Session;
use View;
use Routing;
use Illuminate\Support\Facades\Config;
use Route;
/*use Mail;*/
use Cookie;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Log;
# ODBC Service Provider
//use TCK\Odbc\OdbcServiceProvider;
use App\Http\Controllers\PaymentsController;
# Cargamos el modelo
use App\Models\User;
use App\Models\Subasta;
use App\Models\Favorites;
use App\Models\Newsletter;
use App\Models\Payments;
use App\Models\Facturas;
use App\Models\Enterprise;
use App\Models\Address;
Use App\Http\Controllers\MailController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\apilabel\ClientController;
use App\Http\Controllers\externalws\vottun\VottunController;
use App\libs\EmailLib;
use App\Models\V5\SubAuchouse;
use App\libs\FormLib;
use App\Models\V5\Customer_Presta;
use App\Models\V5\Address_Presta;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgDvc1l;
use App\Models\V5\FxDvc0;
use App\Models\V5\FsIdioma;
use App\Models\V5\FsPaises;
use App\Models\V5\FxCli;
use App\Models\V5\FxClid;
use App\Models\V5\FxCliObcta;
use App\Models\V5\FxCliWeb;
use App\Models\V5\FxDvc0Seg;
use App\Models\V5\FgAsigl1_Aux;
use App\Models\V5\FgSub;
use App\Models\V5\Web_Preferences;
use App\Models\V5\Web_Favorites;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\Fx_Newsletter;
use App\Providers\ToolsServiceProvider;
use GuzzleHttp;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use App\libs\SeoLib;
class UserController extends Controller
{

	public function login($postUser = null, $info = null)
	{
        	if(!Session::has('user')){



              $enterprise = new Enterprise();
              $user = new User();
              //Busca paises
              $countries = $enterprise->getCountries();

              //No se utiliza
              //$provinces = DB::select("SELECT cod_prv, des_prv FROM FSPRV ORDER BY des_prv ASC");
              //$jobs = DB::select("SELECT COD_CNAE, DESC_CNAE FROM FSCNAE WHERE LENGTH(COD_CNAE)=4 ORDER BY DESC_CNAE ASC");
              // $data = array("countries" => $countries, "provinces" => $provinces, "jobs" => $jobs, "via" => $via);

              //Busca vias
              $via = $enterprise->getVia();
              //Busca divisas del cliente
              $divisa = $enterprise->getDivisa();
              $data = array("countries" => $countries, "via" => $via ,"divisa"=>$divisa);

              $data['favorites'] = $user->favorites();
              $data['seo'] = new \stdClass();
              # Cargamos la vista del login en caso de que no exista la sesión del usuario.
              $data['seo']->noindex_follow = true;

                /**
                 * Carga de idiomas
                 */
                if (!empty(FsIdioma::getArrayValues())) {
                    $data['language'] = FsIdioma::getArrayValues();
                } else {
                    foreach (Config::get('app.locales') as $key => $value) {
                        $data['language'][strtoupper($key)] = $value;
                    }
                }

            if (!empty($postUser) && !empty($info)){


                $data['userFields'] = $postUser;
                $data['formulario'] =  new \stdClass();
                $data['formulario']->subalia = FormLib::Hidden("subalia", 0, "subalia");


                $data['formulario']->info = FormLib::Hidden("info", 0, $info);
              }

              return View::make('front::pages.login')->with('data',$data);

            }
            else
            {
                # Hay Sesión;
                return Redirect::to('/');
            }
	}

        //Buscar informacion del usuario
    public function GetInfUser($email,$password){

        $user           = new User();
        $user->user     = $email;
        $user->password = $password;
		#si tienen una semilla para cada usuario
		if(!empty(Config::get('app.multi_key_pass'))){

			$user->email     = $email;
			$dataUser = $user->getUserByLogin();

			if(empty($dataUser)){
				return null;
			}
			$passBD= explode(":", $dataUser[0]->pwdwencrypt_cliweb);


			#debe existir la clave de encriptación y el password encriptado
			if(count($passBD) <2){
				return null;
			}

			$seed = $passBD[1];
			#encriptamos el password k mandan y añadimos la semilla para que concuerde con lo que hay guardado en base de datos.
			$user->password =  md5($seed.$user->password).":".$seed;
			$login          = $user->login_encrypt();

		}#una semilla para todos lso usuarios
        elseif(!empty(Config::get('app.password_MD5'))){



			$user->password =  md5(Config::get('app.password_MD5').$user->password);
			$login          = $user->login_encrypt();

			if(!Config::get('app.strict_password_validation', false) && empty($login) && strlen($password) > 8){
				$user->password = substr($password,0,8);
				$user->password =  md5(Config::get('app.password_MD5').$user->password);
				$login          = $user->login_encrypt();
			}


        }else{
             $login          = $user->login();

        }
        return $login;

    }

    //Crear la session del usuario
    public function SaveSession($login){

        $user = new User();
        # Seteamos la sesión
        Session::put('user.name', $login->nom_cli);
		Session::put('user.rsoc', $login->rsoc_cli);
        Session::put('user.usrw', $login->usrw_cliweb);
        Session::put('user.cod',  $login->cod_cliweb);
        Session::put('user.emp',  $login->emp_cliweb);
        Session::put('user.gemp', $login->gemp_cliweb);
        Session::put('user.currency', $login->cod_div_cli);
        //cada vez que logueamos generamos un token nuevo
        $user->cod = $login->cod_cliweb;

        $token = $user->updateToken();
        Session::put('user.tk', $token);

        if($login->tipacceso_cliweb == 'S') {
            Session::push('user.admin', 1);
        }elseif($login->tipacceso_cliweb == 'A'){
            Session::push('user.adminconfig', 1);
            Session::push('user.admin', 1);
        }

    }

	/**
	 * @deprecated en desuso 25/08/2023
	 */
    public function remmember_user(){
        if(!Session::has('user') && !empty(Cookie::get('user')) && Cookie::get('user') != null){

            $cookie = explode('%',Cookie::get('user'));
            $email=$cookie[0];
            $password=$cookie[1];
            $userCont = new UserController();

            $login = $userCont->GetInfUser($email,$password);

             # Tipacceso (S) = Admin | (N) Normal | (X) Sin acceso | (A) AdminConfig
             if(!empty($login) || $login->tipacceso_cliweb != 'X') {
                $userCont->SaveSession($login);
             }
        }

    }

	public function encryptLogin(HttpRequest $request)
	{

		$dataValues = $request->valoresPresta ?? null;
		//$dataValues = '"rUqswijjRBRNq6bKmw34tFFITA8gGdlB02rEA03b+eGJo5UgXq7qQlHu7NwQAH6/firwGow569jGUbg9i5s7UrGRYA++cXzCxgGeOhCyjmXaZ0CXPm/O9/Y3UqW753s+gfFme4FlrijD0l9RMJLZJXn7/aZgdMCy0ZGOV8Scr0q1c2CuAt3TqdYybJlIWluB"';

		$data = ToolsServiceProvider::descrypt($dataValues, Config::get('app.ps_sb_auth_key'));

		/** [email, password, back, submitLogin] : la útlima era para presta */
		$user = json_decode($data, true);

		//usuario en la empresa que estoy
		$userCliWeb = FxCliWeb::where('lower(EMAIL_CLIWEB)', mb_strtolower($user['email']))->first();

		//duplica usuario si no existe (para llegar aquí tengo que haber realizado login en empresa origen así que seguro que exite el usuario ahí)
		if(!$userCliWeb){

			try {
				DB::beginTransaction();
				$userCliWeb = $this->duplicateWebUser($user['email'], config('app.gemp'), config('app.emp'));
				DB::commit();

			} catch (\Throwable $th) {
				DB::rollBack();
				return response($th->getMessage(), 500);
			}
		}

		$request->request->add([
			'email' => $user['email'],
			'password' => $user['password']
		]);

		//login
		$this->login_post_ajax($request);

		$backTo = $request->get('back_to', null);
		if($backTo){
			return redirect($backTo);
		}
		return redirect("/");
	}

	private function duplicateWebUser($email, $gempOrigen, $empDestino)
	{
		$userInGorupEmp = FxCli::where('lower(email_cli)', mb_strtolower($email))->first();

		$userCliWebMain = FxCliWeb::withoutGlobalScope('emp')
		->where([
			['GEMP_CLIWEB', $gempOrigen],
			['COD_CLIWEB', $userInGorupEmp->cod_cli]
		])
		->first();

		$userCliWebMain->emp_cliweb = $empDestino;

		FxCliWeb::create($userCliWebMain->toArray());

		return $userCliWebMain;
	}

    //login con ajax
    public function login_post_ajax(HttpRequest $request)
    {
        $res = $this->login_post($request, true);
        return  $res;
    }

	public function login_post(HttpRequest $request, $ajax = false)
	{
		$rules = [
			'email'    => 'required|email',    // make sure the email is an actual email
			'password' => 'required'     // password can only be alphanumeric
		];

		$ip = $this->getUserIP();
		$user = new User();
		$email = Request::input('email');
		$password = Request::input('password');

		$responseError = [
			'status' => 'error',
			'msg' => 'login_register_failed'
		];

		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return $ajax ? $responseError : Redirect::to(Routing::slug('login'))->withErrors($validator->errors());
		}

		$limiterKey = 'login_' . $email;
		if(Config::get('app.login_attempts', 0) && RateLimiter::tooManyAttempts($limiterKey, Config::get('app.login_attempts', 0))){
			$responseError['msg'] = 'login_too_many_attempts';
			Log::info('Exceso de intentos de login, bloqueo en entrada', ['email' => $email, 'ip' => $ip]);
			return $ajax ? $responseError : Redirect::to(Routing::slug('login'))->withErrors(['msg' => 'login_too_many_attempts']);
		}

		$login = $this->getInfUser($email, $password);

		# Si existe el usuario
		if (!empty($login)) {
			# Tipacceso (S) = Admin | (N) Normal | (X) Sin acceso | (A) AdminConfig
			if ($login->tipacceso_cliweb == 'X') {
				return $ajax ? $responseError : Redirect::to(Routing::slug('login'))->withErrors([]);
			}

			//Necesario por si se eliminan datos de cache
			if(Config::get('app.login_attempts', 0) && !empty($login->bloqueado_en_cliweb) && now()->diffInSeconds($login->bloqueado_en_cliweb) < Config::get('app.login_attempts_timeout', 60)) {
				$responseError['msg'] = 'login_too_many_attempts';
				Log::info('Exceso de intentos de login, bloqueo de base de datos', ['email' => $email, 'ip' => $ip]);
				return $ajax ? $responseError : Redirect::to(Routing::slug('login'))->withErrors(['msg' => 'login_too_many_attempts']);
			}

			if (!empty(Request::input('remember_me'))) {
				$password = Request::input('password');
				Cookie::queue('user', '' . $email . '%' . $password . '', '525600');
			}

			//Eliminamos los tokens de sesion anteriores

			$request->session()->regenerateToken();

			$this->SaveSession($login);
			$user->logLogin($login->cod_cliweb, Config::get('app.emp'), date("Y-m-d H:i:s"), $ip);

			$externalEncryptData = null;

			if (config('app.ps_activate', false)) {
				$externalLoginData = [
					'email' => $email,
					'password' => $password,
					'back' => request('back', '')
				];
				$externalEncryptData = ToolsServiceProvider::encrypt(json_encode($externalLoginData), config('app.ps_sb_auth_key'));
			}

			$responseSuccess = [
				'status' => 'success',
				'data' => $externalEncryptData,
				'context_url' => request('context_url', ''),
			];

			return $ajax ? $responseSuccess : Redirect::back();
		}

		//Usuario no existe
		$user->email = $email;
		//Buscamos el cliente por email si existe
		$user_inf = $user->getUserByEmail();
		$her_pwd = null;

		//Si existe la contraseña es incorecta
		if (!empty($user_inf)) {
			$her_pwd = $user_inf[0]->pwdwencrypt_cliweb;
		}

        if($this->checkAndAddRateLimitBlock($limiterKey, $user_inf[0]->cod_cliweb ?? null)) {
            $responseError['msg'] = 'login_too_many_attempts';
            Log::info('Exceso de intentos de login, bloqueo realizado', ['email' => $email, 'ip' => $ip]);
        }

		//Insertamos error de login para tener mas informacion
		$user->logLoginError($email, md5(Config::get('app.password_MD5') . $password), $her_pwd, Config::get('app.emp'), date("Y-m-d H:i:s"), $ip);

		if (!empty($user_inf)) {

			if ($user_inf[0]->baja_tmp_cli == 'W') {
				$responseError['msg'] = 'baja_tmp_doble_optin';

			} elseif ($user_inf[0]->baja_tmp_cli == 'S') {
				$responseError['msg'] = 'contact_admin';

			} elseif ($user_inf[0]->baja_tmp_cli == 'A') {
				$responseError['msg'] = 'activacion_casa_subastas';
			}
		}

		return $ajax ? $responseError : Redirect::to('/');
	}

    private function checkAndAddRateLimitBlock($key, $codCli = null)
    {
        if(!Config::get('app.login_attempts', 0)){
            return false;
        }

        RateLimiter::hit($key, Config::get('app.login_attempts_timeout', 60));

        if(!RateLimiter::tooManyAttempts($key, Config::get('app.login_attempts', 0))){
            return false;
        }

        //En caso de existir el usuario añadimos el bloqueo a la base de datos
        if($codCli){
            FxCliWeb::where('cod_cliweb', $codCli)->update(['bloqueado_en_cliweb' => now()]);
        }

        return true;
    }

	/**
	 * Login para Segre
	 */
	public function customLogin()
	{
		//$email = 'rsanchez@labelgrup.com';
		//$password = 'Wd94hT52';
		//$email = 'enadal@labelgrup.com';
		//$password = 'dFcp25Na9';
		// Build URL
		if (empty(Config::get('app.custom_login_url', ''))) {
			return;
		}

		$rules = array('email' => 'required|email', 'password' => 'required');
		$ip = $this->getUserIP();

		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {

			return array('status' => 'error', 'msg' => 'login_register_failed');
		}

		$email    = strtolower(Request::input('email'));
		$password = Request::input('password');

		$clientController = new ClientController();
		$baseUrl =  Config::get('app.custom_login_url');
		$params = http_build_query([
			'email' => $email,
			'password' => base64_encode($password)
		]);
		$finalUrl = $baseUrl . '?' . $params;

		// Request
		$content = file_get_contents($finalUrl);
		// Parse
		$json = json_decode($content);

		if ($json->success != 1) {
			return array(
				'status' => 'error',
				'msg' => 'login_register_failed'
			);
		}

		#si ya existe el email o el código de cliente usaremos este
		$client =FxCli::select("cod_cli,nom_cli,cod2_cli,usrw_cliweb,cod_div_cli,  baja_tmp_cli,emp_cliweb,gemp_cliweb, cod_cliweb, cod2_cliweb")->LeftJoinCliWebCli()->whereRaw('(LOWER(usrw_cliweb) = ? or cod2_cli =?)', [strtolower($json->email),$json->codcli])->first();

		//Si no existe se crea
		if (!$client) {

			$item = [
				'idorigincli' => $json->codcli,
				'email' => $json->email,
				'name' => $json->name,
				'registeredname' => mb_strtoupper($json->name)
			];
			$items[] = $item;
			$jsonClient = $clientController->createClient($items);
			$result = json_decode($jsonClient);

			if ($result->status == 'ERROR') {
				return array(
					'status' => 'error',
					'msg' => 'login_register_failed',
					'error' => $result
				);
			}

			//$client = FxCliWeb::joinCliCliweb()->whereRaw('LOWER(usrw_cliweb) = ?', [strtolower($json->email)])->where('cod2_cliweb', $json->codcli)->where('baja_tmp_cli', 'N')->first();
			$client =FxCli::select("cod_cli,nom_cli,cod2_cli,usrw_cliweb,cod_div_cli,  baja_tmp_cli,emp_cliweb,gemp_cliweb, cod_cliweb, cod2_cliweb")->LeftJoinCliWebCli()->whereRaw('(LOWER(usrw_cliweb) = ? or cod2_cli =?)', [strtolower($json->email),$json->codcli])->first();

		}else{

			#si esta dado de baja bloqueamos el acceso
			if($client->baja_tmp_cli != 'N'){
				return array(
					'status' => 'error',
					'msg' => 'contact_admin'
				);
			}

			#si exsiste cliente pero no tiene cliweb, creamos el cliweb
			if(!empty($client->cod_cli) && empty($client->cod_cliweb)){

				FxCliWeb::create(['cod_cliweb' => $client->cod_cli, 'cod2_cliweb' => $json->codcli, 'usrw_cliweb' => $json->email, 'email_cliweb' =>$json->email, 'nom_cliweb' =>$json->name,    ]);
				$client =FxCli::select("cod_cli,nom_cli,cod2_cli,usrw_cliweb,cod_div_cli,  baja_tmp_cli,emp_cliweb,gemp_cliweb, cod_cliweb, cod2_cliweb")->LeftJoinCliWebCli()->whereRaw('(LOWER(usrw_cliweb) = ? or cod2_cli =?)', [strtolower($json->email),$json->codcli])->first();

			}
			# si existe, y no tiene idorigen ponemos el bueno, antes se guardaba magento y lo hemso borrado
			elseif(!empty($client->cod_cliweb) && empty($client->cod2_cliweb)){

				FxCliWeb::where('cod_cliweb', $client->cod_cliweb)->update(['cod2_cliweb' => $json->codcli]);
				FxCli::where('cod_cli', $client->cod_cli)->update(['cod2_cli' => $json->codcli]);
				FxClid::where('cli_clid', $client->cod_cli)->update(['cli2_clid' => $json->codcli]);
			}

		}

		if($client->tipacceso_cliweb != 'X') {

			$userModel = new User();
			$this->SaveSession($client);
			$userModel->logLogin($client->cod_cli,Config::get('app.emp'),date("Y-m-d H:i:s"),$ip);

			return array('status' => 'success');
		}
		else{
			return array(
				'status' => 'error',
				'msg' => 'login_register_failed'
			);
		}

	}

	public function loginLanding(HttpRequest $request)
	{
		$back = $request->get('back', '');

		if(Session::has("user") && !empty($back)){
			Session::flush();
		}

		if (\Session::has("user")) {
			return Redirect::to('/');
		}

		request()->session()->flash('backUrl', url()->previous());

		return view('pages.user.login_landing', ['back' => $back]);
	}

    # Registrar un usuario
    public function registro(HttpRequest $request)
    {
		if(!empty(\Config::get('app.registerChecker'.$request["pri_emp"])) && !empty($request["pri_emp"])) {

			$camposFormNoNullables = explode(",", \Config::get('app.registerChecker'.$request["pri_emp"]));
			$errorsResponse = [
				"err"       => 1,
				"msg"       => 'error_register'
			];


			foreach ($camposFormNoNullables as $campo) {
				if (empty($request[$campo])) {
					$errorsResponse['check'] = $campo;
					return json_encode($errorsResponse);
				}
			}
		}

		if(Request::has('dni1') && Request::has('dni2')){
			if(!$this->validateNIFImages(request())){
				return json_encode(array(
					"err"       => 1,
					"msg"       => 'max_size_img'
				));
			}
		}

		if($request->has('files_email')) {
			$rules = [
				'files_email.*' => 'max:20000|mimes:jpg,jpeg,png,tiff,bmp,gif,pdf'
			];

			if(!$this->validateFiles($request->file(), $rules)){
				return json_encode([
					"err"       => 1,
					"msg"       => 'error_register_file'
				  ]);
			}
		}

		//Textos por defecto o toUpper
		$strToDefault = Config::get('app.strtodefault_register', 0);

        //Validación recaptcha
		if(Config::get('app.codRecaptcha', false) || Config::get('app.captcha_v3', false)) {
			$token = $request->input('captcha_token');
			$ip = $request->getClientIp();
			$email = $request->input('email');

			if(!ToolsServiceProvider::captchaIsValid($token, $ip, $email, Config::get('app.codRecaptcha'))) {
				return ["err" => 1, "msg"=> 'recaptcha_incorrect'];
			}
		}

        $user = new User();
        //Lo ponemos en el principio por que envia un email, asi savemos que idioma lo tenemos que enviar
        $locales = Config::get('app.locales');
        $lang = strtolower (Request::input('language'));

        /**
         * Buscamos los posibles idiomas en la tabla fsidioma o en el config locales
         */
        if (!empty(FsIdioma::getArrayValues())) {

            $langFacturaKeys = FsIdioma::getArrayValues();
            if (array_key_exists(strtoupper($lang), $langFacturaKeys) || array_key_exists($lang,$locales)) {
                $languages = strtoupper($lang);
            } else {
                $languages = strtoupper(Config::get('app.locale'));
            }
        }
        else{
            $languages = strtoupper (Config::get('app.locale'));
        }

        /*
         $response = array(
                    "err"       => 0,
                    "msg"       => trans(\Config::get('app.theme').'-app.login.success_register')
                );
         return json_encode($response);
         *
         */
        $rules = [
            //'regtype'  => 'required',          // Tipo de usuario
            'email'    => 'required|email',    // make sure the email is an actual email
            'password' => 'required|min:5'     // password can only be alphanumeric and has to be greater than 5 characters
            //Si se modifica el minimo de caracteres para el password, ha de cambiarse tambien en el forms.js
		];

		if(Config::get('app.strict_password_validation', false)) {
			$rules['password'] = ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:256'];
		}

        //VALIDAR SI EXISTE EL DADO DE ALTA PARA ESTA EMPRESA Y GRUPO DE EMPRESAS

		$ya_existe_nif_pais=false;
        $user_id_incorrecto = false;
		$nif = mb_strtoupper(Request::input('nif'));
		$characters_to_remove = array(" ", "_", "-");
		$nif = str_replace($characters_to_remove, "", $nif);

        if(!empty($nif) && !empty(Request::input('pais')) && Request::input('pais') == 'ES')
        {
			#$existe_dni_arr = DB::select("SELECT cod_cli  FROM FXCLI cl JOIN FXCLIWEB cw on cw.COD_CLIWEB = cl.COD_CLI WHERE upper(cl.CIF_CLI) = '$nif' AND cl.GEMP_CLI='". Config::get('app.gemp')."' AND cl.CODPAIS_CLI='".Request::input('pais')."' AND cw.USRW_CLIWEB ='".Request::input('email')."' AND EMP_CLIWEB='". Config::get('app.emp')."'");
			$existe_dni_arr = DB::select("SELECT cod_cli  FROM FXCLI cl JOIN FXCLIWEB cw on cw.COD_CLIWEB = cl.COD_CLI WHERE upper(cl.CIF_CLI) =  upper(:nif) AND cl.GEMP_CLI=:gemp AND cl.CODPAIS_CLI= :codPais AND  upper(cw.USRW_CLIWEB) =  upper(:email) AND EMP_CLIWEB=:emp", array("nif" => $nif, "gemp" => Config::get('app.gemp'), "codPais" => Request::input('pais'), "email" => Request::input('email'), "emp" => Config::get('app.emp')));

			if(isset($existe_dni_arr[0]) && isset($existe_dni_arr[0]->cod_cli) && strlen($existe_dni_arr[0]->cod_cli))
			{
				$ya_existe_nif_pais = true;
			}

			if (!self::validateNifNieCif($nif)) {
				$user_id_incorrecto = true;
			}
        }

        //debemos comprobar que este usuario no tenga ya creado el usuario y asociado un correo, esta asociando varios correos a un mism ousuario
         if(!empty($nif) && !empty(Request::input('email')))
        {
			#$existe_dni_arr = DB::select("SELECT cod_cli  FROM FXCLI cl JOIN FXCLIWEB cw on cw.COD_CLIWEB = cl.COD_CLI  WHERE upper(cl.CIF_CLI) = '$nif' AND cl.GEMP_CLI='". Config::get('app.gemp')."' AND cw.EMP_CLIWEB='".Config::get('app.emp')."'");
			$existe_dni_arr = DB::select("SELECT cod_cli  FROM FXCLI cl JOIN FXCLIWEB cw on cw.COD_CLIWEB = cl.COD_CLI  WHERE upper(cl.CIF_CLI) = :nif AND cl.GEMP_CLI= :gemp AND cw.EMP_CLIWEB= :emp", array("nif" => $nif, "gemp" => Config::get('app.gemp'),  "emp" => Config::get('app.emp')));
			if(isset($existe_dni_arr[0]) && isset($existe_dni_arr[0]->cod_cli) && strlen($existe_dni_arr[0]->cod_cli))
			{
				$ya_existe_nif_pais = true;
			}
        }

        //Comprobamos si este dni esta de baja si es a si no se puede registrar
		$ya_existe_cliweb=false;
        if(!empty($nif))
        {
			$existe_dni_arr = DB::select("SELECT cod_cli,BAJA_TMP_CLI  FROM FXCLI cl WHERE upper(cl.CIF_CLI) = :nif AND cl.GEMP_CLI=:gemp AND BAJA_TMP_CLI != 'N'", array("nif" => $nif, "gemp" => Config::get('app.gemp')));
			if(isset($existe_dni_arr[0]) && isset($existe_dni_arr[0]->cod_cli) && strlen($existe_dni_arr[0]->cod_cli)){
				$ya_existe_cliweb = true;
			}

        }

        if(!empty(Request::input('email')))
        {
			$existe_dni_arr = DB::select(" select * from fxcliweb where LOWER(USRW_CLIWEB) = LOWER(:email) AND GEMP_CLIWEB= :gemp and EMP_CLIWEB= :emp   and COD_CLIWEB != '-1' and COD_CLIWEB != '0' ", array( "gemp" => Config::get('app.gemp'), "email" => Request::input('email'), "emp" => Config::get('app.emp')));
			if(isset($existe_dni_arr[0]) && isset($existe_dni_arr[0]->cod_cliweb) && strlen($existe_dni_arr[0]->cod_cliweb))
			{
				$ya_existe_cliweb = true;
			}
        }

		$correos_diferentes = false;
		if (!empty(Request::input('email')) && !empty(Request::input('confirm_email') && (Request::input('email') != Request::input('confirm_email')))) {
			$correos_diferentes = true;
		}


		$fechas_exageradas = false;
		if (!empty(Request::input('date'))) {
			if (strtotime(Request::input('date')) > strtotime('now')) {
				$fechas_exageradas = true;
			}
			if (strtotime(Request::input('date')) < strtotime('1900-01-01')){
				$fechas_exageradas = true;
			}
		}

        # Tipo de registro, almacenado en WEB_CONFIG dentro de app
        //Request::input('regtype');

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
		#multipleNif es un config que permite repetir el dni al registar
        if (
			($ya_existe_nif_pais && !\Config::get("app.multipleNif"))
			|| $ya_existe_cliweb
			|| $correos_diferentes
			|| $user_id_incorrecto
			|| $fechas_exageradas
			|| $validator->fails()
		)
        {
            if($ya_existe_nif_pais)
            {
                Log::info('REGISTRO_ERRONEO NIF existe : '.print_r(Input::all(), true) );

				$response = array(
					"err"       => 1,
					"msg"       => 'error_exist_dni'
				);
            }
            elseif ($user_id_incorrecto)
            {
                Log::info('REGISTRO_ERRONEO NIF incorrecto : '.print_r(Input::all(), true) );

				$response = array(
					"err"       => 1,
					"msg"       => 'error_nif'
				);
            }
            else
            {
                Log::info('REGISTRO_ERRONEO validator fails or existe cliweb : '.print_r(Input::all(), true) );
				$response = array(
					"err"       => 1,
					"msg"       => 'error_register'
				);
            }

        }
        else
        {

			$shipping_label = \Config::get("app.shipping_label");
			if (empty($shipping_label)) {
				$shipping_label = 'W1';
			}

            $user->email = Request::input('email');
            $user->nif = str_replace($characters_to_remove, '', trim(Request::input('nif')));
            $user->gemp = Config::get('app.gemp');
            $check_if_exists = $user->getUserByEmail(true);

            if(Request::input('date')){
                $fecnac_cli_temp = Request::input('date');
                $fecnac_cli = date('Y-m-d', strtotime($fecnac_cli_temp));
            }else{
                $fecnac_cli = NULL;
            }

            if(Request::input('sexo')){
                $sexo = Request::input('sexo');
            }else{
				$sexo = NULL;
            }


            if (!empty($check_if_exists[0]) && !empty($check_if_exists[0]->usrw_cliweb)) {
                Log::info('REGISTRO_ERRONEO email exist : '.print_r(Input::all(), true) );
                $response = array(
					"err"       => 1,
					"msg"       => 'email_already_exists'
				);
            } else {
                # Auto increment

                //NGAMEZ obtener longitud minima del codigo de usuario y poder preparar los 0 delante
                $res = DB::select("SELECT TCLI_PARAMS AS longitud FROM fsparams WHERE emp_params = :emp", array("emp" => Config::get('app.emp')) );
                $longitud = $res['0']->longitud;

                if(Config::get('app.registro_user_w')){

                    $num_temp = head(DB::select( "select CONTADOR2_ORA(:nom,:gemp,:fecha,:letra) as contador from dual",
						array(
							'nom'   => 'c01',
							'gemp'       => Config::get('app.gemp'),
							'fecha' => '2000-01-01 00:00:00',
							'letra' => 'Z'
						)
                    ));
                    $num = 'W'.str_pad($num_temp->contador, $longitud, 0, STR_PAD_LEFT);

                }else{
                    $res = DB::select("SELECT NVL(MAX(CAST(COD_CLI AS Int)) + 1, 1) AS numero FROM FXCLI WHERE TRANSLATE(cod_cli, 'T 0123456789', 'T') IS NULL AND cod_cli IS NOT NULL and FXCLI.GEMP_CLI ='". Config::get('app.gemp')."' ");
                    $num = $res['0']->numero;
                    $num = str_pad($num, $longitud, 0, STR_PAD_LEFT);
                }
                //$num = str_pad($num, 5, 0, STR_PAD_LEFT); NGAMEZ, ORIGINAL
                //NGAMEZ , CAMBIADO EL 5 POR LA LONGITUD VARIABLE DE LA BASE DE DATOS
                $num = str_pad($num, $longitud, 0, STR_PAD_LEFT);

				$tipo_cli = request('type_user', 'W');


                /*
                |--------------------------------------------------------------------------
                | Tipo de registro de usuarios en la web
                |--------------------------------------------------------------------------
                |   1- Registro en ERP Y WEB
                |   2- Registro en ERP y WEB pero con validación desde ERP
				|   3- Envío de email con los datos del registro
				|	4- Pendiente de activar por api
                */
                if(Config::get('app.regtype') == 1) {
                    $BAJA_TMP_CLI = 'N';
                } elseif(Config::get('app.regtype') == 2) {
                    $BAJA_TMP_CLI = 'S';
				}
				elseif(Config::get('app.regtype') == 4) {
                    $BAJA_TMP_CLI = 'A';
                }

                Log::info('----- EMAIL REGISTRO TIPO: '.Config::get('app.regtype').' -----');
                $nombre_pais = "";
                if(!empty(Request::input('pais')))
                {
                  $pais = DB::select("SELECT des_paises FROM FSPAISES WHERE cod_paises = :codPais", array("codPais" =>Request::input('pais') ));
                  if(count($pais) > 0){
                      $nombre_pais = $pais[0]->des_paises;
                  }
                }

				//TITULO DE LA ACTIVIDAD
				$job_name = substr(trim(Request::input('cargo', "")), 0, 15);

                if(!empty(Request::input('trabajo')))
                {
                  $job_name_arr = DB::select("SELECT DESC_CNAE FROM FSCNAE WHERE LENGTH(COD_CNAE)=4 AND COD_CNAE = :trabajo", array("trabajo" =>Request::input('trabajo') ));
                  if(isset($job_name_arr[0]) && isset($job_name_arr[0]->desc_cnae) && strlen($job_name_arr[0]->desc_cnae))
                  {
                    $job_name = $job_name_arr[0]->desc_cnae;
                    //15 caracteres maximo
                    if(strlen($job_name)>14)
                    {
                      $job_name = substr($job_name, 0, 15);
                    }
                  }
                }

                $prov_name="";

                if(!empty(Request::input('provincia')))
                {
                    $prov_name = Request::input('provincia');
                    $prov_name = mb_substr($prov_name,0,30,'UTF-8');
                }

                $via='';
                if(!empty(Request::input('codigoVia')))
                {
                    $via = Request::input('codigoVia');
                }

                $dir = Request::input('direccion');
                $direccion = $strToDefault ? mb_substr($dir,0,30,'UTF-8') : strtoupper(mb_substr($dir,0,30,'UTF-8'));
                $direccion2 = $strToDefault ? mb_substr($dir,30,30,'UTF-8') : strtoupper(mb_substr($dir,30,30,'UTF-8'));
                //hay un error y está guardando un 0 si esta linea viene vacia
                if(empty($direccion2)){
                    $direccion2 = NULL;
                }

                # Solo entramos en caso de que no sea registro por email
                if(Config::get('app.regtype') == 1 || Config::get('app.regtype') == 2 || Config::get('app.regtype') == 4)
                {
                    //comprobamos si existe un cliente con ese nif
                    $u=$user->getUserByNif('N');
                    $emp = Request::input('pri_emp');

                    $rsoc_empresa = $strToDefault ? Request::input('rsoc_cli') : strtoupper(Request::input('rsoc_cli'));

                    $contact_empresa = $strToDefault ? Request::input('contact') : strtoupper(Request::input('contact'));

					$name = trim(Request::input('usuario'));

					$nomd_clid = trim(Request::input('usuario_clid', Request::input('usuario')));



                    if(!empty(Request::input('last_name'))){

						if(Config::get('app.name_without_coma', 0)){
							$name = $name . ' ' . trim(Request::input('last_name'));
						}
						else{
							$name = trim(Request::input('last_name')). ', '. $name;
						}

					}
                    $rsoc = $name;
					//En carlandia guardamos rsoc también en los usuarios F
					if(config('app.rsoc_in_user_f', false)){
						$rsoc = $rsoc_empresa ?? $name;
					}


					$tipv_cli ="";
                    //emppresa
                    if($emp == 'J'){
						if(!empty($rsoc_empresa) ){
							$rsoc = $rsoc_empresa;
						}
						if(!empty($contact_empresa)){
							$name = $contact_empresa;
						}
						$tipv_cli = request("tipv_cli");


					}
					elseif ($emp == 'R') {

						if(!empty($rsoc_empresa)){
							$name = $rsoc_empresa;
						}
						if(!empty($contact_empresa)){
							$rsoc = $contact_empresa;
						}
					}

					#datos nuevos de empresa, si no hay campo que llegue como null
					$docid_cli = $strToDefault ? Request::input("docid_cli") : strtoupper(Request::input("docid_cli"));
					$tdocid_cli = $strToDefault ? Request::input("tdocid_cli") : strtoupper(Request::input("tdocid_cli"));

					$name = $strToDefault ? $name : mb_strtoupper($name,'UTF-8');
					$rsoc = $strToDefault ? $rsoc : mb_strtoupper($rsoc,'UTF-8');
					$nomd_clid = $strToDefault ? $nomd_clid : mb_strtoupper($nomd_clid,'UTF-8');

					$forma_pago = $user->getDefaultPayhmentMethod($request->input('pais'));

                    $parametros = new Enterprise();
                    $param = $parametros->getParameters();
                    $envcorr = $param->enviodef_prmgt;

                    $iva_cli=$this->cliente_tax(Request::input('pais'),Request::input('cpostal'));
                   /* ARGI HA PEDIDO QUE SE CREEN LOS USUARIOS CON LA W AUNQUE ESTE dni YA EXISTIERA 2019_04_24*/
                    //si no existe un cliente con ese NIF
					#si se permite multiples dni
                    if (empty($u) || Config::get('app.registro_user_w') || \Config::get("app.multipleNif")){

                        $exist_user=$user->getUserByNif('S');
                        if(!empty($exist_user)){


                            Log::info('REGISTRO_ERRONEO existe usuario : '.print_r(Input::all(), true) );
                             $response = array(
                                "err"       => 1,
                                "msg"       => 'error_contact_emp'
                            );
                            return json_encode($response);
						}

						$cod2_cli = str_replace("0","W",$num);
						//si el registro es tipo 4 el cod2_cli (idorigen) se establece por api
						if(Config::get('app.regtype') == 4){
							$cod2_cli = '';
						}


                        $envio= array(
                        'clid_direccion'  => $strToDefault ? mb_substr(Input::get('clid_direccion'),0,30,'UTF-8') : strtoupper(mb_substr(Input::get('clid_direccion'),0,30,'UTF-8')),
                        'clid_direccion_2'  => $strToDefault ? mb_substr(Input::get('clid_direccion'),30,30,'UTF-8') : strtoupper(mb_substr(Input::get('clid_direccion'),30,30,'UTF-8')),
                        'clid_cod_pais'   => Input::get('clid_pais'),
                        'clid_poblacion'   => $strToDefault ? mb_substr(Input::get('clid_poblacion'),0,30,'UTF-8') : strtoupper(mb_substr(Input::get('clid_poblacion'),0,30,'UTF-8')),
                        'clid_cpostal'   =>Input::get('clid_cpostal'),
                        'clid_pais' => DB::select("SELECT des_paises FROM FSPAISES WHERE cod_paises = :codPais", array("codPais" => Request::input('clid_pais', Request::input('pais')))),
                        'clid_via' => !empty(Input::get('clid_codigoVia'))?Input::get('clid_codigoVia'):null,
                        'clid_provincia'    => !empty(Input::get('clid_provincia'))?Input::get('clid_provincia'):null,
                        'clid_name'=> $nomd_clid,
                        'clid_telf'=> Request::input('tele_clid', Request::input('telefono')),
                        'clid_rsoc'=> $rsoc,
                        'codd_clid'=> $shipping_label,
                        'cod2_clid'=> $cod2_cli,
						'preftel_clid' => request('preftel_clid', request('preftel_cli', ''))
                        );




                             //se inserta el nuevo cliente
                        $FXCLI = DB::select("INSERT INTO FXCLI
                           (GEMP_CLI, COD_CLI, COD_C_CLI, TIPO_CLI, RSOC_CLI, NOM_CLI,  DIR_CLI, DIR2_CLI, CP_CLI, POB_CLI, PRO_CLI, TEL1_CLI, BAJA_TMP_CLI, FPAG_CLI, EMAIL_CLI, CODPAIS_CLI, CIF_CLI, CNAE_CLI, PAIS_CLI, SEUDO_CLI, F_ALTA_CLI, SEXO_CLI, FECNAC_CLI,FISJUR_CLI, ENVCORR_CLI, IDIOMA_CLI,SG_CLI,TEL2_CLI,IVA_CLI,OBS_CLI,RIES_CLI,COD_DIV_CLI, DOCID_CLI, TDOCID_CLI, TIPV_CLI, COD2_CLI, PREFTEL_CLI, ORIGEN_CLI, BLOCKPUJ_CLI )
                           VALUES
                           ('".Config::get('app.gemp')."', '".$num."', '4300', '$tipo_cli', :rsoc, :usuario,  :direccion, :direccion2, :cpostal, :poblacion, :provincia, :telf, '".$BAJA_TMP_CLI."', :forma_pago, :email, :pais, :dni, :trabajo, :nombrepais, :nombre_trabajo, :fecha_alta, :sexo_cli, :fecnac_cli, :pri_emp, :envcorr,:lang,:sg,:mobile,:ivacli,:obs,:ries_cli,:divisa, :docid_cli, :tdocid_cli, :tipv_cli, :cod2_cli, :preftel_cli, :origen_cli, :blockpuj_cli)",
                            array(
                               //'gemp'          => "'Config::get('app.gemp')'",
                               'email'         => $strToDefault ? Request::input('email') : strtoupper(Request::input('email')),
                               //'cod_cliweb'    => $num,
                               //'emp'           => Config::get('app.emp'),
                               'usuario'       => $name,
                               'rsoc'          => $rsoc ,
                               'direccion'     => $direccion,
                               'direccion2'     => $direccion2,
                               'cpostal'       => $strToDefault ? Request::input('cpostal') : strtoupper(Request::input('cpostal')),
                               'poblacion'     => $strToDefault ? mb_substr(Input::get('poblacion'),0,30,'UTF-8') : strtoupper(mb_substr(Input::get('poblacion'),0,30,'UTF-8')),
                               'provincia'     => $strToDefault ? $prov_name : strtoupper($prov_name),
                               'telf'          => Request::input('telefono'),
                               'mobile'          => !empty(Request::input('mobile'))?Request::input('mobile'):null,
                               'pais'          => Request::input('pais'),
                               'dni'           => str_replace($characters_to_remove, '', trim(Request::input('nif'))),
                               'trabajo'       => $strToDefault ? Request::input('trabajo') : strtoupper(Request::input('trabajo')),
                               'nombrepais'    => $strToDefault ? $nombre_pais : strtoupper($nombre_pais),
                               'nombre_trabajo'=> $strToDefault ? $job_name : strtoupper($job_name),
                               'fecha_alta'    => date("Y-m-d H:i:s"),
                               'forma_pago'     => $forma_pago,
                               'sexo_cli' => $sexo,
                               'fecnac_cli' => $fecnac_cli,
                               'pri_emp' => Request::input('pri_emp'),
                               'envcorr'       => $envcorr,
                               'lang' => $languages,
                               'sg'  => $via,
                               'obs' => !empty(Request::input('obscli'))?Request::input('obscli'):null,
                               'ivacli' =>$iva_cli,
                               'ries_cli'=>$param->riesgo_prmgt,
							   'divisa'=> !empty(Request::input('divisa'))?Request::input('divisa'):null,
							   'docid_cli' =>  $docid_cli,
							   'tdocid_cli' =>  $tdocid_cli,
							   'tipv_cli' =>  $tipv_cli,
							   'cod2_cli' => $cod2_cli,
							   'preftel_cli' => request('preftel_cli', ''),
							   'origen_cli' => request('origen', null),
							   'blockpuj_cli' => $this->defaultBidBlocking()
                               )
						 );
						#guardamos el evento SEO de registro de usuario
						SeoLib::saveEvent("REGISTER");


                        //creamos la fxcli2 con ENVCAT_CLI2
                            $FXCLI2 = DB::select("INSERT INTO FXCLI2  (GEMP_CLI2, COD_CLI2, ENVCAT_CLI2, COD2_CLI2)  VALUES  ('".Config::get('app.gemp')."', '".$num."','N', '$cod2_cli')");

                        //inserta dirección de envio
                         if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1){

                            $addres = new Address();

							//En carlandia utilizamos la direccion multiple para guardar datos de contacto
							if(config('app.contact_adress', 0)){
								try {
									if($tipo_cli == FxCli::TIPO_CLI_VENDEDOR){
										$addres->addContacto($request, $num);
									}
								} catch (\Throwable $th) {
									Log::error('Error direccion contacto', ['error' => $th]);
								}
							}
							else{
								$max_direcc = $shipping_label;
								$addres->addDirEnvio($envio,$num,$name);
							}

                         }


                                       //Guardar favoritos
                        $data['favorites'] = $user->favorites();
                        $emp  = Config::get('app.emp');
                        foreach($data['favorites'] as $favorites){
                           $interest = Request::input("interest_".$favorites->cod_tsec);
                           if(!empty($interest)){
                               $user->addfavorites($emp,$num,$favorites->cod_tsec);
                           }
                        }


                    }
                    else{
                        //asignamos el codigo de cliente a la variable num para que los usuarios web queden ligados a este cliente
                        $num = $u[0]->cod_cli;
						$cod2_cli = str_replace("0","W",$num);
						//si el registro es tipo 4 el cod2_cli (idorigen) se establece por api
						if(Config::get('app.regtype') == 4){
							$cod2_cli = '';
						}
                        //Si el usuario se registra por la web y ya tiene cliente, comprobamos que su email_cli que no este vacio,
                        //si esta vacio le ponemos el email que se da de alta


                         //Cuando el cliente se da de alta y el cliente ya existe updatemos la información del cliente (FXCLI)
                        if(Config::get('app.update_fxcli_exist_client')){

                            $sql = "UPDATE FXCLI
                            SET RSOC_CLI = :rsoc, NOM_CLI = :usuario,DIR_CLI = :direccion, DIR2_CLI = :direccion2, CP_CLI = :cpostal,
                            POB_CLI = :poblacion , PRO_CLI = :provincia, TEL1_CLI = :telf, FPAG_CLI = :forma_pago, EMAIL_CLI = :email,
                            CODPAIS_CLI = :pais, CNAE_CLI = :trabajo, PAIS_CLI = :nombrepais, SEUDO_CLI = :nombre_trabajo, F_MODI_CLI = :fecha_modi, SEXO_CLI = :sexo_cli,
                            FECNAC_CLI = :fecnac_cli, FISJUR_CLI = :pri_emp,  ENVCORR_CLI = :envcorr,  IDIOMA_CLI = :lang, SG_CLI = :sg, IVA_CLI = :ivacli, COD_DIV_CLI = :divisa
                            WHERE GEMP_CLI = :gemp AND COD_CLI = :cod_cli";

                            $bindings =  array(
                               'cod_cli'=> $num,
                               'gemp'          => Config::get('app.gemp'),
                               'email'         => $strToDefault ? Request::input('email') : strtoupper(Request::input('email')),
                               'usuario'       => $name,
                               'rsoc'          => $rsoc ,
                               'direccion'     => $direccion,
                               'direccion2'     => $direccion2,
                               'cpostal'       => $strToDefault ? Request::input('cpostal') : strtoupper(Request::input('cpostal')),
                               'poblacion'     => $strToDefault ? mb_substr(Input::get('poblacion'),0,30,'UTF-8') : strtoupper(mb_substr(Input::get('poblacion'),0,30,'UTF-8')),
                               'provincia'     => $strToDefault ? $prov_name : strtoupper($prov_name),
                               'telf'          => Request::input('prefix', '') . Request::input('telefono'),
                               'pais'          => Request::input('pais'),
                               'trabajo'       => $strToDefault ? Request::input('trabajo') : strtoupper(Request::input('trabajo')),
                               'nombrepais'    => $strToDefault ? $nombre_pais : mb_strtoupper($nombre_pais,'UTF-8'),
                               'nombre_trabajo'=> $strToDefault ? $job_name : strtoupper($job_name),
                               'fecha_modi'    => date("Y-m-d H:i:s"),
                               'forma_pago'     => $forma_pago,
                               'sexo_cli' => $sexo,
                               'fecnac_cli' => $fecnac_cli,
                               'pri_emp' => Request::input('pri_emp'),
                               'envcorr'       => $envcorr,
                               'lang' => $languages,
                               'sg'  => $via,
                               'ivacli' =>$iva_cli,
                               'divisa'=> !empty(Request::input('divisa'))?Request::input('divisa'):null,
                               );


                            DB::select($sql, $bindings);
                        }else{
                            $user->cod_cli = $num;
                            $inf_user= $user->getUserByCodCli('N');
                            if(!empty($inf_user) && empty($inf_user[0]->email_cli) ){
                                DB::select("UPDATE FXCLI SET
                                            email_cli = :email
                                            WHERE COD_CLI = :cod_cli and GEMP_CLI = :gemp",
                                             array(
                                                'gemp'          => Config::get('app.gemp'),
                                                'email'         => strtolower(Request::input('email')),
                                                'cod_cli'    => $num,
                                                )
                                        );
                            }
                        }
                    }

                    $check_if_exists = $user->getUserByEmail(false);
                    //print_r($check_if_exists);
                    $password = Request::input('password');
                    $password_encrypt = NULL;
                    if(!empty(Config::get('app.multi_key_pass'))){
						$newKey=md5(time());
						$password_encrypt =  md5($newKey.$password).":".$newKey;
					}elseif(!empty(Config::get('app.password_MD5'))){
							$password_encrypt =  md5(Config::get('app.password_MD5').$password);
					}

                    if(!empty(Request::input('newsletter'))){
                        $newsletter = 'S';
                    }else{
                        $newsletter = 'N';
					}
					//Añadidas para Jesús Vico, para aceptar contatcto con otras casas de subastas, para verificar información
					if(!empty(Request::input('condiciones2'))){
                        $condiciones2 = 'S';
                    }else{
                        $condiciones2 = 'N';
                    }

					$newsletter2 = 'N';
					if(!empty(Request::input('newsletter2'))){
						$newsletter2 = 'S';
					}

                    //Si existe el usuario es que es un usuario de newsletter y hay que actualizar en vez de insertar
                    if(is_array($check_if_exists) && count($check_if_exists)>0 && ($check_if_exists[0]->cod_cliweb == 0 || $check_if_exists[0]->cod_cliweb == -1 ))
                    {

                          $FXCLIWEB = DB::select("UPDATE FXCLIWEB SET
                              GEMP_CLIWEB = :gemp, COD_CLIWEB = :cod_cliweb ,COD2_CLIWEB = :cod2   , PWDWENCRYPT_CLIWEB = :password_encrypt, EMP_CLIWEB = :emp, TIPACCESO_CLIWEB = 'N', TIPO_CLIWEB = 'C', NOM_CLIWEB = :usuario, NLLIST1_CLIWEB = :nllist1, NLLIST2_CLIWEB = :nllist2, IDIOMA_CLIWEB = :lang, PUBLI_CLIWEB = :publi
                              WHERE (LOWER(USRW_CLIWEB) = :email ) and EMP_CLIWEB = :emp and GEMP_CLIWEB = :gemp
                              ",
                               array(
                                  'gemp'          => Config::get('app.gemp'),
                                  'email'         => strtolower(Request::input('email')),
                                  'cod_cliweb'    => $num,
                                  'emp'           => Config::get('app.emp'),
                                  'password_encrypt'      => $password_encrypt,
                                  'usuario'       => $name,
                                  'nllist1' =>   $newsletter,
								  'nllist2' =>  $newsletter2,
								  'lang' => $languages,
								  'cod2' => $cod2_cli,
								  'publi' => $condiciones2
                                  )
                          );


                    }
                    else
                    {

                          $FXCLIWEB = DB::select("INSERT INTO FXCLIWEB
                              (GEMP_CLIWEB, COD_CLIWEB, USRW_CLIWEB, PWDWENCRYPT_CLIWEB, EMP_CLIWEB, TIPACCESO_CLIWEB, TIPO_CLIWEB, NOM_CLIWEB, EMAIL_CLIWEB, NLLIST1_CLIWEB, NLLIST2_CLIWEB, FECALTA_CLIWEB,IDIOMA_CLIWEB,COD2_CLIWEB,PUBLI_CLIWEB   )
                              VALUES
                              (:gemp, :cod_cliweb, :email, :password_encrypt, :emp, 'N', 'C', :usuario, :email,:nllist1, :nllist2, :fecha_alta,:lang, :cod2, :publi)",
                               array(
                                  'gemp'          => Config::get('app.gemp'),
                                  'email'         => Request::input('email'),
                                  'cod_cliweb'    => $num,
                                  'emp'           => Config::get('app.emp'),
                                  'password_encrypt'      => $password_encrypt,
                                  'usuario'       =>$name,
                                  'nllist1' =>   $newsletter,
								  'nllist2' =>  $newsletter2,
                                  'fecha_alta'    => date("Y-m-d H:i:s"),
								  'lang' => $languages,
								  'cod2' => $cod2_cli,
								  'publi' => $condiciones2
                                  )
                          );


                    }

					//Añadimos a newsletter controlando tanto el sistema nuevo como el antiguo
					if(!empty(Request::input('newsletter')) || !empty($request->get('families'))){

						if(empty($request->get('families'))) {
							$request->merge(['families' => [Fx_Newsletter::GENERAL => 1]]);
						}
						(new NewsletterController())->setNewsletter($request, "add");
					}

                    $user->cod_cli = $num;
                    $inf_user = $user->getUser();

					if(Request::has('dni1') && Request::has('dni2')){
						$this->saveImages(request(), $num);
					}
					if(Request::has('creditcard_fxcli')){
						$this->saveCreditCard(request(), $num);
					}

					if(Request::has('user_files')){
						$this->saveFiles(request(), $num);
					}

                    if(!empty($u)){
                        # Enviamos email notificando la asociación de un cliente con un usuario web
                            $email = new EmailLib('USER_ASSOCIATED');
                            if(!empty($email->email)){
                                $email->setUserByCod($num);
                                $email->setTo(Config::get('app.admin_email'));
                                $email->send_email();
                            }
                    }

					# Enviamos email notificando nuevo usuario web
					$email = $tipo_cli != FxCli::TIPO_CLI_VENDEDOR ? new EmailLib('NEW_USER_ADMIN') : new EmailLib('NEW_CEDENTE_ADMIN');
					if(!empty($email->email)){
						$email->setUserByCod($num);
						$email->setAtribute("OBS",Request::input('obscli'));

						if(!empty($job_name)){
							$email->setAtribute("JOB_CLI", $job_name);
						}

						if($request->has('files_email')) {
							$files = $request->file('files_email');
							$email->setAttachmentsFiles($files);
						}

						$email->setTo(Config::get('app.admin_email'));
						$email->send_email();
					}

					//solo se puede enviar un tipo de correo al usuario, se ha dado de alta, o emai lde doble optin
					$email = new EmailLib('DOUBLE_OPT_IN');
					if(!empty($email->email)){
							//EMAIL DOBLE OPT-in
							$email->setUserByCod($num,true);
						$email_user = Request::input('email');
						$code = \Tools::encodeStr($email_user.'-'.$num);
						$languages = strtolower($languages);
						$url =  \Config::get('app.url').'/'.$languages.'/email-validation?code='.$code.'&email='.$email_user.'&type=new_user';
						$email->setUrl($url);
						$email->send_email();
						$user->BajaTmpCli($num,'W',date("Y-m-d H:i:s"),'W');
					}elseif($tipo_cli == FxCli::TIPO_CLI_VENDEDOR){
						$email = new EmailLib('NEW_USER_CEDENTE');
						if(!empty($email->email)){
							$email->setUserByCod($num,true);
							$email->setPassword($password);
							$email->send_email();
						}
					}
					else{
						$email = new EmailLib('NEW_USER');
						if(!empty($email->email)){
							$email->setUserByCod($num,true);
							$email->setPassword($password);
							$email->send_email();
						}
					}
					#si la casa de subastas tiene webService de cliente

					if(Config::get('app.WebServiceClient')){
						$theme  = Config::get('app.theme');
						$rutaClientcontroller = "App\Http\Controllers\\externalws\\$theme\ClientController";

						$clientController = new $rutaClientcontroller();

						$clientController->createClient($num);
					}


                    # Enviamos un email de notificacion al usuario
                    /*Mail::send('emails.account', $emailOptions, function ($m)  {
                        $m->from(Config::get('app.from_email'), Config::get('app.name'));

                        $m->to(Request::input('email'), Request::input('usuario'))->subject(trans(\Config::get('app.theme').'-app.emails.register_in').' '.Config::get('app.name'));
                    });*/
                    ######### FIN ENVIAR EMAIL ##########
                }
                elseif(Config::get('app.regtype') == 3)
                {
                    # Registro enviando datos de registro al administrador, sin insertar nada en la DB
                    $Mailer = new \App\Http\Controllers\MailController;
                    $contenido = $Mailer->processVars();
                    $emailOptions = $contenido;
                        $email = new EmailLib('USER_CHANGE_INFO');
                        if(!empty($email->email)){
                           $email->setTo(Config::get('app.admin_email'));
                           $email->setHtml($emailOptions['camposHtml']);

                            if ($email->send_email()){
                                $response = array(
                                    "err"       => 0,
                                    "msg"       => 'user_panel_inf_email_actualizada'
                                );
                            }else{
                                $response = array(
                                    "err"       => 1,
                                    "msg"       => 'user_panel_inf_email_error'
                                );
                            }
                            $email->send_email();
                        }


                    ######### FIN ENVIAR EMAIL TIPO 3 ##########

                }

                if (\Config::get("app.coregistroSubalia") && Request::input('condicionesSubalia')) {

                  $hash = $this->createHash();

                  $ch = curl_init();
                  $post = "hash=".$hash."&cod_cli_subasta=".Config::get("app.subalia_cli");
                  $info = Input::all();
                  foreach($info as $k => $item) {
                    $post .= "&".$k."=".$item;
                  }

                  curl_setopt($ch, CURLOPT_URL,\Config::get("app.subalia_url_coregistro"));
                  curl_setopt($ch, CURLOPT_POST, TRUE);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $remote_server_output = curl_exec ($ch);
                  curl_close ($ch);

				}


				######### CREAR USUARIO EN PRESTASHOP ##########
                /**
                 * @Pendiente
                 * Multiples direcciones
				 *
				 * Eloy 21/02/22: Cambiamos gallery a Laravel, por lo que ya no hace falta
				 * registrar en presta
				 * */

                /* if (!empty(\Config::get("app.ps_activate"))){

					$last = (!empty(Request::input('last_name'))) ? Request::input('last_name') : Request::input('usuario');
                    $name = Request::input('usuario');

                    $resp = $this->addUserToPresta(Request::input('password'), $last, $name, Request::input('email'), Request::input('sexo'), Request::input('date'));

                    if($resp != false){
                        $id_Presta = $resp->customer->id;
                        $this->addAressToPresta($id_Presta, Request::input('nif'), $last, $name, Request::input('codigoVia') . " " . Request::input('direccion'), mb_substr(Input::get('poblacion'), 0, 30, 'UTF-8'), Input::get('clid_pais'), Request::input('cpostal'), Request::input('telefono'));
                    }
                } */
                ######### FIN CREAR USUARIO EN PRESTASHOP ##########


                if( (Config::get('app.regtype') == 1 || Config::get('app.regtype') == 2) && config('app.login_when_sign', 1) && empty(\Config::get("app.ps_activate"))) {

                    $this->login_post_ajax($request);
				}

                 /***************************************************************
                 *
                 * Control en caso de que el registro probenga de una llamada de subalia
                 *
                 ***************************************************************/

                $redirect = "";
                if(!empty(Request::input('subalia'))){

                    $method = 'AES-256-ECB';
                    $urlSubalia = Config::get("app.subalia_URL", "https://subalia.es");

                    $cliAuchouse = Config::get('app.subalia_cli');


                    $info = Request::input('info');

                    if (!empty($cliAuchouse) && !empty($info)) {

                        $key = SubAuchouse::select('COD_AUCHOUSE', 'HASH')
                                ->where('CLI_AUCHOUSE', '=', $cliAuchouse)
                                ->where('EMP_ORIGIN_AUCHOUSE', '=', Config::get('app.emp'))
                                ->get();

                        if (!empty($key)) {
                            $info_decript = openssl_decrypt(base64_decode($info), $method, $key[0]->hash, OPENSSL_RAW_DATA);
                            $info_json = json_decode($info_decript);
                        }
                    } else {
                        return "error al desencriptar";
                    }


                    $data['info'] = array(
                        'email_auchouse' => strtolower(Request::input('email')),
                        'codcli_subalia' => $info_json->cod_cli,
                        'new_user' => 'S'
                    );



                    $data['info'] = base64_encode(openssl_encrypt(json_encode($data['info']), $method, $key[0]->hash, OPENSSL_RAW_DATA));

                    $response = array(
                        "err"       => 0,
                        "msg"       => '/'.(\Routing::slugSeo('usuario-registrado')),
                        "info" => $data['info'],
                        "redirect" => $urlSubalia . $info_json->redirect,
                        "cod_auchouse" => $key[0]->cod_auchouse
                    );

                }
                else{

                    $response = array(
                        "err"       => 0,
                        "msg"       => '/'.(\Routing::slugSeo('usuario-registrado')),
						"backTo" => request('backUrl', false)
                    );
                }

            }


        }

    return json_encode($response);
    }

	private function validateNIFImages($request)
	{

		$rules = [
			'dni1' => 'max:1000000|mimes:jpg,jpeg,png,pdf,webp,heic,heif,JPG,JPEG,PNG,PDF,WEBP,HEIC,HEIF', //a required, max 10000kb, doc or docx file
			'dni2' => 'max:1000000|mimes:jpg,jpeg,png,pdf,webp,heic,heif,JPG,JPEG,PNG,PDF,WEBP,HEIC,HEIF'
		];

		$validator = Validator::make($request->file(), $rules);

		if (!$validator->fails() && $request->file('dni1')->isValid() && $request->file('dni2')->isValid()) {
			return true;
		}

		Log::error($validator->errors());
		return false;
	}

	private function validateFiles($files, $rules)
	{
		$validator = Validator::make($files, $rules);

		if (!$validator->fails()) {
			return true;
		}

		return false;
	}

	private function saveImages($request, $cod_cli)
	{
		if (Config::get('app.dni_in_storage', false) == "cli-documentation") {
			$this->saveDni($request, $cod_cli, 'dni1', User::getUserNIF($cod_cli) . 'A');
			$this->saveDni($request, $cod_cli, 'dni2', User::getUserNIF($cod_cli) . 'R');
			return;
		}
		$this->saveDni($request, $cod_cli, 'dni1');
		$this->saveDni($request, $cod_cli, 'dni2');
	}

	private function dniPath($cod_cli)
	{
		$emp = Config::get('app.emp');
		$path = match (Config::get('app.dni_in_storage', false)) {
			"dni-files" => storage_path("app/files/dni/$emp/$cod_cli/files/"),
			"cli-documentation" => storage_path("app/files/CLI/Archivos/$emp/$cod_cli/documentation/"),
			"base-dni-files" => base_path("dni/$emp/$cod_cli/files/"),
			default => storage_path("app/files/dni/$emp/$cod_cli/files/"),
		};

		return $path;
	}

	public function saveDni($request, $cod_cli, $fileName, $nameOfFile = null)
	{
		try {
			$file = $request->file($fileName);
			if(!$file || !$file->isValid()){
				return false;
			}

			$filename = ($nameOfFile ? $nameOfFile : $fileName) . '.' . $file->getClientOriginalExtension();
			$destinationPath = $this->dniPath($cod_cli);

			if(!is_dir($destinationPath)){
				mkdir($destinationPath, 0755, true);
			}

			$file->move($destinationPath, $filename);

			return true;

		} catch (\Throwable $th) {
			Log::error($th);
			return false;
		}
	}

	private function saveFiles($request, $cod_cli)
	{
		$errorMessage = 'Error al guardar los archivos del usuario en el registro';
		if(!ToolsServiceProvider::isValidMime($request, ['user_files[]' => 'mimes:jpg,jpeg,png,pdf'])){
			Log::error($errorMessage, ['client' => $cod_cli, 'error' => 'Invalid mime type']);
			return;
		}

		$files = ToolsServiceProvider::validFiles($request->file('user_files'));

		try {
			(new User)->storeFiles($files, $cod_cli);
		} catch (\Throwable $th) {
			Log::error($errorMessage, ['client' => $cod_cli, 'error' => $th->getMessage()]);
		}
	}

	public function updateCIFImages($request, $cod_cli, $nif)
	{
		try {

			$images = $this->getCIFImages($cod_cli);

			$dni1Input = "dni1";
			$dni2Input = "dni2";

			# Esta ruta es la ruta que usa Ansorena y el nombre de los archivos para enlazar con el ERP
			$routeCliDocumentation = Config::get('app.dni_in_storage', false) == "cli-documentation";

			if ($routeCliDocumentation) {
				$dni1 = $nif."A";
				$dni2 = $nif."R";
			}

			$destinationPath = $this->dniPath($cod_cli);

			if (isset($request[$dni1Input])) {
				$file = $request[$dni1Input];
				$dni1name = $routeCliDocumentation ? $dni1 : $dni1Input;
				$filename = $dni1name . '.' . $file->getClientOriginalExtension();
				if (isset($images[$dni1name])) {
					unlink($images[$dni1name]);
				}
				$file->move($destinationPath, $filename);
			}

			if (isset($request[$dni2Input])) {
				$file2 = $request[$dni2Input];
				$dni2name = $routeCliDocumentation ? $dni2 : $dni2Input;
				$filename2 = $dni2name . '.' . $file2->getClientOriginalExtension();
				if (isset($images[$dni2name])) {
					unlink($images[$dni2name]);
				}
				$file2->move($destinationPath, $filename2);
			}

			//crear los nuevos archivos
		} catch (\Throwable $th) {
			Log::error($th);
			return false;
		}
	}

	public function getCIFImages($cod_cli)
	{
		try {
			$destinationPath = $this->dniPath($cod_cli);

			$files = glob($destinationPath . '*', GLOB_BRACE);

			$images = [];

			foreach ($files as $file) {
				$filename = pathinfo($file, PATHINFO_FILENAME);
				$images[$filename] = $file;
			}

			return $images;
		} catch (\Throwable $th) {
			Log::error($th);
			return [];
		}
	}

	private function saveCreditCard($request, $cod_cli){

		$credit_card = $request->creditcard_fxcli;
		$key = strtolower($request->email);
		$method = 'aes-256-cbc';

		// Must be exact 32 chars (256 bit)
		$password = substr(hash('sha256', $key, true), 0, 32);

		// IV must be exact 16 chars (128 bit)
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

		$creditEncrypt = base64_encode(openssl_encrypt($credit_card, $method, $password, OPENSSL_RAW_DATA, $iv));

		$lin_cliobcta = FxCliObcta::select('lin_cliobcta')->where('cli_cliobcta', $cod_cli)->max('lin_cliobcta');
		if(!$lin_cliobcta){
			$lin_cliobcta = 0;
		}

		FxCliObcta::create([
			'cli_cliobcta' => $cod_cli,
			'lin_cliobcta' => $lin_cliobcta + 1,
			'fec_cliobcta' => date("Y-m-d H:i:s"),
			'conc_cliobcta' => $creditEncrypt,
			'usr_cliobcta' => FxCliObcta::USR_CLIOBCTA_WEB,
			'orden_cliobcta' => $lin_cliobcta + 1,
			'tipobs_cliobcta' => FxCliObcta::TIPOBS_CLIOBCTA_TARGETA
		]);
	}

	private function updateCreditCard($request, $cod_cli)
	{
		$cli = FxCli::select('email_cli')->where('cod_cli', $cod_cli)->first();
		$credit_card = $request["creditcard_fxcli"];
		/* conseguir el mail */
		$key = strtolower($cli->email_cli);
		$method = 'aes-256-cbc';

		// Must be exact 32 chars (256 bit)
		$password = substr(hash('sha256', $key, true), 0, 32);

		// IV must be exact 16 chars (128 bit)
		$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

		$creditEncrypt = base64_encode(openssl_encrypt($credit_card, $method, $password, OPENSSL_RAW_DATA, $iv));

		if ($this->getCreditCard($cli->email_cli, $cod_cli)) {
			/* Actualizar el conc_cliobcta con el creditEncrypt haciendo where sobre el $cod_cli */
			FxCliObcta::where('cli_cliobcta', $cod_cli)->update([
				'conc_cliobcta' => $creditEncrypt
			]);
		} else {
			/* Crear un nuevo registro en la tabla fxclicobcta */
			$lin_cliobcta = FxCliObcta::select('lin_cliobcta')->where('cli_cliobcta', $cod_cli)->max('lin_cliobcta');
			if(!$lin_cliobcta){
				$lin_cliobcta = 0;
			}

			FxCliObcta::create([
				'cli_cliobcta' => $cod_cli,
				'lin_cliobcta' => $lin_cliobcta + 1,
				'fec_cliobcta' => date("Y-m-d H:i:s"),
				'conc_cliobcta' => $creditEncrypt,
				'usr_cliobcta' => FxCliObcta::USR_CLIOBCTA_WEB,
				'orden_cliobcta' => $lin_cliobcta + 1,
				'tipobs_cliobcta' => FxCliObcta::TIPOBS_CLIOBCTA_TARGETA
			]);
		}
	}

	private function getCreditCard($email, $cod_cli)
	{
		try {
			//Conseguir la tarjeta de base de datos
			$credit_card = FxCliObcta::select('conc_cliobcta')
				->where('cli_cliobcta', $cod_cli)
				->where('tipobs_cliobcta', FxCliObcta::TIPOBS_CLIOBCTA_TARGETA)
				->orderBy('fec_cliobcta', 'desc')
				->first();

			if(!$credit_card){
				return false;
			}

			// Obtenemos key y método
			$key = strtolower($email);
			$method = 'aes-256-cbc';

			// Must be exact 32 chars (256 bit)
			$password = substr(hash('sha256', $key, true), 0, 32);

			// IV must be exact 16 chars (128 bit)
			$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

			$creditDecrypt = openssl_decrypt(base64_decode($credit_card->conc_cliobcta), $method, $password, OPENSSL_RAW_DATA, $iv);

			$creditDecrypt = explode(" ", $creditDecrypt);
			$creditDecrypt[1] = explode("/", $creditDecrypt[1]);

			return $creditDecrypt;


		} catch (\Throwable $th) {
			Log::info('Error al conseguir la tarjeta de credito', ['error' => $th->getMessage()]);
			return false;
		}
	}

	public function getCreditCardAndCIFImages($cod_cli)
	{
		$email = FxCli::select('email_cli', 'cif_cli')->where('cod_cli', $cod_cli)->first();
		$credit_card = $this->getCreditCard($email->email_cli, $cod_cli);
		$cifImages = $this->getCIFImages($cod_cli);
		if ($credit_card && isset($cifImages["dni1"]) && isset($cifImages["dni2"]) && $email->cif_cli) {
			return true;
		} else {
			return false;
		}
	}


	/**
     * Crear usuario en prestashop
     * @param string $passwd contraseña
     * @param string $lastname apellido
     * @param string $firstname nombre
     * @param string $email mail
     * @param string $id_gender genero(H | M)
     * @param string $birthday fecha nacimiento
     * @return PrestashopController
     */
    private function addUserToPresta(string $passwd, string $lastname, string $firstname, string $email, string $id_gender, string $birthday){

        if(empty($passwd) || empty($lastname) || empty($firstname) || empty($email) || empty($id_gender) || empty($birthday)){
            return false;
        }

        $birthday_cli_temp = $birthday;
        $birthday_cli = date('Y-m-d', strtotime($birthday_cli_temp));

        $sexo = ($id_gender == "H") ? 1 : 2;
        $customer = new Customer_Presta($passwd, $lastname, $firstname, $email, $sexo, $birthday_cli);

        $prestaController = new PrestashopController();
        //$result = $prestaCustomer->createCustomer(Request::input('password'), Request::input('last_name'), Request::input('usuario'), Request::input('email'), $sexo, $fecnac_pcli);
        $result = $prestaController->createCustomer($customer);

        return $result;
    }

    /**
     *
     * @PENDIENTE
     *  - comprobar si getIdCountry no retorna valor
     *
     * @param type $id_customer
     * @param type $alias
     * @param type $dni
     * @param type $lastname
     * @param type $firstname
     * @param type $address1
     * @param type $city
     * @param type $id_country codigo del pais, ej: "ES"
     */
    private function addAressToPresta($id_customer, $dni, $lastname, $firstname, $address1, $city, $id_country, $postcode, $phone){

        if(empty($id_customer) || empty($dni) || empty($lastname) || empty($firstname) || empty($address1) || empty($city) || empty($id_country)){
            return false;
        }

        $prestaController = new PrestashopController();

        //convertir el codigo pais al id de pais en prestashop
        $id_country = $prestaController->getIdCountry($id_country);

        //crear un alias con apellido y direccion
        $alias = $lastname . "_address";

        $addressPresta = new Address_Presta($id_customer, $alias, $dni, $lastname, $firstname, $address1, $city, $id_country, $postcode, $phone);
		$prestaController->createAddress($addressPresta);


    }

  //******************************************************************************************************
  // Creación de un hash a partir del código de empresa y de subasta
  //******************************************************************************************************

  function createHash() {

    return hash_hmac("sha256", Config::get("app.emp")." ".Config::get("app.subalia_cli"), Config::get("app.subalia_key"));

  }



    //Cerrar session
    public function logout()
    {

        if(!empty(Session::get('tiempo_real.cod'))) {
            $goto = Session::get('tiempo_real.cod');
        } else {
            $goto = false;
        }


			# Eliminamos la sesión de usuario y redirigimos a login

			Session::forget('user');
			Session::forget('_token');


		if(!empty(Cookie::get('user'))){
			Cookie::queue('user',null);
		}

		return redirect("/");
		#pongo que siempre vaya a la home
        #return Redirect::back();
    }
//2018_01_19 no se esta usando
   /*
    # Subastas en las que está participando el usuario seleccionado
    public function getPujas($cod = false, $lote = false)
    {
        $user = new User();
        $user->cod  = $cod;
        $user->lote = $lote;

        # COD_CLIWEB
        $user->cod_cliweb   = '00001'; // usuario en sesión
        return json_encode($user->getPujas());
    }

    # Ordenes de licitacion del usuario en sesión
    public function getOrdenes($cod = false, $lote = false)
    {
        $user = new User();
        $user->cod  = $cod;
        $user->lote = $lote;

        # COD_CLIWEB
        $user->cod_cliweb   = '00001'; // usuario en sesión

        return json_encode($user->getOrdenes());
    }

    public function panel()
    {
        $data = array(
                'name' => trans(\Config::get('app.theme').'-app.user_panel.panel_usuario'),
                'section' => "favorites",
                );

        return View::make('front::pages.panel.main', array('data' => $data));

    }
   */
    //funcion ue devuelve las pujas y las ordenes de un Usuario

    public function orderbidsList(){
        if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data['data'] = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
			$seo = new \Stdclass();
			$seo->noindex_follow = true;
			if(config('app.seo_notlogged_page', 0)){
				$seo->meta_title = trans(\Config::get('app.theme').'-app.metas.title_no_logged');
				$seo->meta_description = trans(\Config::get('app.theme').'-app.metas.description__no_logged');
			}

			$data['seo'] = $seo;
            return View::make('front::pages.not-logged',  $data);
        }

		//el orderBidsListLots falla la vista, revisar antes de activar
         if(!empty(Config::get('app.user_panel_group_subasta')) && Config::get('app.user_panel_group_subasta') == 1){
            return $this->orderbidsListSubastas();
         }else{
            return $this->orderbidsListLots();
         }
    }

	/**
	 * Generea un certificado del lote en el servidor del cliente
	 * No es posible recuperar el archivo de manera local
	 */
	public function generateAuthenticityCertificate()
	{
		if(!Session::get('user')){
			abort(401);
		}

		$emp = Config::get('app.emp');
		$cod_sub = request('cod_sub', '');
		$ref_asigl0 = request('ref_asigl0', '');
		$url = Config::get('app.url_certificado');

		$path = "/img/certificado/$emp/$cod_sub/$ref_asigl0.pdf";

		$file = public_path(str_replace("\\", "/", $path));
		if(file_exists($file)){
			return response($path, 200);
		}

		if(empty($cod_sub) || empty($ref_asigl0)){
			return response('error', 500);
		}

		$body = [
			'Empresa' => $emp,
			'Subasta' => $cod_sub,
			'Referencia' => $ref_asigl0
		];

		try{
			$response = Http::post($url, $body);
		}
		catch (\Throwable $th){
			Log::error("error al conectar con api de certificados");
			Log::error($th);
			return response($th, 500);
		}

		if ($response->status() != 200) {
			Log::error("error en la api de certificados");
			Log::error($response->status());
			return response($response->status(), 500);
		}

		if($response->body()){
			Log::info("generando certificado", ['body' => $response->body()]);
			if(file_exists($file)){
				return response($path, 200);
			}
			else{
				return response('Not exist', 404);
			}

		}

	}

    public function orderbidsListSubastas(){

        $favorites = null;
        if(!empty(Request::input('favorites'))){
            $favorites = true;
            $data['favorites'] = true;
            # Lista de códigos de licitacion del usuario en sesion

            $data['codigos_licitador']= self::getLicitCodes();
		}
        $sub = new Subasta();
        $sub->licit = Session::get('user.cod');
        $subastas_active = $sub->auctionList ('S');
        $sub->page  = 'all';
        $sub->cod = null;
        $all_pujas = array();


		if(!empty(Config::get('app.lots_closed_inpanel'))){

			$all_pujas_temp = $sub->getAllBidsAndOrders($favorites, true);
		}
		else{
			$all_pujas_temp = $sub->getAllBidsAndOrders($favorites, false, request()->has('contraofertas'));
		}

        foreach($all_pujas_temp as $temp_pujas){
            $all_pujas[$temp_pujas->cod_sub]['lotes'][]=$temp_pujas;
		}
        foreach($all_pujas as $key_inf => $value){
            $sub->cod = $key_inf;
            $all_pujas[$key_inf]['inf']=$sub->getInfSubasta();
        }
        $data['values'] = $all_pujas;
        $data['seo'] = new \stdClass();
		$data['seo']->noindex_follow = true;
        return View::make('front::pages.panel.orders', array('data' => $data));
    }

    public function orderbidsListLots(){
        $sub = new Subasta();
        $sub->licit = Session::get('user.cod');
        $data = array();
        $page = Route::current()->parameter('page');

        $sub->page  = 'all';

        if(!empty(Request::input('order')) && Request::input('order') == 'desc'){
            $sub->where_filter = 'desc';
        }else{

        }

		$queryValues = $sub->getAllBidsAndOrders();
        $totalItems = count($queryValues);


        $itemsPerPage = $sub->itemsPerPage = 10;
        //$urlPattern     = \Routing::slug('user/panel/orders').'/page/(:num)';

        if(empty($page) or $page == 1) {
            $currentPage    = 1;
        } else {
            $currentPage    = $page;
        }

		$path = request()->fullUrlWithoutQuery(['page']);
        $paginator = new Paginator($queryValues, $totalItems, $itemsPerPage, $currentPage, ['path' => $path]);

        $sub->page = $currentPage;
        //$paginator->numPages = ($paginator->numPages -1);

        $data['values'] = $queryValues;
        $data['paginator'] = $paginator;
        $data['currency'] = $sub->getCurrency();
        //}
        $data['seo'] = new \stdClass();
        $data['seo']->noindex_follow = true;

        return View::make('front::pages.panel.orders', array('data' => $data));
    }

	public function getCounterOffers(HttpRequest $request)
	{
		//Si existe o no session, se controla en el middleware. Si llegamos aquí, tenemos sesion.
		$cod_cli = session('user.cod');

		$values = FgAsigl1_Aux::getPujasAuxiliares($cod_cli, [FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA, FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA_RECHAZADA]);
		$seo = (object)['noindex_follow' => true];

		$data = compact('values', 'seo');

        return View::make('front::pages.panel.counteroffers', compact('data'));
	}

	public function preAwards(HttpRequest $request)
	{
		$cod_cli = session('user.cod');

		$values = FgAsigl1_Aux::getPujasAuxiliares($cod_cli, [FgAsigl1_Aux::PUJREP_ASIGL1_COMPRAR_ONLINE, FgAsigl1_Aux::PUJREP_ASIGL1_COMPRAR_VD]);
		$seo = (object)['noindex_follow' => true];

		$data = compact('values', 'seo');

        return View::make('front::pages.panel.preawards', compact('data'));
	}

    # Listado de ordenes de licitacion en el panel de usuario
    public function orderList()
    {
        $sub = new Subasta();
        $sub->licit = Session::get('user.cod');
        $data = array();
        $page = Route::current()->parameter('page');

        //$data_licit = $sub->getCodLicitFromCli();

       /* if (!empty($data_licit)){
            $licits = implode("','", $data_licit);

            $sub->licit = "'".$licits."'";*/

        $sub->page  = 'all';
		$queryValues = $sub->getAllSubastaLicitOrdenes();
        $totalItems = count($queryValues);

        $itemsPerPage = $sub->itemsPerPage = 10;
        $urlPattern     = \Routing::slug('user/panel/orders').'/page/(:num)';

        if(empty($page) or $page == 1) {
            $currentPage    = 1;
        } else {
            $currentPage    = $page;
        }

		$path = request()->fullUrlWithoutQuery(['page']);
        $paginator = new Paginator($queryValues, $totalItems, $itemsPerPage, $currentPage, ['path' => $path]);

        $sub->page = $currentPage;
        //$paginator->numPages = ($paginator->numPages -1);

        $data['values'] = $queryValues;
        $data['paginator'] = $paginator;
        $data['currency'] = $sub->getCurrency();
        //}
        return View::make('front::pages.panel.orders', array('data' => $data));
    }

    # Orden de licitación máxima en un lote
    public function maxOrder()
    {
        $sub = new Subasta();
        $sub->licit = Session::get('user.cod');
        $data = array();
        $page = Route::current()->parameter('page');

        $sub->page  = 'all';
		$queryValues = $sub->getAllSubastaLicitOrdenes();
        $totalItems = count($queryValues);

        $itemsPerPage = $sub->itemsPerPage = 10;
        $urlPattern     = \Routing::slug('user/panel/orders').'/page/(:num)';

        if(empty($page) or $page == 1) {
            $currentPage    = 1;
        } else {
            $currentPage    = $page;
        }

		$path = request()->fullUrlWithoutQuery(['page']);
        $paginator = new Paginator($queryValues, $totalItems, $itemsPerPage, $currentPage, ['path' => $path]);

        $sub->page = $currentPage;

        $data['values']     = $queryValues;
        $data['paginator']  = $paginator;
        $data['currency']   = $sub->getCurrency();

        return View::make('front::pages.panel.orders', array('data' => $data));
    }

    //Acceso panel de usuario para ver su informacion
    public function accountInfo()
    {
		$seo = new \Stdclass();
		$seo->noindex_follow = true;
         if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data['data'] = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
			$data['seo'] = $seo;
            return View::make('front::pages.not-logged',  $data);
        }
        $Usuario          = new User();
        $Usuario->cod_cli = Session::get('user.cod');
        $datos            = $Usuario->getUser();
        $addres = new Address();
        $addres->cod_cli = Session::get('user.cod');
        $shippingaddress            = $addres->getUserShippingAddress();
        $address = array();
        $address            = head($addres->getUserShippingAddress('W1'));
        $enterprise = new Enterprise();
        $divisa = $enterprise->getDivisa();

        $data = array(
                'name' => trans(\Config::get('app.theme').'-app.user_panel.personal_info'),
                'user' => $datos,
                'shippingaddress'  => $shippingaddress,
                'address'  => $address,
                'divisa'    => $divisa,
				'seo'		=> $seo,
                );

        $data['codd_clid']='W1';
        $data['countries'] = $enterprise->getCountries();
        $data['via']  = $enterprise->getVia();


		// divido los configs ya que son independientes
		if(Config::get('app.user_panel_cc', false)) {
			$data['creditCard'] = $this->getCreditCard($datos->email_cli, $datos->cod_cli);
		}
		if(Config::get('app.user_panel_cif', false)) {
			$data['cifImages'] = $this->getCIFImages($datos->cod_cli);
		}

        if (!empty(FsIdioma::getArrayValues())) {
            $data['language'] = FsIdioma::getArrayValues();
        } else {
            foreach (Config::get('app.locales') as $key => $value) {
                $data['language'][strtoupper($key)] = $value;
            }
        }

        return View::make('front::pages.panel.datos_personales', array('data' => $data));
    }



    # Actualizamos la información del perfil del usuario
    public function updateClientInfo()
    {

		//Textos por defecto o toUpper
		$strToDefault = Config::get('app.strtodefault_register', 0);

        $Update = new User();
        $email = new MailController();
        $Update->cod_cli = Session::get('user.cod');

        $dir = Request::input('direccion');
        $direccion = $strToDefault ? mb_substr($dir,0,30,'UTF-8') : mb_strtoupper( mb_substr($dir,0,30,'UTF-8'),'UTF-8');
        $direccion2 = $strToDefault ? mb_substr($dir,30,30,'UTF-8') : mb_strtoupper( mb_substr($dir,30,30,'UTF-8'),'UTF-8');

        # Datos adicionales
        $Update->nom = $strToDefault ? trim(Request::input('usuario')) : trim(mb_strtoupper(Request::input('usuario'), 'UTF-8'));

        if(!empty(Request::input('last_name'))){
            $Update->nom = $strToDefault ? Request::input('last_name'). ', '. $Update->nom : mb_strtoupper(Request::input('last_name'), 'UTF-8'). ', '. $Update->nom ;
        }

        if(!empty(Request::input('rsoc_cli'))){
            $Update->rsoc = Request::input('rsoc_cli');
        }else{
            $Update->rsoc = $Update->nom;
        }

        $Update->dir  = $direccion;
        $Update->dir2  = $direccion2;
        $Update->cp   = Input::get('cpostal');
        $Update->pob  = $strToDefault ? mb_substr(Input::get('poblacion'),0,30,'UTF-8') : strtoupper(mb_substr(Input::get('poblacion'),0,30,'UTF-8'));
        $Update->pro  = Input::get('provincia');
        $Update->tel  = Input::get('telefono');
        $Update->tel2  = Input::get('telefono2');
        $Update->nombre_pais = DB::select("SELECT des_paises FROM FSPAISES WHERE cod_paises = :codPais", array("codPais" =>Request::input('pais')  ));
        $Update->pais = Request::input('pais');

		/**
		 * Una vez establecido el tipo de IVA, no debemos modificarlo al editar el usuario
		 * @see https://genius.labelgrup.com/account/assists/6984
		 */
        //$Update->iva_cli=$this->cliente_tax(Request::input('pais'),Request::input('cpostal'));

		$Update->divisa = Input::get('divisa');
		$Update->nacimiento = request('nacimiento', null);
		$Update->genero = request('genero', null);
		$Update->seudo_cli = request('seudo_cli', null);
		$Update->preftel_cli = request('preftel_cli', null);


		//Posibilidad de guardar las familias con un select multiple
		if(!empty(Request::input('families'))){

			$news = new Newsletter();
			foreach (Request::input('families') as $key => $value) {
				$families[$value] = 1;
			}
            $news->families = $families;
			$news->email =  Request::input('email');
			$news->newFamilies();
        }

		if (Request::input('nif', null)) {
			$Update->nif = Request::input('nif');
		}

		//Separo los configs ya que algunos clientes solo necesitan actualizar una de las dos cosas
		if(Config::get('app.user_panel_cc', false)) {
			$this->updateCreditCard(Request::all(), $Update->cod_cli);
		}

		if(Config::get('app.user_panel_cif', false)) {
			if (Request::has('dni1') || Request::has('dni2')) {
				$this->updateCIFImages(Request::all(), $Update->cod_cli, User::getUserNIF($Update->cod_cli));
			}
		}


		/**Inbusa necesita que se guarde el nombre de la empresa como nombre principal, para correos e informes*/
		if(!empty(Request::input('representar'))){
			if(Request::input('representar') == 'S'){
				$Update->fisjur_cli = 'R';
				$nomTemp = $Update->nom;
				$Update->nom = $Update->rsoc;
				$Update->rsoc = $nomTemp;
			}
			else{
				$Update->fisjur_cli = 'F';
			}
		}


        $Update->via=null;
        if(!empty(Request::input('codigoVia')))
        {
            $Update->via =Request::input('codigoVia');
        }

        $locales = Config::get('app.locales');
        $lang = Request::input('language');

        //Idioma en cli y cli_web
        if (!empty(FsIdioma::getArrayValues()) ) {

            $langFacturaKeys = FsIdioma::getArrayValues();
            if (array_key_exists(strtoupper($lang), $langFacturaKeys) || array_key_exists( strtolower($lang), $locales)) {
                $Update->language = strtoupper ($lang);
            } else {
                $Update->language = strtoupper (Config::get('app.locale'));
            }
        }else{
            $Update->language = strtoupper (Config::get('app.locale'));
        }

		$user=$Update->getClientInfo($Update->cod_cli);

		$addAdress = request()->input('add_address');
		$addressController = new AddressController();
		if(!empty($addAdress)){
			$addressController->updateShippingAddress();
		}
		else if(Config::get('app.delete_addres_toupdate', 1)){
			$addressController->deleteShippingAddress();
		}
		//Tauler necesita conservar la direccion, con los datos de la direccion original
		else{
			/**
			 * Ya no, 19/02/2021. ahora las direcciones se controlan desde una ventana a parte, por lo que la dirrección de envío principal no debe
			 * sobreescribirse en este punto.
			 * Se debe mantener el config de borrado de direcciones ya que tampoco la debemos borrar
			 * */
			//$addressController->updateShippingAddress($Update);
		}

        if(empty(Input::get('email'))){
            $Update->email = $user->usrw_cliweb;
        }else{
            $exist = $Update->EmailExist(Input::get('email'),Config::get('app.emp'),Config::get('app.gemp'));
            if(!empty($exist)){
                $Update->email = $user->usrw_cliweb;
            }else{
                $Update->email = Input::get('email');
            }
        }
            //Administrador recibe un correo que este cliente quiere modificar su información

          $emailOptions = $email->processVars();

          $email = new EmailLib('USER_CHANGE_INFO');
          if(!empty($email->email)){

             $email->setTo(Config::get('app.admin_email'));
             $email->setHtml($emailOptions['camposHtml']);

			 if (Config::get('app.user_panel_cif', false)) {
				$nifAdjuntos = $this->getCIFImages($Update->cod_cli);
				if(!empty($nifAdjuntos)){
					$email->setAttachments($nifAdjuntos);
				}
			 }

              if ($email->send_email()){
                  $response = array(
                      "err"       => 0,
                      "msg"       => 'user_panel_inf_email_actualizada'
                  );
              }else{
                  $response = array(
                      "err"       => 1,
                      "msg"       => 'user_panel_inf_email_error'
                  );
              }

          }


          if (empty(Config::get('app.no_user_change_info')) || Config::get('app.no_user_change_info')!=1) {

            //Guardar información del usuario
            if($Update->updateClientInfo()) {

                $response = array(
                                "err"       => 0,
                                "msg"       => 'user_panel_inf_actualizada'
                            );
            }else {
                $response = array(
                                "err"       => 1,
                                "msg"       => 'user_panel_inf_error'
                            );
            }
		  }
		  if(Config::get('app.WebServiceClient')){
			$theme  = Config::get('app.theme');
			$rutaClientcontroller = "App\Http\Controllers\\externalws\\$theme\ClientController";

			$clientController = new $rutaClientcontroller();

			$clientController->updateClient(Session::get('user.cod'));
		}


        if (!empty($response)) {
          return json_encode($response);
        }
    }


    public function change_password($Update,$user){
        $Update->pwd_encrypt =  md5(Config::get('app.password_MD5').$Update->pwd);
        $validacion = true;

		if(!empty(Cookie::get('user'))){
			Cookie::queue('user', ''.$user->usrw_cliweb.'%'.$Update->pwd.'','525600');
		}

    }

    # Actualizamos el password unicamente
    public function updatePassword()
    {
        $validacion = false;
        $Update          = new User();
        $Update->cod_cli = Session::get('user.cod');
        $email=Session::get('user.name');
        $Update->pwd     = Input::get('password');
        $last_pass= Input::get('last_password');
        $user=$Update->getClientInfo($Update->cod_cli);
		if(!empty(Config::get('app.multi_key_pass'))){
			$passBD= explode(":", trim($user->pwdwencrypt_cliweb));

			#debe existir la clave de encriptación y el password encriptado
			if(count($passBD) <2){
				return null;
			}

			$seed = $passBD[1];
			$last_passBD = $passBD[0];
			#encriptamos el password k mandan y añadimos la semilla para que tenga el formato de multiples semillas
			$last_pass_md5 =  trim(md5($seed.$last_pass));
			if($last_pass_md5 == $last_passBD){
				$newKey=md5(time());
				$Update->pwd_encrypt =  md5($newKey.$Update->pwd).":".$newKey;
				$validacion = true;
			}
		}
        elseif(!empty(Config::get('app.password_MD5'))){
			#una semilla por cada usuario


				$pass_md5 = trim(md5(Config::get('app.password_MD5').$last_pass));
				//permitir cambiar password a los antiguos de 8 caracteres como máximo
				$pass_8char = substr(trim($last_pass),0,8);
				$pass_md5_8char = md5(Config::get('app.password_MD5').$pass_8char);
				$user->pwdwencrypt_cliweb = trim($user->pwdwencrypt_cliweb);
				if(empty($last_pass) && $user->pwdwencrypt_cliweb == $pass_md5){
					$this->change_password($Update,$user);
					$validacion = true;
				}else if( ($user->pwdwencrypt_cliweb == $pass_md5) || ($user->pwdwencrypt_cliweb == $pass_md5_8char ) ){
					$this->change_password($Update,$user);
					$validacion = true;
				}



        }elseif(empty($last_pass)){
            $validacion = true;
        }elseif(!empty($Update->pwd)){
             $validacion = true;
        }

        if($validacion && $Update->updatePassword()) {
            $response = array(
                            "err"       => 0,
                            "msg"       => 'user_panel_inf_actualizada'
                        );
        } else {
            $response = array(
                            "err"       => 1,
                            "msg"       => 'user_panel_inf_error'
                        );
		}
		if(Config::get('app.WebServiceClient')){
			$theme  = Config::get('app.theme');
			$rutaClientcontroller = "App\Http\Controllers\\externalws\\$theme\ClientController";

			$clientController = new $rutaClientcontroller();

			$clientController->updateClient(Session::get('user.cod'));
		}

        return json_encode($response);
    }

	#mostrar listado de pagos pagados y pendientes de transferencia de NFT
	public function nftTransferPay(){
		$asigl0 = new Fgasigl0();
		$nfts = $asigl0->JoinFghces1Asigl0()->JoinCSubAsigl0()->JoinNFT()->
		select("DESCWEB_HCES1, TRANSFER_ID_NFT,COST_TRANSFER_NFT, PAY_TRANSFER_NFT ")->
		where("CLIFAC_CSUB",Session::get('user.cod'))->
		#networks de pago, si no son de pago no se deberá cobrar
		wherein("NETWORK_NFT", explode("," , str_replace(" ","", \Config::get("app.nftPayNetwork")) ))->
		#que el lote se haya solicitado la transferencia
		whereNotNull("TRANSFER_ID_NFT")->
		#si es nulo es que no se ha transferido y si es P es que esta pendiente de transferir
		whereRaw("(PAY_TRANSFER_NFT is NULL or PAY_TRANSFER_NFT = 'P')")->

		# si la transferencia tiene importe es que se debe pagar, puede estar pagada o no pero es la manera de saber que nft mostrar en este listado
		where("COST_TRANSFER_NFT",">",0)->
		get();

		$noTransfer= array();
		$pendingPay = array();

		foreach($nfts as $nft ){
			if($nft->pay_transfer_nft == "P"){
				$pendingPay[] = $nft;
			}else{
				$noTransfer[] = $nft;
			}

		}
		#de momento no uso los pagados, pero dejo el código ya preparado por si hiciera falta
		return View::make('front::pages.panel.nftTransferPayments',compact("pendingPay","noTransfer") );

	}

	#mostrar listado de minteos pendientes de pago
		public function nftMintPay(){
		$asigl0 = new Fgasigl0();
		$nfts = $asigl0->JoinFghces1Asigl0()->JoinCSubAsigl0()->JoinNFT()->
		select("DESCWEB_HCES1, MINT_ID_NFT,COST_MINT_NFT, PAY_MINT_NFT ")->
		where("PROP_HCES1",Session::get('user.cod'))->
		#networks de pago, si no son de pago no se deberá cobrar
		wherein("NETWORK_NFT", explode("," , str_replace(" ","", \Config::get("app.nftPayNetwork")) ))->
		#que el lote se haya solicitado la transferencia
		whereNotNull("MINT_ID_NFT")->
		#si es nulo es que no se ha transferido y si es P es que esta pendiente de transferir
		whereRaw("(PAY_MINT_NFT is NULL or PAY_MINT_NFT = 'P')")->

		# si EL MINTEO tiene importe es que se debe pagar, puede estar pagada o no pero es la manera de saber que nft mostrar en este listado
		where("COST_MINT_NFT",">",0)->
		get();

		$noTransfer= array();
		$pendingPay = array();

		foreach($nfts as $nft ){
			if($nft->pay_mint_nft == "P"){
				$pendingPay[] = $nft;
			}else{
				$noTransfer[] = $nft;
			}

		}

		#de momento no uso los pagados, pero dejo el código ya preparado por si hiciera falta
		return View::make('front::pages.panel.nftMintPayments',compact("pendingPay","noTransfer") );

	}

	/**
	 * Adjudicaciones pendientes de pago del usuario en sesion
	 * @deprecated Este metodo corresponde a una versión del panel antigua, revisamos el inicio si
	 * existe la vista para redirigir a /allotments en caso de no existir
	 */
    public function getAdjudicacionesPendientePago()
    {
		if(!View::exists('front::pages.panel.adjudicaciones_pagar')){
			return redirect()->route('panel.allotments', ['lang' => Config::get('app.locale')], 301);
		}

        $subasta = new Subasta();
        $parametrosSub= $subasta->getParametersSub();
        if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
            return View::make('front::pages.not-logged', array('data' => $data));
        }

        $emp  = Config::get('app.emp');
        $gemp  = Config::get('app.gemp');
        # Lista de códigos de licitacion del usuario en sesion

        $User = new User();
        $User->cod_cli = Session::get('user.cod');
        $User->itemsPerPage = 'all';
        $adjudicaciones = $User->getAdjudicacionesPagar('N');

		$user_cli=$User->getUser($User->cod_cli);

        $pago_controller = new PaymentsController();
        $pago_modelo = new Payments();

        $user_cod = $User->cod_cli;

        $addres = new Address();
        $addres->cod_cli = $User->cod_cli;
        $envio = $addres->getUserShippingAddress();

        $iva = $pago_controller->getIva($emp,date ("Y-m-d"));
		$tipo_iva = $pago_controller->user_has_Iva($gemp,$user_cod);

        foreach($adjudicaciones as $adj){
            $adj->formatted_imp_asigl1 = \Tools::moneyFormat($adj->himp_csub);
            $adj->imagen = $subasta->getLoteImg($adj);
            $adj->date = \Tools::euroDate($adj->fec_asigl1, $adj->hora_asigl1 );
            $adj->imp_asigl1 = $adj->himp_csub;
            $adj->base_csub_iva = $pago_controller->calculate_iva($tipo_iva->tipo,$iva,$adj->base_csub);
            //Modificamos ref_asigl0 de . a _ porque si hay punto el js de calclulo de pagar no calcula bien
            $adj->ref_asigl0 = str_replace('.','_',$adj->ref_asigl0);
			$adj->days_extras_alm = $this->days_extras_almacen($adj->fecha_csub);


			$adj->licencia_exportacion = $pago_controller->licenciaDeExportacionPorPais($user_cli->codpais_clid ?? $user_cli->codpais_cli, $adj->himp_csub);

		}
        //Podemos saber que iva va a tener el cliente
        $user_cli->iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo,$iva);


        $sub = new Subasta();
        $data = array(
                    'adjudicaciones' => $adjudicaciones,
                    'currency'       => $sub->getCurrency(),
                    'envio'    =>$envio,
                    'user'  => $user_cli,
                    'js_item'=>$this->generatePreciosLotAdj($adjudicaciones),
					'price_exportacion' => floatval($parametrosSub->licexp_prmsub)
				);



        return View::make('front::pages.panel.adjudicaciones_pagar', array('data' => $data));
	}

    #Lotes adjudicados pagados
    public function getAdjudicacionesPagadas()
    {
        $pago_controller = new PaymentsController();
        $pago_modelo = new Payments();
        $User = new User();
        $sub = new Subasta();
        $facturas = new Facturas();

        if(!Session::has('user')){
            $url =  Config::get('app.url').parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
            return View::make('front::pages.not-logged', array('data' => $data));
        }

        $emp  = Config::get('app.emp');
        $gemp  = Config::get('app.gemp');
        # Lista de códigos de licitacion del usuario en sesion

        $User->cod_cli = Session::get('user.cod');

        $User->itemsPerPage = null;

        $adjudicaciones = $User->getAdjudicacionesPagar('S');

        $user_cli=$User->getUser($User->cod_cli);

        $user_cod = $User->cod_cli;

        $hoy = date ("Y-m-d");

        $iva = $pago_controller->getIva($emp,$hoy);
        $tipo_iva = $pago_controller->user_has_Iva($gemp,$user_cod);

        foreach($adjudicaciones as $adj){
            $adj->formatted_imp_asigl1 = \Tools::moneyFormat($adj->himp_csub);
            $adj->imagen = $sub->getLoteImg($adj);
            $adj->date = \Tools::euroDate($adj->fec_asigl1, $adj->hora_asigl1 );
            $adj->imp_asigl1 = $adj->himp_csub;
            $adj->base_csub_iva = $pago_controller->calculate_iva($tipo_iva->tipo,$iva,$adj->base_csub);
            $extras = array();
            $adj->extras = $pago_modelo->getGastosExtrasLot($adj->sub_csub,$adj->ref_csub,$tipo = null,'C');
            $adj->factura = $this->bills($adj->afral_csub, $adj->nfral_csub,true);
            $this->serie = $adj->afral_csub;
            $this->numero = $adj->nfral_csub;
            $adj->pending_fact = $facturas->pending_bills(false);
			$adj->days_extras_alm = $this->days_extras_almacen($adj->fecha_csub);
			$adj->licencia_exportacion = $pago_controller->licenciaDeExportacionPorPais($user_cli->codpais_clid ?? $user_cli->codpais_cli, $adj->himp_csub);

        }

        //Podemos saber que iva va a tener el cliente
        $user_cli->iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo,$iva);


        $data = array(
                    'adjudicaciones' => $adjudicaciones,
                    'currency'       => $sub->getCurrency(),
                    'user'  => $user_cli,
                    'js_item'   =>$this->generatePreciosLotAdj($adjudicaciones),
                );

        return View::make('front::pages.panel.adjudicaciones_pagadas', array('data' => $data));

    }


	public function getDirectSaleAdjudicaciones($lang) {
		return $this->getAllAdjudicaciones($lang, true);
	}

    //Todos los lotes pgados y no pagados
    public function getAllAdjudicaciones($lang = null, $onlyDirectSales = false)
    {
		$seo = new \Stdclass();
		$seo->noindex_follow = true;

        $subasta = new Subasta();
        $parametrosSub= $subasta->getParametersSub();
        if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data['data'] =  trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
			$data['seo'] = $seo;
            return View::make('front::pages.not-logged', $data);
        }

        $emp  = Config::get('app.emp');
        $gemp  = Config::get('app.gemp');
        # Lista de códigos de licitacion del usuario en sesion

        $User = new User();
        $User->cod_cli = Session::get('user.cod');
        $User->itemsPerPage = 'all';
        $adjudicaciones = $User->getAdjudicacionesPagar('N');

        $user_cli=$User->getUser($User->cod_cli);

        $pago_controller = new PaymentsController();
        $pago_modelo = new Payments();

        $user_cod = $User->cod_cli;

        $addres = new Address();
        $addres->cod_cli = $User->cod_cli;
        $envio = $addres->getUserShippingAddress();

        $iva = $pago_controller->getIva($emp,date ("Y-m-d"));
        $tipo_iva = $pago_controller->user_has_Iva($gemp,$user_cod);

		#adjudicaciones que estan pendientes de pagar por transferencia, y no pueden salir como no pagadas
		$adjudicaciones_transfer = array();
		$pathAlbaran = DIRECTORY_SEPARATOR . 'bills' . DIRECTORY_SEPARATOR . $emp . DIRECTORY_SEPARATOR;

        foreach($adjudicaciones as $key =>$adj){
            $adj->formatted_imp_asigl1 = \Tools::moneyFormat($adj->himp_csub);
            $adj->imagen = $subasta->getLoteImg($adj);
            $adj->date = \Tools::euroDate($adj->fec_asigl1, $adj->hora_asigl1 );
			$adj->imp_asigl1 = $adj->himp_csub;

			$adj->prefactura = $this->proformaInvoiceFile($adj->sub_csub, true);

			#si no debe llevar iva la subasta online
			if(\Config::get("app.noIVAOnlineAuction") && $adj->tipo_sub == 'O'){
				$adj->base_csub_iva = 0;
			}else{
				$adj->base_csub_iva = $pago_controller->calculate_iva($tipo_iva->tipo,$iva,$adj->base_csub);
			}




            //Modificamos ref_asigl0 de . a _ porque si hay punto el js de calclulo de pagar no calcula bien
            $adj->ref_asigl0 = str_replace('.','_',$adj->ref_asigl0);
			$adj->days_extras_alm = $this->days_extras_almacen($adj->fecha_csub);

			//Existen lotes en Tauler que no deben añadir el precio de exportación al pago, en object_types controlamos si se cobra o no
			//03/11/2021 - Eloy
			$exportacion = $subasta->hasExportLicense($adj->num_hces1, $adj->lin_hces1);

			$adj->licencia_exportacion = 0;
			if($exportacion){
				$envioPorDefecto = collect($envio)->where('codd_clid', 'W1')->first();
				$adj->licencia_exportacion = $pago_controller->licenciaDeExportacionPorPais($envioPorDefecto->codpais_clid ?? $user_cli->codpais_cli, $adj->himp_csub);
			}


			#quitar las adjudicaciones por transferencia y ponerlas en otro listado
			if($adj->estado_csub0 == "T") {
				$adjudicaciones_transfer[] = $adj;
				unset($adjudicaciones[$key]);
			}

		}

		if(config('app.allotments_shopping_cart', false)){
			$adjudicaciones = $adjudicaciones->filter(function($item) use ($onlyDirectSales){
				if($onlyDirectSales) {
					return $item->tipo_sub == 'V';
				}
				return $item->tipo_sub != 'V';
			});
		}

        //Podemos saber que iva va a tener el cliente
        $user_cli->iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo,$iva);


        $sub = new Subasta();

        ///**************************************** */
        $facturas = new Facturas();

        $User->itemsPerPage = null;

        $adjudicaciones_pag = $User->getAdjudicacionesPagar('S');

        $user_cli=$User->getUser($User->cod_cli);

        $user_cod = $User->cod_cli;

        $hoy = date ("Y-m-d");

        foreach($adjudicaciones_pag as $adj){

            $adj->formatted_imp_asigl1 = \Tools::moneyFormat($adj->himp_csub);
            $adj->imagen = $sub->getLoteImg($adj);
            $adj->date = \Tools::euroDate($adj->fec_asigl1, $adj->hora_asigl1 );
			$adj->imp_asigl1 = $adj->himp_csub;
			#si no debe llevar iva la subasta online
			if(\Config::get("app.noIVAOnlineAuction") && $adj->tipo_sub == 'O'){
				$adj->base_csub_iva =0;
			}else{
				$adj->base_csub_iva = $pago_controller->calculate_iva($tipo_iva->tipo,$iva,$adj->base_csub);			}

            $extras = array();
            $adj->extras = $pago_modelo->getGastosExtrasLot($adj->sub_csub,$adj->ref_csub,$tipo = null,'C');
            $adj->factura = $this->bills($adj->afral_csub, $adj->nfral_csub,true);
            $this->serie = $adj->afral_csub;
            $this->numero = $adj->nfral_csub;
            $adj->pending_fact = $facturas->pending_bills(false);
			$adj->days_extras_alm = $this->days_extras_almacen($adj->fecha_csub);

			//Existen lotes en Tauler que no deben añadir el precio de exportación al pago, en object_types controlamos si se cobra o no
			//03/11/2021 - Eloy
			$exportacion = $subasta->hasExportLicense($adj->num_hces1, $adj->lin_hces1);

			$adj->licencia_exportacion = 0;
			if($exportacion){
				$envioPorDefecto = collect($envio)->where('codd_clid', 'W1')->first();
				$adj->licencia_exportacion = $pago_controller->licenciaDeExportacionPorPais($envioPorDefecto->codpais_clid ?? $user_cli->codpais_cli, $adj->himp_csub);
			}
		}


		#si usan las tablas de WEB_GASTOS_ENVIO cargaremos las direcciones
		$address = NULL;
		if(Config::get("app.web_gastos_envio")){
			$fxClid = new FxClid();
			$address = $fxClid->getForSelectHTML(Session::get('user.cod'));
		}



        $data = array(
					'address' => $address,
					'adjudicaciones' => $adjudicaciones,
					'adjudicaciones_transfer' => $adjudicaciones_transfer,
                    'currency'       => $sub->getCurrency(),
                    'envio'    =>$envio,
                    'user'  => $user_cli,
                    'js_item'=>$this->generatePreciosLotAdj($adjudicaciones),
                    'price_exportacion' => floatval($parametrosSub->licexp_prmsub),
					'adjudicaciones_pag' => $adjudicaciones_pag,
					#al pasar le valor 1 devolvera el iva que hay que aplicar, por ejemplo 0,21
					'ivaAplicable' => $pago_controller->calculate_iva($tipo_iva->tipo,$iva,1),
					'seo' => $seo,
					'onlyDirectSales' => $onlyDirectSales
                );


        return View::make('front::pages.panel.adjudicaciones', array('data' => $data));


    }

    //generamos array que nos servira para los calculos de lotes pagados y no pagados
    public function generatePreciosLotAdj($adjudicaciones){
        $sub = new Subasta();
        $pago_modelo = new Payments();
        $pago_controller = new PaymentsController();

        $iva = $pago_controller->getIva(\Config::get('app.emp'),date ("Y-m-d"));
        $tipo_iva = $pago_controller->user_has_Iva(\Config::get('app.gemp'),Session::get('user.cod'));
		$iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo,$iva);

        //parametros de subasta, lo utilizamos por precio de seguro y de licencia exportacion
        $parametrosSub= $sub->getParametersSub();
        //creamos $lot_js donde va estar toda la informaion de precios de cada lote, iva, exportacion, seguro
        $lot_js = array();
        $price_exportacion =  floatval($parametrosSub->licexp_prmsub);
        $price_exportacion_iva = $pago_controller->calculate_iva($tipo_iva->tipo,$iva,$price_exportacion);
        foreach($adjudicaciones as $key=>$puj){

            $price_lot = new \stdClass;
            $price_lot->himp= (float) $puj->himp_csub;
            $price_lot->base= (float) number_format($puj->base_csub, 2, '.', '');
            $price_lot->iva= (float) number_format($puj->base_csub_iva, 2, '.', '');
			$price_lot->tipo_sub = $puj->tipo_sub;

            //Nos dice si la direccion de envio que nos pone el cliente se puede mandar el lote
            $price_lot->transporte_lot = false;

            //precio de transporte y precio transporte iva
            $price_lot->transporte = 0;
            $price_lot->transporte_iva = 0;

            //Para saber si el lote tiene exportacion
            $price_lot->exportacion = $pago_controller->licenciaDeExportacion($puj->ref_csub,$puj->sub_csub);
            // exportacion_codpais esta a false si el cliete quiere mandarlo fuera ES lo sabemos
            $price_lot->exportacion_codpais = false;
            //cliente hace exportacion o empresa
            $price_lot->exportacion_opcion = true;
            //precio seguro y iva seguro
            $price_lot->precio_seguro = 0;
            $price_lot->iva_seguro = 0;

            // precio exporatcion del lote y iva

            $price_lot->precio_exportacion = $price_exportacion;
            $price_lot->iva_exportacion = $price_exportacion_iva;

            //llevar el calculo numero de lotes que se van a recojer o se envian
            $price_lot->recojer = 0;
            $price_lot->enviar = 0;

            $extra = array();
            $extra = $pago_modelo->getGastosExtrasLot($puj->cod_sub,$puj->ref_csub,'E');
            $price_lot->extra = $extra;

			$price_lot->seguro = $pago_controller->calcSeguro($price_lot->himp+$price_lot->base+$price_lot->iva);

			//licencia de exportacion
			$price_lot->licencia_exportacion = floatval($puj->licencia_exportacion);

            //Guardamos el objeto en un array con codigo de subasta, lote y referencia del lote
			$lot_js[$puj->cod_sub]['lots'][$adjudicaciones[$key]->ref_asigl0]=$price_lot;

        }

		$iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo,$iva);



        //tenemos iva del cliente
		$lot_js['iva'] = floatval($iva_cli);


        return $lot_js;
    }

    # Adjudicaciones del usuario en sesion
    public function getSales()
    {
        $user = new User();
		$cod_cli = Session::get('user.cod');

		if(config('app.permission_to_view_seller_panel', false)){
			$cliweb = FxCliWeb::where('cod_cliweb', $cod_cli)->whereNotNull('permission_id_cliweb')->first();
			abort_if(!$cliweb, 404);
		}

        $user->cod_cli = $cod_cli;
		$lotes = $user->getSales(request()->all());

		$condAuctions = $lotes->pluck('cod_sub')->unique()->values();
		$pujas = FgAsigl1::select('ref_asigl1, lin_asigl1, licit_asigl1, imp_asigl1, fec_asigl1', 'sub_asigl1')
			->joinFghces1Asigl0()
			->where('prop_hces1', $cod_cli)
			->whereIn('sub_asigl1', $condAuctions)
			->orderBy('imp_asigl1', 'desc')
			->orderBy('fec_asigl1', 'desc')->get();


		$infoSales = [];
		$facturas = [];
		$subastasActivas = [];

		//La información de las facturas solamente la utilizar tauler, por lo que encapsulo en un config
		if(config('app.userpanel_sales_extends', false)){
			$defaultMonthPeriod = 6;
			$fgAsigl0 = new FgAsigl0();
			$subastasActivas = $fgAsigl0->getActiveAuctionsWithPropietary($cod_cli, Session::get('user.admin'));
			$infoSales = $fgAsigl0->getLotsInfoSales($subastasActivas->where('start', '>=', date("Y-m-d", strtotime("-$defaultMonthPeriod months")))->pluck('sub_asigl0')->toArray(), $cod_cli);

			//toda la informacion. Facturas y lineas incluidas
			//$facturas = (new FxDvc0())->getFacturasPropietario('$cod_cli')->groupBy('sub_hces1');

			//cabeceras y lineas por separado
			$facturasCabeceras = (new FxDvc0())->getFacturasCabecerasPropietario($cod_cli, $defaultMonthPeriod);
			$fgDvc1l = new FgDvc1l();
			foreach ($facturasCabeceras as $key => $cabecera) {
				$facturasCabeceras[$key]['linea'] = $fgDvc1l->getPrimeraLinea($cabecera->anum_dvc0, $cabecera->num_dvc0);
			}
			$facturas = $facturasCabeceras->keyBy('linea.sub_hces1');
		}

        foreach($lotes as $key => $lot){

            //$pujas = $subasta->getPujas(null,$lot->sub_asigl0);
            $lot->pujas = $pujas->where('ref_asigl1', $lot->ref_asigl0)->where('sub_asigl1', $lot->cod_sub);
            $lot->puja_max = 0;
            if($lot->pujas->count() > 0){
                $lot->puja_max = $lot->pujas->first();
            }
		}

		$collection = collect($lotes);

		$subastas = $collection->mapToGroups(function ($item) {
			return [$item->cod_sub => $item];
		});

		$data = array('subastas' => $subastas, 'subastasActivas' => $subastasActivas, 'infoSales' => (object)$infoSales, 'facturas' => $facturas);

		return view('front::pages.panel.sales', $data);
    }

	public function getFacturasPropietarioLineas()
	{
		$anum = request('anum');

		$num = request('num');

		$cod_cli = Session::get('user.cod');

		$lineas = (new FgDvc1l())->getFacturasLineasPropietario($anum, $num, $cod_cli);

		$facturaPdf = $this->bills($anum, $num, true);

		return view('front::pages.panel.sales.invoice_assignor_linea', ['lots' => $lineas, 'facturaPdf' => $facturaPdf])->render();
	}


	public function getInfoSales(){

		$period = null;
		$cod_subs = null;

		if(request('selections') && request('type') == 'time'){
			$period = request('selections');
		}
		else{
			$cod_subs = request('selections');
		}

		$cod_cli = Session::get('user.cod');
		$fgAsigl0 = new FgAsigl0();
		$fgDvc1l = new FgDvc1l();

		if(request('type') == 'time'){
			$subastasActivas = $fgAsigl0->getActiveAuctionsWithPropietary($cod_cli, Session::get('user.admin'), $period[0]);
			$cod_subs = $subastasActivas->pluck('sub_asigl0')->toArray();
		}

		if(empty($cod_subs)){
			return response('Not auctions', 404);
		}


		$infoSales = $fgAsigl0->getLotsInfoSales($cod_subs, $cod_cli);

		$facturasCabeceras = (new FxDvc0())->getFacturasCabecerasPropietario($cod_cli, $period[0] ?? null);

		foreach ($facturasCabeceras as $key => $cabecera) {
			$facturasCabeceras[$key]['linea'] = $fgDvc1l->getPrimeraLinea($cabecera->anum_dvc0, $cabecera->num_dvc0, $cod_subs);
		}

		$facturas = $facturasCabeceras->filter(function ($value, $key) {
				return !empty($value['linea']);
		})->keyBy('linea.sub_hces1');

		$infoSales['factura'] = view('pages.panel.sales.invoice_assignor_cabecera', ['subastas' => $facturas])->render();

		//$lots = FgAsigl0::getLotsInfoSales($cod_sub);
		//$infoSales = $this->infoWithSales(Session::get('user.cod'), $lots);

		return response($infoSales);
	}


    # Lista en formato string de codigos de licitador del usuario en sesión
    public static function getLicitCodes()
    {
        $User = new User();

        $User->cod_cli = Session::get('user.cod');
        $codigos_licitador = array();
        foreach ($User->getLicitCodes() as $key) {
            $codigos_licitador[$key->sub_licit] = $key->cod_licit;
        }

        return $codigos_licitador;
    }

    // lotes favoritos, por subasta o por lotes
    public function getFavoritos(){

         if(!empty(Config::get('app.user_panel_group_subasta')) && Config::get('app.user_panel_group_subasta') == 1){
            return $this->getFavoritosSubastas();
         }else{
            return $this->getFavoritosLots();

         }
    }

    #Lotes favoritos
    public function getFavoritosSubastas(){

        $sub = new Subasta();

          if(!Session::has('user'))
        {
            $favs = array();
            $paginator ="";
            $data = array(
                    'favoritos'      => $favs,
                    'paginator'      => $paginator,
                );

            return View::make('front::pages.panel.favoritos', array('data' => $data));
        }

         # Lista de códigos de licitacion del usuario en sesion
        $codigos_licitador = self::getLicitCodes();
        # Obtenemos los codigos de licitador en formato string para el IN(xxx)

        $lista_codigos = '';
         $coma = '';
        foreach ($codigos_licitador as $key => $value) {
            $lista_codigos .= $coma . "'". $key."-".$value."'";
             $coma = ',';
        }
        $all_favorites=array();
        $favs = array();
        $fav  = new Favorites(false, false);
        if(!empty($lista_codigos)){

            $fav->list_licit = $lista_codigos;

            $fav->page  = 'all';

            $favs = $fav->getFavsByLicits();
        }

        if(!empty($favs['data'])){
            foreach($favs['data'] as $favorites){
                 $all_favorites[$favorites->cod_sub]['lotes'][] = $favorites;
             }
             foreach($all_favorites as $key_inf => $value){
                 $sub->cod = $key_inf;
                 $all_favorites[$key_inf]['inf']=$sub->getInfSubasta();
             }
        }


        $data = array(
                    'favoritos'      => $all_favorites,
                    'codigos_licitador' => $codigos_licitador
                );
        return View::make('front::pages.panel.favoritos', array('data' => $data));


     }

    public function getFavoritosLots()
    {

        if(!Session::has('user'))
        {
            $favs = array();
            $paginator ="";
            $data = array(
                    'favoritos'      => $favs,
                    'paginator'      => $paginator,
                );

            return View::make('front::pages.panel.favoritos', array('data' => $data));
        }
        # Lista de códigos de licitacion del usuario en sesion
        $codigos_licitador = self::getLicitCodes();
        # Obtenemos los codigos de licitador en formato string para el IN(xxx)

        $lista_codigos = '';
         $coma = '';
        foreach ($codigos_licitador as $key => $value) {
            $lista_codigos .= $coma . "'". $key."-".$value."'";
             $coma = ',';
        }

        $favs = array();
        $fav  = new Favorites(false, false);
        if(!empty($lista_codigos)){

            $fav->list_licit = $lista_codigos;

            $fav->page  = 'all';

            $favs = $fav->getFavsByLicits();
        }

        if(!empty($favs) && isset($favs['data']))
        {
          $favs = $favs['data'];
        }

        # Paginador #
        $page = Route::current()->parameter('page');


        $totalItems = count($favs);
        $itemsPerPage   = $fav->itemsPerPage = 10;
        $urlPattern     = \Routing::slug('user/panel/favorites').'/page/(:num)';

        if(empty($page) or $page == 1) {
            $currentPage    = 1;
        } else {
            $currentPage    = $page;
        }

		$path = request()->fullUrlWithoutQuery(['page']);
        $paginator = new Paginator($favs, $totalItems, $itemsPerPage, $currentPage, ['path' => $path]);

        $fav->page           = $currentPage;
        # end paginador #

        $favs = $fav->getFavsByLicits();

        if($favs && isset($favs['data']))
        {
          $favs = $favs['data'];
        }
        else
        {
          $favs= array();
        }

        $data = array(
                    'favoritos'      => $favs,
                    'paginator'      => $paginator,
                    'codigos_licitador' => $codigos_licitador
                );

        return View::make('front::pages.panel.favoritos', array('data' => $data));
    }

    //Ver Temas Faoritos panel
    public function getTemaFavoritos()
    {
        $emp  = Config::get('app.emp');
        if(!Session::has('user'))
        {
            $favs = array();
            $paginator ="";
            $data = array(
                    'favoritos'      => $favs,
                    'paginator'      => $paginator,
                );

            return View::make('front::pages.panel.temas_favorites', array('data' => $data));
        }

        $user = new User();
        $cod_lic =  Session::get('user.cod');
        $data['favorites'] = $user->favorites();

        $data['fav']= $user->fav_themes($emp,$cod_lic);

        return View::make('front::pages.panel.temas_favorites', array('data' => $data));
    }

    //Guardar Temas favoritos panel
    public function savedTemaFavoritos(){
        $emp  = Config::get('app.emp');
        $user = new User();
        $cod_cli =  Session::get('user.cod');

        $user->deletefavorites($emp,$cod_cli);

        $data['favorites'] = $user->favorites();

        foreach($data['favorites'] as $favorites){
           $interest = Request::input("interest_".$favorites->cod_tsec);
           if(!empty($interest)){
               $user->addfavorites($emp,$cod_cli,$favorites->cod_tsec);
           }
        }
    }

	// ---------------------------- //
	// ------Nuevos Favoritos------ //
	// ---------------------------- //

	public function getNewFavoritos(){

		if(!empty(Config::get('app.user_panel_group_subasta')) && Config::get('app.user_panel_group_subasta') == 1){
			return $this->getFavoritosSubastasNew();
		}else{
			return $this->getFavoritosLotsNew();
		}
	}

	#Lotes favoritos
    public function getFavoritosSubastasNew(){

          if(!Session::has('user'))
        {
            $favs = array();
            $paginator ="";
            $data = array(
                    'favoritos'      => $favs,
                    'paginator'      => $paginator,
                );

            return View::make('front::pages.panel.favoritos', array('data' => $data));
        }

        $favs = array();
        $fav  = new Favorites(false, false);
        $favs = $fav->getFavsNewByCodCli();

		$all_favorites = array();


		if(!isset($favs['status']) || $favs['status'] != 'error') {
			foreach ($favs as $lot){
				$lot->url_img = \Tools::url_img("lote_small", $lot->num_hces1, $lot->lin_hces1);
				$url_friendly = str_slug($lot->titulo_hces1);
				$lot->url_lot = \Routing::translateSeo('lote').$lot->id_sub."-".str_slug($lot->name).'-'.$lot->id_auc_sessions."/".$lot->id_ref.'-'.$lot->num_hces1.'-'.$url_friendly;

				$all_favorites[$lot->id_sub]['lotes'][] = $lot;
				$all_favorites[$lot->id_sub]['inf'] = [
					'cod_sub' => $lot->id_sub,
					'des_sub' => $lot->des_sub,
					'name' => $lot->name,
				];
			}
		}

        $data = array(
                    'favoritos'      => $all_favorites
                );

        return View::make('front::pages.panel.new_favoritos', array('data' => $data));


     }

	public function getFavoritosLotsNew()
    {

        if(!Session::has('user'))
        {
            $favs = array();
            $data = array(
                    'favoritos'      => $favs,
                );

            return View::make('front::pages.panel.favoritos', array('data' => $data));
        }

        $favs = array();
        $fav  = new Favorites(Session::get('user.cod'), false);
        $favs = $fav->getFavsNewByCodCli();

        $data = array(
                    'favoritos'      => $favs,
                );

        return View::make('front::pages.panel.favoritos', array('data' => $data));
    }


	// ---------------------------- //
	// ----Fin Nuevos Favoritos---- //
	// ---------------------------- //

     //Guardar save divisas
    public function savedDivisas(){
        if(Session::has('user')){

            $user = new User();
            $cod_cli =  Session::get('user.cod');
            $divisa = Request::input('divisa');
            Session::put('user.currency', $divisa);
            $user->updateDivisa($cod_cli,$divisa);
       }

    }

    /** Tauler **/
    public function activateAcount()
    {
       return View::make('front::pages.activate_account');
    }

    public function sendPasswordActivateRecovery(){

        $email = Request::input('email');
        $val_post = Request::input('post');

        $user = new User();
        $user->email = $email;
        $mail_exists = $user->getCliInfo(true);



        return array(
                'status'            => 'succes',
                'msg'               => trans(\Config::get('app.theme').'-app.login_register.pass_recovery_mail_send')
        );
    }

    public function passwordRecovery()
    {
       return View::make('front::pages.password_recovery');
    }


    public function sendPasswordRecovery()
    {
        $email = Request::input('email');
        $val_post = Request::input('post');
        $activate = Request::input('activate');

        $user = new User();
        $user->email = $email;
        $mail_exists = $user->getUserByEmail(true);

		$successResponse = [
			'status' => 'succes',
			'msg' => trans(Config::get('app.theme').'-app.login_register.pass_recovery_mail_send')
		];

        if (empty($email) || empty($mail_exists) || (!empty($mail_exists) && $mail_exists[0]->baja_tmp_cli != 'N')){

			//cualquier string es true, por lo que siempre entra en este if.
			//Igualmente, todos los clientes tienen el input a "true" ¿Eliminar este if?
            if (!empty($val_post) && $val_post == true) {

				//Solamente lo utiliza tauler
				if (!empty($activate) && $activate == true) {
                    return array(
                        'status' => 'error',
                        'msg' => trans(\Config::get('app.theme').'-app.login_register.email_does_not_exist')
                    );
                }

				//Lo comentamos para no exponer emails de clientes, aunque sean erroneos retornamos el mismo mensaje
                //return array('status' => 'error','msg' => trans(\Config::get('app.theme').'-app.login_register.not_valid_mail'));
				return $successResponse;

             } else {
                return Redirect::to(Routing::slug('login'))->with('error_pass_recovery', trans(\Config::get('app.theme').'-app.login_register.not_valid_mail'));
            }
        }

        $email = urlencode($email);
        $code = ToolsServiceProvider::encodeStr($email.'-'.$mail_exists[0]->pwdwencrypt_cliweb);
        $url = Config::get('app.url').'/'.Config::get('app.locale').'/email-recovery'.'?email='.$email.'&code='.$code;

        $email = new EmailLib('RECOVERY_PASSWORD');

        if(!empty($email->email)){
                $email->setUserByCod($mail_exists[0]->cod_cli,true);
                $email->setLink_pssw($url);
                $email->setTo($mail_exists[0]->usrw_cliweb,$mail_exists[0]->nom_cli);
                $email->send_email();
        }

        return $successResponse;
    }

    public function getPasswordRecovery()
    {

        if(Session::has('user')){
            header("Location: ".Config::get('app.url')."", true, 301);
            exit();
        }

        $email = urldecode(Request::input('email'));
        $old_code = Request::input('code');
        $email_enc = urlencode($email);

        $login = Request::input('login');

        $user = new User();
        $user->email = $email;
        $mail_exists = $user->getUserByEmail(false);

        if (empty($mail_exists) ){
            return Redirect::to(Routing::slug('login'))->with('error_pass_recovery', trans(\Config::get('app.theme').'-app.login_register.code_doesnt_match'));
        }

        $code = \Tools::encodeStr($email_enc.'-'.$mail_exists[0]->pwdwencrypt_cliweb);

        if ($old_code !== $code){
            return Redirect::to(Routing::slug('login'))->with('error_pass_recovery', trans(\Config::get('app.theme').'-app.login_register.code_doesnt_match'));
        }

        //Config para recuperar contraseña, 1 => panel para modificar password del cliente, 0 => contraseña random por email
        if(Config::get('app.panel_password_recovery')){
            $data = array(
                'inf_client' => head($mail_exists),
                'panelPassword' => true,
                'login' => $login
            );
            return View::make('front::pages.pass_recovered', array('data' => $data));

        }else{


            $new_pass = substr(uniqid(), -8);
            $new_pass_encrypt = NULL;
            if(!empty(Config::get('app.password_MD5'))){
                $new_pass_encrypt =  md5(Config::get('app.password_MD5').$new_pass);
            }
            $user->setUserPassword($new_pass_encrypt);

                $email = new EmailLib('NEW_PASSWORD');
                if(!empty($email->email)){
                        $email->setUserByCod($mail_exists[0]->cod_cli,true);
                        $email->setPassword($new_pass);
                        $email->setUrl(Config::get('app.url').\Routing::slug('user/panel/info'));
                        $email->setTo($mail_exists[0]->usrw_cliweb,$mail_exists[0]->nom_cli);
                        $email->send_email();
                }

            return View::make('front::pages.pass_recovered');
        }
       }

       //Comporbamos que exista Tsec
    public function getFavTsec($tipo_tsec){
        $exist = false;
        $user = new User();
        $tsecciones = $user ->fav_themes(Config::get('app.emp'),Session::get('user.cod'));
        foreach($tsecciones as $tsec){
            if($tipo_tsec == $tsec->tsec_cliwebtsec){
                return $exist = true;
            }
        }

        return $exist;
    }

    //Modificar Tsec Favoritos (Balclis)
    public function changeFavTsec(){
        $user = new User();
        $tipo_tsec = Request::input('cod_sec');
        try {
            $exist = $this->getFavTsec($tipo_tsec);
            if($exist){
                $user->deletefavorites(Config::get('app.emp'),Session::get('user.cod'),$tipo_tsec);
                $value = array(
                    'status' => 'success',
                    'msg'=>'deleteSec',
                    'type'=>'delete'
                );
            }else{
                $user->addfavorites(Config::get('app.emp'),Session::get('user.cod'),$tipo_tsec);
                $value = array(
                    'status' => 'success',
                    'msg'=>'addSec',
                    'type'=>'add'
                );
            }

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
           $value = array(
                    'status' => 'error',
                    'msg'=>'generic',
                );
        }



        return $value;

    }
    // Saber si existe pdf de factruas
    public function bills($afral,$nfral,$exist_file = false){
        $user = new User();

        $factura = new \stdClass();
        $factura->afral = $afral;
        $factura->nfral = $nfral;
        $factura->cod_cli = Session::get('user.cod');
        $factura->pdf = null;
        $factura->emp = Config::get('app.emp');
        $a = $user->getFactura($factura);

        if(!empty($a) && !empty($a->fich_dvc02)){
            $factura->pdf = $a->fich_dvc02;
            $factura->date = date('d-m-Y',strtotime ($a->fecha_dvc0));
            //$directorio = $user->getFxdir($factura->emp);
            $filename =  'bills/'.Config::get('app.emp').'/'.$factura->pdf.'.PDF';
            $data = array (
                'date'=>$factura->date,
                'filname'=>$filename
            );
            if($exist_file){
                 return $data;
            }
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="'.$factura->afral.$factura->nfral.'-'.$factura->date.'.PDF"');
            readfile($filename);
        }else{
            if($exist_file){
                return false;
            }else{
                exit (\View::make('front::errors.404'));
            }
        }

    }


	/**
	 * Recibir o descargar albaran
	 */
	public function proformaInvoiceFile($cod_sub, $returnDataFile = false)
	{
		if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
            return View::make('front::pages.not-logged', array('data' => $data));
        }

		$emp = Config::get('app.emp');

		$cod_cli = Session::get('user.cod');

		$path = "bills/$emp/$cod_cli-$cod_sub.PDF";

		$file = public_path(str_replace("\\", "/", $path));

		if(!file_exists($file)){
			if($returnDataFile){
				return false;
			}
			exit(\View::make('front::errors.404'));
		}

		if($returnDataFile)
		{
			return [
				'date' => date('d-m-Y', filemtime($file)),
				'filname'=>$path
			];
		}

		header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$cod_cli.'-'.$cod_sub.'-'.date('d-m-Y', filemtime($file)).'.PDF"');
        readfile($path);
	}

    public function  SuccessRegistered(){

         return view('pages.success_registered');
    }

    public function existEmail(){
        $email = Request::input('email');
        $user = new User();

        if(!empty(Session::get('user.cod'))){
            $user->cod_cli =  Session::get('user.cod');
            $inf_user = $user->getUser();
            if($inf_user->usrw_cliweb == $email){
                return array(
                    'status'            => 'success'
                );
            }
        }

        $exist = $user->EmailExist($email,Config::get('app.emp'),Config::get('app.gemp'));

        if(!empty($exist)){
            return array(
                'status'            => 'error',
            );
        }else{
            return array(
                'status'            => 'success'
            );
        }
    }

    # Miramos on the fly si existe el NIF
    public function existNif(){
		#si permitimos multiples nif n odamos este error
		if(\Config::get("app.multipleNif")){
			return array(
				'status'            => 'success'
			);
		}

        $user = new User();
        $user->nif = mb_strtoupper(trim(Request::input('nif')));
        $exist = $user->getUserByNif("N");


        if(!empty($exist)){

			#COMPROBAMOS SI TIENE USUARIO WEB, SI YA TIENE USUARIO WEB NO DEBE PODER CONTINUAR
			$cliWeb = FxCliWeb::select("cod_cliweb")->where('cod_cliweb', $exist[0]->cod_cli)->first();
			if(!empty($cliWeb)){
				return array(
					'status'            => 'error',
				);
			}else{
				return array(
					'status'            => 'success'
				);
			}
        }else{
            return array(
                'status'            => 'success'
            );
        }
    }

    //Pais i codigo postal devolvemos provincia i ciudad
    public function CodZip(){
        $data = array(
            "pob" => '',
            "des_prv" => '',
            "status"=>'error'
        );
        $zip_code = Request::input('zip');
        $country = Request::input('country');
        $user = new User();
        $pob = $user->getTown($zip_code,$country);

        if(!empty($pob)){
            $data['pob'] = !empty($pob->des_pob)?$pob->des_pob:'';
            $data['des_prv'] = !empty($pob->prov_pob)?$pob->prov_pob:'';
            $data['status'] = 'success';
        }
        return $data;

    }

    //Modificaciom el idioma del controllado, comprobando el idioma del cliente
    public function langUser($cod_user = NULL){

        if(!empty($cod_user)){
            $user = new User();
            $user->cod_cli = $cod_user;
            $inf_user = $user->getUser();
            if (!empty($inf_user)){
                $locales = Config::get('app.locales');
                $lang = strtolower($inf_user->idioma_cliweb);
                if(array_key_exists($lang,$locales))
                {
                    \App::setLocale($lang);
                }
            }
        }

    }

    public function ordersClient(){

        $subastaObj        = new Subasta();
        $data = array(
            "sub" => null,
        );


        if(!empty($_GET['sub'])){
            $subasta = $_GET['sub'];
            $subastaObj->cod = $subasta;
            $inf_subasta =$subastaObj->getInfSubasta();

            if(!empty($inf_subasta) && strtotime("now") > strtotime($inf_subasta->orders_start)  &&   strtotime("now") < strtotime($inf_subasta->orders_end)){

                $subastaObj->page = 'all';
                $subastaObj->licit = Session::get('user.cod');
                $subastas = $subastaObj->getAllSubastaLicitOrdenes($subasta);
                $data['sub'] = $subastas;
            }
        }

        $data['node']  = array(
            'ol'       => Config::get('app.url')."/".\App::getLocale()."/api/ol/subasta",
        );

        return View::make('front::pages.panel.ordenes_cli', array('data' => $data));
    }
    // Ip
    function getUserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return $_SERVER['REMOTE_ADDR'];
    }

    //Doble validación por email (registro y newsletter)
    public function getEmailValidation(){

        $newsletter = new Newsletter();

        $type = Request::input('type');

        if($type == 'new_user'){
            $cod = Request::input('code');
            $email = Request::input('email');
            $user = new User();
            $user->email = $email;
            $mail_exists = $user->getUserByEmail(false);
            if (empty($mail_exists) ){
                return Redirect::to(Routing::slug('login'));
            }
            $code = \Tools::encodeStr($email.'-'.$mail_exists[0]->cod_cli);

            if ( $mail_exists[0]->baja_tmp_cli != 'W' || $cod != $code){
                return Redirect::to(Routing::slug('login'));
            }

            $user->BajaTmpCli($mail_exists[0]->cod_cli,'N',date("Y-m-d H:i:s"),'W');
			Redirect::to(route('user.registered'));

        }elseif($type == 'newsletter'){
            $cod = Request::input('code');
            $email = Request::input('email');

            $bindings = array(
                'gemp'  => Config::get('app.gemp'),
                'emp'  => Config::get('app.emp'),
                'email' => $email,
                'cod'=> '-1',
            );

            $value = $newsletter->checkIfUserHaveNewsletters($bindings);

            if (empty($value) ){
                return Redirect::to(Routing::slug('/'));
            }
            $val_fam='newsletter'.$email;
            $code = \Tools::encodeStr($email.'-'.$val_fam);
            $value = head($value);
            if($code != $cod){
                 return Redirect::to(Routing::slug('/'));
            }
            $bindings['cod'] = '0';
            $newsletter->updateCodNewsletter($bindings);
            return Redirect::to(Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.high-newsletter'));

        }else{
            exit (\View::make('front::errors.404'));
        }
    }

    public function AcceptConditionsUser(){
        $user =  new User();

        $user->cod_cli=Session::get('user.cod');
        $inf_user=$user->getUser();
        if($user->changeConditionsUser()){
            Session::put('user.condiciones','S');
            $res = array(
                 'status' => 'success'
              );
        }else{
            $res = array(
                 'status' => 'error'
              );
        }
        return $res;
    }

    //Buscamos la taxa del cliente que va a tener dependiendo del pais que haya puesto en el registo
    public function cliente_tax($pais,$cpostal){


       //iva parametrizado para todos los clientes, se debe permitir el valor 0, ojo no tocar los tres iguales ni las comillas
       if(!empty(Config::get('app.all_tax_clients')) || Config::get('app.all_tax_clients')=== "0"){
           return Config::get('app.all_tax_clients');
       }


       $iva_cli = 1;
       //iva parametrizado para pasises europeos
       if(!empty(Config::get('app.Eur_tax_clients'))){
           $iva_cli = Config::get('app.Eur_tax_clients');
       }

       //paises europeos
       $paises = \Tools::PaisesEUR();

       $canarias = array('38','35');
       $mel_ceu = array('51','52');

       if($pais == 'AD'){

          $iva_cli = 0;
          //iva parametrizado para andorra
          if(!empty(Config::get('app.andorra_tax_clients'))){
             $iva_cli = Config::get('app.andorra_tax_clients');
          }

	   }

	   elseif($pais == 'ES' && in_array(substr($cpostal,0,2),$canarias)){
			$iva_cli = 1;

			if(Config::get('app.canarias_0_tax_clients', 0)){
				$iva_cli = 0;
			}


		}
       /*elseif($pais == 'ES' && in_array(substr($cpostal,0,2),$mel_ceu)){
          $iva_cli = 0 ;

       }*/
       elseif(!in_array($pais, $paises)){

          $iva_cli = 4;
          //iva parametrizado para paises no europeos
          if(!empty(Config::get('app.notEur_tax_clients'))){
             $iva_cli = Config::get('app.notEur_tax_clients');
          }

       }

       return $iva_cli;
    }

    //facturas pendientes de pago
    public function getPendingBills(){

        if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
            return View::make('front::pages.not-logged', array('data' => $data));
        }

        $User = new User();
        $facturas = new Facturas();
        $payments = new Payments();
        $paymentsCont = new PaymentsController();
        $User->cod_cli = Session::get('user.cod');
        $user_cli=$User->getUser($User->cod_cli);
        $facturas->cod_cli = Session::get('user.cod');

        $inf_fact = array();
        //Sacamos facturas pendiente de pago
        $pendientes = $facturas->pending_bills();
        $tipo_tv = array();
        $js_fact = array();
        if(!empty($pendientes)){
            foreach($pendientes as $val_pendiente){
                $facturas->serie = $val_pendiente->anum_pcob;
                $facturas->numero = $val_pendiente->num_pcob;
                $fact_temp = $this->bills($val_pendiente->anum_pcob,$val_pendiente->num_pcob,true);

                $val_pendiente->date = $fact_temp['date'] ?? null;
                $val_pendiente->factura = $fact_temp['filname'] ?? null;
                //buscamos si la factura esta generada
                $tipo_fact = $facturas->bill_text_sub( substr($facturas->serie, 0, 1),substr($facturas->serie, 1));
                //Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
                if($tipo_fact->tv_contav == 'T'){
                    $inf_fact['T'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facturas->getFactTexto();
                }elseif($tipo_fact->tv_contav == 'L' || $tipo_fact->tv_contav == 'P'){
                    $inf_fact['S'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facturas->getFactSubasta();
                }
                //Sacamos de factura el precio
                $js_fact[$val_pendiente->anum_pcob][$val_pendiente->num_pcob][$val_pendiente->efec_pcob] = floatval($val_pendiente->imp_pcob);
                //Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
                $tipo_tv[$facturas->serie][$facturas->numero] = $tipo_fact->tv_contav;

            }
        }




        $data = array(
            'pending' => $pendientes,
            'inf_factura'   => $inf_fact,
            'user'  =>$user_cli,
            'js_item'   => $js_fact,
            'tipo_tv'  => $tipo_tv,
        );

        return View::make('front::pages.panel.pending_bills', array('data' => $data));
    }

    //Facturas del cliente
     public function myBills(){
         if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
            return View::make('front::pages.not-logged', array('data' => $data));
        }

        $User = new User();
        $facturas = new Facturas();
        $facturas->cod_cli = Session::get('user.cod');
        $tipo_tv = array();
        //buscamos facturas pagadas

        $pagado = $facturas->paid_bill();
        $inf_fact = array();
        if(!empty($pagado)){
            foreach($pagado as $fact_pag){
                $fact_temp = $this->bills($fact_pag->afra_cobro1,$fact_pag->nfra_cobro1,true);
                $fact_pag->date = $fact_temp['date'] ?? null;
                $fact_pag->factura = $fact_temp['filname'] ?? null;
                $facturas->serie = $fact_pag->afra_cobro1;
                $facturas->numero = $fact_pag->nfra_cobro1;
                //Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
                if($fact_pag->tv_contav == 'T'){
                    $inf_fact['T'][$facturas->serie][$facturas->numero] = $facturas->getFactTexto();
                }elseif($fact_pag->tv_contav == 'L' || $fact_pag->tv_contav == 'P'){
                    $inf_fact['S'][$facturas->serie][$facturas->numero] = $facturas->getFactSubasta();
                }
                //Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
                $tipo_tv[$facturas->serie][$facturas->numero] = $fact_pag->tv_contav;
            }
        }

        $data = array(
            'bills' => $pagado,
            'inf_factura' => $inf_fact,
            'tipo_tv'   =>$tipo_tv
        );

        return View::make('front::pages.panel.myBills', array('data' => $data));
    }

    public function days_extras_almacen($fecha_csub){
        $params_sub = new \App\Models\Subasta();
        $params = $params_sub->getParametersSub();
        $date = round ((strtotime("now") - strtotime($fecha_csub) ) / 86400) - $params->dayspagext_prmsub ;
        $price_almacen = 0;
        if($date > 0){
            $price_almacen = $date * $params->imppagext_prmsub;
        }

        return array('price' => $price_almacen, 'date'=>$date);
    }

    //Panel para modificar contraseña, el usuario ha pedido recibir un email para modificarla
    public function changePassw(HttpRequest $request){
        $user = new User();

        $res = array(
            'status' => 'error',
            'msg' => 'user_panel_inf_error'
         );

		if(Config::get('app.strict_password_validation', false)) {
			$validations = $this->checkIsValidPassword($request);
			if(!empty($validations)){
				return response()->json(['status' => 'error', 'message' => $validations], 422);
			}
		}


        $email = Request::input('email');
        $password = Request::input('password');
        $confirm_password = Request::input('confirm_password');

        //Comprobamos que las 2 contraseñas sean iguales
        if($password != $confirm_password){
            return $res;
        }

        $user->email = $email;
        $inf_user = $user->getUserByEmail(false);
        //Comporbamos que exista el usuario
        if(empty($inf_user)){
            return $res;
        }

        $inf_user = head($inf_user);
		$user->cod_cli = $inf_user->cod_cliweb;

		if(!empty(Config::get('app.multi_key_pass'))){
			$newKey=md5(time());
			$pass_md5 =  trim(md5($newKey.$password).":".$newKey);
		}else{
			$pass_md5 = trim(md5(Config::get('app.password_MD5').$password));
		}

        $user->pwd_encrypt = trim($pass_md5);
        //Modificamos password del usuario
        $user->updatePassword();

        //Gerenamos session usuario
        $this->SaveSession($inf_user);
        $ip = $this->getUserIP();
        //Hacemos el log del usuario
        $user->logLogin($inf_user->cod_cliweb,Config::get('app.emp'),date("Y-m-d H:i:s"),$ip);

        $res['status'] = 'success';
        $res['msg'] = 'user_panel_inf_actualizada';
        return $res;



    }

	public function getAllAllotmentsAndBills()
	{
		$seo = new \Stdclass();
		$seo->noindex_follow = true;

		if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data['data'] = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
			$data['seo'] = $seo;
            return View::make('front::pages.not-logged', $data);
        }

		$cod_cli = session('user.cod');

		//user data
		$userModel = User::factory()
			->setCodCli($cod_cli)
			->setItemsPerPage('all');

		$user =	$userModel->getUser();

		$addres = new Address();
        $addres->cod_cli = $user->cod_cli;
        $envio = $addres->getUserShippingAddress();

		//allotments
		$pendingAllotmentsData = $this->getPendingAllotmentsData($user, $envio);
		$payedAllotmentsData = $this->getPayedAllotmentsData($user, $envio);

		//bills
		//Las facturas pagadas contienen los mismos lotes que las adjudicaciones pagadas,
		//por lo que obtener las dos en esta página sería duplicar información
		$pendingBillsData = $this->getPendingBillsData($user);

		//extraer variables para acomodar datos
		//esto no se hace dentro de los metodos anteriores para mantener compativilidad con metodos antiguos
		['adjudicaciones' => $pendingAllotments, 'js_item' => $allotmetsForJs] = $pendingAllotmentsData;
		['adjudicaciones_pag' => $payedAllotments] = $payedAllotmentsData;

		//billsForJs solo sería necesario si permitimos checkear las facturas
		['pending' => $pendingBills, 'js_item' => $billsForJs] = $pendingBillsData;

		//agrupaciones por subasta
		$auctionsIdsPending = $pendingAllotments->pluck('sub_csub')->merge($pendingBills->where('tipo_tv', 'L')->pluck('cod_sub'));
		$auctionsIdsPayed = $payedAllotments->pluck('sub_csub');

		$allAuctionsIds = $auctionsIdsPending->merge($auctionsIdsPayed)->unique()->filter();
		$auctions = FgSub::joinSessionSub()->addSelect('compraweb_sub')->whereIn('cod_sub', $allAuctionsIds)->where('"reference"', '001')->get();

		$payedForAuctions = $auctionsIdsPayed->unique()->map(function($cod_sub) use ($payedAllotments, $auctions) {
			return [
				'auction' => $auctions->where('cod_sub', $cod_sub)->first(),
				'allotments' => $payedAllotments->where('sub_csub', $cod_sub),
			];
		});

		$pendingForAuctions = $auctionsIdsPending->unique()->map(function($cod_sub) use ($pendingAllotments, $pendingBills, $auctions) {
			return [
				'auction' => $auctions->where('cod_sub', $cod_sub)->first(),
				'allotments' => $pendingAllotments->where('sub_csub', $cod_sub),
				'bills' => $pendingBills->where('cod_sub', $cod_sub)->where('tipo_tv', 'L'),
			];
		});

		$data = [
            'user' => $user,
			'seo' => $seo,
			'envio' => $envio,
			'currency' => (new Subasta())->getCurrency(),
			'payedForAuctions' => $payedForAuctions,
			'pendingForAuctions' => $pendingForAuctions,
			'allotmetsForJs' => $allotmetsForJs,
		];

		return view('front::pages.panel.adjudicaciones_facturas', ['data' => $data]);
	}

	private function getPayedAllotmentsData($user, $envio)
	{
		$emp = config('app.emp');
		$gemp = config('app.gemp');

		$pago_controller = new PaymentsController();

        $adjudicaciones_pag = User::factory()->setCodCli($user->cod_cli)->getAdjudicacionesPagar('S');

		//Con este bloque, logramos eliminar la consulta dentro del for de adjudicaciones
		$referencesByAction = $adjudicaciones_pag->groupBy('sub_csub')
			->map(function($adjudicaciones){
				return $adjudicaciones->pluck('ref_asigl0');
			});

		$gastosExtra = (new Payments())->getGastosExtra($referencesByAction, 'C');

		$iva = $pago_controller->getIva($emp, date("Y-m-d"));
        $tipo_iva = $pago_controller->user_has_Iva($gemp, $user->cod_cli);

		$adjudicaciones_pag = collect($adjudicaciones_pag)->map(function($adjudicacion) use ($user, $envio, $iva, $tipo_iva, $gastosExtra) {
			return $this->allotmentFormat($adjudicacion, $user, $envio, $iva, $tipo_iva, $gastosExtra);
		});

		return [
			'adjudicaciones_pag' => $adjudicaciones_pag,
			#al pasar le valor 1 devolvera el iva que hay que aplicar, por ejemplo 0,21
			'ivaAplicable' => $pago_controller->calculate_iva($tipo_iva->tipo, $iva, 1),
		];
	}

	private function getPendingAllotmentsData($user, $envio)
	{
		$emp = config('app.emp');
		$gemp = config('app.gemp');

		$subasta = new Subasta();
		$parametrosSub = $subasta->getParametersSub();

		$adjudicaciones_pendientes = User::factory()->setCodCli($user->cod_cli)->getAdjudicacionesPagar('N');
		$pago_controller = new PaymentsController();

		$iva = $pago_controller->getIva($emp, date("Y-m-d"));
		$tipo_iva = $pago_controller->user_has_Iva($gemp, $user->cod_cli);

		//Con este bloque, logramos eliminar la consulta dentro del for de adjudicaciones
		$referencesByAction = $adjudicaciones_pendientes->groupBy('sub_csub')
			->map(function($adjudicaciones){
				return $adjudicaciones->pluck('ref_asigl0');
			});
		$gastosExtra = (new Payments())->getGastosExtra($referencesByAction, 'C');

		$adjudicaciones_temporales = collect($adjudicaciones_pendientes)->map(function ($adjudicacion) use ($user, $envio, $iva, $tipo_iva, $gastosExtra) {
			return $this->allotmentFormat($adjudicacion, $user, $envio, $iva, $tipo_iva, $gastosExtra);
		});

		$adjudicaciones = $adjudicaciones_temporales->where('estado_csub0', '!=', 'T');
		#adjudicaciones que estan pendientes de pagar por transferencia, y no pueden salir como no pagadas
		$adjudicaciones_transfer = $adjudicaciones_temporales->where('estado_csub0', 'T');

		return [
			'adjudicaciones' => $adjudicaciones,
			'adjudicaciones_transfer' => $adjudicaciones_transfer,
			'js_item' => $this->generatePreciosLotAdj($adjudicaciones),
			'price_exportacion' => floatval($parametrosSub->licexp_prmsub),
		];
	}

	private function allotmentFormat($adjudicacion, $user, $envio, $iva, $tipo_iva, $gastos)
	{
		$subastaClass = new Subasta();
		$pagoController = new PaymentsController();

		$withNotIva = \Config::get("app.noIVAOnlineAuction") && $adjudicacion->tipo_sub == 'O';

		$adjudicacionFormat = (object) array_merge(get_object_vars($adjudicacion), [
			'formatted_imp_asigl1' => \Tools::moneyFormat($adjudicacion->himp_csub),
			'imagen' => $subastaClass->getLoteImg($adjudicacion),
			'date' => \Tools::euroDate($adjudicacion->fec_asigl1, $adjudicacion->hora_asigl1),
			'imp_asigl1' => $adjudicacion->himp_csub,
			'base_csub_iva' => $withNotIva ? 0 : $pagoController->calculate_iva($tipo_iva->tipo, $iva, $adjudicacion->base_csub),
			//'extras' => (new Payments())->getGastosExtrasLot($adjudicacion->sub_csub,$adjudicacion->ref_csub, $tipo = null, 'C'),
			'extras' => $gastos->where('sub_asigl2', $adjudicacion->sub_csub)->where('ref_asigl2', $adjudicacion->ref_asigl0),
            'factura' => $this->bills($adjudicacion->afral_csub, $adjudicacion->nfral_csub, true),
            //'pending_fact' => (new Facturas())->pending_bills(false), //no le he encontrado sentido
			'days_extras_alm' => $this->days_extras_almacen($adjudicacion->fecha_csub),
			'prefactura' => $this->proformaInvoiceFile($adjudicacion->sub_csub, true),
			'ref_asigl0' => str_replace('.', '_', $adjudicacion->ref_asigl0)

		]);

		//Existen lotes en Tauler que no deben añadir el precio de exportación al pago, en object_types controlamos si se cobra o no
		//03/11/2021 - Eloy
		$exportacion = $subastaClass->hasExportLicense($adjudicacion->num_hces1, $adjudicacion->lin_hces1);

		$adjudicacionFormat->licencia_exportacion = 0;
		if($exportacion){
			$envioPorDefecto = collect($envio)->where('codd_clid', 'W1')->first();
			$adjudicacionFormat->licencia_exportacion = $pagoController->licenciaDeExportacionPorPais($envioPorDefecto->codpais_clid ?? $user->codpais_cli, $adjudicacion->himp_csub);
		}

		return $adjudicacionFormat;
	}

	private function getPendingBillsData($user)
	{
		$facturas = new Facturas();
		$facturas->cod_cli = $user->cod_cli;

        $pendientes = $facturas->pending_bills();
		$inf_fact = array();
        $tipo_tv = array();
        $js_fact = array();

		foreach($pendientes as $val_pendiente){

			$facturas->serie = $val_pendiente->anum_pcob;
			$facturas->numero = $val_pendiente->num_pcob;
			$fact_temp = $this->bills($val_pendiente->anum_pcob,$val_pendiente->num_pcob,true);

			$val_pendiente->date = $fact_temp['date'] ?? null;
			$val_pendiente->factura = $fact_temp['filname'] ?? null;

			//buscamos si la factura esta generada
			$tipo_fact = $facturas->bill_text_sub(substr($facturas->serie, 0, 1), substr($facturas->serie, 1));
			$tipo_tv_contav = $tipo_fact->tv_contav;
			$val_pendiente->tipo_tv = $tipo_tv_contav;
			$totalPrice = 0;

			//Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
			if($tipo_tv_contav == 'T'){

				$facturaTexto = $facturas->getFactTexto();
				$inf_fact['T'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facturaTexto;
				$val_pendiente->inf_fact = ['T'];
				$val_pendiente->inf_fact['T'] = $facturaTexto;
				/* Las facturas de texto no tienen código de subasta
				if(!empty($val_pendiente->inf_fact['T'])){
					$val_pendiente->cod_sub = collect($val_pendiente->inf_fact['T'])->where('sub_dvc1l', '!=', 'null')->first()->sub_dvc1l;
				}
				*/
				foreach ($facturaTexto as $factura) {
					$totalPrice += $factura->total_dvc1 + round(($factura->total_dvc1 * $factura->iva_dvc1) / 100, 2);
				}

			}elseif($tipo_tv_contav == 'L' || $tipo_tv_contav == 'P'){

				$facutraSubasta = $facturas->getFactSubasta();
				$inf_fact['S'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facutraSubasta;
				$val_pendiente->inf_fact['S'] = $facutraSubasta;
				if(!empty($val_pendiente->inf_fact['S'])){
					$val_pendiente->cod_sub = collect($val_pendiente->inf_fact['S'])->where('sub_dvc1l', '!=', null)->first()->sub_dvc1l;
				}

				foreach ($facutraSubasta as $factura) {
					if($tipo_tv_contav === 'P'){
						$totalPrice += (round(($factura->basea_dvc1l * $factura->iva_dvc1l) / 100, 2) + $factura->basea_dvc1l) - $factura->padj_dvc1l;
					}
					//=== L
					else {
						$totalPrice += $factura->padj_dvc1l + $factura->basea_dvc1l + round(($factura->basea_dvc1l * $factura->iva_dvc1l) / 100, 2);
					}
				}
			}

			$val_pendiente->total_price = $totalPrice;

			//Sacamos de factura el precio
			$js_fact[$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = floatval($val_pendiente->imp_pcob);
			//Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
			$tipo_tv[$facturas->serie][$facturas->numero] = $tipo_tv_contav;
		}

		return [
			'pending' => $pendientes,
			'inf_factura' => $inf_fact,
			'js_item'   => $js_fact,
			'tipo_tv'  => $tipo_tv,
		];
	}

	private function getPayedBillsData($user)
	{
		$facturas = new Facturas();
		$facturas->cod_cli = $user->cod_cli;

		$pagado = $facturas->paid_bill();
        $inf_fact_pag = array();
        $tipo_tv_pag = array();
		$totalPrice = 0;

		foreach($pagado as $fact_pag){

			$fact_temp = $this->bills($fact_pag->afra_cobro1,$fact_pag->nfra_cobro1,true);
			$fact_pag->date = $fact_temp['date'] ?? null;
			$fact_pag->factura = $fact_temp['filname'] ?? null;
			$facturas->serie = $fact_pag->afra_cobro1;
			$facturas->numero = $fact_pag->nfra_cobro1;

			//Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
			if($fact_pag->tv_contav == 'T'){
				//Las facturas de texto no están asociadas a lotes
				$facturaTexto = $facturas->getFactTexto();
				$fact_pag->cod_sub = null;
				$inf_fact_pag['T'][$facturas->serie][$facturas->numero] = $facturaTexto;
				$fact_pag->inf_fact = ['T'];
				$fact_pag->inf_fact['T'] = $facturaTexto;

				foreach ($facturaTexto as $factura) {
					$totalPrice += $factura->total_dvc1 + round(($factura->total_dvc1 * $factura->iva_dvc1) / 100, 2);
				}


			}elseif($fact_pag->tv_contav == 'L' || $fact_pag->tv_contav == 'P'){

				$facturasSubasta = $facturas->getFactSubasta();
				//Puede existir mas de una subasta en cada factura, pero ahora mismo solamente necesito la primera. (Eloy)
				$fact_pag->cod_sub = (count($facturasSubasta) > 0) ? $facturasSubasta[0]->sub_dvc1l : null;
				$inf_fact_pag['S'][$facturas->serie][$facturas->numero] = $facturasSubasta;

				foreach ($facturasSubasta as $factura) {
					if($fact_pag->tv_contav == 'P'){
						$totalPrice += (round(($factura->basea_dvc1l * $factura->iva_dvc1l) / 100, 2) + $factura->basea_dvc1l) - $factura->padj_dvc1l;
					}
					//=== L
					else {
						$totalPrice += $factura->padj_dvc1l + $factura->basea_dvc1l + round(($factura->basea_dvc1l * $factura->iva_dvc1l) / 100, 2);
					}
				}

			}

			$fact_pag->total_price = $totalPrice;
			//Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
			$tipo_tv_pag[$facturas->serie][$facturas->numero] = $fact_pag->tv_contav;
		}

        return [
			'inf_factura_pag' => $inf_fact_pag,
			'bills' => $pagado,
			'tipo_tv_pag'   =>$tipo_tv_pag,
		];
	}




    //Cargamos todas las facturas pendioente y no pendientes de pago
    public function allBills(){

		$seo = new \Stdclass();
		$seo->noindex_follow = true;


        if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data['data'] = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
			$data['seo'] = $seo;
            return View::make('front::pages.not-logged', $data);
        }

        $User = new User();
        $facturas = new Facturas();
        $payments = new Payments();
        $paymentsCont = new PaymentsController();
        $User->cod_cli = Session::get('user.cod');
        $user_cli=$User->getUser($User->cod_cli);
        $facturas->cod_cli = Session::get('user.cod');

        $inf_fact = array();
        //Sacamos facturas pendiente de pago
        $pendientes = $facturas->pending_bills();
        $tipo_tv = array();
        $js_fact = array();
        if(!empty($pendientes)){
            foreach($pendientes as $val_pendiente){
                $facturas->serie = $val_pendiente->anum_pcob;
                $facturas->numero = $val_pendiente->num_pcob;
                $fact_temp = $this->bills($val_pendiente->anum_pcob,$val_pendiente->num_pcob,true);

                $val_pendiente->date = $fact_temp['date'] ?? null;
                $val_pendiente->factura = $fact_temp['filname'] ?? null;
                //buscamos si la factura esta generada
                $tipo_fact = $facturas->bill_text_sub( substr($facturas->serie, 0, 1),substr($facturas->serie, 1));
                //Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
                if($tipo_fact->tv_contav == 'T'){
                    $inf_fact['T'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facturas->getFactTexto();
                }elseif($tipo_fact->tv_contav == 'L' || $tipo_fact->tv_contav == 'P'){
                    $inf_fact['S'][$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = $facturas->getFactSubasta();
                }
                //Sacamos de factura el precio
                $js_fact[$val_pendiente->anum_pcob][$val_pendiente->num_pcob] = floatval($val_pendiente->imp_pcob);
                //Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
                $tipo_tv[$facturas->serie][$facturas->numero] = $tipo_fact->tv_contav;

            }
        }

        //buscamos facturas pagadas

        $pagado = $facturas->paid_bill();
        $inf_fact_pag = array();
        $tipo_tv_pag = array();

        if(!empty($pagado)){
            foreach($pagado as $fact_pag){
                $fact_temp = $this->bills($fact_pag->afra_cobro1,$fact_pag->nfra_cobro1,true);
                $fact_pag->date = $fact_temp['date'] ?? null;
                $fact_pag->factura = $fact_temp['filname'] ?? null;
                $facturas->serie = $fact_pag->afra_cobro1;
                $facturas->numero = $fact_pag->nfra_cobro1;
                //Dependeiendo de si es una factura de texto o de subasta informacion se busca en un sitio o otro
                if($fact_pag->tv_contav == 'T'){
                    $inf_fact_pag['T'][$facturas->serie][$facturas->numero] = $facturas->getFactTexto();
                }elseif($fact_pag->tv_contav == 'L' || $fact_pag->tv_contav == 'P'){
                    $inf_fact_pag['S'][$facturas->serie][$facturas->numero] = $facturas->getFactSubasta();
                }
                //Generamos un array con el tipo de factura que es, nos sirve en la blade para los calculos
                $tipo_tv_pag[$facturas->serie][$facturas->numero] = $fact_pag->tv_contav;
            }
        }


        $data = array(
            'pending' => $pendientes,
            'inf_factura'   => $inf_fact,
            'user'  =>$user_cli,
            'js_item'   => $js_fact,
            'tipo_tv'  => $tipo_tv,
            'inf_factura_pag' => $inf_fact_pag,
            'bills' => $pagado,
            'tipo_tv_pag'   =>$tipo_tv_pag,
			'seo' => $seo
        );

		return View::make('front::pages.panel.allBills', array('data' => $data));
    }

	public function getShipment(){

		//request -> cod_sub, afral_csub, nfral_csub
		//añadir user.cod
		$auction = FgSub::select('dfec_sub')->where('cod_sub', request('cod_sub'))->first();

		$afral_csub = request('afral_csub', '');
		$nfral_csub = request('nfral_csub', '');

		$shipments = (new FxDvc0Seg())->getSeguimientoEnvío($afral_csub, $nfral_csub);

		$deliveryDate = FxDvc0Seg::getEstimatedDeliveryDate($auction->dfec_sub);

		return response(['shipments' => $shipments, 'delivery_date' => $deliveryDate ], 200);
	}


	public function getAdjudicacionesPendientePagoBySub()
    {
        $subasta = new Subasta();
        $parametrosSub= $subasta->getParametersSub();
        if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data = trans_choice(\Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
            return View::make('front::pages.not-logged', array('data' => $data));
        }

        $emp  = Config::get('app.emp');
        $gemp  = Config::get('app.gemp');
        # Lista de códigos de licitacion del usuario en sesion

        $User = new User();
        $User->cod_cli = Session::get('user.cod');
        $User->itemsPerPage = 'all';

        $adjudicaciones = $User->getAdjudicacionesPagar('N', request('cod_sub'));

		if($adjudicaciones->count() == 0){
			return view('errors.404');
		}

		$user_cli=$User->getUser($User->cod_cli);

        $pago_controller = new PaymentsController();
        $pago_modelo = new Payments();

        $user_cod = $User->cod_cli;

        $addres = new Address();
        $addres->cod_cli = $User->cod_cli;
        $envio = $addres->getUserShippingAddress();

        $iva = $pago_controller->getIva($emp,date ("Y-m-d"));
		$tipo_iva = $pago_controller->user_has_Iva($gemp,$user_cod);

		/**
		 * Aunque solamente tenga una adjudicación, algunos metodos que la reciben esperan un array
		 * por lo que no se puede convertir en un objecto individual
		 * */
        foreach($adjudicaciones as $adj){
            $adj->formatted_imp_asigl1 = \Tools::moneyFormat($adj->himp_csub);
            $adj->imagen = $subasta->getLoteImg($adj);
            $adj->date = \Tools::euroDate($adj->fec_asigl1, $adj->hora_asigl1 );
            $adj->imp_asigl1 = $adj->himp_csub;
            $adj->base_csub_iva = $pago_controller->calculate_iva($tipo_iva->tipo,$iva,$adj->base_csub);
            //Modificamos ref_asigl0 de . a _ porque si hay punto el js de calclulo de pagar no calcula bien
            $adj->ref_asigl0 = str_replace('.','_',$adj->ref_asigl0);
			$adj->days_extras_alm = $this->days_extras_almacen($adj->fecha_csub);

			//Existen lotes en Tauler que no deben añadir el precio de exportación al pago, en object_types controlamos si se cobra o no
			//03/11/2021 - Eloy
			$exportacion = $subasta->hasExportLicense($adj->num_hces1, $adj->lin_hces1);

			$adj->licencia_exportacion = 0;
			if($exportacion){
				$envioPorDefecto = collect($envio)->where('codd_clid', 'W1')->first();
				$adj->licencia_exportacion = $pago_controller->licenciaDeExportacionPorPais($envioPorDefecto->codpais_clid ?? $user_cli->codpais_cli, $adj->himp_csub);
			}


		}
        //Podemos saber que iva va a tener el cliente
        $user_cli->iva_cli = $pago_controller->hasIvaReturnIva($tipo_iva->tipo,$iva);

		//paises
		$countries = FsPaises::select('cod_paises', 'des_paises')->JoinLangPaises()->orderby("des_paises")->pluck('des_paises', 'cod_paises');

		//formas de pago
		/**
		 * @todo pendiente
		 */

        $sub = new Subasta();
        $data = array(
                    'adjudicaciones' => $adjudicaciones,
                    'currency'       => $sub->getCurrency(),
                    'envio'    =>$envio,
                    'user'  => $user_cli,
                    'js_item'=>$this->generatePreciosLotAdj($adjudicaciones),
					'price_exportacion' => floatval($parametrosSub->licexp_prmsub),
					'countries' => $countries
				);

        return View::make('front::pages.panel.adjudicaciones_subasta_pagar', array('data' => $data));
	}

	public function updateWallet(HttpRequest $request)
	{
		if(!session()->has('user')){
			return response(['message' => 'No estas logueado'], 401);
		}

		$user = session('user');
		FxCli::where('cod_cli', $user['cod'])->update(['wallet_cli' => $request->wallet_dir]);

		return response(['status' => 'success', 'message' => 'wallet save'], 200);
	}

	public function createWallet(HttpRequest $request)
	{
		if(!session()->has('user')){
			return response(['message' => 'No estas logueado'], 401);
		}

		$vottunController = new VottunController();
		$hash = $vottunController->vottunPowTicket();
		$hash = $hash->walletRequestTicket;

		$urlBack = route('wallet.back');
		$url = "https://pow.vottun.tech/connect?h=$hash&r=$urlBack";

		return response()->json(['status' => 'success', 'message' => 'hash obtained', 'url' => $url]);
	}

	/**
	 * posibles respuestas
	 * 1. ?success=false&error=user_cancel
	 * 2. ?success=true&walletAddress={walletAddress}
	 */
	public function backVottumWallet(HttpRequest $request)
	{
		Log::info('backVottumWallet', ['request' => $request->all()]);

		if(!session()->has('user') || !$request->get('success') || !$request->has('walletAddress')){
			//redirigir a pagina de error
		}

		$request->request->add(['wallet_dir' => $request->walletAddress]);

		$this->updateWallet($request);

		return redirect()->route('panel.account_info', ['lang' => config('app.locale')]);
	}

	public function getPreferencesAndFamily()
	{
		$user = session('user');
		$queryPreferences = Web_Preferences::select('ID_PREF', 'DESC_PREF', 'NOM_CLIWEB', 'DES_ORTSEC0', 'DES_SEC', 'KEYWORD1_PREF', 'KEYWORD2_PREF', 'KEYWORD3_PREF')
			->joinUsersPreferences()
			->joinOrtsec0Preferences()
			->joinSecPreferences()
			->where('COD_CLIWEB_PREF', $user['cod'])->get();

		$queryFamily = FgOrtsec0::select('SUB_ORTSEC0', 'LIN_ORTSEC0', 'DES_ORTSEC0')->where('FGORTSEC0.SUB_ORTSEC0', '0')->get();

		$ifQueryPreferences = count($queryPreferences) > 0;

		return View::make('front::pages.panel.preferences', array('queryPreferences' => $queryPreferences, 'queryFamily' => $queryFamily, 'ifQueryPreferences' => $ifQueryPreferences));	}

	public function getSubfamilyForPreferences(httpRequest $request)
	{

		$form = $request->all();
		$linAndAucFamily = explode("-", $form['family-selector']);
		$aucFamily = $linAndAucFamily[0];
		$linFamily = $linAndAucFamily[1];

		$querySubfamily = FgOrtsec0::select('COD_SEC','DES_SEC')
			->joinOrtsec1FgOrtsec0()
			->joinSecOrtsec0()
			->where('LIN_ORTSEC1', $linFamily)->where('SUB_ORTSEC1', $aucFamily)->where('EMP_ORTSEC1', \Config::get('app.emp'))->get();


		return response()->json(['querySubfamily' => $querySubfamily]);
	}

	public function setPreferences(HttpRequest $request)
	{
		$form = $request->all();
		$user = session('user');
		$codCli = $user['cod'];

		if ($form['keyword1'] != '' || $form['keyword2'] != '' || $form['keyword1'] != '' || $form['family-selector'] != '-') {
			# Validaciones
			$keyword1 = explode(" ", $form['keyword1']);
			$keyword1 = $keyword1[0];
			$keyword2 = explode(" ", $form['keyword2']);
			$keyword2 = $keyword2[0];
			$keyword3 = explode(" ", $form['keyword3']);
			$keyword3 = $keyword3[0];

			if ($form['family-selector'] != '-') {
				$linAndAucFamily = explode("-", $form['family-selector']);
				$aucFamily = $linAndAucFamily[0];
				$linFamily = $linAndAucFamily[1];
				if ($form['subfamily-selector'] == '') {
					$form['subfamily-selector'] = null;
				}
			} else {
				$aucFamily = null;
				$linFamily = null;
				$form['subfamily-selector'] = null;
			}

			Web_Preferences::insert([
				'COD_CLIWEB_PREF' => $codCli,
				'EMP_PREF' => \Config::get('app.emp'),
				'DESC_PREF' => $form['preference_name'],
				'SUB_ORTSEC0_PREF' => $aucFamily,
				'LIN_ORTSEC0_PREF' => $linFamily,
				'COD_SEC_PREF' => $form['subfamily-selector'],
				'KEYWORD1_PREF' => $keyword1,
				'KEYWORD2_PREF' => $keyword2,
				'KEYWORD3_PREF' => $keyword3,
			]);

		}

		return redirect()->route('panel.preferences', ['lang' => config('app.locale')]);
	}

	public function deletePreferences(HttpRequest $request)
	{
		$form = $request->all();
		$user = session('user');
		$codCli = $user['cod'];

		Web_Preferences::where('COD_CLIWEB_PREF', $codCli)->where('ID_PREF', $form['preference_code'])->delete();

		return redirect()->route('panel.preferences', ['lang' => config('app.locale')]);
	}

	private function checkIsValidPassword(HttpRequest $request)
	{
		$minPasswordLength = 8;
		$rules = [
			'password' => ['required', Password::min($minPasswordLength)->letters()->mixedCase()->numbers()->symbols(), 'max:256'],
		];

		$validator = Validator::make($request->all(), $rules);

		if($validator->fails()){
			//return $validator->errors(); //devuelve un array con los errores producidos
			return [trans(Config::get('app.theme') . '-app.msg_error.invalid_strict_password', ['min' => $minPasswordLength])];
		}

		return null;
	}

	#region Validator for ID Documents

	private function validateNifNieCif($nif)
	{
		if(Request::input('pri_emp') == 'F' && $this->checkValidNIF($nif)){
			return true;
		}
		if(Request::input('pri_emp') == 'F' && $this->checkValidNIE($nif)){
			return true;
		}
		if(Request::input('pri_emp') == 'J' && $this->checkValidCIF($nif)){
			return true;
		}
		if(Request::input('pri_emp') == 'F' && $this->checkValidFormatPassport($nif)){
			return true;
		}

		return false;
	}


	private function checkValidNIF($nif)
	{
		$pattern = "/^[XYZ]?\d{5,8}[A-Z]$/";
        $dni = strtoupper($nif);
        if(preg_match($pattern, $dni))
        {
            $number = substr($dni, 0, -1);
            $number = str_replace('X', 0, $number);
            $number = str_replace('Y', 1, $number);
            $number = str_replace('Z', 2, $number);
            $dni = substr($dni, -1, 1);
            $start = $number % 23;
            $letter = 'TRWAGMYFPDXBNJZSQVHLCKET';
            $letter = substr('TRWAGMYFPDXBNJZSQVHLCKET', $start, 1);
            if($letter != $dni) {
              return false;
            } else {
              return true;
            }
        }else{
            return false;
        }
	}

	private function checkValidNIE($nif){
		if (preg_match('/^[XYZT][0-9][0-9][0-9][0-9][0-9][0-9][0-9][A-Z0-9]/', $nif)) {
		  for ($i = 0; $i < 9; $i ++){
			$num[$i] = substr($nif, $i, 1);
		  }

		  if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $nif), 0, 8) % 23, 1)) {
			return true;
		  } else {
			return false;
		  }
		}
	  }

	  private function checkValidCIF ($cif) {
		$cif_codes = 'JABCDEFGHI';
		#hay que permitir dos tipos de CIF, los que acaban con letra y los que no
		$pattern = "/^[A-Z]{1}\d{5,8}[A-Z]?$/";
		if (!preg_match ($pattern, $cif)) {
		  return false;
		}

		$sum = (string) $this->getCifSum ($cif);
		$n = (10 - substr ($sum, -1)) % 10;

		if (preg_match ('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)) {
		  if (in_array ($cif[0], array ('A', 'B', 'E', 'H'))) {

			// Numerico
			return ($cif[8] == $n);
		  } elseif (in_array ($cif[0], array ('K', 'P', 'Q', 'S'))) {
			// Letras
			return ($cif[8] == $cif_codes[$n]);
		  } else {
			// Alfanumérico
			if (is_numeric ($cif[8])) {
			  return ($cif[8] == $n);
			} else {
			  return ($cif[8] == $cif_codes[$n]);
			}
		  }
		}

		return false;

	  }

	  private function getCifSum($cif) {
		$sum = $cif[2] + $cif[4] + $cif[6];

		for ($i = 1; $i<8; $i += 2) {
		  $tmp = (string) (2 * $cif[$i]);

		  $tmp = $tmp[0] + ((strlen ($tmp) == 2) ?  $tmp[1] : 0);

		  $sum += $tmp;
		}

		return $sum;
	  }

	  private function checkValidFormatPassport($passport)
	  {
		$passport = strtoupper($passport);
		$pattern = "/^[A-Z]{3}[0-9]{6}[A-Z]{1}$/";
		return preg_match($pattern, $passport);
	  }

	#endregion


	/**
	 * Valor por defecto para blockpuj_cli.
	 * En soler utilizamos S por el registro W, en el resto de clientes utilizamos
	 * el que tienen en la base de datos por defecto
	 * @return string
	 */
	private function defaultBidBlocking()
	{
		if(Config::get('app.registro_user_w', false)) {
			return 'S';
		}

		$defaultValue = null;
		try {
			$defaultValueInTable = DB::select("Select DATA_DEFAULT from DBA_TAB_COLUMNS where TABLE_NAME = 'FXCLI' AND COLUMN_NAME = 'BLOCKPUJ_CLI'");
			$defaultValue = trim(str_replace("'", '', $defaultValueInTable[0]->data_default));
		} catch (\Throwable $th) {
			Log::debug('Error al obtener el valor por defecto de blockpuj_cli', ['error' => $th->getMessage()]);
			$defaultValue = 'N';
		}

		return $defaultValue;
	}
}

