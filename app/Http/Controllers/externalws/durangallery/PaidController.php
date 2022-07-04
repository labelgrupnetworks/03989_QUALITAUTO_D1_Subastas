<?php
namespace App\Http\Controllers\externalws\durangallery;


use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\Payments;
use App\Models\V5\WebPayCart;
use App\Models\V5\FxPcob;
use App\Models\V5\Web_Artist;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Jobs\SoapJob;
use Config;
use App\libs\EmailLib;
class PaidController extends DuranGalleryController
{


	public function informPaid($merchantID){
		try{
				if(!empty($merchantID)){



					$xml =	$this->cartInfo($merchantID);

					\Log::info("se ha vendido un lote");
					$res = $this->callWebService($xml,"wbGrabarVenta");

					if(!empty($res) && $res->resultado == 0){
						/* PONER IDORIGEN EN ASIGL0 para que luego puedan marcarlo como pagado */
						foreach($res->articulosnuevos->articulo as $articulo){
							$idorigen = (string) $articulo->codigoarticulo;
							$subRef = explode("-",$articulo->referencia);
							FgAsigl0::where("sub_asigl0", $subRef[0])->where("ref_asigl0", $subRef[1])->update(["idorigen_asigl0" => $idorigen]);

						}
					}

					#el resultado 1 está controlado en duran controller y el 0 es k todo ok
					if(empty($res) || $res->resultado >1){

						$this->sendEmailError("wbGrabarVenta",htmlspecialchars($xml->asXML()), print_r($res,true),true );
					}
				}
			}catch(\Exception $e){
				   \Log::info($e);
			}

	}

	public function responsePaid($res){
		#por si fuera necesario tratar la respuesta
	}


	private function cartInfo($merchantID){
		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $merchantID)->first();
		if(empty($transaccion)){
			\Log::info("No hay carrito pagado con el idtrans $merchantID");
			return ;
		}
		$info_trans = json_decode($transaccion->info_paycart);

		$cli = FxCli::select("cod2_cli,cod_cli")->where("cod_cli",$transaccion->cli_paycart )->first();
		if(empty($cli)){
			\Log::info("No hay cliente con el cod_cli ". $transaccion->cli_paycart);
			return ;
		}
		$info["codigopersona"] =$cli->cod2_cli;
		$info["numeroPedido"] = $merchantID;

		$info["seguro"] = !empty($info_trans->seguro) ?  $info_trans->seguro : 0;

		#3-transferencia; 4-tarjeta; 6-bizum
		if( $info_trans->paymethod == "transfer"){
			$formaPago= 3;
		}elseif($info_trans->paymethod == "bizum"){
			$formaPago= 6;
		}else{
			$formaPago= 4;
		}
		$info["formaPago"] = $formaPago;


		$info["modoVenta"] = 6;#6-galeria 5-subasta online; 3-venta directa online;
		#falta poner campo de texto

		$info["articulos"] = array();
		#cargamos datos de los lotes
		$fgasigl0 = new FgAsigl0();
		#Crear where  de subastas y referencias
			$auctions = array();
			foreach($info_trans->lots as $lot){
				if(empty( $auctions[$lot->cod_sub])){
					$auctions[$lot->cod_sub] = Array();
				}
				$auctions[$lot->cod_sub][] = $lot->ref;
			}

			$refLots=" ( ";
			$or = "";
			foreach ($auctions as $cod_sub => $lots){
				$refLots.= "$or (sub_asigl0 = '$cod_sub' and ref_asigl0 in (". implode(",", $lots) .") )";
				$or = " OR ";
			}
			$refLots.=" )";
		#FIN de Crear where  de subastas y referencias


		$fgasigl0 = $fgasigl0->GetLotsByRefAsigl0($refLots)->leftjoinAlm();
		$lots = $fgasigl0->select("REF_ASIGL0,SUB_ASIGL0, NUM_HCES1, LIN_HCES1, IDORIGEN_ASIGL0, DESC_HCES1, DES_ALM, DIR_ALM, ALM_HCES1, alto_hces1, ancho_hces1, grueso_hces1,   IMPSALHCES_ASIGL0,COML_HCES1 , DESCWEB_HCES1, PERMISOEXP_HCES1, SEC_HCES1, PC_HCES1, TRANSPORT_HCES1")->get();

		$importeTotal = 0;

		 #pongo el valor * 100 ya que el iva lo devolverá con decimales
		$ivaUser = \Tools::TaxForEuropean($cli->cod_cli) *100;
		 #los lotes  tienen iva en el precio por lo que hay que calcularlo sin iva siempre, no podemos usar el iva del usuario, si no el general
		$payments = new Payments();
		$ivaGeneral = $payments->getIVA(date('Y-m-d H:i:s'),'01');

		if(count($ivaGeneral) > 0){
			$ivaGeneral = $ivaGeneral[0]->iva_iva/100;
		}else{
			$ivaGeneral = 0;
		}

		foreach($lots as $lot){

			#no hay que tener en cuenta el iva de la comisión,
			$caracteristicas = FgCaracteristicas_Hces1::getByLot( $lot->num_hces1, $lot->lin_hces1);
			$idAutor="";
			if(!empty($caracteristicas[414])){
				$autor = Web_Artist::where("ID_ARTIST", $caracteristicas[414]->idvalue_caracteristicas_hces1)->first();
				if(!empty($autor)){
					$idAutor = $autor->idexternal_artist;
				}
			}


			#notificar al propietario
			$email = new EmailLib('LOT_SOLD_ASSIGNOR');
			if (!empty($email->email)) {
				 #si no quieren que se envie el correo no pondran el email
				if(!empty($autor) && !empty($autor->email_artist)){
					$email->setLot($lot->sub_asigl0, $lot->ref_asigl0);
					$email->setTo($autor->email_artist,$autor->name_artist);
					$email->setName($autor->name_artist);

					$email->send_email();
				}
			}

			#notificar al admin
			$email = new EmailLib('LOT_SOLD_ADMIN');
			if(!empty($email->email)){
				$email->setUserByCod($cli->cod_cli,false);
				$email->setLot($lot->sub_asigl0,$lot->ref_asigl0);
				$email->setPrice(\Tools::moneyFormat(\Tools::PriceWithTaxForEuropean($lot->impsalhces_asigl0,$cli->cod_cli),"",2) );
				$email->setTo("sara.gimeno@duran-subastas.com");
				$email->setCc("consuelo.duran@duran-subastas.com");
				$email->setCc("macarena.duran@duran-subastas.com");
				$email->send_email();
			}

		   $lote = array();

		   $lote["codigoArticulo"] = !empty($caracteristicas[1])? $caracteristicas[1]->value_caracteristicas_hces1 : ""; # codigo en duran   ;
		   $lote["titulo"] = $lot->descweb_hces1 ;
		   $lote["descripcion"] = $lot->desc_hces1 ;
			#usamos subasta y rferencia como identificador ya que estan usando lotes con stock [OJO MIRAR IMPLICACIÓN DE QUE VARIOS LOTES TENGAN LA MISMA NUM Y LIN]
		   $lote["referencia"] = $lot->sub_asigl0 ."-". $lot->ref_asigl0 ;


		   $lote["importesiniva"] =  round($lot->impsalhces_asigl0 / (1 + $ivaGeneral),2);

		   $lote["tipoiva"] =$ivaUser;   # indicar el iva que tiene 21 o 0 ;
		   $lote["vendedor"] =$idAutor;  # codigo artista ;
		   $lote["familia"] = $lot->sec_hces1; # codigo familia ;
		   $lote["coste"] = $lot->pc_hces1?? ""; # coste del articulo ; campo de caracteristica precio_neto_artista
		   $img = \Tools::url_img('lote_large', $lot->num_hces1, $lot->lin_hces1);
		   $lote["imagen"] =base64_encode(file_get_contents($img));    # imagen bits ;
		   $lote["envioincluido"] =  $lot->transport_hces1=="S"? "1" : "0";  # envío incluido en el precio (1-Sí; 0-No) ;
		   $lote["tenencia"] = $lot->alm_hces1;  # 2-artista, 1-Durán ;
		   $lote["ubicacion"] = !empty($caracteristicas[2])?$caracteristicas[2]->value_caracteristicas_hces1 : ""; ; # ubicacion física ;
		   $lote["total"] = \Tools::PriceWithTaxForEuropean($lot->impsalhces_asigl0,$cli->cod_cli)  ;# precio final

		   #borrar caracteristicas que para duran no son caracteristicas y por lo tanto no se deben enviar
		   unset($caracteristicas[1]);
		   unset($caracteristicas[2]);
		   unset($caracteristicas[3]);
		   #cargar caracteristicas
		   $lote["caracteristica"] = array();
		   foreach($caracteristicas as $caracteristica){
			$car = array();
			$car["codigocaracteristica"] = $caracteristica->id_caracteristicas;
			$car["codigovalorcaracteristica"] = !empty($caracteristica->idvalue_caracteristicas_hces1)? $caracteristica->idvalue_caracteristicas_hces1 : 0 ;
			$car["valor"] = $caracteristica->value_caracteristicas_hces1;
			$lote["caracteristica"][]= $car;
		   }
		   #alto
		    $car = array();
			$car["codigocaracteristica"] = 423			;
			$car["codigovalorcaracteristica"] =  0 ;
			$car["valor"] = $lot->alto_hces1;
			$lote["caracteristica"][]= $car;

		   #ancho
		   $car = array();
		   $car["codigocaracteristica"] = 424			;
		   $car["codigovalorcaracteristica"] =  0 ;
		   $car["valor"] = $lot->ancho_hces1;
		   $lote["caracteristica"][]= $car;

		   #profundo
		   $car = array();
		   $car["codigocaracteristica"] = 425			;
		   $car["codigovalorcaracteristica"] =  0 ;
		   $car["valor"] = $lot->grueso_hces1;
		   $lote["caracteristica"][]= $car;




		   $importeTotal += $lote["total"];

		   $info["lotes"][] = $lote;
	   }
	   #importe total pagado, lo ponemso al final por que necesitamos calcularlo en base a los lotes
	   $info["importeTotal"] = $importeTotal;
	   $info["formaiva"] = $ivaUser > 0? 1:2; #1-IVA incluido en el precio; 2-IVA exento
	  

	  # $this->sendConfirmationMail($info);

		return $this->createXMLPaid( $info);
	}




	private function createXMLPaid( $info){


	$xml = new SimpleXMLElement("<root></root>");

		#de momento no hay campo
		$xml->addChild("importetotal", $info["importeTotal"]  );
		$xml->addChild("codigopersona",  $info["codigopersona"] );
		$xml->addChild("seguro", $info["seguro"]   );
		$xml->addChild("enviarfactura",  1  );#siemrpe a 1

		$xml->addChild("modoventa",  $info["modoVenta"]   );
		$xml->addChild("formapago",  $info["formaPago"]   );
		$xml->addChild("numeropedido", $info["numeroPedido"]  );#como no hay pedido ponemos el identioficador de merchantid

		$xml->addChild("formaiva",  $info["formaiva"]   );

		$articulos = $xml->addChild('articulos');

		#el pedido puede contener varios lotes
		foreach( $info["lotes"] as $lote){
			$articulo = $articulos->addChild('articulo');
			$articulo->addAttribute("codigoarticulo", $lote ["codigoArticulo"]);
			$articulo->addAttribute("referencia",$lote ["referencia"] );

			$articulo->addAttribute("total", $lote ["total"] );
			$articulo->addAttribute("importesiniva", $lote ["importesiniva"] );
			$articulo->addAttribute("tipoiva", $lote ["tipoiva"] );
			$articulo->addAttribute("titulo", $lote ["titulo"] );
			$articulo->addAttribute("descripcion", $lote ["descripcion"] );
			$articulo->addAttribute("vendedor", $lote ["vendedor"] );
			$articulo->addAttribute("familia", $lote ["familia"] );
			$articulo->addAttribute("coste", $lote ["coste"] );
			$articulo->addAttribute("imagen", $lote ["imagen"] );
			$articulo->addAttribute("envioincluido", $lote ["envioincluido"] );
			$articulo->addAttribute("tenencia", $lote ["tenencia"] );

			$caracteristicas = $articulo->addChild('caracteristicas');
			foreach($lote["caracteristica"] as $car){
				$caracteristica = $caracteristicas->addChild('caracteristica');
				$caracteristica->addAttribute("codigocaracteristica", $car["codigocaracteristica"]);
				$caracteristica->addAttribute("codigovalorcaracteristica", $car["codigovalorcaracteristica"]);
				$caracteristica->addAttribute("valor", $car["valor"]);
			}
		}


		return $xml;

	}


	public function sendConfirmationMail($info){
		$cliente = FxCli::select("NOM_CLI, DIR_CLI, DIR2_CLI, CP_CLI, POB_CLI, PRO_CLI, TEL1_CLI, PAIS_CLI, EMAIL_CLI ")->where("cod2_cli", $info["codigopersona"])->first();

		$totalPagar =$info["importeTotal"] + $info["ivaTotal"] + $info["importeEnvio"] + $info["importeSeguro"];

		$infoFacturación =  $cliente->nom_cli."<br>". $cliente->dir_cli.$cliente->dir2_cli." <br> ".$cliente->pob_cli.", ".$cliente->pro_cli.", ".$cliente->cp_cli."<br> ".$cliente->pais_cli." <br> "."T: ".$cliente->tel1_cli;

		$email = new EmailLib('NOTIFICAR_PAGO_DURAN');
		$email->setAtribute("INFO_FACTURACION",$infoFacturación);
		$email->setAtribute("NUM_PEDIDO",$info["numeroPedido"]);
		$email->setAtribute("TOTAL",\Tools::moneyFormat($info["importeSeguro"] + $info["importeEnvio"] + $info["importeTotal"] + $info["ivaTotal"]," €",2) );
	#3-transferencia; 4-tarjeta; 6-bizum
		if($info["formaPago"] == 3){
			$infoPago =  trans(\Config::get('app.theme').'-app.user_panel.pay_transfer'). "<br><br>" . trans(\Config::get('app.theme').'-app.user_panel.text_transfer', ["pago" => \Tools::moneyFormat($totalPagar,null,2),"cuenta" => \Config::get('app.tranferCount')]);
		}else if($info["formaPago"] == 4){
			$infoPago= trans(\Config::get('app.theme').'-app.user_panel.pay_creditcard');
		}else if($info["formaPago"] == 6){
			$infoPago= trans(\Config::get('app.theme').'-app.user_panel.pay_bizum');
		}
		$email->setAtribute("INFO_PAGO",$infoPago);

		if($info["formaEnvio"] == 1){
			$infoEnvio = $cliente->nom_cli." <br> ". $info["direccion"] ." <br>  ".$info["poblacion"] .", ".$info["provincia"] .", ".$info["cp"] ." <br> ".$info["pais"] ." <br>  "."T: ".$info["telefono"] ;
			$infoMetodoEnvio = trans(\Config::get('app.theme').'-app.user_panel.envio_agencia');
			$infoMetodoEnvio .="<br><br>".trans(\Config::get('app.theme').'-app.user_panel.gastos_envio').": ".\Tools::moneyFormat($info["importeEnvio"]," €",2);
			if($info["seguro"] == 1){
				$infoMetodoEnvio .="<br><br> ".trans(\Config::get('app.theme').'-app.user_panel.seguro_envio').": ".\Tools::moneyFormat($info["importeSeguro"]," €",2);
			}
		}else{
			$infoEnvio = "";
			$infoMetodoEnvio = trans(\Config::get('app.theme').'-app.user_panel.recogida_producto')."<br> ".trans(\Config::get('app.theme').'-app.user_panel.sala_almacen');
		}

		if(!empty($info["notasEnvio"])){
			$infoEnvio .= "<br><br>". trans(\Config::get('app.theme').'-app.global.coment').": <br> \" ".$info["notasEnvio"]."\" ";
		}

		$email->setAtribute("INFO_ENVIO",$infoEnvio);
		$email->setAtribute("INFO_METODO_ENVIO",$infoMetodoEnvio);
		$infoLots = "";
		foreach($info["lotes"] as $lote){
			$permisoExportacion = $lote ["permisoExportacion"]==1? "<br><br>".trans(\Config::get('app.theme').'-app.lot.permiso_exportacion') : "";
			$infoLots .= "<tr bgcolor=\"#efefef\"> ";
			$infoLots .= "<td><p style=\"padding-left: 5px;\"><strong>".$lote["titulo"]."</strong><br>".$lote["infoAlmacen"] .$permisoExportacion ."</p></td>";
			$infoLots .= "<td style=\"text-align: center;\">".$lote["codigoArticulo"] ."</td>";
			//$infoLots .= "<td style=\"text-align: center;\">1</td>";
			$infoLots .= "<td style=\"text-align: right;padding-right: 5px;\">".\Tools::moneyFormat($lote["total"] + $lote["iva"]," €",2) ."</td>";
			$infoLots .= " </tr> ";
		}
		#suma de lotes
		#$infoLots .= "<tr ><td> </td> <td> </td> <td bgcolor=\"#efefef\"  style=\"text-align: right;padding-right: 5px;\">  <strong>".\Tools::moneyFormat($info["importeTotal"] + $info["ivaTotal"]," €",2) ." </strong></td> </tr> ";

		$email->setAtribute("INFO_ARTICULOS",$infoLots);
		$email->setAtribute("TOTAL_ARTICULOS",\Tools::moneyFormat( $info["importeTotal"] + $info["ivaTotal"]," €",2) );
		$email->setAtribute("GASTOS_ENVIO",\Tools::moneyFormat( $info["importeEnvio"]," €",2) );
		$email->setAtribute("SEGURO_ENVIO",\Tools::moneyFormat( $info["importeSeguro"] ," €",2) );

		$email->setTo($cliente->email_cli, $cliente->nom_cli);
		$email->setBcc("ventaonline@duran-subastas.com");

		$email->send_email();

	}

	private function invoicesInfo( $idTransaction){


		$pago = FxPcob::select("IMP_PCOB, COD2_CLI, ANUM_PCOB, NUM_PCOB")->where("IDTRANS_PCOB0", $idTransaction)->joincli()->joinpcob1()->joinpcob0()->get();
#Falta conseguir la informacion de que metodo de pago usan
#pueden venir varios pagos
		dd($pago);

		#no hay pago
		if(empty($pago)){
			\Log::info("Transaccion no encontrada, $idTransaction");
			return ;
		}

		$xml = new SimpleXMLElement("<root></root>");


		#de momento no hay campo
		$xml->addChild("numero",  "1" );
		$xml->addChild("fecha",  "1" );
		$xml->addChild("formapago",  "1" );


		return $xml;
	}


}
