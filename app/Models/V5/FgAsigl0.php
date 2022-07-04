<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Models\V5\Traits\Hces1Asigl0Methods;
use App\Models\V5\Traits\ScopeFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;

class FgAsigl0 extends Model
{
	use Hces1Asigl0Methods, ScopeFilter;

    protected $table = 'FGASIGL0';
    protected $primaryKey = 'EMP_ASIGL0,SUB_ASIGL0, REF_ASIGL0';

    public $timestamps = false;
    public $incrementing = false;

 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

     #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_asigl0' => \Config::get("app.emp")
		];

        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_asigl0', \Config::get("app.emp"));
        });
    }
    protected $casts = [
        'ref_asigl0' => 'float',
        'impsalhces_asigl0' => 'float',
        'imptash_asigl0' => 'float',
        'imptas_asigl0' => 'float',
        'impres_asigl0' => 'float',


    ];

     #esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
     public function scopeWhereUpdateApi($query, $item){
        return $query->where('idorigen_asigl0', $item["idorigen_asigl0"])->where('sub_asigl0', $item["sub_asigl0"]);
    }

    public function scopeJoinFghces1Asigl0($query){
        return $query->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
    }

    public function scopeLeftJoinFghces1Asigl0($query){
        return $query->leftjoin('FGHCES1', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
    }

     public function scopeLeftJoinCaracteristicasAsigl0($query){
        return $query->leftjoin('FGCARACTERISTICAS_HCES1', 'FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')
        ->leftjoin('FGCARACTERISTICAS', 'FGCARACTERISTICAS.EMP_CARACTERISTICAS = FGASIGL0.EMP_ASIGL0 AND  FGCARACTERISTICAS.ID_CARACTERISTICAS = FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1' )
        ->leftjoin('FGCARACTERISTICAS_VALUE', 'FGCARACTERISTICAS_VALUE.EMP_CARACTERISTICAS_VALUE = FGASIGL0.EMP_ASIGL0 AND  FGCARACTERISTICAS_VALUE.IDCAR_CARACTERISTICAS_VALUE = FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 AND  FGCARACTERISTICAS_VALUE.ID_CARACTERISTICAS_VALUE = FGCARACTERISTICAS_HCES1.IDVALUE_CARACTERISTICAS_HCES1');


    }


    public function scopeJoinFgOrtsecAsigl0($query){
        return $query->join('FGORTSEC1', "FGORTSEC1.EMP_ORTSEC1 = FGHCES1.EMP_HCES1 AND FGORTSEC1.SUB_ORTSEC1 = '0' AND FGORTSEC1.SEC_ORTSEC1 =  FGHCES1.SEC_HCES1 ")
                     ->join('FGORTSEC0',"FGORTSEC0.EMP_ORTSEC0 = FGORTSEC1.EMP_ORTSEC1  AND FGORTSEC0.SUB_ORTSEC0 = FGORTSEC1.SUB_ORTSEC1 AND FGORTSEC0.LIN_ORTSEC0 = FGORTSEC1.LIN_ORTSEC1");
     }

     static function arrayByIdOrigin($idAuction){
        $lotsTmp =self::select("numhces_asigl0, linhces_asigl0, idorigen_asigl0, sub_asigl0, ref_asigl0")->where("sub_asigl0", $idAuction)->get();
        $lots = array();
        foreach($lotsTmp as $lot) {
            $lots[$lot->idorigen_asigl0] = $lot->toarray();
        }

        return $lots;

	 }

	 static function arrayByRef($idAuction){
        $lotsTmp =self::select("numhces_asigl0, linhces_asigl0,  sub_asigl0, ref_asigl0")->where("sub_asigl0", $idAuction)->get();
        $lots = array();
        foreach($lotsTmp as $lot) {
            $lots[$lot->ref_asigl0] = $lot->toarray();
        }

        return $lots;

	 }





	 # para que aparezca un lote
	 public function scopeActiveLotAsigl0($query){


        $query = $query->JoinFghces1Asigl0()
                ->JoinSubastaAsigl0()
                ->JoinSessionAsigl0()
                ->where('FGSUB.SUBC_SUB','!=','N')
				->where("FGASIGL0.OCULTO_ASIGL0", "N");

		if(\Config::get("app.permanent_auction", 0)){
			$query = $query->whereRaw("((FGSUB.TIPO_SUB in('W','V','O')) OR ( FGSUB.TIPO_SUB = 'P' AND(TRUNC(fgasigl0.FINI_ASIGL0) < TRUNC(SYSDATE) or (fgasigl0.FINI_ASIGL0 = TRUNC(SYSDATE) AND  fgasigl0.HINI_ASIGL0 <= TO_CHAR(SYSDATE, 'HH24:MI:SS'))) ))");
		}

				#si tienen este config los lotes de las subastas que sean V-tienda o O-Online no se ven cuando estan cerrados
		if(\Config::get("app.hideCloseLots")){
			$query = $query->whereRAW("(FGASIGL0.CERRADO_ASIGL0 ='N' OR FGSUB.TIPO_SUB ='W') ");
		}
		#los lotes retirados no apareceran en el grid, de esta manera se podrá ver la ficha y no se pierde la URL cómo pasa con los ocultos
		if(\Config::get("app.hideRetiredLots")){
			$query = $query->whereRAW("(FGASIGL0.RETIRADO_ASIGL0 ='N') ");
		}

		#las subastas de venta directa no aparecen si se ha superado la fecha
		if(\Config::get("app.hideExpireSaleLots")){
			$query = $query->whereRaw("((FGSUB.TIPO_SUB in('W','P','O')) OR ( FGSUB.TIPO_SUB = 'V' AND(TRUNC(fgasigl0.FFIN_ASIGL0) > TRUNC(SYSDATE) or (fgasigl0.FFIN_ASIGL0 = TRUNC(SYSDATE) AND  fgasigl0.HFIN_ASIGL0 >= TO_CHAR(SYSDATE, 'HH24:MI:SS'))) ))");
		}

		#el lote no aparece si no tiene imágenes
		if(\Config::get("app.hideNoFoto")){
			$query = $query->whereRAW("FGHCES1.TOTALFOTOS_HCES1 IS NOT NULL ");
		}

		return $query;
    }

	public function scopeJoinSubastaAsigl0($query){
        return $query->join('FGSUB','FGSUB.EMP_SUB = FGASIGL0.EMP_ASIGL0 AND FGSUB.COD_SUB = FGASIGL0.SUB_ASIGL0');
	}

	 #Devolvemos la session que pertenece a la referencia del lote, no se debe usar le where con referencia
	 public function scopeJoinSessionAsigl0($query){
        return $query->join('"auc_sessions" auc','auc."company" = FGASIGL0.EMP_ASIGL0 AND auc."auction" = FGASIGL0.SUB_ASIGL0 and auc."init_lot" <= ref_asigl0 and   auc."end_lot" >= ref_asigl0');

	}



	#devuelve la cantidad de lotes por cada filtro, por ejemplo nº lotes de subastas tipo O, o nº lotes de categoria joyas
    public function scopeCountLotsFilterAsigl0($query)  {
		return  $query->select("FGORTSEC1.LIN_ORTSEC1, FGORTSEC1.SEC_ORTSEC1, FGSUB.TIPO_SUB, FGHCES1.SUBFAM_HCES1, count(FGASIGL0.REF_ASIGL0) count_lots")
			   ->ActiveLotAsigl0()
			   #no debe hacer el join con fxsec, si no con fgortsec1
			   ->JoinFgOrtsec1Asigl0()
			   ->groupby("FGORTSEC1.LIN_ORTSEC1, FGORTSEC1.SEC_ORTSEC1, FGSUB.TIPO_SUB, FGHCES1.SUBFAM_HCES1");
   }

   public function scopeJoinFgOrtsec1Asigl0($query){
        return $query->leftjoin("FGORTSEC1" , "FGORTSEC1.EMP_ORTSEC1 = FGASIGL0.EMP_ASIGL0 AND FGORTSEC1.SUB_ORTSEC1 ='0' AND FGORTSEC1.SEC_ORTSEC1 =  FGHCES1.SEC_HCES1");
	}

	public function scopejoinFgCaracteristicasAsigl0($query){
		return $query->join("FGCARACTERISTICAS" , "FGCARACTERISTICAS.EMP_CARACTERISTICAS = EMP_ASIGL0 ");

	}

	public function scopejoinFgCaracteristicasHces1Asigl0($query){
		return $query->join("FGCARACTERISTICAS_HCES1" , "FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = EMP_ASIGL0 AND FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 = FGCARACTERISTICAS.ID_CARACTERISTICAS AND FGCARACTERISTICAS_HCES1.NUMHCES_CARACTERISTICAS_HCES1 = NUMHCES_ASIGL0 AND FGCARACTERISTICAS_HCES1.LINHCES_CARACTERISTICAS_HCES1 = LINHCES_ASIGL0");

	}

	public function scopejoinFgCaracteristicasValueAsigl0($query){
		return $query->join("FGCARACTERISTICAS_VALUE" , "EMP_CARACTERISTICAS_VALUE = EMP_ASIGL0 AND IDCAR_CARACTERISTICAS_VALUE = ID_CARACTERISTICAS AND  ID_CARACTERISTICAS_VALUE = IDVALUE_CARACTERISTICAS_HCES1");

	}
	public function scopeJoinLangCaracteristicasValueAsigl0($query){
		$lang =  \Tools::getLanguageComplete(\Config::get('app.locale'));
		return $query->leftjoin("FGCARACTERISTICAS_VALUE_LANG","EMP_CAR_VAL_LANG = EMP_CARACTERISTICAS_VALUE AND IDCARVAL_CAR_VAL_LANG = ID_CARACTERISTICAS_VALUE AND LANG_CAR_VAL_LANG= '". $lang . "'");
	}

	public function scopeJoinCSubAsigl0($query){
        return $query->leftJoin('FGCSUB','FGCSUB.EMP_CSUB = FGASIGL0.EMP_ASIGL0 AND FGCSUB.SUB_CSUB = FGASIGL0.SUB_ASIGL0  AND FGCSUB.REF_CSUB = FGASIGL0.REF_ASIGL0  ') ;
	}

	public function scopeJoinNFT($query){
        return $query->join('FGNFT', ' FGNFT.EMP_NFT = FGASIGL0.EMP_ASIGL0  AND  FGNFT.NUMHCES_NFT = FGASIGL0.NUMHCES_ASIGL0 AND FGNFT.LINHCES_NFT = FGASIGL0.LINHCES_ASIGL0');
	}

	public function scopeLeftJoinCliWithCsub($query){
        return $query->leftjoin("FXCLI", "GEMP_CLI = '". \Config::get("app.gemp") ."' AND COD_CLI = CLIFAC_CSUB");
	}

	public function scopeLeftJoinOwnerWithHces1($query, $tableName = 'fxcli')
	{
        return $query->leftjoin("FXCLI $tableName", "$tableName.GEMP_CLI = '". \Config::get("app.gemp") ."' AND $tableName.COD_CLI = PROP_HCES1");
	}
	public function scopeWithArtist($query)
	{
		return $query->leftJoin("FGCARACTERISTICAS" , "FGCARACTERISTICAS.EMP_CARACTERISTICAS = EMP_ASIGL0 and FGCARACTERISTICAS.ID_CARACTERISTICAS = ".config('app.ArtistCode', 0))
			->leftJoin("FGCARACTERISTICAS_HCES1" , "FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = EMP_ASIGL0 AND FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 = FGCARACTERISTICAS.ID_CARACTERISTICAS AND FGCARACTERISTICAS_HCES1.NUMHCES_CARACTERISTICAS_HCES1 = NUMHCES_ASIGL0 AND FGCARACTERISTICAS_HCES1.LINHCES_CARACTERISTICAS_HCES1 = LINHCES_ASIGL0")
			->leftJoin("FGCARACTERISTICAS_VALUE" , "EMP_CARACTERISTICAS_VALUE = EMP_ASIGL0 AND IDCAR_CARACTERISTICAS_VALUE = ID_CARACTERISTICAS AND  ID_CARACTERISTICAS_VALUE = IDVALUE_CARACTERISTICAS_HCES1")

			//por si necesito buscar má de uno por lote
			//->selectRaw("LISTAGG(FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE, '; ') as artists ")
			//->groupBy($selects);
			->addSelect('FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE as artist_name');

	}


	/**
	 *NECESITA EL JOIN DE HCES1
	 */
	public function scopeLeftJoinAlm($query){
        return $query->leftjoin("FXALM", "EMP_ALM = EMP_HCES1 AND COD_ALM = ALM_HCES1 ");
    }

	public function scopeWhereAuction($query, $codSub, $refSession){

		#si no tienen el web config gridAllSessions  se filtra por session
		if(empty(\Config::get("app.gridAllSessions")) ){
			$query = $query->where('"reference"',$refSession);
		}

		return $query->where("FGASIGL0.SUB_ASIGL0", $codSub);



	}
	#condiciones que deben cumplir los lotes para verse si estamos en categorias
	public function scopeActiveLotForCategory($query){

		if (\Session::has('user') &&  \Session::get('user.admin')){
			$active =  array("S","A");
		}else{
			$active =  array("S");
		}
		$query = $query->where("FGSUB.TIPO_SUB","!=", "E" );
		//return $query->wherein("FGSUB.SUBC_SUB",$active)->wherein("FGSUB.TIPO_SUB",["W","V","O","P"])->whereraw("( FGASIGL0.CERRADO_ASIGL0='N' OR (FGASIGL0.CERRADO_ASIGL0='S' AND FGASIGL0.COMPRA_ASIGL0 ='S' AND FGHCES1.LIC_HCES1 ='N'  ) )");
		$query = $query->wherein("FGSUB.SUBC_SUB",$active);

		if( empty(\Config::get("app.showCloseLotCategoryGrid")) && empty(\Config::get("app.showCloseLotCategoryGridWithoutCompra"))) {
			$query = $query->whereraw("( FGASIGL0.CERRADO_ASIGL0='N' OR (FGASIGL0.CERRADO_ASIGL0='S' AND FGASIGL0.COMPRA_ASIGL0 ='S' AND FGHCES1.LIC_HCES1 ='N'  ) )");
		}

		if( (\Config::get("app.showCloseLotCategoryGridWithoutCompra"))) {
			$query = $query->whereraw("( FGASIGL0.CERRADO_ASIGL0='N' OR (FGASIGL0.CERRADO_ASIGL0='S'  AND FGHCES1.LIC_HCES1 ='N'  ) )");
		}else




		return $query;



	}
	#condiciones que deben cumplir los lotes para verse si estamos en histórico de categorias
	public function scopeHistoricLotForCategory($query){
		#lote cerrado y adjudicado de una subasta histórica o actual si ya se ha cerrado el lote, no puede cojer la venta privada tipo E

		return $query->where("FGSUB.SUBC_SUB",'H')->wherein("FGSUB.TIPO_SUB",["W","V","O","P"])->whereraw("( FGASIGL0.CERRADO_ASIGL0='S' AND  FGHCES1.LIC_HCES1 ='S' )");

	}

	#Devuelve un listado de lotes a partir de las referencias y subastas, no hace comprobaciones solo devuelve los datos.
    public function scopeGetLotsByRefAsigl0($query,$refLots){
		#falta hacer select de ordenes para subastas abiertas tipo O, si no se necesita n ose hará ya que consume recursos

		#ver el numero de pujas y de licitadores si está configurado el web config
		if(\Config::get("app.number_bids_lotlist") ){
			$query = $query->selectRaw(" (SELECT COUNT(DISTINCT(LICIT_ASIGL1))  FROM FGASIGL1 WHERE EMP_ASIGL1 = FGASIGL0.EMP_ASIGL0 AND SUB_ASIGL1 = FGASIGL0.SUB_ASIGL0 AND REF_ASIGL1 = FGASIGL0.REF_ASIGL0) LICITS")
							->selectRaw(" (SELECT COUNT(LIN_ASIGL1)  FROM FGASIGL1 WHERE EMP_ASIGL1 = FGASIGL0.EMP_ASIGL0 AND SUB_ASIGL1 = FGASIGL0.SUB_ASIGL0 AND REF_ASIGL1 = FGASIGL0.REF_ASIGL0) BIDS");
		}

		#ver los lotes favoritos si está configurado el web config
		if(\Config::get("app.favorites_lotlist")  && \Session::has('user')){

			$query = $query->addselect("ID_WEB_FAVORITES")->leftJoin("WEB_FAVORITES", function($join) {
				$join->on("WEB_FAVORITES.ID_EMP", "=", "FGASIGL0.EMP_ASIGL0")
				->on("WEB_FAVORITES.ID_SUB" ,"=", "FGASIGL0.SUB_ASIGL0")
				->on("WEB_FAVORITES.ID_REF" ,"=", "FGASIGL0.REF_ASIGL0")
				->on("WEB_FAVORITES.COD_CLI" ,"=",\Session::get('user.cod'));
			});

		}



		#ver si se ha pujado
		if(\Config::get('app.user_bid_lotList', '0') && \Session::has('user')){
			$query = $query->selectRaw(" (SELECT FGLICIT.CLI_LICIT FROM FGASIGL1 JOIN FGLICIT ON FGLICIT.CLI_LICIT = '".\Session::get('user.cod')."' AND FGLICIT.COD_LICIT = FGASIGL1.LICIT_ASIGL1 AND FGLICIT.SUB_LICIT = FGASIGL0.SUB_ASIGL0 WHERE EMP_ASIGL1 = FGASIGL0.EMP_ASIGL0 AND SUB_ASIGL1 = FGASIGL0.SUB_ASIGL0 AND REF_ASIGL1 = FGASIGL0.REF_ASIGL0 AND ROWNUM = 1) USER_HAVE_BID");
		}

		#ganador de la puja
		$query = $query->selectRaw("(SELECT CLI_LICIT	FROM FGASIGL1
									JOIN FGLICIT ON FGLICIT.EMP_LICIT = FGASIGL1.EMP_ASIGL1 AND FGLICIT.SUB_LICIT = FGASIGL1.SUB_ASIGL1 AND COD_LICIT = LICIT_ASIGL1
									WHERE EMP_ASIGL1 = FGASIGL0.EMP_ASIGL0 AND SUB_ASIGL1 = FGASIGL0.SUB_ASIGL0 AND REF_ASIGL1 = FGASIGL0.REF_ASIGL0
									AND LIN_ASIGL1 =  (SELECT MAX(LIN_ASIGL1)  FROM FGASIGL1 WHERE EMP_ASIGL1 = FGASIGL0.EMP_ASIGL0 AND SUB_ASIGL1 = FGASIGL0.SUB_ASIGL0 AND REF_ASIGL1 = FGASIGL0.REF_ASIGL0)
									 ) CLI_WIN_BID");

        $query = $query->addselect("FGASIGL0.EMP_ASIGL0,FGASIGL0.SUB_ASIGL0, FGASIGL0.REF_ASIGL0, FGASIGL0.CERRADO_ASIGL0, FGASIGL0.IMPSALHCES_ASIGL0, FGASIGL0.COMLHCES_ASIGL0, FGASIGL0.RETIRADO_ASIGL0, FGASIGL0.REMATE_ASIGL0, FGASIGL0.COMPRA_ASIGL0, FGASIGL0.IDORIGEN_ASIGL0, FGASIGL0.IMPTAS_ASIGL0, FGASIGL0.OFERTA_ASIGL0, FGASIGL0.FINI_ASIGL0, FGASIGL0.HINI_ASIGL0, FGASIGL0.OCULTARPS_ASIGL0 ")
                ->addSelect("FGHCES1.NUM_HCES1, FGHCES1.LIN_HCES1,  FGHCES1.FAC_HCES1, FGHCES1.SEC_HCES1, FGHCES1.IDORIGEN_HCES1, FGHCES1.IMPLIC_HCES1, FGHCES1.LIC_HCES1, FGHCES1.TOTALFOTOS_HCES1, FGHCES1.TRANSPORT_HCES1, FGHCES1.ANCHO_HCES1 ")
                ->addSelect("NVL(FGHCES1_LANG.TITULO_HCES1_LANG, FGHCES1.TITULO_HCES1) TITULO_HCES1,   NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1")

                ->addSelect("FGSUB.COD_SUB, FGSUB.TIPO_SUB, FGSUB.SUBC_SUB, FGSUB.SUBABIERTA_SUB")
                ->addSelect('auc."name",auc."end", auc."start", auc."reference", auc."id_auc_sessions"', 'auc."orders_end"', 'auc."orders_start"')

                ->addSelect(\DB::raw("(CASE WHEN FGASIGL0.ffin_asigl0 IS NOT NULL AND FGASIGL0.hfin_asigl0 IS NOT NULL
                    THEN TO_DATE(TO_CHAR(FGASIGL0.ffin_asigl0, 'DD-MM-YY') || ' ' || FGASIGL0.hfin_asigl0, 'DD-MM-YY HH24:MI:SS')
                    ELSE null END) close_at"))
                ->addSelect('FGASIGL0.DESADJU_ASIGL0')
                ->JoinFghces1Asigl0()
                ->JoinFghces1LangAsigl0()
                ->JoinSubastaAsigl0()
                ->JoinSessionAsigl0()

				#refLots contiene el código sql que identifica a los lotes
				->whereRaw($refLots);


				if(config('app.artist_in_grid', false)){
					$query->joinFgCaracteristicasAsigl0()
					->joinFgCaracteristicasHces1Asigl0()
					->addSelect('FGCARACTERISTICAS_HCES1.VALUE_CARACTERISTICAS_HCES1 as artist')
					->where('FGCARACTERISTICAS.ID_CARACTERISTICAS', config('app.ArtistCode', 0));
				}

				//son caracteristicas que solamente se mostrarán en grid en carlandia
				if(config('app.theme') == 'carlandia') {
					$query->selectRaw("(SELECT FGCARACTERISTICAS_HCES1.VALUE_CARACTERISTICAS_HCES1 FROM FGCARACTERISTICAS_HCES1 WHERE FGCARACTERISTICAS_HCES1.NUMHCES_CARACTERISTICAS_HCES1 = FGHCES1.NUM_HCES1 AND FGCARACTERISTICAS_HCES1.LINHCES_CARACTERISTICAS_HCES1 = FGHCES1.LIN_HCES1 AND IDCAR_CARACTERISTICAS_HCES1 = '25') AS matriculacion");
					$query->selectRaw("(SELECT FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE FROM FGCARACTERISTICAS_HCES1 JOIN FGCARACTERISTICAS_VALUE ON FGCARACTERISTICAS_VALUE.ID_CARACTERISTICAS_VALUE = FGCARACTERISTICAS_HCES1.IDVALUE_CARACTERISTICAS_HCES1 AND FGCARACTERISTICAS_VALUE.EMP_CARACTERISTICAS_VALUE = FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1
						WHERE FGCARACTERISTICAS_HCES1.NUMHCES_CARACTERISTICAS_HCES1 = FGHCES1.NUM_HCES1 AND FGCARACTERISTICAS_HCES1.LINHCES_CARACTERISTICAS_HCES1 = FGHCES1.LIN_HCES1 AND IDCAR_CARACTERISTICAS_HCES1 = '3') AS km");

					$query->selectRaw("(SELECT FGCARACTERISTICAS_HCES1.VALUE_CARACTERISTICAS_HCES1 FROM FGCARACTERISTICAS_HCES1 WHERE FGCARACTERISTICAS_HCES1.NUMHCES_CARACTERISTICAS_HCES1 = FGHCES1.NUM_HCES1 AND FGCARACTERISTICAS_HCES1.LINHCES_CARACTERISTICAS_HCES1 = FGHCES1.LIN_HCES1 AND IDCAR_CARACTERISTICAS_HCES1 = '61') AS importe_max");
					$query->selectRaw("(SELECT FGCARACTERISTICAS_HCES1.VALUE_CARACTERISTICAS_HCES1 FROM FGCARACTERISTICAS_HCES1 WHERE FGCARACTERISTICAS_HCES1.NUMHCES_CARACTERISTICAS_HCES1 = FGHCES1.NUM_HCES1 AND FGCARACTERISTICAS_HCES1.LINHCES_CARACTERISTICAS_HCES1 = FGHCES1.LIN_HCES1 AND IDCAR_CARACTERISTICAS_HCES1 = '62') AS importe_min");
					$query->selectRaw("(SELECT FGCARACTERISTICAS_HCES1.VALUE_CARACTERISTICAS_HCES1 FROM FGCARACTERISTICAS_HCES1 WHERE FGCARACTERISTICAS_HCES1.NUMHCES_CARACTERISTICAS_HCES1 = FGHCES1.NUM_HCES1 AND FGCARACTERISTICAS_HCES1.LINHCES_CARACTERISTICAS_HCES1 = FGHCES1.LIN_HCES1 AND IDCAR_CARACTERISTICAS_HCES1 = '55') AS matricula");
				}

				#ver la subseccion del lote
				if(\Config::get("app.subSecInGrid")){
					$query = $query->addselect("DES_SUBSEC")->leftJoin("FXSUBSEC", "GEMP_SUBSEC = '". Config::get("app.gemp")."' AND COD_SUBSEC = SUBFAM_HCES1");
				}

				#reducimos mucho los tiempos de carga si no cargamos los clob y los convertimos a varchar de 4000
				if ( env('APP_DEBUG') || \Config::get("app.clobToVarchar")) {
					$query = $query->addSelect(" dbms_lob.substr(NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1), 4000, 1 ) DESCWEB_HCES1")
					->addSelect(" dbms_lob.substr(NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1), 4000, 1 ) DESC_HCES1");
				}else{
					$query = $query->addSelect(" NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1) DESCWEB_HCES1")
					->addSelect(" NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1) DESC_HCES1");
				}

				if(config('app.opcioncar_togrid', 0)){
					$query = $query->addSelect('opcioncar_sub');
				}



				return	$query;
	}

	#listado de Carcateristicas

   public function scopeGetFeaturesAsigl0($query,   $codSub,  $refSession){

	return  $query->selectRaw("ID_CARACTERISTICAS_VALUE, IDCAR_CARACTERISTICAS_VALUE,   count(VALUE_CARACTERISTICAS_VALUE) total")
			->addSelect("MAX(NVL(VALUE_CAR_VAL_LANG, VALUE_CARACTERISTICAS_VALUE)) VALUE_CARACTERISTICAS_VALUE")
			->ActiveLotAsigl0()
			->joinFgCaracteristicasHces1Asigl0()
			->joinFgCaracteristicasValueAsigl0()
			->JoinLangCaracteristicasValueAsigl0()
			->groupBy("ID_CARACTERISTICAS_VALUE, IDCAR_CARACTERISTICAS_VALUE,  VALUE_CARACTERISTICAS_VALUE");

	}

	public function scopeJoinFghces1LangAsigl0($query)
	{
		$lang = \Tools::getLanguageComplete(\Config::get('app.locale'));
        return $query->leftjoin('FGHCES1_LANG',"FGHCES1_LANG.EMP_HCES1_LANG = FGASIGL0.EMP_ASIGL0 AND FGHCES1_LANG.NUM_HCES1_LANG = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1_LANG.LIN_HCES1_LANG = FGASIGL0.LINHCES_ASIGL0 AND FGHCES1_LANG.LANG_HCES1_LANG = '" . $lang . "'");
	}

	/**
	 * Obtiene información de lotes de una o más subastas
	 * @param array|string $cod_subs id de una subasta o array de varias
	 */
	public function getLotsInfoSales($cod_subs, $cod_cli){

		$lots = self::select('impsalhces_asigl0, sub_asigl0')
							->addSelect('prop_hces1', 'implic_hces1')
							->joinFghces1Asigl0()
							->where('cerrado_asigl0', 'S')
							->whereIn('sub_asigl0', $cod_subs);

		if(\Config::get("app.number_bids_lotlist") ){
					$lots->selectRaw(" (SELECT COUNT(DISTINCT(LICIT_ASIGL1))  FROM FGASIGL1 WHERE EMP_ASIGL1 = FGASIGL0.EMP_ASIGL0 AND SUB_ASIGL1 = FGASIGL0.SUB_ASIGL0 AND REF_ASIGL1 = FGASIGL0.REF_ASIGL0) LICITS")
					->selectRaw(" (SELECT COUNT(LIN_ASIGL1)  FROM FGASIGL1 WHERE EMP_ASIGL1 = FGASIGL0.EMP_ASIGL0 AND SUB_ASIGL1 = FGASIGL0.SUB_ASIGL0 AND REF_ASIGL1 = FGASIGL0.REF_ASIGL0) BIDS");
		}

		return $this->infoWithSales($cod_cli, $lots->get());
	}

	private function infoWithSales($cod_cli, $lots)
	{
		$lotes = 0;
		$lotesProp = 0;

		$lotesVendidosProp = 0;
		$lotesVendidos = 0;

		$sumPrecioSalida = 0;
		$sumPrecioSalidaProp = 0;

		$sumPrecioAdjudicacion = 0;
		$sumPrecioAdjudicacionProp = 0;
		$sumPrecioNoAdjudicacionProp = 0;

		$pujas = 0;
		$pujasProp = 0;

		$pujadores = 0;
		$pujadoresProp = 0;

		$auctionsCods = collect([]);

		foreach ($lots as $lot) {

			if(!$auctionsCods->contains($lot->sub_asigl0)){
				$auctionsCods->push($lot->sub_asigl0);
			}

			$lotes++;
			$sumPrecioSalida += $lot->impsalhces_asigl0;
			$pujas += $lot->bids;
			$pujadores += $lot->licits;

			if(!empty($lot->implic_hces1)){
				$lotesVendidos++;
				$sumPrecioAdjudicacion += $lot->implic_hces1;
			}

			if($lot->prop_hces1 == $cod_cli){

				$lotesProp++;
				//$sumPrecioSalidaProp += $lot->impsalhces_asigl0;
				$pujasProp += $lot->bids;
				$pujadoresProp += $lot->licits;

				if(!empty($lot->implic_hces1)){
					$lotesVendidosProp++;
					$sumPrecioAdjudicacionProp += $lot->implic_hces1;
					//summos precio solo de vendidos, para revalorizar solo estos
					$sumPrecioSalidaProp += $lot->impsalhces_asigl0;

				}
				else{
					$sumPrecioNoAdjudicacionProp += $lot->impsalhces_asigl0;
				}
			}
		}

		$auctions = $auctionsCods->count();

		return compact('lotes', 'lotesProp', 'lotesVendidos', 'lotesVendidosProp', 'sumPrecioSalida', 'sumPrecioSalidaProp', 'sumPrecioAdjudicacion', 'sumPrecioAdjudicacionProp', 'sumPrecioNoAdjudicacionProp', 'pujas', 'pujasProp', 'pujadores', 'pujadoresProp', 'auctions');
	}



	public function scopeVisibilidadsubastas($query, $codCli){

		#si no hay usuario no puede ver nada, por lo que ponemos una condicion imposible
		if(empty($codCli)){
			$query = $query->where(1,2);
		}else{

			# buscar lotes con permiso de visualizacion para este usuario o para todos
			$query = $query->leftjoin("FGVISIBILIDAD VISIBILIDAD_LOTES", "VISIBILIDAD_LOTES.EMP_VISIBILIDAD = fgasigl0.emp_asigl0 and (VISIBILIDAD_LOTES.CLI_VISIBILIDAD = '". $codCli  ."' OR   VISIBILIDAD_LOTES.CLI_VISIBILIDAD IS NULL )  AND VISIBILIDAD_LOTES.INVERSO_VISIBILIDAD ='N' AND VISIBILIDAD_LOTES.SUB_VISIBILIDAD = SUB_ASIGL0 AND VISIBILIDAD_LOTES.REF_VISIBILIDAD  = REF_ASIGL0")->

			#buscar subastas con todos los lotes, de este usuario o de todos
			leftjoin("FGVISIBILIDAD VISIBILIDAD_SUBASTAS", "VISIBILIDAD_SUBASTAS.EMP_VISIBILIDAD = fgasigl0.emp_asigl0 and (VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD = '". $codCli  ."' OR   VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD IS NULL )  AND VISIBILIDAD_SUBASTAS.INVERSO_VISIBILIDAD ='N'  AND VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD = SUB_ASIGL0 AND VISIBILIDAD_SUBASTAS.REF_VISIBILIDAD IS NULL")->

			#mirar si el usuario tiene visibilidad en todas las subastas
			leftjoin("FGVISIBILIDAD VISIBILIDAD_TODAS_SUBASTAS","VISIBILIDAD_TODAS_SUBASTAS.EMP_VISIBILIDAD = fgasigl0.emp_asigl0 and  VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD = '". $codCli  ."'  AND VISIBILIDAD_TODAS_SUBASTAS.INVERSO_VISIBILIDAD ='N' AND VISIBILIDAD_TODAS_SUBASTAS.SUB_VISIBILIDAD IS NULL")->

			#mirar si hay alguna norma inversa por subasta para este usuario o para todos sobre la subasta
			leftjoin("FGVISIBILIDAD INVERSO_VISIBILIDAD_SUBASTAS","INVERSO_VISIBILIDAD_SUBASTAS.EMP_VISIBILIDAD = EMP_ASIGL0 and INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD = SUB_ASIGL0 AND INVERSO_VISIBILIDAD_SUBASTAS.INVERSO_VISIBILIDAD ='S' AND (INVERSO_VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD = '". $codCli  ."' OR INVERSO_VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD is null ) AND INVERSO_VISIBILIDAD_SUBASTAS.REF_VISIBILIDAD IS NULL")->

			#mirar si hay alguna norma inversa para este usuario o para todos sobre el lote
			leftjoin("FGVISIBILIDAD INVERSO_VISIBILIDAD_LOTE","INVERSO_VISIBILIDAD_LOTE.EMP_VISIBILIDAD = EMP_ASIGL0 and INVERSO_VISIBILIDAD_LOTE.SUB_VISIBILIDAD = SUB_ASIGL0 AND INVERSO_VISIBILIDAD_LOTE.INVERSO_VISIBILIDAD ='S' AND (INVERSO_VISIBILIDAD_LOTE.CLI_VISIBILIDAD = '". $codCli  ."' OR INVERSO_VISIBILIDAD_LOTE.CLI_VISIBILIDAD is null ) AND INVERSO_VISIBILIDAD_LOTE.REF_VISIBILIDAD= REF_ASIGL0 ")->

			# el lote no está bloqueado, o si está bloqueado el usuario tiene permiso expreso de ver ese lote
			whereRAW("( INVERSO_VISIBILIDAD_LOTE.REF_VISIBILIDAD is null or (VISIBILIDAD_LOTES.REF_VISIBILIDAD is not null AND VISIBILIDAD_LOTES.CLI_VISIBILIDAD is not null) )")->

			#comprueba si puede ver el lote, o si puede ver la subasta entera, o si este usuario puede ver todas las subastas
			whereRAW("( VISIBILIDAD_LOTES.REF_VISIBILIDAD IS NOT NULL OR VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NOT NULL OR VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL)")->

			#si una subasta esta como invisible, solo afecta a los usuarios que pueden ver todas las subastas por lo que
			#si el usuario no tiene visibilidad universal o  si la tiene pero no tiene oculta esta subasta (para el o para todos) o si esta oculta pero el la tiene expresamente como visible
			whereRAW("( VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NULL OR ( VISIBILIDAD_TODAS_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL AND  (INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NULL OR  VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NOT NULL OR   VISIBILIDAD_LOTES.REF_VISIBILIDAD IS NOT NULL)))")->
			# SI LA SUBASTA NO ESTA BLOQUEADA O SI ESTA  BLOQUEADA PERO EL USUARIO TIENE EXPRESAMENTE VISIBLE LA SUBASTA O ALGUN LOTE
			whereRAW("(INVERSO_VISIBILIDAD_SUBASTAS.SUB_VISIBILIDAD IS NULL OR (VISIBILIDAD_SUBASTAS.CLI_VISIBILIDAD IS NOT NULL OR VISIBILIDAD_LOTES.CLI_VISIBILIDAD IS NOT NULL)  )");

		}
        return $query;

	}


	public function getActiveAuctionsWithPropietary($cod_cli, $isAdmin, $period = null){

		$lang = \Tools::getLanguageComplete(\Config::get('app.locale'));

		$lots = self::select('SUB_ASIGL0', 'auc."start"')
			->addSelect('NVL("auc_sessions_lang"."name_lang", auc."name")as name')
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->leftJoin('"auc_sessions_lang"','auc."company" = "auc_sessions_lang"."company_lang" AND auc."auction" = "auc_sessions_lang"."auction_lang" AND "auc_sessions_lang"."lang_auc_sessions_lang" = \''.$lang.'\'')
			->joinFghces1Asigl0()
			->where('PROP_HCES1', $cod_cli)

			->where(function ($query) use ($isAdmin){

				$types = ['S', 'H'];
				if($isAdmin){
					$types[] = ['A'];
				}

				$query->whereIn('SUBC_SUB', $types);
			})
			->where('cerrado_asigl0', 'S');

		if($period){
			$lots->where('auc."start"', '>=', date("Y-m-d",strtotime("-$period months")));
		}

		$lots = $lots->distinct()->orderBy('auc."start"', 'desc')->get();

		return $lots;
	}

	public function scopelog($query){
        return $query->joinUsr()->select("FSUSR.NOM_USR, FGASIGL0.*");
	}

	public function scopeJoinUsr($query){
        return $query->leftjoin("FSUSR","FSUSR.COD_USR = FGASIGL0.USR_UPDATE_ASIGL0");
	}

     #comento el código para ir usando solo las funciones  que necesitemos
/*

    #where
    public function scopeWherePKAsigl0($query, $emp, $cod_sub, $ref){
        return $query->where("emp_asigl0",$emp)
                ->where("sub_asigl0", $cod_sub)
                ->where("ref_asigl0", $ref);
    }

    # Joins





    public function scopeJoinFgOrtsecLangAsigl0($query, $lang){
       return $query->leftjoin('FGORTSEC0_LANG',"FGORTSEC0_LANG.EMP_ORTSEC0_LANG = FGORTSEC0.EMP_ORTSEC0  AND FGORTSEC0_LANG.SUB_ORTSEC0_LANG =  FGORTSEC0.SUB_ORTSEC0 AND FGORTSEC0_LANG.LIN_ORTSEC0_LANG = FGORTSEC0.LIN_ORTSEC0 AND FGORTSEC0_LANG.LANG_ORTSEC0_LANG = '$lang'");
    }




     public function scopeJoinFxsecLangAsigl0($query, $gemp, $lang){
        return $query->leftjoin("FXSEC_LANG" , "FXSEC_LANG.CODSEC_SEC_LANG =  FGHCES1.SEC_HCES1 AND FXSEC_LANG.GEMP_SEC_LANG = '$gemp' AND FXSEC_LANG.LANG_SEC_LANG = '$lang'");
    }








    public function scopeJoinAsigl1Asigl0($query){
        return $query->leftJoin('FGASIGL1','FGASIGL1.EMP_ASIGL1 = FGASIGL0.EMP_ASIGL0 AND FGASIGL1.SUB_ASIGL1 = FGASIGL0.SUB_ASIGL0  AND FGASIGL1.REF_ASIGL1 = FGASIGL0.REF_ASIGL0  ') ;
    }

    public function scopeJoinAuchouseAsigl0($query){
        return $query->Join('SUB_AUCHOUSE','SUB_AUCHOUSE.EMP_AUCHOUSE = FGASIGL0.EMP_ASIGL0 AND SUB_AUCHOUSE.CLI_AUCHOUSE = FGSUB.AGRSUB_SUB') ;
    }

    #Orders
     public function scopeOrderRefAsigl0($query, $order = "ASC"){
        return $query->orderby("FGASIGL0.REF_ASIGL0", $order);

    }

    # Funciones




    public function scopeLotSubaliaAsigl0($query, $lang){
        #necesitamos el id_auc_session en la ficha del lote, en cuanto no se necesite se quita
        return $query->select('FGHCES1.IDORIGEN_HCES1, FGHCES1.NUM_HCES1,FGHCES1.LIN_HCES1, FGSUB.SUBC_SUB, FGASIGL0.IDORIGEN_ASIGL0, FGASIGL0.REF_ASIGL0, FGSUB.COD_SUB, auc."idorigen",auc."id_auc_sessions" ,auc."reference", auc."name"')
                ->addSelect("NVL(FGHCES1_LANG.TITULO_HCES1_LANG, FGHCES1.TITULO_HCES1) TITULO_HCES1, NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1")
                ->JoinFghces1LangAsigl0($lang)
                ->ActiveLotSubaliaAsigl0() ;


    }


    #Se recoge la información del lote en la subasta original
    public function GetLotOriginAsigl0($emp, $codSub, $ref, $refSession, $lang){
        return $this->select("FGASIGL0.EMP_ASIGL0, FGASIGL0.REF_ASIGL0, FGASIGL0.CERRADO_ASIGL0, FGASIGL0.IMPSALHCES_ASIGL0, FGASIGL0.COMLHCES_ASIGL0, FGASIGL0.RETIRADO_ASIGL0,  FGASIGL0.COMPRA_ASIGL0, FGASIGL0.REMATE_ASIGL0, FGASIGL0.DESADJU_ASIGL0")
                ->addSelect("FGHCES1.NUM_HCES1, FGHCES1.LIN_HCES1,  FGHCES1.FAC_HCES1, FGHCES1.SEC_HCES1, FGHCES1.IDORIGEN_HCES1,FGHCES1.LIC_HCES1,FGHCES1.TOTALFOTOS_HCES1")
                ->addSelect("NVL(FGHCES1_LANG.TITULO_HCES1_LANG, FGHCES1.TITULO_HCES1) TITULO_HCES1,  NVL(FGHCES1_LANG.DESCWEB_HCES1_LANG, FGHCES1.DESCWEB_HCES1) DESCWEB_HCES1,   NVL(FGHCES1_LANG.DESC_HCES1_LANG, FGHCES1.DESC_HCES1) DESC_HCES1,  NVL(FGHCES1_LANG.WEBMETAT_HCES1_LANG, FGHCES1.WEBMETAT_HCES1) WEBMETAT_HCES1,  NVL(FGHCES1_LANG.WEBMETAD_HCES1_LANG, FGHCES1.WEBMETAD_HCES1) WEBMETAD_HCES1,  NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1")
                ->addSelect("FGSUB.COD_SUB, FGSUB.TIPO_SUB, FGSUB.SUBC_SUB, FGSUB.SUBABIERTA_SUB")
                ->addSelect('auc."name",auc."end", auc."start", auc."orders_start", auc."orders_end" ')
                ->addSelect('fgcsub.himp_csub')
                 ->addSelect(\DB::raw("(CASE WHEN FGASIGL0.ffin_asigl0 IS NOT NULL AND FGASIGL0.hfin_asigl0 IS NOT NULL
                    THEN TO_DATE(TO_CHAR(FGASIGL0.ffin_asigl0, 'DD-MM-YY') || ' ' || FGASIGL0.hfin_asigl0, 'DD-MM-YY HH24:MI:SS')
                    ELSE null END) close_at") )
                ->JoinFghces1Asigl0()
                ->JoinFghces1LangAsigl0($lang)
                ->JoinSubastaAsigl0()
                ->JoinSessionAsigl0()
                ->JoinCSubAsigl0()
                ->where('auc."reference"',$refSession)
                ->WherePKAsigl0( $emp, $codSub, $ref)
                ->first();
    }


    #Devuelve el lote siguiente de la subasta
    public function getNextAsigl0($emp, $cod_sub, $ref, $refSession, $lang){
        return  $this->QueryPrevNextAsigl0($emp, $cod_sub, $refSession, $lang)
                ->where("ref_asigl0",">", $ref)
                ->where('auc."reference"',$refSession)
                 ->where("sub_asigl0", $cod_sub)
                ->OrderRefAsigl0()
                ->first();
    }

    #Devuelve el lote anterior de la subasta
    public function getPreviousAsigl0($emp, $cod_sub, $ref, $refSession, $lang){
        return $this->QueryPrevNextAsigl0( $emp, $lang)
                ->where("ref_asigl0","<", $ref)
                ->where('auc."reference"',$refSession)
                ->where("sub_asigl0", $cod_sub)
                ->OrderRefAsigl0("DESC")
                ->first();
    }
    #Devuleve el lote siguiente de la categoria
     public function getNextLotCategoryAsigl0($emp,$idorigen, $subcsub, $category, $numHces, $ref, $lang){
        return  $this->QueryPrevNextAsigl0($emp, $lang)
                ->whereRaw("( (FGASIGL0.NUMHCES_ASIGL0 = $numHces AND FGASIGL0.REF_ASIGL0 > $ref) OR FGASIGL0.NUMHCES_ASIGL0 > $numHces  )")
                ->JoinFxsecAsigl0( Config::get("app.gemp"))

                ->where("FXSEC.TSEC_SEC", $category)
                ->where("FGSUB.SUBC_SUB", $subcsub) //LA SUBASTA DEBE ESTAR EN EL MISM OESTADO (ACTIVE, HISTORICA,ACTIVA ADMINISTRADOR)
                ->where("FGASIGL0.CERRADO_ASIGL0", 'N')
                ->where("FGHCES1.IDORIGEN_HCES1","!=", $idorigen)
                ->orderby("FGHCES1.NUM_HCES1")
                ->orderby("FGASIGL0.REF_ASIGL0")
                ->first();
    }
     #Devuleve el lote siguiente de la categoria
     public function getpreviousLotCategoryAsigl0($emp, $idorigen, $subcsub, $category, $numHces, $ref, $lang){
        return  $this->QueryPrevNextAsigl0($emp, $lang)
                ->whereRaw("( (FGASIGL0.NUMHCES_ASIGL0 = $numHces AND FGASIGL0.REF_ASIGL0 < $ref) OR FGASIGL0.NUMHCES_ASIGL0 < $numHces  )")
                ->JoinFxsecAsigl0( Config::get("app.gemp"))
                ->where("FXSEC.TSEC_SEC", $category)
                ->where("FGSUB.SUBC_SUB", $subcsub) //LA SUBASTA DEBE ESTAR EN EL MISMO ESTADO (ACTIVE, HISTORICA,ACTIVA ADMINISTRADOR) ASI SE NAVEGA CON MAAS COHERENCIA
                ->where("FGHCES1.IDORIGEN_HCES1","!=", $idorigen)
                ->where("FGASIGL0.CERRADO_ASIGL0", 'N')
                ->orderby("FGHCES1.NUM_HCES1","DESC")
                ->orderby("FGASIGL0.REF_ASIGL0","DESC")
                ->first();
    }

    public function scopeQueryPrevNextAsigl0($query,$emp, $lang){
        return $query->select("FGSUB.COD_SUB, FGHCES1.IDORIGEN_HCES1")
                ->addSelect("NVL(FGHCES1_LANG.TITULO_HCES1_LANG, FGHCES1.TITULO_HCES1) TITULO_HCES1, NVL(FGHCES1_LANG.WEBFRIEND_HCES1_LANG, FGHCES1.WEBFRIEND_HCES1) WEBFRIEND_HCES1")
                ->ActiveLotSubaliaAsigl0()
                ->JoinFghces1LangAsigl0($lang)
                ->where("emp_asigl0",$emp)
                ->ShowFichaAsigl0();

    }








    public function scopeGetHighlightLotsAsigl0() {

        $res = FGASIGL0::addSelect('"id_auc_sessions"','"name"',"fgsub.tipo_sub","fgasigl0.SUB_ASIGL0","fgasigl0.cerrado_asigl0","fgasigl0.ref_asigl0", "fgasigl0.FFIN_ASIGL0", "fgasigl0.hfin_asigl0", "retirado_asigl0", "remate_asigl0", "impsalhces_asigl0", "hces1.num_hces1", "hces1.lin_hces1", "imptas_asigl0","imptash_asigl0", "hces1.implic_hces1 as max_puja",
            "hces1.idorigen_hces1", "fgsub.agrsub_sub")
        ->addSelect(\DB::raw("(CASE WHEN fgasigl0.ffin_asigl0 IS NOT NULL AND fgasigl0.hfin_asigl0 IS NOT NULL THEN REPLACE(TO_DATE(TO_CHAR(fgasigl0.ffin_asigl0, 'DD/MM/YY') || ' ' || fgasigl0.hfin_asigl0, 'DD/MM/YY HH24:MI:SS'), '-', '/') ELSE NULL END) close_at"))
        ->addSelect("NVL(hces1lang.WEBFRIEND_HCES1_LANG, hces1.WEBFRIEND_HCES1) webfriend_hces1")
        ->addSelect("NVL(hces1lang.TITULO_HCES1_LANG, hces1.titulo_hces1) titulo_hces1")
        ->addSelect("NVL(hces1lang.descweb_hces1_lang, hces1.descweb_hces1) descweb_hces1")
        ->addSelect("NVL(hces1lang.desc_hces1_lang, hces1.desc_hces1) desc_hces1")
        ->join("FGHCES1 hces1"," (hces1.EMP_HCES1 = fgasigl0.EMP_ASIGL0  AND hces1.NUM_HCES1 = fgasigl0.NUMHCES_ASIGL0  AND hces1.LIN_HCES1 = fgasigl0.LINHCES_ASIGL0 )")
        ->join("\"auc_sessions\" AUC","(AUC.\"auction\" = fgasigl0.SUB_ASIGL0 and AUC.\"company\" = fgasigl0.EMP_ASIGL0 )")
        ->join("FGSUB","FGSUB.EMP_SUB =  fgasigl0.EMP_ASIGL0 and  FGSUB.cod_sub = fgasigl0.SUB_ASIGL0")
        ->leftJoin("FGHCES1_LANG hces1lang","(hces1lang.EMP_HCES1_LANG = '".\Config::get("app.emp")."'  AND hces1lang.NUM_HCES1_LANG=hces1.NUM_HCES1 and hces1lang.LIN_HCES1_LANG=hces1.LIN_HCES1 and hces1lang.LANG_HCES1_LANG='".\Config::get("app.locale")."')")
        ->JoinAuchouseAsigl0()
        ->where("SUB_AUCHOUSE.ENABLED_AUCHOUSE","S")
        ->where("FGSUB.SUBC_SUB","S")
        ->where("fgasigl0.retirado_asigl0","N")
        ->where("fgasigl0.oculto_asigl0","N")
        ->where("fgasigl0.EMP_ASIGL0",\Config::get("app.emp"))
        ->where("fgasigl0.DESTACADO_SUBALIA_ASIGL0","S")
        ->whereRaw('fgasigl0.REF_ASIGL0 >= "init_lot"')
        ->whereRaw('fgasigl0.REF_ASIGL0 <= "end_lot"')
        ->where("FGSUB.deleted_sub",'N')
        ->where("hces1.deleted_hces1",'N')
        ->where("fgasigl0.deleted_asigl0",'N')
        ->where("AUC.\"deleted\"",'N')
        ->whereRaw("((FGSUB.TIPO_SUB in('W','V')) OR (  FGSUB.TIPO_SUB = 'O' AND(TRUNC(fgasigl0.FINI_ASIGL0) < TRUNC(SYSDATE) or (fgasigl0.FINI_ASIGL0 = TRUNC(SYSDATE) AND  fgasigl0.HINI_ASIGL0 <= TO_CHAR(SYSDATE, 'HH24:MI:SS'))) ))")
        ->orderby("DBMS_RANDOM.VALUE")
        ->where("desadju_asigl0","N")
        ->limit(30)->get();

        return $res;


    }
    */
}

