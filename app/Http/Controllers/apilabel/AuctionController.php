<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;

use App\Models\V5\FgSub;
use App\Models\V5\FgSub_lang;
use App\Models\V5\AucSessions_Lang;
use App\Models\V5\AucSessions;
use App\Providers\ToolsServiceProvider;
use DB;

class AuctionController extends ApiLabelController
{
    protected  $auctionRename = array("idauction"=>"cod_sub", "name"=>"des_sub","type"=>"tipo_sub", "status" => "subc_sub", "description" => "descdet_sub", "visiblebids" => "subabierta_sub",  "startauction"=> "dfec_sub", "finishauction" => "hfec_sub", "startorders"=> "dfecorlic_sub", "finishorders" => "hfecorlic_sub", "metatitle" => "webmetat_sub", "metadescription" => "webmetad_sub", "phoneorders" => "ordentel_sub"    );
	protected  $sessionRename = array("idauction"=>'"auction"', "name"=>'"name"',"reference"=> '"reference"', "description" => '"description"',  "start"=> '"start"', "finish" => '"end"', "startorders"=> '"orders_start"', "finishorders" => '"orders_end"', "firstlot" => '"init_lot"', "lastlot" => '"end_lot"'   );

	protected  $auctionLangRename = array("lang" => "lang_sub_lang","idauction"=>"cod_sub_lang", "name"=>"des_sub_lang", "description" => "descdet_sub_lang",    "metatitle" => "webmetat_sub_lang", "metadescription" => "webmetad_sub_lang"    );
	protected  $sessionLangRename = array("lang" => '"lang_auc_sessions_lang"',"id_auc_sessions" => '"id_auc_session_lang"'  ,"idauction"=>'"auction_lang"', "reference"=> '"reference_lang"', "name"=>'"name_lang"', "description" => '"description_lang"' );


	protected  $rules = array('idauction' => "required|alpha_num|max:8",'name'   => "required|max:40", 'type'  => "required|alpha_num|max:1", 'status'  => "required|alpha_num|max:1",'visiblebids'   => "nullable|alpha_num|max:1",'startauction' => "date_format:Y-m-d H:i:s|nullable", 'finishauction' => "date_format:Y-m-d H:i:s|nullable", 'startorders' => "date_format:Y-m-d H:i:s|nullable",  'finishorders' => "date_format:Y-m-d H:i:s|nullable" );
	protected  $sessionrules = array('idauction' => "required|alpha_num|max:8", 'reference' => "required|alpha_num|max:3", 'name'   => "required|max:40", 'start' => "date_format:Y-m-d H:i:s|nullable", 'finish' => "date_format:Y-m-d H:i:s|nullable", 'startorders' => "date_format:Y-m-d H:i:s|nullable",  'finishorders' => "date_format:Y-m-d H:i:s|nullable" ,  'firstlot' => "required|numeric|max:999999" ,  'lastlot' => "required|numeric|max:999999",'phoneorders' => "nullable|numeric", );
	protected  $auctionLangRules = array('lang' => "required");
	protected  $sessionLangRules = array('lang' => "required");
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

			$itemsAuctionLang = array();
			$itemsSessionLang = array();
			$this->create($items, $this->rules, $this->auctionRename, new FgSub());
			foreach($items as $item){
				#al crear subasta debe haber como mínimo una session
				if(empty($item["sessions"])){
					throw new ApiLabelException(trans('apilabel-app.errors.no_sessions'));

				}else{

					foreach($item["sessions"] as $keySession => $session){
						$item["sessions"][$keySession]["idauction"] = $item["idauction"];
					}

					$this->create($item["sessions"], $this->sessionrules, $this->sessionRename, new AucSessions());

					#ponemos el código de la subasta en las sessiones
					foreach($item["sessions"] as $keySession => $session){

						#idiomas sesion
						if(!empty($session["sessionLanguages"])){
							foreach($session["sessionLanguages"] as $sessionLang){
								$sessionLang["lang"] =  ToolsServiceProvider::getLanguageComplete($sessionLang["lang"]);
								$sessionLang["idauction"] =  $session["idauction"];
								$sessionLang["reference"] =  $session["reference"];
								#debemos recuperar su id de sesion
								$sessionId = AucSessions::select('"id_auc_sessions"')->where('"auction"', $sessionLang["idauction"])->where('"reference"', $sessionLang["reference"])->first();
								$sessionLang["id_auc_sessions"] =$sessionId->id_auc_sessions;
								$itemsSessionLang[] = $sessionLang;
							}
						}
					}


				}

				if(!empty($item["auctionlanguages"])){
					foreach($item["auctionlanguages"] as $auctionLang){
						$auctionLang["lang"] =  ToolsServiceProvider::getLanguageComplete($auctionLang["lang"]);
						$auctionLang["idauction"] =  $item["idauction"];


						$itemsAuctionLang[] = $auctionLang;
					}
				}






			}

			if(count($itemsAuctionLang) > 0){
				#creamos registros en multiidioma
				$this->create($itemsAuctionLang, $this->auctionLangRules , $this->auctionLangRename, new FgSub_lang());
			}
			if(count($itemsSessionLang) > 0){

				#creamos registros en multiidioma
				$this->create($itemsSessionLang, $this->sessionLangRules , $this->sessionLangRename, new AucSessions_Lang());
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
				$this->updateAuctionLang($items);
				$this->updateSessionLang($items);
            DB::commit();
            return $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }

    }

	public function updateAuctionLang($items){

		foreach($items as $key => $item){

			$create=array();



			//hay que comrpobar si existe, no si está vacia, ya que si existe y está vacia es que quieren borrarlo todo
			if(isset(($item["auctionlanguages"]))){

				foreach($item["auctionlanguages"] as $auctionlang){
					$lang =ToolsServiceProvider::getLanguageComplete($auctionlang["lang"]);
					$auctionlang["idauction"] = $item["idauction"];
					$auctionlang["lang"] = $lang;
					$create[] = $auctionlang;
				}

				#borramso las traducciones que habia en base de dtos, borrara todos los idiomas del num y lin
				#no ponemos reglas para que no sea obligatorio el lang
				$this->erase(["idauction" =>$item["idauction"] ], [], $this->auctionLangRename, new FgSub_lang(), false);

				$this->create($create, $this->auctionLangRules, $this->auctionLangRename, new FgSub_lang());


			}
		}
	}

	public function updateSessionLang($items){

		foreach($items as $key => $item){

			if(!empty($item["sessions"])){
				#ponemos el código de la subasta en las sessiones
				foreach($item["sessions"] as $keySession => $session){
					$itemsSessionLang = array();
					#idiomas sesion
					if(!empty($session["sessionLanguages"])){
						$sessionId = AucSessions::select('"id_auc_sessions"')->where('"auction"', $item["idauction"])->where('"reference"', $session["reference"])->first();

						foreach($session["sessionLanguages"] as $sessionLang){
							$sessionLang["lang"] =  ToolsServiceProvider::getLanguageComplete($sessionLang["lang"]);
							$sessionLang["idauction"] =  $item["idauction"];
							$sessionLang["reference"] =  $session["reference"];
							#debemos recuperar su id de sesion
							$sessionLang["id_auc_sessions"] =$sessionId->id_auc_sessions;
							$itemsSessionLang[] = $sessionLang;
						}

						$this->erase(["id_auc_sessions" =>$sessionId->id_auc_sessions], [], $this->sessionLangRename, new AucSessions_Lang(), false);
						$this->create($itemsSessionLang, $this->sessionLangRules , $this->sessionLangRename, new AucSessions_Lang());
					}
				}
			}


			/*

			$create=array();



			//hay que comrpobar si existe, no si está vacia, ya que si existe y está vacia es que quieren borrarlo todo
			if(isset(($item["auctionlanguages"]))){

				foreach($item["auctionlanguages"] as $auctionlang){
					$lang =ToolsServiceProvider::getLanguageComplete($auctionlang["lang"]);
					$auctionlang["idauction"] = $item["idauction"];
					$auctionlang["lang"] = $lang;
					$create[] = $auctionlang;
				}
				#borramso las traducciones que habia en base de dtos, borrara todos los idiomas del num y lin
				#no ponemos reglas para que no sea obligatorio el lang
				$this->erase(["idauction" =>$item["idauction"] ], [], $this->auctionLangRename, new FgSub_lang(), false);
				$this->create($create, $this->auctionLangRules, $this->auctionLangRename, new FgSub_lang());

			}
			*/
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
