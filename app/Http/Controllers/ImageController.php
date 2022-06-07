<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;

use App\libs\ImageGenerate;

use Config;
use DB;
use Request;

class ImageController extends BaseController
{
	public function return_image_friend(){
		$size = request("size");

		$num = request("num");
		$lin = request("lin");
		$numfoto =  sprintf("%02d",request("numfoto"));


		$extension = "";
		if ($numfoto != "00"){
			$extension = "_".$numfoto;
		}
/*
		$image_to_load = "img/thumbs/$size/$emp/$num/$emp-$num-$lin". $extension.".jpg";
		#en vez de devovler la imagen mejor llamar a la función return image lang y que haga todo el proceso de generar la iamgen si n oexiste
		$type = 'image/jpeg';
        header('Content-Type:'.$type);
        header('Content-Length: ' . filesize($image_to_load));
        readfile($image_to_load);
        //no borrar el die();
        die();
		echo "size:$size  emp:$emp num:$num lin:$lin friendly:$friendly";
		*/
		//002-2-2_01.jpg
		$img = Config::get('app.emp')."-".$num."-".$lin."$extension.jpg";

		$this->return_image_lang('es', $size, $img);
	}


        //no consigo que el routes funcione con y sin ´lang}, lo monto así para no perder más tiempo
    public function return_image( $size, $img)
            {
            $this->return_image_lang('es', $size, $img);
    }
    public function return_image_lang($lang='es', $size, $img)
            {

        $imageGenerate = new ImageGenerate();
        $imageGenerate->resize_img( $size, $img, Config::get('app.theme'));
    }

    public function generateMiniatures(){
        $cod_sub = NULL;
        if(!empty(Request::input('cod_sub'))){
            $cod_sub = Request::input('cod_sub');
        }

        $num_hces1 = NULL;
        if(!empty(Request::input('num_hces1'))){
            $num_hces1 = Request::input('num_hces1');
        }

        echo ' <meta charset="utf-8" http-equiv="content-type">';
        //si no han pasado datos daremso error
        if (empty($num_hces1) && empty($cod_sub)){
            echo "<center> <h1>Es necesario informar de una hoja de cesión o de una subasta</h1> </center>";
            die();
        }

        if (!empty($num_hces1)){
			$sql = "select num_hces1,lin_hces1 from fghces1 where emp_hces1 =  :emp  and num_hces1 = :num_hces1  order by num_hces1, lin_hces1";
			$lots = DB::select($sql, array("emp" => Config::get('app.emp') ,"num_hces1" => $num_hces1 ));
        }elseif(!empty($cod_sub)){
			$sql = "select num_hces1,lin_hces1 from fghces1 where emp_hces1 = :emp and  sub_hces1 =  :codsub order by num_hces1, lin_hces1";
			$lots = DB::select($sql, array("emp" => Config::get('app.emp') ,"codsub" => $cod_sub ));
        }


        if (count($lots) == 0){
            echo "<center> <h1>No se han encontrado lotes</h1> </center>";
            die();
        }

         /* tamaño imagenes */
         $sql="select * from WEB_IMAGES_SIZE WHERE ID_EMP = :emp";
         $parameters=array('emp' => Config::get('app.main_emp'));//cogemos la main para que así solo tengamso que mantener una empresa en base de datos

        $sizes=array();

        $sizes_DB = DB::select($sql,$parameters);

        if (count($sizes_DB) == 0){
            echo "Se deben determinar los tamaños de las imágenes";
            die();
        }

        foreach($sizes_DB as $size_DB){
            $sizes[$size_DB->name_web_images_size] = $size_DB->size_web_images_size;
        }
        $images_generates = array();

        foreach($lots as $key_lot => $lot){
             $images_generates = $this->generare_images_lot($lot,$images_generates,$sizes);
        }

        echo "<center><h1>Finalizado</h1><br><br>";


        if(count($images_generates) > 0){
         echo "<br><br>Imágenes  generadas:<br><br>";
            foreach($images_generates as $key => $lot){

                echo "Hoja de cesion:<strong> $lot->num_hces1 </strong> linea: <strong>$lot->lin_hces1</strong>  img: <strong>$key.jpg </strong> <br>";


            }
        }

        if(count($images_generates) ==  0 ){
            echo "<br>No se han generado imágenes<br>";
        }

        echo "</center>";
    }

    function regenerate_images_table(){
        $sql_sizes="select * from WEB_IMAGES_SIZE WHERE ID_EMP = :emp";
         $parameters=array('emp' => Config::get('app.emp'));

        $sizes=array();
        $sizes_DB = DB::select($sql_sizes,$parameters);
        if (count($sizes_DB) == 0){
            echo "Se deben determinar los tamaños de las imágenes";
            die();
        }
        foreach($sizes_DB as $size_DB){
            $sizes[$size_DB->name_web_images_size] = $size_DB->size_web_images_size;
        }

        $sql = "  select num as num_hces1, lin as lin_hces1 from (
                    select rownum,num,lin from Z_Regenerate_img where regenerate = 'N'
                    ) where rownum < 26";
          /* tamaño imagenes */


        $lots = DB::select($sql);

        $images_generates = array();

        foreach($lots as $key_lot => $lot){
             $sql_update = "update Z_Regenerate_img set regenerate = 'S' where num = '$lot->num_hces1' and lin='$lot->lin_hces1'";

              DB::select($sql_update);
             $images_generates = $this->generare_images_lot($lot,$images_generates,$sizes);

        }

        echo "<center><h1>Finalizado</h1><br><br>";


        if(count($images_generates) > 0){
         echo "<br><br>Imágenes  generadas:<br><br>";
            foreach($images_generates as $key => $lot){

                echo "Hoja de cesion:<strong> $lot->num_hces1 </strong> linea: <strong>$lot->lin_hces1</strong>  img: <strong>$key.jpg </strong> <br>";


            }
        }

        if(count($images_generates) ==  0 ){
            echo "<br>No se han generado imágenes<br>";
        }

        echo "</center>";

    }

    function generare_images_lot($lot,$images_generates, $sizes){

         $new_image_folders = \Config::get("app.new_image_folders");

        if($new_image_folders){
            $emp = Config::get('app.emp');
            $pathImagenes = 'img/'.$emp.'/'.$lot->num_hces1.'/';
        }else{
            $pathImagenes = 'img/';
        }


        $imageGenerate = new ImageGenerate();

        $img = Config::get('app.emp').'-'.$lot->num_hces1. '-' .$lot->lin_hces1;


            //imagenes ocultas se deben crear antes por que el break  del otro if hacia salta y no se llegaban a ver
                for ($x=1;$x<=20 ;$x++){

                    $name_img = $img."-NV$x";

                    if (file_exists((string)$pathImagenes.$name_img.".jpg") || file_exists((string)$pathImagenes.$name_img.".JPG") || file_exists((string)$pathImagenes.$name_img.".jpeg") || file_exists((string)$pathImagenes.$name_img.".JPEG")){
                        $name_img = $name_img.".jpg";
                         set_time_limit(60);

                        //si se crea gurdamos com oque se ha creado
                        if($imageGenerate->generateMini($name_img,$sizes['lote_small'])){

                            $images_generates[$lot->num_hces1. '-' .$lot->lin_hces1 . "-NV$x"]=$lot;
                        }
                        if($imageGenerate->generateMini($name_img,$sizes['lote_medium'])){

                            $images_generates[$lot->num_hces1. '-' .$lot->lin_hces1 . "-NV$x"]=$lot;
                        }
                        if($imageGenerate->generateMini($name_img,$sizes['lote_medium_large'])){

                            $images_generates[$lot->num_hces1. '-' .$lot->lin_hces1 . "-NV$x"]=$lot;
                        }

                    }
                }

            //imagenes normales
            for ($x=0;$x<=30 ;$x++){
                if($x == 0){
                    $y="";
                }
                elseif ($x < 10) {
                    $y = '_0'.$x;
                }else{
                    $y = "_$x";
                }
                $name_img = $img.$y;

                if (file_exists((string)$pathImagenes.$name_img.".jpg") || file_exists((string)$pathImagenes.$name_img.".JPG") || file_exists((string)$pathImagenes.$name_img.".jpeg") || file_exists((string)$pathImagenes.$name_img.".JPEG")){
                    $name_img = $name_img.".jpg";
                     set_time_limit(60);

                    //si se crea gurdamos com oque se ha creado
                    if($imageGenerate->generateMini($name_img,$sizes['lote_small'])){

                        $images_generates[$lot->num_hces1. '-' .$lot->lin_hces1 . $y]=$lot;
                    }
                    if($imageGenerate->generateMini($name_img,$sizes['lote_medium'])){

                        $images_generates[$lot->num_hces1. '-' .$lot->lin_hces1 . $y]=$lot;
                    }
                    if($imageGenerate->generateMini($name_img,$sizes['lote_medium_large'])){

                        $images_generates[$lot->num_hces1. '-' .$lot->lin_hces1 . $y]=$lot;
                    }
                    if($imageGenerate->generateMini($name_img,$sizes['lote_large'])){

                        $images_generates[$lot->num_hces1. '-' .$lot->lin_hces1 . $y]=$lot;
                    }

                }else{

                    break;
                }



            }
            return $images_generates;
    }
}
