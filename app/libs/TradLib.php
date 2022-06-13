<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\libs;

use Illuminate\Support\Facades\Config;
use App\Models\Translate;
use Illuminate\Support\Facades\DB as DB;

class TradLib {

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

    public static function getTranslations($language, $emp = null, $withCache = null) {

        $translateModel = new Translate();

        if (empty($emp)) {
            $emp = Config::get('app.main_emp');
        }

		require lang_path(strtolower($language) . DIRECTORY_SEPARATOR . 'app.php');

        $sql = "SELECT WEB_TRANSLATE_HEADERS.KEY_HEADER,WEB_TRANSLATE_KEY.KEY_TRANSLATE,WEB_TRANSLATE.WEB_TRANSLATION "
                . "FROM WEB_TRANSLATE_HEADERS "
                . "JOIN WEB_TRANSLATE_KEY ON (WEB_TRANSLATE_HEADERS.ID_HEADERS = WEB_TRANSLATE_KEY.ID_HEADERS_TRANSLATE AND WEB_TRANSLATE_KEY.ID_EMP = :emp) "
                . "JOIN WEB_TRANSLATE ON (WEB_TRANSLATE_KEY.ID_KEY = WEB_TRANSLATE.ID_KEY_TRANSLATE AND WEB_TRANSLATE.ID_EMP = :emp) "
                . "WHERE WEB_TRANSLATE.LANG = :language order by key_header, key_translate";

        $params = array(
            'emp' => $emp,
            'language' => strtoupper($language)
		);

        $data = CacheLib::useCache('translate', $sql, $params, $withCache);

        $translate = array();

        foreach ($data as $key => $value) {
            if (empty($translate[$value->key_header])) {
                $translate[$value->key_header] = array();
            }
            $translate[$value->key_header][$value->key_translate] = $value->web_translation;
        }

        //primer merge para obtener todas las key_headers
        $headers = array_merge($lang, $translate);
        $result = array();

        //segundo merge en cada key_header para obtener todas las translate_keys
        foreach ($lang as $keyLang => $valueLang) {
            $result[$keyLang] = array_merge($lang[$keyLang], $headers[$keyLang]);
        }

        //añadimos los headers y su contenido que no existan en el archivo
        foreach ($headers as $keyHeader => $value) {
            if (empty($result[$keyHeader])) {
                $result[$keyHeader] = $value;
            }
        }

        foreach ($translateModel->headersTrans() as $headers) {
            if (empty($result[$headers->key_header])) {
                $result[$headers->key_header]['null'] = null;
            }
        }


        return $result;
    }

    public static function getArchiveTranslations($language) {

		require lang_path(strtolower($language) . DIRECTORY_SEPARATOR . 'app.php');
        return $lang;
    }

    public static function getSqlTranslation($language) {

        $sql = "SELECT WEB_TRANSLATE_HEADERS.KEY_HEADER,WEB_TRANSLATE_KEY.KEY_TRANSLATE,WEB_TRANSLATE.WEB_TRANSLATION "
                . "FROM WEB_TRANSLATE_HEADERS "
                . "JOIN WEB_TRANSLATE_KEY ON (WEB_TRANSLATE_HEADERS.ID_HEADERS = WEB_TRANSLATE_KEY.ID_HEADERS_TRANSLATE AND WEB_TRANSLATE_KEY.ID_EMP = :emp) "
                . "JOIN WEB_TRANSLATE ON (WEB_TRANSLATE_KEY.ID_KEY = WEB_TRANSLATE.ID_KEY_TRANSLATE AND WEB_TRANSLATE.ID_EMP = :emp) "
                . "WHERE WEB_TRANSLATE.LANG = :language order by key_header, key_translate";

        $params = array(
            'emp' => Config::get('app.main_emp'),
            'language' => strtoupper($language)
        );
        $data = CacheLib::useCache('translate', $sql, $params);
        $translate = array();

        foreach ($data as $key => $value) {
            if (empty($translate[$value->key_header])) {
                $translate[$value->key_header] = array();
            }
            $translate[$value->key_header][$value->key_translate] = $value->web_translation;
        }

        return $translate;
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
/*

        if (empty($palabraTraducida)) {
                $palabraTraducida = DB::table('WEB_SEO_ROUTES')->select('KEYLANG_SEO_ROUTES')
                        ->where([
                            ['KEY_SEO_ROUTES', '=', $keySeo->key_seo_routes],
                            ['LANG_SEO_ROUTES', '=', $idiomaActual],
                            ['ID_EMP', '=', Config::get('app.emp')],
                        ])->first();
        }

*/

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
