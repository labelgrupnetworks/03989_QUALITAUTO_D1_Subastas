<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgCaracteristicas extends Model
{
    protected $table = 'fgcaracteristicas';
    protected $primaryKey = ' EMP_CARACTERISTICAS, ID_CARACTERISTICAS';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

      #definimos la variable emp para no tener que indicarla cada vez
      public function __construct(array $vars = []){
        $this->attributes=[
            'EMP_CARACTERISTICAS' => \Config::get("app.emp")
		];

        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_caracteristicas', \Config::get("app.emp"));
        });
    }

    static function getFeatures(){
		$featuresBBDD = self::select("ID_CARACTERISTICAS")

					->JoinLang()
					->where("FILTRO_CARACTERISTICAS", "S")
					->orderBy("ORDEN_CARACTERISTICAS");
		#poner multiidioma solo si no se entra en el idioma	principal

		$features = Array();
		foreach($featuresBBDD->get() as $feature){
			$features[$feature->id_caracteristicas] = $feature->name_caracteristicas;
		}
		return $features;
	}

	static function getAllFeatures(){
		$featuresBBDD = self::select("NAME_CARACTERISTICAS, ID_CARACTERISTICAS, FILTRO_CARACTERISTICAS, VALUE_CARACTERISTICAS")
					->orderBy("ORDEN_CARACTERISTICAS");

		$features = Array();
		foreach($featuresBBDD->get() as $feature){
			$features[$feature->id_caracteristicas] = $feature;
		}
		return $features;
	}

	public function scopeJoinLang($query){
		$lang = ToolsServiceProvider::getLanguageComplete(\Config::get('app.locale'));
		return $query->leftjoin("FGCARACTERISTICAS_LANG","EMP_CARACTERISTICAS_LANG = EMP_CARACTERISTICAS AND ID_CARACTERISTICAS_LANG = ID_CARACTERISTICAS AND LANG_CARACTERISTICAS_LANG= '". $lang . "'")
						->addSelect("NVL(NAME_CARACTERISTICAS_LANG, NAME_CARACTERISTICAS) NAME_CARACTERISTICAS");



	}


}
