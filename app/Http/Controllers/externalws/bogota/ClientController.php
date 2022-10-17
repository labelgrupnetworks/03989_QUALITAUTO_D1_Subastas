<?php

namespace App\Http\Controllers\externalws\bogota;

use App\Http\Controllers\externalws\bogota\BogotaController;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClientController extends BogotaController
{
	public function createClient($codCli)
	{
		$function = "createContact";
		$user = FxCli::leftJoinCliWebCli()
			->select('cod_cli', 'email_cli, nom_cli', 'tel1_cli', 'fecnac_cli', 'pob_cli', 'dir_cli', 'dir2_cli', 'cp_cli', 'pais_cli', 'cif_cli', 'nllist1_cliweb')
			->where('cod_cli', $codCli)
			->first();

		$users = [$this->constructUserDataToZoho($user)];
		$response = null;

		try {
			$response = $this->callWebService($users, $function);

		} catch (Throwable $th) {
			Log::info($th->getMessage());
			$this->sendEmailError($function, "", $th->getMessage());
			return false;
		}

		if(!empty($response->data) && $response->data[0]->status == "success"){
			return true;
		}

		$this->ErrorLog($function, $users, $response ?? '');
		$this->sendEmailError($function, json_encode($users), json_encode($response));
		return false;
	}

	public function updateClient($codCli)
	{
		return $this->createClient($codCli);
	}

	public function exportListClients(array $codCliArray)
	{
		foreach ($codCliArray as $codCli) {
			$this->createClient($codCli);
		}
	}

	public function exportAllClients()
	{

		$users = FxCli::leftJoinCliWebCli()
			->select('cod_cli', 'email_cli, nom_cli', 'tel1_cli', 'fecnac_cli', 'pob_cli', 'dir_cli', 'dir2_cli', 'cp_cli', 'pais_cli', 'cif_cli', 'nllist1_cliweb')
			->orderBy('email_cli')
			->get();

		$chunkBlocks = $users->chunk(99);

		$chunkBlocks->each(function($users, $key) {

			$function = "createContact";

			$userFormat = $users->map(function($user) {
				return $this->constructUserDataToZoho($user);
			})->values()->all();

			try {
				$response = $this->callWebService($userFormat, $function);

				if(empty($response->data)){
					Log::error("error in $key block", ['users' => $userFormat, 'response' => $response]);
				}

				if(is_array($response->data)){
					foreach($response->data as $userIndex => $data) {
						if($data->status != 'success') {
							Log::error("error in user with block $key", ['user' => $userFormat[$userIndex], 'error' => $data, 'userindex' => $userIndex]);
						}
					}
				}
				echo("send $key block");
			} catch (Throwable $th) {
				echo("error $key block");
				Log::error($th->getMessage(), ['usersSend' => $userFormat]);
			}
		});

		echo "Finish";
	}

	private function constructUserDataToZoho(FxCli $user)
	{
		$fieldsToConvertCase = ['nom_cli', 'pob_cli', 'direction', 'pais_cli'];
		foreach ($fieldsToConvertCase as $field) {
			$user->{$field} = mb_convert_case($user->{$field}, MB_CASE_TITLE, "UTF-8");
		}

		$dateOfBirth = ToolsServiceProvider::getDateFormat($user->fecnac_cli ?? now(), 'Y-m-d H:i:s', 'Y-m-d');
		$clientCode = intval($user->cod_cli);

		$isSubscribeInNewsletter = (!empty($user->nllist1_cliweb) && $user->nllist1_cliweb == 'S');

		return [
			'First_Name' => $user->nom_cli,
			'Last_Name' => '.',
			'Email' => mb_strtolower($user->email_cli),
			'Phone' => $user->tel1_cli,
			'Date_of_Birth' => $dateOfBirth,
			'Mailing_City' => $user->pob_cli,
			'Mailing_Street' => $user->direction,
			'Mailing_Zip' => $user->cp_cli,
			'Mailing_Country' => $user->pais_cli,
			'Lead_Source' => 'Inscripción en Label',
			'C_digo_de_cliente_Label' => "$clientCode",
			'External_ID' => $user->cod_cli,
			'C_dula_o_ID' => $user->cif_cli,
			'Email_Opt_Out' => $isSubscribeInNewsletter
		];
	}

}
