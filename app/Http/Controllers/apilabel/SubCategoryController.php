<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;

use App\Models\V5\FxSec;
use App\Models\V5\FgOrtsec1;

use DB;

class SubCategoryController extends ApiLabelController
{
    #arrays que sirve para traducir las variables que envian y las de busqueda
    protected  $secRename = array("idsubcategory"=>"cod_sec", "description"=>"des_sec", "metadescription" => "meta_description_sec", "metatitle" => "meta_titulo_sec", "metacontent" => "meta_contenido_sec", "urlfriendly" => "key_sec" );
    protected  $ortsec1Rename = array("idsubcategory" => "sec_ortsec1", "idcategory" => "lin_ortsec1", "order" => "orden_ortsec1"    );



    protected  $rules = array('idsubcategory' => "required|alpha_num|max:2", 'idcategory' => "required|numeric|max:999999",'description'   => "max:30|nullable", 'urlfriendly'  => "max:50|nullable",'order'   => "numeric|max:999999|nullable",'metadescription' => "max:155|nullable",'metatitle' => "max:67|nullable",'metacontent' => "nullable");


    protected $searchRules = array('idsubcategory' => "alpha_num|max:2", 'idcategory' => "numeric|max:999999");




    public function postSubCategory(){
        $items =  request("items");
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
}
