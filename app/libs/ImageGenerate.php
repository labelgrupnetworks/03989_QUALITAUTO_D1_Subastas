<?php

namespace App\libs;

use App\Models\V5\Web_Images_Size;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;
use Exception;
use Illuminate\Support\Facades\Log;

//use DB;
/**
 * Description of ImageGenerate
 *
 * @author LABEL-RSANCHEZ
 */
class ImageGenerate
{
	//put your code here
	public function exist()
	{
		//falta hacer la funcion que deuelva la url de la imagen si esta existe y false en caso de que no exista.
		return false;
	}
	public function resize_img($size, $img, $theme, $base64 = false)
	{
		$this->logFromErpRequest("resize_img $size, $img, $theme, $base64");

		$new_image_folders_config = Config::get("app.new_image_folders");
		//las imagenes de subastas no se han reubicado
		if ($new_image_folders_config && $size != 'subasta_medium' && $size != 'subasta_large') {
			$new_image_folders = true;
		} else {
			$new_image_folders = false;
		}


		# Path de la carpeta de imágenes

		if ($new_image_folders) {
			$rutas = explode("-", $img);
			$emp = $rutas[0];
			//estan viniendo url  desde facebook de subastas con tamaño  lote_medium en vez de subasta_medium y da error, para que coja la ruta correcta usaremos el path normal
			if (!empty($rutas[1])) {
				$numhces = $rutas[1];
				$pathImagenes = 'img/' . $emp . '/' . $numhces . '/';
			} else {
				$new_image_folders = false;
				$pathImagenes = 'img/';
			}
		} else {
			$pathImagenes = 'img/';
		}

		$pathImagenesThumbs = 'img/thumbs/';

		$imagenOriginal = $pathImagenes  . $img;
		if (!file_exists($imagenOriginal)) {
			$len_img = strlen($imagenOriginal);
			$imagenOriginal = substr($imagenOriginal, 0, $len_img - 3) . "JPG";
			if (!file_exists($imagenOriginal)) {
				$imagenOriginal = substr($imagenOriginal, 0, $len_img - 3) . "jpeg";
			}
			if (!file_exists($imagenOriginal)) {
				$imagenOriginal = substr($imagenOriginal, 0, $len_img - 3) . "JPEG";
			}
		}
		if (!empty(Config::get('app.img_quality')) && Config::get('app.img_quality') > 75) {
			$imgQuality = array("jpeg" => Config::get('app.img_quality'), "png" => 7); # Porcentaje de calidad de las imágenes reescaladas
		} else {
			$imgQuality = array("jpeg" => 75, "png" => 7); # Porcentaje de calidad de las imágenes reescaladas
		}

		/* tamaño imagenes */
		$sql = "select * from WEB_IMAGES_SIZE WHERE ID_EMP = :emp";
		$parameters = array('emp' => Config::get('app.emp'));
		$sizes_DB = CacheLib::useCache('WEB_IMAGES_SIZE', $sql, $parameters, 100);
		$sizes = array();

		//$sizes_DB = \DB::select($sql);

		foreach ($sizes_DB as $size_DB) {
			$sizes[$size_DB->name_web_images_size] = $size_DB->size_web_images_size;
		}

		//Si la imagen ya viene comprimida no hacemos nada ya que perdería calidad
		$comprimir = true;
		switch ($size) {
			case 'real':
				if (file_exists($imagenOriginal)) {
					//si pesa menos de 500 bytes es que es una imagen errornea y no debemos tratarla
					$peso = filesize($imagenOriginal);
					if ($peso <= 500) {
						$width_size = NULL;
						break;
					}
					list($imgWidth, $imgHeight) = getimagesize($imagenOriginal);
					$area = $imgWidth * $imgHeight;

					$compresion = $peso / $area;


					if ($compresion <  floatval(Config::get("app.compresion_img", '0.35'))) {
						$comprimir = false;
					}
					$width_size = $imgWidth;
				} else {
					$width_size = NULL;
				}

				break;
			case 'lote_large':
				if (isset($sizes) && isset($sizes['lote_large'])) {
					$width_size = $sizes['lote_large'];
				} else {
					$width_size = 838;
				}
				break;
			case 'lote_medium_large':
				if (isset($sizes) && isset($sizes['lote_medium_large'])) {
					$width_size = $sizes['lote_medium_large'];
				} else {
					$width_size = 500;
				}

				break;
			case 'lote_medium':
				if (isset($sizes) && isset($sizes['lote_medium'])) {
					$width_size = $sizes['lote_medium'];
				} else {
					$width_size = 367;
				}
				break;
			case 'lote_small':
				if (isset($sizes) && isset($sizes['lote_small'])) {
					$width_size = $sizes['lote_small'];
				} else {
					$width_size = 100;
				}
				break;

			case 'subasta_medium':
				if (isset($sizes) && isset($sizes['subasta_medium'])) {
					$width_size = $sizes['subasta_medium'];
				} else {
					$width_size = 255;
				}
				break;

			case 'subasta_large':
				if (isset($sizes) && isset($sizes['subasta_large'])) {
					$width_size = $sizes['subasta_large'];
				} else {
					$width_size = 750;
				}
				break;

			case 'square_medium':
				if (isset($sizes) && isset($sizes['square_medium'])) {
					$width_size = $sizes['square_medium'];
				}
				break;

			case 'square_large':
				if (isset($sizes) && isset($sizes['square_large'])) {
					$width_size = $sizes['square_large'];
				}
				break;


			default:
				$width_size = 120;
		}

		if ($size == 'real') {
			$pathImagenesThumbsSize = "img/thumbs/real/";
		} else {
			$pathImagenesThumbsSize = "img/thumbs/$width_size/";
		}


		/* En caso de no recibir imagen, o la imagen original no está disponible, o pesa muy poco po lo que puede que sea erronea mostramos la no encontrada */
		if ($img == "" || $width_size == "" || !file_exists($imagenOriginal) || filesize($imagenOriginal) < 500) {
			$this->logFromErpRequest("no existe $imagenOriginal");
			$image_to_load =  $this->no_foto($theme, $size);
		} elseif (!$comprimir) {
			$image_to_load = $imagenOriginal;
		} else {
			/* Buscamos si la carpeta thumbs existe */
			if (!file_exists($pathImagenesThumbs)) {
				try {
					mkdir($pathImagenesThumbs, 0775, true);
					chmod($pathImagenesThumbs, 0775);
				} catch (Exception $e) {
					# Controlar el error en el log y la app
					echo $e->getMessage();
				}
			}
			/* Buscamos si la carpeta thumbsSize existe */
			if (!file_exists($pathImagenesThumbsSize)) {
				try {
					mkdir($pathImagenesThumbsSize, 0775, true);
					chmod($pathImagenesThumbsSize, 0775);
				} catch (Exception $e) {
					# Controlar el error en el log y la app
					echo $e->getMessage();
				}
			}

			if ($new_image_folders) {
				//comprobamos que exista la carpeta de empresa
				$pathImagenesThumbsSize .= "$emp/";
				if (!file_exists($pathImagenesThumbsSize)) {
					try {
						mkdir($pathImagenesThumbsSize, 0775, true);
						chmod($pathImagenesThumbsSize, 0775);
					} catch (Exception $e) {
						# Controlar el error en el log y la app
						echo $e->getMessage();
					}
				}
				//comprobamos que exista la carpeta de la hoja de cesion
				$pathImagenesThumbsSize .= "$numhces/";
				#ya n ose generan las imagenes en la carpeta real, ahora se sustituyen las originales
				if ($size != "real") {
					if (!file_exists($pathImagenesThumbsSize)) {
						try {
							mkdir($pathImagenesThumbsSize, 0775, true);
							chmod($pathImagenesThumbsSize, 0775);
						} catch (Exception $e) {
							# Controlar el error en el log y la app
							echo $e->getMessage();
						}
					}
				}
			}
			$imagenThumbs = $pathImagenesThumbsSize . $img;


			$image_to_load = $imagenThumbs;

			/* Comprobamos si la fecha de la imagen original es más nueva que la thumb, de ser así, se genera de nuevo */
			$accederThumb = false;

			if (file_exists($imagenThumbs)) {
				$fechaOriginal = date("Y-m-d H:i:s", filemtime($imagenOriginal)); // $fechaOriginal = date ("Y-m-d H:i:s", filemtime($imagenOriginal));
				$fechaThumb = date("Y-m-d H:i:s", filemtime($imagenThumbs));

				//si la imagen original es mas nueva o si la imagen thumbs esta creada pero pesa demasiado poco para ser correcta
				if ((strtotime($fechaOriginal) > strtotime($fechaThumb)) || filesize($imagenThumbs) < 200) {

					$accederThumb = true;
				}
			}

			/* Buscamos si existe la foto que nos están enviando */

			# En caso de no encontrar alguna de las dos imágenes se hacen nuevas
			if (!file_exists($imagenThumbs) || $accederThumb) {


				$imgMaxWidth = $width_size;
				$imgMaxHeight = 3000; # Valor grande para mantener proporción horizontal

				try {
					if (file_exists($imagenThumbs)) {
						unlink($imagenThumbs);
					}

					$image_type = $this->image_type($imagenOriginal);

					$source = $this->image_create($image_type, $imagenOriginal);
					// $imgMaxWidth=1000;
					//  $imgQuality=$imgQuality = array("jpeg" => 90, "png" => 7) ;
					//si se puede crear la imagen
					if ($source) {

						#si el tamaño no es un cuadrado, hacemos el escalado normal
						if (strpos($size, "square") === false) {
							list($imgWidth, $imgHeight) = getimagesize($imagenOriginal);
							$imgAspectRatio = $imgWidth / $imgHeight;
							//si la imagen propuesta es mas grande la dejamos como está
							if ($imgMaxWidth > $imgWidth) {
								$imgMaxWidth = $imgWidth;
								$imgMaxHeight = $imgHeight;
							}
							if ($imgMaxWidth / $imgMaxHeight > $imgAspectRatio) {
								$imgMaxWidth = $imgMaxHeight * $imgAspectRatio;
							} else {
								$imgMaxHeight = $imgMaxWidth / $imgAspectRatio;
							}

							$image_p = imagecreatetruecolor($imgMaxWidth, $imgMaxHeight);
							imagecopyresampled($image_p, $source, 0, 0, 0, 0, $imgMaxWidth, $imgMaxHeight, $imgWidth, $imgHeight);
						} else {
							#si es un cuadrado


							list($imgWidth, $imgHeight) = getimagesize($imagenOriginal);

							if ($imgWidth > $imgHeight) {
								$original_start_Y = 0;
								# el punto de inicio en x es la mitad de lo que sobra la restar la anchura original
								$original_start_X = ($imgWidth - $imgHeight) / 2;
								#marcamos el ancho original como el alto para que recorte un cuadrado del original
								$imgWidth = $imgHeight;
							} else {
								$original_start_X = 0;
								# el punto de inicio en y es la mitad de lo que sobra al restar la altura original
								$original_start_Y = ($imgHeight - $imgWidth) / 2;
								$imgHeight = $imgWidth;
							}
							#si la imagen original es más pequeña, cogemos como tamaño máximo la original
							if ($imgWidth < $imgMaxWidth) {
								$imgMaxWidth = $imgWidth;
							}
							$imgMaxHeight = $imgMaxWidth;
							$image_p = imagecreatetruecolor($imgMaxWidth, $imgMaxHeight);
							imagecopyresampled($image_p, $source, 0, 0, $original_start_X, $original_start_Y, $imgMaxWidth, $imgMaxHeight, $imgWidth, $imgHeight);


							//	echo "$original_start_X, $original_start_Y, $imgMaxWidth, $imgMaxHeight, $imgWidth, $imgHeight"; die();

						}

						#si es el tamaño real, modificamos la original
						if ($size == "real") {
							$imagenThumbs =  $imagenOriginal;
							$image_to_load = $imagenOriginal;
						}

						$this->image_quality($image_type, $image_p, $imagenThumbs, $imgQuality);
						chmod($imagenThumbs, 0777);
					}
					//Si no se puede crear imagen
					else {
						//cargamos la imagen original por que no se puede crear el resto de tamaños, por ejemplo por que la imagen no es JPG
						$image_to_load = $imagenOriginal;
					}
				} catch (\Exception $e) {

					$this->logFromErpRequest($e->getMessage());

					# Controlar el error en el log y la app
					$image_to_load =  $this->no_foto($theme, $size);
				}
			}
		}
		if ($base64) {
			$img_content = file_get_contents($image_to_load);
			return base64_encode($img_content);
		} else {
			# Escupimos la imagen por el navegador
			$type = 'image/jpeg';
			header('Content-Type:' . $type);
			header('Content-Length: ' . filesize($image_to_load));
			readfile($image_to_load);
			//no borrar el die();
			die();
		}
	}

	private function image_create($image_type, $imagenOriginal)
	{


		switch ($image_type) {
			case 'jpeg':
				$image = imagecreatefromjpeg($imagenOriginal);
				break;
			case 'png':
				$image = imagecreatefrompng($imagenOriginal);
				break;
		}

		return $image;
	}
	private function image_quality($image_type, $image_p, $imagenThumbs, $imgQuality)
	{

		switch ($image_type) {
			case 'jpeg':
				imagejpeg($image_p, $imagenThumbs, $imgQuality['jpeg']);
				break;
			case 'png':
				imagepng($image_p, $imagenThumbs, $imgQuality['png']);
				break;
		}
		// return $imagenThumbs;
	}
	private function image_type($imagenOriginal)
	{
		switch (exif_imagetype($imagenOriginal)) {
			case 2:
				$type = 'jpeg';
				break;
			case 3:
				$type =  'png';
				break;
			default:
				$type =  'jpeg';
		}

		return $type;
	}

	private function no_foto($theme, $size)
	{
		if (file_exists("themes/" . $theme . "/img/items/no_photo_" . $size . ".png")) {
			$image_to_load = "themes/" . $theme . "/img/items/no_photo_" . $size . ".png"; # Indicar path de imagen no disponible al tamaño que se necesita
		} else {
			$image_to_load = "themes/" . $theme . "/img/items/no_photo.png"; # Indicar path de imagen no disponible
		}

		return $image_to_load;
	}

	public function generateMini($img, $size)
	{
		$new_image_folders = Config::get("app.new_image_folders");

		if ($new_image_folders) {
			$rutas = explode("-", $img);
			$emp = $rutas[0];
			$numhces = $rutas[1];
			$imageThumb = "img/thumbs/$size/" . $emp . '/' . $numhces . '/' . $img;
			$pathImagenes = public_path('img/' . $emp . '/' . $numhces . '/');
		} else {
			$imageThumb = "img/thumbs/$size/$img";
			$pathImagenes = public_path('img/');
		}


		if (!empty(Config::get('app.img_quality')) && Config::get('app.img_quality') > 75) {
			$imgQuality = array("jpeg" => Config::get('app.img_quality'), "png" => 7); # Porcentaje de calidad de las imágenes reescaladas
		} else {
			$imgQuality = array("jpeg" => 75, "png" => 7); # Porcentaje de calidad de las imágenes reescaladas
		}

		$imagenOriginal = $pathImagenes  . $img;
		//si no existe buscamos la misma imagen con JPG en mayusculas
		if (!file_exists($imagenOriginal)) {
			$len_img = strlen($imagenOriginal);
			$imagenOriginal = substr($imagenOriginal, 0, $len_img - 3) . "JPG";
			if (!file_exists($imagenOriginal)) {
				$imagenOriginal = substr($imagenOriginal, 0, $len_img - 3) . "jpeg";
			}
			if (!file_exists($imagenOriginal)) {
				$imagenOriginal = substr($imagenOriginal, 0, $len_img - 4) . "JPEG";
			}
		}
		//si tampoco existe no hacemos nada
		if (!file_exists($imagenOriginal)) {
			Log::info("no existe $imagenOriginal");
			return False;
		}
		$generateThumb = false;



		/* TEST CARPETAS IMG */
		if ($new_image_folders) {
			//comprobamos que exista la carpeta de empresa
			$pathImagenesThumbsSize = public_path("img/thumbs/$size/$emp/");
			if (!file_exists($pathImagenesThumbsSize)) {
				try {
					mkdir($pathImagenesThumbsSize, 0775, true);
					chmod($pathImagenesThumbsSize, 0775);
				} catch (Exception $e) {
					# Controlar el error en el log y la app
					echo $e->getMessage();
				}
			}
			//comprobamos que exista la carpeta de la hoja de cesion
			$pathImagenesThumbsSize .= "$numhces/";
			if (!file_exists($pathImagenesThumbsSize)) {
				try {
					mkdir($pathImagenesThumbsSize, 0775, true);
					chmod($pathImagenesThumbsSize, 0775);
				} catch (Exception $e) {
					# Controlar el error en el log y la app
					echo $e->getMessage();
				}
			}
			//ponemos la nueva ruta completa
			$imageThumb = $pathImagenesThumbsSize . $img;
		}
		/* FIN TEST CARPETAS IMG */


		if (file_exists($imageThumb)) {
			$fechaOriginal = date("Y-m-d H:i:s", filemtime($imagenOriginal));
			$fechaThumb = date("Y-m-d H:i:s", filemtime($imageThumb));
			if (strtotime($fechaOriginal) > strtotime($fechaThumb)) {       // echo  "image original mas nueva " .date("Y-m-d H:i:s", filectime ($imagenOriginal))."<br> $imageThumb fecha nueva ".date ("Y-m-d H:i:s", filectime ($imageThumb));      die();
				$generateThumb = true;
			} else {

				if (!empty(Config::get("app.date_regenerate_image")) && strtotime($fechaThumb) < strtotime(Config::get("app.date_regenerate_image"))) {

					$generateThumb = true;
				}
			}
		}

		if (!file_exists($imageThumb) || $generateThumb) {
			$imgMaxWidth = $size;
			$imgMaxHeight = 3000; # Valor grande para mantener proporción horizontal

			try {


				$image_type = $this->image_type($imagenOriginal);

				$source = $this->image_create($image_type, $imagenOriginal);
				//si se puede crear la imagen
				if ($source) {
					list($imgWidth, $imgHeight) = getimagesize($imagenOriginal);
					$imgAspectRatio = $imgWidth / $imgHeight;
					//si la imagen propuesta es mas grande la dejamos como está
					if ($imgMaxWidth > $imgWidth) {
						$imgMaxWidth = $imgWidth;
						$imgMaxHeight = $imgHeight;
					}
					if ($imgMaxWidth / $imgMaxHeight > $imgAspectRatio) {
						$imgMaxWidth = $imgMaxHeight * $imgAspectRatio;
					} else {
						$imgMaxHeight = $imgMaxWidth / $imgAspectRatio;
					}
					$image_p = imagecreatetruecolor($imgMaxWidth, $imgMaxHeight);
					$image = $this->image_create($image_type, $imagenOriginal);
					imagecopyresampled($image_p, $source, 0, 0, 0, 0, $imgMaxWidth, $imgMaxHeight, $imgWidth, $imgHeight);

					$this->image_quality($image_type, $image_p, $imageThumb, $imgQuality);
					chmod($imageThumb, 0777);
				}
				//Si no se puede crear imagen
				else {
					echo "error source";
					return false;
				}
			} catch (\Exception $e) {
				# Controlar el error en el log y la app
				Log::error($e);
				return false;
			}

			return true;
		}
	}

	public function imageLot($numHces, $linHces = null, $imagePosition = null, $imageSize = null)
	{
		$emp = Config::get('app.emp');
		$path = "img/$emp/$numHces/";
		$images = $this->getlotImages($numHces, $linHces, $imagePosition);

		$sizes = $imageSize
			? [$imageSize]
			: CacheLib::rememberCache('image_sizes', 1200, function () {
				return Web_Images_Size::query()
					->where('name_web_images_size', 'like', '%lote%')
					->pluck('size_web_images_size');
			});

		foreach ($images as $image) {
			[$imageName, $extension] = explode(".", $image);
			$imagePath = public_path($path . $image);

			foreach ($sizes as $size) {
				set_time_limit(60);
				$directoryThumb = "img/thumbs/$size/$emp/$numHces";
				if (!is_dir(public_path($directoryThumb))) {
					mkdir(public_path($directoryThumb), 0775, true);
					chmod(public_path($directoryThumb), 0775);
				}

				$imageThumb = public_path("$directoryThumb/$imageName.jpg");

				$imageMake = Image::make($imagePath);

				$imageMake->resize($size, null, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});

				$imageMake->save($imageThumb, 75, 'jpg');
			}
		}
	}

	public function getlotImages($numHces, $linHces = null, $imagePosition = null)
	{
		$emp = Config::get('app.emp');
		$path = "img/$emp/$numHces/";

		if (!is_dir($path)) {
			return [];
		}

		$images = array_diff(scandir($path), ['.', '..']);

		if (!empty($linHces)) {
			$images = array_filter($images, fn ($image) => $this->isSameLineAndPosition($image, $linHces, $imagePosition));
		}

		return $images;
	}

	/**
	 * ejemplos de nombre
	 * 001-50-1.jpg
	 * 001-50-1_01.jpg
	 * [emp-numHces-linHces]_[imagePosition].[extension]
	 *
	 * si $imagePosition es 0, obtenemos la que concida con el linHces y que no tenga imagePosition
	 * si $imagePosition es null, obtenemos todas las que concidan con el linHces
	 * si $imagePosition no es null, obtenemos la que concida con el linHces y la imagePosition
	 */
	private function isSameLineAndPosition($image, $linHces, $imagePosition)
	{
		if(strpos($image, ".") === false){
			return false;
		}
		[$imageName, $extension] = explode(".", $image);

		if(strpos($imageName, "-") === false){
			return false;
		}
		$imageParams = explode("-", $imageName);
		[$imgEmp, $imgNum, $imgLin] = $imageParams;

		if ($imagePosition === 0) {
			return $imgLin == $linHces && strpos($imgLin, "_") === false;
		}

		if ($imagePosition === null) {
			return $imgLin == $linHces || (strpos($imgLin, "_") !== false && explode("_", $imgLin)[0] == $linHces);
		}

		$imagePosition = str_pad($imagePosition, 2, "0", STR_PAD_LEFT);
		$imgPos = explode("_", $imgLin)[1] ?? null;
		$imgLin = explode("_", $imgLin)[0] ?? $imgLin;

		return $imgLin == $linHces && $imgPos == $imagePosition;
	}

	private function logFromErpRequest($message)
	{
		if(!Config::get('app.debug_erp', false) || !request('from') == 'erp'){
			return;
		}

		Log::debug($message);
	}
}
