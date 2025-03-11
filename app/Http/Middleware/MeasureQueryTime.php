<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MeasureQueryTime
{
    public function handle($request, Closure $next)
    {

		if(!Config::get('app.measure_query_time', false)) {
			return $next($request);
		}

        // Habilitar el registro de consultas
        DB::enableQueryLog();

		//  Medir el tiempo de del proceso
		$processTimeStart = microtime(true);

		$response = $next($request);

		$processTimeEnd = microtime(true) - $processTimeStart;
		$processTotalTime = round($processTimeEnd * 1000, 2);

		//generar identificador de la petición
		$uuid = uniqid();

		$this->addProcessTimeFromRequest($uuid, $request, $processTotalTime);

		$queries = DB::getQueryLog();
		$this->addQueryTimes($uuid, $queries);

        DB::disableQueryLog();

        return $response;
    }

	private function addProcessTimeFromRequest($uuid, $request, $time)
	{
		$params = $request->all();
		$action = $request->route()->getAction()['as'] ?? 'Sin acción';
		if(count($params) > 0) {
			Log::driver('analytics')->info($action, ['time' => $time, 'uuid' => $uuid, $params]);
		}
	}

	private function addQueryTimes($uuid, $queries)
	{
		$messages = [];
		foreach ($queries as $query) {
			$table = explode(" ", $query['query']);
			$table = $table[array_search("from", array_map('strtolower', $table)) + 1];
			$table = trim($table, " \t\n\r`");
			$table = str_replace("\n", "", $table);
			$table = str_replace("\t", "", $table);

			$messages[] = "Consulta ejecutada: en {$table} en {$query['time']} ms";
		}

		Log::driver('analytics')->info("Consultas de la peticion: $uuid", ['messages' => $messages]);
	}
}
