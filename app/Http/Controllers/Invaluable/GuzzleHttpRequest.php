<?php
namespace App\Http\Controllers\Invaluable;

use GuzzleHttp\Client;

class GuzzleHttpRequest {
    protected $client;
    protected $token;
    protected $credencial;

    public function __construct()
    {
        //Si el token no esta en cache, llamar a getToken() para general uno nuevo y guardando en cache durante 55 min
        if(!\Cache::has('token')){

            $this->credencial = new Client([
                'base_uri' => 'https://tokengen.invaluable.com/'
            ]);

            \Cache::put('token',$this->getToken(),55);
        }
        $this->token = \Cache::get('token');

        //Creando objeto para hacer las peticiones HTTP
        $this->client = new Client([
            'base_uri' => 'https://ptnr-cps2.invaluableauctions.com/'
        ]);
    }

 //Peticiones a la API por GET
    public function getUrl($url)
    {
        try {
            $headers = array('Content-Type' => 'application/json', 'Authorization' => $this->token);

            $response = $this->client->request('GET', $url,['headers' => $headers]);

            return utf8_decode($response->getBody()->getContents());

        }
        catch (\Exception $e) {
            \Log::error('Error al realizar una llamada  a la API por GET: '.$e->getMessage());
        return $this->errorResponse('Error al realizar una llamada  a la API por get', $e->getCode());
        }
    }

    //Peticiones a la API para obtener el Token
    public function getToken(){
        try {
            $headers = array('Content-Type' => 'application/json');

            $credenciales = '{
                         "username":"subastas@labelgrup.com",
                         "password":"Superadmin1*"
                        }';

            $response = $this->credencial->request('PUT','partner', ['headers' => $headers, 'body' => $credenciales]);

            return json_decode($response->getBody()->getContents())->token;

        }
        catch (\Exception $e) {
            \Log::error(['error'=>'Error al general el token' . $e->getMessage(),'code'=>$e->getCode()]);
            return $this->errorResponse('Error al general el token', $e->getCode());
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
            return $this->errorResponse($error, $e->getCode());

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
            return $this->errorResponse('Error al eliminar un lote del catálogo', $e->getCode());
        }
    }

    //mensaje de error
    public function errorResponse($message,$code){

        return response(['error'=>$message,'code'=>$code,'success' => false],$code);
    }

    //mensaje de OK
    public function successResponse($message){
        return response(['message' => $message,'success' => true],200);
    }
}