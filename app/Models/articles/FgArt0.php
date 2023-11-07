<?php

# Ubicacion del modelo
namespace App\Models\articles;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
class FgArt0 extends Model
{
    protected $table = 'FGART0';
    protected $primaryKey = 'ID_ART0';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_art0' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_art0', Config::get("app.emp"));
        });
	}

	public function scopeActivo($query){
		return $query->where("BAJA_ART0","N")
					->wherein("WEB_ART0",array("S","2"));
	}
	public function scopeArtActivo($query){
		return $query->where("BAJAT_ART","N")
					->wherein("WEB_ART",array("S","2"));
	}
	public function scopeJoinOrtsec1($query)
	{
		return $query->join("FGORTSEC1", "FGORTSEC1.EMP_ORTSEC1 = FGART0.EMP_ART0 AND FGORTSEC1.SEC_ORTSEC1 = FGART0.SEC_ART0");
	}

	public function scopeJoinOrtsec0($query, $withLangs = false)
	{
		return $query->join("FGORTSEC0", "FGORTSEC0.EMP_ORTSEC0 = FGORTSEC1.EMP_ORTSEC1 AND FGORTSEC0.LIN_ORTSEC0 = FGORTSEC1.LIN_ORTSEC1");
	}

	public function scopeJoinOrtsec0Lang($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		return $query->join("FGORTSEC0", "FGORTSEC0.EMP_ORTSEC0 = FGORTSEC1.EMP_ORTSEC1 AND FGORTSEC0.LIN_ORTSEC0 = FGORTSEC1.LIN_ORTSEC1")
			->join("FGORTSEC0_LANG", "FGORTSEC0_LANG.EMP_ORTSEC0_LANG = FGORTSEC0.EMP_ORTSEC0 AND FGORTSEC0_LANG.SUB_ORTSEC0_LANG = FGORTSEC0.SUB_ORTSEC0 AND FGORTSEC0_LANG.LIN_ORTSEC0_LANG = FGORTSEC0.LIN_ORTSEC0 AND FGORTSEC0_LANG.LANG_ORTSEC0_LANG = '$lang'");
	}

	public function scopeJoinSec($query, $withLangs = false)
	{
		return $query->join("FXSEC", "FXSEC.GEMP_SEC = ". Config::get("app.gemp") . "  AND FXSEC.COD_SEC = FGART0.SEC_ART0" );
	}

	public function scopeJoinSecLang($query){
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		return $query
			->join("FXSEC", "FXSEC.GEMP_SEC = ". Config::get("app.gemp") . "  AND FXSEC.COD_SEC = FGART0.SEC_ART0" )
			->join("FXSEC_LANG", "FXSEC_LANG.GEMP_SEC_LANG = FXSEC.GEMP_SEC AND FXSEC_LANG.CODSEC_SEC_LANG = FXSEC.COD_SEC AND FXSEC_LANG.LANG_SEC_LANG = '$lang'");
	}

	public function scopeJoinArt($query  ){
        return $query->join("FGART "," FGART.EMP_ART = FGART0.EMP_ART0 AND   FGART.IDART0_ART  = FGART0.ID_ART0");
	}

	public function scopeJoinLineasVariantes($query ){

        return $query->join("FGART_LINEASVARIANTES ","FGART_LINEASVARIANTES.ID_FGART  = FGART.ID_ART");
	}

	public function scopeJoinValVariantes($query){

        return $query->join('FGART_VALVARIANTES',"FGART_VALVARIANTES.EMP_VALVARIANTE = FGART.EMP_ART AND FGART_VALVARIANTES.ID_VALVARIANTES = FGART_LINEASVARIANTES.ID_VALVARIANTES");
	}

	public function scopeJoinVariantes($query){

        return $query->join('FGART_VARIANTES',"FGART_VARIANTES.EMP_VARIANTE = FGART_VALVARIANTES.EMP_VALVARIANTE  AND  FGART_VARIANTES.ID_VARIANTE = FGART_VALVARIANTES.ID_VARIANTE");
	}

	public function scopeJoinMarca($query)
	{
		return $query->join('FGMARCA',"FGMARCA.EMP_MARCA = FGART0.EMP_ART0 AND FGMARCA.MARCA_MARCA = FGART0.MARCA_ART0");
	}

	public function scopeJoinFamilia($query)
	{
		return $query->join('FGFAMART',"FGFAMART.EMP_FAMART = FGART0.EMP_ART0 AND FGFAMART.COD_FAMART = FGART0.FAMART_ART0");
	}

	public function scopeJoinFamiliaLang($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		return $query
			->join('FGFAMART',"FGFAMART.EMP_FAMART = FGART0.EMP_ART0 AND FGFAMART.COD_FAMART = FGART0.FAMART_ART0")
			->join('FGFAMART_LANG',"FGFAMART_LANG.ID_FAMART = FGFAMART.ID_FAMART AND FGFAMART_LANG.LANG_FAMART_LANG = '$lang'");
	}

	public function scopeJoinFgPedc1($query){
        return $query->join('FGPEDC1', 'FGPEDC1.EMP_PEDC1 = FGART0.EMP_ART0 AND FGPEDC1.ART_PEDC1 = FGART.COD_ART');
    }

	public function scopeJoinFgPedc0($query){
        return $query->join('FGPEDC0', 'FGPEDC0.EMP_PEDC0 = FGART0.EMP_ART0 AND FGPEDC0.NUM_PEDC0 = FGPEDC1.NUM_PEDC1');
    }

	public function scopeLeftJoinFgArt0Lang($query){
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));
		return $query->leftJoin('FGART0_LANG', "FGART0_LANG.ID_ART0 = FGART0.ID_ART0 AND FGART0_LANG.LANG_ART0_LANG = '$lang'");
	}

	#tallas y colores, se pueden poner mas condiciones antes de hacer la llamada
	public function scopegetTallaColor($query, $onlyPrincipalLang = true)
	{


		if(!$onlyPrincipalLang) {
			$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));

		#DEBEMOS CONTAR LOS DISTINTOS IDART0, PARA QUE SOLO CUENTE UNA VEZ POR PRODUCTO
		return $query
			->select("NVL(FGART_VARIANTES_LANG.NAME_VARIANTE_LANG, FGART_VARIANTES.NAME_VARIANTE) NAME_VARIANTE, FGART_VALVARIANTES.VALOR_VALVARIANTE,FGART_VALVARIANTES.ID_VALVARIANTES,FGART_VARIANTES.ID_VARIANTE, COUNT(DISTINCT(FGART.IDART0_ART) ) AS CUANTOS")
			->JoinArt()->ArtActivo()->JoinLineasVariantes()->JoinValVariantes()->JoinVariantes()
			->leftJoin(
				'FGART_VARIANTES_LANG',
				"FGART_VARIANTES_LANG.ID_VARIANTE = FGART_VARIANTES.ID_VARIANTE AND FGART_VARIANTES_LANG.LANG_VARIANTE_LANG = '$lang'")
		->groupby("FGART_VALVARIANTES.ID_VALVARIANTES,FGART_VARIANTES.ID_VARIANTE, NVL(FGART_VARIANTES_LANG.NAME_VARIANTE_LANG, FGART_VARIANTES.NAME_VARIANTE), FGART_VALVARIANTES.VALOR_VALVARIANTE")
		->orderby("NVL(FGART_VARIANTES_LANG.NAME_VARIANTE_LANG, FGART_VARIANTES.NAME_VARIANTE), FGART_VALVARIANTES.VALOR_VALVARIANTE");
		}

		return $query->select("FGART_VARIANTES.NAME_VARIANTE, FGART_VALVARIANTES.VALOR_VALVARIANTE,FGART_VALVARIANTES.ID_VALVARIANTES,FGART_VARIANTES.ID_VARIANTE, COUNT(DISTINCT(FGART.IDART0_ART) ) AS CUANTOS")
		->JoinArt()->ArtActivo()->JoinLineasVariantes()->JoinValVariantes()->JoinVariantes()
		->groupby("FGART_VALVARIANTES.ID_VALVARIANTES,FGART_VARIANTES.ID_VARIANTE,FGART_VARIANTES.NAME_VARIANTE,FGART_VALVARIANTES.VALOR_VALVARIANTE")
		->orderby("FGART_VARIANTES.NAME_VARIANTE, FGART_VALVARIANTES.VALOR_VALVARIANTE");
	}

	#tallas y colores, se pueden poner mas condiciones antes dee hacer la llamada
	public function scopegetTallasColores($query){
		#DEBEMOS CONTAR LOS DISTINTOS IDART0, PARA QUE SOLO CUENTE UNA VEZ POR PRODUCTO
		$elementos = $query->select("FGART_VARIANTES.NAME_VARIANTE, FGART_VALVARIANTES.VALOR_VALVARIANTE,FGART_VALVARIANTES.ID_VALVARIANTES,FGART_VARIANTES.ID_VARIANTE, COUNT(DISTINCT(FGART.IDART0_ART) ) AS CUANTOS")
		->JoinArt()->JoinLineasVariantes()->JoinValVariantes()->JoinVariantes()
		->groupby("FGART_VALVARIANTES.ID_VALVARIANTES,FGART_VARIANTES.ID_VARIANTE,FGART_VARIANTES.NAME_VARIANTE,FGART_VALVARIANTES.VALOR_VALVARIANTE")
		->orderby("FGART_VARIANTES.NAME_VARIANTE, FGART_VALVARIANTES.VALOR_VALVARIANTE")->get();

		$tallasColores = array();

		foreach($elementos as $elemento){
			if(empty($tallasColores[$elemento->id_variante])){
				$tallasColores[$elemento->id_variante] = array();
			}
			$tallasColores[$elemento->id_variante][] = $elemento;
		}

		return $tallasColores;
	}
}

