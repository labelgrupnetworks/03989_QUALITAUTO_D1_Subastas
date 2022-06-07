<?php

namespace App\Http\Controllers\apilabel;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

use Controller;
use Config;
use Request;

use App\Models\V5\FgLicit;
use App\Models\V5\FxCli;
use App\libs\EmailLib;
use Exception;

use function GuzzleHttp\json_encode;

use Illuminate\Database\QueryException;
use Illuminate\Support\MessageBag;

class ApiLabelController extends BaseController
{
    /*Empresa*/
    public $emp;

    /*Grupo Empresa*/
    public $gemp;
	public $index;

	# reglas para wheres speciales
	protected  $rulesSpecialWhere = array('min_date' => "date_format:Y-m-d H:i:s", 'max_date' => "date_format:Y-m-d H:i:s" );
	protected  $rawSpecialWhere = array('min_date' => ">=", 'max_date' => "<=" );


    //Si la llamada la hacen desde la API pedirá estar logeado, si no, no lo comprueba
    public function __construct(){

        $this->emp = Config::get('app.emp');
        $this->gemp = Config::get('app.gemp');
        $this->index = NULL;
		$this->request = request()->all();

    }

    /*
        $items = elementos a crear
        $rules = reglas para validar que los datos enviados son correctos
        $varsRename = array que contiene los nombres de las variables enviados y su correspondiente en base de datos
        $model = modelo en el que se hara la insercion
        $defaultValues = Valores por defecto que deberemos poner en los campos indicados en el array
    */


    #funcion que inserta nuevos registros en base de datos
    public function create($items, $rules, $varsRename, $model, $defaultValues = array()){

        $this->validatorArray($items, $rules);

       #renombremos variables
       $itemsRename = $this->renameArray($items, $varsRename );

       #variable que nos indica la posición dentro del array de registros, lo usaremos para devolver la posición del error
       $this->index = null;
       #realizamos las modificaciones necesarias y creamos el item
       foreach($itemsRename as $key=>$item){
            $this->index = $key;
            foreach($defaultValues as $keyDefault => $valueDefault){
                $item[$keyDefault] = $valueDefault;
            }
           $model->create($item);
       }


    }


     /*
        $whereVars = Variables que envian para hacer la busqeuda
        $searchRules = Reglas de validación para esas variables
        $whereRename = array que contiene los nombres de las variables enviados y su correspondiente en base de datos
        $model = modelo en el que se hara la busqueda
        $varAPI = array que contiene los nombres de las variables en base de datos qeu devolverá la query y su correspondiente  nombre al que se espera

     */
    #Funcion que realiza una busqueda en base de datos y devuelve el resultado
    public function show($whereVars  ,$rules, $whereRename, $model,  $varAPI){
        try {

            if(is_null($whereVars)){
                $whereVars = array();
            }
            $this->validator($whereVars, $rules);


            $whereVarsRename = $this->rename($whereVars, $whereRename);
            #comprobamos si existen los parametros que han enviado, si el numero de parametros que han enviaod no coincido con lso que tenemos es que alguno no se ha podido hacer rename
            #de momento no lo usaré
            # $this->errorParameter( $whereVars, $whereVarsRename);


            #realizamos los where en el modelo, el modelo ya vendra con los Joins y selects que necesite
            $model= $this->Where($whereVarsRename, $model);
            $items = $model->get()->toArray();
            #echo "<pre>"; print_r(json_encode($items));die();
            $res = $this->renameArray($items, $varAPI);

            return $this->responseSuccsess("", $res);

        } catch(\Exception $e){
			#Hago directamente un exit por que se supone que si ha habido algun error no debería poder continuar
            echo  $this->exceptionApi($e);
			exit;
        }

    }


    /*
        $items = elementos a modificar
        $rules = reglas para validar que los datos enviados son correctos
        $varsRename = array que contiene los nombres de las variables enviados y su correspondiente en base de datos
        $model = modelo en el que se hara la insercion
    */


    #funcion que modifica registros en base de datos
    public function update($items, $rules, $varsRename, $model){
       $this->validatorArray($items, $rules);

	   $itemsRename= $this->renameArray($items, $varsRename);

       $this->index = null;
       foreach($itemsRename as $key=>$item){
			if(empty($item)){

				$messageBag = new MessageBag();
				$messageBag->add("Error",trans('apilabel-app.errors.no_params') );
                $errorsItem["item_".($key +1)] =$messageBag;
                #guardamos en un array los errores provocados

				throw new ApiLabelException(trans('apilabel-app.errors.updating'),$errorsItem);
			}

            $this->index = $key;
            #WhereUpdateApi es una funcion que esta creada en el modelo, que espera  un registro y hace los wheres necesarios
            $model->WhereUpdateApi($item)->update($item);
       }


    }

    /*
        $whereVars = Variables que envian para hacer la busqeuda
        $rules = Reglas de validación para esas variables
        $whereRename = array que contiene los nombres de las variables enviados y su correspondiente en base de datos
        $model = modelo en el que se hara la busqueda
        $varAPI = array que contiene los nombres de las variables en base de datos qeu devolverá la query y su correspondiente  nombre al que se espera

     */

    #para eliminar solo deberan pasarnos los identificadores, el resto de campos no haran nada
    public function erase($whereVars, $rules, $whereRename, $model, $errorNotdelete = true ){
        if(is_null($whereVars)){
            throw new ApiLabelException(trans('apilabel-app.errors.no_params'));
        }
        $this->validator($whereVars, $rules);

        $whereVarsRename= $this->rename($whereVars, $whereRename);
        #realizamos los where en el modelo
        $model = $this->Where( $whereVarsRename, $model);

		$res = $model->delete();
		#generamos excepción si no hay elementos, se puede evitar enviando false en $errorNotdelete
        if($res==0 && $errorNotdelete){
			$campos=" FIELDS: ";
			foreach($whereVars as $key=> $vars){
				$campos.=" $key = $vars ";
			}

            throw new ApiLabelException(trans('apilabel-app.errors.delete'). $campos);
        }

    }


    protected function validator($item, $rules, $exit = true){
        $messages = array(
            'required' => trans('apilabel-app.validation.required'),
            'numeric' => trans('apilabel-app.validation.numeric'),
            'alpha_num' => trans('apilabel-app.validation.alpha_num'),
            'alpha' => trans('apilabel-app.validation.alpha'),
            'email' => trans('apilabel-app.validation.email'),
            'date' => trans('apilabel-app.validation.date'),
            'min' => trans('apilabel-app.validation.min_characters'),
            'max' => trans('apilabel-app.validation.max_characters'),
            'date_format' => trans('apilabel-app.validation.date_format'),
            'filled' => trans('apilabel-app.validation.filled'),
			'required_without_all' => trans('apilabel-app.validation.required_without_all'),
			'required_with' =>  trans('apilabel-app.validation.required'),
			'min_date' => trans('apilabel-app.validation.date_format'),
			'min_date' => trans('apilabel-app.validation.date_format'),


        );
        $validator = Validator::make($item, $rules, $messages);

        if($exit){
            if ($validator->fails()) {

                throw new ApiLabelException(trans('apilabel-app.errors.validation'), $validator->errors());
            }else{
                return  true ;
            }
        }


        return  $validator ;

    }

    protected function validatorArray($items, $rules){


        $fails = false;
        $errors = array();
        foreach ($items as $key=>$item){
            $validator = $this->validator($item, $rules, false);

            if ($validator->fails()) {
                $fails = true;
                $pos=$key+1;
                #guardamos en un array los errores provocados
                $errors["item_$pos"]=$validator->errors();
            }
        }
        #
        if($fails) {
            throw new ApiLabelException(trans('apilabel-app.errors.validation'), $errors);

        }
        else {
            return true;
        }

    }

    #faltaria  hacer que pueda comprobar variables a null
    protected function where($whereVars, $model){
        foreach($whereVars as $key=>$value){
            $model= $model->where($key,$value);
        }

        return $model;
	}


	protected function whereSpecial($items,$rename, $model){

		$this->validator($items, $this->rulesSpecialWhere);
        foreach($items as $key=>$value){
			$name = null;
			$whereraw = null;
			//le ponemos el nombre del campo
			if(!empty($rename[$key])){
				$name = $rename[$key];
			}

			if(!empty($this->rawSpecialWhere[$key])){
				$whereraw = $this->rawSpecialWhere[$key];
			}

			# si existe
			if(!empty($name) && !empty($whereraw)){
/*
				if($key == "min_date" || $key == "max_date" ){
					$value = date("d-m-Y H:i:s",strtotime("$value"));
				}
*/

				$model = $model->whereraw("$name $whereraw ?",[ $value]);
			}

        }

        return $model;
	}


    #si pasan un array de parametros
    protected function renameArray($items, $names){
        $newitems = array();
        foreach($items as $key => $item){
			#hay que mantener las keys para notificar bien los errores
            $newitems[$key]=$this->rename($item, $names);
        }

        return $newitems;
    }

    #renombre de las columnas para adaptar los nombres a la base de datos
    protected function rename($item, $names){

            $newitem = array();
            foreach($item as $column => $value){
                if(!empty($names[$column])){
                    $newitem[$names[$column]] = $value;
                }
            }

        return $newitem;
    }

    protected function exceptionApi($e){

        #vemos si es posible detectar el numero del registro, como el indice empieza en 0 sumamos 1
        $pos="";
        if(!is_null($this->index) && is_numeric($this->index)){
            $pos =" _". ($this->index +1);
        }

        if($e instanceof QueryException){


			\Log::error( $e->getMessage());
            $errorCode =sprintf("%'.05d", $e->getCode());
            $items["item$pos"]= new MessageBag();
			$items["item$pos"]->add("Values", implode(", ",$e->getBindings()));

			$message = $this->desOracleError( $errorCode );
            return $this->responseError($message, $items);

        }elseif ($e instanceof ApiLabelException){

            return $this->responseError($e->getMessage(),$e->getItems());
        }
        else{

            # si no tenemos controlada la excepcion no damos mas info, que nos pregunten y revisamos el log
            return $this->responseError( trans('apilabel-app.errors.unexpected_exception')." ". $e->getMessage() );
        }
    }

    #de momento n olo usaré
    //han pasado parametros que no exsiten, por lo que no se han podido convertir a los campos de la base de datos
    protected function errorParameter($requestParameters, $renamedParameters){
        if(count($requestParameters) != count($renamedParameters)){
            throw new ApiLabelException(trans('apilabel-app.errors.parameter_not_exist'));
        }
    }

     #devuelve los elementos que coinciden con la keys pasadas
   protected function getItems( $allItems, $requestKeys = array() ){

        if(empty($requestKeys) ){
            $responseItems =  $allItems;
        }else{
            $responseItems = array();
            foreach($requestKeys as $requestKey){
                if(array_key_exists($requestKey, $allItems)){
                    $responseItems[$requestKey]=$allItems[$requestKey];
                }
            }
        }

        return $responseItems;
    }
    #modifica las reglas para adaptarlas a la funcion Show, las variables en  no change no se modifican
    protected function cleanRequired($rules, $noChange = array()){
        $showRules = array();
        foreach($rules as $key => $rule){
            if(!in_array($key, $noChange)){
                #quitamos las apariciones de requeired, ya sea al principio o enmedio, al final o si solo hay required en al regla
                $rule = str_replace("required|","",$rule);
                $rule = str_replace("|required","",$rule);
                $rule = str_replace("required","",$rule);
            }

            $showRules[$key] = $rule;
        }

        return $showRules;

	}


	 #devuelve un array con el código de licitador y de usuario, el licitador si no  existe crea uno nuevo y lo devuelve, pasamos $key para ver el indice si da error
	 protected function getLicit($licits,$item, $key){
        #si el cliente ya tiene licitador devolvemos su valor
        if(!empty($licits[$item["idoriginclient"]])){
            return $licits[$item["idoriginclient"]];
        }
        else{
            $client = FxCli::select("cod_cli,nvl(rsoc_cli, nom_cli) rsoc_cli")->where("cod2_cli", $item["idoriginclient"])->first();

            #si no existe el cliente devolvemos error
            if(empty($client)){
				$messageBag = new MessageBag();
				$messageBag->add("idoriginclient",trans('apilabel-app.errors.no_match') );
                $errorsItem["item_".($key +1)] =$messageBag;
                throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
            }
            #sumamos uno al maximo que habia para usar el siguiente
            $this->maxCodLicit++;

            $licit=array("sub_licit" => $item["idauction"],
                        "cli_licit" => $client->cod_cli,
                        "cod_licit" => $this->maxCodLicit,
                        "rsoc_licit" => $client->rsoc_cli);


            $fgLicit = new FgLicit();
            $fgLicit->create($licit);

            return ["cod_licit" => $this->maxCodLicit, "cod_cli" => $client->cod_cli];

        }
    }


    protected function responseSuccsess(  $message="",$items = null) {
        return $this->responseApi("SUCCESS", $message, $items);
    }

    protected function responseError( $message="", $items = null) {

		#si existe el array items de la petición
		if( !empty($this->request["items"])  ){

			#recorremos los items que han dado error
			foreach($items as $key => $item){
				#con el indice del item del errorpodemos obtener el indice real, solo hay que extraer el numero y restarle 1
				$indexExplode = explode("_", $key);

				#comprobamos que estuviera compuesto por dos elementos y que el segundo fuera entero
				if(count($indexExplode) == 2 && is_numeric($indexExplode[1])){
					$index = $indexExplode[1] -1;
					# si existe el indice en la petición, podemos recuperar la petición que se hizo
					if(!empty($this->request["items"][$index])){
						#esta dando error al ser array
						if(!is_array($items[$key])){
							$items[$key]->add("request", $this->request["items"][$index]);
						}

					}
				}
			}

		}

		$this->sendEmailError($message, $items);

        return $this->responseApi("ERROR", $message, $items);
    }


    private function responseApi($status, $message, $items ) {

        $response = array('status' => $status,
                          'message' => $message
                        );

        if(!empty($items)){
            $response["items"] = $items;
        }

        return json_encode($response);
    }

	protected function desOracleError($oracleError){
		$errors =array(
			"00001" => "Unique constraint has been violated",
			"01407"	=> "Column does not accept NULL values",
			"12899" => "value too large for column",
			"01400" => "Cannot insert null "
		);
		return $errors[$oracleError]?? "Oracle Error ORA-$oracleError";
	}

	protected function sendEmailError($message, $items ){

		#si estamos fuera del circuito de pruebas, se envia el emails
		if(!env('APP_DEBUG')){
			$email = new EmailLib('API_ERROR');
			if(!empty($email->email)){
				#Email que recibe el correo de alerta
				$to = \Config::get("app.emailApiError")?? \Config::get("app.debug_to_email");
				$email->setTo($to);
				#Emails que recibiran copia del error
				if(!empty(\Config::get("app.emailsCopyApiError"))){
					$bcc = explode(",",\Config::get("app.emailsCopyApiError") );
					foreach($bcc as $bcc_email){
						$email->setBcc($bcc_email);
					}

				}

				#FORMATEAR LOS TEXTOS DEL MENSAJE DE ERROR
				$body="";

				if(is_array($items)){
					foreach($items as $key => $item){
						$body.="<br> ". strtoupper($key)." <br>";
						#esta dando error al ser array, revisar problema con la llamada lot/put {"idorigin": "", "idauction": "0", "warehouse": "3"}
						if(!is_array($item)){
							$itemArray = $item->toArray();
						}else{
							$itemArray = $item;
						}
						foreach($itemArray as $field => $arrayValues){
							$body.="<br> <b>$field:</b> <br>";
							foreach($arrayValues as  $element){

								if(!is_array($element)){
									$body.=" $element: <br>";
								}else{
									$body.=" <ul>";
									foreach($element as $subElementKey => $subElement){
										if(!is_array($subElement)){
											$body.="<li> <b>$subElementKey</b>: $subElement</li>";
										}else{
											$body.="<li> <b>$subElementKey</b>: ".print_r($subElement,true)."</li>";
										}
									}
									$body.=" </ul>";
								}
							}

						}
					}
				}

				$clase = str_replace("App\Http\Controllers\apilabel\\","",get_class($this));
				$clase = str_replace("Controller","",$clase);
				$email->setAtribute("MODELO", $clase);
				$email->setAtribute("METHOD", $_SERVER['REQUEST_METHOD']);

				$email->setAtribute("MESSAGE", $message);
				$email->setAtribute("BODY", $body ); //nl2br(print_r($items,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
				$email->send_email();
				#lo comento para que se guarden siempre los logs
				//return;
			}
		}
		# si estamos en pruebas, lo escribimos en log
		\Log::channel('api')->error($message . " ".print_r($items,true));


	}


}
