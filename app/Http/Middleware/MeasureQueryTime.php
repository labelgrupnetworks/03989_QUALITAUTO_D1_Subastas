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
		$queryStart = microtime(true);

		$response = $next($request);

		$queryTime = microtime(true) - $queryStart;
		$proccessTime = round($queryTime * 1000, 2) . ' ms';

		$messages[] = "Tiempo de ejecución de la petición: {$proccessTime}";

        // Obtener todas las consultas realizadas durante esta petición
        $queries = DB::getQueryLog();

        foreach ($queries as $query) {
			$table = explode(" ", $query['query']);
			$table = $table[array_search("from", array_map('strtolower', $table)) + 1];
			$table = trim($table, " \t\n\r`");

			$messages[] = "Consulta ejecutada: en {$table} en {$query['time']} ms";
        }

        DB::disableQueryLog();

		Log::driver('analytics')->info(['messages' => $messages]);

        return $response;
    }
}
