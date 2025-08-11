<?php

namespace App\Services\Notifications;

use App\libs\EmailLib;
use App\Models\V5\FgDeposito;
use App\Models\V5\FgRepresentados;

class RequestBiddingPermissionNotificationService
{

	public function __construct(
		private readonly FgDeposito $deposit,
		private readonly ?FgRepresentados $represented
	) {}

	/**
	 * Send a notification to the user requesting bidding permission.
	 *
	 * @return void
	 */
	public function send()
	{
		$email = new EmailLib('USER_BIDDING_PERMISSION');
		if (empty($email->email)) {
			return;
		}

		$email->setUserByCod($this->deposit->cli_deposito, true);
		$email->setLot($this->deposit->sub_deposito, $this->deposit->ref_deposito);
		$email->setAtribute("BANK_REFERENCE", $this->deposit->bank_reference);

		if(!empty($this->deposit->representado_deposito)) {
			$representedToString = $this->represented->toEmailString();
			$email->setAtribute("REPRESENTED_TO", $representedToString);
		}

		$email->send_email();
	}

}
