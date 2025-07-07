<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;


use App\Models\V5\FgOrlic;
use App\Models\V5\FgLicit;

use App\Models\V5\FgAsigl0;

use DB;
use stdClass;

class OrderController extends ApiLabelController
{
    #arrays que sirve para traducir las variables que envian y las de busqueda
    protected  $renameExtra = array("idoriginlot"=>"idorigen_asigl0", "idoriginclient"=>"cod2_cli");
    protected  $rename = array("licit"=>"licit_orlic","lin"=>"lin_orlic", "idauction"=>"sub_orlic",  "ref"=>"ref_orlic",  "order"=>"himp_orlic", "type" => "tipop_orlic", "date" => "fec_orlic" , "phone1" => "tel1_orlic", "phone2" => "tel2_orlic",  "phone3" => "tel3_orlic", "num_award_conditional" => "num_conditional_orlic", "lots_list_conditional" => "lots_conditional_orlic");

	protected $renameSpecialWhere = array('min_date' => "fec_orlic", 'max_date' => "fec_orlic" );
	#fecha debe ser obligatoria
	protected  $rules = [
		'idoriginlot' => "required|max:255",
		"idauction" => "required|max:8",
		"idoriginclient" => "required|max:8",
		"order" => "required|numeric",
		"type" => "alpha|max:1|nullable",
		"date" => "required|date_format:Y-m-d H:i:s",
		"phone1" => "alpha_num|max:20|nullable",
		"phone2" => "alpha_num|max:20|nullable",
		"phone3" => "alpha_num|max:20|nullable",
		"phone3" => "alpha_num|max:20|nullable",
		"num_award_conditional" => "integer"
	];


	public function postOrder(){
        $items =  request("items");
        return $this->createOrder( $items );
    }


    public function createOrder($items){
        try {
            DB::beginTransaction();

                if(empty($items) || empty($items[0])){
                    throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
                }
                $this->validatorArray($items, $this->rules);
                $idAuction = $items[0]["idauction"];

                #máximo código de licitador actual
                $this->maxCodLicit= FgLicit::getMaxCodLicit($idAuction);

                $lots = FgAsigl0::arrayByIdOrigin($idAuction);
                $licits = FgLicit::getLicitsSubIdOrigin($idAuction);
                $orders = FgOrlic::arrayByRef($idAuction);

                $maxLinOrder= FgOrlic::getMaxLinArrayRef($idAuction);
                $update = array();
                $create=array();
                foreach($items as $key => $item){
                    $idOriginLot = $item["idoriginlot"];
                    #todos las ordenes han de ser de la misma subasta
                    if($idAuction != $item["idauction"]){
                        throw new ApiLabelException(trans('apilabel-app.errors.different_auctions'));
                    }


					#si no viene el licitador se busca por idorigen, El ADMIN enviará siempre licitador, la API si no envia licitador usará idorigin
					if(empty($item["licit"] )){
						#Obtenemos el código de licitador, si no está en el listado se le asigna uno
						$licit = $this->getLicit($licits, $item, $key);
						$item["licit"] = $licit["cod_licit"];

					}
					#si no viene la referencia del lote se consigue mediante el idorigin, El admin enviará la referencia, la API idorigin
					if(empty($item["ref"] )){
						 #si no existe el lote devolvemos error
						 if(empty($lots[$idOriginLot])){
							$errorsItem["item_".($key +1)] = array("idoriginlot" => $idOriginLot);
							throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
						}
						$item["ref"] = $lots[$idOriginLot]["ref_asigl0"];
					}
					$ref =(string)$item["ref"];


					#debemos crear la hora a partir de la fecha, la fecha debe ser obligatoria pero la comprobación se hara mas adelante en el create o update
					if(!empty($item["date"])){
						$item["hora_orlic"] = substr($item["date"],-8);
					}
					if(!empty($item["lots_list_conditional"])){

						#debemos comprobar que llega un array de elementos
						if(!is_array($item["lots_list_conditional"]))
						{
							$errorsItem["item_".($key +1)] =  array("lots_list_conditional" => $item["lots_list_conditional"]);
							throw new ApiLabelException(trans('apilabel-app.errors.no_array', ["field" =>"lots_list_conditional"]), $errorsItem);
						}else{
							#debemos comprobar que los elementos dentro del array deben ser de tipo numerico, se deben permitir decimales
							foreach($item["lots_list_conditional"] as $refLot){
									if( !is_numeric($refLot) ){
										$errorsItem["item_".($key +1)] =  array("lots_list_conditional" => $item["lots_list_conditional"]);
										throw new ApiLabelException(trans('apilabel-app.errors.no_numeric', ["field" =>"lots_list_conditional"]), $errorsItem);
									}
							}
						}

						#convertimos el array en un string separado por pipes
						$item["lots_list_conditional"] = implode("|", $item["lots_list_conditional"] );
					}


                    if (!empty($orders[$ref]) && !empty($orders[$ref][$item["licit"]]) ){
                        $update[] = $item;
                    }else{
                        if(!empty($maxLinOrder[$ref])){
                            $maxLinOrder[$ref]++;
                        }else{
                            $maxLinOrder[$ref] = 1;
                        }
                        $item["lin"] = $maxLinOrder[$ref];
                        $create[] = $item;
                    }



                    #$this->setOrder( $item, $licits, $lots[$item["idoriginlot"]], $orders);
				}
				#añadimos la hora, ya que es necesaria para que funcione la web
				$this->rename["hora_orlic"] = "hora_orlic";

                $this->update($update, $this->rules, $this->rename, new FgOrlic());
                $this->create($create, $this->rules, $this->rename, new FgOrlic());


            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }



    #
    public function getOrder(){
        return $this->showOrder(request("parameters"));
    }

    public function showOrder($whereVars){
		try
        {
			$rename = array_merge($this->rename, $this->renameExtra);
			$varAPI = array_flip( $rename);
			$searchRules = $this->cleanRequired($this->rules, array("idauction"));


			$orlic = new FgOrlic();
			#haremos select solo con los campos que necesitamos, para eso uso los del rename
			$select = implode(",",  $rename);

			#specials where, nos permite buscar entr fechas
			$orlic = $this->whereSpecial($whereVars, $this->renameSpecialWhere, $orlic);

			$orlic = $orlic->addselect($select)->joinCli()->JoinAsigl0()->orderby("ref_orlic")->orderby("lin_orlic");

			return $this->show($whereVars, $searchRules,  $rename,  $orlic,  $varAPI);
		}catch (\Exception $e){

            return $this->exceptionApi($e);
        }


    }

    public function deleteOrder(){
        return $this->eraseOrder(request("parameters"));
    }

    public function eraseOrder($whereVars){
        try
        {

			DB::beginTransaction();
			$orlic = new FgOrlic();
			if(empty($whereVars["ref"]) ||  empty($whereVars["licit"]) ){
				$whereRules = $this->getItems($this->rules, array("idoriginlot", "idauction", "idoriginclient"));
				$this->validator($whereVars,  $whereRules);
				$orlic = $orlic->where("idorigen_asigl0",$whereVars["idoriginlot"])->where("sub_orlic",$whereVars["idauction"])->where("cod2_cli",$whereVars["idoriginclient"]);
            	$orden = $orlic->addselect("sub_orlic,ref_orlic, licit_orlic")->joinCli()->JoinAsigl0()->first();
			}else{ #si viene de ladmin, pasaran licit y ref
				$orlic = $orlic->where("ref_orlic",$whereVars["ref"])->where("sub_orlic",$whereVars["idauction"])->where("licit_orlic",$whereVars["licit"]);
				$orden = $orlic->addselect("sub_orlic,ref_orlic, licit_orlic")->first();
			}




            #si existe la orden la borramos
            if(!empty($orden)){
                $deleteOrlic = new FgOrlic();
                $deleteOrlic->where("sub_orlic",$orden->sub_orlic)->where("ref_orlic",$orden->ref_orlic)->where("licit_orlic",$orden->licit_orlic)->delete();
            }else{  # si no hay orden con esos identificadores

					#se puede indicar que no se envie alerta de excepción con el web config ApiNoErrorDelete, si no existe ese web config si que enviamos la alerta
					if(empty(\Config::get("app.ApiNoErrorDeleteNotExistOrder"))){
						throw new ApiLabelException(trans('apilabel-app.errors.delete'));
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
