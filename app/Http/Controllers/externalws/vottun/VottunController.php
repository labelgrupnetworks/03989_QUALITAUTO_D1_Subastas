<?php
namespace App\Http\Controllers\externalws\vottun;

use Request;
use Exception;
use SoapFault;
use SimpleXMLElement;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7;
class VottunController extends Controller
{
	private $ipfs ="https://uatapi.vottun.tech/ipfs/v1/";
	private $nft = "https://uatapi.vottun.tech/nft/v2/";
	private $appId = "qa3j5nULTtoX_2PTjot5YA5MKdJCs1Nlq9LJWWyvmba37Wj8FyV1oyriQV4Podpq";
	private $accesstoken = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIyNzQ3SFVKamtkbXRnUk43bUNkYWRLaXRKam4iLCJ0eXBlIjoiZXJwIiwidXNlcm5hbWUiOiJyc2FuY2hlekBsYWJlbGdydXAuY29tIiwiY2lkIjo0LCJza3UiOlt7InIiOjEsInMiOjEsImUiOjF9XX0.BOUzw8ixUfEf6vj511Ed_ikU6O1HlGtQURiWQ9lxT4GPwm1zJhdh0ZfOUV4dHLYO9VjXAldIlDRMt7zcUZLGq-nC7TxvFEWMQ9Z4w9Xffc7CG8kTG6LxX3L6VIFMRJpk_XJReZ8g7Jbqq5iQyc2rYKWIjDCTjTUo7v2kUWAP7yJphdgOal1Wl7UkvmXaUQWimTQzCD6I-0OyPHRgU5XTsIIAiTfmSSfJT9PyPnzUqCDLQ5kVDMamdgYHCbcDq6BatpMXLiMc4MXz9sUa8wdflHHY0jcx8jNmvzher3pSonFDS_9ctcHfCXKiB-WCthXhvBRJFjx5p9tr71PGu-drA4OT8f1BjLR_WEHV4yPpIcd3m8dcwz5XcQQo4_eywrEdtsJV2318Ihux7aCHozq7diW7tsI3rqP4byTAfxM_cM25XzPYntI3MnNj9mDg5i2hH0KTSCZQvSbt20LQFNOIHR-7jb8t6KWsJnN3mI-QHvzmSL1f0qxFlsxa1gmG-KZ_-GIvn0Tnn7gNy2lU-RSsNGMUm9w9jF9Ooy4JcaYZCAD2rjKQxm3x77PYB6m_XeIjP7DRrWLjksDfZfQ1Md64ZDpfW0IRNxMhYomHrk8P9o8ei3Kvpi4mP2rLZ-D_UE2trKyZP8uUh-oZrhddaCeCYpMi6EqHC4O2j5xodTLcpiw";


		public function VottumRequest($type, $method   ,$parameters, $function){




		#segun el tipo la url es distinta
			if($type == "nft"){
				$url = $this->nft;
			}else{
				$url = $this->ipfs;
			}



			$clientGuzz = new Client();

			if($method == "GET"){

				$headers = [
					#	'Content-Type' => 'application/json',
						'Accept' => '*/*',
						'x-application-vkn' => $this->appId,
						'authorization' =>"Bearer ". $this->accesstoken,
					];

					$response = $clientGuzz->request($method, $url . $function,[
						'headers' => $headers,
						'verify' => false,
						'query' =>$parameters
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




				echo "<pre>";
				echo json_encode(json_decode($response->getBody()),JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE);

				return $response;
		}

		#el idOperación es necesario para identificar la transaccion, el network es necesario por que el id de transaccion puede llegar a repetirse segun la network
		public function nftGetTransaction($network,$idOperacion){
			$type="nft";
			$function = "vottun/transaction/".$idOperacion;

			$method= "GET";
			$parameters = [];
			$parameters["network"] = $network ; #43113

			return $this->VottumRequest($type, $method   ,$parameters, $function);

			#Petición de prueba
			/*
				$vottun = new VottunController();
				$res = $vottun->nftGetTransaction('43113','0xcb0055d268be1fb892c44d6d28f15e7532d3ebf9fcd4c2b29440b0efa0fc5f74');
			*/
		}

		#Precio del gas
		public function nftGetGasPrice($network){
			$type="nft";
			$function = "vottun/gasprice";

			$method= "GET";
			$parameters = [];
			$parameters["network"] = $network ; #43113

			return $this->VottumRequest($type, $method   ,$parameters, $function);

			#Petición de prueba
			#$vottun = new VottunController();
			#$res = $vottun->nftGetGasPrice('43113');
		}

		public function ipfsUploadFile($file, $filename){
			#"hash": "https:\/\/gateway.pinata.cloud\/ipfs\/QmTFLUJrPe7ivsTJuEwhM3xcLkB429tNZBniZTZfXfvN78"

			$type="ipfs";
			$function = "file/upload";

			$method= "POST_FORM-DATA";
			$parameters = [

				'file' => $file,
				'filename' => $filename
			];


			return $this->VottumRequest($type, $method   ,$parameters, $function);


			#Petición de prueba
			/*
				$vottun = new VottunController();
				$file = Psr7\Utils::tryFopen('themes/duran/img/logo.png', 'r');
				$res = $vottun->ipfsUploadFile($file,'logo.png');
			*/
		}
		public function ipfsMetadata($parameters){
			#"hash": "https:\/\/gateway.pinata.cloud\/ipfs\/QmTFLUJrPe7ivsTJuEwhM3xcLkB429tNZBniZTZfXfvN78"

			$type="ipfs";
			$function = "file/metadata";

			$method= "POST";


			return $this->VottumRequest($type, $method   ,$parameters, $function);


			#Petición de prueba
			/*
				$vottun = new VottunController();
				$file = Psr7\Utils::tryFopen('themes/duran/img/logo.png', 'r');
				$res = $vottun->ipfsUploadFile($file,'logo.png');
			*/
		}




}
