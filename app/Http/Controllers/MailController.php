<?php

namespace App\Http\Controllers;

use Request;
use Requests;
use Session;
use Routing;
use Route;
use Input;
use Config;
use Mail;
use View;
use Redirect;
use DB;
use App\Http\Controllers\SubastaController;
use App\Models\Busqueda;
use App\Models\Subasta;
use App\Models\Bloques;
use App\Models\MailQueries;
use App\Models\User;
use App\Models\Facturas;
use App\Models\Payments;
use Paginator;
use App\Models\Enterprise;
use App\libs\EmailLib;
use Illuminate\Support\Facades\Validator;
use \App\Http\Controllers\UserController;
use App\Models\V5\FgHces1;
use App\Models\V5\FxCli;
use App\Models\V5\FxClid;
use App\Providers\ToolsServiceProvider as Tools;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{

    public $template;
    public $emailOptions;
    public $to;

    # Enviamos un correo al admin
    public function mailToAdmin()
    {

        \Log::info("Formulario contacto: " . print_r($_POST, true));

        if (!empty(Config::get('app.codRecaptchaEmail'))) {

            if (empty($_POST['g-recaptcha-response'])) {
                \Log::info("Correo bloqueado validateRecaptcha no variable");
                return Redirect::to(Routing::slug('thanks'));
            }

            $jsonResponse = \Tools::validateRecaptcha(Config::get('app.codRecaptchaEmail'));
            if (empty($jsonResponse) || $jsonResponse->success !== true) {
                \Log::info("Correo bloqueado validateRecaptcha ");
                return Redirect::to(Routing::slug('thanks'));
            }
        }
        //hemso recibido ataques y ellos envian el input Submit, nosotros no lo enviamos
        if (isset($_POST['submit'])) {
            \Log::info("Correo bloqueado Submit ");
            return Redirect::to(Routing::slug('thanks'));
        }

        /* No esta funcionando el codigo de Hansel
            $rules = [
                'nombre' => 'required|min:5',
                'email' => 'required|email',
                'comentario' => 'required'
            ];

            $validator = Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return Redirect::to(Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.contact'))
                        ->withInput(request()->all());
            }
            */
        //evita ataques desde fuera de nuestro dominio
        if (!empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' && strpos($_SERVER['HTTP_REFERER'], \Config::get('app.url')) !== false   && strpos($_SERVER['HTTP_REFERER'], \Config::get('app.url')) == 0) {
            $this->template     = 'mailer';
            if (!empty(Request::input('departamento'))) {
                $this->to             = Request::input('departamento');
            } else {
                $this->to             = Config::get('app.admin_email');
            }

            $this->emailOptions = self::processVars();

            //Email Admin

            \Log::info("emailOptions " . print_r($this->emailOptions, true));
            $this->send();

            $this->emailOptions['to'] = Request::input('email');
            $this->send();
        } else {
            \Log::info("Correo bloqueado HTTP_REFERER " . $_SERVER['HTTP_REFERER']);
        }
        return Redirect::to(Routing::slug('thanks'));
    }

    # Procesamos los campos del formulario y lo montamos para la plantilla html
    public function processVars()
    {
        $htmlFields = false;

        # Si tenemos session de usuario lo añadimos al contenido
        if (!empty(Session::get('user.name'))) {
            $emailOptions['user'] = Session::get('user.name');
            $htmlFields .= '<span style="color:#333"><strong>User:</strong></span> ' . Session::get('user.cod') . ' <br />';
        }
		$prohibidos = array( 'confirm_password', 'departamento');
        $htmlFields = $this->processPostVars($htmlFields, $prohibidos);
        if (empty($_POST)) {
            exit;
        }

        $emailOptions = array(
            'camposHtml' => ($htmlFields),
        );

        $emailOptions['user'] = '';

        if (!empty(Session::get('user.name'))) {
            $emailOptions['user'] = Session::get('user.name');
        }

        $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.email_received_from') . Config::get('app.name');
        $emailOptions['to'] = $this->to;

        return $emailOptions;
        //return View::make('front::emails.mailer',$emailOptions);
    }

	public function processPostVars($htmlFields = "", $prohibidosAux = array(""))
    {
		#por defecto quitamos los campos del formulario que no deben enviarse, si hay alguno más se puede pasar por la función
		$prohibidos = array_merge( array('condiciones',  '_token', 'regtype',  'g-recaptcha-response'), $prohibidosAux);
		foreach ($_POST as $key => $value) {

            if (!in_array($key, $prohibidos)) {
                $bool_title = stripos($key, 'title_');
                if ($bool_title === false) {
                    $key_temp = Request::input('title_' . $key);
                    if (!empty($key_temp)) {
                        $key = $key_temp;
                    }
                    if (!is_array($key) && !is_array($value)) {
                        $htmlFields .= '<span style="color:#333"><strong>' . ucfirst(htmlspecialchars($key)) . ':</strong></span> ' . htmlspecialchars($value) . ' <br />';
                    }
                }
            }
        }

		return  $htmlFields;

	}

    # Enviamos un correo
    public function send()
    {
        return \Tools::sendMail($this->template, $this->emailOptions);

        /*Mail::send('emails.'.$this->template, $this->emailOptions, function ($m)  {
            $m->from(Config::get('app.from_email'), Config::get('app.name'));

            $m->to($this->to, 'admin')->subject(trans(\Config::get('app.theme').'-emails.mailer.email_received_from').Config::get('app.name'));
        });*/
	}
	# 13-08-2020 comento la funcion por que parece ser que ya n ose usa
/*
    public function send_new_password($num_mails = 1, $country = 'ES')
    {

        $sql = "select ROWNUM , T.* from (SELECT  WEB_SEND_PASS.* FROM WEB_SEND_PASS WHERE SENDED_WEB_SEND_PASS = 0  AND COUNTRY_WEB_SEND_PASS = :country ORDER BY EMAIL_WEB_SEND_PASS) T where ROWNUM <= :num_mails";
        //$sql ="select * from web_config";

        $emails = DB::select($sql, array("country" =>$country,  "num_mails" =>$num_mails, ));

        foreach ($emails as $email) {

            $sql = "UPDATE WEB_SEND_PASS SET SENDED_WEB_SEND_PASS = 2   WHERE EMAIL_WEB_SEND_PASS ='" . $email->email_web_send_pass . "'";

            $a = DB::select($sql);


            $emailOptions = array(
                'user'      => $email->name_web_send_pass,
                'pwd'       => $email->password_web_send_pass,
                'email'     => $email->email_web_send_pass,
            );

            $emailOptions['to'] = $email->email_web_send_pass;
            $emailOptions['subject'] = "";
            //return \View::make('front::emails.'.'new_web' , array('emailOptions' => $emailOptions,'user' => 'user','email' =>'email','pwd' => 'pwd'));
            if (\Tools::sendMail('new_web', $emailOptions)) {

                $sql = "UPDATE WEB_SEND_PASS SET SENDED_WEB_SEND_PASS = 1   WHERE EMAIL_WEB_SEND_PASS ='" . $email->email_web_send_pass . "'";

                $a = DB::select($sql);
            }



            //return \View::make('front::emails.'.'new_web' , array('emailOptions' => $emailOptions,'user' => 'user','email' =>'email','pwd' => 'pwd'));
        }
    }
*/
    public function sendEmailCerrado($emp, $cod_sub, $ref)
    {

        try {

            $bloque = new Bloques();
            $mailquery = new MailQueries;
            $emailOptions = array(
                'user'      => Config::get('app.name'),
                'email'     => Config::get('app.admin_email'),
            );


            $hoy = date("Y-m-d");
            $lot_relacionados_lang;
            $lotes_relacionado_final = '';
            $relacionados = array();
            $licit = '';
            $exist = false;
            $licitadores = array();
            $user = new User();
            $subasta = new Subasta();

            $subasta->ref = $ref;
            $subasta->cod = $cod_sub;
            $subasta->lote = $ref;
            $subasta->page = 'all';
            $user->cod = $cod_sub;
            //Informacion del lote



            $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'S');


            $inf_lot = $subasta->getLoteLight();
            if (empty($inf_lot)) {
                $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'V');

                //$this->emailAdminError("Error al enviar notificaciones, no se han podido enviar ya que el lote no existe, <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref");
                $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no existe";
                $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_adjudicado');
                $this->emailAdminError($msg, $subject, $cod_sub, $ref, NULL, NULL, NULL, NULL);

                return;
            }
            $inf_lot_translate = $subasta->getMultilanguageTextLot($inf_lot->num_hces1, $inf_lot->lin_hces1);


            $adjudicado = $subasta->get_csub($emp);
            //Hay un ganador, solo enviamos emails si hay un ganador, los perdedores no reciben email si no hay un ganador, lo ha pedido Sam asi
            if (!empty($adjudicado)) {

                //$this->EmailsAdjudicacionesCedente($cod_sub,$ref,'ONLINE');

                $user->licit = $adjudicado->licit_csub;

                //Inf user
                $inf_user = $user->getFXCLIByLicit();
                //Existe el usuario

                if (count($inf_user) > 0 &&  !empty($inf_user[0]->email_cli)) {

                    //Precio que tiene que pagar el cliente
                    $pay =  new PaymentsController();
                    $comision = $inf_lot->coml_hces1;

                    $iva = $pay->getIva($emp, $hoy);
                    $tipo_iva = $pay->user_has_Iva(Config::get('app.gemp'), $inf_user[0]->cli_licit);
                    $tax = $pay->calculate_iva($tipo_iva->tipo, $iva, $adjudicado->base_csub);

                    $precio_tax = $tax + $adjudicado->base_csub;
                    $precio = $tax + $adjudicado->himp_csub + $adjudicado->base_csub;

                    \App::setLocale(strtolower($inf_user[0]->idioma_cli));
                    $utm_email = '';
                    if (!empty(Config::get('app.utm_email'))) {
                        $utm_email = Config::get('app.utm_email') . '&utm_campaign=puja_ganadora';
                    }

                    $emailOptions['UTM'] = $utm_email;
                    $emailOptions['user'] = '';
                    $emailOptions['to'] = trim($inf_user[0]->email_cli);
                    $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_adjudicado');

                    $content = new \stdClass();
                    $content->title = trans_choice(\Config::get('app.theme') . '-app.emails.title_lote_adjudicado', 1, ['name' => ucwords(strtolower($inf_user[0]->nom_cli))]);
                    $content->text = trans(\Config::get('app.theme') . '-app.emails.contenido_lote_adjudicado');
                    $content->final_text_up_button = trans(\Config::get('app.theme') . '-app.emails.up_button_lote_adjudicado');
                    $content->final_text = trans(\Config::get('app.theme') . '-app.emails.final_text_lote_adjudicado');
                    $content->button = trans(\Config::get('app.theme') . '-app.emails.button_pay');
                    $content->url_button = Config::get('app.url') . \Routing::slug('user/panel/allotments/outstanding');
                    $content->block_help = true;
                    $content->hide_thanks = true;
                    $emailOptions['content'] = $content;

                    $email_lot = new \stdClass();
                    $email_lot->ref = $inf_lot->ref_asigl0;
                    $email_lot->sub = trans(\Config::get('app.theme') . '-app.user_panel.auctions_online');
                    $email_lot->desc = $inf_lot_translate[$inf_user[0]->idioma_cli]->desc_hces1;
                    $email_lot->img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $inf_lot->num_hces1 . '-' . $inf_lot->lin_hces1 . '.jpg';
                    $email_lot->price_pay = $precio;
                    $email_lot->price_tax = $tax;
                    $email_lot->price_base = $adjudicado->base_csub;
                    $email_lot->price_himp = $adjudicado->himp_csub;
                    $emailOptions['lot'][] = $email_lot;


                    if (\Tools::sendMail('emails_automaticos', $emailOptions) == true) {
                        $mailquery->setEmailLogs('WEBLICIT02', $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user[0]->cli_licit, trim($inf_user[0]->email_cli), 'L');
                    } else {

                        $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'E');


                        //$this->emailAdminError("Error al intentar notificar al usuario. ".trans(\Config::get('app.theme').'-app.emails.asunto_lote_adjudicado').". Revise el email del usuario, puede contener caracteres no válidos.<br>Código de cliente: ".$inf_user[0]->cli_licit." <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref  <br> Hoja de Cesión: ". $inf_lot->numhces_asigl0 ." <br>Línea: ". $inf_lot->linhces_asigl0 );
                        $msg = "Error al intentar notificar al usuario. Revise el email del usuario, puede contener caracteres no válidos.";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_adjudicado');
                        $this->emailAdminError($msg, $subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user[0]->cli_licit, NULL);
                    }
                } else {
                    //le falta el email
                    if (count($inf_user) > 0) {
                        $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'E');
                        //$this->emailAdminError("Error al intentar notificar al usuario. ".trans(\Config::get('app.theme').'-app.emails.asunto_lote_adjudicado').". Revise el email del usuario, puede contener caracteres no válidos.<br>Código de cliente: ".$inf_user[0]->cli_licit." <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref  <br> Hoja de Cesión: ". $inf_lot->numhces_asigl0 ." <br>Línea: ". $inf_lot->linhces_asigl0 );
                        $msg = "Error al intentar notificar al usuario. El cliente no tiene email.";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_adjudicado');
                        $this->emailAdminError($msg, $subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user[0]->cli_licit, NULL);
                    } else { //el usuario no existe
                        $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'E');
                        //$this->emailAdminError("Error al intentar notificar al usuario. ".trans(\Config::get('app.theme').'-app.emails.asunto_lote_adjudicado').". Revise el email del usuario, puede contener caracteres no válidos.<br>Código de cliente: ".$inf_user[0]->cli_licit." <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref  <br> Hoja de Cesión: ". $inf_lot->numhces_asigl0 ." <br>Línea: ". $inf_lot->linhces_asigl0 );
                        $msg = "Error al intentar notificar al usuario. El licitador no existe";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_adjudicado');
                        $this->emailAdminError($msg, $subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, NULL, NULL, $adjudicado->licit_csub);
                    }
                }
                $get_pujas = $subasta->getPujas(false, $cod_sub);

                //creamos un array con los pujadores no adjudicados del lote
                foreach ($get_pujas as $get_value_pujas) {
                    //si no ha ganado nadie o el que gano noes el pujador actual  y el licitador n oes el dummy
                    if ((empty($adjudicado) ||  $adjudicado->licit_csub != $get_value_pujas->cod_licit) && (Config::get('app.dummy_bidder') != $get_value_pujas->cod_licit)) {
                        $licitadores[$get_value_pujas->cod_licit] = $get_value_pujas->cod_licit;
                    }
                }
                $utm_email = '';
                if (!empty(Config::get('app.utm_email'))) {
                    $utm_email = Config::get('app.utm_email') . '&utm_campaign=puja_no_adjudicacion';
                }
                $emailOptions['UTM'] = $utm_email;

                //Licitadores no adjudicados
                foreach ($licitadores as $licitador) {
                    $user->licit = $licitador;
                    $inf_user = $user->getFXCLIByLicit();
                    if (count($inf_user) > 0 &&  !empty($inf_user[0]->email_cli)) {
                        \App::setLocale(strtolower($inf_user[0]->idioma_cli));

                        $emailOptions['user'] = $inf_user[0]->nom_cli;
                        $emailOptions['to'] =  trim($inf_user[0]->email_cli);
                        $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_no_adjudicado');

                        $content = new \stdClass();
                        $content->title = trans(\Config::get('app.theme') . '-app.emails.title_lote_no_adjudicado');
                        $content->text = trans_choice(\Config::get('app.theme') . '-app.emails.contenido_lote_lote_no_adjudicado', 1, ['lot' => $inf_lot->ref_asigl0]);
                        $content->button = trans(\Config::get('app.theme') . '-app.emails.button_see_novelties');
                        $content->url_button = \Config::get('app.url') . '/' . \Routing::slugSeo('subastas') . '/' . trans(\Config::get('app.theme') . '-app.links.all_categories');
                        $content->hide_thanks = true;

                        $emailOptions['content'] = $content;

                        $emailOptions['lot'] = null;

                        if (\Tools::sendMail('emails_automaticos', $emailOptions) == true) {
                            $mailquery->setEmailLogs('WEBLICIT03', $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user[0]->cli_licit, $inf_user[0]->email_cli, 'L');
                        } else {
                            $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'L');
                            //$this->emailAdminError("Error al intentar notificar al usuario. ".trans(\Config::get('app.theme').'-app.emails.asunto_lote_adjudicado').". Revise el email del usuario, puede contener caracteres no válidos.<br>Código de cliente: ".$inf_user[0]->cli_licit." <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref  <br> Hoja de Cesión: ". $inf_lot->numhces_asigl0 ." <br>Línea: ". $inf_lot->linhces_asigl0 );
                            $msg = "Error al intentar notificar al usuario. Revise el email del usuario, puede contener caracteres no válidos.";
                            $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_no_adjudicado');
                            $this->emailAdminError($msg, $subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user[0]->cli_licit, NULL);
                        }
                    } else {
                        //le falta el email
                        if (count($inf_user) > 0) {
                            $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'E');
                            //$this->emailAdminError("Error al intentar notificar al usuario. ".trans(\Config::get('app.theme').'-app.emails.asunto_lote_adjudicado').". Revise el email del usuario, puede contener caracteres no válidos.<br>Código de cliente: ".$inf_user[0]->cli_licit." <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref  <br> Hoja de Cesión: ". $inf_lot->numhces_asigl0 ." <br>Línea: ". $inf_lot->linhces_asigl0 );
                            $msg = "Error al intentar notificar al usuario. El cliente no tiene email.";
                            $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_no_adjudicado');
                            $this->emailAdminError($msg, $subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user[0]->cli_licit, NULL);
                        } else { //el usuario no existe
                            $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'E');
                            //$this->emailAdminError("Error al intentar notificar al usuario. ".trans(\Config::get('app.theme').'-app.emails.asunto_lote_adjudicado').". Revise el email del usuario, puede contener caracteres no válidos.<br>Código de cliente: ".$inf_user[0]->cli_licit." <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref  <br> Hoja de Cesión: ". $inf_lot->numhces_asigl0 ." <br>Línea: ". $inf_lot->linhces_asigl0 );
                            $msg = "Error al intentar notificar al usuario. El licitador no existe";
                            $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_no_adjudicado');
                            $this->emailAdminError($msg, $subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, NULL, NULL, $licitador);
                        }
                    }
                }
            } else {
                echo "no hay ganador no se envia: $emp - $cod_sub - $ref <br><br>";
            }
        } catch (\Exception $e) {

            $this->error_email_exception('sendEmailCerrado', $e->getMessage(), $emp, $cod_sub, $ref);
            return;
        }
	}

	function sendEmailNoAdjudicado($emp, $cod_sub, $ref, $cod_sub_last, $ref_last){

		try {

			$mailquery = new MailQueries;
			$subasta = new Subasta();
            $subasta->ref = $ref;
            $subasta->cod = $cod_sub;
			$subasta->lote = $ref;

			$mailquery->updateSendEmailNoAdjudicado($emp, $cod_sub, $ref, 'S');
			$inf_lot = head($subasta->getLote(false, false));

			if (empty($inf_lot)) {
                $mailquery->updateSendEmailNoAdjudicado($emp, $cod_sub, $ref, 'V');
                $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no existe";
                $subject = trans_choice(\Config::get('app.theme') . '-app.emails.asunto_new_subasta_lote', 1, ['lot' => "$cod_sub-$ref"]);
                $this->emailAdminError($msg, $subject, $cod_sub, $ref, null, null, null, NULL);
                return;
			}

			$email = new EmailLib('MOVE_LOT');
        	if (!empty($email->email)) {
            	$email->setLot($cod_sub, $ref);
            	//$email->setPrice(\Tools::moneyFormat($inf_lot->impres_asigl0, false, 2));
            	$email->titularidad_send_mail();
			}

		} catch (\Exception $e) {
			$this->error_email_exception('sendEmailMoveLot', $e->getMessage(), $emp, $cod_sub, $ref);
            return;
		}

	}


	/**
	 * Utilizado por Balclis, y substituido por sendEmailNoAdjudicado.
	 * @deprecated
	 * @see MailController::sendEmailNoAdjudicado()
	 */
	/*
    function sendEmailNoAdjudicado_old($emp, $cod_sub, $ref, $cod_sub_last, $ref_last)
    {

        try {

            $emailOptions = array(
                'user'      => Config::get('app.name'),
                'email'     => Config::get('app.admin_email'),
            );

            $lin = '';
            $mailquery = new MailQueries;
            $user = new User();
            $subasta = new Subasta();
            $subasta->cod = $cod_sub;
            $subasta->lote = $ref;

            //Precio Actual
            $inf_lot = $subasta->getLoteLight();
            $inf_lot_new_sub = $inf_lot;

            $mailquery->updateSendEmailNoAdjudicado($emp, $cod_sub, $ref, 'S');


            if (empty($inf_lot)) {
                $mailquery->updateSendEmailNoAdjudicado($emp, $cod_sub, $ref, 'V');
                $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no existe";
                $subject = trans_choice(\Config::get('app.theme') . '-app.emails.asunto_new_subasta_lote', 1, ['lot' => "$cod_sub-$ref"]);
                $this->emailAdminError($msg, $subject, $cod_sub, $ref, null, null, null, NULL);
                return;
            }
            $inf_lot_translate = $subasta->getMultilanguageTextLot($inf_lot->num_hces1, $inf_lot->lin_hces1);


            $nuevo_precio = $inf_lot->impres_asigl0;
            //Precio antiguo
            $subasta->cod = $cod_sub_last;
            $subasta->lote = $ref_last;

            $inf_lot = $subasta->getLoteLight();

            if (empty($inf_lot)) {
                $mailquery->updateSendEmailNoAdjudicado($emp, $cod_sub, $ref, 'V');

                //$this->emailAdminError("Error al enviar notificaciones, no se han podido enviar ya que el lote no existe, <br> Código de subasta: $cod_sub_last <br>Referencia del lote: $ref_last");
                $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no existe";
                $subject = trans_choice(\Config::get('app.theme') . '-app.emails.asunto_new_subasta_lote', 1, ['lot' => "$cod_sub_last-$ref_last"]);
                $this->emailAdminError($msg, $subject, $cod_sub_last, $ref_last, null, null, null, NULL);
                return;
            }
            $precio = $inf_lot->impres_asigl0;



            $contrato = $inf_lot->num_hces1 . '/' . $inf_lot->lin_hces1;
            //email propietario
            if (!empty($inf_lot->prop_hces1)) {

                $user->cod_cli = $inf_lot->prop_hces1;

                $inf_user = $user->getUserByCodCli('N');

                if (count($inf_user) > 0 &&  !empty($inf_user[0]->email_cli)) {
                    $inf_user = head($inf_user);
                    \App::setLocale(strtolower($inf_user->idioma_cli));

                    $emailOptions['to'] =  trim($inf_user->email_cli);
                    $fini_lot = date("d/m/Y", strtotime($inf_lot_new_sub->fini_asigl0));
                    $hini_lot = $inf_lot_new_sub->hini_asigl0;

                    $utm_email = '';
                    if (!empty(Config::get('app.utm_email'))) {
                        $utm_email = Config::get('app.utm_email') . '&utm_campaign=novacion';
                    }
                    $emailOptions['UTM'] = $utm_email;
                    $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.asunto_new_subasta_lot');
                    $emailOptions['user'] = $inf_user->nom_cli;
                    $content = new \stdClass();
                    $content->title = trans(\Config::get('app.theme') . '-app.emails.title_new_subasta_lote');
                    $content->text = trans_choice(\Config::get('app.theme') . '-app.emails.contenido_new_subasta_lote', 1, ['date' => $fini_lot, 'hora' => $hini_lot, 'precio' => \Tools::moneyFormat($nuevo_precio, false, 2)]);
                    $content->final_text = trans(\Config::get('app.theme') . '-app.emails.finaltext_new_subasta_lote');
                    $emailOptions['content'] = $content;

                    $email_lot = new \stdClass();
                    $email_lot->contrato = $contrato;
                    $email_lot->desc = $inf_lot_translate[$inf_user->idioma_cli]->desc_hces1;
                    $email_lot->ref = $inf_lot->ref_asigl0;
                    $email_lot->img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $inf_lot->numhces_asigl0 . '-' . $inf_lot->linhces_asigl0 . '.jpg';
                    $email_lot->sub = trans(\Config::get('app.theme') . '-app.user_panel.auctions_online');
                    $email_lot->new_price = \Tools::moneyFormat($nuevo_precio, false, 2);
                    //$email_lot->date = $fini_lot.' '.$hini_lot;
                    $emailOptions['lot'][] = $email_lot;



                    if (\Tools::sendMail('emails_automaticos', $emailOptions) == true) {
                        $mailquery->setEmailLogs('WEBPROP02', $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user->cod_cli, $inf_user->email_cli, 'P');
                    } else {
                        $mailquery->updateSendEmailNoAdjudicado($emp, $cod_sub, $ref, 'E');
                        $msg = "Error al  notificar al cedente. Revise el email del cedente, puede contener caracteres no válidos.";
                        $subject = trans_choice(\Config::get('app.theme') . '-app.emails.asunto_new_subasta_lote', 1, ['lot' => $contrato]);
                        $this->emailAdminError($msg, $subject, $cod_sub_last, $ref_last, $inf_lot->num_hces1, $inf_lot->lin_hces1, null, $user->cod_cli);
                    }
                } else {
                    $mailquery->updateSendEmailNoAdjudicado($emp, $cod_sub, $ref, 'P');

                    if (empty($inf_user)) {
                        $msg = "Error al  notificar al cedente, no existe ningún usuario con el código de propietario.";
                        $subject = trans_choice(\Config::get('app.theme') . '-app.emails.asunto_new_subasta_lote', 1, ['lot' => $contrato]);
                        $this->emailAdminError($msg, $subject, $cod_sub_last, $ref_last, $inf_lot->num_hces1, $inf_lot->lin_hces1, null, $user->cod_cli);
                    } else {
                        $msg = "Error al  notificar al cedente.  El cedente, no tiene email";
                        $subject = trans_choice(\Config::get('app.theme') . '-app.emails.asunto_new_subasta_lote', 1, ['lot' => $contrato]);
                        $this->emailAdminError($msg, $subject, $cod_sub_last, $ref_last, $inf_lot->num_hces1, $inf_lot->lin_hces1, null, $user->cod_cli);
                    }
                }
            } else {
                $mailquery->updateSendEmailNoAdjudicado($emp, $cod_sub, $ref, 'P');
                $msg = "Error al  notificar al cedente, el lote no tiene propietario ";
                $subject = trans_choice(\Config::get('app.theme') . '-app.emails.asunto_new_subasta_lote', 1, ['lot' => $contrato]);
                $this->emailAdminError($msg, $subject, $cod_sub_last, $ref_last, $inf_lot->num_hces1, $inf_lot->lin_hces1, null, $user->cod_cli);
            }
        } catch (\Exception $e) {
            $this->error_email_exception('sendEmailNoAdjudicado', $e->getMessage(), $emp, $cod_sub, $ref);
            return;
        }
	}*/


    //cuando un lote se vuelve a poner a la venta en otra subasta
    function reSaleLot($id_emp, $id_sub, $id_ref, $id_last_sub, $id_last_ref)
    {
        try {
            $mailquery = new MailQueries;

            $emailOptions = array(
                'user'      => Config::get('app.name'),
                'email'     => Config::get('app.admin_email'),
            );

            $mailquery->updateReSaleLot($id_emp, $id_sub, $id_ref, $id_last_sub, $id_last_ref, 'S');



            $subasta = new Subasta();
            $subasta->page = 'all';
            $subasta->cod = $id_last_sub;
            $subasta->ref = $id_last_ref;
            $user = new User();
            //hay que poner la subasta anterior, para que encuentre el licitador
            $user->cod = $id_last_sub;

            $get_pujas = $subasta->getPujas();

            $licitadores = array();
            //creamos un array con los pujadores no adjudicados del lote
            foreach ($get_pujas as $puja) {
                //que no haya como pujador el dummy
                if ((Config::get('app.dummy_bidder') != $puja->cod_licit)) {
                    $licitadores[$puja->cod_licit] = $puja->cod_licit;
                }
            }


            //coger datos del lote, se coge los valores de subasta y referencia de la subasta actual
            $subasta->cod = $id_sub;
            $subasta->lote = $id_ref;
            $inf_lot_array = $subasta->getLote(false, false);
            //si no hay lote el codigo de error es V
            if (empty($inf_lot_array)) {
                $mailquery->updateReSaleLot($id_emp, $id_sub, $id_ref, $id_last_sub, $id_last_ref, 'V');

                //$this->emailAdminError("Error al enviar notificaciones, no se han podido enviar ya que el lote no existe, <br> Código de subasta: $id_sub <br>Referencia del lote: $id_ref");
                $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no existe,";
                $subject = trans(\Config::get('app.theme') . '-app.emails.subject_resalelot');
                $this->emailAdminError($msg, $subject, $id_sub, $id_ref, null, null, null, null);
                return;
            }
            $inf_lot_translate = $subasta->getMultilanguageTextLot($inf_lot_array[0]->num_hces1, $inf_lot_array[0]->lin_hces1);


            $inf_lot = head($inf_lot_array);
            $img = '<img src="' . Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $inf_lot->numhces_asigl0 . '-' . $inf_lot->linhces_asigl0 . '.jpg' . '"><br>';


            foreach ($licitadores as $licitador) {

                $user->licit = $licitador;
                $inf_user = $user->getFXCLIByLicit();
                $url_lot = Config::get('app.url') . \Routing::translateSeo('lote') . $inf_lot->sub_asigl0 . "-" . str_slug($inf_lot->id_auc_sessions) . '-' . $inf_lot->id_auc_sessions . "/" . $inf_lot->ref_asigl0 . '-' . $inf_lot->num_hces1 . '-' . str_slug($inf_lot->webfriend_hces1) . Config::get('app.utm_email');
                if (count($inf_user) > 0 &&  !empty($inf_user[0]->email_cli)) {
                    \App::setLocale(strtolower($inf_user[0]->idioma_cli));

                    $emailOptions['to'] =  trim($inf_user[0]->email_cli);
                    $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_resalelot');
                    $emailOptions['content'] = trans_choice(\Config::get('app.theme') . '-app.emails.content_resalelot', 1, ['name' =>  $inf_user[0]->nom_cli, 'url_lot' => $url_lot, 'img' => $img, 'name_lot' => $inf_lot_translate[$inf_user[0]->idioma_cli]->descweb_hces1]);
                    if (\Tools::sendMail('notification', $emailOptions) == true) {
                        $mailquery->setEmailLogs('WEBLICIT04', $id_sub, $id_ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user[0]->cli_licit, $inf_user[0]->email_cli, 'L');
                    } else {
                        $mailquery->updateReSaleLot($id_emp, $id_sub, $id_ref, $id_last_sub, $id_last_ref, 'E');
                        $msg = "Error al intentar notificar al usuario. Revise el email del usuario, puede contener caracteres no válidos.";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.subject_resalelot');
                        $this->emailAdminError($msg, $subject, $id_sub, $id_ref, $inf_lot->num_hces1, $inf_lot->lin_hces1, $inf_user[0]->cli_licit, null);
                    }
                } else {
                    //si no tiene email
                    if (count($inf_user) > 0) {
                        $msg = "Error al intentar notificar al usuario. El licitador no tiene email.";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.subject_resalelot');
                        $this->emailAdminError($msg, $subject, $id_sub, $id_ref, $inf_lot->num_hces1, $inf_lot->lin_hces1, $inf_user[0]->cli_licit, null);
                    } else { //no existe el usuario
                        $msg = "Error al intentar notificar al usuario. No hay ningun usuario con este código de licitador";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.subject_resalelot');
                        $this->emailAdminError($msg, $subject, $id_sub, $id_ref, $inf_lot->num_hces1, $inf_lot->lin_hces1, null, null, $licitador);
                    }
                }
            }
            //si no tiene licitadores el codigo de error es L
            if (count($licitadores) == 0) {
                echo "no hay licitadores";
                $mailquery->updateReSaleLot($id_emp, $id_sub, $id_ref, $id_last_sub, $id_last_ref, 'L');
            }
        } catch (\Exception $e) {
            $this->error_email_exception('reSaleLot', $e->getMessage(), $id_emp, $id_sub, $id_ref);
            return;
        }
    }

    function EmailsAdjudicacionesCedente($cod_sub, $ref, $sub = '-')
    {

        $subasta = new Subasta();

        $subasta->cod = $cod_sub;
        $subasta->lote = $ref;

        $price_lot = $subasta->getAssignetPrice();

        $email = new EmailLib('LOT_SOLD_ASSIGNOR');
        if (!empty($email->email)) {
            $email->setLot($cod_sub, $ref);
            $email->setPrice(\Tools::moneyFormat($price_lot->himp_csub, false, 2));
            $email->titularidad_send_mail();
        }
    }

    /* 2019/02/19 no se est'a usando
         function EmailsAdjudicacionesCedentePersonalized($cod_sub,$ref,$sub = '-'){
            $locale_actual = Config::get('app.locale');

            $user = new User();
            $subasta = new Subasta();

            $subasta->ref = $ref;
            $subasta->cod = $cod_sub;
            $subasta->lote = $ref;
            $user->cod = $cod_sub;

            $inf_lot = $subasta->getLoteLight();

            if(empty($inf_lot)){
                //$this->emailAdminError("Error al enviar notificaciones, no se han podido enviar ya que el lote no existe, <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref");

                $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no existe.";
                $subject = trans(\Config::get('app.theme').'-app.emails.asunto_lote_vendido');
                $this->emailAdminError($msg,$subject, $cod_sub, $ref, null, null,null,null);
                return;
            }
            $inf_lot_translate = $subasta->getMultilanguageTextLot($inf_lot->num_hces1,$inf_lot->lin_hces1);
            $img = Config::get('app.url').'/img/load/lote_medium/'.Config::get('app.emp').'-'.$inf_lot->numhces_asigl0.'-'.$inf_lot->linhces_asigl0.'.jpg';
            $emailOptions = array(
                'user'      => '',
                'email'     => Config::get('app.admin_email'),
            );
            $price_lot = $subasta->getAssignetPrice();

            if(empty($price_lot)){
                $msg = "Error al  notificar al cedente, el lote no tiene precio de adjudicación.";
                $subject = trans(\Config::get('app.theme').'-app.emails.asunto_lote_vendido');
                $this->emailAdminError($msg,$subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0,null,null);
                //$this->emailAdminError("Error al  notificar al cedente, el lote no tiene precio de adjudicación.<br>Código de subasta:$cod_sub <br>Referencia del lote:$ref <br> Hoja de Cesión: ". $inf_lot->numhces_asigl0 ." <br>Línea: ". $inf_lot->linhces_asigl0 );
                \Log::error("EmailsAdjudicacionesCedente  cod sub: $cod_sub, ref:$ref ");
                return;
            }
            $contrato = $inf_lot->num_hces1.'/'.$inf_lot->lin_hces1;

            $titularidad = $this->titularidad($inf_lot->num_hces1,$inf_lot->lin_hces1,$inf_lot->prop_hces1);


            $utm_email = '';
            if(!empty(Config::get('app.utm_email'))){
                $utm_email = Config::get('app.utm_email').'&utm_campaign=lote_vendido';
            }


            foreach($titularidad as $cod_cli_titularidad){
                $emailOptions['lot'] = array();
                $user->cod_cli = $cod_cli_titularidad;

                $inf_user= $user->getUserByCodCli('N');

                if(!empty($inf_user) && !empty($inf_user[0]->email_cli)){
                    $inf_user = head($inf_user);
                    \App::setLocale(strtolower($inf_user->idioma_cli));

                    $emailOptions['to'] = trim($inf_user->email_cli);
                    $emailOptions['subject'] = trans(\Config::get('app.theme').'-app.emails.asunto_lote_vendido');

                    $emailOptions['user'] = '';

                    $content = new \stdClass();
                    $content->title = trans_choice(\Config::get('app.theme').'-app.emails.title_lote_vendido', 1,['name'=>ucwords(strtolower($inf_user->nom_cli))]);
                    $content->text = trans_choice(\Config::get('app.theme').'-app.emails.contenido_lote_vendido', 1, ['price'=> \Tools::moneyFormat($price_lot->himp_csub,false,2),'ref'=>$inf_lot->ref_asigl0]);
                    $content->button = trans(\Config::get('app.theme').'-app.emails.button_go_to_account');
                    $content->url_button = Config::get('app.url').\Routing::slug('user/panel/sales');
                    $content->hide_thanks = true;
                    $emailOptions['content'] = $content;

                    $email_lot = new \stdClass();
                    $email_lot->contrato = $contrato;
                    $email_lot->desc = $inf_lot_translate[$inf_user->idioma_cli]->desc_hces1;
                    $email_lot->ref = $inf_lot->ref_asigl0;
                    $email_lot->img = $img;
                    $email_lot->desc = $inf_lot_translate[$inf_user->idioma_cli]->desc_hces1;
                    $email_lot->price = \Tools::moneyFormat($price_lot->himp_csub,false,2);
                    $emailOptions['lot'][] = $email_lot;

                    if (\Tools::sendMail('emails_automaticos', $emailOptions) == true){
                        $mailquery= new MailQueries;
                        $mailquery->setEmailLogs('WEBPROP01', $cod_sub,$ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user->cod_cli, $inf_user->email_cli, 'P');

                    }else{
                         //$this->emailAdminError(" Error al  notificar al cedente.". trans(\Config::get('app.theme').'-app.emails.asunto_lote_vendido'). "Revise el email del cedente, puede contener caracteres no válidos.<br> Código de propietario: ".$user->cod_cli." <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref  <br> Hoja de Cesión: ". $inf_lot->numhces_asigl0 ." <br>Línea: ". $inf_lot->linhces_asigl0 );
                        $msg = " Error al  notificar al cedente. Revise el email del cedente, puede contener caracteres no válidos";
                        $subject = trans(\Config::get('app.theme').'-app.emails.asunto_lote_vendido');
                        $this->emailAdminError($msg,$subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0,null,$user->cod_cli);
                    }
                }else{
                        if(empty($inf_user)){
                               $msg = "  Error al  notificar al cedente, no existe ningún usuario con este código de propietario";
                                $subject = trans(\Config::get('app.theme').'-app.emails.asunto_lote_vendido');
                                $this->emailAdminError($msg,$subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0,null,$cod_cli_titularidad);
                        }else{
                            $msg = "  Error al  notificar al cedente. Revise el email del cedente, puede contener caracteres no válidos.";
                                $subject = trans(\Config::get('app.theme').'-app.emails.asunto_lote_vendido');
                                $this->emailAdminError($msg,$subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0,null,$cod_cli_titularidad);

                        }
                    }
            }
            //volvemos a dejar los idiomas como estaba
            \App::setLocale($locale_actual);
         }
         */
    public function EmailCedentFirstAuction($emp, $cod_sub, $ref)
    {
        try {

            $emailOptions = array(
                'user'      => Config::get('app.name'),
                'email'     => Config::get('app.admin_email'),
            );

            $mailquery = new MailQueries;
            $mailquery->updateWebEmailFirstAuction($emp, $cod_sub, $ref, 'S');


            $user = new User();
            $subasta = new Subasta();

            $subasta->ref = $ref;
            $subasta->cod = $cod_sub;
            $subasta->lote = $ref;
			$user->cod = $cod_sub;

            $inf_lot = head($subasta->getLote(false, false));

            if (empty($inf_lot)) {
                $msg = " Error al enviar notificaciones, no se han podido enviar ya que el lote no existe.";
                $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_subasta_primera_vez');
                $this->emailAdminError($msg, $subject, $cod_sub, $ref, null, null, null, null);
                return;
			}


			/**
			 * Codigo nuevo
			 */
			$email = new EmailLib('FIRST_AUCTION');
        	if (!empty($email->email)) {
            	$email->setLot($cod_sub, $ref);
            	$email->titularidad_send_mail();
			}

			/**
			 * A partir de aqui contiene el proceso original realizado para Balclis
			 */
			/*
            $inf_lot_translate = $subasta->getMultilanguageTextLot($inf_lot->num_hces1, $inf_lot->lin_hces1);
            $img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $inf_lot->numhces_asigl0 . '-' . $inf_lot->linhces_asigl0 . '.jpg';

            $contrato = $inf_lot->num_hces1 . '/' . $inf_lot->lin_hces1;
            $titularidad = array();
            $titularidad_multiple = $subasta->titularidadMultiple($inf_lot->num_hces1, $inf_lot->lin_hces1);
            if (empty($titularidad_multiple)) {
                $titularidad_multiple = $subasta->titularidadMultiple($inf_lot->num_hces1, '0');
            }

            if (!empty($titularidad_multiple)) {
                foreach ($titularidad_multiple as $titulares) {
                    $titularidad[] = $titulares->cli_hcesmt;
                }
            } else {
                $titularidad[] = $inf_lot->prop_hces1;
            }

            $utm_email = '';
            if (!empty(Config::get('app.utm_email'))) {
                $utm_email = Config::get('app.utm_email') . '&utm_campaign=lote_subasta';
            }

            foreach ($titularidad as $cod_cli_titularidad) {
                $user->cod_cli = $cod_cli_titularidad;
                $inf_user = $user->getUserByCodCli('N');
                if (!empty($inf_user) && !empty($inf_user[0]->email_cli)) {
                    $inf_user = head($inf_user);

                    $fini_lot = date("d/m/Y", strtotime($inf_lot->fini_asigl0));
                    $ffin_lot = date("d/m/Y", strtotime($inf_lot->ffin_asigl0));
                    $hfin_lot = $inf_lot->hfin_asigl0;

                    \App::setLocale(strtolower($inf_user->idioma_cli));

                    $emailOptions['UTM'] = $utm_email;
                    $emailOptions['to'] = trim($inf_user->email_cli);
                    $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_subasta_primera_vez');
                    $emailOptions['user'] = $inf_user->nom_cli;

                    if (!empty($lot->webfriend_hces1) && !empty($inf_lot_translate[$inf_user->idioma_cli]->webfriend_hces1)) {
                        $url_friendly = str_slug($inf_lot_translate[$inf_user->idioma_cli]->webfriend_hces1);
                    } else {
                        $url_friendly = str_slug($inf_lot_translate[$inf_user->idioma_cli]->titulo_hces1);
                    }
                    $link = Config::get('app.url') . \Routing::translateSeo('lote') . $inf_lot->sub_asigl0 . "-" . $inf_lot->id_auc_sessions . '-' . $inf_lot->id_auc_sessions . "/" . $inf_lot->ref_asigl0 . '-' . $inf_lot->num_hces1 . '-' . $url_friendly;


                    $content = new \stdClass();
                    $content->title = trans(\Config::get('app.theme') . '-app.emails.title_lote_subasta_primera_vez');
                    $content->text = trans_choice(\Config::get('app.theme') . '-app.emails.contenido_lote_subasta_primera_vez', 1, ['price' => \Tools::moneyFormat($inf_lot->impres_asigl0, false, 2), 'fini' => $ffin_lot, 'hini' => $hfin_lot]);
                    $content->final_text = trans(\Config::get('app.theme') . '-app.emails.finaltext_lote_subasta_primera_vez');
                    $content->button = trans(\Config::get('app.theme') . '-app.emails.button_go_to_auction');
                    $content->url_button = $link;
                    $content->share = true;
                    $content->share_link = $link;
                    $content->share_img = $img;
                    $content->share_title = str_replace(' ', '&nbsp;', $inf_lot_translate[$inf_user->idioma_cli]->titulo_hces1);
                    $content->hide_thanks = true;
                    $emailOptions['content'] = $content;

                    $email_lot = new \stdClass();
                    $email_lot->contrato = $contrato;
                    $email_lot->desc = $inf_lot_translate[$inf_user->idioma_cli]->desc_hces1;
                    $email_lot->ref = $inf_lot->ref_asigl0;
                    $email_lot->img = $img;
                    $email_lot->sub = trans(\Config::get('app.theme') . '-app.user_panel.auctions_online');
                    $email_lot->date = $fini_lot . ' - ' . $ffin_lot;
                    $emailOptions['lot'][] = $email_lot;

                    #$emailOptions['content']['text'] = trans_choice(\Config::get('app.theme').'-app.emails.contenido_lote_subasta_primera_vez', 1, ['name' => $inf_user->nom_cli,'contrato'=>$contrato,'price'=> \Tools::moneyFormat($inf_lot->impres_asigl0,false,2)
	                                   #,'img'=>$img,'ref'=>$inf_lot->ref_asigl0,'desc'=>$inf_lot_translate[$inf_user->idioma_cli]->desc_hces1,'url'=>$link,'fini'=>$ffin_lot,'hini'=>$hfin_lot]);


                    if (\Tools::sendMail('emails_automaticos', $emailOptions) == true) {
                        $mailquery->setEmailLogs('WEBPROP03', $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_user->cod_cli, trim($inf_user->email_cli), 'P');
                    } else {
                        $msg = " Error al  notificar al cedente. Revise el email del cedente, puede contener caracteres no válidos.";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_subasta_primera_vez');
                        $this->emailAdminError($msg, $subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, null, $user->cod_cli);
                        $mailquery->updateWebEmailFirstAuction($emp, $cod_sub, $ref, 'E');
					}

                } else {
                    if (empty($inf_user)) {
                        $msg = "  Error al  notificar al cedente, No existe el usuario.";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_subasta_primera_vez');
                        $this->emailAdminError($msg, $subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, null, $cod_cli_titularidad);
                    } else {
                        $msg = "  Error al  notificar al cedente, El cedente no tiene email";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_subasta_primera_vez');
                        $this->emailAdminError($msg, $subject, $cod_sub, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, null, $cod_cli_titularidad);
                    }

                    $mailquery->updateWebEmailFirstAuction($emp, $cod_sub, $ref, 'E');
				}

			}
			*/

        } catch (\Exception $e) {
            $this->error_email_exception('EmailCedenteAuctionSale', $e->getMessage(), $emp, $cod_sub, $ref);
            return;
        }
    }

    public function emailAdminError($msg, $subject = NULL, $cod_sub = NULL, $ref = NULL, $num_hces = NULL, $lin_hces = NULL, $cod_cli_lic = NULL, $cod_cli_prop = NULL, $cod_licit = NULL)
    {
        $content = $msg;
        if (!empty($cod_cli_prop)) {
            $content .= " <br> Código de propietario:<strong> $cod_cli_prop </strong>";
        }
        if (!empty($cod_cli_lic)) {
            $content .= " <br> Código de cliente: <strong> $cod_cli_lic </strong>";
        }
        if (!empty($cod_licit)) {
            $content .= " <br> Código de licitador: <strong> $cod_licit </strong>";
        }
        if (!empty($cod_sub)) {
            $content .= " <br> Código de subasta:  <strong> $cod_sub </strong>";
        }
        if (!empty($ref)) {
            $content .= " <br> Referencia del lote:  <strong> $ref </strong>";
        }
        if (!empty($num_hces)) {
            $content .= " <br> Hoja de Cesión:   <strong> $num_hces </strong>";
        }
        if (!empty($lin_hces)) {
            $content .= " <br>Línea:  <strong> $lin_hces </strong>";
        }
        $emailOptions = array(
            'content'     => $content
        );

        $emailOptions['to'] = Config::get('app.admin_email');
        $emailOptions['subject'] = "Error notificacion: $subject";
        $emailOptions['user'] = "WEB";


        if (\Tools::sendMail('notification', $emailOptions)) {
            echo ('Mail sent:');
        } else {
            echo ('Error mail not sent');
        }
    }

    function error_email_exception($text, $e, $emp, $cod_sub, $ref)
    {
        $emailOptions = array(
            'user'      => Config::get('app.name'),
            'email'     => Config::get('app.debug_to_email'),
        );

        $emailOptions['to'] = $emailOptions['email'];
        $emailOptions['subject'] = "Error email diarios";
        $emailOptions['content'] = "Error emails diarios :" . Config::get('app.name') . " <br>Funcion: " . $text . " - Empresa:" . $emp . " - Codigo subastas:" . $cod_sub . ""
            . " - Referencia:" . $ref . "<br><br>$e";
        if (\Tools::sendMail('notification', $emailOptions)) {
            echo ('Mail sent:');
        } else {
            echo ('Error mail sent');
        }
    }

    public function sendLastCall($users)
    {
        try {
            $mailquery = new MailQueries;
            $locale_actual = Config::get('app.locale');

            $emailOptions = array(
                'user'      => Config::get('app.name'),
                'email'     => Config::get('app.admin_email'),
            );

            $utm_email = '';
            if (!empty(Config::get('app.utm_email'))) {
                $utm_email = Config::get('app.utm_email') . '&utm_campaign=fin_favoritos';
            }

            foreach ($users as $user) {

                \App::setLocale(strtolower($user[0]->idioma_cli));

                //$contenido_lotes = trans_choice(\Config::get('app.theme').'-app.emails.content_last_call', 1,['name' => $user[0]->nom_cli] );
                $ids_web_email_last_call = array();

                $hora_fin_lot = null;
                $emailOptions['lot'] = array();
                foreach ($user as $lot) {

                    $ids_web_email_last_call[] = $lot->id_web_email_last_call;
                    if (!empty($lot->webfriend_hces1)) {
                        $url_friendly = str_slug($lot->webfriend_hces1);
                    } else {
                        $url_friendly = str_slug($lot->titulo_hces1);
                    }
                    $link = Config::get('app.url') . \Routing::translateSeo('lote') . $lot->sub_asigl0 . "-" . $lot->id_auc_sessions . '-' . $lot->id_auc_sessions . "/" . $lot->ref_asigl0 . '-' . $lot->num_hces1 . '-' . $url_friendly;

                    if (empty($hora_fin_lot) || strtotime($lot->hora_fin) < strtotime($hora_fin_lot)) {
                        $hora_fin_lot = $lot->hora_fin;
                    }

                    $price = $lot->implic_hces1;
                    if (empty($lot->implic_hces1)) {
                        $price = $lot->impsalhces_asigl0;
                    }

                    $email_lot = new \stdClass();
                    $email_lot->img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $lot->num_hces1 . '-' . $lot->lin_hces1 . '.jpg';
                    $email_lot->button = trans(\Config::get('app.theme') . '-app.emails.bid_now');
                    $email_lot->url_button_lot = $link;
                    $email_lot->ref = $lot->ref_asigl0;
                    $email_lot->desc = $lot->descweb_hces1;
                    $email_lot->sub = trans(\Config::get('app.theme') . '-app.user_panel.auctions_online');
                    $email_lot->text_lot = trans_choice(\Config::get('app.theme') . '-app.emails.bid_now_content_lot', 1, ['imp' => $price]);
                    $emailOptions['lot'][] = $email_lot;
                }


                if (!empty($user[0]->email_cli)) {

                    $emailOptions['to'] =  trim($user[0]->email_cli);
                    $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_last_call');
                    $emailOptions['UTM'] = $utm_email;
                    $emailOptions['user'] = $user[0]->nom_cli;

                    $content = new \stdClass();
                    $content->title = trans(\Config::get('app.theme') . '-app.emails.title_send_last_call');
                    $content->text = trans_choice(\Config::get('app.theme') . '-app.emails.contenido_send_last_call', 1, ['ffin' => date('H:i', strtotime($hora_fin_lot)) . 'h']);
                    $content->hide_thanks = true;
                    $emailOptions['content'] = $content;


                    $mailquery->sendedLastCall($ids_web_email_last_call, 'S');

                    if (\Tools::sendMail('emails_automaticos', $emailOptions)) {
                        //marcamos el envio

                        $mailquery->setEmailLogs('WEBLICIT01', $user[0]->sub_asigl0, $user[0]->ref_asigl0, $user[0]->num_hces1, $user[0]->lin_hces1, $user[0]->cod_cli, $user[0]->email_cli, 'L');
                    } else {
                        //marcar como error en envio
                        $msg = "Error al  notificar al cliente. Revise el email del cliente, puede contener caracteres no válidos";
                        $subject = trans(\Config::get('app.theme') . '-app.emails.subject_last_call');
                        $this->emailAdminError($msg, $subject, $user[0]->sub_asigl0, $user[0]->ref_asigl0, $user[0]->num_hces1, $user[0]->lin_hces1, $user[0]->cod_cli, null);
                        $mailquery->sendedLastCall($ids_web_email_last_call, 'E');
                    }
                } else {
                    $msg = "Error al  notificar al cliente. El cliente no tiene email";
                    $subject = trans(\Config::get('app.theme') . '-app.emails.subject_last_call');
                    $this->emailAdminError($msg, $subject, $user[0]->sub_asigl0, $user[0]->ref_asigl0, $user[0]->num_hces1, $user[0]->lin_hces1, $user[0]->cod_cli, null);

                    //marcar como error en envio
                    $mailquery->sendedLastCall($ids_web_email_last_call, 'E');
                }
            }

            \App::setLocale($locale_actual);
        } catch (\Exception $e) {
            $this->error_email_exception('sendLastCall', $e->getMessage(), Config::get('app.emp'), $lot->sub_asigl0, $lot->ref_asigl0);
            return;
        }
    }


    public function emailConsultLot()
    {


        $subasta = new Subasta();
        $subasta->cod = Request::input('subasta');
        $subasta->lote =  Request::input('lot');
        $email = Request::input('email');
        $telf = Request::input('telf');
        $name = Request::input('name');
        $comentario = Request::input('comentario');

        $inf_lot = head($subasta->getLote(false, false));
        $img = '<img src="' . Config::get('app.url') . '/img/load/lote_small/' . Config::get('app.emp') . '-' . $inf_lot->numhces_asigl0 . '-' . $inf_lot->linhces_asigl0 . '.jpg' . '"><br>';

        $emailOptions['user'] = "WEB";
        $emailOptions['to'] =  Config::get('app.admin_email');
        $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.asunto_consult_lot');
        $emailOptions['content'] = trans_choice(\Config::get('app.theme') . '-app.emails.texto_consult_lot', 1, [
            'anme' => $name, 'sub' =>  $inf_lot->des_sub, 'ref' => $inf_lot->ref_asigl0, 'img' => $img, 'desc' => $inf_lot->desc_hces1, 'email' => $email, 'telf' => $telf, 'text' => $comentario
        ]);

        if (\Tools::sendMail('notification', $emailOptions) != true) {
            \Log::info('Mail sent consultar lote referencia:' . $inf_lot->ref_asigl0 . 'codigo subasta:' . $inf_lot->sub_asigl0);
            $res = array(
                "status" => "error",
                "msg"     => 'consult-department'
            );
        } else {
            $res = array(
                "status" => "success",
                "msg"     => 'consult-department'
            );
        }

        return $res;
    }

    public function mailToAdminPeticionCatalogo()
    {
        $user = new User();
        $user->cod_cli = Session::get('user.cod');
        $user_email = head($user->getUserByCodCli('N'));
        $content =  '<h1>Peticion de catalogo</h1><br>Codigo Cliente: ' . $user_email->cod_cli . '<br>Cliente: ' . $user_email->rsoc_cli . '<br>Email: ' . $user_email->email_cli . '<br>Dirección: ' . $user_email->dir_cli . ', ' . $user_email->cp_cli . ', ' . $user_email->pob_cli . '<br>Telefono: ' . $user_email->tel1_cli;
        $emailOptions['user'] = "WEB";
        $emailOptions['to'] =  Config::get('app.admin_email');
        $emailOptions['subject'] = 'Petición de catalogo';
        $emailOptions['content'] = $content;

        if (\Tools::sendMail('notification', $emailOptions) != true) {
            \Log::info('Mail sent Petición de catalogo');
            $res = array(
                "status" => "error",
            );
        } else {
            $res = array(
                "status" => "success",
            );
        }
        return $res;
    }

    public function acceptNews()
    {
        $user = new User();
        $user->email =  trim(strtolower(Request::input('email')));
        $inf_user = $user->getUserByEmail(false);
        if (!empty($inf_user) && $inf_user[0]->cod_cliweb == 0 && $inf_user[0]->nllist1_cliweb == 'N') {
            DB::table('FXCLIWEB')
                ->where('GEMP_CLIWEB', Config::get('app.gemp'))
                ->where('EMP_CLIWEB', Config::get('app.emp'))
                ->where('EMAIL_CLIWEB', $user->email)
                ->update(
                    ['NLLIST1_CLIWEB' => 'S']
                );
        }
        return redirect(\Routing::translateSeo('pagina') . 'accept_news');
	}


	public function sendEmailCloseAucion($cod_sub, $email){

		//Dejo preparado switch por si en un futuro se añaden más correos
		switch ($email) {
			case 'AUCTION_REPORT':
				$this->sendEmailAuctionReport($cod_sub);
				break;

			default:
				# code...
				break;
		}
		return;
	}


	public function sendEmailAuctionReport($cod_sub){

		$email = new EmailLib('AUCTION_REPORT');
		$mailquery = new MailQueries;

		if(empty($email)){
			return;
		}

		$emp = Config::get('app.emp');
		$pdfController = new PdfController();

		$subasta = new Subasta();
        $subasta->cod = $cod_sub;
        $subasta->page = 'all';
		$info = $subasta->getInfSubasta();

		try {
			//Titulo del reportes
			$reportTitleBidsReport = trans(\Config::get('app.theme') . '-app.reports.lots_report');
			$reportTitleAwardsReport = trans(\Config::get('app.theme') . '-app.reports.awards_report');

			$pdfController->generateAuctionBidsReportPdf($info, $reportTitleBidsReport);
			$pdfController->generateAuctionAwardsReportPdf($info, $reportTitleAwardsReport);

			//generamos y guardamos archivos
			$pdfController->savePdfs($info->cod_sub, null);

			$email->setAuction_code($cod_sub);
			$email->setTo(Config::get('app.admin_email'));

			$email->attachments = $pdfController->getPathsPdfs([$reportTitleBidsReport,$reportTitleAwardsReport]);

			if(!$email->send_email()){
				return;
			};
			$mailquery->updateWebEmailCloseAuction($emp, $cod_sub, 'AUCTION_REPORT', 'S');

		} catch (\Exception $e) {
			Log::error($e);
            $this->error_email_exception('sendEmailAuctionClose', $e->getMessage(), $emp, $cod_sub, '');
            return;
        }
	}

	public function sendCompletLotReport($cod_sub, $ref)
	{
		$pdfController = new PdfController();
		$pdfController->generateCompletLotReport($cod_sub, $ref, "Informe Lote $ref de subasta $cod_sub");
		$pdfController->savePdfs($cod_sub, $ref);

		$email = new EmailLib('AUCTION_REPORT');
		if (!empty($email->email)) {
			$email->attachments = $pdfController->getPathsPdfs(["Informe Lote $ref de subasta $cod_sub"]);
			$email->setAuction_code($cod_sub);
			$email->setTo(Config::get('app.admin_email'));
			$email->send_email();
		} else {
			Log::info("email AUCTION_REPORT no enviado, no existe o está deshabilitadio");
		}

		return;
	}

    public function sendEmailCerradoGeneric($emp, $cod_sub, $ref)
    {

        try {

            $bloque = new Bloques();
            $mailquery = new MailQueries;

            $emailOptions = array(
                'user'      => Config::get('app.name'),
                'email'     => Config::get('app.admin_email'),
            );


            $hoy = date("Y-m-d");
            $licitadores = array();
            $user = new User();
            $subasta = new Subasta();
            $subasta->ref = $ref;
            $subasta->cod = $cod_sub;
            $subasta->lote = $ref;
            $subasta->page = 'all';
            $user->cod = $cod_sub;
			//Informacion del lote
			$pdfController = new PdfController();

            $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'S');

            $inf_lot = head($subasta->getLote());

            if (empty($inf_lot)) {
                $mailquery->updateWebEmailCloslot($emp, $cod_sub, $ref, 'V');

                //$this->emailAdminError("Error al enviar notificaciones, no se han podido enviar ya que el lote no existe, <br> Código de subasta: $cod_sub <br>Referencia del lote: $ref");
                $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no existe";
                $subject = trans(\Config::get('app.theme') . '-app.emails.asunto_lote_adjudicado');
                $this->emailAdminError($msg, $subject, $cod_sub, $ref, NULL, NULL, NULL, NULL);

                return;
            }
			$id_auc_sessions = $subasta->getIdAucSessionslote($subasta->cod, $subasta->ref);
            $inf_lot->id_auc_sessions  = $id_auc_sessions;
			$inf_subasta = $subasta->getInfSubasta();

            $inf_lot_translate = $subasta->getMultilanguageTextLot($inf_lot->num_hces1, $inf_lot->lin_hces1);

            $img = '<img src="' . Config::get('app.url') . '/img/load/lote_small/' . Config::get('app.emp') . '-' . $inf_lot->numhces_asigl0 . '-' . $inf_lot->linhces_asigl0 . '.jpg' . '"><br>';

            $adjudicado = $subasta->get_csub($emp);
			$get_pujas = $subasta->getPujas(false, $cod_sub);

            if (empty($adjudicado)) {

				$email = new EmailLib('LOT_NOT_AWARD_ADMIN');
				$admin_email = Config::get('app.admin_email');

				if (!empty($email->email)) {
					$email->setLot($cod_sub, $ref);

					if(Config::get('app.informes_pdf_user', 0) || Config::get('app.informes_pdf', 0)){
						$pdfController->setBids($get_pujas, true);
						$pdfController->generateWithNotAward($inf_subasta, $inf_lot);
						$pdfController->savePdfs($inf_subasta->cod_sub, $inf_lot->ref_asigl0);

						$admin_email = Config::get('app.admin_email_subasta_online');
						$email->attachments = $pdfController->getPathsPdfs();
					}

					$email->setTo($admin_email);


					$email->send_email();
				}

                return;
			}



            //creamos un array con los pujadores no adjudicados del lote
            foreach ($get_pujas as $get_value_pujas) {
                //si no ha ganado nadie o el que gano noes el pujador actual  y el licitador n oes el dummy
                if ((empty($adjudicado) ||  $adjudicado->licit_csub != $get_value_pujas->cod_licit) && (Config::get('app.dummy_bidder') != $get_value_pujas->cod_licit)) {
                    $licitadores[$get_value_pujas->cod_licit] = $get_value_pujas->cod_licit;
                }
			}


			if(Config::get('app.informes_pdf_user', 0) || Config::get('app.informes_pdf', 0)){
				if(!empty($inf_lot->prop_hces1)){
					$propietary = FxCli::select('RSOC_CLI')->where('COD_CLI', $inf_lot->prop_hces1)->first();
				}

				$tableInfo = [
					trans(\Config::get('app.theme').'-app.reports.prop_hces1') => $propietary->rsoc_cli ?? '',
					trans(\Config::get('app.theme').'-app.reports.lote_aparte') => $inf_lot->loteaparte_hces1 ?? '',
					trans(\Config::get('app.theme').'-app.reports.auction_code') => $inf_subasta->cod_sub,
					trans(\Config::get('app.theme').'-app.reports.lot_code') => $inf_lot->ref_asigl0,
					trans(\Config::get('app.theme').'-app.reports.date_start') => Tools::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'd/m/Y'),
					trans(\Config::get('app.theme').'-app.reports.hour_start') => Tools::getDateFormat($inf_subasta->start, 'Y-m-d H:i:s', 'H:i:s'),
					trans(\Config::get('app.theme').'-app.reports.date_end') => Tools::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'd/m/Y'),
					trans(\Config::get('app.theme').'-app.reports.hour_end') => Tools::getDateFormat($inf_subasta->end, 'Y-m-d H:i:s', 'H:i:s'),
				];

				$pdfController->setTableInfo($tableInfo);
				$pdfController->setBids($get_pujas, true);

				$pdfController->generateBidsPdf();
				$pdfController->generateClientsPdf();
				$pdfController->generateAwardLotPdf($propietary->rsoc_cli, $inf_lot->ref_asigl0, $adjudicado->licit_csub, $adjudicado->himp_csub);

				$pdfController->savePdfs($inf_subasta->cod_sub, $inf_lot->ref_asigl0);
			}

            $email = new EmailLib('LOT_AWARD');
            if (!empty($email->email)) {

				$email->setUserByLicit($cod_sub, $adjudicado->licit_csub, true);

                $email->setLot($cod_sub, $ref);
                $email->setPriceAdjudication($cod_sub, $ref);
				$email->setAtribute('DESCDET_HCES1', $inf_lot_translate[strtoupper(Config::get('app.locale'))]->descdet_hces1);

				if(Config::get('app.informes_pdf_user', 0)){
					$email->attachments = $pdfController->getPathsPdfs([
						trans(\Config::get('app.theme') . '-app.reports.bid_report'),
						trans(\Config::get('app.theme') . '-app.reports.client_report'). "_$adjudicado->licit_csub",
						trans(\Config::get('app.theme') . '-app.reports.bidder_report'). "_$adjudicado->licit_csub"
					]);
				}

				if(config('app.emailOwnerInformation', 0)){
					$email->setPropInfo($cod_sub, $ref);
				}

				//para stn, añadir copia de envío a email_clid de W1
				if(config('app.sendToContactEmail')){
					$clientCode = $email->getAtribute('CLIENT_CODE');
					$contactDirection = FxClid::where([
						['CODD_CLID', 'W1'],
						['CLI_CLID', $clientCode]
					])->first();

					if(!empty($contactDirection->email_clid)){
						$email->setBcc($contactDirection->email_clid);
					}

				}

                $email->send_email();
			}

            if (!empty(Config::get('app.admin_email_subasta_online'))) {
                $admin_email = Config::get('app.admin_email_subasta_online');

                $email = new EmailLib('LOT_SOLD_ADMIN');
                if (!empty($email->email)) {
                    $email->setUserByLicit($cod_sub, $adjudicado->licit_csub, false);
                    $email->setPriceAdjudication($cod_sub, $ref);
                    $email->setAuction_code($cod_sub);
                    $email->setLot_ref($ref);
                    $email->setAtribute('DESCDET_HCES1', $inf_lot_translate[strtoupper(Config::get('app.locale'))]->descdet_hces1);
					$email->setTo($admin_email);

					if(Config::get('app.informes_pdf', 0)){
						$email->attachments = $pdfController->getPathsPdfs();
					}

					$email->send_email();
                }
            }

            //Licitadores no adjudicados
            foreach ($licitadores as $licitador) {
                $email = new EmailLib('LOST_AWARD_LOT');
                if (!empty($email->email)) {
                    $email->setUserByLicit($cod_sub, $licitador, true);
					$email->setLot($cod_sub, $ref);

					if(Config::get('app.informes_pdf_user', 0)){
						$email->attachments = $pdfController->getPathsPdfs([
							trans(\Config::get('app.theme') . '-app.reports.bid_report'),
							trans(\Config::get('app.theme') . '-app.reports.client_report'). "_$licitador",
							trans(\Config::get('app.theme') . '-app.reports.bidder_report'). "_$licitador"
						]);
					}

                    $email->send_email();
                }
			}


			//Propietario
			if(Config::get('app.mail_prop_online', 0)){
				$this->EmailsAdjudicacionesCedente($cod_sub, $ref);
			}

        } catch (\Exception $e) {
			\Log::error("Error en sendEmailCerrado:" . $e);
            $this->error_email_exception('sendEmailCerrado', $e->getMessage(), $emp, $cod_sub, $ref);
            return;
        }
	}

    //Email de cancelar puja
    public function emailCancelBid($cod_sub, $ref, $cod_licit)
    {
        $send_email_cancel_bid = \Config::get('app.send_email_cancel_bid');
        if (empty($send_email_cancel_bid)) {
            return;
        }
        $subasta = new Subasta();

        $subasta->cod = $cod_sub;
        $subasta->ref = $ref;
        $subasta->lote = $ref;
        $subasta->page = 'all';

        $email = new EmailLib('CANCEL_BID');
        if (!empty($email->email)) {
            $email->setUserByLicit($cod_sub, $cod_licit, true);
            $email->setLot($cod_sub, $ref);
            $email->send_email();
        }

        //si $send_email_cancel_bid es 2  debe enviar al resto de usuarios, por lo que si no es 2 salimos
        if ($send_email_cancel_bid != 2) {
            return;
        }
        $pujas = $subasta->getPujas(false, $cod_sub);
        $clients_pujas = array();

        foreach ($pujas as $get_value_pujas) {
            if ((Config::get('app.dummy_bidder') != $get_value_pujas->cod_licit && $get_value_pujas->cod_licit != $cod_licit)) {
                $clients_pujas[$get_value_pujas->cod_licit] = $get_value_pujas->cod_licit;
            }
        }

        foreach ($clients_pujas as $client) {

            $puja               = $pujas[0];
            $max_puja           = $puja->imp_asigl1;

            $email = new EmailLib('CANCEL_BID_OTHER_USERS');
            if (!empty($email->email)) {
                $email->setUserByLicit($cod_sub, $client, true);
                $email->setLot($cod_sub, $ref);
                $email->setPrice($max_puja);
                $email->send_email();
            }
        }
    }

    //Email Factura Generada
    public function emailFacturaGenerated()
    {
        $fact = new Facturas();
        if (empty($_GET["anum"]) || empty($_GET["num"])) {
            return;
        }

        $anum = $_GET["anum"];
        $num = $_GET["num"];

        $fact->serie = $_GET["anum"];
        $fact->numero = $_GET["num"];

        $inf_user = $fact->cliFact();

        if (empty($inf_user)) {
            return;
        }

        //buscamos si la factura esta generada
        $tipo_fact = $fact->bill_text_sub(substr($fact->serie, 0, 1), substr($fact->serie, 1));

        if (empty($tipo_fact)) {
            return;
        }

        \App::setLocale(strtolower($inf_user->idioma_cli));

        if ($tipo_fact->tv_contav == 'T') {
            $inf_fact['T'][$tipo_fact->tv_contav] = $fact->getFactTexto();
        } elseif ($tipo_fact->tv_contav == 'L' || $tipo_fact->tv_contav == 'P') {
            $inf_fact['S'][$tipo_fact->tv_contav] = $fact->getFactSubasta();
        }

        if (empty($inf_fact)) {
            return;
        }

        $ref_lots = '';
        foreach ($inf_fact as $key_bill => $bills) {
            foreach ($bills as $key_type => $inf) {
                foreach ($inf as $cont => $fact) {
                    $ref_lots .= $fact->ref_dvc1l . ', ';
                }
            }
        }

        $utm_email = '';
        if (!empty(Config::get('app.utm_email'))) {
            $utm_email = Config::get('app.utm_email') . '&utm_campaign=factura_disponible';
        }

        \App::setLocale(strtolower($inf_user->idioma_cli));
        $emailOptions = array(
            'subject' => trans(\Config::get('app.theme') . '-app.emails.subject_generated_facturas'),
            'user' => $inf_user->nom_cli,
            'to'  => $inf_user->email_cli,
            'UTM' => $utm_email,
            'ref_lots' => substr($ref_lots, 0, -2),
            'num_lots' => $num
        );

        $emailOptions['content']['factura'] = $inf_fact;
        $emailOptions['content']['cod_factura'] = $anum . '/' . $num;

        if (\Tools::sendMail('factura_generated', $emailOptions) != true) {
            \Log::error("Error mandar email Factura generada cod sub: $adj->sub_csub, ref:$adj->ref_csub ");
        }
    }

    //Email lotes pendientes de pago
    public function emailLotePendingPay($id_key, $days)
    {
        $subasta = new Subasta();
        $pay =  new PaymentsController();
        $payments = new Payments();
        $mailquery = new MailQueries;
        $utm_email = '';
        if (!empty(Config::get('app.utm_email'))) {
            $utm_email = Config::get('app.utm_email') . '&utm_campaign=pago_pendiente';
        }


        $emailOptions = array(

            'UTM' => $utm_email
        );

        $date = date("Y-m-d", strtotime("-$days day", strtotime("now")));

        $inf_adj = $payments->lots_pending_pay($date);

        if (empty($inf_adj)) {
            $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no esta adjudicado o noexiste el usuario";
            $subject = trans(\Config::get('app.theme') . '-app.emails.subject_pending_payment');
            $this->emailAdminError($msg, $subject, null, null, NULL, NULL, NULL, NULL);
            return;
        }

        $emailOptions['lot'] = array();
        foreach ($inf_adj as $adj_day) {

            foreach ($adj_day as $adj) {
                //Ponemos el idioma del cliente
                \App::setLocale(strtolower($adj->idioma_cli));
                $subasta->cod = $adj->sub_csub;
                $subasta->lote = $adj->ref_csub;

                //Buscamos Lote
                $lot = $subasta->getLote(false, false);

                if (empty($lot)) {
                    $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no ";
                    $subject = trans(\Config::get('app.theme') . '-app.emails.subject_pending_payment');
                    $this->emailAdminError($msg, $subject, $subasta->cod, $subasta->lote, NULL, NULL, NULL, NULL);
                } else {
                    $lot = head($lot);

                    $emailOptions['user'] = $adj->nom_cli;
                    $emailOptions['to'] = $adj->email_cli;

                    $iva = $pay->getIva(Config::get('app.emp'), $date);
                    $tipo_iva = $pay->user_has_Iva(Config::get('app.gemp'), $adj->clifac_csub);
                    $tax = $pay->calculate_iva($tipo_iva->tipo, $iva, $adj->base_csub);

                    $precio = $tax + $adj->himp_csub + $adj->base_csub;

                    $content = new \stdClass();
                    if ($id_key == 2) {
                        $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_pending_payment_lastcall');
                        $content->title = trans(\Config::get('app.theme') . '-app.emails.subject_pending_payment_lastcall');
                        $content->text = trans_choice(\Config::get('app.theme') . '-app.emails.contenido_pending_payment_lastcall', 1, ['date' => date("d-m-Y", strtotime($lot->ffin_asigl0))]);
                        $content->final_text_up_button = trans(\Config::get('app.theme') . '-app.emails.contenido_up_button_pending_payment_lastcall');
                    } else {
                        $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_pending_payment');
                        $content->title = trans(\Config::get('app.theme') . '-app.emails.pending_payment');
                        $content->text = trans_choice(\Config::get('app.theme') . '-app.emails.contenido_pending_payment', 1, ['date' => date("d-m-Y", strtotime($lot->ffin_asigl0))]);
                        $content->final_text_up_button = trans(\Config::get('app.theme') . '-app.emails.contenido_up_button_pending_payment');
                    }
                    $content->hide_thanks = true;
                    $content->final_text_up_button = trans(\Config::get('app.theme') . '-app.emails.contenido_up_button_pending_payment');
                    $content->button = trans(\Config::get('app.theme') . '-app.emails.button_pay');
                    $content->url_button =  Config::get('app.url') . \Routing::slug('user/panel/allotments/outstanding');
                    $content->block_help = 1;
                    $emailOptions['content'] = $content;

                    $email_lot = new \stdClass();
                    $email_lot->ref = $lot->ref_asigl0;
                    $email_lot->sub = trans(\Config::get('app.theme') . '-app.user_panel.auctions_online');
                    $email_lot->desc = $lot->desc_hces1;
                    $email_lot->img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $lot->num_hces1 . '-' . $lot->lin_hces1 . '.jpg';
                    $email_lot->price_himp = $adj->himp_csub;
                    $email_lot->price_base = $adj->base_csub;
                    $email_lot->price_tax = $tax;
                    $email_lot->price_pay = $precio;
                    $emailOptions['lot'][] = $email_lot;
                }
            }
            if (!empty($emailOptions['lot']) && \Tools::sendMail('emails_automaticos', $emailOptions) != true) {
                \Log::error("Error mandar email lote pendiente de pago cod sub: $adj->sub_csub, ref:$adj->ref_csub ");
            } else {
                $mailquery->setEmailLogs('WEBLICIT05', $lot->sub_asigl0, $lot->ref_asigl0, $lot->num_hces1, $lot->lin_hces1, $adj->cod_cli, $adj->email_cli, 'L');
            }
        }
    }

    //Recordatorio de recogida del loto comprador
    public function lot_redy_collect($id_key, $day)
    {

        $subasta = new Subasta();
        $mailquery = new MailQueries;
        $enterprise = new Enterprise();

        $utm_email = '';
        if (!empty(Config::get('app.utm_email'))) {
            $utm_email = Config::get('app.utm_email') . '&utm_campaign=recordatorio_recogida';
        }

        $emailOptions = array(
            'UTM' => $utm_email
        );


        $date = date("Y-m-d", strtotime("-$day day", strtotime("now")));

        $collect = $mailquery->redy_collect($date);

        if (empty($collect)) {
            return;
        }

        foreach ($collect as $inf) {
            $emailOptions['lot'] = array();
            //Ponemos el idioma del cliente
            \App::setLocale(strtolower($inf->idioma_cli));
            $subasta->cod = $inf->sub_csub;
            $subasta->lote = $inf->ref_csub;

            //Buscamos Lote
            $lot = $subasta->getLote(false, false);

            if (!empty($lot)) {

                $lot = head($lot);
                $almacen = $enterprise->getAlmacen($lot->alm_hces1);

                $content = new \stdClass();
                if ($id_key == 1) {
                    $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_collect_lot');
                    $content->block_help = true;
                    $content->title = trans(\Config::get('app.theme') . '-app.emails.title_collect_lot_more_days');
                    $content->text = trans(\Config::get('app.theme') . '-app.emails.contenido_collect_lot_more_days');
                } else {
                    $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_collect_lot');
                    $content->title = trans(\Config::get('app.theme') . '-app.emails.title_collect_lot');
                    $content->text = trans(\Config::get('app.theme') . '-app.emails.contenido_collect_lot');
                    $content->final_text_up_button = trans(\Config::get('app.theme') . '-app.emails.up_button_collect_lot');
                }
                $content->hide_thanks = true;
                $content->block_help = 1;
                $emailOptions['content'] = $content;

                $email_lot = new \stdClass();
                $email_lot->ref = $lot->ref_asigl0;
                $email_lot->desc = $lot->desc_hces1;
                $email_lot->img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $lot->num_hces1 . '-' . $lot->lin_hces1 . '.jpg';
                $email_lot->ubic_lot = $almacen;
                $email_lot->date_fact = $inf->vto_cobro1;
                $emailOptions['lot'][] = $email_lot;
                $emailOptions['user'] = $inf->nom_cli;
                $emailOptions['to'] = $inf->email_cli;

                if (\Tools::sendMail('emails_automaticos', $emailOptions) != true) {
                    \Log::error("Error mandar email lote pendiente de recogida cod sub: $inf->sub_csub, ref:$inf->ref_csub ");
                } else {
                    $mailquery->setEmailLogs('WEBLICIT06', $lot->sub_asigl0, $lot->ref_asigl0, $lot->num_hces1, $lot->lin_hces1, $inf->cod_cli, $inf->email_cli, 'L');
                }
            } else {
                $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no ";
                $subject = trans(\Config::get('app.theme') . '-app.emails.subject_collect_lot');
                $this->emailAdminError($msg, $subject, $subasta->cod, $subasta->lote, NULL, NULL, NULL, NULL);
            }
            die();
        }
    }

    //Recordatorio de recogida del loto cedente
    public function lot_redy_collect_cedente($id_key, $day)
    {

        $subasta = new Subasta();
        $mailquery = new MailQueries;
        $enterprise = new Enterprise();

        $utm_email = '';
        if (!empty(Config::get('app.utm_email'))) {
            $utm_email = Config::get('app.utm_email') . '&utm_campaign=recogida_cedente';
        }

        $emailOptions = array(
            'UTM' => $utm_email
        );

        $day = 0;
        $date = date("Y-m-d", strtotime("-$day day", strtotime("now")));

        $collect = $mailquery->return_lot_cedente($date);

        if (empty($collect)) {
            return;
        }

        foreach ($collect as $inf) {
            $inf_lot_translate = $subasta->getMultilanguageTextLot($inf->num_hces1, $inf->lin_hces1);

            $emailOptions['lot'] = array();
            //Ponemos el idioma del cliente
            \App::setLocale(strtolower($inf->idioma_cli));
            \App::setLocale(strtolower('ES'));
            $almacen = $enterprise->getAlmacen($inf->alm_hces1);

            $content = new \stdClass();
            $id_key = 1;
            if ($id_key == 2) {
                $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_collect_lot_15days');
                $content->title = trans(\Config::get('app.theme') . '-app.emails.title_collect_lot_more_days');
                $content->text = trans(\Config::get('app.theme') . '-app.emails.contenido_collect_lot_cedente_15days');
            } else {
                if ($id_key == 1) {
                    $date_send_email = date("d-m-Y", strtotime("-$day day", strtotime("now")));
                    $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_collect_lot_7days');
                    $content->text = trans(\Config::get('app.theme') . '-app.emails.contenido_collect_lot_cedente_7days');
                    $content->final_text_up_button = trans_choice(\Config::get('app.theme') . '-app.emails.up_button_collect_lot_cedente_7days', 1, ['date' => $date_send_email]);
                } else {
                    $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_collect_lot');
                    $content->text = trans(\Config::get('app.theme') . '-app.emails.contenido_collect_lot_cedente');
                    $content->final_text_up_button = trans(\Config::get('app.theme') . '-app.emails.up_button_collect_lot_cedente');
                }
                $content->title = trans(\Config::get('app.theme') . '-app.emails.title_collect_lot');
            }
            $content->block_help = 1;
            $emailOptions['content'] = $content;

            $email_lot = new \stdClass();
            $email_lot->contrato = $inf->num_hces1 . '/' . $inf->lin_hces1;
            $email_lot->ref = $inf->ref_hces1;
            $email_lot->desc = $inf_lot_translate[$inf->idioma_cli]->desc_hces1;
            $email_lot->img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $inf->num_hces1 . '-' . $inf->lin_hces1 . '.jpg';
            $email_lot->ubic_lot = $almacen;
            $emailOptions['lot'][] = $email_lot;
            $emailOptions['user'] = $inf->nom_cli;
            $emailOptions['to'] = $inf->email_cli;

            if (\Tools::sendMail('emails_automaticos', $emailOptions) != true) {
                \Log::error("Error mandar email lote pendiente de recogida cod sub: $inf->sub_csub, ref:$inf->ref_csub ");
            } else {
                die();
                $mailquery->setEmailLogs('WEBPROP04', $inf->sub_hces1, $inf->ref_hces1, $inf->num_hces1, $inf->lin_hces1, $inf->cod_cli, $inf->email_cli, 'L');
            }
            die();
        }
    }

    //Lote desadjudicado
    public function disbandment_lot()
    {
        $user = new User();
        $subasta = new Subasta();
        $mailquery = new MailQueries();

        if (empty($_GET["cod"]) || empty($_GET["ref"]) || empty($_GET["cli"])) {
            return;
        }

        $cod = $_GET["cod"];
        $ref = $_GET["ref"];
        $cli = $_GET["cli"];

        $utm_email = '';
        if (!empty(Config::get('app.utm_email'))) {
            $utm_email = Config::get('app.utm_email') . '&utm_campaign=desadjudicacion_comprador';
        }

        $emailOptions = array(
            'UTM' => $utm_email
        );

        $subasta->cod = $cod;
        $subasta->lote = $ref;
        $inf_lot = $subasta->getLoteLight();

        if (empty($inf_lot)) {
            $msg = "Error al enviar notificaciones, no se han podido enviar ya que el lote no existe ";
            $subject = trans(\Config::get('app.theme') . '-app.emails.subject_disbandment_lot');
            $this->emailAdminError($msg, $subject, $cod, $ref, NULL, NULL, NULL, NULL);
            return;
        }

        $img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $inf_lot->numhces_asigl0 . '-' . $inf_lot->linhces_asigl0 . '.jpg';

        //Email al comprador
        $user->cod_cli = $cli;
        $inf_cli = $user->getUserByCodCli();
        if (!empty($inf_cli) && !empty($inf_cli[0]->email_cli)) {
            $inf_cli = head($inf_cli);

            //Ponemos el idioma del cliente
            \App::setLocale(strtolower($inf_cli->idioma_cli));
            $content = new \stdClass();

            $content->block_help = true;
            $content->title = trans(\Config::get('app.theme') . '-app.emails.title_disbandment_lot_licit');
            $content->text = trans_choice(\Config::get('app.theme') . '-app.emails.contenido_disbandment_lot_licit', 1, ['ref' => $inf_lot->ref_asigl0]);
            $emailOptions['content'] = $content;

            $email_lot = new \stdClass();
            $email_lot->ref = $inf_lot->ref_asigl0;
            $email_lot->desc = $inf_lot->desc_hces1;
            $email_lot->sub = trans(\Config::get('app.theme') . '-app.user_panel.auctions_online');
            $email_lot->img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $inf_lot->num_hces1 . '-' . $inf_lot->lin_hces1 . '.jpg';
            $emailOptions['lot'][] = $email_lot;

            $emailOptions['user'] = $inf_cli->nom_cli;
            $emailOptions['to'] = $inf_cli->email_cli;
            $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_disbandment_lot');

            if (\Tools::sendMail('emails_automaticos', $emailOptions) != true) {
                \Log::error("Error mandar email desadjudicacion cod sub: $inf_lot->sub_csub, ref:$inf_lot->ref_csub ");
            } else {
                $mailquery->setEmailLogs('WEBLICIT07', $inf_lot->sub_asigl0, $inf_lot->ref_asigl0, $inf_lot->num_hces1, $inf_lot->lin_hces1, null, null, 'L');
            }
        } else {

            $msg = "Error al enviar notificaciones, no se han podido enviar ya que el licitador no existe o email incorrecto ";
            $subject = trans(\Config::get('app.theme') . '-app.emails.subject_disbandment_lot');
            $this->emailAdminError($msg, $subject, $cod, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $cli, NULL);
        }

        $utm_email = '';
        if (!empty(Config::get('app.utm_email'))) {
            $utm_email = Config::get('app.utm_email') . '&utm_campaign=desadjudicacion_cedente';
        }

        $emailOptions = array(
            'UTM' => $utm_email
        );

        $titularidad = $this->titularidad($inf_lot->num_hces1, $inf_lot->lin_hces1, $inf_lot->prop_hces1);

        foreach ($titularidad as $titular) {
            //Email al comprador
            $user->cod_cli = $titular;
            $inf_cli = $user->getUserByCodCli();
            if (!empty($inf_cli) && !empty($inf_cli[0]->email_cli)) {
                $emailOptions['lot'] = array();
                $inf_cli = head($inf_cli);

                //Ponemos el idioma del cliente
                \App::setLocale(strtolower($inf_cli->idioma_cli));
                $content = new \stdClass();

                $content->block_help = true;
                $content->title = trans(\Config::get('app.theme') . '-app.emails.title_disbandment_lot_cedente');
                $content->text = trans_choice(\Config::get('app.theme') . '-app.emails.contenido_disbandment_lot_cedente', 1, ['ref' => $inf_lot->ref_asigl0]);
                $content->final_text_up_button = trans_choice(\Config::get('app.theme') . '-app.emails.up_button_disbandment_lot_cedente', 1, ['price' => $inf_lot->impsalhces_asigl0]);
                $content->hide_thanks = true;
                $content->button = trans(\Config::get('app.theme') . '-app.emails.button_go_to_account');
                $content->url_button = Config::get('app.url') . \Routing::slug('user/panel/sales');
                $emailOptions['content'] = $content;

                $email_lot = new \stdClass();
                $email_lot->contrato = $inf_lot->num_hces1 . '/' . $inf_lot->lin_hces1;
                $email_lot->ref = $inf_lot->ref_asigl0;
                $email_lot->desc = $inf_lot->desc_hces1;
                $email_lot->sub = trans(\Config::get('app.theme') . '-app.user_panel.auctions_online');
                $email_lot->img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $inf_lot->num_hces1 . '-' . $inf_lot->lin_hces1 . '.jpg';
                $emailOptions['lot'][] = $email_lot;

                $emailOptions['user'] = $inf_cli->nom_cli;
                $emailOptions['to'] = $inf_cli->email_cli;
                $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.subject_disbandment_lot');
                if (\Tools::sendMail('emails_automaticos', $emailOptions) != true) {
                    \Log::error("Error mandar email desadjudicacion cod sub: $inf_lot->sub_csub, ref:$inf_lot->ref_csub ");
                } else {
                    $mailquery->setEmailLogs('WEBLICIT07', $inf_lot->sub_asigl0, $inf_lot->ref_asigl0, $inf_lot->num_hces1, $inf_lot->lin_hces1, null, null, 'L');
                }
            } else {
                $msg = "Error al enviar notificaciones, no se han podido enviar ya que el licitador no existe o email incorrecto ";
                $subject = trans(\Config::get('app.theme') . '-app.emails.subject_disbandment_lot');
                $this->emailAdminError($msg, $subject, $cod, $ref, $inf_lot->numhces_asigl0, $inf_lot->linhces_asigl0, $inf_lot->prop_hces1, NULL);
            }
        }
    }

    public function not_bidder_yet($day)
    {

        $utm_email = '';
        if (!empty(Config::get('app.utm_email'))) {
            $utm_email = Config::get('app.utm_email') . '&utm_campaign=todavia_no_has_pujado';
        }

        $user_model = new User();

        $mailquery = new MailQueries();
        $date = date("Y-m-d", strtotime("-$day day", strtotime("now")));
        $users = $mailquery->getUserDontBidder($date);
        foreach ($users as $user) {
            $user_model->cod_cli = $user->cod_cliweb;
            $pujas = $user_model->getPujas();
            if (empty($pujas)) {
                \App::setLocale(strtolower($user->idioma_cliweb));

                $content = new \stdClass();
                $content->title = trans(\Config::get('app.theme') . '-app.emails.title_not_bidder');
                $content->text = trans(\Config::get('app.theme') . '-app.emails.contenido_not_bidder');
                $content->hide_thanks = true;
                $content->button = trans(\Config::get('app.theme') . '-app.emails.button_see_novelties');
                $content->url_button = \Config::get('app.url') . '/' . \Routing::slugSeo('subastas') . '/' . trans(\Config::get('app.theme') . '-app.links.all_categories');

                $emailOptions['content'] = $content;
                $emailOptions['user'] = $user->nom_cliweb;
                $emailOptions['to'] = $user->email_cliweb;
                $emailOptions['UTM'] = $utm_email;
                $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.title_not_bidder');

                if (\Tools::sendMail('emails_automaticos', $emailOptions) != true) {
                    \Log::error("Error mandar email ¿Todavía no has pujado? email: $user->email_cliweb ");
                } else {
                    $mailquery->setEmailLogs('WEBLICIT08', null, null, null, null, $user->cod_cliweb,  $user->email_cliweb, 'L');
                }
            }
        }
    }


    public function emailPujaInferior($sub, $ref, $licit, $importe)
    {

        $usercontroller = new \App\Http\Controllers\UserController();
        $user = new User();
        $subasta = new Subasta();


        $user->cod   = $sub;
        $user->licit = $licit;

        $subasta->lote = $ref;
        $subasta->cod = $sub;

        $usuario     = head($user->getFXCLIByLicit());

        if (empty($usuario)) {
            \Log::info('Error al enviar email de puja inferior, licitador no existe licit: ' . $user->licit);
            return false;
        }

        $email = new EmailLib('BID_LOWER');
        if (!empty($email->email)) {
			$email->setUserByLicit($sub, $licit, true);
			if(!empty(Config::get("app.admin_email_bid_lower"))){
				$email->setBcc(Config::get("app.admin_email_bid_lower"));
			}
            $email->setLot($sub, $ref);
            $email->setBid($importe);
            $email->send_email();
        } else {
            \Log::info("email de puja inferior No enviado, no existe o está deshabilitadio");
        }
    }

    /*public function pending_license_export(){
            $mailquery = new MailQueries();
            $lots = $mailquery->pending_license_export();
            foreach($lots as $lot){
                $lot->titular = $this->titularidad($lot->num_hces1,$lot->lin_hces1,$lot->prop_hces1);
            }


            print_r($lots);
            die();
        }*/

    function titularidad($num, $lin, $prop)
    {
        $titularidad = array();
        $subasta = new Subasta();
        $titularidad_multiple = $subasta->titularidadMultiple($num, $lin);
        if (empty($titularidad_multiple)) {
            $titularidad_multiple = $subasta->titularidadMultiple($num, '0');
        }

        if (!empty($titularidad_multiple)) {
            foreach ($titularidad_multiple as $titulares) {
                $titularidad[] = $titulares->cli_hcesmt;
            }
        } else {
            $titularidad[] = $prop;
        }
        return $titularidad;
    }

    /* function ganadorLot($emp,$cod_sub,$ref){
            $sql = "select sub.* from fgcsub sub
                    where EMP_CSUB = :emp
                    and SUB_CSUB = :subasta
                    and REF_CSUB = :ref";

            $params = array(
                'emp'       =>  $emp,
                'subasta'   =>  $cod_sub,
                'ref'   =>  $ref
                );

             return $subasta = DB::select($sql, $params);
        }

        function perdedorLot($emp,$cod_sub,$ref,$licit_csub){

            $sql = "select asigl1.LICIT_ASIGL1,cli.EMAIL_CLI,hces1.sec_hces1,hces1.num_hces1,hces1.lin_hces1 from FGASIGL1 asigl1
            JOIN FGLICIT licit on (asigl1.LICIT_ASIGL1 = licit.COD_LICIT and licit.EMP_LICIT = asigl1.EMP_ASIGL1 and licit.SUB_LICIT = asigl1.SUB_ASIGL1)
            JOIN FSEMPRES empres ON (asigl1.EMP_ASIGL1 = empres.cod_emp)
            JOIN FXCLI cli ON (empres.gemp_emp=cli.gemp_cli and licit.CLI_LICIT = cli.COD_CLI)
            JOIN FGASIGL0 asigl0 ON(asigl0.emp_asigl0 = asigl1.EMP_ASIGL1 and asigl0.REF_ASIGL0 = asigl1.REF_ASIGL1 and asigl0.SUB_ASIGL0 = asigl1.SUB_ASIGL1)
            JOIN FGHCES1 hces1 ON(hces1.EMP_HCES1 = asigl1.EMP_ASIGL1 and hces1.NUM_HCES1 = asigl0.NUMHCES_ASIGL0 and hces1.LIN_HCES1 = asigl0.LINHCES_ASIGL0)
            where asigl1.EMP_ASIGL1 = :emp
            and asigl1.SUB_ASIGL1 = :subasta
            and asigl1.REF_ASIGL1 = :ref
            and asigl1.LICIT_ASIGL1 <> :licit
            GROUP by asigl1.LICIT_ASIGL1,cli.EMAIL_CLI,hces1.sec_hces1,hces1.num_hces1,hces1.lin_hces1";

            $params = array(
                'emp'       =>  $emp,
                'subasta'   =>  $cod_sub,
                'ref'   =>  $ref,
                'licit' => $licit_csub
                );
            $subasta = DB::select($sql, $params);
            print_r($subasta);
            die();
            return $subasta;
		}*/

	/**
	 * Envia mail a admin conforme un usuario esta interesado en un lote en concreto
	 * y a usuario, la confirmación del envío del email.
	 */
    public function sendInfoLot()
    {

        $cod_licit = Request::input('cod_licit');
        $cod_sub = Request::input('cod_sub');
        $ref = Request::input('ref');

        $usercontroller = new UserController();
        $user = new User();
        $subasta = new Subasta();


        $user->cod   = $cod_sub;
        $user->licit = $cod_licit;

        $subasta->lote = $ref;
        $subasta->cod = $cod_sub;

        $usuario = head($user->getFXCLIByLicit());

        if (empty($usuario)) {
            \Log::info('Error al enviar email información, licitador no existe licit: ' . $user->licit);
            return array(
				'msg' => trans(\Config::get('app.theme') . '-app.msg_error.need_login'),
				'status' => 'error'
			);
        }

        $email = new EmailLib('INFO_LOT_ADMIN');
        if (!empty($email->email)) {
            $email->setUserByLicit($cod_sub, $cod_licit, true);
            //$email->setBcc(Config::get("app.admin_email"));
			$email->setLot($cod_sub, $ref);
			$email->setTo(Config::get('app.admin_email'));

			if(request('to_owner') && request('num_hces1') && request('lin_hces1') ){

				$emailOwner = FgHces1::select('email_cli')->getOwner()->where([
					['num_hces1', request('num_hces1')],
					['lin_hces1', request('lin_hces1')]
				])->first();

				if($emailOwner){
					$email->setCc($emailOwner->email_cli);
				}
			}

            $email->send_email();
        } else {
            \Log::info("email INFO_LOT_ADMIN no enviado, no existe o está deshabilitadio");
		}


		$email = new EmailLib('INFO_LOT');
		if (!empty($email->email)) {
            $email->setUserByLicit($cod_sub, $cod_licit, true);
			$email->setLot($cod_sub, $ref);
            $email->send_email();
        } else {
            \Log::info("email INFO_LOT no enviado, no existe o está deshabilitadio");
		}

		return array(
			'msg' => trans(\Config::get('app.theme') . '-app.msg_success.mensaje_enviado'),
			'status' => 'success'
		);

	}

	public function askInfoLot(){

		$auction = request("auction");
		$lot = request("lot");
		$info_lot = request("info_lot", false);
		$cod_user = session('user.cod', null);

		$email = new EmailLib('ASK_LOT_ADMIN');
        if (!empty($email->email)) {
			$formFields ="";
			$prohibidosAux= array("auction", "lot", "info_lot");
			$formFields = $this->processPostVars($formFields, $prohibidosAux);

			$email->setAtribute("AUCTION_NAME", $auction);
			$email->setAtribute("LOT_REF", $lot);

			if($info_lot){
				$email->setLot($auction, $lot);
			}

			if($cod_user){
				$email->setUserByCod($cod_user, false);
			}

			$email->setFormFields($formFields);
			$email->setTo(Config::get('app.admin_email'));
			if(Config::get('app.copyAdminEmailInfoLot')){

				#puede haber mas de un email
				$explode_email = explode(";",Config::get('app.copyAdminEmailInfoLot'));

                foreach($explode_email as $key=> $cc){
					$email->setCc($cc);
                }

			}

            $email->send_email();
        }
		
		$email = new EmailLib('ASK_LOT');
		if (!empty($email->email) && $cod_user) {
			$email->setAtribute("AUCTION_NAME", $auction);
			$email->setAtribute("LOT_REF", $lot);
			if($info_lot){
				$email->setLot($auction, $lot);
			}
			$email->setUserByCod($cod_user, true);
			$email->send_email();
		}
	}

	public function sendFormAuthorizeBid($fxCli, $cod_sub, $ref, $files){

		$email = new EmailLib('AUTHORIZE_BID');
		if (!empty($email->email)) {

			$rsoc_cli = '';
			if($fxCli->fisjur_cli == 'R'){
				$rsoc_cli = $fxCli->nom_cli;
			}
			elseif ($fxCli->fisjur_cli == 'J') {
				$rsoc_cli = $fxCli->rsoc_cli;
			}

			$email->setName($fxCli->fisjur_cli == 'R' ? $fxCli->rsoc_cli : $fxCli->nom_cli);
			$email->setClient_code($fxCli->cod_cli);
			$email->setEmail($fxCli->email_cli);
			$email->setCif($fxCli->cif_cli);
			$email->setAtribute("FISJUR_CLI", $fxCli->fisjur_cli == 'F' ? 'No' : 'Si');
			$email->setAtribute("RSOC_CLI", $rsoc_cli);
			$email->setLot($cod_sub, $ref);
			$email->attachmentsFiles = $files;
			$email->setTo(Config::get('app.admin_email'));
			if($email->send_email()){
				return true;
			}
			return false;
		}

	}

	public function sendCounterofferRejected($cod_licit, $cod_sub, $ref, $imp)
	{
		$email = new EmailLib('COUNTEROFFER_REJECTED');
		if(!empty($email->email)){
			$email->setUserByLicit($cod_sub, $cod_licit, true);
			$email->setLot($cod_sub, $ref);
			$email->setAtribute('PRICE_COUNTEROFFER', Tools::moneyFormat($imp, trans(\Config::get('app.theme').'-app.subastas.euros')));
			if($email->send_email()){
				return true;
			}
		}
		return false;
	}


	public function sendCounterofferToOwner($cod_cli, $cod_sub, $ref, $imp, $num_hces1, $lin_hces1, $aboveMinPrice)
	{
		$emailOwner = FgHces1::select('email_cli')->getOwner()->where([
			['num_hces1', $num_hces1],
			['lin_hces1', $lin_hces1]
		])->first();

		if(!$emailOwner){
			return false;
		}

		$email = new EmailLib('COUNTEROFFER_OWNER');

		if(!empty($email->email)){
			$email->setUserByCod($cod_cli, false);
			$email->setLot($cod_sub, $ref);

			if(config('app.carlandiaCommission', 0)){
				$email->subtractCommissionToAttributes();
				$comision = 1 + config('app.carlandiaCommission');
				$imp = $imp / $comision;
			}

			$email->setAtribute('PRICE_COUNTEROFFER', Tools::moneyFormat($imp, trans(\Config::get('app.theme').'-app.subastas.euros'), 2));
			$email->setTo($emailOwner->email_cli);
			$email->setCc(config('app.admin_email'));

			//en principio siempre llega false, pero lo mantego por si acaso
			$email->setAtribute('SUBJECT', $aboveMinPrice ? trans(config('app.theme').'-app.emails.counteroffer_owner_accept') : trans(config('app.theme').'-app.emails.counteroffer_owner_min_price'));

			if($email->send_email()){
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 */
	public function sendToOwner($cod_email, EmailLib $emailData, $num_hces1, $lin_hces1)
	{
		$emailOwner = FgHces1::select('email_cli')->getOwner()->where([
			['num_hces1', $num_hces1],
			['lin_hces1', $lin_hces1]
		])->first();

		$existDesign = $emailData->get_design($cod_email);

		if(!$emailOwner || !$existDesign){
			return false;
		}

		$emailData->setTo($emailOwner);

		return $emailData->send_email();
	}

}
