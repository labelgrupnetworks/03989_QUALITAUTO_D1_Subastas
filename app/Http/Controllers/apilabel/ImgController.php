<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;


use App\Models\V5\FgHces1;
use App\Models\V5\Web_Images_Size;
use App\libs\ImageGenerate;



use stdClass;
class ImgController extends ApiLabelController
{
    #arrays que sirve para traducir las variables que envian y las de busqueda


    protected  $rules = array('idoriginlot' => "required|max:255",'order' => "required|numeric|max:9999999", 'img' => "max:255|active_url|nullable", 'img64' => "nullable");

    public function postImg(){
        $items =  request("items");
       return $this->createImg( $items );
    }


    public function createImg($items){
        try {


                if(empty($items) || empty($items[0])){
                    throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
                }
                $error = false;
                $itemsException = array();
                foreach($items as $key => $item){
                    $this->validator($item, $this->rules);

                    #Si da error
                    if(!$this->CreateFileImg($item)){
                        $error = true;
                        unset($items[$key]["img64"]);
                        $itemsException [] = $items[$key] ;

                    }
                }

                if(!$error){
                    return  $this->responseSuccsess();
                }else{
					\Log::info("error en imagen");
                    return $this->responseError(trans('apilabel-app.errors.img'), $itemsException);
                }


        } catch(\Exception $e){
            # si el error ya lo hemos controlado, hay que devolver el mensaje que toca, si no ponemso este if se machaca el mensaje de error por el generico
            if($e instanceof ApiLabelException){
                return $this->exceptionApi($e);
            }else{
                $exception = new ApiLabelException(trans('apilabel-app.errors.img'), $itemsException);
                return $this->exceptionApi($exception);
            }
        }
    }



    public function deleteImg(){
        return $this->eraseImg(request("parameters"));
    }

    public function eraseImg($whereVars){
        try
        {


            $this->validator($whereVars, $this->rules);
            $lot = FgHces1::select("EMP_HCES1, NUM_HCES1, LIN_HCES1, nvl(TOTALFOTOS_HCES1,0) TOTALFOTOS_HCES1")->WHERE("IDORIGEN_HCES1", $whereVars["idoriginlot"] )->first();
            $order = $whereVars["order"] ;
            #si eliminan la "ultima imagen", actualizamos el contador
            if($whereVars["order"]  > 0 && $whereVars["order"] == $lot->totalfotos_hces1){
                $hces1 = new FgHces1();

                $hces1->where("num_hces1", $lot->num_hces1)->where("lin_hces1", $lot->lin_hces1)->update(["TOTALFOTOS_HCES1" =>   ($order-1) ]);
            }

            $nameImg = $lot->emp_hces1."-".$lot->num_hces1."-".$lot->lin_hces1;
            if($order > 0){

                # si es mayor de 9 no hay que ponerle el cero delante
                if($order > 9){
                    $nameImg.="_".$order;
                }else{
                    $nameImg.="_0".$order;
                }
            }
            $nameImg.=".jpg";
            $pathImg = 'img/'.$lot->emp_hces1.'/'.$lot->num_hces1.'/'. $nameImg;
            unlink ($pathImg);

            $this->deleteThumbs($lot, $nameImg);


            return $this->responseSuccsess();

        }catch (\Exception $e){

            return $this->exceptionApi($e);
        }

    }


	public function deleteAllImg(){
        return $this->eraseAllImg(request("parameters"));
    }

    public function eraseAllImg($whereVars){
        try
        {
			 #funcion que elimina los campso required, id auction debe ser olbligatorio
			$erasaAllRules = $this->cleanRequired($this->rules, array("idoriginlot"));
			$this->validator($whereVars, $erasaAllRules);
            $lot = FgHces1::select("EMP_HCES1, NUM_HCES1, LIN_HCES1, nvl(TOTALFOTOS_HCES1,0) TOTALFOTOS_HCES1")->WHERE("IDORIGEN_HCES1", $whereVars["idoriginlot"] )->first();

			$nameImgBase = $lot->emp_hces1."-".$lot->num_hces1."-".$lot->lin_hces1;
			$totalFotos =$lot->totalfotos_hces1;
			$order = 0;
			for($order=0;$order<$totalFotos;$order++ ){
				$nameImg=	$nameImgBase;
				if($order > 0){
					# si es mayor de 9 no hay que ponerle el cero delante
					if($order > 9){
						$nameImg.="_".$order;
					}else{
						$nameImg.="_0".$order;
					}
				}
				$nameImg.=".jpg";
				$pathImg = 'img/'.$lot->emp_hces1.'/'.$lot->num_hces1.'/'. $nameImg;
				unlink ($pathImg);

				$this->deleteThumbs($lot, $nameImg);
			}

		}catch (\Exception $e){

            return $this->exceptionApi($e);
        }
	}

    private function CreateFileImg($item){
        try {

        $lot = FgHces1::select("EMP_HCES1, NUM_HCES1, LIN_HCES1, nvl(TOTALFOTOS_HCES1,0) TOTALFOTOS_HCES1")->WHERE("IDORIGEN_HCES1", $item["idoriginlot"] )->first();
        if(empty($lot)){
            throw new ApiLabelException(trans('apilabel-app.errors.no_match'));
        }
        if(!empty($item["img64"])) {
            $img64 = base64_decode($item["img64"]);
            $img =  imagecreatefromstring($img64);
        }elseif(!empty($item["img"])) {
			$context = stream_context_create(["ssl" => [
				"verify_peer"      => false,
				"verify_peer_name" => false
				],#SIMULO UN ENCABEZADO POR SI TIENEN CAPADOS LOS SCRIPTS, así se piensa que entramos desde un navegador
				"http" => [
					"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
				]
			  ]
			 );

			  $fileContent = file_get_contents( $item["img"], false, $context);
            $img = imagecreatefromstring($fileContent);
        }

        $nameImg = $lot->emp_hces1."-".$lot->num_hces1."-".$lot->lin_hces1;
        $order = $item["order"];
        #si nos pasan una nueva imagen con orden mayor al ultimo que tenemos lo guardamos con ese
        if(($order+1) > $lot->totalfotos_hces1){
            $hces1 = new FgHces1();
            $hces1->where("num_hces1", $lot->num_hces1)->where("lin_hces1", $lot->lin_hces1)->update(["TOTALFOTOS_HCES1" => ($order+1) ]);
        }
        # si es una imagen secundaria
        if($order > 0){

            # si es mayor de 9 no hay que ponerle el cero delante
            if($order > 9){
                $nameImg.="_".$order;
            }else{
                $nameImg.="_0".$order;
            }
        }

        $nameImg.=".jpg";

        #creamos carpetas si no existen
        $this->createPath($lot->emp_hces1, $lot->num_hces1);
        #creamos imagen a partir de url

        $pathImg = public_path('img/'.$lot->emp_hces1.'/'.$lot->num_hces1.'/'. $nameImg);
        #si no existe le sumamos 1 a l cantidad de imagenes
        if (!file_exists($pathImg))
        {
            #aqui habría que hacer que sume uno al
        }
        #guardar la imagen en el archivo
        imagejpeg($img,$pathImg, 90);
        #crear las miniaturas
        $this->createThumbs($lot, $nameImg);
        return true;
    } catch (\Exception $e){
		\Log::info($e);
		$this->exceptionApi($e);
		return false;
    }


    }

    private function createPath($emp, $num,$folder = "img"){
		$path= public_path($folder);
        $this->createFolder($path);
        $this->createFolder($path."/$emp");
        $this->createFolder($path."/$emp/$num");
    }

    private function createFolder($folderPath){
        if (!file_exists($folderPath))
        {
            mkdir($folderPath, 0775, true);
            chmod($folderPath,0775);
        }
    }

    private function createThumbs($lot, $name_img){
        $sizes = Web_Images_Size::getSizes();
        $imageGenerate = new ImageGenerate();
        $path = "img/thumbs/";

        $this->createPath($lot->emp_hces1, $lot->num_hces1,  $path.$sizes['lote_small']);
        $imageGenerate->generateMini($name_img,$sizes['lote_small']);
        $this->createPath($lot->emp_hces1, $lot->num_hces1,  $path.$sizes['lote_medium']);
        $imageGenerate->generateMini($name_img,$sizes['lote_medium']);
        $this->createPath($lot->emp_hces1, $lot->num_hces1,  $path.$sizes['lote_medium_large']);

        $imageGenerate->generateMini($name_img,$sizes['lote_medium_large']);

    }

    private function deleteThumbs($lot, $name_img){
        $sizes = Web_Images_Size::getSizes();
        $imageGenerate = new ImageGenerate();
        $path = "img/thumbs/";
        unlink ($path.$sizes['lote_small']."/".$lot->emp_hces1."/".$lot->num_hces1."/". $name_img );
        unlink ($path.$sizes['lote_medium']."/".$lot->emp_hces1."/".$lot->num_hces1."/". $name_img );
        unlink ($path.$sizes['lote_medium_large']."/".$lot->emp_hces1."/".$lot->num_hces1."/". $name_img );
    }





    #creamos imagen a partir de url


}
