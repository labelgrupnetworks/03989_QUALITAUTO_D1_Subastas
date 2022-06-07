<?php

# Ubicacion del modelo
namespace App\Models\V5\log;

//use App\Override\RelationCollection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;



class FgSub_Log extends Model
{
    protected $table = 'FgSub';
    protected $primaryKey = 'emp_sub,cod_sub';

    public $timestamps = false;
    public $incrementing = false;

    //permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];
	public $lang;

    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_sub' => \Config::get("app.emp")
		];

        parent::__construct($vars);
	}

	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {

            $builder->where('emp_sub', \Config::get("app.emp"));
        });
	}

}

