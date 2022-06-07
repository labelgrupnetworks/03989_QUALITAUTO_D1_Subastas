<?php

namespace App\Http\Controllers\externalws\duran;


use App\Http\Controllers\Controller;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use Session;
use Illuminate\Http\Request;

class ConsentimientoControler extends DuranController
{

	public function createConsentimiento()
	{
		$idValidation = request('id');
		$codCli = request('codigo');
		

		$xml = $this->createXMLConsentimiento($idValidation, $codCli);
		$res = $this->callWebService($xml, "wbConsentimiento");
		$stringXML = str_replace('<?xml version="1.0"?>', "", $xml->asXML());

		if (!empty($res) && $res->resultado == 0) {
			return view('pages.consent', ['res' => $res->resultado]);
		}else{
			$service = "wbConsentimiento";
			if (empty($res) || $res->resultado == 1) {
				$this->sendEmailError($service, htmlspecialchars($stringXML), print_r($res, true),true);
			}
			# Hace el condicional que si es nulo pone por defecto 1
			return view('pages.consent', ['res' => $res? $res->resultado :  1]);
		}


	}

	private function createXMLConsentimiento($idValidation, $codCli)
	{
		$xml = new SimpleXMLElement("<root></root>");
		$xml->addChild("id", $idValidation);
		$xml->addChild("codigo", $codCli);

		return $xml;
	}
}
