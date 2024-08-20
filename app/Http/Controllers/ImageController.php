<?php

namespace App\Http\Controllers;

use App\libs\CacheLib;
use App\libs\ImageGenerate;
use App\Models\V5\Web_Images_Size;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Intervention\Image\Facades\Image;

class ImageController extends BaseController
{
	public function return_image_friend()
	{
		$size = request("size");

		$num = request("num");
		$lin = request("lin");
		$numfoto =  sprintf("%02d", request("numfoto"));


		$extension = "";
		if ($numfoto != "00") {
			$extension = "_" . $numfoto;
		}

		$img = Config::get('app.emp') . "-" . $num . "-" . $lin . "$extension.jpg";

		$this->return_image_lang('es', $size, $img);
	}

	//no consigo que el routes funcione con y sin ´lang}, lo monto así para no perder más tiempo
	public function return_image($size, $img)
	{
		$this->return_image_lang('es', $size, $img);
	}

	public function return_image_lang($lang = 'es', $size, $img)
	{
		$imageGenerate = new ImageGenerate();
		$imageGenerate->resize_img($size, $img, Config::get('app.theme'));
	}

	public function generateMiniatures()
	{
		$cod_sub = NULL;
		if (!empty(Request::input('cod_sub'))) {
			$cod_sub = Request::input('cod_sub');
		}

		$num_hces1 = NULL;
		if (!empty(Request::input('num_hces1'))) {
			$num_hces1 = Request::input('num_hces1');
		}

		echo ' <meta charset="utf-8" http-equiv="content-type">';
		//si no han pasado datos daremso error
		if (empty($num_hces1) && empty($cod_sub)) {
			echo "<center> <h1>Es necesario informar de una hoja de cesión o de una subasta</h1> </center>";
			die();
		}

		if (!empty($num_hces1)) {
			$sql = "select num_hces1,lin_hces1 from fghces1 where emp_hces1 =  :emp  and num_hces1 = :num_hces1  order by num_hces1, lin_hces1";
			$lots = DB::select($sql, array("emp" => Config::get('app.emp'), "num_hces1" => $num_hces1));
		} elseif (!empty($cod_sub)) {
			$sql = "select num_hces1,lin_hces1 from fghces1 where emp_hces1 = :emp and  sub_hces1 =  :codsub order by num_hces1, lin_hces1";
			$lots = DB::select($sql, array("emp" => Config::get('app.emp'), "codsub" => $cod_sub));
		}


		if (count($lots) == 0) {
			echo "<center> <h1>No se han encontrado lotes</h1> </center>";
			die();
		}

		/* tamaño imagenes */
		$sql = "select * from WEB_IMAGES_SIZE WHERE ID_EMP = :emp";
		$parameters = array('emp' => Config::get('app.main_emp')); //cogemos la main para que así solo tengamso que mantener una empresa en base de datos

		$sizes = array();

		$sizes_DB = DB::select($sql, $parameters);

		if (count($sizes_DB) == 0) {
			echo "Se deben determinar los tamaños de las imágenes";
			die();
		}

		foreach ($sizes_DB as $size_DB) {
			$sizes[$size_DB->name_web_images_size] = $size_DB->size_web_images_size;
		}
		$images_generates = array();

		foreach ($lots as $key_lot => $lot) {
			$images_generates = $this->generare_images_lot($lot, $images_generates, $sizes);
		}

		echo "<center><h1>Finalizado</h1><br><br>";


		if (count($images_generates) > 0) {
			echo "<br><br>Imágenes  generadas:<br><br>";
			foreach ($images_generates as $key => $lot) {

				echo "Hoja de cesion:<strong> $lot->num_hces1 </strong> linea: <strong>$lot->lin_hces1</strong>  img: <strong>$key.jpg </strong> <br>";
			}
		}

		if (count($images_generates) ==  0) {
			echo "<br>No se han generado imágenes<br>";
		}

		echo "</center>";
	}

	function regenerate_images_table()
	{
		$sql_sizes = "select * from WEB_IMAGES_SIZE WHERE ID_EMP = :emp";
		$parameters = array('emp' => Config::get('app.emp'));

		$sizes = array();
		$sizes_DB = DB::select($sql_sizes, $parameters);
		if (count($sizes_DB) == 0) {
			echo "Se deben determinar los tamaños de las imágenes";
			die();
		}
		foreach ($sizes_DB as $size_DB) {
			$sizes[$size_DB->name_web_images_size] = $size_DB->size_web_images_size;
		}

		$sql = "  select num as num_hces1, lin as lin_hces1 from (
                    select rownum,num,lin from Z_Regenerate_img where regenerate = 'N'
                    ) where rownum < 26";
		/* tamaño imagenes */


		$lots = DB::select($sql);

		$images_generates = array();

		foreach ($lots as $key_lot => $lot) {
			$sql_update = "update Z_Regenerate_img set regenerate = 'S' where num = '$lot->num_hces1' and lin='$lot->lin_hces1'";

			DB::select($sql_update);
			$images_generates = $this->generare_images_lot($lot, $images_generates, $sizes);
		}

		echo "<center><h1>Finalizado</h1><br><br>";


		if (count($images_generates) > 0) {
			echo "<br><br>Imágenes  generadas:<br><br>";
			foreach ($images_generates as $key => $lot) {

				echo "Hoja de cesion:<strong> $lot->num_hces1 </strong> linea: <strong>$lot->lin_hces1</strong>  img: <strong>$key.jpg </strong> <br>";
			}
		}

		if (count($images_generates) ==  0) {
			echo "<br>No se han generado imágenes<br>";
		}

		echo "</center>";
	}

	function generateImageLot(HttpRequest $request)
	{
		$num_hces = $request->input('num_hces1');
		$lin_hces = $request->input('lin_hces1');

		$emp = Config::get('app.emp');

		$sizes = CacheLib::rememberCache('image_sizes', 1200, function () {
			return Web_Images_Size::query()
				->where('name_web_images_size', 'like', '%lote%')->pluck('size_web_images_size');
		});


		$path = "img/$emp/$num_hces/";
		$images = array_diff(scandir($path), ['.', '..']);

		if (!empty($lin_hces)) {
			$images = array_filter($images, function ($image) use ($lin_hces) {
				[$imageName, $extension] = explode(".", $image);

				$imageParams = explode("-", $imageName);
				[$imgEmp, $imgNum, $imgLin] = $imageParams;

				$imgLin = strpos($imgLin, "_") === false ? $imgLin : explode("_", $imgLin)[0];
				return $imgLin == $lin_hces;
			});
		}

		foreach ($images as $image) {
			[$imageName, $extension] = explode(".", $image);
			$imagePath = public_path($path . $image);

			foreach ($sizes as $size) {
				set_time_limit(60);
				$directoryThumb = "img/thumbs/$size/$emp/$num_hces";
				if (!is_dir(public_path($directoryThumb))) {
					mkdir(public_path($directoryThumb), 0775, true);
					chmod(public_path($directoryThumb), 0775);
				}

				$imageThumb = public_path("$directoryThumb/$imageName.webp");

				$imageMake = Image::make($imagePath);

				$imageMake->resize($size, null, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});

				$imageMake->save($imageThumb, 80, 'webp');
				echo "<br><br>Imágen $imageName $size generada:<br><br>";
			}
		}

		dd("fin");
	}

	function generare_images_lot($lot, $images_generates, $sizes)
	{

		$new_image_folders = Config::get("app.new_image_folders");

		if ($new_image_folders) {
			$emp = Config::get('app.emp');
			$pathImagenes = 'img/' . $emp . '/' . $lot->num_hces1 . '/';
		} else {
			$pathImagenes = 'img/';
		}


		$imageGenerate = new ImageGenerate();

		$img = Config::get('app.emp') . '-' . $lot->num_hces1 . '-' . $lot->lin_hces1;


		//imagenes ocultas se deben crear antes por que el break  del otro if hacia salta y no se llegaban a ver
		for ($x = 1; $x <= 20; $x++) {

			$name_img = $img . "-NV$x";

			if (file_exists((string)$pathImagenes . $name_img . ".jpg") || file_exists((string)$pathImagenes . $name_img . ".JPG") || file_exists((string)$pathImagenes . $name_img . ".jpeg") || file_exists((string)$pathImagenes . $name_img . ".JPEG")) {
				$name_img = $name_img . ".jpg";
				set_time_limit(60);

				//si se crea gurdamos com oque se ha creado
				if (!empty($sizes['lote_small']) && $imageGenerate->generateMini($name_img, $sizes['lote_small'])) {

					$images_generates[$lot->num_hces1 . '-' . $lot->lin_hces1 . "-NV$x"] = $lot;
				}
				if (!empty($sizes['lote_medium']) &&  $imageGenerate->generateMini($name_img, $sizes['lote_medium'])) {

					$images_generates[$lot->num_hces1 . '-' . $lot->lin_hces1 . "-NV$x"] = $lot;
				}
				if (!empty($sizes['lote_medium_large']) &&  $imageGenerate->generateMini($name_img, $sizes['lote_medium_large'])) {

					$images_generates[$lot->num_hces1 . '-' . $lot->lin_hces1 . "-NV$x"] = $lot;
				}
			}
		}

		//imagenes normales
		for ($x = 0; $x <= 30; $x++) {
			if ($x == 0) {
				$y = "";
			} elseif ($x < 10) {
				$y = '_0' . $x;
			} else {
				$y = "_$x";
			}
			$name_img = $img . $y;

			if (file_exists((string)$pathImagenes . $name_img . ".jpg") || file_exists((string)$pathImagenes . $name_img . ".JPG") || file_exists((string)$pathImagenes . $name_img . ".jpeg") || file_exists((string)$pathImagenes . $name_img . ".JPEG")) {
				$name_img = $name_img . ".jpg";
				set_time_limit(60);

				//si se crea gurdamos com oque se ha creado
				if (!empty($sizes['lote_small']) && $imageGenerate->generateMini($name_img, $sizes['lote_small'])) {

					$images_generates[$lot->num_hces1 . '-' . $lot->lin_hces1 . $y] = $lot;
				}
				if (!empty($sizes['lote_medium']) && $imageGenerate->generateMini($name_img, $sizes['lote_medium'])) {

					$images_generates[$lot->num_hces1 . '-' . $lot->lin_hces1 . $y] = $lot;
				}
				if (!empty($sizes['lote_medium_large']) && $imageGenerate->generateMini($name_img, $sizes['lote_medium_large'])) {

					$images_generates[$lot->num_hces1 . '-' . $lot->lin_hces1 . $y] = $lot;
				}
				if (!empty($sizes['lote_large']) && $imageGenerate->generateMini($name_img, $sizes['lote_large'])) {

					$images_generates[$lot->num_hces1 . '-' . $lot->lin_hces1 . $y] = $lot;
				}
			} else {

				break;
			}
		}
		return $images_generates;
	}

	public function converterImage($imagePathToBase64Url)
	{
		$image = base64_decode(strtr($imagePathToBase64Url, '-_.', '+/='));

		$imageWebp = public_path($image);

		if (!file_exists($imageWebp)) {
			return response('Image not found', 404);
		}

		$imageJpgPath = str_replace('.webp', '.jpg', $imageWebp);
		Image::make($imageWebp)->save($imageJpgPath, 100, 'jpg');

		return response()->file($imageJpgPath);
	}
}
