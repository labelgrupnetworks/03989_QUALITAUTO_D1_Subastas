<?php

namespace App\Http\Integrations\Tecalis;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class TecalisService
{
	public string $url;
	public string $apiKey;

	function __construct()
	{
		$this->url = 'https://int.ms-service.identity.tecalis.dev';
		$this->apiKey = Config::get('services.tecalis.api_key');
	}

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
						'status_post_url' => Config::get('app.url') . '/tecalis/callback',
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
						'redirect' => Config::get('app.url') . '/tecalis/redirect',
						'accept_manual_text' => ''
					]
				]
			]
		]);

		$authObject =  $this->authDto($response->object());

		//devolver solamente enlace para acceder al formulario
		//mostrar como enlace en la vista
		echo "<a href='" . $authObject['pwcs_url'] . "'>Acceder a formulario</a>";
	}

	private function authDto($authResponse)
	{
		return [
			//mensaje de respuesta
			'message' => $authResponse->message ?? null,
			//token de autenticación
			'token_pwcs' => $authResponse->token_pwcs ?? null,
			//fecha de expiración del token
			'expireAt' => $authResponse->expireAt ?? null,
			//url para acceder automáticamente al formulario
			'pwcs_url' => $authResponse->pwcs_url ?? null,
			//url para acceder a formulario donde introducir número de teléfono o email para recibir enlace a pwcs_url
			//'sdr_frm_url' => $authResponse->sdr_frm_url ?? null,
			//url para enviar por post  número de teléfono o email donde se enviarán enlace a pwcs_url
			//'pst_frm_url' => $authResponse->pst_frm_url ?? null,
			//uuid único con el que se identificará el proceso.
			'auth_uuid' => $authResponse->auth_uuid ?? null,
		];
	}
}
