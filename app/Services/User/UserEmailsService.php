<?php

namespace App\Services\User;

use App\libs\EmailLib;

class UserEmailsService
{
	/**
	 * Método para enviar un email de desbloqueo de cuenta al usuario.
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
}
