<?php
namespace App\Http\Controllers\V5;

use Config;
use View;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\libs\MessageLib;
use App\libs\FormLib;
use App\libs\EmailLib;

# Cargamos los modelos

use App\Models\V5\Web_Page;

class ContactController extends Controller
{
	public function index(Request $request){
		#cogemos la empresa principal y si no existe la actual, de esta manera solo contenidos solo deben estar en la empresa principal
		$emp =   Config::get('app.main_emp');
		$data = array();
		$data['formulario'] = array();
		$data['formulario']['nombre'] = Formlib::Text("nombre",1,"");
		$data['formulario']['email'] = Formlib::Email("email",1,"");
		$data['formulario']['telefono'] = Formlib::Text("telefono",1,"");
		$data['formulario']['comentario'] = Formlib::TextArea("comentario",1,"");
		$data['formulario']['_token'] = Formlib::hidden("_token",1,csrf_token());
		$data['formulario']['SUBMIT'] = Formlib::Submit(trans(\Config::get('app.theme').'-app.login_register.acceder'),"contactForm");

		$a = Web_Page::where("key_web_page","contacto")->where("lang_web_page",strtoupper(\Config::get("app.locale")))->where("emp_web_page",$emp)->first();
		if (!empty($a)) {
			$data['content'] = $a->content_web_page;
		}

		$a = Web_Page::where("key_web_page","contacto2")->where("lang_web_page",strtoupper(\Config::get("app.locale")))->where("emp_web_page",$emp)->first();
		if (!empty($a)) {
			$data['content2'] = $a->content_web_page;
		}

		$a = Web_Page::where("key_web_page","contacto3")->where("lang_web_page",strtoupper(\Config::get("app.locale")))->where("emp_web_page",$emp)->first();
		if (!empty($a)) {
			$data['content3'] = $a->content_web_page;
		}

		if(Config::get('app.seo_in_contact', 0)){
			$data['seo'] = new \stdClass();
			$data['seo']->meta_title = trans(\Config::get('app.theme').'-app.metas.title_contact');
			$data['seo']->meta_description = trans(\Config::get('app.theme').'-app.metas.description_contact');
		}

		return View::make('pages.V5.contact', array('data' => $data));

	}

	public function contactSendmail(Request $request) {

		// Recogemos la info

		$data = $request->all();
		$jsonResponse = \Tools::validateRecaptcha(\Config::get('app.codRecaptchaEmail'));

		// Lista de emails baneados por spam y que no son captados por el recaptcha
		$bannedsEmails = ['eric.jones.z.mail@gmail.com'];
		$isEmailBanned = in_array(trim($data['email']), $bannedsEmails);

        if (empty($jsonResponse) || $jsonResponse->success !== true || $isEmailBanned) {
        	return MessageLib::errorMessage("recaptcha_incorrect");
        }


		// Enviamos el email a admin

		$email = new EmailLib('NEW_CONTACT_ADMIN');

		if (!empty($email->email)) {

			$email->setAtribute('NAME',$data['nombre']);
			$email->setAtribute('PHONE',$data['telefono'] ?? '');
			$email->setAtribute('EMAIL',$data['email']);
			$email->setAtribute('COMMENT',$data['comentario']);

			if (isset($data['email_to'])) {
				$email->setTo($data['email_to']);
			}
			else {
                            #13/01/2020 añado contact email para que el formulario de contacto pueda recibir correo de otra persona
                            if(!empty(Config::get('app.contact_email'))){
                                $email->setTo(Config::get('app.contact_email'));
                            }else{
								$email->setTo(Config::get('app.admin_email'));
                            }
			}

			if(isset($data['email_cc'])){
				$email->setCc($data['email_cc']);
			}

			$email->send_email();
		}


		// Enviamos el email de confirmación al usuario

		$email = new EmailLib('NEW_CONTACT');
		if (!empty($email->email)) {

			$email->setAtribute('NAME',$data['nombre']);
			$email->setAtribute('COMMENT',$data['comentario']);

			$email->setTo($data['email']);
			$email->send_email();
		}



		return MessageLib::successMessage("mensaje_enviado");

	}


        public function admin(Request $request){

		$data = array();
		$data['formulario'] = array();
		$data['formulario']['nombre'] = Formlib::Text("nombre",1,"");
		$data['formulario']['email'] = Formlib::Email("email",1,"");
		$data['formulario']['telefono'] = Formlib::Text("telefono",1,"");
		$data['formulario']['comentario'] = Formlib::TextArea("comentario",1,"");
		$data['formulario']['_token'] = Formlib::hidden("_token",1,csrf_token());
		$data['formulario']['SUBMIT'] = Formlib::Submit(trans(\Config::get('app.theme').'-app.login_register.acceder'),"contactForm");

		return View::make('pages.V5.administradores_concursales', array('data' => $data));
	}


}
