<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FgFamart extends Model
{
    protected $table = 'FGFAMART';
    protected $primaryKey = 'EMP_FAMART, ID_FAMART, COD_FAMART, PADRE_FAMART';
    public $timestamps = false;
    public $incrementing = false;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];
    protected $attributes = [];
	public $lang;
    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'EMP_FAMART' => \Config::get("app.emp")
		];

        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('EMP_FAMART', \Config::get("app.emp"));
        });
    }

}
