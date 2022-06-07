<?php

# Ubicacion del modelo
namespace App\Models\V5\log;

//use App\Override\RelationCollection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;



class FgSub_Log extends Model
{
    protected $table = 'FGSUB_LOG';
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

	public function scopelog($query){
        return $query->joinUsr()->select("FSUSR.NOM_USR, FGSUB_LOG.*");
	}

	public function scopeJoinUsr($query){
        return $query->leftjoin("FSUSR","FSUSR.COD_USR = FGSUB_LOG.USR_UPDATE_SUB");
	}

}

