<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;



use App\Models\V5\FgLicit;
use App\Models\V5\FxCli;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgOrlic;


use DB;
use stdClass;

class BidderController extends ApiLabelController
{
    #arrays que sirve para traducir las variables que envian y las de busqueda
    protected  $renameExtra = array( "idoriginclient"=>"cod2_cli");
    protected  $rename = array("codbidder"=>"cod_licit", "idauction"=>"sub_licit" );

	protected $renameSpecialWhere = array('min_date' => "fec_licit", 'max_date' => "fec_licit" );
    protected  $rules = array('codbidder' => "required|numeric", "idoriginclient" => "required|max:8", "idauction" => "required|max:8" );


    public function postBidder(){
        $items =  request("items");
        return $this->createBidder( $items );
    }


    public function createBidder($items){
        try {
            DB::beginTransaction();

                if(empty($items) || empty($items[0])){
                    throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
                }
                $this->validatorArray($items, $this->rules);

                foreach($items as $key => $item){

					$client = FxCli::select("cod_cli,nvl(rsoc_cli, nom_cli) rsoc_cli")
					->addSelect("FGLICIT.COD_LICIT")
        			->leftjoin('FGLICIT', "FGLICIT.CLI_LICIT = FXCLI.COD_CLI  AND FGLICIT.EMP_LICIT = '" . Config::get("app.emp") . "'  AND FGLICIT.SUB_LICIT = '" . $item["idauction"]. "'")
					->where("cod2_cli", $item["idoriginclient"])->first();

					#si no existe el cliente devolvemos error
					if(empty($client)){
						$errorsItem["item_".($key +1)] = $item;
						throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
					}

					# si ya tiene paleta se la reasignamos
					if(!empty($client->cod_licit)){

						unset($items[$key]);
						DB::select(" call CAMBIAR_LICIT(:emp, :sub, :old, :new, 'API')",
							array(
								"emp" => Config::get("app.emp"),
								"sub" => $item["idauction"],
								"old" => $client->cod_licit,
								"new" => $item["codbidder"]
							)
						);
					}else{
						$items[$key]["cli_licit"] =  $client->cod_cli;
						$items[$key]["rsoc_licit"] =  $client->rsoc_cli;
					}





                    #$this->setBidder( $item, $licits, $lots[$item["idoriginlot"]], $bidders);
				}

				if(count($items) > 0){
					#añadimos los campos de la tabla
					$this->rename["cli_licit"] = "cli_licit";
					$this->rename["rsoc_licit"] = "rsoc_licit";
					$this->create($items, $this->rules, $this->rename, new FgLicit());
				}



            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }




    public function getBidder(){
        return $this->showBidder(request("parameters"));
    }

    public function showBidder($whereVars){

        $rename = array_merge($this->rename, $this->renameExtra);
        $varAPI = array_flip( $rename);
        $searchRules = $this->cleanRequired($this->rules, array("idauction"));

        $licit = new FgLicit();
        #haremos select solo con los campos que necesitamos, para eso uso los del rename
        $select = implode(",",  $rename);

        $licit = $licit->addselect($select)->joinCli();

        return $this->show($whereVars, $searchRules,  $rename,  $licit,  $varAPI);
    }

    public function deleteBidder(){
        return $this->eraseBidder(request("parameters"));
    }

    public function eraseBidder($whereVars){
        try
        {

			DB::beginTransaction();
			#comprobamos que existan, si no existe no hacemos la busqueda y ya devolverá error la función erase al comprobar las reglas
			if(!empty($whereVars["idauction"]) && !empty($whereVars["codbidder"]))
			{
				#buscamos pujas u ordenes para avisar que no se puede eliminar un licitador
				$pujas = FgAsigl1::select("count(sub_asigl1) as cuantos")->where("sub_asigl1", $whereVars["idauction"])->where("licit_asigl1", $whereVars["codbidder"])->first();
				$ordenes = FgOrlic::select("count(sub_orlic) as cuantos")->where("sub_orlic", $whereVars["idauction"])->where("licit_orlic", $whereVars["codbidder"])->first();

				if ($pujas->cuantos > 0 || $ordenes->cuantos > 0){
					throw new ApiLabelException(trans('apilabel-app.errors.exist_bids'));
				}

			}

			$whereRules = $this->getItems($this->rules, array("idauction", "codbidder"));
			$this->erase($whereVars, $whereRules, $this->rename, new FgLicit());


            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }



}
