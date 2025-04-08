<?php

namespace App\Services\User;

use App\libs\EmailLib;

class UserEmailsService
{
	/**
	 * Enviar un email de desbloqueo de cuenta al usuario.
	 *
	 * @param string $userId ID del usuario a desbloquear.
	 * @return void
	 */
	public function sendUnlockAccountEmail(string $userId): void
	{
		$email = new EmailLib('UNLOCK_ACCOUNT');
		if (!empty($email->email)) {
			$email->setUserByCod($userId, true);
			$email->send_email();
		}
	}

	/**
	 * Enviar un email de verificación de KYC pendiente al usuario.
	 */
	public function sendPendigKycVerificationEmail(string $userId): void
	{
		$email = new EmailLib('PENDING_VERIFICATION');
		if (!empty($email->email)) {
			$email->setUserByCod($userId, true);
			$email->send_email();
		}
	}
}
