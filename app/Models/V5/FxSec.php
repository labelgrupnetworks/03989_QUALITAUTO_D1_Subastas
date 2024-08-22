<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FxSec extends Model
{
    protected $table = 'FXSEC';
    protected $primaryKey = 'gemp_sec, cod_sec';
    public $timestamps = false;
    public $incrementing = false;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];
    protected $attributes = [];
	public $lang;
    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'gemp_sec' => \Config::get("app.gemp")
		];

        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('gemp', function(Builder $builder) {
            $builder->where('gemp_sec', \Config::get("app.gemp"));
        });
    }

    protected $casts = [
        'lin_ortsec1' => 'int',
        'orden_ortsec1' => 'int',
    ];

    #esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
    public function scopeWhereUpdateApi($query, $item){
        return $query->where('cod_sec', $item["cod_sec"]);
	}


	#Genera todas las claves de key_sec
	static function generateKeySec(){
		$sections = self::select("gemp_sec, cod_sec, des_sec, key_sec")->whereRaw("key_sec is null")->get();

		if(!empty($sections)){

			foreach($sections as $section){
				$section->key_sec = \Str::slug($section->des_sec);
				self::where('gemp_sec', $section->gemp_sec)->where('cod_sec', $section->cod_sec)->update(array('key_sec' => $section->key_sec));


			}

		}


	}



    public function scopeJoinFgOrtsecFxSec($query){
        return $query->where("FGORTSEC1.EMP_ORTSEC1",\Config::get("app.emp") )->leftjoin("FGORTSEC1","FGORTSEC1.SEC_ORTSEC1 = FXSEC.COD_SEC");

	}

	public function GetSecFromLinFxsec($lin){
        if(empty($lin)  || !is_numeric($lin)){
            return array();
		}


       return $this->JoinLangFxSec()
                    ->addselect("FXSEC.COD_SEC")
                    ->addselect("NVL(FXSEC_LANG.KEY_SEC_LANG, FXSEC.KEY_SEC) KEY_SEC")
                    ->addselect("NVL(FXSEC_LANG.DES_SEC_LANG, FXSEC.DES_SEC) DES_SEC")
					->JoinFgOrtsecFxSec()
					->where("FGORTSEC1.LIN_ORTSEC1", $lin)
					->where("FGORTSEC1.SUB_ORTSEC1", "0")
					->orderby("FGORTSEC1.ORDEN_ORTSEC1", "ASC")
                    //->orderby("NVL(FXSEC_LANG.DES_SEC_LANG, FXSEC.DES_SEC)")
                    ->get()
                    ->toarray();
   }

    public function GetCodFromKey($key){
		if(empty($key)  ){
			return null;
		}


   	$sec =  $this->JoinLangFxSec()
				->addselect("FXSEC.COD_SEC")

				->where("NVL(FXSEC_LANG.KEY_SEC_LANG, FXSEC.KEY_SEC) ", $key)

				//->orderby("NVL(FXSEC_LANG.DES_SEC_LANG, FXSEC.DES_SEC)")
				->first();
	if(empty($sec)){
		return null;
	}

		return $sec->cod_sec;

}

   public function scopeJoinLangFxSec($query ){
	$lang = ToolsServiceProvider::getLanguageComplete(\Config::get('app.locale'));
	return $query->leftjoin("FXSEC_LANG","FXSEC_LANG.GEMP_SEC_LANG = FXSEC.GEMP_SEC AND FXSEC_LANG.CODSEC_SEC_LANG = FXSEC.COD_SEC AND FXSEC_LANG.LANG_SEC_LANG  = '". $lang . "'");

	}

	public function GetInfoFxsec( $codSec){
		if(empty($codSec)){
			return null;
		}

		return $this->JoinLangFxSec()
			->addSelect("NVL(FXSEC_LANG.META_TITULO_SEC_LANG, FXSEC.META_TITULO_SEC) META_TITULO_SEC")
			->addSelect("NVL(FXSEC_LANG.META_DESCRIPTION_SEC_LANG, FXSEC.META_DESCRIPTION_SEC) META_DESCRIPTION_SEC")
			->addSelect("NVL(FXSEC_LANG.META_CONTENIDO_SEC_LANG, FXSEC.META_CONTENIDO_SEC) META_CONTENIDO_SEC")
			->addSelect("NVL(FXSEC_LANG.DES_SEC_LANG, FXSEC.DES_SEC) DES_SEC")
			->addSelect("NVL(FXSEC_LANG.KEY_SEC_LANG, FXSEC.KEY_SEC)  KEY_SEC")
			->where("FXSEC.COD_SEC", $codSec)
			->first();

	}
	#mostramso en un array el listado de secciones asociados con la subasta 0
	static function GetActiveFxSec(){
        $sections = FxSec::JoinFgOrtsecFxSec()->select("COD_SEC, DES_SEC")->where("SUB_ORTSEC1",0)->get();
        $res = array();
        foreach($sections as $sec){
            $res[$sec->cod_sec]= $sec->des_sec;
        }
        return $res;

    }

	static function newCodSec(){
		$codigos = array();

		#numeros, no se puede usar el 0
		for($i=1;$i<=9; $i++){
			$codigos[]=$i;
		}
		#letras mayusculas
		for($i=65;$i<=90; $i++){
			$codigos[]=chr($i);
		}
		#letras minusculas
		for($i=97;$i<=122; $i++){
			$codigos[]=chr($i);
		}



		#cogemos el valor mas alto
		$codMax = self::select("max(cod_sec) as cod")->whereraw("LENGTH(cod_sec)= 2")->first()->cod;
		# si aun no hay códigos ponemos el primero
		if(empty($codMax)){
			[$decenas,$unidades]=["1","1"];
		}else{
			[$decenas,$unidades]=str_split($codMax,1);


			$indiceUnidades = array_search($unidades, $codigos) +1;


			#si hemos llegado al limite
			if(empty($codigos[$indiceUnidades])){
				$indiceUnidades=0;
				$indiceDecenas = array_search($decenas, $codigos) +1;
				#no miro el limite por que fallaria igual...
				$decenas = $codigos[$indiceDecenas];
			}

			$unidades = $codigos[$indiceUnidades];
		}

		return $decenas.$unidades;



	}

    #comento todas las funciones que vienen de subalia para que solo se usen las quesean necesarias, cuando se necesite una se saca del comentario
    /*


    public function scopeJoinHces1FxSec($query, $emp){
        return $query->join('FGHCES1', "FGHCES1.EMP_HCES1 = '$emp' AND FGHCES1.SEC_HCES1 = FXSEC.COD_SEC");
    }

   #LISTADO DE SECCIONES



    public function GetActiveForOrtsecFxSec($gemp, $emp){
        $sections = $this->GetActiveFxSec($gemp, $emp)->get()->toarray();
        $res = array();
        foreach($sections as $sec){
            $res[$sec["tsec_sec"]][] = $sec;
        }
        return $res;

    }
    */

}
