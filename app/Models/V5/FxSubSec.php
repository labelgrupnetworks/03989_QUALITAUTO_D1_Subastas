<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FxSubSec extends Model
{
    protected $table = 'FXSUBSEC';
    protected $primaryKey = 'GEMP_SUBSEC, COD_SUBSEC';
    public $timestamps = false;
    public $incrementing = false;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];
    protected $attributes = [];
	public $lang;
    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'gemp_subsec' => \Config::get("app.gemp")
		];

        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('gemp', function(Builder $builder) {
            $builder->where('GEMP_SUBSEC', \Config::get("app.gemp"));
        });
	}



	public function GetSubSecFromSec($codSec){
        if(empty($codSec)){
            return array();
        }

       return $this->JoinLangFxSubSec()
                    ->addselect("FXSUBSEC.COD_SUBSEC")
					->addselect("NVL(FXSUBSEC_LANG.KEY_SUBSEC_LANG, FXSUBSEC.KEY_SUBSEC) KEY_SUBSEC")
                    ->addselect("NVL(FXSUBSEC_LANG.DES_SUBSEC_LANG, FXSUBSEC.DES_SUBSEC) DES_SUBSEC")
					->JoinFxsecSubSec()
					->where("FXSEC_SUBSEC.CODSEC_SEC_SUBSEC", $codSec)
                    ->orderby("NVL(FXSUBSEC_LANG.DES_SUBSEC_LANG, FXSUBSEC.DES_SUBSEC) ")
                    ->get()
                    ->toarray();
   }

   public function scopeJoinLangFXSUBSEC($query ){
	$lang = \Tools::getLanguageComplete(\Config::get('app.locale'));
	return $query->leftjoin("FXSUBSEC_LANG","FXSUBSEC_LANG.GEMP_SUBSEC_LANG = FXSUBSEC.GEMP_SUBSEC AND FXSUBSEC_LANG.CODSUBSEC_SUBSEC_LANG = FXSUBSEC.COD_SUBSEC AND FXSUBSEC_LANG.LANG_SUBSEC_LANG  = '". $lang . "'");

	}

	public function scopeJoinFxsecSubSec($query ){
		return $query->leftjoin("FXSEC_SUBSEC","FXSEC_SUBSEC.GEMP_SEC_SUBSEC = FXSUBSEC.GEMP_SUBSEC AND FXSEC_SUBSEC.CODSUBSEC_SEC_SUBSEC = FXSUBSEC.COD_SUBSEC");

		}

	public function GetInfoFXSUBSEC( $codSec){
		if(empty($codSec)){
			return null;
		}

		return $this->JoinLangFXSUBSEC()
			->addSelect("NVL(FXSUBSEC_LANG.META_TITULO_SUBSEC_LANG, FXSUBSEC.META_TITULO_SUBSEC) META_TITULO_SUBSEC")
			->addSelect("NVL(FXSUBSEC_LANG.META_DESCRIPTION_SUBSEC_LANG, FXSUBSEC.META_DESCRIPTION_SUBSEC) META_DESCRIPTION_SUBSEC")
			->addSelect("NVL(FXSUBSEC_LANG.META_CONTENIDO_SUBSEC_LANG, FXSUBSEC.META_CONTENIDO_SUBSEC) META_CONTENIDO_SUBSEC")
			->addSelect("NVL(FXSUBSEC_LANG.DES_SUBSEC_LANG, FXSUBSEC.DES_SUBSEC) DES_SUBSEC")
			->addSelect("NVL(FXSUBSEC_LANG.KEY_SUBSEC_LANG, FXSUBSEC.KEY_SUBSEC) KEY_SUBSEC")
			->where("FXSUBSEC.COD_SUBSEC", $codSec)
			->first();

	}


	#Genera todas las claves de key_subsec
	static function generateKeySec(){
		$subsections = self::select("gemp_subsec, cod_subsec, des_subsec, key_subsec")->whereRaw("key_subsec is null")->get();

		if(!empty($subsections)){

			foreach($subsections as $subsection){
				$subsection->key_subsec = \Str::slug($subsection->des_subsec);
				self::where('gemp_subsec', $subsection->gemp_subsec)->where('cod_subsec', $subsection->cod_subsec)->update(array('key_subsec' => $subsection->key_subsec));

			}

		}


	}


    #comento todas las funciones que vienen de subalia para que solo se usen las quesean necesarias, cuando se necesite una se saca del comentario
    /*


    public function scopeJoinHces1FXSUBSEC($query, $emp){
        return $query->join('FGHCES1', "FGHCES1.EMP_HCES1 = '$emp' AND FGHCES1.SEC_HCES1 = FXSUBSEC.COD_SUBSEC");
    }

   #LISTADO DE SECCIONES



    public function GetActiveForOrtsecFXSUBSEC($gemp, $emp){
        $sections = $this->GetActiveFXSUBSEC($gemp, $emp)->get()->toarray();
        $res = array();
        foreach($sections as $sec){
            $res[$sec["tsec_SUBSEC"]][] = $sec;
        }
        return $res;

    }
    */

}
