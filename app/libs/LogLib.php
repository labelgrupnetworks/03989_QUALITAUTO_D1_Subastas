<?php

namespace App\libs;

use Config;
use DB;
class LogLib
{

#devuelve todos los cambios que ha habido, mostrando los datos originales y los cambios en cada momento.
	static function getLogChanges($model, $table, $tableSuffix,$fieldsPK, $smallLetter = false ){
		$tablePath= 'App\Models\V5\\'.$model;
		$tableModel = new $tablePath();
		foreach($fieldsPK as $fieldName => $fieldValue){
			$tableModel= $tableModel->where($fieldName,$fieldValue);
		}

		$actual = $tableModel->select( $table.".*")->first();



		$tableLogPath = 'App\Models\V5\log\\'.$model.'_Log';
		$tableLogModel = new $tableLogPath();
		foreach($fieldsPK as $fieldName => $fieldValue){

			$tableLogModel= $tableLogModel->where($fieldName,$fieldValue);
		}

		$dateField = 'date_update_'. $tableSuffix;

		#si la base de datos está en minusculas debemos ponerle la comilla
		if($smallLetter){
			$dateField ='"'. $dateField .'"';
		}

		$logs = $tableLogModel->orderby($dateField)->get();

		if(!empty($actual)){
			#hacemos un merge para que el actual quede el último
			$allStates = array_merge($logs->toArray(), [$actual->toArray()]);
		}else{
			$allStates = $logs->toArray();
		}


		$cambios = array();

		foreach($allStates as $key => $item){
			#el primero
			if(count($cambios)== 0){

				$cambios[] =(array) $item;
			}else{

				$cambio = self::compare_rows($allStates[$key-1], $item, $tableSuffix);

				if(!empty($cambio)){
					$cambios[] = $cambio;
				}

			}

		}

		return json_encode($cambios);

	}

	#compara dos estados para poder devolver sólo los cambios
	static function compare_rows($anterior, $actual, $tableSuffix){

		#SI ES DELETE SOLO MOSTRAMOS DELETE, LA FECHA Y EL USUARIO, NO COMPARAMOS YA QUE ETSAN TODOS LOS CAMPOS ANULL Y LOS MARCARA COMO CAMBIOS
		//if( $actual["type_update"] != 'DELETE'){
			$diferencias = array();
			foreach($anterior as $prop => $value){
				#no se comprueba el id_update ya que en la tabla original no existe
				if($prop !="id_update_".$tableSuffix && $value != $actual[$prop] ){
					$diferencias[$prop] = $actual[$prop];
				}
			}

			# si solo cambia la fecha de modificación es que no hay cambios por lo que no lo mostramos
			if( (count($diferencias) == 1 && !empty($diferencias["date_update_".$tableSuffix]) ) || (count($diferencias) == 2 && !empty($diferencias["date_update_".$tableSuffix]) && !empty($diferencias["type_update"]) )  ){
				return [];
			}
		//}

		#unificamos nombres para todas las tablas
		$diferencias["type_update"] = $actual["type_update_".$tableSuffix];
		$diferencias["usr_update"]= $actual["usr_update_".$tableSuffix];
		unset($diferencias["usr_update_".$tableSuffix]);
		$diferencias["date_update"]= $actual["date_update_".$tableSuffix];
		unset($diferencias["date_update_".$tableSuffix]);


		return $diferencias;
	}

	#devuelve todos los logs de una tabla y el estado actual, siempre que se enceuntren dentro de los rangos de fechas especificados
	static function getLog($model, $tableSuffix, $startDate = null , $endDate = null,$smallLetter = false){

		$tablePath= 'App\Models\V5\\'.$model;
		$tableModel = new $tablePath();

		$tableLogPath = 'App\Models\V5\log\\'.$model.'_Log';
		$tableLogModel = new $tableLogPath();


		$dateField = 'date_update_'. $tableSuffix;
		#si la base de datos está en minusculas debemos ponerle la comilla
		if($smallLetter){
			$dateField ='"'. $dateField .'"';
		}

		if(!empty($startDate)){
			$tableModel = $tableModel->where($dateField,">=",$startDate);
			$tableLogModel = $tableLogModel->where($dateField,">=",$startDate);
		}

		if(!empty($endDate)){

			$tableModel = $tableModel->where($dateField,"<=",$endDate);
			$tableLogModel = $tableLogModel->where($dateField,"<=",$endDate);
		}

		$actual = $tableModel->log()->get();

		$logs = $tableLogModel->log()->orderby($dateField)->get();


		if(!empty($actual)){
			#hacemos un merge para que el actual quede el último
			$allStates = array_merge($logs->toArray(), $actual->toArray());
		}else{
			$allStates = $logs->toArray();
		}

		return $allStates;
	}

	#para cuando la tabla esta en minusculas
	static function getLog_smallLetter($table ,  $tableSuffix, $startDate = null , $endDate = null){

		$tablePath= 'App\Models\V5\\'.$table;
		$tableModel = new $tablePath();

		$tableLogPath = 'App\Models\V5\log\\'.$table.'_Log';
		$tableLogModel = new $tableLogPath();

		if(!empty($startDate)){
			$tableModel = $tableModel->where('"date_update_'. $tableSuffix.'"',">=",$startDate);
			$tableLogModel = $tableLogModel->where('"date_update_'. $tableSuffix.'"',">=",$startDate);
		}

		if(!empty($endDate)){
			$tableModel = $tableModel->where('"date_update_'. $tableSuffix.'"',"<=",$endDate);
			$tableLogModel = $tableLogModel->where('"date_update_'. $tableSuffix.'"',"<=",$endDate);
		}

		$actual = $tableModel->first();
		$logs = $tableLogModel->orderby('"date_update_'. $tableSuffix.'"')->get();

		if(!empty($actual)){
			#hacemos un merge para que el actual quede el último
			$allStates = array_merge($logs->toArray(), [$actual->toArray()]);
		}else{
			$allStates = $logs->toArray();
		}

		return $allStates;
	}

}

?>
