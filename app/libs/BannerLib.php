<?php

namespace App\libs;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\PageSetting;
use App\Models\WebNewbannerModel;
use App\Providers\ToolsServiceProvider as Tools;
use Detection\MobileDetect;

/**
 * @method static array getOnlyContentForBanner(WebNewbannerModel|null $banner)
 */
class BannerLib
{
	const BANNER_DEFAULT_OPTIONS = ['dots' => true, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1];

	static function bannerParallax($key = 0, $class = "", $height = '100%', $emp = null){

		if (!$key){
			return false;
		}
		if(empty($emp)){
			$emp = Config::get("app.main_emp");
		}

		$theme = Config::get('app.theme');
		$html = "";

		$banner = CacheLib::rememberCache(self::banerCacheName($key), $seconds = 3600, function() use($key) {
			return WebNewbannerModel::getActiveBannerWithKey($key);
		});

		if (empty($banner)){
			return false;
		}

		$item = $banner->activeItems->first();
		$MobileDetect = new MobileDetect();

		$rutaImg ="/img/banner/$theme/$emp/$banner->id/$item->id/" ;

		#añadimos el locale a un array para poder buscar por idimo principal y si n oesta en ES
		$languages[strtoupper(Config::get("app.locale"))] = 1;
		#añadimos el ES despues para que busque primero en el idioma principal, si el principal es ES, esto no hace nada
		$languages["ES"] = 1;

		if ($MobileDetect->isMobile()){

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

	static function bannerPorId($id, $class = "", $options = self::BANNER_DEFAULT_OPTIONS, $emp = null, $event = false, $methodEvent = '', PageSetting $page_settings = null)
	{
		$banner = WebNewbannerModel::where('id', $id)->first();
		if (!$banner){
			return false;
		}
		return self::bannersPorKey($banner->key, $class, $options, $emp, $event, $methodEvent, $page_settings);
	}


	static function bannersPorKey($key = 0, $class = "", $options = self::BANNER_DEFAULT_OPTIONS, $emp = null, $event = false, $methodEvent = '', PageSetting $page_settings = null)
	{
		if (!$key){
			return false;
		}
		if(empty($emp)){
			$emp = Config::get("app.main_emp");
		}

		$theme = Config::get('app.theme');
		$html = "";

		$banner = CacheLib::rememberCache(self::banerCacheName($key), $seconds = 3600, function() use ($key) {
			return WebNewbannerModel::getActiveBannerWithKey($key);
		});

		$options = !empty($banner->type->opciones) ? json_decode($banner->type->opciones, true) : $options;

		if (empty($banner)){
			return false;
		}
		$bloques = explode(",", $banner->type->bloques);

		if(is_array($options) && count($banner->activeItems) == 1){
			$options['dots'] = false;
		}

		$itemsPorBloque = array();
		foreach ($banner->activeItems as $item) {
			if (!isset($itemsPorBloque[$item->bloque]))
				$itemsPorBloque[$item->bloque] = array();
			$itemsPorBloque[$item->bloque][] = $item;
		}

		$bannerTypeClass = "banner_type_{$banner->type->id}";
		$bannerContainerClass = $banner->type->completo ? "container-fluid" : "container";
		$html .= "<div class='{$bannerContainerClass} {$bannerTypeClass}'>";

		if(is_array($options) && !empty($options['title'])){
			$html .= "<h3 class='banner_title'>{$options['title']}</h3>";
		}
		$html .= "<div class='row rowBanner'>";

		$MobileDetect = new MobileDetect();
		$isMobile = $MobileDetect->isMobile();

		foreach ($bloques as $k => $tipo_item) {

			$rand = rand(0, 999);

			$cols = round(12 / sizeof($bloques));

			$html .= '<div class="column_banner col-xs-12 col-md-' . $cols . '">';
			$html .= '<div id="banner' . $rand . '" class="' . $class . ' charge">';

			if (isset($itemsPorBloque[$k])) {


				if (in_array($tipo_item, ["imagen", "imgBlock", "imgSingle"])) {

					foreach ($itemsPorBloque[$k] as $index => $item) {
						$item_name = "item_".$tipo_item ;
						$rutaImg ="/img/banner/$theme/$emp/$banner->id/$item->id/" ;

						#añadimos el locale a un array para poder buscar por idimo principal y si n oesta en ES
						$languages[strtoupper(Config::get("app.locale"))] = 1;
						#añadimos el ES despues para que busque primero en el idioma principal, si el principal es ES, esto no hace nada
						$languages["ES"] = 1;


						foreach (["webp", "jpg", "gif"] as $extension){
							foreach (array_keys($languages) as $locale){

								$pathImg = $isMobile
									? "{$rutaImg}{$locale}_mobile.$extension"
									: "{$rutaImg}{$locale}.{$extension}";

								$backup = $isMobile
									? "{$rutaImg}{$locale}_mobile.jpg"
									: "{$rutaImg}{$locale}.jpg";

								if(file_exists(public_path($pathImg))){
									break 2;

								}else{
									#si no existe en mobile buscamos en tamaño escritorio
									$pathImg = $rutaImg . $locale  . ".$extension";
									if(file_exists(public_path($pathImg))){
										break 2;
									}
								}
							}
						}

						if(file_exists(public_path($pathImg))) {

							$html .= "<div class=\"item $item_name pos_item_$index";
							$html .= $isMobile ? "\">" : " hidden-xs\">";
							if ($item->url) {
								if ($item->ventana_nueva) {
									$html .= '<a href="' . $item->url . '" target="_blank">';
								} else {
									$html .= '<a href="' . $item->url . '">';
								}
							}

							$publicPath = Tools::urlAssetsCache($pathImg);
							$publicPathBackup = Tools::urlAssetsCache($backup);

							//pathImg in base 64 url
							$pathImgConverter = strtr(base64_encode($pathImg), '+/=', '-_.');

							//change to picture tag
							$html .= "<picture>";
							$html .= "<source srcset=\"$publicPath\" type=\"image/webp\">";
							$html .= "<source srcset=\"$publicPathBackup\" type=\"image/jpg\">";
							$html .= "<img src=\"/img/converter/$pathImgConverter\" alt=\"banner image\">";
							$html .= "</picture>";

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

				if($tipo_item == 'iframe') {
					foreach ($itemsPorBloque[$k] as $item) {
						$hasUrl = !empty($item->url);
						$iframe = $hasUrl ? str_replace('src=""', 'src="'.$item->url.'"', $item->texto) : $item->texto;
						$html .= '<div class="item banner_iframe">';
						$html .= $iframe;
						$html .= '</div>';
					}
				}

				if (strpos($tipo_item, ':') !== false) {
					$viewBlade = explode(':', $tipo_item)[1];

					foreach ($itemsPorBloque[$k] as $item) {
						$params = self::jsonTextToArray($item->texto);
						if ($params) {
							$html .= view("front::includes.banners.$viewBlade", $params);
						}
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
				$html .= ";$('#banner" . $rand . "').removeClass('charge')";
				$html .= ";</script>";
			}
		}

		if ($page_settings != null) {
			$page_settings->addSettings([
				['name' => "banner_edit", 'url' => route('newbanner.edit', ['id' => $banner->id]), 'name_val' => ['key' => $banner->key]]
			]);
		}

		$html .= "</div></div>";

		return $html;
	}

	static function bannersPorUbicacion($ubicacion = 0, $class = 0, PageSetting $page_settings = null)
	{
		$banners = DB::table("WEB_NEWBANNER")->where("UBICACION", "LIKE", "%" . $ubicacion . "%")->where("activo", 1)->orderBy("orden")->orderBy("WEB_NEWBANNER.id")->get();
		$html = "";
		foreach ($banners as $item) {
			$html .= BannerLib::bannersPorKey($item->key, $class, self::BANNER_DEFAULT_OPTIONS, null, false, '', $page_settings);
			$html .= "<div class='clearfix'></div>";
		}

		return $html;
	}

	static function bannersPorUbicacionKeyAsClass($ubicacion = 0, $options = array(), PageSetting $page_settings = null)
	{
		$banners = DB::table("WEB_NEWBANNER")->where("UBICACION",  $ubicacion )->where("activo", 1)->orderBy("orden")->orderBy("WEB_NEWBANNER.ID")->get();
		$html = "";
		foreach ($banners as $item) {
			$class= $item->key;
			$option = $options[$item->key]?? null;

			$html .= BannerLib::bannersPorKey($item->key, $class, $option, null, false, '', $page_settings);
			$html .= "<div class='clearfix'></div>";
		}

		return $html;
	}

	static function banerCacheName($key)
	{
		return "banner_{$key}";
	}

	static function bannerWithView($key, $view, $content = [], $options = [])
	{
		$banner = CacheLib::rememberCache(self::banerCacheName($key), $seconds = 3600, function() use ($key) {
			return WebNewbannerModel::getActiveBannerWithKey($key);
		});

		if(!$banner) {
			return '';
		}

		return view("front::includes.banners.$view", ['banner' => $banner, 'content' => $content, 'options' => $options]);
	}

	static function getOnlyContentForBanner($banner)
	{
		$texts = [];
		$images = [];
		$links = [];

		if($banner) {
			$bannerItems = $banner->activeItems;
			$texts = $bannerItems->pluck('texto')->filter();
			$images = $bannerItems->pluck('images')->filter(function($item) {
				return $item['desktop'] !== null;
			})->values();

			$blockTypes = explode(',' ,$banner->type->bloques);
			$linksPositions = array_keys($blockTypes, 'link');

			foreach($linksPositions as $position) {
				$linkItem = $bannerItems->where('bloque', $position)->first();
				if($linkItem) {
					$links[] = $linkItem->url;
				}
			}

		}

		$data = [
			'texts' => $texts,
			'images' => $images,
			'links' => $links
		];

		return $data;
	}

	/**
	 * Decodifica el texto lo convierte en un array asociativo filtrado.
	 * Se filtra para que si las claves son vacías o los valores son nulos, no se muestre
	 * el banner item.
	 * Tener en cuanta si se crea un banner tipo vista sin variables.
	 *
	 * @param string $text
	 * @return array
	 */
	private static function jsonTextToArray($text)
	{
		if(empty($text)) {
			return [];
		}

		$params = json_decode($text, true);
		if (!is_array($params)) {
			$params = [];
		}
		return array_filter($params);
	}
}
