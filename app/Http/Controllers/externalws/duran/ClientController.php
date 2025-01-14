<?php

namespace App\Http\Controllers\externalws\duran;

use App\Models\V5\FxCli;
use App\Models\V5\FxCliWeb;
use App\Models\V5\FxCli2;
use App\Models\V5\FxClid;
use App\libs\EmailLib;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ClientController extends DuranController
{


	public function createClient($codCli)
	{

		$fields = $this->CreateFields($codCli, "A");

		$xml = $this->createXML($fields);
		$res = $this->callWebService($xml, "wbclientes");

		$stringXML = str_replace('<?xml version="1.0"?>', "", $xml->asXML());

		if (!empty($res)) {
			if ($res->resultado == 0 || $res->resultado == 5) {

				#comprobamos que no exista el código que nos han pasado, usamos cliweb por que si usamso cli y existe un usuario solo ERP no permite crear usuario web
				$client = FxCliWeb::where("cod2_cliweb", intval($res->codigo))->first();
				#el usuario ya debería tenerla en 'A' ya que es registro  regtype = 4
				$bajaTemporal = 'A';
				if (empty($client)) {
					# si el usuario existia ya en Duran
					if (!empty($res->existente)  || $res->resultado == 5) {

						$email = new EmailLib('USER_DURAN_EXIST');
						if (!empty($email->email)) {
							//EMAIL DOBLE OPT-in
							$email->setUserByCod($codCli, true);
							$email_user = $fields['email'];
							$code = ToolsServiceProvider::encodeStr($email_user . '-' . $codCli);

							$url =  Config::get('app.url') . '/es/email-validation?code=' . $code . '&email=' . $email_user . '&type=new_user';
							$email->setUrl($url);
							$email->send_email();
							$bajaTemporal = 'W';
						}
					} else {

						$email = new EmailLib('USER_DURAN_NO_EXIST');
						if (!empty($email->email)) {

							$email->setUserByCod($codCli, true);
							$email->send_email();
						}
					}
					FxCli::where("cod_cli", $codCli)->update(["cod2_cli" => intval($res->codigo), "baja_tmp_cli" => $bajaTemporal]);
					FxCliWeb::where("cod_cliweb", $codCli)->update(["cod2_cliweb" => intval($res->codigo)]);
					FxCli2::where("cod_cli2", $codCli)->update(["cod2_cli2" => intval($res->codigo)]);
					FxCliD::where("cli_clid", $codCli)->update(["cli2_clid" => intval($res->codigo)]);
				} else {
					$this->sendEmailError("wbclientes", "No se ha podido asignar el código de usuario externo ya que ya está en uso cod_cli: $codCli, cod2_cli: " . $res->codigo,  print_r($res, true), true);
					$bajaTemporal = 'E';
					$email = new EmailLib('USER_DURAN_DUPLICATED');
					if (!empty($email->email)) {

						$email->setUserByCod($codCli, true);
						$email->send_email();
					}
					FxCli::where("cod_cli", $codCli)->update(["baja_tmp_cli" => $bajaTemporal]);
				}
			} else {

				$this->sendEmailError("wbclientes", htmlspecialchars($stringXML), print_r($res, true), true);
			}
		}
	}

	public function updateClient($codCli)
	{
		$fields = $this->CreateFields($codCli, "M");
		$xml = $this->createXML($fields);
		$res = $this->callWebService($xml, "wbclientes");
		if (!empty($res)) {
			if ($res->resultado != 0) {
				Log::info("error Web service Duran" . print_r($res, true));
			}
		}
	}

	public function deleteClient($codCli)
	{

		$fields = $this->CreateFields($codCli, "B");
		$xml = $this->createXML($fields);
		$this->callWebService($xml, "wbclientes");
	}

	#creo esta función para que sea más visible la asociación de campos
	private function CreateFields($codCli, $tipoActualizacion)
	{
		$cliente = FxCli::SELECT("nllist1_cliweb,nllist2_cliweb, cod_cli, cod2_cli,rsoc_cli, nom_cli, fisjur_cli,tipv_cli, cif_cli, codpais_cli, dir_cli, dir2_cli, pob_cli, pro_cli, cp_cli, tel1_cli, tel2_cli, email_cli, origen_cli, CODPAIS_CLID, DIR_CLID, DIR2_CLID, CP_CLID,POB_CLID, PRO_CLID")
			->where("cod_cli", $codCli)
			->join('FXCLIWEB', 'FXCLIWEB.COD_CLIWEB = FXCLI.COD_CLI  AND FXCLIWEB.GEMP_CLIWEB = FXCLI.GEMP_CLI')
			->leftjoin('FXCLID', "FXCLID.GEMP_CLID = FXCLI.GEMP_CLI AND FXCLID.CLI_CLID = FXCLI.COD_CLI AND FXCLID.CODD_CLID ='W1' ")

			->first();

		#si no es empresa se les envia vacio
		$razonSocial =  ($cliente->fisjur_cli == "J") ? $cliente->rsoc_cli : "";



		$fields = array(
			'origen' => $cliente->origen_cli ?? 2,
			'tipoactualizacion' => $tipoActualizacion,
			#si es una creación no debemos coger el cod2 ya que se cogerá el qeu se crea por defecto y no es el de Duran
			'codigo' =>  $tipoActualizacion == "A" ? null : $cliente->cod2_cli,
			'codigoweb' => $cliente->cod_cli,
			'apellidos' => "",
			'nombre' =>  str_replace(",", "", $cliente->nom_cli),
			'razonsocial' => mb_strtolower($razonSocial), # si es empresa, persona juridica, indicamos el valor
			'tipodoc' => 1,
			'nif' => mb_substr($cliente->cif_cli, 0, 20),
			'pais' => $cliente->codpais_cli,
			'direccion' => mb_substr($cliente->dir_cli . $cliente->dir2_cli, 0, 100),
			'poblacion' => mb_substr($cliente->pob_cli, 0, 50),
			'provincia' => mb_substr($cliente->pro_cli, 0, 50),
			'cp' =>  mb_substr($cliente->cp_cli, 0, 6),
			'telefono' =>  mb_substr($cliente->tel1_cli, 0, 20),
			'movil' =>  mb_substr($cliente->tel2_cli, 0, 20),
			'fax' => "",
			'email' => mb_substr($cliente->email_cli, 0, 150),
			'nifautorizado' => $cliente->cif_cli,
			'encalidadde' => $cliente->tipv_cli,
			'empresa' => ($cliente->fisjur_cli == "J") ? 1 : 0,
			'publicidad' => $cliente->nllist1_cliweb == 'S' ? 1 : 0,
			'cesion' =>  $cliente->nllist2_cliweb == 'S' ? 1 : 0,

			'paisenvio' => $cliente->codpais_clid,
			'direccionenvio' =>  mb_substr($cliente->dir_clid . $cliente->dir2_clid, 0, 100),
			'poblacionenvio' => $cliente->pob_clid,
			'provinciaenvio' => $cliente->pro_clid,
			'cpenvio' => $cliente->cp_clid,
		);

		return $fields;
	}

	public function addSuscription($email)
	{
		$fields = [
			'origen' => 37,
			'email' => $email,
			'publicidad' => 1,
		];

		$xml = $this->createXML($fields);
		$res = $this->callWebService($xml, "wbcontactos");

		if (!empty($res)) {
			if ($res->resultado != 0) {
				Log::info("error Web service Duran" . print_r($res, true));
			}
		}
	}
}
