<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;

use App\Models\V5\FgOrtsec0;

use DB;

class CategoryController extends ApiLabelController
{
    protected  $ortsecRename = array("idcategory"=>"lin_ortsec0", "description"=>"des_ortsec0","order"=>"orden_ortsec0", "metadescription" => "meta_description_ortsec0", "metatitle" => "meta_titulo_ortsec0", "metacontent" => "meta_contenido_ortsec0", "urlfriendly" => "key_ortsec0" );
    protected  $rules = array('idcategory' => "required|numeric|max:999999",'description'   => "max:60|nullable", 'urlfriendly'  => "max:50|nullable",'order'   => "numeric|max:999999",'metadescription' => "max:155|nullable",'metatitle' => "max:67|nullable",'metacontent' => "nullable",);


    #si se amplia esto ampliar el where del get, las reglas de busqueda son diferentes a las reglas normales ya que si no, habria campos requeridos y no nos permitiria hacer busquedas por todo
    protected $searchRules = array('idcategory' => "filled|numeric|max:999999");

    public function postCategory(){
        $items =  request("items");
       return $this->createCategory( $items );
    }


    public function createCategory($items){
        try {
            DB::beginTransaction();
            $defaultValues=array("sub_ortsec0" => 0);
            $this->create($items, $this->rules, $this->ortsecRename, new FgOrtsec0(), $defaultValues);
            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }


    #
    public function getCategory(){
        return $this->showCategory(request("parameters"));
    }

    public function showCategory($whereVars){
        $ortsec0 =  New FgOrtsec0();
        $ortsec0 = $ortsec0->select("FGORTSEC0.*");
        $varAPI = array_flip($this->ortsecRename);
        $whereOrtsec0Rename = $this->getItems($this->ortsecRename , array("idcategory"));
        return $this->show($whereVars, $this->searchRules, $whereOrtsec0Rename, $ortsec0,  $varAPI);
    }


    public function putCategory(){
        $items =  request("items");
        return $this->updateCategory( $items );

    }

    public function updateCategory($items){
        try {
            DB::beginTransaction();
                 $this->update($items, $this->rules, $this->ortsecRename, new FgOrtsec0());
            DB::commit();
            return $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }

    }

    public function deleteCategory(){
        return $this->eraseCategory(request("parameters"));
    }

    public function eraseCategory($whereVars){
        try
        {

            DB::beginTransaction();
            $rules = $this->getItems($this->rules, array("idcategory"));
            $fgOrtsec0 =  New FgOrtsec0();
            $fgOrtsec0= $fgOrtsec0->where("sub_ortsec0","0");
            $whereOrtsec0Rename = $this->getItems($this->ortsecRename , array("idcategory"));
            $this->erase($whereVars, $rules, $whereOrtsec0Rename, $fgOrtsec0 );
            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }









}
