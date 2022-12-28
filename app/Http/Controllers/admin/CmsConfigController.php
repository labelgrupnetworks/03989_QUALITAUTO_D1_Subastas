<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\apilabel\ClientController;
use App\Imports\ExcelImport;
use App\Models\V5\FxCli;
use Controller;
use Illuminate\Support\Facades\Request as Input;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\V5\FsParams;



class CmsConfigController extends Controller
{
    public function index()
    {

        return \View::make('admin::pages.cms');
    }


	/**
	 * path: GET /admin/genericImport
	 */
	public function getImportFile()
	{
		return view('admin::pages.importExcels');
	}

	/****
	 * path: POST /admin/genericImport
	 * Función para importar cualquier archivo excel,
	 * Se puede modificar como se quiera y si la función es útil dejarla en el controlador
	 */
	public function ImportFile()
	{
		$file = Input::file('file');
		$rows = Excel::toArray(new ExcelImport, $file)[0];

		$this->importStnClients($rows);

		dd('fin');
		return response('success', 200);
	}

	private function importStnClients($rows)
	{
		//El excel que cargo tiene este formato por fila
		/*********
		 * 1 => array:6 [▼
			0 => "#REF!"
			1 => "7c27bbb08c2df8e96e41c6cdde176f71"
			2 => "S4300001263"
			3 => "7c27bbb08c2df8e96e41c6cdde176f71"
			4 => "GRUPO FARDOUN TRADING FAIRCO S.L"
			5 => "ali@grupofardoun.com"
		]
		 */

		$emails = FxCli::select('email_cli')->pluck('email_cli')->toArray();
		$existUsers = [];

		for ($i = 1; $i < count($rows); $i++) {
			if(in_array($rows[$i][5], $emails)){
				$existUsers[] = [$rows[$i][2], $rows[$i][3], $rows[$i][4], $rows[$i][5]];
				continue;
			}

			$createUsers = [
				'idorigincli' => $this->newCod2Cli(null),
				'registeredname' => $rows[$i][2],
				'name' => $rows[$i][4],
				'email' => $rows[$i][5],
				'createdate' => date("Y-m-d h:i:s"),
				'updatedate' => date("Y-m-d h:i:s"),
			];

			$clientes = [];
			$clientes[] = $createUsers;

			$clientController = new ClientController();
			$json = $clientController->createClient($clientes);
			$result = json_decode($json);

			dump($result);
		}

		//si quiero descargar excel con los usuarios repetidos
		//return ToolsServiceProvider::exportCollectionToExcel(collect($existUsers), 'usuarios_repetidos', false);
	}

	private function newCod2Cli($cod_cli)
	{
		if(!$cod_cli){
			$cod_cli = FxCli::getNextCodCli();
		}
		$tcli_params = FsParams::select("tcli_params")->first();

		if(!empty($tcli_params) && !empty($tcli_params->tcli_params)){
			$numdigits = $tcli_params->tcli_params;
		}else{
			$numdigits = 6;
		}

		$formatCodCli = sprintf("%'.0".$numdigits ."d", $cod_cli);

		return str_replace("0", "W", $formatCodCli);
	}

}
