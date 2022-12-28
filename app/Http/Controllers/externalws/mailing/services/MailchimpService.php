<?php

namespace App\Http\Controllers\externalws\mailing\services;

use App\Models\V5\FsIdioma;
use GuzzleHttp\Client;
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
		$user = $this->getUserInfo($email_cli);

		if(empty($user->subscriptions)) {
			$this->loggin("empty newsletters", ['user' => $user]);
		}

		$completName = array_map("trim", explode(",", $user->nom_cliweb));
		$nameHaveComa = count($completName) !== 1;
		$name = $nameHaveComa ? $completName[1] : $completName[0];
		$lastName = $nameHaveComa ? $completName[0] : "";

		$idioma = FsIdioma::where('cod_idioma', $user->idioma_cli)->value('des_idioma');

		$userHash = md5($user->email_cliweb);
		$resource = "{$this->membersResource}/{$userHash}";

		$body = json_encode([
			'email_address' => $user->email_cliweb,
			'status' => 'subscribed',
			'merge_fields' => [
				'FNAME' => $name,
				'LNAME' => $lastName,
				'IDIOMA' => $idioma,
				'PAIS' => $user->pais_cli,
				'GRUPO' => implode(',', $user->subscriptions)
			],
			//'tags' => $user->subscriptions
		]);

		$request = new Request('PUT', $resource, $this->headerWithAutoritzation, $body);

		$this->sendRequest($request);
	}

	function unsuscribe($email_cli)
	{
		$user = $this->getUserInfo($email_cli);
		$userHash = md5($user->email_cliweb);
		$resource = "{$this->membersResource}/{$userHash}";

		$body = json_encode([
			'email_address' => $user->email_cliweb,
			'status' => 'unsubscribed',
		]);

		$request = new Request('PUT', $resource, $this->headerWithAutoritzation, $body);

		$this->sendRequest($request);
	}

	private function sendRequest(Request $request)
	{
		try {
			$response = $this->client->send($request);
			$responseJson = json_decode($response->getBody()->getContents(), true);
			$this->loggin("mailchimp response", $responseJson);
		} catch (\Throwable $th) {
			$this->loggin("mailchimp error", $th->getMessage());
		}
	}

	private function loggin($title, $message)
	{
		if(!config('app.debug')){
			return;
		}
		Log::debug($title, ['message' => $message]);
	}

}
