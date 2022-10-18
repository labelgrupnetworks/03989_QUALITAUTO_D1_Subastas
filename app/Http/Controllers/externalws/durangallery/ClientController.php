<?php

namespace App\Http\Controllers\externalws\durangallery;

use App\Http\Controllers\externalws\durangallery\DuranGalleryController;
use App\Models\V5\FxCli;
use Illuminate\Support\Facades\Log;

class ClientController extends DuranGalleryController
{
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

	#creo esta función para que sea más visible la asociación de campos
	private function CreateFields($codCli, $tipoActualizacion)
	{
		$cliente = FxCli::SELECT("nllist1_cliweb,nllist2_cliweb, cod_cli, cod2_cli,rsoc_cli, nom_cli, fisjur_cli,tipv_cli, cif_cli, codpais_cli, dir_cli, dir2_cli, pob_cli, pro_cli, cp_cli, tel1_cli, tel2_cli, email_cli, CODPAIS_CLID, DIR_CLID, DIR2_CLID, CP_CLID,POB_CLID, PRO_CLID")
			->where("cod_cli", $codCli)
			->join('FXCLIWEB', 'FXCLIWEB.COD_CLIWEB = FXCLI.COD_CLI  AND FXCLIWEB.GEMP_CLIWEB = FXCLI.GEMP_CLI')
			->leftjoin('FXCLID', "FXCLID.GEMP_CLID = FXCLI.GEMP_CLI AND FXCLID.CLI_CLID = FXCLI.COD_CLI AND FXCLID.CODD_CLID ='W1' ")

			->first();

		#si no es empresa se les envia vacio
		$razonSocial =  ($cliente->fisjur_cli == "J") ? $cliente->rsoc_cli : "";



		$fields = array(
			'origen' => 2,
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
}
