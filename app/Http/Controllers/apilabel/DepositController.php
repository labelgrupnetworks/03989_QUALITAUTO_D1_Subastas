<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;




use App\Models\V5\FxCli;

use App\Models\V5\FgDeposito;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgLicit;
use App\libs\EmailLib;

use DB;
use stdClass;

class DepositController extends ApiLabelController
{
    #arrays que sirve para traducir las variables que envian y las de busqueda
    protected  $renameExtra = array( "idoriginlot"=>"idorigen_asigl0","idoriginclient"=>"cod2_cli");
    protected  $rename = array("idauction"=>"sub_deposito", "reflot"=>"ref_deposito", "status" => "estado_deposito", "amount" => "importe_deposito","date" => "fecha_deposito" , );


    protected  $rules = array('reflot' => "required", "idoriginclient" => "required|max:8", "idauction" => "required|max:8", "amount" => "required|numeric|max:99999999","date" => "required|date_format:Y-m-d H:i:s", );


    public function postDeposit(){
        $items =  request("items");
        return $this->createDeposit( $items );
    }


    public function createDeposit($items){
        try {
            DB::beginTransaction();

                if(empty($items) || empty($items[0])){
                    throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
                }

				$idAuction = $items[0]["idauction"];
				$lots = FgAsigl0::arrayByIdOrigin($idAuction);

                foreach($items as $key => $item){

					$client = FxCli::select("cod_cli")->where("cod2_cli", $item["idoriginclient"])->first();

					#si no existe el cliente devolvemos error
					if(empty($client)){
						$errorsItem["item_".($key +1)] = array("idoriginclient" => $item["idoriginclient"]);
						throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
					}
					$items[$key]["cli_deposito"] =  $client->cod_cli;

					#todos los depositos han de ser de la misma subasta
					if($idAuction != $item["idauction"]){
                        throw new ApiLabelException(trans('apilabel-app.errors.different_auctions'));
                    }

					#si no viene la referencia del lote se consigue mediante el idorigin,
					if(empty($item["reflot"] )){

						#si no existe el lote devolvemos error
						if(empty($lots[$item["idoriginlot"]])){
						   $errorsItem["item_".($key +1)] = array("idoriginlot" =>  $item["idoriginlot"]);
						   throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
					   }
					   $items[$key]["reflot"] = $lots[ $item["idoriginlot"]]["ref_asigl0"];
				   }






                    #$this->setDeposit( $item, $licits, $lots[$item["idoriginlot"]], $deposits);
				}
				#añadimos los campos de la tabla
				$this->rename["cli_deposito"] = "cli_deposito";

                $this->create($items, $this->rules, $this->rename, new FgDeposito());

           		DB::commit();

				#una vez comprobado que se han creado los depositos y si se tienen que crear las pujas iniciales
				if(\Config::get("app.depositBid") ){

					$idAuction = $items[0]["idauction"];

					#máximo código de licitador actual
					$this->maxCodLicit = FgLicit::getMaxCodLicit($idAuction);

					$licits = FgLicit::getLicitsSubIdOrigin($idAuction);
					foreach($items as $key => $item){
						$licit = $this->getLicit($licits, $item, $key);
						FgAsigl1::depositBid($licit["cod_licit"],$item["idauction"],$item["reflot"],$item["amount"],$item["date"]);
						if(\Config::get("app.mailDepositBid") ){
							$client = FxCli::select("cod_cli")->where("cod2_cli", $item["idoriginclient"])->JoinCliWebCli()->first();
							if(!empty($client)){
								$email = new EmailLib('DEPOSIT_ACTIVATE');
								if (!empty($email->email)) {
									$email->setUserByCod($client->cod_cli, true);
									$email->setLot($item["idauction"],$item["reflot"]);
									$urlLot = $email->getAtribute("LOT_LINK");

									$email->setAtribute("URL_PASWWORD",$urlLot."?recoveryPassword=S&emailRecovery=".$client->email_cli);
									$email->send_email();

								}
							}
						}
					}


				}

            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }




    public function getDeposit(){
        return $this->showDeposit(request("parameters"));
    }

    public function showDeposit($whereVars){

		$rename = array_merge($this->rename, $this->renameExtra);

        $varAPI = array_flip( $rename);
        $searchRules = $this->cleanRequired($this->rules, array("idauction"));

        $deposito = new FgDeposito();
        #haremos select solo con los campos que necesitamos, para eso uso los del rename
        $select = implode(",",  $rename);

        $licit = $deposito->addselect($select)->joinCli()->joinasigl0();

        return $this->show($whereVars, $searchRules,  $rename,  $licit,  $varAPI);
    }

	public function putDeposit(){
        $items =  request("items");
        return $this->updateDeposit( $items );
    }


    public function updateDeposit($items){
        try {
            DB::beginTransaction();

                if(empty($items) || empty($items[0])){
                    throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
                }

				$idAuction = $items[0]["idauction"];
				$lots = FgAsigl0::arrayByIdOrigin($idAuction);

                foreach($items as $key => $item){

					$client = FxCli::select("cod_cli")->where("cod2_cli", $item["idoriginclient"])->first();

					#si no existe el cliente devolvemos error
					if(empty($client)){
						$errorsItem["item_".($key +1)] = array("idoriginclient" => $item["idoriginclient"]);
						throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
					}

					   $items[$key]["cli_deposito"] =  $client->cod_cli;

					   #todos los depositos han de ser de la misma subasta
					if($idAuction != $item["idauction"]){
                        throw new ApiLabelException(trans('apilabel-app.errors.different_auctions'));
                    }

					#si no viene la referencia del lote se consigue mediante el idorigin,
					if(empty($item["reflot"] )){

						#si no existe el lote devolvemos error
						if(empty($lots[$item["idoriginlot"]])){
						   $errorsItem["item_".($key +1)] = array("idoriginlot" =>  $item["idoriginlot"]);
						   throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
					   }
					   $items[$key]["reflot"] = $lots[ $item["idoriginlot"]]["ref_asigl0"];
				   }

                    #$this->setDeposit( $item, $licits, $lots[$item["idoriginlot"]], $deposits);
				}
				#añadimos los campos de la tabla
				$this->rename["cli_deposito"] = "cli_deposito";


                $this->update($items, $this->rules, $this->rename, new FgDeposito());


            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }









    public function deleteDeposit(){
        return $this->eraseDeposit(request("parameters"));
    }

    public function eraseDeposit($whereVars){
        try
        {

			DB::beginTransaction();

			$lots = FgAsigl0::arrayByIdOrigin($whereVars["idauction"]);

				$client = FxCli::select("cod_cli")->where("cod2_cli", $whereVars["idoriginclient"])->first();

				#si no existe el cliente devolvemos error
				if(empty($client)){
					$errorsItem["item_1"] = array("idoriginclient" => $whereVars["idoriginclient"]);
					throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
				}

				$whereVars["cli_deposito"] = $client->cod_cli;
				$this->rename["cli_deposito"] ="cli_deposito";



				#si no viene la referencia del lote se consigue mediante el idorigin,
				if(empty($whereVars["reflot"] )){

					#si no existe el lote devolvemos error
					if(empty($whereVars["idoriginlot"])){
					   $errorsItem["item_1"] = array("idoriginlot" =>  $whereVars["idoriginlot"]);
					   throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
				   }
				   $whereVars["reflot"] = $lots[ $whereVars["idoriginlot"]]["ref_asigl0"];
			   }



			$whereRules = $this->getItems($this->rules, array("idauction", "reflot"));


			$this->erase($whereVars, $whereRules, $this->rename, new FgDeposito());


			#una vez comprobado que se ha eliminado el deposito y si se tienen que crear las pujas iniciales
			if(\Config::get("app.depositBid") ){
				$licit = FgLicit::JoinCli()->where("CLI_LICIT",$whereVars["cli_deposito"])->first();
				if(!empty($licit)){
					FgAsigl1::where("SUB_ASIGL1",$whereVars["idauction"])->where("REF_ASIGL1",$whereVars["reflot"])->where("LICIT_ASIGL1",$licit->cod_licit)->delete();
					#reordenar pujas
					$bids = FgAsigl1::where("SUB_ASIGL1",$whereVars["idauction"])->where("REF_ASIGL1",$whereVars["reflot"])->orderby("lin_asigl1")->get();
					$lin=1;
					foreach($bids as $bid){
						if($bid->lin_asigl1 !=$lin){
							FgAsigl1::where("SUB_ASIGL1",$whereVars["idauction"])->where("REF_ASIGL1",$whereVars["reflot"])->where("LIN_ASIGL1",$bid->lin_asigl1)->update(["LIN_ASIGL1" => $lin]);
						}
						$lin++;
					}
				}

			}


            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }



}
