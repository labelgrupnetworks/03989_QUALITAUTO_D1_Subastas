<?php

# Ubicacion del modelo

namespace App\Models;

use App\Http\Controllers\externalws\mailing\ExternalMailingController;
use Illuminate\Support\Facades\Config;
use App\libs\EmailLib;
use Illuminate\Support\Facades\DB;
use App\Models\V5\FxCliWeb;
use App\Models\V5\Fx_Newsletter;
use App\Models\V5\Fx_Newsletter_Suscription;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class Newsletter
{
	public $email;
	public $families;
	public $emp;
	public $gemp;
	public $lang;

	public function __construct()
	{
		$this->emp = Config::get('app.emp');
		$this->gemp = Config::get('app.gemp');
	}

	static function factory()
	{
		return new self();
	}

	public function setAttributes($lang, $email, $families)
	{
		$this->lang = mb_strtoupper($lang);
		$this->email = $email;
		$this->families = $families;
		return $this;
	}

	public function suscribe($checkForGroup)
	{
		if (!$this->email) {
			throw new Exception(trans(Config::get('app.theme') . "-app.msg_error.err-add_newsletter"));
		}

		$newslettersIds = array_keys($this->families ?? [Fx_Newsletter::GENERAL => 1]);

		$this->deleteSuscriptions($this->email, $checkForGroup);

		$suscriptions = array_map(function ($id) {
			return [
				'lang_newsletter_suscription' => $this->lang,
				'email_newsletter_suscription' => $this->email,
				'id_newsletter' => $id
			];
		}, $newslettersIds);

		Fx_Newsletter_Suscription::insertWithDefaultValues($suscriptions);

		$this->subscribeToExternalService($this->email);

		//Soporte Concursal queria que también se enviara el registro en la newsletter
		$email = new EmailLib('USER_NEWSLETTER');
		if (!empty($email->email)) {
			$email->setTo(strtolower($this->email));
			$email->send_email();
		}
	}

	public function getIdSuscriptions(string $email): array
	{
		return Fx_Newsletter_Suscription::query()
			->whereEmail($email)
			->pluck('id_newsletter')
			->toArray();
	}

	public function getSuscriptionsWithNamesByEmail(string $email): EloquentCollection
	{
		return Fx_Newsletter_Suscription::query()
			->with('name')
			->whereEmail($email)
			->get();
	}

	public function getSuscriptionsNamesByEmail(string $email): Collection
	{
		$suscriptions = Fx_Newsletter_Suscription::query()
			->with('name')
			->whereEmail($email)
			->get();

		$suscriptionNames = $suscriptions->pluck('name.name_newsletter');
		return $suscriptionNames;
	}

	/**
	 * Modificar request por un objeto con los posibles filtros.
	 */
	public function getSuscriptionsQueryWithCliInfoById($id_newsletter, $onlyRegisterClients) : Builder
	{
		return Fx_Newsletter_Suscription::query()
			->select('id_newsletter_suscription, email_newsletter_suscription, create_newsletter_suscription, cod_cli, nom_cli, pais_cli, lang_newsletter_suscription, id_newsletter')

			->when($onlyRegisterClients, function ($query) {
				return $query->joinCli();
			}, function ($query) {
				return $query->leftJoinCli();
			})
			->where('id_newsletter', $id_newsletter);
			/* ->when($filters, function ($query, $filters) {
				collect($filters)->map(function ($filter) use (&$query) {
					$query->where($filter->field, $filter->operation, $filter->value);
				});
			}) */
			/* ->when($suscriptions, function ($query, $suscriptions) {
				$suscriptions->map(function ($suscription) use (&$query) {
					$query->where('id_newsletter', $suscription);
				});
			}) */

	}

	public function deleteSuscriptions(string $email, bool $checkForGroup = false)
	{
		Fx_Newsletter_Suscription::whereEmail($email)
			->when(!$checkForGroup, function($query) {
				$query->WhereEmp();
			})
			->delete();
	}

	public function deleteSuscriptionsById($idNewsletter, string $email)
	{
		Fx_Newsletter_Suscription::whereEmail($email)->where('id_newsletter', $idNewsletter)->delete();
	}

	public function getNewslettersNames($includingGeneral = false): Collection
	{
		return Fx_Newsletter::query()
			->whereLang()
			->when(!$includingGeneral, function ($query) {
				return $query->where('id_newsletter', '!=', Fx_Newsletter::GENERAL);
			})
			->orderBy('id_newsletter')
			->pluck('name_newsletter', 'id_newsletter');
	}

	/**
	 * @deprecated
	 * Método llamado desde setNewletterWithTable()
	 * @see \App\Http\Controllers\NewsletterController
	 * Con el nuevo sistema de tabla este método quedará en desuso al realizar la migración
	 */
	public function newFamilies()
	{
		$info = $this->newsletterFormat($this->families);

		FxCliWeb::where('LOWER(USRW_CLIWEB)', strtolower($this->email))
			->update($info);

		//Soporte Concursal queria que también se enviara el registro en la newsletter
		$email = new EmailLib('USER_NEWSLETTER');
		if (!empty($email->email)) {
			$email->setTo(strtolower($this->email));
			$email->send_email();
		}
	}

	/**
	 * @deprecated
	 * Con el nuevo sistema de tabla este método quedará en desuso al realizar la migración
	 *
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
		if (!$sendToExternalService = config('app.mailing_service', null)) {
			return false;
		}

		$service = "App\Http\Controllers\\externalws\mailing\services\\$sendToExternalService";
		if (!class_exists($service)) {
			return false;
		}

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
		if (!$sendToExternalService = config('app.mailing_service', null)) {
			return false;
		}

		$service = "App\Http\Controllers\\externalws\mailing\services\\$sendToExternalService";
		if (!class_exists($service)) {
			return false;
		}
		$externalMailingService = new ExternalMailingController(new $service());
		$externalMailingService->remove($email_cli);

		return true;
	}

	public function checkIfUserHaveNewsletters($bindings)
	{
		$sql = "SELECT cli.* FROM FXCLIWEB cli
                    WHERE cli.GEMP_CLIWEB = :gemp AND cli.EMP_CLIWEB = :emp AND LOWER(cli.USRW_CLIWEB) = LOWER(:email) and cod_cliweb = :cod";

		return DB::select($sql, $bindings);
	}

	public function updateCodNewsletter($bindings)
	{
		$sqls = "update FXCLIWEB "
			. "set cod_cliweb = :cod "
			. "where EMAIL_CLIWEB = :email and GEMP_CLIWEB = :gemp and EMP_CLIWEB = :emp and LOWER(usrw_cliweb) = LOWER(:email)";


		DB::select($sqls, $bindings);
	}
}
