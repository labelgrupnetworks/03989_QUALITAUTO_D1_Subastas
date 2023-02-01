<?php

namespace App\Http\Controllers\externalws\mailing\services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class MailchimpService extends ExternalMailingService
{
	private $membersResource;
	private $headerWithAutoritzation;
	private $client;

	function __construct()
	{
		$server = config('app.mailchimp_server_prefix', null);
		$listId = config('app.mailchimp_list_id', null);

		$this->membersResource = "https://{$server}.api.mailchimp.com/3.0/lists/{$listId}/members";

		$this->client = new Client(['verify' => !config('app.debug')]);

		$api = config('app.mailchimp_api_key', null);
		$auth = base64_encode("key:{$api}");

		$this->headerWithAutoritzation = [
			'Authorization' => "Basic $auth",
			'Content-Type' => 'application/json',
		];
	}

	function subscribe($email_cli)
	{
		[
			'newsletterSuscriptions' => $newsletterSuscriptions,
			'user' => $user
		] = $this->getUserInfo($email_cli);

		if (empty($newsletterSuscriptions)) {
			$this->loggin("empty newsletters", ['user' => $email_cli]);
			return false;
		}

		$userHash = md5(mb_strtolower($email_cli));
		$resource = "{$this->membersResource}/{$userHash}";

		$language = [
			'ES' => 'es_ES',
			'EN' => 'en'
		];

		$body = json_encode([
			'email_address' => $email_cli,
			'status' => 'subscribed',
			'language' => $language[$user->idioma_short] ?? 'es_ES',
			'merge_fields' => [
				'FNAME' => $user->first_name ?? '',
				'LNAME' => $user->last_name ?? '',
				"ADDRESS" => [
					"addr1" => $user->dir_cli ?? '-',
					"addr2" => $user->dir2_cli ?? '',
					"city" => $user->pob_cli ?? '-',
					"state" => $user->pro_cli ?? '',
					"zip" => $user->cp_cli ?? '-',
					"country" => $user->codpais_cli ?? ''
				],
				'PHONE' => $user->tel1_cli ?? '',
				'IDIOMA' => $user->idioma ?? '',
				'PAIS' => $user->pais_cli ?? '',
				'GRUPO' => implode(',', $newsletterSuscriptions),
				'CODCLI' => $user->cod_cli ?? '',
				'POB' => $user->pob_cli ?? '',
				'PRO' => $user->pro_cli ?? '',
			],
			//'tags' => $user->subscriptions
		]);

		$request = new Request('PUT', $resource, $this->headerWithAutoritzation, $body);

		$this->sendRequest($request);
	}

	function unsuscribe($email_cli)
	{
		$userHash = md5(mb_strtolower($email_cli));
		$resource = "{$this->membersResource}/{$userHash}";

		$body = json_encode([
			'email_address' => $email_cli,
			'status' => 'unsubscribed',
		]);

		$request = new Request('PUT', $resource, $this->headerWithAutoritzation, $body);

		$this->sendRequest($request);
	}

	private function sendRequest(Request $request)
	{
		$requestBody = $request->getBody()->getContents();
		try {
			$response = $this->client->send($request);
			$responseJson = json_decode($response->getBody()->getContents(), true);
			$this->loggin("mailchimp response", $responseJson);
		} catch (ClientException $e) {
			$error = $e->getResponse()->getBody()->getContents();
			$this->loggin("mailchimp error", $error);
			$this->sendEmailError('mailchimp', $requestBody, $error);
		}
	}

	private function loggin($title, $message)
	{
		/* if (!config('app.debug')) {
			return;
		} */
		Log::debug($title, ['message' => $message]);
	}
}
