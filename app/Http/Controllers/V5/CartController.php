<?php
namespace App\Http\Controllers\V5;

use Redirect;
use Config;
use Response;
use View;
use Route;
use Cookie;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FxClid;
use App\Models\User;
use App\Http\Controllers\PaymentsController;

class CartController extends Controller
{
	var $shoppingCart = array();
	var $errorMsg = "add_lot_cart";
	#lo usaremos para ver si se han borrado lotes
	var $deletedLots = false;




	public function addLot(){

		$this->loadCart();
		#ponemos por defecto una unidad del producto ya que no hay stock y no pasaremos de momento la variable units
		$success = $this->addLotCart(request("codSub"), request("ref"), request("units", 1));
		$this->saveCart();
		return	$this->response($success);
	}

	public function deleteLot( ){

		$this->loadCart();

		$success = $this->deleteLotCart( request("codSub") ,request("ref") );
		$this->saveCart();
		return $this->response($success);
	}



	private function loadCart(){
		$cookieName= "shoppingCart".Session::get('user.cod');

		$shoppingCart = json_decode( Cookie::get($cookieName),true );

		$fgasigl0 = new FgAsigl0();
		$lots =array();
		if(!empty($shoppingCart)){
			#cogemos los lotes de las subastas enviadas
			foreach($shoppingCart as $cod_sub => $lots){
				$fgasigl0 = $fgasigl0->orWhere(function($query) use  ($cod_sub, $lots) {
					$query->where("sub_asigl0", $cod_sub)
					->whereIn("ref_asigl0", array_keys($lots));
				});

			}
			#certificamos que no se carguen lotes cerrados
			$lots = $fgasigl0->select("SUB_ASIGL0, REF_ASIGL0")->where("CERRADO_ASIGL0","N")->where("OCULTO_ASIGL0","N")->where("RETIRADO_ASIGL0","N")->get();
			foreach($lots as $key => $lot){
				$this->shoppingCart[$lot->sub_asigl0][$lot->ref_asigl0] = $shoppingCart[$lot->sub_asigl0][$lot->ref_asigl0];

			}
		}

	}

	public function saveCart(){
		$cookieName= "shoppingCart".Session::get('user.cod');
		Cookie::queue( $cookieName,  json_encode($this->shoppingCart), 60 * 24 * 7 );
	}

	private function addLotCart($codSub, $ref, $units){

		if(!empty($codSub) && !empty($ref) && !empty($units)  ){
			#comprobamos que no esté reservado
			$check = $this->checkReservedLot($codSub,$ref);

			if($check){
				#lo reservamos en base de datos, con el checkReservedLot ya se ha reservado en webservice
				$this->reserveLot($codSub,$ref);
				#lo añadimos al carrito
				$this->shoppingCart[$codSub][$ref] = $units;
				return true;
			}



		}

		return false;


	}
	public function deleteLotCart($codSub, $ref){
		\Log::info("dentro de borrar lote de carrito $codSub, $ref");
		if(!empty($codSub) && !empty($ref) ){
			$this->deleteReserveLot($codSub,$ref);
			$this->deleteReserveWebService(Session::get('user.cod'),$ref);
			unset($this->shoppingCart[$codSub][$ref]) ;
			return true;
		}else{
			return false;
		}

	}

	private function response($success = true){

		$response = array("status" => $success? "success" :  "error" ,
						 "shoppingCart" => $this->shoppingCart,
						 "errorMsg" =>  $this->errorMsg
						);


		#se debe lanzar el response para que la cookie se grabe
		response();
		return $response;

	}

	public function loadLotsCart(){
		$this->loadCart();

		$fgasigl0 = new FgAsigl0();
		$lots =array();
		if(!empty($this->shoppingCart)){
			#cogemos los lotes de las subastas enviadas
			foreach($this->shoppingCart as $cod_sub => $lots){
				$fgasigl0 = $fgasigl0->orWhere(function($query) use  ($cod_sub, $lots) {
					$query->where("sub_asigl0", $cod_sub)
					->whereIn("ref_asigl0", array_keys($lots));
				});

			}
			$fgasigl0 = $fgasigl0->JoinFghces1Asigl0()->JoinFgOrtsec1Asigl0();

			$lots = $fgasigl0->select(" SUB_ASIGL0, REF_ASIGL0, IMPSALHCES_ASIGL0,COML_HCES1 , DESCWEB_HCES1, NUM_HCES1, LIN_HCES1, PESO_HCES1, PESOVOL_HCES1, SEC_HCES1, LIN_ORTSEC1, PERMISOEXP_HCES1")->get();

			if(\Config::get("app.TaxForEuropean")){
				foreach($lots as $lot){
					$lot->impSinIva = $lot->impsalhces_asigl0;
					$lot->iva = \Tools::PriceWithTaxForEuropean(1,\Session::get('user.cod'));
					$lot->impsalhces_asigl0 = \Tools::PriceWithTaxForEuropean($lot->impsalhces_asigl0,\Session::get('user.cod'));

				}
			}
		}


		return $lots;

	}



	public function showShoppingCart(){


		$user_cod =Session::get('user.cod');



		$lots  = $this->loadLotsCart();

		#si usan las tablas de WEB_GASTOS_ENVIO cargaremos las direcciones


		$fxClid = new FxClid();
		$data['address'] = $fxClid->getForSelectHTML($user_cod);



		#IVA aplicable
		$data["ivaAplicable"] =  $this->ivaAplicable();



		#gastos envio
		$paymentcontroller = new PaymentsController();
		$codd_clid = !empty($data['address']) ? head(array_keys($data['address'])) : null;
		$gastosEnvio = $paymentcontroller->calc_web_gastos_envio ($lots,$codd_clid);

		$data["gastosEnvio"] = $gastosEnvio + round($gastosEnvio * $data["ivaAplicable"],2);
		$data["lots"] = $lots;
		#Calculos lotes seguros
		$total_lotes = 0;
		foreach($lots as $lot){
			$total_lotes +=  $lot->impsalhces_asigl0;
		}

		$porcentajeSeguro =  \Config::get("app.porcentaje_seguro_envio");
		$seguro = round($total_lotes * $porcentajeSeguro / 100,2);
		$tax_seguro =  round($seguro * $data["ivaAplicable"],2 );
		$data["totalSeguro"] = round($seguro + $tax_seguro,2);
		$data["totalLotes"] = $total_lotes;


		$seo = new \Stdclass();
		$seo->noindex_follow = true;
		$data['seo']=$seo;
		return View::make('front::pages.panel.shoppingCart', $data);
	}

	public function shippingCostsCart(){
		$lots = $this->loadLotsCart();
		$codd_clid = request("clidd_carrito");

		$paymentcontroller = new PaymentsController();
		$gastosEnvio = $paymentcontroller->calc_web_gastos_envio ($lots,$codd_clid);




		if($gastosEnvio > -1){
			$iva = $this->ivaAplicable();
			$gastosEnvio = $gastosEnvio + ($gastosEnvio * $iva);
			return array( "status" => "success",
			"costs" =>$gastosEnvio );
		}else{

			return array( "status" => "error",
			"costs" =>$gastosEnvio
			 );
		}


	}
	# devuelve el iva que se va a aplicar, por ejemplo 0.21
	public function ivaAplicable(){
		$emp  = Config::get('app.emp');
        $gemp  = Config::get('app.gemp');
		$user_cod =Session::get('user.cod');
		#IVA aplicable
		$pago_controller = new PaymentsController();
		$iva = $pago_controller->getIva($emp,date ("Y-m-d"));
		if(!empty($user_cod)){
			$tipo_iva_user = $pago_controller->user_has_Iva($gemp,$user_cod);
			$tipo_iva = $tipo_iva_user->tipo;
		}else{
			$tipo_iva = 1;
		}
		return $pago_controller->calculate_iva($tipo_iva,$iva,1);
	}


#reservar un lote
public function reserveLot($codSub,$ref){
	#si hay que bloquear el lote para reservar
	if(\Config::get("app.reserveLot")){
		\Log::info("reservando lote");
		$update['FFIN_ASIGL0'] = date ( 'Y-m-d H:i:s' , strtotime ( '+'.\Config::get("app.reserveLot").' minute' )); #strtotime ( '+'.\Config::get("app.reserveLot").' minute' )
		$update['USRDESADJU_ASIGL0'] = Session::get('user.cod');
		FgAsigl0::where("SUB_ASIGL0", $codSub)->where("REF_ASIGL0", $ref)->update($update);




	}
}

#eliminar reserva de un lote
public function deleteReserveLot($codSub,$ref){
	#si hay que bloquear el lote para reservar
	if(\Config::get("app.reserveLot")){
		\Log::info("eliminando reserva de lote");
		$update['FFIN_ASIGL0'] = null;
		$update['USRDESADJU_ASIGL0'] = null;
		FgAsigl0::where("SUB_ASIGL0", $codSub)->where("REF_ASIGL0", $ref)->update($update);


	}
}


public function deleteReserveWebService($codCli,$ref){
	#borrar reserva con webservice
	if(\Config::get("app.WebServiceReservation")){
		$theme  = Config::get('app.theme');
		$rutaReservationController = "App\Http\Controllers\\externalws\\$theme\ReservationController";
		$ReservationController = new $rutaReservationController();
		 $ReservationController->deleteReservation($codCli, [$ref]);
	}
}
#chequea si se puede resrvar el lote, si se usa el web service el checkeo tambien lo reserva
public function checkReservedLot($codSub,$ref){

	if(! Session::has('user')){
		$this->errorMsg ="session_end" ;
		return false;
	}
	#si hay que bloquear el lote para reservar
	if(\Config::get("app.reserveLot")){
		


		#confirmar reserva con webservice
		if(\Config::get("app.WebServiceReservation")){
			\Log::info("comprobando webservice reserva de lote");
			$theme  = Config::get('app.theme');
			$rutaReservationController = "App\Http\Controllers\\externalws\\$theme\ReservationController";

			$ReservationController = new $rutaReservationController();

			$res = $ReservationController->createReservation(Session::get('user.cod'), [$ref]);

			#si no se ha podido reservar, devolvemos false
			if(empty($res) || $res->resultado !='0'){
				$this->errorMsg ="lotReserved" ;
				return false;
			}

		}

		#De momento nadie tiene reserva de lotes sin web service
		#si no usan reserva con webservice, lo miramos en nuestras tablas el lote aun está reservado, y no es por este usuario
		/*
			$lot = FgAsigl0::select("FFIN_ASIGL0,USRDESADJU_ASIGL0 ")->where("SUB_ASIGL0", $codSub)->where("REF_ASIGL0", $ref)->first();

			if(!empty($lot) && strtotime($lot->ffin_asigl0) > time() && $lot->usrdesadju_asigl0 != Session::get('user.cod')  ){

				$this->errorMsg ="lotReserved" ;
				return false;
			}
		*/

	}

	return true;

}

#comprobamos los lotes antes de pagar
public function checkToBuy(){
	$this->loadCart();
	$ok = true;
	if(! Session::has('user')){
		$this->errorMsg ="session_end" ;
		return false;
	}
	#si hay que bloquear el lote para reservar
	if(\Config::get("app.reserveLot")){


		$codSub=7500;

		#confirmar reserva con webservice
		if(\Config::get("app.WebServiceReservation")){
			\Log::info("comprobando webservice reserva de lote");
			$theme  = Config::get('app.theme');
			$rutaReservationController = "App\Http\Controllers\\externalws\\$theme\ReservationController";

			$ReservationController = new $rutaReservationController();

			$res = $ReservationController->createReservation(Session::get('user.cod'), array_keys($this->shoppingCart[$codSub]));

			#si no se ha podido reservar, devolvemos false
			if(!empty($res) && $res->resultado !='0'){
				$rechazados = array();
				if(is_array($res->rechazados->rechazado)){
					$rechazados = $res->rechazados->rechazado;
				}else{
					$rechazados[] = $res->rechazados->rechazado;
				}

				foreach($res->rechazados->rechazado as $lote){
					$ref = intval($lote);
					unset($this->shoppingCart[$codSub][$ref]) ;
				}
				$this->saveCart();
				response();
				$ok =false;
			}else{
				#si se han podido reservar, lo marcamos en base de datos para ampliar los tiempos
				foreach(array_keys($this->shoppingCart[$codSub]) as $ref){
					$this->reserveLot($codSub,$ref);
				}

			}

		}
	}

	return $ok;
}




}



