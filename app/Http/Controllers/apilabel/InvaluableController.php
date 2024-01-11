<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;

use App\Http\Controllers\externalAggregator\Invaluable\House;
use App\Models\V5\FgAsigl0;


class InvaluableController extends ApiLabelController
{
	protected  $rulesCatalog = array('idauction' => "required|alpha_num|max:8", 'reference' => "required|alpha_num|max:3");
	protected  $rulesLot = array('idauction' => "required|alpha_num|max:8", 'reference' => "required|alpha_num|max:3", 'reflot' => "required|numeric|max:999999999");
	#crea y updatea el catalogo
	public function catalog($parameters = array()){
		try {
			if(empty($parameters)){
				$parameters = request("parameters");
				if(empty($parameters)){#las pruebas con test envian la variable items
					$parameters = request("items");
				}
			}
			//\Log::info(print_r($parameters,true));
			$this->validator($parameters, $this->rulesCatalog);
			$house = new House();

			$resJson = $house->catalogs( $parameters["idauction"], $parameters["reference"]);
			$res = json_decode($resJson);
			if($res->success){
				return  $this->responseSuccsess();
			}else{
				throw new ApiLabelException($res->message);
			}


		} catch(\Exception $e){
			return $this->exceptionApi($e);
		}
    }

	public function lot($parameters = array()){
		try {
			if(empty($parameters)){
				$parameters = request("parameters");
				if(empty($parameters)){#las pruebas con test envian la variable items
					$parameters = request("items");
				}
			}
			$this->validator($parameters, $this->rulesCatalog);
			$house = new House();
			$resJson = $house->catalogLots( $parameters["idauction"], $parameters["reference"], $parameters["reflot"]);
			$res = json_decode($resJson);
			
			if($res->success){
				return  $this->responseSuccsess();
			}else{
				throw new ApiLabelException($res->message);
			}


		} catch(\Exception $e){
			return $this->exceptionApi($e);
		}
    }

	public function deleteLot(){
		try {
			$parameters = request("parameters");
			$this->validator($parameters, $this->rulesCatalog);

			$lot = FgAsigl0::select("oculto_asigl0, retirado_asigl0")->where("sub_asigl0",$parameters["idauction"] )->where("ref_asigl0",$parameters["reflot"] )->first();

			#si esta vacio dejaremos continuar para que puedan dar de baja un lote en invaluable que ya no exista
			if (!empty($lot) && ($lot->oculto_asigl0 !='S' && $lot->retirado_asigl0 != 'S')){
				throw new ApiLabelException("El lote debe estar retirado u oculto para poder eliminarse de Invaluable");
			}
			$house = new House();
			$resJson = $house->deleteLot( $parameters["idauction"], $parameters["reference"], $parameters["reflot"]);
			$res = json_decode($resJson);
			if($res->success){
				return  $this->responseSuccsess();
			}else{
				throw new ApiLabelException($res->message);
			}


		} catch(\Exception $e){
			return $this->exceptionApi($e);
		}
    }

	/*
    #arrays que sirve para traducir las variables que envian y las de busqueda
    protected  $secRename = array("idsubcategory"=>"cod_sec", "description"=>"des_sec", "metadescription" => "meta_description_sec", "metatitle" => "meta_titulo_sec", "metacontent" => "meta_contenido_sec", "urlfriendly" => "key_sec" );
    protected  $ortsec1Rename = array("idsubcategory" => "sec_ortsec1", "idcategory" => "lin_ortsec1", "order" => "orden_ortsec1"    );



    protected  $rules = array('idsubcategory' => "required|alpha_num|max:2", 'idcategory' => "required|numeric|max:999999",'description'   => "max:30|nullable", 'urlfriendly'  => "max:50|nullable",'order'   => "numeric|max:999999|nullable",'metadescription' => "max:155|nullable",'metatitle' => "max:67|nullable",'metacontent' => "nullable");


    protected $searchRules = array('idsubcategory' => "alpha_num|max:2", 'idcategory' => "numeric|max:999999");

	public function postCatalog(){
		$parameters = request("parameters");
		$this->validatorArray($items, $rules);
       return $this->createSubCategory( $items );
    }








    public function createSubCategory($items){
        try {
            DB::beginTransaction();
                #creamso registros en FXSEC
                $this->create($items, $this->rules, $this->secRename, new FxSec());
                #creamos registros en FGORTSEC
                $defaultValues=array("sub_ortsec1" => 0);
                $this->create($items,$this->rules, $this->ortsec1Rename, new FgOrtsec1(), $defaultValues);
            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }

    #
    public function getSubCategory(){
        return $this->showSubCategory(request("parameters"));
    }

    public function showSubCategory($whereVars){
            $sec =  New FxSec();
            $sec = $sec->select("FGORTSEC1.lin_ortsec1,FGORTSEC1.orden_ortsec1,FXSEC.* ")->joinFgOrtsecFxSec();
            $varAPIOrtsec = $this->getItems($this->ortsec1Rename , array("idcategory", "order"));
            $varAPI = array_flip(array_merge($varAPIOrtsec, $this->secRename));
            $whereOrtsecRename = $this->getItems($this->ortsec1Rename , array("idcategory", "idsubcategory"));
            return $this->show($whereVars, $this->searchRules, $whereOrtsecRename, $sec,  $varAPI);
    }


    public function putSubCategory(){
        $items =  request("items");
        return $this->updateSubCategory( $items );

    }

    public function updateSubCategory($items){
        try {
            DB::beginTransaction();
                $this->update($items, $this->rules, $this->secRename, new Fxsec());
                $this->update($items, $this->rules, $this->ortsec1Rename, new FgOrtsec1());
            DB::commit();
            return $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }

    }

    public function deleteSubCategory(){
        return $this->eraseSubCategory(request("parameters"));
    }

    public function eraseSubCategory($whereVars){
        try
        {
            DB::beginTransaction();
             #Borrar FXSEC
                $rules = $this->getItems($this->rules, array("idcategory", "idsubcategory"));
                $whereSecRename = $this->getItems($this->secRename , array("idsubcategory"));
                $this->erase($whereVars, $rules, $whereSecRename, New Fxsec() );

            #Borrar FGORTSEC1
                $fgOrtsec1 =  New FgOrtsec1();
                $fgOrtsec1 = $fgOrtsec1->where("sub_ortsec1","0");
                $whereOrtsecRename = $this->getItems($this->ortsec1Rename , array("idcategory", "idsubcategory"));
                $this->erase($whereVars, $rules, $whereOrtsecRename, $fgOrtsec1 );

            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }
	*/
}
