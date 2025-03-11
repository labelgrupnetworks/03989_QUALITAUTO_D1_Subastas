<?php

namespace App\Console\Commands\Scripts;

use Illuminate\Console\Command;
use DateTime;


/**
 * Clase para leer los logs de Laravel y extrer de el los datos de las pujas y guardarlos en un archivo JSON
 * Posteriormente se puede leer el archivo JSON y mostrar los datos en una tabla
 */
class ProcessLogsBids extends Command
{
	protected $signature = 'label:process-logs-bids';
	protected $description = 'Lee el archivo de pujas y calcula el tiempo entre inicio y fin';

	public function handle()
	{
		$logsFiles = glob(storage_path('logs/laravel-*.log'));
		//select file in array logsfiles
		$filename = $this->choice('Seleccione el archivo a procesar', $logsFiles);

		$result = $this->proccessFile($filename);

		if ($result['error']) {
			$this->error($result['message']);
			return 1;
		}

		$this->info($result['message']);
		return 0;
	}

	public function proccessFile($filename, $output = false)
	{
		// Ruta del archivo a leer
		$inputPath = storage_path('logs/' . $filename);

		if (!file_exists($inputPath)) {
			return [
				'error'      => true,
				'message'    => "El archivo {$inputPath} no existe.",
			];
		}

		// Leemos todas las líneas del archivo
		$lines = file($inputPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		// Arreglo para almacenar los inicios pendientes
		$pendingStart = [];
		// Arreglo de resultado final
		$result = [];
		// Contador para asignar el id en orden de aparición
		$idCounter = 1;

		foreach ($lines as $line) {
			if (strpos($line, 'Inicio de la puja:') !== false) {
				// Extraemos el JSON usando regex
				preg_match('/Inicio de la puja:\s+(.*)$/', $line, $matches);
				if (isset($matches[1])) {
					$json = $matches[1];
					$data = json_decode($json, true);
					if (json_last_error() === JSON_ERROR_NONE) {
						// La clave para emparejar es la combinación de licitador, referencia e importe.
						$key = $data['licit'] . '_' . $data['ref'] . '_' . $data['imp'];
						// Creamos un objeto DateTime con microsegundos
						$dateTimeObj = DateTime::createFromFormat('Y-m-d H:i:s.u', $data['time']);
						// Guardamos el evento de inicio
						$pendingStart[$key][] = [
							'id'             => $idCounter,
							// Se extrae la hora, minutos y segundos del inicio
							'fecha'          => $dateTimeObj ? $dateTimeObj->format('H:i:s') : null,
							'codSub'         => $data['codSub'],
							'ref'            => $data['ref'],
							'licitador'      => $data['licit'],
							'imp'            => $data['imp'],
							'start_time_obj' => $dateTimeObj,
						];
						$idCounter++;
					}
				}
			} elseif (strpos($line, 'Fin de la puja:') !== false) {
				// Extraemos el JSON de la línea de fin
				preg_match('/Fin de la puja:\s+(.*)$/', $line, $matches);
				if (isset($matches[1])) {
					$json = $matches[1];
					$data = json_decode($json, true);
					if (json_last_error() === JSON_ERROR_NONE) {
						// Generamos la clave para buscar el inicio correspondiente
						$key = $data['licit'] . '_' . $data['ref'] . '_' . $data['imp'];
						if (isset($pendingStart[$key]) && count($pendingStart[$key]) > 0) {
							// Se extrae el primer inicio pendiente para ese par
							$startEvent = array_shift($pendingStart[$key]);
							// Creamos el objeto DateTime para el fin
							$finishTimeObj = DateTime::createFromFormat('Y-m-d H:i:s.u', $data['time']);
							if ($startEvent['start_time_obj'] && $finishTimeObj) {
								// Convertimos ambos DateTime a un float que incluya segundos y microsegundos
								$startFloat = (float) $startEvent['start_time_obj']->format('U.u');
								$finishFloat = (float) $finishTimeObj->format('U.u');
								// Calculamos la diferencia en milisegundos
								$diffMs = ($finishFloat - $startFloat) * 1000;
							} else {
								$diffMs = null;
							}
							// Se agrega la puja procesada al arreglo de resultados
							$result[] = [
								'id'        => $startEvent['id'],
								'fecha'     => $startEvent['fecha'],
								'codSub'    => $startEvent['codSub'],
								'ref'       => $startEvent['ref'],
								'licitador' => $startEvent['licitador'],
								'imp'       => $startEvent['imp'],
								'tiempo'    => $diffMs,
							];
						}
					}
				}
			}
		}

		if(!$output) {
			return [
				'error'      => false,
				'message'    => "Archivo analizado correctamente.",
				'result'     => $result,
			];
		}

		// Generamos el nombre del archivo de salida a partir del de entrada
		$filename = pathinfo($inputPath, PATHINFO_FILENAME); // archivo
		$outputFilename = $filename . '_analisis.json';
		$outputPath = storage_path('logs/' . $outputFilename);

		// Codificamos el resultado en formato JSON con pretty print
		$jsonResult = json_encode($result, JSON_PRETTY_PRINT);

		// Guardamos el archivo en /storage/logs
		if (file_put_contents($outputPath, $jsonResult) !== false) {
			return [
				'error'      => false,
				'message'    => "Archivo analizado guardado en: " . $outputPath,
			];
		} else {
			return [
				'error'      => true,
				'message'    => "No se pudo guardar el archivo analizado en: " . $outputPath,
			];
		}
	}


}
