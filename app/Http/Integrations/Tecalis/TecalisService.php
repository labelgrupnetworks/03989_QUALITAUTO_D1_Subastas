<?php

namespace App\Http\Integrations\Tecalis;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Clase para la integración con el servicio de Tecalis.
 *
 * - Documentación del servicio.
 * @see https://int.admin.identity.tecalis.dev/public/MO-1127088153-021220-1702_en.pdf
 *
 * - Documentación de la API de Tecalis.
 * @see https://documenter.getpostman.com/view/2973045/TVmMfd2w
 */
class TecalisService
{
	public string $url;
	public string $apiKey;

	function __construct()
	{
		$this->url = 'https://int.ms-service.identity.tecalis.dev';
		$this->apiKey = Config::get('services.tecalis.api_key');
	}

	/**
	 * Método para autenticar el servicio KYC de Tecalis.
	 *
	 * @return TecalisResponseDTO
	 * @throws \Exception
	 */
	public function auth()
	{
		$response = Http::post($this->url . '/auth', [
			'apiKey' => $this->apiKey,
			'config' => [
				'configuration' => [
					'kyc' => [
						'excludedDocTypes' => [],
						'allowedDocTypes' => [
							'ESP_DNI',
							'ESP_NIE',
							'ITA_DNI',
							'ITA_NIE',
							'PRT_DNI',
							'PRT_NIE',
							'PASSPORT'
						],
						'status_post_url' => route('user.kyc_callback'),
						'status_report' => [
							'Verification OK'
						],
						'methods' => [
							'ReadMrz' => true,
							'VerifyData' => true,
							'Images' => true,
							'Liveness' => true,
							'FaceMatch' => true,
							'FacialRecognition' => true,
							'Selfie' => false,
							'FraudScoring' => true,
							'ImagesUrl' => false,
							'StorageUpload' => true,
							'Otp' => true,
							'Location' => true,
							'CheckFacesNumber' => true
						],
						'fraudScoring' => [
							'aeat' => true,
							'expirationDate' => true,
							'legalAge' => false,
							'photocopyCheck' => false,
							'hologramCheck' => false
						]
					],
					'front' => [
						'skin' => 'dark',
						'iccid' => false,
						'msisdn' => false,
						'barcode_min' => false,
						'borders' => true,
						'auditoria' => false,
						'css_url' => false,
						'title_kyc' => 'KYC',
						'headless_barcode' => false,
						'wait_after_kyc_success' => true,
						'prioridad_interrupcion' => 'postergado',
						'borders_percentage' => 50,
						'borders_width' => 50,
						'video_mode' => 'native',
						'final_imprimible' => false,
						'mirror_mode_pc' => false,
						'kyc_mode' => 'auto',
						'liveness_guidance_mode' => 'smile',
						'location_config' => 'optional',
						'adapt_detector_to_passport' => true,
						'no-spinner' => false,
						'default_lang' => 'en',
						'force_lang' => 'es',
						'retries_before_manual_pc' => 1,
						'retries_before_manual_others' => 3,
						'logs' => false,
						'redirect' =>route('user.registered'),
						'accept_manual_text' => ''
					]
				]
			]
		]);

		if($response->failed()) {
			Log::error("Error en el servicio de autenticación de Tecalis", [
				'data' => $response->body()
			]);
			throw new \Exception("Error en el servicio de autenticación");
		}

		return TecalisResponseDTO::fromArray($response->json());
	}
}
