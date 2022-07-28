<?php

namespace App\Http\Controllers\externalws\bogota;

use App\Http\Controllers\externalws\bogota\BogotaController;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;

class ClientController extends BogotaController
{
	public function createClient($codCli)
	{
		$user = FxCli::select('nom_cli', 'tel1_cli', 'fecnac_cli', 'dir_cli', 'dir2_cli', 'cp_cli', 'pais_cli')
			->joinCliWebCli()
			->where('cod_cli', $codCli)
			->first();

		$userFormat = $this->createFormatForApi($user);

		$response = $this->callWebService($userFormat, 'create_contact');

		return $response;
	}

	private function createFormatForApi($user)
	{
		$nameAndLastName = mb_convert_case($user->nom_cli, MB_CASE_TITLE, "UTF-8");

		return [
			'First_Name' => $nameAndLastName,
			'Last_Name' => null,
			'Email' => mb_strtolower($user->email_cli),
			'Phone' => $user->tel1_cli,
			'Date_of_Birth' => ToolsServiceProvider::getDateFormat($user->fecnac_cli, 'Y-m-d H:i:s', 'Y/m/d'),
			'Mailing_City' => $user->pob_cli,
			'Mailing_Street' => $user->dir_cli . $user->dir2_cli,
			'Mailing_Zip' => $user->cp_cli,
			'Mailing_Country' => $user->pais_cli,
			'Lead_Source' => 'Casos de la Web',
		];
	}

}
