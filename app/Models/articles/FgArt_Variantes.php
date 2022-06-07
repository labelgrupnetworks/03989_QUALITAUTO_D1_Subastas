<?php

# Ubicacion del modelo
namespace App\Models\articles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
class FgArt_Variantes extends Model
{
    protected $table = 'FGART_VARIANTES';
    protected $primaryKey = 'ID_VARIANTE';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'EMP_VARIANTE' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('EMP_VARIANTE', Config::get("app.emp"));
        });
	}








}

