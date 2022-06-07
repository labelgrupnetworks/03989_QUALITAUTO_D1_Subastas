<?php

# Ubicacion del modelo
namespace App\Models\V5\log;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;

class FgAsigl0_Log extends Model
{
    protected $table = 'FGASIGL0_LOG';
    protected $primaryKey = 'EMP_ASIGL0,SUB_ASIGL0, REF_ASIGL0';

    public $timestamps = false;
    public $incrementing = false;

    //public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

  #definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = []){
		$this->attributes=[
			'emp_asigl0' => \Config::get("app.emp")
		];

		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function(Builder $builder) {
			$builder->where('emp_asigl0', \Config::get("app.emp"));
		});
	}

}

