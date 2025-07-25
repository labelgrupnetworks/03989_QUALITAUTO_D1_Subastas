<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\V5\FgEspecial1;
use App\Providers\RoutingServiceProvider as Routing;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class ValoracionController extends Controller
{
	public $emp;
	public $gemp;

	public function __construct()
	{
		$this->emp = Config::get('app.emp');
		$this->gemp = Config::get('app.gemp');
	}

	public function GetValoracion()
	{
		$data = [];
		return View::make('pages.valoracion_articulos', array('data' => $data));
	}

	public function GetValoracionGratuita($lang, $key)
	{
		$lang = Config::get('app.locale');
		$SEO_metas = new \stdClass();
		$SEO_metas->noindex_follow = Config::get('app.valoracion_index_follow', 0) ? false : true;

		if (Config::get('app.seo_in_valoracion')) {
			$SEO_metas->meta_title = trans(Config::get('app.theme') . '-app.metas.title_valoracion');
			$SEO_metas->meta_description = trans(Config::get('app.theme') . '-app.metas.description_valoracion');
		}

		$data = array(
			'title' =>  trans(Config::get('app.theme') . '-app.home.free-valuations'),
			'seo'   => $SEO_metas,
			'lang' => $lang,
		);

		if (config('app.experts_in_valoration', false)) {
			$especialistas = FgEspecial1::orderBy('orden_especial1', 'asc')->withSpecialty()->get();
			$data['especialistas'] = $especialistas;
		}

		if (!config('app.assessment_registered', false)) {
			return view('pages.valoracion.valoracion_articulos', array('data' => $data));
		}

		$possibleKeys = ['articulos', 'no-register'];

		if (!in_array($key, $possibleKeys)) {
			return Redirect::to('/');
		}

		//valoracion inicial
		if ($key == 'articulos') {
			//valorar un articulo si
			$url = '/' . $lang . '/valoracion-no-register';
			if (!Session::has('user')) {
				return Redirect::to($url);
			} else {

				$Usuario = new User();
				$Usuario->cod_cli = Session::get('user.cod');
				$inf_user = $Usuario->getUser();

				$data["name"] = $inf_user->nom_cli;
				$data["email"] = $inf_user->usrw_cliweb;
				$data["telf"] = $inf_user->tel1_cli;

				return View::make('pages.valoracion.valoracion_articulos', array('data' => $data));
			}
		}

		//no-register
		$url = '/' . $lang . '/valoracion-articulos';
		if (Session::has('user')) {
			return Redirect::to($url);
		} else {
			$data['seo'] = new \stdClass();
			$data['seo']->noindex_follow = true;
			return View::make('pages.valoracion.no_registrado', array('data' => $data));
		}
	}

	/**
	 * Metodo protegido por el middleware VerifyCaptcha
	 * @see App\Http\Middleware\VerifyCaptcha
	 */
	public function ValoracionArticulosAdv(Request $request, $lang)
	{
		try {

			if (empty($_POST['post'])) {
				$url = Routing::translateSeo('valoracion-articulos-success');
			} else {
				$url = Routing::translateSeo('pagina') . 'vender-monedas-success';
			}

			App::setLocale($lang);

			$i = 1;
			$relative_dest_path = 'img/valoracion';
			$relative = '/' . $relative_dest_path;
			$destination_path = public_path($relative);
			$max_size = 20000000;
			//debes poner imagen
			if (empty(Input::file('imagen')) && Config::get('app.imageRequiredInValoraciones', 1)) {
				return $result = array(
					'status'  => 'error_no_image',
					'msg' => 'error_no_image',
				);
			}

			foreach (Input::file('imagen') ?? [] as $val_img) {
				$file = $val_img;
				if (!empty($file)) {
					if (filesize($file) < $max_size) {
						$filename = $file->getClientOriginalName();
						$file->move($destination_path, $filename);
						$emailOptions['img']['imagen' . $i] = Config::get('app.url') . $relative . '/' . str_replace(" ", "%20", $filename);
						$i++;
					} else {
						return [
							'status'  => 'error_size',
							'msg' => 'max_size',
						];
					}
				}
			}

			$htmlFields = false;
			$prohibidos = [
				'_token',
				'imagen',
				'email_category',
				'name',
				'email',
				'telf',
				'post',
				'g-recaptcha-response',
				'captcha_token',
				'to_specialist'
			];
			$htmlFieldsArray = array_diff_key(request()->all(), array_flip($prohibidos));

			foreach ($_POST as $key => $value) {
				// Inputs prohibidos de mostrar
				if (!in_array($key, $prohibidos)) {
					if (!is_array($key) && !is_array($value)) {
						$htmlFields .= '<strong>' . ucfirst(htmlspecialchars($key)) . ':</strong> ' . htmlspecialchars($value) . ' <br />';
					}
				}
			}

			if (Config::get('app.assessment_registered')) {
				$Usuario          = new User();
				$Usuario->cod_cli = Session::get('user.cod');
				$inf_user = $Usuario->getUser();

				//si el formulario viene vacios se rellena con los datos del usuario
				$name = $request->input('name', $inf_user->nom_cli);
				$email = $request->input('email', $inf_user->usrw_cliweb);
				$telf = $request->input('telf', $inf_user->usrw_cliweb);

				$emailOptions['content'] = array(
					'texto' => trans(Config::get('app.theme') . '-app.emails.valoracion_articulos') . ' ' . Config::get('app.name'),
					'name'       => $name,
					'email' =>  $email,
					'telf' => $telf,
					'camposHtml' => $htmlFields,
					'camposHtmlArray' => $htmlFieldsArray,
				);

				$emailOptions['user'] = $name;
			} else {
				$emailOptions['content'] = array(
					'texto' => trans(Config::get('app.theme') . '-app.emails.valoracion_articulos') . ' ' . Config::get('app.name'),
					'name'       => $request->input('name', ''),
					'email' => $request->input('email', ''),
					'telf' => $request->input('telf', ''),
					'camposHtml' => $htmlFields,
					'camposHtmlArray' => $htmlFieldsArray,

				);

				$emailOptions['user'] = $request->input('name', '');
			}

			$send_email = Config::get('app.admin_email');

			if (!empty($request->input('email_category'))) {
				$send_email = $request->input('email_category');
			} elseif (!empty($request->input('to_specialist'))) {
				$specialistEmail = FgEspecial1::where('per_especial1', $request->input('to_specialist'))->value('email_especial1');
				if($specialistEmail) {
					$send_email = $specialistEmail;
				}
			}

			$utm_email = '';
			if (!empty(Config::get('app.utm_email'))) {
				$utm_email = Config::get('app.utm_email') . '&utm_campaign=valoracion';
			}

			$emailOptions['UTM'] = $utm_email;
			$emailOptions['to'] = $send_email;
			$emailOptions['subject'] = trans(Config::get('app.theme') . '-app.emails.valoracion_articulos') . ' ' . Config::get('app.name');
			if (ToolsServiceProvider::sendMail('notification_valoracion', $emailOptions)) {

				if (Config::get('app.cc_email_valoracion')) {

					$emailOptions['to'] =  Config::get('app.cc_email_valoracion');
					ToolsServiceProvider::sendMail('notification_valoracion', $emailOptions);
				}

				if (Config::get('app.email_tasacion_client')) {

					$emailOptions['to'] =  $emailOptions['content']['email'];
					ToolsServiceProvider::sendMail('notification_valoracion', $emailOptions);
				}



				foreach (Input::file('imagen') ?? [] as $val_img) {
					$file = $val_img;
					if (!empty($file)) {
						$filename = $file->getClientOriginalName();
						if (file_exists($relative_dest_path . "/" . $filename)) {
							unlink($relative_dest_path . "/" . $filename); //acá le damos la direccion exacta del archivo
						}
					}
				}

				return array(
					'status'  => 'correct',
					'url' => URL::asset($url),

				);
			} else {

				foreach (Input::file('imagen') ?? [] as $val_img) {
					$file = $val_img;
					$filename = $file->getClientOriginalName();
					if (file_exists($relative_dest_path . "/" . $filename)) {
						unlink($relative_dest_path . "/" . $filename); //acá le damos la direccion exacta del archivo
					}
				}

				return array(
					'status'  => 'error',
				);
			}
		} catch (\Exception $e) {

			Log::error("Error en Valoración" . print_r($_POST, true));
			Log::error($e);
			return array(
				'status'  => 'error',
			);
		}
	}

	function formatSizeUnits($bytes)
	{
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}

		return $bytes;
	}

	public function ValoracionSuccess()
	{
		$lang = Config::get('app.locale');

		$SEO_metas = new \stdClass();
		$SEO_metas->noindex_follow = true;


		$data = array(
			'title' => '',
			'seo'   => $SEO_metas,
		);

		return View::make('pages.valoracion.valoracion_articulos_success', array('data' => $data));
	}
}
