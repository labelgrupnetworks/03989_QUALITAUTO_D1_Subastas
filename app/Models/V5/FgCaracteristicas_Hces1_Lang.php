<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgCaracteristicas_Hces1_Lang extends Model
{
    protected $table = 'fgcaracteristicas_hces1_lang';
    protected $primaryKey = ' EMP_CAR_HCES1_LANG, NUMHCES_CAR_HCES1_LANG, LINHCES_CAR_HCES1_LANG, IDCAR_CAR_HCES1_LANG, LANG_CAR_HCES1_LANG,';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

      #definimos la variable emp para no tener que indicarla cada vez
      public function __construct(array $vars = []){
        $this->attributes=[
            'EMP_CAR_HCES1_LANG' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_car_hces1_lang', \Config::get("app.emp"));
        });
    }

    public function scopeWhereUpdateApi($query, $item){
        return $query->where('NUMHCES_CAR_HCES1_LANG', $item["numhces_car_hces1_lang"])
                        ->where('LINHCES_CAR_HCES1_LANG', $item["linhces_car_hces1_lang"])
                        ->where('IDCAR_CAR_HCES1_LANG', $item["idcar_car_hces1_lang"])
                        ->where('LANG_CAR_HCES1_LANG', $item["lang_car_hces1_lang"]);
	}

	static function getByLot($num, $lin){
		$res = array();
		$caracteristicasLang = self::where("NUMHCES_CAR_HCES1_LANG", $num)
					->where("LINHCES_CAR_HCES1_LANG",$lin)
					->get();

		foreach($caracteristicasLang as $caracteristicaLang){
			$lang = array_search($caracteristicaLang->lang_car_hces1_lang, config('app.language_complete'));
			if(empty($res[$lang])){

				$res[$lang] = array();
			}

			$res[$lang][ $caracteristicaLang->idcar_car_hces1_lang] =  $caracteristicaLang;
		}

		return $res;

	}

}
