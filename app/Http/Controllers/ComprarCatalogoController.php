<?php
namespace App\Http\Controllers;

use Config;
use View;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\libs\MessageLib;
use App\libs\FormLib;
use App\libs\EmailLib;

# Cargamos los modelos

use App\Models\V5\Web_Page;

class ComprarCatalogoController extends Controller
{   
	public function index(Request $request){
   
		$data = array();
		$data['formulario'] = array();
		$data['formulario']['nombre'] = Formlib::Text("nombre",1,"");
		$data['formulario']['apellidos'] = Formlib::Text("apellidos",1,"");
		$data['formulario']['nif'] = Formlib::Text("nif",1,"");
		$data['formulario']['profesion'] = Formlib::Text("profesion",1,"");
		$data['formulario']['direccion'] = Formlib::Text("direccion",1,"");
		$data['formulario']['poblacion'] = Formlib::Text("poblacion",1,"");
		$data['formulario']['provincia'] = Formlib::Text("provincia",1,"");
		$data['formulario']['pais'] = Formlib::Text("pais",1,"");
		$data['formulario']['cp'] = Formlib::Text("cp",1,"");

		$data['formulario']['email'] = Formlib::Email("email",1,"");
		$data['formulario']['telefono'] = Formlib::Text("telefono",1,"");
		
		$data['formulario']['_token'] = Formlib::hidden("_token",1,csrf_token());
		$data['formulario']['SUBMIT'] = Formlib::Submit(trans(\Config::get('app.theme').'-app.login_register.acceder'),"contactForm");
                
		$a = Web_Page::where("key_web_page","contacto")->where("lang_web_page",strtoupper(\Config::get("app.locale")))->where("emp_web_page",\Config::get("app.emp"))->first();
		if (!empty($a)) {
			$data['content'] = $a->content_web_page;
		}

		
		return View::make('pages.comprarCatalogo', array('data' => $data));
	   
	}
	
	public function Sendmail(Request $request) {

		// Recogemos la info

		$data = $request->all();
		$jsonResponse = \Tools::validateRecaptcha(\Config::get('app.codRecaptchaEmail'));
		
        if (empty($jsonResponse) || $jsonResponse->success !== true) {

        	return MessageLib::errorMessage("recaptcha_incorrect");
                
        }

       
		// Enviamos el email a admin

		$email = new EmailLib('NEW_CATALOGO_ADMIN'); 

		if (!empty($email->email)) {

			$email->setAtribute('NOMBRE',$data['nombre']);
			$email->setAtribute('APELLIDOS',$data['nombre']);
			$email->setAtribute('NIF',$data['nif']);
			$email->setAtribute('PROFESION',$data['profesion']);

			$email->setAtribute('DIRECCION',$data['direccion']);
			$email->setAtribute('POBLACION',$data['poblacion']);
			$email->setAtribute('PROVINCIA',$data['provincia']);
			$email->setAtribute('PAIS',$data['pais']);
			$email->setAtribute('CP',$data['cp']);
			
			$email->setAtribute('EMAIL',$data['email']);
			$email->setAtribute('TELEFONO',$data['telefono']);

			if (isset($data['email_to'])) {
				$email->setTo($data['email_to']);
			}
			else {
				$email->setTo(Config::get('app.admin_email'));	
			}
			
			$email->send_email();
		}
		else {
			return MessageLib::errorMessage("Error Sending Mail","Error");
		}

		// Enviamos el email de confirmación al usuario

		$email = new EmailLib('NEW_CATALOGO'); 
		if (!empty($email->email)) {

			$email->setAtribute('NAME',$data['nombre']);

			$email->setTo($data['email']);
			$email->send_email();
		}
		else {
			return MessageLib::errorMessage("Error Sending Mail","Error");
		}

		return MessageLib::successMessage("mensaje_enviado");

	}
}