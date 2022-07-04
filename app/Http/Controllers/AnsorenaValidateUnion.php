<?php

namespace App\Http\Controllers;

use Redirect;


use DB;
use Request;
class AnsorenaValidateUnion extends Controller
{


	public function validateUnion(){
		$type = request("type","NIF");

		if($type == "NIF"){
			$sql="
				SELECT min(ID_PADRE_NIF)ID_PADRE_NIF , count(distinct(ID_PADRE_NIF)) pendientes FROM (
				SELECT ID_PADRE_NIF FROM VOLCADO_CLIENTES
				JOIN FXCLI ON GEMP_CLI='01' AND COD_CLI = NUMERO AND BAJA_TMP_CLI = 'N'
				WHERE   ID_PADRE_NIF is not null  and VERIFICADO = 0 GROUP BY ID_PADRE_NIF HAVING COUNT(ID_PADRE_NIF) > 1
				) t
				";
			$idPadre = \DB::select($sql, []);

			if($idPadre[0]->pendientes > 0){
				$sql="				SELECT ID_NUM,ID_PADRE_NIF, NUMERO,NOMBRE,DIRECCION, POBLACION, TELEFONO,NIF, EMAIL FROM VOLCADO_CLIENTES WHERE ID_PADRE_NIF =".$idPadre[0]->id_padre_nif;
				$usuarios = \DB::select($sql, []);
				$idPadreOld = $idPadre[0]->id_padre_nif;
				return \View::make('front::pages.validateUnion', array('usuarios' => $usuarios, 'idPadreOld' =>$idPadreOld , 'type' => $type, 'pendientes' =>  $idPadre[0]->pendientes ));
			}
		}

		if($type == "TELEFONO"){
			$sql="

			SELECT min(ID_PADRE_TELEFONO)ID_PADRE_TELEFONO , count(distinct(ID_PADRE_TELEFONO)) pendientes FROM (
				SELECT ID_PADRE_TELEFONO FROM VOLCADO_CLIENTES
				JOIN FXCLI ON GEMP_CLI='01' AND COD_CLI = NUMERO AND BAJA_TMP_CLI = 'N'
				WHERE   ID_PADRE_TELEFONO is not null  and VERIFICADO = 0 GROUP BY ID_PADRE_TELEFONO HAVING COUNT(ID_PADRE_TELEFONO) > 1
				) t
			";
			$idPadre = \DB::select($sql, []);

			if($idPadre[0]->pendientes > 0){
				$sql="SELECT ID_NUM,ID_PADRE_TELEFONO, NUMERO,NOMBRE,DIRECCION, POBLACION, TELEFONO,NIF, EMAIL FROM VOLCADO_CLIENTES WHERE ID_PADRE_TELEFONO =".$idPadre[0]->id_padre_telefono;
				$usuarios = \DB::select($sql, []);
				$idPadreOld = $idPadre[0]->id_padre_telefono;
				return \View::make('front::pages.validateUnion', array('usuarios' => $usuarios, 'idPadreOld' => $idPadreOld, 'type' => $type, 'pendientes' =>  $idPadre[0]->pendientes ));
			}
		}

		if($type == "EMAIL"){
			$sql="
			SELECT min(ID_PADRE_EMAIL) ID_PADRE_EMAIL , count(distinct(ID_PADRE_EMAIL)) pendientes FROM (
				SELECT ID_PADRE_EMAIL FROM VOLCADO_CLIENTES
				JOIN FXCLI ON GEMP_CLI='01' AND COD_CLI = NUMERO AND BAJA_TMP_CLI = 'N'
				WHERE   ID_PADRE_EMAIL is not null  and VERIFICADO = 0 GROUP BY ID_PADRE_EMAIL HAVING COUNT(ID_PADRE_EMAIL) > 1
				) t
			";
			$idPadre = \DB::select($sql, []);

			if($idPadre[0]->pendientes > 0){
				$sql="SELECT ID_NUM,ID_PADRE_EMAIL, NUMERO,NOMBRE,DIRECCION, POBLACION, TELEFONO,NIF, EMAIL FROM VOLCADO_CLIENTES WHERE ID_PADRE_EMAIL =".$idPadre[0]->id_padre_email;
				$usuarios = \DB::select($sql, []);
				$idPadreOld = $idPadre[0]->id_padre_email;
				return \View::make('front::pages.validateUnion', array('usuarios' => $usuarios, 'idPadreOld' => $idPadreOld, 'type' => $type, 'pendientes' =>  $idPadre[0]->pendientes ));
			}
		}

		if($type == "NOMBRE"){
			$sql="
			SELECT min(ID_PADRE_NOMBRE) ID_PADRE_NOMBRE , count(distinct(ID_PADRE_NOMBRE)) pendientes FROM (
				SELECT ID_PADRE_NOMBRE FROM VOLCADO_CLIENTES
				JOIN FXCLI ON GEMP_CLI='01' AND COD_CLI = NUMERO AND BAJA_TMP_CLI = 'N'
				WHERE   ID_PADRE_NOMBRE is not null  and VERIFICADO = 0 GROUP BY ID_PADRE_NOMBRE HAVING COUNT(ID_PADRE_NOMBRE) > 1
				) t
			";
			$idPadre = \DB::select($sql, []);

			if($idPadre[0]->pendientes > 0){
				$sql="SELECT ID_NUM,ID_PADRE_NOMBRE, NUMERO,NOMBRE,DIRECCION, POBLACION, TELEFONO,NIF, EMAIL FROM VOLCADO_CLIENTES WHERE ID_PADRE_NOMBRE =".$idPadre[0]->id_padre_nombre;
				$usuarios = \DB::select($sql, []);
				$idPadreOld = $idPadre[0]->id_padre_nombre;
				return \View::make('front::pages.validateUnion', array('usuarios' => $usuarios, 'idPadreOld' => $idPadreOld, 'type' => $type, 'pendientes' =>  $idPadre[0]->pendientes ));
			}
		}


	}

	public function decisionUnion(){
		//print_r(request()->all());


		$clients = request("clients");
		$principal = request("principal");
		$idPadreOld = request("idPadreOld");
		$unir =  request("unir");
		$type =  request("type");

		if($unir == "SI" && !empty($clients) && count($clients) >1){
			$sql="update volcado_clientes set id_padre_final = $principal where id_num in (" . implode(",", $clients)  . ")";
			\DB::select($sql, []);
		}

		$sql="update volcado_clientes set VERIFICADO = 1 where id_padre_". $type." = $idPadreOld";
		\DB::select($sql, []);


		header('Location: '.Route("AnsorenaValidateUnion")."?type=$type" );
		exit;

	}

	public function resultUnion(){
		$sql="select ID_NUM,NUMERO,NOMBRE,DIRECCION, POBLACION, TELEFONO,NIF, EMAIL,ID_PADRE_FINAL from VOLCADO_CLIENTES WHERE ID_PADRE_FINAL IS NOT NULL ORDER BY ID_PADRE_FINAL";
		$unidos = \DB::select($sql, []);

		return \View::make('front::pages.resultUnion', array('unidos' => $unidos ));

	}


}
