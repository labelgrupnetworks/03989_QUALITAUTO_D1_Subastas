<?php

namespace App\libs;

use Config;
use DB;
use \ForceUTF8\Encoding;
use \App\libs\MobileDetect;
use App\Providers\ToolsServiceProvider as Tools;

class BannerLib
{

	static function bannerParallax($key = 0, $class = "", $height = '100%', $emp = null){

		if (!$key){
			return false;
		}
		if(empty($emp)){
			$emp = Config::get("app.main_emp");
		}

		$theme = Config::get('app.theme');
		$html = "";

		$banner = DB::table("WEB_NEWBANNER")->where("KEY", $key)->where("EMPRESA",$emp )->where("activo", 1)->first();

		if (empty($banner)){
			return false;
		}

		$item = DB::table("WEB_NEWBANNER_ITEM")->where("ID_WEB_NEWBANNER", $banner->id)->where('ACTIVO', 1)->where("LENGUAJE", strtoupper(Config::get("app.locale")))->orderBy("bloque")->orderBy("orden")->orderBy("WEB_NEWBANNER_ITEM.ID")->first();
		$MobileDetect = new MobileDetect();

		$rutaImg ="/img/banner/$theme/$emp/$banner->id/$item->id/" ;

		#añadimos el locale a un array para poder buscar por idimo principal y si n oesta en ES
		$languages[strtoupper(Config::get("app.locale"))] = 1;
		#añadimos el ES despues para que busque primero en el idioma principal, si el principal es ES, esto no hace nada
		$languages["ES"] = 1;


		if ($MobileDetect->isMobile() ){

			foreach (["jpg","gif"] as  $extension){
				foreach ($languages as  $locale=>$a){
					$pathImg = $rutaImg . $locale  . "_mobile.$extension";
					if(file_exists(public_path().$pathImg)){
						break 2;
					}else{
						#si no existe en mobile buscamos en tamaño escritorio
						$pathImg = $rutaImg . $locale  . ".$extension";
						if(file_exists(public_path().$pathImg)){
							break 2;
						}

					}
				}
			}

		}else{
			foreach (["jpg","gif"] as  $extension){
				foreach ($languages as  $locale=>$a){
					#si no existe en mobile buscamos en tamaño escritorio
					$pathImg = $rutaImg . $locale  . ".$extension";
					if(file_exists(public_path().$pathImg)){
						break 2;
					}
				}
			}
		}


		if ($item->url) {
			if ($item->ventana_nueva) {
				$html .= '<a href="' . $item->url . '" target="_blank">';
			} else {
				$html .= '<a href="' . $item->url . '">';
			}
		}
		$html .= '<div class="bann-parallax" style="background-image: url(\'' .  Tools::urlAssetsCache($pathImg) . '\'); min-height: ' . $height . ';"></div>';
		if ($item->url) {
			$html .= '</a>';
		}
		return $html;


	}

	static function bannersPorKey($key = 0, $class = "", $options = ['dots' => true, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1], $emp = null, $event = false, $methodEvent = '')
	{

		if (!$key){
			return false;
		}
		if(empty($emp)){
			$emp = Config::get("app.main_emp");
		}

		$html = "";

		$banner = DB::table("WEB_NEWBANNER")->where("KEY", $key)->where("EMPRESA",$emp )->where("activo", 1)->first();

		if (empty($banner)){
			return false;
		}

		$theme = Config::get('app.theme');

		$bannerObject = DB::table("WEB_NEWBANNER_ITEM")->where("ID_WEB_NEWBANNER", $banner->id)->where('ACTIVO', 1)->where("LENGUAJE", strtoupper(Config::get("app.locale")))->orderBy("bloque")->orderBy("orden")->orderBy("WEB_NEWBANNER_ITEM.ID");

		#reducimos mucho los tiempos de carga si no cargamos los clob y los convertimos a varchar de 4000
		if ( env('APP_DEBUG') || \Config::get("app.clobToVarchar")) {
			$bannerObject = $bannerObject->select("dbms_lob.substr(TEXTO, 4000, 1 ) texto, ORDEN, ACTIVO, ID, ID_WEB_NEWBANNER, VENTANA_NUEVA, BLOQUE, URL, LENGUAJE");
		}
		$items = $bannerObject->get();
		$tipo = DB::table("WEB_NEWBANNER_TIPO")->where("ID", $banner->id_web_newbanner_tipo)->first();
		$bloques = explode(",", $tipo->bloques);

		if(is_array($options) && count($items) == 1){
			$options['dots'] = false;
		}


		$itemsPorBloque = array();
		foreach ($items as $item) {
			if (!isset($itemsPorBloque[$item->bloque]))
				$itemsPorBloque[$item->bloque] = array();
			$itemsPorBloque[$item->bloque][] = $item;
		}

		if ($tipo->completo) {
			$html .= "<div class='container-fluid'><div class='row rowBanner'>";
		} else {
			$html .= "<div class='container'><div class='row rowBanner'>";
		}

		$MobileDetect = new MobileDetect();

		foreach ($bloques as $k => $tipo_item) {

			$rand = rand(0, 999);

			$cols = round(12 / sizeof($bloques));

			$html .= '<div class="column_banner col-xs-12 col-md-' . $cols . '">';
			$html .= '<div id="banner' . $rand . '" class="' . $class . '">';

			if (isset($itemsPorBloque[$k])) {


				if ($tipo_item == "imagen" || $tipo_item == "imgBlock" || $tipo_item == "imgSingle") {

					foreach ($itemsPorBloque[$k] as $index => $item) {
						$item_name = "item_".$tipo_item ;
						$rutaImg ="/img/banner/$theme/$emp/$banner->id/$item->id/" ;

						#añadimos el locale a un array para poder buscar por idimo principal y si n oesta en ES
						$languages[strtoupper(Config::get("app.locale"))] = 1;
						#añadimos el ES despues para que busque primero en el idioma principal, si el principal es ES, esto no hace nada
						$languages["ES"] = 1;


						if ($MobileDetect->isMobile()) {
							// MOBILE
							$html .= "<div class=\"item $item_name pos_item_$index  \">";
							if ($item->url) {
								if ($item->ventana_nueva) {
									$html .= '<a href="' . $item->url . '" target="_blank">';
								} else {
									$html .= '<a href="' . $item->url . '">';
								}
							}

							//Por defecto si no existe imagen mobile se muestra la de escritorio
							//$imagen_mobile = public_path() . '/themes/' . $theme . '/assets/img/banner/' . $banner->id . '/' . $item->id . '/' . strtoupper(Config::get("app.locale")) . '_mobile.jpg';


							foreach (["jpg","gif"] as  $extension){
								foreach ($languages as  $locale=>$a){
									$pathImg = $rutaImg . $locale  . "_mobile.$extension";
									if(file_exists(public_path().$pathImg)){
										break 2;
									}else{
										#si no existe en mobile buscamos en tamaño escritorio
										$pathImg = $rutaImg . $locale  . ".$extension";
										if(file_exists(public_path().$pathImg)){
											break 2;
										}

									}
								}
							}

							$html .= '<img src="' . Tools::urlAssetsCache($pathImg) . '" width="100%">';

							if ($item->texto) {
								$html .= "<span>" . $item->texto . "</span>";
							}
							if ($item->url) {
								$html .= '</a>';
							}
							$html .= '</div>';
						} else {
							$html .= "<div class=\"item $item_name pos_item_$index hidden-xs\">";
							if ($item->url) {
								if ($item->ventana_nueva) {
									$html .= '<a href="' . $item->url . '" target="_blank">';
								} else {
									$html .= '<a href="' . $item->url . '">';
								}
							}


							foreach (["jpg","gif"] as  $extension){
								foreach ($languages as  $locale=>$a){
									$pathImg = $rutaImg . $locale  . ".$extension";
									if(file_exists(public_path().$pathImg)){
										break 2;
									}
								}
							}




							$html .= '<img src="' . Tools::urlAssetsCache($pathImg) . '" width="100%">';

							if ($item->texto) {
								$html .= "<span>" . $item->texto . "</span>";
							}
							if ($item->url) {
								$html .= '</a>';
							}
							$html .= '</div>';
						}
					}
				}

				if ($tipo_item == "texto") {

					foreach ($itemsPorBloque[$k] as $item) {
						$html .= '<div class="item item_texto">';
						if ($item->url) {
							if ($item->ventana_nueva) {
								$html .= '<a href="' . $item->url . '" target="_blank">';
							} else {
								$html .= '<a href="' . $item->url . '">';
							}
						}
						$html .= $item->texto;
						if ($item->url) {
							$html .= '</a>';
						}
						$html .= '</div>';
					}
				}

				if ($tipo_item == "video") {

					foreach ($itemsPorBloque[$k] as $item) {
						$html .= '<div class="item banner_video">';
						$html .='<iframe src="'.$item->texto.'?rel=0&controls=0&fs=0&iv_load_policy=3&showinfo=0&modestbranding=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""  frameborder="0"></iframe>';
						$html .= '</div>';
					}
				}
			}
			$html .= '</div></div>';
			if ($tipo_item == "imagen" ||  $tipo_item == "texto") {
				if(is_array($options)){
					$options = json_encode($options);
				}

				$html .= "<script>$('#banner" . $rand . "').slick($options)";

				if($event){

					switch($event){
						case 'beforeChange':
							$html .= ".on('beforeChange', function(event, slick, currentSlide, nextSlide){ ". $methodEvent ."(event, slick, currentSlide, nextSlide);})";
							break;

						case 'afterChange':
							$html .= ".on('afterChange', function(slick, currentSlide){ ". $methodEvent ."(slick, currentSlide);})";
							break;

						default:
							break;
					}

					$html .= ".on('" . $event . "', function(){ ". $methodEvent ."(this) })";
				}

				$html .= ";</script>";
			}
		}

		$html .= "</div></div>";


		return $html;
	}





	static function bannersPorUbicacion($ubicacion = 0, $class = 0)
	{


		$banners = DB::table("WEB_NEWBANNER")->where("UBICACION", "LIKE", "%" . $ubicacion . "%")->where("activo", 1)->orderBy("orden")->orderBy("WEB_NEWBANNER.id")->get();
		$html = "";
		foreach ($banners as $item) {
			$html .= BannerLib::bannersPorKey($item->key, $class);
			$html .= "<div class='clearfix'></div>";
		}

		return $html;
	}

	static function bannersPorUbicacionKeyAsClass($ubicacion = 0, $options = array())
	{


		$banners = DB::table("WEB_NEWBANNER")->where("UBICACION",  $ubicacion )->where("activo", 1)->orderBy("orden")->orderBy("WEB_NEWBANNER.ID")->get();
		$html = "";
		foreach ($banners as $item) {
			$class= $item->key;
			$option = $options[$item->key]?? null;

			$html .= BannerLib::bannersPorKey($item->key, $class, $option);
			$html .= "<div class='clearfix'></div>";
		}

		return $html;
	}
}
