<?php

namespace App\Services\User;

use App\Enums\User\UserKycStatus;
use App\Http\Integrations\Tecalis\TecalisCallbackDTO;
use App\Http\Integrations\Tecalis\TecalisService;
use App\Models\V5\FxCli;
use App\Models\V5\Kyc;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserRegisterService
{
	private $kycService;
	private $userEmailService;

	public function __construct()
	{
		$this->kycService = new TecalisService();
		$this->userEmailService = new UserEmailsService();
	}

	/**
	 * Método para registrar un usuario en el sistema KYC.
	 * Por el momento solamente utilizamos el servicio de Tecalis. en caso de que
	 * se necesite otro servicio, se puede crear una interfaz y un servicio para
	 * cada uno de ellos.
	 *
	 * @param string $userId ID del usuario a registrar.
	 * @return string Url para redirigir al usuario a la verificación KYC.
	 */
	public function registerToKyc(string $userId): string
	{
		try {
			$kycData = $this->kycService->auth();

			Kyc::create([
				'cli' => $userId,
				'estado' => UserKycStatus::PENDING,
				'kyc' => $kycData->auth_uuid,
			]);

			return $kycData->pwcs_url;
		} catch (\Exception $e) {
			return '';
		}
	}

	/**
	 * Método para manejar la respuesta del servicio KYC de Tecalis.
	 *
	 * @param TecalisCallbackDTO $callbackDto DTO con la información de la respuesta.
	 * @return void
	 */
	public function kycCallback(TecalisCallbackDTO $callbackDto)
	{
		if (!$callbackDto->isVerificationOk()) {
			return;
		}

		$kyc = Kyc::where('kyc', $callbackDto->auth_uuid)->first();
		if (!$kyc) {
			Log::error('KYC not found for auth_uuid: ' . $callbackDto->auth_uuid);
			return;
		}

		DB::transaction(function () use ($kyc) {
			$kyc->estado = UserKycStatus::APPROVED;
			$kyc->save();

			//Desbloqueamos la cuenta del usuario
			FxCli::where([
				'cod_cli' => $kyc->cli
			])->update([
				'blockpuj_cli' => 'N',
				'f_modi_cli' => now(),
			]);
		});

		$this->userEmailService->sendUnlockAccountEmail($kyc->cli);
		return;
	}
}
