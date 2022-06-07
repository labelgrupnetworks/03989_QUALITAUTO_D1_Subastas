<?php

namespace App\Http\Controllers;

use Request;
use Requests;
use Session;
use Routing;
use Route;
use Input;
use Config;
use Mail;
use View;
use Redirect;

use App\Models\Subasta;
use App\Models\Bloques;
use Paginator;
use App\Models\Favorites;
use stdClass;

class BusquedaController extends Controller
{

        public function index(){

           \Tools::linguisticSearch();

            if (Config::get('app.group_auction_in_search')){
               return $this->auction_search();
            }else{
               return  $this->lots_search();
            }
        }
	public function lots_search()
	{
            //las palabras deben tener mas de un caracter, si no n oson validas, debe haber almenos una palabra valida para ralizar la busqueda
            $valid_words = false;
            $bloque = new Bloques();
            $sub = new Subasta();
            # Cargamos modelo de subasta para el getLote
            $page 		= 'all';
            $itemsPerPage  			= 48;
            $totalItems = 0;

            if(!empty(Request::input('history'))){
                    $history = Request::input('history');
            }else{
                $history = 'S';
            }

            $texto = Route::current()->parameter('texto');
            //tiene preferencia el escrito en la caja de texto
            if(!empty(Request::input('texto'))){
                $texto = Request::input('texto');
            }

            $texto = \Tools::replaceDangerqueryCharacter($texto);

            if(!empty($texto) && strlen($texto)> 1){

               if (Config::get('app.search_multiple_words')){
                    $words = explode(" ",$texto);
                    $search ="";
                    $pipe = "";
                    foreach ($words as $word){
                        if(!empty($word) && strlen($word) > 1){
                            $valid_words = true;

                            //hay una nueva configuracion que podemso indicarle por que campos buscar
                            if(\Config::get( 'app.search_fields' )){
                                $fields = explode(",", \Config::get( 'app.search_fields' ));
                                $search .=$pipe;
                                $or ="";
                                foreach ($fields as $field){
                                     $search .="$or REGEXP_LIKE (NVL(". $field ."_LANG, $field), '$word')  ";
                                     $or = "OR";
                                }

                                if(\Config::get( 'app.search_fields_no_lang' )){
                                    $fields_no_lang = explode(",", \Config::get( 'app.search_fields_no_lang' ));

                                    foreach ($fields_no_lang as $field_no_lang){
                                         $search .="$or REGEXP_LIKE  ($field_no_lang, '$word')  ";
                                         $or = "OR";
                                    }

                                }

                            }
                            elseif(\Config::get( 'app.desc_hces1' )){
                                $search .=$pipe." REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$word') OR REGEXP_LIKE (NVL(DESC_HCES1_LANG, DESC_HCES1), '$word')";
                            }else{
                                $search .=$pipe." REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$word') OR REGEXP_LIKE (NVL(DESCWEB_HCES1_LANG, DESCWEB_HCES1), '$word')";
                            }
                            $pipe = ") AND (";
                        }
                    }

                }else{
                    if (strlen($texto) > 1){
                        $valid_words = true;
                         if(\Config::get( 'app.desc_hces1' )){
                                $search =" REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$texto') OR REGEXP_LIKE (NVL(DESC_HCES1_LANG, DESC_HCES1), '$texto')";
                        }else{
                            $search =" REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$texto') OR REGEXP_LIKE (NVL(DESCWEB_HCES1_LANG, DESCWEB_HCES1), '$texto')";
                        }
                    }
                }
                if($valid_words){
                    $orden = $this->orden(Request::input('order'),$history);


                    $replace = array(
                      'text' =>  $search,
                      'lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,
                      'emp' => Config::get('app.emp'),
                      'gemp' => Config::get('app.gemp'),
                      'hist' => $history,
                      'orden' => $orden
                    );

                    //Utilizamos bloc para buscar los lotes
                    $resultado = $bloque->getResultBlockByKeyname('count_search',$replace);


                    if(!empty($resultado) && !empty($resultado[0]->num_lots)){
                        $totalItems = $resultado[0]->num_lots;
                    }
                }else{
                    $texto = "";
                }
            }
   		# Siempre que haya resultados de la busqueda activa
            if($totalItems > 0) {
                    # Pagina de paginador
                    if(empty(Route::current()->parameter('page')) or Route::current()->parameter('page') == 1) {
                        $currentPage    = 1;
                    } else {
                        $currentPage    = Route::current()->parameter('page');
                    }

                    # Volvemos a buscar los resultados pero esta vez delimitando los items segun paginas e itemsperpagina
                    $page  	= Route::current()->parameter('page');

                    $replace['paginacion'] = $sub->getOffset($page, $itemsPerPage);

                    $resultado = $bloque->getResultBlockByKeyname('search',$replace);

                    //dejamos los parametros de busqueda a normal, por que estaban afectando a todas las queries de la página
                    \Tools::normalSearch();
                    # Paginador
                    $urlPattern     = Routing::slug('busqueda').'/'.$texto.'/(:num)';
                    $paginator      = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

                    # Info de lotes extendida
                    $subastaObj = new Subasta();
                    $resultado = $subastaObj->getAllLotesInfo($resultado, true, false);


            } else {
                $resultado = array();
                $paginator = null;
            }
            $lots_favs = array();
            if (Session::has('user'))
            {
                $fav = new Favorites(null,null);
                $cod_cli       = Session::get('user.cod');
                $lots_favs = $fav->getFavsSub(null,$cod_cli);
            }
            $data = array(
                                'subastas' => $resultado,
                                'name'     => trans(\Config::get('app.theme').'-app.subastas.search-name'),
                                'subastas.paginator' => $paginator,
                                'search' => $texto,
                                'history' => $history,
                                'favs' => $lots_favs,
						);

			if(Config::get('app.seo_in_search', 0)){
				$data['seo'] = new \stdClass();
				$data['seo']->meta_title = trans(\Config::get('app.theme').'-app.metas.title_search');
				$data['seo']->meta_description = trans(\Config::get('app.theme').'-app.metas.description_search');
			}

            return View::make('front::pages.busqueda', array('data' => $data));
	}


        public function auction_search()
	{
            //las palabras deben tener mas de un caracter, si no n oson validas, debe haber almenos una palabra valida para ralizar la busqueda
            $valid_words = false;
            $bloque = new Bloques();
            $sub = new Subasta();
            # Cargamos modelo de subasta para el getLote

            $texto = Route::current()->parameter('texto');

            if(!empty(Request::input('texto'))){
                $texto = Request::input('texto');
            }
            if(!empty(Request::input('history'))){
                $history = Request::input('history');
            }else{
                $history = 'S';
            }
            $resultado = array();
           $texto =     \Tools::replaceDangerqueryCharacter($texto);
            if(!empty($texto)){
                if (Config::get('app.search_multiple_words')){
                  $words = explode(" ",$texto);
                    $search ="";
                    $pipe = "";
                    foreach ($words as $word){
                        if(!empty($word) && strlen($word) > 1){
                            $valid_words = true;
                            if(\Config::get( 'app.desc_hces1' )){
                                $search .=$pipe." REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$word') OR REGEXP_LIKE (NVL(DESC_HCES1_LANG, DESC_HCES1), '$word')";
                            }else{
                                $search .=$pipe." REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$word') OR REGEXP_LIKE (NVL(DESCWEB_HCES1_LANG, DESCWEB_HCES1), '$word')";
                            }
                            $pipe = ") AND (";
                        }
                    }

                }else{
                    if (strlen($texto) > 1){
                        $valid_words = true;
                         if(\Config::get( 'app.desc_hces1' )){
                                $search =" REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$texto') OR REGEXP_LIKE (NVL(DESC_HCES1_LANG, DESC_HCES1), '$texto')";
                        }else{
                            $search =" REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$texto') OR REGEXP_LIKE (NVL(DESCWEB_HCES1_LANG, DESCWEB_HCES1), '$texto')";
                        }
                    }
                }
                if($valid_words){

                    $replace = array(
                      'text' =>  $search,
                      'lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,
                      'emp' => Config::get('app.emp'),
                      'hist' => $history,
                    );

                    $resultado = $bloque->getResultBlockByKeyname('search_auction',$replace);
                     //dejamos los parametros de busqueda a normal, por que estaban afectando a todas las queries de la página
                    \Tools::normalSearch();
                    if(empty($resultado))  {
                        $resultado= array();
                    }
                }else{
                    //si no hay palabras a buscar vaciamso el texto
                    $texto="";
                }
            }
                    $data = array(
                                    'subastas' => $resultado,
                                    'name'     => trans(\Config::get('app.theme').'-app.subastas.search-name'),

                                    'search' => $texto,
                                    'history' => $history,
                            );

            return View::make('front::pages.busqueda', array('data' => $data));
            }

            /* lo dej ocomentado por que ya no se usará 08/02/2018
            # Limpiamos la busqueda y redirigimos a los resultados
            public function redirect()
            {
                    $texto 		= Input::get('texto');

                    return Redirect::to(Routing::slug('busqueda').'/'.$texto);
            }
            */
            public function orden($get_order, $history){
                //pordefecto ponemos mas proximos
                if(empty($get_order)){
                    $get_order = 'ffin';
                }


                switch ($get_order) {

                    case 'name':
                        $order = 'titulo_hces1, ref_asigl0 ASC';
                        break;

                    case 'price_asc':

                        if($history = 'H'){
                            $order = 'implic_hces1 ASC';
                        }else{
                            if(\Config::get('app.order_by_filter') == 'estimacion'){
                                $order = 'imptash_asigl0 ASC, imptas_asigl0 ASC, ref_asigl0 ASC ';
                            }elseif(\Config::get('app.order_by_filter') == 'precio_salida'){
                                $order = 'impsalhces_asigl0 ASC, ref_asigl0 ASC ';
                            }
                        }
                        break;

                    case 'price_desc':
                        if($history = 'H'){
                            $order = 'implic_hces1 DESC';
                        }else{
                            if(\Config::get('app.order_by_filter') == 'estimacion'){
                                $order = 'imptash_asigl0 DESC, imptas_asigl0 DESC, ref_asigl0 ASC ';
                            }elseif(\Config::get('app.order_by_filter') == 'precio_salida'){
                                $order = 'impsalhces_asigl0 DESC, ref_asigl0 ASC ';
                            }
                        }
                        break;

                    case 'ref':
                        $order = 'ref_asigl0';
                        break;
                    //mas proximo
                    case 'ffin':
                        if($history = 'H'){
                            $order = ' auc."start" DESC';
                        }else{
                            $order = 'ffin_asigl0 ASC,hfin_asigl0 ASC, ref_asigl0 ASC';
                        }
                        break;
                    //mas Sam ha pedido que le más reciente es el que le uqede más para subastarse, no el que se ha dado de alta mas tarde
                    case 'fecalta':
                        $order = 'ffin_asigl0 DESC,hfin_asigl0 DESC, ref_asigl0 DESC';  // 'fecalta_asigl0 DESC,horaalta_asigl0 DESC, ref_asigl0 ASC';
                        break;
                    //mayor numero de pujas
                    case 'mbids':
                        $order = ' nvl( (select max(lin_asigl1) from fgasigl1 asig11 where asig11.emp_asigl1 = p.emp_asigl0 and asig11.sub_asigl1 = p.sub_asigl0 and  asig11.ref_asigl1 = p.ref_asigl0) , 0) desc, ref_asigl0 ASC';
                        break;
                    //puja más alta
                    case 'hbids':
                        $order = 'implic_hces1 DESC';
                        break;
                }

                return $order;
        }
}
