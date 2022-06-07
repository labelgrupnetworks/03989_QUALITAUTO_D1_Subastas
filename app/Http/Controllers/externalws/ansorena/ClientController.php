<?php
namespace App\Http\Controllers\externalws\ansorena;


use App\Http\Controllers\Controller;
use App\Models\V5\FxCli;
use App\Models\V5\FxCliWeb;
use App\Models\V5\FxCli2;
use App\Models\V5\FxClid;
class ClientController extends AnsorenaController
{

	public function createClient($codCli){
		#no se crean usuarios, solo se modifican
		return;
	}


	public function updateClient($codCli){
\Log::info("update client");
	   $cliRename = array( "pIdOriginCli" => "cod2_cli",   "pIdNumber"=> "cif_cli", "pName"=>"nom_cli", "pRegisteredName" => "rsoc_cli",   "pCountry" => "codpais_cli", "pProvince" => "pro_cli", "pCity" => "pob_cli", "pZipCode" => "cp_cli",  "pPhone" => "tel1_cli", "pMobile" => "tel2_cli", "pFax" => "tel3_cli", "pLegalEntity" => "fisjur_cli",  "pCreateDate" => "f_alta_cli", "pUpdateDate" => "f_modi_cli", "pSource" => "tipo_cli", "pDocumentType" =>  "tdocid_cli", "pDocrepresentative" => "docid_cli", "pTyperepresentative" => "tipv_cli","profession" =>"seudo_cli" );
	   $cliWebRename = array(  "pEmail" => "usrw_cliweb","pPassword" => "pwdwencrypt_cliweb");
	   $clidRename = array(  "pCountryShipping" => "codpais_clid", "pProvinceShipping" => "pro_clid", "pCityShipping" => "pob_clid", "pZipCodeShipping" => "cp_clid",  "pPhoneShipping" => "tel1_clid", "pMobileShipping" => "tel2_clid" );

	   $fieldsArray= array_merge($cliRename, $cliWebRename, $clidRename);



		$fields = $this->CreateFields($codCli,  $fieldsArray);
		$xml = $this->createXML($fields);
		/*
		$stringXML =  str_replace('<?xml version="1.0"?>', "", $xml->asXML());
		echo $stringXML;
		die();
		*/
		$res = $this->callWebService($xml,"ModCustomer");
		if(!empty($res)){
			if ($res->codigo != "OK"){
				\Log::info("error Web service Ansorena". print_r($res,true));
			}
		}


	}

	public function showClient($codCli){
		$cliente = FxCli::SELECT("COD2_CLI")->where("COD_CLI", $codCli)->first();
		$fields = array("pIdOriginCli" => $cliente->cod2_cli);
		$xml = $this->createXML($fields);
		$xml->addChild("pIdOriginCli", $codCli);

		$res = $this->callWebService($xml,"ShowCustomer");
		print_r($res);
	}



	#creo esta función para que sea más visible la asociación de campos
	private function CreateFields($codCli, $fieldsArray){
		#generamos los campos a cargar de base de datos
		$fields = implode(",",$fieldsArray);

		$cliente = FxCli::SELECT($fields.", concat(dir_cli, dir2_cli)  address, concat(dir_clid, dir2_clid)  addressshiping, nllist1_cliweb || ',' || nllist2_cliweb || ',' || nllist3_cliweb || ',' || nllist4_cliweb || ',' || nllist5_cliweb || ',' || nllist6_cliweb || ',' || nllist7_cliweb || ',' || nllist8_cliweb || ',' || nllist9_cliweb || ',' || nllist10_cliweb || ',' || nllist11_cliweb || ',' || nllist12_cliweb || ',' || nllist13_cliweb || ',' || nllist14_cliweb || ',' || nllist15_cliweb || ',' || nllist16_cliweb || ',' || nllist17_cliweb || ',' || nllist18_cliweb || ',' || nllist19_cliweb || ',' || nllist20_cliweb newsletter")
		->where("cod_cli",$codCli)
		->join('FXCLIWEB', 'FXCLIWEB.COD_CLIWEB = FXCLI.COD_CLI  AND FXCLIWEB.GEMP_CLIWEB = FXCLI.GEMP_CLI')
		->leftjoin('FXCLID', "FXCLID.GEMP_CLID = FXCLI.GEMP_CLI AND FXCLID.CLI_CLID = FXCLI.COD_CLI AND FXCLID.CODD_CLID ='W1' ")
		->first();

		#cargamos los campos indicados en el array
		$fields=array();
		foreach($fieldsArray as $key => $field){
			$fields[$key] = $cliente->$field;
		}

		#cargamos los campos compuestos que no estaban en el array
		$fields["pAddress"] = $cliente->address;
		$fields["pAddressShipping"] = $cliente->address;
		//reemplazamos los S y N por ceros y unos, para que sean "booleanos" dentro de un string
		$fields["pNewsLetterX"] = str_replace(["S","N"],["1","0"], $cliente->newsletter);

		#pongo las fechas en el formato que me piden
		$fields["pCreateDate"] = date("m/d/Y h:i:s", strtotime($fields["pCreateDate"]));
		$fields["pUpdateDate"] = date("m/d/Y h:i:s", strtotime($fields["pUpdateDate"]));

		/*  Pruebas de rellenar campos con los máximos valores posibles de nuestra base de datos   para ver si se provoca un error
		#Poner máximos de valores en lso campos
		$longitudes = array( "pEmail" => 80,"pPassword" => 256, "pAddress" => 60,"pAddressShipping" => 60,   "pIdNumber"=> 20, "pName"=>60, "pRegisteredName" => 60,   "pCountry" => 2, "pProvince" => 30, "pCity" => 30, "pZipCode" => 10,  "pPhone" => 40, "pMobile" => 40, "pFax" => 40, "pLegalEntity" => 1,   "pSource" => 1, "pDocumentType" =>  1, "pDocrepresentative" => 20, "pTyperepresentative" => 1,  "pCountryShipping" => 2, "pProvinceShipping" => 30, "pCityShipping" => 30, "pZipCodeShipping" => 10,  "pPhoneShipping" => 40, "pMobileShipping" => 40 );
		foreach($longitudes as $key => $numChar){
					$fields[$key] = $this->generar_valores_ejemplo($key,$numChar);
		}

		/* fin pruebas de rellenar los cmapos con los máximos valores posibles */




		#LIMITAMOS LOS CAMPOS AL NUMERO DE CARCATERES MÁXIMO QUE ADMITEN ELLOS
		$limites = array("pPhone" => 30, "pMobile" => 30, "pFax" => 30,   "pPhoneShipping" => 30, "pMobileShipping" => 30 );
		//	$longitudes = array( "pEmail" => 80,"pPassword" =>90);
			foreach($limites as $key => $limite){

						$fields[$key] = mb_substr($fields[$key], 0 ,  $limite);

			}

		return $fields;

	}




}
