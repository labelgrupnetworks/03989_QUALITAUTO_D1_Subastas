<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MailController;
use App\Http\Controllers\V5\DepositController;
use App\libs\LoadLotFileLib;
use App\Models\MailQueries;
use App\Models\Subasta;
use App\Models\User;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgSub;
use App\Models\V5\FsDiv;
use App\Models\V5\FxCli;
use App\Providers\RoutingServiceProvider;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class CronController extends Controller
{

	public function CloseLotsWebServiceCall()
	{

		$emp =  Config::get('app.emp');

		$lots = DB::table('WEB_EMAIL_CLOSLOT')
			->where('ID_EMP', $emp)
			->where('SENDED', 'N')
			->get();

		$theme  = Config::get('app.theme');
		$rutaCloseLotcontroller = "App\Http\Controllers\\externalws\\$theme\CloseLotControllerOnline";

		$closeLotController = new $rutaCloseLotcontroller();

		try {
			foreach ($lots as $lot) {

				$closeLotController->createCloseLot($lot->id_sub, $lot->id_ref);
				DB::select("update WEB_EMAIL_CLOSLOT set sended = 'S' where id_sub = '" . $lot->id_sub . "' and id_ref='" . $lot->id_ref . "' and sended='N' ");
			}
		} catch (\Exception $e) {
			Log::error("Error cerrando lote Online: " . $e);
			if (!empty($lot) && !empty($lot->id_sub) && !empty($lot->id_ref)) {
				DB::select("update WEB_EMAIL_CLOSLOT set sended = 'E' where id_sub = '" . $lot->id_sub . "' and id_ref='" . $lot->id_ref . "' and sended='N' ");
			}
		}
	}

	#función que llama al webservice de duran para cancelar las reservas
	public function CancelReservationWS()
	{
		$codSub = "7500";
		$theme  = Config::get('app.theme');
		$rutaReservationController = "App\Http\Controllers\\externalws\\$theme\ReservationController";
		$ReservationController = new $rutaReservationController();

		$lots = Fgasigl0::select("REF_ASIGL0 ref, USRDESADJU_ASIGL0 client")->whereRaw("FFIN_ASIGL0 < ?", date("Y-m-d H:i:s"))->where("sub_asigl0", "7500")->get();

		$clients = array();
		foreach ($lots as $lot) {
			if (empty($clients[$lot->client])) {
				$clients[$lot->client] = array();
			}
			$clients[$lot->client][] = $lot->ref;
		}

		foreach ($clients as $codCli => $lots) {
			$ReservationController->deleteReservation($codCli, $lots);
			$update['FFIN_ASIGL0'] = null;
			$update['USRDESADJU_ASIGL0'] = null;
			FgAsigl0::where("SUB_ASIGL0", $codSub)->wherein("REF_ASIGL0", $lots)->update($update);
		}
	}

	public function cronEmailReports()
	{
		$mail = new MailController();
		$emp =  Config::get('app.emp');
		$mailquery = new MailQueries;

		$index = DB::table('WEB_EMAIL_CLOSLOT')
			->where('ID_EMP', $emp)
			->where('SENDED', 'N')
			->get();

		foreach ($index as $value) {

			try {

				$mail->sendCompletLotReport($value->id_sub, $value->id_ref);
				$mailquery->updateWebEmailCloslot($emp, $value->id_sub, $value->id_ref, 'S');
			} catch (\Exception $e) {

				Log::error($e);
				$mailquery->updateWebEmailCloslot($emp, $value->id_sub, $value->id_ref, 'E');
				$mail->error_email_exception('sendEmailAuctionReport', $e->getMessage(), config('app.emp'), $value->id_sub, $value->id_ref);
				return;
			}
		}
		return;
	}

	public function EmailsAdjudicaciones()
	{
		$mail = new MailController();
		$emp =  Config::get('app.emp');

		$index = DB::table('WEB_EMAIL_CLOSLOT')
			->where('ID_EMP', $emp)
			->where('SENDED', 'N')
			->get();
		$i = 0;
		foreach ($index as $value) {
			$mail->sendEmailCerradoGeneric($value->id_emp, $value->id_sub, $value->id_ref);
		}



		$index = DB::table('WEB_EMAIL_MOVELOT')
			->where('ID_EMP', $emp)
			->where('SENDED', 'N')
			->get();

		$i = 0;

		foreach ($index as $value) {

			$mail->sendEmailNoAdjudicado($value->id_emp, $value->id_sub, $value->id_ref, $value->id_last_sub, $value->id_last_ref);
		}
	}

	public function EmailsAdjudicacionesGeneric()
	{
		$mail = new MailController();
		$deposito = new DepositController();
		$emp =  Config::get('app.emp');

		$index = DB::table('WEB_EMAIL_CLOSLOT')
			->where('ID_EMP', $emp)
			->where('SENDED', 'N')
			->get();

		foreach ($index as $value) {
			if (Config::get('app.payDepositTpv')) {
				$deposito->confirmPreAuthorization($value->id_sub, $value->id_ref);
			}

			$mail->sendEmailCerradoGeneric($value->id_emp, $value->id_sub, $value->id_ref);
		}
	}

	/**
	 * Comprueba las subastas online con todos los lotes cerrados y que tengan email por enviar
	 */
	public function EmailCloseAuction()
	{
		$mail = new MailController();
		$emp = Config::get('app.emp');

		$emails = ['AUCTION_REPORT'];

		$auctionsWithLotsClosed = FgSub::select('FGSUB.COD_SUB')
			->join('FGASIGL0', 'FGASIGL0.SUB_ASIGL0 = FGSUB.COD_SUB')
			->where([['TIPO_SUB', 'O'], ['SUBC_SUB', 'S']])
			->groupBy('COD_SUB')
			->havingRaw("count(COD_SUB) = (select count(asig.SUB_ASIGL0) from fgasigl0 asig where asig.sub_asigl0 = FGSUB.COD_SUB and asig.CERRADO_ASIGL0 = 'S')")
			->get();

		foreach ($auctionsWithLotsClosed as $auction) {
			foreach ($emails as $email) {

				$isSended = DB::table('WEB_EMAIL_CLOSAUCTION')
					->where([
						['ID_EMP', $emp],
						['ID_SUB', $auction->cod_sub],
						['ID_EMAIL', $email],
					])
					->first();

				if (!$isSended) {
					//enviar correo
					Log::info("Enviar correo: $email. subasta: $auction->cod_sub.");
					DB::table('WEB_EMAIL_CLOSAUCTION')
						->insert([
							'ID_EMP' => $emp,
							'ID_SUB' => $auction->cod_sub,
							'ID_EMAIL' => $email,
							'SENDED' => 'E'
						]);

					$mail->sendEmailCloseAucion($auction->cod_sub, $email);
				}
			}
		}

		return;
	}

	public function EmailFirstAuction()
	{
		$mail = new MailController();
		$params = array(
			'emp'       =>  Config::get('app.emp')

		);

		$sql = "SELECT fa.*  FROM WEB_EMAIL_FIRST_AUCTION fa "
			. "JOIN FGASIGL0 ON EMP_ASIGL0 = ID_EMP AND SUB_ASIGL0 = ID_SUB AND REF_ASIGL0 = ID_REF"
			. " WHERE ID_EMP = :emp and SENDED='N' AND TRUNC(FINI_ASIGL0) = TRUNC(SYSDATE)";
		$lots = DB::select($sql, $params);

		foreach ($lots as $lot) {
			$mail->EmailCedentFirstAuction($lot->id_emp, $lot->id_sub, $lot->id_ref);
		}
	}

	public function emailsReSaleLots()
	{
		$mail = new MailController();
		$params = array(
			'emp'       =>  Config::get('app.emp')

		);
		//buscamos lotes que se hayan pasado de subasta y que hayan salido a la venta ayer
		$sql = "insert into WEB_EMAIL_RESALELOT SELECT ASIGL0.EMP_ASIGL0, ASIGL0.SUB_ASIGL0 current_sub, ASIGL0.ref_asigl0 current_ref, ASIGL0_ANT.SUB_ASIGL0, ASIGL0_ANT.REF_ASIGL0,'N' FROM FGSUB SUB
                    JOIN FGASIGL0 ASIGL0 ON ASIGL0.EMP_ASIGL0 = SUB.EMP_SUB AND ASIGL0.SUB_ASIGL0 =  SUB.TRASPSUB_SUB
                    JOIN FGASIGL0 ASIGL0_ANT ON ASIGL0_ANT.EMP_ASIGL0 = SUB.EMP_SUB AND ASIGL0_ANT.SUB_ASIGL0 = SUB.COD_SUB AND ASIGL0_ANT.NUMHCES_ASIGL0 = ASIGL0.NUMHCES_ASIGL0  AND ASIGL0_ANT.LINHCES_ASIGL0 = ASIGL0.LINHCES_ASIGL0
                    LEFT JOIN WEB_EMAIL_RESALELOT  RESALE ON RESALE.ID_EMP=SUB.EMP_SUB AND RESALE.ID_SUB =   ASIGL0.SUB_ASIGL0 AND RESALE.ID_REF = ASIGL0.REF_ASIGL0 AND RESALE.ID_LAST_SUB =  ASIGL0_ANT.SUB_ASIGL0 AND RESALE.ID_LAST_REF =  ASIGL0_ANT.REF_ASIGL0
                    WHERE
                    SUB.EMP_SUB = :emp
                    AND SUB.TIPO_SUB='P'

                    AND SUB.TRASPSUB_SUB IS NOT NULL
                    AND TRASPSUB_SUB != 'FIN'
                    AND ASIGL0.CERRADO_ASIGL0 ='N'
                    --que no se encuentre actualmente en la tabla resale
                    AND RESALE.ID_EMP IS NULL

                     AND ASIGL0.FINI_ASIGL0 > TRUNC(SYSDATE-2)
                    AND ASIGL0.FINI_ASIGL0 < TRUNC(SYSDATE)

                    ORDER BY ASIGL0_ANT.SUB_ASIGL0, ASIGL0_ANT.REF_ASIGL0
                    ";
		DB::select($sql, $params);

		$sql = "SELECT *  FROM WEB_EMAIL_RESALELOT WHERE ID_EMP = :emp and SENDED='N'";
		$lots = DB::select($sql, $params);

		$i = 0;
		foreach ($lots as $lot) {
			$mail->reSaleLot($lot->id_emp, $lot->id_sub, $lot->id_ref, $lot->id_last_sub, $lot->id_last_ref);
			$i++;
			if ($i >= Config::get('app.max_email_cron')) {
				break;
			}
		}
	}

	public function lastCall()
	{

		$mailquery = new MailQueries;
		$mail = new MailController();
		$mailquery->lastCallLot();
		$users = $mailquery->getLastCall();

		$mail->sendLastCall($users);
	}

	public function LotePendingPay()
	{
		$mail = new MailController();
		$days = explode(",", Config::get('app.email_lote_pending'));
		foreach ($days as $id_key => $day) {
			$mail->emailLotePendingPay($id_key, $day);
		}
	}

	public function LotePendingCollect()
	{

		$mail = new MailController();

		//Cliente
		$days = explode(",", Config::get('app.email_lote_redy_collect'));
		foreach ($days as $id_key => $day) {
			$mail->lot_redy_collect($id_key, $day);
		}
		die();

		//Cedente
		$days = explode(",", Config::get('app.email_lote_redy_collect_cedente'));
		foreach ($days as $id_key => $day) {
			$mail->lot_redy_collect_cedente($id_key, $day);
		}
	}


	public function emailNotBiddedYet()
	{
		$mail = new MailController();
		$days = array('3');
		foreach ($days as $id_key => $day) {
			$mail->not_bidder_yet($day);
		}
	}

	public function emailCedenteAmedidaError()
	{
		$sql = "select wnc.*, impsalhces_asigl0,fini_asigl0 from web_notificar_prop_error  wnc
                join fgasigl0 asigl0 on asigl0.numhces_asigl0 = wnc.numhces1 and asigl0.linhces_asigl0 = wnc.linhces1  and emp_asigl0 = '" . Config::get('app.emp') . "'
                join fghces1 hces1 on hces1.emp_hces1 ='" . Config::get('app.emp') . "'  and hces1.num_hces1 =  wnc.numhces1 and hces1.lin_hces1 = wnc.linhces1
                where sended = 'N'
                and sub_asigl0 = '2ONLINE' and
                cerrado_asigl0 = 'N' ";


		$lots = DB::select($sql);
		$users = array();
		foreach ($lots as $lot) {
			$users[$lot->cod_prop][] = $lot;
		}

		foreach ($users as $key_user => $user) {

			DB::select("update web_notificar_prop_error set sended = 'S' where cod_prop = '$key_user' and sended='N' ");
			$emailOptions['subject'] = "Rectificación precio de novación.";
			$emailOptions['to'] = $user[0]->mail_prop;
			$emailOptions['user'] = $user[0]->nom_prop;
			$emailOptions['content'] = new \stdClass();
			//datos email
			$emailOptions['content']->hide_thanks = true;
			$emailOptions['content']->final_text = "<br><br>";
			$emailOptions['content']->title = "";
			$emailOptions['content']->text = "Debido a un error de sistema, recibiste ayer un email con un precio de novación incorrecto para tu(s) lote(s): <br> <ul style='text-align: left;'>";

			foreach ($user as $inf_lot) {

				$fini_lot = date("d/m/Y", strtotime($inf_lot->fini_asigl0));


				$emailOptions['content']->text .= "<li>Te informamos que el lote " . $inf_lot->refhces1 . ", saldrá a subasta el día  " . $fini_lot . " por el precio de reserva " . $inf_lot->impsalhces_asigl0 . ".</li>";
			}
			$emailOptions['content']->text .= "</ul><p style='text-align: left;'><br>Si prefieres que tu(s) lote(s) no vuelva a salir a subasta, tienes un plazo máximo de 15 días para comunicar tu decisión. Si notificas tu disconformidad y no lo recoges dentro del plazo de 15 días indicado, incurrirás en gastos de almacenamiento a razón de 5 € por día.<br>Disculpa las molestias.<br><br>Gracias por confiar en Balclis.<br><br>Un saludo,</p>";
			if (ToolsServiceProvider::sendMail('emails_automaticos', $emailOptions) == true) {

				echo "enviado - $inf_lot->numhces1/$inf_lot->linhces1 <br>";
			} else {
				DB::select("update web_notificar_prop_error set sended = 'E' where cod_prop = '$key_user' and sended='S' ");
				echo "error - $inf_lot->numhces1/$inf_lot->linhces1 <br>";
			}
		}
	}

	public function emailCedeneteAMedida()
	{

		$subasta = new Subasta();
		$usercli = new User();
		$sql = "select wnc.*, hces1.desc_hces1,ref_hces1,fgcsub.fecha_csub from web_notificar_cedentes  wnc
                join fghces1 hces1 on hces1.emp_hces1 ='" . Config::get('app.emp') . "'  and hces1.num_hces1 =  wnc.numhces1 and hces1.lin_hces1 = wnc.linhces1
                join fgcsub on emp_csub = hces1.emp_hces1 and ref_csub = ref_hces1 and sub_hces1 = sub_csub
                where sended = 'N'";

		$sql = "select wnc.*, hces1.desc_hces1,ref_hces1,fgcsub.fecha_csub from web_notificar_cedentes wnc
                left join fghces1 hces1 on hces1.emp_hces1 ='001'  and hces1.num_hces1 =  wnc.numhces1 and hces1.lin_hces1 = wnc.linhces1
                join fgcsub on emp_csub = emp_hces1 and ref_csub = ref_hces1 and sub_csub = sub_hces1
                where sended = 'N'";

		$lots = DB::select($sql);

		$users = array();
		/* foreach ($lots as $lot){
            $users[$lot->cod_prop][] = $lot;
        }*/

		//$emailOptions=array();
		$emailOptions = array(
			'user'      => Config::get('app.name'),
			'email'     => Config::get('app.admin_email'),
		);
		$emailOptions['subject'] = "Nueva subasta para tus lotes.";
		$emailOptions['hidden_footer'] = true;

		foreach ($lots as $key_user => $user) {
			print_r($user);
			die();
			$inf_user = DB::select(
				"SELECT * FROM FXCLI cl
                            WHERE
                            cl.cod_cli = :cod_cli
                            AND
                            cl.GEMP_CLI = :gemp
                            ",
				array(
					'cod_cli'       => $user->cod_prop,
					'gemp'      =>  Config::get('app.gemp')
				)
			);

			$inf_user = head($inf_user);
			$inf_lot_translate = $subasta->getMultilanguageTextLot($user->numhces1, $user->linhces1);
			App::setLocale(strtolower($inf_user->idioma_cli));

			//marcar como enviado
			//DB::select("update web_notificar_cedentes set sended = 'S' where cod_prop = '$key_user' and sended='N' ");
			$emailOptions['to'] = $user->mail_prop;
			$emailOptions['user'] = $user->nom_prop;
			$emailOptions['content'] = new \stdClass();
			//datos email
			if ($inf_user->idioma_cli == 'ES') {
				$emailOptions['content']->final_text = "Según nuestros términos y condiciones de subasta, recuerda:<br><br> 1. Dispones de un <strong>plazo máximo de 10 días</strong> a partir del día de la adjudicación para realizar el pago del lote.
                    <br>2. Si vienes tu o un tercero a recoger el lote, <strong>dispones de 15 días</strong> para efectuar la recogida. <br>
                   3. A partir de estos 15 días, incurrirás en gastos de almacenamiento a razón 5 € / día. <br><br>
                   <strong>¿Ya está pagado?</strong> <br><br>
                 Si has efectuado el pago, puedes ignorar este correo electrónico. Al realizar el pago manualmente, puede tardar unos días en llegarnos.
     ";
				$emailOptions['content']->title = "";
				$emailOptions['content']->text = 'Nos ponemos en contacto contigo para recordarte que tienes <strong>pendiente</strong> el <strong>pago</strong> del lote que se te adjudicó en Balclis en fecha ' . date("d-m-Y", strtotime($user->fecha_csub)) . '.';
				$emailOptions['content']->final_text_up_button =  'Ten en cuenta que si no pagas dentro de <strong>5 días</strong> procederemos a la desadjudicación del lote y nos veremos obligados a bloquear tu cuenta y no podrás pujar.<br><br> '
					. 'Accede a <a style="color: #555555;text-decoration:underline;" target="_blank" href="https://www.balclis.com/es/user/panel/allotments/outstanding?utm_source=automatic_notification&utm_medium=email_web&utm_campaign=pago_pendiente">tu perfil</a> para realizar el pago y gestionar el envío de tu lote.';
			} else {

				$emailOptions['content']->final_text = "According to our terms and conditions of auction, remember:<br><br> 1. You have a <strong>maximum period of 10 days</strong> from the day of the award to pay the lot.
                    <br>2. If you or a third party comes to collect the lot, <strong>you have 15 days</strong>  to make the collection.<br>
                   3. After these 15 days, storage costs will be incurred at a rate of € 5 per day.<br><br>
                   <strong>The lot is already paid?</strong> <br><br>
                 If you have made the payment, you can ignore this email. When making the payment manually, it may take a few days to be successfully registered on our system.
     ";
				$emailOptions['content']->title = "";
				$emailOptions['content']->text = 'We are contacting you to remind you that the payment of the lot that was awarded to you in Balclis on date ' . date("d-m-Y", strtotime($user->fecha_csub)) . ' is still pending.';
				$emailOptions['content']->final_text_up_button =  'Please note that if you do not pay within 5 days we will proceed to remove your award of the lot and we will be forced to block your account and you will not be able to bid.<br><br> '
					. 'Access <a style="color: #555555;text-decoration:underline;" target="_blank" href="https://www.balclis.com/es/user/panel/allotments/outstanding?utm_source=automatic_notification&utm_medium=email_web&utm_campaign=pago_pendiente">your profile</a> to execute the payment and manage the shipment of your lot.';
			}

			$emailOptions['content']->button = trans(Config::get('app.theme') . '-app.emails.button_pay');
			$emailOptions['content']->block_help = 1;
			$emailOptions['content']->url_button =  Config::get('app.url') . RoutingServiceProvider::slug('user/panel/allotments/outstanding');
			$emailOptions['content']->hide_thanks = true;



			$email_lot = new \stdClass();
			$email_lot->desc = $inf_lot_translate[$inf_user->idioma_cli]->desc_hces1;
			$email_lot->ref = $user->ref_hces1;
			$email_lot->img = Config::get('app.url') . '/img/load/lote_medium/' . Config::get('app.emp') . '-' . $user->numhces1 . '-' . $user->linhces1 . '.jpg';

			$emailOptions['lot'][] = $email_lot;



			$utm_email = '';
			if (!empty(Config::get('app.utm_email'))) {
				$utm_email = Config::get('app.utm_email');
			}
			$emailOptions['UTM'] = $utm_email;
			return View::make('front::emails.emails_automaticos', array("emailOptions" => $emailOptions));
			die();
			if (ToolsServiceProvider::sendMail('emails_automaticos', $emailOptions) == true) {
				echo "enviado - $key_user<br>";
			} else {
				echo "error enviando<br>";
			}

			/*
           if($key_user == $codcli){

           }

            */
		}
	}

	function generateProductFeed()
	{
		$sql = "SELECT  HCES1.NUM_HCES1,HCES1.LIN_HCES1,SUB.COD_SUB, ASIGL0.ref_asigl0,AUC.\"id_auc_sessions\",TRUNC(ASIGL0.FFIN_ASIGL0) - TRUNC(SYSDATE) DAYS,

                    NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG, ORTSEC0.DES_ORTSEC0) CATEGORY,
                    NVL(SEC_LANG.DES_SEC_LANG, SEC.DES_SEC) DES_SEC ,
                    NVL(ASIGL0.IMPSALHCES_ASIGL0,HCES1.IMPLIC_HCES1) PRICE,
                    NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.TITULO_HCES1) TITULO_HCES1,

                    NVL(HCES1_LANG.WEBFRIEND_HCES1_LANG, HCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1,
                    NVL(HCES1_LANG.webmetat_hces1_lang, HCES1.webmetat_hces1) webmetat_hces1

                        FROM FGASIGL0 ASIGL0
                        INNER JOIN FGHCES1 HCES1 ON (HCES1.EMP_HCES1 = ASIGL0.EMP_ASIGL0 AND HCES1.NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0 AND HCES1.LIN_HCES1 = ASIGL0.LINHCES_ASIGL0)
                        INNER JOIN FGSUB SUB ON SUB.EMP_SUB = ASIGL0.EMP_ASIGL0 AND SUB.COD_SUB = ASIGL0.SUB_ASIGL0
                        INNER JOIN \"auc_sessions\" AUC ON AUC.\"auction\" = SUB.COD_SUB AND AUC.\"company\" = ASIGL0.EMP_ASIGL0
                        JOIN FXSEC SEC ON (SEC.COD_SEC = HCES1.SEC_HCES1 )
                        LEFT JOIN FXSEC_LANG SEC_LANG ON (SEC_LANG.CODSEC_SEC_LANG = SEC.COD_SEC AND SEC_LANG.GEMP_SEC_LANG = SEC.GEMP_SEC AND SEC_LANG.LANG_SEC_LANG = :lang)
                        JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = HCES1.SEC_HCES1 AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = '0')
                        JOIN FGORTSEC0 ORTSEC0 ON ( ORTSEC0.EMP_ORTSEC0 = HCES1.EMP_HCES1 AND ORTSEC0.SUB_ORTSEC0 = ORTSEC1.SUB_ORTSEC1  AND ORTSEC0.LIN_ORTSEC0 = ORTSEC1.LIN_ORTSEC1)
                        LEFT JOIN FGHCES1_LANG HCES1_LANG ON (HCES1_LANG.EMP_HCES1_LANG = HCES1.EMP_HCES1 AND HCES1_LANG.NUM_HCES1_LANG = HCES1.NUM_HCES1 AND HCES1_LANG.LIN_HCES1_LANG = HCES1.LIN_HCES1 AND HCES1_LANG.LANG_HCES1_LANG = :lang)
                        LEFT JOIN FGORTSEC0_LANG ORTSEC0_LANG ON ( ORTSEC0_LANG.EMP_ORTSEC0_LANG = HCES1.EMP_HCES1 AND ORTSEC0_LANG.SUB_ORTSEC0_LANG = ORTSEC1.SUB_ORTSEC1  AND ORTSEC0_LANG.LIN_ORTSEC0_LANG = ORTSEC1.LIN_ORTSEC1 AND HCES1_LANG.LANG_HCES1_LANG = :lang)

                    WHERE
                            ASIGL0.EMP_ASIGL0 = :emp AND
                            SUB.TIPO_SUB ='P' AND
                            ASIGL0.REF_ASIGL0 >= AUC.\"init_lot\" AND
                            ASIGL0.REF_ASIGL0 <= AUC.\"end_lot\" AND
                            SUB.SUBC_SUB IN ('S') AND
                            OCULTO_ASIGL0 = 'N' AND
                            ASIGL0.CERRADO_ASIGL0 ='N' AND
                            HCES1.FAC_HCES1 != 'D' AND
                            HCES1.FAC_HCES1 != 'R' AND
                            LIN_ORTSEC0 !=10 AND
                            GEMP_SEC = :gemp AND
                            /* si la subasta es de tipo P los lotes deben estar activos */
                            (TRUNC(ASIGL0.FINI_ASIGL0) < TRUNC(SYSDATE) or (ASIGL0.FINI_ASIGL0 = TRUNC(SYSDATE) AND ASIGL0.HINI_ASIGL0 <= TO_CHAR(SYSDATE, 'HH24:MI:SS')))

                        ";
		$this->generateProductFeedGoogle($sql);
		$this->generateProductFeedFacebook($sql);
	}

	private function generateProductFeedFacebook($sql)
	{
		$langs = array('ES' => 'es-ES', 'EN' => 'en-GB');

		foreach ($langs as $key_lang => $lang) {


			$params = array(
				'emp'       =>  Config::get('app.emp'),
				'gemp'       =>  Config::get('app.gemp'),
				'lang'      => $lang //ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))
			);

			$lots = DB::select($sql, $params);

			$buffer = "
                         <rss xmlns:g=\"http://base.google.com/ns/1.0\" version=\"2.0\">
                            <channel>
                            <title>Balclis</title>
                            <link>" . Config::get('app.url') . "</link>
                            <description></description>
                            ";
			foreach ($lots as $inf_lot) {
				$buffer .= "
                             " . $this->genXMLProductFeedFacebook($inf_lot);
			}
			$buffer .= "
                     </channel>
                           </rss>";
			$file_name = $_SERVER['DOCUMENT_ROOT'] . "/files/product_feed_Facebook_" . $key_lang . ".xml";

			if (file_exists($file_name)) {
				$file = fopen($file_name, "w+");
			} else {
				$file = fopen($file_name, "a");
			}
			fwrite($file, $buffer);
			fclose($file);
		}
	}

	private function generateProductFeedGoogle($sql)
	{
		$langs = array('ES' => 'es-ES', 'EN' => 'en-GB');
		$days_limit = array("3", "0");

		foreach ($days_limit as $day_limit) {
			//se tiene que iniciar para que funcione el tema de los dias
			$sql_def = $sql;
			foreach ($langs as $key_lang => $lang) {

				$name_file_days = "";
				if ($day_limit != 0) {
					$sql_def .= "  and ASIGL0.FFIN_ASIGL0 <=TRUNC(SYSDATE+$day_limit)";
					$name_file_days = $day_limit . "_days_";
				}
				$params = array(
					'emp'       =>  Config::get('app.emp'),
					'gemp'       =>  Config::get('app.gemp'),
					'lang'      => $lang //ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))
				);

				$lots = DB::select($sql_def, $params);

				$buffer = "ID,Item title,Final URL,Image URL";
				foreach ($lots as $inf_lot) {
					$buffer .= "
                             " . $this->genXMLProductFeedGoogle($inf_lot);
				}

				$file_name = $_SERVER['DOCUMENT_ROOT'] . "/files/product_feed_Google_" . $name_file_days . $key_lang . ".csv";

				if (file_exists($file_name)) {
					$file = fopen($file_name, "w+");
				} else {
					$file = fopen($file_name, "a");
				}
				fwrite($file, $buffer);
				fclose($file);
			}
		}
	}

	private function genXMLProductFeedFacebook($inf_lot)
	{
		$img = Config::get('app.url') . '/img/load/lote_medium_large/' . Config::get('app.emp') . '-' . $inf_lot->num_hces1 . '-' . $inf_lot->lin_hces1 . '.jpg';
		$webfriend = !empty($inf_lot->webfriend_hces1) ? $inf_lot->webfriend_hces1 :  str_slug($inf_lot->titulo_hces1);
		$url_friendly = Config::get('app.url') . RoutingServiceProvider::translateSeo('lote') . $inf_lot->cod_sub . "-" . $inf_lot->id_auc_sessions . '-' . $inf_lot->id_auc_sessions . "/" . $inf_lot->ref_asigl0 . '-' . $inf_lot->num_hces1 . '-' . $webfriend;
		$titulo = !empty($inf_lot->webmetat_hces1) ? $inf_lot->webmetat_hces1 : $inf_lot->titulo_hces1;
		$titulo = str_replace('&', 'and', $titulo);
		$product_type = str_replace('&', 'and', $inf_lot->des_sec);
		$category = str_replace('&', 'and', $inf_lot->category);
		$price_range = "entre 0 y 250";
		if ($inf_lot->price >= 250 && $inf_lot->price <= 500) {
			$price_range = "250-500";
		} elseif ($inf_lot->price > 500) {
			$price_range = "> 500";
		}

		$xml = " <item>
                        <g:id> $inf_lot->ref_asigl0 </g:id>
                        <title> $titulo</title>
                        <link> $url_friendly </link>
                        <g:price> $inf_lot->price </g:price>
                        <description> $titulo</description>
                        <g:image_link> $img </g:image_link>
                        <g:condition> used</g:condition>
                        <g:availability> in stock </g:availability>
                        <g:brand> Balclis </g:brand>
                        <custom_label_0>$category  </custom_label_0>
                        <custom_label_1>$inf_lot->days days </custom_label_1>
                        <product_type>$product_type  </product_type>
                        <custom_label_2>$price_range </custom_label_2>
                        <custom_label_3>$inf_lot->cod_sub </custom_label_3>
                      </item>";

		return $xml;
	}

	private function genXMLProductFeedGoogle($inf_lot)
	{
		$img = Config::get('app.url') . '/img/load/lote_medium_large/' . Config::get('app.emp') . '-' . $inf_lot->num_hces1 . '-' . $inf_lot->lin_hces1 . '.jpg';
		$webfriend = !empty($inf_lot->webfriend_hces1) ? $inf_lot->webfriend_hces1 :  str_slug($inf_lot->titulo_hces1);
		$url_friendly = Config::get('app.url') . RoutingServiceProvider::translateSeo('lote') . $inf_lot->cod_sub . "-" . $inf_lot->id_auc_sessions . '-' . $inf_lot->id_auc_sessions . "/" . $inf_lot->ref_asigl0 . '-' . $inf_lot->num_hces1 . '-' . $webfriend;
		$titulo = !empty($inf_lot->webmetat_hces1) ? $inf_lot->webmetat_hces1 : $inf_lot->titulo_hces1;
		$titulo = str_replace('"', '""', $titulo);
		$xml = $inf_lot->ref_asigl0 . ',"' . $titulo . '",' . $url_friendly . ',' . $img;

		return $xml;
	}

	public function update_divisa()
	{
		//Cogemos divisas del cliente
		$divisa = FsDiv::getDivisas();

		$money = array();
		//Divisas prestahop
		$path = simplexml_load_file('http://api.prestashop.com/xml/currencies.xml');

		//Array divisas prestashop
		for ($i = 0; $i <= count($path->list->currency) - 1; $i++) {
			$index = $path->list->currency[$i]->attributes()->iso_code[0];
			$money["$index"] = (float)$path->list->currency[$i]->attributes()->rate;
			//    $money[$path->list->currency[$i]->attributes()->iso_code[0]] = $path->list->currency[$i]->attributes()->rate;
		}

		$subasta = new Subasta();
		#Ver moneda principal
		$currency = $subasta->getCurrency();
		# si la moneda es euro copiamos tal cual
		if ($currency->name == "EUR") {
			foreach ($divisa as $cod) {
				$cod_divisa = $cod->cod_div;
				if (!empty($money[$cod_divisa]) && $cod_divisa != 'EUR') {
					/*   echo " $cod_divisa - $money[$cod_divisa] <br>";  */

					DB::table('FSDIV')
						->where('COD_DIV', $cod_divisa)
						->update(['IMPD_DIV' => $money[$cod_divisa]]);
				}
			}
		} else {

			#si no es Euro, debemos hacer la conversion a euro
			$eur = 1 / $money[$currency->name];
			foreach ($divisa as $cod) {
				$cod_divisa = $cod->cod_div;
				if (!empty($money[$cod_divisa])) {

					$impd_div = $eur *	$money[$cod_divisa];
					DB::table('FSDIV')
						->where('COD_DIV', $cod_divisa)
						->update(['IMPD_DIV' => $impd_div]);
				}
			}
		}
	}

	public function loadCarsMotorflash()
	{

		$fxCli = FxCli::select("COD_CLI")->WHERE("TIPO_CLI", "V")->get();

		foreach ($fxCli as $cedente) {
			$this->loadCarsCedente($cedente);
		}
	}

	#hacemos la carga por cedente para que si alguno da error no afecte al resto
	public function loadCarsCedente($cedente)
	{
		try {
			$url = Config::get("app.urlMotorflash");
			$cod_cli = $cedente->cod_cli;

			$xml = simplexml_load_file($url . "/" . $cod_cli);
			$loadFileLib = new	LoadLotFileLib($cod_cli);
			$loadFileLib->loadMotorFlash($xml);
			Log::info("<br> cedente $cod_cli cargado ");
		} catch (\Exception $e) {
			Log::error("<br>Error cedente $cod_cli");
			Log::error($e);
			echo "error en la carga " . $e;
		}
	}

	public function dynamicAds()
	{
		# Lanza la query para coger los datos
		$queryForExport = FgAsigl0::selectRaw("
				 SUB_ASIGL0  || '-' || REF_ASIGL0 as id,
				DESCWEB_HCES1 as description,
				DESC_HCES1 as title,
				IMPSALHCES_ASIGL0 as price,
                WEBFRIEND_HCES1,
                WEBMETAT_HCES1,
                TITULO_HCES1,
				NUM_HCES1,
				LIN_HCES1,
				\"id_auc_sessions\",
				TIPO_SUB,
				COD_SUB,
				IMGFRIENDLY_HCES1,
				REF_ASIGL0,
				FECALTA_ASIGL0,
				RSOC_CLI,
				(SELECT VALUE_CARACTERISTICAS_HCES1 FROM FGCARACTERISTICAS_HCES1  WHERE EMP_CARACTERISTICAS_HCES1 = EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = LINHCES_ASIGL0  AND IDCAR_CARACTERISTICAS_HCES1 = 55) matricula,
				nvl((SELECT VALUE_CARACTERISTICAS_HCES1 FROM FGCARACTERISTICAS_HCES1  WHERE EMP_CARACTERISTICAS_HCES1 = EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = LINHCES_ASIGL0  AND IDCAR_CARACTERISTICAS_HCES1 = 62)
				, IMPSALHCES_ASIGL0   )precio_min,
				nvl((SELECT VALUE_CARACTERISTICAS_HCES1 FROM FGCARACTERISTICAS_HCES1  WHERE EMP_CARACTERISTICAS_HCES1 = EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = LINHCES_ASIGL0  AND IDCAR_CARACTERISTICAS_HCES1 = 61)
				, IMPSALHCES_ASIGL0   )precio_max")
			->joinFghces1Asigl0()->joinSubastaAsigl0()->joinSessionAsigl0()
			->LeftJoinOwnerWithHces1()
			->whereIn('SUB_ASIGL0', ['MOTORO', 'MOTORV'])
			->where('CERRADO_ASIGL0', 'N')
			->where('RETIRADO_ASIGL0', 'N')
			->where('OCULTO_ASIGL0', 'N')
			->orderBy('REF_ASIGL0', 'asc')
			->get();


		# Itera sobre la query para poner los datos en un array
		foreach ($queryForExport as $key => $inf_lot) {

			$img = ToolsServiceProvider::url_img('lote_medium_large', $inf_lot->num_hces1, $inf_lot->lin_hces1, 0, $inf_lot->imgfriendly_hces1);
			$webfriend = !empty($inf_lot->webfriend_hces1) ? $inf_lot->webfriend_hces1 :  str_slug($inf_lot->titulo_hces1);
			$url_friendly = Config::get('app.url') . RoutingServiceProvider::translateSeo('lote') . $inf_lot->cod_sub . "-" . $inf_lot->id_auc_sessions . '-' . $inf_lot->id_auc_sessions . "/" . $inf_lot->ref_asigl0 . '-' . $inf_lot->num_hces1 . '-' . $webfriend;
			$splittedString = explode(' ', $inf_lot->description);
			$brand = $splittedString[0];
			$priceFormated = ToolsServiceProvider::moneyFormat($inf_lot->price, "EUR", 0);
			#no pueden ser iguales por que provocarian division por 0, loscampos no vienen vacios por que se les pone el preci ode venta si no tienen valor
			if ($inf_lot->precio_min == $inf_lot->precio_max) {
				$inf_lot->precio_min = $inf_lot->precio_max * 0.85;
			}

			$export = [
				"id" => $inf_lot->id,
				"availability" => 'in stock',
				"condition" => 'used',
				"description" => $inf_lot->description,
				"image_link" => $img,
				"link" => $url_friendly,
				"title" => $inf_lot->title,
				"price" => $priceFormated,
				"brand" => $brand,
				"google_product_category" => '916',
				"product_type" => 'VO',

			];

			if ($inf_lot->tipo_sub == "V") {
				$export["custom_label_0"] = "VENTA";
			} else {
				$export["custom_label_0"] = "SUBASTA";
			}
			$export["custom_label_1"] = (int)(($inf_lot->price - $inf_lot->precio_min) / ($inf_lot->precio_max - $inf_lot->precio_min) * 100);

			$export["custom_label_2"] = $inf_lot->matricula;

			/*
			•	muy bueno: todos los valores menores o iguales a 25
			•	bueno: todos los valores mayores a 25 y menor o iguales a 40
			•	regular: todos los valores mayores a 40 y menor o iguales a 50
			•	malo: todos los valores mayores a 50
			*/

			if ($export["custom_label_1"] <= 25) {
				$export["custom_label_3"] = "muy bueno";
			} elseif ($export["custom_label_1"] <= 40) {
				$export["custom_label_3"] = "bueno";
			} elseif ($export["custom_label_1"] <= 50) {
				$export["custom_label_3"] = "regular";
			} else {
				$export["custom_label_3"] = "malo";
			}
			$export["custom_label_4"] = $inf_lot->rsoc_cli;

			$arrayForExport[] = $export;
		}

		#Transforma el Array en collection y lo exporta
		$collectForExport = collect($arrayForExport);

		$filename = Config::get('app.theme') . 'DynamicAds';

		ToolsServiceProvider::storeCollectionToCSV($collectForExport, $filename);
	}
}
