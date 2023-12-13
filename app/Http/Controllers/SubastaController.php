<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Request;
//use Controller;
//use View;
use Illuminate\Support\Facades\Session;
use Routing;
use Route;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\Subasta;
use App\Models\Enterprise;
use App\Models\Filters;
use App\Models\User;
use App\Models\Favorites;
use App\Models\SeoFamiliasSessiones;

use App\Models\AucIndex;
use App\libs\StrLib;
use App\libs\ImageGenerate;
use App\libs\Currency;

use App\libs\EmailLib;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Sec;
use App\Models\Category;
use App\Models\Bloques;
use App\Http\Controllers\PaymentsController;
use App\libs\FormLib;
use App\libs\TradLib;
use App\Models\Payments;
use App\Http\Controllers\V5\LotListController;
use App\Models\V5\AucSessionsFiles;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgDeposito;
use App\Models\V5\FgHces1Files;
use App\Models\V5\FgLicit;
use App\Models\V5\FgSubConditions;
use App\Models\V5\FxCli;
use App\Models\V5\WebCalendar;
use App\Models\V5\WebCalendarEvent;
use Illuminate\Support\Str;
use SplFileInfo;

class SubastaController extends Controller
{



	public function index($cod)
	{

		//dd($this->lotList(Route::current()->parameter('cod')));
		return  $this->lotList(Route::current()->parameter('cod'));
	}


	public function subasta_actual_online()
	{
		return $this->subasta_actual("O");
	}



	public function subasta_actual($type="W")
	{
		$subastaObj        = new Subasta();
		$auction_list = $subastaObj->auctionList("S", $type);
		$lotListController = new LotListController();

		//mostramos solo las subastas ocultas a los administradores
		if (Session::has('user') && Session::get('user.admin') ) {
			$auction_list_oculto = $subastaObj->auctionList('A',$type);
			$auction_list = array_merge( $auction_list,$auction_list_oculto);
		}

		if(count($auction_list ) > 0){
			$auction = $auction_list[0];
		}else{
			$auction_list = $subastaObj->auctionList("H", $type);
			if(count($auction_list)==0){
				if($type=="O"){
					return redirect(\Routing::translateSeo('subastas-online'));
				}else{
					return redirect(\Routing::translateSeo('presenciales'));
				}
			}
			$auction = $auction_list[0];
		}

		return $lotListController->getLotsList( Str::slug($auction->name), $auction->cod_sub,  $auction->reference );
	}

	public function subastas_especiales()
	{
		return $this->listaSubastasSesiones('S', 'E');
	}

	public function subastas_presenciales()
	{
		return      $this->listaSubastasSesiones('S', 'W');
	}

	public function subastas_historicas()
	{
		return      $this->listaSubastasSesiones('H');
	}

	public function subastas_historicas_presenciales()
	{
		return      $this->listaSubastasSesiones('H', 'W');
	}

	public function subastas_historicas_online()
	{
		return      $this->listaSubastasSesiones('H', 'O');
	}

	public function subastas_online()
	{
		return      $this->listaSubastasSesiones('S', 'O');
	}
	public function subastas_permanentes()
	{
		return      $this->listaSubastasSesiones('S', 'P');
	}

	public function venta_directa()
	{
		return      $this->listaSubastasSesiones('S', 'V');
	}

	public function haz_oferta()
	{
		return      $this->listaSubastasSesiones('S', 'M');
	}

	public function subasta_inversa()
	{
		return      $this->listaSubastasSesiones('S', 'I');
	}

	public function subastas_activas($return_value = false)
	{
		$sub_active = array();
		$subastas_activas = Config::get('app.subastas_activas');
		$subastas_activas = explode(",", $subastas_activas);

		foreach ($subastas_activas as $sub) {
			$sub_active[] = $this->listaSubastasSesiones('S', $sub, false);
		}

		$subastas = array();
		foreach ($sub_active as $sub) {
			if (empty($subastas)) {
				$subastas = $sub;
			} else {
				foreach ($sub['auction_list'] as $auc) {
					$subastas['auction_list'][] = $auc;
				}
			}
		}


		if ($return_value) {
			return $subastas;
		} else {
			return \View::make('front::pages.subastas', array('data' => $subastas));
		}
	}

	public function customizeLotListCategory($key)
	{
		//reemplazar ñ y acentos para que no fallen las url indexadas en google que hemos substituido

		$search  = array('ñ', 'á');
		$replace = array('n', 'a');

		$key = str_replace($search, $replace, $key);
		//cogemos la ruta
		$route_customize = \Request::segment(2);

		//$cod_sub = Request::input('c');

		return  $this->lotList(null, $key,  $route_customize, 'category');
	}

	public function customizeLotListTheme($key)
	{
		//cogemos la ruta
		$route_customize = \Request::segment(2);

		return  $this->lotList(NULL, $key,  $route_customize, 'theme');
	}

	public function themeAuctionList()
	{
		$lang = Config::get('app.locale');
		$key = 'subjects_thematic_' . strtoupper($lang);
		$menu_obj = new \App\Models\AucIndex;
		$Menu = $menu_obj->getMenuWeb($key);

		$data = array();
		$data['theme_auctions'] = array();
		if (!empty($Menu)) {
			$data['theme_auctions'] = $menu_obj->getMenuWebHijo($Menu->id_web_auc_index_lang);
		}
		$SEO_metas = new \stdClass();
		$SEO_metas->meta_title = trans(\Config::get('app.theme') . '-app.metas.theme_auction_meta_title');
		$SEO_metas->meta_description = trans(\Config::get('app.theme') . '-app.metas.theme_auction_meta_description');
		$data['seo'] = $SEO_metas;

		return \View::make('front::pages.subastas_tematicas', array('data' => $data));
	}

	public function indice_subasta($cod_sub = NULL)
	{

		$subasta        = new Subasta();
		$subasta->cod   = $cod_sub;
		$subasta->texto     = Route::current()->parameter('texto');
		preg_match('#.*-(\d+)$#', $subasta->texto, $matches);
		if (!empty($matches[1]) || (isset($matches[1]) && $matches[1] == 0)) {
			$subasta->id_auc_sessions = $matches[1];
		}
		$data['id_auc_sessions'] = $subasta->id_auc_sessions;
		$data['cod_sub'] = $cod_sub;
		$data['url'] = \Routing::translateSeo('subasta') . $subasta->cod . '-' . $subasta->texto;

		$data['subasta'] = $subasta->getInfSubasta();
		if (empty($data['subasta'])) {
			exit(\View::make('front::errors.404'));
		}
		return \View::make('front::pages.indice_subasta', array('data' => $data));
	}



	# lista de lotes que cargaremos en la página subasta
	public function lotList($cod_sub = NULL, $key_customize = NULL,  $route_customize = NULL, $type = NULL)
	{

		if (!empty($_GET) && !empty($_GET['querylog'])) {
			\DB::enableQueryLog();
		}
		$lots_favs = array();
		$subasta        = new Subasta();
		$subasta->cod   = $cod_sub;
		$subasta->tipo          = "'W', 'V', 'O'";
		$subasta->texto         = Route::current()->parameter('texto');
		$subasta->page          = 'all';
		$referencia             = Route::current()->parameter('ref');
		$dataAuxSubasta = array();

		if (isset($_GET['s']) && is_numeric($_GET['s'])) {

			$a = DB::table('"auc_sessions"')->where('"id_auc_sessions"', $_GET['s'])->first();
			$dataAuxSubasta = DB::table("FGSUB")->where("COD_SUB", $a->auction)->first();
		}

		$js_item['lang_code'] = strtoupper(\App::getLocale());

		# Retornamos la información del usuario
		if (Session::has('user')) {
			# Cogemos la informacion del usuario ya que necesitamos datos de otras tablas como RSOC_CLI
			$user                = new User();
			$user->cod_cli       = Session::get('user.cod');
			$usuario             = $this->userLoged;

			# Comprobamos si tiene un código de licitador asignado, de lo contrario le asignaremos uno.
			$subasta->cli_licit = Session::get('user.cod');
			$subasta->rsoc      = !empty($usuario->rsoc_cli) ? $usuario->rsoc_cli : $usuario->nom_cli;

			# Check dummy bidder y codigo licitador de subasta
			if (!empty($subasta->cod)) {

				$subasta->checkDummyLicitador();

				# Si tienen numero de ministerio asignado, creamos ministerio como licitador
				if(Config::get('app.ministeryLicit', false)){
					$subasta->checkOrInstertMinisteryLicitador(Config::get('app.ministeryLicit'), 'Ministerio');
				}

				$res = $subasta->checkLicitador();

				if ($res) {
					$js_item['user']['cod_licit'] = head($res)->cod_licit;
				} else {
					$js_item['user']['cod_licit'] =  0;
				}
				//$js_item['user']['cod_licit']       = head($res)->cod_licit;
			} elseif (isset($_GET['s'])) {

				$subasta_aux        = new Subasta();
				$subasta_aux->cli_licit = Session::get('user.cod');
				$subasta_aux->rsoc      = !empty($usuario->rsoc_cli) ? $usuario->rsoc_cli : $usuario->nom_cli;
				$subasta_aux->cod   = $dataAuxSubasta->cod_sub;
				$subasta_aux->tipo          = "'W', 'V', 'O'";
				//$subasta_aux->texto         = Route::current()->parameter('texto');
				$subasta_aux->page          = 'all';
				$referencia             = $_GET['s'];
				$res = $subasta_aux->checkLicitador();
				if ($res) {
					$js_item['user']['cod_licit'] = head($res)->cod_licit;
				} else {
					$js_item['user']['cod_licit'] =  0;
				}
			} else {
				$js_item['user']['cod_licit'] = 0;
			}


			$subasta->licit = Session::get('user.cod');


			$js_item['user']['is_gestor']       = $usuario->tipacceso_cliweb == 'S' ? TRUE : FALSE;
			$js_item['user']['adjudicaciones']  = array();
			$js_item['user']['favorites']       = array();

			$fav = new Favorites($subasta->cod, $js_item['user']['cod_licit']);
			/*$favs = $fav->getFavs();

            if (!empty($favs['data'])){
                $js_item['user']['favorites'] = $favs['data'];
            }*/
			$lots_favs = $fav->getFavsSub($subasta->cod, $user->cod_cli);
		}

		if (!empty(Request::input('total'))) {
			$itemsPerPage = Request::input('total');
		} else {
			$itemsPerPage   = head(Config::get('app.filter_total_shown_options'));
		}

		//solo debe funcionar el goto si no han clicado una página
		if (empty(Route::current()->parameter('page'))) {
			$goto = Request::input('goto');
		} else {
			$goto = NULL;
		}


		$subastaObj            = new Subasta();




		$subastaObj->tipo      = "'W','O','V'";
		$subastaObj->cod       = $cod_sub;

		$subastaObj->texto     = Route::current()->parameter('texto');
		preg_match('#.*-(\d+)$#', $subastaObj->texto, $matches);
		if (!empty($matches[1]) || (isset($matches[1]) && $matches[1] == 0)) {

			$subastaObj->id_auc_sessions = $matches[1];
		}

		$subastaObj->page      = 'all';
		$subastaObj->cat       = Route::current()->parameter('cat'); // Categorias de la subasta
		//indicamso el orden elegido
		$this->set_order($subastaObj);


		$subastaObj->itemsPerPage = $itemsPerPage;
		//$sub_data= new \stdClass();
		$sub_data = NULL;
		$cache_sql = false;
		/* datos de la session */

		if (!empty($cod_sub)) {
			$sub_data = $subastaObj->getInfSubasta();
			\Tools::exit404IfEmpty($sub_data);

			if (!empty($sub_data) && $sub_data->subc_sub == 'H') {
				$cache_sql = true;
			}
		} else {
			//$sub_data->tipo_sub = 'P';
		}
		$SEO_metas = new \stdClass();


		//crea los where customizado de categories y tematicas
		//genera lso datos del Seo_metas
		//hace el filtro de categorias si la subasta nno es de categorias
		$subcategory = NULL;
		$category = $this->setFilterCustomize($subastaObj, $type, $key_customize, $route_customize,  $SEO_metas, $subcategory);

		if ($type == "theme") {
			$theme = $key_customize;
		} else {
			$theme = NULL;
		}

		$this->set_filter($subastaObj);



		//se usaran solo para tauler y fau de momento y estan configurados mediante web_config
		$filters = NULL;

		//si hay un código de subasta usaremos la query que solo busca en esa subasta
		if (!empty($cod_sub)) {
			$subastaObj->where_filter .= " AND \"id_auc_sessions\" =  $subastaObj->id_auc_sessions ";

			if (!empty($sub_data)) {
				$SEO_metas->meta_title = $sub_data->des_sub;
				$SEO_metas->meta_description = $sub_data->description;
			}
			/*
            if ( Config::get('app.filter_period')){
                $get_filters =  $subastaObj->getSubasta_filters(TRUE);

                $filters = $this->create_filters($get_filters);
            }
             *
             */
		}

		$totalItems = $subastaObj->getLots("count", $cache_sql);

		if(request()->has('page')){
			$currentPage = request()->input('page');
		}
		elseif (empty(Route::current()->parameter('page')) or Route::current()->parameter('page') == 1) {
			$currentPage    = 1;
		} else {
			$currentPage    = Route::current()->parameter('page');
		}


		$arr_position = 0;
		$find = false;
		# Encuentra la posición del lote en el array.
		if (!empty($goto) && intval($goto > 0)) {
			//llamamos a la funcion get lots indicandole que necesitamos el campo ref_asigl0
			$subastaObj->select_filter = "ref_asigl0, GREATEST (implic_hces1, IMPSALHCES_ASIGL0) as max_puja";
			$all_items = $subastaObj->getLots("small", $cache_sql);
			foreach ($all_items as $key => $value) {
				if ($value->ref_asigl0 == $goto) {
					$arr_position = $key;
					$find = true;
				}
			}
		}

		$actual_link = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if ($arr_position >= 0 && $find) {
			$lot_page = ceil(($arr_position + 1) / $subastaObj->itemsPerPage);
			if ($lot_page != $currentPage) {
				$request_url = explode('?', $actual_link);

				$base_route = explode('/', $request_url[0]);
				if (is_numeric(end($base_route))) {
					$url_definitva = substr_replace($request_url[0], $lot_page, -1) . '?' . $request_url[1];
				} else {
					$url_definitva = $request_url[0] . '/' . $lot_page . '?' . $request_url[1];
				}

				header('Location: http://' . $url_definitva);
				exit;
			}
		}

		//Si hay código de subasta debemos generar la url de la subasta
		if (!empty($cod_sub)) {
			$url = \Routing::translateSeo('subasta') . $subastaObj->cod . '-' . $subastaObj->texto;
			$url_indice = \Routing::translateSeo('indice-subasta') . $subastaObj->cod . '-' . $subastaObj->texto;
		} else {
			$url = \Routing::translateSeo($route_customize) . $key_customize;
			if (!empty(Route::current()->parameter('subcategory'))) {
				$url .= "/" . Route::current()->parameter('subcategory');
			}
			$url_indice = null;
		}
		$SEO_metas->canonical = $_SERVER['HTTP_HOST'] . $url;
		$urlPattern     = $url . '/page-(:num)';

		//$paginator      = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
		//$subastaObj->page  = Route::current()->parameter('page');
		$subastaObj->page = intval($currentPage);

		# Bug de paginador, a menos que se muestre 1 registro por pagina
		//$paginator->numPages    = ($paginator->numPages -1);
		//Si hay código de subasta debemos llamar a la funcion que carga los datos con el cod de subasta

		$subasta = $subastaObj->getLots("normal", $cache_sql);

		$paginator = new LengthAwarePaginator(range(1, $totalItems), $totalItems, $itemsPerPage, $currentPage, ["path" => $url]);
		$paginator->appends(request()->except('page'));

		//dejamos los parametros de busqueda a normal, por que estaban afectando a todas las queries de la página
		\Tools::normalSearch();

		$get_ordenes = false;
		//si es subasta abierta tipo o cargamos las ordenes para sabersi el usuario actual es el ganador
		if (count($subasta) > 0 && ($subasta[0]->subc_sub == 'S' || $subasta[0]->subc_sub == 'A') && $subasta[0]->tipo_sub == 'W' && $subasta[0]->subabierta_sub == 'O') {
			$get_ordenes = true;
		}

		$subasta = $subastaObj->getAllLotesInfo($subasta, true, $get_ordenes, true);

		// KIKE - Guardamos el código de subasta en una variable aparte para cuando se está llamando a la lista de lotes por
		//        categoria y filtrando por sesión ?s=XXXX  si forzamos el cod_sub pasan cosas raras.

		$cod_sub_aux = 0;
		if (!empty($subastaObj->cod) && isset($_GET['s'])) {

			$cod_sub_aux = $subastaObj->getInfSubasta();
		}

		$first_item = reset($subasta);

		$data = array(
			'type' => $type,
			'theme' => $theme,
			'subcategory' => $subcategory,
			'category' => $category,
			'sub_data'  => $sub_data,
			'route_customize' => $route_customize,
			'filters' => $filters,
			'subastas' => $subasta,
			'name' => !empty($first_item->des_sub) ? ucfirst(mb_strtolower($first_item->des_sub, "UTF-8")) : trans(\Config::get('app.theme') . '-app.subastas.lots_not_found'),
			'title' => !empty($first_item->des_sub) ? ucfirst(mb_strtolower($first_item->name, "UTF-8")) : trans(\Config::get('app.theme') . '-app.subastas.lots_not_found'),
			'cod_sub' => $subastaObj->cod,
			'id_auc_sessions' => $subastaObj->id_auc_sessions,
			'seo' => $SEO_metas,
			'subastas.paginator' => $paginator,
			'url' => $url,
			'url_indice' => $url_indice,
			'goto' => $goto,
			'get_order' => $subastaObj->get_order,
			'favs' => $lots_favs,
			'cod_sub_aux' => $cod_sub_aux,
			'dataAuxSubasta' => $dataAuxSubasta,
			'totalItems' => $totalItems,
		);



		if (!empty(Request::input('description'))) {
			$data['filterDescription'] = Request::input('description');
		}
		if (!empty(Request::input('open'))) {
			$data['filterOpen'] = Request::input('open');
		}
		if (!empty(Request::input('award'))) {
			$data['filterAward'] = Request::input('award');
		}
		if (!empty(Request::input('no_award'))) {
			$data['filterNo_award'] = Request::input('no_award');
		}

		$data['node']  = array(
			'comprar'       => Config::get('app.url') . \Routing::slug('api') . "/comprar/subasta",
			'ol'       => Config::get('app.url') . \Routing::slug('api') . "/ol/subasta",
		);

		$data['js_item'] = $js_item;
		/*
       echo "<pre>";
        print_r($data);
      die();
      */


		return \View::make('front::pages.subasta', array('data' => $data));
	}


	//devuelve el where de limite de precios
	private function getWherePrices($ini_price, $subastaObj)
	{

		if (!empty($ini_price)) {
			if (!empty($ini_price['from']) && is_numeric($ini_price['from'])) {
				$subastaObj->where_filter  .= " AND GREATEST (implic_hces1, IMPSALHCES_ASIGL0)  >= " . $ini_price['from'];
			}
			if (!empty($ini_price['to']) && is_numeric($ini_price['to'])) {
				$subastaObj->where_filter  .= " AND GREATEST (implic_hces1, IMPSALHCES_ASIGL0) <= " . $ini_price['to'];
			}
		}
	}

	//devuelve el where de limite de precios
	private function getWhereEstimate($estimate, $subastaObj)
	{

		if (!empty($estimate)) {
			if (!empty($estimate['from']) && is_numeric($estimate['from'])) {
				$subastaObj->where_filter  .= " AND IMPTASINI_HCES1  >= " . $estimate['from'];
			}
			if (!empty($estimate['to']) && is_numeric($estimate['to'])) {
				$subastaObj->where_filter  .= " AND IMPTASINI_HCES1 <= " . $estimate['to'];
			}
		}
	}
	//devuelve el where de los lotes no adjudicados
	private function getWhereNoAward($noaward, $subastaObj)
	{
		if (!empty($noaward)) {
			//un lote no esta adjudicado si no tiene linea en CSUB
			$subastaObj->where_filter .= " AND CERRADO_ASIGL0 = 'S' AND CSUB.REF_CSUB IS NULL ";
		}
	}

	//devuelve los lotes no vendidos de las subastas tipo Venta
	private function getWhereDontSoldV($subastaObj)
	{
		if (!empty(Config::get("app.hide_sold_lots_V"))) {
			//un lote no esta adjudicado si no tiene linea en CSUB
			$subastaObj->where_filter .= " AND (SUB.TIPO_SUB != 'V' OR  (CERRADO_ASIGL0 = 'N' AND CSUB.REF_CSUB IS NULL) )";
		}
	}

	private function getWhereOpen($open, $subastaObj)
	{
		if (!empty($open)) {
			$subastaObj->where_filter .= " AND cerrado_asigl0 = 'N' ";
		}
	}

	//devuelve el where de los lotes disponibles para la venta (no retirados, no devueltos)
	private function getWhereOnlySalable($onlySalable, $subastaObj)
	{

		if (!empty($onlySalable)) { //si es V debe estar abierto, si no da igual
			$subastaObj->where_filter .= "AND (SUB.TIPO_SUB != 'V' OR cerrado_asigl0 = 'N') AND ASIGL0.RETIRADO_ASIGL0 = 'N' AND  HCES1.FAC_HCES1 = 'N'";
		}
	}

	private function getWhereAward($award, $subastaObj)
	{
		if (!empty($award)) {
			$subastaObj->where_filter .= " AND cerrado_asigl0 = 'S' and  CSUB.REF_CSUB IS NOT NULL ";
		}
	}

	private function getWhereAwardAndOpens($awardOpen, $subastaObj)
	{
		if (!empty($awardOpen)) {
			$subastaObj->where_filter .= "AND (cerrado_asigl0 = 'N' OR cerrado_asigl0 = 'S' and  CSUB.REF_CSUB IS NOT NULL) ";
		}
	}


	//Retorna el where con los lotes comprados
	private function getWhereMyLotsClient($myLotsClient, $subastaObj)
	{
		if (!empty($myLotsClient) && !empty(\Session::get('user.cod'))) {

			$subastaObj->join_filter .= ' JOIN FGLICIT ON ( FGLICIT.EMP_LICIT = ASIGL0.EMP_ASIGL0 AND FGLICIT.SUB_LICIT = AUC."auction" AND FGLICIT.CLI_LICIT  =' . \Session::get('user.cod') . ' )';
			//hago el join sobre una salect con group by para evitar duplicados
			$subastaObj->join_filter .= ' JOIN (select EMP_ASIGL1,SUB_ASIGL1,LICIT_ASIGL1,REF_ASIGL1 from FGASIGL1 group by EMP_ASIGL1,SUB_ASIGL1,LICIT_ASIGL1,REF_ASIGL1)  LOTS_CLIENT ON (LOTS_CLIENT.EMP_ASIGL1 = ASIGL0.EMP_ASIGL0 AND LOTS_CLIENT.SUB_ASIGL1 = ASIGL0.SUB_ASIGL0 AND LOTS_CLIENT.LICIT_ASIGL1 = FGLICIT.COD_LICIT AND LOTS_CLIENT.REF_ASIGL1 =  ASIGL0.REF_ASIGL0) ';
		}
	}

	//Retorna el where con los como cedente
	private function getWhereMyLotsProperty($myLotsProperty, $subastaObj)
	{

		if (!empty($myLotsProperty) && !empty(\Session::get('user.cod'))) {
			$subastaObj->where_filter .= " AND HCES1.PROP_HCES1 = '" . \Session::get('user.cod') . "'";
		}
	}



	private function getWhereDescription($description, $subastaObj)
	{
		//las palabras deben tener mas de un caracter, si no n oson validas, debe haber almenos una palabra valida para ralizar la busqueda
		$valid_words = false;
		$description = \Tools::replaceDangerqueryCharacter($description);
		if (!empty($description)) {
			\Tools::linguisticSearch();

			if (Config::get('app.search_multiple_words')) {

				$words = explode(" ", $description);
				$search = "(";
				$pipe = "";
				foreach ($words as $word) {
					if (!empty($word) && strlen($word) > 1) {
						$valid_words = true;
						if (\Config::get('app.desc_hces1')) {
							$search .= $pipe . " REGEXP_LIKE (NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.titulo_hces1), '$word') OR REGEXP_LIKE (NVL(HCES1_LANG.DESC_HCES1_LANG, HCES1.DESC_HCES1), '$word')";
						} else {
							$search .= $pipe . " REGEXP_LIKE (NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.titulo_hces1), '$word') OR REGEXP_LIKE (NVL(HCES1_LANG.DESCWEB_HCES1_LANG, HCES1.DESCWEB_HCES1), '$word')";
						}
						$pipe = ") AND (";
					}
				}
				$search .= ") ";
			} else {
				if (strlen($description) > 1) {
					if (\Config::get('app.desc_hces1')) {
						$search = " REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$description') OR REGEXP_LIKE (NVL(DESC_HCES1_LANG, DESC_HCES1), '$description')";
					} else {
						$search = " REGEXP_LIKE (NVL(TITULO_HCES1_LANG, titulo_hces1), '$description') OR REGEXP_LIKE (NVL(DESCWEB_HCES1_LANG, DESCWEB_HCES1), '$description')";
					}
					$valid_words = true;
				}
			}

			if ($valid_words) {
				$subastaObj->where_filter  .= " AND ( $search) ";
			}
			/*
            $subastaObj->where_filter  .= "AND ( CONTAINS (DESC_HCES1, '$search',1) > 0
                                                or
                                                CONTAINS (DESC_HCES1_LANG, '$search',2) > 0
                                                or
                                                CONTAINS (TITULO_HCES1, '$search',3) > 0
                                                or
                                                CONTAINS (TITULO_HCES1_LANG, '$search',4) > 0
                                            )
                                ";
             */
			//$subastaObj->where_filter  .= " AND ( REGEXP_LIKE (NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.titulo_hces1), '$search') OR REGEXP_LIKE (NVL(HCES1_LANG.DESC_HCES1_LANG, HCES1.DESC_HCES1), '$search') )";
		}
	}

	private function getWhereExactDescription($description, $subastaObj)
	{
		$description = str_replace("'", "\'", $description);

		if (!empty($description)) {
			$search = $description;
			$subastaObj->where_filter  .= " AND ( REGEXP_LIKE (NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.titulo_hces1), :search) OR REGEXP_LIKE (NVL(HCES1_LANG.DESC_HCES1_LANG, HCES1.DESC_HCES1), :search) )";
			$subastaObj->params_filter["search"] = $search;
		}
	}


	private function getWhereReference($referencia, $subastaObj)
	{
		if (!empty($referencia) && is_numeric($referencia)) {
			$subastaObj->where_filter  .= " AND (ASIGL0.REF_ASIGL0 = '$referencia')";
		}
	}

	private function getWhereLin_ortsec($lin_ortsec, $subastaObj)
	{
		if (!empty($lin_ortsec) && is_numeric($lin_ortsec)) {
			$subastaObj->where_filter  .= " AND (ORTSEC1.LIN_ORTSEC1 = :lin_ortsec)";
			$subastaObj->params_filter["lin_ortsec"] = $lin_ortsec;

			$subastaObj->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = HCES1.SEC_HCES1 AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = ASIGL0.SUB_ASIGL0) ";
		}
	}
	//buscar en categoria personalizada con la subasta 0
	private function getWhereCat_pers($catpers, $subastaObj)
	{
		if (!empty($catpers) && is_numeric($catpers)) {
			$subastaObj->where_filter  .= " AND (ORTSEC1.LIN_ORTSEC1 = :catpers)";
			$subastaObj->params_filter["catpers"] = $catpers;
			$subastaObj->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = HCES1.SEC_HCES1 AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = '0') ";
		}
	}


	private function getWhereCod_sec($cod_sec, $subastaObj)
	{
		if (!empty($cod_sec)) {
			$subastaObj->where_filter  .= " AND (HCES1.SEC_HCES1= :cod_sec)";
			$subastaObj->params_filter["cod_sec"] = $cod_sec;
		}
	}

	private function getWhereFirst_lot($first_lot, $subastaObj)
	{
		if (!empty($first_lot) && is_numeric($first_lot)) {
			$subastaObj->where_filter  .= " AND (ASIGL0.REF_ASIGL0 >= :first_lot)";
			$subastaObj->params_filter["first_lot"] = $first_lot;
		}
	}

	private function getWhereLast_lot($last_lot, $subastaObj)
	{
		if (!empty($last_lot) && is_numeric($last_lot)) {
			$subastaObj->where_filter  .= " AND (ASIGL0.REF_ASIGL0 <= :last_lot)";
			$subastaObj->params_filter["last_lot"] = $last_lot;
		}
	}
	//los filtros de la subasta, object_types_values
	private function getWhereAuctionFilter($subastaObj)
	{

		if (!empty($subastaObj->cod)) {
			$cod_sub = $subastaObj->cod;
		} else {
			$cod_sub = 0;
		}
		//si no se está buscando en lso filtros no agragamos los joins
		$search_in_filters = false;
		$filters_obj = new Filters();
		$auction_filters =  $filters_obj->getFiltersAuction($cod_sub);
		foreach ($auction_filters as $auction_filter) {
			//cogemos las variables de los selectores, asi mas adelante podemso usar las de checks usando la extension _check
			$filter_name = $auction_filter->col_subfw . "_select";
			$filter_value = Request::input($filter_name);


			if (!empty($filter_value)) {
				$search_in_filters = true;
				$subastaObj->where_filter .= ' AND TRIM(NVL(otv_lang."' . $auction_filter->col_subfw  . '_lang",  otv."' . $auction_filter->col_subfw  . '"))  = :'.$filter_name.' ';
				$subastaObj->params_filter[$filter_name] = $filter_value;
			}
		}

		if ($search_in_filters) {
			$subastaObj->join_filter .= 'JOIN "object_types_values" otv on ( otv."company" =  HCES1.emp_HCES1 and  otv."transfer_sheet_number" = HCES1.NUM_HCES1 AND  otv."transfer_sheet_line" = HCES1.lin_HCES1)';
			$subastaObj->join_filter .= 'LEFT JOIN "object_types_values_lang" otv_lang on ( otv_lang."company_lang" =  HCES1.emp_HCES1 and  otv_lang."transfer_sheet_number_lang" = HCES1.NUM_HCES1 AND  otv_lang."transfer_sheet_line_lang" = HCES1.lin_HCES1 AND otv_lang."lang_object_types_values_lang" = :lang)';
		}
	}

	private function getWhereDiscountsOffers($oferta, $subastaObj)
	{
		if (!empty($oferta) && is_numeric($oferta)) {
			$subastaObj->where_filter  .= " AND (ASIGL0.OFERTA_ASIGL0 = $oferta)";
		}
	}







	//2018_01_19 no se esta usando
	/*
    # Pujas de un lote
    public function getPujas($cod, $lote)
    {
        $subastaObj        = new Subasta();
        $subastaObj->cod   = $cod;
        $subastaObj->lote  = $lote;
        return  json_encode($subastaObj->getPujas());
    }

    # Ordenes de licitación
    public function getOrdenes($cod, $lote)
    {
        $subastaObj        = new Subasta();
        $subastaObj->cod   = $cod;
        $subastaObj->lote  = $lote;
        return  json_encode($subastaObj->getOrdenes());
    }

    public function listaLotes($id_subasta = false)
    {
        return \View::make('front::pages.list');
    }*/




	/**
	 * Cargar listado de subastas
	 * @version 12.05.20 Eloy,cambiado el type de nuevo a null para mostrar todas las subastas por defecto
	 */
	public function listaSubastasSesiones($status = 'S', $type = NULL, $return_view = true)
	{

		$subastaObj        = new Subasta();
		$auction_list = $subastaObj->auctionList($status, $type);

		//mostramos solo las subastas ocultas a los administradores
		if (Session::has('user') && Session::get('user.admin') && $status == 'S') {
			$auction_list_oculto = $subastaObj->auctionList('A', $type);
			$auction_list = array_merge($auction_list_oculto, $auction_list);
		}

		$SEO_metas = new \stdClass();

		if ($status == 'H') {
			$SEO_metas->meta_title =  trans(\Config::get('app.theme') . '-app.metas.title_historic');
			$SEO_metas->meta_description =  trans(\Config::get('app.theme') . '-app.metas.description_historic');
			$name = trans(\Config::get('app.theme') . '-app.subastas.historic_auctions');
		} elseif ($type == 'O') {
			$SEO_metas->meta_title =  trans(\Config::get('app.theme') . '-app.metas.title_online');
			$SEO_metas->meta_description =  trans(\Config::get('app.theme') . '-app.metas.description_online');
			$name = trans(\Config::get('app.theme') . '-app.foot.online_auction');
		} elseif ($type == 'V') {
			$SEO_metas->meta_title =  trans(\Config::get('app.theme') . '-app.metas.title_venta');
			$SEO_metas->meta_description =  trans(\Config::get('app.theme') . '-app.metas.description_venta');
			$name = trans(\Config::get('app.theme') . '-app.foot.direct_sale');
		}  elseif ($type == 'M') {
			$SEO_metas->meta_title =  trans(\Config::get('app.theme') . '-app.metas.title_haz_oferta');
			$SEO_metas->meta_description =  trans(\Config::get('app.theme') . '-app.metas.description_haz_oferta');
			$name = trans(\Config::get('app.theme') . '-app.foot.make_offer');
		}  elseif ($type == 'I') {
			$SEO_metas->meta_title =  trans(\Config::get('app.theme') . '-app.metas.title_inversa');
			$SEO_metas->meta_description =  trans(\Config::get('app.theme') . '-app.metas.description_inversa');
			$name = trans(\Config::get('app.theme') . '-app.foot.reverse_auction');
		}else {
			$SEO_metas->meta_title =  trans(\Config::get('app.theme') . '-app.metas.title_presenciales');
			$SEO_metas->meta_description =  trans(\Config::get('app.theme') . '-app.metas.description_presenciales');
			$name = trans(\Config::get('app.theme') . '-app.subastas.auctions');
		}

		//ponemos el canonical, de momento solo las subastas tematicas llevan variables, pero no afectara negativamente al resto
		$SEO_metas->canonical = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		//quitamso las variables
		$SEO_metas->canonical = substr($SEO_metas->canonical, 0, strpos($SEO_metas->canonical, '?'));

		// Mostrar grid lotes de VD con query only_salable cuando solamente exista una subasta
		if ($type == 'V' && count($auction_list) == 1) {
			$url = \Tools::url_auction($auction_list[0]->cod_sub, $auction_list[0]->name, $auction_list[0]->id_auc_sessions, $auction_list[0]->reference);
			return redirect($url);
		}
		elseif(config('app.goGridIfOnlyOneAuction', 0) && count($auction_list) == 1){
			$subasta = $auction_list[0];
			$url= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions,$subasta->reference);
			return redirect($url);
		}

		$calendars = Config::get('app.add_calendar_feature', false) ? $subastaObj->getCalendarsLinks($auction_list) : null;

		$data = array(
			'auction_list' => $auction_list,
			'calendars' => $calendars,
			'subc_sub' => $status,
			'type' => $type,
			'name' => $name,
			'seo' => $SEO_metas
		);

		if ($return_view == true) {
			return \View::make('front::pages.subastas', array('data' => $data));
		}

		return $data;
	}

	public function listaSubastasSesionesTR()
	{
		//mientras no se defina bien cargo lo mismo que las subastas normales
		return $this->listaSubastasSesiones('S');
	}

	//http://www.auctionlabel.dev/api-ajax/get_lote/0417/5/NEXT
	// Funcion que recibe una referencia y un código de subasta y devuleve el siguiente lote o el anterior ordenado por orden
	public function getNextPreviousLotAjax()
	{
		$lang = Route::current()->parameter('lang');
		$cod = Route::current()->parameter('cod');
		$id_auc_sessions  = Route::current()->parameter('id_auc_sessions');
		$ref  = Route::current()->parameter('ref');
		$order  = Route::current()->parameter('order');
		$search = Route::current()->parameter('search');
		$ref_search =  NULL;
		$subasta       = new Subasta();
		$subasta->cod   = $cod;
		$subasta->lote  = $ref;
		$session = $subasta->get_session($id_auc_sessions);
		// Si es current es que la referencia es la que nos pasan y no debemos buscar
		if ($search == "CURRENT") {
			if (is_numeric($ref) && $ref >= $session->init_lot && $ref < $session->end_lot) {
				$ref_search = $ref;
			}
		}
		//si no debemos buscar la anterior o la siguiente
		else {
			$subasta->session_reference = $session->reference;
			if(!is_numeric($order)){
				return json_encode(["status" => "error"]);
			}

			//Buscamos el lote segun el orden
			$ref_search = $subasta->getNextPreviousLot($search, $order, 'order');
		}

		if (!empty($ref_search)) {
			$subasta_search = new Subasta();
			$subasta_search->cod = $cod;
			$subasta_search->lote = $ref_search;
			$lote_search = $subasta_search->getLote(false, true, true);

			if (!empty($lote_search)) {
				$lote_search = head($lote_search);
				#si el importe de salida es 0 debemos cojer el primer escalado como puja válida
				if( $lote_search->impsalhces_asigl0== 0){
					$lote_search->impsalhces_asigl0 = head($subasta->AllScales())->scale;
				}
				$inf_lot_translate =  $subasta->getMultilanguageTextLot($lote_search->num_hces1, $lote_search->lin_hces1);
				$lote_search->text_lang = $inf_lot_translate;

				$strLib = new StrLib();
				$lote_search->titulo_hces1 = $inf_lot_translate[$lang]->titulo_hces1;
				$lote_search->desc_hces1 = $inf_lot_translate[$lang]->desc_hces1;
				$lote_search->descweb_hces1 = $inf_lot_translate[$lang]->descweb_hces1;
/*


				$lote_search->desc_hces1 = $strLib->CleanStr($inf_lot_translate[$lang]->desc_hces1);
				*/
				$lote_search->formatted_impsalhces_asigl0 = \Tools::moneyFormat($lote_search->impsalhces_asigl0);
				$lote_search->imagen            = $subasta_search->getLoteImg($lote_search);
				//si hay alguna puja max_puja >0 miramos si tiene un valor de adjudicación y lo ponemos como max puja
				$lote_search->himp_csub = 0;
				if ($lote_search->max_puja > 0) {
					$adj = $subasta_search->getAssignetPrice();
					if (!empty($adj)) {
						$lote_search->himp_csub = \Tools::moneyFormat($adj->himp_csub);;
					}
				}

				$imageGenerate = new ImageGenerate();
				$lote_search->imagen_base64 = $imageGenerate->resize_img("lote_small", $lote_search->imagen, Config::get('app.theme'), true);
				\App::setLocale(strtolower($lang));
				$webfriend = !empty($inf_lot_translate[strtoupper($lang)]->webfriend_hces1) ? $inf_lot_translate[strtoupper($lang)]->webfriend_hces1 :  str_slug($inf_lot_translate[strtoupper($lang)]->titulo_hces1);
				$url_friendly = \Routing::translateSeo('lote') . $lote_search->sub_asigl0 . "-" . str_slug($lote_search->id_auc_sessions) . '-' . $lote_search->id_auc_sessions . "/" . $lote_search->ref_asigl0 . '-' . $lote_search->num_hces1 . '-' . $webfriend;
				$lote_search->url_lot    = $url_friendly;

				$res = array(
					"status" => "success",
					"lote"     => $lote_search
				);

				return json_encode($res);
			}
		}
		//si no hay referencia o lote devolvemos error
		$res = array(
			"status" => "error"
		);
		return json_encode($res);
	}

	# Un lote en concreto mediante codigo de subasta y ref del lote
	# ej: /subasta-1A15/6-blabla
	public function lote()
	{

		abort_if(config('app.restrictAccessIfNoSession', 0) && !session('user.cod'), 401);
		# Información del Lote
		$subastaObj        = new Subasta();
		$subastaObj->cod   = Route::current()->parameter('cod');
		$subastaObj->lote  = Route::current()->parameter('ref');

		$where = FALSE;

		if (Request::ajax()) {
			$subastaObj->orden  = Route::current()->parameter('ref');

			if (Route::current()->parameter('search') == 'orden') {
				$where = "ORDEN_HCES1";
			}
		}
		#18-10-22 MODIFICADO PARA PODER USAR UNA URL ALTERNATIVA
/*
		$subastaObj->texto = Route::current()->parameter('texto2');
		preg_match('#.*-(\d+)$#', $subastaObj->texto, $matches);
		if (empty($matches[1]) || !is_numeric($matches[1])) {

			exit(\View::make('front::errors.404'));
		}

		$session_slug = $matches[1];

		$subastaObj->id_auc_sessions = $matches[1];
*/
		$lote = $subastaObj->getLote($where, true, true);

		if (empty($lote) || $lote[0]->subc_sub =='N') {

			if(config('app.redirect_home_nolot', false)){
				return redirect(route('home'), 302);
			}
			exit(\View::make('front::errors.404'));
		}
		$session_slug = $lote[0]->id_auc_sessions;
		$subastaObj->id_auc_sessions = $session_slug;
		#quito los parametros para comparar solo la url
		$urlsinparametros= explode('?', $_SERVER['REQUEST_URI']);
		$url_actual = $urlsinparametros[0];


		$titulo = $lote[0]->titulo_hces1?? $lote[0]->descweb_hces1;

		#18-10-22 MODIFICADO PARA QUE USE LA MISMA FUNCION
		/* $webfriend = !empty($lote[0]->webfriend_hces1) ? $lote[0]->webfriend_hces1 :  str_slug($titulo);
		$url_buena = \Routing::translateSeo('lote') . $lote[0]->cod_sub . "-" . $lote[0]->id_auc_sessions . '-' . $lote[0]->id_auc_sessions . "/" . $lote[0]->ref_asigl0 . '-' . $lote[0]->num_hces1 . '-' . $webfriend;
		*/
		$url_buena = \Tools::url_lot($lote[0]->cod_sub,$lote[0]->id_auc_sessions,$lote[0]->name,$lote[0]->ref_asigl0,$lote[0]->num_hces1,$lote[0]->webfriend_hces1,$titulo);
		#quitamos la parte del dominio, por si viniera informada
		$url_buena = str_replace( Config::get('app.url'),"",$url_buena);
		if ($url_buena != $url_actual) {
			//exit (\View::make('front::errors.404'));
			return \Redirect::to(\URL::asset($url_buena), 301);
		}




		$subastaObj->select_filter = 'asigl0.ref_asigl0,  hces1.num_hces1, SUB.cod_sub, "id_auc_sessions",  NVL(HCES1_LANG.WEBFRIEND_HCES1_LANG,  HCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1,titulo_hces1';
		$subastaObj->page = 'all';

		$auction_in_categories =  \Config::get('app.auction_in_categories');

		$theme_key = null;
		//se usará para los enlaces de siguiente y anterior
		$get_theme_key = "";
		if (!empty($_GET['theme'])) {
			$theme_key = $_GET['theme'];
			$get_theme_key = "?theme=$theme_key";
		}

		//comprueba si es una subasta tematica o  de tipo categoria, por ejemplo si la subasta es de tipo O es de categorias
		if (!empty($theme_key)) {
			$auc = new AucIndex();
			//es subasta tematica
			if (!empty($theme_key)) {
				//creamos url para el breadcumb
				$url_subasta = \Routing::translateSeo('tematicas') . $theme_key;
				$key_name = $theme_key;
			} else { // es de tipo categoria
				//buscamos el key a partir de la categoria del lote
				$category = $auc->getKeyBySec($lote[0]->sec_hces1, \Config::get('app.locale'));

				if (empty($category)) {
					$category =  new \stdClass();

					$category->key_name = Config::get('app.default_category_' . \Config::get('app.locale'));
				}


				//url para el breadcumb
				$url_subasta = \Routing::translateSeo('subastas') . $category->key_name;
				$key_name = $category->key_name;
			}
			//definimos los wheres segun la key
			$customize_values = $auc->getAucIndexByKeyname($key_name, \Config::get('app.locale'));

			if (empty($customize_values)) {
				exit(\View::make('front::errors.404'));
			}

			$this->set_customize_values_filter($customize_values, $subastaObj);

			$subastaObj->where_filter .= " AND SUB.TIPO_SUB  IN ($auction_in_categories) AND ASIGL0.CERRADO_ASIGL0 = 'N' ";


			$title_url_subasta = $customize_values->title;
		} //si es una subasta de tipo categorias
		/* 2019_09_05 - LO DEJO COMENTADO, ya que con la estructura que tiene tauler es dificil controlar el anterior siguiente por categoria, solo cuando vienen de categoria.
        elseif( !empty($auction_in_categories) &&    stripos($auction_in_categories,$lote[0]->tipo_sub) !== false ){
            $sec_obj = new Sec();
            $ortsec  = $sec_obj->getOrtsecByCodSec('0',$lote[0]->sec_hces1);

            //si nose ha encontrado la ordenacion de catalogo de esa categoria
            if(empty($ortsec)){
                $ortsec=  new \stdClass();
                $ortsec->key_ortsec0 = Config::get('app.default_category_'. \Config::get('app.locale'));
                $ortsec->des_ortsec0 =trans(\Config::get('app.theme').'-app.lot_list.all_categories');
                //Lin_ortsec de todas las categories.
                $ortsec->lin_ortsec0 = 10;
            }
            $title_url_subasta =$ortsec->des_ortsec0;
            $url_subasta= \Routing::translateSeo('subastas').$ortsec->key_ortsec0;
            $key_name = $ortsec->key_ortsec0;
            $subastaObj->where_filter  .= " AND (ORTSEC1.LIN_ORTSEC1 = '$ortsec->lin_ortsec0')";
            $subastaObj->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = HCES1.SEC_HCES1 AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = '0') ";


            $subastaObj->where_filter .=" AND SUB.TIPO_SUB  IN ($auction_in_categories) AND ASIGL0.CERRADO_ASIGL0 = 'N' ";
            $subastaObj->order_by_values = 'ffin_asigl0 ASC,hfin_asigl0 ASC, ref_asigl0 ASC';
        }
         *
         */ else {
			//$subastaObj->tipo = "'W','O','V','P'";

			$subastaObj->where_filter = "AND \"id_auc_sessions\" =  $subastaObj->id_auc_sessions ";

			if(!empty(\Config::get("app.gridAllSessions") )){
				$url_subasta = route("urlAuction",["texto" => \Str::slug($lote[0]->des_sub), "cod" => $lote[0]->cod_sub, "session" => '001']);

			}else{
				#$url_subasta= \Routing::translateSeo('subasta').$lote[0]->cod_sub."-".str_slug($lote[0]->name)."-".str_slug($lote[0]->id_auc_sessions);
				$url_subasta = \Tools::url_auction($lote[0]->cod_sub, $lote[0]->name, $lote[0]->id_auc_sessions, $lote[0]->reference);
			}


			$title_url_subasta = $lote[0]->des_sub;
		}

		# Monta las URL para los lotes: anterior y siguiente.
		//$item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R'
		$subastaObj->where_filter .= " AND  retirado_asigl0 = 'N' AND fac_hces1 != 'D' AND fac_hces1 != 'R'";
		$total_lotes = $subastaObj->getLots('small');

		$previous = null;
		$next = null;
		$actual = false;
		foreach ($total_lotes as $key => $value) {

			if ($value->ref_asigl0 == $lote[0]->ref_asigl0) {
				$actual = true;
			}
			//si no hemos pasado por actual y aun no hay previous
			elseif ($actual == false) {
			#	$webfriend = !empty($value->webfriend_hces1) ? $value->webfriend_hces1 :  str_slug($value->titulo_hces1);
			#	$previous = Routing::translateSeo('lote') . $value->cod_sub . "-" . $session_slug . "-" . $value->id_auc_sessions . '/' . $value->ref_asigl0 . '-' . $value->num_hces1 . '-' . $webfriend . $get_theme_key;



			$previous = \Tools::url_lot($value->cod_sub,$value->id_auc_sessions,"",$value->ref_asigl0,$value->num_hces1,$value->webfriend_hces1,$value->titulo_hces1);



			} elseif ($actual && is_null($next)) {
				#$webfriend = !empty($value->webfriend_hces1) ? $value->webfriend_hces1 :  str_slug($value->titulo_hces1);
				#$next = Routing::translateSeo('lote') . $value->cod_sub . "-" . $session_slug . "-" . $value->id_auc_sessions . '/' . $value->ref_asigl0 . '-' . $value->num_hces1 . '-' . $webfriend . $get_theme_key;



				$next = \Tools::url_lot($value->cod_sub,$value->id_auc_sessions,"",$value->ref_asigl0,$value->num_hces1,$value->webfriend_hces1,$value->titulo_hces1);


				break;
			}
		}




		$data = array();
		$data['previous'] = $previous;
		$data['next'] = $next;


		# El ajax se utiliza en el modo api tiempo real



		$subasta        = new Subasta();
		$subasta->cod   = Route::current()->parameter('cod');
		$subasta->tipo          = "'W', 'V', 'O'";
		$subasta->texto         = Route::current()->parameter('texto');
		$subasta->page          = 'all';
		$referencia             = Route::current()->parameter('ref');

		$js_item['lang_code'] = strtoupper(\App::getLocale());
		//inicializamos favorito a false, ya que si no hay usuario logeado no sabemso si es favorito
		$favorito = false;
		# Retornamos la información del usuario
		if (Session::has('user')) {
			# Cogemos la informacion del usuario ya que necesitamos datos de otras tablas como RSOC_CLI
			$user                = new User();
			$user->cod_cli       = Session::get('user.cod');
			$usuario             = $this->userLoged;
			$data["usuario"] = $usuario ;
			# Comprobamos si tiene un código de licitador asignado, de lo contrario le asignaremos uno.
			$subasta->cli_licit = Session::get('user.cod');
			$subasta->rsoc      = !empty($usuario->rsoc_cli) ? $usuario->rsoc_cli : $usuario->nom_cli;



			#cogemos lso telefonos para la puja telefonica
			$js_item['user']['phone1'] = $usuario->tel1_cli;
			$js_item['user']['phone2'] = $usuario->tel2_cli;

			//cogemso el cp de la direccion por defecto o el del cliente
			$js_item['user']['cp'] = !empty($usuario->cp_clid) ? $usuario->cp_clid : $usuario->cp_cli;
			$js_item['user']['country_code'] = !empty($usuario->codpais_clid) ? $usuario->codpais_clid : $usuario->codpais_cli;
			$js_item['user']['cod_div_cli'] = !empty($usuario->cod_div_cli) ? $usuario->cod_div_cli : '';
			if (!empty($subasta->cod)) {
				# Check dummy bidder y codigo licitador de subasta
				$subasta->checkDummyLicitador();

				# Si tienen numero de ministerio asignado, creamos ministerio como licitador
				if(Config::get('app.ministeryLicit', false)){
					$subasta->checkOrInstertMinisteryLicitador(Config::get('app.ministeryLicit'), 'Ministerio');
				}

				//si no tiene licitador y tampoco se asigna automaticamente es como si no tubiese usuario
				$res = $subasta->checkLicitador();
				if ($res) {
					$js_item['user']['cod_licit'] = head($res)->cod_licit;
				} else {
					$js_item['user']['cod_licit'] =  0;
				}
			} else {
				$js_item['user']['cod_licit']       = 0;
			}
			$subasta->licit = Session::get('user.cod');
			$js_item['user']['tk'] = Session::get('user.tk');
			//$js_item['user']['lang_code']     = strtoupper(\App::getLocale());
			// $js_item['user']['is_gestor']       = $usuario->tipo_cliweb == 'G' ? TRUE : FALSE;
			$js_item['user']['is_gestor']       = $usuario->tipacceso_cliweb == 'S' ? TRUE : FALSE;
			$js_item['user']['adjudicaciones']  = array();
			$js_item['user']['favorites']       = array();
			$js_item['user']['ordenMaxima']     = $subasta->getMaxOrden($subasta->cod, $referencia);
			$js_item['user']['pujaMaxima']      = head(array_reverse($subasta->getAllSubastaLicitPujas($subasta->cod, $referencia)));
			$js_item['user']['iva'] = $this->calculateIva();


			//miramos si esta en favoritos el lote, habria que hacer una funcion mejor esta hace joins que no son necesarios....
			/* # Eloy: Modificada a 31/08/2021, mantengo codigo original hasta asegurar que funcione correctamente.
			$fav = new Favorites($subasta->cod, $js_item['user']['cod_licit'], session('user.cod'));
			$favs = $fav->getFav($subastaObj->lote);
			if ($favs['status'] != 'error') {
				$favorito = true;
			} */
			$favorito = (new Favorites(null, null))->isFavorite($subasta->cod, $subastaObj->lote);
		}



		if (!empty($lote[0]->alm_hces1)) {
			$enterprise = new Enterprise();
			$almacen = $enterprise->getAlmacen($lote[0]->alm_hces1);
		} else {
			$almacen = null;
		}

		//aqui para siguiente
		$subasta_info = new \stdClass();

		$subasta_info->lote_actual    = $subastaObj->getAllLotesInfo($lote)[0];

		$subasta_info->lote_actual->comision = $subasta_info->lote_actual->comlhces_asigl0;

		$subasta_info->lote_actual->favorito = $favorito;
		$subasta_info->lote_actual->url_subasta = $url_subasta;
		$strLib = new StrLib();
		$subasta_info->lote_actual->title_url_subasta =$strLib->CleanStr($title_url_subasta);


		# Materiales de un lote en concreto
		$available_mats = array(1, 2, 3, 4, 5);
		$materiales     = array();
		$subastaObj->where_filter = " AND fghces1.LIN_HCES1 = " . $lote[0]->linhces_asigl0 . " AND fghces1.NUM_HCES1 = " . $lote[0]->numhces_asigl0;
		foreach ($available_mats as $key) {

			if (!empty($subastaObj->getMaterials($key)[0])) {
				array_push($materiales, $subastaObj->getMaterials($key)[0]);
			}
		}
		# Fin materiales de un lote

		# Asignamos el array de materiales al lote.
		$subasta_info->lote_actual->materiales = $materiales;

		# Asignamos el escalado para el próximo.
		if (isset($subasta_info->lote_actual->max_puja->imp_asigl1) && is_numeric($subasta_info->lote_actual->max_puja->imp_asigl1)) {
			$subasta->imp = $subasta_info->lote_actual->max_puja->imp_asigl1;
		} else {
			$subasta->imp = $subasta_info->lote_actual->impsalhces_asigl0;
		}

		if (!isset($subasta_info->lote_actual->max_puja->imp_asigl1)) {
			$subasta->sin_pujas = true;
		}


		# Escalado de la puja y la siguiente
		//$la_escalado = $subasta->escalado();

		$la_escalado = $subasta->NextScaleBid($subasta_info->lote_actual->impsalhces_asigl0, $subasta_info->lote_actual->actual_bid);
		$subasta_info->lote_actual->importe_escalado_siguiente = $la_escalado;

		$subasta_info->lote_actual->siguientes_escalados[] = $la_escalado;
		$siguienteEscalado = $subasta->NextScaleBid($subasta_info->lote_actual->impsalhces_asigl0, $subasta_info->lote_actual->siguientes_escalados[0]);
		if($siguienteEscalado != $subasta_info->lote_actual->siguientes_escalados[0]){
			$subasta_info->lote_actual->siguientes_escalados[] = $siguienteEscalado;
			$subasta_info->lote_actual->siguientes_escalados[] = $subasta->NextScaleBid($subasta_info->lote_actual->impsalhces_asigl0, $subasta_info->lote_actual->siguientes_escalados[1]);
		}
		else{
			$subasta_info->lote_actual->siguientes_escalados[] = $subasta->NextScaleBid($subasta_info->lote_actual->impsalhces_asigl0, $subasta_info->lote_actual->siguientes_escalados[0], true);
			$subasta_info->lote_actual->siguientes_escalados[] = $subasta->NextScaleBid($subasta_info->lote_actual->impsalhces_asigl0, $subasta_info->lote_actual->siguientes_escalados[1], true);
		}

		# Ficha del lote mediante subasta sin tiempo real
		$js_item['subasta']['cod_sub']  = $subastaObj->cod;

		$subasta_info->lote_actual =  $subastaObj->CleanStrLote($subasta_info->lote_actual);

		//quitamos los datos de pujas, ordenes y max puja para que no salgan datos en la web
		//lo ideal es quitarlo de raiz pero puede afectar a mas cosas...
		$subasta_info->lote_actual->pujas;
		$ordenes = $subasta_info->lote_actual->ordenes;
		unset($subasta_info->lote_actual->ordenes);
		//al vaciar las ordenes falla el js
		$subasta_info->lote_actual->ordenes = array();
		$subasta_info->lote_actual->max_puja;
		$subasta_info->almacen = $almacen;

		$other_sub = null;
		if ($subasta_info->lote_actual->subc_sub == 'H') {
			//Cojemos lote que esta en otra subasta activa
			$other_sub = $subastaObj->getLotActiveSession($subasta_info->lote_actual->num_hces1, $subasta_info->lote_actual->lin_hces1);
			if (count($other_sub) >= 1) {
				$other_sub = head($other_sub);
			}
		}

		$data['lot_other_sub'] = $other_sub;
		$js_item['lote_actual']         = $subasta_info->lote_actual;

		$js_item['subasta']['currency'] = $subasta->getCurrency();
		//código de divisa del usuario, si no tiene le ponemos el dollar
		$js_item['subasta']['cod_div_cli'] = !empty($js_item['user']) ? $js_item['user']['cod_div_cli'] : '';
		$data['subasta_info']           = $subasta_info;
		// las necesitamos para la subasta abierta saber si el usuario es el ganador de la puja
		$data['ordenes'] = $ordenes;
		$data['node']  = array(
			'action_url'    => Config::get('app.url') . "/api/action/subasta",
			'comprar'       => Config::get('app.url') . "/" . \App::getLocale() . "/api/comprar/subasta",
			'ol'       => Config::get('app.url') . "/" . \App::getLocale() . "/api/ol/subasta",
			'status_url'    => Config::get('app.url') . "/api/status/subasta",
			'chat'          => Config::get('app.url') . "/api/chat",
			'end_lot'       => Config::get('app.url') . "/api/end_lot",
			'pause_lot'     => Config::get('app.url') . "/api/pause_lot",
			'resume_lot'    => Config::get('app.url') . "/api/resume_lot",
			'cancel_bid'    => Config::get('app.url') . "/api/cancel_bid",
			'add_favorites'  => Config::get('app.url') . "/api-ajax/favorites/add",
		);
		$data['js_item'] = $js_item;

		$data['twitter'] = array(
			'card' => 'summary',
			'site' => Config::get('app.name'),
			'title' => $subasta_info->lote_actual->titulo_hces1,
			'description' => $subasta_info->lote_actual->desc_hces1,
			'image' => $subasta_info->lote_actual->imagen
		);
		//variable necesaria para google tagmanager para poder cargar la referencia del lote en el head de la web
		$data["gtag_pageview"] = $subastaObj->cod . "-" . $subasta_info->lote_actual->ref_asigl0;

		// (!empty($data['js_item']['lote_actual']->max_puja)  && !empty($data['js_item']->user) &&  $data['js_item']['lote_actual']->max_puja->cod_licit == $data['js_item']->user->cod_licit)? 'mine':'other';  >
		$SEO_metas = new \stdClass();
		if (strtotime(Config::get('app.fecha_noindex_follow')) > strtotime($subasta_info->lote_actual->end_session) || $subasta_info->lote_actual->subc_sub == 'A') {
			$SEO_metas->noindex_follow = true;
		} else {
			$SEO_metas->noindex_follow = false;
		}
		$SEO_metas->meta_title = $lote[0]->webmetat_hces1;
		$SEO_metas->meta_description = $lote[0]->webmetad_hces1;

		if (empty($lote[0]->webmetat_hces1)) {
			$SEO_metas->meta_title = mb_substr(strip_tags($lote[0]->descweb_hces1),0,60 );
		}
		if (empty($lote[0]->webmetad_hces1)) {
			$SEO_metas->meta_description = mb_substr(strip_tags($lote[0]->desc_hces1),0,160 );
		}

		//ponemos el canonical, de momento solo las subastas tematicas llevan variables, pero no afectara negativamente al resto
		$webfriend = !empty($subasta_info->lote_actual->webfriend_hces1) ? $subasta_info->lote_actual->webfriend_hces1 :  str_slug($subasta_info->lote_actual->titulo_hces1);
		$SEO_metas->canonical ="https://". $_SERVER['HTTP_HOST'] . \Routing::translateSeo('lote') . $subasta_info->lote_actual->cod_sub . "-" . $subasta_info->lote_actual->id_auc_sessions . '-' . $subasta_info->lote_actual->id_auc_sessions . "/" . $subasta_info->lote_actual->ref_asigl0 . '-' . $subasta_info->lote_actual->num_hces1 . '-' . $webfriend;

		//quitamso las variables
		if (!empty(strpos($SEO_metas->canonical, '?'))) {
			$SEO_metas->canonical = substr($SEO_metas->canonical, 0, strpos($SEO_metas->canonical, '?'));
		}

		#datos para Open Graph
		$SEO_metas->openGraphImagen = \Tools::url_img('lote_medium_large',$subasta_info->lote_actual->num_hces1,$subasta_info->lote_actual->lin_hces1);

		$data['seo'] = $this->cleanStrMeta($SEO_metas);

		/*  codigo temporal */
		if ($subasta_info->lote_actual->importe_escalado_siguiente  == $subasta_info->lote_actual->impsalhces_asigl0) {
			//miramos el config a ver si tenemos que cuadrar le precio de salida co nel escalado
			if (\Config::get('app.force_correct_price')) {
				$precio_salida = $subastaObj->NextScaleBid(0, $subasta_info->lote_actual->impsalhces_asigl0 - 1, FALSE);
			} else {
				$precio_salida = $subasta_info->lote_actual->impsalhces_asigl0;
			}
		} else {
			$precio_salida = $subasta_info->lote_actual->importe_escalado_siguiente;
		}

		//precio en recuadro de insertar puja
		$data['precio_salida'] = $precio_salida;

		//precio en siguiente puja
		$data['js_item']['lote_actual']->importe_escalado_siguiente = $precio_salida;
		$data['js_item']['lote_actual']->importe_escalado_siguiente_formated = \Tools::moneyFormat($precio_salida);


		#divisas
		$currency = new Currency();

		$data['divisas'] =  $currency->getAllCurrencies($js_item['subasta']['currency']->name);


		/* FIN  codigo temporal */
		return \View::make('front::pages.ficha', array('data' => $data));

	}


	//clean los SEO metas
	public function cleanStrMeta($meta)
	{

		$strLib = new StrLib();
		$meta->meta_title = $strLib->CleanStr(str_replace('"', '', $meta->meta_title));
		$meta->meta_description = $strLib->CleanStr(str_replace('"', '', $meta->meta_description));

		return $meta;
	}

	//Añadir un lote a favoritos o eliminarlo
	public function favorites($action)
	{

		$cod_sub   = Input::get('cod_sub');
		$cod_licit = Input::get('cod_licit');
		$ref       = Input::get('ref');
		// $cod_licit = Session::get('user.cod');

		if(empty($cod_licit) && !empty(Session::has('user'))){
			$licitador = FgLicit::SELECT("COD_LICIT")->where("SUB_LICIT", $cod_sub)->where("CLI_LICIT", Session::get('user.cod'))->first();
			if(!empty($licitador)){
				$cod_licit = $licitador->cod_licit;
			}else{
				return array(
					'status'    => 'error',
					);
			}
		}




		$res = array();

		$fav = new Favorites($cod_sub, $cod_licit);

		switch ($action) {
			case 'add':
				$res = $fav->setFav($ref);
				break;
			case 'remove':
				$res = $fav->removeFav($ref);
				break;
		}

		return $res;
	}


	//---------- NEW FAVORITES ----------------
	//Añadir un lote a favoritos o eliminarlo
	public function favoritesNew($action)
	{

		$cod_sub   = Input::get('cod_sub');
		$ref       = Input::get('ref');

		$res = array();

		$fav = new Favorites($cod_sub, false);

		switch ($action) {
			case 'add':
				$res = $fav->setFavNew($ref);
				break;
			case 'remove':
				$res = $fav->removeFavNew($ref);
				break;
		}

		return $res;
	}

	//-------- FIN NEW FAVORITES --------------


	private function create_filters($data)
	{

		$filters = array(
			"period" => array(),
			"period_count" => array()
		);

		foreach ($data as $item) {
			if (!empty($item->period)) {
				if (!isset($filters["period"][$item->period])) {
					$filters["period"][$item->period] = array();
					$filters["period_count"][$item->period] = 0;
				}
				$filters["period_count"][$item->period]++;
				if (!empty($item->subperiod_1) && !isset($filters["period"][$item->period][$item->subperiod_1])) {
					$filters["period"][$item->period][$item->subperiod_1] = 1;
				} elseif (!empty($item->subperiod_1)) {
					$filters["period"][$item->period][$item->subperiod_1]++;
				}
			}
		}
		//ahora se ordena teniendo encuenta el campo orden
		/*
        foreach ($filters["period"] as $key => $period){

            ksort($filters["period"][$key]);

        }

        ksort($filters["period"]);
        ksort($filters["period_count"]);
         *
         */
		return $filters;
	}

	public function auction_info()
	{

		$previous = NULL;
		$next = NULL;
		$actual = False;
		$subasta        = new Subasta();
		$subasta->cod   = Route::current()->parameter('cod');


		$auction = $subasta->getInfSubasta();

		if (!empty($auction) && ($auction->subc_sub == 'S' || $auction->subc_sub == 'H' || ($auction->subc_sub == 'A' && Session::get('user.admin')) )) {

			if ($auction->subc_sub == 'H') {
				$url_bread = \Routing::translateSeo('subastas-historicas');
				$name_bread = trans(\Config::get('app.theme') . '-app.subastas.historic_auctions');
			} elseif ($auction->tipo_sub == 'W') {
				$url_bread = \Routing::translateSeo('presenciales');
				$name_bread = trans(\Config::get('app.theme') . '-app.subastas.auctions');
			} elseif ($auction->tipo_sub == 'O') {
				$url_bread = \Routing::translateSeo('subastas-online');
				$name_bread = trans(\Config::get('app.theme') . '-app.foot.online_auction');
			}elseif ($auction->tipo_sub == 'P') {
				$url_bread = \Routing::translateSeo('subastas-online');
				$name_bread = trans(\Config::get('app.theme') . '-app.foot.online_auction');
			} elseif ($auction->tipo_sub == 'V') {
				$url_bread = \Routing::translateSeo('venta-directa');
				$name_bread = trans(\Config::get('app.theme') . '-app.foot.direct_sale');
			}else{
				$url_bread = "";
				$name_bread = "";
			}

			//cogemos las sesiones para hacer los enlaces de ver lotes
			$data['sessions'] =  $subasta->getSessiones();

			//cargamos el listado de subastas que sean del mismo tipo y tengan el mism oestado que esta, por ejmeplo W y activas
			$all_sessions = $subasta->auctionList($auction->subc_sub, $auction->tipo_sub);
			//hay que tener en cuenta que el listado es de sesiones, por lo que una subasta puede estar varias veces
			foreach ($all_sessions as $session) {

				if ($session->cod_sub == $auction->cod_sub) {
					$actual = true;
				}
				//si no hemos pasado por actual y aun no hay previous
				elseif ($actual == false) {
					$previous = Routing::translateSeo('info-subasta') . $session->cod_sub . "-" . str_slug($session->name);
				} elseif ($actual && is_null($next)) {
					$next = Routing::translateSeo('info-subasta') . $session->cod_sub . "-" . str_slug($session->name);
				}
			}
		} else {
			if(config('app.redirect_auction_finish_to_home')){
				return redirect()->route('home', [], 301);
			}
			exit(\View::make('front::errors.404'));
		}
		$data['seo'] = new \stdClass();
		$data['seo']->meta_title = $auction->des_sub;
		$data['seo']->meta_description = $auction->des_sub;
		if(\Config::get('app.noindexInfoAuction') && $auction->tipo_sub != 'E' ){
			$data['seo']->noindex_follow=true;
		}
		$data['previous'] = $previous;
		$data['url_bread'] = $url_bread;
		$data['name_bread'] = $name_bread;
		$data['next'] = $next;
		$data['auction'] = $auction;
		$data['noindex-follow'] = true;

		return \View::make('front::pages.ficha_subasta', array('data' => $data));
	}



	//coger fecha de subasta del lote
	public function getFechaFin()
	{

		# Información del Lote
		$subastaObj        = new Subasta();
		$subastaObj->cod   = Request::input('cod');
		$subastaObj->lote  = Request::input('ref');

		if (empty($subastaObj->cod) || empty($subastaObj->lote)) {
			$result = array(
				'status' => 'error'
			);
			return $result;
		}

		$lotes = $subastaObj->getLote();
		if (count($lotes) <= 0) {
			$result = array(
				'status' => 'error'
			);
		} else {

			$lote = head($lotes);
			$countdown = strtotime($lote->close_at) - getdate()[0];

			$result = array(
				'status' => 'success',
				'countdown' => $countdown,
				'close_at' =>  strtotime($lote->close_at)
			);
		}

		return $result;
	}
	//enviar email de recordatorio para la subasta, la ultima vez se uso desde pruebas, usando directamente la subasta, faltaria configurarlo para que funcionase con una llamada para poderlo programar
	//OJO por que si hay varias subastas no funcionará. por lo que la llamada se debería de hacer por subasta.
	public function send_reminder_email()
	{

		echo "COMENTADO PARA QUE NO LA LIE Y SE EJECUTE SIN QUERER";
		die();

		$sql = "select WEB_FAVORITES.ID_LICIT, ASIGL0.REF_ASIGL0, ASIGL0.SUB_ASIGL0, HCES1.TITULO_HCES1 ,HCES1.NUM_HCES1 , AUC.\"name\", AUC.\"id_auc_sessions\" from WEB_FAVORITES
                JOIN FGASIGL0 ASIGL0 ON ASIGL0.SUB_ASIGL0 = WEB_FAVORITES.ID_SUB AND ASIGL0.REF_ASIGL0 = WEB_FAVORITES.ID_REF AND ASIGL0.EMP_ASIGL0 =  WEB_FAVORITES.ID_EMP
                INNER JOIN FGHCES1 HCES1  ON (HCES1.EMP_HCES1 =  ASIGL0.EMP_ASIGL0 AND HCES1.NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0  AND HCES1.LIN_HCES1 = ASIGL0.LINHCES_ASIGL0)
                JOIN \"auc_sessions\" AUC ON (AUC.\"auction\" = ASIGL0.SUB_ASIGL0  AND AUC.\"company\" = ASIGL0.EMP_ASIGL0)

                WHERE
                WEB_FAVORITES.ID_SUB=:cod_sub
                AND
                    WEB_FAVORITES.ID_EMP =:emp
                    /*
                AND
                    FFIN_ASIGL0 > TRUNC(SYSDATE)
                AND
                    FFIN_ASIGL0 < TRUNC(SYSDATE+4)
                    ORDER BY WEB_FAVORITES.ID_LICIT
                    */
                ";

		$lots_reminder = DB::select(
			$sql,
			array(
				'emp'       =>  Config::get('app.emp'), //'002',//
				'cod_sub'   =>  '27092017', //'ONLINE'  //

			)
		);
		$users = array();

		//OJO SOLO SE PUEDE HACER POR UNA SUBASTA CADA VEZ
		foreach ($lots_reminder as $lot_reminder) {


			if (empty($users[$lot_reminder->id_licit])) {
				$user        = new User();
				$user->cod   = $lot_reminder->sub_asigl0;
				$user->licit = $lot_reminder->id_licit;
				$usuario     = head($user->getUserByLicit($user->cod, $user->licit));
				if (!empty($usuario)) {

					$users[$lot_reminder->id_licit]  = array();
					$users[$lot_reminder->id_licit]["usuario"] = $usuario;
					$users[$lot_reminder->id_licit]["lots"] = array();
				}
			}
			//ES NECESARIO COMPROBAR QUE NO ESTE VACIO, ES POSIBLE QUE ALGUN LICIT DE ERROR AL INTENTAR RECUPERAR EL USUARIO
			if (!empty($users[$lot_reminder->id_licit])) {
				$lot_reminder->link_lote = \Config::get('app.url') . '/' . \App::getLocale() . '/subasta-' . $lot_reminder->sub_asigl0 . '-' . str_slug($lot_reminder->id_auc_sessions) . '-' . $lot_reminder->id_auc_sessions . '/' . $lot_reminder->ref_asigl0 . '-' . $lot_reminder->num_hces1 . '-' . str_slug($lot_reminder->titulo_hces1);
				$users[$lot_reminder->id_licit]["lots"][] = $lot_reminder;
			}
		}



		foreach ($users as $id_licit => $user) {




			$content = "<p> SUS LOTES FAVORITOS DE LA SUBASTA <STRONG>" .  head($user["lots"])->name . "</STRONG> FINALIZARÁN EN MENOS DE 24 HORAS</p> <p> <i>  YOUR FAVORITE LOTS OF THE AUCTION  <STRONG>" .  head($user["lots"])->name . "</STRONG> WILL BE FINISH IN 24 HOURS</i></p> <br/>";
			$subject = "Pronto se celebrará la subasta de tus lotes favoritos / Your favorite lots will be auctioned soon";
			foreach ($user['lots'] as $lote) {
				$content .= "<p style='text-align:left'> * Lote $lote->ref_asigl0,  $lote->titulo_hces1  <a href='$lote->link_lote' target='_blank' >ver lote</a> </p>";
			}
			$emailOptions = array(
				'notification'       =>  "RECORDAROTIO / <i> REMINDER </i>",
				'user'      => $user["usuario"]->nom_cliweb,
				'content'      => $content,
				'to'        => $user["usuario"]->email_cliweb,
				'subject'   => $subject

			);


			if (\Tools::sendMail('notification', $emailOptions)) {
				Log::info('Mail sent recordatorio subasta  ID_LICIT:' . $id_licit);
			} else {

				Log::info('ERROR Mail sent recordatorio subasta  ID_LICIT:' . $id_licit);
			}
		}
	}

	//funcion que indica el orden que han seleccioando en la web
	private function set_order($subastaObj)
	{
		$get_order = Request::input('order');
		$order = FALSE;
		if (empty($get_order)) {
			//no hay codigo de subasta , la subasta es de tipo O o P
			if (empty($subastaObj->cod)) {
				$get_order = 'ffin';
			} else {
				$get_order = 'ref';
			}
		}

		switch ($get_order) {

			case 'name':
				$order = 'titulo_hces1, ref_asigl0 ASC';
				break;

			case 'price_asc':
				if (\Config::get('app.order_by_filter') == 'estimacion') {
					$order = 'imptash_asigl0 ASC, imptas_asigl0 ASC, ref_asigl0 ASC ';
				} elseif (\Config::get('app.order_by_filter') == 'precio_salida') {
					$order = 'impsalhces_asigl0 ASC, ref_asigl0 ASC ';
				}
				break;

			case 'price_desc':
				if (\Config::get('app.order_by_filter') == 'estimacion') {
					$order = 'imptash_asigl0 DESC, imptas_asigl0 DESC, ref_asigl0 ASC ';
				} elseif (\Config::get('app.order_by_filter') == 'precio_salida') {
					$order = 'impsalhces_asigl0 DESC, ref_asigl0 ASC ';
				}
				break;

			case 'ref':
				$order = 'ref_asigl0';
				break;

			//referencia más alta a más baja
			case 'ref_desc':
				$order = 'ref_asigl0 DESC';
				break;

				//mas proximo
			case 'ffin':
				$order = 'ffin_asigl0 ASC,hfin_asigl0 ASC, ref_asigl0 ASC';
				break;
				//mas lejano
			case 'ffin_desc':
				$order = 'ffin_asigl0 DESC,hfin_asigl0 DESC, ref_asigl0 ASC';
				break;
				//mas Sam ha pedido que le más reciente es el que le uqede más para subastarse, no el que se ha dado de alta mas tarde
			case 'fecalta':
				$order = 'ffin_asigl0 DESC,hfin_asigl0 DESC, ref_asigl0 DESC';  // 'fecalta_asigl0 DESC,horaalta_asigl0 DESC, ref_asigl0 ASC';
				break;
				//mayor numero de pujas
			case 'mbids':
				$order = ' nvl( (select max(lin_asigl1) from fgasigl1 asig11 where asig11.emp_asigl1 = ASIGL0.emp_asigl0 and asig11.sub_asigl1 = ASIGL0.sub_asigl0 and  asig11.ref_asigl1 = ASIGL0.ref_asigl0) , 0) desc, ref_asigl0 ASC';
				break;
				//puja más alta
			case 'hbids':
				$order = 'implic_hces1 DESC';
				break;
				//primero los no comprados, luego por orden de referencia, cojo la referencia de la csub de los cerrados, si no existe le doy valor 0 por lo que iran antes de cualquier referencia, luego ordeno por referencia normal para que mantenga el orden en lso no vendidos
			case 'fbuy':
				$order = "  (CASE   WHEN  (ASIGL0.CERRADO_ASIGL0 = 'S' AND ref_csub IS NULL) AND ASIGL0.DESADJU_ASIGL0 ='N' AND ASIGL0.RETIRADO_ASIGL0 ='N' AND FAC_HCES1 !='D' AND FAC_HCES1 != 'R'     THEN 0    ELSE     1    END ) ASC, ref_asigl0 ASC";
				break;
			case 'lastbids':
				$order = " nvl( (select max(fec_asigl1) from fgasigl1 asig11 where asig11.emp_asigl1 = ASIGL0.emp_asigl0 and asig11.sub_asigl1 = ASIGL0.sub_asigl0 and  asig11.ref_asigl1 = ASIGL0.ref_asigl0) ,'1970-01-01') desc, ref_asigl0 ASC";
				break;
			case '360':
				$order = '  img360_hces1 desc, ref_asigl0 asc';
				break;
		}

		$subastaObj->get_order = $get_order;
		$subastaObj->order_by_values = $order;
	}

	private function set_filter($subastaObj)
	{

		# Filtros
		$this->getWherePrices(Request::input('ini_price'), $subastaObj);
		$this->getWhereEstimate(Request::input('estimate'), $subastaObj);
		$this->getWhereDescription(Request::input('description'), $subastaObj);
		$this->getWhereExactDescription(Request::input('description_exact'), $subastaObj);
		$this->getWhereNoAward(Request::input('no_award'), $subastaObj);
		$this->getWhereDontSoldV($subastaObj);
		$this->getWhereReference(Request::input('reference'), $subastaObj);
		$this->getWhereLin_ortsec(Request::input('lin_ortsec'), $subastaObj);
		$this->getWhereCat_pers(Request::input('catpers'), $subastaObj);
		$this->getWhereCod_sec(Request::input('cod_sec'), $subastaObj);
		$this->getWhereFirst_lot(Request::input('first_lot'), $subastaObj);
		$this->getWhereLast_lot(Request::input('last_lot'), $subastaObj);
		$this->getWhereOpen(Request::input('open'), $subastaObj);
		$this->getWhereAward(Request::input('award'), $subastaObj);
		$this->getWhereAwardAndOpens(Request::input('awardOpen'), $subastaObj);
		$this->getWhereAuctionFilter($subastaObj);
		$this->getWhereDiscountsOffers(Request::input('offers'), $subastaObj);
		$this->getWhereMyLotsClient(Request::input('my_lots_client'), $subastaObj);
		$this->getWhereMyLotsProperty(Request::input('my_lots_property'), $subastaObj);
		$this->getWhereOnlySalable(Request::input('only_salable'), $subastaObj);
		if (Config::get('app.filter_period')) {

			$urlPeriodo = null;
			if (isset($_GET['subperiodo'])) {
				$urlPeriodo = $_GET['subperiodo'];
			}


			$this->getWhereSubperiod($urlPeriodo, $subastaObj);
		}

		// $where_filter .= $this->getWhereMaterials();
		// $where_filter .= $this->getWhereFamilies();

	}


	private function getWhereSubperiod($periodo, $subastaObj)
	{

		if (!empty($periodo)) {
			$periodo = urldecode($periodo);
			if (\Config::get("app.locale") == 'es') {
				$subastaObj->join_filter .= ' LEFT JOIN "object_types_values" ON "transfer_sheet_line" = HCES1.LIN_HCES1  AND "transfer_sheet_number" = HCES1.NUM_HCES1 AND "object_types_values"."company" = ' . \Config::get("app.emp");
			} else {
				$subastaObj->join_filter .= ' LEFT JOIN "object_types_values_lang" ON "transfer_sheet_line_lang" = HCES1.LIN_HCES1 AND "transfer_sheet_number_lang" = HCES1.NUM_HCES1 AND "company_lang" = ' . \Config::get("app.emp");
			}
			if ($periodo == strtolower(trans(\Config::get('app.theme') . '-app.lot_list.sin_periodo'))) {
				$subastaObj->where_filter .= ' AND "subperiod_1" IS NULL ';
			} else {
				if (\Config::get("app.locale") == 'es') {
					$subastaObj->where_filter .= " AND \"subperiod_1\" COLLATE LATIN_AI LIKE :subperiod || '%' ";
					$subastaObj->params_filter["subperiod"] = $periodo;
				} else {
					$subastaObj->where_filter .= " AND \"subperiod_1_lang\" COLLATE LATIN_AI LIKE :subperiod || '%' ";
					$subastaObj->params_filter["subperiod"] = $periodo;
				}
			}
		}
	}


	private function set_customize_values_filter($customize_values, $subastaObj)
	{

		if (!empty($customize_values->sections)) {

			if ($customize_values->sections == "'ALL'") {
				$subastaObj->where_filter .= " AND HCES1.SEC_HCES1 is not null";
			} else {
				$subastaObj->where_filter  .= " AND HCES1.SEC_HCES1 in ($customize_values->sections)";
			}
		}
		//filtramos lotes por session, permite mas de una session
		if (!empty($customize_values->lots)) {
			$subastaObj->where_filter .= " AND (";
			$or = "";

			foreach ($customize_values->lots as $id_auc_sessions => $lots) {

				//si no hay lotes cojemso toda la sesion
				if (empty($lots) || $lots == "'*'") {
					$subastaObj->where_filter  .= " $or  \"id_auc_sessions\" = $id_auc_sessions ";
				} else {
					$subastaObj->where_filter  .= " $or ( \"id_auc_sessions\" = $id_auc_sessions  AND HCES1.REF_HCES1 in ($lots) )";
				}
				$or = " OR ";
			}
			$subastaObj->where_filter  .= ")";
		}

		if (!empty($customize_values->tipo_sub)) {
			$subastaObj->where_filter .= $customize_values->tipo_sub;
		}
	}
	private function setFilterCustomize_copy($subastaObj, $type, $key_customize, $route_customize,  $SEO_metas)
	{
		$category = NULL;
		//si venimos de categorias o de temáticas
		if (!empty($type)) {
			if ($type == "category") {
				$category = $key_customize;
			}
			$auc = new AucIndex();
			$customize_values = $auc->getAucIndexByKeyname($key_customize, \Config::get('app.locale'));
			if (empty($customize_values)) {
				exit(\View::make('front::errors.404'));
			}

			$this->set_customize_values_filter($customize_values, $subastaObj);

			$auction_in_categories =  \Config::get('app.auction_in_categories');
			//debemos limitar la busqueda a los tipso de subasta que hayan indicado
			if (!empty($auction_in_categories)) {
				$subastaObj->where_filter .= " AND SUB.TIPO_SUB  IN ($auction_in_categories) AND ASIGL0.CERRADO_ASIGL0 = 'N' ";
			}



			//COMO LAS SUBASTAS DE TIPO P Y TIPO O SE COPIAN LOS LOTES DEBEMOS PONER QUE SOLO COJA EL ULTIMO LOTE, ES DECIR EL QUE COINCIDA CON LA HCES1
			// DE MOMENTO LO OBVIAMOS POR QUE NOS BASTA CON PONER QUE LOS LOTES NO ESTEN CERRADOS
			// AND HCES1.REF_HCES1 = ASIGL0.REF_ASIGL0 AND HCES1.SUB_HCES1 = ASIGL0.SUB_ASIGL0

			//Queremos que reindexe si es una familia
			$seo_family_session = new SeoFamiliasSessiones();
			$metas = $seo_family_session->FamilySessionsSeoLang($customize_values->id_web_auc_index, \Config::get('app.emp'), strtoupper(\Config::get('app.locale')));
			//Crea el objeto del Seo donde vendra todo el Seo de esa Category
			//Crea el objeto para que devuelva informacion de esa category para el breadcrumb
			$SEO_metas->noindex_follow = false;
			if (!empty($metas)) {
				$SEO_metas->url = \Routing::translateSeo($route_customize) . $key_customize;
				$SEO_metas->webname = $metas[0]->webname_auc_seo;
				$SEO_metas->meta_title = $metas[0]->webmetat_auc_seo;
				$SEO_metas->meta_description = $metas[0]->webmetad_auc_seo;
				$SEO_metas->meta_content = $metas[0]->webcont_auc_seo;
			}
		}
		//es subasta normal
		else {
			//Miramos si existe codigo de subasta, si no viene quiere decir que es una categoria

			//Es no es una category que no reindexe
			$SEO_metas->noindex_follow = true;
		}
		//si han elegido una categoria del listado y la subasta no es de tipo categoria
		if ($type != "category" &&  !empty(Request::input('category'))) {
			$category = Request::input('category');
			$auc = new AucIndex();
			//aqui no usamos $customize values por  que los customize_values son de para subastas customizadas
			$cat_values = $auc->getAucIndexByKeyname($category, \Config::get('app.locale'));
			$this->set_customize_values_filter($cat_values, $subastaObj);
		}

		return $category;
	}


	private function setFilterCustomize($subastaObj, $type, $key_customize, $route_customize,  $SEO_metas, $subcategory)
	{
		$category = NULL;
		//si venimos de categorias o de temáticas
		if (!empty($type)) {
			//si son categorias
			if ($type == "category") {
				$category = $key_customize;

				$sec_obj =  new Sec();
				$ortsec = $sec_obj->getOrtsecByKey('0', $category);
				if (empty($ortsec)) {
					exit(\View::make('front::errors.404'));
				}

				//Filtarr por subastas categories
				if (Request::input('s')) {
					$id_session = Request::input('s');

					$redirect_categ = true;

					if (is_numeric($id_session)) {

						$subastaObj->where_filter  .= " AND (AUC.\"id_auc_sessions\" = '$id_session') ";
						$session_sub = $subastaObj->getAuctionWithSession($id_session);

						if (!empty($session_sub)) {
							$redirect_categ = false;
						}
					}

					//No existe subasta redirigimos a la categoria seleccionada
					if ($redirect_categ) {
						$url_redirect = \Routing::translateSeo('subastas') . $key_customize;
						header("Location:" . $url_redirect);
						exit;
					}
				}
				//condiciones por la categoria
				$subastaObj->where_filter  .= " AND (ORTSEC1.LIN_ORTSEC1 = '$ortsec->lin_ortsec0')";
				$subastaObj->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = HCES1.SEC_HCES1 AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = '0') ";
				$auction_in_categories =  \Config::get('app.auction_in_categories');
				if (!empty($auction_in_categories)) {

					$subastaObj->where_filter .= " AND SUB.TIPO_SUB  IN ($auction_in_categories) ";
					if ($auction_in_categories != "'W'") {
						$subastaObj->where_filter .= " AND ASIGL0.CERRADO_ASIGL0 = 'N' ";
					}
				}

				$subcategory = Route::current()->parameter('subcategory');
				//si hay subcatgorias
				if (!empty($subcategory)) {
					$sec = $sec_obj->getSecByKey($subcategory);
					if (empty($sec) && !\Config::get('app.filter_period')) {
						exit(\View::make('front::errors.404'));
					} elseif (!\Config::get('app.filter_period')) {
						$subastaObj->where_filter  .= " AND (HCES1.SEC_HCES1 = '$sec->cod_sec')";
						$SEO_metas->url = \Routing::translateSeo($route_customize) . $category;
						$SEO_metas->subcategory = $sec->des_sec;
						$SEO_metas->webname = $ortsec->des_ortsec0;
						$SEO_metas->meta_title = $sec->meta_titulo_sec;
						$SEO_metas->meta_description = $sec->meta_description_sec;
						$SEO_metas->meta_content = $sec->meta_contenido_sec;
					}
				} else {
					$SEO_metas->url = \Routing::translateSeo($route_customize) . $category;
					$SEO_metas->webname = $ortsec->des_ortsec0;
					$SEO_metas->meta_title = $ortsec->meta_titulo_ortsec0;
					$SEO_metas->meta_description = $ortsec->meta_description_ortsec0;
					$SEO_metas->meta_content = $ortsec->meta_contenido_ortsec0;
				}
			}
			//subastas temáticas
			else {
				$auc = new AucIndex();
				$customize_values = $auc->getAucIndexByKeyname($key_customize, \Config::get('app.locale'));
				if (empty($customize_values)) {
					exit(\View::make('front::errors.404'));
				}

				$this->set_customize_values_filter($customize_values, $subastaObj);

				$auction_in_categories =  \Config::get('app.auction_in_categories');
				//debemos limitar la busqueda a los tipso de subasta que hayan indicado
				if (!empty($auction_in_categories)) {
					$subastaObj->where_filter .= " AND SUB.TIPO_SUB  IN ($auction_in_categories) AND ASIGL0.CERRADO_ASIGL0 = 'N' ";
				}



				//COMO LAS SUBASTAS DE TIPO P Y TIPO O SE COPIAN LOS LOTES DEBEMOS PONER QUE SOLO COJA EL ULTIMO LOTE, ES DECIR EL QUE COINCIDA CON LA HCES1
				// DE MOMENTO LO OBVIAMOS POR QUE NOS BASTA CON PONER QUE LOS LOTES NO ESTEN CERRADOS
				// AND HCES1.REF_HCES1 = ASIGL0.REF_ASIGL0 AND HCES1.SUB_HCES1 = ASIGL0.SUB_ASIGL0

				//Queremos que reindexe si es una familia
				$seo_family_session = new SeoFamiliasSessiones();
				$metas = $seo_family_session->FamilySessionsSeoLang($customize_values->id_web_auc_index, \Config::get('app.emp'), strtoupper(\Config::get('app.locale')));
				//Crea el objeto del Seo donde vendra todo el Seo de esa Category
				//Crea el objeto para que devuelva informacion de esa category para el breadcrumb
				$SEO_metas->noindex_follow = false;
				if (!empty($metas)) {
					$SEO_metas->url = \Routing::translateSeo($route_customize) . $key_customize;
					$SEO_metas->webname = $metas[0]->webname_auc_seo;
					$SEO_metas->meta_title = $metas[0]->webmetat_auc_seo;
					$SEO_metas->meta_description = $metas[0]->webmetad_auc_seo;
					$SEO_metas->meta_content = $metas[0]->webcont_auc_seo;
				}
			}
		}
		//es subasta normal
		else {
			//Miramos si existe codigo de subasta, si no viene quiere decir que es una categoria

			//Es no es una category que no reindexe
			$SEO_metas->noindex_follow = true;
			$subcategory = Request::input('cod_sec');
			$category = Request::input('lin_ortsec');
		}
		/*
        //si han elegido una categoria del listado y la subasta no es de tipo categoria
        if($type!="category" &&  !empty(Request::input('lin_ortsec1')) ){
            $category = Request::input('lin_ortsec1');

            $auc = new AucIndex();
            //aqui no usamos $customize values por  que los customize_values son de para subastas customizadas
            $cat_values = $auc->getAucIndexByKeyname($category,\Config::get('app.locale'));
            $this->set_customize_values_filter($cat_values, $subastaObj);

        }
             */

		return $category;
	}

	//Elimnar orden desde el panel del usuario
	public function DeleteOrders()
	{

		$subasta = new Subasta();
		$user = new User();
		$cod_sub = Request::input('sub');
		$subasta->cod = $cod_sub;
		$subasta->cod_cli =  Session::get('user.cod');
		$subasta->ref =  Request::input('ref');

		/*Buscar codgo de licitador*/
		$user->cod = $cod_sub;
		$user->cod_cli = Session::get('user.cod');

		$licit = $user->getLicitCli();

		$inf_subasta = $subasta->getInfSubasta();

									#
		if (!empty($inf_subasta)  && empty(\Config::get("app.DeleteOrdersAnyTime")) && empty(\Config::get("app.DeleteOrders"))   && (strtotime("now") < strtotime($inf_subasta->orders_start)  ||  strtotime("now") > strtotime($inf_subasta->orders_end))) {


			$res = array(
				"status" => "error",
				"msg" => 'generic'
			);
		} else if (!empty($licit)) {
			$orden = $subasta->getOrden($licit[0]->cod_licit);
			#para subastas online
			if( $inf_subasta->tipo_sub == 'O'  ){

				#debemos comprobar si la orden es superior a las pujas que quedan, por que si no lo es le daremos un mensaje al usuario que no se puede borrar uan orden si ya se ha convertido en puja
				$pujas = $subasta->getPujas($licit[0]->cod_licit);

				#Si no hay ordenes o la orden ya se ha materializado en puja
				if(count($orden)==0 || ( count($pujas) > 0 && $pujas[0]->imp_asigl1>= $orden[0]->himp_orlic)) {

					$res = array(
						"status" => "error",
						"msg" => 'error_delete_order_online',
						"respuesta" => $subasta->cod . '-' . $subasta->ref
					);
					return json_encode($res);
				}




			}



			if (!empty($orden[0]->himp_orlic)) {
				$subasta->imp = $orden[0]->himp_orlic;
				$subasta->cancelarOrden($licit[0]->cod_licit);

				#enviamso email
				$email = new EmailLib('CANCEL_ORDER');
				if (!empty($email->email)) {
					$email->setUserByCod($user->cod_cli,true);
					$email->setLot($cod_sub, $subasta->ref);
					$email->setAtribute("CANCEL_BID", $subasta->imp);
					$email->send_email();
				}

				#llamada a funcion de webservice de borrar orden
				$this->webServiceDeleteOrder($inf_subasta->tipo_sub,$licit[0]->cod_licit, $subasta->cod, $subasta->ref, $subasta->imp);


				$res = array(
					"status" => "success",
					"msg" => 'delete_order',
					"respuesta" => $subasta->cod . '-' . $subasta->ref
				);
			} else {
				$res = array(
					"status" => "error",
					"msg" => 'error_delete_order',
					"respuesta" => $subasta->cod . '-' . $subasta->ref
				);
			}
		} else {
			$res = array(
				"status" => "error",
				"msg" => 'no_licit'
			);
		}


		return json_encode($res);
	}


	public function webServiceDeleteOrder($type, $licit, $codSub, $ref, $imp ){

		if(Config::get('app.WebServiceDeleteOrder')){

			$theme  = Config::get('app.theme');

			if($type=="W"){

				$rutaBidController = "App\Http\Controllers\\externalws\\$theme\OrderController";
			}else{
				$rutaBidController = "App\Http\Controllers\\externalws\\$theme\BidController";
			}


			$bidController = new $rutaBidController();

			$bidController->deleteOrder($licit, $codSub, $ref, $imp );
		}

	}


	public function rechargeFilters()
	{


		$data["type"] = app('request')->input('type');
		$data['cod_sub'] = app('request')->input('cod_sub');
		$data['id_auc_sessions'] = app('request')->input('id_auc_sessions');
		$data['category'] = app('request')->input('category');
		$data['subcategory'] = app('request')->input('subcategory');

		return \View::make('front::includes.select_filters', array('data' => $data));
	}

	public function categSubcateg($all_categ_sub)
	{
		$order_catge = array();
		$category = new Category();
		$all_catgeorys = $category->getCategSubCateg(true, $all_categ_sub);

		return $all_catgeorys;
	}

	public function reloadLot()
	{
		$subastaObj = new Subasta();
		$subastaObj->cod = Request::input('cod_sub');
		$subastaObj->lote = Request::input('ref');
		$where = FALSE;
		$lote = $subastaObj->getLote($where);
		if (empty($lote)) {
			return 'error';
		}
		$lote_actual = $subastaObj->getAllLotesInfo($lote)[0];
		if ($lote_actual->cerrado_asigl0 == 'N') {
			return 'error';
		}
		$data['retirado'] = $lote_actual->retirado_asigl0 == 'S' ? true : false;
		$data['remate'] = $lote_actual->remate_asigl0 == 'S' ? true : false;
		$data['subasta_venta'] = ($lote_actual->tipo_sub == 'V') ? true : false;
		$data['devuelto'] = ($lote_actual->cerrado_asigl0 == 'D') ? true : false;
		$data['lote_actual'] = $lote_actual;
		$data['cerrado'] = true;

		return \View::make('front::includes.ficha.pujas_ficha_cerrada', $data);
	}

	public function lotes_recomendados($lot)
	{
		try {
			$subasta = new Subasta();
			$bloque = new Bloques();
			$subasta->cod = $lot->sub_csub;
			$subasta->lote = $lot->ref_csub;

			//Precio Actual
			$inf_lot = $subasta->getLoteLight();
			$lot_relacionados_lang = [];
			$lotes_relacionado_final = '';
			$relacionados = array();
			$key = "recomendados_email";
			foreach (Config::get('app.language_complete') as $key_lang => $lang) {
				\App::setLocale(strtolower($key_lang));
				$replace = array(
					'emp' => Config::get('app.emp'),
					'sec_hces1' => $inf_lot->sec_hces1,
					'id_hces1' => $inf_lot->id_hces1,
					'lang' => $lang,
				);


				$relacionados_temp = $bloque->getResultBlockByKeyname($key, $replace);

				foreach ($relacionados_temp as $value) {
					$relacionados[$value->num_hces1 . '-' . $value->lin_hces1] = $value;
				}
				foreach ($relacionados as $value) {

					$url_friendly = str_slug($value->webfriend_hces1);
					$url_friendly = \Routing::translateSeo('lote') . $value->sub_asigl0 . "-" . $value->id_auc_sessions . '-' . $value->id_auc_sessions . "/" . $value->ref_asigl0 . '-' . $value->num_hces1 . '-' . $url_friendly;
					$lotes_relacionado = '<a style="color:rgb(110,109,109);" href="' . Config::get('app.url') . $url_friendly . "/" . Config::get('app.utm_email') . '"><div>'
						. '<img width="50px" src="' . Config::get('app.url') . '/img/load/lote_small/' . Config::get('app.emp') . '-' . $value->num_hces1 . '-' . $value->lin_hces1 . '.jpg">'
						. '<br><u>' . $value->descweb_hces1 . '</div></u></a><br><br>';

					$lotes_relacionado_final .= $lotes_relacionado;
				}
				if (empty($relacionados)) {
					$lotes_relacionado_final = '';
				}
				$lot_relacionados_lang[strtoupper($key_lang)] = $lotes_relacionado_final;
				$lotes_relacionado_final = '';
			}

			return $lot_relacionados_lang;
		} catch (\Exception $e) {
			return null;
		}
	}


	public function calculateIva()
	{

		$payment = new Payments();
		$paymentsController = new PaymentsController();

		$iva = $paymentsController->getIva(Config::get('app.emp'), date("Y-m-d"));

		$tipo_iva = $paymentsController->user_has_Iva(Config::get('app.gemp'), Session::get('user.cod'));

		return $paymentsController->hasIvaReturnIva($tipo_iva->tipo, $iva);
	}

	public function calendarController(HttpRequest $request)
	{
		$auctions = $this->subastas_activas(true);
		if (!empty($auctions)) {
			$auctions = head($auctions);
		}

		$events = WebCalendarEvent::get();
		$year = $request->input('year', date("Y"));
		$isValidYear = is_numeric($year) && $year > 2000 & $year < 2100;

		if(!$isValidYear){
			$year = date("Y");
		}

		$days = WebCalendar::where("start_calendar", ">", date("$year-01-01"))
			->where("start_calendar", "<=", date("$year-12-31 23:59:59"))
			->joinEvent()
			->get();

		$eventInCalendar = [];
		foreach($days as $day){
			$eventInCalendar[$day->cod_calendar_event] = 1;
		}

		//Formatos para utilizar en javascript
		$daysEventsFormat = $days->map(function($day) {
			return [
				'name' => $day->name_calendar,
				'description' => $day->description_calendar,
				'startDate' => $day->start_calendar,
				'endDate' => $day->end_calendar,
				'calIniSub' => $day->calini_sub,
				'color' => $day->color_calendar_event,
				'url' => $day->url_calendar,
			];
		});
		$auctionsEventsFormat = array_map(function($auction){
			return [
				'description' => $auction->des_sub,
				'startDate' => $auction->session_start,
				'endDate' => $auction->calfin_sub ?? $auction->session_start,
				'calIniSub' => $auction->calini_sub,
				'color' => '#AAAAAA'
			];
		}, $auctions);

		$seoExist = TradLib::getWebTranslateWithStringKey('metas', 'title_calendar', config('app.locale', 'es'));
		$seo = new \stdClass();
		if(!empty($seoExist)){
			$seo->meta_title = trans(\Config::get('app.theme') . '-app.metas.title_calendar');
			$seo->meta_description = trans(\Config::get('app.theme') . '-app.metas.description_calendar');
		}
		$seo->canonical=$_SERVER['HTTP_HOST'].\Routing::slugSeo('calendar');

		$viewData = [
			'seo' => $seo,
			'year' => $year,
			'events' => $events,
			'auctions' => $auctions,
			'eventInCalendar' => $eventInCalendar,
			'daysEventsFormat' => $daysEventsFormat,
			'auctionsEventsFormat' => $auctionsEventsFormat
		];

		return view('front::pages.calendar', $viewData);
	}

	public function reproducciones()
	{

		$data = Input::all();

		$video = DB::table("WEB_VIDEO")->where("EMP_VIDEO", \Config::get('app.emp'))->where("REF_VIDEO", $data['ref'])->where("SUB_VIDEO", $data['sub'])->where("VIDEO_VIDEO", $data['video'])->first();

		$res = new \stdClass();

		if (empty($video)) {
			DB::table("WEB_VIDEO")->insert([
				"emp_video" => \Config::get('app.emp'),
				"sub_video" => $data['sub'],
				"ref_video" => $data['ref'],
				"video_video" => $data['video'],
				"reproducciones_video" => 1,
				"megusta_video" => 0
			]);
			$res->reproducciones = 1;
			$res->megusta = 0;
		} else {
			DB::table("WEB_VIDEO")->where("EMP_VIDEO", \Config::get('app.emp'))->where("REF_VIDEO", $data['ref'])->where("SUB_VIDEO", $data['sub'])->where("VIDEO_VIDEO", $data['video'])->update([
				"reproducciones_video" => $video->reproducciones_video + 1
			]);
			$res->reproducciones = $video->reproducciones_video + 1;
			$res->megusta = $video->megusta_video;
		}

		$has_megusta = 0;

		if (\Session::has("user")) {
			$has_megusta = DB::table("WEB_VIDEO_MEGUSTA")
				->where("EMP_VIDEO_MEGUSTA", \Config::get('app.emp'))
				->where("VIDEO_VIDEO_MEGUSTA", $data['video'])
				->where("CLI_VIDEO_MEGUSTA", \Session::get("user.cod"))
				->first();
		}

		echo $res->reproducciones . "-" . $res->megusta . "-" . (!empty($has_megusta));
	}

	public function megusta()
	{

		$data = Input::all();

		$video = DB::table("WEB_VIDEO")->where("EMP_VIDEO", \Config::get('app.emp'))->where("REF_VIDEO", $data['ref'])->where("SUB_VIDEO", $data['sub'])->where("VIDEO_VIDEO", $data['video'])->first();

		$has_megusta = DB::table("WEB_VIDEO_MEGUSTA")
			->where("EMP_VIDEO_MEGUSTA", \Config::get('app.emp'))
			->where("VIDEO_VIDEO_MEGUSTA", $data['video'])
			->where("CLI_VIDEO_MEGUSTA", \Session::get("user.cod"))
			->first();

		if (empty($has_megusta)) {

			DB::table("WEB_VIDEO_MEGUSTA")->insert([
				"EMP_VIDEO_MEGUSTA" => \Config::get('app.emp'),
				"VIDEO_VIDEO_MEGUSTA" => $data['video'],
				"CLI_VIDEO_MEGUSTA" => \Session::get("user.cod")
			]);

			DB::table("WEB_VIDEO")->where("EMP_VIDEO", \Config::get('app.emp'))->where("REF_VIDEO", $data['ref'])->where("SUB_VIDEO", $data['sub'])->where("VIDEO_VIDEO", $data['video'])->update([
				"megusta_video" => $video->megusta_video + 1
			]);
			echo ($video->megusta_video + 1) . "-1";
		} else {

			DB::table("WEB_VIDEO_MEGUSTA")
				->where("EMP_VIDEO_MEGUSTA", \Config::get('app.emp'))
				->where("VIDEO_VIDEO_MEGUSTA", $data['video'])
				->where("CLI_VIDEO_MEGUSTA", \Session::get("user.cod"))
				->delete();

			DB::table("WEB_VIDEO")->where("EMP_VIDEO", \Config::get('app.emp'))->where("REF_VIDEO", $data['ref'])->where("SUB_VIDEO", $data['sub'])->where("VIDEO_VIDEO", $data['video'])->update([
				"megusta_video" => $video->megusta_video - 1
			]);
			echo ($video->megusta_video - 1) . "-0";
		}
	}


	public function getFormularioPujar(){

		$cod_cli = request('cod_cli');
		$cod_sub = request('cod_sub');
		$ref = request('ref');

		\App::setLocale(strtolower(request('lang', 'es')));

		$fxCli = FxCli::select('cod_cli', 'nom_cli', 'rsoc_cli', 'email_cli', 'cif_cli' ,'fisjur_cli')->where('cod_cli', $cod_cli)->first();

		$formulario = [
			trans(\Config::get('app.theme') . '-app.login_register.nombre') => FormLib::TextReadOnly('nom', 1, $fxCli->fisjur_cli == 'R' ? $fxCli->rsoc_cli : $fxCli->nom_cli),
			trans(\Config::get('app.theme') . '-app.login_register.email') => FormLib::TextReadOnly('email_cli', 1, $fxCli->email_cli),
			trans(\Config::get('app.theme') . '-app.login_register.nif_dni_nie') => FormLib::TextReadOnly('cif_cli', 1, $fxCli->cif_cli),
			trans(\Config::get('app.theme') . '-app.login_register.representar') => FormLib::Select('representar', 1, $fxCli->fisjur_cli == 'R' ? 'S' : 'N', ['S' => trans(\Config::get('app.theme') . '-app.login_register.yes'), 'N' => trans(\Config::get('app.theme') . '-app.login_register.no') ], '', '', false),
			trans(\Config::get('app.theme') . '-app.login_register.company') => FormLib::Text('nom_rsoc', 1, $fxCli->fisjur_cli == 'R' ? $fxCli->nom_cli : ($fxCli->fisjur_cli == 'J' ? $fxCli->rsoc_cli : ''), 'maxlength="59"'),
		];

		return view('front::includes.ficha.formulario_deposito', compact('formulario', 'cod_sub', 'ref'))->render();
	}

	public function sendFormularioPujar()
	{

		if(!Session::has('user')){
			return response(trans(\Config::get('app.theme') . '-app.msg_error.mustLogin'), 404);
		}

		$files = request()->file('file') ?? [];

		foreach ($files as $file) {
			if($file->getError() == 1){
				$max_size = $file->getMaxFileSize() / 1024 / 1024;  // Get size in Mb
				$error = 'El tamaño del documento debe ser menor a ' . $max_size . 'Mb.';
        		return response($error, 404);
			}
		}

		$cod_cli = Session::get('user')['cod'];
		$representar = request('representar', 'N');
		$nom_rsoc = request('nom_rsoc', '');
		$cod_sub = request('cod_sub', '');
		$ref = request('ref', '');

		$user = new User();
		$mailController = new MailController();
		$fxCli = FxCli::select('cod_cli', 'nom_cli', 'rsoc_cli', 'fisjur_cli', 'email_cli', 'cif_cli')->where('COD_CLI', $cod_cli)->first();

		//quiere representar, antes ya lo era y el nombre de la empresa ha cambiado
		if($representar == 'S' && mb_strtolower($nom_rsoc) != mb_strtolower($fxCli->nom_cli)){

			$fxCli = $user->changeToRepresentative($fxCli, $nom_rsoc);
		}
		//No quiere representar, pero antes era representante
		elseif($representar == 'N' && $fxCli->fisjur_cli == 'R'){

			$fxCli = $user->changeFromRepresentativeToParticular($fxCli);
		}

		//Envio de email
		if($mailController->sendFormAuthorizeBid($fxCli, $cod_sub, $ref, $files)){
			return response(trans(\Config::get('app.theme') . '-app.msg_success.mensaje_enviado'), 200);
		}
		return response(trans(\Config::get('app.theme') . '-app.msg_error.generic'), 404);

	}

	public function acceptAuctionConditions(HttpRequest $request)
	{
		$theme = Config::get('app.theme');

		if(!Session::has('user')){
			Log::error("Error al aceptar condiciones de subasta, usuario no logueado", ["request" => $request->all()]);

			return response()->json([
				'status' => 'error',
				'message' => trans("$theme-app.msg_error.mustLogin")
			]);
		}

		$cod_cli = Session::get('user')['cod'];;
		$cod_sub = $request->input('codSub');

		FgSubConditions::create([
			'cod_subconditions' => $cod_sub,
			'cli_subconditions' => $cod_cli
		]);

		return response()->json([
			'status' => 'success',
			'message' => trans("$theme-app.msg_success.conditions_accepted"),
		]);
	}

	public function modalGridImages(){

		$num_hces1 = request('num_hces1');
		$lin_hces1 = request('lin_hces1');
		$ref_asigl0 = request('ref_asigl0');
		$titulo = request('titulo');
		$descripcion = request('descripcion');
		$cod_sub = request('cod_sub');

		$subastaModel = new \App\Models\Subasta();
		$numLin = new \stdClass();
		$numLin->num_hces1 = $num_hces1;
		$numLin->lin_hces1 = $lin_hces1;

		$imagenes = $subastaModel->getLoteImages($numLin);
		$videos = $subastaModel->getLoteVideos($numLin);

		return view('front::includes.grid._modal_images', compact('imagenes', 'videos', 'ref_asigl0', 'titulo', 'descripcion', 'num_hces1', 'lin_hces1', 'cod_sub'))->render();

	}

	public function modalImagesFullScreen()
	{
		$lote = (object)[
			'num_hces1' => request('num_hces1'),
			'lin_hces1' => request('lin_hces1')
		];
		$page = request('page');

		$images = (new Subasta())->getLoteImages($lote);
		$imagesUrl = [];

		foreach ($images as $key => $value) {
			//$imagesUrl[] = ToolsServiceProvider::url_img("lote_large", $lote->num_hces1, $lote->lin_hces1, $key);
			$imagesUrl[] = "/img/load/real/$value";
		}

		return view('front::includes.grid._modal_images_mobile', compact('imagesUrl', 'page'))->render();
	}


	public function getDownloadLotFile($lang, $idFile, $num_hces1, $lin_hces1)
	{
		$user = session('user');
		$lot = FgAsigl0::select('sub_asigl0', 'ref_asigl0')->where([
			['numhces_asigl0', $num_hces1],
			['linhces_asigl0', $lin_hces1]
		])->first();

		$deposit = (new FgDeposito())->isValid($user['cod'], $lot->sub_asigl0, $lot->ref_asigl0);

		$file = FgHces1Files::getFileByIdCanViewUser($user, $idFile, $deposit);

		if(!$file){
			abort(404);
		}

		if(!file_exists($file->storage_path)){
			abort(404);
		}

		$extension = (new SplFileInfo($file->storage_path))->getExtension();
		return response()->download($file->storage_path, $file->name_hces1_files . '.' . $extension);
	}

	public function getAucSessionFiles(HttpRequest $request)
	{
		if(!$request->auction || !$request->reference){
			return response()->json(['status' => 'error', 'error' => 'No se ha encontrado la subasta'], 404);
		}

		$auctionSessionFiles = AucSessionsFiles::where([
			'"auction"' => $request->auction,
			'"reference"' => $request->reference,
			'"lang"' => config('app.language_complete')[config('app.locale')]
		])->get();

		$view = view('front::includes.subasta.files', ['auctionSessionFiles' => $auctionSessionFiles])->render();

		return response()->json([
			'status' => 'success',
			'html' => $view,
		]);
	}

}
