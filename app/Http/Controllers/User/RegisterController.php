<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\SubaliaController;
use App\Http\Controllers\UserController;
use App\libs\FormLib;
use App\Models\V5\FsIdioma;
use App\Models\V5\FsPaises;
use App\Models\V5\FxCliWeb;
use App\Models\V5\SubAuchouse;
use App\Services\User\UserAddressService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{

	/****************************************************************************************************/
	//
	//  REGISTER - Función para crear el formulario de registro
	//
	//	@gemp 	-	Codigo de empresa (opcional) - Parámetros para afiliados
	//	@cod_cliweb - Codigo de cliente (opcional) - Parámetros para afiliados
	//	@type - Via de entrada (email, ...) (opcional) - Parámetros para afiliados
	//
	/****************************************************************************************************/


	public function index($gemp = null, $cod_cliweb = null, $type = null,  $postUser = null, $datosEnviar = null)
	{

		if (!Session::has("user") || Config::get('app.no_redirect_register')) {

			// Miramos si se ha pasado por get algun codigo de ambassador

			if ($gemp && $cod_cliweb && $type) {
				$ambassador = FxCliWeb::where("gemp_cliweb", $gemp)
					->where("cod_cliweb", $cod_cliweb)
					->first();
			}


			// Obtenemos la información necesaria para construir el formulario

			$data = array();
			$countries = array();
			$prefix = array();
			$divisas = array();
			$idiomas = array();

			$countries_aux = FsPaises::JoinLangPaises()->addSelect('preftel_paises')->orderby("des_paises")->get();

			if (!empty(Config::get('app.defaultCountry'))) {
				$country_selected = Config::get('app.defaultCountry');
			} else {
				$country_selected = (Config::get('app.locale') == 'es') ? 'ES' : '';
			}

			foreach ($countries_aux as $item) {
				$countries[$item->cod_paises] = $item->des_paises;
				$prefix[$item->cod_paises] = str_pad($item->preftel_paises, 4, 0, STR_PAD_LEFT);
			}

			$streetsTypes = (new UserAddressService())->getPluckStreetTypes();

			$divisas_aux = DB::table('FSDIV')
				->select('cod_div', 'des_div', 'impd_div', 'symbolhtml_div')
				->get();

			foreach ($divisas_aux as $key => $value) {
				$divisas[$value->cod_div] = $value->cod_div;
			}

			$idiomas = FsIdioma::getArrayValues();
			if (empty($idiomas)) {
				foreach (Config::get('app.locales') as $key => $value) {
					$idiomas[strtoupper($key)] = $value;
				}
			}

			// Construimos los campos de formulario

			$data['jsitem'] = new \stdClass();
			$data['jsitem']->prefix = $prefix;

			$data['formulario'] = new \stdClass();

			if (empty($postUser)) {

				$data['formulario']->usuario = FormLib::Text("usuario", 0, "", 0);
				$data['formulario']->last_name = FormLib::Text("last_name", 0, "", 0);
				$data['formulario']->rsoc_cli = FormLib::Text("rsoc_cli", 0, "", "maxlength='60'");
				$data['formulario']->contact = FormLib::Text("contact", 0, "", 0);


				$data['formulario']->prefix = FormLib::Text("preftel_cli", 1, '', 'maxlength="4"');
				$data['formulario']->telefono = FormLib::Text("telefono", 1, "", 0);
				$data['formulario']->movil = FormLib::Text("movil", 0, "", 0);
				$data['formulario']->cif = FormLib::Nif("nif", 0, "", 0);
				$data['formulario']->fecha_nacimiento = FormLib::Date("date", 1, "", 0);

				$data['formulario']->vias = FormLib::Select("codigoVia", 1, "", $streetsTypes, 0, trans(Config::get('app.theme') . '-app.login_register.via'));
				$data['formulario']->pais = FormLib::Select("pais", 1, $country_selected, $countries, 0, trans(Config::get('app.theme') . '-app.login_register.pais'));
				$data['formulario']->direccion = FormLib::Text("direccion", 1, "", 0);
				$data['formulario']->cpostal = FormLib::Text("cpostal", 1, "", 'maxlength="10"');
				$data['formulario']->poblacion = FormLib::Text("poblacion", 1, "", 0);
				$data['formulario']->provincia = FormLib::Text("provincia", 1, "", 0);

				$data['formulario']->email = FormLib::Email("email", 1, "", 0);
				$data['formulario']->confirm_email = FormLib::Email("confirm_email", 1, "", "check='email__1__email'");
				$data['formulario']->password = FormLib::Password("password", 1, "", 'maxlength="20"');
				$data['formulario']->confirm_password = FormLib::Password("confirm_password", 1, "", "check='password__1__password'");

				$data['formulario']->language = FormLib::Hidden("language", 1, strtoupper(App::getLocale()));
				$data['formulario']->condiciones = FormLib::Bool("condiciones", 1, 0, "on");
				$data['formulario']->condicionesSubalia = FormLib::Bool("condicionesSubalia", 0, 0, "on");
				$data['formulario']->newsletter = FormLib::Bool("newsletter", 0, 0, "on");
				$data['formulario']->condiciones2 = FormLib::Bool("condiciones2", 0, 0, "on");


				// Dirección de envío

				if (empty(Config::get('app.delivery_address')) || !Config::get('app.delivery_address')) {

					$data['formulario']->clid = FormLib::Hidden("clid", 1, 1);
					$data['formulario']->clid_pais = FormLib::Hidden("select__0__clid_pais", 1);
					$data['formulario']->clid_cpostal = FormLib::Hidden("texto__0__clid_cpostal", 1);
					$data['formulario']->clid_poblacion = FormLib::Hidden("texto__0__clid_poblacion", 1);
					$data['formulario']->clid_provincia = FormLib::Hidden("texto__0__clid_provincia", 1);
					$data['formulario']->clid_codigoVia = FormLib::Hidden("select__0__clid_codigoVia", 1);
					$data['formulario']->clid_direccion = FormLib::Hidden("texto__0__clid_direccion", 1);
				} else {

					$data['formulario']->clid = FormLib::Hidden("clid", 1, 1);
					$data['formulario']->clid_pais = FormLib::Select("clid_pais", 0, $country_selected, $countries, 0, trans(Config::get('app.theme') . '-app.login_register.pais'));
					$data['formulario']->clid_cpostal = FormLib::Text("clid_cpostal", 0, "", 0);
					$data['formulario']->clid_poblacion = FormLib::Text("clid_poblacion", 0, "", 0);
					$data['formulario']->clid_provincia = FormLib::Text("clid_provincia", 0, "", 0);
					$data['formulario']->clid_codigoVia = FormLib::Select("clid_codigoVia", 0, "", $streetsTypes, 0, trans(Config::get('app.theme') . '-app.login_register.via'));
					$data['formulario']->clid_direccion = FormLib::Text("clid_direccion", 0, "", 0);
				}
			} else {

				$data['postUser'] = $postUser;

				if ($postUser->fisjur_cli == "F") {
					$data['formulario']->usuario = FormLib::Text("usuario", 0, "$postUser->nombre1 $postUser->nombre2", 0);
					$data['formulario']->last_name = FormLib::Text("last_name", 0, $postUser->nombre2, 0);
					$data['formulario']->rsoc_cli = FormLib::Text("rsoc_cli", 0, "", "maxlength='60'");
					$data['formulario']->contact = FormLib::Text("contact", 0, "", 0);
				} else {
					$data['formulario']->usuario = FormLib::Text("usuario", 0, "", 0);
					$data['formulario']->last_name = FormLib::Text("last_name", 0, "", 0);
					$data['formulario']->rsoc_cli = FormLib::Text("rsoc_cli", 0, $postUser->nombre1, "maxlength='60'");
					$data['formulario']->contact = FormLib::Text("contact", 0, $postUser->nom_cli, 0);
				}

				$data['formulario']->prefix = FormLib::Text("preftel_cli", 1, '', 'maxlength="4"');
				$data['formulario']->telefono = FormLib::Text("telefono", 1, $postUser->tel1_cli, 0);
				$data['formulario']->movil = FormLib::Text("movil", 0, "", 0);
				$data['formulario']->cif = FormLib::Text("nif", 0, $postUser->cif_cli, 0);
				$data['formulario']->fecha_nacimiento = FormLib::Date("date", 1, $postUser->fecnac_cli, 0);

				$data['formulario']->vias = FormLib::Select("codigoVia", 1, $postUser->sg_cli, $streetsTypes, 0, trans(Config::get('app.theme') . '-app.login_register.via'));
				$data['formulario']->pais = FormLib::Select("pais", 1, $postUser->codpais_cli, $countries, 0, trans(Config::get('app.theme') . '-app.login_register.pais'));
				$data['formulario']->direccion = FormLib::Text("direccion", 1, $postUser->dir_cli, 0);
				$data['formulario']->cpostal = FormLib::Text("cpostal", 1, $postUser->cp_cli, 'maxlength="10"');
				$data['formulario']->poblacion = FormLib::Text("poblacion", 1, $postUser->pob_cli, 0);
				$data['formulario']->provincia = FormLib::Text("provincia", 1, $postUser->pro_cli, 0);

				$data['formulario']->email = FormLib::Email("email", 1, $postUser->email_cli, 0);
				$data['formulario']->confirm_email = FormLib::Email("confirm_email", 1, $postUser->email_cli, "check='email__1__email'");
				$data['formulario']->password = FormLib::Password("password", 1, "", 'maxlength="20"');
				$data['formulario']->confirm_password = FormLib::Password("confirm_password", 1, "", "check='password__1__password'");

				$data['formulario']->language = FormLib::Hidden("language", 1, strtoupper(App::getLocale()));
				$data['formulario']->condiciones = FormLib::Bool("condiciones", 1, 0, "on");
				$data['formulario']->condicionesSubalia = FormLib::Bool("condicionesSubalia", 0, 0, "on");
				$data['formulario']->newsletter = FormLib::Bool("newsletter", 0, 0, "on");
				$data['formulario']->condiciones2 = FormLib::Bool("condiciones2", 0, 0, "on");


				$data['formulario']->subalia = FormLib::Hidden("subalia", 0, "subalia");
				$data['formulario']->info = FormLib::Hidden("info", 0, $datosEnviar);


				// Dirección de envío

				if (empty(Config::get('app.delivery_address')) || !Config::get('app.delivery_address')) {

					$data['formulario']->clid = FormLib::Hidden("clid", 1, 1);
					$data['formulario']->clid_pais = FormLib::Hidden("select__0__clid_pais", 1);
					$data['formulario']->clid_cpostal = FormLib::Hidden("texto__0__clid_cpostal", 1);
					$data['formulario']->clid_poblacion = FormLib::Hidden("texto__0__clid_poblacion", 1);
					$data['formulario']->clid_provincia = FormLib::Hidden("texto__0__clid_provincia", 1);
					$data['formulario']->clid_codigoVia = FormLib::Hidden("select__0__clid_codigoVia", 1);
					$data['formulario']->clid_direccion = FormLib::Hidden("texto__0__clid_direccion", 1);
				} else {

					$data['formulario']->clid = FormLib::Hidden("clid", 1, 1);
					$data['formulario']->clid_pais = FormLib::Select("clid_pais", 0, $country_selected, $countries, 0, trans(Config::get('app.theme') . '-app.login_register.pais'));
					$data['formulario']->clid_cpostal = FormLib::Text("clid_cpostal", 0, "", 0);
					$data['formulario']->clid_poblacion = FormLib::Text("clid_poblacion", 0, "", 0);
					$data['formulario']->clid_provincia = FormLib::Text("clid_provincia", 0, "", 0);
					$data['formulario']->clid_codigoVia = FormLib::Select("clid_codigoVia", 0, "", $streetsTypes, 0, trans(Config::get('app.theme') . '-app.login_register.via'));
					$data['formulario']->clid_direccion = FormLib::Text("clid_direccion", 0, "", 0);
				}
			}

			//Inputs compartidos sea por donse sea que se acceda a la blade de registro
			$data['formulario']->language = FormLib::Select("language", 0, "ES", $idiomas);
			$data['formulario']->divisa = FormLib::Select("divisa", 0, "EUR", $divisas);
			$data['formulario']->obscli = FormLib::Textarea("obscli", 0, "", 'maxlength="4000"');

			$data['formulario']->sexo = FormLib::Select("sexo", 0, "", array("H" => trans(Config::get('app.theme') . '-app.login_register.hombre'), "M" => trans(Config::get('app.theme') . '-app.login_register.mujer')), 0, trans(Config::get('app.theme') . '-app.login_register.genre'));

			if (!empty(Config::get('app.ps_activate')) && !empty(request('back'))) {
				$data['formulario']->back = request('back');
			}

			$data['formulario']->submit = FormLib::Submit("Finalizar", "registerForm");

			if (Config::get('app.seo_in_register', 0)) {
				$data['seo'] = new \stdClass();
				$data['seo']->meta_title = trans(Config::get('app.theme') . '-app.metas.title_register');
				$data['seo']->meta_description = trans(Config::get('app.theme') . '-app.metas.description_register');
			}

			$data['backUrl'] = session('backUrl') ?? url()->previous();

			return view('pages.user.register', $data);
		} else {

			if (!empty($postUser)) {
				$subalia = new SubaliaController();
				return $subalia->validarSubaliaIndex();
				//Devera redirigir por metodo post
				//return Redirect::to('/' . Config::get('app.locale') . '/login/subalia');
			}
			# Si ya hay sesión, redirigimos a la home ( No se debería poder acceder aquí si ya estas logueado, pero por si acaso)

			return Redirect::to('/');
		}
	}

	public function registerComplete()
	{

		$postUser = new \stdClass();
		$fields = array("nombre1", "nombre2", "email_cli", "nom_cli", "tel1_cli", "cif_cli", "codpais_cli", "cp_cli", "pob_cli", "pro_cli", "dir_cli", "sg_cli", "fecnac_cli", "sexo_cli", "fisjur_cli");

		$method = 'AES-256-ECB';

		$info = request('info');
		$usertmp = new \stdClass();

		$codAuchouse = Config::get("app.subalia_cli");

		if (!empty($codAuchouse) && !empty($info)) {

			$key = SubAuchouse::select('COD_AUCHOUSE', 'HASH')
				->where('CLI_AUCHOUSE', '=', $codAuchouse)
				->where('EMP_ORIGIN_AUCHOUSE', '=', Config::get('app.emp'))
				->get();



			if (!empty($key)) {
				$info_decript = openssl_decrypt(base64_decode($info), $method, $key[0]->hash, OPENSSL_RAW_DATA);

				$usertmp = json_decode($info_decript);
			}
		}

		$postUser = new \stdClass();


		foreach ($fields as $field) {
			$postUser->{$field} = !empty($usertmp->{$field}) ? $usertmp->{$field} : "";
		}

		//redirige al antiguo formulario de registro
		if (!empty(Config::get('app.old_register'))) {
			$userControler = new UserController();
			return $userControler->login($postUser, $info);
		}

		//redirige al nuevo formulario de registro
		return $this->index(null, null, null, $postUser, $info);
	}
}
