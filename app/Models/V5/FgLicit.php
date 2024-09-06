<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class FgLicit extends Model
{
    protected $table = 'FGLICIT';
    protected $primaryKey = 'EMP_LICIT, SUB_LICIT, COD_LICIT';

    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_licit' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_licit', Config::get("app.emp"));
        });
    }

       #esta funcion espera un objeto y coje los valores que necesita la APi para hacer un update
    public function scopeWhereUpdateApi($query, $item){
        return $query->where('sub_licit', $item["sub_licit"])
                    ->where('cod_licit', $item["cod_licit"]);
    }

    public function scopeJoinCli($query){
        return $query->join("FXCLI", "GEMP_CLI = '".Config::get("app.gemp") . "'  AND COD_CLI = CLI_LICIT");
    }

    static function getLicitsSubIdOrigin($codsub){
        $licits=array();
        $licitsTmp = self::select("cod_licit, cod2_cli, cod_cli")->where("sub_licit",$codsub)->joinCli()->get();
        foreach($licitsTmp as $licit){
            $licits[$licit->cod2_cli]= $licit->toarray();
        }
        return $licits;
    }

	static function getMaxCodLicit($codSub)
	{
		$maxLicit = self::select("cod_licit")
			->where([
				["sub_licit", $codSub],
				["cod_licit", "!=", Config::get("app.dummy_bidder", 9999)],
				["cod_licit", "<", Config::get("app.subalia_min_licit", 100000)]
			])
			->max("cod_licit");

		$licitLog = DB::table("fglicit_log")
			->select("max(cod_licit_new) as cod_licit_new", "max(cod_licit_old) as cod_licit_old")
			->where([
				["emp_licit", Config::get("app.emp")],
				["sub_licit", $codSub],
				["cod_licit_new", "!=", Config::get("app.dummy_bidder", 9999)],
				["cod_licit_new", "<", Config::get("app.subalia_min_licit", 100000)]
			])
			->first();

		//@todo añadir numlicweb_prmsub al max

		$max = max($maxLicit, $licitLog->cod_licit_new, $licitLog->cod_licit_old);

		return $max;
	}

	static function newCodLicit($codSub)
	{
		return self::getMaxCodLicit($codSub) + 1;
	}


}
