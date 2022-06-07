<?php

# Ubicacion del modelo
namespace App\Models\V5\log;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgAsigl1_Log extends Model
{
    protected $table = 'FGASIGL1_LOG';
    protected $primaryKey = 'EMP_ASIGL1, SUB_ASIGL1, REF_ASIGL1, LIN_ASIGL1';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_asigl1' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_asigl1', \Config::get("app.emp"));
        });
    }

}
