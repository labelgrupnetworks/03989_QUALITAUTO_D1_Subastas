<?php
namespace App\Http\Controllers\V5;

use Redirect;
use Config;
use Response;
use View;
use Route;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\V5\LotListController;
use App\libs\TradLib;
# Cargamos el modelo
use App\Models\V5\FgAsigl0;
use App\Models\V5\Web_Artist;
use App\Models\V5\Web_Artist_Article;
use App\Providers\ToolsServiceProvider;
use stdClass;

class ArtistController extends Controller
{
	var $numElements = 40;
    public function index(Request $request){
		$order = request("order","asc");

		if(request("order","asc") == "asc"){
			$order ="ASC";
		}else{
			$order ="DESC";
		}

		$webArtists  = Web_Artist::select("NAME_ARTIST, ID_ARTIST, INFO_ARTIST")->where("ACTIVE_ARTIST",1)->orderby("NAME_ARTIST", $order);

		if(!empty(request("description"))){
			$lotlist = new LotListController();
			$description = $lotlist->clearWords(request("description"), ToolsServiceProvider::getLanguageComplete(Config::get("app.locale")));
			$words = explode(" ",$description);
			$search="";
			$and="";


			foreach($words as $key => $word ){

				#ponemos el comodin de busqueda % para que busque cualquier texto despues de la palabra y dolar $ para que busque por stem (raiz, origen de una palabra)
				$search .=$and. " $".$word."% ";
				$and=" AND ";
			}
			#Es necesario poner las dos pipes || para concatenar la variable si no da error  número/nombre de variable no válid
			$webArtists  =  $webArtists->whereraw(" CATSEARCH(name_artist,'<query><textquery grammar=\"context\">' || ? || '</textquery></query>',null) >0", [ $search]);

		}

       $artists = $webArtists->paginate($this->numElements);

		$seoExist = TradLib::getWebTranslateWithStringKey('metas', 'title_artist', config('app.locale', 'es'));
		$data['seo'] = new \stdClass();
		if (!empty($seoExist)) {
			$data['seo']->meta_title = trans(\Config::get('app.theme') . '-app.metas.title_artist');
			$data['seo']->meta_description = trans(\Config::get('app.theme') . '-app.description_artist');
		}

		return View::make('front::pages.artists', compact("artists", "data"));
    }

	public function artist(Request $request){
		$artist  = Web_Artist::select('id_artist', 'name_artist')->LeftJoinLang()->where("id_artist", $request->idArtist)->first();

		$articles = Web_Artist_Article::where("idartist_artist_article", $request->idArtist)->get();
		#coger lotes activos, historicos los coje, ordenados por fecha y subasta
		$lots = FgAsigl0::select('DESCWEB_HCES1, COD_SUB, "id_auc_sessions",	"name" , REF_ASIGL0, NUM_HCES1, LIN_HCES1, WEBFRIEND_HCES1, SUBC_SUB, CERRADO_ASIGL0, OFERTA_ASIGL0, RETIRADO_ASIGL0,FAC_HCES1, IMPLIC_HCES1, SUBC_SUB, IMPADJ_ASIGL0')->ActiveLotAsigl0()->joinFgCaracteristicasAsigl0()->joinFgCaracteristicasHces1Asigl0()->where("IDVALUE_CARACTERISTICAS_HCES1",$request->idArtist)->orderby('"start"',"desc")->orderby('cod_sub')->get();

		$historyLots = array();
		$activeLots = array();
		foreach($lots as $lot){
			if($lot->subc_sub == 'S'){
				$activeLots[] =$lot;
			}else{
				$historyLots[] = $lot;
			}
		}

		$data = array();
		$data['seo'] = new stdClass();
 		$data['seo']->meta_title = $artist->name_artist;
		$data['seo']->meta_description = ToolsServiceProvider::acortar(strip_tags($artist->biography_artist), 166,  " ",  "...");

		return View::make('front::pages.artist', compact("artist","articles", "historyLots", "activeLots",  "data"));
	}

}
