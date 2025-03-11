<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Console\Commands\Scripts\ProcessLogsBids;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class AdminMesuresController extends Controller
{
	public function index(Request $request)
	{
		$logs = glob(storage_path('logs/analytics-*.log'));

		$logsNames = array_map(function ($file) {
			$baseName = basename($file);
			return str_replace('analytics-', '', str_replace('.log', '', $baseName));
		}, $logs);

		//sort logs names descendent
		rsort($logsNames);

		$logSelected = $request->input('day', head($logsNames));

		$actions = [];
		try {
			$actions = $this->getDataFile($logSelected);
		} catch (\Throwable $th) {
			Log::error($th);
		}

		$toFile = fn($file) => route('admin.mesures.index', ['day' => $file]);

		return View::make('admin::pages.configuracion.mesures.actions', ['logs' => $logsNames, 'actions' => $actions, 'logSelected' => $logSelected, 'toFile' => $toFile]);
	}


	/**
	 * Esta función es para leer archivos logs de laravel
	 * Es posible que con archivos muy grandes se tarde mucho
	 * o incluso no se pueda.
	 *
	 * En ese caso, al processFile se le puede pasar un segundo argumento para
	 * crear un archivo json.
	 */
	public function analizeLogFile(Request $request)
	{
		$logsFiles = glob(storage_path('logs/laravel-*.log'));

		$logsNames = array_map(function ($file) {
			$baseName = basename($file);
			return str_replace('.log', '', $baseName);
		}, $logsFiles);

		rsort($logsNames);

		$logSelected = $request->input('file', head($logsNames));

		$process = new ProcessLogsBids();
		$result = $process->proccessFile("$logSelected.log");

		$actions = $result['result'];

		//add uuid and action type to all actions
		foreach ($actions as $key => $action) {
			$actions[$key]['uuid'] = '';
			$actions[$key]['action_type'] = 'BID';
			$actions[$key]['time'] = (float) $action['tiempo'];
		}

		$toFile = fn($file) => route('admin.mesures.index-json', ['file' => $file]);

		return view('admin::pages.configuracion.mesures.actions', ['logs' => $logsNames, 'actions' => $actions, 'logSelected' => $logSelected, 'toFile' => $toFile]);
	}

	private function getDataFile($fileName)
	{
		$pathFile = storage_path("logs/analytics-{$fileName}.log");

		if (!file_exists($pathFile)) {
			return null;
		}

		$lines = file($pathFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		$actions = [];
		$bidCounter = 1;
		$closeCounter = 1;
		foreach ($lines as $line) {
			if (strpos($line, 'api.action.subasta') !== false) {
				// Extraemos el JSON usando regex
				preg_match('/api.action.subasta\s+(.*)$/', $line, $matches);

				if (isset($matches[1])) {
					$json = $matches[1];
					$data = json_decode($json, true);
					if (json_last_error() === JSON_ERROR_NONE) {
						$actions[] = $this->actionDto($bidCounter, 'BID', $data);
						$bidCounter++;
					}
				}
			}
			if (strpos($line, 'api.action.end_lot') !== false) {
				// Extraemos el JSON usando regex
				preg_match('/api.action.end_lot\s+(.*)$/', $line, $matches);

				if (isset($matches[1])) {
					$json = $matches[1];
					$data = json_decode($json, true);
					if (json_last_error() === JSON_ERROR_NONE) {
						$actions[] = $this->actionDto($closeCounter, 'END_LOT', $data);
						$closeCounter++;
					}
				}
			}
		}

		return $actions;
	}

	private function actionDto($id, $type, $data)
	{
		$params = data_get($data, '0.params', []);

		return [
			'action_type' => $type,
			'id' => $id,
			'time' => $data['time'],
			'uuid' => $data['uuid'],
			'codSub' => data_get($params, 'cod_sub', ''),
			'ref' => data_get($params, 'ref', data_get($params, 'lot', '')),
			'imp' => data_get($params, 'imp', ''),
			'licitador' => data_get($params, 'cod_licit', ''),
		];
	}
}
