<?php

# Ubicacion del modelo
namespace App\Models\V5\log;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
use phpDocumentor\Reflection\Types\Integer;

class FgOrlic_Log extends Model
{
    protected $table = 'FGORLIC_LOG';
    protected $primaryKey = 'EMP_ORLIC, SUB_ORLIC, REF_ORLIC, LIN_ORLIC';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_orlic' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_orlic', \Config::get("app.emp"));
        });
    }



}
