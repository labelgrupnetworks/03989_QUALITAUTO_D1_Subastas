<?php

namespace App\Http\Controllers\externalws\mailing\services;

use App\libs\EmailLib;
use App\Models\Newsletter;
use App\Models\V5\FsIdioma;
use App\Models\V5\FxCli;

abstract class ExternalMailingService
{
	abstract function subscribe($email_cli);

	abstract function unsuscribe($email_cli);

	function getUserInfo($email_cli)
	{
		$newsletterModel = new Newsletter();
		$suscriptions = $newsletterModel->getSuscriptionsWithNamesByEmail($email_cli);

		$userInfo = FxCli::where('lower(email_cli)', mb_strtolower($email_cli))->first() ?? new FxCli();

		$userLanguage = $userInfo->idioma_cli ?? optional($suscriptions->first())->lang_newsletter_suscription ?? 'ES';
		$userInfo->idioma = FsIdioma::where('lower(cod_idioma)', mb_strtolower($userLanguage))->value('des_idioma');
		$userInfo->idioma_short = $userLanguage;

		$completName = array_map("trim", explode(",", $userInfo->nom_cli ?? ''));
		$nameHaveComa = count($completName) !== 1;

		$userInfo->first_name = $nameHaveComa ? $completName[1] : $completName[0];
		$userInfo->last_name = $nameHaveComa ? $completName[0] : "";

		return [
			'newsletterSuscriptions' => $suscriptions->pluck('name.name_newsletter')->toArray(),
			'user' => $userInfo
		];
	}

	protected function sendEmailError($function, $request, $response)
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
