<?php
namespace App\Http\Controllers\V5;


use Config;

use View;
use Route;
use DB;
use Session;
use Cookie;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\articles\FgArt;
use App\Models\articles\FgArt0;
use App\Models\articles\FgArt_Variantes;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FxSec;
use App\Models\V5\FgFamart;

# Cargamos el modelo



class ArticleController extends Controller
{
	var $numElements = 12;
	var $errorMsg = "add_lot_cart";
	var $articleCart = array();
	var $imagesDir = "articulos";

	public function index(Request $request){
		$data=[];
		if($request->family){
			$fgfamart = FgFamart::select("COD_FAMART")->where("COD_FAMART",strtoupper($request->family) )->first();

			\Tools::exit404IfEmpty($fgfamart);
			$data["familia"] = $fgfamart->cod_famart;

		}

		if($request->category){
			$fgortsec0 = new FgOrtsec0();
			$linOrtsec =  $fgortsec0->getLinFgOrtsec($request->category) ;

			\Tools::exit404IfEmpty($linOrtsec);
			$data["ortsec"] = $linOrtsec;

		}
		if($request->subcategory){

			$fxSec = new FxSec();
			$cod_sec = $fxSec->GetCodFromKey($request->subcategory);
			\Tools::exit404IfEmpty($cod_sec);
			$data["sec"] = $cod_sec;

		}

		return View::make('front::pages.articles.grid', $data);
	}





	#funcion que incluye información extra en los articulos, ruta img, url ficha etc...
	private function setInfoArticles($articles){
		#cargamos el iva
		$cartController = new CartController();
		$iva =  $cartController->ivaAplicable();
		foreach($articles as $key =>$article){
			$article->img  =$this->getImageArt(NULL,$article->id_art0);

			//$article->url = route("article",["idArticle" => $article->id_art0, "friendly" =>\Str::slug($article->model_art0)]);
			$slug = \Str::slug($article->model_art0);
			$article->url = \Routing::translateSeo('articulo',"/{$article->id_art0}-{$slug}");

			$imp = round($article->pvp_art0 + ($article->pvp_art0 * $iva),2);
			
			$article->pvpFormat = \Tools::moneyFormat($imp, trans(\Config::get('app.theme').'-app.subastas.euros'),0 );

			$articles[$key] = $article;
		}

		return $articles;
	}

	/*   FICHA ARTICULOS */

	public function article($idArticle){

		$article = FgArt0::select("ID_ART0, MODEL_ART0,   DES_ART0, PVP_ART0, SEC_ART0")->where("ID_ART0", $idArticle)->Activo()->first();




		\Tools::exit404IfEmpty($article);

		#el iva que aplicaremos
		$cartController = new CartController();
		$iva =  $cartController->ivaAplicable();
		$article->imp =  round($article->pvp_art0 + ($article->pvp_art0 * $iva), 2);


		$section = FxSec::JoinLangFxSec()
		->addselect("FXSEC.COD_SEC")
		->addselect("NVL(FXSEC_LANG.KEY_SEC_LANG, FXSEC.KEY_SEC) KEY_SEC")
		->addselect("NVL(FXSEC_LANG.DES_SEC_LANG, FXSEC.DES_SEC) DES_SEC")
		->where("FXSEC.COD_SEC", $article->sec_art0)->first();

		$valvariantes = FgArt0::select("FGART.ID_ART, FGART.PVP_ART, FGART.STK_ART,  DP_STOCK_PROD.STOCK, FGART_VARIANTES.ID_VARIANTE, FGART_VARIANTES.NAME_VARIANTE, FGART_VALVARIANTES.ID_VALVARIANTES, FGART_VALVARIANTES.VALOR_VALVARIANTE")->where("ID_ART0", $idArticle)
						->JoinArt()->ArtActivo()
						->JoinLineasVariantes()->JoinValVariantes()->JoinVariantes()
						->leftjoin("DP_STOCK_PROD", " DP_STOCK_PROD.EMP_ART = FGART.EMP_ART AND DP_STOCK_PROD.ID_ART = FGART.ID_ART")
						->orderby("FGART_VARIANTES.ID_VARIANTE, FGART_VALVARIANTES.ORDEN ")->get();


		$tallasColores = [];
		$variantes = [];

		$precioArticulos = [];


		foreach($valvariantes as $valvariante){

			$variantes[$valvariante->id_variante] = $valvariante->name_variante;

			if(empty($tallasColores[$valvariante->id_variante])){
				$tallasColores[$valvariante->id_variante] = [];
			}
			$tallasColores[$valvariante->id_variante][$valvariante->id_valvariantes] = $valvariante;
			$precioArticulos[ $valvariante->id_valvariantes]=  round($valvariante->pvp_art + ($valvariante->pvp_art * $iva), 2);
		}






		/*
			En art0 deberia haber siempre como mínimo una imagen
			Las imagenes de FGART las podemos usar como thumbs, ya que de momento no veo necesario hacer que se cambien al elejir un color
			ruta imagenes en FGART
			"img/articles/LINES/". $article->id_art.".jpg";
			$name_archive = "img/articles/LINES/". $article->id_art.".jpg";

            if (file_exists($name_archive)) {
				$article->img  ="/". $name_archive;
		*/
		#primero buscamos la imagen en lines
			#\LINES\id_art.jpg


			$images=array();
		$name_archive = $this->imagesDir ."/". $article->id_art0;
		if (file_exists($name_archive.".jpg")) {
			$article->img  ="/". $name_archive.".jpg";
			$images[] = $article->img ;

			foreach (glob($name_archive.'_*') as $file) {
				if (file_exists($file)){
					$images[] = "/". $this->imagesDir ."/".basename($file);
				}
			}

			#de momento cargamos solo las de art0, por eso comentamos la carga de art
			/*
			#cargamos las secundarias
			foreach($idarticulos as $idArt){
				#si aun no tenemos la imagen, un idart puede estar varias veces deido a las lavariantes
				if(empty($images[$idArt])){
					$name_archive = $this->imagesDir ."/LINES/". $idArt.".jpg";
					if (file_exists($name_archive)) {
						$images[$idArt]   ="/". $name_archive;
					}
				}
			}
			*/
		}else{
			$article->img  = "/themes/". \Config::get("app.theme") . "/img/items/no_photo.png";

		}

		$article->images = $images;
		$data["section"]= $section;
		$data["variantes"]= $variantes;
		$data["tallasColores"]= $tallasColores;
		$data["article"]= $article;
		$data["precioArticulos"] = $precioArticulos;
		#es neceario para lso articulos realcionados
		$data["iva"]= $iva;


		#anterior y siguiente  lo haremos por id, los articulos estan ordenados al reves
		$anterior = FgArt0::select("ID_ART0, MODEL_ART0")->where("id_art0",">", $article->id_art0 )->Activo()->where("SEC_ART0", $article->sec_art0)->orderby("id_art0")->first();

		if(!empty($anterior)){
			$data["data"]["previous"]= route("article", ["idArticle" => $anterior->id_art0, "friendly" =>\Str::slug($anterior->model_art0)]);
		}
		$siguiente = FgArt0::select("ID_ART0, MODEL_ART0")->where("id_art0","<", $article->id_art0 )->Activo()->where("SEC_ART0", $article->sec_art0)->orderby("id_art0","DESC")->first();

		if(!empty($siguiente)){
			$data["data"]["next"]= route("article", ["idArticle" => $siguiente->id_art0, "friendly" =>\Str::slug($siguiente->model_art0)]);
		}


			return View::make('front::pages.articles.article', $data);
	}

	public function getTallasColoresFicha(){
		$tallaColores = request("tallaColor",[]);
		$idArt0 = request("idArt0");
		\Tools::exit404IfEmpty($idArt0);

		#llegara un array de tallas colores, la idea es que devolvamos información de las tallas colores que vengan vacios, de esta manera solo recargaremos los que no estan seleccionados
		$fgart0 = new FgArt0();
		$fgart0 =  $fgart0->select("FGART.ID_ART, FGART.PVP_ART, FGART.STK_ART, DP_STOCK_PROD.STOCK, FGART_VARIANTES.ID_VARIANTE, FGART_VARIANTES.NAME_VARIANTE, FGART_VALVARIANTES.ID_VALVARIANTES, FGART_VALVARIANTES.VALOR_VALVARIANTE")
		->Activo()
		->where("ID_ART0", $idArt0)
		->JoinArt()->ArtActivo()
		->JoinLineasVariantes()->JoinValVariantes()->JoinVariantes()->orderby("FGART_VARIANTES.ID_VARIANTE, FGART_VALVARIANTES.ORDEN ");

		#bucle para filtrar por los que han sido seleccionados
		foreach($tallaColores as $keyTallaColor => $tallaColor){
			#puede venir el valor de talla color en negativo debido a que se usa el negativo para indicar que no hay stock
			if($tallaColor<0){
				$tallaColor = -1 * $tallaColor;
			}
			#bucle para filtrar por los que han sido seleccionados
			if(!empty($tallaColor)){
				$fgart0 = $fgart0->join(DB::raw("(SELECT ID_ART FROM  fgart ART_". $keyTallaColor ."
					JOIN FGART_LINEASVARIANTES LINEASVARIANTES_". $keyTallaColor ." ON LINEASVARIANTES_". $keyTallaColor .".ID_FGART  =  ART_". $keyTallaColor .".ID_ART
					WHERE  LINEASVARIANTES_". $keyTallaColor .".ID_VALVARIANTES = '$tallaColor'  AND emp_art = '". \Config::get("app.emp") ."' GROUP BY ID_ART) T"), "T.ID_ART  = FGART.ID_ART " );

			}
		}
		$fgart0 = $fgart0->leftjoin("DP_STOCK_PROD", " DP_STOCK_PROD.EMP_ART = FGART.EMP_ART AND DP_STOCK_PROD.ID_ART = FGART.ID_ART");

		$valvariantes =$fgart0->get();

		$tallasColoresRespuesta = [];


		foreach($valvariantes as $valvariante){


				#Devolveremos solo los que no han sido filtrados, para quer se recarguen los selects
				if(empty($tallaColores[$valvariante->id_variante])){
					if(empty($tallasColoresRespuesta[$valvariante->id_variante])){
						$tallasColoresRespuesta[$valvariante->id_variante] = [];
					}
					$tallasColoresRespuesta[$valvariante->id_variante][$valvariante->id_valvariantes] = $valvariante;
				}

		}


		return $tallasColoresRespuesta;
	}



/* FIN FICHA ARTICULOS */


	#Llamadas por react a Json

	public function getArticles($lang){


		Config::set('app.locale', $lang);

		$order = request('order', 'ID_ART0');
		$orderDirection = request('order_dir', 'desc');

		$fgArt0 = FgArt0::select("ID_ART0,   max(MODEL_ART0) MODEL_ART0, max(PVP_ART0) PVP_ART0")->ArtActivo()->JoinArt()->groupby("ID_ART0")->orderBy($order, $orderDirection);

		$fgArt0 = $this->setFilters($fgArt0);

		$articles = $fgArt0->paginate($this->numElements);

		header('Content-Type: application/json');
		#genero el urlArt con código de articulo vacio por que se lo concatenaré en react

		 $this->setInfoArticles($articles) ;

		echo json_encode($articles);
	}
	#Se pasa el objeto fgart y un array de filtros que se deben omitir, por ejemplo si estamos buscando ortsec no podemos filtar por el
	private function setFilters($fgArt0, $omitir = []){

		#se comprueba que el art0 este activo
		$fgArt0 = $fgArt0->Activo();

		if(request("sec") && !in_array("sec", $omitir)){
			$fgArt0 = $fgArt0->where("SEC_ART0",  request("sec"));
			#no tiene sentido buscar por ortsec si ya se hace una busqueda más restrictiva
		}elseif(request("ortsec") && !in_array("ortsec", $omitir)){
			$fgArt0 = $fgArt0->JoinOrtsec1()->where("SUB_ORTSEC1",  0)->where("LIN_ORTSEC1",  request("ortsec"));
		}

		if(request("marca") && !in_array("marca", $omitir)){
			$fgArt0 = $fgArt0->where("MARCA_ART0",  request("marca"));
		}

		if(request("familia") && !in_array("familia", $omitir)){
			$fgArt0 = $fgArt0->where("FAMART_ART0",  request("familia"));
		}

		if(request("search") && !in_array("search", $omitir)){
			#FALTA que hacer bien la query, con indice y que no tenga en cuenta mayusculas ni acentos
			$fgArt0 = $fgArt0->where("DES_ART0","like","%". strtoupper(request("search")) . "%"  );
			#no tiene sentido buscar por ortse si yase hace una busqueda más restrictiva
		}

		if(!empty(request("tallaColor")) && !in_array("tallaColor", $omitir)){
			foreach(request("tallaColor") as $key => $val ){
				#el array trae elementos vacios, por lo que solo hay que coger los que tienen info
				#comprobamos que sea numero para evitar injection
				#comprobamos que se pueda filtrar esta Variante
				if(!empty($val) && is_numeric($val) && !in_array("tallaColor_$key", $omitir)){

					$fgArt0 = $fgArt0->join(DB::raw("(SELECT IDART0_ART FROM  fgart ART_". $key ."
					JOIN FGART_LINEASVARIANTES LINEASVARIANTES_". $key ." ON LINEASVARIANTES_". $key .".ID_FGART  =  ART_". $key .".ID_ART
					WHERE BAJAT_ART='N' AND  LINEASVARIANTES_". $key .".ID_VALVARIANTES = '$val'  AND emp_art = '". \Config::get("app.emp") ."' GROUP BY IDART0_ART) T"), "T.IDART0_ART  = FGART0.ID_ART0 " );


					/*
					$fgArt0 = $fgArt0->join("FGART as ART_".$key ," ART_".$key.".EMP_ART = FGART0.EMP_ART0 AND   ART_".$key.".IDART0_ART  = FGART0.ID_ART0")

					->join("FGART_LINEASVARIANTES as LINEASVARIANTES_". $key ,"LINEASVARIANTES_". $key.".ID_FGART  =  ART_".$key.".ID_ART")
					->where("LINEASVARIANTES_".$key.".ID_VALVARIANTES", $val);
					*/
				}

			}


		}

		#NO VAMOS A CONTROLAR SI HAY STOCK, SIEMPRE SE MOSTRARAN TODOS LOS PRODUCTOS EN EL LISTADO Y UNA VEZ DENTRO SI QUE INDICAMOS SI HAY O NO STOCK.
		#CONTROLAR EL STCOK EN EL GRID ES UNA TAREA COSTOSA QUE PUEDE REDUCIR LA VELOCIDAD DE PROCESO




		return $fgArt0;
	}

	public function getOrtSec(){
		$fgArt0 = new fgArt0();
		$fgArt0 = $fgArt0->select("MAX(DES_ORTSEC0) DES_ORTSEC0, LIN_ORTSEC0, COUNT(LIN_ORTSEC0) AS CUANTOS")->Activo()->JoinOrtsec1()->JoinOrtsec0()->groupby("LIN_ORTSEC0");

		$fgArt0 = $this->setFilters($fgArt0, ["ortsec","sec"]);

		return $fgArt0->get();
	}

	public function getSec(){
		$fgArt0 = new fgArt0();

		$fgArt0 =$fgArt0->select("MAX(DES_SEC) DES_SEC, COD_SEC, COUNT(COD_SEC) AS CUANTOS")->Activo()->joinSec()->groupby("COD_SEC");
		$fgArt0 = $this->setFilters($fgArt0,["sec"]);
		return $fgArt0->get();

	}

	public function getMarcas()
	{
		$fgArt0 = new FgArt0();

		$fgArt0 = $fgArt0->select("MAX(DES_MARCA) DES_MARCA, MARCA_MARCA, COUNT(MARCA_MARCA) AS CUANTOS")->Activo()->joinMarca()->groupby("MARCA_MARCA");

		$fgArt0 = $this->setFilters($fgArt0, ["marca"]);

		return $fgArt0->get();
	}

	public function getFamilias()
	{
		$fgArt0 = new FgArt0();

		$fgArt0 = $fgArt0->select("MAX(DES_FAMART) DES_FAMART, COD_FAMART, COUNT(COD_FAMART) AS CUANTOS")->Activo()->joinFamilia()->groupby("COD_FAMART");

		$fgArt0 = $this->setFilters($fgArt0, ["familia"]);

		return $fgArt0->get();
	}

	public function getTallasColores(){

		$tallasColores = [];
		$variantes = FgArt_Variantes::select("FGART_VARIANTES.ID_VARIANTE")->orderby("NAME_VARIANTE")->get();

		foreach ($variantes as $variante){
			$fgArt0 = new fgArt0();
			$fgArt0 = $this->setFilters($fgArt0,["tallaColor_". $variante->id_variante]);
			$fgArt0 =  $fgArt0->where("FGART_VARIANTES.ID_VARIANTE", $variante->id_variante);

			$elementos =  $fgArt0->getTallaColor()->get();

			if(count($elementos)>0){

				$tallasColores[] =  $elementos ;

			}

		}

		return $tallasColores;
	}

	/* FUNCIONES DE CARRITO DE ARTICULOS */

	public function addArticle(){

		$this->loadArticleCart();

		$idArt0 = request("idArt0");
		$tallaColores = request("tallaColor", []);
		$units = request("units", 1);
		#llegara el idart y  un array de tallas colores, con estos valores se debe identificar al articulo concreto y obtener su idart
		$fgart0 = new FgArt0();
		$fgart0 =  $fgart0->select("FGART.ID_ART")
		->where("ID_ART0", $idArt0)
		->JoinArt();

		#si vienen tallas y colores
		if(count($tallaColores) >0){
			$fgart0 =  $fgart0->JoinLineasVariantes()->JoinValVariantes()->JoinVariantes()->groupby("FGART.ID_ART ");
			#bucle para filtrar por los que han sido elegidos.
			foreach($tallaColores as $keyTallaColor => $tallaColor){
				#bucle para filtrar por los que han sido elegidos.
				if(!empty($tallaColor)){
					#puede venir negativo por que hay varias variantes y una de ellas puede tener valores sin stock
					if($tallaColor <0){
						$tallaColor=  -1 * $tallaColor;
					}

					$fgart0 = $fgart0->join(DB::raw("(SELECT ID_ART FROM  fgart ART_". $keyTallaColor ."
						JOIN FGART_LINEASVARIANTES LINEASVARIANTES_". $keyTallaColor ." ON LINEASVARIANTES_". $keyTallaColor .".ID_FGART  =  ART_". $keyTallaColor .".ID_ART
						WHERE  LINEASVARIANTES_". $keyTallaColor .".ID_VALVARIANTES = '$tallaColor'  AND emp_art = '". \Config::get("app.emp") ."' GROUP BY ID_ART) T"), "T.ID_ART  = FGART.ID_ART " );

				}
			}
		}
		#Si no vienen tallas y colores, solo deberáimso recibir un resultado, si se reciben más es que hay un error.

		$art = $fgart0->get();

		#debe haber solo un articulo que cumpla con las lineas variantes, si no hay ninguno o hay mas de uno es que algo ha ido mal
		if(count($art) == 0 or count($art) > 1){
			return $this->response(false);
		}
			$success = $this->addArticleCart($art[0]->id_art,$units);
		$this->saveArticleCart();
		return	$this->response($success);
	}

	public function changeUnitsArticleCart($id_art,$units){
		$this->loadArticleCart();
		$success = $this->addArticleCart($id_art,$units);
		$this->saveArticleCart();
		return	$this->response($success);

	}

	public function deleteArticle( ){

		$this->loadArticleCart();

		$success = $this->deleteArticleCart( request("idArt"));
		$this->saveArticleCart();
		return $this->response($success);
	}



	private function addArticleCart($idart, $units){

		if(!empty($idart) && !empty($units)  ){

			#comprobamos stock
			#DE MOMENTO NO SE COMPRUEBA STOCK
			$check=true;
			if($check){

				#lo añadimos al carrito
				$this->articleCart[$idart] = $units;
				return true;
			}
		}

		return false;
	}

	public function deleteArticleCart($idart){

		if(!empty($idart) ){
			unset($this->articleCart[$idart]) ;
			return true;
		}else{
			return false;
		}

	}

	public function loadArticleCart(){
		$cookieName= "articleCart".Session::get('user.cod');
		$fgart0 =new FgArt0();
		#si no hay nada que sea un array vacio
		$this->articleCart = json_decode( Cookie::get($cookieName),true )??[];

		$articles = array();

			#se deberá buscar por fgart y no por fgart0, pero de momento lo pongo así
			$articlesTmp = $fgart0->select("FGART.ID_ART, FGART0.ID_ART0, FGART0.MODEL_ART0, FGART.PVP_ART, FGART.SEC_ART, FGART.COD_ART, FGART.STK_ART,
			PERSONALIZADO_ART0,
			case stk_art
					when 'N' then 10
					when 'S' then nvl(DP_STOCK_PROD.STOCK,0)
				end
				as stock")
							->joinart()
							->ArtActivo()
							->leftjoin("DP_STOCK_PROD", " DP_STOCK_PROD.EMP_ART = FGART.EMP_ART AND DP_STOCK_PROD.ID_ART = FGART.ID_ART")
							->wherein("FGART.ID_ART", array_keys($this->articleCart) )->get();

			foreach($articlesTmp as $article){
				$articles[$article->id_art] = $article;
			}


		return $articles;

		}


	public function saveArticleCart(){
		$cookieName= "articleCart".Session::get('user.cod');
		Cookie::queue( $cookieName,  json_encode($this->articleCart), 60 * 24 * 7 );
	}

	private function response($success = true){

		$response = array("status" => $success? "success" :  "error" ,
						 "articleCart" => $this->articleCart,
						 "errorMsg" =>  $this->errorMsg
						);


		#se debe lanzar el response para que la cookie se grabe
		response();
		return $response;

	}


	public function showArticleCart(){


		$user_cod =Session::get('user.cod');


		$articles = $this->loadArticleCart();
		$fgart0 = new FgArt0();
		$tmpTallasColores = $fgart0->select("id_art, valor_valvariante, name_variante ")
								->joinart()
								->ArtActivo()
								->JoinLineasVariantes()->JoinValVariantes()->JoinVariantes()
								->wherein("id_art", array_keys($articles) )->orderby("orden")->get();
		$tallasColores = array();
		foreach($tmpTallasColores  as $tallaColor){
			if(empty($tallasColores[$tallaColor->id_art])){
				$tallasColores[$tallaColor->id_art] = [];
			}
			$tallasColores[$tallaColor->id_art][$tallaColor->name_variante] = $tallaColor->valor_valvariante;
		}

		#Carga de imagenes
		foreach($articles as $idArt => $article){
			$articles[$idArt]->image = $this->getImageArt($idArt,$article->id_art0);
		}


		$data["tallasColores"] =$tallasColores;
		$data["articles"] = $articles;

		$data["units"] = $this->articleCart;
		$data["gastosEnvio"] =0;
		$data["totalSeguro"] =0;
		/*

		$fxClid = new FxClid();
		$data['address'] = $fxClid->getForSelectHTML($user_cod);



		#IVA aplicable
		$data["ivaAplicable"] =  $this->ivaAplicable();



		#gastos envio
		$paymentcontroller = new PaymentsController();
		$codd_clid = !empty($data['address']) ? head(array_keys($data['address'])) : null;
		$gastosEnvio = $paymentcontroller->calc_web_gastos_envio ($lots,$codd_clid);

		$data["gastosEnvio"] = $gastosEnvio + round($gastosEnvio * $data["ivaAplicable"],2);
		$data["lots"] = $lots;
		#Calculos lotes seguros
		$total_lotes = 0;
		foreach($lots as $lot){
			$total_lotes +=  $lot->impsalhces_asigl0;
		}

		$porcentajeSeguro =  \Config::get("app.porcentaje_seguro_envio");
		$seguro = round($total_lotes * $porcentajeSeguro / 100,2);
		$tax_seguro =  round($seguro * $data["ivaAplicable"],2 );
		$data["totalSeguro"] = round($seguro + $tax_seguro,2);
		$data["totalLotes"] = $total_lotes;

		*/
		#cargamos el iva
		$cartController = new CartController();
		$data['iva'] =  $cartController->ivaAplicable();

		return View::make('front::pages.panel.articlesCart', $data);
	}

	private function getImageArt($idArt,$idArt0){

		$imgArt = $this->imagesDir."/LINES/". $idArt.".jpg";

		if (file_exists($imgArt)) {
			return "/". $imgArt;
		}else{
			$imgArt0 = $this->imagesDir . "/". $idArt0.".jpg";
			if (file_exists($imgArt0)) {
				return "/". $imgArt0;
			}
		}

		return "/themes/" . \Config::get("app.theme")."/img/items/no_photo.png";
	}


	/* FUNCIONES DE CARRITO DE ARTICULOS */


	public function showShoppingOrders()
	{
		$user_cod = Session::get('user.cod');

		//Todos los articulos de los pedidos
		$articlesInOrders = FgArt0::select('id_art, id_art0, model_art0, anum_pedc0, num_pedc0, base_pedc0, impiva_pedc0, total_pedc0, fecaccpto_pedc0', 'imp_pedc1', 'impiva_pedc1', 'cant_pedc1')
		->joinArt()->joinFgPedc1()->joinFgPedc0()
		->where([
			['cod_pedc0', $user_cod],
			['acceppto_pedc0', 'S']
		])
		->orderBy('fecha_pedc0', 'desc')
		->get();

		//Id's articulos
		$idArticles = $articlesInOrders->pluck('id_art')->unique();

		//Caracteristicas
		$tallasColores = FgArt0::select("id_art, valor_valvariante, name_variante")
		->joinart()->ArtActivo()
		->JoinLineasVariantes()->JoinValVariantes()->JoinVariantes()
		->wherein("id_art", $idArticles)->orderby("orden")->get();

		//Agrupamos todo dentro de array de pedidos
		$shoppingOrders = [];
		foreach($articlesInOrders as $article){
			$article->image = $this->getImageArt($article->id_art, $article->id_art0);
			$article->tallasColores = $tallasColores->where('id_art', $article->id_art);

			$shoppingOrders["{$article->anum_pedc0}/{$article->num_pedc0}"]['info'] = $article->only(['base_pedc0', 'impiva_pedc0', 'total_pedc0', 'fecaccpto_pedc0']);
			$shoppingOrders["{$article->anum_pedc0}/{$article->num_pedc0}"]['articles'][] = $article;
		}

		return view('front::pages.panel.shopping_orders', compact('shoppingOrders'));
	}


}
