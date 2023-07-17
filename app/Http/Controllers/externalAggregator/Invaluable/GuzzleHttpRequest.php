<?php
namespace App\Http\Controllers\externalAggregator\Invaluable;

use GuzzleHttp\Client;

class GuzzleHttpRequest {
    protected $client;
    protected $token;
    protected $credencial;
	protected $house;

    public function __construct()
    {
		$this->house = \Config::get("app.invaluableHouse");

		

        //Si el token no esta en cache, llamar a getToken() para generar uno nuevo y guardando en cache durante 55 min
        if(1== 1 || !\Cache::has('token')){

            $this->credencial = new Client([
				'verify' => false
            ]);

            \Cache::put('token',$this->getToken(),55);
        }
        $this->token = \Cache::get('token');

        //Creando objeto para hacer las peticiones HTTP
        $this->client = new Client([
            'base_uri' => 'https://stagecps.invaluableauctions.com',
			'verify' => false
        ]);
    }

 //Peticiones a la API por GET
    public function getUrl($url)
    {
        try {
            $headers = array('Content-Type' => 'application/json', 'Authorization' =>  $this->token);

            $response = $this->client->request('GET', $url,['headers' => $headers]);

            return utf8_decode($response->getBody()->getContents());

        }
        catch (\Exception $e) {
            \Log::error('Error al realizar una llamada  a la API por GET: '.$e->getMessage());
        return $this->errorResponse('Error al realizar una llamada  a la API por get', $e->getCode());
        }
    }

    //Peticiones a la API para obtener el Token
    public function getToken(){/* REVISADO */
        try {
            $headers = array('Content-Type' => 'application/json');

            $credenciales = '{
                         "username":"rsanchez@labelgrup.com",
                         "password":"1wb6C/s0G"
                        }';

            $response = $this->credencial->request('PUT','https://stage-tokengen.invaluable.com/ssa', ['headers' => $headers, 'body' => $credenciales]);

            return json_decode($response->getBody())->token;

        }
        catch (\Exception $e) {
            \Log::error(['error'=>'Error al general el token'.', ' . $e->getMessage(),'code'=>$e->getCode()]);
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //Peticiones a la API por PUT
    public function PUT($url,$request,$mensaje = "Catálogo actualizado con éxito", $error = "Error al actualizar catálogo de la subasta"){

        try {

            $headers = array('Content-Type' => 'application/json', 'Authorization' => $this->token);

            $this->client->request('PUT',$url, ['headers' => $headers, 'body' => $request]);

            return $this->successResponse($mensaje);

         }
         catch (\Exception $e) {

             \Log::error(['error'=>$error.', ' . $e->getMessage(),'code'=>$e->getCode()]);
            return $this->errorResponse($e->getMessage(), $e->getCode());

         }
    }

    //Peticiones a la API por DELETE
    public function DELETE($url,$message){

        try {
            $headers = array('Content-Type' => 'application/json', 'Authorization' => $this->token);

            $this->client->request('DELETE',$url, ['headers' => $headers]);

            return $this->successResponse($message);

        }
        catch (\Exception $e) {
            \Log::error(['error'=>'Error al eliminar un lote del catálogo, ' . $e->getMessage(),'code'=>$e->getCode()]);
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    //mensaje de error
    public function errorResponse($message,$code){

        return json_encode([ 'success' => false, 'message'=>$message,'code'=>$code]);
    }

    //mensaje de OK
    public function successResponse($message){
        return json_encode(['success' => true, 'message' => $message,'code'=>200]);
    }
}
