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

	public function scopeleftJoinCli($query){
        return $query->join("FGLICIT", "EMP_LICIT = EMP_ORLIC AND SUB_LICIT = SUB_ORLIC AND COD_LICIT = LICIT_ORLIC ")
                     ->leftjoin("FXCLI", "GEMP_CLI = '". \Config::get("app.gemp") ."' AND COD_CLI = CLI_LICIT");

    }

	public function scopelog($query){
        return $query->joinUsr()->LeftJoinCli()->select("FXCLI.NOM_CLI, FXCLI.CIF_CLI,FSUSR.NOM_USR, FGORLIC_LOG.*");
	}

	public function scopeJoinUsr($query){
        return $query->leftjoin("FSUSR","FSUSR.COD_USR = FGORLIC_LOG.USR_UPDATE_ORLIC");
	}



}
