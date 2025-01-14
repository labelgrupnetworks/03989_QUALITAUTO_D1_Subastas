<?php

namespace App\Http\Controllers\apilabel;

use App\Models\V5\Fx_Newsletter_Suscription;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class NewsletterSubscriptionController extends ApiLabelController
{
	protected  $newsletterSuscriptionRename = [
		'email' => "email_newsletter_suscription",
		'lang' => "lang_newsletter_suscription",
		'family' => "id_newsletter",
		'createdate' => "create_newsletter_suscription",
	];

	protected  $rules = [
		'email' => "required|email|max:260",
		'lang' => "required|max:2",
		'family' => "numeric"
	];

	protected $searchRules = [
		'email' => "email|max:260",
		'lang' => "max:2",
		'family' => "numeric",
		'startdate' => "date_format:Y-m-d|nullable",
		'enddate' => "date_format:Y-m-d|nullable"
	];

	public function postNewsletterSubscription()
	{
		$items = request("items");
		return $this->createNewsletterSubscription($items);
	}

	public function createNewsletterSubscription($items)
	{
		//eliminamos las suscripciones anteriores antes de crear las nuevas
		$deleteEmails = Arr::pluck($items, "email");
		foreach ($deleteEmails as $email) {
			$this->eraseNewsletterSubscription(["email" => $email]);
		}

		DB::beginTransaction();
		try {
			//Nos aseguramos de que el idioma se guarde en mayúsculas
			$items = array_map(function ($item) {
				$item["lang"] = mb_strtoupper($item["lang"]);
				//si no tiene familia le añadimos la 1 por defecto
				if (!isset($item["family"])) {
					$item["family"] = 1;
				}

				return $item;
			}, $items);

			$this->create($items, $this->rules, $this->newsletterSuscriptionRename, new Fx_Newsletter_Suscription());
			DB::commit();

			return  $this->responseSuccsess();
		} catch (\Exception $e) {
			DB::rollBack();
			return $this->exceptionApi($e);
		}
	}

	public function getNewsletterSubscription()
	{
		return $this->showNewsletterSubscription(request("parameters"));
	}

	public function showNewsletterSubscription($whereVars)
	{
		$newsletterSuscription = Fx_Newsletter_Suscription::query()
			->select("*")
			->when(isset($whereVars["startdate"]), function ($query) use ($whereVars) {
				return $query->where("create_newsletter_suscription", ">=", $whereVars["startdate"]);
			})->when(isset($whereVars["enddate"]), function ($query) use ($whereVars) {
				return $query->where("create_newsletter_suscription", "<=", $whereVars["enddate"]);
			});

		return $this->show($whereVars, $this->searchRules, $this->newsletterSuscriptionRename, $newsletterSuscription, array_flip($this->newsletterSuscriptionRename));
	}

	public function deleteNewsletterSubscription()
	{
		return $this->eraseNewsletterSubscription(request("parameters"));
	}

	public function eraseNewsletterSubscription($whereVars)
	{
		DB::beginTransaction();
		try {
			$rules = ['email' => "required|email|max:260"];

			$newsletterSuscription =  new Fx_Newsletter_Suscription();
			$this->erase($whereVars, $rules, $this->newsletterSuscriptionRename, $newsletterSuscription, false);
			DB::commit();
			return $this->responseSuccsess();
		} catch (\Exception $e) {
			DB::rollBack();
			return $this->exceptionApi($e);
		}
	}
}
