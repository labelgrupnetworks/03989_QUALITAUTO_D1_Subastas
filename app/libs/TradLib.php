<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\libs;

use Illuminate\Support\Facades\Config;
use App\Models\V5\WebTranslateHeaders;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB as DB;

class TradLib {

	public static function getAppMobileTranslation($langs=["es"])
	{
		$theme = config('app.theme');
		$lang = head($langs);
		foreach ($langs as $lang){
			$translations[$lang]=array();

			$mobileDefaultFile = (__DIR__ . "/../../resources/lang/$lang/appMobile.php");
			if(!file_exists($mobileDefaultFile)){
				return;
			}
			$mobileDefault = include $mobileDefaultFile;

			$pathMobileThemeFile = (__DIR__ . "/../../resources/lang/$lang/appMobile_$theme.php");

			if(!file_exists($pathMobileThemeFile)){
				$translations[$lang]["translation"] = $mobileDefault;
			}else{
				$mobileTheme = include $pathMobileThemeFile;

				$translations[$lang]["translation"] =  array_replace_recursive($mobileDefault, $mobileTheme);
			}
		}

		return $translations;
	}

	public static function getAdminTranslation()
	{
		$theme = config('app.theme');
		$lang = config('app.locale');

		$adminDefaultFile = lang_path("$lang/admin-default-app.php");
		if(!file_exists($adminDefaultFile)){
			return;
		}

		$adminDefault = include $adminDefaultFile;

		$pathAdminThemeFile = lang_path("$lang/admin-$theme-app.php");

		if(!file_exists($pathAdminThemeFile)){
			return $adminDefault;
		}

		$adminTheme = include $pathAdminThemeFile;

		return array_replace_recursive($adminDefault, $adminTheme);
	}

	public static function getTranslations($language = null)
	{
		$language = $language ?: Config::get('app.locale');
		$languageLower = strtolower($language);

		if (!file_exists(lang_path("$languageLower/app.php"))) {
			$languageLower = 'es';
		}

		$lang = include lang_path("$languageLower/app.php");

		$databaseTranslates = Cache::remember(Config::get('cache.prefix') . "translates.$language", 60, function () use ($language) {
			try {
				return WebTranslateHeaders::getTranslations($language)->get()
					->groupBy('key_header')->map(function ($item) {
						return $item->pluck('web_translation', 'key_translate');
					})->toArray();
			} catch (\Exception $e) {
				return [];
			}
		});

		return array_replace_recursive($lang, $databaseTranslates);
	}

	public static function getArchiveTranslations($language)
	{
		$language = strtolower($language);
		if(!file_exists(lang_path("$language/app.php"))){
			$language = 'es';
		}

		$lang = include lang_path("$language/app.php");
		return $lang;
    }

    /**
     * Obtener la key de una traduccion dada una header y una traduccion
     * @param string $header donde buscar
     * @param string $translate web_translate que deba coincidir
     * @param string $lang idioma
     * @return string key de la palabra
     */
    public static function getStringKeyTranslate(string $header, string $translate, string $lang): string {

        $traducciones = TradLib::getTranslations($lang, null, 0);

        foreach ($traducciones[$header] as $key => $value) {
            if ($value == $translate) {
                return $key;
            }
        }
        return false;
    }

    /**
     * Obtener el valor de una traduccion en el idoma seleccionado dado un header y una key
     * @param string $header donde buscar
     * @param string $keyTranslate
     * @param string $lang
     * @return string
     */
    public static function getWebTranslateWithStringKey(string $header, string $keyTranslate, string $lang): string {

        $traducciones = TradLib::getTranslations($lang, null, 0);

        foreach ($traducciones[$header] as $key => $value) {
            if ($key == $keyTranslate) {
                return $value;
            }
        }
        return false;
    }

    /**
     * Traducir ruta actual al idioma contrario
     * @param string $rutaActual ruta actual sin el punto final del idioma
     * @param string $idiomaActual idioma actual
     * @param  string $idiomaTraducir idioma al que traducir
     * @return string Ruta traducida
     */
    public static function getRouteTranslate(string $rutaActual, string $idiomaActual, string $idiomaTraducir): string {

        $ruta = "";

        if (empty($rutaActual)) {
            return $ruta;
        }

		$seoCompleta = TradLib::getSeoTranslate($rutaActual, $idiomaActual, $idiomaTraducir);
		if(!empty($seoCompleta)){
			return "/$seoCompleta";
		}

        $rutaSplit = explode('/', $rutaActual);

		if (count($rutaSplit) == 1) {
			return "/$rutaActual";
		}

        foreach ($rutaSplit as $key => $value) {

            $traduccionSeo = TradLib::getSeoTranslate($value, $idiomaActual, $idiomaTraducir);

            if (!empty($traduccionSeo)) {

                $rutaSplit[$key] = $traduccionSeo;
            } else {

                $keyTrad = TradLib::getStringKeyTranslate('links', $value, $idiomaActual, 0);
                if (!empty($keyTrad)) {
                    $rutaSplit[$key] = TradLib::getWebTranslateWithStringKey('links', $keyTrad, $idiomaTraducir);
                }
            }

            $ruta .= "/" . $rutaSplit[$key];
        }


        return ($ruta);
    }

    /**
     * Buscar traduccion segun tabla web_seo_routes
     * @param string $palabra
     * @param string $idiomaActual
     * @param string $idiomaTraducir
     * @return string
     */
    private static function getSeoTranslate(string $palabra, string $idiomaActual, string $idiomaTraducir): string {

        $keySeo = DB::table('WEB_SEO_ROUTES')->select('KEY_SEO_ROUTES')
                        ->where([
                            ['KEYLANG_SEO_ROUTES', '=', $palabra],
                            ['LANG_SEO_ROUTES', '=', $idiomaActual],
                            ['ID_EMP', '=', Config::get('app.main_emp')],
                        ])->first();

        if (empty($keySeo)) {
            return false;
        }

        $palabraTraducida = DB::table('WEB_SEO_ROUTES')->select('KEYLANG_SEO_ROUTES')
                        ->where([
                            ['KEY_SEO_ROUTES', '=', $keySeo->key_seo_routes],
                            ['LANG_SEO_ROUTES', '=', $idiomaTraducir],
                            ['ID_EMP', '=', Config::get('app.main_emp')],
                        ])->first();

        if (empty($palabraTraducida)) {
            return false;
        }

        return $palabraTraducida->keylang_seo_routes;
    }


	public function createTranslatesJs($lang)
	{
		$theme = Config::get('app.theme');
		$dir = str_replace("\\", "/", public_path("js/lang/$lang"));

		if(!is_dir($dir)){
    		mkdir($dir, 0775, true);
		}

		$trans = self::getTranslations($lang);

		$keysJs = collect(array_keys($trans))->filter(function($value){
			if(strpos($value, '_js') !== false){
				return true;
			}
		})->values();

		$translatesJs = [];
		foreach ($trans as $key => $value) {
			if($keysJs->contains($key)){
				$translatesJs[$key] = $trans[$key];
			}
		}

		$variable = "const translates = ";
		$pathFile = str_replace("\\", "/", "$dir/$theme-app.js");
		file_put_contents($pathFile, $variable . json_encode($translatesJs));

		return ($translatesJs);
	}

}
