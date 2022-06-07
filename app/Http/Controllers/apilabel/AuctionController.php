<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;

use App\Models\V5\FgSub;
use App\Models\V5\AucSessions;

use DB;

class AuctionController extends ApiLabelController
{
    protected  $auctionRename = array("idauction"=>"cod_sub", "name"=>"des_sub","type"=>"tipo_sub", "status" => "subc_sub", "description" => "descdet_sub", "visiblebids" => "subabierta_sub",  "startauction"=> "dfec_sub", "finishauction" => "hfec_sub", "startorders"=> "dfecorlic_sub", "finishorders" => "hfecorlic_sub", "metatitle" => "webmetat_sub", "metadescription" => "webmetad_sub", "phoneorders" => "ordentel_sub"    );
	protected  $sessionRename = array("idauction"=>'"auction"', "name"=>'"name"',"reference"=> '"reference"', "description" => '"description"',  "start"=> '"start"', "finish" => '"end"', "startorders"=> '"orders_start"', "finishorders" => '"orders_end"', "firstlot" => '"init_lot"', "lastlot" => '"end_lot"'   );

	protected  $rules = array('idauction' => "required|alpha_num|max:8",'name'   => "required|max:40", 'type'  => "required|alpha_num|max:1", 'status'  => "required|alpha_num|max:1",'visiblebids'   => "nullable|alpha_num|max:1",'startauction' => "date_format:Y-m-d H:i:s|nullable", 'finishauction' => "date_format:Y-m-d H:i:s|nullable", 'startorders' => "date_format:Y-m-d H:i:s|nullable",  'finishorders' => "date_format:Y-m-d H:i:s|nullable" );
	protected  $sessionrules = array('idauction' => "required|alpha_num|max:8", 'reference' => "required|alpha_num|max:3", 'name'   => "required|max:40", 'start' => "date_format:Y-m-d H:i:s|nullable", 'finish' => "date_format:Y-m-d H:i:s|nullable", 'startorders' => "date_format:Y-m-d H:i:s|nullable",  'finishorders' => "date_format:Y-m-d H:i:s|nullable" ,  'firstlot' => "required|numeric|max:999999" ,  'lastlot' => "required|numeric|max:999999",'phoneorders' => "nullable|numeric", );

	#hasta aqui está hecho

    #si se amplia esto ampliar el where del get, las reglas de busqueda son diferentes a las reglas normales ya que si no, habria campos requeridos y no nos permitiria hacer busquedas por todo
    protected $searchRules = array('idauction' => "required|alpha_num|max:8");

    public function postAuction(){
        $items =  request("items");
       return $this->createAuction( $items );
    }


    public function createAuction($items){
        try {
			DB::beginTransaction();


			$this->create($items, $this->rules, $this->auctionRename, new FgSub());
			foreach($items as $item){
				#al crear subasta debe haber como mínimo una session
				if(empty($item["sessions"])){
					throw new ApiLabelException(trans('apilabel-app.errors.no_sessions'));

				}else{
					#ponemos el código de la subasta en las sessiones
					foreach($item["sessions"] as $keySession => $session){
						$item["sessions"][$keySession]["idauction"] = $item["idauction"];
					}
					$this->create($item["sessions"], $this->sessionrules, $this->sessionRename, new AucSessions());
				}
			}

            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }


    #
    public function getAuction(){
        return $this->showAuction(request("parameters"));
    }

    public function showAuction($whereVars){

			$auction =  New FgSub();
			$auction = $auction->select("FGSUB.*");
			$varAPI = array_flip($this->auctionRename);
			$whereAuctionRename = $this->getItems($this->auctionRename , array("idauction"));
			$resAuctionJson = $this->show($whereVars, $this->searchRules, $whereAuctionRename, $auction,  $varAPI);

			#sesiones
			$session =  New AucSessions();
			//$session = $session->select('"auc_sessions".*');



			$whereSessionRename = $this->getItems($this->sessionRename , array('idauction'));
			#debemos quitar las comillas para que no de error
			foreach($this->sessionRename as $keyR => $valR){
				$this->sessionRename[$keyR] = str_replace('"','',$valR);
			}
			$varAPI = array_flip($this->sessionRename);
			$resSessionJson = $this->show($whereVars, $this->searchRules, $whereSessionRename, $session,  $varAPI);


			$resSession = json_decode($resSessionJson);
			if(!empty($resSession->items)){
				$resAuction = json_decode($resAuctionJson);

				$resAuction->items[0]->sessions = $resSession->items;
				$resAuctionJson = json_encode($resAuction);
			}


			return  $resAuctionJson;

    }


    public function putAuction(){
        $items =  request("items");
        return $this->updateAuction( $items );

    }

    public function updateAuction($items){
        try {
            DB::beginTransaction();


				$rules = $this->cleanRequired($this->rules, ["idauction"]);
				$this->update($items, $rules, $this->auctionRename, new FgSub());
				foreach($items as $item){
					#revisamos que no modifiquen ni tipo de subasta ni que ahora pongan que es subasta abierta.
					if(!empty($item["type"])){
						throw new ApiLabelException(trans('apilabel-app.errors.no_change_type_auction'));
					}
					if(!empty($item["visiblebids"])){
						throw new ApiLabelException(trans('apilabel-app.errors.no_change_visiblebids'));
					}

					if(!empty($item["sessions"])){
						#ponemos el código de la subasta en las sessiones
						foreach($item["sessions"] as $keySession => $session){
							$sessionrules = $this->cleanRequired($this->sessionrules, ["reference"]);
							$item["sessions"][$keySession]["idauction"] = $item["idauction"];
						}
						$this->update($item["sessions"],$sessionrules , $this->sessionRename, new AucSessions());

					}


				}
            DB::commit();
            return $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }

    }

    public function deleteAuction(){
        return $this->eraseAuction(request("parameters"));
    }

	#si viene con referencia es que borramos session, si solo viene idauction, borramos subasta
    public function eraseAuction($whereVars){
        try
        {

            DB::beginTransaction();
            $rules = $this->getItems($this->rules, array("idauction"));

			#borrar solo la session indicada
			if( !empty($whereVars["reference"])   ){
				$whereSessionRename = $this->getItems($this->sessionRename , array("idauction", "reference"));
				$this->erase($whereVars, $rules, $whereSessionRename, New  AucSessions() );
			}else{
				#borrar subastas
				$whereAuction0Rename = $this->getItems($this->auctionRename , array("idauction"));
				$this->erase($whereVars, $rules, $whereAuction0Rename, New FgSub() );
				#borrar sessiones
				$whereSessionRename = $this->getItems($this->sessionRename , array("idauction"));
				$this->erase($whereVars, $rules, $whereSessionRename, New  AucSessions() );
			}


            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }









}
