<?php

namespace App\Http\Controllers\V5;

use Config;
use Session;
use View;
use App\Models\V5\FxCli;
use App\Models\V5\FgCsub;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgDeposito;
use App\Models\V5\WebPayCart;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;

# Cargamos el modelo
use App\Models\Page;
use App\Providers\ToolsServiceProvider;

class DepositController extends Controller
{
/*

S430….STN
A430… Alaplana
K430… Keratile
D430….KTL
W430… Vitacer
M430…Moncofar tiles

*/

	public function createPayment(){

		$res = array(
			"status" => "error",
			"msgError" => "generic"
		);


		$codCli = Session::get('user.cod');
		$codSub = request("codSub");
		$ref= request("ref");
		ToolsServiceProvider::exit404IfEmpty($codCli);

		$paymethod= request("paymethod","creditcard");

		$lot = FgAsigl0::select("IMPSALHCES_ASIGL0")->where("SUB_ASIGL0", $codSub)->where("REF_ASIGL0", $ref)->first();
		$depositPct = Config::get("app.depositPct");
		#generamos la información a guardar.
		$inf = new \stdClass();
		$inf->paymethod=$paymethod;
		$inf->comments = request("comments","");
		$inf->type = "deposit";

		$impDeposit = round($lot->impsalhces_asigl0 * ($depositPct / 100), 2);



		$inf->cod_sub = $codSub;
		$inf->ref = $ref;
		$inf->precio_salida = $lot->impsalhces_asigl0;
		$inf->deposito = $impDeposit;



		$inf->total= $impDeposit;

		$webpayCart["CLI_PAYCART"] = $codCli;
		$webpayCart["EMP_PAYCART"] = \Config::get("app.emp");

		#CREAMOS EL ID DE LA TRANSACCION, LA LETRA QUE IDENTIFICARÁ LOS PAGOS DE Depositos es la D
		$webpayCart["IDTRANS_PAYCART"] = "D" . rand(1, 9) . time();
		$webpayCart["DATE_PAYCART"] = date("Y-m-d H:i:s");
		$webpayCart["INFO_PAYCART"] = json_encode($inf) ;
		WebPayCart::insert($webpayCart);
		$parameters=["idTrans" => $webpayCart["IDTRANS_PAYCART"] ];

		if (Config::get('app.paymentRedsys')) {
			if(!empty($paymethod)){
				$parameters["paymethod"]= $paymethod;
			}

			$url =Route("depositCallRedsys",$parameters) ;

			$res = array(
				"status" => "success",
				"location" => $url
			);
		}

		return $res;
	}


	#Carga el formulariode redsys
	public function callRedsys(){

		$paymentcontroller = new PaymentsController();
		$idTrans = request("idTrans");
		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();

		if(empty($transaccion)){
			exit (\View::make('front::errors.404'));
		}
		$info = json_decode($transaccion->info_paycart);
		\Log::info("Dentro de llamada a redsys");

		$multiRedsys = null ;
		#url normal para el tpv
		$merchantURL ='/gateway/pagoDirectoReturn';
		if( Config::get('app.multiredsysRsoc')){

			$cli = FxCli::select("RSOC_CLI")->where("cod_cli", $transaccion->cli_paycart)->first();
			#nos indica que número de digitos debemos coger del principio del Rsoc para identificar la empresa a facturar
			$numCars =Config::get('app.multiredsysRsoc');
			$multiRedsys = substr($cli->rsoc_cli,0,$numCars);
			#url multi tpv
			$merchantURL = "/response_redsys_multi_tpv/".$multiRedsys;
		}
		#preautorización
		$operacion = 1;
		$urlOk =Route("returnPayPageDeposit",["lang"=> Config::get("app.locale"), "codSub"=>$info->cod_sub,"ref"=>$info->ref]);
		$urlKo =Config::get('app.url')."/es/pagina/pago-cancelado-deposito";

		$varsRedsys = $paymentcontroller->requestRedsys($info->total, $idTrans,$merchantURL,$operacion, $multiRedsys,$urlOk, $urlKo);

		#reenviamos al formulario
		return \View::make('front::pages.panel.RedsysForm', $varsRedsys);
	}
	#Desde el cron controller se llamará cuando haya finalizado el lote online,
	#se valida el pago del usuario ganador y se desestiman
	public function confirmPreAuthorization($codSub,$ref){
		$paymentcontroller = new PaymentsController();

		$ganador = FgCsub::select("CLIFAC_CSUB")->where("sub_csub", $codSub)->where("ref_csub", $ref)->first();
		$depositos = FgDeposito::select("RSOC_CLI, COD_DEPOSITO, IMPORTE_DEPOSITO, CLI_DEPOSITO")->JoinCli()->where("sub_deposito", $codSub)->where("ref_deposito", $ref)->get();
		$url ='/gateway/pagoDirectoReturn';
		#si es multi TPVnos indica que número de digitos debemos coger del principio del Rsoc para identificar la empresa a facturar
		$numCars =Config::get('app.multiredsysRsoc');

		foreach($depositos as $deposito){
			if( $numCars){

				$multiRedsys = substr($deposito->rsoc_cli,0,$numCars);
				#url multi tpv
				$url = "response_redsys_multi_tpv/".$multiRedsys;
				if(!empty($ganador) && $ganador->clifac_csub == $deposito->cli_deposito){
					#confirmamos la preautorización
					$operacion = 2;
				}else{
					#cancelamos la preautorización
					$operacion = 9;
				}



				$varsRedsys = $paymentcontroller->requestRedsys($deposito->importe_deposito, $deposito->cod_deposito,$url,$operacion, $multiRedsys);

				#lanzamos con redys la preautorizacion ya sea para confirmarla o para cancelarla
				if($paymentcontroller->restRedsys ($varsRedsys)){
					#SI SE HA REALIZADO LA ACCION LO MARCAMOS COMO PAGADO (ENVIO A "P")
					if($operacion==2){
						$enviado = "P";
					}else{
						#SI SE HA REALIZADO LA ACCION LO MARCAMOS COMO CANCELADO (ENVIO A "C")
						$enviado = "C";
					}
				}else{
					#SI SE HA FALLADO LA ACCION LO MARCAMOS COMO ERROR (ENVIO A "E")
					$enviado = "E";
					\Log::info("error en confirmación o cancelacion del deposito  operacion: $operacion");
				}

				FgDeposito::where("sub_deposito", $codSub)->where("ref_deposito", $ref)->where("cli_deposito", $deposito->cli_deposito)->update(["ENVIADO_DEPOSITO" => $enviado ,"FECHAENVIO_DEPOSITO" => date("Y-m-d H:i:s")]);

			//	return \View::make('front::pages.panel.RedsysForm', $varsRedsys);

			}

		}

		#recuperar todos los depositos
		#recuperar ganador del lote
		#recorrer todos los depositos y autorizar  el del ganador y cancelar los perdedores
	}

	public function returnPay($idTrans){

		\Log::info("Dentro de Return Pay de depositos $idTrans");

		$transaccion = WebPayCart::where("IDTRANS_PAYCART", $idTrans)->first();
		if(empty($transaccion)){
			\Log::info("Error en pasarela de pago de depositos  $idTrans no se encuentra en base de datos ");
			return;
		}

		#MARCAMOS EL PEDIDO COMO PAGAGO
		WebPayCart::where("IDTRANS_PAYCART", $idTrans)->update(["PAID_PAYCART" => "S"]);

		$info = json_decode($transaccion->info_paycart);
		#quitamos la D para ponerle el nuemro 4 de esta manera el código de depósito coincide con el de redsys y podemos extraerlo en el  excel de ventas para que puedan relacionar el deposito con el pago en redsys
		$codDeposito ="4".substr($idTrans,1);

			#hacemos el deposito al usuario
			$deposito = [
				"cod_deposito" =>$codDeposito ,
				"sub_deposito" => $info->cod_sub,
				"ref_deposito"	=> $info->ref,
				"estado_deposito"	=> FgDeposito::ESTADO_VALIDO,
				"importe_deposito"	=> $info->deposito,
				"fecha_deposito"	=> date("Y-m-d H:i:s"),
				"cli_deposito"	=> $transaccion->cli_paycart
			];
			FgDeposito::create($deposito);



	}

	public function returnPayPageDeposit()
	{

		$codSub = request("codSub");
		$ref =  request("ref");
		$lang = request("lang");
/*
		if ( (!empty(request('result')) && request('result') == 'success')) {
			$keyWebPage = "pago-realizado-deposito";
			#Cargamos la vista de pago
		} else {
			$keyWebPage = "pago-cancelado-deposito";
		}
*/

		$keyWebPage = "pago-realizado-deposito";

		$lote = FgAsigl0::select('cod_sub,"id_auc_sessions", ref_asigl0, num_hces1, webfriend_hces1, titulo_hces1')
			->JoinFghces1Asigl0()
			->JoinSubastaAsigl0()
			->JoinSessionAsigl0()
			->where("sub_asigl0", $codSub )
			->where("ref_asigl0", $ref)
			->first();

			$url = ToolsServiceProvider::url_lot($lote->cod_sub,$lote->id_auc_sessions,"",$lote->ref_asigl0,$lote->num_hces1,$lote->webfriend_hces1,$lote->titulo_hces1);


			#cargamos la página de deposito
			$pagina = new Page();

			$data  = $pagina->getPagina($lang,$keyWebPage);
				if(empty( $data )) {
					exit (\View::make('front::errors.404'));
				}

			# Asignamos
				//$data->name = $data->title.' - '.Config::get('app.name');

				$SEO_metas= new \stdClass();
				if(!empty($data->webnoindex_web_page) && $data->webnoindex_web_page == 1){
					$SEO_metas->noindex_follow = true;
				}else{
					$SEO_metas->noindex_follow = false;
				}


				$SEO_metas->meta_title = $data->webmetat_web_page;
				$SEO_metas->meta_description = $data->webmetad_web_page;
				#ponemos la url del lote
				$data->content_web_page =str_replace("[*URL*]",$url,$data->content_web_page) ;


					$data = array(
					'data' => $data,
					'seo' => $SEO_metas ,
					'lang' => $lang
				);

				return View::make('front::pages.page', array('data' => $data));

	}




}
