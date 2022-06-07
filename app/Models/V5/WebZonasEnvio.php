<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
class WebZonasEnvio extends Model
{
    protected $table = 'web_zonas_envio';
    protected $primaryKey = 'emp_zenvio, id_zenvio, cp_zenvio';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_zenvio' => Config::get('app.main_emp')
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_zenvio', Config::get('app.main_emp'));
        });
	}


}

