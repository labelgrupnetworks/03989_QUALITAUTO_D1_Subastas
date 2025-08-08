<?php

namespace App\Http\Controllers\User;

use App\DataTransferObjects\User\SubaliaPostUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\SubaliaController;
use App\Http\Controllers\UserController;
use App\libs\FormLib;
use App\Models\V5\FsDiv;
use App\Models\V5\FsIdioma;
use App\Models\V5\FsPaises;
use App\Models\V5\SubAuchouse;
use App\Services\User\UserAddressService;
use App\Support\Localization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
	public function index(?SubaliaPostUserDTO $postUser = null, $datosEnviar = null)
	{
		//Si ya hay un usuario en sesión redirigimos a home
		if (Session::has("user") && !Config::get('app.no_redirect_register')) {
			if (empty($postUser)) {
				return Redirect::to('/');
			}
			$subalia = new SubaliaController();
			return $subalia->validarSubaliaIndex();
		}

		$fromSubalia = isset($postUser);
		$postUser = $postUser ? $postUser : SubaliaPostUserDTO::fromArray([]);

		$data = [];

		$countries = [];
		$prefix = [];
		$countries_aux = FsPaises::JoinLangPaises()->addSelect('preftel_paises')->orderby("des_paises")->get();

		foreach ($countries_aux as $item) {
			$countries[$item->cod_paises] = $item->des_paises;
			$prefix[$item->cod_paises] = str_pad($item->preftel_paises, 4, 0, STR_PAD_LEFT);
		}

		$streetsTypes = (new UserAddressService())->getPluckStreetTypes();
		$divisas = FsDiv::pluck('cod_div', 'cod_div')->all();

		$idiomas = FsIdioma::getArrayValues();
		if (empty($idiomas)) {
			foreach (Config::get('app.locales') as $key => $value) {
				$idiomas[strtoupper($key)] = $value;
			}
		}

		$data['jsitem'] = new \stdClass();
		$data['jsitem']->prefix = $prefix;

		$formulario = [
			'usuario' => FormLib::Text("usuario", 0, "", 0),
			'last_name' => FormLib::Text("last_name", 0, "", 0),
			'rsoc_cli' => FormLib::Text("rsoc_cli", 0, $postUser->nombre1, "maxlength='60'"),
			'contact' => FormLib::Text("contact", 0, $postUser->nom_cli, 0),
			'prefix' => FormLib::Text("preftel_cli", 1, '', 'maxlength="4"'),
			'telefono' => FormLib::Text("telefono", 1, $postUser->tel1_cli, 0),
			'movil' => FormLib::Text("movil", 0, "", 0),
			'cif' => FormLib::Text("nif", 0, $postUser->cif_cli, 0),
			'fecha_nacimiento' => FormLib::Date("date", 1, $postUser->fecnac_cli, 0),
			'vias' => FormLib::Select("codigoVia", 1, $postUser->sg_cli, $streetsTypes, 0, trans('web.login_register.via')),
			'pais' => FormLib::Select("pais", 1, $postUser->codpais_cli, $countries, 0, trans('web.login_register.pais')),
			'direccion' => FormLib::Text("direccion", 1, $postUser->dir_cli, 0),
			'cpostal' => FormLib::Text("cpostal", 1, $postUser->cp_cli, 'maxlength="10"'),
			'poblacion' => FormLib::Text("poblacion", 1, $postUser->pob_cli, 0),
			'provincia' => FormLib::Text("provincia", 1, $postUser->pro_cli, 0),
			'clid' => FormLib::Hidden("clid", 1, 1),
			'clid_pais' => FormLib::Hidden("select__0__clid_pais", 1),
			'clid_cpostal' => FormLib::Hidden("texto__0__clid_cpostal", 1),
			'clid_poblacion' => FormLib::Hidden("texto__0__clid_poblacion", 1),
			'clid_provincia' => FormLib::Hidden("texto__0__clid_provincia", 1),
			'clid_codigoVia' => FormLib::Hidden("select__0__clid_codigoVia", 1),
			'clid_direccion' => FormLib::Hidden("texto__0__clid_direccion", 1),
			'email' => FormLib::Email("email", 1, $postUser->email_cli, 0),
			'confirm_email' => FormLib::Email("confirm_email", 1, $postUser->email_cli, "check='email__1__email'"),
			'password' => FormLib::Password("password", 1, "", 'maxlength="20"'),
			'confirm_password' => FormLib::Password("confirm_password", 1, "", "check='password__1__password' maxlength='20'"),
			'language' => FormLib::Hidden("language", 1, strtoupper(App::getLocale())),
			'condiciones' => FormLib::Bool("condiciones", 1, 0, "on"),
			'condicionesSubalia' => FormLib::Bool("condicionesSubalia", 0, 0, "on"),
			'newsletter' => FormLib::Bool("newsletter", 0, 0, "on"),
			'condiciones2' => FormLib::Bool("condiciones2", 0, 0, "on"),
			'language' => FormLib::Select("language", 0, "ES", $idiomas),
			'divisa' => FormLib::Select("divisa", 0, "EUR", $divisas),
			'obscli' => FormLib::Textarea("obscli", 0, "", 'maxlength="4000"'),
			'sexo' => FormLib::Select("sexo", 0, "", array("H" => trans('web.login_register.hombre'), "M" => trans('web.login_register.mujer')), 0, trans('web.login_register.genre')),
			'submit' => FormLib::Submit("Finalizar", "registerForm")
		];

		if ($postUser->fisjur_cli == "F") {
			$formulario = array_merge($formulario, [
				'usuario' => FormLib::Text("usuario", 0, "$postUser->nombre1 $postUser->nombre2", 0),
				'last_name' => FormLib::Text("last_name", 0, $postUser->nombre2, 0),
				'rsoc_cli' => FormLib::Text("rsoc_cli", 0, "", "maxlength='60'"),
				'contact' => FormLib::Text("contact", 0, "", 0)
			]);
		}

		if (Config::get('app.delivery_address')) {
			$country_selected = Config::get('app.defaultCountry', Localization::isDefaultLocale() ? 'ES' : '');

			$formulario = array_merge($formulario, [
				'clid' => FormLib::Hidden("clid", 1, 1),
				'clid_pais' => FormLib::Select("clid_pais", 0, $country_selected, $countries, 0, trans('web.login_register.pais')),
				'clid_cpostal' => FormLib::Text("clid_cpostal", 0, "", 0),
				'clid_poblacion' => FormLib::Text("clid_poblacion", 0, "", 0),
				'clid_provincia' => FormLib::Text("clid_provincia", 0, "", 0),
				'clid_codigoVia' => FormLib::Select("clid_codigoVia", 0, "", $streetsTypes, 0, trans('web.login_register.via')),
				'clid_direccion' => FormLib::Text("clid_direccion", 0, "", 0)
			]);
		}

		if ($fromSubalia) {
			$formulario = array_merge($formulario, [
				'subalia' => FormLib::Hidden("subalia", 0, "subalia"),
				'info' => FormLib::Hidden("info", 0, $datosEnviar)
			]);

			$data['postUser'] = $postUser;
		}

		if (!empty(Config::get('app.ps_activate')) && !empty(request('back'))) {
			$formulario['back'] = request('back');
		}

		$data['formulario'] = (object) $formulario;

		if (Config::get('app.seo_in_register', 0)) {
			$data['seo'] = new \stdClass();
			$data['seo']->meta_title = trans('web.metas.title_register');
			$data['seo']->meta_description = trans('web.metas.description_register');
		}

		$data['backUrl'] = session('backUrl') ?? url()->previous();

		return view('pages.user.register', $data);
	}

	public function registerComplete(Request $request)
	{
		$method = 'AES-256-ECB';
		$info = $request->input('info');
		$codAuchouse = Config::get("app.subalia_cli");

		if (!empty($codAuchouse) && !empty($info)) {

			$key = SubAuchouse::select('COD_AUCHOUSE', 'HASH')
				->where('CLI_AUCHOUSE', '=', $codAuchouse)
				->where('EMP_ORIGIN_AUCHOUSE', '=', Config::get('app.emp'))
				->get();

			if (!empty($key)) {
				$info_decript = openssl_decrypt(base64_decode($info), $method, $key[0]->hash, OPENSSL_RAW_DATA);

				$usertmp = json_decode($info_decript, true);
			}
		}

		$postUser = SubaliaPostUserDTO::fromArray($usertmp ?? []);

		//redirige al antiguo formulario de registro
		if (!empty(Config::get('app.old_register'))) {
			$userControler = new UserController();
			return $userControler->login($postUser, $info);
		}

		//redirige al nuevo formulario de registro
		return $this->index($postUser, $info);
	}
}
