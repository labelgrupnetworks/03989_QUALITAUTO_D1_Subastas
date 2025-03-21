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

		$queries = [];
		DB::listen(function ($query) use (&$queries) {
			//Guardamos solo las consultas que superen los 50ms
			if($query->time < 50) {
				return;
			}

			$queries[] = [
				'table' => $this->getTableName($query->sql),
				'time'     => $query->time,
			];
		});

		//  Medir el tiempo de del proceso
		$processTimeStart = microtime(true);
		$response = $next($request);
		$processTimeEnd = microtime(true) - $processTimeStart;
		$processTotalTime = round($processTimeEnd * 1000, 2);

		//generar identificador de la petición
		$uuid = uniqid();

		$this->addProcessTimeFromRequest($uuid, $request, $processTotalTime);
		$this->addQueryTimes($uuid, $queries);

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
		Log::driver('analytics')->info("Consultas lentas de la peticion: $uuid", ['querys' => $queries]);
	}

	private function getTableName($sql)
	{
		if (preg_match('/\bfrom\s+(?!\()[`]?(\w+)[`]?/i', $sql, $matches)) {
			return $matches[1];
		}
		return 'desconocida';
	}
}
