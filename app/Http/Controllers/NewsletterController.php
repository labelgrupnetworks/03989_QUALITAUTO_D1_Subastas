<?php

namespace App\Http\Controllers;

use App\Events\user\UserNewsletterSubscribed;
use App\Exports\MailChimpExport;
use App\libs\SeoLib;
use App\Models\Newsletter;
use App\Models\V5\Fx_Newsletter_Suscription;
use App\Models\V5\FxCliWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
	private $newsletterModel;

	public function __construct()
	{
		$this->newsletterModel = new Newsletter();

		if(Config::get('app.captcha_v3', false)){
			$this->middleware('verify.captcha')->only(['setNewsletterAjax']);
		}
	}

	#hago por ajax la funcion para poder guardar solo el evento al darse de alta en la newsletter desde la web
	public function setNewsletterAjax(Request $request, $option = "add")
	{
		$res = $this->setNewsletter($request,$option);

		if($res['status'] == 'success'){
			#guardamos el evento SEO de creación de newsletter
			SeoLib::saveEvent("NEWSLETTER");
		}

		return $res;
	}
	public function setNewsletter(Request $request, $option = "add")
	{
		if ($this->isNotValid($request)) {
			return [
				'status' => 'error',
				"msg" => 'err-add_newsletter'
			];
		}

		$email = trim($request->input('email'));
		$lang = $request->input('lang', $request->input('language'));
		$families = $request->get('families');
		$cehckForGroup = $request->get('isMultiCompany', false);

		$result = Config::get('app.newsletter_table', false)
			? $this->setNewNewsletters($lang, $email, $families, $cehckForGroup)
			: $this->setOldNewsletter($lang, $email, $families);


		return $result;
	}

	private function isNotValid(Request $request)
	{
		$rules = [
			'email' => 'required|email',
			'condiciones' => 'required',
		];

		$validator = Validator::make($request->all(), $rules);

		return $validator->fails();
	}

	private function setNewNewsletters($lang, $email, $families, $cehckForGroup)
	{
		if(empty($families)) {
			return [
				'status' => 'error',
				"msg" => 'err-families_newsletter'
			];
		}

		$this->newsletterModel
			->setAttributes($lang, $email, $families)
			->suscribe($cehckForGroup, 'newsletter');

		return [
			'status' => 'success',
			'msg' => 'success-add_newsletter'
		];
	}

	/**
	 * @deprecated
	 * Eliminar este y los metodos que solo se llamen desde aquí, cuando la migración a las
	 * nuevas tablas este finalizado
	 */
	private function setOldNewsletter($lang, $email, $families)
	{
		// Miramos si ya existe el usuario y está registrado
		$hasCliweb = FxCliWeb::where('LOWER(USRW_CLIWEB)', strtolower($email))->first();
		if (!$hasCliweb) {
			FxCliWeb::insert([
				"GEMP_CLIWEB" => Config::get('app.gemp'),
				"COD_CLIWEB" => "0",
				"USRW_CLIWEB" => $email,
				"EMAIL_CLIWEB" => $email,
				"EMP_CLIWEB" => Config::get('app.emp'),
				"TIPACCESO_CLIWEB" => "N",
				"TIPO_CLIWEB" => "C",
				"FECALTA_CLIWEB" => date("Y-m-d H:i:s"),
				"IDIOMA_CLIWEB" => $lang,
			]);
		}

		$news = new Newsletter();
		$news->families = $families;
		$news->lang = $lang;
		$news->email = $email;

		if (!empty($news->families)) {
			$news->newFamilies();
		}

		event(new UserNewsletterSubscribed($email));

		return [
			'status' => 'success',
			'msg' => 'success-add_newsletter'
		];
	}

	public function configNewsletter(Request $request, $lang, $email)
	{
		$isAdmin = (bool)session('user.admin');
		//abort_if(!Hash::check($email, $request->input('hash', null)) && !$isAdmin, 404);

		$isMultiCompany = Config::get('app.multi_company', false);

		$suscriptions = $this->newsletterModel->getIdSuscriptions($email);
		$newsletters = $this->newsletterModel->getNewslettersNames($isMultiCompany);

		return view('front::pages.newsletters', ['suscriptions' => $suscriptions, 'newsletters' => $newsletters, 'isMultiCompany' => $isMultiCompany]);
	}

	public function unsuscribeNewsletter(Request $request, $lang, $email)
	{
		$requestType = $request->query('type', 'view');

		abort_if(md5($email) !== $request->input('hash', null), 404, "Not Found");

		$idNewsletter = $request->input('id', null);
		if($idNewsletter) {
			$this->newsletterModel->deleteSuscriptionsById($idNewsletter, $email);

			if(!empty($this->newsletterModel->getIdSuscriptions($email))){
				return $this->suscribeOnlyToExternalService($request, $lang, $email);
			}
		}

		$this->newsletterModel->deleteSuscriptions($email, true);
		$this->newsletterModel->unSubscribeToExternalService($email);

		$message = trans(config('app.theme') . '-app.msg_success.newsletter_unsubscribe', ['email' => $email]);

		return $requestType === "json"
			? response()->json(["message" => $message, "status" => "success"])
			: view("front::pages.message", ["message" => $message]);
	}

	public function suscribeOnlyToExternalService(Request $request, $lang, $email)
	{
		$requestType = $request->query('type', 'view');

		abort_if(md5($email) !== $request->input('hash', null), 404, "Not Found");

		$this->newsletterModel->subscribeToExternalService($email);
		$message = trans(config('app.theme') . '-app.msg_success.newsletter_subscribe', ['email' => $email]);

		return $requestType === "json"
			? response()->json(["message" => $message, "status" => "success"])
			: view("front::pages.message", ["message" => $message]);
	}

	/**
	 * Eliminar cuando estén todos los clientes migrados
	 */
	public function migrateNewslettersToNewFormat()
	{
		abort(404);
		Fx_Newsletter_Suscription::query()->delete();
		$fxCliWebQuery = FxCliWeb::query()->select("email_cliweb, idioma_cliweb, fecalta_cliweb");
		foreach (range(1, 20) as $value) {
			$fxCliWebQuery->addSelect("nllist{$value}_cliweb");
			$fxCliWebQuery->orWhere("nllist{$value}_cliweb", "S");
		}
		$users = $fxCliWebQuery->get();

		$suscriptions = [];
		$users->each(function ($user) use (&$suscriptions) {
			foreach (range(1, 20) as $value) {
				if ($user->{"nllist{$value}_cliweb"} === "S") {
					$suscriptions[] = [
						'lang_newsletter_suscription' => mb_strtoupper($user->idioma_cliweb ?? 'ES'),
						'email_newsletter_suscription' => $user->email_cliweb,
						'id_newsletter' => $value,// + 1, //cuando la nllist_1 corresponde a una familia
						'create_newsletter_suscription' => $user->fecalta_cliweb ?? now()
					];
				}
			}
		});

		foreach (collect($suscriptions)->chunk(1000) as $suscriptions) {
			Fx_Newsletter_Suscription::insertWithDefaultValues($suscriptions->all());
		};

		dd('fin');
		//dd($users->count(), $users, $suscriptions);
	}

	public function mailchimpExportCsv()
	{
		$isAdmin = (bool)session('user.admin');
		abort_if(!$isAdmin, 404);

		return (new MailChimpExport(true))->download('export.csv', \Maatwebsite\Excel\Excel::CSV);
	}

	/**
	 * En Mailchimp es necesario que la url del callback sea accesible por get
	 * y de una respuesta correcta.
	 */
	public function checkCallback()
	{
		return response()->json(['status' => 'success']);
	}

	/**
	 * Callback para webhook de servicios externos.
	 * Por el momento solo para mailchimp, pero en la variable service se
	 * utilizará para controlar de que servicio llega
	 */
	public function callbackUnsuscribe(Request $request, $service, $action)
	{
		$data = $request->data;
		$email = $data['email'];

		$this->newsletterModel->deleteSuscriptions($email, true);

		return response()->json(['status' => 'success']);
	}
}
