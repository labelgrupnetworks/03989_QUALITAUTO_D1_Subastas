<?php

namespace App\Http\Services\b2b;

use App\Http\Controllers\apilabel\ClientController;
use App\Imports\b2b\UsersB2BImport;
use App\Jobs\MailJob;
use App\libs\EmailLib;
use App\Mail\AuctionInvitationMail;
use App\Models\V5\FgSub;
use App\Models\V5\FgSubInvites;
use App\Models\V5\FxCli;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UserB2BService
{
	public function __construct()
	{
		// Constructor
	}

	public function getInvitationsByOwnerAndInvited($ownerCod, $invitedCod)
	{
		$invitation = FgSubInvites::query()
			->with('invited:cod_cliweb, cod2_cliweb, email_cliweb')
			->where('owner_codcli_subinvites', $ownerCod)
			->where('invited_codcli_subinvites', $invitedCod)
			->first();

		if (!$invitation) {
			return null;
		}

		return UserB2BData::fromInvitationWithInvited($invitation);
	}


	/**
	 * @throws Exception
	 */
	public function createInvitation($ownerCod, UserB2BData $userData)
	{
		$fxCli = $this->getOrCreateCli($userData);

		$auctionCode = FgSub::query()
			->where('agrsub_sub', $ownerCod)
			->first();

		$hasInvite = FgSubInvites::query()
			->where('owner_codcli_subinvites', $ownerCod)
			->where('invited_codcli_subinvites', $fxCli->cod_cli)
			->where('codsub_subinvites', $auctionCode->cod_sub)
			->first();

		if ($hasInvite) {
			throw new Exception('El cliente ya ha sido invitado a la subasta');
		}

		FgSubInvites::create([
			'emp_subinvites' => Config::get('app.emp'),
			'owner_codcli_subinvites' => $ownerCod,
			'codsub_subinvites' => $auctionCode->cod_sub,
			'invited_codcli_subinvites' => $fxCli->cod_cli,
			'invited_nom_subinvites' => $userData->name,
			'invited_cif_subinvites' => $userData->idnumber,
			'invited_tel_subinvites' => $userData->phone
		]);
	}

	public function updateInvitation($ownerCod, $invitedCod, UserB2BData $userData)
	{
		$invitation = FgSubInvites::query()
			->where('owner_codcli_subinvites', $ownerCod)
			->where('invited_codcli_subinvites', $invitedCod)
			->first();

		if (!$invitation) {
			throw new Exception('Invitación no encontrada');
		}

		FgSubInvites::query()
			->where('owner_codcli_subinvites', $ownerCod)
			->where('invited_codcli_subinvites', $invitedCod)
			->update([
				'invited_nom_subinvites' => $userData->name,
				'invited_cif_subinvites' => $userData->idnumber,
				'invited_tel_subinvites' => $userData->phone
			]);
	}

	public function importFromExcel($ownerCod, $file)
	{
		Excel::import(new UsersB2BImport($this, $ownerCod), $file);
	}

	private function getOrCreateCli(UserB2BData $userData)
	{
		$fxCli = FxCli::query()
			->where('lower(email_cli)', mb_strtolower($userData->email))
			->first();

		if (!$fxCli) {
			$fxCli = $this->createCliWithApi($userData);
		}

		return $fxCli;
	}

	private function createCliWithApi(UserB2BData $userData)
	{
		$apiUser = array_merge($userData->toArray(), [
			'idorigincli' => FxCli::newCod2Cli(),
			'registeredname' => $userData->name,
			'source' => FxCli::TIPO_CLI_WEB,
			'createdate' => date("Y-m-d h:i:s"),
			'updatedate' => date("Y-m-d h:i:s"),
		]);

		$apiRequest = [$apiUser];
		$clientController = new ClientController();
		$json = $clientController->createClient($apiRequest);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			Log::error('Error creating user with API', ['error' => $result]);
			throw new Exception('Error creating user with API');
		}

		return FxCli::query()
			->where('cod2_cli', $apiUser['idorigincli'])
			->first();
	}

	public function sendInvitationEmail(OwnerB2BData $owner, $auction, UserB2BData $user, $delayToDispatch)
	{
		$notification = new AuctionInvitationMail($owner, $auction->toArray(), $user);

		$emailLib = new EmailLib('AUTION_INVITE');
		if (!empty($emailLib->email)) {
			$emailLib->setHtmlBody($notification->render());
			$emailLib->setTo($user->email);

			MailJob::dispatch($emailLib)
				->onQueue(Config::get('app.queue_env'))
				->delay(now()->addSeconds($delayToDispatch));

			FgSubInvites::query()
				->where('owner_codcli_subinvites', $owner->id)
				->where('invited_codcli_subinvites', $user->id)
				->update(['notification_sent_subinvites' => 1]);
		}
	}
}
