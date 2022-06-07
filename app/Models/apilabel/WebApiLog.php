<?php

# Ubicacion del modelo
namespace App\Models\apilabel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WebApiLog extends Model
{
    protected $table = 'WEB_API_LOG';
    protected $primaryKey = 'ID_API_LOG';

    public $timestamps = false;
    public $incrementing = false;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];
    protected $attributes = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_api_log' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }

    #el where emp estará en todas las consultas
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_api_log', \Config::get("app.emp"));
        });
    }

}
