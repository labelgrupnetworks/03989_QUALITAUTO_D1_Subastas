<?php

namespace App\Services\Auction;

use App\Exceptions\Auction\DepositAlreadyExistsException;
use App\Models\V5\FgDeposito;
use App\Models\V5\FgRepresentados;
use App\Services\Notifications\RequestBiddingPermissionAdminNotificationService;
use App\Services\Notifications\RequestBiddingPermissionNotificationService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Config;

class AuctionDepositService
{
	/**
	 * Process the bidding request and create a deposit.
	 *
	 * @param string $codCli
	 * @param array $data
	 * @param array $files
	 * @return void
	 * @throws DepositAlreadyExistsException
	 */
	public function processBiddingRequest(string $codCli, array $data, array $files)
	{
		$user = (new UserService)
			->getUserQueryByCodCli($codCli)
			->select('cod_cli', 'nom_cli', 'rsoc_cli', 'fisjur_cli', 'email_cli', 'cif_cli')
			->first();

		$represented = null;
		if ($data['represented'] != 'N') {
			$represented = FgRepresentados::query()
				->where([
					'id' => $data['represented'],
					'cli_representados' => $codCli,
				])
				->first();
		}

		if (Config::get('app.withDepositNotification', false)) {
			// Check if deposit already exists
			if ($this->depositExists($codCli, $data)) {
				throw new DepositAlreadyExistsException(trans('web.msg_error.deposit_exists', ['contact' => route('contact_page')]));
			}

			// Create pending deposit
			$deposit = $this->createPendingDeposit($codCli, $data, $represented);

			// Notify the user
			(new RequestBiddingPermissionNotificationService($deposit, $represented))
				->send();
		}


		// Notify the admin
		(new RequestBiddingPermissionAdminNotificationService(
			$user,
			$data['cod_sub'],
			$data['ref'],
			$files,
			$represented
		))->send();
	}

	private function depositExists(string $codCli, array $data): bool
	{
		return FgDeposito::query()
			->where([
				'cli_deposito' => $codCli,
				'sub_deposito' => $data['cod_sub'],
				'ref_deposito' => $data['ref']
			])
			->when($data['representedTo'] ?? null, function ($query) use ($data) {
				$query->where('representado_deposito', $data['representedTo']->id);
			})
			->exists();
	}

	private function createPendingDeposit(string $codCli, array $data, ?FgRepresentados $represented): FgDeposito
	{
		return FgDeposito::create([
			'cli_deposito' => $codCli,
			'sub_deposito' => $data['cod_sub'],
			'ref_deposito' => $data['ref'],
			'estado_deposito' => FgDeposito::ESTADO_PENDIENTE,
			'representado_deposito' => $represented?->id,
		]);
	}
}
