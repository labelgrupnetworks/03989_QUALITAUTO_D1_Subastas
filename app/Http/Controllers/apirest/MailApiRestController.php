<?php

namespace App\Http\Controllers\apirest;

use App\Http\Controllers\MailController;
use Illuminate\Http\Request as Request;
use App\libs\EmailLib;
use App\Models\Subasta;
use App\Models\User;
use App\Models\V5\FgDeposito;
use App\Models\V5\FgSub;
use App\Models\V5\FxCliWeb;
use App\Models\V5\FxDvc0Seg;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class MailApiRestController extends ApiRestController {

    public function sendMail($lang)
	{
        $validate = $this->validateParams(['type', 'apikey', 'user', 'passw']);
        if (count($validate) != 0) {
            return $this->responder(false, "Need the data params", $validate, 401);
        }

		Log::debug('sendMail', ['params' => request()->all()]);

        $type = request('type');

        switch ($type){

            case "ACTIVE_ACCOUNT":
                $validate = $this->validateParams(['email']);
                if (count($validate) != 0) {
                    return $this->responder(false, "Need the data params", $validate, 401);
                }
                return $this->acctiveAcount($type);

            case "PAY_BILL":

                $validate = $this->validateParams(['codCli', 'path']);
                if (count($validate) != 0) {
                    return $this->responder(false, "Need the data params", $validate, 401);
                }

				$userModel = new User();
				$userModel->cod_cli = request('codCli');
				$isCliWeb = $userModel->isCliWeb();

                if (empty(request('url'))){
                    $type = "PAY_BILL_ONLY_BANK";
                }

				if($isCliWeb){
					$type = "PAY_BILL_WEB_LINK";
				}

                return $this->sendMailWithAttachment($type);

            case "PROVISIONAL_SETTLEMENT":
            case "RELATIONSHIP_LOTS_INCLUDED":

                $validate = $this->validateParams(['codCli', 'path', 'codSub', 'codLot']);
                if (count($validate) != 0) {
                    return $this->responder(false, "Need the data params", $validate, 401);
                }
                return $this->sendMailWithAttachment($type);

			case "CHANGE_SEG_STATE":
				$validate = $this->validateParams(['codCli', 'codSub', 'codSeg']);
				if (count($validate) != 0) {
					Log::error('sendMail - Need the data params', ['params', $validate]);
                    return $this->responder(false, "Need the data params", $validate, 401);
                }
				return $this->sendMailTrackingChange();

            case "OTHER":
                return $this->sendTestMail();
            default :
                return $this->responder(false, "the type of email is not valid", "", 401);
        }

    }

	private function sendMailTrackingChange(){

		$codCli = request('codCli');
		$codSub = request('codSub');
		$codSeg = request('codSeg');

		$emailsTemplates = [
			'1' => 'TRACKING_CHANGE_SEG_STATE_1',
			'2' => 'TRACKING_CHANGE_SEG_STATE_2',
			'3' => 'TRACKING_CHANGE_SEG_STATE_3',
			'4' => 'TRACKING_CHANGE_SEG_STATE_4',
		];

		$email = new EmailLib($emailsTemplates[$codSeg]);

		if (empty($email->email)) {
			return $this->responder(false, "the type of email is not valid", "", 401);
		}

		$email->setUserByCod($codCli, true);

		$auction = FgSub::select('dfec_sub')
			->joinLangSub()
			->where('cod_sub', $codSub)
			->first();

		$email->setAtribute('AUCTION_NAME', $auction->des_sub);

		$deliveryDate = FxDvc0Seg::getEstimatedDeliveryDate($auction->dfec_sub);

		$email->setAtribute('DELIVERY_DATE', $deliveryDate);

		$email->send_email();
		return $this->responder(true, "Email sent", "", 200);
	}

    public function sendMailWithAttachment($type)
	{

        $codCli = request('codCli');
        $user = new User();
        $user->cod_cli = $codCli;
        $user_exists = $user->getUserByCodCli();


        if (empty($user_exists) || (!empty($user_exists) && $user_exists[0]->baja_tmp_cli != 'N')) {
            return "user not exist";
        }

        $path = Request('path');
        $path = 'reports' . explode('REPORTS', $path)[1];

        $url = Request('url');

        $email = new EmailLib($type);

        if (!empty($email->email)) {

            $email->setUserByCod($codCli, true, 'en');
            $email->setTo($user_exists[0]->email_cli, $user_exists[0]->nom_cli);
            $email->attachments[] = public_path($path);

            if(!empty($url)){
                $email->setUrl($url);
            }

			if(!empty(request('codSub'))){
				$email->setAuction_code(request('codSub'));
			}

            if (!empty(Request('codSub')) && !empty(Request('codLot'))){
                $email->setLot(Request('codSub'), Request('codLot'));
            }

            $email->send_email();
        }
        else{
            return $this->responder(false, "the email type does not exist", "", 401);
        }
        return $this->responder(true, "mail send", "", 200);

    }

	public function emailUserActivation()
	{
		$validate = $this->validateParams(['cod_cli']);
        if (count($validate) != 0) {
            return $this->responseRules($validate);
        }

		$email = new EmailLib('ACTIVE_ACCOUNT');
		if(!$email->email){
			return $this->responseNotFound(trans(config('app.theme') . '-app.emails.api_email_type'));
		}

		$fxCliWeb = FxCliWeb::select('cod_cli')
			->joinCliCliweb()
			->where('cod_cli', request('cod_cli'))
			->first();

		if(!$fxCliWeb){
			return $this->responseNotFound(trans(config('app.theme') . '-app.emails.api_not_client'));
		}

		$email->setUserByCod($fxCliWeb->cod_cli, true);
        $email->send_email();

		return $this->responder(true, trans(config('app.theme') . '-app.emails.api_email_send'), "", 200);
	}

	public function emailAccessToVisibility(Request $request)
	{
		$validate = $this->validateParams(['cod_cli', 'cod_sub']);
        if (count($validate) != 0) {
            return $this->responseRules($validate);
        }

		$email = new EmailLib('AUCTION_VISIBILITY');
		if(!$email->email){
			return $this->responseNotFound(trans(config('app.theme') . '-app.emails.api_email_type'));

		}

		$email->setUrl(route('allCategories', ['order' => 'date_desc']));
		$email->setUserByCod(request('cod_cli'), true);

		$subasta = new Subasta();
        $subasta->cod = $request->cod_sub;
        $subasta->page = 'all';

		$inf_subasta = $subasta->getInfSubasta();

		if(!$inf_subasta){
			return $this->responseNotFound(trans(config('app.theme') . '-app.emails.api_not_auction'));
		}

		$textContent = trans_choice(config('app.theme').'-app.emails.visibility_auction', 1, ['name' => $inf_subasta->name]);

		//cliente subasta lote -> esa subasta y lote concretos
		if($request->ref_lot){

			$subasta->ref = $request->ref_lot;
        	$subasta->lote = $request->ref_lot;
			$inf_lot = head($subasta->getLote(false, true));

			if (empty($inf_lot)) {
				return $this->responseNotFound(trans(config('app.theme') . '-app.emails.api_not_lot'));
			}

			$textContent = trans_choice(config('app.theme').'-app.emails.visibility_lot', 1, ['desc' => $inf_lot->descweb_hces1, 'name' => $inf_subasta->name]);
		}

		$email->setText($textContent);
		$email->send_email();

		return $this->responder(true, trans(config('app.theme') . '-app.emails.api_email_send'), "", 200);
	}

	public function emailAccessToBids(Request $request)
	{
		$validate = $this->validateParams(['cod_cli']);
        if (count($validate) != 0) {
            return $this->responseRules($validate);
        }

		$cod_cli = request('cod_cli');
		$cod_sub = $request->cod_sub;
		$ref_lot = $request->ref_lot;

		try {
			(new MailController())->sendValidDepositNotification($cod_cli, $cod_sub, $ref_lot);
			return $this->responder(true, trans(config('app.theme') . '-app.emails.api_email_send'), "", 200);
		}
		catch (\Throwable $th) {
			return $this->responseNotFound($th->getMessage());
		}
	}

	public function emailProvisionalLotAward()
	{
		$validate = $this->validateParams(['cod_sub', 'ref_lot']);
        if (count($validate) != 0) {
            return $this->responseRules($validate);
        }

		$ref = request('ref_lot');
		$cod_sub = request('cod_sub');

		$subasta = new Subasta();
		$subasta->ref = $ref;
        $subasta->cod = $cod_sub;
        $subasta->lote = $ref;
        $subasta->page = 'all';
		$licitadores = [];

		$inf_lot = head($subasta->getLote(false, true));
		if (empty($inf_lot)) {
			return $this->responseNotFound(trans(config('app.theme') . '-app.emails.api_not_lot'));
		}

		//solo si queremos enviar email de ningun adjudicado
		//$inf_subasta = $subasta->getInfSubasta();

		$adjudicado = $subasta->get_csub(config('app.emp'));
		if(empty($adjudicado)){
			//?? email cuando no existe adjudicacion
			return $this->responseNotFound(trans(config('app.theme') . '-app.emails.api_not_award'));
		}

		//creamos un array con los pujadores no adjudicados del lote
		$get_pujas = $subasta->getPujas(false, $cod_sub);
		$ordenes = $subasta->getOrdenes();

		foreach ($get_pujas as $get_value_pujas) {
			//si el que gano noes el pujador actual  y el licitador n oes el dummy
			if ($adjudicado->licit_csub != $get_value_pujas->cod_licit && (config('app.dummy_bidder') != $get_value_pujas->cod_licit)) {
				$licitadores[$get_value_pujas->cod_licit] = $get_value_pujas->cod_licit;
			}
		}
		foreach ($ordenes as $orden) {
			//si el que gano noes el pujador actual  y el licitador n oes el dummy
			if ($adjudicado->licit_csub != $orden->cod_licit && (config('app.dummy_bidder') != $orden->cod_licit)) {
				$licitadores[$orden->cod_licit] = $orden->cod_licit;
			}
		}

		$email = new EmailLib('LOT_AWARD');
		if (!empty($email->email)) {
			$email->setUserByLicit($cod_sub, $adjudicado->licit_csub, true);
			$email->setLot($cod_sub, $ref);
			$email->setPriceAdjudication($cod_sub, $ref);
			$email->send_email();
		}


		foreach ($licitadores as $licitador) {
			$email = new EmailLib('LOST_AWARD_LOT');
			if (!empty($email->email)) {
				$email->setUserByLicit($cod_sub, $licitador, true);
				$email->setLot($cod_sub, $ref);
				$email->send_email();
			}
		}

		return $this->responder(true, trans(config('app.theme') . '-app.emails.api_email_send'), "", 200);
	}

	public function emailCompletLotReport()
	{
		$validate = $this->validateParams(['cod_sub', 'ref_lot']);
        if (count($validate) != 0) {
			return $this->responseRules($validate);
        }

		$mailController = new MailController();

		try {
			$mailController->sendCompletLotReport(request('cod_sub'), request('ref_lot'));

		} catch (\Exception $e) {

			Log::error($e);

			return $this->responseNotFound("Error inesperado");
		}
		return $this->responder(true, trans(config('app.theme') . '-app.emails.api_email_send'), "", 200);
	}


	/**Metodo de Tauler */
    public function acctiveAcount($type){

        $email = request('email');
        $user = new User();
        $user->email = $email;
        $mail_exists = $user->getUserByEmail(true);

        if (empty($mail_exists) || (!empty($mail_exists) && $mail_exists[0]->baja_tmp_cli != 'N')) {
            return "email not exist";
        }

        $email = urlencode($email);
        $code = \Tools::encodeStr($email . '-' . $mail_exists[0]->pwdwencrypt_cliweb);
        $url = config('app.url') . '/' . config('app.locale') . '/email-recovery' . '?email=' . $email . '&code=' . $code . '&login=true';

        $email = new EmailLib($type);

        if (!empty($email->email)) {

            $email->setUserByCod($mail_exists[0]->cod_cli, true, 'en');
            $email->setLink_pssw($url);
            $email->setTo($mail_exists[0]->usrw_cliweb, $mail_exists[0]->nom_cli);
            $email->send_email();

        }

        return $this->responder(true, "mail send", "", 200);

    }

    private function validateParams($params){

        $result = array();
        foreach ($params as $value) {
            if(empty(request($value))){
                array_push($result, $value);
            }
        }
        return $result;
    }


    public function sendTestMail(){

        $email = new EmailLib(request('type2'));
        if (!empty($email->email)) {
            $email->rellenarCampos();
            $email->setTo(request('email'));
            $email->send_email();
        }
        return $this->responder(true, "mail send", "", 200);
    }


	/**
	 * Sende email to users with deposit in lot
	 */
	public function sendToUsersWithDepositWhenChangeFiles()
	{
		$cod_sub = request('cod_sub');
		$ref = request('ref');

		try {
			$usersWithDeposit = FgDeposito::where('estado_deposito', FgDeposito::ESTADO_VALIDO)
				->where(function (Builder $query) use ($cod_sub, $ref) {

					$query->where('SUB_DEPOSITO', $cod_sub)
					->where(function (Builder $query) use ($ref) {
						$query->where('REF_DEPOSITO', $ref)
							->orWhereNull('REF_DEPOSITO');
					});
				})->get();

			if (!$usersWithDeposit) {
				return $this->responseNotFound("No existen usuarios con deposito");
			}

			$email = new EmailLib('DEPOSIT_CHANGE_FILES');
			if (empty($email->email)) {
				return $this->responseNotFound("No existe el email");
			}

			$usersWithDepositArray = $usersWithDeposit->unique('cli_deposito')->pluck('cli_deposito');

			foreach ($usersWithDepositArray as $user) {
				$email->setUserByCod($user, true);
				$email->setLot($cod_sub, $ref);
				$email->send_email();
			}

			return $this->responder(true, trans(config('app.theme') . '-app.emails.api_email_send'), "", 200);
		} catch (\Throwable $th) {
			return $this->responseNotFound($th->getMessage());
		}
	}

}
