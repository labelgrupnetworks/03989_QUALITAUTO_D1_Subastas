<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgCaracteristicas_Value_Lang extends Model
{
    protected $table = 'fgcaracteristicas_value_lang';
    protected $primaryKey = ' EMP_CAR_VAL_LANG, IDCARVAL_CAR_VAL_LANG';

    public $timestamps = false;
    public $incrementing = false;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

      #definimos la variable emp para no tener que indicarla cada vez
      public function __construct(array $vars = []){
        $this->attributes=[
            'EMP_CAR_VAL_LANG' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }

    public function scopeWhereUpdateApi($query, $item){
        return $query->where('IDCARVAL_CAR_VAL_LANG', $item["idcarval_car_val_lang"]);

	}

}
