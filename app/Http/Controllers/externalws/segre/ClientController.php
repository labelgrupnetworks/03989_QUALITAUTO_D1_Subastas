<?php
namespace App\Http\Controllers\externalws\segre;


use App\Http\Controllers\Controller;
use App\Models\V5\FxCli;
use App\Models\V5\FxCliWeb;
use App\Models\V5\FxCli2;
use App\Models\V5\FxClid;
use App\libs\EmailLib;
class ClientController extends SegreController
{


	public function createClient($codCli){

		$fields = $this->CreateFields($codCli, "A");

		$res = $this->callWebService(json_encode($fields),"ClientNew");

		\Log::info(print_r($res,true));
		if(!empty($res)){
			#ClientStatus==0 => alta pendiente
			if ($res->ClientStatus == 0 && $res->IdError == 0){
				#cliente nuevo, esperando validación
				\Log::info("Cliente $codCli nuevo, esperando validación");
			}
			#cliente existente dado de alta
			elseif ($res->ClientStatus == 1 ){
				\Log::info("Cliente $codCli ya existenate en segre");

				#comprobamos que no exista el código que nos han pasado, usamos cliweb por que si usamso cli y existe un usuario solo ERP no permite crear usuario web
				$client = FxCliWeb::where("cod2_cliweb", intval ($res->IdOriginClient))->first();
				#el usuario  debería tener bajaTemporal en 'A' ya que es registro  regtype = 4

				if( empty($client) ){
				
					$bajaTemporal='N';
					$email = new EmailLib('NEW_USER');
					if(!empty($email->email)){

						$email->setUserByCod($codCli,true);
						$email->send_email();
					}

					FxCli::where("cod_cli", $codCli)->update(["cod2_cli" => intval ($res->IdOriginClient), "baja_tmp_cli" => $bajaTemporal]);
					FxCliWeb::where("cod_cliweb", $codCli)->update(["cod2_cliweb" => intval ($res->IdOriginClient)]);
					FxCli2::where("cod_cli2", $codCli)->update(["cod2_cli2" => intval ($res->IdOriginClient)]);
					FxCliD::where("cli_clid", $codCli)->update(["cli2_clid" => intval ($res->IdOriginClient)]);

				}else{

					$this->sendEmailError("wbclientes","No se ha podido asignar el código de usuario externo ya que ya está en uso cod_cli: $codCli, cod2_cli: ". $res->IdOriginClient,  print_r($res,true),true );
					$bajaTemporal='E';
					$email = new EmailLib('USER_SEGRE_DUPLICATED');
					if(!empty($email->email)){

						$email->setUserByCod($codCli,true);
						$email->send_email();
					}
					FxCli::where("cod_cli", $codCli)->update([ "baja_tmp_cli" => $bajaTemporal]);
				}


			}
			elseif($res->ClientStatus == 3 ){
				#Alta rechazada
				$bajaTemporal='R';
				FxCli::where("cod_cli", $codCli)->update([ "baja_tmp_cli" => $bajaTemporal]);
				#no enviamos email al usuario, puesto que si se ha rechazado es por que debe ser moroso y hablandolo con anselmo mejor no enviarlo
			}
			else{

				$this->sendEmailError("wbclientes",print_r($fields,true ), print_r($res,true),true );
			}
		}


	}

	public function updateClient($codCli){

		#No se enviaran modificaciones de usuarios a traves del web service, si no que se hará por envio de email al administrador.


	}

	public function deleteClient($codCli){

		#no hay eliminación


	}

	#creo esta función para que sea más visible la asociación de campos
	private function CreateFields($codCli, $tipoActualizacion){
		$cliente = FxCli::SELECT("nllist1_cliweb,nllist2_cliweb, cod_cli, cod2_cli,rsoc_cli, nom_cli, fisjur_cli,tipv_cli, cif_cli, codpais_cli, dir_cli, dir2_cli, pob_cli, pro_cli, cp_cli, tel1_cli, tel2_cli, email_cli, CODPAIS_CLID, DIR_CLID, DIR2_CLID, CP_CLID,POB_CLID, PRO_CLID")
		->where("cod_cli",$codCli)
		->join('FXCLIWEB', 'FXCLIWEB.COD_CLIWEB = FXCLI.COD_CLI  AND FXCLIWEB.GEMP_CLIWEB = FXCLI.GEMP_CLI')
		->leftjoin('FXCLID', "FXCLID.GEMP_CLID = FXCLI.GEMP_CLI AND FXCLID.CLI_CLID = FXCLI.COD_CLI AND FXCLID.CODD_CLID ='W1' ")

		->first();

		#si no es empresa se les envia vacio
		$razonSocial =  ($cliente->fisjur_cli == "J")? $cliente->rsoc_cli : "";



		$name ="";
		$surname ="";
		$fiscalName = "";
		#si es empresa
		if($cliente->fisjur_cli == "J"){
			$fiscalName = $cliente->rsoc_cli;
		}else{
			$nom_cli = explode(",",$cliente->nom_cli);
			$name =$nom_cli[1];
			$surname =$nom_cli[0];
		}

		$fields=array(
					'Email' => $cliente->email_cli,
					'Name' =>  $name,
					'SurName' => $surname ,
					'FiscalName' => $fiscalName ,
					'Address' => $cliente->dir_cli .$cliente->dir2_cli,
					'zipCode' =>  $cliente->cp_cli,
					'City' =>$cliente->pob_cli,
					'Province' =>$cliente->pro_cli,
					'country' =>$cliente->codpais_cli,
					'NIF' => $cliente->cif_cli,
					'Phone1' =>  $cliente->tel1_cli,
					'Phone2' =>  $cliente->tel2_cli,
					);

		return $fields;

	}




}
