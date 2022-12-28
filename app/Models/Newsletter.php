<?php

# Ubicacion del modelo

namespace App\Models;

use App\Http\Controllers\externalws\mailing\ExternalMailingController;
use App\Http\Controllers\externalws\mailing\services\MailchimpService;
use Illuminate\Database\Eloquent\Model;
use Config;
use App\libs\EmailLib;
use DB;
use App\Models\V5\FxCliWeb;

class Newsletter extends Model {

    public $email;
    public $families;
    public $emp;
    public $gemp;
    public $lang;

    public function __construct() {
        $this->emp = Config::get('app.emp');
        $this->gemp = Config::get('app.gemp');
    }

    public function setNewsletter() {
        # Vars
        $sqls = array();
        $binds = array();

        $bindings = array(
            'gemp' => $this->gemp,
            'emp' => $this->emp,
            'email' => $this->email
        );



        /* try
          { */
        //Existe cliente web id clienet diferente 0
        $user = $this->checkUser($bindings);
        //Existe cliente web id clienet igual 0

        $bindings['cod'] = '0';
        $user_newsletter = $this->checkIfUserHaveNewsletters($bindings);

        if (Config::get('app.email_double_opt_in_recaptcha')) {
            $bindings['cod'] = '-1';
            //si está vacio debemos comprobar que no haya un registro con el codigo -1, si ya ha encontrado uno no hace falta
            if (empty($user_newsletter)) {
                $user_newsletter = $this->checkIfUserHaveNewsletters($bindings);
            }
        }

        if (!empty($user) || !empty($user_newsletter)) {
            //Si existen solo modificamos las familias
            $this->family();

            if (!empty($user)) {
                $email = new EmailLib('CHANGE_INFO_USER_NEWSLETTER');

                if (!empty($email->email)) {
                    $email->setTo(Config::get('app.admin_email'));
                    $email->setEmail($user[0]->usrw_cliweb);
                    $email->setClient_code($user[0]->cod_cliweb);

                    $email->send_email();
                }
            }
        } else {

            if (Config::get('app.email_double_opt_in_recaptcha')) {
                $value = -1;
            } else {
                $value = 0;
            }
            //Creamos el nuevo cliente y luego le ponemos las familias
            $sqls = "INSERT INTO FXCLIWEB
                        (GEMP_CLIWEB, COD_CLIWEB, USRW_CLIWEB, EMAIL_CLIWEB, EMP_CLIWEB, TIPACCESO_CLIWEB, TIPO_CLIWEB,FECALTA_CLIWEB,IDIOMA_CLIWEB)
                        VALUES
                        (:gemp, :cod, :email, :email, :emp, 'N', 'C',:fecha_alta,:lang)";

            $bindings = array(
                'cod' => $value,
                'emp' => $this->emp,
                'fecha_alta' => date("Y-m-d H:i:s"),
                'gemp' => $this->gemp,
                'email' => strtolower($this->email),
                'lang' => $this->lang,
            );
            DB::select($sqls, $bindings);
            $this->family();

            $email = new EmailLib('NEW_USER_NEWSLETTER');
            if (!empty($email->email)) {
                $email->setTo(Config::get('app.admin_email'));
                $email->setEmail($this->email);
                $email->send_email();
            }
            if (Config::get('app.email_double_opt_in_recaptcha')) {

                $email = $this->email;
                $val_fam = 'newsletter' . $email;

                \App::setLocale(strtolower($this->lang));

                //EMAIL DOBLE OPT-in
                $email = $this->email;
                $code = \Tools::encodeStr($email . '-' . $val_fam);
                $url = \Config::get('app.url') . '/' . strtolower($this->lang) . '/email-validation?code=' . $code . '&email=' . $email . '&type=newsletter';


                $emailOptions['user'] = Config::get('app.name');
                $emailOptions['to'] = $email;
                $emailOptions['subject'] = trans(\Config::get('app.theme') . '-app.emails.asunto_confirm_newsletter');
                $emailOptions['content'] = trans_choice(\Config::get('app.theme') . '-app.emails.text_confirm_newsletter', 1, ['email' => $email, 'url' => $url]);
                if (\Tools::sendMail('notification', $emailOptions)) {
                    \Log::info('Mail sent: EMAIL DOBLE OPT-in');
                } else {
                    \Log::emergency('Error mail sent: EMAIL DOBLE OPT-in');
                }

                return $result = array(
                    'status' => 'success',
                    'msg' => 'success-add_newsletter_email'
                );
            }
        }

        # Devuelve resultado.
        $result = array(
            'status' => 'success',
            'msg' => 'success-add_newsletter'
        );
        /* }
          catch (\Exception $e)
          {

          $result = array(
          'status' 	=> 'error',
          'msg' 		=> 'err-add_newsletter'
          );

          } */

        return $result;
    }

    /*
     * @DEPRECATED -> por newFamilies
     */
    //Ponemos a S para saber que family quieren recibir
    public function family() {
        for ($x = 0; $x <= 10; $x++) {
            $value = 'N';

            if ((!empty($this->families) && in_array($x, $this->families))) {
                $value = 'S';
            }
            if ($x != '0') {
                $sqls = "update FXCLIWEB "
                        . "set nllist" . $x . "_cliweb = :nllist "
                        . "where EMAIL_CLIWEB = :email and GEMP_CLIWEB = :gemp and EMP_CLIWEB = :emp and LOWER(usrw_cliweb) = LOWER(:email)";


                $bindings = array(
                    'emp' => $this->emp,
                    'email' => strtolower($this->email),
                    'gemp' => $this->gemp,
                    'nllist' => $value,
                );

                DB::select($sqls, $bindings);
            }
        }
    }

    /*
     * Metodo nuevo, substituye a family()
     */
    public function newFamilies()
	{
		$info = $this->newsletterFormat($this->families);

		FxCliWeb::where('LOWER(USRW_CLIWEB)', strtolower($this->email))
				->update($info);

		$this->subscribeToExternalService($this->email);

		//Soporte Concursal queria que también se enviara el registro en la newsletter
		$email = new EmailLib('USER_NEWSLETTER');
		if(!empty($email->email)){
			$email->setTo(strtolower($this->email));
			$email->send_email();
		}
    }

	/**
	 * formato de families
	 * [1 => 1, 4 => 1, 5 => 1, ...]
	 */
	private function newsletterFormat($families)
	{
		$newsletters = ['NLLIST1_CLIWEB' => 'S'];
		foreach (range(2, 20) as $number) {
			$newsletters["NLLIST{$number}_CLIWEB"] = (!empty($families[$number]) && $families[$number] == 1) ? 'S' : 'N';
		}
		return $newsletters;
	}

	/**
	 * Crea o modifica la información de newsletters
	 * en plataformas externas
	 */
	public function subscribeToExternalService($email_cli)
	{
		if(!$sendToExternalService = config('app.mailing_service', null)){
			return false;
		}

		$service = "App\Http\Controllers\\externalws\mailing\services\\$sendToExternalService";
		$externalMailingService = new ExternalMailingController(new $service());
		$externalMailingService->add($email_cli);

		return true;
	}

	/**
	 * Desuscribe o elimina la información de newsletters
	 * en plataformas externas
	 */
	public function unSubscribeToExternalService($email_cli)
	{
		if(!$sendToExternalService = config('app.mailing_service', null)){
			return false;
		}

		$service = "App\Http\Controllers\\externalws\mailing\services\\$sendToExternalService";
		$externalMailingService = new ExternalMailingController(new $service());
		$externalMailingService->remove($email_cli);

		return true;
	}

    public function checkUser($bindings) {
        $sql = "SELECT cli.cod_cliweb, cli.usrw_cliweb FROM FXCLIWEB cli
                    WHERE cli.GEMP_CLIWEB = :gemp AND cli.EMP_CLIWEB = :emp AND LOWER(cli.USRW_CLIWEB) = LOWER(:email) and cod_cliweb != '0'";

        return DB::select($sql, $bindings);
    }

    public function checkIfUserHaveNewsletters($bindings) {
        $sql = "SELECT cli.* FROM FXCLIWEB cli
                    WHERE cli.GEMP_CLIWEB = :gemp AND cli.EMP_CLIWEB = :emp AND LOWER(cli.USRW_CLIWEB) = LOWER(:email) and cod_cliweb = :cod";

        return DB::select($sql, $bindings);
    }

    public function updateCodNewsletter($bindings) {
        $sqls = "update FXCLIWEB "
                . "set cod_cliweb = :cod "
                . "where EMAIL_CLIWEB = :email and GEMP_CLIWEB = :gemp and EMP_CLIWEB = :emp and LOWER(usrw_cliweb) = LOWER(:email)";


        DB::select($sqls, $bindings);
    }

}
