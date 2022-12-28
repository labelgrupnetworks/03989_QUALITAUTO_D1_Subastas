<?php

namespace App\Http\Controllers\externalws\mailing\services;

use App\libs\EmailLib;
use App\Models\V5\FxCliWeb;

abstract class ExternalMailingService
{
	abstract function subscribe($email_cli);

	abstract function unsuscribe($email_cli);

	function getUserInfo($email_cli)
	{
		$user = FxCliWeb::query()
			->joinCliCliweb()
			->where('LOWER(USRW_CLIWEB)', strtolower($email_cli))
			->first();

		//substituir por petición a bbdd
		$newsletters = collect([
			(object) ["id_newsletter" => 1, "name_newsletter" => "JOYAS"],
			(object) ["id_newsletter" => 2, "name_newsletter" => "COCHES"],
			(object) ["id_newsletter" => 3, "name_newsletter" => "ARTE"],
			(object) ["id_newsletter" => 4, "name_newsletter" => "MUEBLES"],
			(object) ["id_newsletter" => 5, "name_newsletter" => "JOYAS"],
			(object) ["id_newsletter" => 6, "name_newsletter" => "JOYAS"],
		]);

		$user->subscriptions = $newsletters->filter(function($newsletter) use ($user){
			return $user->{"nllist{$newsletter->id_newsletter}_cliweb"} === "S";
		})->pluck('name_newsletter')->toArray();

		return $user;
	}

	protected function sendEmailError($function, $request, $response, $sendExternalAdmin = false)
	{
		$email = new EmailLib('WS_ERROR');
		if (!empty($email->email)) {
			$email->setTo("enadal@labelgrup.com");
			$email->setAtribute("FUNCTION", $function);
			$email->setAtribute("REQUEST", $request);
			$email->setAtribute("RESPONSE", $response);
			$email->send_email();
		}
	}
}
