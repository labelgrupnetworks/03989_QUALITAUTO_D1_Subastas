<?php

namespace App\Http\Controllers;

use Request;
use Validator;
use Input;
use Config;
use App\Models\V5\FxCliWeb;
use App\libs\EmailLib;

class NewsletterController extends Controller
{


    /*************************************************************************************************************************\
    #
    # 	SETNEWSLETTER - Añadir una cuenta de correo al newsletter    #
    #
    #
    \*************************************************************************************************************************/
 # Añadir una cuenta de correo al newsletter
 #Metodo antiguo
    /*
  public function setNewsletter_deprecated()
  {
    if(empty(Input::get('no_validate'))) {
        if(!empty(Config::get('app.recaptchad_newsletter'))){

            if(empty(Request::input('g-recaptcha-response'))){
                  return array(
                   'status'  => 'error',
                   "msg"       => 'recaptcha_incorrect'
                 );
            }


           $jsonResponse = \Tools::validateRecaptcha(Config::get('app.recaptchad_newsletter'));
           if (empty($jsonResponse) || $jsonResponse->success !== true) {

               return array(
                 'status'  => 'error',
                 "msg"       => 'recaptcha_incorrect'
               );

           }
       }
    }

    $rules = array(
        'email'    => 'required|email'    // make sure the email is an actual email
    );


    // run the validation rules on the inputs from the form
    $validator = Validator::make(Input::all(), $rules);
    # Validamos la direccion de correo introducida
    if (!$validator->fails()) {
      $newsletter = new Newsletter();
      $newsletter->email = Input::get('email');
      $newsletter->lang = strtoupper(Input::get('lang'));

      if (!empty(Input::get('families'))) {
        $newsletter->families = Input::get('families');
      }else{
          $newsletter->families = NULL;
      }

      $result = $newsletter->setNewsletter();

    } else {
      $result = array(
                  'status'  => 'error',
                  'msg'     => 'err-add_newsletter'
                );
    }

    return $result;

  }
 */

  #Metodo nuevo
  public function setNewsletter() {

         // Validamos que nos ha llegado un email

         //$families = Request::input('families');

         //return;

	    $rules = array(
	        'email'    => 'required|email',    // make sure the email is an actual email
	        'condiciones'    => 'required',    // Se han aceptado las condiciones de uso
	    );

	    $validator = Validator::make(Input::all(), $rules);

	    if (!$validator->fails()) {

	    	$email = trim(Request::input('email'));
			$lang = Request::input('lang');

            $newsletters = array();
            $newsletterInput = Request::input("families");

			for ($t = 1; $t <= 20; $t++) {

                $newsletters[$t] = (!empty($newsletterInput) && in_array($t, $newsletterInput)) ? 'S' : 'N';

            }

			# Miramos si ya existe el usuario y está registrado

	   		$hasCliweb = FxCliWeb::where('GEMP_CLIWEB',Config::get('app.gemp'))
						->where('EMP_CLIWEB',Config::get('app.emp'))
						->where('LOWER(USRW_CLIWEB)',strtolower($email))->first();

			if ($hasCliweb) {

				// Si ya existe le ratificamos la suscripción

				$info = array();
				foreach($newsletters as $k => $item) {
					$info["NLLIST".$k."_CLIWEB"] = $item;
				}

				FxCliWeb::where('GEMP_CLIWEB',Config::get('app.gemp'))
						->where('EMP_CLIWEB',Config::get('app.emp'))
						->where('LOWER(USRW_CLIWEB)',strtolower($email))
						->update($info);

				//Soporte Concursal queria que también se enviara el registro en la newsletter
				$emailSend = new EmailLib('USER_NEWSLETTER');
				if(!empty($emailSend->email)){
					$emailSend->setTo(strtolower($email));
					$emailSend->send_email();
				}

				return $result = array(
		        'status' 	=> 'success',
		        'msg' 		=> 'success-add_newsletter'
                                  );

			}


			# Si no existe el usuario lo registramos con el COD_CLIWEB = 0

			$info = array();

			$info["GEMP_CLIWEB"] = Config::get('app.gemp');
			$info["COD_CLIWEB"] = "0";
			$info["USRW_CLIWEB"] = $email;
			$info["EMAIL_CLIWEB"] = $email;
		 	$info["EMP_CLIWEB"] = Config::get('app.emp');
		 	$info["TIPACCESO_CLIWEB"] = "N";
		 	$info["TIPO_CLIWEB"] = "C";
			$info["FECALTA_CLIWEB"] = date("Y-m-d H:i:s");
			$info["IDIOMA_CLIWEB"] = $lang;

			foreach($newsletters as $k => $item) {
				$info["NLLIST".$k."_CLIWEB"] = $item;
			}

			FxCliWeb::insert($info);

			$result = $result = array(
		        'status' 	=> 'success',
		        'msg' 		=> 'success-add_newsletter'
	        );

	    }
	    else {
	    	return array(
                 'status'  => 'error',
                 "msg"       => 'err-add_newsletter'
                );
	    }

	    return $result;

	}

}
