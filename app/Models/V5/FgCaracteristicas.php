<?php

# Ubicacion del modelo
namespace App\Models\V5;

use App\Support\Localization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
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

	static function getAllFeatures()
	{
		$features = self::query()
					->select("id_caracteristicas, filtro_caracteristicas, value_caracteristicas")
					->JoinLang()
					->orderBy("orden_caracteristicas")
					->get();

		return $features->keyBy('id_caracteristicas')->all();
	}

	public function scopeJoinLang($query)
	{
		$lang = Localization::getLocaleComplete();
		return $query->leftJoin('fgcaracteristicas_lang', function ($join) use ($lang) {
			$join->on('emp_caracteristicas_lang', '=', 'emp_caracteristicas')
				->on('id_caracteristicas_lang', '=', 'id_caracteristicas')
				->where('lang_caracteristicas_lang', $lang);
		})
		->addSelect("coalesce(name_caracteristicas_lang, name_caracteristicas) as name_caracteristicas");
	}

}
