<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;

use App\Models\V5\FxCli;
use App\Models\V5\FxCliWeb;
use App\Models\V5\FxCli2;
use App\Models\V5\FxClid;
use App\libs\EmailLib;
use DB;
use stdClass;

class ClientController extends ApiLabelController
{
	#arrays que sirve para traducir las variables que envian y las de busqueda
	#la variable codcli es interna, por lo que solo se usaria para crear el lote
    protected  $cliRename = array( "idorigincli" => "cod2_cli", "idnumber"=> "cif_cli", "name"=>"nom_cli", "registeredname" => "rsoc_cli", "email" => "email_cli",  "country" => "codpais_cli", "province" => "pro_cli", "city" => "pob_cli", "zipcode" => "cp_cli", "address1" => "dir_cli", "address2" => "dir2_cli", "phone" => "tel1_cli", "mobile" => "tel2_cli", "fax" => "tel3_cli", "legalentity" => "fisjur_cli", "notes" =>"obs_cli", "temporaryblock" => "baja_tmp_cli", "createdate" => "f_alta_cli", "updatedate" => "f_modi_cli", "source" => "tipo_cli", "documenttype" =>  "tdocid_cli", "docrepresentative" => "docid_cli", "typerepresentative" => "tipv_cli", "profession" => "seudo_cli", "prefix" => "preftel_cli", "language" => "idioma_cli", "track" => "sg_cli");
    protected  $cliWebRename = array("codcli" => "cod_cliweb","idorigincli" => "cod2_cliweb", "name"=>"nom_cliweb", "email" => "usrw_cliweb", "email_cliweb" => "email_cliweb","password" => "pwdwencrypt_cliweb"  ,"newsletter1" => "nllist1_cliweb"  ,"newsletter2" => "nllist2_cliweb"  ,"newsletter3" => "nllist3_cliweb"  ,"newsletter4" => "nllist4_cliweb"  ,"newsletter5" => "nllist5_cliweb"  ,"newsletter6" => "nllist6_cliweb"  ,"newsletter7" => "nllist7_cliweb"  ,"newsletter8" => "nllist8_cliweb"  ,"newsletter9" => "nllist9_cliweb", "newsletter10" => "nllist10_cliweb" ,"newsletter11" => "nllist11_cliweb"  ,"newsletter12" => "nllist12_cliweb"  ,"newsletter13" => "nllist13_cliweb"  ,"newsletter14" => "nllist14_cliweb"  ,"newsletter15" => "nllist15_cliweb"  ,"newsletter16" => "nllist16_cliweb"  ,"newsletter17" => "nllist17_cliweb"  ,"newsletter18" => "nllist18_cliweb"  ,"newsletter19" => "nllist19_cliweb", "newsletter20" => "nllist20_cliweb" );
    protected  $cli2Rename = array("codcli" => "cod_cli2", "idorigincli" => "cod2_cli2", "enviocatalogo" => "envcat_cli2" );
	protected  $clidRename = array("codcli" => "cli_clid","idorigincli" => "cli2_clid", "name"=>"nomd_clid",  "countryshipping" => "codpais_clid","namecountryshipping" => "pais_clid", "provinceshipping" => "pro_clid", "cityshipping" => "pob_clid", "zipcodeshipping" => "cp_clid", "address1shipping" => "dir_clid", "address2shipping" => "dir2_clid", "phoneshipping" => "tel1_clid", "mobileshipping" => "tel2_clid", "webshipping" => 'tipo_clid', 'emailshipping' => 'email_clid');

	//protected  $rules = array("idorigincli" => "required|alpha_num|max:8");
	#la direccion tiene 60 pero se han de partir en dos de 30
	protected  $rules = array("idorigincli" => "required|alpha_num|max:8" ,  "idnumber"=> "max:20", "email" => "email|max:80", "password" => "max:256" , "name"=>"max:60", "registeredname" => "max:60", "country" => "alpha|max:2", "province" => "max:30", "city" => "max:30", "zipcode" => "max:10", "address" => "max:60", "phone" => "max:40", "mobile" => "max:40", "fax" => "max:40", "legalentity" => "alpha_num|max:1", "notes" =>"max:200", "temporaryblock" => "alpha_num|max:1", "createdate" => "date_format:Y-m-d H:i:s|nullable" , "updatedate" => "date_format:Y-m-d H:i:s|nullable",  "source" => "alpha_num|max:2|nullable", "documenttype" =>"alpha_num|max:1|nullable", "docrepresentative" => "max:20|nullable", "typerepresentative" => "alpha_num|max:1|nullable" , "profession" => "max:15|nullable", "enviocatalogo" => "alpha_num|max:1|nullable", "prefix" => "alpha_num|max:4|nullable", "language" => "alpha_num|max:2|nullable", "sg_cli" => "alpha_num|max:2|nullable");


    public function postClient(){

        $items =  request("items");

       return $this->createClient( $items );
    }

    public function createClient($items){
        try {

            DB::beginTransaction();

                if(empty($items) || empty($items[0])){
                    throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
                }
                #comprovamos los valores antes de seguir
                $this->validatorArray($items, $this->rules);

                #obtenemos el próximo código cliente
				$codcli = FxCli::getNextCodCli();
				#para crear usuarios web
				$itemsWeb = array();
                foreach($items as $key => $item){
					$items[$key]["codcli"] = sprintf("%'.06d", $codcli);

					if(!empty($item["email"])){
						$items[$key]["email_cliweb"] = $item["email"];

						#se produce un error de clave duplicada cuando modifican un email asignando uno que ya existe como newsletter, por lo que si envian email, borramos el registro si este es de newsletter
						FxCliWeb::where("usrw_cliweb",$item["email"])->where("cod_cliweb", 0)->delete();
					}
					$codcli++;

					if(!empty($item["address"])){
						$items[$key]["address1"] =  mb_substr($item["address"],0,30,'UTF-8');
						$items[$key]["address2"] = mb_substr($item["address"],30,30,'UTF-8');
					}
					if(!empty($item["addressshipping"])){
						$items[$key]["address1shipping"] =  mb_substr($item["addressshipping"],0,30,'UTF-8');
						$items[$key]["address2shipping"] = mb_substr($item["addressshipping"],30,30,'UTF-8');
					}
					#le ponemos el tipo_clid a E para que funcione en la web
					$items[$key]["webshipping"] = 'E';

					#si no indican nada o no lo ponen en positivo crearemos el usuario web
					if(empty($item["notwebuser"]) || $item["notwebuser"] != "S" ){
						#debe llevar el key para que el indice sea el mismo que el del request para cuando hay que recuperara el error
						$itemsWeb[$key] =$items[$key];
					}
                }


                #Creamos FxCli
                $this->cliRename = array_merge($this->cliRename, array( "codcli" => "cod_cli" ));
				$this->create($items, $this->rules, $this->cliRename, new FxCli());
                #Creamos FxCliWeb
                $this->cliWebRename = array_merge($this->cliWebRename, array( "codcli" => "cod_cliweb" ));
				$this->create($itemsWeb, $this->rules, $this->cliWebRename, new FxCliWeb());
				#Creamos FxCli2
                $this->cli2Rename = array_merge($this->cli2Rename, array( "codcli" => "cod_cli2" ));
				$this->create($items, $this->rules, $this->cli2Rename, new FxCli2());
				#Creamos FxClid
                $this->clidRename = array_merge($this->clidRename, array( "codcli" => "cli_clid" ));
                $this->create($items, $this->rules, $this->clidRename, new FxClid());

            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }

    #
    public function getClient(){

        return $this->showClient(request("parameters"));
    }

    public function showClient($whereVars){

		#funcion que elimina los campso required,
		$searchRules = $this->cleanRequired($this->rules);

		$searchRules["idorigincli"].="|nullable";
		#si por algun motivo necesitan ver los usuariso con valor nulo, deberan poner valor -999
		if(!empty($whereVars["idorigincli"]) &&  $whereVars["idorigincli"]==-999){
			$whereVars["idorigincli"] = null;
		}

		#juntamos los renames de las dos tablas, El orden es de suma impportancia, ya que prevalecen los indices del ultimo array,
		#debemos dejar clirename al final ya que es posible que no exista usurio web por lo que cliweb estaria vacio
		$whereRename = array_merge($this->clidRename, $this->cliWebRename, $this->cliRename);
		#lo sobrescribimso por si no hay cliweb creado
		$whereRename["codcli"]= "cod_cli";

		#quitamos los campos de direccion para hacer que se cargue solo en una
		unset($whereRename["address1"]);
		unset($whereRename["address2"]);
		#quitamos email_cliweb
		unset($whereRename["email_cliweb"]);


		$varAPI = array_flip($whereRename);
		#hacemos que devuelva solo 1 campo address
		$varAPI["address"] = "address";
		$fxCli = new FxCli();
		$fxCli = $fxCli->leftjoin('FxCliWeb', 'COD_CLIWEB = COD_CLI  AND GEMP_CLIWEB = GEMP_CLI');
		$fxCli = $fxCli->leftjoin('FXCLID', "CLI_CLID = COD_CLI  AND GEMP_CLID = GEMP_CLI AND CODD_CLID = 'W1' ");

		#haremos select solo con los campos que necesitamos, para eso los saco del whererename
		#añado el campo de address para que solo haya uno
		$select = implode(",", $whereRename).", concat(dir_cli, dir2_cli) as address";
		$fxCli = $fxCli->addselect($select);

		#recogemos la respuesta con el listado de Clientes
		$users = $this->show($whereVars  ,$searchRules, $whereRename, $fxCli,  $varAPI);

        return $users;
    }


    public function putClient(){
		$items =  request("items");

        return $this->updateClient( $items );

    }

    public function updateClient($items){
        try {
            DB::beginTransaction();


            if(empty($items) || empty($items[0])){
                throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
			}
			#si quieren cambiar idorigen debemos hacerlo por otro circuito, por lo que guardamos los clinetes aqui
			$itemsChangeIdOrigin=array();
			$itemsWeb = array();

			foreach($items as $key => $item){
				//dd($item['email']);
				if(!empty($item["email"])){
					$items[$key]["email_cliweb"] = $item["email"];
					#se produce un error de clave duplicada cuando modifican un email asignando uno que ya existe como newsletter, por lo que si envian email, borramos el registro si este es de newsletter
					FxCliWeb::where("usrw_cliweb",$item["email"])->where("cod_cliweb", 0)->delete();
				}


				if(!empty($item["address"])){
					$items[$key]["address1"] =  mb_substr($item["address"],0,30,'UTF-8');
					$items[$key]["address2"] = mb_substr($item["address"],30,30,'UTF-8');
				}
				#si es un usuario que hay que cambiarle el idorigen
				if(!empty($item["setidorigincli"]) && $item["setidorigincli"] == 'S'){
					$itemsChangeIdOrigin[]=$item;
					#No se elimina del array original de items para que así pueda modificar el resto de datos que vengan en la petición, por ejemplo activar la baja temporal

				}

				#si no indican nada o no lo ponen en positivo crearemos el usuario web
				if(empty($item["notwebuser"]) || $item["notwebuser"] != "S" ){
					$itemsWeb[$key] =$items[$key];
				}
			}
			if(!empty($itemsChangeIdOrigin)){
				$this->changeIdOrigin($itemsChangeIdOrigin);
			}


			#miramos si hay items ya que se han podido mover todosd al array de cambio de idorigen
			$rules = $this->rules;
			if(!empty($items)){
				$this->update($items, $rules, $this->cliRename, new FxCli());
				$this->update($items, $rules, $this->cli2Rename, new FxCli2());
				$this->update($items, $rules, $this->clidRename, new FxClid());
			}

			if(!empty($itemsWeb)){
				#vamos a revisar todos los usuario s por si alguno n oexistía en cliweb
				foreach($itemsWeb as $key => $item){
					#no se debe hacer para la API , si para el admin por eso comprobamos el codcli
					if(!empty($item["codcli"])){
						$cliweb = FxCliweb::where('cod2_cliweb', $item["idorigincli"])->first();
						#crea usuario por que no existe
						if(empty($cliweb)){
							#debe mantener el indice para controlar errores
							$this->create([$key =>$item], $rules, $this->cliWebRename, new FxCliWeb());
						}
					}
				}

				$this->update($itemsWeb, $rules, $this->cliWebRename, new FxCliWeb());
			}
               // $this->update($items, $rules, $this->hces1Rename, new FgHces1());
            DB::commit();
            return $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }

	}

	#funcion exclusiva que sire para poder modificar el idorigen de un usuario usando el email como campo de busqueda
	private function changeIdOrigin($itemsChangeIdOrigin){
		$rulesChangeIdOrigin = array("idorigincli" => "required|alpha_num|max:8" ,   "email" => "required|email|max:80");
		$this->validatorArray($itemsChangeIdOrigin, $rulesChangeIdOrigin);

		foreach($itemsChangeIdOrigin as $item){
			$client = FxCliWeb::emailExistCliweb($item["email"]);
			#devolvemos error avisando de que ese email no identifica a ningun usuario
			if(empty($client)){
				$errorsItem["item"] = array("email" => array($item["email"]));
                throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
			}

			#modificamos el idorigen en todas las tablas asociadas.
			$codCli=$client->cod_cliweb;
			$idOrigin = $item["idorigincli"];
			FxCliWeb::where("cod_cliweb", $codCli)->update(array("cod2_cliweb"=> $idOrigin));
			fxCli::where("cod_cli", $codCli)->update(array("cod2_cli"=> $idOrigin));
			fxClid::where("cli_clid", $codCli)->update(array("cli2_clid"=> $idOrigin));
			fxCli2::where("cod_cli2", $codCli)->update(array("cod2_cli2"=> $idOrigin));

			if(!empty($item["sendactivateemail"]) && $item["sendactivateemail"] == 'S'){
				$email = new EmailLib('USER_ACTIVATE');
				if(!empty($email->email)){
					$email->setTo($item["email"]);
					$email->setUrl(\Config::get("app.url"));
					$email->send_email();
				}
			}

		}
	}



    public function deleteClient(){
        return $this->eraseClient(request("parameters"));
    }

    public function eraseClient($whereVars){
        try
        {
            DB::beginTransaction();

            # el idorigen es obligatorio, por eso solo cojemso esa regla
			$rules = $this->getItems($this->rules, array("idorigincli"));

            #Borrar FxCliWeb, pasamos false para que no de error al no encontrar cliweb si el cliente no tiene usuario web
                $this->erase($whereVars, $rules, $this->cliWebRename, New FxCliWeb(),false );
            #Borrar FXCLI
				$this->erase($whereVars, $rules, $this->cliRename, new FxCli());
			#Borrar FXCLID
				$this->erase($whereVars, $rules, $this->clidRename, new FxClid());
			#Borrar FXCLI2
				$this->erase($whereVars, $rules, $this->cli2Rename, new FxCli2());

            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }

}
