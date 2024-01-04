<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;


use App\Models\V5\FgCaracteristicas_Value;
use App\Models\V5\FgCaracteristicas_Value_Lang;

use DB;
use stdClass;

class FeatureValueController extends ApiLabelController
{
    #arrays que sirve para traducir las variables que envian y las de busqueda
    protected  $rename = array("idfeaturevalue"=>"id_caracteristicas_value","idfeature"=>"idcar_caracteristicas_value",  "value"=>"value_caracteristicas_value");
	protected  $rename_lang = array("idfeaturevalue"=>"idcarval_car_val_lang",  "value"=>"value_car_val_lang", "lang" => "lang_car_val_lang");

    protected  $rules = array('idfeaturevalue' => "required|numeric", "idfeature" => "required|numeric","value" => "required|max:2000" );
    protected  $rules_lang = array('idfeaturevalue' => "required|numeric", "value" => "required|max:2000", "lang" => "required" );

    public function postFeatureValue(){
        $items =  request("items");
       return $this->createFeatureValue( $items );
    }


    public function createFeatureValue($items){
        try {
            DB::beginTransaction();

                if(empty($items) || empty($items[0])){
                    throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
                }

				$items_lang = array();
				foreach($items as $key => $item){

					if(!empty($item["lang"])){

						$item["lang"] = \Tools::getLanguageComplete($item["lang"]);
						$items_lang[]=$item;
						unset($items[$key]);
					}
				}

                #creamos registros en fgCaracteristicas_value
				if(!empty($items)){
                	$this->create($items, $this->rules, $this->rename, new FgCaracteristicas_Value());
				}

				if(!empty($items_lang)){
					$this->create($items_lang, $this->rules_lang, $this->rename_lang, new FgCaracteristicas_Value_Lang());
				}

            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }

    #
    public function getFeatureValue(){
        return $this->showFeatureValue(request("parameters"));
    }

    public function showFeatureValue($whereVars){
        $varAPI = array_flip($this->rename);
        $searchRules = $this->cleanRequired($this->rules, array("idfeature"));
       // $whereOrtsec0Rename = $this->getItems($this->rename , array("idcategory"));
        return $this->show($whereVars, $searchRules, $this->rename,  New FgCaracteristicas_Value(),  $varAPI);



    }


    public function putFeatureValue(){
        $items =  request("items");
        return $this->updateFeatureValue( $items );

    }

    public function updateFeatureValue($items){
        try {
            DB::beginTransaction();
				$items_lang = array();
				foreach($items as $key => $item){

					if(!empty($item["lang"])){
						$item["lang"] = \Tools::getLanguageComplete($item["lang"]);
						$items_lang[]=$item;
						unset($items[$key]);
					}
				}

                #creamos registros en fgCaracteristicas_value
				if(!empty($items)){
                	$deleteRules = $this->cleanRequired($this->rules, array("idfeaturevalue"));
                 	$this->update($items, $deleteRules, $this->rename, new FgCaracteristicas_Value());
				}
				if(!empty($items_lang)){
					$this->update($items_lang, $this->rules_lang, $this->rename_lang, new FgCaracteristicas_Value_Lang());
				}

            DB::commit();
            return $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }

    }





    public function deleteFeatureValue(){
        return $this->eraseFeatureValue(request("parameters"));
    }

    public function eraseFeatureValue($whereVars){
        try
        {

            DB::beginTransaction();
            $whereRules = $this->getItems($this->rules, array("idfeaturevalue"));
            $whereRename = $this->getItems($this->rename , array("idfeaturevalue"));
            $this->erase($whereVars, $whereRules, $whereRename, new FgCaracteristicas_Value(), false);


            $whereRenameLang = $this->getItems($this->rename_lang , array("idfeaturevalue"));
            $this->erase($whereVars, $whereRules, $whereRenameLang, new FgCaracteristicas_Value_Lang(), false);
            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }

}
