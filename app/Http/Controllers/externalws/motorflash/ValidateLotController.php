<?php
namespace App\Http\Controllers\externalws\motorflash;


use Exception;
use App\Http\Controllers\Controller;

use App\Models\V5\FgAsigl0;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;
use App\Jobs\UniversalJob;
class ValidateLotController extends Controller
{
	#esta funcion se encuentra en LoadLotFileLib, la dejo aquí para poder hacer pruebas de manera facil
	public function requestProvider($idProvider){
		$lotes = FgAsigl0::select("NUM_HCES1, LIN_HCES1, IDORIGEN_HCES1, IMPSALHCES_ASIGL0 ")->JoinFghces1Asigl0()
		->where("prop_hces1",$idProvider)->where("retirado_asigl0","N")->get();
		foreach($lotes as $lote){
			UniversalJob::dispatch("App\Http\Controllers\\externalws\motorflash\\ValidateLotController", "requestLot", $lote->toarray())->onQueue(env('QUEUE_IMG_ENV','imagesPRE'));
		}

	}


	public function requestLot($lote){

		try{

		$idOrigen = explode("-",$lote["idorigen_hces1"]);

		$request = new \stdClass();
		$request->anuncioMF = $idOrigen[1];
		$request->token = "3918547a68f4d2ab943f44956b836fde26a66a77b8c12dd3dd4ea31a558c5bc8";
		$request->anuncioPortal = $lote["num_hces1"] ."-".$lote["lin_hces1"];
		$request->precioContado = $lote["impsalhces_asigl0"];

       
		#$url = "https://apistockportales.premotorflash.com/api/stocks";
	    $url = "https://apistockportales.motorflash.com/api/stocks";

		$clientGuzz = new Client(['verify' => false]);
		$res = $clientGuzz->request("POST", $url,[

            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
              ],
           'auth' => ["apiPortal", "MFstock.P0rtales"],
            \GuzzleHttp\RequestOptions::JSON =>$request
            ]);



		}catch(ClientException $e){
			#devolveran 422 si no es correcto el stock
			if($e->getCode()=="422"){
				$this->invalidateLot($lote["num_hces1"], $lote["lin_hces1"],$request->anuncioMF);
			}if($e->getCode()=="401"){
				\Log::info("Error en autenticación webService Motorflash ValidateLotController");
			}
			\Log::info("Error inesperado ".$e);

		}
	}
	#dar de baja un lote
	public function invalidateLot($numhces, $linhces, $anuncioMF){
		#Lo comento para que no vaya anulando lso lotes
		FgAsigl0::where("NUMHCES_ASIGL0", $numhces)->where("LINHCES_ASIGL0", $linhces)->update(["RETIRADO_ASIGL0"=> "E"]);
		\Log::info("Lote bloqueado num_hces1:".$numhces." lin_hces1: ". $linhces." anuncio:".$anuncioMF);

	}

}
