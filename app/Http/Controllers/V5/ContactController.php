<?php

namespace App\Http\Controllers\V5;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\libs\MessageLib;
use App\libs\FormLib;
use App\libs\EmailLib;
use App\Models\V5\Web_Page;
use App\Services\Content\PageService;

class ContactController extends Controller
{
	public function index(PageService $pageService)
	{
		$withPlaceHolders = Config::get('app.contat_with_placeholders', 0);

		$data = [
			'formulario' => $this->formContact($withPlaceHolders),
			'content' => $pageService->getPage('contacto')?->content_web_page,
			'content2' => $pageService->getPage('contacto2')?->content_web_page,
			'content3' => $pageService->getPage('contacto3')?->content_web_page,
		];

		if (Config::get('app.seo_in_contact', 0)) {
			$data['seo'] = new \stdClass();
			$data['seo']->meta_title = trans(Config::get('app.theme') . '-app.metas.title_contact');
			$data['seo']->meta_description = trans(Config::get('app.theme') . '-app.metas.description_contact');
		}

		return View::make('pages.V5.contact', array('data' => $data));
	}

	public static function formContact($withPlaceHolders = 0)
	{
		$theme = Config::get('app.theme');
		$formulario = [
			'nombre' => Formlib::Text("nombre", 1, "", "", $withPlaceHolders ? trans("$theme-app.login_register.contact") : ""),
			'email' => Formlib::Email("email", 1, "", "", $withPlaceHolders ? trans("$theme-app.foot.newsletter_text_input") : ""),
			'telefono' => Formlib::Text("telefono", 1, "", "", $withPlaceHolders ? trans("$theme-app.user_panel.phone") : ""),
			'comentario' => Formlib::TextArea("comentario", 1, "", "", $withPlaceHolders ? trans("$theme-app.global.coment") : ""),
			'_token' => Formlib::hidden("_token", 1, csrf_token()),
			'SUBMIT' => Formlib::Submit(trans(Config::get('app.theme') . '-app.login_register.acceder'), "contactForm")
		];

		return $formulario;
	}

	/**
	 * Enviamos un correo de contacto al admin
	 * Metodo protegido por el middleware VerifyCaptcha
	 * @see App\Http\Middleware\VerifyCaptcha
	 */
	public function contactSendmail(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
		]);

		// Recogemos la info
		$data = $request->all();

		// Lista de emails baneados por spam y que no son captados por el recaptcha
		$bannedsEmails = ['eric.jones.z.mail@gmail.com'];
		$isEmailBanned = in_array(trim($data['email']), $bannedsEmails);
		if ($isEmailBanned) {
			return MessageLib::errorMessage("recaptcha_incorrect");
		}

		// Enviamos el email a admin
		$email = new EmailLib('NEW_CONTACT_ADMIN');

		if (!empty($email->email)) {

			$email->setAtribute('NAME', $data['nombre']);
			$email->setAtribute('PHONE', $data['telefono'] ?? '');
			$email->setAtribute('EMAIL', $data['email']);
			$email->setAtribute('COMMENT', $data['comentario']);

			if (isset($data['email_to'])) {
				$email->setTo($data['email_to']);
			} else {
				#13/01/2020 añado contact email para que el formulario de contacto pueda recibir correo de otra persona
				if (!empty(Config::get('app.contact_email'))) {
					$email->setTo(Config::get('app.contact_email'));
				} else {
					$email->setTo(Config::get('app.admin_email'));
				}
			}

			if (isset($data['email_cc'])) {
				$email->setCc($data['email_cc']);
			}

			if (!empty($request->file('images'))) {
				$email->attachmentsFiles = array();
				$email->attachmentsFiles = $request->file('images');
			}

			$email->send_email();
		}

		// Enviamos el email de confirmación al usuario

		$email = new EmailLib('NEW_CONTACT');
		if (!empty($email->email)) {

			$email->setAtribute('NAME', $data['nombre']);
			$email->setAtribute('COMMENT', $data['comentario']);

			$email->setTo($data['email']);
			$email->send_email();
		}



		return MessageLib::successMessage("mensaje_enviado");
	}


	public function admin(Request $request)
	{
		$data = array();
		$data['formulario'] = array();
		$data['formulario']['nombre'] = Formlib::Text("nombre", 1, "");
		$data['formulario']['email'] = Formlib::Email("email", 1, "");
		$data['formulario']['telefono'] = Formlib::Text("telefono", 1, "");
		$data['formulario']['comentario'] = Formlib::TextArea("comentario", 1, "");
		$data['formulario']['_token'] = Formlib::hidden("_token", 1, csrf_token());
		$data['formulario']['SUBMIT'] = Formlib::Submit(trans(Config::get('app.theme') . '-app.login_register.acceder'), "contactForm");

		return View::make('pages.V5.administradores_concursales', array('data' => $data));
	}

}
