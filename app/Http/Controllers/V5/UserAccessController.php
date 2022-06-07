<?php

namespace App\Http\Controllers\V5;

use Redirect;
use Config;
use View;

use App\Http\Controllers\Controller;

# Cargamos librerias
use Tools;
use App\libs\MessageLib;
use App\libs\EmailLib;
use App\libs\FormLib;

# Cargamos los modelos
use App\Models\V5\FxCli;   // Clientes
use App\Models\V5\FxCli2;   // Clientes aux
use App\Models\V5\FxCliWeb;  // Clientes web
use App\Models\V5\FsPaises;  // Paises
use App\Models\V5\FgSg;   // Tipos de via
use App\Models\V5\FsParams;  // Parametros de la empresa
use App\Models\V5\FxClid;   // Direcciones
use App\Models\V5\FxCliCli;  // Relacion con cliente de casa de subastas
use App\Models\V5\FsEmail;   // Modelo de emails a enviar
use App\Models\V5\FxAmbassador; // Modelo de embajadores/afiliados
use App\Models\V5\FxAucHouse;  // Modelo de embajadores

class UserAccessController extends Controller {
    /*     * ************************************************************************************************* */

    //
    //  LOGIN - Función para loguearse en la aplicación como usuario normal.
    //
    //	@request 	-	post con la información de logueo (email, password)
    //
    /*     * ************************************************************************************************* */

    /*
      public function login(Request $request) {

      # Validamos los datos de formulario
      $rules = array(
      'email'    => 'required|email',
      'password' => 'required'
      );

      if (Validator::make(Input::all(), $rules)->fails())	{
      return MessageLib::errorMessage('login_register_failed');
      }


      // Obtenemos información necesaria

      $email    	= Request::input('email');
      $password 	= Request::input('password');
      $ip 		= \Tools::IP();
      $error 		= 0;
      $realUserPassword = 0;

      $FxCliWeb = new FxCliWeb();
      $user = $FxCliWeb->checkUserCliWeb($email,$password, \Config::get("app.emp"), \Config::get("app.gemp"));


      // Realizamos la comprobación del usuario

      if (empty($user)) {

      // Miramos si el usuario si que existe pero la contraseña está mal

      $useraux = $FxCliWeb->emailExistCliWeb($email);

      if (!empty($useraux) && ($useraux->cod_cli == 0 || $useraux->cod_cli == 1)) {
      $error = "login_newsletter_failed";
      }
      elseif (!empty($useraux)) {
      $error = "login_pass_failed";
      $realUserPassword = $useraux->pwdwencrypt_cliweb;
      }
      else {
      // Si no existe el usuario mostramos error
      $error = "login_register_user_failed";
      }

      }
      else {

      // Controlamos si el usuario es baja temporal (W o S), y mostramos mensaje

      if ($user->baja_tmp_cli == "W") {
      $error = "baja_tmp_doble_optin";
      }

      if ($user->baja_tmp_cli == "S") {
      $error = "contact_admin";
      }

      // Controlamos si el tipo de acceso es correcto - Tipacceso (S) = Admin | (N) Normal | (X) Sin acceso | (A) AdminConfig

      if($user->tipacceso_cliweb == 'X') {
      $error = "contact_admin";
      }

      }

      if ($error) {

      // Guardamos el error en el registro de accesos con error

      try {

      DB::table('WEB_LOGIN_LOG_ERROR')
      ->insert(['USRW_WEB_LOGIN_LOG_ERROR' => $email,
      'PWDWENCRYPT_USER_WEB_LOGIN_LOG_ERROR' => $realUserPassword,
      'PWDWENCRYPT_WEB_LOGIN_LOG_ERROR' => trim(md5(Config::get('app.password_MD5').$password)),'DATE_WEB_LOGIN_LOG_ERROR' => date("Y-m-d"), 'EMP_WEB_LOGIN_LOG_ERROR'=> Config::get('app.emp'), 'IP_WEB_LOGIN_LOG_ERROR'=> $ip]
      );
      } catch (\Exception $e) {
      \Log::emergency('Insert WEB_LOGIN_LOG_ERROR');
      }

      return MessageLib::errorMessage($error);

      }


      // Si existe el user, hacemos las funciones necesarias de login.

      FxCliWeb::loginUserCliWeb($user->cod_cli);

      return MessageLib::successMessage();


      }
     */















    /*     * ************************************************************************************************* */
    //
    //  REGISTER - Función para crear el formulario de registro
    //
    //	@gemp 	-	Codigo de empresa (opcional) - Parámetros para afiliados
    //	@cod_cliweb - Codigo de cliente (opcional) - Parámetros para afiliados
    //	@type - Via de entrada (email, ...) (opcional) - Parámetros para afiliados
    //
    /*     * ************************************************************************************************* */


    public function register($gemp = null, $cod_cliweb = null, $type = null) {

        if (!\Session::has("user") && empty(Config::get('app.registration_disabled'))) {

            // Miramos si se ha pasado por get algun codigo de ambassador

            if ($gemp && $cod_cliweb && $type) {
                $ambassador = FxCliWeb::where("gemp_cliweb", $gemp)
                        ->where("cod_cliweb", $cod_cliweb)
                        ->first();
            }


            // Obtenemos la información necesaria para construir el formulario

            $data = array();
            $countries = array();
            $vias = array();
            //ordeno alfabéticamente
            $countries_aux = FsPaises::selectBasicPaises()->JoinLangPaises()->orderby("des_paises")->get();
            $country_selected = (\Config::get('app.locale') == 'es') ? 'ES' : '';
            $via_aux = FgSg::selectBasicSg()->JoinLangSg()->get();
            foreach ($countries_aux as $item) {
                $countries[$item->cod_paises] = $item->des_paises;
            }
            foreach ($via_aux as $item) {
                $vias[$item->cod_sg] = $item->des_sg;
            }

            // Construimos los campos de formulario

            $data['formulario'] = new \stdClass();

            $data['formulario']->usuario = FormLib::Text("usuario", 0, "", 0);
            $data['formulario']->last_name = FormLib::Text("last_name", 0, "", 0);
            $data['formulario']->rsoc_cli = FormLib::Text("rsoc_cli", 0, "", 0);
            $data['formulario']->contact = FormLib::Text("contact", 0, "", 0);

            $data['formulario']->sexo = FormLib::Select("sexo", 0, "", array("H" => trans(\Config::get('app.theme') . '-app.login_register.hombre'), "M" => trans(\Config::get('app.theme') . '-app.login_register.mujer')), 0, trans(\Config::get('app.theme') . '-app.login_register.genre'));
            $data['formulario']->telefono = FormLib::Text("telefono", 1, "", 0);
            $data['formulario']->cif = FormLib::Text("nif", 0, "", 0);
            $data['formulario']->fecha_nacimiento = FormLib::Date("date", 0, "", 0, trans(\Config::get('app.theme') . '-app.user_panel.date_birthday'));


            $data['formulario']->vias = FormLib::Select("codigoVia", 1, "", $vias, 0, trans(\Config::get('app.theme') . '-app.login_register.via'));
            $data['formulario']->pais = FormLib::Select("pais", 1, $country_selected, $countries, 0, trans(\Config::get('app.theme') . '-app.login_register.pais'));
            $data['formulario']->direccion = FormLib::Text("direccion", 1, "", 0);
            $data['formulario']->cpostal = FormLib::Text("cpostal", 1, "", 0);
            $data['formulario']->poblacion = FormLib::Text("poblacion", 1, "", 0);
            $data['formulario']->provincia = FormLib::Text("provincia", 1, "", 0);


            $data['formulario']->email = FormLib::Email("email", 1, "", 0);
            $data['formulario']->confirm_email = FormLib::Email("confirm_email", 1, "", "check='email__1__email'");
            $data['formulario']->password = FormLib::Password("password", 1, "", 0);
            $data['formulario']->confirm_password = FormLib::Password("confirm_password", 1, "", "check='password__1__password'");

            $data['formulario']->language = FormLib::Hidden("language", 1, strtoupper(\App::getLocale()));
            $data['formulario']->condiciones = FormLib::Bool("condiciones", 1, 0, "on");
            $data['formulario']->condicionesSubalia = FormLib::Bool("condicionesSubalia", 0, 0, "on");
            $data['formulario']->newsletter = FormLib::Bool("newsletter", 0, 1, "on");


            // Preparamos para el futuro, cuando nos pidan que podamos poner la dirección de envío

            if (empty(\Config::get('app.delivery_address')) || !\Config::get('app.delivery_address')) {

                $data['formulario']->clid = FormLib::Hidden("clid", 1, 1);
                $data['formulario']->clid_pais = FormLib::Hidden("select__0__clid_pais", 1);
                $data['formulario']->clid_cpostal = FormLib::Hidden("texto__0__clid_cpostal", 1);
                $data['formulario']->clid_poblacion = FormLib::Hidden("texto__0__clid_poblacion", 1);
                $data['formulario']->clid_provincia = FormLib::Hidden("texto__0__clid_provincia", 1);
                $data['formulario']->clid_codigoVia = FormLib::Hidden("select__0__clid_codigoVia", 1);
                $data['formulario']->clid_direccion = FormLib::Hidden("texto__0__clid_direccion", 1);

            } else {

                $data['formulario']->clid = FormLib::Hidden("clid", 1, 1);
                $data['formulario']->clid_pais = FormLib::Select("clid_pais", 0, $country_selected, $countries, 0, trans(\Config::get('app.theme') . '-app.login_register.pais'));
                $data['formulario']->clid_cpostal = FormLib::Text("clid_cpostal", 0, "", 0);
                $data['formulario']->clid_poblacion = FormLib::Text("clid_poblacion", 0, "", 0);
                $data['formulario']->clid_provincia = FormLib::Text("clid_provincia", 0, "", 0);
                $data['formulario']->clid_codigoVia = FormLib::Select("clid_codigoVia", 0, "", $vias, 0, trans(\Config::get('app.theme') . '-app.login_register.via'));
                $data['formulario']->clid_direccion = FormLib::Text("clid_direccion", 0, "", 0);
            }



            $data['formulario']->submit = FormLib::Submit("Finalizar", "registerForm");

            return View::make('front::pages.V5.register')->with('data', $data);
        } else {

            # Si ya hay sesión, redirigimos a la home ( No se debería poder acceder aquí si ya estas logueado, pero por si acaso)
            return Redirect::to('/');
        }
    }

    /*     * ************************************************************************************************************\
      #
      #	REGISTER_RUN - 	Función que realiza propiamente la accion de registrar al usuario que le pasamos por post.
      # 					La llamada a esta función ha de ser viaajax
      #
      #	@request 	-	post con la información del user
      #
      #	Vias de entrada:
      #		- Pagina de registro 	-	UserAccessController@register
      #		- Coregistro de usuario desde la web de casa de subastas
      #
      \*************************************************************************************************************** */

    /*
      public function registerRun(Request $request) {


      $data = Input::all();

      //	VALIDACIONES

      # Validamos los datos del formulario

      ## TODO - Poner todas las validaciones por php con el validador de laravel

      $rules = array(
      'email' => "required|email",
      'password' => "required",
      'confirm_password' => "required|same:password",
      'confirm_email' => "required|same:email",
      'condiciones' => "accepted"

      );
      $messages = [
      'required' => trans(\Config::get('app.theme').'-app.msg_error.form_required'),
      'accepted' => trans(\Config::get('app.theme').'-app.msg_error.form_accepted'),
      'alpha_num' => trans(\Config::get('app.theme').'-app.msg_error.form_alpha_num'),
      'email' => trans(\Config::get('app.theme').'-app.msg_error.form_email'),
      'date' => trans(\Config::get('app.theme').'-app.msg_error.form_date'),
      ];


      $validator = Validator::make($data, $rules, $messages);
      if ($validator->fails()) {
      $ret = array();
      $errors = $validator->errors();

      foreach($rules as $k => $item) {
      if ($errors->has($k)) {

      if (!isset($ret[$k]))
      $ret[$k] = "";
      $ret[$k] .= implode(".",$errors->get($k));
      }
      }
      Log::info('REGISTRO_ERRONEO : Errores de validación de formulario. '.print_r($ret,1), array());
      return MessageLib::errorMessage('error_register',array("errors" => $ret));
      }


      # Controlamos si existe el usuario válido mediante el nif de FxCli y registro asociado de FxCliWeb.
      # El mismo clientCode nos servirá para determinar si existe o no.

      if ($data['nif']) {
      $auxUser = FxCli::MyGempCli()->where("CIF_CLI",$data['nif'])->first();

      if ($auxUser) {
      Log::info('REGISTRO_ERRONEO : Ya existe usuario con el nif '.$data['nif'].' y con CLIWEB creado', array());
      return MessageLib::errorMessage('error_exist_dni');
      }
      }

      # En este punto, si hemos llegado significa que el cliente no existe.
      # Si el clientCode es nulo significa que o no hay $data['nif'], o no hay auxUser, por lo que no existe el usuario en FxCli


      # Determinamos si el usuario existe en FxCliWeb

      $hasCliweb = FxCliWeb::where("LOWER(USRW_CLIWEB)",strtolower($data['email']))->first();

      # Si existe el FxCliWeb y el COD_CLIWEB no es 0 o -1, significa que ya hay FxCliWeb. Si fuera 0 sería el caso de newsletter.
      if ($hasCliweb && $hasCliweb->cod_cliweb != -1 && $hasCliweb->cod_cliweb != 0) {

      Log::info('REGISTRO_ERRONEO : Ya existe usuario con el email '.$data['email'], array());
      return MessageLib::errorMessage('email_already_exists');

      }

      # Comprobamos el recaptcha - Hemos de ponerlo como último punto de comprobación porque a veces,
      # si ya hemos enviado a validar el recaptcha el sr. google se lo toma como que reenviamos la misma petición
      # Así evitamos errores y solo dará error cuando realmente se haga algo extraño

      $jsonResponse = \Tools::validateRecaptcha(\Config::get('app.codRecaptchaEmail'));

      if (empty($jsonResponse) || $jsonResponse->success !== true) {

      return MessageLib::errorMessage("recaptcha_incorrect");

      }


      # FORMATEAMOS LA INFORMACIÓN Y OBTENEMOS LA INFORMACIÓN RESTANTE

      if ($data['contact']) {
      $nombre = mb_strtoupper($data['contact']);
      }
      else {
      $nombre = mb_strtoupper($data["last_name"].", ".$data["usuario"]);
      }

      if (empty($data['rsoc_cli'])) {
      $data['rsoc_cli'] = $nombre;
      }

      $lang = $data['language'];
      if (!$lang) {
      $lang = strtoupper(Config::get('app.locale'));
      }







      # Si llegados a este punto no tenemos un clientCode significa que no hemos encontrado un usuario en FxCli, por lo que lo generaremos

      $clientCode = FxCli::select("NVL(MAX(CAST(COD_CLI AS Int))+1,1) AS numero")
      ->whereNull("TRANSLATE(cod_cli, 'T 0123456789', 'T')")
      ->whereNotNull("cod_cli")
      ->where("FxCli.GEMP_CLI",Config::get('app.gemp'))
      ->first()['nvl(max(cast(cod_cliasint))+1,1)']; // FIXME - No se porque no pilla el AS numero

      $longitud = FsParams::select("tcli_params")->MyEmpParams(\Config::get('app.emp'))->first()->tcli_params;
      $clientCode = str_pad($clientCode, $longitud, 0, STR_PAD_LEFT);


      $hasDoubleOption = FsEmail::where("EMP_EMAIL",Config::get('app.emp'))->where("COD_EMAIL","DOUBLE_OPT_IN")->where("ENABLED_EMAIL",1)->first();

      $info_cli = [
      'GEMP_CLI' => Config::get('app.gemp'),
      'COD_CLI' => $clientCode,
      'COD_C_CLI' => 4300,
      'TIPO_CLI' => 'W',
      'RSOC_CLI' => $data['rsoc_cli'],
      'NOM_CLI' => $nombre,
      "BAJA_TMP_CLI" => $hasDoubleOption?'W':'N',
      'EMAIL_CLI' => mb_strtoupper($data["email"]),
      'F_ALTA_CLI' => date("Y-m-d H:i:s"),
      'FISJUR_CLI' => $data["pri_emp"],
      'IDIOMA_CLI' => $lang
      ];

      // Recorremos el array y ponemos en mayusculas por compatibilidad con el ERP

      FxCli::insert($info_cli);


      # Incluimos información en FxCli2, por temas de compatibilidad con el ERP

      FxCli2::insert([
      "GEMP_CLI2" => Config::get('app.gemp'),
      "COD_CLI2" => $clientCode,
      "ENVCAT_CLI2" => 'N'
      ]);


      # Insertamos como dirección de envio la misma que nos ha facilitado (por si acaso)

      FxClid::insert([
      "GEMP_CLID" => Config::get('app.gemp'),
      "CLI_CLID" => $clientCode,
      "CODD_CLID" => 'W1',
      "NOMD_CLID" => $nombre,
      "TIPO_CLID" => 'E'
      ]);


      # El clientCode, si no existia ha sido generado al generar el FxCli, si ya existia FxCli, hemos obtenido el codigo del mismo

      # Damos de alta en FxCliWeb

      $info_cliweb = [
      'GEMP_CLIWEB' => Config::get('app.gemp'),
      'COD_CLIWEB' => $clientCode,
      'USRW_CLIWEB' => $data['email'],
      'PWDWENCRYPT_CLIWEB' => md5(Config::get('app.password_MD5').$data['password']),
      'EMP_CLIWEB' => Config::get('app.emp'),
      'TIPACCESO_CLIWEB' => 'N',
      'TIPO_CLIWEB' => 'C',
      'NOM_CLIWEB' => $nombre,
      'EMAIL_CLIWEB' => $data['email'],
      'FECALTA_CLIWEB' => date("Y-m-d H:i:s"),
      'IDIOMA_CLIWEB' => $lang
      ];


      # Si está dado de alta como newsletter, actualizamos la info. Sino damos de alta. Comprobamos que el cod_cli sea 0 o -1

      if ($hasCliweb) {
      FxCliWeb::where('LOWER(USRW_CLIWEB)',$data['email'])->where("EMP_CLIWEB",Config::get('app.emp'))->where("GEMP_CLIWEB",Config::get('app.gemp'))->update($info_cliweb);
      }
      else {
      FxCliWeb::insert($info_cliweb);
      }



      # Envio de email notificando el nuevo usuario web a los admins si así se ha definido en el config

      $hasAdminMail = FsEmail::where("EMP_EMAIL",Config::get('app.emp'))->where("COD_EMAIL","NEW_USER_ADMIN")->where("ENABLED_EMAIL",1)->first();
      if ($hasAdminMail) {
      $email = new EmailLib('NEW_USER_ADMIN');
      if (!empty($email->email)) {
      $email->setUserByCod($clientCode,true);
      $email->setTo(Config::get('app.admin_email'));
      $email->send_email();
      }
      }


      # Forzamos el idioma por si se ha seleccionado un idioma diferente al actual
      \App::setLocale($lang);

      # Loguear al usuario una vez se ha registrado.

      $FxCliWeb = new FxCliWeb();
      $user = $FxCliWeb->checkUserCliWeb($data['email'],$data['password'], \Config::get("app.emp"), \Config::get("app.gemp"));
      //if ($user->baja_tmp_cli == 'N') {
      FxCliWeb::loginUserCliWeb($user->cod_cli);
      //}


      return MessageLib::successMessage("",array("url" => '/'.strtolower($lang).'/usuario-registrado'));

      }

     */





















    /*     * ************************************************************************************************************\
      #
      #	SUCCESSREGISTERED - 	Función que visualiza el OK del registro.
      #
      \************************************************************************************************************* */
    /*
      public function  SuccessRegistered(){

      return (\View::make('front::pages.success_registered'));
      }
     */











    /*     * ************************************************************************************************************\
      #
      #	LOGOUT - 	Función que realiza el logout del usuario
      #
      \************************************************************************************************************* */
    /*
      public function logout() {

      # Eliminamos la sesión y redirigimos a la home
      Session::flush();
      Cookie::queue('user',null);
      return Redirect::to("/");
      }
     */
}
