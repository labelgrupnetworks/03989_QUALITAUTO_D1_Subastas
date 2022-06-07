<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgCsub0 extends Model
{
    protected $table = 'FGCSUB0';
    protected $primaryKey = 'EMP_CSUB0, SUB_CSUB0, REF_CSUB0';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_csub0' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_csub0', Config::get("app.emp"));
        });
	}


	#HACE JOIN CON LA PUJA GANADORA
	public function scopeJoinCsub($query){
		return $query->join("FGCSUB", "EMP_CSUB = EMP_CSUB0  AND APRE_CSUB = APRE_CSUB0 AND NPRE_CSUB = NPRE_CSUB0  ");

	}

	#NECESITA EL JOIN CON CSUB PARA FUBNCIONAR
	public function scopeJoinSub($query){
		return $query->join("FGSUB", "EMP_SUB = EMP_CSUB0  AND COD_SUB = SUB_CSUB ");

	}

	 public function scopeJoinCli($query){
        return $query->join("FXCLI", "GEMP_CLI = '". \Config::get("app.gemp") ."' AND COD_CLI = CLI_CSUB0");

	}

	#NECESITA EL JOIN CON CSUB PARA FUBNCIONAR
    public function scopeJoinAsigl0($query){
        return $query->join("FGASIGL0", "EMP_ASIGL0 = EMP_CSUB0 AND SUB_ASIGL0 = SUB_CSUB AND REF_ASIGL0 = REF_CSUB ");
	}
	#NECESITA EL JOIN DE ASIGL0
	public function scopeJoinHces1($query){
        return $query->join("FGHCES1", "EMP_HCES1 = EMP_CSUB0 AND NUM_HCES1 = NUMHCES_ASIGL0 AND LIN_HCES1 = LINHCES_ASIGL0 ");
	}

	#NECESITA EL JOIN DE HCES1
	public function scopeJoinAlm($query){
        return $query->leftjoin("FXALM", "EMP_ALM = EMP_CSUB0 AND COD_ALM = ALM_HCES1 ");
    }

}

