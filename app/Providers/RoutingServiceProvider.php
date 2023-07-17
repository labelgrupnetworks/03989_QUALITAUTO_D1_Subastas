<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Config;
use Log;
use Illuminate\Support\Facades\URL;
use Session;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    public function register()
    {


    }

   public static function seo_rutes() {
       // $seo_routes = DB::select("SELECT * FROM WEB_SEO_ROUTES WHERE ID_EMP = '".Config::get('app.main_emp')."'");
        $sql="SELECT * FROM WEB_SEO_ROUTES WHERE ID_EMP = '".Config::get('app.main_emp')."'";
        $seo_routes = \CacheLib::useCache('WEB_SEO_ROUTES',$sql, array(),100);
        $arr_seo_routes = array();
        $arr_seo_translate = array();
        foreach ($seo_routes as $route) {
            $arr_seo_routes[$route->keylang_seo_routes] = $route;
            if(!isset($arr_seo_translate[$route->key_seo_routes])){
                $arr_seo_translate[$route->key_seo_routes] = array();
            }
            $arr_seo_translate[$route->key_seo_routes][$route->lang_seo_routes] = $route;
        }
        Config::set('routes_SEO', $arr_seo_routes);
        Config::set('translate_SEO', $arr_seo_translate);
    }



    # Devuelve el Slug de rutas.
    public static function slug($name, $remove_lang = false)
    {


        # Fallback locale
        $browser_lang = \App::getLocale();


        $locales = Config::get('app.locales');

        # Comprobamos si en la URL existe el idioma /es/xxx
        if(!empty(\Request::segment(1)))
        {

            $lang = \Request::segment(1);
            //si la variable lang coincide con alguno de los idiomas, lo asignamos como idioma
            if(array_key_exists($lang,$locales))
            {

                 /*if(!empty(Config::get('app.force_language_redirect'))){

                    # Seteamos el idioma en el applocale
                    \App::setLocale(Config::get('app.force_language_redirect'));
                 }else{
                    \App::setLocale($lang);
                 }*/

               \App::setLocale($lang);
                  $browser_lang = $lang;
            }

        }



        /*2017-10-25 no parece que nun usuario tenga un idioma asoiado
        # Si existe la sesion de idioma la utilizamos
        if(Session::has('user.lang'))
        {
            \App::setLocale(Session::get('user.lang'));
        }
        */
        if ($name === '/')
        {
            return URL::to('/'.$browser_lang);
        }

        if(!$remove_lang)
        {
            return '/'.$browser_lang.'/'.$name;
        }
        else
        {
            return '/'.$name;
        }


    }

    //si lang se usa como atributo hay que ponerlo entre llaves
	#sirve solo para lectura, para generar la url es translateSeo
    public static function slugSeo($key,$lang_is_attr = false){
        $language = \Request::segment(1);
        $segment = \Request::segment(2);
        $domain =  !empty($_SERVER['SERVER_NAME'])? $_SERVER['SERVER_NAME'] : '';
        if(empty(Config::get('routes_SEO'))){
            \Routing::seo_rutes();
        }

        if(!empty(Config::get('routes_SEO')[$segment])){
			 $seo_route = Config::get('routes_SEO')[$segment];

             $correct_domain = empty($seo_route->domain_seo_routes) || $seo_route->domain_seo_routes == $domain;
             $correct_language = empty($seo_route->lang_seo_routes) || $seo_route->lang_seo_routes == $language ;
             //comprobamos que el dominio y el lenguage sea correcto, si lso campos estan vacios es que se permite en cualquier lenguaje o cualquier dominio

             if($correct_domain &&  $correct_language && $key == $seo_route->key_seo_routes){

                 if($lang_is_attr){
                    return "{lang}/$segment";
                 }else{
                    return \Routing::slug("$segment");
                 }

             }
        }

        $browser_lang = \App::getLocale();
        if($lang_is_attr){
            return "{lang}/$key";
        }else{
            return "$browser_lang/$key";
        }

    }
    public static function translateSeo($key, $slash = "/", $domain = null){

        $lang = \App::getLocale();

        if(empty(Config::get('translate_SEO'))){
            \Routing::seo_rutes();
        }

        $array_seo_translate = Config::get('translate_SEO');
		//buscamos la key y el idioma para devolver la traducción
        if(isset($array_seo_translate[$key]) && isset($array_seo_translate[$key][$lang]) ){
			$domain = $array_seo_translate[$key][$lang]->domain_seo_routes ?? $domain;
            return "$domain/$lang/".$array_seo_translate[$key][$lang]->keylang_seo_routes.$slash;
        }
        else{
            return "$domain/$lang/$key".$slash;
        }
    }
    //esta funcion mira si la url que hay es simplemente el idioma o es la raiz
    public static function is_home(){

        $locales = Config::get('app.locales');

       //si solo hay un segmento
        if(!empty(\Request::segment(1)) && empty(\Request::segment(2)))
        {
            $lang = \Request::segment(1);

            //si la variable lang coincide con alguno de los idiomas, lo asignamos como idioma
            if(array_key_exists($lang,$locales))
            {
               \App::setLocale($lang);
               return $lang;
            }
        }


       //si, no devolvemos el idioma por defecto
        return \App::getLocale();

    }

    public static function redirect_lot(){

         $lang = "";
        if(!empty(\Request::segment(1))){
            $lang = \Request::segment(1);
            $locales = Config::get('app.locales');
             if(array_key_exists($lang,$locales))
            {

               \App::setLocale($lang);

            }
		}

        $redirect = \Routing::find_redirect_lot();

        //redireccionamos a la
        if(count($redirect) > 0){
			#caso de url que está directamente en la raiz, sin el idioma
			if( \Config::get("app.redirectHtmlRaiz") && empty($url_tmp) && !empty(\Request::segment(1)) && empty(\Request::segment(2)) && strpos(\Request::segment(1),".html") !== false){
				return $redirect[0]->url_web_redirect_lots;
			}

              return "/".$lang."/".$redirect[0]->url_web_redirect_lots;
        }

         return "nomach";

    }
    /*
    public static function get_cod_redirect_lot(){
        return \Routing::find_redirect_lot();

    }
    */
    public static function find_redirect_lot(){
        $i=2;
        $fin = false;
        $url_tmp = "";

        $barra="";
        while($i <=6 && !$fin){
            if(!empty(\Request::segment($i))){
            $url_tmp.= $barra.\Request::segment($i);
             $barra="/";
            }else{
                $fin = true;
            }
            $i++;
		}

		#Codigo para redirecciones de duran que solo tienen un segemento
		if( \Config::get("app.redirectHtmlRaiz") && empty($url_tmp) && !empty(\Request::segment(1)) && empty(\Request::segment(2)) && strpos(\Request::segment(1),".html") !== false){
			$url_tmp = \Request::segment(1);
		}

        if(!empty($url_tmp)){

           $select = 'SELECT * FROM WEB_REDIRECT_LOTS WHERE URL_WEB_REDIRECT_LOTS = :url and EMP_WEB_REDIRECT_LOTS = :emp';
             $bindings = array('url' => $url_tmp,
                'emp' => Config::get('app.main_emp') );
             return DB::select($select,$bindings);
        }else{

            return array();
        }

    }

     public static function redirect_page(){
         $lang = "";
        if(!empty(\Request::segment(1))){
            $lang = \Request::segment(1);
            $locales = Config::get('app.locales');
             if(array_key_exists($lang,$locales))
            {
               \App::setLocale($lang);
            }
        }

        $redirect = \Routing::find_redirect_page();

        //si pasan una URL con una sola palabra y esta esta en redirect_page
        if(count($redirect) > 0 && (\Request::segment(1) == $redirect[0]->url_web_redirect_pages || $_SERVER['REQUEST_URI'] == '/'.$redirect[0]->url_web_redirect_pages)){
              return "/".$redirect[0]->url_web_redirect_pages;
        }elseif (count($redirect) > 0){
            return "/".$lang."/".$redirect[0]->url_web_redirect_pages;
        }

         return "nomach";

    }


     public static function find_redirect_page(){
        $i=2;
        $fin = false;
        $url_tmp = "";

        $barra="";
        while($i <=6 && !$fin){
            if(!empty(\Request::segment($i))){
            $url_tmp.= $barra.\Request::segment($i);
             $barra="/";
            }else{
                $fin = true;
            }
            $i++;
        }

        //si la url es de una sola palabra y no es un idioma
        if(empty($url_tmp)){
            if(!empty(\Request::segment(1))){
                $locales = Config::get('app.locales');
                if(!array_key_exists(\Request::segment(1),$locales))
                {
                   $url_tmp = \Request::segment(1);
                }
            }
        }

        if(!empty($url_tmp)){

            $select = 'SELECT * FROM WEB_REDIRECT_PAGES WHERE URL_WEB_REDIRECT_PAGES = :url and EMP_WEB_REDIRECT_PAGES = :emp';
            $bindings = array(
                'url' => $url_tmp,
                'emp' => Config::get('app.main_emp')
            );

            $val = DB::select($select,$bindings);

            if(empty($val)){
                $url_tmp = \Request::segment(1).'/'.$url_tmp;

                $select = 'SELECT * FROM WEB_REDIRECT_PAGES WHERE URL_WEB_REDIRECT_PAGES = :url and EMP_WEB_REDIRECT_PAGES = :emp';
                $bindings = array(
                    'url' => $url_tmp,
                    'emp' => Config::get('app.main_emp')
                );
                $val = DB::select($select,$bindings);

            }

            return $val;



        }else{
            return array();
        }

    }

	public static function currentUrl($url)
	{
		return URL::full() == $url;
	}

	/**
	 * @param array $urls
	 */
	public static function currentUrlInArray($urls)
	{
		foreach($urls as $url)
		{
			if(self::currentUrl($url)){
				return true;
			}
		}
		return false;
	}

}
