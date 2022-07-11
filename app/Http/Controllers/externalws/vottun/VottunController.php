<?php
namespace App\Http\Controllers\externalws\vottun;

use Request;
use Exception;
use SoapFault;
use SimpleXMLElement;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7;

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
			\Log::info( json_encode(json_decode($response->getBody()),JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE));

				return json_decode($response->getBody());
		}


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
			if (empty($adjudicacion->wallet_cli)){
				return $this->responseError("buyerNoWallet");
			}


			$network = $operation->networkId;
			$from = $walletProp;
			$to = $adjudicacion->wallet_cli;
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


}
