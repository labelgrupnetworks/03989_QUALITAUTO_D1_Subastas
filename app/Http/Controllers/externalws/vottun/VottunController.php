<?php
namespace App\Http\Controllers\externalws\vottun;

use Request;
use Exception;
use SoapFault;
use SimpleXMLElement;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7;
use App\libs\EmailLib;
use App\Models\V5\FgNft;
use App\Models\V5\FgNftTransHist;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;
use App\Models\V5\FxCli;
use App\Models\V5\FgCaracteristicas_Hces1;

use stdClass;
/* VALORES DE LOS ESTADOS
			1  REQUESTED
			2  POSTED
			3  PROCESSING
			4  CONFIRMED
			5  ERROR
		*/

		/* wallets de prueba */
		/*
		Vendedor
		{
			"publickey": "0x50dc51a0b3D57f27dd8652b66812c184764ec2bD",
			"privatekey": "2ee5c0f33c2a1d871078487da0d2c7d0bb1721f5b0a9e581f6d5620d4085936b"
		}

		Comprador
		{
			"publickey": "0x8e2dC0de77ab7cF61f9Ae72a18B157e46826509e",
			"privatekey": "69122e04041d01d958e4dac254650ee4a6515d85e07508c4a7b557da315467a6"
		}




		*/

class VottunController extends Controller
{
	private $ipfs = "";  //"https://uatapi.vottun.tech/ipfs/v1/";
	private $nft = "" ; //"https://uatapi.vottun.tech/nft/v2/";
	private $pow = ""; //"https://uatapi.vottun.com/pow/v2/";
	private $appId = ""; // "qa3j5nULTtoX_2PTjot5YA5MKdJCs1Nlq9LJWWyvmba37Wj8FyV1oyriQV4Podpq";
	private $accesstoken = ""; //"eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIyNzQ3SFVKamtkbXRnUk43bUNkYWRLaXRKam4iLCJ0eXBlIjoiZXJwIiwidXNlcm5hbWUiOiJyc2FuY2hlekBsYWJlbGdydXAuY29tIiwiY2lkIjo0LCJza3UiOlt7InIiOjEsInMiOjEsImUiOjF9XX0.BOUzw8ixUfEf6vj511Ed_ikU6O1HlGtQURiWQ9lxT4GPwm1zJhdh0ZfOUV4dHLYO9VjXAldIlDRMt7zcUZLGq-nC7TxvFEWMQ9Z4w9Xffc7CG8kTG6LxX3L6VIFMRJpk_XJReZ8g7Jbqq5iQyc2rYKWIjDCTjTUo7v2kUWAP7yJphdgOal1Wl7UkvmXaUQWimTQzCD6I-0OyPHRgU5XTsIIAiTfmSSfJT9PyPnzUqCDLQ5kVDMamdgYHCbcDq6BatpMXLiMc4MXz9sUa8wdflHHY0jcx8jNmvzher3pSonFDS_9ctcHfCXKiB-WCthXhvBRJFjx5p9tr71PGu-drA4OT8f1BjLR_WEHV4yPpIcd3m8dcwz5XcQQo4_eywrEdtsJV2318Ihux7aCHozq7diW7tsI3rqP4byTAfxM_cM25XzPYntI3MnNj9mDg5i2hH0KTSCZQvSbt20LQFNOIHR-7jb8t6KWsJnN3mI-QHvzmSL1f0qxFlsxa1gmG-KZ_-GIvn0Tnn7gNy2lU-RSsNGMUm9w9jF9Ooy4JcaYZCAD2rjKQxm3x77PYB6m_XeIjP7DRrWLjksDfZfQ1Md64ZDpfW0IRNxMhYomHrk8P9o8ei3Kvpi4mP2rLZ-D_UE2trKyZP8uUh-oZrhddaCeCYpMi6EqHC4O2j5xodTLcpiw",

	private $status = ["1" => "REQUESTED" , "2" => "POSTED" , "3" => "PROCESSING" , "4" => "CONFIRMED" , "5" => "ERROR"  ];

		public function __construct()
		{
			$this->ipfs = \Config('app.urlIpfsVottun') ;
			$this->nft = \Config('app.urlNftVottun') ;
			$this->pow = \Config('app.urlPowVottun') ;
			$this->appId = \Config('app.appIdVottun') ;
			#está en config/app.php no en webconfig
			$this->accesstoken = \Config('app.accesstoken') ;

		}

		public function VottumRequest($type, $method   ,$parameters, $function){

		#segun el tipo la url es distinta
			if($type == "nft"){
				$url = $this->nft;
			}elseif($type == "ipfs"){
				$url = $this->ipfs;
			}elseif($type == "pow"){
				$url = $this->pow;
			}

			$clientGuzz = new Client();

			if($method == "GET"){

				$headers = [
						'Accept' => '*/*',
						'x-application-vkn' => $this->appId,
						'authorization' =>"Bearer ". $this->accesstoken,
					];

					$response = $clientGuzz->request("GET", $url . $function,[
						'headers' => $headers,
						'verify' => false,
						'query' =>$parameters
						]);

			}elseif($method == "POST"){

				$headers = [
						'Content-Type' => 'application/json',
						'Accept' => '*/*',
						'x-application-vkn' => $this->appId,
						'authorization' =>"Bearer ". $this->accesstoken,
					];

				$response = $clientGuzz->request("POST", $url . $function,[
						'headers' => $headers,
						'verify' => false,
						'body' => json_encode($parameters),
						]);
			#envio URL
			}elseif($method == "POST_URL" || $method == "PUT_URL"){

				$meth="POST";
				if($method == "PUT_URL"){
					$meth="PUT";
				}

				$headers = [
						'Content-Type' => 'application/x-www-form-urlencoded',
						'Accept' => '*/*',
						'x-application-vkn' => $this->appId,
						'authorization' =>"Bearer ". $this->accesstoken,
					];

				$response = $clientGuzz->request($meth, $url . $function,[
						'headers' => $headers,
						'verify' => false,
						'form_params' => $parameters,
						]);
			#envio de archivos
			}elseif($method == "POST_FORM-DATA"){
				$boundary= 'my_custom_boundary';
				$headers = [
						'Content-Type' => 'multipart/form-data;boundary='.$boundary,
						'Accept' => '*/*',
						'x-application-vkn' => $this->appId,
						'authorization' =>"Bearer ". $this->accesstoken,
					];

				$multipart_form =[
						[
							'name' => 'filename',
							'contents' => $parameters["filename"],
						],
						[
							'name' => 'file',
							'contents' =>$parameters["file"],
						],
					];

				$response = $clientGuzz->request("POST", $url . $function,[
						'headers' => $headers,
						'verify' => false,
						'body' => new Psr7\MultipartStream($multipart_form, $boundary),
						]);
			}




			\Log::info("funcion: $function");
			\Log::info( "parameters:". json_encode($parameters,JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE));
			\Log::info("body response:". json_encode(json_decode($response->getBody()),JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE));
			\Log::info("response statuscode:". $response->getStatusCode());

				return json_decode($response->getBody());
		}

		/* AHORA SOLO DEBERÁ TRANSFERIR, LA OBRÁ DEBERA ESTAR MINTEADA ANTES DE PUBLICAR */
		/******  Proceso de publicacion , minteo y transferencia  automático despues del pago  ******/
		/*
		public function mintPostPay($merchantID ,$sub, $ref){
			#cogemos el identificador del pago y creamos otro igual pero con la letra M para poder identificar todas las obras asociadas a un mismo pago
			#ademas usaremos este código para el pago del minteo
			$merchantID = "M".substr($merchantID,1) ;
			$lot = FgAsigl0::JoinNFT()->select("ES_NFT_ASIGL0 ,NUMHCES_ASIGL0, LINHCES_ASIGL0, HASHFILE_NFT, HASHMETADATA_NFT")->where("sub_asigl0", $sub)->where("ref_asigl0", $ref)->first();

			#GUARDAMOS EL IDENTIFICADOR DEL PAGO PARA PODER AGRUPARLOS A TODOS LOS LOTES Y PODER USARLO DESPUES PARA PAGAR
			FgNft::where("NUMHCES_NFT", $lot->numhces_asigl0)->where("LINHCES_NFT", $lot->linhces_asigl0)->update(["ID_PAYMENT_NFT" =>$merchantID] );


			$mintear = false;
			#si es un lote nft
			if($lot && $lot->es_nft_asigl0 == 'S'){
				# si no está publicado
				if(empty($lot->hashfile_nft)){
					$response = $this->uploadFile($lot->numhces_asigl0, $lot->linhces_asigl0);
					if($response->status == "success"){
						$response = $this->uploadMetadata($lot->numhces_asigl0, $lot->linhces_asigl0);
						if($response->status == "success"){
							#activamos mintear por que ya está publicado el lote
							$mintear = true;
						}
					}
				}else{
					#activamos mintear por que ya estaba publicado el lote
					$mintear = true;
				}

				if($mintear){
					$this->mint($lot->numhces_asigl0, $lot->linhces_asigl0);
				}else{
					$this->sendEmailError("No se ha podido mintear el lote " . $lot->numhces_asigl0 + " " +$lot->linhces_asigl0 );

				}

			}
		}
	*/
		/******  FIN Proceso de publicacion y minteo automático despues del pago  ******/
		#Sube el archivo a vottun y guarda el hash del archivo en bbdd
		public function uploadFile($num, $lin) {
			try{
				$fgnft = new FgNft();
				$nft = $fgnft->where("numhces_nft", $num)->where("linhces_nft", $lin)->first();
				if(empty($nft->path_nft)){
					return $this->responseError("noFile");
				}
				$path = storage_path("app/$nft->path_nft");

				$file = Psr7\Utils::tryFopen($path, 'r');
				$filenameArray = explode("/", $nft->path_nft);
				$filename = array_pop($filenameArray);

				$res = $this->vottunUploadFile($file, $filename);
				FgNft::where("numhces_nft", $num)->where("linhces_nft", $lin)->update(["hashfile_nft" => $res->hash]);

				return $this->responseSuccess("uploadFile");

			}catch (Exception $e){
				\Log::info($e);
				return $this->responseError($e->getMessage(), true);
			}
		}


		#Sube los metadatos y los asocia a la imagen
		public function uploadMetadata($num, $lin){
			try{
				$asigl0 = new FgAsigl0();
	#
				$nft = $asigl0->select('FGNFT.*,SUB_ASIGL0,REF_ASIGL0,NUM_HCES1, LIN_HCES1, "id_auc_sessions","name",WEBFRIEND_HCES1,DESCWEB_HCES1')->JoinNFT()->JoinFghces1Asigl0()->JoinSessionAsigl0()->where("numhces_nft", $num)->where("linhces_nft", $lin)->first();
				$url_friendly = \Tools::url_lot($nft->sub_asigl0,$nft->id_auc_sessions,$nft->name,$nft->ref_asigl0,$nft->numhces_nft,$nft->webfriend_hces1,$nft->descweb_hces1);

				$data = new StdClass();

				$data->artist = $nft->artista_nft;
				$data->created = date("d/m/Y" ,strtotime($nft->created_nft));
				$data->year = date("Y" ,strtotime($nft->created_nft));
				$data->mediaType = $nft->media_type_nft;
				if(!empty($nft->total_tokens_nft) && !empty($nft->n_of_token_nft) ){
					$data->nftTotal = $nft->total_tokens_nft;
					$data->numberOfToken = $nft->n_of_token_nft;
				}

				$res = $this->vottunMetadata($nft->name_nft,$nft->hashfile_nft, $nft->description_nft, $url_friendly, $data);
				FgNft::where("numhces_nft", $num)->where("linhces_nft", $lin)->update(["hashmetadata_nft" => $res->IpfsHash]);
				return $this->responseSuccess("uploadMetadata");

			}catch (Exception $e){
				\Log::info($e);
				return $this->responseError($e->getMessage(), true);
			}
		}


		#mintea el NFT, previamente tiene que estar subido a vottun, guardamos el identificador del minteo
		public function mint($num, $lin){
			try{
				$asigl0 = new FgAsigl0();
	#
				$nft = $asigl0->select('FGNFT.*,PROP_HCES1')->JoinNFT()->JoinFghces1Asigl0()->where("numhces_asigl0", $num)->where("linhces_asigl0", $lin)->first();
				$ipfsUri = $nft->hashmetadata_nft;

				if (empty($ipfsUri)){
					return $this->responseError("noMetaToMint");
				}

				#ultima posición de "/" +1 para que no coja la "/"
				$pos = strrpos($ipfsUri,"/") +1;
				#desde la ultima aparicion de "/" hasta el final, osea el hash que necesitamos
				$ipfsHash = substr($ipfsUri, $pos);

				$prop = FxCli::select("WALLET_CLI, COD_CLI")->where("cod_cli", $nft->prop_hces1)->first();
				if(empty($prop) || empty($prop->wallet_cli)){
					return $this->responseError("propNoWallet");
				}

				$res = $this->vottunMint($prop->wallet_cli, $ipfsUri, $ipfsHash, $nft->network_nft);
				FgNft::where("numhces_nft", $num)->where("linhces_nft", $lin)->update(["mint_id_nft" => $res->id]);
				FgNftTransHist::insert(["emp_nftth" => \Config::get("app.emp"),
										"numhces_nftth" => $num,
										"linhces_nftth" => $lin,
										"key_nftth" => $res->id,
										"type_nftth" => "MINT"]);

				return $this->responseSuccess("mint");

			}catch (Exception $e){
				\Log::info($e);
				return $this->responseError($e->getMessage(), true);
			}


		}

		public function requestHistorictransactions($num, $lin){
			$info = [];
			$transactions = FgNftTransHist::where("NUMHCES_NFTTH", $num)->where("LINHCES_NFTTH", $lin)->orderby("DATE_NFTTH", "DESC")->get();
			foreach($transactions as $transaction){
				$operation = $this->vottunGetOpperation($transaction->key_nftth) ;
				$transaction->infoTransfer =  $this->vottunGetTransaction($operation->transactionHash, $operation->networkId);
				$info[] =$transaction->toArray();
			}

			return $info;
		}

		public function transferNFT($num, $lin){

			#recuperamos el transactionhash de los datos de la operación de minteo
			$operation = $this->getMintOpperation($num, $lin);
			# si ha fallado la carga de la operacio nde minteo
			if (empty($operation)  || empty($operation->networkId) || empty($operation->statusId)){

				return $this->responseError("failGetMintTransaction");
			}
			#si todo ha ido bien devolverá un 4 y seguiremos adelante
			if ($operation->statusId !=4){
				if ($operation->statusId ==5){
					return $this->responseError("Mint failed: ". $operation->errorDescription,true);
				}else{
					return $this->responseError("Mint is not Confirmed: actual status is $operation->statusId -".$this->status[$operation->statusId] ,true);
				}
			}


			#recuperamos la transaccion de minteo
			$transaction = $this->getTransaction($operation->transactionHash, $operation->networkId);

			#si ha fallado la carga de la transaccion de minteo
			if(empty($transaction) || empty($transaction->transaction) || empty($transaction->receipt)  || empty($transaction->receipt->to) ){
				return $this->responseError("failGetMintTransaction");
			}

			if( $transaction->transaction->pending == true){
				return $this->responseInfo("mintTransactionIsPending");
			}

			#en una transaccion de minteo el propietario será el to del receipt, en lso datos de transaccion encontramos el from que será el propietario del smart contract y el to es el contrato

			$walletProp = $transaction->receipt->to;

			if(empty($transaction->receipt) ){
				return $this->responseError("transaction");
			}elseif($transaction->receipt->status !=1){
				return $this->responseError($transaction->receipt->errorMessage, true);
			}elseif(!is_int($transaction->receipt->tokenId)){
				return $this->responseError("getTokenId");
			}

			$tokenId = $transaction->receipt->tokenId;



			$csub = new FgCsub();
			$adjudicacion = $csub->select('HIMP_CSUB, WALLET_CLI')->JoinCli()->JoinAsigl0()->where("numhces_asigl0", $num)->where("linhces_asigl0", $lin)->first();

			if(empty($adjudicacion)){
				return $this->responseError("lotNotSelled");
			}

			if (!empty($adjudicacion->wallet_cli)){
				$buyerWallet = $adjudicacion->wallet_cli;

			}else{
				#harcodeamos el wallet para permitir pujar sin indicar el wallet , esto puede provocar que el usuario n otenga wallet
				$buyerWallet = "0xE552B25eEcA5c10b3100982cD661312D3c3BA09e";
				#return $this->responseError("buyerNoWallet");
			}


			$network = $operation->networkId;
			$from = $walletProp;
			$to = $buyerWallet ;
			$price = $adjudicacion->himp_csub;

			$res = $this->vottunTransferNft($from, $to, $tokenId, $price, $network );
			FgNft::where("numhces_nft", $num)->where("linhces_nft", $lin)->update(["transfer_id_nft" => $res->id]);
			FgNftTransHist::insert(["emp_nftth" => \Config::get("app.emp"),
			"numhces_nftth" => $num,
			"linhces_nftth" => $lin,
			"key_nftth" => $res->id,
			"type_nftth" => "TRANSFER"]);
			return $this->responseSuccess("NFT transfer ",true);


		}
		public function getTransferOpperation($num, $lin){
			try{
				$asigl0 = new FgAsigl0();
	#
				$nft = $asigl0->select('FGNFT.*')->JoinNFT()->where("numhces_asigl0", $num)->where("linhces_asigl0", $lin)->first();

				if (empty($nft) ||empty($nft->transfer_id_nft) ){
					return null;
				}

				return $this->vottunGetOpperation($nft->transfer_id_nft);

			}catch (Exception $e){
				\Log::info($e);
				return null;
			}
		}

		public function getMintOpperation($num, $lin){
			try{
				$asigl0 = new FgAsigl0();
	#
				$nft = $asigl0->select('FGNFT.*')->JoinNFT()->where("numhces_asigl0", $num)->where("linhces_asigl0", $lin)->first();

				if (empty($nft) ||empty($nft->mint_id_nft) ){
					return null;
				}

				return $this->vottunGetOpperation($nft->mint_id_nft);

			}catch (Exception $e){
				\Log::info($e);
				return null;
			}
		}

		public function getTransaction($transactionHash, $network){
			try{
				$res = $this->vottunGetTransaction($transactionHash, $network);
				return $res;
			}catch (Exception $e){
				\Log::info($e);
				return false;
			}
		}

		#vottun llamara a esta función cuando haya finalizado algun evento
		#url de pruebas http://www.newsubastas.test/prueba?operation=mint&status[status]=4&networkId=4&operationId=7de69f65-f697-40bd-b7bc-fddaaa6b515b
		#url de pruebas http://www.newsubastas.test/prueba?operation=transfer&status[status]=4&networkId=4&operationId=b8e4f247-34eb-4c04-9599-0263b2fe7a21
		public function webhook(){
			$all = request()->all();

			#SI ES MINTEO
			if(count($all) >0 &&  $all["operation"] == "mint"  ){

				#VALOR 4 SIGNIFICA QUE HA IDO CORRECTO
				if($all["status"]["status"] == 4){
					#SI EL MINTEO SE HA REALIZADO CORRECTAMENTE SE DEBERÁ COMPROBAR SI ESTA DENTRO DE UNA RED QUE SE LE DEBA COBRAR AL PROPIETARIO
					$network = $all["networkId"];

					$payNetworks = explode("," , str_replace(" ","", \Config::get("app.nftPayNetwork")) );
					$costMint = 0;
					# Si pertenece a una network que se deba cobrar
					if(in_array($network, $payNetworks)){
						#indicamos que está pendiente el pago del minteo
						$payMintNft = "P";

						$vottunController = new VottunController();
						#enviar email para el pago del minteo

						$operationmint = $vottunController->vottunGetOpperation($all["operationId"]) ;
						if(!empty($operationmint) ){

							$mintTransaction =  $vottunController->vottunGetTransaction($operationmint->transactionHash, $operationmint->networkId);
							if(!empty($mintTransaction) && !empty($mintTransaction->transaction)  && !empty($mintTransaction->transaction->gas)){
								$lot = FgAsigl0::JoinFghces1Asigl0()->JoinNFT()->select("SUB_ASIGL0, REF_ASIGL0, PROP_HCES1 ")->where("MINT_ID_NFT", $all["operationId"])->first();

										$link = Route("mintNftPayUrl", ["operationId" =>$all["operationId"]]);
										#VOTTUM DEBE INDICAR EL PRECIO DEL MINTEO, DE MONMENTO PONGO EL CAMPO GAS Y LO DIVIDO ENTRE 100.000 PARA QUE NO SEA TAN GRANDE
										$price = $mintTransaction->transaction->gas/100000;

										#sumamos la comision de Vottun
										$costMint =  $price + ($price * \Config::get("app.VottunComission") /100);

										#notificar al propietario que debe pagar el minteo
										$email = new EmailLib('MINT_PAY_OWNER');
										if(!empty($email->email)){
											$email->setUserByCod($lot->prop_hces1);
											$email->setLot($lot->sub_asigl0,$lot->ref_asigl0);
											$email->setPrice(\Tools::moneyFormat($costMint,"€",2));
											$email->setUrl($link);

											$email->send_email();
										}

										//informPendingPaid($operationId, $type)
										# LLAMADA A WEBSERVICE DE DURAN INDICANDO QUE EL AUTOR TIENE PENDIENTE UN PAGO DEL MINTEO Wbcrearpagonft
										#Notificar a casas de subastas por webservice que hay pendiente de pagar un minteo
										if(Config::get('app.WebServicePaidtransactionNft')){

											$theme  = Config::get('app.theme');
											$rutaPendingOperitionPaid = "App\Http\Controllers\\externalws\\$theme\PendingOperitionPaid";

											$pendingOperitionPaid = new $rutaPendingOperitionPaid();
											$pendingOperitionPaid->informPaid($all["operationId"],"mint");
										}


							}
						}



					}else{
						#indicamos que no será necesario el pago del minteo
						$payMintNft = "N";
						#PENDIENTE DE APROBAR:- SI LA RED NO ES DE PAGO , SE LLAMARA A GENERAR  EL PAGO createMintPay($operationId) Y SE MARCARÁ COMO PAGADO Y SE AVISARÁ A DURAN QUE  HAY UN PAGO PENDIENTE PendingOperitionPaid, AUNQUE LUEGO NO SE PAGUE
					}
					#actualizamos el valor de Pay_mint para indicar el coste (solo si es de pago) y si esta pendiente o no será necesario el cobro
					FgNft::where("MINT_ID_NFT", $all["operationId"])->update(["PAY_MINT_NFT" => $payMintNft, "COST_MINT_NFT" => $costMint]);

				}else{
					$this->sendEmailError("error en Webhook, el mintado no ha se ha realizado correctamente errorMessage:". $all["status"]["errorMessage"]);

				}
			}elseif(count($all) >0 &&  $all["operation"] == "transfer"){
				#VALOR 4 SIGNIFICA QUE HA IDO CORRECTO
				if($all["status"]["status"] == 4){
					#SI la transferencia SE HA REALIZADO CORRECTAMENTE SE DEBERÁ COMPROBAR SI ESTA DENTRO DE UNA RED QUE SE LE DEBA COBRAR AL PROPIETARIO
					$network = $all["networkId"];

					$payNetworks = explode("," , str_replace(" ","", \Config::get("app.nftPayNetwork")) );

					# Si pertenece a una network que se deba cobrar
					if(in_array($network, $payNetworks)){
						#indicamos que está pendiente el pago de la transferencia


						$vottunController = new VottunController();
						#enviar email para el pago de LA TRANSFERENCIA SOLO SI ESTAN TODAS LAS OBRAS TRANSFERIDAS

						$operationTransfer = $vottunController->vottunGetOpperation($all["operationId"]) ;
						if(!empty($operationTransfer) ){

							$transferTransaction =  $vottunController->vottunGetTransaction($operationTransfer->transactionHash, $operationTransfer->networkId);
							if(!empty($transferTransaction) && !empty($transferTransaction->transaction)  && !empty($transferTransaction->transaction->gas)){

								#VOTTUM DEBE INDICAR EL PRECIO DEL MINTEO, DE MONMENTO PONGO EL CAMPO GAS Y LO DIVIDO ENTRE 100.000 PARA QUE NO SEA TAN GRANDE
								$price = $transferTransaction->transaction->gas/100000;

								#sumamos la comision de Vottun
								$costTransfer =  $price + ($price * \Config::get("app.VottunComission") /100);

								#actualizamos el valor de PAY_TRANSFER para indicar el coste  y que esta pendiente del cobro
								FgNft::where("TRANSFER_ID_NFT", $all["operationId"])->update(["PAY_TRANSFER_NFT" => "P", "COST_TRANSFER_NFT" => $costTransfer]);

								# LLAMADA A WEBSERVICE DE DURAN INDICANDO QUE EL comprador TIENE PENDIENTE UN PAGO de la transferencia Wbcrearpagonft
								#Notificar a casas de subastas por webservice que hay pendiente de pagar una transferencia
								if(Config::get('app.WebServicePaidtransactionNft')){

									$theme  = Config::get('app.theme');
									$rutaPendingOperitionPaid = "App\Http\Controllers\\externalws\\$theme\PendingOperitionPaid";

									$pendingOperitionPaid = new $rutaPendingOperitionPaid();
									$pendingOperitionPaid->informPaid($all["operationId"],"transfer");
								}



								#Recuperamos el id del comprador para ver si tiene más transferencias pendientes
								$buyer = FgAsigl0::JoinCSubAsigl0()->JoinNFT()->
								select("CLIFAC_CSUB")->
								#debemos poner el not null ya que JoinCSubAsigl0 hace un left join, si el lote ha estado en mas de una subasta puede devolver datos vacios de csub
								wherenotnull("CLIFAC_CSUB") ->
								where("TRANSFER_ID_NFT", $all["operationId"])->
								first();

								$transfers =array();
								if(!empty($buyer)){
									#buscamos todas las transferencias pendientes de pago o pendientes de finalizar que pertenezcan a las network de pago.
									$transfers = FgAsigl0::JoinFghces1Asigl0()->JoinCSubAsigl0()->JoinNFT()->
									select("DESCWEB_HCES1, TRANSFER_ID_NFT,COST_TRANSFER_NFT, PAY_TRANSFER_NFT ")->
									where("CLIFAC_CSUB",$buyer->clifac_csub)->
									#networks de pago, si no son de pago no se deberá cobrar
									wherein("NETWORK_NFT", explode("," , str_replace(" ","", \Config::get("app.nftPayNetwork")) ))->
									#que el lote se haya solicitado la transferencia
									whereNotNull("TRANSFER_ID_NFT")->
									#si es nulo es que no se ha transferido y si es P es que esta pendiente de transferir
									whereRaw("(PAY_TRANSFER_NFT is NULL or PAY_TRANSFER_NFT = 'P')")->

									get();

								}
								#si hay transferecnias ponemos por defecto en true, y si hay algun registro que no se ha transferido cancelamos
								$enviar = count($transfers) > 1;

								$total = 0;
								$transfersIds = array();
								$lots_name = "<table>";
								foreach($transfers as $transfer){
									$total+=$transfer->cost_transfer_nft;
									$transfersIds[]=$transfer->transfer_id_nft;
									$lots_name .="<tr><td> * ".$transfer->descweb_hces1."<td><td style='text-align:right'> ". \Tools::moneyFormat($transfer->cost_transfer_nft,"€",2) ."</td></tr>";
									#si hay alguna que no ha finalizado cancelamos el envio
									if( empty($transfer->pay_transfer_nft)){
										$enviar = false;
									}


								}
								$lots_name .= "</table>";
								if($enviar){
									$link = Route("transferNftPayUrl", ["operationId" => implode("_",$transfersIds)]);
									$email = new EmailLib('TRANSFERNFT_PAY_BUYER');
									if(!empty($email->email)){
										$email->setUserByCod($buyer->clifac_csub);
										#falta indicar los lotes
										$email->setAtribute("LOTS_NAME",$lots_name );
										$email->setPrice(\Tools::moneyFormat($total,"€",2));
										$email->setUrl($link);

										$email->send_email();
									}
								}
							}
						}


					}else{
						#indicamos que no será necesario el pago de la transferencia
						FgNft::where("TRANSFER_ID_NFT", $all["operationId"])->update(["PAY_TRANSFER_NFT" => "N", "COST_TRANSFER_NFT" => 0]);
							#PENDIENTE DE APROBAR:- SI LA RED NO ES DE PAGO , SE LLAMARA A GENERAR  EL PAGO createMintPay($operationId) Y SE MARCARÁ COMO PAGADO Y SE AVISARÁ A DURAN QUE  HAY UN PAGO PENDIENTE PendingOperitionPaid, AUNQUE LUEGO NO SE PAGUE

					}

				}else{
					$this->sendEmailError("error en Webhook, el mintado no ha se ha realizado correctamente errorMessage:". $all["status"]["errorMessage"]);

				}
			}


			//echo "hola " .print_r($all);
			\Log::info("webhook funcvionando".print_r($all, true) );


			/*
			INFORMACION QUE ENVIARÁ VOTTUN sobre el minteo
			(
				[operationId] => 155635de-28b2-4d37-aa9b-ffbb9e30b5f2
				[networkId] => 43113
				[appId] => 4
				[contractAddress] => 0xF93f3a2936e14eD8ED9C05f0fAd0ac515FAD19A0
				[tokenId] => 4
				[txHash] => 0x9455dc2f13fe07e28bbea0a041825d3dc6ebedbee1c63dd5e39669e1d9c9a60e
				[operation] => mint
				[to] => 0x50dc51a0b3D57f27dd8652b66812c184764ec2bD
				[from] => 0xEC6fc3dc4607dA2d945DFa8ab0391Aa9FEDa60E9
				[status] => Array
					(
						[status] => 4
						[errorMessage] =>
					)

			)

			INFORMACIÓN QUE ENVIARÁ VOTTUN SOBRE LA TRANSFERENCIA
			(
				[operationId] => b040c87d-7772-460a-aafb-7efb9484db6d
				[networkId] => 43113
				[appId] => 4
				[contractAddress] => 0xF93f3a2936e14eD8ED9C05f0fAd0ac515FAD19A0
				[tokenId] => 4
				[txHash] => 0x1377475563cf6a66d7f3429e9f83a18c37a7e4027060ca7161ef1531465f587c
				[operation] => transfer
				[to] => 0x8e2dC0de77ab7cF61f9Ae72a18B157e46826509e
				[from] => 0x50dc51a0b3D57f27dd8652b66812c184764ec2bD
				[status] => Array
					(
						[status] => 4
						[errorMessage] =>
					)

			)
			*/

		}

		#calcular el coste del minteo
		#Debe saber cuando estan todos los lotes transferidos y calcularlo entonces
		public function calcMintCost($num, $lin) {

			$lot = FgNft::where("NUMHCES_NFT", $num)->where("LINHCES_NFT", $lin)->first();
			if(!empty($lot)){
				#seleccionamos todos los lotes que pertenecen al mismo pago
				$lots = FgNft::where("ID_PAYMENT_NFT", $lot->id_payment_nft)->get();
				$transferidos = true;
				foreach($lots as $lot){
					echo "<br>comprobando transferencia num:" .$lot->numhces_nft. " lin:".$lot->linhces_nft;
					#si uno de los lotes no ha sido transferido marcamos a false
					if (empty($lot->transfer_id_nft)){
						$transferidos = false;
						echo "<br>falta transferencia";
					}
				}

				if($transferidos){
					echo "<br>calcular coste";
					$coste = 0;
					foreach($lots as $lot){
						#extrar informacion de transaccion  para obtenmer los precios
						$operationmint = $this->vottunGetOpperation($lot->mint_id_nft) ;
						$mintTransaction=  $this->vottunGetTransaction($operationmint->transactionHash, $operationmint->networkId);

						$operationtransfer = $this->vottunGetOpperation($lot->transfer_id_nft) ;

						$transferTransaction=  $this->vottunGetTransaction($operationtransfer->transactionHash, $operationtransfer->networkId);




						if(!empty($mintTransaction) && !empty($transferTransaction)){
							$coste += $mintTransaction->transaction->gas;
							echo "<br> Coste mint: ". $coste;
							$coste += $transferTransaction->transaction->gas;
							echo "<br> Coste trans: ". $coste;

						}
					}
				}
			}


		}




		/* FUNCIONES CONTRA LA RED DE VOTTUN */

		#sube el archivo a vottun y devuelve el hash
		public function vottunUploadFile($file, $filename){

			$type="ipfs";
			$function = "file/upload";

			$method= "POST_FORM-DATA";
			$parameters = [

				'file' => $file,
				'filename' => $filename
			];
			\Log::info(print_r($parameters, true));
			return $this->VottumRequest($type, $method   ,$parameters, $function);

			#respuesta
			/*
				{
					"hash": "https:\/\/gateway.pinata.cloud\/ipfs\/QmahWHGFLhdddZvNhM3NhQXqFXnVe365XzikfgAhv4qdEp"
				}
			*/
		}

		public function vottunMetadata($name,$image, $description, $external_url, $data){

			$parameters = array(
				"name" => $name,
				"image" => $image,
				"description" => $description,
				"external_url" =>$external_url,
				"data" => $data
			);



			$type="ipfs";
			$function = "file/metadata";
			$method= "POST";
			return $this->VottumRequest($type, $method   ,$parameters, $function);

			#respuesta
			/*
				{
					"IpfsHash": "https:\/\/gateway.pinata.cloud\/ipfs\/QmcPDYEa5tUSQ91FADj3R23TmwPGTWXULKhbxjsSSHcYbU",
					"PinSize": 333,
					"Timestamp": "2022-05-02T13:27:14.443Z"
				}
			*/
		}

		public function vottunMint($recipientAddress, $ipfsUri, $ipfsHash, $blockchainNetwork ){



			$type="nft";
			$function = "vottun/mint";
			$method= "POST";
			$parameters = array(
				"recipientAddress" => $recipientAddress,
				"ipfsUri" => $ipfsUri,
				"ipfsHash" =>$ipfsHash ,
				"blockchainNetwork" => (integer)$blockchainNetwork,

			);
			return $this->VottumRequest($type, $method   ,$parameters, $function);

			#respuesta
			/*
				{
					"id": "36a4199e-9a49-4a88-8687-eae3a08f9f7b",
					"network": "Avalanche Fuji"
				}
			*/
		}
		#permitre consultar las operaciones llevadas a cabo en la blockchain, como por ejemplo el minteo
		public function vottunGetOpperation($operationId){
			$type="nft";
			#no se pasan variables, si no el id por la url
			$function = "operation/".$operationId;
			$method= "GET";
			$parameters = [];
			return $this->VottumRequest($type, $method   ,$parameters, $function);

			#respuesta
			/*
				{
					"operationId": "873ddbc9-5d98-4be1-9a39-99f258486670",
					"networkId": 43113,
					"transactionHash": "0x75e9036859ef8417799dcd171f8586c95a5ea1c687c2411fd8999572181da57a",
					"statusId": 3,
					"errorDescription": "",
					"transactionTimestamp": "2022-05-04T15:21:50Z"
				}
			*/
		}

		#el idOperación es necesario para identificar la transaccion, el network es necesario por que el id de transaccion puede llegar a repetirse segun la network
		public function vottunGetTransaction($transactionHash, $network){
			$type="nft";
			$function = "vottun/transaction/".$transactionHash;

			$method= "GET";
			$parameters = [];
			$parameters["network"] = $network ; #43113

			return $this->VottumRequest($type, $method   ,$parameters, $function);

			#respuesta
			/*
				{
					"network": 43113,
					"transaction": {
						"hash": "0xd5926ce124b0e158222bba184d811c71506ae7af18752d8b6e71f1cfeeba7a5b",
						"value": "0",
						"gas": 3000000,
						"gasPrice": 25000000000,
						"nonce": 138,
						"to": "0x1478135E49Db09c62F56Fbea6008b14eAa56F55B",
						"from": "0x72d0CF5Bab8Abdf72E114C3FE869A94033fb3bC7",
						"pending": false
					},
					"receipt": {
						"hash": "0xd5926ce124b0e158222bba184d811c71506ae7af18752d8b6e71f1cfeeba7a5b",
						"blockHash": "0x9850aa49a6b3666b23bf6ac466157331a460d6140bdfade78c33ca4d8365672a",
						"gasUsed": 222573,
						"cumulativeGasUsed": 341732,
						"tokenId": 23,
						"ownerAddress": "0x50dc51a0b3d57f27dd8652b66812c184764ec2bd",
						"status": 1,
						"errorMessage": ""
					},
					"error": false,
					"errorInfo": {
						"code": "",
						"message": ""
					}
				}
			*/
		}

		#Precio del gas
		public function vottunGetGasPrice($network){
			$type="nft";
			$function = "vottun/gasprice";

			$method= "GET";
			$parameters = [];
			$parameters["network"] = $network ; #43113

			return $this->VottumRequest($type, $method   ,$parameters, $function);

		}


		public function vottunTransferNft($from, $to, $tokenId, $price, $network ){
			$type="nft";
			$function = "vottun/transfer";
			$method= "POST";
			$parameters = [
				"from" => $from,
				"to"	=> $to,
				"tokenId" => $tokenId,
				"price"	=> (integer)$price,
				"blockchainNetwork"	=> (integer)$network
			];

			return $this->VottumRequest($type, $method   ,$parameters, $function);

			#respuesta
			/*
				{
					"id": "36a4199e-9a49-4a88-8687-eae3a08f9f7b",
					"network": "Avalanche Fuji"
				}
			*/
		}

		public function vottunPowTicket( ){
			$type="pow";
			$function = "ticket";
			$method= "GET";
			$parameters = [
			];

			return $this->VottumRequest($type, $method   ,$parameters, $function);

			#respuesta
			/*
			{
				"walletRequestTicket": "HjgLoqlkjpQhpen_cK7mMdYwrClZbNzx9v2lsDJDwG2mrIggQxebVCv7O-c0Ewgp3wByf72PGa_a4Er9cLmMZA=="
			}
			*/
		}

		public function vottunCreateWebhook(){
			$type="nft";
			$function = "config/webhook";
			$method= "POST_URL";
			$parameters =["url"=> Route("webhookvottun")];
			return $this->VottumRequest($type, $method   ,$parameters, $function);
		}

		public function vottunUpdateWebhook(){
			$type="nft";
			$function = "config/webhook";
			$method= "PUT_URL";
			$parameters =["url"=> Route("webhookvottun")];
			return $this->VottumRequest($type, $method   ,$parameters, $function);

		}

		public function vottunGetWebhook(){
			$type="nft";
			$function = "config/webhook";
			$method= "GET";
			$parameters =[];
			return $this->VottumRequest($type, $method   ,$parameters, $function);

		}

		public function vottunTestWebhook(){
			$type="nft";
			$function = "config/webhook/test";
			$method= "GET";
			$parameters =[];
			return $this->VottumRequest($type, $method   ,$parameters, $function);
		}



		public function vottunNetworks( ){
			$type="nft";
			$function = "networks";
			$method= "GET";
			$parameters = [
			];

			return $this->VottumRequest($type, $method   ,$parameters, $function);

		}

		/* FIN FUNCIONES CONTRA LA RED DE VOTTUN */

		private function responseSuccess($successDescription, $freeMessage = false){
			return $this-> response("success", $successDescription, $freeMessage );
		}

		private function responseError($errorDescription, $freeMessage = false ){
			return $this-> response("error", $errorDescription, $freeMessage  );
		}

		private function responseInfo($successDescription, $freeMessage = false){
			return $this-> response("info", $successDescription, $freeMessage );
		}


		private function response($status, $message = "",$freeMessage = false ) {
			$response =new Stdclass();
			$response->status = $status;

			if ($freeMessage && !empty($message)){
				$response->message = $message;
			}elseif(!empty($message)){
				if($status == "error"){
					$response->message = trans('admin-app.vottun.errors.'.$message) ;
				}elseif($status == "success"){
					$response->message = trans('admin-app.vottun.success.'.$message) ;
				}elseif($status == "info"){
					$response->message = trans('admin-app.vottun.info.'.$message) ;
				}

			}

			return $response;
		}

		public function sendEmailError($message){

			#si estamos fuera del circuito de pruebas, se envia el emails
			if(!env('APP_DEBUG')){
				$email = new EmailLib('VOTTUN_ERROR');
				if(!empty($email->email)){
					#Email que recibe el correo de alerta
					$to = \Config::get("app.debug_to_email");
					$email->setTo($to);
					#Emails que recibiran copia del error
					if(!empty(\Config::get("app.emailVottunError"))){
						$bcc = explode(",",\Config::get("app.emailVottunError") );
						foreach($bcc as $bcc_email){
							$email->setBcc($bcc_email);
						}
					}


					$email->setAtribute("MESSAGE", $message);

					$email->send_email();
					#lo comento para que se guarden siempre los logs
					//return;
				}
			}
			# si estamos en pruebas, lo escribimos solo en log
			\Log::error($message);


		}


			#vOTTUN A MONTADO UN WEBHOOK AL QUE LLAMA AL FINALIZAR EL MINTEO, POR LO QUE YA NO ES NECESARIA ESTA FUNCIÓN
	/*
		#consulta el estado del minteo y actualiza en base de datos si el resultado es que ya está minteado
		public function requestStateMint($num, $lin){

			#recuperamos el transactionhash de los datos de la operación de minteo
			$operation = $this->getMintOpperation($num, $lin);
			# si ha fallado la carga de la operacionde minteo
			if (empty($operation)  || empty($operation->networkId) || empty($operation->statusId)){

				return $this->responseError("failGetMintTransaction");
			}
			#si todo ha ido bien devolverá un 4 y seguiremos adelante
			if ($operation->statusId !=4){
				if ($operation->statusId ==5){
					return $this->responseError("Mint failed: ". $operation->errorDescription,true);
				}else{
					return $this->responseInfo("Mint is not Confirmed: actual status is $operation->statusId -".$this->status[$operation->statusId] ,true);
				}
			}

			#recuperamos la transaccion de minteo
			$transaction = $this->getTransaction($operation->transactionHash, $operation->networkId);

			#si ha fallado la carga de la transaccion de minteo
			if(empty($transaction) || empty($transaction->transaction) ){
				return $this->responseError("failGetMintTransaction");
			}

			if( $transaction->transaction->pending == true){
				return $this->responseInfo("mintTransactionIsPending");
			}

			if(empty($transaction->receipt) ){
				return $this->responseError("transaction");
			}elseif($transaction->receipt->status !=1){
				return $this->responseError($transaction->receipt->errorMessage, true);
			}elseif(!is_int($transaction->receipt->tokenId)){
				return $this->responseError("getTokenId");
			}

			FgNft::where("numhces_nft", $num)->where("linhces_nft", $lin)->update(["token_id_nft" => $transaction->receipt->tokenId]);

			return $this->responseSuccess("NFT minted ",true);
		}

		#consulta el estado de la transferencia
		public function requestStateTransfer($num, $lin){

			#recuperamos el transactionhash de los datos de la operación de minteo
			$operation = $this->getTransferOpperation($num, $lin);
			# si ha fallado la carga de la operacionde transferencia
			if (empty($operation)){
				return $this->responseError("notTransaction");
			}
			if ( empty($operation->networkId) || empty($operation->statusId)){

				return $this->responseError("failGetTransferTransaction");
			}
			#si todo ha ido bien devolverá un 4 y seguiremos adelante
			if ($operation->statusId !=4){
				if ($operation->statusId ==5){
					return $this->responseError("Transfer failed: ". $operation->errorDescription,true);
				}else{
					return $this->responseInfo("Transfer is not Confirmed: actual status is $operation->statusId -".$this->status[$operation->statusId] ,true);
				}
			}

			#recuperamos la transaccion de la transferencia
			$transaction = $this->getTransaction($operation->transactionHash, $operation->networkId);

			#si ha fallado la carga de la transaccion de transferencia
			if(empty($transaction) || empty($transaction->transaction) ){
				return $this->responseError("failGetTransferTransaction");
			}

			if( $transaction->transaction->pending == true){
				return $this->responseInfo("transferTransactionIsPending");
			}

			if(empty($transaction->receipt) ){
				return $this->responseError("transaction");
			}elseif($transaction->receipt->status !=1){
				return $this->responseError($transaction->receipt->errorMessage, true);
			}elseif(!is_int($transaction->receipt->tokenId)){
				return $this->responseError("getTokenId");
			}
			return $this->responseSuccess("NFT transfered ",true);
		}
*/


}
