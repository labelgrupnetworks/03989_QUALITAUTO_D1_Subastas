<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;


use App\Models\V5\FgAsigl1;
use App\Models\V5\FgLicit;

use App\Models\V5\FgAsigl0;

use DB;
use stdClass;

class BidController extends ApiLabelController
{
    #arrays que sirve para traducir las variables que envian y las de busqueda
    protected  $renameExtra = array("idoriginlot"=>"idorigen_asigl0", "idoriginclient"=>"cod2_cli");
    protected  $rename = array("licit"=>"licit_asigl1","lin"=>"lin_asigl1", "idauction"=>"sub_asigl1",  "ref"=>"ref_asigl1",  "bid"=>"imp_asigl1",  "date" => "fec_asigl1" , "type" => "pujrep_asigl1", "automatic" => "type_asigl1" );

	protected $renameSpecialWhere = array('min_date' => "fec_asigl1", 'max_date' => "fec_asigl1" );
    protected  $rules = array('idoriginlot' => "required|max:255", "idauction" => "required|max:8","idoriginclient" => "required|max:8", "bid" => "required|numeric", "date" => "required|date_format:Y-m-d H:i:s" );

    public function postBid(){
        $items =  request("items");
        return $this->createBid( $items );
    }




    public function getBid(){
        return $this->showBid(request("parameters"));
    }

    public function showBid($whereVars){



        $rename = array_merge($this->rename, $this->renameExtra);
        $varAPI = array_flip( $rename);
        $searchRules = $this->cleanRequired($this->rules, array("idauction"));


		$fgasigl1 = new FgAsigl1();
		#specials where, nos permite buscar entr fechas
		$fgasigl1 = $this->whereSpecial($whereVars, $this->renameSpecialWhere, $fgasigl1);

         #haremos select solo con los campos que necesitamos, para eso uso los del rename
		$select = implode(",",  $rename);
		#si tienen configurado que les devolvemos las pujas con dummy (licitador 9999), estas pujas deben hacerse con left join ya que no hay usuari oasociado
		if(\Config::get("app.getDummyApiBid")){
			$fgasigl1 = $fgasigl1->leftjoinCli();
		}else{
			$fgasigl1 = $fgasigl1->joinCli();
		}

        $fgasigl1 = $fgasigl1->addselect($select)->JoinAsigl0()->orderby("ref_asigl1")->orderby("imp_asigl1")->orderby("lin_asigl1");

        return $this->show($whereVars, $searchRules,  $rename,  $fgasigl1,  $varAPI);



    }
#DE MOMENTO NO SE DESARRROLLA, SOL OEL GET, EL CÓDIGO QUE HAY ES HEREDADO DIRECTAMENTE DE ORDER POR LO QUE SE DEBE REVISAR
/*
    public function deleteBid(){
        return $this->eraseBid(request("parameters"));
    }

    public function eraseBid($whereVars){
        try
        {

			DB::beginTransaction();
			$fgasigl1 = new FgAsigl1();
			if(empty($whereVars["ref"]) ||  empty($whereVars["licit"]) ){
				$whereRules = $this->getItems($this->rules, array("idoriginlot", "idauction", "idoriginclient"));
				$this->validator($whereVars,  $whereRules);
				$fgasigl1 = $fgasigl1->where("idorigen_asigl0",$whereVars["idoriginlot"])->where("sub_asigl1",$whereVars["idauction"])->where("cod2_cli",$whereVars["idoriginclient"]);
            	$orden = $fgasigl1->addselect("sub_asigl1,ref_asigl1, licit_asigl1")->joinCli()->JoinAsigl0()->first();
			}else{ #si viene de ladmin, pasaran licit y ref
				$fgasigl1 = $fgasigl1->where("ref_asigl1",$whereVars["ref"])->where("sub_asigl1",$whereVars["idauction"])->where("licit_asigl1",$whereVars["licit"]);
				$orden = $fgasigl1->addselect("sub_asigl1,ref_asigl1, licit_asigl1")->first();
			}




            #si existe la orden la borramos
            if(!empty($orden)){
                $deleteOrlic = new FgAsigl1();
                $deleteOrlic->where("sub_asigl1",$orden->sub_asigl1)->where("ref_asigl1",$orden->ref_asigl1)->where("licit_asigl1",$orden->licit_asigl1)->delete();
            }else{  # si no hay orden con esos identificadores
                    throw new ApiLabelException(trans('apilabel-app.errors.delete'));
            }

            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }
*/


}
