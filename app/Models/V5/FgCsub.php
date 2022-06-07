<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgCsub extends Model
{
    protected $table = 'FGCSUB';
    protected $primaryKey = 'EMP_CSUB, SUB_CSUB, REF_CSUB';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_csub' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_csub', \Config::get("app.emp"));
        });
	}

	  #esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
	public function scopeWhereUpdateApi($query, $item){
        return $query->where('sub_csub', $item["sub_csub"])
                     ->where('ref_csub', $item["ref_csub"]);
    }


	 #devulve las adjudicaciones de una subasta organizadas por referencia
	 static function arrayByRef($idAuction){
        $lotsTmp =self::select(" ref_csub, licit_csub")
                        ->where("sub_csub", $idAuction)->get();
        $lots = array();
        foreach($lotsTmp as $lot) {
            if(empty($lots[$lot->ref_csub])){
                $lots[$lot->ref_csub] = array();
            }
            $lots[$lot->ref_csub]= $lot;
        }

        return $lots;

     }


	 public function scopeJoinCli($query){
        return $query->join("FXCLI", "GEMP_CLI = '". \Config::get("app.gemp") ."' AND COD_CLI = CLIFAC_CSUB");

	}
	public function scopeLeftJoinCli($query){
        return $query->leftjoin("FXCLI", "GEMP_CLI = '". \Config::get("app.gemp") ."' AND COD_CLI = CLIFAC_CSUB");

	}
	#HACE JOIN CON LA PUJA GANADORA
	public function scopeJoinWinnerBid($query){
        return $query->join("FGASIGL1", "EMP_ASIGL1 = EMP_CSUB  AND SUB_ASIGL1 = SUB_CSUB AND REF_ASIGL1 = REF_CSUB  AND LICIT_ASIGL1 = LICIT_CSUB AND IMP_ASIGL1 = HIMP_CSUB ");

    }

    public function scopeJoinAsigl0($query){
        return $query->join("FGASIGL0", "EMP_ASIGL0 = EMP_CSUB AND SUB_ASIGL0 = SUB_CSUB AND REF_ASIGL0 = REF_CSUB ");
	}

	public function scopeJoinFgLicit($query){
		return $query->join("FGLICIT", "EMP_LICIT = EMP_CSUB AND SUB_LICIT = SUB_CSUB AND COD_LICIT = LICIT_CSUB ");
	}

	//no funcionara sino se hace el antes el join con Asigl0
	public function scopeJoinFghces1($query){
        return $query->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
    }

	public function scopeLeftJoinFgCusb0($query)
	{
		return $query->leftJoin('FGCSUB0', 'FGCSUB0.EMP_CSUB0 = FGCSUB.EMP_CSUB AND FGCSUB0.APRE_CSUB0 = FGCSUB.APRE_CSUB AND FGCSUB0.NPRE_CSUB0 = FGCSUB.NPRE_CSUB');
	}

	static function getAdjudicacionesPendientesCount($cod_cli, $auctions = null)
	{
		return self::leftJoinFgCusb0()
		->when($auctions, function($query, $auctions){
			return $query->whereIn('SUB_CSUB', $auctions);
		})
		->where('CLIFAC_CSUB', $cod_cli)
		->where(function ($query) {
			return $query->orWhereNull('estado_csub0')
				->orWhere('ESTADO_CSUB0', 'N');
		})
		->count();
	}

}

