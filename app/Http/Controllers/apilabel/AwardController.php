<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;


use App\Models\V5\FgCsub;
use App\Models\V5\FgLicit;

use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgHces1;


use DB;
use stdClass;

class AwardController extends ApiLabelController
{
    #arrays que sirve para traducir las variables que envian y las de busqueda
    protected  $renameExtra = array("idoriginlot"=>"idorigen_asigl0", "idoriginclient"=>"cod2_cli");
    protected  $rename = array("licit"=>"licit_csub", "idauction"=>"sub_csub",  "ref"=>"ref_csub",  "bid"=>"himp_csub", "commission" => "base_csub", "date" => "fecha_csub", "clifac" => "clifac_csub", "invoice" => "fac_csub", "serialpay" => "afral_csub", "numberpay" => "nfral_csub"  );
	protected  $renameAsigl1 = array("licit"=>"licit_asigl1","lin"=>"lin_asigl1", "idauction"=>"sub_asigl1",  "ref"=>"ref_asigl1",  "bid"=>"imp_asigl1", "type" => "type_asigl1", "date" => "fec_asigl1","hour" => "hora_asigl1");

    protected  $rules = array('idoriginlot' => "required|max:255", "idauction" => "required|max:8","idoriginclient" => "required|max:8", "bid" => "required|numeric", "date" => "date_format:Y-m-d H:i:s|nullable", "hour" => "date_format:H:i:s|nullable","commission" => "numeric|nullable", "invoice" => "alpha_num|max:1|nullable", "serialpay" => "alpha_num|max:3|nullable", "numberpay" => "numeric|nullable|max:99999999" );
	protected $maxCodLicit;
    public function postAward(){
        $items =  request("items");
        return $this->createAward( $items );
    }


    public function createAward($items){
        try {
            DB::beginTransaction();

                if(empty($items) || empty($items[0])){
                    throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
                }
                $this->validatorArray($items, $this->rules);
                $idAuction = $items[0]["idauction"];

				$numcliweb = DB::table('fgprmsub')
                ->select('numlicweb_prmsub')
                ->where('EMP_PRMSUB', Config::get('app.emp'))
                ->first();

                #máximo código de licitador actual
               	$this->maxCodLicit= FgLicit::select("max(cod_licit) max_cod_licit")->where("sub_licit",$idAuction )->where("cod_licit","!=", \Config::get("app.dummy_bidder"))->where("cod_licit","<", \Config::get("app.subalia_min_licit"))->first()->max_cod_licit;


                if(empty($this->maxCodLicit) || (!empty($numcliweb) && !empty($numcliweb->numlicweb_prmsub) &&  $this->maxCodLicit < $numcliweb->numlicweb_prmsub)){
                    if(!empty($numcliweb) && !empty($numcliweb->numlicweb_prmsub) ){
						$this->maxCodLicit = $numcliweb->numlicweb_prmsub-1; #empieza por el mil y se le sumara 1 antes de asignarselo al cliente por eso se lo restamos ahora
					}else{
						$this->maxCodLicit = 1000-1;
					}

                }
				$lots = FgAsigl0::arrayByIdOrigin($idAuction);
				$lotsByRef = FgAsigl0::arrayByRef($idAuction);
                $licits = FgLicit::getLicitsSubIdOrigin($idAuction);
                $awards = FgCsub::arrayByRef($idAuction);

                $update = array();
                $create=array();
                foreach($items as $key => $item){
                    $idOriginLot = $item["idoriginlot"];
                    #todas las adjudicaciones han de ser de la misma subasta
                    if($idAuction != $item["idauction"]){
                        throw new ApiLabelException(trans('apilabel-app.errors.different_auctions'));
                    }


					#si no viene el licitador se busca por idorigen, El ADMIN enviará licitador, la API idorigin
					if(empty($item["licit"] )){
						#Obtenemos el código de licitador, si no está en el listado se le asigna uno
						$licit = $this->getLicit($licits, $item, $key);
						$item["licit"] = $licit["cod_licit"];
						$item["clifac"] = $licit["cod_cli"];
					}

					#si no viene la referencia del lote se consigue mediante el idorigin, El admin enviará la referencia, la API idorigin
					if(empty($item["ref"] )){
						 #si no existe el lote devolvemos error
						 if(empty($lots[$idOriginLot])){
							$errorsItem["item_".($key +1)] = array("idoriginlot" => $idOriginLot);
							throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
						}
						$item["ref"] = $lots[$idOriginLot]["ref_asigl0"];
					}else{
						# Si han mandado la referencia, comprobamos que exista
						if(empty($lotsByRef[$item["ref"]])){
							$errorsItem["item_".($key +1)] = array("idoriginlot" => $idOriginLot);
							throw new ApiLabelException(trans('apilabel-app.errors.no_match'), $errorsItem);
						}
					}
					if(empty($item["date"])){
						$item["date"] = date("Y-m-d H:i:s");
					}

                    if (!empty($awards[$item["ref"]]) ){
                        $update[] = $item;
                    }else{
                        $create[] = $item;
					}

					#crear puja si no existe.
					$this->createBid($item);
					#indicar el implic para que se marque como vendido en el grid
					$this->updateImplic($lotsByRef[$item["ref"]]["numhces_asigl0"], $lotsByRef[$item["ref"]]["linhces_asigl0"], $item["bid"],"S");
					#cerrar el lote si no está cerrado
					FgAsigl0::where("sub_asigl0",$item["idauction"])->where("ref_asigl0", $item["ref"])->update(["cerrado_asigl0" => "S"]);
		     }

                $this->update($update, $this->rules, $this->rename, new FgCsub());
                $this->create($create, $this->rules, $this->rename, new FgCsub());

            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }



    #
    public function getAward(){
        return $this->showAward(request("parameters"));
    }

    public function showAward($whereVars){

        $rename = array_merge($this->rename, $this->renameExtra);
        $varAPI = array_flip( $rename);
        $searchRules = $this->cleanRequired($this->rules, array("idauction"));


        $csub = new FgCsub();
         #haremos select solo con los campos que necesitamos, para eso uso los del rename
        $select = implode(",",  $rename);

        $csub = $csub->addselect($select)->joinCli()->JoinAsigl0();

        return $this->show($whereVars, $searchRules,  $rename,  $csub,  $varAPI);



    }

    public function deleteAward(){
        return $this->eraseAward(request("parameters"));
    }

    public function eraseAward($whereVars){
        try
        {

			DB::beginTransaction();
			$csub = new FgCsub();
			#si viene de la API, en la API no se sabe la ref ni el licit , si no idoriginLot e idOriginClient
			if(empty($whereVars["ref"]) ||  empty($whereVars["licit"]) ){
				$whereRules = $this->getItems($this->rules, array("idoriginlot", "idauction"));
				$this->validator($whereVars,  $whereRules);
				$csub = $csub->where("idorigen_asigl0",$whereVars["idoriginlot"])->where("sub_csub",$whereVars["idauction"]);
            	$award = $csub->addselect("sub_csub,ref_csub, licit_csub, himp_csub, numhces_asigl0, linhces_asigl0")->joinCli()->JoinAsigl0()->first();
			}else{ #si viene de ladmin, pasaran licit y ref
				$csub = $csub->where("ref_csub",$whereVars["ref"])->where("sub_csub",$whereVars["idauction"])->where("licit_csub",$whereVars["licit"]);
				$award = $csub->addselect("sub_csub,ref_csub, licit_csub, himp_csub, numhces_asigl0, linhces_asigl0")->JoinAsigl0()->first();
			}




            #si existe la award la borramos
            if(!empty($award)){
                $deleteAward = new FgCsub();
				$deleteAward->where("sub_csub",$award->sub_csub)->where("ref_csub",$award->ref_csub)->where("licit_csub",$award->licit_csub)->delete();
				$this->updateImplic($award->numhces_asigl0, $award->linhces_asigl0 ,0,"N");
				$this->deleteBid($award->sub_csub, $award->ref_csub);
            }else{  # si no hay award con esos identificadores
				if(empty($whereVars["ref"]) ||  empty($whereVars["licit"]) ){
					$errorsItem["item_1"] = array("idoriginlot" => $whereVars["idoriginlot"],"idauction" => $whereVars["idauction"] );
				}else{
					$errorsItem["item_1"] = array("ref" => $whereVars["ref"],"idauction" => $whereVars["idauction"] ,"licit" => $whereVars["licit"]  );
				}
                    throw new ApiLabelException(trans('apilabel-app.errors.delete'),$errorsItem);
            }

            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


	}

	private function updateImplic($num, $lin, $implic,$licit){
		#updateo implic de hces1, en

		FgHces1::where("num_hces1",$num )->where("lin_hces1", $lin)->update(["implic_hces1" => $implic, "lic_hces1" => $licit]);

	}

	private function createBid($lot){
		if(!empty($lot["date"]) && empty($lot["hour"])){
			$lot["hour"] = substr($lot["date"],-8);
		}
		$bidUser = FgAsigl1::select(" lin_asigl1")
						->where("sub_asigl1", $lot["idauction"])
						->where("ref_asigl1", $lot["ref"])
						->where("licit_asigl1", $lot["licit"])
						->where("imp_asigl1", $lot["bid"])
						->first();


		#si no existe la puja del usuario creamos una
		if(empty($bidUser)){
			#eliminamos si hubiera una puja que ya se hubiera creado por asignación de lote
			$this->deleteBid($lot["idauction"], $lot["ref"]);
			#cogemos la linea máxima
			$bid = FgAsigl1::select(" max(lin_asigl1) maxlin")
							->where("sub_asigl1", $lot["idauction"])
							->where("ref_asigl1", $lot["ref"])
							->groupby("ref_asigl1")
							->first();


			#marcamos esta puja como Z para indicar que es una puja creada por la API al adjudicar un lote y no encontrar una puja por ese importe
			$lot["type"] = FgAsigl1::TYPE_AWARD;
			if(empty($bid) ){
				$lot["lin"] = 1;
			}else{
				$lot["lin"] = $bid->maxlin +1;
			}
			#borramos por si hubiera una puja de bid

			$this->create([$lot], $this->rules, $this->renameAsigl1, new FgAsigl1());
		}

		//$renameAsigl1 = array("licit"=>"licit_asigl1","lin"=>"lin_asigl1", "idauction"=>"sub_asigl1",  "ref"=>"ref_asigl1",  "bid"=>"imp_asigl1", "type" => "type_asigl1", "date" => "fec_asigl1");


	}

	private function deleteBid($sub,$ref){

		#si la puja tiene Z la borramso ya que es la creada por la API, si no es que ya existia y no hay que borrarla
		FgAsigl1::where("type_asigl1","Z")
				->where("sub_asigl1",$sub)
				->where("ref_asigl1",$ref)
				->delete();




	}
}
