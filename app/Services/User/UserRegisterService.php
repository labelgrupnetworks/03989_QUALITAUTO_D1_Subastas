<?php

namespace App\Services\User;

use App\Enums\User\UserKycStatus;
use App\Http\Integrations\Tecalis\TecalisService;
use App\libs\EmailLib;
use App\Models\V5\FxCli;
use App\Models\V5\Kyc;
use Illuminate\Support\Facades\Log;

class UserRegisterService
{

	/**
	 * Método para registrar un usuario en el sistema KYC.
	 *
	 * @param string $userId ID del usuario a registrar.
	 * @return string Url para redirigir al usuario a la verificación KYC.
	 */
	public function registerToKyc($userId): string
	{
		//obtenemos los datos del servicio kyc. por el momento solo tenemos tecalis
		$kycService = new TecalisService();
		$kycData = $kycService->auth();

		$newKyc = Kyc::create([
			'cli' => $userId,
			'estado' => UserKycStatus::PENDING,
			'kyc' => $kycData['auth_uuid'],
		]);

		Log::debug("Almacenando intento de registro KYC", [
			'data' => $newKyc
		]);

		return $kycData['pwcs_url'];
	}


	/**
	 * Método para recibir el callback del servicio KYC.
	 * Ejemlo de respuesta ok:
	 * {"request":{"status":"Verification OK","auth_uuid":"68f766b9-842d-47b5-bd22-cd3b2970fd791741690918","op_uuid":"1429475a-4615-4bb1-890e-4eda0442de3d"}}
	 */
	public function kycCallback($request)
	{
		if ($request->input('status') != 'Verification OK') {
			Log::debug("El registro KYC no ha sido correcto", [
				'data' => $request->all()
			]);
			return;
		}

		$kycId = $request->input('auth_uuid');

		$kyc = Kyc::where('kyc', $kycId)->first();

		$kyc->estado = UserKycStatus::APPROVED;
		$kyc->save();


		//save. de momento, log.
		Log::debug("Actualizando intento de registro KYC", [
			'data' => $request->all(),
			'kycId' => $kycId
		]);

		FxCli::where([
			'cod_cli' => $kyc->cli
		])->update([
			'blockpuj_cli' => 'N',
		]);

		Log::debug("Desbloqueando usuario", [
			'data' => $kyc->cli
		]);
	}
}
