<?php

namespace App\Http\Controllers\V5;

use App\Http\Controllers\Controller;

use App\Http\Controllers\PaymentsController;
use App\Models\V5\WebPayCart;
use App\Models\V5\FgLicit;
use App\libs\RedsysAPI;
use App\Models\V5\FgAsigl1_Aux;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Models\Subasta;
use App\Models\User;
use App\libs\EmailLib;
use App\Providers\ToolsServiceProvider;
use Config;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\View;
use Route;
/*
use App\Models\V5\FxClid;

use App\Models\Subasta;
use View;

use Session;


*/
class CarlandiaPayController extends Controller

{
	# table puede tener valor B= bid o  A =aux
	public function getPayLink($codSub, $ref, $licit, $lin, $table){

		$hashPagos = Config::get('app.hashPagos');
		$vars = "$codSub-$ref-$licit-$lin-$table";
		$hash = md5($hashPagos.$vars);



		#el enlace tendrá el id y un hash para validarlo
		return route("carlandiaGeneratePay",["payLink" => $vars."-".$hash]);

	}



	#genera un pago y redirije a la página de pago de redsys
	public function generatePay($payLink){
		$hashPagos = Config::get('app.hashPagos');
		$requestVars =explode("-",$payLink);
		if(count($requestVars)== 6){

			$info = new \Stdclass();
			$info->codSub = $requestVars[0];
			$info->ref = $requestVars[1];
			$info->licit =$requestVars[2];
			$info->lin = $requestVars[3];
			$info->table =$requestVars[4];
			$hashRequest = $requestVars[5];


			$vars = $info->codSub."-".$info->ref."-".$info->licit."-".$info->lin."-".$info->table;
			$hash = md5($hashPagos.$vars);
			if($hash == $hashRequest){


				$client = FgLicit::select("cli_licit")->where("sub_licit", $info->codSub)->where("cod_licit", $info->licit)->first();
				ToolsServiceProvider::exit404IfEmpty($client);





				$carlandiaCommission = \Config::get("app.carlandiaCommission");

				#recuperamos el lote para poder comparar los campos por si han cambiado, EL LOTE NO PUEDE ESTAR RETIRADO
				$lote = FgAsigl0::select("IMPSALHCES_ASIGL0","IMPTASH_ASIGL0","IMPTAS_ASIGL0","IMPRES_ASIGL0", "TIPO_SUB", "FFIN_ASIGL0")->JoinSubastaAsigl0()->where("SUB_ASIGL0", $info->codSub)->where("REF_ASIGL0", $info->ref)->where("RETIRADO_ASIGL0", "N")->first();
				if(empty($lote)){
					return $this->error("El lote ya no se encuentra activo", $info->codSub,$info->ref,$info );
				}
				#comprobamos que aun se pueda pagar el lote en los casos de la puja en la tabla Auxiliar
				if($info->table == "A"){
					#recuperamos la puja AUXILIAR para tener sus datos en todo momento
					$bid = FgAsigl1_Aux::where("SUB_ASIGL1", $info->codSub)->where("REF_ASIGL1", $info->ref)->where("LIN_ASIGL1", $info->lin)->first();

					#Que no este el lote adjudicado

					$adjudicado = FgCsub::select("licit_csub")->where("SUB_CSUB",$info->codSub)->where("REF_CSUB", $info->ref)->first();

					if(!empty($adjudicado)){
						return $this->error("Lote adjudicado", $info );
					}

					#si la fecha del lote ya ha pasado no se pueden realizar pagos, puesto que venimos de pujas auxiliares
					if(strtotime($lote->ffin_asigl0) < time()){
						return $this->error("Fecha de fin de lote superada", $info );
					}

					#Se debe respetar el importe del usuario durante 24h,por lo que no se consultan los precios si aun no han pasado las 24h
					$fecha = strtotime($bid->fec_asigl1 ." + 1 days") ;
					if($fecha < time() ){

						#comprobar que el precio de comprar ya (imptash_asigl0) de la subasta online sigue aceptando esta compra
						if($lote->tipo_sub == 'O' && $bid->pujrep_asigl1=='Y' && $bid->imp_asigl1 < $lote->imptash_asigl0){
							return $this->error("Precio 'COMPRAR YA'  ". ToolsServiceProvider::moneyFormat($lote->imptash_asigl0,"€")." en subasta Online es superior a la puja aux ". ToolsServiceProvider::moneyFormat($bid->imp_asigl1,"€"), $info );
						}

						#comprobar que el precio de comprar (impsalhces_asigl0) de la venta directa sigue aceptando esta compra
						if($lote->tipo_sub == 'V' && $bid->pujrep_asigl1=='B' && $bid->imp_asigl1 < $lote->impsalhces_asigl0){
							return $this->error("Precio 'COMPRAR' ". ToolsServiceProvider::moneyFormat($lote->impsalhces_asigl0,"€")."   en Venta directa es superior a la puja aux ". ToolsServiceProvider::moneyFormat($bid->imp_asigl1,"€"), $info );
						}

						#comprobar que el precio de contraoferta minimo(imptas_asigl0) de la subasta online sigue aceptando esta compra
						if($lote->tipo_sub == 'V' && $bid->pujrep_asigl1=='C' && $bid->imp_asigl1 < $lote->imptas_asigl0){
							return $this->error("Precio 'CONTRAOFERTA MINIMA' ". ToolsServiceProvider::moneyFormat($lote->impsalhces_asigl0,"€")."   en Venta directa es superior a la puja aux ". ToolsServiceProvider::moneyFormat($bid->imp_asigl1,"€"),  $info );
						}

						#para casos de contraoferta aceptada por el concesionario, tipo A , no se comprueba nada
					}


				}else{

					#recuperamos la puja  para tener sus datos en todo momento
					$bid = FgAsigl1::where("SUB_ASIGL1", $info->codSub)->where("REF_ASIGL1", $info->ref)->where("LIN_ASIGL1", $info->lin)->first();
					#Se debe respetar el importe del usuario durante 24h,por lo que no se consultan los precios si aun no han pasado las 24h
					$fecha = strtotime($bid->fec_asigl1 ." + 1 days") ;
					if($fecha < time() ){
						#comprobar que el precio de reserva (impres_asigl0) de la subasta online sigue aceptando esta compra
						if($lote->tipo_sub == 'O'  && $bid->imp_asigl1 < $lote->impres_asigl0){
							return $this->error("Precio 'RESERVA'  ". ToolsServiceProvider::moneyFormat($lote->impres_asigl0,"€")." en subasta Online es superior a la puja ". ToolsServiceProvider::moneyFormat($bid->imp_asigl1,"€"), $info );
						}
					}

				}
				# hay que redondear a 2 decimales
				$impreserva = $bid->imp_asigl1 - ($bid->imp_asigl1 / (1 + $carlandiaCommission));
				$info->impReserva = round($impreserva, 2);
				$info->impTotal = $bid->imp_asigl1;
				$info->tipoSub = $lote->tipo_sub ;
				$info->tipoPuja = $bid->pujrep_asigl1 ;
				$info->fecha = $bid->fec_asigl1 ;

				#usaremos la tabla webPayCart, el límite para el id será de 12 por limitacion de redsys, por eso usamos centisenconds
				$mt = explode(' ', microtime());
				$idTrans = ((int)$mt[1]) * 100 + ((int)round($mt[0] * 100));

				$info_paycart=json_encode($info);
				WebPayCart::create(["IDTRANS_PAYCART" =>$idTrans,
									"CLI_PAYCART"  => $client->cli_licit ,
									"INFO_PAYCART" => $info_paycart,
									"DATE_PAYCART" => date("Y-m-d H:i:s") ]);

				$paymentcontroller = new PaymentsController();

				#hacemos llamada a redsys indicando el paylink en la url para poder validar el pago
				$varsRedsys = $paymentcontroller->requestRedsys($impreserva, $idTrans, "/carlandia/confirmPayment" );

				#reenviamos al formulario
				return \View::make('front::pages.panel.RedsysForm', $varsRedsys);

			}else{
				return $this->error("hash no válido  ", $requestVars );

			}


		}
		else{
			return $this->error("numero de variables no válido  ", $requestVars );
		}

	}

	private function error($description, $info ){
		\Log::info("No se puede Proceder con el pago, $description , infoPuja: ".print_r($info,true));
		return \View::make('front::pages.vehiculoNoDisponible');
	}

	public function confirmPayment(){
		try{
			$redsys = new RedsysAPI;
			$request = request()->all();

			$version = $request["Ds_SignatureVersion"];
			$datos = $request["Ds_MerchantParameters"];
			$signatureRecibida = $request["Ds_Signature"];

			$kc = Config::get('app.KeyRedsys');//Clave recuperada de CANALES
			$firma = $redsys->createMerchantSignatureNotif($kc,$datos);
			$decodec = $redsys->decodeMerchantParameters($datos);
			$returnedVars = json_decode($decodec);
			#datos decodificados
			\Log::info("Confirm Payment ".print_r($returnedVars, true));
		#Si la información es válida
		if ($firma === $signatureRecibida){
			$respuesta = $returnedVars->Ds_Response;

			#las respuesta 0000 a 0099 son de transaccion autorizada, el resto no lleva 2 ceros al principio
			if(substr($respuesta, 0,2) == "00"){
				$idTrans = $returnedVars->Ds_Order;
				$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();
				if(empty($transaccion)){
					\Log::info("Error en pasarela de pago de tienda online, $idTrans no se encuentra en base de datos ");
					return;
				}
				#MARCAMOS EL PEDIDO COMO PAGADO
				WebPayCart::where("IDTRANS_PAYCART", $idTrans)->update(["PAID_PAYCART" => "S"]);
				$this->adjudicarLote($idTrans);

				$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();
				$info = json_decode($transaccion->info_paycart);
				#envio de Email de reserva pagada al usuario
				$email = new EmailLib('PAID_RESERVATION');
				if (!empty($email->email)) {
					$email->setLot($info->codSub, $info->ref);
					$email->setPropInfo($info->codSub, $info->ref);
					$email->setUserByCod($transaccion->cli_paycart, true);
					$email->setUrl(\Config::get('app.url') . \Routing::slug('user/panel/info'));
					#importe pagado, el de la reserva
					$email->setAtribute("IMPORTE_RESERVA", ToolsServiceProvider::moneyFormat($info->impReserva,trans(\Config::get('app.theme').'-app.subastas.euros'),2));
					#redondeamos para que si hay decimales no salgan, es posible que aparezcan decimales al haber redondeado lso precios de venta
					$email->setAtribute("IMPORTE_RESTANTE", ToolsServiceProvider::moneyFormat( floor($info->impTotal - $info->impReserva),trans(\Config::get('app.theme').'-app.subastas.euros'),2));
					$email->setAtribute("IMPORTE_TOTAL", ToolsServiceProvider::moneyFormat($info->impTotal,trans(\Config::get('app.theme').'-app.subastas.euros'),2));

					$email->send_email();
				}

				#envio de email de subasta finalizada a los otros pujadores
				#solo los lotes de subasta Online y de puja auxiliar (comprar ya) avisaran al resto de compradores de que se ha cancelado la subasta
				if($info->tipoSub=='O' && $info->table == 'A'){


					$email = new EmailLib('SUBASTA_PERDIDA');
					if (!empty($email->email)) {
						$email->setLot($info->codSub, $info->ref);

						#hacer foreach de usuarios que han pujado y no se llevan el lote
						$pujadores = FgAsigl1::select("LICIT_ASIGL1")->where("SUB_ASIGL1", $info->codSub)->where("REF_ASIGL1", $info->ref)->where("LICIT_ASIGL1","!=", $info->licit)->groupby("LICIT_ASIGL1")->GET();
						foreach($pujadores as $pujador){
							$email->setUserByLicit($info->codSub, $pujador->licit_asigl1, true);
							$email->send_email();
						}

					}

				}

				#envio de notificacion de pago al propieario

				$lote = FgAsigl0::select("PROP_HCES1, IDORIGEN_HCES1, NUM_HCES1, LIN_HCES1")->JoinFghces1Asigl0()->where("sub_asigl0", $info->codSub)->where("ref_asigl0", $info->ref)->first();

				#id motorflash
				$idorigin = explode("-",$lote->idorigen_hces1);
				$idmotorflash = $idorigin[1];

				#MATRICULA
				$matriculaObj = FgCaracteristicas_Hces1::SELECT("VALUE_CARACTERISTICAS_HCES1")->where("NUMHCES_CARACTERISTICAS_HCES1", $lote->num_hces1)->where("LINHCES_CARACTERISTICAS_HCES1", $lote->lin_hces1)->where("IDCAR_CARACTERISTICAS_HCES1", 55)->first();
				if(!empty($matriculaObj)){
					$matricula = $matriculaObj->value_caracteristicas_hces1;
				}else{
					$matricula = "No disponible";
				}

				#tipo Venta
				if($info->tipoSub == "O"){
					$tipoVenta = "Subasta";
				}else{
					$tipoVenta =  "Venta";
				}

				#tipo Oferta
				if($info->tipoPuja == "C"){
					$tipoOferta = "Contra oferta";
				} elseif($info->tipoPuja == "Y"){
					$tipoOferta = "Comprar ya";
				}elseif($info->tipoPuja == "B"){
					$tipoOferta = "Compra";
				}else{
					$tipoOferta = "Adjudicación puja";
				}

				#fecha
					$fechaVenta=  date("d/m/Y H:i:s ", strtotime($info->fecha));

				#importes
				$importeReserva =ToolsServiceProvider::moneyFormat($info->impReserva,trans(\Config::get('app.theme').'-app.subastas.euros'),2);
				$importeRestante = ToolsServiceProvider::moneyFormat(floor($info->impTotal - $info->impReserva),trans(\Config::get('app.theme').'-app.subastas.euros'),2);
				$importeTotal = ToolsServiceProvider::moneyFormat($info->impTotal,trans(\Config::get('app.theme').'-app.subastas.euros'),2);

				#envio de notificacion de pago al propieario
				$email = new EmailLib('PAID_RESERVATION_OWNER');
				if (!empty($email->email)) {
					$lote = FgAsigl0::select("PROP_HCES1, IDORIGEN_HCES1, NUM_HCES1, LIN_HCES1")->JoinFghces1Asigl0()->where("sub_asigl0", $info->codSub)->where("ref_asigl0", $info->ref)->first();

					$email->setLot($info->codSub, $info->ref);
					#cargamos al cliente  pero no para que envie
					$email->setUserByCod($transaccion->cli_paycart, false);

					#Información del propietario
					$email->setPropInfo($info->codSub, $info->ref);

					#ponemos al propietario  del vehículo apra que reciba el email
					$email->addOwnerToReceiveMail( $info->codSub, $info->ref, true);

					# que carlandia reciba copia
					$email->setBcc(Config::get('app.admin_email'));

					#importe pagado, el de la reserva
					$email->setAtribute("IMPORTE_RESERVA", $importeReserva);
					$email->setAtribute("IMPORTE_RESTANTE", $importeRestante);
					$email->setAtribute("IMPORTE_TOTAL", $importeTotal );

					#id motorflash
					$email->setAtribute("ID_MOTORFLASH", $idmotorflash);

					#MATRICULA
					$email->setAtribute("MATRICULA", $matricula);

					#tipo Venta
					$email->setAtribute("TIPO_VENTA", $tipoVenta);

					#tipo Oferta
					$email->setAtribute("TIPO_OFERTA", $tipoOferta);

					#fecha
					$email->setAtribute("FECHA_VENTA", $fechaVenta);

					$email->send_email();
				}


				$email = new EmailLib('LEADS_INTERESADOS');
				if (!empty($email->email)) {

					#importe pagado, el de la reserva
					$email->setAtribute("IMPORTE_TOTAL", $importeTotal );
					/*
					$email->setAtribute("IMPORTE_RESTANTE", $importeRestante);
					$email->setAtribute("IMPORTE_RESERVA", $importeReserva);

					#tipo Venta
					$email->setAtribute("TIPO_VENTA", $tipoVenta);
					#tipo Oferta
					$email->setAtribute("TIPO_OFERTA", $tipoOferta);
					#fecha
					$email->setAtribute("FECHA_VENTA", $fechaVenta);

					*/
					#id motorflash
					$email->setAtribute("ID_MOTORFLASH", $idmotorflash);

					#MATRICULA
					$email->setAtribute("MATRICULA", $matricula);


					$subasta = new Subasta();
					$subasta->cod = $info->codSub;
					$subasta->lote = $info->ref;

					#cargamos los datos del vehículo
					$email->setLot($info->codSub, $info->ref);

					#Precio minimo de venta
					$minPrice = $info->tipoSub == 'V' ? $email->getAtribute("ESTIMACION_BAJA") : $email->getAtribute('RESERVE_PRICE');

					$email->setAtribute('MIN_PRICE', $minPrice);

					#Información del propietario
					$email->setPropInfo($info->codSub, $info->ref);

					#marcamos que el email se envia al propietario
					$email->addOwnerToReceiveMail( $info->codSub, $info->ref, true);


					$adjudicado = $subasta->get_csub(\Config('app.emp'));

					$pujas = FgAsigl1::select("COD_LICIT, COD_CLI, IMP_ASIGL1, FEC_ASIGL1, NOM_CLI,EMAIL_CLI, CIF_CLI,TEL1_CLI,DIR_CLI,CP_CLI,POB_CLI,PRO_CLI, PAIS_CLI, CIF_CLI")->JoinCli()->where("SUB_ASIGL1",$info->codSub)->where("REF_ASIGL1",$info->ref)->get();

					$pujasAux = FgAsigl1_Aux::select("COD_LICIT, COD_CLI, IMP_ASIGL1, FEC_ASIGL1, NOM_CLI,EMAIL_CLI, CIF_CLI,TEL1_CLI,DIR_CLI,CP_CLI,POB_CLI,PRO_CLI, PAIS_CLI, CIF_CLI")->JoinCli()->where("SUB_ASIGL1",$info->codSub)->where("REF_ASIGL1",$info->ref)->get();
					#deben ser arrays para que no falle
					$allPujas = array_merge($pujas->toArray(),$pujasAux->toArray() );

					$licitadores = array();
					//creamos un array con los pujadores no adjudicados del lote
					foreach ($allPujas as $get_value_pujas) {
						//si no ha ganado nadie o el que gano noes el pujador actual  y el licitador n oes el dummy
						if ((empty($adjudicado) ||  $adjudicado->licit_csub != $get_value_pujas["cod_licit"]) && (Config::get('app.dummy_bidder') != $get_value_pujas["cod_licit"])) {

							if(empty($licitadores[$get_value_pujas["cod_licit"]] )){
								$licitadores[$get_value_pujas["cod_licit"]] = $get_value_pujas;
							}else{
								#nosquedamos la puja mas alta, pueden venir pujas de pujas auxiliares o normales
								if($get_value_pujas["imp_asigl1"] > $licitadores[$get_value_pujas["cod_licit"]]["imp_asigl1"]  ){
									$licitadores[$get_value_pujas["cod_licit"]]["imp_asigl1"] = $get_value_pujas["imp_asigl1"];
								}
							}

						}
					}

					\Log::info("licitadores de la subasta", ['licits' => $licitadores]);

					if(count($licitadores)>0){
						$htmlLicitadores="";
						foreach($licitadores as $licitador){
							$htmlLicitadores.="<p><strong>".	$licitador["nom_cli"]."</strong></p>
							<ul>
								<li><b>Fecha:</b> ". date("d/m/Y H:i:s ", strtotime($licitador["fec_asigl1"])) ." </li>
								<li><b>Importe ofrecido:</b> ". ToolsServiceProvider::moneyFormat($licitador["imp_asigl1"]) ."€ </li>
								<li><b>Teléfono:</b> ". $licitador["tel1_cli"] ." </li>
								<li><b>Email:</b> ". mb_strtolower($licitador["email_cli"]) ." </li>
								<li><b>NIF:</b> ". $licitador["cif_cli"] ."</li>
								<li><b>Direccion:</b> ". $licitador["pob_cli"] ." (". $licitador["cp_cli"] .") ". $licitador["pais_cli"] ."</li>
							</ul>";
						}

						$email->setAtribute("DATOS_LICITADORES", $htmlLicitadores);
						$email->send_email();
					}
	}




			}else{
			if($respuesta=="9915"){
				\Log::error("El usuario ha cancelado el pago");
			}else{
				\Log::error("No se ha podido completar el pago correctamente, respuesta: $respuesta");
			}

		}


		}else{
			\Log::error("No concuerda la firma con los datos enviados en Redsys" );
		}
	}catch( \Exception $e ){
			\Log::error("Excepcion en pago con redsys \n" . $e);

		return;
	}

	}

	public function adjudicarLote($idTrans){
		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();
		$info = json_decode($transaccion->info_paycart);

		# Si es A, es que es uan puja auxiliar y por lo tanto debemos crear la puja y adjudicar el lote, en el otro caso ya esta creado ambos
		if($info->table == "A"){
			$bid = FgAsigl1_Aux::where("SUB_ASIGL1", $info->codSub)->where("REF_ASIGL1", $info->ref)->where("LIN_ASIGL1", $info->lin)->first();
			if(!empty($bid)){


				$subasta = new Subasta();
				$subasta->cli_licit = $transaccion->cli_paycart;

				#todos los lotes de la tienda son de la misma subasta por lo que podemos cogerla del primer lote
				$subasta->cod =  $info->codSub;
				$subasta->type_bid = $bid->pujrep_asigl1;

				//datos para hacer la puja
				$subasta->licit = $info->licit;
				$subasta->imp = $bid->imp_asigl1;
				$subasta->ref = $info->ref;

				//debe ir a true para que no compruebe que este cerrado
				$result = $subasta->addPuja(TRUE);

				\Log::info("adjudicando lote".$subasta->cod  ."   ". $subasta->ref );

				$a=DB::select("call CERRARLOTE(:subasta, :ref, :emp, :user_rp, :redondeo)",
				array(
					'subasta'    => $subasta->cod,
					'ref'        => $subasta->ref,
					'emp'        => Config::get('app.emp'),
					'user_rp'     => 'admin',
					'redondeo'     => 2
					)
				);
				#si es una puja de comprar ya de la online generara un correo de lote adjudicado, hay que evitarlo, por eso lo marco como vendido
				DB::select("update WEB_EMAIL_CLOSLOT set sended = 'S' where id_emp='" . Config::get('app.emp')."' and id_sub = '".$subasta->cod."' and id_ref='". $subasta->ref."' and sended='N' ");

			}else{
				\Log::info("no existe la puja de la transaccion".$idTrans);
				return ;
			}
		}
		#debemos asignar la adjudicación como pagada, lo haremos indicando el campo AFRAL_CSUB con valor  'L00', como si fuera una factura de texto
		FgCsub::where("SUB_CSUB", $info->codSub)->where("REF_CSUB", $info->ref)->update(["AFRAL_CSUB" => "L00"]);

	}


	public function aceptarContraoferta(){
		#sku =$ref-$imp-$licit
		$sku = explode("-",request("sku"));

		$ref = $sku[0];
		$lin =$sku[1];
		$licit = $sku[2];

		# LA SUBASTA SIEMPRE SERA MOTORV Y EL TIPO DE PUJA K
		$asigAux = FgAsigl1_Aux::where("SUB_ASIGL1", "MOTORV")->where("REF_ASIGL1", $ref)->where("PUJREP_ASIGL1", "K")->where("LICIT_ASIGL1", $licit)->where("LIN_ASIGL1", $lin)->first();

		#mensaje de que ya ha sido aceptada con anterioridad
		if(empty($asigAux)){
			$estado ="no_existe";
			return \View::make('front::pages.panel.contraofertaAceptada',compact( "estado"));
		}

		$lot = FgAsigl0::select("REF_ASIGL0, DESCWEB_HCES1, IMPTAS_ASIGL0, PROP_HCES1")->joinFghces1Asigl0()->where("SUB_ASIGL0","MOTORV")->where("REF_ASIGL0", $ref)->first();

		return \View::make('front::pages.panel.aceptarContraoferta',compact( "asigAux","lot"));


	}

	public function contraofertaAceptada(){
		try{
			$sku = explode("-",request("sku"));
			# LA SUBASTA SIEMPRE SERA MOTORV Y EL TIPO DE PUJA K
			$cod_sub = "MOTORV";
			$ref_asigl0 = $sku[0];
			$lin =$sku[1];
			$cod_licit = $sku[2];
			$aceptada = false;
			$estado ="";
			$asigAux = FgAsigl1_Aux::where("SUB_ASIGL1", $cod_sub)->where("REF_ASIGL1", $ref_asigl0)->where("PUJREP_ASIGL1", "K")->where("LICIT_ASIGL1", $cod_licit)->where("LIN_ASIGL1", $lin)->first();
			if(empty($asigAux)){
				$estado ="no_existe";
				return \View::make('front::pages.panel.contraofertaAceptada',compact( "estado"));
			}

			FgAsigl1_Aux::where("SUB_ASIGL1", $cod_sub)->where("REF_ASIGL1", $ref_asigl0)->where("LICIT_ASIGL1", $cod_licit)->where("LIN_ASIGL1", $lin)->update(["PUJREP_ASIGL1" => FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA_ACEPTADA]);

			$link = (new CarlandiaPayController())->getPayLink("MOTORV", $ref_asigl0, $cod_licit, $asigAux->lin_asigl1, 'A');

			//mail a usuario confirmando el envio de la contraoferta
			$counterOffer = $asigAux->imp_asigl1;
			$email = new EmailLib('COUNTEROFFER_LICIT');
			if(!empty($email->email)){
				$email->setUserByLicit($cod_sub, $cod_licit, true);
				$email->setLot($cod_sub, $ref_asigl0);
				$email->setAtribute('PRICE_COUNTEROFFER', ToolsServiceProvider::moneyFormat($counterOffer, trans(\Config::get('app.theme').'-app.subastas.euros'),2));
				$carlandiaCommission = \Config::get("app.carlandiaCommission");
				$impreserva = $counterOffer - ($counterOffer / (1 + $carlandiaCommission));
				$email->setAtribute("IMPORTE_RESERVA", ToolsServiceProvider::moneyFormat($impreserva,trans(\Config::get('app.theme').'-app.subastas.euros'),2));
				$email->setAtribute('PAY_LINK', $link);

				if(config('app.emailOwnerInformation', 0)){
					$email->setPropInfo($cod_sub, $ref_asigl0);
				}
				$email->send_email();

			}
			$estado ="aceptada";
			return \View::make('front::pages.panel.contraofertaAceptada',compact( "estado"));
		}
		catch(exception $e){
			return \View::make('front::pages.panel.contraofertaAceptada',["estado" => "aceptada" ]);
		}
	}

	public function getCounterOffers(Request $request)
	{
		//Si existe o no session, se controla en el middleware. Si llegamos aquí, tenemos sesion.
		$cod_cli = session('user.cod');

		$values = FgAsigl1_Aux::getPujasAuxiliares($cod_cli, [FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA, FgAsigl1_Aux::PUJREP_ASIGL1_CONTRAOFERTA_RECHAZADA]);
		$seo = (object)['noindex_follow' => true];

		$data = compact('values', 'seo');

        return View::make('front::pages.panel.counteroffers', compact('data'));
	}

	public function preAwards(Request $request)
	{
		$cod_cli = session('user.cod');

		$values = FgAsigl1_Aux::getPujasAuxiliares($cod_cli, [FgAsigl1_Aux::PUJREP_ASIGL1_COMPRAR_ONLINE, FgAsigl1_Aux::PUJREP_ASIGL1_COMPRAR_VD]);
		$seo = (object)['noindex_follow' => true];

		$data = compact('values', 'seo');

        return View::make('front::pages.panel.preawards', compact('data'));
	}



}
