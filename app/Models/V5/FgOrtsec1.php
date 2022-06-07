<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FgOrtsec1 extends Model
{
    protected $table = 'FGORTSEC1';
    protected $primaryKey = 'EMP_ORTSEC1, SUB_ORTSEC1, LIN_ORTSEC1, SEC_ORTSEC1';

    public $timestamps = false;
    public $incrementing = false;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];
    protected $attributes = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_ortsec1' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_ortsec1', \Config::get("app.emp"));
        });
    }

     #esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
     public function scopeWhereUpdateApi($query, $item){
        #la subasta debe ser la 0
		return $query->where('sub_ortsec1', "0")->where('sec_ortsec1', $item["sec_ortsec1"]);
		#Quito lin ortsec por que si no no em deja cambiarlo de familia.
		#->where('lin_ortsec1', $item["lin_ortsec1"])
	}

	public function scopeJoinFgOrtsec0($query){
        return $query->join('FGORTSEC0', 'FGORTSEC0.EMP_ORTSEC0 = FGORTSEC1.EMP_ORTSEC1 AND FGORTSEC0.LIN_ORTSEC0 = FGORTSEC1.LIN_ORTSEC1');
    }

	public function scopeJoinFxSec($query){
        return $query->join('FXSEC', 'FXSEC.COD_SEC = FGORTSEC1.SEC_ORTSEC1')
					->where("GEMP_SEC", \Config::get("app.gemp"));
    }

}
