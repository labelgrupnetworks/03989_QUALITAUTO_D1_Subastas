<?php

namespace App\Http\Controllers\Invaluable;

use Config;
use Illuminate\Support\Facades\DB;

class House extends GuzzleHttpRequest {

    public function groupSettings($house){

        $response = $this->getUrl('cps/app/data/ssa/houses/'.$house.'/groupSettings');
        return json_decode($response)->groupSettings[0]->groupSettingsID;
    }

    public function listContacts($house){

        $response = $this->getUrl('cps/app/data/ssa/houses/'.$house.'/listContacts');
        return $response;
    }

    public function addresses($house){

        $response = $this->getUrl('cps/app/data/ssa/houses/'.$house.'/addresses');

        return json_decode($response)->addresses[0]->id;
    }

    public function channels($house){

        return $this->getUrl('cps/app/data/ssa/houses/'.$house.'/channels');
    }

    public function catalogs($house, $subasta, $sesionID){

        //consulta a la BD para obtener la informacion de session de la subasta

        $empresa = Config::get('app.emp');

        $sql = "       SELECT auc.\"company\", auc.\"auction\",auc.\"reference\",auc.\"start\",auc.\"end\",
                       NVL(auc_lang.\"name_lang\",  auc.\"name\") nombre,
                       NVL(auc_lang.\"description_lang\",  auc.\"description\") descripcion,
                       sub.tipo_sub
                       FROM FGSUB sub
                       LEFT JOIN FGSUB_LANG fgsublang ON (sub.EMP_SUB = fgsublang.EMP_SUB_LANG AND sub.COD_SUB = fgsublang.COD_SUB_LANG AND  fgsublang.LANG_SUB_LANG = :lang)
                       JOIN \"auc_sessions\" auc ON (auc.\"auction\" = :cod_sub AND auc.\"company\" = :emp) 
                       LEFT JOIN \"auc_sessions_lang\" auc_lang on (auc_lang.\"auction_lang\" = sub.cod_sub and auc_lang.\"company_lang\" = :emp and auc_lang.\"lang_auc_sessions_lang\" = :lang)
                       where sub.EMP_SUB = :emp AND auc.\"reference\" = :sesion AND sub.cod_sub = :cod_sub
                ";
        $params = array(
            'emp'   =>  $empresa,
            'cod_sub'   =>  $subasta,
            'sesion'    =>  $sesionID,
            'lang'      => "en-GB"
        );

        $infosubasta = head(DB::select($sql,$params));

        //Si existe la sesion de la subasta, conformar el $request con la informacion para enviar a la API
        if(!empty($infosubasta))
        {
            $groupID=$this->groupSettings($house);
            $addressID = $this->addresses($house);
            $url = 'cps/app/data/ssa/houses/'.$house.'/groups/'.$subasta.'/catalogs';

            //conformar el request si la subasta es de tipo Online
            if($infosubasta->tipo_sub == "O"){

                $fecha = str_replace(' ','T',trim($infosubasta->end));

                $request = [
                    "houseUserName" => $house,
                    "groupName" => $subasta,
                    "groupSettingsID" => $groupID,
                    "timed"=> true,
                    "catalogs"=> [
                        [
                            "sourceSessionID" => $subasta.'-'.$sesionID,
                            "catalogTitle" => $infosubasta->nombre,
                            "addressID" => $addressID,
                            "catalogDateTime" => $fecha,
                            "timeZoneCode" => "Europe/Madrid",
                            "avEnabled" => true,
                            "channels" =>   [
                                [
                                    "channelID" => 1
                                ],
                                [
                                    "channelID" => 4
                                ]
                            ],
                            "timedStaggerValue" => 60
                        ]
                    ]
                ];

            }
            //conformar el request si la subasta es de tipo Presencial
            elseif($infosubasta->tipo_sub == "W"){

                $fecha = str_replace(' ','T',trim($infosubasta->start));

                $request = [
                    "groupSettingsID" => $groupID,
                    "timed"=> false,
                    "catalogs"=> [
                        [
                            "sourceSessionID" => $subasta.'-'.$sesionID,
                            "catalogTitle" => $infosubasta->nombre,
                            "addressID" => $addressID,
                            "catalogDateTime" => $fecha,
                            "timeZoneCode" => "Europe/Madrid",
                            "avEnabled" => true,
                            "channels" =>   [
                                [
                                    "channelID" => 1
                                ],
                                [
                                    "channelID" => 4
                                ]
                            ]
                        ]
                    ]
                ];
            }
            else{
                \Log::error("Este tipo de subasta no esta permitida en Invaluable, código de subasta: " .$subasta." sesión: ".$sesionID);
                return $this->errorResponse("Este tipo de subasta no esta permitida en Invaluable",400);
            }

            //haciendo peticion PUT a la API para crear o actualizar la informacion de la subasta
            $catalog= $this->PUT($url,json_encode($request));

            if (json_decode($catalog->getContent())->success == false){
                \Log::error("Error al actualizar el catálogo con código de subasta: " .$subasta." sesión: ".$sesionID);
                return $catalog;
            }

            // Insertando o actualizando los lotes del Catálago

            $lots = $this->catalogLots($house,$subasta,$sesionID,$empresa);

            if (json_decode($lots->getContent())->success == false){
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

    public function catalogLots($house, $subasta, $sesionID, $empresa){

        //consulta a la BD para obtener todos los lotes de esa sesion de la subasta
        $sql="Select HCES1.LIN_HCES1, HCES1.NUM_HCES1, 
                NVL(HCES1_LANG.DESC_HCES1_LANG, HCES1.DESC_HCES1) DESC_HCES1, NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.TITULO_HCES1) TITULO_HCES1,
                ASIGL0.REF_ASIGL0, ASIGL0.IMPSALHCES_ASIGL0
                FROM FGASIGL0 ASIGL0
                INNER JOIN FGHCES1 HCES1 ON (HCES1.EMP_HCES1 = :emp AND HCES1.NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0  AND HCES1.LIN_HCES1 = ASIGL0.LINHCES_ASIGL0)
                INNER JOIN FGSUB SUB ON SUB.EMP_SUB = ASIGL0.EMP_ASIGL0 AND SUB.COD_SUB = ASIGL0.SUB_ASIGL0
                INNER JOIN \"auc_sessions\" AUC ON  AUC.\"auction\" = SUB.COD_SUB AND AUC.\"company\" = ASIGL0.EMP_ASIGL0
                LEFT JOIN FGHCES1_LANG HCES1_LANG ON (HCES1_LANG.EMP_HCES1_LANG = HCES1.EMP_HCES1 AND HCES1_LANG.NUM_HCES1_LANG =  HCES1.NUM_HCES1 AND HCES1_LANG.LIN_HCES1_LANG = HCES1.LIN_HCES1  AND HCES1_LANG.LANG_HCES1_LANG = :lang)
                WHERE ASIGL0.EMP_ASIGL0 = :emp AND SUB.COD_SUB = :cod_sub AND AUC.\"reference\" = :sesion AND ASIGL0.REF_ASIGL0 >= AUC.\"init_lot\" AND ASIGL0.REF_ASIGL0 <= AUC.\"end_lot\" AND OCULTO_ASIGL0 = 'N' AND CERRADO_ASIGL0 = 'N' AND RETIRADO_ASIGL0 = 'N' AND HCES1.FAC_HCES1 = 'N'
                ORDER BY ASIGL0.REF_ASIGL0";

        $params = array(
            'emp'       =>  $empresa,
            'cod_sub'   =>  $subasta,
            'sesion'    =>  $sesionID,
            'lang' => "en-GB"
        );

        $lotes = DB::select($sql,$params);

        $lots = array();
        // recorriendo cada lote para conformar el request para enviar a la API
        foreach ($lotes as $key => $lote){
            //url imagen original
            $dir_img = '/img/'.$empresa.'/'.$lote->num_hces1.'/'.$empresa.'-'.$lote->num_hces1.'-'.$lote->lin_hces1;
            $enc_img_main = false;
            $url_img_principal = '';

            $imagen = array();

            if(file_exists( public_path().$dir_img.'.jpg'))
            {
                $url_img_principal = url($dir_img.'.jpg');
                $enc_img_main = true;
            }
            elseif(file_exists( public_path().$dir_img.'.JPG')) {
                $url_img_principal = url($dir_img.'.JPG');
                $enc_img_main = true;
            }
            else{
                $enc_img_main = false;
            }

            $lots[$key] = [
                "sourceSessionID" => $subasta . '-' . $sesionID,
                "lotNumber" => $lote->ref_asigl0,
                "title" => $lote->titulo_hces1,
                "description" => $lote->desc_hces1,
                "startingBid" => $lote->impsalhces_asigl0
            ];

            // si se encontro la imagen principal, se conforma el request de ese lote con la opcion de enviar imagen
            if($enc_img_main){

                //guardando la url de la imagen principal
                $imagen []= [
                    "imageURL" => $url_img_principal,
                    "primary"=> true
                ];

                // buscando url imagenes extras del lote para guardarla en el array imagenes
                for ($i=1;$i<10 ;$i++){

                    $img = $dir_img.'_0'.$i;

                    if(file_exists( public_path().$img.'.jpg'))
                    {
                        $url_img = url($img.'.jpg');

                        $imagen[] = [
                            "imageURL" => $url_img, //conformar la url de la imagen
                            "primary"=> false
                        ];

                    }
                    elseif(file_exists( public_path().$img.'.JPG')) {
                        $url_img = url($img.'.JPG');
                        $imagen[] = [
                            "imageURL" => $url_img, //conformar la url de la imagen
                            "primary"=> false
                        ];

                    }
                    else{
                        break;
                    }

                }

                $lots[$key]["images"] = $imagen;
            }

        }

        //terminando de construir el request para enviar a la API
        $requestlot =  [
            "lots" => $lots
        ];

        //llamar al metodo lots($house,$requestlot)

        $lotes = $this->lots($house,$requestlot);

        return $lotes;
    }

    public function lots($house,$request=null,$message = "Catálogo actualizado con éxito", $error = 'Error al actualizar lotes en el catálogo'){

        $url = 'cps/app/data/ssa/houses/'.$house.'/lots';
        return $this->PUT($url,json_encode($request), $message, $error);
    }

    public function updateLot($house,$subasta,$sesionID,$lotNumber){

        $sourceSessionID = $subasta . '-' . $sesionID;
        $url = 'cps/app/data/ssa/houses/' . $house . '/lots';

        $empresa = Config::get('app.emp');
        //consulta a la BD para obtener la informacion del lote de esa sesion de la subasta
        $sql="Select HCES1.LIN_HCES1, HCES1.NUM_HCES1, 
                NVL(HCES1_LANG.DESC_HCES1_LANG, HCES1.DESC_HCES1) DESC_HCES1, NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.TITULO_HCES1) TITULO_HCES1,
                ASIGL0.REF_ASIGL0, ASIGL0.IMPSALHCES_ASIGL0
                FROM FGASIGL0 ASIGL0
                INNER JOIN FGHCES1 HCES1 ON (HCES1.EMP_HCES1 = :emp AND HCES1.NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0  AND HCES1.LIN_HCES1 = ASIGL0.LINHCES_ASIGL0)
                INNER JOIN FGSUB SUB ON SUB.EMP_SUB = ASIGL0.EMP_ASIGL0 AND SUB.COD_SUB = ASIGL0.SUB_ASIGL0
                INNER JOIN \"auc_sessions\" AUC ON  AUC.\"auction\" = SUB.COD_SUB AND AUC.\"company\" = ASIGL0.EMP_ASIGL0
                LEFT JOIN FGHCES1_LANG HCES1_LANG ON (HCES1_LANG.EMP_HCES1_LANG = HCES1.EMP_HCES1 AND HCES1_LANG.NUM_HCES1_LANG =  HCES1.NUM_HCES1 AND HCES1_LANG.LIN_HCES1_LANG = HCES1.LIN_HCES1  AND HCES1_LANG.LANG_HCES1_LANG = :lang)
                WHERE ASIGL0.EMP_ASIGL0 = :emp AND SUB.COD_SUB = :cod_sub AND AUC.\"reference\" = :sesion AND ASIGL0.REF_ASIGL0 >= AUC.\"init_lot\" AND ASIGL0.REF_ASIGL0 <= AUC.\"end_lot\" AND OCULTO_ASIGL0 = 'N' AND CERRADO_ASIGL0 = 'N' AND RETIRADO_ASIGL0 = 'N' AND HCES1.FAC_HCES1 = 'N'
                AND ASIGL0.REF_ASIGL0 = :ref_lote";

        $params = array(
            'emp'       =>  $empresa,
            'cod_sub'   =>  $subasta,
            'sesion'    =>  $sesionID,
            'ref_lote'  =>  $lotNumber,
            'lang' => "en-GB"
        );

        $lote = head(DB::select($sql,$params));

        if(!empty($lote)){

            //url imagen original
            $dir_img = '/img/'.$empresa.'/'.$lote->num_hces1.'/'.$empresa.'-'.$lote->num_hces1.'-'.$lote->lin_hces1;
            $enc_img_main = false;
            $url_img_principal = '';
            $imagen = array();

            if(file_exists( public_path().$dir_img.'.jpg'))
            {
                $url_img_principal = url($dir_img.'.jpg');
                $enc_img_main = true;
            }
            elseif(file_exists( public_path().$dir_img.'.JPG')) {
                $url_img_principal = url($dir_img.'.JPG');
                $enc_img_main = true;
            }
            else{
                $enc_img_main = false;
            }

            $lots[0] = [
                "sourceSessionID" => $subasta . '-' . $sesionID,
                "lotNumber" => $lote->ref_asigl0,
                "title" => $lote->titulo_hces1,
                "description" => $lote->desc_hces1,
                "startingBid" => $lote->impsalhces_asigl0
            ];

            // si se encontro la imagen principal, se conforma el request de ese lote con la opcion de enviar imagen
            if($enc_img_main){

                //guardando la url de la imagen principal
                $imagen []= [
                    "imageURL" => $url_img_principal,
                    "primary"=> true
                ];

                // buscando url imagenes extras del lote para guardarla en el array imagenes
                for ($i=1;$i<10 ;$i++){

                    $img = $dir_img.'_0'.$i;

                    if(file_exists( public_path().$img.'.jpg'))
                    {
                        $url_img = url($img.'.jpg');

                        $imagen[] = [
                            "imageURL" => $url_img, //conformar la url de la imagen
                            "primary"=> false
                        ];

                    }
                    elseif(file_exists( public_path().$img.'.JPG')) {
                        $url_img = url($img.'.JPG');
                        $imagen[] = [
                            "imageURL" => $url_img, //conformar la url de la imagen
                            "primary"=> false
                        ];

                    }
                    else{
                        break;
                    }

                }

                $lots[0]["images"] = $imagen;
            }

            //terminando de construir el request para enviar a la API
            $requestlot =  [
                "lots" => $lots
            ];

            //llamar al metodo lots($house,$requestlot,mensaje)

            $lotes = $this->lots($house,$requestlot,"Lote ".$lotNumber." actualizado con éxito");

            if (json_decode($lotes->getContent())->success == false){
                \Log::error("Error al actualizar el lote del catálogo con código de subasta: " .$subasta." sesión: ".$sesionID." referencia de lote: ".$lotNumber);
            }
            else {
                \Log::info("El lote: ".$lotNumber." del catálogo con código de subasta: " .$subasta." sesión: ".$sesionID." se actualizó con éxito");
            }

            return $lotes;

        }
        else{
            \Log::error("Lote no existe, error al actualizar el lote del catálogo con código de subasta: " .$subasta." sesión: ".$sesionID." referencia de lote: ".$lotNumber);
            return $this->errorResponse("El lote no existe en esa subasta",400);
        }

    }

    public function deletelot($house,$subasta,$sesionID,$lotNumber){

        $sourceSessionID = $subasta.'-'.$sesionID;
        $url = 'cps/app/data/ssa/houses/'.$house.'/sessionIds/'.$sourceSessionID.'/lots/'.$lotNumber;

        $lote = $this->DELETE($url, "Lote ".$lotNumber." eliminado con éxito del catálogo");

        if (json_decode($lote->getContent())->success == false){
            \Log::error("Error al eliminar el lote del catálogo con código de subasta: " .$subasta." sesión: ".$sesionID." referencia del lote: ".$lotNumber);
        }else{
            \Log::info("Lote eliminado con éxito del catálogo con código de subasta: " .$subasta." sesión: ".$sesionID." referencia del lote: ".$lotNumber);
        }

        return $lote;

    }
}