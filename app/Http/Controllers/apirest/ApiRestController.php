<?php

namespace App\Http\Controllers\apirest;

use Illuminate\Routing\Controller as BaseController;

use Controller;
use Config;
use Request;
use File;
use App\Models\apirest\UserApiRest;

class ApiRestController extends BaseController
{
    /*Empresa*/
    public $emp;

    /*Grupo Empresa*/
    public $gemp;

    public function __construct(){

        $this->emp = Config::get('app.emp');
        $this->gemp = Config::get('app.gemp');
        $this->requiedValues();

        $this->existUser();

    }

    //Campos obligatorios
    function requiedValues(){
        $required = array('apikey','user','passw');
        $this->validatorRequired($required);

		if(request('apikey') != Config::get('app.apikey')){
            exit(json_encode('Error apikey'));
        }
    }

    //Devolver informacion
    public function returnInf($inf){

        exit(json_encode($inf));
    }

    //Validamos que vengan los campos obligatorios
    public function validatorRequired($required){

        $input = request()->all();

        //Bucle campos obligatiros, si no existe error.
        foreach($required as $require){

            if (!isset($input[$require])) {
                $inf = array (
                    'status' => 'error',
                    'data' => array('msg_error' => "falta el campo : ".$require)
                );
                return $this->returnInf($inf);
            }
        }

    }

    //Validar Usuario exista
    public function existUser($return_user = false){

        $user_api = new UserApiRest();
        $inf = array(
            "status"=>"error",
            "data" => array ("msg_error" => "Incorrect username or password"));
        $password = Request::input('passw');
        $user = Request::input('user');
        //Buscamos el usuario si existe y que no tenga baja
        $inf_user = $user_api->getUserLogin($user,$password);

         if(empty($inf_user)){
            $this->returnInf($inf);
        }

        if($return_user){
            return $inf_user;
        }

    }

    public function generateFilters($required, $sendedvars){

        $filter = array();
        foreach($required as $val){
            if(property_exists($sendedvars, $val)){
               $filter[$val] =  $sendedvars->$val;
            }
        }
        return $filter;

    }

    //Function save image
    public function saveImage($dest_path,$imageName,$imgSource){

         if (!File::exists($dest_path)){
            //mkdir($pathImagenesThumbs, 0777, true);
            File::makeDirectory($dest_path, 0775, true);

        }
        $imgSource = str_replace('data:image/jpeg;base64,', '', $imgSource);
        $imgSource = str_replace('data:image/jpg;base64,', '', $imgSource);
        $imgSource = str_replace('data:image/png;base64,', '', $imgSource);
        $imgSource = str_replace(' ', '+', $imgSource);

        $imgSource = base64_decode($imgSource);

        File::put($dest_path.$imageName, $imgSource);
        chmod($dest_path.$imageName,0775);
    }


    /**
     * Metodo generico para enviar respuestas Json
     *
     * @param bool $succes
     * @param string $mensaje de la respuesta
     * @param array $datos a enviar
     * @param int $codigo de resupuesta
     * @return type
     */
    public function responder($succes, $mensaje, $datos, $codigo) {

        return response()->json([
                    'succes' => $succes,
                    'message' => $mensaje,
                    'data' => $datos
                        ], $codigo);
    }

	public function responseRules($rules)
	{
		return $this->responder(false, trans(config('app.theme') . '-app.emails.api_need_params'), $rules, 400);
	}

	public function responseNotFound($text)
	{
		return $this->responder(false, $text, '', 404);
	}


}
