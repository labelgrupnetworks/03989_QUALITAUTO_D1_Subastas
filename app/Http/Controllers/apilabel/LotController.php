<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;

use App\Models\V5\FgAsigl0;
use App\Models\V5\FxCli;
use App\Models\V5\FgHces0;
use App\Models\V5\FgHces1;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Models\V5\FgCaracteristicas_Hces1_Lang;
use App\Models\V5\FgHces1_Lang;
use Illuminate\Support\MessageBag;

use DB;

class LotController extends ApiLabelController
{
    #arrays que sirve para traducir las variables que envian y las de busqueda
    protected  $asigl0Rename = array("idorigin"=>"idorigen_asigl0","idauction"=>"sub_asigl0",  "reflot"=>"ref_asigl0", "close"=>"cerrado_asigl0",  "highlight" => "destacado_asigl0", "buyoption" => "compra_asigl0", "soldprice" => "remate_asigl0", "startprice" => "impsalhces_asigl0", "lowprice" => "imptas_asigl0", "highprice" => "imptash_asigl0", "reserveprice" => "impres_asigl0", "retired" => "retirado_asigl0", "hidden" => "oculto_asigl0",  "disclaimed" => "desadju_asigl0", "startdate" => "fini_asigl0", "enddate" => "ffin_asigl0", "starthour" => "hini_asigl0", "endhour" => "hfin_asigl0", "ownercommission" => "comphces_asigl0" , "biddercommission" => "comlhces_asigl0", "enddateoriginal" => "ffin_original_asigl0", "endhouroriginal" => "hfin_original_asigl0", "label" => "oferta_asigl0" );
    protected  $hces1Rename = array("idorigin"=>"idorigen_hces1",  "idauction"=>"sub_hces1",  "reflot"=>"ref_hces1",  "idsubcategory" => "sec_hces1", "title" => "descweb_hces1", "description" => "desc_hces1",  "extrainfo" => "descdet_hces1",   "htmlcontent" => "contextra_hces1",  "search" => "search_hces1", "startprice" => "impsal_hces1", "lowprice" => "imptasini_hces1",  "highprice" => "imptash_hces1","reserveprice" => "impres_hces1", "costprice" => "pc_hces1" ,"biddercommission" => "coml_hces1" ,"biddercommissionini" => "comlini_hces1" ,"ownercommission" => "comp_hces1" ,"ownercommissionini" => "compini_hces1" ,"warehouse" =>"alm_hces1", "numberobjects" => "nobj_hces1","high" => "alto_hces1", "width" => "ancho_hces1", "diameter" => "diam_hces1","thickness" => "grueso_hces1" , "weight" => "peso_hces1", "volumetricweight" => "pesovol_hces1", "video" => "img360_hces1", "ministry"=>"ministerio_hces1", "exportpermission"=>"permisoexp_hces1", "urlfriendly" => "webfriend_hces1", "order" => "orden_hces1", "maxbid" =>"implic_hces1", "infoforauctioner" => "infotr_hces1",  "owner" => "prop_hces1",  "imgfriendly" => "imgfriendly_hces1", "metatitle" => "webmetat_hces1", "metadescription" => "webmetad_hces1", "withstock" => "controlstock_hces1", "stock" => "stock_hces1", "transport" => "transport_hces1", "other_id" => "loteaparte_hces1");
    protected  $featuresRename = array("idfeature"=>"idcar_caracteristicas_hces1",  "num"=>"numhces_caracteristicas_hces1",   "lin"=>"linhces_caracteristicas_hces1", "idvaluefeature" => "idvalue_caracteristicas_hces1", "value" => "value_caracteristicas_hces1", "orden" => "orden_caracteristicas_hces1"  );
	protected  $featuresLangRename = array("idfeature"=>"idcar_car_hces1_lang",  "num"=>"numhces_car_hces1_lang",   "lin"=>"linhces_car_hces1_lang",  "value" => "value_car_hces1_lang", "lang" => "lang_car_hces1_lang"  );

	protected  $hces1LangRename = array( "lang" => "lang_hces1_lang", "num" => "num_hces1_lang", "lin" => "lin_hces1_lang",  "title" => "descweb_hces1_lang", "description" => "desc_hces1_lang",  "extrainfo" => "descdet_hces1_lang",  "search" => "search_hces1_lang", "urlfriendly" => "webfriend_hces1_lang", "metatitle" => "webmetat_hces1_lang", "metadescription" => "webmetad_hces1_lang");

    protected  $featuresRules = array('idfeature' => "filled|numeric|max:9999999", "idvaluefeature" => "numeric|nullable","value" => "nullable" );
    protected  $rules = array('idorigin' => "required|max:255",'idauction' => "required|alpha_num|max:8", 'reflot' => "required|numeric|max:999999999", 'idsubcategory' => "required|alpha_num|max:2",'title' => "required", 'description' => "required", 'search' => "nullable|max:3000",  'startprice' => "required|numeric", 'lowprice' => "numeric|nullable", 'highprice' => "numeric|nullable",  'reserveprice' => "numeric|nullable",  'highlight' => "filled|alpha|max:1",'buyoption' => "filled|alpha|max:1",'soldprice' => "filled|alpha|max:1",'retired' => "filled|alpha|max:1",'hidden' => "filled|alpha|max:1",'disclaimed' => "filled|alpha|max:1",'startdate' => "date_format:Y-m-d|nullable",'enddate' => "date_format:Y-m-d|nullable",'starthour' => "date_format:H:i:s|nullable",'endhour' => "date_format:H:i:s|nullable",'feature' => "array", "costprice" => "numeric|nullable","biddercommission" => "numeric|nullable" ,"biddercommissionini" => "numeric|nullable", "ownercommission" => "numeric|nullable" ,"ownercommissionini" => "numeric|nullable" ,"warehouse" =>"alpha_num|max:9|nullable","numberobjects" => "numeric|nullable","high" => "numeric|nullable", "width" => "numeric|nullable", "diameter" => "numeric|nullable","thickness" => "numeric|nullable" , "weight" => "numeric|nullable", "volumetricweight" => "numeric|nullable", "video" =>  "filled|alpha|max:1", "ministry" =>"alpha_num|max:1|nullable", "exportpermission" =>"alpha_num|max:1|nullable", "order" =>"numeric|nullable", "maxbid" => "numeric|nullable", "infoforauctioner" =>"max:2000|nullable" , "owner" =>"alpha_num|max:8|nullable", "imgfriendly" =>"max:256|nullable", "label" =>"numeric|nullable", "withstock" => "alpha_num|max:1|nullable", "stock" => "numeric|nullable");
	protected  $hces1LangRules = array('lang' => "required");

    public function postLot(){
        $items =  request("items");
       return $this->createLot( $items );
    }


    public function createLot($items){
        try {
            DB::beginTransaction();

                if(empty($items) || empty($items[0])){
                    throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
                }
                #comprovamos los valores antes de seguir
                $this->validatorArray($items, $this->rules);
/*
                if(empty($items[0]["idauction"])) {
                    # si no hay subasta devolvemos error de que es requerida
                    $errorsItem["item_1"] = array("idauction" => array(trans('apilabel-app.validation.required')));
                    throw new ApiLabelException(trans('apilabel-app.errors.validation'), $errorsItem);
                }
*/

                $codSub = $items[0]["idauction"];

                #obtenemos el numhces que corresponde
                $numHces = FgHces0::getNumHces($codSub);
                #lo seteamos
                FgHces0::setNumHces($codSub, $numHces);
                #obtenemos el lin para el proximo lote
                $linHces = FgHces1::getLinHces($codSub,$numHces);
				$itemsFeatures = array();
				$itemsFeaturesLang = array();
				#lo creamos vacio y solo se rellenará en el caso que se vaya a usar, así n ose consumen recursos siempre
				$users = array();
				$itemsHces1Lang = array();

				foreach($items as $key => $item){
                    #todos los lotes han de ser de la misma subasta
                    if($codSub != $item["idauction"]){
                        throw new ApiLabelException(trans('apilabel-app.errors.different_auctions'));
                    }
                    $items[$key]["lin"] = $linHces;
                    $items[$key]["num"] = $numHces;

					#Caracteristicas
                    if(!empty($item["features"])){
						$orderFeatures = array();

                        foreach($item["features"] as $feature){

                            $feature["lin"] =  $linHces;
							$feature["num"] =  $numHces;

							#se va a permitir mas de una caracteristica por tipo de caracteristica, por ejemplo varios autores
							#usaremos el campo orden como nuevo campo de la primary key para poder permitir mas de una caracteristica
							if(empty($orderFeatures[$feature["idfeature"]])){
								$orderFeatures[$feature["idfeature"]] = 1;
							}else{
								$orderFeatures[$feature["idfeature"]]++;
							}
							$feature["orden"] = $orderFeatures[$feature["idfeature"]];
                            #generamos un registro por cada id que llegue
							if(empty($feature["lang"])){
								$itemsFeatures[] = $feature;
							}else{
								$feature["lang"] =  \Tools::getLanguageComplete($feature["lang"]);
								$itemsFeaturesLang[] = $feature;
							}

                        }
					}

					#multiidioma
					if(!empty($item["languages"])){
                        foreach($item["languages"] as $hces1Lang){
							$hces1Lang["lang"] =  \Tools::getLanguageComplete($hces1Lang["lang"]);
                            $hces1Lang["lin"] =  $linHces;
							$hces1Lang["num"] =  $numHces;
							if(empty($hces1Lang["urlfriendly"]) && !empty($hces1Lang["title"])){
								$hces1Lang[$key]["urlfriendly"] =  mb_substr(\Str::slug($hces1Lang["title"]),0,99);
							}
                            #generamos un registro por cada id que llegue
                            $itemsHces1Lang[] = $hces1Lang;
                        }
					}

					if(empty($item["urlfriendly"]) && !empty($item["title"])){
						$items[$key]["urlfriendly"] =  mb_substr(\Str::slug($item["title"]),0,99);
					}

					if(!empty($item["originowner"])){
						#si el array de usuarios esta vacio lo cargamos, una vez, de esta manera n ose carga siempre solo cuando hay propietarios del lote
						if(empty($users)){
							$users = FxCli::select("COD_CLI, COD2_CLI")->pluck('cod_cli','cod2_cli');
						}
						if(!empty($users[$item["originowner"]])){
							$items[$key]["owner"] = $users[$item["originowner"]];
						}else{
							throw new ApiLabelException(trans('apilabel-app.errors.no_exist_client')."  ".$item["originowner"]);
						}
					}

					#Añadimos la fecha y hora de cierre origial, que no sera modificada por las pujas de últimos minutos
					if(!empty($items[$key]['enddate'])){
						$items[$key]['enddateoriginal'] = $items[$key]['enddate'];
					}
					if(!empty($items[$key]['endhour'])){
						$items[$key]['endhouroriginal'] = $items[$key]['endhour'];
					}

                    $linHces++;
                }

                #añadimos num y lin en asigl0 y haces1 rename para que lso guarde en base de datos
                $this->asigl0Rename = array_merge($this->asigl0Rename, array( "num"=>"numhces_asigl0","lin"=>"linhces_asigl0"));
                $this->hces1Rename = array_merge($this->hces1Rename, array("num"=>"num_hces1","lin"=>"lin_hces1"));
               // $this->hces1LangRename = array_merge($this->hces1LangRename, array("num"=>"num_hces1_lang","lin"=>"lin_hces1_lang"));

                #creamos registros en fgasigl0
                $this->create($items, $this->rules, $this->asigl0Rename, new FgAsigl0());
                #creamos registros en hces1
                $this->create($items, $this->rules, $this->hces1Rename, new FgHces1());
                #ceamos registros en Caracteristicas

                $this->create($itemsFeatures, $this->featuresRules , $this->featuresRename, new FgCaracteristicas_Hces1());
				$this->create($itemsFeaturesLang, $this->featuresRules , $this->featuresLangRename, new FgCaracteristicas_Hces1_Lang());

				#creamos registros en multiidioma
				$this->create($itemsHces1Lang, $this->hces1LangRules , $this->hces1LangRename, new FgHces1_Lang());


            DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }

    #
    public function getLot(){
        return $this->showLot(request("parameters"));
    }

    public function showLot($whereVars){


        #funcion que elimina los campso required, id auction debe ser olbligatorio
        $searchRules = $this->cleanRequired($this->rules, array("idauction"));
        #juntamos los renames de las dos tablas, El orden es de suma impportancia, ya que asigl0 al estar segunda prevalecen sus indices
		$whereRename = array_merge( $this->hces1Rename,$this->asigl0Rename);

        $whereRename["numcession"] = "num_hces1";
        $whereRename["lincession"] = "lin_hces1";
		unset($whereRename["other_id"]);

		$varAPI = array_flip($whereRename);
		#hacen falta estos campos  para la url del lote
		$varAPI["id_auc_sessions"] ='id_auc_sessions';
		$varAPI["name"] = 'name';



        $asigl0 = new FgAsigl0();
        $featureAsigl0 = new FgAsigl0();
        #haremos select solo con los campos que necesitamos, para eso los saco del whererename
        $select = implode(",", $whereRename);

		$asigl0 = $asigl0->addselect($select)->JoinFghces1Asigl0();

		#hacen falta estos campos  para la url del lote
		$asigl0 = $asigl0->JoinSessionAsigl0();
		$asigl0 = $asigl0->addselect('auc."id_auc_sessions", auc."name"');


        #Si envian catgoría buscamos por ella
        if(!empty($whereVars["idcategory"])){
            $asigl0 = $asigl0->JoinFgOrtsecAsigl0()->where("lin_ortsec0", $whereVars["idcategory"]);
            $featureAsigl0 = $featureAsigl0->JoinFgOrtsecAsigl0()->where("lin_ortsec0", $whereVars["idcategory"]);
            #añadimos la comprobación por idcategoria,
            $searchRules['idcategory'] = "numeric|max:999999";
        }

        $features = array();
            #obtenemos las caracteristicas de todos lso lotes de la subasta, la subasta es obligatoria pero como no se comprueba hasta más adelante poneos el if
        if(!empty($whereVars["idauction"]) ){
            $features = $featureAsigl0->addselect("idorigen_asigl0 as idorigen, ID_CARACTERISTICAS  , NAME_CARACTERISTICAS NAME,  NVL(VALUE_CARACTERISTICAS_VALUE, VALUE_CARACTERISTICAS_HCES1) VALUE, ORDEN_CARACTERISTICAS_HCES1 ORDEN")
                                        ->where("sub_asigl0", $whereVars["idauction"])->LeftJoinCaracteristicasAsigl0()->get();
        }

        $caracteristicas = array();
        #agrupamos las categorias por idorigen
        foreach($features as $feature){
            if(!empty($feature->name)){
                if(empty($caracteristicas[$feature->idorigen])){
                    $caracteristicas[$feature->idorigen] = array();
				}
				$orden ="";
				if($feature->orden != 1){
					$orden = "-".$feature->orden;
				}
                $caracteristicas[$feature->idorigen][$feature->name.$orden] = $feature->value;
            }
        }
        #recogemos la respuesta con el listado de lotes
        $resJson = $this->show($whereVars  ,$searchRules, $whereRename, $asigl0,  $varAPI);

        $res = json_decode($resJson);
        #si todo ha ido bien, añadimos las caractristicas lote a lote y la url
        if(!empty($res) && !empty($res->status) && $res->status == 'SUCCESS' && !empty($res->items) ){
            foreach($res->items as $key => $item){
                if(!empty($caracteristicas[$item->idorigin])){
                    $res->items[$key]->features = $caracteristicas[$item->idorigin];
				}

				$res->items[$key]->url= \Tools::url_lot($item->idauction, $item->id_auc_sessions, $item->name, $item->reflot,$item->numcession,$item->urlfriendly,$item->title);
				$res->items[$key]->urlimg= \Tools::url_img("lote_large", $item->numcession, $item->lincession, null, false);
				$res->items[$key]->urlimgMedium= \Tools::url_img("lote_medium", $item->numcession, $item->lincession, null, false);
				$res->items[$key]->urlimgSmall= \Tools::url_img("lote_small", $item->numcession, $item->lincession, null, false);

				#eliminamso num, ya que no se necesita devolver el num_hces
				unset($res->items[$key]->name);
				unset($res->items[$key]->id_auc_sessions);

			}

            return json_encode($res);

        }else{
            return  $resJson;
        }

    }


    public function putLot(){
        $items =  request("items");
        return $this->updateLot( $items );

    }

    public function updateLot($items){
        try {
            DB::beginTransaction();


            if(empty($items) || empty($items[0])){
                throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
            }

			$users=null;
			foreach($items as $key => $item){
				# si no hay subasta devolvemos error de que es requerida / antes solo se revisaba quefaltara en el primer elemento, 07-06-22
				if(empty($item["idauction"])) {
					$messageBag = new MessageBag();
					$messageBag->add("idauction", trans('apilabel-app.validation.required',["attribute" => "idauction"]));
					$errorsItem["item_".($key +1)] =$messageBag;
					throw new ApiLabelException(trans('apilabel-app.errors.validation'), $errorsItem);
				}

				if(empty($item["urlfriendly"]) && !empty($item["title"])){

					$items[$key]["urlfriendly"] =  mb_substr(\Str::slug( $item["title"]),0,100);

				}
				#propietari odel lote
				if(!empty($item["originowner"])){
					#si el array de usuarios esta vacio lo cargamos una vez, de esta manera no se carga siempre, solo cuando hay propietarios del lote
					if(empty($users)){
						$users = FxCli::select("COD_CLI, COD2_CLI")->pluck('cod_cli','cod2_cli');
					}
					if(!empty($users[$item["originowner"]])){
						$items[$key]["owner"] = $users[$item["originowner"]];
					}else{
						throw new ApiLabelException(trans('apilabel-app.errors.no_exist_client')."  ".$item["originowner"]);
					}


				}

				#Añadimos la fecha y hora de cierre origial, que no sera modificada por las pujas de últimos minutos
				if(!empty($items[$key]['enddate'])){
				$items[$key]['enddateoriginal'] = $items[$key]['enddate'];
				}
				if(!empty($items[$key]['endhour'])){
					$items[$key]['endhouroriginal'] = $items[$key]['endhour'];
				}
				// Se ha modificado para que se guarde por trigger
				/* if(empty($items[$key]['dateupdate'])){
					$items[$key]['dateupdate'] = date("Y-m-d H:i:s ");
				} */

			}


            #limpiamos los required menos de idorigen
                $rules = $this->cleanRequired($this->rules, array("idorigin"));
                $this->updateFeature($items);
				$this->updateHces1Lang($items);

                $this->update($items, $rules, $this->asigl0Rename, new FgAsigl0());
                $this->update($items, $rules, $this->hces1Rename, new FgHces1());
            DB::commit();
            return $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }

    }

    public function updateFeature($items){
            $codSub = $items[0]["idauction"];
            $featureAsigl0 = new FgAsigl0();
            #obtenemos las caracteristicas, renombramos los campos para que sean como en la API
			$featureAsigl0 = $featureAsigl0->addselect("idorigen_asigl0  idorigen, numhces_asigl0  num, linhces_asigl0  lin")
                                            ->where("sub_asigl0", $codSub);

            $lots = array();
            foreach ($featureAsigl0->get() as $feature ){
                    $lots[$feature->idorigen] =array();
                    $lots[$feature->idorigen]["num"] =  $feature->num;
                    $lots[$feature->idorigen]["lin"] =  $feature->lin;
            }

            foreach($items as $key => $item){

                $create=array();
				$createLang=array();
                #todos los lotes han de ser de la misma subasta
                if($codSub != $item["idauction"]){
                    throw new ApiLabelException(trans('apilabel-app.errors.different_auctions'));
                }
                //hay que comrpobar si existe, no si está vacia, ya que si existe y está vacia es que quieren borrarlo todo
                if(isset(($item["features"]))){
					$orderFeatures = array();

                    foreach($item["features"] as $featureLot){

                        if( !empty( $lots[$item["idorigin"]])){
                            $featureLot["num"] = $lots[$item["idorigin"]]["num"];
							$featureLot["lin"] = $lots[$item["idorigin"]]["lin"];

							#se va a permitir mas de una caracteristica por tipo de caracteristica, por ejemplo varios autores
							#usaremos el campo orden como nuevo campo de la primary key para poder permitir mas de una caracteristica

							if(empty($orderFeatures[$featureLot["idfeature"]])){
								$orderFeatures[$featureLot["idfeature"]] = 1;
							}else{
								$orderFeatures[$featureLot["idfeature"]]++;
							}

							$featureLot["orden"] = $orderFeatures[$featureLot["idfeature"]];

							if(empty($featureLot["lang"])){
                                $create[] = $featureLot;

							}else{
								$featureLot["lang"] =  \Tools::getLanguageComplete($featureLot["lang"]);
								$createLang[] = $featureLot;
							}

                        }

                    }


                    #borramos todas las features del lote
                    if(!empty($lots[$item["idorigin"]])){
						$featureToDelete = $this->getItems($lots[$item["idorigin"]], array( "num", "lin"));
						$this->erase($featureToDelete, $this->featuresRules, $this->featuresRename, new FgCaracteristicas_Hces1(), false);
						#borramos los idiomas, como hay que borrarlos todos no hace falta pasar el lang
						$this->erase($featureToDelete, $this->featuresRules, $this->featuresLangRename, new FgCaracteristicas_Hces1_Lang(), false);
                    }

                    $this->create($create, $this->featuresRules, $this->featuresRename, new FgCaracteristicas_Hces1());
                    $this->create($createLang, $this->featuresRules, $this->featuresLangRename, new FgCaracteristicas_Hces1_Lang());
                }
            }
    }

	public function updateHces1Lang($items){
		$codSub = $items[0]["idauction"];
		#pongo el num como num_hces1_lang para luego el delete
		$hces1_lang = FgHces1::select("IDORIGEN_HCES1, NUM_HCES1 , LIN_HCES1  ")->get();
	 /*	->leftjoin("FGHCES1_LANG", " EMP_HCES1_LANG = EMP_HCES1  AND  NUM_HCES1_LANG = NUM_HCES1  AND  LIN_HCES1_LANG = LIN_HCES1") */
		$lots = array();


		foreach($hces1_lang as $hces1){
			$lot = array("num" =>$hces1->num_hces1,"lin" =>$hces1->lin_hces1   );
			$lots[$hces1->idorigen_hces1] =$lot ;
		}

		foreach($items as $key => $item){

			$create=array();

			#todos los lotes han de ser de la misma subasta
			if($codSub != $item["idauction"]){
				throw new ApiLabelException(trans('apilabel-app.errors.different_auctions'));
			}

			//hay que comrpobar si existe, no si está vacia, ya que si existe y está vacia es que quieren borrarlo todo
			if(isset(($item["languages"]))){

				foreach($item["languages"] as $hces1Lang){
					$lang =\Tools::getLanguageComplete($hces1Lang["lang"]);
					if(!empty( $lots[$item["idorigin"]])){
						$hces1Lang["lang"] = $lang;
						$hces1Lang["num"] = $lots[$item["idorigin"]]["num"];
						$hces1Lang["lin"] = $lots[$item["idorigin"]]["lin"];
						if(empty($hces1Lang["urlfriendly"]) && !empty($hces1Lang["title"])){
							$hces1Lang[$key]["urlfriendly"] =  mb_substr(\Str::slug($hces1Lang["title"]),0,99);
						}
						$create[] = $hces1Lang;
					}


				}
				#borramso las traducciones que habia en base de dtos, borrara todos los idiomas del num y lin
				if(!empty( $lots[$item["idorigin"]])){

						#nos quedamos solo con los campos que identifican a la caracteristica
						# $hces1LangToDelete = $this->getItems($lots[$item["idorigin"]], array( "num", "lin"));

						#no ponemso reglas para que no sea obligatorio el lang_hces1_lang
						$this->erase($lots[$item["idorigin"]], [], $this->hces1LangRename, new FgHces1_Lang(), false);

				}


				$this->create($create, $this->hces1LangRules, $this->hces1LangRename, new FgHces1_Lang());

			}
		}
	}



    public function deleteLot(){
        return $this->eraseLot(request("parameters"));
    }

    public function eraseLot($whereVars){
        try
        {
            DB::beginTransaction();

			$bindings = array(
				'emp'           => Config::get('app.emp'),
				'idorigin'     => $whereVars["idorigin"]
				);

             #Borrar carcateristicas,se borran primero las caracteristcas por que si no el join con hces1 no funcionaria ya que no habria lote
             $sql="  DELETE FROM (
                select FGCARACTERISTICAS_HCES1.* FROM FGCARACTERISTICAS_HCES1
                JOIN FGHCES1 ON EMP_HCES1= EMP_CARACTERISTICAS_HCES1 AND NUM_HCES1 = NUMHCES_CARACTERISTICAS_HCES1 AND LIN_HCES1 = LINHCES_CARACTERISTICAS_HCES1
                WHERE EMP_CARACTERISTICAS_HCES1= :emp AND IDORIGEN_HCES1 =:idorigin)";

            \DB::select($sql, $bindings);

			#Borrar carcateristicas LANG,
			$sql="  DELETE FROM (
                select FGCARACTERISTICAS_HCES1_LANG.* FROM FGCARACTERISTICAS_HCES1_LANG
                JOIN FGHCES1 ON EMP_HCES1= EMP_CAR_HCES1_LANG AND NUM_HCES1 = NUMHCES_CAR_HCES1_LANG AND LIN_HCES1 = LINHCES_CAR_HCES1_LANG
                WHERE EMP_CAR_HCES1_LANG= :emp AND IDORIGEN_HCES1 =:idorigin)";

            \DB::select($sql, $bindings);


			#Borrar fghces1lang,se borran primero los idiomas por que si no el join con hces1 no funcionaria ya que no habria lote
			$sql="  DELETE FROM (
				select FGHCES1_LANG.* FROM FGHCES1_LANG
				JOIN FGHCES1 ON EMP_HCES1= EMP_HCES1_LANG AND NUM_HCES1 = NUM_HCES1_LANG AND LIN_HCES1 = LIN_HCES1_LANG
				WHERE EMP_HCES1_LANG = :emp AND IDORIGEN_HCES1 =:idorigin)";

			\DB::select($sql, $bindings);




            # el idorigen es obligatorio, por eso solo cojemos esa regla
            $rules = $this->getItems($this->rules, array("idorigin"));
            #Borrar FGASIGL0
                $this->erase($whereVars, $rules, $this->asigl0Rename, New FgAsigl0() );

            #Borrar FGHCES1
                 $this->erase($whereVars, $rules, $this->hces1Rename, new FgHces1());




            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }

}
