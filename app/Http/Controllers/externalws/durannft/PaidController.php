<?php
namespace App\Http\Controllers\externalws\durannft;


use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\externalws\vottun\VottunController;
use SimpleXMLElement;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgNft;
use App\Models\Payments;
use App\Models\V5\WebPayCart;
use App\Models\V5\FxPcob;
use App\Models\V5\Web_Artist;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Jobs\SoapJob;
use Config;
use App\libs\EmailLib;
class PaidController extends DuranNftController
{


	public function informPaid($merchantID){
		try{

				if(!empty($merchantID)){
					$xml =	$this->cartInfo($merchantID);

					$tipo = substr($merchantID,0,1) ;
					if($tipo == "M"){

						#SE LLAMARA A LA FUNCION QUE TOQUE PARA INDICAR QUE SE HA PAGADO EL MINTADO O LA TRANSFERENCIA
						$res = $this->callWebService($xml,"wbFinalizarPagoNft");
					}else{
						\Log::info("se ha vendido un lote");
						$res = $this->callWebService($xml,"wbGrabarVenta");
					}


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
		//ver tipo de transaccion
		$tipo = substr($merchantID,0,1) ;

		#EL TIPO 'M' ES PARA PAGO DE MINTEO O TRANSFERENCIA
		if ($tipo == "M"){
			$transaccion = WebPayCart::where("IDTRANS_PAYCART", $merchantID)->first();
			if(empty($transaccion)){
				\Log::info("No hay carrito pagado con el idtrans $merchantID");
				return ;
			}
			$info = json_decode($transaccion->info_paycart);
			$xml = new SimpleXMLElement("<root></root>");

			#de momento no hay campo

			$xml->addChild("idpago", $merchantID );
			$xml->addChild("formapago", $info->paymethod );
			$xml->addChild("fechapagado", date("Y-m-d H:i:s") );
			$xml->addChild("importe", $info->total);
			$transacciones = $xml->addChild('transacciones');



			if($info->reason == "mint"){
				foreach($info->lots as $keyLot => $lot) {
					$mint=FgNft::select("MINT_ID_NFT, NETWORK_NFT")->where("NUMHCES_NFT",$lot->num)->where("LINHCES_NFT",$lot->lin)->first();
					if(!empty($mint)){
						$transacciones->addChild('idtransaccion', $mint->network_nft ."-". $mint->mint_id_nft);
					}else{
						\Log::info("no se ha encontrado ningun minteo relacionado con el pago $merchantID");
					}
				}

			}elseif($info->reason == "transfer"){
				foreach($info->lots as $keyLot => $lot) {
					$transfer = FgNft::select("TRANSFER_ID_NFT, NETWORK_NFT")->where("NUMHCES_NFT",$lot->num)->where("LINHCES_NFT",$lot->lin)->first();
					if(!empty($transfer)){
						$transacciones->addChild('idtransaccion',$transfer->network_nft ."-". $transfer->transfer_id_nft);
					}else{
						\Log::info("no se ha encontrado ninguna transferencia relacionada con el pago $merchantID");
					}

				}

			}
			#para que no siga
			return $xml;
		}

		#si es una factura
		if ($tipo == "T"){
			$transaccion = WebPayCart::where("IDTRANS_PAYCART", $merchantID)->first();
			if(empty($transaccion)){
				\Log::info("No hay carrito pagado con el idtrans $merchantID");
				return ;
			}
			$info_trans = json_decode($transaccion->info_paycart);
			$paymethod = $info_trans->paymethod;


			$cli = FxCli::select("cod2_cli,cod_cli")->where("cod_cli",$transaccion->cli_paycart )->first();
			if(empty($cli)){
				\Log::info("No hay cliente con el cod_cli ". $transaccion->cli_paycart);
				return ;
			}
			$info["codigopersona"] =$cli->cod2_cli;


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


		}elseif ($tipo == "P"){
			$pago = new Payments();

			$fact = $pago->getInfTransExt($merchantID);
			if(empty($fact)){
				\Log::info("No hay lotes pagados con el merchandId $merchantID");
				return ;
			}

			$csubArray =  $pago->getFGCSUB0($fact->emp_csub0ext,$fact->npre_csub0ext,$fact->apre_csub0ext);
			if(count($csubArray) == 0){
				\Log::info("No se ha podido recuperar el csub con los datos ".$fact->emp_csub0ext." ".$fact->npre_csub0ext. " " . $fact->apre_csub0ext);
				return ;
			}
			$csub = head($csubArray);
			$cli = FxCli::select("cod2_cli,cod_cli")->where("cod_cli",$csub->cli_csub0 )->first();
			if(empty($cli)){
				\Log::info("No hay cliente con el cod_cli ". $csub->cli_csub0);
				return ;
			}
			$info["codigopersona"] =$cli->cod2_cli;

			$extraInfo =  $pago->getEXTRAINF($fact->apre_csub0ext, $fact->npre_csub0ext);

			$info_trans = json_decode($extraInfo->extrainf_csub0);
			$paymethod = "creditcard";
			foreach($info_trans as $subasta){
				foreach($subasta as $lote){
					$paymethod = $lote->inf->paymethod;
				}
			}



			$lotesCsub = $pago->getLotsFact($fact->apre_csub0ext, $fact->npre_csub0ext);


			#Crear where  de subastas y referencias
			$auctions = array();
			foreach($lotesCsub as $lot){
				if(empty( $auctions[$lot->sub_csub])){
					$auctions[$lot->sub_csub] = Array();
				}
				$auctions[$lot->sub_csub][] = $lot->ref_csub;
			}


		}else{
			\Log::info("tipo de pago no contemplado en webservice Duran NFT");
			die();
		}

		#Parte comun de coger lotes tanto si es compra de carrito como si es adjudicacion por subasta online
		$refLots=" ( ";
			$or = "";
			foreach ($auctions as $cod_sub => $lots){
				$refLots.= "$or (sub_asigl0 = '$cod_sub' and ref_asigl0 in (". implode(",", $lots) .") )";
				$or = " OR ";
			}
			$refLots.=" )";


		#cargamos datos de los lotes
		$fgasigl0 = new FgAsigl0();
		$fgasigl0 = $fgasigl0->GetLotsByRefAsigl0($refLots)->leftjoinAlm();
		$lots = $fgasigl0->select("REF_ASIGL0,SUB_ASIGL0, TIPO_SUB,  NUM_HCES1, LIN_HCES1, IDORIGEN_ASIGL0, DESC_HCES1, DES_ALM, DIR_ALM, ALM_HCES1, alto_hces1, ancho_hces1, grueso_hces1, HIMP_CSUB , BASE_CSUB ,   IMPSALHCES_ASIGL0,COML_HCES1 , DESCWEB_HCES1, PERMISOEXP_HCES1, SEC_HCES1, PC_HCES1, TRANSPORT_HCES1, COD2_CLI AS PROPIETARIO, COMP_HCES1")
		->LeftJoinOwnerWithHces1()->JoinCSubAsigl0()->get();


		# 4-tarjeta; 6-bizum
		if($paymethod == "bizum"){
			$formaPago= 6;
		}else{
			$formaPago= 4;
		}

		$info["numeroPedido"] = $merchantID;

		$info["enviarfactura"] = 1;


		$info["formaPago"] = $formaPago;


		$info["modoVenta"] = 9;#9-NFT ;6-galeria 5-subasta online; 3-venta directa online;
		#falta poner campo de texto

		$info["articulos"] = array();


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
		$vottuncontroller = new VottunController();
		foreach($lots as $lot){
			#SE DEBEN TRANSFERIR LAS OBRAS AL USUARIO
			$vottuncontroller->transferNFT($lot->num_hces1, $lot->lin_hces1);
			#no hay que tener en cuenta el iva de la comisión,
			$caracteristicas = FgCaracteristicas_Hces1::getByLot( $lot->num_hces1, $lot->lin_hces1);
			if($lot->tipo_sub == 'O'){
				$price = $lot->himp_csub;
				$paid = $price;
				$importesiniva= round($price / (1 + ($ivaUser/100)),2);
			}else{
				$price = $lot->impsalhces_asigl0;
				$paid =\Tools::PriceWithTaxForEuropean($price,$cli->cod_cli) ;
				$importesiniva= round($price / (1 + $ivaGeneral),2);
			}

			#notificar al admin
			$email = new EmailLib('LOT_SOLD_ADMIN');
			if(!empty($email->email)){
				$email->setUserByCod($cli->cod_cli,false);
				$email->setLot($lot->sub_asigl0,$lot->ref_asigl0);
				$email->setPrice(\Tools::moneyFormat($paid,"",2));
				$email->setTo(\Config::get('app.admin_email'));

				$email->send_email();
			}

		   $lote = array();

		  # $lote["codigoArticulo"] = !empty($caracteristicas[1])? $caracteristicas[1]->value_caracteristicas_hces1 : ""; # codigo en duran   ;
		   $lote["titulo"] = $lot->descweb_hces1 ;
		   $lote["descripcion"] = $lot->desc_hces1 ;
			#usamos Num y lin ya que los lotes pueden variar de subasta, por lo que se han podido mintear en otra subasta y se envia el identificador del lote al mintear
		   $lote["referencia"] = $lot->num_hces1 ."-".  $lot->lin_hces1 ;


		   $lote["importesiniva"] =  $importesiniva;

		   $lote["tipoiva"] =$ivaUser;   # indicar el iva que tiene 21 o 0 ;
		   $lote["vendedor"] =$lot->propietario;  # codigo artista ;
		   $lote["familia"] = $lot->sec_hces1; # codigo familia ;
		   $lote["coste"] = $lot->pc_hces1?? ""; # coste del articulo ; campo de caracteristica precio_neto_artista
		   #solo indicamos el porcentaje si no hay información del precio de coste
		   if(empty($lote["coste"])){
				$lote["porcentajevendedor"] =  $lot->comp_hces1;
		   }else{
				$lote["porcentajevendedor"] =  "";
		   }
		   $img = \Tools::url_img('lote_large', $lot->num_hces1, $lot->lin_hces1);
		   $lote["imagen"] =base64_encode(file_get_contents($img));    # imagen bits ;

		   $lote["total"] = $paid  ;# precio final

		   #borrar caracteristicas que para duran no son caracteristicas y por lo tanto no se deben enviar
		 //  unset($caracteristicas[1]);

		   #cargar caracteristicas
		   $lote["caracteristica"] = array();
		   foreach($caracteristicas as $key => $caracteristica){
				#la caracteristica de colección nueva no se debe enviar ya que es propia de la web
				if($key !=1){
					$car = array();
					$car["codigocaracteristica"] = $caracteristica->id_caracteristicas;
					$car["codigovalorcaracteristica"] = !empty($caracteristica->idvalue_caracteristicas_hces1)? $caracteristica->idvalue_caracteristicas_hces1 : 0 ;
					$car["valor"] = $caracteristica->value_caracteristicas_hces1;
					$lote["caracteristica"][]= $car;
				}

		   }

		   $importeTotal += $lote["total"];
		   $info["lotes"][] = $lote;
	   }
	   #importe total pagado, lo ponemso al final por que necesitamos calcularlo en base a los lotes
	   $info["importeTotal"] = $importeTotal;
	   $info["formaiva"] = $ivaUser > 0? 1:2; #1-IVA incluido en el precio; 2-IVA exento
	   #comento correo hay que reactivarlo
	   $this->sendConfirmationMail($info);

		return $this->createXMLPaid( $info);
	}


	private function createXMLPaid( $info){
		$xml = new SimpleXMLElement("<root></root>");

		#de momento no hay campo

		$xml->addChild("codigopersona",  $info["codigopersona"] );
		$xml->addChild("enviarfactura",  1  );#siemrpe a 1
		$xml->addChild("importetotal", $info["importeTotal"]  );



		$xml->addChild("modoventa",  $info["modoVenta"]   );
		$xml->addChild("formapago",  $info["formaPago"]   );
		$xml->addChild("numeropedido", $info["numeroPedido"]  );#como no hay pedido ponemos el identioficador de merchantid

		$xml->addChild("formaiva",  $info["formaiva"]   );

		$articulos = $xml->addChild('articulos');

		#el pedido puede contener varios lotes
		foreach( $info["lotes"] as $lote){
			$articulo = $articulos->addChild('articulo');
		//	$articulo->addAttribute("codigoarticulo", $lote ["codigoArticulo"]);
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

			$caracteristicas = $articulo->addChild('caracteristicas');

			foreach($lote["caracteristica"] as $car){
				$caracteristica = $caracteristicas->addChild('caracteristica');
				$caracteristica->addAttribute("codigocaracteristica", $car["codigocaracteristica"]);
				$caracteristica->addAttribute("codigovalorcaracteristica", $car["codigovalorcaracteristica"]);
				$caracteristica->addAttribute("valor", $car["valor"]);
			}
			#Caracteristica TITULO
			$caracteristica = $caracteristicas->addChild('caracteristica');
			$caracteristica->addAttribute("codigocaracteristica","393");
			$caracteristica->addAttribute("codigovalorcaracteristica", "");
			$caracteristica->addAttribute("valor",$lote ["titulo"]);
		}


		return $xml;

	}


	public function sendConfirmationMail($info){
		$cliente = FxCli::select("NOM_CLI, DIR_CLI, DIR2_CLI, CP_CLI, POB_CLI, PRO_CLI, TEL1_CLI, PAIS_CLI, EMAIL_CLI ")->where("cod2_cli", $info["codigopersona"])->first();



		$infoFacturación =  $cliente->nom_cli."<br>". $cliente->dir_cli.$cliente->dir2_cli." <br> ".$cliente->pob_cli.", ".$cliente->pro_cli.", ".$cliente->cp_cli."<br> ".$cliente->pais_cli." <br> "."T: ".$cliente->tel1_cli;

		$email = new EmailLib('NOTIFICAR_PAGO_DURAN');
		$email->setAtribute("INFO_FACTURACION",$infoFacturación);
		$email->setAtribute("NUM_PEDIDO",$info["numeroPedido"]);
		$email->setAtribute("NAME",$cliente->nom_cli);
		$email->setAtribute("TOTAL",\Tools::moneyFormat( $info["importeTotal"] ," €",2) );
	#solo tienen 4-tarjeta; 6-bizum
		if($info["formaPago"] == 4){
			$infoPago= trans(\Config::get('app.theme').'-app.user_panel.pay_creditcard');
		}else if($info["formaPago"] == 6){
			$infoPago= trans(\Config::get('app.theme').'-app.user_panel.pay_bizum');
		}
		$email->setAtribute("INFO_PAGO",$infoPago);

		$infoLots = "";
		foreach($info["lotes"] as $lote){
			$infoLots .= "<tr bgcolor=\"#efefef\" style=\"text-align: center;\" >";
			$infoLots .= "<td><p ><strong>".$lote["titulo"]."</strong></p></td>";
			$infoLots .= "<td style=\"text-align: right;padding-right: 5px;\">".\Tools::moneyFormat($lote["total"] ," €",2) ."</td>";
			$infoLots .= " </tr> ";
		}

		$email->setAtribute("INFO_ARTICULOS",$infoLots);


		$email->setTo($cliente->email_cli, $cliente->nom_cli);
		$email->setBcc(\Config::get('app.admin_email'));

		$email->send_email();

	}


}
