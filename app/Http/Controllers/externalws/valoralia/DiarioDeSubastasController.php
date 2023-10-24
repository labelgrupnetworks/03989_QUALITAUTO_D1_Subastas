<?php

namespace App\Http\Controllers\externalws\valoralia;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use App\Providers\ToolsServiceProvider;
use App\Models\V5\FgSub;
use App\Models\V5\FgHces1;
use App\Models\V5\FgAsigl0;
use App\Models\Subasta;
use App\Http\Controllers\Controller;

class DiarioDeSubastasController extends Controller
{
	private $base_url;
	private $client;
	private $email;
	private $environment;
	private $password;

	public function __construct()
	{
		$this->base_url = "https://api.diariodesubastas.com/api/v1";
		$this->client = new Client(['verify' => !config('app.debug')]);
		$this->environment = Config::get('app.debug') ? 'test' : 'prod';
		$this->email = Config::get('app.dds_api_email');
		$this->password = Config::get('app.dds_api_secret');
	}

	public function getAccessToken()
	{
		$cacheKey = 'dds_access_token';

		if (Cache::has($cacheKey)) {
			return Cache::get($cacheKey);
		}

		$url = "{$this->base_url}/auction-management/login";

		$body = [
			'email' => $this->email,
			'password' => $this->password,
		];

		$body = json_encode([
			'email' => $this->email,
			'password' => $this->password,
			'remember' => 1,
		]);

		$headers = [
			'Content-Type' => 'application/json',
			'Accept' => '*/*',
		];

		$request = new Request('POST', $url, $headers, $body);

		$response = $this->client->send($request);
		$responseJson = json_decode($response->getBody()->getContents(), true);

		$seconsTimeToCache = strtotime($responseJson['expires_at']) - strtotime(now());
		Cache::put($cacheKey, $responseJson['access_token'], $seconsTimeToCache);

		return $responseJson['access_token'];
	}

	public function check()
	{
		$url = "{$this->base_url}/auction-management/check";

		$headers = [
			'Content-Type' => 'application/json',
			'X-Requested-With' => 'XMLHttpRequest',
			'Authorization' => "Bearer {$this->getAccessToken()}",
		];

		$request = new Request('GET', $url, $headers);

		$response = $this->client->send($request);
		$responseJson = json_decode($response->getBody()->getContents(), true);
		dd($responseJson);
	}

	public function upsertAuction($arguments)
	{
		$codSub = $arguments['codSub'];
		$refAsigl0 = $arguments['refAsigl0'];
		$customProperties = $arguments['customProperties'];

		$lot = FgAsigl0::getLotWithSession($codSub, $refAsigl0);
		if(!$this->isValidLot($lot)) {
			throw new WsLotException('El lote no cumple las condiciones para ser enviado a Diario de Subastas');
		}

		$isExported = $lot->idorigen_asigl0 != "{$lot->sub_asigl0}-{$lot->ref_asigl0}";

		$response = $isExported
			? $this->updateAuction($lot, $customProperties)
			: $this->addAuction($lot, $customProperties);

		if($lot->destacado_asigl0 == 'S') {
			$this->updateAuctionState($lot);
		}

		return $response;
	}

	public function addAuction($lot, $customProperties)
	{
		$url = "{$this->base_url}/auction-management/{$this->environment}/auction";
		$body = $this->getParsedLot($lot, $customProperties);

		$response = $this->sendRequest('POST', $url, $body);

		$lot->idorigen_asigl0 = $response['id'];

		$this->updateIdOrigen($lot);
		return $response;
	}

	public function updateAuction($lot, $customProperties)
	{
		$url = "{$this->base_url}/auction-management/{$this->environment}/auction/{$lot->idorigen_asigl0}";
		$body = $this->getParsedLot($lot, $customProperties);

		$response = $this->sendRequest('PUT', $url, $body);
		return $response;
	}

	public function updateAuctionState($lot)
	{
		if($lot->idorigen_asigl0 == "{$lot->sub_asigl0}-{$lot->ref_asigl0}" || $lot->destacado_asigl0 == 'N') {
			return;
		}

		$url = "{$this->base_url}/auction-management/{$this->environment}/auction/{$lot->idorigen_asigl0}/featured";

		$response = $this->sendRequest('POST', $url, [], false);
		return $response;
	}

	public function updateStatusAuction($arguments)
	{
		$codSub = $arguments['codSub'];
		$refAsigl0 = $arguments['refAsigl0'];

		$lot = FgAsigl0::getLotWithSession($codSub, $refAsigl0);

		if($lot->idorigin_asigl0 == "{$lot->sub_asigl0}-{$lot->ref_asigl0}") {
			throw new WsLotException('El lote no cumple las condiciones para ser actualizado en Diario de Subastas');
		}

		$url = "{$this->base_url}/auction-management/{$this->environment}/auction/{$lot->idorigen_asigl0}/status";

		//estados posibles: activa, paralizada, reanudada, finalizada o cancelada.
		$state = 'activa';
		$body = [
			'estado' => $state,
			'ultimaPuja' => max($lot->impsalhces_asigl0, $lot->implic_hces1),
		];

		$response = $this->sendRequest('POST', $url, $body);
		return $response;
	}

	private function sendRequest($method, $url, $body, $withException = true)
	{
		$headers = [
			'Content-Type' => 'application/json',
			'X-Requested-With' => 'XMLHttpRequest',
			'Authorization' => "Bearer {$this->getAccessToken()}",
		];

		$request = new Request($method, $url, $headers, json_encode($body));
		$responseJson = null;

		try {
			$response = $this->client->send($request);
			$responseJson = json_decode($response->getBody()->getContents(), true);

		} catch (ClientException $e) {
			$response = $e->getResponse();
			$responseJson = json_decode($response->getBody()->getContents(), true);
			$message = $responseJson['message'] ?? $e->getMessage();

			if($withException) {
				throw new WsLotException($message, $body, $responseJson);
			}
		}

		return $responseJson;
	}

	private function isValidLot(FgAsigl0 $lot)
	{
		return $lot->cerrado_asigl0 == 'N' && $lot->subc_sub == FgSub::SUBC_SUB_ACTIVO;
	}

	private function getParsedLot(FgAsigl0 $lot, $customProperties)
	{
		$features = $lot->getFeatures();
		$featuresParsed = $this->parseFeatures($features, $customProperties);
		$sectionsParsed = $this->parseTypeAndSubtpe($lot->sec_hces1, $customProperties);
		$lotParsed = $this->parseAuction($lot);

		$data = array_merge($lotParsed, $featuresParsed, $sectionsParsed);
		ksort($data);

		return $data;
	}

	// function parse auction object to dds auction object
	private function parseAuction(FgAsigl0 $lot)
	{
		$addHost = function ($path) {
			//return request()->getSchemeAndHttpHost() . $path;
			$host = Config::get('app.url');
			return "{$host}{$path}";
		};
		$imagesWithRelativePath = $lot->getImages();
		$documentsWithRelativePath = $lot->getFiles();

		$images = array_map($addHost, $imagesWithRelativePath);
		$documents = array_map($addHost, $documentsWithRelativePath);

		$scale = (new Subasta(['cod' => $lot->sub_asigl0]))->getNextScaleValue($lot->impsalhces_asigl0);

		//en dds no utilizan los segundos
		$lot->fini_asigl0 = substr($lot->fini_asigl0, 0, 16);
		$lot->ffin_asigl0 = substr($lot->ffin_asigl0, 0, 16);

		return [
			'nombre' => $lot->descweb_hces1,
			'descripcion' => strip_tags(html_entity_decode($lot->desc_hces1), '<br>,<p>'),
			'otros' => $lot->descdet_hces1,
			'identificador' => "{$lot->sub_asigl0}-{$lot->ref_asigl0}",
			'url' => ToolsServiceProvider::url_lot($lot->sub_asigl0, $lot->reference, $lot->descweb_hces1, $lot->ref_asigl0, $lot->numhces_asigl0, $lot->webfriend_hces1),
			//'condiciones_generales' => $lot->condiciones_generales,
			//'condiciones_particulares' => $lot->condiciones_particulares,
			'valorSubasta' => $lot->imptas_asigl0,
			'tasacion' => $lot->imptash_asigl0,
			'pujaMinima' => $lot->impsalhces_asigl0,
			'tramosEntrePujas' => $scale, //escalado
			'importeDelDeposito' => 0, //deposito
			'tieneReserva' => !empty($lot->impres_hces1),
			'fechaApertura' => $lot->fini_asigl0,
			'fechaCierre' => $lot->ffin_asigl0,
			'imagenes' => implode(',', $images),
			'documentos' => implode(',', $documents)
		];
	}

	private function parseFeatures($features, $customProperties)
	{
		$arraysFeatures = array_map(function ($feature) use ($features, $customProperties) {
			return [$feature => $this->getFeatureValue($features, $customProperties, $feature)];
		},  array_keys($customProperties['features']));

		return array_merge(...$arraysFeatures);
	}

	private function getFeatureValue($features, $customProperties, $featureName)
	{
		$feature = $features->firstWhere('id_caracteristicas', Arr::get($customProperties, "features.{$featureName}", 0));

		if (!$feature) {
			return null;
		}

		return $feature->value_caracteristicas_hces1;
	}

	private function parseTypeAndSubtpe($section, $customProperties)
	{
		$type = Arr::get($customProperties, "sections.{$section}.type", null);
		$subtype = Arr::get($customProperties, "sections.{$section}.subtype", null);

		return [
			'tipo' => $type,
			'subtipo' => $subtype,
		];
	}

	private function updateIdOrigen($lot)
	{
		FgAsigl0::where([
			['sub_asigl0', $lot->sub_asigl0],
			['ref_asigl0', $lot->ref_asigl0]
		])->update([
			'idorigen_asigl0' => $lot->idorigen_asigl0,
		]);

		FgHces1::where([
			['sub_hces1', $lot->sub_asigl0],
			['ref_hces1', $lot->ref_asigl0]
		])->update([
			'idorigen_hces1' => $lot->idorigen_asigl0,
		]);
	}
}
