<?php

namespace App\Http\Controllers\externalws\bogota;

use App\Http\Controllers\Controller;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * El archivo no se esta utilizando, pero lo mantengo porque contiene los metodos para la integración tanto con
 * Zoho Invoice, como con Zoho CRM. por si fuesen necesarios.
 */
class ZohoController extends Controller
{
	private $client_id;
	private $client_secret;
	private $grant_code;
	private $refresh_token;
	private $auth_url;
	private $base_url;

	public function __construct()
	{
		$this->client_id = config('app.zoho_client_id');
		$this->client_secret = config('app.zoho_client_secret');

		//es de un solo uso, si es necesario generar otro acceder a https://api-console.zoho.com/ aplicación Self Client, scope ZohoCRM.modules.ALL
		$this->grant_code = config('app.zoho_grant_code');
		$this->refresh_token = config('app.zoho_refresh_token');

		//si el dominio esta en europa, se utilizan los acabados en eu.
		$this->auth_url = "https://accounts.zoho.com/oauth/v2/token";
		$this->base_url = "https://www.zohoapis.com/crm/v3";

		//$this->auth_url = "https://accounts.zoho.eu/oauth/v2/token";
		//$this->base_url = "https://www.zohoapis.eu/crm/v3";
	}

	/**
	 * Metodo de un solo uso para obtener el refresh_token
	 * Copiar el resultado en el config zoho_refreh_token
	 */
	public function getTokensWithGrantCode()
	{
		$query = "?code={$this->grant_code}&client_id={$this->client_id}&client_secret={$this->client_secret}&grant_type=authorization_code";
		$url = "{$this->auth_url}$query";

		$response = $this->curlCall($url, 'POST');
		$responseJson = json_decode($response);

		dd($responseJson);
	}

	private function getAccessTokenWithRefreshToken()
	{
		$query = "?refresh_token={$this->refresh_token}&client_id={$this->client_id}&client_secret={$this->client_secret}&grant_type=refresh_token";
		$url = "{$this->auth_url}$query";

		$response = $this->curlCall($url, 'POST');
		$responseJson = json_decode($response);

		if (empty($responseJson->access_token)) {
			dd($responseJson);
		}

		return $responseJson->access_token;
	}

	public function createContact($users)
	{
		$accessToken = $this->getAccessTokenWithRefreshToken();
		$url = "{$this->base_url}/Contacts/upsert";
		$header = ["Authorization: Zoho-oauthtoken $accessToken"];
		$body = json_encode(['data' => $users, 'duplicate_check_fields' => ['Email']]);

		$response = $this->curlCall($url, 'POST', $header, $body);
		$jsonResponse = json_decode($response);

		return $jsonResponse;
	}

	private function curlCall($url, $method = 'GET', $header = [], $body = [])
	{
		$isPost = $method == 'POST';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_VERBOSE, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //comentar en produccion
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); //comentar en produccion
		curl_setopt($curl, CURLOPT_TIMEOUT, 300);
		curl_setopt($curl, CURLOPT_POST, $isPost); //Regular post
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		if (!empty($header)) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		}

		if ($isPost) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
		}

		$result = curl_exec($curl);
		curl_close($curl);

		return $result;
	}

	public function getContact($cod_cli)
	{
		$contact = $this->getModelWithCriteria("Contacts", "External_ID", $cod_cli, "Email,C_digo_de_cliente_Label,External_ID,First_Name");
		dd($contact);
	}

	public function getAllContacts()
	{
		$contacts = $this->getAllModel('Contacts', 'Email,C_digo_de_cliente_Label,First_Name');
		dd($contacts);
	}

	public function getContactWithEmail($email, $fields)
	{
		$accessToken = $this->getAccessTokenWithRefreshToken();

		$header = [
			"Authorization: Zoho-oauthtoken $accessToken",
		];

		if (is_array($fields)) {
			$fields = implode(",", $fields);
		}

		$url = "{$this->base_url}/Contacts/search?email=$email&fields=$fields";

		$response = $this->curlCall($url, 'GET', $header);
		$jsonResponse = json_decode($response);

		dd($jsonResponse);

		return $jsonResponse;
	}

	public function getModelByExternalId(string $model, $whereField, $whereId, $fields)
	{
		$accessToken = $this->getAccessTokenWithRefreshToken();

		$header = [
			"Authorization: Zoho-oauthtoken $accessToken",
			"X-EXTERNAL: $model.$whereField"
		];

		if (is_array($fields)) {
			$fields = implode(",", $fields);
		}

		$url = "{$this->base_url}/$model/$whereId?fields=$fields";

		$response = $this->curlCall($url, 'GET', $header);
		$jsonResponse = json_decode($response);

		return $jsonResponse;
	}

	public function getAllModel(string $model, $fields)
	{
		$accessToken = $this->getAccessTokenWithRefreshToken();

		$header = [
			"Authorization: Zoho-oauthtoken $accessToken"
			//"X-com-zoho-invoice-organizationid: $zohoOrganitzation", //solo en invoice modulo
		];

		if (is_array($fields)) {
			$fields = implode(",", $fields);
		}

		$url = "{$this->base_url}/$model?fields=$fields";

		$response = $this->curlCall($url, 'GET', $header);
		$jsonResponse = json_decode($response);

		return $jsonResponse;
	}

	/**
	 * Metodo de prueba para obtener todos los leads desde la api
	 */
	public function getAllLeadsWithApi()
	{
		$response = $this->getAllModel('Leads', 'Last_Name,Email,Created_Time');
		return $response;
	}

	/**
	 * Metodo de prueba para llamar a las funciones serverless
	 * ahora mismo no se esta utilizando.
	 */
	public function runApiFunction()
	{
		$apiKey = config('app.zoho_api_key');

		$function = "create_contact";
		$url = "https://www.zohoapis.eu/crm/v2/functions/$function/actions/execute?auth_type=apikey&zapikey=$apiKey";

		$contact = [
			'First_Name' => 'Contact',
			'Last_Name' => 'Test',
			'Email' => 'test@laravel.com',
			'Phone' => 'Phone',
			'Date_of_Birth' => '2000/01/01',
			'Mailing_City' => 'Barcelona',
			'Mailing_Street' => 'Carrer de la Rambla',
			'Mailing_Zip' => '08008',
			'Mailing_Country' => 'Spain',
			'Lead_Source' => 'Casos de la Web',
		];
		$body = array('arguments' => json_encode($contact));

		$response = $this->curlCall($url, 'POST', [], $body);
		$jsonResponse = json_decode($response);

		//{"code":"success","details":{"output":"Contacto creado correctamente. conctactId: null","output_type":"string","id":"130292000000008001"},"message":"function executed successfully"}
		dd($jsonResponse);
	}

	/**
	 * Metodo para obtener token desde el cliente. En nuestro caso no nos sirve
	 */
	public function auth(Request $request)
	{
		$uri = "ruta redireccion callback";
		$scope =  "ZohoCRM.modules.Leads.ALL";
		$clientid = config('app.zoho_client_id');
		$accestype = 'offline';

		$redirectTo = 'https://accounts.zoho.com/oauth/v2/auth' . '?' . http_build_query(
			[
				'client_id' => $clientid,
				'redirect_uri' => $uri,
				'scope' => $scope,
				'response_type' => 'code',
				'access_type' => $accestype,
			]
		);
		//\Session()->put('zoho_contact_id', $request->id);
		return redirect($redirectTo);
	}
}
