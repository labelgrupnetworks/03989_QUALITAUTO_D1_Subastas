<?php

namespace App\Http\Controllers\V5;
use View;
use Route;
use Config;
use Input;

use Session;
use App\Http\Controllers\Controller;

# Modelos

use App\Models\Subasta;
use App\Models\V5\FgAsigl0;

use App\Models\V5\Web_Artist;
use App\Models\V5\FgSub;


use App\libs\SeoLib;
use stdClass;

class GaleriaArte extends Controller
{

	public function getGalery( $texto, $codSub,  $reference ){

        $lang = \Tools::getLanguageComplete(\Config::get('app.locale'));

		if(!empty($codSub) && !empty($reference)){
            $fgsub = new Fgsub();
            #cargamos información de la subasta
			$auction = $fgsub->getInfoSub(  $codSub, $reference);

			 \Tools::exit404IfEmpty($auction);
			 if($auction->tipo_sub !='E'){
				exit(\View::make('front::errors.404'));
			 }

			$fgasigl0 = new FgAsigl0();

			$lots = $fgasigl0->select('FGHCES1.NUM_HCES1, FGHCES1.LIN_HCES1,  WEBFRIEND_HCES1, DESCWEB_HCES1, REF_ASIGL0,   DES_SUB, DESCDET_SUB, DFEC_SUB, HFEC_SUB, COD_SUB,auc."reference" , auc."id_auc_sessions", auc."name",IDVALUE_CARACTERISTICAS_HCES1')
							->leftjoin('FGCARACTERISTICAS_HCES1', "FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0 AND FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 = '". \Config::get("app.ArtistCode")."'" )
							->where("COD_SUB", $codSub)
			 				#ordenamos por orden, pero tambien tenemos en cuenta la referencia ya que por defecto el orden esta a nully rompia la ordenacion
							->ActiveLotAsigl0()->orderby("nvl(orden_hces1,ref_hces1), nvl(orden_hces1,99999999999) ")->get();

			#buscamos los artistas de la exposición
			$idArtists = [];
			foreach($lots as $lot){
				if(empty($idArtists[$lot->idvalue_caracteristicas_hces1])){
					$idArtists[$lot->idvalue_caracteristicas_hces1]=$lot->idvalue_caracteristicas_hces1;
				}
			}
			



			$artists = [];

			if (count($idArtists) > 0){
				$web_artist = new WEB_ARTIST();
				$artists = $web_artist->select("NAME_ARTIST, ID_ARTIST")->LeftJoinLang()->wherein("WEB_ARTIST.ID_ARTIST", $idArtists)->get();

				if(\Config::get("app.ArtistNameSurname")){
					$artists =	$this->nameSurname($artists );
				}
			}


			$data["artists"] = $artists;
			$data['lots'] = $lots;
			$data['auction'] = $auction;
			$data['exhibitions'] =array();
			#cargamos el resto de exposiciones del artista
			if(count($artists) == 1){
				$artist = $artists[0];
				$fgasigl0 = new  FgAsigl0 ;
				$exhibitions =	$fgasigl0->select("DES_SUB, COD_SUB, DFEC_SUB, HFEC_SUB, NAME_ARTIST")
						->JoinSubastaAsigl0()
						->join('FGCARACTERISTICAS_HCES1', 'FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')
						->join('WEB_ARTIST', 'WEB_ARTIST.EMP_ARTIST = FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 AND WEB_ARTIST.ID_ARTIST =  FGCARACTERISTICAS_HCES1.IDVALUE_CARACTERISTICAS_HCES1')
						->where("WEB_ARTIST.ID_ARTIST", $artist->id_artist)
						->where("TIPO_SUB","E")
						->wherein("SUBC_SUB", ["S","H"] )
						#que no salga la exposicion actual
						->where("COD_SUB","!=", $codSub)


						->groupby("COD_SUB,DES_SUB, COD_SUB, DFEC_SUB, HFEC_SUB, NAME_ARTIST")
						->orderby("DFEC_SUB", "DESC")
						->get();
						$data['exhibitions'] = $exhibitions;

			}


			return \View::make('front::pages.galery.galeryGrid', $data);
		}

    }
	public function artists(){
		$webArtist = new Web_Artist();
		#$fgasigl0 = new  FgAsigl0 ;
		$searchWords = request("search");
		if($searchWords){
			$search = $this->prepareSearchWords($searchWords);
			#Es necesario poner las dos pipes || para concatenar la variable si no da error  número/nombre de variable no válid
			$webArtist  =  $webArtist->whereraw(" CATSEARCH(name_artist,'<query><textquery grammar=\"context\">' || ? || '</textquery></query>',null) >0", [ $search]);

		}


		$data["artists"] = $webArtist->select("ID_ARTIST, NAME_ARTIST")->where("ACTIVE_ARTIST","1")->orderby("NAME_ARTIST")->get();
		#cargaremos todos los artistas activos por lo que no hay que mirar que tengan galeria
		/*
		$data["artists"] =	$fgasigl0->select("ID_ARTIST, NAME_ARTIST")
		->JoinSubastaAsigl0()
		->join('FGCARACTERISTICAS_HCES1', 'FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')
		->join('WEB_ARTIST', 'WEB_ARTIST.EMP_ARTIST = FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 AND WEB_ARTIST.ID_ARTIST =  FGCARACTERISTICAS_HCES1.IDVALUE_CARACTERISTICAS_HCES1')
		->where("TIPO_SUB","E")
		->wherein("SUBC_SUB", ["S","H"] )
		->groupby("ID_ARTIST, NAME_ARTIST")
		->get();
		*/
		if(\Config::get("app.ArtistNameSurname")){
			$data["artists"] = $this->nameSurname($data["artists"] );
		}


		return \View::make('front::pages.galery.artists', $data);
	}

	public function artist($id_artist){
		$artist = Web_Artist::where("ID_ARTIST", $id_artist)->first();

		if(\Config::get("app.ArtistNameSurname")){
			$artist = $this->nameSurname($artist );
		}
		\Tools::exit404IfEmpty($artist);

		$fgasigl0 = new  FgAsigl0 ;
		$exhibitions =	$fgasigl0->select("DES_SUB, COD_SUB, DFEC_SUB, HFEC_SUB, NAME_ARTIST")
				->JoinSubastaAsigl0()
				->join('FGCARACTERISTICAS_HCES1', 'FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')
				->join('WEB_ARTIST', 'WEB_ARTIST.EMP_ARTIST = FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 AND WEB_ARTIST.ID_ARTIST =  FGCARACTERISTICAS_HCES1.IDVALUE_CARACTERISTICAS_HCES1')
				->where("WEB_ARTIST.ID_ARTIST", $id_artist)
				->where("TIPO_SUB","E")
				->wherein("SUBC_SUB", ["S","H"] )
				->groupby("COD_SUB,DES_SUB, COD_SUB, DFEC_SUB, HFEC_SUB, NAME_ARTIST")
				->orderby("DFEC_SUB", "DESC")
				->get();

		if(\Config::get("app.ArtistNameSurname")){
			$exhibitions = $this->nameSurname($exhibitions );
		}

		return \View::make('front::pages.galery.artist', ["exhibitions" =>$exhibitions, "artist" => $artist]);
	}

	public function exhibitons(){
		$exhibitions = $this->getExhibitions($status = array("S","H"));


		return \View::make('front::pages.galery.exhibitions', ["exhibitions" =>$exhibitions]);
	}
/* de momento lo dejo comentado, y pongo las H en exhibitions
	public function previousExhibitons (){
		$exhibitions = $this->getExhibitions($status = array("H"));
		return \View::make('front::pages.galery.exhibitions', ["exhibitions" =>$exhibitions]);
	}
*/
	#no tenemos en cuenta las sesiones
	private function getExhibitions($status = array("S","H")){

		if(Session::has('user') && Session::get('user.admin')) {
			$status = array_merge($status, ["A"]);
		}

		$subObj = new FgSub();

		if(count($status) > 0){
			$subObj = $subObj->wherein("SUBC_SUB", $status );
		}

		if(!empty(request("online"))){
			$subObj = $subObj->where("OPCIONCAR_SUB", request("online") );
		}

		if(request("search")){
			$subObj = $subObj->where("lower(DES_SUB)", "like", "%".mb_strtolower(request("search"))."%");
		}

		return  $subObj->select("DES_SUB, COD_SUB, DFEC_SUB, HFEC_SUB")->where("TIPO_SUB","E")->orderby("DFEC_SUB", "DESC")->get();

	}

	public function fondoGaleria(){
		$fgasigl0 = new  FgAsigl0 ;

		$searchWords = request("search");
		if($searchWords){
			$search = $this->prepareSearchWords($searchWords);
			#Es necesario poner las dos pipes || para concatenar la variable si no da error  número/nombre de variable no válid
			$fgasigl0  =  $fgasigl0->whereraw(" CATSEARCH(search_hces1,'<query><textquery grammar=\"context\">' || ? || '</textquery></query>',null) >0", [ $search]);

		}

		$lots=	$fgasigl0->select("ID_ARTIST, NAME_ARTIST,NUM_HCES1, LIN_HCES1, REF_ASIGL0 ")
		->ActiveLotAsigl0()
		->join('FGCARACTERISTICAS_HCES1', 'FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')
		->join('WEB_ARTIST', 'WEB_ARTIST.EMP_ARTIST = FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 AND WEB_ARTIST.ID_ARTIST =  FGCARACTERISTICAS_HCES1.IDVALUE_CARACTERISTICAS_HCES1')
		->wherein("TIPO_SUB",["E","F"])
		#PARA QUE APAREZCA EN EL FONDO DE GALERIA DEBE SER NECESARIO QUE SE PONGA QUE SE PUEDE COMPRAR
		->where("COMPRA_ASIGL0", "S" )
		#ORDENAMOS POR DESTACADO DESC PARA QUE PONGA PRIMERO EL DESTACADO SI EXISTE, SI NO, COJERÁ EL QUE TENGA LA REFERENCIA MÁS PEQUEÑA
		->orderby('NAME_ARTIST,DESTACADO_ASIGL0 desc, REF_ASIGL0')
		->get();
		$artists=[];

		if(\Config::get("app.ArtistNameSurname")){
			$exhibitions = $this->nameSurname($lots );
		}
		foreach($lots as $lot){
			#COJEMOS SOLO EL PRIMER LOTE, YA QUE NECESITAREMSO SU NUM Y LIN PARA LA FOTO.
			if(empty($artists[$lot->id_artist])){
				$artists[$lot->id_artist] = $lot;
				$artists[$lot->id_artist]->numlots = 1;
			}else{
				$artists[$lot->id_artist]->numlots ++;
			}

		}
		$data["artists"] = $artists;


		return \View::make('front::pages.galery.fondoGaleria', $data );
	}


	function artistFondoGaleria( $idArtist){

		$artist = Web_Artist::where("ID_ARTIST", $idArtist)->first();
		\Tools::exit404IfEmpty($artist);

		$fgasigl0 = new  FgAsigl0 ;
		#lotes de este artista
		$lotsTmp =	$fgasigl0->select(' FEATURES.IDCAR_CARACTERISTICAS_HCES1,  FEATURES.VALUE_CARACTERISTICAS_HCES1, NUM_HCES1, LIN_HCES1, REF_ASIGL0, COD_SUB, auc."reference" ,WEBFRIEND_HCES1, DESCWEB_HCES1 , auc."id_auc_sessions", auc."name" ')
		->ActiveLotAsigl0()
		#join para saber el artista
		->join('FGCARACTERISTICAS_HCES1 AUTOR', 'AUTOR.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND AUTOR.NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND AUTOR.LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')
		#left join para las caracteristicas
		->leftjoin('FGCARACTERISTICAS_HCES1 FEATURES', "FEATURES.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND FEATURES.NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FEATURES.LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0 AND FEATURES.IDCAR_CARACTERISTICAS_HCES1 !='" .\Config::get("app.ArtistCode") ."'" )

		->wherein("TIPO_SUB",["E","F"])
		#usar solo el id que corresponde a artistas
		->where("AUTOR.IDCAR_CARACTERISTICAS_HCES1", \Config::get("app.ArtistCode") )
		->where("AUTOR.IDVALUE_CARACTERISTICAS_HCES1", $idArtist )
		#PARA QUE APAREZCA EN EL FONDO DE GALERIA DEBE SER NECESARIO QUE SE PONGA QUE SE PUEDE COMPRAR
		->where("COMPRA_ASIGL0", "S" )
		#ORDENAMOS POR DESTACADO DESC PARA QUE PONGA PRIMERO EL DESTACADO SI EXISTE, SI NO, COJERÁ EL QUE TENGA LA REFERENCIA MÁS PEQUEÑA
		->orderby('DESTACADO_ASIGL0 desc, REF_ASIGL0')
		->get();
		$lots = array();
		$features = array();
		foreach($lotsTmp as $lot){
			$k = $lot->num_hces1."_".$lot->lin_hces1;
			if(empty($lots[$k])){
				$features[$k] = array();
				$lots[$k] = $lot;

			}
			#quitamos el html
			$features[$k][$lot->idcar_caracteristicas_hces1] = strip_tags($lot->value_caracteristicas_hces1);

		}

		$data["features"] = $features;
		$data["lots"] = $lots;
		$data["artist"] = $artist;



		return \View::make('front::pages.galery.artistFondoGaleria', $data );
	}

	function prepareSearchWords($searchWords){
		$lotlist = new LotListController();
		$description = $lotlist->clearWords($searchWords, \Tools::getLanguageComplete(Config::get("app.locale")));
		$words = explode(" ",$description);
		$search="";
		$and="";


		foreach($words as $key => $word ){


			#ponemos el comodin de busqueda % para que busque cualquier texto despues de la palabra y dolar $ para que busque por stem (raiz, origen de una palabra)
			$search .=$and. " $".$word."% ";
			$and=" AND ";
		}

		return $search;
	}

	#cuando el nombre está compuesto por "apellido, nombre" , le da la vuelta y escribe "nombre apellido"
	public function nameSurname($data){

		if(!is_array($data)){
			if(!empty($data->name_artist)){
				$data->name_artist =  $this->explodeComillas($data->name_artist);
			}
		}

		foreach($data as $item){
			if(!empty($item->name_artist)){
				$item->name_artist = $this->explodeComillas($item->name_artist);
			}

		}
			return $data;


	}

	public function  explodeComillas($val){

			$name = explode(",",  $val);
			$val="";
			#si habia coma
			if(count($name)== 2){
				$val = $name[1] ." ";
			}
			$val .= $name[0];


		return $val;
	}




}
