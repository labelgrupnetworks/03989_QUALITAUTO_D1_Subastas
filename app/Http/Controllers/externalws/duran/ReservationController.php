<?php
namespace App\Http\Controllers\externalws\duran;


use App\Http\Controllers\Controller;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use SimpleXMLElement;
class ReservationController extends DuranController
{


	public function createReservation($codCli, $lots){


		$xml = $this->createXMLReservation($codCli, $lots);


		$res = $this->callWebService($xml,"wbHacerReserva");

		return $res;
	}


	public function deleteReservation($codCli, $lots){

		$xml = $this->createXMLReservation($codCli, $lots);

		$res = $this->callWebService($xml,"wbQuitarReserva");


	}



	private function createXMLReservation($codCli, $lots){

		$codigoPersona = FxCli::SELECT("COD2_CLI")->where("cod_cli",$codCli)->first();

		if(empty($codigoPersona) ){
			$textEmail = "No se ha podido realizar la reserva, usuario no existe<br> codcli: $codCli  ";
			$this->sendEmailError("createOrder", $textEmail,""  );
			return;
		}
		$xml = new SimpleXMLElement("<root></root>");

		$xml->addChild("codigopersona", $codigoPersona->cod2_cli);

		$fgasigl0 = new FgAsigl0();
		#siempre es la subasta 7500, la de venta directa
		$fgasigl0 = $fgasigl0->where("sub_asigl0","7500");

		$where = [];
		foreach($lots as  $lot){
			$where[] = ["ref_asigl0", '=', $lot, 'or'];
		}
		$fgasigl0 = $fgasigl0->where($where);


		$codigoarticulos = $fgasigl0->select("IDORIGEN_ASIGL0")->get();



		$articulos =$xml->addChild("articulos");
		foreach($codigoarticulos as $codigoarticulo){
			$articulo =	$articulos->addChild("articulo" );
			$codigoarticulo = substr($codigoarticulo->idorigen_asigl0,-7);
			$articulo->addAttribute("codigoarticulo",$codigoarticulo);
		}

		return $xml;
	}

}
