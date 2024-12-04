<?php

namespace App\Http\Services\b2b;

use App\Http\Controllers\apilabel\ClientController;
use App\Imports\b2b\UsersB2BImport;
use App\Imports\ExcelImport;
use App\Models\V5\FgSub;
use App\Models\V5\FxCli;
use App\Models\V5\FxSubInvites;
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

	/**
	 * Add user from request
	 * @throws Exception
	 */
	public function createInvitation($ownerCod, UserB2BData $userData)
	{
		$fxCli = $this->getOrCreateCli($userData);

		$auctionCode = FgSub::query()
			->where('agrsub_sub', $ownerCod)
			->first();

		$hasInvite = FxSubInvites::query()
			->where('owner_codcli_subinvites', $ownerCod)
			->where('invited_codcli_subinvites', $fxCli->cod_cli)
			->where('codsub_subinvites', $auctionCode->cod_sub)
			->first();

		if ($hasInvite) {
			throw new Exception('El cliente ya ha sido invitado a la subasta');
		}

		FxSubInvites::create([
			'emp_subinvites' => Config::get('app.emp'),
			'owner_codcli_subinvites' => $ownerCod,
			'invited_codcli_subinvites' => $fxCli->cod_cli,
			'codsub_subinvites' => $auctionCode->cod_sub
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

		if(!$fxCli) {
			$fxCli = $this->createCliWithApi($userData);
		}

		return $fxCli;
	}

	private function createCliWithApi(UserB2BData $userData)
	{
		$apiUser = array_merge($userData->toArray(), [
			'idorigincli' => FxCli::newCod2Cli(),
			'source' => FxCli::TIPO_CLI_WEB,
			'createdate' => date("Y-m-d h:i:s"),
			'updatedate' => date("Y-m-d h:i:s"),
		]);

		$apiRequest = [$apiUser];
		$clientController = new ClientController();
		$json = $clientController->createClient($apiRequest);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			//excepiton
			Log::error('Error creating user with API', ['error' => $result]);
			throw new Exception('Error creating user with API');
		}

		return FxCli::query()
			->where('cod2_cli', $apiUser['idorigincli'])
			->first();

	}

}
