<?php

namespace App\Http\Controllers\V5;
use View;
use Route;
use Illuminate\Support\Facades\Request as Input;

use Session;
use App\Http\Controllers\Controller;

# Modelos

use App\Models\Subasta;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgLicit;
use App\Models\V5\FxSec;
use App\Models\V5\FxSubSec;
use App\Models\V5\FgSub;
use App\Models\V5\Sub_AucHouse;
use App\Models\V5\Sub_AucHouse_Desc;
use App\Models\V5\FgCaracteristicas;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Providers\ToolsServiceProvider as Tools;
use Illuminate\Support\Facades\Config;

use App\libs\SeoLib;
use stdClass;

class LotListController extends Controller
{

    var $lotsPerPage = 24 ;
	var $actualPage = 1;
	var $totalLots=0;
	var $landing_page = null;

/*
     #entrada por URL a la petición de mostrar lotes segun busqueda de texto
    public function getLotsListSearch( $search ){
          return $this->lotList( null,null, null,  null, null, $search);
    }
*/

    #entrada por URL a la petición de mostrar lotes por categoria
    public function getLotsListCategory($keyCategory ){
        return $this->getLotsListSubSection( $keyCategory, null, null);
	}

	public function getLotsListSection(  $keyCategory, $keySection ){
		return $this->getLotsListSubSection( $keyCategory, $keySection, null);
	}

    public function getLotsListSubSection(  $keyCategory, $keySection, $keySubSection ){
        $lang = \Tools::getLanguageComplete(\Config::get('app.locale'));
        $emp = Config::get("app.emp");
        $gemp = Config::get("app.gemp");

        #buscamos la categoria
        $fgortsec0 = new FgOrtsec0();
		$linSec = $fgortsec0->getLinFgOrtsec( $keyCategory);

         \Tools::exit404IfEmpty($linSec);
        #puede venir vacio si la llamada la hacen desde lotslistCategory
        if(!empty($keySection)){
            $fxsec = new FxSec();
            $sec = $fxsec->select("COD_SEC")->JoinLangFxSec($lang)->where('NVL(FXSEC_LANG.KEY_SEC_LANG, FXSEC.KEY_SEC)', $keySection)->where('gemp_sec', $gemp)->first();
             \Tools::exit404IfEmpty($sec);
            $codSec = $sec->cod_sec;
        }else{
            $codSec = null;
		}

		 #puede venir vacio si la llamada la hacen desde lotslistCategory o LotlistSection
		 if(!empty($keySubSection)){
            $fxsubsec = new FxSubSec();
            $subsec = $fxsubsec->select("COD_SUBSEC")->JoinLangFxSubSec($lang)->where('NVL(FXSUBSEC_LANG.KEY_SUBSEC_LANG, FXSUBSEC.KEY_SUBSEC)', $keySubSection)->where('gemp_subsec', $gemp)->first();
            \Tools::exit404IfEmpty($subsec);
            $codSubSec = $subsec->cod_subsec;
        }else{
            $codSubSec = null;
        }

        return $this->lotList( $linSec, $codSec,$codSubSec, null,  null);
    }
	public function getCustomListSubSection($keySubSection){
		$lang = \Tools::getLanguageComplete(\Config::get('app.locale'));

        $gemp = Config::get("app.gemp");

		$this->landing_page =  FxSubSec::JoinLangFXSUBSEC()
		->select("cod_subsec")
		->addSelect("NVL(FXSUBSEC_LANG.META_TITULO_SUBSEC_LANG, FXSUBSEC.META_TITULO_SUBSEC) META_TITULO_SUBSEC")
		->addSelect("NVL(FXSUBSEC_LANG.META_DESCRIPTION_SUBSEC_LANG, FXSUBSEC.META_DESCRIPTION_SUBSEC) META_DESCRIPTION_SUBSEC")
		->addSelect("NVL(FXSUBSEC_LANG.META_CONTENIDO_SUBSEC_LANG, FXSUBSEC.META_CONTENIDO_SUBSEC) META_CONTENIDO_SUBSEC")
		->addSelect("NVL(FXSUBSEC_LANG.DES_SUBSEC_LANG, FXSUBSEC.DES_SUBSEC) DES_SUBSEC")
		->addSelect("NVL(FXSUBSEC_LANG.KEY_SUBSEC_LANG, FXSUBSEC.KEY_SUBSEC) KEY_SUBSEC")
		->where('NVL(FXSUBSEC_LANG.KEY_SUBSEC_LANG, FXSUBSEC.KEY_SUBSEC)', $keySubSection)
		->where('gemp_subsec', $gemp)
		->first();
		\Tools::exit404IfEmpty($this->landing_page);


		$linSec = $codSubSec= $this->landing_page->cod_subsec;

		return $this->lotList( $linSec, null,$codSubSec, null,  null);
	}

    public function getLotsListAllCategories (){

        return $this->lotList( null,null, null,null,  null);
    }

	#entrada por url a la petición de mostrar lotes de subasta
	#http://www.newsubastas.test/es/subastaTest/subasta-presencial_SUBALIAW-001
    public function getLotsList( $texto, $codSub,  $refSession ){

        return $this->lotList( null, null, null, $codSub,  $refSession);
    }

    #carga el listado de lotes en la blade
    public function lotList( $category, $section, $subsection,  $codSub,  $refSession, $search = NULL)
	{
		abort_if(config('app.restrictAccessIfNoSession', 0) && !session('user.cod'), 401);

        $lang = \Tools::getLanguageComplete(\Config::get('app.locale'));
		$fgasigl0 = new FgAsigl0();

        $tipos_sub = array("O" =>trans(\Config::get('app.theme').'-app.lot_list.online_auction'), "W" => trans(\Config::get('app.theme').'-app.lot_list.face_auction'), "V" => trans(\Config::get('app.theme').'-app.lot_list.direct_sale'), "P" => trans(\Config::get('app.theme').'-app.lot_list.permanent_auction'), "E" => trans(\Config::get('app.theme').'-app.lot_list.special_auction'), "M" => trans(\Config::get('app.theme').'-app.lot_list.make_offer'), "I" => trans(\Config::get('app.theme').'-app.lot_list.reverse_auction'));

		$bread = array();

        if(!empty($codSub) && !empty($refSession)){
            $fgsub = new Fgsub();
            #cargamos información de la subasta
			$auction = $fgsub->getInfoSub(  $codSub, $refSession);

			 \Tools::exit404IfEmpty($auction);

            #recogemos los filtros pasados por get
			$filters = $this->getInputFilters($auction->tipo_sub);

			#url de la subasta
			#si tienen el web config gridAllSessions no se filtra por session, debemos hacer una url igual para todas las sesiones por eso cogemos la descripción subasta y la session 001
			if(!empty(\Config::get("app.gridAllSessions") )){
				$url = route("urlAuction",["texto" => \Str::slug($auction->des_sub), "cod" => $codSub, "session" => '001']);

			}else{
				$url = route("urlAuction",["texto" => \Str::slug($auction->name), "cod" => $codSub, "session" => $refSession]);
			}

			/* genera licitador */
			if (Session::has('user')) {
				$subasta  = new Subasta();
				$subasta->cod   = $codSub;
				$subasta->cli_licit = Session::get('user.cod');
				$subasta->rsoc      = Session::get('user.name');
				$licit = $subasta->checkLicitador();
			}
			/* fin genera licitador */


        }#lotes de categoria
        else{
			#no hay subasta
            $auction = null;

            #recogemos los filtros pasados por get y definimos algunos campos  por defecto si no han pasado nada por get
            $filters = $this->getInputFilters(null, $category, $section, $subsection, $search);


            $url = route("allCategories");
		}


		#conteo de carcateriasticas para uan subasta
		$featuresCount = $this->getfeaturesFilters($filters, $codSub,  $refSession);



         #carga del listado de filtros activos y la cantidad de lotes que contienen
        $numActiveFilters = $this->numActiveFilters($codSub,  $refSession, $filters);


        #listado de categorias
        $fgortsec0 = new FgOrtsec0();

        $categories = $fgortsec0->GetAllFgOrtsec0()->get()->toarray();

        #información de la categoria seleccionada
        $infoOrtsec = $fgortsec0->GetInfoFgOrtsec0($filters["category"]);

        #cargamos las secciones que dependen de la categoria
        $sec = new FxSec();
        $sections = $sec->GetSecFromLinFxsec($filters["category"]);
		$infoSec =  $sec->GetInfoFxsec($filters["section"]);

		#cargamos las subsecciones que dependen de la seccion
		$subsec = new FxSubSec();
        $subSections = $subsec->GetSubSecFromSec($filters["section"]);
		$infoSubSec =  $subsec->GetInfoFxSubSec($filters["subsection"]);

		#cargamos los datos de la landing page ya que el id de subsección puede ser compartidos por las landings y puede cargar los de otra subsección
		if(!empty($this->landing_page)){
			$infoSubSec = $this->landing_page;
		}


		$seo_data = $this->lotListSeo($auction, $infoOrtsec, $infoSec, $infoSubSec,  $filters, $tipos_sub);


		$bread = $this->generateBreadCrumb($auction, $infoOrtsec, $infoSec, $infoSubSec);
		if(!empty( $bread) && !empty($bread[0]) && empty($seo_data->canonical)){
			$seo_data->canonical = $bread[0]["url"];
		}

		$features = FgCaracteristicas::getFeatures();
		if(\Config::get("app.paginacion_grid_lotes")){
			$this->actualPage = request('page',1);
			$this->lotsPerPage  = request('total');

			if(empty($this->lotsPerPage)){
				$this->lotsPerPage = head(Config::get('app.filter_total_shown_options'));
			}

			$lots = $this->getlots($category, $section, $subsection,  $codSub,  $refSession, $search);




			$paginator = new LengthAwarePaginator(range(1,$this->totalLots), $this->totalLots, $this->lotsPerPage, $this->actualPage,["path" => $url]);
			$paginator->appends(Input::except('page'));
		}else{
			$paginator = NULL;
			$lots = NULL;
		}

        $data = [
			#info necesaria para la carga de lotes
			"paginator" => $paginator,
			"codSub" => $codSub,
			"refSession" => $refSession,
            "tipos_sub" => $tipos_sub,
            "auction"    => $auction,
            "categories" => $categories,
            "sections"   => $sections,
            "subsections"   => $subSections,
            "url"        => $url,
            "filters"    => $filters,
            "infoOrtsec"   =>  $infoOrtsec,
            "infoSec"   =>  $infoSec,
            "infoSubSec"   =>  $infoSubSec,
            "numActiveFilters" => $numActiveFilters,
			"features" => $features,
			"featuresCount" => $featuresCount,
			"seo_data" => $seo_data,
			"bread" => $bread,
			#hay zonas de la web donde esperan un array llamado data
			"data" => array("seo"=>$seo_data),
			"lots" => $lots


        ];

		return \View::make('front::pages.grid', $data);

	}



	#carga los lotes por ajax
	public function GetAjaxLots(){

		$codSub = request("codSub");
		$refSession = request("refSession");
		$category = request("category");
		$section = request("section");
		$subsection = request("subSection");
		$this->actualPage = request('actualPage',1);
		$this->lotsPerPage  =  head(Config::get('app.filter_total_shown_options'));
		$lots = $this->getLots($category, $section, $subsection,  $codSub,  $refSession);
		if(empty($lots)){
			return "";
		}

        return \View::make('front::includes.grid.lots', ["lots"  => $lots, "actualPage" => $this->actualPage]);
	}

	#devuelve el numero de lotes historicos que hay, si devuelve mas de 0 se muestra el link de ver histórico
	public function  showHistoricLink(){

		$codSub = request("codSub");
		$refSession = request("refSession");
		$category = request("category");
		$section = request("section");
		$subsection = request("subSection");


        $lang = \Tools::getLanguageComplete(\Config::get('app.locale'));
		$fgasigl0 = new FgAsigl0();

		$fgasigl0 =  $fgasigl0->HistoricLotForCategory();

		#No es necesario el search ya que vendra por variables
		$search = "" ;

		#recogemos los filtros pasados por get y definimos algunos campos  por defecto si no han pasado nada por get
        $filters = $this->getInputFilters(null, $category, $section, $subsection, $search);


		$fgasigl0 = $fgasigl0->selectRaw("count(FGASIGL0.REF_ASIGL0) as numlots")
				->ActiveLotAsigl0();
		#los filtros siempre despues de la query principal ya que algunos joins dependen de otras tablas
		$fgasigl0 = $this->setFilters($fgasigl0, $filters);

		$lots =   $fgasigl0->first();


		return $lots->numlots;
	}

	public function setVarsLot($lots){

		foreach ($lots as $key => $item){

			$bladeVars = Array();
			$bladeVars["alt"] ="$item->descweb_hces1";

			$refLot = $item->ref_asigl0;
			#si  tiene el . decimal hay que ver si se debe separar
			if(strpos($refLot,'.')!==false){

				$refLot =str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $refLot);

				#si hay que recortar
			}elseif( \config::get("app.substrRef")){
				#cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
				#le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
				$refLot = substr($refLot,-\config::get("app.substrRef"))+0;
			}

			$bladeVars["titulo"] ="$refLot   -  $item->descweb_hces1";
			$bladeVars["descripcion"] =$item->desc_hces1;
			$bladeVars["hay_pujas"] = !empty($item->implic_hces1)? true : false;
			$bladeVars["maxPuja"] = \Tools::moneyFormat($item->implic_hces1);
			$bladeVars["cerrado"] = $item->cerrado_asigl0 == 'S'? true : false;
			$bladeVars["compra"] = $item->compra_asigl0 == 'S'? true : false;
			$bladeVars["subasta_online"] = ($item->tipo_sub == 'P' || $item->tipo_sub == 'O')? true : false;
			$bladeVars["subasta_venta"] = $item->tipo_sub == 'V' ? true : false;
			$bladeVars["subasta_web"] = $item->tipo_sub == 'W' ? true : false;
			$bladeVars["subasta_make_offer"] = $item->tipo_sub == 'M' ? true : false;
			$bladeVars["subasta_inversa"] = $item->tipo_sub == 'I' ? true : false;


			$bladeVars["subasta_abierta_P"] = $item->subabierta_sub == 'P'? true : false;
			$bladeVars["subasta_abierta_O"] = $item->subabierta_sub == 'O'? true : false;
			$bladeVars["retirado"] = $item->retirado_asigl0 !='N'? true : false;
			$bladeVars["sub_historica"] = $item->subc_sub == 'H'? true : false;
			$bladeVars["remate"] = $item->remate_asigl0 == 'S'? true : false;
			$bladeVars["awarded"]	 = \Config::get('app.awarded');
			$bladeVars["inicio_pujas_online"] = strtotime("now") > strtotime($item->fini_asigl0) ? true : false;
			// D = factura devuelta, R = factura pedniente de devolver
			$bladeVars["devuelto"] = ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R' || $item->cerrado_asigl0 == 'D') ? true : false;
			$bladeVars["precio_venta"] = \Tools::moneyFormat($item->implic_hces1);
			$bladeVars["desadjudicado"] = $item->desadju_asigl0 == 'S'? true : false;

			$bladeVars["webfriend"] = !empty($item->webfriend_hces1)? $item->webfriend_hces1 :  \Str::slug(strip_tags($item->descweb_hces1));
			$bladeVars["precio_salida"] = \Tools::moneyFormat(!empty($item->impsalweb_asigl0) ? $item->impsalweb_asigl0 : $item->impsalhces_asigl0);
			#debe haber la variable number_bids_lotlist a 1 en webconfig para que devuelva el numero de pujas y de licitadores
			$bladeVars["bids"] = !empty($item->bids)? $item->bids : 0;
			$bladeVars["licits"] = !empty($item->licits)? $item->licits : 0;
			$bladeVars["numFotos"] = $item->totalfotos_hces1?? 0;
			$bladeVars["end_session"] = strtotime("now")  > strtotime($item->end);

			$url = "";
			//Si no esta retirado tendrá enlaces
			if(!$bladeVars["retirado"]  && !$bladeVars["devuelto"] ){
				$url_friendly = \Tools::url_lot($item->cod_sub,$item->id_auc_sessions,$item->name,$item->ref_asigl0,$item->num_hces1,$item->webfriend_hces1,$item->descweb_hces1);
				$url = "href='$url_friendly'";
			}
			$bladeVars["url"] = $url;

			$imageResolion = Config::get('app.lotlist_img', 'lote_medium');
			$bladeVars["img"] = Tools::url_img($imageResolion, $item->num_hces1, $item->lin_hces1);


			if(!Session::has('user')){
				$bladeVars["winner"] ="";
			}elseif ( Session::get('user.cod') == $item->cli_win_bid ){
					$bladeVars["winner"] = "winner";

			}else{
				$bladeVars["winner"] = "no_winner";
			}



			#descuentos y ofertas
			$bladeVars["oferta"] = $item->oferta_asigl0 == 2? true : false;
			$bladeVars["estimacion"] = $item->imptas_asigl0;
			if($item->oferta_asigl0 == 1 && $item->impsalhces_asigl0 >0 && $item->imptas_asigl0 > 0){
				$bladeVars["descuento"] =  round(((($item->imptas_asigl0 -  $item->impsalhces_asigl0)/$item->imptas_asigl0) *100), 0);
			}else{
				$bladeVars["descuento"] = "";
			}

			if(\Config::get("app.videoEnGrid")){
				$subastaModel = new subasta();
				$numLin = new stdClass();
				$numLin->num_hces1 = $item->num_hces1;
				$numLin->lin_hces1 = $item->lin_hces1;

				$bladeVars["videos"] = $subastaModel->getLoteVideos($numLin);

			}else{
				$bladeVars["videos"] = array();
			}

			if(\Config::get("app.subSecInGrid")){
				$bladeVars["subSec"] = $item->des_subsec;
			}

			$item->bladeVars = $bladeVars;

		}

		return $lots;
	}

    private function lotListSeo($auction, $infoOrtsec, $infoSec, $infoSubSec,  $filters, $tipos_sub){
		$seo_data = new stdClass();
		$seo_data->meta_content = "";
		if (isset($auction) && isset($auction->name)) {

			$seo_data->h1_seo=$auction->name;
			$seo_data->meta_title =$auction->webmetat_sub;
			$seo_data->meta_description =$auction->webmetad_sub;
				#datos para Open Graph
			$seo_data->openGraphImagen = \Tools::url_img_session("subasta_large", $auction->cod_sub, $auction->reference);
		}else{
			if(request("historic")){
				$seo_data->h1_seo = trans(\Config::get('app.theme').'-app.lot_list.historic_sold_lots');
				$seo_data->meta_title = trans(\Config::get('app.theme').'-app.lot_list.historic_sold_lots');
				$seo_data->meta_description = trans(\Config::get('app.theme').'-app.lot_list.historic_sold_lots');
			}else{
				$seo_data->h1_seo = trans(\Config::get('app.theme').'-app.lot_list.lots');
				if(Config::get('app.seo_in_Lot_list', 0)){
					$seo_data->meta_title =trans(\Config::get('app.theme').'-app.metas.title_lot_list');
					$seo_data->meta_description = trans(\Config::get('app.theme').'-app.metas.description_lot_list');
				}else{
					$seo_data->meta_title = trans(\Config::get('app.theme').'-app.lot_list.lots');
					$seo_data->meta_description = trans(\Config::get('app.theme').'-app.lot_list.lots');
				}


			}

			if(!empty($infoOrtsec)){

				if(!empty($infoSubSec)){
					$seo_data->h1_seo.= " ". ucfirst(mb_strtolower($infoSubSec->des_subsec));
					$seo_data->meta_content = !empty($infoSubSec->meta_contenido_subsec)? $infoSubSec->meta_contenido_subsec : $infoOrtsec->meta_contenido_ortsec0;
					$seo_data->meta_title = !empty($infoSubSec->meta_titulo_subsec)? $infoSubSec->meta_titulo_subsec : $infoOrtsec->meta_titulo_ortsec0;
					$seo_data->meta_description = !empty($infoSubSec->meta_description_subsec)? $infoSubSec->meta_description_subsec : $infoOrtsec->meta_description_ortsec0;
				}elseif(!empty($infoSec)){

					$seo_data->h1_seo.= " ". ucfirst(mb_strtolower($infoSec->des_sec));
					$seo_data->meta_content = !empty($infoSec->meta_contenido_sec)? $infoSec->meta_contenido_sec : $infoOrtsec->meta_contenido_ortsec0;
					if(Config::get('app.seo_in_section', 0)){
						$seo_data->meta_title = str_replace("[SEC]",$infoOrtsec->des_ortsec0. " ". $infoSec->des_sec ,trans(\Config::get('app.theme').'-app.metas.title_sec'));
						$seo_data->meta_description = str_replace("[SEC]",$infoOrtsec->des_ortsec0. " ". $infoSec->des_sec ,trans(\Config::get('app.theme').'-app.metas.description_sec'));

					}else{
						$seo_data->meta_title = !empty($infoSec->meta_titulo_sec)? $infoSec->meta_titulo_sec : $infoOrtsec->meta_titulo_ortsec0;
						$seo_data->meta_description = !empty($infoSec->meta_description_sec)? $infoSec->meta_description_sec : $infoOrtsec->meta_description_ortsec0;
					}
				}else{
					$seo_data->h1_seo.= " ". $infoOrtsec->des_ortsec0;
					$seo_data->meta_content = $infoOrtsec->meta_contenido_ortsec0;
					if(Config::get('app.seo_in_ortsec', 0)){
						$seo_data->meta_title = str_replace("[ORTSEC]",$infoOrtsec->des_ortsec0 ,trans(\Config::get('app.theme').'-app.metas.title_ortsec'));
						$seo_data->meta_description =  str_replace("[ORTSEC]",$infoOrtsec->des_ortsec0 ,trans(\Config::get('app.theme').'-app.metas.description_ortsec'));
					}else{
						$seo_data->meta_title = $infoOrtsec->meta_titulo_ortsec0;
						$seo_data->meta_description =  $infoOrtsec->meta_description_ortsec0;
					}
				}
			}
		}
		#Segun experto de Seo de motorflash, las paginas deben ser no index no follow, y el canonical debe ser el propio de la url
		if(Config::get('app.seoPaginacion')){
			if( !empty(request("page"))){
				$seo_data->noindex_follow = true;
				$seo_data->canonical = str_replace("http:","https:",\Request::url())."?page=".request("page");
			}
		}

        return $seo_data;
    }
	private function generateBreadCrumb($auction, $infoOrtsec, $infoSec, $infoSubSec){
		$bread = array();
		if (isset($auction) && isset($auction->name)) {
			$urlInfo =  route("urlAuctionInfo",["texto" => \Str::slug($auction->des_sub), "cod" => $auction->cod_sub, "lang" => \Config::get("app.locale")]);
			$bread[] = array("url" =>$urlInfo, "name" =>$auction->des_sub  );
		}else{
			$urlAllCategories =  route("allCategories");
				$bread[] = array("url" =>$urlAllCategories, "name" => trans(\Config::get('app.theme').'-app.lot_list.all_categories') );
			if(!empty($infoOrtsec)){
				$urlCategory =  route("category",[ "keycategory" => $infoOrtsec->key_ortsec0 ]);
				$bread[] = array("url" =>$urlCategory, "name" =>$infoOrtsec->des_ortsec0  );

				if(!empty($infoSec->key_sec)){
					$urlSection =  route("section",[ "keycategory" => $infoOrtsec->key_ortsec0 , "keysection" => $infoSec->key_sec]);
					$bread[] = array("url" =>$urlSection, "name" => ucfirst(mb_strtolower($infoSec->des_sec))  );
				}
				if(!empty($infoSubSec)){
					#si se trata de una  landing
					if(!empty($this->landing_page)){
						$urlSubSection =  route("landing-subastas",[ "subfam" =>  $infoSubSec->key_subsec]);
					}else{
						$urlSubSection =  route("subsection",[ "keycategory" => $infoOrtsec->key_ortsec0 , "keysection" => $infoSec->key_sec, "keysubsection" => $infoSubSec->key_subsec]);
					}
					$bread[] = array("url" =>$urlSubSection, "name" => ucfirst(mb_strtolower($infoSubSec->des_subsec))  );


				}
			}
		}

		return $bread;

	}




	private function getLots($category, $section, $subsection,  $codSub,  $refSession){


		#No es necesario el search ya que vendra por variables
		$search = "" ;

        $lang = \Tools::getLanguageComplete(\Config::get('app.locale'));
		$fgasigl0 = new FgAsigl0();

        #filtros activos y cantidad de lotes para cada uno


        \Tools::exit404IfEmpty(is_numeric($this->actualPage));

        #devuelve el listado de idorigin y la cantidad de lotes totales
        #lotes de subasta

        if(!empty($codSub) && !empty($refSession)){
            $fgsub = new Fgsub();
            #cargamos información de la subasta
			$auction = $fgsub->getInfoSub(  $codSub, $refSession);

			 \Tools::exit404IfEmpty($auction);
            #recogemos los filtros pasados por get
			$filters = $this->getInputFilters($auction->tipo_sub);
			#ponemos los filtros para que coja lso datosde la subasta
			$fgasigl0 = $fgasigl0->WhereAuction($codSub, $refSession);

        }#lotes de categoria
        else{
			#no hay subasta
			$auction = null;
			//$fgasigl0 =  $fgasigl0->whereraw("( FGASIGL0.CERRADO_ASIGL0='N' OR (FGASIGL0.CERRADO_ASIGL0='S' AND FGASIGL0.COMPRA_ASIGL0 ='S' AND FGHCES1.LIC_HCES1 ='N'  ) )");

			if(request('historic')){
				$fgasigl0 =  $fgasigl0->HistoricLotForCategory();
			}else{
				$fgasigl0 =  $fgasigl0->ActiveLotForCategory();
			}



			#recogemos los filtros pasados por get y definimos algunos campos  por defecto si no han pasado nada por get
            $filters = $this->getInputFilters(null, $category, $section, $subsection, $search);
		}


		$lotlist =  $this->getLotsBBDD($fgasigl0, $filters);

		$lots = null;
        #cargamos los datos de los lotes
        if(!empty($lotlist) && !empty($lotlist->refLots)){
			$this->totalLots = $lotlist->numLots;
			$fgasigl0 = new FgAsigl0();
			#hace falta cargar erl filtro de orden
            $fgasigl0 = $this->setFilterOrder($fgasigl0, $filters['order']);
			$lots = $fgasigl0->GetLotsByRefAsigl0( $lotlist->refLots)->get();


				#seteamos las variables para la blade
				$lots = $this->setVarsLot($lots);
		}

		return $lots;
	}



	private function getLotsBBDD($fgasigl0, $filters){

		$fgasigl0 = $fgasigl0->selectRaw("FGASIGL0.REF_ASIGL0, FGASIGL0.SUB_ASIGL0, count(emp_asigl0) over (partition by emp_asigl0) as numlots")
					->ActiveLotAsigl0();
		#los filtros siempre despues de la query principal ya que algunos joins dependen de otras tablas
        $fgasigl0 = $this->setFilters($fgasigl0, $filters);


        $lots =   $fgasigl0
                ->skip(($this->actualPage-1) *  $this->lotsPerPage)
                ->take($this->lotsPerPage)
                ->get();


         return $this->setRef($lots);
	}

	#cuenta el numero de caracteristicas
	private function getfeaturesFilters($filters, $codSub = null,  $refSession =null ){

		$fgasigl0 = new FgAsigl0();
		if(!empty($codSub) && !empty( $refSession)){
			$fgasigl0 =   $fgasigl0->WhereAuction($codSub, $refSession);
		}else{
			if(request('historic')){
				$fgasigl0 =  $fgasigl0->HistoricLotForCategory();
			}else{
				$fgasigl0 =  $fgasigl0->ActiveLotForCategory();
			}
		}
		$fgasigl0 = $fgasigl0->selectRaw("FGCARACTERISTICAS_VALUE.ID_CARACTERISTICAS_VALUE, FGCARACTERISTICAS_VALUE.IDCAR_CARACTERISTICAS_VALUE, count(FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE) total")
							->addSelect("MAX(NVL(VALUE_CAR_VAL_LANG, FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE)) VALUE_CARACTERISTICAS_VALUE")
							->ActiveLotAsigl0()
							->joinFgCaracteristicasAsigl0()
							->joinFgCaracteristicasHces1Asigl0()
							->joinFgCaracteristicasValueAsigl0()
							->JoinLangCaracteristicasValueAsigl0()
							->where("FGCARACTERISTICAS.FILTRO_CARACTERISTICAS", "S")
							->orderby("VALUE_CARACTERISTICAS_VALUE")
							->groupBy("FGCARACTERISTICAS_VALUE.ID_CARACTERISTICAS_VALUE, FGCARACTERISTICAS_VALUE.IDCAR_CARACTERISTICAS_VALUE,  FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE");

        $fgasigl0 = $this->setFilters($fgasigl0, $filters, false);
		$features = Array();

		foreach($fgasigl0->get() as $feature){
			if(empty($features[$feature->idcar_caracteristicas_value])){
				$features[$feature->idcar_caracteristicas_value] = Array();
			}
			$features[$feature->idcar_caracteristicas_value][$feature->id_caracteristicas_value] = $feature->toArray() ;

		}

        return $features;
	}

	public function setRef($lots){
        $lotList = new \StdClass();
        $lotList->refLots= "";
		$lotList->numLots = 0;
		$auctions = Array();
		if(empty($lots)){
			return null;
		}

        foreach($lots as $lot){
			if(!empty( $lot->numlots)){
				#es un count que esta en todos los lotes
				$lotList->numLots = $lot->numlots;
			}
			if(empty( $auctions[$lot->sub_asigl0])){
				$auctions[$lot->sub_asigl0] = Array();
			}
            $auctions[$lot->sub_asigl0][] = $lot->ref_asigl0;
		}
		#generamos las condiciones para que busque por subasta y referencias
		if(!empty($auctions)){
			$lotList->refLots.=" ( ";
			$or = "";
			foreach ($auctions as $cod_sub => $lots){
				$lotList->refLots.= "$or (sub_asigl0 = '$cod_sub' and ref_asigl0 in (". implode(",", $lots) .") )";
				$or = " OR ";
			}
			$lotList->refLots.=" )";
		}

        return $lotList;
    }
/*

    #monta el array de idOrigen
    private function setIdOrigins($lots){
        $lotList = new \StdClass();
        $lotList->idOrigins= Array();
        $lotList->numLots = 0;
        foreach($lots as $lot){
            $lotList->numLots = $lot->numlots;
            $lotList->idOrigins[] = $lot->idorigen_asigl0;
        }
        return $lotList;
    }
*/
    #######################################
    #  FUNCIONES DE FILTROS Y ORDENACION  #
    #######################################


        #generamos las variables por cada input que se espera
        public function getInputFilters($typeSub, $category = NULL, $section = NULL, $subsection = NULL,  $search = NULL){
            $filters = array();
			//lots per page
			/*
            $this->lotsPerPage = request('lotsPerPage',$this->lotsPerPage);
            $filters["lotsPerPage"] =$this->lotsPerPage;
*/
            #orden de los lotes
            $filters["order"] = request('order',\Config::get("app.default_order",'ref') );
            #busqueda por texto
            $filters["description"] = request('description', $search);
            #filtro pot categorias
			$filters["category"] = request('category', $category ?? \Config::get("app.default_category") );
            #filtro pot secciones
            $filters["section"] = request('section', $section);
            #filtro pot secciones
            $filters["subsection"] = request('subsection',$subsection);
            #filtro por tipo de subasta
            $filters["typeSub"] = request('typeSub',$typeSub);
			#filtro por referencia el lote
			$filters["reference"] =  request('reference');

             #filtro por caracteristica
			$filters['features'] = request('features');

             #filtro lotes vendidos
            $filters['award'] = request('award');
             #filtro lotes no vendidos
            $filters['noAward'] = request('noAward');
             #filtro lotes en curso
			$filters['liveLots'] = request('liveLots');

			#propietario de los lotes
			$filters['myLotsProperty'] = request('myLotsProperty');
			$filters['myLotsClient'] = request('myLotsClient');

			#filtro de precios
			$filters['prices'] = request('prices');

            return $filters;
        }

        # se llama a cada una de las funciones de filtros u ordenacion
        public function setFilters($fgasigl0, $filters, $organize = true){

			#este no es un filtro como tal, pero lo incluyo aquí para que se tenga en cuenta en todas las llamadas
			/* MOSTRAR SOLO LAS SUBASTAS QUE PUEDE VER EL USUARIO */
			if(\Config::get("app.restrictVisibility")){
				$fgasigl0 = $fgasigl0->Visibilidadsubastas(\Session::get('user.cod'));
			}

            if($organize){
                $fgasigl0 =  $this->setFilterOrder($fgasigl0, $filters['order']);
            }
            $fgasigl0 =  $this->setFilterReference($fgasigl0, $filters['reference']);
            $fgasigl0 =  $this->setFilterDescription($fgasigl0, $filters['description']);
            $fgasigl0 =  $this->setFilterCategory($fgasigl0, $filters['category']);
            $fgasigl0 =  $this->setFilterSection($fgasigl0, $filters["section"]);
            $fgasigl0 =  $this->setFilterSubSection($fgasigl0, $filters["subsection"]);
            $fgasigl0 =  $this->setFilterTypeSub($fgasigl0, $filters['typeSub']);
            $fgasigl0 =  $this->setFilterFeature($fgasigl0, $filters['features']);
            $fgasigl0 =  $this->setFilterPrices($fgasigl0, $filters['prices']);

			#Estado lotes
            $fgasigl0 =  $this->setFilterAward($fgasigl0, $filters['award']);
            $fgasigl0 =  $this->setFilterNoAward($fgasigl0, $filters['noAward']);
			$fgasigl0 =  $this->setFilterLiveLots($fgasigl0, $filters['liveLots']);

			#propietario o pujador lote
			$fgasigl0 =  $this->setFilterMyLotsProperty($fgasigl0, $filters['myLotsProperty']);
			$fgasigl0 =  $this->setFilterMyLotsClient($fgasigl0, $filters['myLotsClient']);

            return $fgasigl0;
        }

        #ordenamso segun el criterio
        public function setFilterOrder($fgasigl0, $order ){

            if($order == 'price_asc'){
                $fgasigl0 =  $fgasigl0->orderby(" greatest(FGASIGL0.IMPSALHCES_ASIGL0, FGHCES1.IMPLIC_HCES1)", "ASC");
            }elseif($order == 'price_desc'){
                $fgasigl0 =  $fgasigl0->orderby("greatest(FGASIGL0.IMPSALHCES_ASIGL0, FGHCES1.IMPLIC_HCES1)", "DESC");
            }elseif($order == 'name'){
                $fgasigl0 =  $fgasigl0->orderby("FGHCES1.TITULO_HCES1", "ASC");
            }elseif($order == 'nameweb'){
                $fgasigl0 =  $fgasigl0->orderby("dbms_lob.SUBSTR(FGHCES1.DESCWEB_HCES1, 50,1)", "ASC");
            }elseif($order == 'ffin' || $order == 'date_asc'){
                $fgasigl0 =  $fgasigl0->orderby("FGASIGL0.FFIN_ASIGL0", "ASC")->orderby("FGASIGL0.HFIN_ASIGL0", "ASC");
            }elseif($order == 'hbids'){#puja mas alta
                $fgasigl0 =  $fgasigl0->orderby("FGHCES1.IMPLIC_HCES1", "DESC");
            }elseif($order == 'mbids'){#mayor numero de pujas
                $fgasigl0 =  $fgasigl0->orderByRaw(" nvl( (select max(lin_asigl1) from fgasigl1 asig11 where asig11.emp_asigl1 = FGASIGL0.emp_asigl0 and asig11.sub_asigl1 = FGASIGL0.sub_asigl0 and  asig11.ref_asigl1 = FGASIGL0.ref_asigl0) , 0) DESC");
            }elseif($order == 'lastbids'){#ultima puja
                $fgasigl0 =  $fgasigl0->orderByRaw("nvl( (select max(fec_asigl1) from fgasigl1 asig11 where asig11.emp_asigl1 = FGASIGL0.emp_asigl0 and asig11.sub_asigl1 = FGASIGL0.sub_asigl0 and  asig11.ref_asigl1 = FGASIGL0.ref_asigl0) ,'1970-01-01') desc, ref_asigl0 ASC");
            }elseif($order == 'orden_asc'){
                $fgasigl0 =  $fgasigl0->orderby("FGHCES1.ORDEN_HCES1", "ASC");
            }elseif($order == 'orden_desc'){
                $fgasigl0 =  $fgasigl0->orderby("FGHCES1.ORDEN_HCES1", "DESC");
            }elseif($order == '360'){
				$fgasigl0 =  $fgasigl0->orderby("FGHCES1.IMG360_HCES1", "DESC");
			}elseif($order == 'media'){
				$fgasigl0 = $fgasigl0->selectRaw("case when (FGHCES1.VIDEOS_HCES1 = 'S' or (FGHCES1.TOTALFOTOS_HCES1 IS NOT NULL and FGHCES1.TOTALFOTOS_HCES1 > 1)) then '1' else '0' end as media");
				$fgasigl0 = $fgasigl0->orderby("media", "DESC");
			}elseif($order == 'award'){
				$fgasigl0 =  $fgasigl0->orderby("FGASIGL0.CERRADO_ASIGL0", "DESC")->orderBy("FGHCES1.LIC_HCES1",'DESC');
			}elseif($order == 'noaward'){
				$fgasigl0 =  $fgasigl0->orderby("FGASIGL0.CERRADO_ASIGL0", "DESC")->orderBy("FGHCES1.LIC_HCES1",'ASC');
			}
			elseif($order == 'date_desc'){
                $fgasigl0 =  $fgasigl0->orderby("FGASIGL0.FFIN_ASIGL0", "DESC")->orderby("FGASIGL0.HFIN_ASIGL0", "DESC");
            }
			elseif($order == 'olderLots'){#lotes que lleban más tiempo publicados
                $fgasigl0 =  $fgasigl0->orderby("FGASIGL0.FECALTA_ASIGL0", "ASC");
			}
			elseif($order == 'newerLots'){#lotes que lleban más tiempo publicados
                $fgasigl0 =  $fgasigl0->orderby("FGASIGL0.FECALTA_ASIGL0", "DESC");
			}
			elseif($order == 'auctionFirst'){#ordenar poniendo primero las subastas ONLINE
                $fgasigl0 =  $fgasigl0->orderby("FGSUB.TIPO_SUB", "ASC");
			}
			elseif($order == 'directSaleFirst'){#ordenar poniendo primero las venta directa
                $fgasigl0 =  $fgasigl0->orderby("FGSUB.TIPO_SUB", "DESC");
			}
			elseif($order == 'bestPrice'){#ordenar poniendo primero los precios más atractivos (Carlandia)
				#Ordena lso lotes por cual tiene un mejor precio de mercado, teniend oen cuenta el importe actual del lote
				#en pc se guarda el precio medio de motorflash y se usa para saber si el precio es atractivo, luego se saca un procentaje para no diferenciar coches caros de baratos
                $fgasigl0 =  $fgasigl0->orderby("(greatest(implic_hces1,impsal_hces1) - pc_hces1) /pc_hces1 ", "ASC");
			}
			elseif($order == 'posibleBestPrice'){
				#ordenar poniendo primero los precios más atractivos que podrían llegar a tener el coche,
				#usando los campos de precio de reserva para las subastas online y el preico minimo para la subasta de venta directa
				# y los comparamos con el precio que tenia en motorflash

                #hay que asegurar que haya valor, si no hay precio reserva ni precio minimo cojemos el precio de salida para que n ode error
				$fgasigl0 =  $fgasigl0->orderby("(nvl(nvl(impres_asigl0,imptas_asigl0),IMPSALHCES_ASIGL0)- pc_hces1) /pc_hces1 ", "ASC");
			}
			#Los siguientes tres metodos de ordenación solo sirven para las subastas W
			elseif($order == 'pending'){
				$fgasigl0 =  $fgasigl0->orderby("FGASIGL0.CERRADO_ASIGL0", "ASC")->orderby('auc."orders_start"', "desc");
			}
			elseif($order == 'finish'){
				$fgasigl0 =  $fgasigl0->orderby("FGASIGL0.CERRADO_ASIGL0", "DESC")->orderby('auc."orders_end"', "asc");
			}
			elseif($order == 'active'){
				$fgasigl0 =  $fgasigl0->selectRaw('case when (auc."orders_end" < SYSDATE or FGASIGL0.CERRADO_ASIGL0 = \'S\') then \'CF\' when auc."orders_start" > SYSDATE then \'BP\' else \'AA\' end as state');
				$fgasigl0 =  $fgasigl0->orderby("state", "ASC")->orderby('auc."orders_end"', "asc");
			}

			/**
			 * Carlandia exige que el segundo orden principal sea el de mejor descuento
			 * @todo Mirar de cambiar los tipos de orden a funciones para poder configurar el orden principal
			 */
			if(config('app.principal_order', false)){
				$fgasigl0 =  $fgasigl0->orderby("(greatest(implic_hces1,impsal_hces1) - pc_hces1) /pc_hces1 ", "ASC");
			}

            #SIEMPRE ORDENAM0S AL FINAL POR REFERENCIA,SI NO HAY FILTRO SE ORDENA POR DEFECTO Y SI LO HAY SE ORDENA  AUNQUE SEA COMO SEGUNDA O TERCERA ORDENACIÓN
            $fgasigl0 = $fgasigl0->orderby("FGASIGL0.REF_ASIGL0","ASC");


            return   $fgasigl0;

        }


        public function setFilterReference($fgasigl0, $reference ){
            if(!empty($reference) && is_numeric($reference)){
				#si tienen este config es que se deben quitar los deicmales y los códigos  mayores que \Config::get("app.substrRef")
				if(\Config::get("app.substrRef")){
					#quitamos decimales y cojemos como máximo 7 números, sumamos 0 para convertirlo en entero
					#$intRef= substr(floor($reference),-\Config::get("app.substrRef")) +0;
					$fgasigl0 =  $fgasigl0->whereRaw("( (FGSUB.TIPO_SUB!='W' AND  FGASIGL0.REF_ASIGL0 like '%$reference%') OR (FGSUB.TIPO_SUB='W' AND  ( FGASIGL0.REF_ASIGL0 = '$reference'  OR   FGASIGL0.REF_ASIGL0 like '$reference%') ) )");

				}else{
                	$fgasigl0 =  $fgasigl0->where("FGASIGL0.REF_ASIGL0", $reference);
				}
            }

            return  $fgasigl0;
        }
        #Falta montar la busqueda en multi idioma
        public function setFilterDescription($fgasigl0, $description ){
            if(!empty($description)){

				$description = $this->clearWords($description, \Tools::getLanguageComplete(Config::get("app.locale")));

                if(\Config::get("app.search_engine") ){
					$excludedWords =[];
					#sacamos listado de palabras excluidas
					foreach(  \DB::select("SELECT spw_word as excluded FROM ctx_stopwords") as $excludeWord){
						$excludedWords[]=$excludeWord->excluded;
					}

                    $words = explode(" ",$description);
                    $search="";
					$and="";


                    foreach($words as $key => $word ){

						#que no sea una palabra escluida por Catsearctch
                      	if(!in_array($word,$excludedWords)){
						#ponemos el comodin de busqueda % para que busque cualquier texto despues de la palabra y dolar $ para que busque por stem (raiz, origen de una palabra)

							$search .=$and. " $".$word."% ";
							$and=" AND ";
						}

					}

					if(!empty($search)){


						#si el idioma es el principal buscamos en hces1, si no en hces1_lang
						if(\Tools::getLanguageComplete(Config::get("app.locale")) == head(Config::get("app.language_complete")) ){
							#Es necesario poner las dos pipes || para concatenar la variable si no da error  número/nombre de variable no válid
							$fgasigl0 =  $fgasigl0->whereraw(" CATSEARCH(search_hces1,'<query><textquery grammar=\"context\">' || ? || '</textquery></query>',null) >0", [ $search]);
						}else{
							$fgasigl0 =  $fgasigl0->whereraw(" CATSEARCH(search_hces1_lang,'<query><textquery grammar=\"context\">' || ? || '</textquery></query>',null) >0", [ $search]);
							$lang = \Tools::getLanguageComplete(\Config::get('app.locale'));
							#OJO debe ser Join no left Join
							$fgasigl0 =  $fgasigl0->join('FGHCES1_LANG',"FGHCES1_LANG.EMP_HCES1_LANG = FGASIGL0.EMP_ASIGL0 AND FGHCES1_LANG.NUM_HCES1_LANG = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1_LANG.LIN_HCES1_LANG = FGASIGL0.LINHCES_ASIGL0 AND FGHCES1_LANG.LANG_HCES1_LANG = '" . $lang . "'");

						//	$fgasigl0 = $fgasigl0->JoinFghces1LangAsigl0();
						}
					}

                }else{
                    $words = explode(" ",$description);
                     \Tools::linguisticSearch();
                    $fgasigl0 = $fgasigl0->JoinFgOrtsecAsigl0();

                    foreach ($words as $word){
                        if(!empty($word) && strlen($word) > 1){

							if(\Config::get("app.searchField")){
								$field =\Config::get("app.searchField");
								$field_lang = "FGHCES1_LANG.".$field."_LANG";
								$fgasigl0 =  $fgasigl0->whereraw(" (REGEXP_LIKE (NVL(".$field_lang.", FGHCES1." .$field ."), ?))",[$word]);

							}else{
								$fgasigl0 =  $fgasigl0->whereraw(" (REGEXP_LIKE (NVL(FGHCES1_LANG.descweb_hces1_LANG, FGHCES1.descweb_hces1), ?) OR REGEXP_LIKE (NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1), ?) OR REGEXP_LIKE (DES_ORTSEC0, ?))",[$word,$word,$word]);

							}




                        }
                    }
                   $lang = \Tools::getLanguageComplete(\Config::get('app.locale'));
                   $fgasigl0 = $fgasigl0->JoinFghces1LangAsigl0($lang);
                }

            }
            return  $fgasigl0;
        }

        public function setFilterTypeSub($fgasigl0, $typeSub ){
            if(!empty($typeSub)){
                $fgasigl0 = $fgasigl0->where("FGSUB.TIPO_SUB", $typeSub);
            }
            return  $fgasigl0;
		}

		public function setFilterFeature($fgasigl0, $features ){


            if(!empty($features) && is_array($features)){
				#es necesario que se puedan buscar por mas de un filtro por lo que es necesario que haya un join con la tabla FGCARACTERISTICAS_HCES1 para cada filtro
				#usamos un indice autonumérico por que no podemos coger por seguridad el indice que viene de la web
				$index=1;
				foreach($features as $key => $feature){
					if(!empty($feature) && is_numeric($feature)){
						$fgasigl0 = $fgasigl0->join("FGCARACTERISTICAS_HCES1 FEATURES_$index", "FEATURES_$index.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND FEATURES_$index.NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FEATURES_$index.LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0");
						$fgasigl0 =$fgasigl0->where("FEATURES_$index.IDVALUE_CARACTERISTICAS_HCES1", $feature);

						$index++;
					}

					#Solo sirve para propiedades de min max valor, si en un futuro se añade selector multiple, esto no sirve
					#revisamos que al menos uno de las dos propiedades tenga valor
					else if(!empty($feature) && is_array($feature) && collect($feature)->filter()->isNotEmpty()){

						$fgasigl0 = $fgasigl0->join("FGCARACTERISTICAS_HCES1 FEATURES_$index", "FEATURES_$index.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND FEATURES_$index.NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FEATURES_$index.LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0");
						$fgasigl0 = $fgasigl0->join("FGCARACTERISTICAS_VALUE FEATURES_VALUE_$index", "FEATURES_VALUE_$index.EMP_CARACTERISTICAS_VALUE = FEATURES_$index.EMP_CARACTERISTICAS_HCES1 AND FEATURES_VALUE_$index.IDCAR_CARACTERISTICAS_VALUE = FEATURES_$index.IDCAR_CARACTERISTICAS_HCES1 AND FEATURES_VALUE_$index.ID_CARACTERISTICAS_VALUE = FEATURES_$index.IDVALUE_CARACTERISTICAS_HCES1");

						$fgasigl0 = $fgasigl0->where("FEATURES_$index.IDCAR_CARACTERISTICAS_HCES1", $key);
						$fgasigl0 = $fgasigl0->whereRaw("TRANSLATE(FEATURES_VALUE_$index.value_caracteristicas_value, 'T 0123456789', 'T') IS NULL");

						if(!empty($feature[0])){
							$fgasigl0 = $fgasigl0->where("cast(FEATURES_VALUE_$index.value_caracteristicas_value as int)", '>=', $feature[0]);
						}
						if(!empty($feature[1])){
							$fgasigl0 = $fgasigl0->where("cast(FEATURES_VALUE_$index.value_caracteristicas_value as int)", '<=', $feature[1]);
						}

						$index++;
					}
				}

            }
            return  $fgasigl0;
		}

		public function setFilterPrices($fgasigl0, $prices)
		{
			$pricesFilter = collect($prices)->filter();
			$minPrice = $pricesFilter[0] ?? null;
			$maxPrice = $pricesFilter[1] ?? null;

			if(!empty($minPrice) && is_numeric($minPrice)){
                $fgasigl0 =  $fgasigl0->where("greatest(FGHCES1.IMPLIC_HCES1, FGASIGL0.IMPSALHCES_ASIGL0)", ">=", $minPrice);
            }
			if(!empty($maxPrice) && is_numeric($maxPrice)){
                $fgasigl0 =  $fgasigl0->where("greatest(FGHCES1.IMPLIC_HCES1, FGASIGL0.IMPSALHCES_ASIGL0)", "<=", $maxPrice);
            }

            return  $fgasigl0;
		}

        public function setFilterCategory($fgasigl0, $category ){
            if(!empty($category) && is_numeric($category)){
            	$fgasigl0->join("FGORTSEC1" , "FGORTSEC1.EMP_ORTSEC1 = FGASIGL0.EMP_ASIGL0 AND FGORTSEC1.SUB_ORTSEC1 ='0' AND FGORTSEC1.SEC_ORTSEC1 =  FGHCES1.SEC_HCES1")
			   			->where("FGORTSEC1.LIN_ORTSEC1", $category);
            }
            return  $fgasigl0;
        }

        public function setFilterSection($fgasigl0, $section ){
            if(!empty($section)){
                $fgasigl0 = $fgasigl0->where("FGHCES1.SEC_HCES1", $section);
            }
            return  $fgasigl0;
		}

		public function setFilterSubSection($fgasigl0, $section ){
            if(!empty($section)){
                $fgasigl0 = $fgasigl0->where("FGHCES1.SUBFAM_HCES1", $section);
            }
            return  $fgasigl0;
        }
        #lote vendido, comprobamos que esté cerrado y tenga pujas
        public function setFilterAward($fgasigl0, $award ){
            if(!empty($award)){
                $fgasigl0 = $fgasigl0->where("FGASIGL0.CERRADO_ASIGL0", "S")->where("FGHCES1.LIC_HCES1",'S');
            }
            return  $fgasigl0;
        }

        #lote vendido, comprobamos que esté cerrado y tenga no pujas
        public function setFilterNoAward($fgasigl0, $noAward ){
            if(!empty($noAward)){
                $fgasigl0 = $fgasigl0->where("FGASIGL0.CERRADO_ASIGL0", "S")->where("FGHCES1.LIC_HCES1",'N');
            }
            return  $fgasigl0;
        }

        #lote vendido, comprobamos que esté abierto
        public function setFilterLiveLots($fgasigl0, $liveLots ){
            if(!empty($liveLots)){
                $fgasigl0 = $fgasigl0->where("FGASIGL0.CERRADO_ASIGL0", "N");
            }
            return  $fgasigl0;
		}

		#lotes de mi propiedad
		public function setFilterMyLotsProperty($fgasigl0, $myLotsProperty ){
            if(!empty($myLotsProperty) && !empty(\Session::get('user.cod'))) {
                $fgasigl0 = $fgasigl0->where("FGHCES1.PROP_HCES1 ", \Session::get('user.cod'));
            }
            return  $fgasigl0;
		}

		#lotes de mi propiedad
		public function setFilterMyLotsClient($fgasigl0, $myLotsClient ){
            if(!empty($myLotsClient) && !empty(\Session::get('user.cod'))) {
				$fgasigl0 = $fgasigl0->join("FGLICIT", "FGLICIT.EMP_LICIT = FGASIGL0.EMP_ASIGL0 AND FGLICIT.SUB_LICIT = SUB_ASIGL0 AND FGLICIT.CLI_LICIT  ='". \Session::get('user.cod')."'");
				$userBids = FGASIGL1::select('EMP_ASIGL1,SUB_ASIGL1,LICIT_ASIGL1,REF_ASIGL1')
                   ->groupBy('EMP_ASIGL1,SUB_ASIGL1,LICIT_ASIGL1,REF_ASIGL1');
				#he puesto "OF TIMESTAMP (SYSTIMESTAMP - INTERVAL '1' MINUTE) " antes del nombre de la tabla por que si no no funciona, paranoias de oracle
				//03/04/23: Eloy. Quito el "OF TIMESTAMP (SYSTIMESTAMP - INTERVAL '1' MINUTE)". En las nuevas versiones de oracle no es necesario.
				$fgasigl0 = $fgasigl0->joinSub($userBids, "LOTS_CLIENT", "LOTS_CLIENT.EMP_ASIGL1 = FGASIGL0.EMP_ASIGL0 AND LOTS_CLIENT.SUB_ASIGL1 = FGASIGL0.SUB_ASIGL0 AND LOTS_CLIENT.LICIT_ASIGL1 = FGLICIT.COD_LICIT AND LOTS_CLIENT.REF_ASIGL1 =  FGASIGL0.REF_ASIGL0");

            }
            return  $fgasigl0;
		}



        #carga del listado de filtros activos y la cantidad de lotes que contienen
        private function numActiveFilters( $codSub,  $refSession, $filters){

			$asigl0 = new FgAsigl0();
			/* MOSTRAR SOLO LAS SUBASTAS QUE PUEDE VER EL USUARIO */
			if(\Config::get("app.restrictVisibility")){
				$asigl0 = $asigl0->Visibilidadsubastas(\Session::get('user.cod'));
			}


            $asigl0 = $asigl0->CountLotsFilterAsigl0();
			//Filtros independientes que hay que tener en cuenta

            $asigl0 =  $this->setFilterReference($asigl0, $filters['reference']);
            $asigl0 =  $this->setFilterFeature($asigl0, $filters['features']);
            $asigl0 = $this->setFilterDescription($asigl0, request('description'));
            $asigl0 = $this->setFilterAward($asigl0, request('award'));
            $asigl0 = $this->setFilterNoAward($asigl0, request('noAward'));
			$asigl0 = $this->setFilterLiveLots($asigl0, request('liveLots'));

            $asigl0 =  $this->setFilterPrices($asigl0, $filters['prices']);

			$asigl0 =  $this->setFilterMyLotsProperty($asigl0, $filters['myLotsProperty']);
			$asigl0 =  $this->setFilterMyLotsClient($asigl0, $filters['myLotsClient']);
            // fin filtros a tener en cuenta

            #si es una subasta filtrar por codigo de subasta y referencia de session
            if(!empty($codSub) && !empty($refSession)){
                $asigl0 =  $asigl0->WhereAuction( $codSub, $refSession);
            }else{
                //si es por categoria ocultamos los cerrados y que no se puedan comprar
				if(request('historic')){
					$asigl0 =  $asigl0->HistoricLotForCategory();
				}else{
					$asigl0 =  $asigl0->ActiveLotForCategory();
				}

            }


            #contiene TSEC_SEC, COD_SEC, TIPO_SUB, SUBFAM_HCES1
            $numLotsPerFilter_array = $asigl0->get()->toarray();

            $filters = array("typeSub" => "tipo_sub",  "category" =>"lin_ortsec1", "section" => "sec_ortsec1" , "subsection" => "subfam_hces1" );
            $countLots = array();
            #generamos un array con cada combinatoria posible indicando el numero de lotes.
            foreach($numLotsPerFilter_array as $numLotsPerFilter){

                $names = array();
                #creamos la combinatoria con los valores de los filtros
                foreach ($filters as $key => $filter){
                    foreach($names as $name => $val){
                       $names[$name ."_".$key."-".$numLotsPerFilter[$filter] ] = $numLotsPerFilter["count_lots"];
                    }
                    $names[$key."-".$numLotsPerFilter[$filter]] = $numLotsPerFilter["count_lots"];
                }
               #si no existen los añadimos y si ya existia le sumamos
                foreach($names as $name => $val){
                    if(empty($countLots[$name]) ){
                        $countLots[$name] = $val;
                    }else{
                        $countLots[$name] += $val;
                    }
                }
            }

            return $countLots;
        }




    ###########################################
    #  FIN FUNCIONES DE FILTROS Y ORDENACION  #
    ###########################################



    public function getInfo($texto,  $codSub,  $refSession){

		abort_if(config('app.restrictAccessIfNoSession', 0) && session('user.cod'), 401);

        $lang = \Tools::getLanguageComplete(\Config::get('app.locale'));

        $gemp = Config::get("app.gemp");
        $auction = NULL;
        if(!empty($codSub) && !empty($refSession)){
            $fgsub = new Fgsub();
            #cargamos información de la subasta
            $auction = $fgsub->getInfoSub(  $codSub, $refSession);

        }

        \Tools::exit404IfEmpty($auction);
        $aucHouse = Sub_AucHouse::getByCliHouse($auction->agrsub_sub);

        $auction->house = $aucHouse->cod_auchouse;
        if( $aucHouse->ownsale_auchouse=='S'){
            $auction->idorigen = $aucHouse->cod_auchouse."-".\Config::get("app.emp")."-".$auction->cod_sub."-". $auction->reference;
        }

        $sub_Auchouse_Desc = new Sub_Auchouse_Desc();
        $auchouseDesc =$sub_Auchouse_Desc->getAucHouseDescAucHouse_Desc($auction->house)->first();
        $seo_data = SeoLib::getItem("auctionInfo");

        $aux = $seo_data->toArray();

        foreach ($aux as $k => $v) {
            $seo_data->$k = str_replace("[NAME]",$auction->name,$v);
        }

        $seo_data->parent = SeoLib::getItem("auctions");
        $seo_data->parent->url = route("auctions");
        #datos estructurados
        $type = ($auction->tipo_sub == "W")?  "SaleEvent" : "Auction";
        $seo_data->structured_li = 'itemscope itemtype="http://schema.org/Event"';
        $seo_data->structured_a = "itemprop='additionalType' href='http://www.productontology.org/id/$type'";
        $seo_data->structured_extra = "<meta itemprop='startDate' content='$auction->session_start'/>";

        $data = [
            "auction"    => $auction,
            "texto" => $texto,
            "codSub" => $codSub,
            "refSession" => $refSession,
            "auchouseDesc" => $auchouseDesc,
            "seo_data"   => $seo_data,
        ];

        return \View::make('front::pages.subasta-info', array('data' => $data));

	}

	public function clearWords($string, $lang){

		#eliminamos simbolos de puntuacion para evitar que den problemas en la query, he detectado un error al llegar un . en la consulta
		$string =str_replace(['"', ':', '.', ',', ';','$','%', "'", "(", ")", "+","-","=","|","*", "\\","&", "{","}"], '', $string);

        $all_words = explode(" ",$string);

        #Quitamos palabras reptidas, convietiendo la palabra en indice del array
		$wordsKeys = array_flip($all_words);




		#eliminamos palabras de una y dos letras
		foreach($wordsKeys as $word => $val){
			#vemos longitud de palabra y eliminamos si es menor o igual a 2
			if(strlen($word)<\Config::get("app.searchLongWord",2)){
				unset($wordsKeys[$word]);
			}

		}

        #Eliminamos palabras que no aportan nada
        $deleteWords = array(
           "es-ES" => array("a","aca","ahi","ajena","ajenas","ajeno","ajenos","al","algo","alguna","algunas","alguno","algunos","algun","alla","alli","aquel","aquella","aquellas","aquello","aquellos","aqui","cada","cierta","ciertas","cierto","ciertos","como","con","conmigo","consigo","contigo","cualquier","cualquiera","cualquieras","cuan","cuanta","cuantas","cuanto","cuantos","cuan","de","dejar","del","demasiada","demasiadas","demasiado","demasiados","demas","el","ella","ellas","ellos","el","esa","esas","ese","esos","esta","estar","estas","este","estos","hacer","hasta","jamas","junto","juntos","la","las","lo","los","mas","me","menos","mia","mientras","mio","misma","mismas","mismo","mismos","mucha","muchas","muchisima","muchisimas","muchisimo","muchisimos","mucho","muchos","muy","nada","ni","ninguna","ningunas","ninguno","ningunos","no","nos","nosotras","nosotros","nuestra","nuestras","nuestro","nuestros","nunca","os","otra","otras","otro","otros","para","parecer","poca","pocas","poco","pocos","por","porque","que","querer","que","quien","quienes","quienesquiera","quienquiera","quien","ser","si","siempre","si","sin","Sr","Sra","Sres","Sta","suya","suyas","suyo","suyos","tal","tales","tan","tanta","tantas","tanto","tantos","te","tener","ti","toda","todas","todo","todos","tomar","tuya","tuyo","tu","un","una","unas","unos","usted","ustedes","varias","varios","vosotras","vosotros","vuestra","vuestras","vuestro","vuestros","y","yo"),
           "en-GB" => array("a","all","almost","also","although","an","and","any","are","as","at","be","because","been","both","but","by","can","could","d","did","do","does","either","for","from","had","has","have","having","he","her","here","hers","him","his","how","however","i","if","in","into","is","it","its","just","ll","me","might","Mr","Mrs","Ms","my","no","non","nor","not","of","on","one","only","onto","or","our","ours","s","shall","she","should","since","so","some","still","such","t","than","that","the","their","them","then","there","therefore","these","they","this","those","though","through","thus","to","too","until","ve","very","was","we","were","what","when","where","whether","which","while","who","whose","why","will","with","would","yet","you","your","yours"),
            );

        foreach ($deleteWords[$lang] as $deleteWord){
            if(array_key_exists($deleteWord, $wordsKeys)){
                unset($wordsKeys[$deleteWord]);
            }
        }
        #pasamos las palabras de nuevo como array en vez de como keys de array
		$words =  array_keys($wordsKeys);
		$i=1;
		foreach($words as $key => $val){
			if($i>4){
				unset($words[$key]);
			}
			$i++;
		}
		#dejamos solo las 4 primeras palabras


        #generamos otra vez un texto de palabras separado por mayusculas
        $string = implode(" ", $words);



        return $string;

    }


}
