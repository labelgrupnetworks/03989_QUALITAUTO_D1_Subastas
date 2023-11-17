<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgCaracteristicas_Value extends Model
{
    protected $table = 'fgcaracteristicas_value';
    protected $primaryKey = 'ID_CARACTERISTICAS_VALUE';

    public $timestamps = false;
    public $incrementing = true;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

      #definimos la variable emp para no tener que indicarla cada vez
      public function __construct(array $vars = []){
        $this->attributes=[
            'EMP_CARACTERISTICAS_VALUE' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_caracteristicas_value', \Config::get("app.emp"));
        });
    }

    public function scopeWhereUpdateApi($query, $item){
        return $query->where('ID_CARACTERISTICAS_VALUE', $item["id_caracteristicas_value"]);

	}

	#devuelve la estructura para guardar en un input select del formulario
	public function scopeSelectInput($query){
       	$fgCaracteristicas_Value = $query->select("id_caracteristicas_value, value_caracteristicas_value")->get();
			$values = array();
			foreach($fgCaracteristicas_Value as $value){
				$values[$value->id_caracteristicas_value] = $value->value_caracteristicas_value;
			}
		return $values;

	}

	static function SelectAllForInput(){
		$fgCaracteristicas_Value = self::select("id_caracteristicas_value,idcar_caracteristicas_value, value_caracteristicas_value")->orderby("value_caracteristicas_value")->get();
		$values = array();
		foreach($fgCaracteristicas_Value as $value){
			if(empty($values[$value->idcar_caracteristicas_value])){
				$values[$value->idcar_caracteristicas_value]= array();
			}
			$values[$value->idcar_caracteristicas_value][$value->id_caracteristicas_value] = $value->value_caracteristicas_value;
		}
	 return $values;

 }

	/**
	 * Recibe una descripción de carcateristica,devuelve su ID, la crea si no existe
	 */
	static function addFeature($idFeature, $newValue, &$valueList =array())
	{

		$idFeatureValue=null;
		$new = false;

		#si se pasa un array con todos los valores de caracteriticas, esto se hace para no consultar a base de datos de manera masiva cuando cargamos por excel o xml
		if(count($valueList) > 0){

				if(!empty($valueList[$idFeature])){
					$idFeatureValue =array_search($newValue, $valueList[$idFeature]);

				}

		}else{
			#buscamos si existe en base de datos
			$caracteristicaValue = self::select("ID_CARACTERISTICAS_VALUE")->where("IDCAR_CARACTERISTICAS_VALUE", $idFeature)->where("LOWER(VALUE_CARACTERISTICAS_VALUE)", mb_strtolower($newValue))->first();
			if (!empty($caracteristicaValue)) {
				$idFeatureValue = $caracteristicaValue->id_caracteristicas_value;
			}
		}
		#si no existe lo creamos
		if (empty($idFeatureValue)) {
			$featureValue = array( "IDCAR_CARACTERISTICAS_VALUE" => $idFeature,  "VALUE_CARACTERISTICAS_VALUE" => $newValue);

			self::create($featureValue);

			$new = true;

			#recuperamos el id
			$caracteristicaValue = self::select("ID_CARACTERISTICAS_VALUE")->where("IDCAR_CARACTERISTICAS_VALUE", $idFeature)->where("LOWER(VALUE_CARACTERISTICAS_VALUE)", mb_strtolower($newValue))->first();
			$idFeatureValue = $caracteristicaValue->id_caracteristicas_value;
			#lo agregamos al array de value
			if(empty($valueList[$idFeature])){
				$valueList[$idFeature] = array();
			}
			$valueList[$idFeature][$idFeatureValue]=$newValue;

		}

		#devolvemos el id e indicamos si es nuevo o no
		return ["new" => $new, "idFeatureValue" => $idFeatureValue];
	}

}
