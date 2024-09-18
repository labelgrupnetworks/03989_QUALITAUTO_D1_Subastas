<?php

namespace App\Http\Controllers\externalAggregator\Invaluable;

use App\Models\V5\FgSub;
use App\Models\V5\FgAsigl0;
use App\Providers\ToolsServiceProvider;
use Config;
use Illuminate\Support\Facades\DB;

class House extends GuzzleHttpRequest {

    public function groupSettings(){
		#en test es el mismo valor

        $response = $this->getUrl('cps/app/data/ssa/houses/'.$this->house.'/groupSettings');


        return json_decode($response)->groupSettings[0]->groupSettingsID;
    }

    public function listContacts(){

        $response = $this->getUrl('cps/app/data/ssa/houses/'.$this->house.'/listContacts');
		#echo $response; die();
        return $response;
    }

    public function addresses(){

        $response = $this->getUrl('cps/app/data/ssa/houses/'.$this->house.'/addresses');
		#echo $response; die();
        return json_decode($response)->addresses[0]->id;
    }

    public function channels(){

		$response = $this->getUrl('cps/app/data/ssa/houses/'.$this->house.'/channels');

		 #echo $response; die();

		 return $response;
    }

	#OJO si falla el catalogo revisar que la fecha de kla subasta sea a futuro, con subastas pasadas no funciona
    public function catalogs( $subasta, $sesionID){

        //consulta a la BD para obtener la informacion de session de la subasta
		$fgSub = new FgSub();
		$infosubasta =$fgSub
		->join('"auc_sessions"','"auc_sessions"."company" = FGSUB.EMP_SUB AND "auc_sessions"."auction" = FGSUB.COD_SUB')
        ->leftJoin('"auc_sessions_lang"',' "auc_sessions_lang"."id_auc_session_lang" = "auc_sessions"."id_auc_sessions"   AND "auc_sessions"."company" = "auc_sessions_lang"."company_lang" AND "auc_sessions"."auction" = "auc_sessions_lang"."auction_lang" AND "auc_sessions_lang"."lang_auc_sessions_lang" = \'en-GB\'')

		->select('tipo_sub, NVL("auc_sessions_lang"."name_lang","auc_sessions"."name")as name')
		->addSelect('"auc_sessions"."start" as session_start')
        ->addSelect('"auc_sessions"."end" as session_end')
		->where("FGSUB.COD_SUB",$subasta)
		->where('"auc_sessions"."reference"',$sesionID)->first();
		//->getInfoSub($subasta, $sesionID);

        //Si existe la sesion de la subasta, conformar el $request con la informacion para enviar a la API
        if(!empty($infosubasta))
        {
            $groupID=$this->groupSettings();
            $addressID = $this->addresses();
            $url = 'cps/app/data/ssa/houses/'.$this->house.'/groups/'.$subasta.'/catalogs';


			if($infosubasta->tipo_sub == "W"){
				$fecha = str_replace(' ','T',trim($infosubasta->session_start));
				$timed = false;
			}elseif($infosubasta->tipo_sub == "O"){
				$fecha = str_replace(' ','T',trim($infosubasta->session_end));
				$timed = true;
				$timedStaggerValue = Config::get('app.increment_endlot_online', 60);
			} else{
                \Log::error("Este tipo de subasta no esta permitida en Invaluable, código de subasta: " .$subasta." sesión: ".$sesionID);
                return $this->errorResponse("Este tipo de subasta no esta permitida en Invaluable",400);
            }

			$request = [
				"groupSettingsID" => $groupID,
				"timed"=> $timed,
				"catalogs"=> [
					[
						"sourceSessionID" => $subasta.'-'.$sesionID,
						"catalogTitle" => $infosubasta->name,
						"addressID" => $addressID,
						"catalogDateTime" => $fecha,
						"avEnabled" => true,
						"channels" =>   [
							[
								"channelID" => 1 //invaluable
							]
						]
					]
				]
			];

            //haciendo peticion PUT a la API para crear o actualizar la informacion de la subasta
            $catalog= $this->PUT($url,json_encode($request));

            if (json_decode($catalog)->success == false){
                \Log::error("Error al actualizar el catálogo con código de subasta: " .$subasta." sesión: ".$sesionID);
                return $catalog;
            }

            // Insertando o actualizando los lotes del Catálago

            $lots = $this->catalogLots($subasta,$sesionID);

            if (json_decode($lots)->success == false){
                \Log::error("Error al actualizar los lotes del catálogo con código de subasta: " .$subasta." sesión: ".$sesionID);
                return $lots;
            }


                \Log::info("El catálogo con código de subasta: " .$subasta." sesión: ".$sesionID." se actualizó con éxito");

                return $catalog;

        }

        else{
            \Log::error("La subasta no existe, error al actualizar el catálogo con código de subasta: " .$subasta." sesión: ".$sesionID);
            return $this->errorResponse("La subasta no existe",400);
        }
    }

    public function catalogLots( $subasta, $sesionID , $ref = null){


		$asigl0 = new FgAsigl0();
		$asigl0 = $asigl0->select("ref_asigl0,  impsalhces_asigl0, num_hces1, lin_hces1,  imptas_hces1, imptash_hces1, totalfotos_hces1 ")
		->addSelect("NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1) DESCWEB_HCES1,   NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1")
		->addSelect("NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1) DESC_HCES1")

		#ponemos que siempre saque la info en ingles
		->leftjoin('FGHCES1_LANG',"FGHCES1_LANG.EMP_HCES1_LANG = FGASIGL0.EMP_ASIGL0 AND FGHCES1_LANG.NUM_HCES1_LANG = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1_LANG.LIN_HCES1_LANG = FGASIGL0.LINHCES_ASIGL0 AND FGHCES1_LANG.LANG_HCES1_LANG = 'en-GB'")

		->WithArtist()->ActiveLotAsigl0()->where("RETIRADO_ASIGL0", "N")->where("sub_asigl0", $subasta)->where('"reference"', $sesionID);


		if(!empty($ref)){
			$asigl0 = $asigl0->where("ref_asigl0", $ref);
		}
		$lotes = $asigl0->get();

        $lots = array();
        // recorriendo cada lote para conformar el request para enviar a la API
        foreach ($lotes as $key => $lote){
			$title = substr(strip_tags($lote->descweb_hces1),0,250);
			#hay limitaciones de caracteres, por lo que mejor recortar y ya se hará un
			$lote->webfriend_hces1 =  !empty($lote->webfriend_hces1)? substr($lote->webfriend_hces1,0,50) :  substr($title,0, 50);
            $lots[$key] = [
                "sourceSessionID" => $subasta . '-' . $sesionID,
                "lotNumber" => str_replace(array(".1",".2",".3", ".4", ".5"), array("A", "B", "C", "D", "E"), $lote->ref_asigl0),
                "title" =>  $title,
                "description" => $lote->desc_hces1?? $lote->descweb_hces1,
                "startingBid" => $lote->impsalhces_asigl0,

				"estimatedLow" => !empty($lote->imptas_hces1)? $lote->imptas_hces1*1 : $lote->impsalhces_asigl0 * 1.5,
				"estimatedHigh" => !empty($lote->imptash_hces1)? $lote->imptash_hces1*1 :  $lote->impsalhces_asigl0 * 2,
			];

			if(!empty($lote->artist_name)){
				$lots[$key]["artistFullName"] = $lote->artist_name;
			}

			#le quitaremos las variables por que si no da error la carga de imágenes
			$urlImagen = explode("?",ToolsServiceProvider::url_img("lote_medium_large", $lote->num_hces1, $lote->lin_hces1));
			$imagen = $urlImagen[0];

			$imagenes = [
				"imageURL" => $imagen,
				"primary"=> true
			];
			$lots[$key]["images"][] =$imagenes;
			for ($i=1;$i< $lote->totalfotos_hces1 ;$i++){

				#le quitaremos las variables
				$urlImagen = explode("?",ToolsServiceProvider::url_img("lote_medium_large", $lote->num_hces1, $lote->lin_hces1, $i));
				$imagen = $urlImagen[0];

				$imagenes = [
					"imageURL" => $imagen,
					"primary"=> false
				];
				$lots[$key]["images"][] =$imagenes;
			}


        }

        //terminando de construir el request para enviar a la API
        $requestlot =  [
            "lots" => $lots
        ];


        $lotes = $this->lots($requestlot);

        return $lotes;
    }

    public function lots($request=null,$message = "Lote actualizado con éxito", $error = 'Error al actualizar lotes en el catálogo'){

        $url = 'cps/app/data/ssa/houses/'.$this->house.'/lots';
        return $this->PUT($url,json_encode($request), $message, $error);
    }
	#hace lo mismo que la llamada a todos los lotes del catalogo

    public function deleteLot($subasta,$sesionID,$ref){

        $sourceSessionID = $subasta.'-'.$sesionID;
        $url = 'cps/app/data/ssa/houses/'.$this->house.'/sessionIds/'.$sourceSessionID.'/lots/'.$ref;

        $lote = $this->DELETE($url, "Lote ".$ref." eliminado con éxito del catálogo");


            \Log::info("Lote eliminado con éxito del catálogo con código de subasta: " .$subasta." sesión: ".$sesionID." referencia del lote: ".$ref);


        return $lote;

    }
}
