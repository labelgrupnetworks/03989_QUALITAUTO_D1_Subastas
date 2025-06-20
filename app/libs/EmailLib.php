<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\libs;

use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\V5\CarlandiaPayController;
use App\Jobs\MailJob;
use App\Models\MailQueries;
use App\Models\Subasta;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgAsigl1Mt;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;
use App\Services\User\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

/**
 * Description of Str_lib
 *
 * @author LABEL-RSANCHEZ
 */
#[\AllowDynamicProperties]
class EmailLib
{

	private $atributes = array();
	public $email = NULL;
	private $to = NULL;
	private $bcc = array();
	private $to_name = NULL;
	private $lang = NULL;
	private $from = NULL;
	private $blade = "bbdd_email";
	public $attachments = NULL;
	public $attachmentsFiles = [];
	private $pdfs = array();
	public $old_lang = NULL;
	private $cc = array();
	private $debug = true;

	public function __construct($cod_email)
	{
		#si existe la variable debug_email es la que manda, si no se usará APP_DEBUG
		$this->debug = Config::get('mail.debug_email') ?: Config::get('app.debug');

		if ($this->debug) {
			$this->from = Config::get('mail.from.address') ?: Config::get('app.from_email');
		} else {
			$this->from = Config::get('app.from_email', Config::get('mail.from.address'));
		}

		$this->lang = Config::get('app.locale');

		$this->inicialize_atributes();
		//$this->test_atributes();

		//si devuelve false es que no existe o está desactivado, por lo tanto no se envia.
		$this->get_design($cod_email);
	}

	//generamos un archivo por cada cliente con los correos que le corresponden
	public function test_design($url, $cod_email = null)
	{
		//configuramso idiomas del cliente
		Config::set('app.language_complete', ['es' => 'es-ES']);

		#el diseñ odel email lo cojemso de la empresa principal para evitar tener duplicados.
		if (empty($cod_email)) {
			$designs =  DB::table('FSEMAIL')
				->where('emp_email', Config::get('app.main_emp'))
				->where('enabled_email', 1)
				#el autoformulari oque n osalga
				->where('cod_email', '!=', 'AUTOFORMULARIO')
				->where('cod_email', '!=', 'API_ERROR')

				->get();
		} else {
			$designs =  DB::table('FSEMAIL')
				->where('emp_email', Config::get('app.main_emp'))
				->where('enabled_email', 1)
				->where('cod_email', $cod_email)
				->get();
		}

		$this->test_atributes($url);


		$text = "<div style='display:flex;flex-direction:column;align-items: center;'>";

		foreach ($designs as $design) {

			foreach (Config::get('app.language_complete') as $lang) {

				$this->get_design_test($design->cod_email, $design->cod_template_email, $design->emp_email, $lang);
				$this->replace();
				$text .= "<div style='width: 600px;'>";
				$text .= "<br>" . "<br>" . "<strong>Email: </strong>" . $design->cod_email . "<br><br>";
				#	$text.=  "<strong>Idioma:</strong> ". $lang ."<br><br>";
				$text .=  "<strong>Descripción:</strong> " . $this->email->des_email . "<br><br>";

				$text .=  "<strong>Asunto: </strong>" . $this->email->subject_email . "<br><br><br><br>";


				$text .= View::make('front::emails.bbdd_email', array("HTML_email" => $this->HTML_email));
				$text .=  "<br><br><br><div style='text-align:center'>---------------------------------------------------------------------------------------------------</div>";
				$text .=  "</div>";
			}
		}
		$text .= "</div>";

		/*Activar el codigo cuando se quiere general una plantilla HTML 20//3/19 */
		//$file = fopen($this->email->des_template.".html", "w+");
		//fwrite($file, $text);
		//fclose($file);


		echo $text;
	}

	public function send_email()
	{
		//si no existe email cargado no se puede enviar
		if (empty($this->email)) {
			return false;
		}

		$this->replace();
		#quitado 11/01/2023 Config::get("app.queueEmails") &&
		if (Config::get('queue.default') != "sync" && empty($this->attachments) && empty($this->pdfs) && empty($this->attachmentsFiles)) {
			MailJob::dispatch($this)->onQueue(Config::get('app.queue_env'));
			return true;
		}

		try {
			return $this->send();
		} catch (\Exception $e) {
			if (!empty($this->email)) {
				Log::error("Error Send email: " . $this->email->cod_email);
				$this->setEmailLog('E');
			}
			Log::error($e);
			if (!empty($this->old_lang)) {
				App::setLocale($this->old_lang);
			}
			return false;
		}
	}

	public function send_email_queue()
	{
		return $this->send();
	}

	public function demo()
	{
		//si no existe email cargado no se puede enviar
		if (empty($this->email)) {
			return false;
		}
		$this->replace();
		echo $this->email->subject_email . "<br><br>";
		echo View::make('front::emails.bbdd_email', array("HTML_email" => $this->HTML_email));
		die();
	}


	public function get_design($cod_email)
	{
		$sql = "select  NVL(LANG.SUBJECT_LANG,EMAIL.SUBJECT_EMAIL) SUBJECT_EMAIL, NVL(LANG.BODY_LANG,EMAIL.BODY_EMAIL) BODY_EMAIL, UTM_EMAIL, DESIGN_TEMPLATE, COD_EMAIL, TYPE_EMAIL, TO_EMAIL, BCC_EMAIL from FSEMAIL EMAIL
            left join FSEMAIL_TEMPLATE TEMPLATE ON TEMPLATE.EMP_TEMPLATE = EMAIL.EMP_EMAIL AND TEMPLATE.COD_TEMPLATE = EMAIL.COD_TEMPLATE_EMAIL
            LEFT JOIN  FSEMAIL_LANG LANG ON LANG.EMP_LANG = EMAIL.EMP_EMAIL AND LANG.CODEMAIL_LANG = EMAIL.COD_EMAIL AND LANG.LANG_LANG = :lang
            where emp_email= :emp AND cod_email = :cod_email and enabled_email=1";



		$bindings = array(
			'emp'            => Config::get('app.main_emp'),
			'cod_email' => $cod_email,
			'lang' => ToolsServiceProvider::getLanguageComplete($this->lang)

		);
		$emails =  DB::select($sql, $bindings);

		if (empty($emails)) {
			# si viene vacio no mostramso log ya que en algunos casos se requiere llamar a una funcion de email
			if (!empty($cod_email)) {
				Log::info("No Existe el email $cod_email en base de datos");
			}

			return False;
		}

		$this->email = head($emails);

		return true;
	}
	//modificamos un poco la función para que funcione como necesitamos para el test
	private function get_design_test($cod_email, $cod_template, $emp_tmp, $lang)
	{
		$sql = "select des_email,des_template, NVL(LANG.SUBJECT_LANG,EMAIL.SUBJECT_EMAIL) SUBJECT_EMAIL, NVL(LANG.BODY_LANG,EMAIL.BODY_EMAIL) BODY_EMAIL, UTM_EMAIL, DESIGN_TEMPLATE, COD_EMAIL, TYPE_EMAIL from FSEMAIL EMAIL
            left join FSEMAIL_TEMPLATE TEMPLATE ON TEMPLATE.EMP_TEMPLATE = :emp AND TEMPLATE.COD_TEMPLATE = :cod_template
            LEFT JOIN  FSEMAIL_LANG LANG ON LANG.EMP_LANG = EMAIL.EMP_EMAIL AND LANG.CODEMAIL_LANG = EMAIL.COD_EMAIL AND LANG.LANG_LANG = :lang
            where emp_email= :emp AND cod_email = :cod_email and enabled_email=1";



		$bindings = array(
			'emp'            => $emp_tmp,
			'cod_email' => $cod_email,
			'lang' => $lang,
			'cod_template' => $cod_template

		);
		$emails =  DB::select($sql, $bindings);

		if (empty($emails)) {
			return False;
		}

		$this->email = head($emails);

		return true;
	}

	public function setTo($to, $to_name = '')
	{
		$this->to = $to;
		$this->to_name = $to_name;
	}

	/**
	 * Añadir pdf al correo
	 * @param array $pdfs La key representara el nombre del archivo y el valor el pdf en si
	 */
	public function setPdf(array $pdfs)
	{
		$this->pdfs = $pdfs;
	}

	public function setLang($lang)
	{
		$this->lang = $lang;
	}

	public function setUserByLicit($cod_sub, $cod_licit, $recipient = false)
	{
		$user = (new UserService)->getUserQueryByLicitCod($cod_sub, $cod_licit)
			->select(array_merge($this->userSelect(), [
				'cod_licit',
				'rsoc_licit'
			]))
			->where('baja_tmp_cli', 'N')
			->first();

		if (empty($user)) {
			Log::error("No existe el usuario con código de subasta: $cod_sub y licitador: $cod_licit");
			return $this;
		}

		$this->setUserAttributes($user);
		$this->setLicit_code($cod_licit);

		if ($recipient) {
			$this->setUserAsRecipient($user);
		}

		return $this;
	}

	public function setUserByCod($cod_cli, $recipient = false, $default_lang = null)
	{
		$user = (new UserService)->getUserQueryByCodCli($cod_cli)
			->select($this->userSelect())
			->first();

		if (empty($user)) {
			Log::error("No existe el usuario con código: $cod_cli");
			return $this;
		}

		$this->setUserAttributes($user);

		if ($recipient) {
			$this->setUserAsRecipient($user, $default_lang);
		}

		return $this;
	}

	public function setUserByEmail($email, $recipient = false, $default_lang = null)
	{
		$user = (new UserService)->getUserQueryByEmail($email)
			->select($this->userSelect())
			->first();

		if (empty($user)) {
			Log::error("No existe el usuario con email: $email");
			return $this;
		}

		$this->setUserAttributes($user);

		if ($recipient) {
			$this->setUserAsRecipient($user, $default_lang);
		}

		return $this;
	}

	private function userSelect(): array
	{
		return [
			'cod_cli',
			'cod2_cli',
			'nom_cli',
			'baja_tmp_cli',
			'cif_cli',
			'preftel_cli',
			'tel1_cli',
			'dir_cli',
			'pob_cli',
			'cp_cli',
			'pais_cli',
			'COALESCE(email_cli, email_cliweb) as email_cli',
			'COALESCE(idioma_cli, idioma_cliweb) as idioma_cli',
			'rsoc_cli',
			'fisjur_cli',
			'ries_cli',
			'obs_cli',
			'fecnac_cli'
		];
	}

	private function setUserAttributes(FxCli $user)
	{
		$this->atributes['CLIENT_CODE'] = $user->cod_cli;
		$this->atributes['EXTERNAL_CLIENT_CODE'] = $user->cod2_cli ?? '';
		$this->atributes['EMAIL'] = $user->email_cli;

		#si viene por licitador cogemos el nombre de licitador, asi los usuarios con multiples licitadores reciben el email que toca
		$name = $user->rsoc_licit ?? $user->nom_cli;
		$this->atributes['NAME'] = $this->getUserName($name);

		$this->atributes['CIF'] = $user->cif_cli;
		$this->atributes['PREFIX_PHONE'] = $user->preftel_cli ?? '';
		$this->atributes['PHONE'] = $user->tel1_cli;
		$this->atributes['ADDRESS'] = $user->dir_cli ?? '';
		$this->atributes['CITY'] = $user->pob_cli;
		$this->atributes['ZIP_CODE'] = $user->cp_cli;
		$this->atributes['COUNTRY'] = $user->pais_cli;
		$this->atributes['RSOC_CLI'] = $user->rsoc_cli;
		$this->atributes['FISJUR'] = $user->fisjur_cli;
		$this->atributes['RIES_CLI'] = ToolsServiceProvider::moneyFormat($user->ries_cli ?? 0);
		$this->atributes['OBS'] = $user->obs_cli ?? '';

		if (!empty($user->fecnac_cli)) {
			$this->atributes['DATE_OF_BIRTH'] = $user->fecnac_cli ? Carbon::parse($user->fecnac_cli)->format('d/m/Y') : '';
		}

		return $this;
	}

	private function getUserName($name)
	{
		$nameSurname = explode(",", $this->atributes['NAME']);
		if (!empty($nameSurname[1])) {
			return $nameSurname[1] . " " . $nameSurname[0];
		}
		return $name;
	}

	private function setUserAsRecipient(FxCli $user, ?string $fallbackLang = null): void
	{
		// locales soportados
		$shortLocales = Config::get('app.locales');
		$longLocales = Config::get('app.language_complete');

		$name = $user->rsoc_licit ?? $user->nom_cli;
		$this->to = $user->email_cli;
		$this->to_name = $this->getUserName($name);
		$this->lang = strtolower($user->idioma_cli);

		// Si el idioma del usuario es soportado, ajustamos locale
		if ($this->isSupportedLocale($this->lang, $shortLocales, $longLocales)) {
			$this->applyNewLocale($this->lang);
			return;
		}

		// Si no, usamos el idioma por defecto si está definido
		if ($fallbackLang) {
			$this->applyNewLocale($fallbackLang);
		}
	}

	/**
	 * Comprueba si un locale existe en los arrays de configuración.
	 */
	private function isSupportedLocale(string $lang, array $short, array $long): bool
	{
		return isset($short[$lang]) || isset($long[$lang]);
	}

	/**
	 * Cambia el locale de la aplicación, guarda el idioma anterior y recarga el diseño.
	 */
	private function applyNewLocale(string $newLang): void
	{
		$current = App::getLocale();

		if ($current === $newLang) {
			return;
		}

		$this->old_lang = $current;
		App::setLocale($newLang);
		$this->lang = $newLang;

		// Recargamos plantilla de email en el nuevo idioma
		$this->get_design($this->email->cod_email);
	}

	public function setLot($cod_sub, $lot_ref)
	{
		$subasta = new Subasta();
		$subasta->cod = $cod_sub;
		$subasta->lote = $lot_ref;
		$lot_array = $subasta->getLote(false, false);

		if (!empty($lot_array)) {
			$lot = head($lot_array);
		}


		if (!empty($lot)) {

			$this->lot = $lot;

			$this->atributes['LOT_DESCRIPTION'] = $lot->desc_hces1;
			$this->atributes['LOT_IMG'] = ToolsServiceProvider::url_img('lote_medium', $lot->numhces_asigl0, $lot->linhces_asigl0);

			// $this->atributes['LOT_LINK'] = Config::get('app.url').\Routing::translateSeo('lote').$cod_sub."-".$lot->id_auc_sessions.'-'.$lot->id_auc_sessions."/".$lot->ref_asigl0.'-'.$lot->num_hces1.'-'.$webfriend.
			$this->atributes['LOT_LINK'] = ToolsServiceProvider::url_lot($cod_sub, $lot->id_auc_sessions, $lot->des_sub, $lot->ref_asigl0, $lot->num_hces1, $lot->webfriend_hces1, $lot->titulo_hces1);
			$this->atributes['LOT_LINK_DESCWEB'] = ToolsServiceProvider::url_lot($cod_sub, $lot->id_auc_sessions, $lot->des_sub, $lot->ref_asigl0, $lot->num_hces1, $lot->webfriend_hces1, $lot->descweb_hces1);

			$this->atributes['LOT_LINHCES'] = $lot->lin_hces1;
			$this->atributes['LOT_NUMHCES'] = $lot->num_hces1;
			$refLot = $lot->ref_asigl0;
			#si  tiene el . decimal hay que ver si se debe poner las letras
			if (strpos($refLot, '.') !== false) {
				#si no hay limitación de subastas o si la hay y la actual es de ese tipo
				if (!Config::get("app.bisOnlyIn") || (Config::get("app.bisOnlyIn") && in_array($lot->tipo_sub, explode(",", Config::get("app.bisOnlyIn"))))) {
					if (Config::get("app.bis") && Config::get("app.bis") == "A") {
						$refLot = str_replace(array(".1", ".2", ".3", ".4", ".5", ".6"), array("-A", "-B", "-C", "-D", "-E"),  $refLot);
					} elseif (Config::get("app.bis") && Config::get("app.bis") == "B") {
						$refLot = str_replace(array(".1", ".2", ".3", ".4", ".5", ".6"), array("-B", "-C", "-D", "-E", "-F"),  $refLot);
					}
					#si hay limitacion de subastas y esta subasta no pertenece a las que permiten bises, hay que borrar los decimales
					#duran pondra decimales en subastas online y tienda para poder repertir lote sin repetir referencia, pero no se tiene que ver el bis ni el decimal
				} elseif (Config::get("app.bisOnlyIn") && !in_array($lot->tipo_sub, explode(",", Config::get("app.bisOnlyIn")))) {

					$refLot = str_replace(array(".1", ".2", ".3", ".4", ".5", ".6", ".7", ".8", ".9"), array("", "", "", "", "", "", "", "", "", ""),  $refLot);
					if (Config::get("app.substrRef")) {
						$refLot = substr($refLot, -Config::get("app.substrRef")) + 0;
					}
				}



				#si hay que recortar
			} elseif (Config::get("app.substrRef")) {
				#cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
				#le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
				$refLot = substr($lot->ref_asigl0, -Config::get("app.substrRef")) + 0;
			}

			$this->atributes['LOT_REF'] = $refLot;
			$this->atributes['LOT_TITLE'] = $lot->titulo_hces1;
			$this->atributes['LOT_DESCWEB'] = $lot->descweb_hces1;
			$this->atributes['AUCTION_CODE'] = $cod_sub;
			$this->atributes['AUCTION_NAME'] = $lot->auc_name;
			$this->atributes['PROP'] = $lot->prop_hces1;
			$this->atributes['ACTUAL_BID'] = ToolsServiceProvider::moneyFormat($lot->implic_hces1);
			$this->atributes['PRICE'] = ToolsServiceProvider::moneyFormat($lot->impsalhces_asigl0);
			$this->atributes['ESTIMACION_ALTA'] = ToolsServiceProvider::moneyFormat($lot->imptash_asigl0);
			$this->atributes['RESERVE_PRICE'] = ToolsServiceProvider::moneyFormat($lot->impres_asigl0);
			$this->atributes['ESTIMACION_BAJA'] = ToolsServiceProvider::moneyFormat($lot->imptas_asigl0);
			$this->atributes['ANCHO'] = $lot->ancho_hces1 ?? '';

			$this->setLotOpen($lot->open_at ?? '');
			$this->setDate($lot->start_session, 'j \d\e F \d\e Y');
			$this->setCloseDate($lot->close_at);

			if (config('app.featuresSimilarLots', '')) {

				$licitBid = 0;
				if (!empty($this->atributes['LICIT_CODE'])) {
					$licitBid = FgAsigl1::where([
						['SUB_ASIGL1', $cod_sub],
						['REF_ASIGL1', $refLot],
						['LICIT_ASIGL1', $this->atributes['LICIT_CODE']],
					])->max('imp_asigl1');
					$this->atributes['MY_BID'] = $licitBid;
				}

				// de la nada ahora el precio es desde 0 hasta un 25% mas de su puja maxima...
				//$this->atributes['URL_GRID'] = $this->getUrlGridLots($lot->numhces_asigl0, $lot->linhces_asigl0, $lot->tipo_sub, $licitBid, $lot->implic_hces1);
				$this->atributes['URL_GRID'] = $this->getUrlGridLots($lot->numhces_asigl0, $lot->linhces_asigl0, $lot->tipo_sub, 0, ($licitBid * 1.25));
			}
		}
	}

	public function setPropInfo($cod_sub, $lot_ref)
	{
		if (empty($this->atributes["PROP"])) {
			$this->setLot($cod_sub, $lot_ref);
		}

		$property = FxCli::LeftJoinClid("CONT")->select('rsoc_cli, nvl(nomd_clid,nom_cli) nom_cli , nvl(ctaiban_clid,ctaiban_cli) ctaiban_cli , nvl(tel1_clid,tel1_cli)  tel1_cli , nvl(email_clid,email_cli)  email_cli')->where('cod_cli', $this->atributes["PROP"])->first();

		if (!$property) {
			return false;
		}
		#el rsoc no está en direcciones por lo que se coje siempre la principal
		$this->atributes['PROP_NAME'] = $property->rsoc_cli;
		$this->atributes['PROP_CONTACT'] = $property->nom_cli;
		$this->atributes['PROP_IBAN'] = $property->ctaiban_cli;
		$this->atributes['PROP_TEL'] = $property->tel1_cli;
		$this->atributes['PROP_EMAIL'] = mb_strtolower($property->email_cli);

		return true;
	}

	public function addOwnerToReceiveMail($cod_sub, $lot_ref, $to = false)
	{
		if (empty($this->atributes["PROP"])) {
			$this->setLot($cod_sub, $lot_ref);
		}
		#CONT es el código de direccion multiple para los datos del contacto del proveedor
		$property = FxCli::LeftJoinClid("CONT")->select('nvl(email_clid,email_cli) as email_cli,nvl(nomd_clid,nom_cli) as nom_cli')->where('cod_cli', $this->atributes["PROP"])->first();

		if (!$property) {
			return false;
		}
		if ($to) {
			$this->setTo($property->email_cli ?? null, $property->nom_cli ?? null);
		} else {
			$this->setCc($property->email_cli ?? null);
		}
	}

	public function getUrlGridLots($numhces, $linhces, $tipoSub, $minPrice, $maxPrice)
	{
		$fgcaracteristicas = FgCaracteristicas_Hces1::getByLot($numhces, $linhces);

		$filters = array_map('trim', explode(',', config('app.featuresSimilarLots', '')));

		//Esto es exclusivamente para Carlandia, si en un futuro hace falta añadir diferentes segun cliente
		//Se necesitará un config.
		$order = 'auctionFirst';
		if ($tipoSub == 'V') {
			$order = 'directSaleFirst';
		}

		$conditions = $fgcaracteristicas->whereIn('id_caracteristicas', $filters);

		$params = [
			'prices' => [$minPrice, $maxPrice],
			'order' => $order
		];
		foreach ($conditions as $fgcaracteristica) {
			$params["features[$fgcaracteristica->id_caracteristicas]"] = $fgcaracteristica->idvalue_caracteristicas_hces1;
		}

		return route('allCategories', $params);
	}

	public function setText($text)
	{
		$this->atributes['TEXT'] = $text;
	}

	public function replace()
	{

		//reemplazamso los códigos por sus valores, si el valor es nulo se sustituye por vacio
		$body_email = $this->email->body_email;

		$design_template = $this->email->design_template;
		foreach ($this->atributes as $key => $value) {
			$body_email = str_replace('[*' . $key . '*]', $value, $body_email);
			$this->email->subject_email = str_replace('[*' . $key . '*]', $value, $this->email->subject_email);
			$design_template = str_replace('[*' . $key . '*]', $value, $design_template);
		}
		//AÑADIMOS LOS UTM
		if (!empty(Config::get('app.utm_email'))) {
			$utm_email = Config::get('app.utm_email') . '&utm_campaign=' . $this->email->utm_email;
			$body_email = str_replace('[*UTM*]', $utm_email, $body_email);
		}

		$tags = "|\[#[a-zA-Z0-9_-áéíóúÁÉÍÓÚ@/(/),.-¿/%]*(\s*[a-zA-Z0-9_-ÁÉÍÓÚáéíóú/(/)@,.-/%]*)*[a-zA-Z0-9_-áéíóúÁÉÍÓÚ?/(/)@/%,.-]+\#]|";
		preg_match_all($tags, $design_template, $matches);

		foreach ($matches[0] as $item) {
			$key = str_replace(array("[#", "#]"), "", $item);
			$design_template = str_replace($item, trans(Config::get('app.theme') . '-app.emails.' . $key), $design_template);
		}

		//cargamos el contenido definitivo, con o sin plantilla
		if (empty($body_email)) {
			$this->HTML_email = $body_email;
		} else {
			$this->HTML_email = str_replace('[*CONTENT*]', $body_email, $design_template);
		}
	}

	private function send()
	{
		if (!Config::get('app.enable_emails')) {
			return false;
		}

		$this->checkTo();

		//para pruebas
		if (Config::get('app.user_tests')) {
			$userTests = explode(';', Config::get('app.user_tests'));
			if (in_array(strtolower($this->to), $userTests)) {
				$cod_email = $this->email->cod_email . '_TEST';
				if ($this->get_design($cod_email)) {
					$this->replace();
				}
			}
		}

		//si esta configurada la opcción envio de copias y existe el mailbox, envia una copia a ese mailbox
		//bcc de la tabla Config
		if (
			Config::get('app.copies_emails')
			&& !empty(Config::get('app.copies_emails_mailbox'))
			&& $this->to != Config::get('app.debug_to_email')
		) {
			$emailsEnCopia = explode(";", Config::get('app.copies_emails_mailbox'));
			foreach ($emailsEnCopia as $item) {
				$this->bcc[] = $item;
			}
		}

		//añadimos los bcc de base de datos
		if ($this->email->bcc_email) {
			$bcc_emails = explode(";", $this->email->bcc_email);
			foreach ($bcc_emails as $bcc_email) {
				$this->bcc[] = $bcc_email;
			}
		}

		// KIKE - Añadido el 31/05/2019. Controlamos si relamente hay un receptor del email. Se envian mails a usuarios
		// dados de alta desde ERP sin email. Así evitamos estos envíos.
		if (!empty($this->to)) {

			Mail::send('emails.' . $this->blade, array("HTML_email" => $this->HTML_email), function ($m) {
				$m->from($this->from, Config::get('app.name'));
				$m->to($this->to, $this->to_name)->subject($this->email->subject_email);
				if (!$this->debug) {

					foreach ($this->bcc as $bcc) {
						$m->bcc(trim($bcc), trim($bcc));
					}

					foreach ($this->cc as $cc) {
						$m->cc(trim($cc), trim($cc));
					}
				}

				if (!empty($this->attachments)) {
					foreach ($this->attachments as $item) {
						$m->attach($item);
					}
				}

				if (!empty($this->pdfs)) {
					foreach ($this->pdfs as $key => $pdf) {
						$m->attachData($pdf->output(), "$key.pdf");
					}
				}
				if (!empty($this->attachmentsFiles)) {
					foreach ($this->attachmentsFiles as $file) {
						$m->attach(
							$file->getRealPath(),
							[
								'as' => $file->getClientOriginalName(),
								'mime' => $file->getClientMimeType(),
							]
						);
					}
				}
			});
			$this->setEmailLog('S');
		} else {
			$this->setEmailLog('E');
		}
		if (!empty($this->old_lang)) {
			App::setLocale($this->old_lang);
		}
		return true;
	}

	private function checkTo()
	{
		if ($this->debug) {
			$this->to = Config::get('mail.mail_to') ?: explode(";", Config::get('app.debug_to_email'));
			return;
		}

		// Si esta configurado en la base de datos el email de destino, lo usamos
		if ($this->email->type_email === "A" && $this->email->to_email) {
			$this->to = $this->email->to_email;
		}

		if (strpos($this->to, ';') === false) {
			return;
		}

		$explode_email = explode(";", $this->to);

		$this->to = trim(array_shift($explode_email));
		foreach ($explode_email as $email) {
			$this->cc[] = $email;
		}
		return;
	}

	private function setEmailLog($sended)
	{
		$mailQueries = new MailQueries();
		$mailQueries->setEmailLogs($this->email->cod_email, $this->atributes['AUCTION_CODE'], $this->atributes['LOT_REF'], $this->atributes['LOT_NUMHCES'], $this->atributes['LOT_LINHCES'], $this->atributes['CLIENT_CODE'], $this->to, $this->email->type_email, $sended);
	}

	public function titularidad_send_mail()
	{

		$num = $this->atributes["LOT_NUMHCES"];
		$lin = $this->atributes["LOT_LINHCES"];
		$prop = $this->atributes["PROP"];

		if (!empty($prop) && !empty($num) && !empty($lin)) {
			$this->setContract($num . '/' . $lin);
			$titularidad = array();
			$titularidad_multiple = $this->getTitularidadLot($num, $lin);
			if (empty($titularidad_multiple)) {
				$titularidad_multiple = $this->getTitularidadLot($num, '0');
			}

			if (!empty($titularidad_multiple)) {
				foreach ($titularidad_multiple as $titulares) {
					$titularidad[] = $titulares->cli_hcesmt;
				}
			} else {
				$titularidad[] = $prop;
			}

			foreach ($titularidad as $cod_cli) {
				$this->setUserByCod($cod_cli, true);
				$this->replace();
				$this->send();
			}
		}
	}

	private function getTitularidadLot($num, $lin)
	{
		$bindings = array(
			'emp'           => Config::get('app.emp'),
			'num'   => $num,
			'lin'   => $lin
		);

		$sql = "SELECT CLI_HCESMT FROM FGHCESMT WHERE EMP_HCESMT = :emp AND NUM_HCESMT = :num AND LIN_HCESMT = :lin";
		return DB::select($sql, $bindings);
	}

	public function setPriceAdjudication($cod_sub, $ref)
	{
		//Precio que tiene que pagar el cliente
		$subasta = new Subasta();
		$subasta->cod = $cod_sub;
		$subasta->lote = $ref;
		$adjudicado = $subasta->get_csub(Config::get('app.emp'));

		if (!empty($adjudicado)) {
			$pay =  new PaymentsController();
			#el iva lo cojemso de la empresa principal para no tenerlo duplicado
			$iva = $pay->getIva(Config::get('app.main_emp'), date("Y-m-d"));
			$tipo_iva = $pay->user_has_Iva(Config::get('app.gemp'), $this->atributes['CLIENT_CODE']);
			$tax = $pay->calculate_iva($tipo_iva->tipo, $iva, $adjudicado->base_csub);
			$precio_tax = $tax + $adjudicado->base_csub;
			$precio = $tax + $adjudicado->himp_csub + $adjudicado->base_csub;
			#duran no usa el iva en als adjudicaciones
			$precioSinIva = $adjudicado->himp_csub + $adjudicado->base_csub;

			$this->setPrice(ToolsServiceProvider::moneyFormat($precio, false, 2));
			$this->setPrice_tax(ToolsServiceProvider::moneyFormat($precio_tax, false, 2));
			$this->setPrice_auction(ToolsServiceProvider::moneyFormat($adjudicado->himp_csub, false, 2));

			if (Config::get('app.emails_with_commission', false)) {
				$this->addCommissionMessages($precio);
			}

			if (config::get("app.carlandiaCommission")) {
				#importe total, lo pongo con € y sin decimales
				$this->setPrice(ToolsServiceProvider::moneyFormat($precio, trans(Config::get('app.theme') . '-app.subastas.euros')));

				#importe reserva
				$carlandiaCommission = Config::get("app.carlandiaCommission");
				$impreserva = $precio - ($precio / (1 + $carlandiaCommission));
				$this->setAtribute("IMPORTE_RESERVA", ToolsServiceProvider::moneyFormat(round($impreserva, 2), trans(Config::get('app.theme') . '-app.subastas.euros'), 2));

				#Enlace de pago
				$linAsigl1 = FgAsigl1::where([
					['SUB_ASIGL1', $cod_sub],
					['REF_ASIGL1', $ref],
					['LICIT_ASIGL1', $this->atributes['LICIT_CODE']],
				])->max('lin_asigl1');
				$link = (new CarlandiaPayController())->getPayLink($cod_sub, $ref, $adjudicado->licit_csub, $linAsigl1, 'B');
				$this->setAtribute('PAY_LINK', $link);
			}

			$this->setAtribute("IMPORTESINIVA", ToolsServiceProvider::moneyFormat($precioSinIva, false, 2));
		}
	}


	private function inicialize_atributes()
	{
		$this->atributes = array(
			'ACTUAL_YEAR' => date("Y"),
			'APP_URL' => Config::get('app.url'),
			'AUCTION_CODE' => NULL,
			'AUCTION_NAME' => NULL,
			'BID' => NULL,
			'DATE' => NULL,
			'CIF' => NULL,
			'CITY' => NULL,
			'CLIENT_CODE' => NULL,
			'CONTRACT' => NULL,
			'COUNTRY' => NULL,
			'EMAIL' => NULL,
			'HTML' => NULL,
			'INVOICE_CODE' => NULL,
			'LICIT_CODE' => NULL,
			'LINK_PSSW' => NULL,
			'LOT_DESCRIPTION' => NULL,
			'LOT_DESCWEB' => NULL,
			'LOT_IMG' => NULL,
			'LOT_LINK' => NULL,
			'LOT_LINK_DESCWEB' => NULL,
			'LOT_LINHCES' => NULL,
			'LOT_NUMHCES' => NULL,
			'LOT_REF' => NULL,
			'LOT_TITLE' => NULL,
			'LOT_OPEN' => NULL,
			'NAME' => NULL,
			'NAME_EMP' => Config::get("app.name"),
			'ORDER_ID' => NULL,
			'PASSWORD' => NULL,
			'PHONE' => NULL,
			'PRICE' => NULL,
			'PRICE_AUCTION' => NULL,
			'PRICE_TAX' => NULL,
			'PUJA_PERDEDOR' => NULL,
			'SESSION_NAME' => NULL,
			'SESSION_START' => NULL,
			'THEME' => Config::get('app.theme'),
			'TEXT' => NULL,
			'URL' => NULL,
			'ZIP_CODE' => NULL,
			'PROP' => NULL,
			'BILL' => NULL,
			'FORM_FIELDS' => NULL,
			'ANCHO' => null
		);
	}

	public function test_atributes($url = null)
	{
		if (empty($url)) {
			$url = Config::get('app.url');
		}
		$this->atributes = array(
			'ACTUAL_YEAR' => date("Y"),
			'APP_URL' => $url,
			'AUCTION_CODE' => "<span style=\"color:#000CFF;\">Subasta</span>",
			'AUCTION_NAME' => "<span style=\"color:#000CFF;\">Nombre Sesión</span>",
			'BID' => "<span style=\"color:#000CFF;\">52</span>",
			'CIF' => "<span style=\"color:#000CFF;\">52917574d</span>",
			'CITY' => "<span style=\"color:#000CFF;\">VILADECANS</span>",
			'CLIENT_CODE' => "<span style=\"color:#000CFF;\">001234</span>",
			'CONTRACT' => "<span style=\"color:#000CFF;\">2234</span>",
			'COUNTRY' => "<span style=\"color:#000CFF;\">SPAIN</span>",
			'EMAIL' => "<span style=\"color:#000CFF;\">subastas@labelgrup.com</span>",
			'HTML' => "<span style=\"color:#000CFF;\">HTML TEST</span>",
			'INVOICE_CODE' => "<span style=\"color:#000CFF;\">23/234</span>",
			'LICIT_CODE' => "<span style=\"color:#000CFF;\">1002</span>",
			'LINK_PSSW' => "PASSLINK",
			'LOT_LINHCES' => "<span style=\"color:#000CFF;\">2000</span>",
			'LOT_NUMHCES' => "<span style=\"color:#000CFF;\">1</span>",
			'LOT_DESCRIPTION' => "<span style=\"color:#000CFF;\">Descripción del lote, texto descriptico del lote</span>",
			'LOT_DESCWEB' => "<span style=\"color:#000CFF;\">Descripción del lote, texto descriptico del lote</span>",
			'LOT_IMG' => "http://demoauction.label-grup.com/img/load/lote_medium/001-2-3.jpg",
			'LOT_LINK' => $url,
			'LOT_REF' => "<span style=\"color:#000CFF;\">101</span>",
			'LOT_TITLE' => "<span style=\"color:#000CFF;\">Lote de ejemplo</span>",
			'NAME' => "<span style=\"color:#000CFF;\">Nombre Cliente</span>",
			'NAME_EMP' => Config::get("app.name"),
			'ORDER_ID' => "<span style=\"color:#000CFF;\">123456</span>",
			'PASSWORD' => "<span style=\"color:#000CFF;\">password</span>",
			'PHONE' => "<span style=\"color:#000CFF;\">902902902</span>",
			'PRICE' => "<span style=\"color:#000CFF;\">80</span>",
			'PRICE_AUCTION' => "<span style=\"color:#000CFF;\">30</span>",
			'PRICE_TAX' => "<span style=\"color:#000CFF;\">10.3</span>",
			'PUJA_PERDEDOR' => "<span style=\"color:#000CFF;\">50</span>",
			'SESSION_NAME' => "<span style=\"color:#000CFF;\">sesion name</span>",
			'THEME' => Config::get('app.theme'),
			'TEXT' => "",
			'URL' => $url,
			'ZIP_CODE' => "<span style=\"color:#000CFF;\">08840</span>",
			'PROP' => NULL,
			'BILL' => NULL,
			'ANCHO' => "<span style=\"color:#000CFF;\">693</span>",
			'PRICE_COUNTEROFFER' => "<span style=\"color:#000CFF;\">0.000,00 €</span>",
			'ESTIMACION_BAJA' => "<span style=\"color:#000CFF;\">0.000 €</span>",
			'DIFF_IN_PERCENT' => "<span style=\"color:#000CFF;\">0,00 €</span>",
			'DIFF_IN_MONEY' => "<span style=\"color:#000CFF;\">0,00 €</span>",
			'LOT_CLOSE' => "<span style=\"color:#000CFF;\">01/01/2000</span>",
		);
	}

	public function rellenarCampos()
	{
		$this->atributes = array(
			'ACTUAL_YEAR' => '2019',
			'APP_URL' => Config::get('app.url'),
			'AUCTION_CODE' => '001',
			'AUCTION_NAME' => 'Subasta prueba',
			'BID' => '200',
			'CIF' => '5555555',
			'CITY' => 'Barcelona',
			'CLIENT_CODE' => '22222',
			'CONTRACT' => '',
			'COUNTRY' => 'España',
			'EMAIL' => 'mail@mail.es',
			'HTML' => 'www.prueba.es',
			'INVOICE_CODE' => '',
			'LICIT_CODE' => '22222',
			'LINK_PSSW' => 'link pssw',
			'LOT_DESCRIPTION' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s',
			'LOT_IMG' => 'https://testauction.labelgrup.com/img/load/lote_medium/001-65040-632.jpg',
			'LOT_LINK' => 'Link lote',
			'LOT_LINHCES' => 'Lin Lore',
			'LOT_NUMHCES' => 'Numero Lote',
			'LOT_REF' => 'Referencia Lote',
			'LOT_TITLE' => 'Titulo lote',
			'LOT_OPEN' => '01 de enero de 2001',
			'NAME' => 'Nombre',
			'NAME_EMP' => Config::get("app.name"),
			'ORDER_ID' => '222',
			'PASSWORD' => 'password',
			'PHONE' => '666777888',
			'PRICE' => '99',
			'PRICE_AUCTION' => '99',
			'PRICE_TAX' => '99',
			'SESSION_NAME' => 'Nombre sesión',
			'SESSION_START' => '01 de enero de 2001',
			'THEME' => Config::get('app.theme'),
			'URL' => 'url',
			'ZIP_CODE' => '08830',
			'PROP' => '99',
			'BILL' => '99'
		);
	}

	public function setBcc($email)
	{
		$this->bcc[] = $email;
	}

	public function setCc($email)
	{
		$this->cc[] = $email;
	}



	//genera las funciones de set a partir de los indices
	public function generate_functions()
	{
		foreach ($this->atributes as $atribute => $value) {
			echo "public function set" . ucfirst(strtolower($atribute)) . "($" . strtolower($atribute) . "){
                  " . '  $this->atributes["' . $atribute . '"] = $' . strtolower($atribute) . ";
                }


                ";
		}
	}
	public function setActual_year($actual_year)
	{
		$this->atributes["ACTUAL_YEAR"] = $actual_year;
	}


	public function setApp_url($app_url)
	{
		$this->atributes["APP_URL"] = $app_url;
	}


	public function setAuction_code($auction_code)
	{
		$this->atributes["AUCTION_CODE"] = $auction_code;
	}


	public function setBid($bid)
	{
		$this->atributes["BID"] = $bid;

		if (Config::get('app.emails_with_commission', false)) {
			$this->addCommissionMessages($bid);
		}
	}

	/**
	 * Añade los mensajes de comisión al email (utilizado por tda)
	 * @param $bid
	 */
	private function addCommissionMessages($bid)
	{
		$totalAmount = $bid;
		$this->atributes["COMMISSION_MESSAGE"] = '';

		if (Config::get('app.buyer_premium_active', false)) {

			$totalAmount = $bid + (($bid * config('app.addComisionEmailBid', 0)) / 100);

			$this->atributes["COMMISSION_MESSAGE"] = trans(Config::get('app.theme') . '-app.emails.commission_message', [
				'commission' => Config::get('app.addComisionEmailBid', 0) . '%'
			]);
		}

		$this->atributes["TOTAL_AMOUNT_MESSAGE"] = trans(Config::get('app.theme') . '-app.emails.total_amount_message', [
			'totalAmount' => $totalAmount
		]);
	}

	public function setBidDate($bidDate)
	{
		$this->atributes["BID_DATE"] = $bidDate;
	}

	public function setCif($cif)
	{
		$this->atributes["CIF"] = $cif;
	}


	public function setCity($city)
	{
		$this->atributes["CITY"] = $city;
	}


	public function setClient_code($client_code)
	{
		$this->atributes["CLIENT_CODE"] = $client_code;
	}


	public function setContract($contract)
	{
		$this->atributes["CONTRACT"] = $contract;
	}


	public function setCountry($country)
	{
		$this->atributes["COUNTRY"] = $country;
	}


	public function setEmail($email)
	{
		$this->atributes["EMAIL"] = $email;
	}


	public function setHtml($html)
	{
		$this->atributes["HTML"] = $html;
	}

	public function setHtmlBody($html)
	{
		$this->HTML_email = $html;
	}

	public function setInvoice_code($invoice_code)
	{
		$this->atributes["INVOICE_CODE"] = $invoice_code;
	}


	public function setLicit_code($licit_code)
	{
		$this->atributes["LICIT_CODE"] = $licit_code;
	}


	public function setLink_pssw($link_pssw)
	{
		$this->atributes["LINK_PSSW"] = $link_pssw;
	}


	public function setLot_description($lot_description)
	{
		$this->atributes["LOT_DESCRIPTION"] = $lot_description;
	}


	public function setLot_img($lot_img)
	{
		$this->atributes["LOT_IMG"] = $lot_img;
	}


	public function setLot_link($lot_link)
	{
		$this->atributes["LOT_LINK"] = $lot_link;
	}


	public function setLot_linhces($lot_linhces)
	{
		$this->atributes["LOT_LINHCES"] = $lot_linhces;
	}


	public function setLot_numhces($lot_numhces)
	{
		$this->atributes["LOT_NUMHCES"] = $lot_numhces;
	}


	public function setLot_ref($lot_ref)
	{
		$this->atributes["LOT_REF"] = $lot_ref;
	}


	public function setLot_title($lot_title)
	{
		$this->atributes["LOT_TITLE"] = $lot_title;
	}


	public function setName($name)
	{
		$this->atributes["NAME"] = $name;
	}


	public function setName_emp($name_emp)
	{
		$this->atributes["NAME_EMP"] = $name_emp;
	}


	public function setOrder_id($order_id)
	{
		$this->atributes["ORDER_ID"] = $order_id;
	}


	public function setPassword($password)
	{
		$this->atributes["PASSWORD"] = $password;
	}


	public function setPhone($phone)
	{
		$this->atributes["PHONE"] = $phone;
	}


	public function setPrice($price)
	{
		$this->atributes["PRICE"] = $price;
	}


	public function setPrice_auction($price_auction)
	{
		$this->atributes["PRICE_AUCTION"] = $price_auction;
	}


	public function setPrice_tax($price_tax)
	{
		$this->atributes["PRICE_TAX"] = $price_tax;
	}


	public function setSession_name($session_name)
	{
		$this->atributes["SESSION_NAME"] = $session_name;
	}


	public function setTheme($theme)
	{
		$this->atributes["THEME"] = $theme;
	}


	public function setUrl($url)
	{
		$this->atributes["URL"] = $url;
	}


	public function setZip_code($zip_code)
	{
		$this->atributes["ZIP_CODE"] = $zip_code;
	}


	public function setProp($prop)
	{
		$this->atributes["PROP"] = $prop;
	}

	public function setBill($imp)
	{
		$this->atributes["BILL"] = $imp;
	}

	public function setFormFields($formFields)
	{
		$this->atributes["FORM_FIELDS"] = $formFields;
	}


	public function setLotOpen($open_at)
	{

		if (empty($open_at)) {
			$this->atributes["LOT_OPEN"] = '';
			return;
		}
		$dateBuff = new \DateTime($open_at);
		setlocale(LC_TIME, ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'))   . ".UTF-8");
		if (Config::get('app.locale') == 'es') {
			$this->atributes["LOT_OPEN"] = Carbon::createFromFormat('Y/m/d H:i:s', $open_at)->locale('es')->isoFormat('D \d\e MMMM \d\e YYYY \a \l\a\s kk:mm');
		} else {
			$this->atributes["LOT_OPEN"] = strftime("%B %dth, %Y", $dateBuff->getTimestamp());
		}
	}

	//Modificar para recibir format desde fuera
	public function setCloseDate($date)
	{

		if (!$date) {
			return;
		}
		$dateBuff = new \DateTime($date);
		setlocale(LC_TIME, ToolsServiceProvider::getLanguageComplete(Config::get('app.locale')) . ".UTF-8");
		if (Config::get('app.locale') == 'es') {
			//$this->atributes["LOT_CLOSE"] = strftime ("%d de %B de %Y", $dateBuff->getTimestamp());
			$this->atributes["LOT_CLOSE"] = Carbon::createFromFormat('Y/m/d H:i:s', $date)->locale('es')->isoFormat('D \d\e MMMM \d\e YYYY \a \l\a\s kk:mm');
		} else {
			$this->atributes["LOT_CLOSE"] = strftime("%B %dth, %Y", $dateBuff->getTimestamp());
		}
	}

	public function setDate($date, $format)
	{

		$dateBuff = new \DateTime($date);
		setlocale(LC_TIME, ToolsServiceProvider::getLanguageComplete(Config::get('app.locale')) . ".UTF-8");
		if (Config::get('app.locale') == 'es') {
			$this->atributes["SESSION_START"] = strftime("%d de %B de %Y", $dateBuff->getTimestamp());
		} else {
			$this->atributes["SESSION_START"] = strftime("%B %dth, %Y", $dateBuff->getTimestamp());
		}


		//$this->atributes["SESSION_START"] = $dateBuff->format($format);
		//j \d\e F \d\e Y -> (01 de Enero de 2001);
	}

	public function setAtribute($atribute, $value)
	{
		$this->atributes[$atribute] = $value;
	}

	public function getAtribute($atribute, $default = null)
	{
		if (isset($this->atributes[$atribute])) {
			return $this->atributes[$atribute];
		}
		return $default;
	}

	public function getAtributes()
	{

		return $this->atributes;
	}

	public function subtractCommissionToAttributes()
	{

		if (!empty($this->lot) && config('app.carlandiaCommission', 0)) {
			$carlandiaCommission = 1 + config("app.carlandiaCommission");

			$this->atributes['ACTUAL_BID'] = ToolsServiceProvider::moneyFormat($this->lot->implic_hces1 / $carlandiaCommission, false, 2);
			$this->atributes['PRICE'] = ToolsServiceProvider::moneyFormat($this->lot->impsalhces_asigl0 / $carlandiaCommission, false, 2);
			$this->atributes['ESTIMACION_ALTA'] = ToolsServiceProvider::moneyFormat($this->lot->imptash_asigl0 / $carlandiaCommission, false, 2);
			$this->atributes['RESERVE_PRICE'] = ToolsServiceProvider::moneyFormat($this->lot->impres_asigl0 / $carlandiaCommission, false, 2);
			$this->atributes['ESTIMACION_BAJA'] = ToolsServiceProvider::moneyFormat($this->lot->imptas_asigl0 / $carlandiaCommission, false, 0);
		}
	}

	/**
	 * Añade diseño alternativo de email, si no existe, usa el diseño por defecto
	 */
	public function setAlternativeDesign($addCode)
	{
		$actualDesign = $this->email->cod_email;
		$this->get_design("{$actualDesign}_{$addCode}");
	}

	public function setMultipleBidders($codSub, $ref, $licit, $amount)
	{
		$theme = config('app.theme');

		$bidders = FgAsigl1Mt::joinAsigl1()
			->where([
				['sub_asigl1', $codSub],
				['ref_asigl1', $ref],
				['licit_asigl1', $licit],
				['imp_asigl1', $amount]
			])
			->get();

		$this->atributes['BIDDERS'] = "";

		if (!$bidders) {
			$this->atributes['BIDDERS'] = trans("$theme-app.emails.multiple_bidder", ['name' => $this->atributes['NAME'], 'ratio' => "100", 'value' => $amount]);
			return;
		}

		foreach ($bidders as $bidder) {
			$bidValue = $bidder->getAmountRatio($amount);
			$stringValue = ToolsServiceProvider::moneyFormat($bidValue, trans("$theme-app.subastas.euros"), 2);
			$this->atributes['BIDDERS'] .= trans("$theme-app.emails.multiple_bidder", ['name' => $bidder->full_name, 'ratio' => $bidder->ratio_asigl1mt, 'value' => $stringValue]);
		}
		return;
	}

	/**
	 * En cmoriones necesitamos adjuntar documentación a rellenar por el adjudicatario.
	 * Por el momento esta solo pensado para este cliente, pero se puede ampliar desde
	 * este punto si fuera necesario
	 */
	public function addAwardAttachedDocumentation()
	{
		$theme = Config::get('app.theme');
		$legalPersonality = $this->getAtribute('FISJUR', 'F');

		if (!is_array($this->attachments)) {
			$this->attachments = [];
		}

		$path = "themes/$theme/assets/files/";
		$nameFile = $legalPersonality == 'F' ? 'oferta web física.pdf' : 'oferta web jurídica.pdf';

		$this->attachments[] = public_path($path . $nameFile);
	}

	public function setAttachments($attachments)
	{
		$this->attachments = $attachments;
	}

	/**
	 * Archivos en plano sin necesidad de tener path en el servidor
	 */
	public function setAttachmentsFiles($attachments)
	{
		$this->attachmentsFiles = $attachments;
		return $this;
	}

	public function setAddress($address)
	{
		if (empty($address)) {
			$this->setAtribute('DIR_ENVIO', '');
			return;
		}

		$this->setAtribute('DIR_ENVIO', "{$address->pob_clid} ({$address->cp_clid}) {$address->pais_clid}");
	}

	public function setPackengersUrl($codSub, $ref)
	{
		$url = Config::get('app.urlToPackengers', '');
		if (empty($url)) {
			return;
		}

		$url .= "/{$codSub}-{$ref}?source=email";
		$this->setAtribute('PACKENGERS_URL', $url);
	}
}
