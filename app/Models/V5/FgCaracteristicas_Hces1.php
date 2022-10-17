<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgCaracteristicas_Hces1 extends Model
{
    protected $table = 'fgcaracteristicas_hces1';
    protected $primaryKey = ' EMP_CARACTERISTICAS_HCES1, NUMHCES_CARACTERISTICAS_HCES1, LINHCES_CARACTERISTICAS_HCES1, IDCAR_CARACTERISTICAS_HCES1,';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

      #definimos la variable emp para no tener que indicarla cada vez
      public function __construct(array $vars = []){
        $this->attributes=[
            'EMP_CARACTERISTICAS_HCES1' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_caracteristicas_hces1', \Config::get("app.emp"));
        });
    }

    public function scopeWhereUpdateApi($query, $item){
        return $query->where('NUMHCES_CARACTERISTICAS_HCES1', $item["numhces_caracteristicas_hces1"])
                        ->where('LINHCES_CARACTERISTICAS_HCES1', $item["linhces_caracteristicas_hces1"])
                        ->where('IDCAR_CARACTERISTICAS_HCES1', $item["idcar_caracteristicas_hces1"]);
	}

	public function scopeJoinLang($query){
		$lang =  \Tools::getLanguageComplete(\Config::get('app.locale'));
		return $query->leftjoin("FGCARACTERISTICAS_HCES1_LANG", " EMP_CAR_HCES1_LANG = EMP_CARACTERISTICAS_HCES1 AND NUMHCES_CAR_HCES1_LANG = NUMHCES_CARACTERISTICAS_HCES1  AND LINHCES_CAR_HCES1_LANG = LINHCES_CARACTERISTICAS_HCES1  AND IDCAR_CAR_HCES1_LANG = IDCAR_CARACTERISTICAS_HCES1 AND LANG_CAR_HCES1_LANG = '$lang'");
	}

	public function scopeJoinCaracteristicas($query){
		return $query->join("FGCARACTERISTICAS", " EMP_CARACTERISTICAS = EMP_CARACTERISTICAS_HCES1 and ID_CARACTERISTICAS = IDCAR_CARACTERISTICAS_HCES1");
	}

	public function scopeJoinCaracteristicasLang($query){
		$lang =  \Tools::getLanguageComplete(\Config::get('app.locale'));
		return $query->leftjoin("FGCARACTERISTICAS_LANG", " EMP_CARACTERISTICAS_LANG = EMP_CARACTERISTICAS and ID_CARACTERISTICAS_LANG = ID_CARACTERISTICAS AND LANG_CARACTERISTICAS_LANG = '$lang'");
	}

	public function scopeJoinCateristicasValue($query){
		$lang =  \Tools::getLanguageComplete(\Config::get('app.locale'));
		return $query->leftjoin("FGCARACTERISTICAS_VALUE", "EMP_CARACTERISTICAS_VALUE = EMP_CARACTERISTICAS_HCES1 and IDCAR_CARACTERISTICAS_VALUE = IDCAR_CARACTERISTICAS_HCES1 and ID_CARACTERISTICAS_VALUE = IDVALUE_CARACTERISTICAS_HCES1")
					->leftjoin("FGCARACTERISTICAS_VALUE_LANG", "EMP_CAR_VAL_LANG = EMP_CARACTERISTICAS_HCES1  and IDCARVAL_CAR_VAL_LANG = IDVALUE_CARACTERISTICAS_HCES1 AND LANG_CAR_VAL_LANG ='$lang'");

	}

	static function getByLot($num, $lin){
		return self::select("id_caracteristicas", " nvl(name_caracteristicas_lang, name_caracteristicas) name_caracteristicas, nvl( nvl(VALUE_CAR_VAL_LANG, VALUE_CARACTERISTICAS_VALUE), nvl(value_car_hces1_lang, value_caracteristicas_hces1 )) value_caracteristicas_hces1, IDVALUE_CARACTERISTICAS_HCES1")
				->joinlang()
				->JoinCaracteristicas()
				->JoinCaracteristicasLang()
				->JoinCateristicasValue()
				->where("NUMHCES_CARACTERISTICAS_HCES1", $num)
				->where("LINHCES_CARACTERISTICAS_HCES1", $lin)
				->orderBy("ORDEN_CARACTERISTICAS")
				->get()
				->mapWithKeys(function ($item) {
					return [$item['id_caracteristicas'] => $item];
				});

	}


	/*
	select name_caracteristicas, nvl( nvl(VALUE_CAR_VAL_LANG, VALUE_CARACTERISTICAS_VALUE), nvl(value_car_hces1_lang, value_caracteristicas_hces1 )) value_caracteristicas_hces1, fgcaracteristicas_hces1.* from fgcaracteristicas_hces1
left join FGCARACTERISTICAS_HCES1_LANG ON EMP_CAR_HCES1_LANG = EMP_CARACTERISTICAS_HCES1 AND NUMHCES_CAR_HCES1_LANG = NUMHCES_CARACTERISTICAS_HCES1  AND LINHCES_CAR_HCES1_LANG = LINHCES_CARACTERISTICAS_HCES1 AND LANG_CAR_HCES1_LANG = 'en-GB'
join fgcaracteristicas on EMP_CARACTERISTICAS = EMP_CARACTERISTICAS_HCES1 and ID_CARACTERISTICAS = IDCAR_CARACTERISTICAS_HCES1
left join FGCARACTERISTICAS_VALUE on EMP_CARACTERISTICAS_VALUE = EMP_CARACTERISTICAS_HCES1 and IDCAR_CARACTERISTICAS_VALUE = IDCAR_CARACTERISTICAS_HCES1 and ID_CARACTERISTICAS_VALUE = IDVALUE_CARACTERISTICAS_HCES1
left join FGCARACTERISTICAS_VALUE_LANG on EMP_CAR_VAL_LANG = EMP_CARACTERISTICAS_HCES1  and IDCARVAL_CAR_VAL_LANG = IDVALUE_CARACTERISTICAS_HCES1 AND LANG_CAR_VAL_LANG ='en-GB'

where NUMHCES_CARACTERISTICAS_HCES1 = 2 and LINHCES_CARACTERISTICAS_HCES1 = 1
order by ORDEN_CARACTERISTICAS
*/


/*
    public function deleteIdOrigin(){

      $sql="  DELETE FROM (
            select FGCARACTERISTICAS_HCES1.* FROM FGCARACTERISTICAS_HCES1
            JOIN FGHCES1 ON EMP_HCES1= EMP_CARACTERISTICAS_HCES1 AND NUM_HCES1 = NUMHCES_CARACTERISTICAS_HCES1 AND LIN_HCES1 = LINHCES_CARACTERISTICAS_HCES1
            WHERE emp= :emp IDORIGEN_HCES1 in (:idorigins))";


            $bindings = array(
                            'emp'           => Config::get('app.emp'),
                            'idorigins'     => $origins
                            );

            DB::select($sql, $bindings);
    }
*/
}
