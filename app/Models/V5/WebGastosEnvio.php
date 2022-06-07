<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
class WebGastosEnvio extends Model
{
    protected $table = 'web_gastos_envio';
    protected $primaryKey = 'emp_genvio, id_genvio';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_genvio' => Config::get('app.main_emp')
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_genvio', Config::get('app.main_emp'));
        });
	}


}

